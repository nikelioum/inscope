<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class CompanyController extends Controller
{
    // Create a new company
    public function store(Request $request)
    {

        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    // View all companies or only those the user belongs to
    public function index(Request $request)
    {
        if (PermissionHelper::isAdmin($request->user())) {
            $companies = Company::all(); // Admin can see all companies
        } else {
            $user = $request->user();
            $companies = $user->companies; // Regular users see only their companies
        }

        return response()->json($companies, 200);
    }

    // View a single company
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $company = Company::with('users')->findOrFail($id);

        if (!$company->users->contains($user)) {
            if (!PermissionHelper::isAdmin($user)) {
                return response()->json(['message' => 'Access denied'], 403);
            }
        }

        return response()->json($company, 200);
    }

    // Edit a company (full update)
    public function update(Request $request, $id)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $company->update($validated);

        return response()->json($company, 200);
    }

    // Partial update a company
    public function partialUpdate(Request $request, $id)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
        ]);

        $company->update($validated);

        return response()->json($company, 200);
    }

    // Delete a company
    public function destroy(Request $request, $id)
    {
        if (!PermissionHelper::isAdmin($request->user())) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(null, 204);
    }
}
