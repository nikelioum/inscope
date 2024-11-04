<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProjectController;


//Login
Route::post('/login', [AuthController::class, 'login']);

//Create new user
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');

//Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Get user loggedin info
Route::get('/user', [AuthController::class, 'me'])->middleware('auth:sanctum');


//Users routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
    Route::patch('/users/{id}', [AuthController::class, 'partialUpdate']);
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
    // Companies routes
    Route::prefix('companies')->group(function () {
        Route::post('/', [CompanyController::class, 'store']); // Create a new company
        Route::get('/', [CompanyController::class, 'index']); // View all companies
        Route::get('/{id}', [CompanyController::class, 'show']); // View a single company
        Route::put('/{id}', [CompanyController::class, 'update']); // Edit a company (full update)
        Route::patch('/{id}', [CompanyController::class, 'partialUpdate']); // Partial update
        Route::delete('/{id}', [CompanyController::class, 'destroy']); // Delete a company
    });

    // Projects routes
    Route::prefix('projects')->group(function () {
        Route::post('/', [ProjectController::class, 'store']); // Create project
        Route::get('/', [ProjectController::class, 'index']); // View all projects
        Route::get('/{id}', [ProjectController::class, 'show']); // View single project
        Route::put('/{id}', [ProjectController::class, 'update']); // Update project
        Route::patch('/{id}', [ProjectController::class, 'patch']); // Partially update project
        Route::delete('/{id}', [ProjectController::class, 'destroy']); // Delete project
    });


    // Roles routes (accessible without authentication)
    Route::prefix('roles')->group(function () {
        Route::post('/', [RoleController::class, 'store']); // Create role
        Route::get('/', [RoleController::class, 'index']); // View all roles
        Route::get('/{id}', [RoleController::class, 'show']); // View single role
        Route::put('/{id}', [RoleController::class, 'update']); // Update role
        Route::patch('/{id}', [RoleController::class, 'patch']); // Partially update role
        Route::delete('/{id}', [RoleController::class, 'destroy']); // Delete role
    });

});

