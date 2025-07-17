use Illuminate\Support\Facades\Storage;
<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationPackage;
use App\Models\Fundraiser;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['user', 'donationPackage', 'fundraiser']);

        // Admin can see all donations, users only see their own
        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('title', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Date range filter
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json($donations);
    }

    public function show(Request $request, $id)
    {
        $donation = Donation::with(['user', 'donationPackage', 'fundraiser'])->findOrFail($id);

        // Users can only see their own donations, admin can see all
        if (!$request->user()->isAdmin() && $donation->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Donation not found'], 404);
        }

        return response()->json($donation);
    }

    public function store(Request $request)
    {

        $request->validate([
            'donation_package_id' => 'nullable|exists:donation_packages,id',
            'fundraiser_id' => 'nullable|exists:fundraisers,id',
            'title' => 'required_without_all:donation_package_id,fundraiser_id|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:255',
            'amount' => 'required|integer|min:1000', // Minimum 1000
            'proof_image' => 'nullable|file|image|max:2048',
        ]);

        // Determine title if not provided
        $title = $request->title;
        if (!$title) {
            if ($request->donation_package_id) {
                $package = DonationPackage::find($request->donation_package_id);
                $title = "Donasi untuk: " . $package->title;
            } elseif ($request->fundraiser_id) {
                $fundraiser = Fundraiser::find($request->fundraiser_id);
                $title = "Donasi untuk: " . $fundraiser->title;
            }
        }

        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $proofImagePath = $request->file('proof_image')->store('donation_proofs', 'public');
        }

        $donation = Donation::create([
            'user_id' => $request->user() ? $request->user()->id : null,
            'donation_package_id' => $request->donation_package_id,
            'fundraiser_id' => $request->fundraiser_id,
            'title' => $title,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category' => $request->category,
            'amount' => $request->amount,
            'status' => 'pending',
            'proof_image' => $proofImagePath,
        ]);

        return response()->json([
            'message' => 'Donation created successfully. Please wait for admin verification.',
            'donation' => $donation->load(['donationPackage', 'fundraiser']),
            'payment_instructions' => [
                'bank_account' => 'BCA 1234567890 a.n. Yayasan Daarul Ummahaat',
                'amount' => $donation->amount,
                'note' => 'Please include your name and phone number in the transfer description'
            ]
        ], 201);
    }

    public function createManual(Request $request)
    {
        // Only admin can create manual donations (for offline donations)
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can create manual donations'], 403);
        }

        $request->validate([
            'donation_package_id' => 'nullable|exists:donation_packages,id',
            'fundraiser_id' => 'nullable|exists:fundraisers,id',
            'title' => 'required_without_all:donation_package_id,fundraiser_id|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:255',
            'amount' => 'required|integer|min:1000',
            'status' => 'in:pending,confirmed',
            'confirmation_note' => 'nullable|string',
        ]);

        // Determine title if not provided
        $title = $request->title;
        if (!$title) {
            if ($request->donation_package_id) {
                $package = DonationPackage::find($request->donation_package_id);
                $title = "Donasi untuk: " . $package->title;
            } elseif ($request->fundraiser_id) {
                $fundraiser = Fundraiser::find($request->fundraiser_id);
                $title = "Donasi untuk: " . $fundraiser->title;
            }
        }

        $donation = Donation::create([
            'user_id' => null, // Manual donations are not linked to users
            'donation_package_id' => $request->donation_package_id,
            'fundraiser_id' => $request->fundraiser_id,
            'title' => $title,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category' => $request->category,
            'amount' => $request->amount,
            'status' => $request->status ?? 'confirmed',
            'confirmation_note' => $request->confirmation_note,
            'confirmed_at' => $request->status === 'confirmed' ? now() : null,
        ]);

        // Update fundraiser progress if confirmed and linked to fundraiser
        if ($donation->status === 'confirmed' && $donation->fundraiser_id) {
            $this->updateFundraiserProgress($donation->fundraiser_id);
        }

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'create_manual_donation',
            'donations',
            $donation->id,
            "Created manual donation: {$donation->name} - Rp " . number_format($donation->amount)
        );

        return response()->json($donation->load(['donationPackage', 'fundraiser']), 201);
    }

    public function confirmDonation(Request $request, Donation $donation)
    {
        // Only admin can confirm donations
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can confirm donations'], 403);
        }

        $request->validate([
            'confirmation_note' => 'nullable|string',
        ]);

        $donation->update([
            'status' => 'confirmed',
            'confirmation_note' => $request->confirmation_note,
            'confirmed_at' => now(),
        ]);

        // Update fundraiser progress if linked to fundraiser
        if ($donation->fundraiser_id) {
            $this->updateFundraiserProgress($donation->fundraiser_id);
        }

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'confirm_donation',
            'donations',
            $donation->id,
            "Confirmed donation: {$donation->name} - Rp " . number_format($donation->amount)
        );

        return response()->json([
            'message' => 'Donation confirmed successfully',
            'donation' => $donation->load(['donationPackage', 'fundraiser'])
        ]);
    }

    public function cancelDonation(Request $request, Donation $donation)
    {
        // Only admin can cancel donations
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can cancel donations'], 403);
        }

        $request->validate([
            'confirmation_note' => 'required|string',
        ]);

        $donation->update([
            'status' => 'cancelled',
            'confirmation_note' => $request->confirmation_note,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'cancel_donation',
            'donations',
            $donation->id,
            "Cancelled donation: {$donation->name} - Reason: {$request->confirmation_note}"
        );

        return response()->json([
            'message' => 'Donation cancelled successfully',
            'donation' => $donation->load(['donationPackage', 'fundraiser'])
        ]);
    }

    public function userDonations(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $donations = Donation::where('user_id', $request->user()->id)
            ->with(['donationPackage', 'fundraiser'])
            ->latest()
            ->paginate($request->per_page ?? 15);

        $stats = [
            'total_donations' => Donation::where('user_id', $request->user()->id)->count(),
            'total_amount' => Donation::where('user_id', $request->user()->id)
                ->where('status', 'confirmed')
                ->sum('amount'),
            'pending_donations' => Donation::where('user_id', $request->user()->id)
                ->where('status', 'pending')
                ->count(),
        ];

        return response()->json([
            'donations' => $donations,
            'stats' => $stats
        ]);
    }

    public function statistics(Request $request)
    {
        // Only admin can view overall statistics
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can view statistics'], 403);
        }

        $stats = [
            'total_donations' => Donation::count(),
            'confirmed_donations' => Donation::where('status', 'confirmed')->count(),
            'pending_donations' => Donation::where('status', 'pending')->count(),
            'total_amount' => Donation::where('status', 'confirmed')->sum('amount'),
            'monthly_amount' => Donation::where('status', 'confirmed')
                ->whereMonth('confirmed_at', now()->month)
                ->whereYear('confirmed_at', now()->year)
                ->sum('amount'),
            'daily_amount' => Donation::where('status', 'confirmed')
                ->whereDate('confirmed_at', now()->toDateString())
                ->sum('amount'),
        ];

        return response()->json($stats);
    }

    private function updateFundraiserProgress($fundraiserId)
    {
        $fundraiser = Fundraiser::find($fundraiserId);
        if ($fundraiser) {
            $totalConfirmed = Donation::where('fundraiser_id', $fundraiserId)
                ->where('status', 'confirmed')
                ->sum('amount');

            $fundraiser->update(['current_amount' => $totalConfirmed]);
        }
    }

    private function logAdminAction($user, $action, $targetTable, $targetId, $note)
    {
        AdminLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'target_table' => $targetTable,
            'target_id' => $targetId,
            'note' => $note,
        ]);
    }
}
