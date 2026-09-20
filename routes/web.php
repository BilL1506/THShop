<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GameInfoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeetaController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\VipController;
use Illuminate\Support\Facades\Route;

Route::get('/login',[AuthController::class,'loginForm'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.submit')->middleware('throttle:10,1');
Route::get('/register',[AuthController::class,'registerForm'])->name('register');
Route::post('/register',[AuthController::class,'register'])->name('register.submit')->middleware('throttle:5,1');
Route::get('/download',DownloadController::class)->name('download');
Route::get('/meeta',MeetaController::class)->name('meeta.legacy')->middleware('throttle:10,1');
Route::get('/tmpay/callback',[TopUpController::class,'callback'])->name('tmpay.callback')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

Route::middleware('tr.auth')->group(function(){
 Route::get('/',HomeController::class)->name('home');
 Route::post('/logout',[AuthController::class,'logout'])->name('logout');
 Route::get('/settings/password',[AuthController::class,'passwordForm'])->name('password.form');
 Route::post('/settings/password',[AuthController::class,'password'])->name('password.update')->middleware('throttle:5,1');
 Route::get('/shop',[ShopController::class,'index'])->name('shop');
 Route::post('/shop/buy',[ShopController::class,'buy'])->name('shop.buy')->middleware('throttle:20,1');
 Route::get('/vip',[VipController::class,'index'])->name('vip');
 Route::post('/vip/claim',[VipController::class,'claim'])->name('vip.claim')->middleware('throttle:10,1');
 Route::get('/topup',[TopUpController::class,'index'])->name('topup');
 Route::post('/topup',[TopUpController::class,'submit'])->name('topup.submit')->middleware('throttle:5,1');
 Route::get('/game/alchemist',[GameInfoController::class,'index'])->name('game.alchemist');
 Route::prefix('admin')->middleware('tr.admin')->name('admin.')->group(function(){
   Route::get('/',[AdminController::class,'index'])->name('index');
   Route::post('/menus',[AdminController::class,'storeMenu'])->name('menus.store');
   Route::put('/menus/{menu}',[AdminController::class,'updateMenu'])->name('menus.update');
   Route::post('/items',[AdminController::class,'storeItem'])->name('items.store');
   Route::put('/items/{item}',[AdminController::class,'updateItem'])->name('items.update');
   Route::delete('/items/{item}',[AdminController::class,'deleteItem'])->name('items.delete');
 });
});
