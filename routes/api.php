<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookSeriesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|------------------------------------------------------------------
| User Authentication Routes
|------------------------------------------------------------------
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'is_active' => $user->is_active,
                'role' => $user->roles->select( 'id', 'name', 'role_level')->first(),
            ],
        ]);
    });
});

/*
|------------------------------------------------------------------
| Home Page and General Routes
|------------------------------------------------------------------
*/
Route::get('/', HomePageController::class);

/*
|------------------------------------------------------------------
| Book Routes
|------------------------------------------------------------------
*/
Route::apiResource('/books', BookController::class);
Route::get('/books/{book}/download', [BookController::class, 'download'])->name('api.books.download');
Route::get('/books/pending/approval', [BookController::class, 'pendingApproval']);
Route::post('/books/{book}/approve', [BookController::class, 'approve']);

/*
|------------------------------------------------------------------
| Book Series Routes
|------------------------------------------------------------------
*/
Route::apiResource('book-series', BookSeriesController::class);

/*
|------------------------------------------------------------------
| Comment Routes
|------------------------------------------------------------------
*/
Route::post('/books/{bookId}/comments', [CommentController::class, 'store']);
Route::put('/books/{bookId}/comments/{commentId}', [CommentController::class, 'update']);
Route::delete('/books/{bookId}/comments/{commentId}', [CommentController::class, 'destroy']);
Route::get('/books/{bookId}/comments', [CommentController::class, 'index']);

/*
|------------------------------------------------------------------
| Download Routes
|------------------------------------------------------------------
*/
Route::get('/downloads', [DownloadController::class, 'index']);

/*
|------------------------------------------------------------------
| Role Management Routes
|------------------------------------------------------------------
*/
Route::apiResource('roles', RolesController::class);

Route::get('/permissions', function (Request $request) {
    $permissions = Permission::all();
    return response()->json([
        'data' => $permissions,
    ]);
});

/*
|------------------------------------------------------------------
| User Management Routes
|------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::post('/{user}/roles/add', [UserController::class, 'addRole'])->name('users.roles.add');
    Route::post('/{user}/roles/remove', [UserController::class, 'removeRole'])->name('users.roles.remove');
});

/*
|------------------------------------------------------------------
| Category Management Routes
|------------------------------------------------------------------
*/
Route::apiResource('categories', CategoryController::class);

/*
|------------------------------------------------------------------
| Category Group Management Routes
|------------------------------------------------------------------
*/
Route::prefix('category-groups')->group(function () {
    Route::get('/', [CategoryController::class, 'categoryGroups']);
    Route::get('/{id}', [CategoryController::class, 'showCategoryGroup']);
    Route::post('/', [CategoryController::class, 'storeCategoryGroup']);
    Route::put('/{id}', [CategoryController::class, 'updateCategoryGroup']);
    Route::delete('/{id}', [CategoryController::class, 'destroyCategoryGroup']);
});

/*
|------------------------------------------------------------------
| Notification Routes
|------------------------------------------------------------------
*/
Route::prefix('notifications')->group(function () {
    Route::delete('/delete-all', [NotificationController::class, 'deleteAllNotifications']);
    Route::post('/send/all', [NotificationController::class, 'sendToAllUsers']);
    Route::post('/send/user/{id}', [NotificationController::class, 'sendToSpecificUser']);
    Route::get('/user', [NotificationController::class, 'getUserNotifications']);
    Route::get('/user/read', [NotificationController::class, 'getReadNotifications']);
    Route::post('/read/{notificationId}', [NotificationController::class, 'markAsRead']);
    Route::delete('/{notificationId}', [NotificationController::class, 'deleteNotification']);
});

/*
|------------------------------------------------------------------
| Author Management Routes
|------------------------------------------------------------------
*/
Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index']);
    Route::get('/{author}', [AuthorController::class, 'show']);
    Route::delete('/{id}', [AuthorController::class, 'delete']);
    Route::get('/{id}/books', [AuthorController::class, 'booksByAuthor']);
});

/*
|------------------------------------------------------------------
| Author Request Management Routes
|------------------------------------------------------------------
*/
Route::prefix('author-requests')->group(function () {
    Route::get('/', [AuthorController::class, 'listRequests']);
    Route::post('/create', [AuthorController::class, 'requestAuthor']);
    Route::post('/{id}/handle', [AuthorController::class, 'handleRequest']);
    Route::post('/{id}/update', [AuthorController::class, 'updateAuthorRequest']);
    Route::post('/{id}/handle-update', [AuthorController::class, 'handleUpdateRequest']);
});
