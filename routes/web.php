<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', fn () => view('home'))->name('home');
Route::get('/pricing', fn () => view('pricing'))->name('pricing');
Route::get('/contact', fn () => view('contact'))->name('contact');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('units', UnitController::class);
    Route::resource('locations', LocationController::class);

    // Transactions
    Route::resource('purchases', PurchaseController::class);
    Route::post('purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');
    Route::post('purchases/{purchase}/submit', [PurchaseController::class, 'submit'])->name('purchases.submit');
    Route::post('purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    Route::get('purchases/{purchase}/print', [PurchaseController::class, 'print'])->name('purchases.print');

    Route::resource('sales', SaleController::class);
    Route::post('sales/{sale}/complete', [SaleController::class, 'complete'])->name('sales.complete');
    Route::post('sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    Route::get('sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');

    // Stock Opname
    Route::resource('stock-opname', StockOpnameController::class)->parameters(['stock-opname' => 'opname']);
    Route::post('stock-opname/{opname}/start', [StockOpnameController::class, 'start'])->name('stock-opname.start');
    Route::post('stock-opname/{opname}/complete', [StockOpnameController::class, 'complete'])->name('stock-opname.complete');
    Route::post('stock-opname/{opname}/items/{item}/count', [StockOpnameController::class, 'countItem'])->name('stock-opname.count');
    Route::post('stock-opname/{opname}/apply-adjustments', [StockOpnameController::class, 'applyAdjustments'])->name('stock-opname.apply');

    // Stock Movements (Kartu Stok)
    Route::get('/movements', [StockMovementController::class, 'index'])->name('movements.index');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/movement', [ReportController::class, 'movement'])->name('movement');
        Route::get('/valuation', [ReportController::class, 'valuation'])->name('valuation');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/company', [ProfileController::class, 'updateCompany'])->name('profile.company.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::put('/company', [CompanyController::class, 'update'])->name('company.update');
    Route::post('/company/add-member', [CompanyController::class, 'addMember'])->name('company.add-member');
    Route::delete('/company/remove-member/{member}', [CompanyController::class, 'removeMember'])->name('company.remove-member');
});

require __DIR__ . '/auth.php';
