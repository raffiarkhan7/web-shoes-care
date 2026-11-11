<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LandingTreatmentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WebsiteContentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pembeli\DashboardController as PembeliDashboardController;
use App\Http\Controllers\Pembeli\OrderController as PembeliOrderController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;
use App\Http\Controllers\Penjual\OrderController as PenjualOrderController;
use App\Http\Controllers\Penjual\ServiceController as PenjualServiceController;
use App\Models\LandingTreatment;
use App\Models\WebsiteContent;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $websiteContent = WebsiteContent::first();
    } catch (QueryException $exception) {
        $databaseErrorCode = (int) ($exception->errorInfo[1] ?? 0);
        if (! in_array($databaseErrorCode, [1045, 1049, 1146, 1698, 2002, 2003], true)) {
            throw $exception;
        }

        $websiteContent = null;
    }
    $previewDraft = request()->boolean('preview') && request()->user()?->isAdmin();
    $whatsappAdminNumber = preg_replace('/\D+/', '', (string) config('services.whatsapp.admin_number'));
    $content = $previewDraft
        ? ($websiteContent?->draft ?? config('landing.content'))
        : ($websiteContent?->published ?? config('landing.content'));
    $treatments = $content['treatments'] ?? ($websiteContent?->catalog_initialized
        ? LandingTreatment::orderBy('sort_order')->orderBy('id')->get()->toArray()
        : config('landing.treatments'));
    $treatments = array_values(array_filter($treatments, fn (array $treatment): bool => (bool) ($treatment['is_active'] ?? true)));
    $whatsappAdminNumber = preg_replace('/\D+/', '', (string) ($content['whatsapp_number'] ?? $whatsappAdminNumber));

    return view('landing', [
        'content' => $content,
        'isPreview' => $previewDraft,
        'treatments' => $treatments,
        'pickupInfo' => config('landing.pickup'),
        'whatsappAdminNumber' => $whatsappAdminNumber,
    ]);
})->name('home');

Route::get('/admin/login', [AdminLoginController::class, 'show'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.store');
Route::get('/admin', fn () => redirect()->route('admin.dashboard'))->name('admin.home');

Route::get('/landing/{path}', function (string $path) {
    $path = str_replace(['..', '\\'], '', $path);
    $file = base_path('landing/'.$path);

    abort_unless(is_file($file), 404);

    $mime = match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        default => 'application/octet-stream',
    };

    return response()->file($file, ['Content-Type' => $mime]);
})->where('path', '.*')->name('landing.asset');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->dashboardRoute());
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('website', [WebsiteContentController::class, 'edit'])->name('website.edit');
        Route::put('website', [WebsiteContentController::class, 'update'])->name('website.update');
        Route::post('website/discard', [WebsiteContentController::class, 'discard'])->name('website.discard');
        Route::get('website/treatments', [LandingTreatmentController::class, 'index'])->name('website.treatments.index');
        Route::get('website/treatments/create', [LandingTreatmentController::class, 'create'])->name('website.treatments.create');
        Route::post('website/treatments', [LandingTreatmentController::class, 'store'])->name('website.treatments.store');
        Route::get('website/treatments/{treatment}/edit', [LandingTreatmentController::class, 'edit'])->name('website.treatments.edit');
        Route::put('website/treatments/{treatment}', [LandingTreatmentController::class, 'update'])->name('website.treatments.update');
        Route::delete('website/treatments/{treatment}', [LandingTreatmentController::class, 'destroy'])->name('website.treatments.destroy');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::patch('users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::patch('services/{service}/toggle', [AdminServiceController::class, 'toggle'])->name('services.toggle');
        Route::delete('services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    });

    Route::prefix('penjual')->name('penjual.')->middleware('role:penjual')->group(function () {
        Route::get('/', [PenjualDashboardController::class, 'index'])->name('dashboard');

        Route::get('services', [PenjualServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [PenjualServiceController::class, 'create'])->name('services.create');
        Route::post('services', [PenjualServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}/edit', [PenjualServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{service}', [PenjualServiceController::class, 'update'])->name('services.update');
        Route::patch('services/{service}/toggle', [PenjualServiceController::class, 'toggle'])->name('services.toggle');
        Route::delete('services/{service}', [PenjualServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('orders', [PenjualOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [PenjualOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [PenjualOrderController::class, 'update'])->name('orders.update');
    });

    Route::prefix('pembeli')->name('pembeli.')->middleware('role:pembeli')->group(function () {
        Route::get('/', [PembeliDashboardController::class, 'index'])->name('dashboard');

        Route::get('orders', [PembeliOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [PembeliOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [PembeliOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{order}', [PembeliOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/cancel', [PembeliOrderController::class, 'cancel'])->name('orders.cancel');
    });
});
