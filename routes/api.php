<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\DonationPackageController;
use App\Http\Controllers\FundraiserController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DonationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public content routes (no authentication required)
Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/programs/{id}', [ProgramController::class, 'show']);
Route::get('/donation-packages', [DonationPackageController::class, 'index']);
Route::get('/donation-packages/{id}', [DonationPackageController::class, 'show']);
Route::get('/fundraisers', [FundraiserController::class, 'index']);
Route::get('/fundraisers/{id}', [FundraiserController::class, 'show']);
Route::get('/activities', [ActivityController::class, 'index']);
// register static routes before the parameterized route to avoid conflicts (e.g. 'past' being treated as an {id})
Route::get('/activities/upcoming', [ActivityController::class, 'upcoming']);
Route::get('/activities/past', [ActivityController::class, 'past']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);

// Public donation creation (for anonymous donors)
Route::post('/donations', [DonationController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // User routes
    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::get('/donations', [DonationController::class, 'userDonations']);
    });

    // Donation routes (authenticated users)
    Route::get('/donations', [DonationController::class, 'index']);
    Route::get('/donations/{id}', [DonationController::class, 'show']);

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);

        // Admin-only CRUD for Programs
        Route::get('/programs', [ProgramController::class, 'index']); // Admin can see all programs
        Route::post('/programs', [ProgramController::class, 'store']);
        Route::put('/programs/{program}', [ProgramController::class, 'update']);
        Route::delete('/programs/{program}', [ProgramController::class, 'destroy']);

        // Admin-only CRUD for Donation Packages
        Route::get('/donation-packages', [DonationPackageController::class, 'index']); // Admin can see all packages
        Route::post('/donation-packages', [DonationPackageController::class, 'store']);
        Route::put('/donation-packages/{donationPackage}', [DonationPackageController::class, 'update']);
        Route::delete('/donation-packages/{donationPackage}', [DonationPackageController::class, 'destroy']);
        Route::post('/donation-packages/{donationPackage}/toggle-status', [DonationPackageController::class, 'toggleStatus']);

        // Admin donation management
        Route::post('/donations/manual', [DonationController::class, 'createManual']);
        Route::post('/donations/{donation}/confirm', [DonationController::class, 'confirmDonation']);
        Route::post('/donations/{donation}/cancel', [DonationController::class, 'cancelDonation']);
        Route::get('/donations/statistics', [DonationController::class, 'statistics']);

        // Admin fundraiser progress update
        Route::post('/fundraisers/{fundraiser}/update-progress', [FundraiserController::class, 'updateProgress']);

        // Admin can view all fundraisers and activities
        Route::get('/fundraisers', [FundraiserController::class, 'index']); // Admin can see all fundraisers
        Route::get('/activities', [ActivityController::class, 'index']); // Admin can see all activities
    });

    // Admin & Editor routes for Fundraisers and Activities (content management)
    Route::middleware(['role:admin,editor'])->group(function () {
        Route::post('/fundraisers', [FundraiserController::class, 'store']);
        Route::put('/fundraisers/{fundraiser}', [FundraiserController::class, 'update']);
        Route::delete('/fundraisers/{fundraiser}', [FundraiserController::class, 'destroy']);

        Route::post('/activities', [ActivityController::class, 'store']);
        Route::put('/activities/{activity}', [ActivityController::class, 'update']);
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);
    });
});
