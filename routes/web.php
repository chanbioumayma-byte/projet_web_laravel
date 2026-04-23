<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authentification (routes générées par laravel/ui)
|--------------------------------------------------------------------------
*/
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Produits (resource = index, create, store, show, edit, update, destroy)
|--------------------------------------------------------------------------
*/
Route::resource('products', ProductController::class);

/*
|--------------------------------------------------------------------------
| Panier (nécessite d'être connecté)
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->name('cart.')->middleware('auth')->group(function () {
    Route::get('/',              [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear',      [CartController::class, 'clear'])->name('clear');
});

/*
|--------------------------------------------------------------------------
| Commandes (nécessite d'être connecté)
|--------------------------------------------------------------------------
*/
Route::prefix('orders')->name('orders.')->middleware('auth')->group(function () {
    Route::get('/',               [OrderController::class, 'index'])->name('index');
    Route::post('/',              [OrderController::class, 'store'])->name('store');
    Route::get('/{order}',        [OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
});

/*
|--------------------------------------------------------------------------
| Évaluations (nécessite d'être connecté)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');
});

/*
|--------------------------------------------------------------------------
| Profil utilisateur (nécessite d'être connecté)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
