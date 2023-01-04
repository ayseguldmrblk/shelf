<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'signup'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/verify-email', [AuthController::class, 'confirmCode'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users', [AuthController::class, 'users'])->middleware('guest');
Route::post('/password-reset', [AuthController::class, 'resetPassword'])->middleware('guest');
Route::post('/change-password', [AuthController::class, 'setPassword'])->middleware('guest');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/authors', [AuthorController::class, 'getAuthors'])->middleware('guest');
Route::post('/authors/add', [AuthorController::class, 'add'])->middleware(['auth:sanctum','admin']);
Route::get('/authors/{id}/delete', [AuthorController::class, 'delete'])->middleware(['auth:sanctum','admin']);

Route::get('/categories', [CategoryController::class, 'getCategories'])->middleware('guest');
Route::post('/category/add', [CategoryController::class, 'add'])->middleware(['auth:sanctum','admin']);
Route::get('/category/{id}/delete', [CategoryController::class, 'delete'])->middleware(['auth:sanctum','admin']);

Route::get('/books', [BookController::class, 'getBooks'])->middleware('guest');
Route::get('/books/{id}', [BookController::class, 'getBook'])->middleware('guest');
Route::put('/books/{id}', [BookController::class, 'update'])->middleware('guest');
Route::post('/books/add', [BookController::class, 'add'])->middleware('guest');
Route::get('/books/{id}/delete', [BookController::class, 'delete'])->middleware('guest');

Route::get('/cart', [CartController::class, 'getCart'])->middleware('auth:sanctum');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('auth:sanctum');
Route::get('/cart/{id}/delete', [CartController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/order-right', [CartController::class, 'checkRight'])->middleware('auth:sanctum');

Route::get('/favorites', [FavoriteController::class, 'getFavorites'])->middleware('auth:sanctum');
Route::post('/favorites/add', [FavoriteController::class, 'add'])->middleware('auth:sanctum');
Route::get('/favorites/{id}/delete', [FavoriteController::class, 'delete'])->middleware('auth:sanctum');

Route::get('/addresses', [AddressController::class, 'getAddresses'])->middleware('auth:sanctum');
Route::post('/addresses/add', [AddressController::class, 'add'])->middleware('auth:sanctum');
Route::get('/addresses/{id}/delete', [AddressController::class, 'delete'])->middleware('auth:sanctum');

Route::post('/order/create', [OrderController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/orders', [OrderController::class, 'orders'])->middleware('auth:sanctum');
Route::get('/sales', [OrderController::class, 'sales'])->middleware('auth:sanctum');
Route::post('/sale/update/delivery-status', [OrderController::class, 'updateDeliveryStatus'])->middleware('guest');
Route::post('/sale/update/tracking-code', [OrderController::class, 'updateTrackingCode'])->middleware('guest');

Route::get('user/{id}/reviews', [ReviewController::class, 'reviews'])->middleware('guest');
Route::get('user/{id}/delete', [AuthController::class, 'delete'])->middleware('guest');
Route::put('user/{id}/update', [AuthController::class, 'update'])->middleware('guest');
Route::post('user/set-superuser', [AuthController::class, 'setAdmin'])->middleware('guest');
Route::post('user/set-manager', [AuthController::class, 'setManager'])->middleware('guest');

Route::post('review/add', [ReviewController::class, 'add'])->middleware('auth:sanctum');
Route::post('review/{id}/delete', [ReviewController::class, 'delete'])->middleware('auth:sanctum');

Route::get('/reports', [ReportController::class, 'getReports'])->middleware('guest');
Route::get('/reports/add', [ReportController::class, 'add'])->middleware('guest');
Route::get('/reports/{id}/delete', [ReportController::class, 'add'])->middleware('guest');
