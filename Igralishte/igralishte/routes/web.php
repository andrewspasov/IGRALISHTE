<?php

use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteLoginController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;







/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [AuthenticatedSessionController::class, 'createAdmin'])->name('admin.login.view');
Route::post('/admin/login', [AuthenticatedSessionController::class, 'storeAdmin'])->name('admin.login');

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard')->middleware(['auth', 'verified']);






// Route to redirect to Google
Route::get('login/google', function () {
    return Socialite::driver('google')->redirect();
})->name('login.google');

// Google callback route
Route::get('login/google/callback', function () {
    $user = Socialite::driver('google')->user();
    $user->token;
});

// Route to redirect to Facebook
Route::get('login/facebook', function () {
    return Socialite::driver('facebook')->redirect();
})->name('login.facebook');

// Facebook callback route
Route::get('login/facebook/callback', function () {
    $user = Socialite::driver('facebook')->user();
    $user->token;
});



// Google routes
Route::get('auth/google', [SocialiteLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialiteLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Facebook routes
Route::get('auth/facebook', [SocialiteLoginController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [SocialiteLoginController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');




// Admin routes
Route::middleware(['auth', 'verified', 'check.user.role'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/profile', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::put('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('/admin/profile/password/change', [AdminProfileController::class, 'showPasswordForm'])->name('password.newShow');
    Route::put('/admin/profile/password/change', [AdminProfileController::class, 'newPassword'])->name('password.new');


    // Display Add Brand Form
    Route::get('admin/brands/add-brand', [BrandController::class, 'create'])
        ->name('admin.brands.create');

    // Store Brand Data
    Route::post('admin/brands/add-brand', [BrandController::class, 'store'])
        ->name('admin.brands.store');



    // Display active and inactive brands
    Route::get('admin/brands', [BrandController::class, 'show'])->name('brands');

    Route::get('/admin/brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');

    Route::put('/admin/brands/{id}', [BrandController::class, 'update'])->name('brands.update');

    // Delete a specific brand
    Route::delete('admin/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    Route::get('/brand/image/{brandImage}', [BrandController::class, 'getImage'])->name('brand.image');





    // Display Add Discount Form
    Route::get('admin/discounts/add-discount', [DiscountController::class, 'create'])
        ->name('admin.discount.create');

    // Display Add Discount Form
    Route::post('admin/discounts/add-discount', [DiscountController::class, 'store'])
        ->name('admin.discount.store');


    Route::get('admin/discounts', [DiscountController::class, 'show'])->name('discounts');


    Route::get('/admin/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');


    Route::put('/admin/discounts/{id}', [DiscountController::class, 'update'])->name('discount.update');

    // Delete a specific brand
    Route::delete('admin/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');





    // PRODUCTS

    Route::get('admin/products/add-product', [ProductController::class, 'create'])->name('create');
    Route::post('admin/products/add-product', [ProductController::class, 'store'])->name('store');


    Route::get('/admin/products/{viewType?}', [ProductController::class, 'index'])->name('products');



    // Show the form for editing a product
    Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // Update the specified product
    Route::put('admin/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Delete the specified product
    Route::delete('admin/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


    Route::get('admin/products/details/{id}', [ProductController::class, 'showDetails'])->name('products.showDetails');

    Route::post('/admin/products/{product}/reset-variants-test', [ProductController::class, 'resetVariants'])->name('products.resetVariants');

    Route::get('/product-image/{image}', [ProductController::class, 'getImage'])->name('product.getImage');



    Route::post('/admin/products/check-variant', [ProductController::class, 'checkVariant'])->name('products.checkVariant');

});




// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';







// Step 1: Show Registration Form Step One
Route::get('register-step-one', [RegisteredUserController::class, 'showRegistrationFormStepOne'])
    ->middleware('guest')
    ->name('register.step.one');

// Process Step 1
Route::post('register-step-one', [RegisteredUserController::class, 'processRegistrationStepOne'])
    ->middleware('guest')
    ->name('register.process.step.one');

// Step 2: Show Registration Form Step Two
Route::get('register-step-two', [RegisteredUserController::class, 'showRegistrationFormStepTwo'])
    ->middleware('guest')
    ->name('register.step.two');

// Process Step 2
Route::post('register-step-two', [RegisteredUserController::class, 'processRegistrationStepTwo'])
    ->middleware('guest')
    ->name('register.process.step.two');

// Step 3: Show Registration Form Step Three
Route::get('register-step-three', [RegisteredUserController::class, 'showRegistrationFormStepThree'])
    ->middleware('guest')
    ->name('register.step.three');

// Process Step 3 (Final Step)
Route::post('register-step-three', [RegisteredUserController::class, 'processRegistrationStepThree'])
    ->middleware('guest')
    ->name('register.process.step.three');

// Route for skipping optional registration details
Route::get('register-skip', [RegisteredUserController::class, 'skipRegistration'])
    ->middleware('guest')
    ->name('register.skip.step.three');
