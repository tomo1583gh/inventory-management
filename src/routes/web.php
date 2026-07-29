<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    // 商品管理
    Route::resource('items', ItemController::class)
        ->except(['show']);

    // 在庫管理
    Route::get('/stocks', [StockController::class, 'index'])
        ->name('stocks.index');

    // 在庫一覧csv出力
    Route::get('/stocks/export/csv', [StockController::class, 'exportCsv'])
        ->name('stocks.export.csv');
    
    // 入庫
    Route::get('/stocks/in', [StockController::class, 'createIn'])
        ->name('stocks.in.create');

    Route::post('/stocks/in', [StockController::class, 'storeIn'])
        ->name('stocks.in.store');

    // 出庫
    Route::get('/stocks/out', [StockController::class, 'createOut'])
        ->name('stocks.out.create');

    Route::post('/stocks/out', [StockController::class,'storeOut'])
        ->name('stocks.out.store');

    // 入出庫履歴
    Route::get('/stocks/logs', [StockController::class, 'logs'])
        ->name('stocks.logs');

    // 商品詳細
    Route::get('/items/{item}', [ItemController::class, 'show'])
        ->name('items.show');

    // 商品別入出庫履歴
    Route::get(
        '/items/{item}/logs',
        [StockController::class,'itemLogs']
    )->name('items.logs');

    // 入出庫履歴訂正機能
    Route::get(
        '/stock-logs/{stockLog}/correct',
        [StockController::class,'createCorrection']
    )->name('stock-logs.corrections.create');

    Route::post(
        '/stock-logs/{stockLog}/correct',
        [StockController::class, 'storeCorrection']
    )->name('stock-logs.corrections.store');

    // ダッシュボード
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');
});
