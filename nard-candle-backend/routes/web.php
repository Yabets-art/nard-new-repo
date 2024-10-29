<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\FeaturedProductController;
use App\Http\Controllers\YouTubeVideoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Middleware\VerifyCsrfToken;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

Route::get('/', function () {
    return view('admin/index');
});

// Route::get('/csrf-token', function () {
//     return response()->json(['csrfToken' => csrf_token()]);
// });


Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

// require __DIR__.'/auth.php';


Route::get('/admin', function () {
    return view('admin.index');    
})->name('admin.index');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');


// Route for Layout
Route::get('/admin/layout', function () {
    return view('admin.layout');
})->name('admin.layout');

// Route for Order
Route::get('/admin/order', function () {
    return view('admin.order');
})->name('admin.order');


// ===================List of Api
// Route::prefix('api')->group(function () {

//     Route::get('/products', [ProductApiController::class, 'index']);
//     Route::post('/custom-orders', [CustomOrderController::class, 'store']);
//     Route::get('/promotions', [PromotionController::class, 'index']);

//     Route::prefix('admin')->name('admin.')->group(function () {
//         Route::resource('products', ProductController::class);
//         Route::get('/admin/messages', [MessageController::class, 'index'])->name('admin.message.index');
//     });

//     Route::post('/messages', [MessageController::class, 'store']);

//     Route::get('/csrf-token', function () {
//         return response()->json(['csrfToken' => csrf_token()]);
//     });

// });

// ===================List of Api




Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('product', [ProductController::class, 'index'])->name('product');
    Route::post('product', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{id}', [ProductController::class, 'delete'])->name('product.delete');
});



Route::get('/admin/custom-orders', [CustomOrderController::class, 'index'])->name('custom-orders.index');
Route::get('/admin/custom-orders/accept/{id}', [CustomOrderController::class, 'accept'])->name('custom-orders.accept');
Route::get('/admin/custom-orders/complete/{id}', [CustomOrderController::class, 'complete'])->name('custom-orders.complete');


// Route for Post
Route::get('/admin/post', function () {
    return view('admin.post');
})->name('admin.post');


// Route for Profile
Route::get('/admin/profile', function () {
    return view('admin.profile');
})->name('admin.profile');


Route::resource('promotions', PromotionController::class);
Route::resource('featured_products', FeaturedProductController::class);
Route::resource('youtube_videos', YouTubeVideoController::class);

// Promotion routes
Route::get('/admin/promotions', [PromotionController::class, 'index'])->name('admin.promotions.index');
Route::post('/admin/promotions', [PromotionController::class, 'store'])->name('admin.promotions.store');
Route::put('/admin/promotions/{promotion}', [PromotionController::class, 'update'])->name('admin.promotions.update');
Route::delete('/admin/promotions/{promotion}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy');
Route::patch('/admin/promotions/{promotion}/toggle', [PromotionController::class, 'toggleStatus'])->name('admin.promotions.toggleStatus');



// Correct route definitions:
Route::get('/admin/featured-products', [FeaturedProductController::class, 'index'])->name('admin.featured-products.index');
Route::post('/admin/featured-products', [FeaturedProductController::class, 'store'])->name('admin.featured-products.store');
Route::put('/admin/featured-products/{featuredProduct}', [FeaturedProductController::class, 'update'])->name('admin.featured-products.update');
Route::delete('/admin/featured-products/{featuredProduct}', [FeaturedProductController::class, 'destroy'])->name('admin.featured-products.destroy');


// YouTube Video routes
Route::get('/admin/youtube-videos', [YouTubeVideoController::class, 'index'])->name('admin.youtube-videos.index');
Route::post('/admin/youtube-videos', [YouTubeVideoController::class, 'store'])->name('admin.youtube-videos.store');
Route::put('/admin/youtube-videos/{youtubeVideo}', [YouTubeVideoController::class, 'update'])->name('admin.youtube-videos.update');
Route::delete('/admin/youtube-videos/{youtubeVideo}', [YouTubeVideoController::class, 'destroy'])->name('admin.youtube-videos.destroy');
Route::post('/admin/videos/store', [YouTubeVideoController::class, 'store'])->name('admin.videos.store');


// Post management routes
Route::get('/admin/post', [PostController::class, 'index'])->name('admin.post');
Route::post('/admin/post', [PostController::class, 'create'])->name('admin.post.create');
Route::post('/admin/post', [PostController::class, 'store'])->name('admin.post.store');
Route::get('/admin/post/{id}/edit', [PostController::class, 'edit'])->name('admin.post.edit');
Route::put('/admin/post/{id}', [PostController::class, 'update'])->name('admin.post.update');
Route::delete('/admin/post/{id}', [PostController::class, 'destroy'])->name('admin.post.destroy');


Route::post('/admin/message', [MessageController::class, 'store']);
Route::get('/admin/message', [MessageController::class, 'index'])->name('admin.message.index');
Route::get('/admin/message/{id}', [MessageController::class, 'show'])->name('admin.message.show');
