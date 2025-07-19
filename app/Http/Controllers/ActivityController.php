<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('creator');

        // Public access - only show published activities
        if (!$request->user() || (!$request->user()->isAdmin() && !$request->user()->hasRole('editor'))) {
            $query->where('is_published', true);
        }

        // For editors, only show their own activities unless published
        if ($request->user() && $request->user()->hasRole('editor')) {
            $query->where(function ($q) use ($request) {
                $q->where('is_published', true)
                    ->orWhere('created_by', $request->user()->id);
            });
        }

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by date range
        if ($request->date_from) {
            $query->where('event_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('event_date', '<=', $request->date_to);
        }

        $activities = $query->orderBy('event_date', 'desc')->paginate($request->per_page ?? 15);

        return response()->json($activities);
    }

    public function show(Request $request, $id)
    {
        $activity = Activity::with('creator')->findOrFail($id);

        // Public access - only show published activities
        if (!$request->user() || (!$request->user()->isAdmin() && !$request->user()->hasRole('editor'))) {
            if (!$activity->is_published) {
                return response()->json(['error' => 'Activity not found'], 404);
            }
        }

        // For editors, only show if they own it or it's published
        if ($request->user() && $request->user()->hasRole('editor')) {
            if (!$activity->is_published && $activity->created_by !== $request->user()->id) {
                return response()->json(['error' => 'Activity not found'], 404);
            }
        }

        return response()->json($activity);
    }

    public function store(Request $request)
    {
        // Check if user is authenticated first
        if (!$request->user()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        // Admin or editor can create activities
        if (!$request->user()->isAdmin() && !$request->user()->hasRole('editor')) {
            return response()->json(['error' => 'Only admin or editor can create activities'], 403);
        }


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'required|file|image|max:2048',
            'is_published' => 'boolean',
        ]);

        // Store image
        $imagePath = $request->file('image')->store('activities', 'public');

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'image' => $imagePath,
            'created_by' => $request->user()->id,
            'is_published' => $request->is_published ?? false,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'create',
            'activities',
            $activity->id,
            "Created activity: {$activity->title}"
        );

        return response()->json($activity->load('creator'), 201);
    }

    public function update(Request $request, Activity $activity)
    {
        // Check if user is authenticated first
        if (!$request->user()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        // Admin can update any, editor can only update their own
        if (
            !$request->user()->isAdmin() &&
            (!$request->user()->hasRole('editor') || $activity->created_by !== $request->user()->id)
        ) {
            return response()->json(['error' => 'Unauthorized to update this activity'], 403);
        }


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'nullable|file|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'is_published' => $request->is_published ?? $activity->is_published,
        ];

        // Handle image update
        if ($request->hasFile('image')) {
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $updateData['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($updateData);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'update',
            'activities',
            $activity->id,
            "Updated activity: {$activity->title}"
        );

        return response()->json($activity->load('creator'));
    }

    public function destroy(Request $request, Activity $activity)
    {
        // Check if user is authenticated first
        if (!$request->user()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        // Admin can delete any, editor can only delete their own
        if (
            !$request->user()->isAdmin() &&
            (!$request->user()->hasRole('editor') || $activity->created_by !== $request->user()->id)
        ) {
            return response()->json(['error' => 'Unauthorized to delete this activity'], 403);
        }


        // Delete image from storage
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }

        $activityTitle = $activity->title;
        $activity->delete();

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'delete',
            'activities',
            $activity->id,
            "Deleted activity: {$activityTitle}"
        );

        return response()->json(['message' => 'Activity deleted successfully']);
    }

    public function upcoming(Request $request)
    {
        $query = Activity::where('event_date', '>=', now())
            ->where('is_published', true)
            ->with('creator');

        $activities = $query->orderBy('event_date', 'asc')
            ->take($request->limit ?? 5)
            ->get();

        return response()->json($activities);
    }

    public function past(Request $request)
    {
        $query = Activity::where('event_date', '<', now())
            ->where('is_published', true)
            ->with('creator');

        $activities = $query->orderBy('event_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($activities);
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
