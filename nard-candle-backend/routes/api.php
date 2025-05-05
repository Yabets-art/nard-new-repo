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
use App\Http\Controllers\WebPaymentController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\UserCartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\EmailValidationController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json(['status' => 'API is working']);
});

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


  // Payment routes
  Route::middleware('auth:sanctum')->post('/initiate-payment', [WebPaymentController::class, 'initiatePayment']);
  Route::get('/payment-status/{tx_ref}', [WebPaymentController::class, 'checkPaymentStatus'])->name('payment.status');
  
  // Order verification route - faster than checking Chapa when their API is down
  Route::middleware('auth:sanctum')->get('/check-order-by-txref/{tx_ref}', [OrderController::class, 'checkOrderByTxRef']);

  // Order routes
  Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'getUserOrders']);
  Route::middleware('auth:sanctum')->get('/update-order-status/{tx_ref}', [OrderController::class, 'updateOrderStatusOnReceipt']);

  // Add other admin-only routes here
  // Cart Management Routes
  Route::prefix('cart')->middleware('auth:sanctum')->group(function() {
    Route::get('/', [CartItemController::class, 'index'])->name('cart.index'); // View the cart
    Route::post('/add', [CartItemController::class, 'addItem'])->name('cart.add'); // Add item to the cart
    Route::post('/remove', [CartItemController::class, 'removeItem'])->name('cart.remove'); // Remove item from the cart
    Route::post('/update', [CartItemController::class, 'updateQuantity'])->name('cart.update'); // Update item quantity
    Route::post('/clear', [CartItemController::class, 'clearCart'])->name('cart.clear'); // Clear the cart
    Route::get('/total', [CartItemController::class, 'getTotal'])->name('cart.total'); // Get total price
  });

// Debug routes
Route::middleware('auth:sanctum')->get('/debug-auth', function (Request $request) {
    return response()->json([
        'status' => 'authenticated',
        'user' => $request->user(),
        'token_info' => [
            'abilities' => $request->bearerToken() ? 'Token exists' : 'No token',
            'headers' => $request->header()
        ]
    ]);
});

// Test route to create a sample order - helpful for debugging
Route::middleware('auth:sanctum')->get('/create-test-order', function (Request $request) {
    $user = \Illuminate\Support\Facades\Auth::user();
    
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    
    // Create a sample order for testing
    $order = \App\Models\Order::create([
        'user_id' => $user->id,
        'tx_ref' => 'test_' . uniqid(),
        'total_amount' => 100.00,
        'status' => 'processing',
        'order_items' => [
            [
                'product_name' => 'Test Candle',
                'price' => 50.00,
                'quantity' => 2
            ]
        ],
        'payment_method' => 'chapa',
        'customer_email' => $user->email,
        'customer_name' => $user->name,
        'customer_phone' => $user->phone_number ?? '123456789',
        'paid_at' => now(),
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Test order created successfully',
        'order' => $order
    ]);
});

Route::get('/debug-system', function () {
    return response()->json([
        'status' => 'system info',
        'laravel_version' => app()->version(),
        'environment' => app()->environment(),
        'php_version' => phpversion(),
        'sanctum_enabled' => class_exists('Laravel\Sanctum\Sanctum'),
        'chapa_configured' => !empty(env('CHAPA_SECRET_KEY'))
    ]);
});

// Special debug endpoint for Chapa testing
Route::middleware('auth:sanctum')->post('/debug-payment-test', function (Request $request) {
    // Get the user
    $user = \Illuminate\Support\Facades\Auth::user();
    
    // Get a test email from the request or use the user's email
    $testEmail = $request->input('test_email', $user->email);
    
    // Log debug info
    \Illuminate\Support\Facades\Log::debug('DEBUG PAYMENT TEST', [
        'requested_by' => $user->id,
        'test_email' => $testEmail
    ]);
    
    // Create minimal data for Chapa test
    $data = [
        'amount' => 10.00,
        'currency' => 'ETB',
        'first_name' => $user->first_name ?? 'Test',
        'last_name' => $user->last_name ?? 'User',
        'phone_number' => $user->phone_number ?? '0000000000',
        'tx_ref' => 'test_' . uniqid(),
        'email' => $testEmail,
        'return_url' => '',
        'customization' => [
            'title' => 'Debug Test',
            'description' => 'Testing payment initialization'
        ]
    ];
    
    // Log the payload
    \Illuminate\Support\Facades\Log::debug('DEBUG PAYMENT TEST - PAYLOAD', ['payload' => $data]);
    
    // Make request to Chapa
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . env('CHAPA_SECRET_KEY')
    ])->post('https://api.chapa.co/v1/transaction/initialize', $data);
    
    // Log the response
    \Illuminate\Support\Facades\Log::debug('DEBUG PAYMENT TEST - RESPONSE', [
        'status' => $response->status(),
        'body' => $response->json()
    ]);
    
    // Return detailed info for debugging
    return response()->json([
        'test_email' => $testEmail,
        'chapa_response' => [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->json()
        ],
        'payload_sent' => $data
    ]);
});

// Authentication routes
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisteredUserController::class, 'apiStore']);

// User profile routes
Route::middleware('auth:sanctum')->post('/user/update-profile', [UserProfileController::class, 'updateProfile']);

// Email validation test route
Route::middleware('auth:sanctum')->post('/test-email-validation', [EmailValidationController::class, 'testEmailValidation']);

// Add test route at the end of the file
Route::get('/test-order-creation', 'App\Http\Controllers\TestController@testOrderCreation');