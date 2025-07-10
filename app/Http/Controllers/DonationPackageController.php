<?php

namespace App\Http\Controllers;

use App\Models\DonationPackage;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class DonationPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = DonationPackage::query();

        // Public access - only show active packages
        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('is_active', true);
        }

        // Search and filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('category', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $packages = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json($packages);
    }

    public function show(Request $request, $id)
    {
        $package = DonationPackage::with('donations')->findOrFail($id);

        // Public access - only show active packages
        if (!$request->user() || !$request->user()->isAdmin()) {
            if (!$package->is_active) {
                return response()->json(['error' => 'Package not found'], 404);
            }
        }

        return response()->json($package);
    }

    public function store(Request $request)
    {
        // Only admin can create packages
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can create donation packages'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $package = DonationPackage::create([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'is_active' => $request->is_active ?? true,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'create',
            'donation_packages',
            $package->id,
            "Created donation package: {$package->title}"
        );

        return response()->json($package, 201);
    }

    public function update(Request $request, DonationPackage $donationPackage)
    {
        // Only admin can update packages
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can update donation packages'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $donationPackage->update([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'is_active' => $request->is_active ?? $donationPackage->is_active,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'update',
            'donation_packages',
            $donationPackage->id,
            "Updated donation package: {$donationPackage->title}"
        );

        return response()->json($donationPackage);
    }

    public function destroy(Request $request, DonationPackage $donationPackage)
    {
        // Only admin can delete packages
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can delete donation packages'], 403);
        }

        $packageTitle = $donationPackage->title;
        $donationPackage->delete();

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'delete',
            'donation_packages',
            $donationPackage->id,
            "Deleted donation package: {$packageTitle}"
        );

        return response()->json(['message' => 'Donation package deleted successfully']);
    }

    public function toggleStatus(Request $request, DonationPackage $donationPackage)
    {
        // Only admin can toggle status
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can toggle package status'], 403);
        }

        $donationPackage->update([
            'is_active' => !$donationPackage->is_active
        ]);

        $status = $donationPackage->is_active ? 'activated' : 'deactivated';

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            $status,
            'donation_packages',
            $donationPackage->id,
            "Package {$donationPackage->title} was {$status}"
        );

        return response()->json([
            'message' => "Package {$status} successfully",
            'package' => $donationPackage
        ]);
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
