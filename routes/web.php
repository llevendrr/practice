<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategorySpecFieldController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportMessageController;
use App\Http\Controllers\SupportThreadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');

if (app()->environment('local')) {
    Route::get('/mail/test', function () {
        $recipient = env('MAIL_TEST_RECIPIENT', config('mail.from.address'));

        try {
            Mail::raw('Це тестовий лист TechnoDim.', function ($message) use ($recipient) {
                $message->to($recipient)->subject('TechnoDim · тестова пошта');
            });

            return response()->json(['status' => 'sent']);
        } catch (\Throwable $exception) {
            Log::error('Тестове відправлення листа провалилось', [
                'recipient' => $recipient,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Не вдалося надіслати лист. Перевірте налаштування SMTP.',
            ], 500);
        }
    })->name('mail.test');
}
Route::view('/about', 'pages.about')->name('about');
Route::view('/delivery', 'pages.delivery')->name('delivery');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product-images/{id}', [ProductImageController::class, 'show'])->name('product-images.show');
Route::post('/product/{product:slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/support', [SupportThreadController::class, 'index'])->name('support.index');
    Route::post('/support', [SupportThreadController::class, 'store'])->name('support.store');
    Route::get('/support/{supportThread}', [SupportThreadController::class, 'show'])->name('support.show');
    Route::post('/support/{supportThread}/messages', [SupportMessageController::class, 'store'])->name('support.messages.store');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/payment/select/{order}', [PaymentController::class, 'select'])->name('payment.select');
    Route::post('/payment/select/{order}/cod', [PaymentController::class, 'selectCod'])->name('payment.select.cod');
    Route::get('/payment/card/{order}', [PaymentController::class, 'cardForm'])->name('payment.card');
    Route::post('/payment/card/{order}', [PaymentController::class, 'processCard'])->name('payment.card.process');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/fail/{order}', [PaymentController::class, 'fail'])->name('payment.fail');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');

    Route::get('categories/{category}/specs', [CategorySpecFieldController::class, 'index'])->name('categories.specs.index');
    Route::post('categories/{category}/specs', [CategorySpecFieldController::class, 'store'])->name('categories.specs.store');
    Route::patch('categories/{category}/specs/{field}', [CategorySpecFieldController::class, 'update'])->name('categories.specs.update');
    Route::delete('categories/{category}/specs/{field}', [CategorySpecFieldController::class, 'destroy'])->name('categories.specs.destroy');
    Route::get('categories/{category}/specs/json', [CategorySpecFieldController::class, 'api'])->name('categories.specs.api');
    Route::get('categories/{category}/spec-fields', [CategorySpecFieldController::class, 'api'])->name('categories.spec-fields');

    Route::resource('orders', AdminOrderController::class)->except('destroy');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/toggle', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggle');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::get('support', [AdminSupportController::class, 'index'])->name('support.index');
    Route::get('support/{supportThread}', [AdminSupportController::class, 'show'])->name('support.show');
    Route::post('support/{supportThread}/messages', [AdminSupportController::class, 'storeMessage'])->name('support.messages.store');
    Route::patch('support/{supportThread}/status', [AdminSupportController::class, 'updateStatus'])->name('support.status.update');
});
