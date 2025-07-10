<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FundraiserController extends Controller
{
    public function index(Request $request)
    {
        $query = Fundraiser::with('creator', 'donations');

        // Public access - only show published fundraisers
        if (!$request->user() || (!$request->user()->isAdmin() && !$request->user()->hasRole('editor'))) {
            $query->where('is_published', true);
        }

        // For editors, only show their own fundraisers unless published
        if ($request->user() && $request->user()->hasRole('editor')) {
            $query->where(function($q) use ($request) {
                $q->where('is_published', true)
                  ->orWhere('created_by', $request->user()->id);
            });
        }

        // Search functionality
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $fundraisers = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json($fundraisers);
    }

    public function show(Request $request, $id)
    {
        $fundraiser = Fundraiser::with(['creator', 'donations' => function($query) {
            $query->where('status', 'confirmed');
        }])->findOrFail($id);

        // Public access - only show published fundraisers
        if (!$request->user() || (!$request->user()->isAdmin() && !$request->user()->hasRole('editor'))) {
            if (!$fundraiser->is_published) {
                return response()->json(['error' => 'Fundraiser not found'], 404);
            }
        }

        // For editors, only show if they own it or it's published
        if ($request->user() && $request->user()->hasRole('editor')) {
            if (!$fundraiser->is_published && $fundraiser->created_by !== $request->user()->id) {
                return response()->json(['error' => 'Fundraiser not found'], 404);
            }
        }

        return response()->json($fundraiser);
    }

    public function store(Request $request)
    {
        // Admin or editor can create fundraisers
        if (!$request->user()->isAdmin() && !$request->user()->hasRole('editor')) {
            return response()->json(['error' => 'Only admin or editor can create fundraisers'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|integer|min:1',
            'deadline' => 'required|date|after:today',
            'image' => 'required|string',
            'status' => 'in:active,closed,archived',
            'is_published' => 'boolean',
        ]);

        $fundraiser = Fundraiser::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . time()),
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'current_amount' => 0,
            'deadline' => $request->deadline,
            'image' => $request->image,
            'status' => $request->status ?? 'active',
            'created_by' => $request->user()->id,
            'is_published' => $request->is_published ?? false,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'create',
            'fundraisers',
            $fundraiser->id,
            "Created fundraiser: {$fundraiser->title}"
        );

        return response()->json($fundraiser->load('creator'), 201);
    }

    public function update(Request $request, Fundraiser $fundraiser)
    {
        // Admin can update any, editor can only update their own
        if (!$request->user()->isAdmin() && 
            (!$request->user()->hasRole('editor') || $fundraiser->created_by !== $request->user()->id)) {
            return response()->json(['error' => 'Unauthorized to update this fundraiser'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|integer|min:1',
            'current_amount' => 'integer|min:0',
            'deadline' => 'required|date',
            'image' => 'required|string',
            'status' => 'in:active,closed,archived',
            'is_published' => 'boolean',
        ]);

        $fundraiser->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . $fundraiser->id),
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? $fundraiser->current_amount,
            'deadline' => $request->deadline,
            'image' => $request->image,
            'status' => $request->status ?? $fundraiser->status,
            'is_published' => $request->is_published ?? $fundraiser->is_published,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'update',
            'fundraisers',
            $fundraiser->id,
            "Updated fundraiser: {$fundraiser->title}"
        );

        return response()->json($fundraiser->load('creator'));
    }

    public function destroy(Request $request, Fundraiser $fundraiser)
    {
        // Admin can delete any, editor can only delete their own
        if (!$request->user()->isAdmin() && 
            (!$request->user()->hasRole('editor') || $fundraiser->created_by !== $request->user()->id)) {
            return response()->json(['error' => 'Unauthorized to delete this fundraiser'], 403);
        }

        $fundraiserTitle = $fundraiser->title;
        $fundraiser->delete();

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'delete',
            'fundraisers',
            $fundraiser->id,
            "Deleted fundraiser: {$fundraiserTitle}"
        );

        return response()->json(['message' => 'Fundraiser deleted successfully']);
    }

    public function updateProgress(Request $request, Fundraiser $fundraiser)
    {
        // Only admin can manually update progress (for offline donations)
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can update fundraiser progress'], 403);
        }

        $request->validate([
            'current_amount' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $oldAmount = $fundraiser->current_amount;
        $fundraiser->update([
            'current_amount' => $request->current_amount
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'update_progress',
            'fundraisers',
            $fundraiser->id,
            "Updated progress from {$oldAmount} to {$request->current_amount}. Note: " . ($request->note ?? 'N/A')
        );

        return response()->json([
            'message' => 'Fundraiser progress updated successfully',
            'fundraiser' => $fundraiser
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
