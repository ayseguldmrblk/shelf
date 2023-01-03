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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/authors', [AuthorController::class, 'getAuthors'])->middleware('guest');
Route::post('/authors/add', [AuthorController::class, 'add'])->middleware(['auth:sanctum','admin']);
Route::delete('/authors/{id}', [AuthorController::class, 'delete'])->middleware(['auth:sanctum','admin']);

Route::get('/categories', [CategoryController::class, 'getCategories'])->middleware('guest');
Route::post('/category/add', [CategoryController::class, 'add'])->middleware(['auth:sanctum','admin']);
Route::delete('/category/{id}', [CategoryController::class, 'delete'])->middleware(['auth:sanctum','admin']);

Route::get('/books', [BookController::class, 'getBooks'])->middleware('guest');
Route::get('/books/{id}', [BookController::class, 'getBook'])->middleware('guest');
Route::put('/books/{id}', [BookController::class, 'update'])->middleware('guest');
Route::post('/books/add', [BookController::class, 'add'])->middleware('guest');
Route::delete('/books/{id}', [BookController::class, 'delete'])->middleware('guest');

Route::get('/cart', [CartController::class, 'getCart'])->middleware('auth:sanctum');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('auth:sanctum');
Route::delete('/cart/{id}', [CartController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/order-right', [CartController::class, 'checkRight'])->middleware('auth:sanctum');

Route::get('/favorites', [FavoriteController::class, 'getFavorites'])->middleware('auth:sanctum');
Route::post('/favorites/add', [FavoriteController::class, 'add'])->middleware('auth:sanctum');
Route::delete('/favorites/{id}', [FavoriteController::class, 'delete'])->middleware('auth:sanctum');

Route::get('/addresses', [AddressController::class, 'getAddresses'])->middleware('auth:sanctum');
Route::post('/addresses/add', [AddressController::class, 'add'])->middleware('auth:sanctum');
Route::delete('/addresses/{id}', [AddressController::class, 'delete'])->middleware('auth:sanctum');


Route::post('/order/create', [OrderController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/orders', [OrderController::class, 'orders'])->middleware('auth:sanctum');
Route::get('/sales', [OrderController::class, 'sales'])->middleware('auth:sanctum');

Route::get('user/{id}/reviews', [ReviewController::class, 'reviews'])->middleware('guest');
Route::get('user/{id}/delete', [AuthController::class, 'delete'])->middleware('guest');

Route::post('review/add', [ReviewController::class, 'add'])->middleware('auth:sanctum');
