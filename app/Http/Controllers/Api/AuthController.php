<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;
use App\Helpers\PermissionHelper;

class AuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role_id' => 'required|exists:roles,id',
            'company_ids' => 'required|array',
            'company_ids.*' => 'exists:companies,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        $user->companies()->attach($request->company_ids);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
        ], 201);
    }

    // User login
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('token_name')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    // Get authenticated user
    public function me(Request $request)
    {
        return new UserResource($request->user()->load('companies', 'role'));
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Get all users
    public function index(Request $request)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $users = User::with('companies', 'role')->get();
        return UserResource::collection($users);
    }

    // Full update of a user (PUT)
    public function update(Request $request, $id)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|confirmed|min:8',
            'role_id' => 'required|exists:roles,id',
            'company_ids' => 'required|array',
            'company_ids.*' => 'exists:companies,id',
        ]);

        // Hash the password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Update the user and sync companies
        $user->update($validated);
        $user->companies()->sync($validated['company_ids']);

        return response()->json(new UserResource($user), 200);
    }

    // Partial update of a user (PATCH)
    public function partialUpdate(Request $request, $id)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|confirmed|min:8',
            'role_id' => 'sometimes|exists:roles,id',
            'company_ids' => 'sometimes|array',
            'company_ids.*' => 'exists:companies,id',
        ]);

        // Hash the password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Update the user attributes
        $user->update($validated);

        // Sync companies if company_ids are provided
        if (isset($validated['company_ids'])) {
            $user->companies()->sync($validated['company_ids']);
        }

        return response()->json(new UserResource($user), 200);
    }

    // Delete a user
    public function destroy(Request $request, $id)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
