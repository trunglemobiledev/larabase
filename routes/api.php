<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailController;

use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;

// API V1
use App\Http\Controllers\Api\V1\PostCategoryController;
use App\Http\Controllers\Api\V1\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    //User
    // manager_user quyền quản lí user
    Route::group(['middleware' => 'can:manager_user'], function () {
        Route::resource('user', UserController::class)->middleware('can:quyen_xem_dsnhanvien'); // quyen xem danh sách nhan viên
        Route::post('user/change-role/{id}', [UserController::class, 'changeRole']);
        Route::post('user/change-permission/{id}', [UserController::class, 'changePermission']);
        Route::resource('user-detail', UserDetailController::class);
    });

    //Permission
    Route::group(['middleware' => 'can:manager_permission'], function () {
        Route::get('permissions', [PermissionController::class, 'list']);
        Route::post('permission/create', [PermissionController::class, 'store']);
        Route::get('permission/{id}', [PermissionController::class, 'show']);
        Route::delete('permission/delete/{id}', [PermissionController::class, 'delete']);
    });

    //Role
//    Route::group(['middleware' => 'can:manager_role'], function () {
        Route::get('role', [RolesController::class, 'list'])->middleware('can:quyen_xem_dsrole');
        Route::post('role/create', [RolesController::class, 'store']);
        Route::get('role/{id}', [RolesController::class, 'show']);
        Route::delete('role/delete/{id}', [RolesController::class, 'delete']);
        Route::post('role/change-permission/{id}', [RolesController::class, 'changePermissions']);
//    });

    //Post category
    Route::group(['middleware' => 'can:manager_post_category'], function () {
        Route::resource('post-category', PostCategoryController::class);
    });

    //Post
    Route::group(['middleware' => 'role:manager_post'], function () {
        Route::resource('post', PostController::class);
    });

});

Route::any('{any}', function () {
    return response()->json([
        'status' => false,
        'message' => 'Resource not found'], 404);
})->where('any', '.*');



