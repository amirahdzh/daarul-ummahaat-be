<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with('creator');

        // Public access - only show published programs
        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('is_published', true);
        }

        // Search functionality
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $programs = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json($programs);
    }

    public function show(Request $request, $id)
    {
        $program = Program::with('creator')->findOrFail($id);

        // Public access - only show published programs
        if (!$request->user() || !$request->user()->isAdmin()) {
            if (!$program->is_published) {
                return response()->json(['error' => 'Program not found'], 404);
            }
        }

        return response()->json($program);
    }

    public function store(Request $request)
    {
        // Only admin can create programs (scenario requirement)
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can create programs'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
            'external_link' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $program = Program::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'image' => $request->image,
            'external_link' => $request->external_link,
            'is_published' => $request->is_published ?? false,
            'created_by' => $request->user()->id,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'create',
            'programs',
            $program->id,
            "Created program: {$program->title}"
        );

        return response()->json($program->load('creator'), 201);
    }

    public function update(Request $request, Program $program)
    {
        // Only admin can update programs
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can update programs'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
            'external_link' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $oldData = $program->toArray();

        $program->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'image' => $request->image,
            'external_link' => $request->external_link,
            'is_published' => $request->is_published ?? $program->is_published,
        ]);

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'update',
            'programs',
            $program->id,
            "Updated program: {$program->title}"
        );

        return response()->json($program->load('creator'));
    }

    public function destroy(Request $request, Program $program)
    {
        // Only admin can delete programs
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Only admin can delete programs'], 403);
        }

        $programTitle = $program->title;
        $program->delete();

        // Log admin action
        $this->logAdminAction(
            $request->user(),
            'delete',
            'programs',
            $program->id,
            "Deleted program: {$programTitle}"
        );

        return response()->json(['message' => 'Program deleted successfully']);
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
