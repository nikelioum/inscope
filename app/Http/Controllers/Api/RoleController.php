<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }
        return Role::all();
    }

    public function show(Request $request, $id)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }
        return Role::findOrFail($id);
    }

    public function store(Request $request)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
        ]);

        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }


        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->only(['name']));

        return response()->json($role, 200);
    }

    public function patch(Request $request, $id)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $role = Role::findOrFail($id);
        $role->update($request->only(['name']));

        return response()->json($role, 200);
    }

    public function destroy(Request $request, $id)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(null, 204);
    }
}
