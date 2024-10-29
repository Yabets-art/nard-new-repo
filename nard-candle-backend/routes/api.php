<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\FeaturedProductController;
use App\Http\Controllers\YouTubeVideoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserCartController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductApiController::class, 'index']);
Route::post('/custom-orders', [CustomOrderController::class, 'store']);
Route::get('/promotions', [PromotionController::class, 'promotions']);
Route::get('/featured_products', [FeaturedProductController::class, 'featured_products']);
Route::get('/youtube-videos', [YouTubeVideoController::class, 'youtube_videos']);




Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    // Route::get('/admin/messages', [MessageController::class, 'index'])->name('admin.message.index');
});

Route::post('/messages', [MessageController::class, 'store']);

Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});


Route::get('user-cart', [UserCartController::class, 'index']);
Route::post('user-cart', [UserCartController::class, 'store']);
// Route::middleware('auth')->group(function () {
//     Route::get('user-cart', [UserCartController::class, 'index']); // Display the user's cart
//     Route::post('user-cart', [UserCartController::class, 'store']); // Add product to the user's cart
//     Route::put('user-cart/{id}', [UserCartController::class, 'update']); // Update the user's cart
//     Route::delete('user-cart/{id}', [UserCartController::class, 'destroy']); // Remove product from the cart
// });


Route::get('/posts', [PostController::class, 'getPosts']);


Route::get('admin/youtube-videos/{id}', [YouTubeVideoController::class, 'edit']);
Route::post('admin/youtube-videos/update/{id}', [YouTubeVideoController::class, 'update']);
