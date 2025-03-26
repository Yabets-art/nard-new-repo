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
use App\Http\Controllers\TrainersController;
use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductApiController::class, 'index']);
Route::post('/custom-orders', [CustomOrderController::class, 'store']);
Route::get('/promotions', [PromotionController::class, 'promotions']);
Route::get('/featured_products', [FeaturedProductController::class, 'featured_products']);
Route::get('/youtube-videos', [YouTubeVideoController::class, 'youtube_videos']);
Route::get('/trainees', [TrainersController::class, 'index']);
Route::get('/TrainingDay', [TrainersController::class, 'trainingDays']);




Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    
});

Route::post('/messages', [MessageController::class, 'store']);

Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});


Route::get('user-cart', [UserCartController::class, 'index']);
Route::post('user-cart', [UserCartController::class, 'store']);



Route::get('/posts', [PostController::class, 'getPosts']);


Route::get('admin/youtube-videos/{id}', [YouTubeVideoController::class, 'edit']);
Route::post('admin/youtube-videos/update/{id}', [YouTubeVideoController::class, 'update']);







Route::post('/verify-payment', [PaymentController::class, 'verifyPayment']);








// Route::prefix('trainees')->name('trainees.')->group(function () {
//     Route::get('admin/trainees', [TrainersController::class, 'index'])->name('trainees.index');
//     Route::get('admin/trainees/{id}', [TrainersController::class, 'show'])->name('trainees.show');
//     Route::post('admin/trainees/remove-unpaid', [TrainersController::class, 'removeUnpaid'])->name('trainees.removeUnpaid');
//     Route::get('admin/training-days', [TrainersController::class, 'trainingDays'])->name('training-days.index');
//     Route::post('admin/training-days', [TrainersController::class, 'updateTrainingDays'])->name('training-days.update');
// });
// Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
//     // Route for managing training days
//     Route::get('training-day', [TrainersController::class, 'trainingDays'])->name('training-day');
//     // Route to update training days
//     Route::post('training-day/update', [TrainersController::class, 'updateTrainingDays'])->name('training-days.update');
//     // Route::get('/trainees', [TrainerController::class, 'index'])->name('trainees');
//     Route::get('/admin/trainers', [TrainersController::class, 'index'])->name('admin.trainers');
//     Route::get('/admin/trainees', [TrainersController::class, 'index'])->name('admin.trainees.index');

// }); 
