<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donation;
use App\Models\Fundraiser;
use App\Models\Program;
use App\Models\Activity;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'total_donations' => Donation::count(),
            'total_fundraisers' => Fundraiser::count(),
            'total_programs' => Program::count(),
            'total_activities' => Activity::count(),
            'total_donation_amount' => Donation::where('status', 'confirmed')->sum('amount'),
            'pending_donations' => Donation::where('status', 'pending')->count(),
        ];

        // Recent activities
        $recent_donations = Donation::with(['user', 'fundraiser', 'donationPackage'])
            ->latest()
            ->take(10)
            ->get();

        // Log admin action
        $this->logAdminAction($request->user(), 'view', 'dashboard', null, 'Viewed admin dashboard');

        return response()->json([
            'stats' => $stats,
            'recent_donations' => $recent_donations,
        ]);
    }

    public function users(Request $request)
    {
        $users = User::with('role')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                return $query->whereHas('role', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 15);

        // Log admin action
        $this->logAdminAction($request->user(), 'view', 'users', null, 'Viewed users list');

        return response()->json($users);
    }

    public function toggleUserStatus(Request $request, User $user)
    {
        // Prevent admin from disabling themselves
        if ($user->id === $request->user()->id) {
            return response()->json(['error' => 'Cannot modify your own account status'], 403);
        }

        // Toggle soft delete
        if ($user->trashed()) {
            $user->restore();
            $action = 'activated';
        } else {
            $user->delete();
            $action = 'deactivated';
        }

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            $action,
            'users',
            $user->id,
            "User {$user->name} ({$user->email}) was {$action}"
        );

        return response()->json([
            'message' => "User {$action} successfully",
            'user' => $user->load('role')
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
