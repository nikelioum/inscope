<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class ProjectController extends Controller
{
    // Get all projects for companies the user belongs to or all projects for admins
    public function index(Request $request)
    {
        if (PermissionHelper::isAdmin($request->user())) {
            $projects = Project::all(); // Admin can see all projects
        } else {
            $user = $request->user();
            $projects = Project::whereIn('company_id', $user->companies()->pluck('id'))->get(); // Regular users see only their projects
        }

        return response()->json($projects, 200);
    }

    // Get a specific project
    public function show(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Check if the user belongs to the company associated with the project or if they're an admin
        if (!$request->user()->companies->contains($project->company_id) && !PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return response()->json($project, 200);
    }

    // Create a new project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => 'required|uuid|exists:companies,id',
            'type' => 'required|string|max:50',
        ]);

        // Ensure user belongs to the company
        if (!$request->user()->companies->contains($request->company_id) && !PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $project = Project::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'type' => $request->type,
        ]);

        return response()->json($project, 201);
    }

    // Update an existing project
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => 'required|uuid|exists:companies,id',
            'type' => 'required|string|max:50',
        ]);

        $project = Project::findOrFail($id);

        // Check if the user belongs to the company associated with the project or if they're an admin
        if (!$request->user()->companies->contains($project->company_id) && !PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $project->update($request->only(['name', 'description', 'company_id', 'type']));

        return response()->json($project, 200);
    }

    // Delete a project
    public function destroy(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Check if the user belongs to the company associated with the project or if they're an admin
        if (!$request->user()->companies->contains($project->company_id) && !PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $project->delete();

        return response()->json(null, 204);
    }
}
