<?php

use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BookSeriesController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\DownloadController;
use App\Http\Controllers\API\HomePageController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Middleware to authenticate Sanctum requests
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('roles');
        return $user;
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
| User Routes
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
| Category Routes
|------------------------------------------------------------------
*/
Route::apiResource('categories', CategoryController::class);

/*
|------------------------------------------------------------------
| Category Group Routes
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
| Author Routes
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
| Author Request Routes
|------------------------------------------------------------------
*/
Route::prefix('author-requests')->group(function () {
    Route::get('/', [AuthorController::class, 'listRequests']);
    Route::post('/create', [AuthorController::class, 'requestAuthor']);
    Route::post('/{id}/handle', [AuthorController::class, 'handleRequest']);
    Route::post('/{id}/update', [AuthorController::class, 'updateAuthorRequest']);
    Route::post('/{id}/handle-update', [AuthorController::class, 'handleUpdateRequest']);
});

// Additional routes
require __DIR__.'/auth.php';
