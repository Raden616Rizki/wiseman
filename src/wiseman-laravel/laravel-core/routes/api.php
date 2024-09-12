<?php

use App\Http\Controllers\Api\ArchiveController;
use App\Http\Controllers\Api\VotingController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\GroupUserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api;" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

	Route::get('/archives', [ArchiveController::class, 'index']);
	Route::get('/archives/{id}', [ArchiveController::class, 'show']);
	Route::post('/archives', [ArchiveController::class, 'store']);
	Route::put('/archives/{id}', [ArchiveController::class, 'update']);
	Route::delete('/archives/{id}', [ArchiveController::class, 'destroy']);


	Route::get('/votings', [VotingController::class, 'index']);
	Route::get('/votings/{id}', [VotingController::class, 'show']);
	Route::post('/votings', [VotingController::class, 'store']);
	Route::put('/votings/{id}', [VotingController::class, 'update']);
	Route::delete('/votings/{id}', [VotingController::class, 'destroy']);

	Route::get('/activities', [ActivityController::class, 'index']);
	Route::get('/activities/{id}', [ActivityController::class, 'show']);
	Route::post('/activities', [ActivityController::class, 'store']);
	Route::put('/activities/{id}', [ActivityController::class, 'update']);
	Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);

	Route::get('/group_users', [GroupUserController::class, 'index']);
	Route::get('/group_users/{id}', [GroupUserController::class, 'show']);
	Route::post('/group_users', [GroupUserController::class, 'store']);
	Route::put('/group_users/{id}', [GroupUserController::class, 'update']);
	Route::delete('/group_users/{id}', [GroupUserController::class, 'destroy']);

	Route::get('/groups', [GroupController::class, 'index']);
	Route::get('/groups/{id}', [GroupController::class, 'show']);
	Route::post('/groups', [GroupController::class, 'store']);
	Route::put('/groups/{id}', [GroupController::class, 'update']);
	Route::delete('/groups/{id}', [GroupController::class, 'destroy']);

	Route::get('/users', [UserController::class, 'index']);
	Route::get('/users/{id}', [UserController::class, 'show']);
	Route::post('/users', [UserController::class, 'store']);
	Route::put('/users/{id}', [UserController::class, 'update']);
	Route::delete('/users/{id}', [UserController::class, 'destroy']);

	Route::get('/groups', [GroupController::class, 'index']);
	Route::get('/groups/{id}', [GroupController::class, 'show']);
	Route::post('/groups', [GroupController::class, 'store']);
	Route::put('/groups/{id}', [GroupController::class, 'update']);
	Route::delete('/groups/{id}', [GroupController::class, 'destroy']);

    Route::get('/', [SiteController::class, 'index']);

    Route::post('/auth/login', [AuthController::class, 'login']); //->middleware(['signature']);
    Route::post('/auth/logout', [AuthController::class, 'logout']); //->middleware(['signature']);
    Route::get('/auth/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);

    Route::get('/users', [UserController::class, 'index']); //->middleware(['auth.api', 'role:user.view']);
    Route::get('/users/{id}', [UserController::class, 'show']); //->middleware(['auth.api', 'role:user.view']);
    Route::post('/users', [UserController::class, 'store']); //->middleware(['auth.api', 'role:user.create|roles.view']);
    Route::put('/users/{id}', [UserController::class, 'update']); //->middleware(['auth.api', 'role:user.update||roles.view']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']); //->middleware(['auth.api', 'role:user.delete']);

    Route::get('/roles', [RoleController::class, 'index']); //->middleware(['auth.api', 'role:roles.view']);
    Route::get('/roles/{id}', [RoleController::class, 'show']); //->middleware(['auth.api', 'role:roles.view']);
    Route::post('/roles', [RoleController::class, 'store']); //->middleware(['auth.api', 'role:roles.create']);
    Route::put('/roles', [RoleController::class, 'update']); //->middleware(['auth.api', 'role:roles.update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']); //->middleware(['auth.api', 'role:roles.delete']);
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
