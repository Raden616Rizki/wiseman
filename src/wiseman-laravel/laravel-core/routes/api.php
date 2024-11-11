<?php

use App\Http\Controllers\Api\UserVotingController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\MemoController;
use App\Http\Controllers\Api\ArchiveController;
use App\Http\Controllers\Api\VotingController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\GroupUserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
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
	Route::get('/user_votings', [UserVotingController::class, 'index'])->middleware(['auth.api']);
	Route::get('/user_votings/{id}', [UserVotingController::class, 'show'])->middleware(['auth.api']);
	Route::post('/user_votings', [UserVotingController::class, 'store'])->middleware(['auth.api']);
	Route::put('/user_votings/{id}', [UserVotingController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/user_votings/{id}', [UserVotingController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/enrollments', [EnrollmentController::class, 'index'])->middleware(['auth.api']);
	Route::get('/enrollments/{id}', [EnrollmentController::class, 'show'])->middleware(['auth.api']);
	Route::post('/enrollments', [EnrollmentController::class, 'store'])->middleware(['auth.api']);
	Route::put('/enrollments/{id}', [EnrollmentController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/memos', [MemoController::class, 'index'])->middleware(['auth.api']);
	Route::get('/memos/{id}', [MemoController::class, 'show'])->middleware(['auth.api']);
	Route::post('/memos', [MemoController::class, 'store'])->middleware(['auth.api']);
	Route::put('/memos/{id}', [MemoController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/memos/{id}', [MemoController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/archives', [ArchiveController::class, 'index'])->middleware(['auth.api']);
	Route::get('/archives/{id}', [ArchiveController::class, 'show'])->middleware(['auth.api']);
	Route::post('/archives', [ArchiveController::class, 'store'])->middleware(['auth.api']);
	Route::post('/archives/{id}', [ArchiveController::class, 'copy'])->middleware(['auth.api']);
	Route::put('/archives/{id}', [ArchiveController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/archives/{id}', [ArchiveController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/votings', [VotingController::class, 'index'])->middleware(['auth.api']);
	Route::get('/votings/{id}', [VotingController::class, 'show'])->middleware(['auth.api']);
	Route::post('/votings', [VotingController::class, 'store'])->middleware(['auth.api']);
	Route::put('/votings/{id}', [VotingController::class, 'update'])->middleware(['auth.api']);
	Route::put('/votings/option/{id}', [VotingController::class, 'addTotalOption'])->middleware(['auth.api']);
	Route::delete('/votings/{id}', [VotingController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/activities', [ActivityController::class, 'index'])->middleware(['auth.api']);
	Route::get('/activities/{id}', [ActivityController::class, 'show'])->middleware(['auth.api']);
	Route::post('/activities', [ActivityController::class, 'store'])->middleware(['auth.api']);
	Route::put('/activities/{id}', [ActivityController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/group_users', [GroupUserController::class, 'index'])->middleware(['auth.api']);
	Route::get('/group_users/{id}', [GroupUserController::class, 'show'])->middleware(['auth.api']);
	Route::post('/group_users', [GroupUserController::class, 'store'])->middleware(['auth.api']);
	Route::put('/group_users/{id}', [GroupUserController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/group_users/{id}', [GroupUserController::class, 'destroy'])->middleware(['auth.api']);

	Route::get('/groups', [GroupController::class, 'index'])->middleware(['auth.api']);
	Route::get('/groups/{id}', [GroupController::class, 'show'])->middleware(['auth.api']);
	Route::post('/groups', [GroupController::class, 'store'])->middleware(['auth.api']);
	Route::put('/groups/{id}', [GroupController::class, 'update'])->middleware(['auth.api']);
	Route::delete('/groups/{id}', [GroupController::class, 'destroy'])->middleware(['auth.api']);

    Route::get('/', [SiteController::class, 'index'])->middleware(['auth.api']);

    Route::post('/auth/login', [AuthController::class, 'login']); //->middleware(['signature']);
    Route::post('/auth/logout', [AuthController::class, 'logout']); //->middleware(['signature']);
    Route::get('/auth/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);

    Route::get('/users', [UserController::class, 'index'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:user.view'])->middleware(['auth.api']);
    Route::get('/users/{id}', [UserController::class, 'show']); //->middleware(['auth.api', 'role:user.view'])->middleware(['auth.api']);
    Route::post('/users', [UserController::class, 'store']); //->middleware(['auth.api', 'role:user.create|roles.view'])->middleware(['auth.api']);
    Route::put('/users/{id}', [UserController::class, 'update']); //->middleware(['auth.api', 'role:user.update||roles.view'])->middleware(['auth.api']);
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:user.delete'])->middleware(['auth.api']);
    Route::post('/user/update-password', [UserController::class, 'updatePassword']);

    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);

    Route::get('/roles', [RoleController::class, 'index'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:roles.view'])->middleware(['auth.api']);
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:roles.view'])->middleware(['auth.api']);
    Route::post('/roles', [RoleController::class, 'store'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:roles.create'])->middleware(['auth.api']);
    Route::put('/roles', [RoleController::class, 'update'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:roles.update'])->middleware(['auth.api']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware(['auth.api']); //->middleware(['auth.api', 'role:roles.delete']);
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
