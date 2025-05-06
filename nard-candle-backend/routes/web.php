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
use App\Http\Controllers\WebPaymentController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Middleware\VerifyCsrfToken;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\TrainersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/order', function () {
        return view('admin.order');
    })->name('admin.order');

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

    Route::middleware(['ensure.user.authenticated'])->group(function () {
        Route::post('/payment/initiate', [WebPaymentController::class, 'initiatePayment'])->name('payment.initiate');
        Route::get('/payment/confirm', [WebPaymentController::class, 'confirmPayment'])->name('payment.confirm');
    });
});

// Temporary admin access route (REMOVE THIS IN PRODUCTION)
Route::get('/force-admin-access', function () {
    // Find our admin user
    $user = \App\Models\User::where('email', 'yabetsd29@gmail.com')->first();
    
    if (!$user) {
        return "User not found!";
    }
    
    // Force login
    \Illuminate\Support\Facades\Auth::login($user);
    
    // Debug user info
    echo "Logged in as: " . $user->email . "<br>";
    echo "Is admin: " . ($user->is_admin ? 'YES' : 'NO') . "<br>";
    echo "Admin type: " . gettype($user->is_admin) . "<br>";
    echo "Admin value: " . var_export($user->is_admin, true) . "<br>";
    
    return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
});

require __DIR__ . '/auth.php';
