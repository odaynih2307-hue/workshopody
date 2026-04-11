<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PenjualanController;

/*
|--------------------------------------------------------------------------
| Redirect Awal
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect('/login'));

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| GOOGLE LOGIN
|--------------------------------------------------------------------------
*/

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('google.login');

Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('google.callback');

/*
|--------------------------------------------------------------------------
| SETELAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/home', function () {

        if (session()->has('login_otp')) {
            return redirect()->route('otp.form');
        }

        return app(HomeController::class)->index();

    })->name('home');

    // OTP Routes
    Route::get('/otp', [OtpController::class, 'showForm'])->name('otp.form');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    // Kota Routes (form-html & form-datatable HARUS sebelum resource)
    Route::get('/kota/form-html', [KotaController::class, 'formHtml'])
        ->name('kota.form-html');

    Route::get('/kota/form-datatable', [KotaController::class, 'formDatatable'])
        ->name('kota.form-datatable');

    Route::resource('kota', KotaController::class);

    // AJAX API Routes for Kota
    Route::prefix('api/kota')->group(function () {
        Route::get('/all', [KotaController::class, 'getAllKota'])->name('kota.api.all');
        Route::post('/store', [KotaController::class, 'storeKotaAjax'])->name('kota.api.store');
        Route::put('/update/{id}', [KotaController::class, 'updateKotaAjax'])->name('kota.api.update');
        Route::delete('/delete/{id}', [KotaController::class, 'destroyKotaAjax'])->name('kota.api.delete');
    });

    Route::get('/pdf/sertifikat', function () {

        $pdf = Pdf::loadView('pdf.sertifikat')
            ->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat.pdf');

    });

    Route::get('/pdf/undangan', function () {

        $pdf = Pdf::loadView('pdf.undangan')
            ->setPaper('a4', 'portrait');

        return $pdf->download('undangan.pdf');

    });

    // Barang Routes (form-html & form-datatable HARUS sebelum resource)
    Route::get('/barang/form-html', [BarangController::class, 'formHtml'])
        ->name('barang.form-html');

    Route::get('/barang/form-datatable', [BarangController::class, 'formDatatable'])
        ->name('barang.form-datatable');

    Route::resource('barang', BarangController::class);

    Route::post('/barang/cetak', [BarangController::class, 'cetak'])
        ->name('barang.cetak');

    // AJAX API Routes for Barang
    Route::prefix('api/barang')->group(function () {
        Route::get('/all', [BarangController::class, 'getAllBarang'])->name('barang.api.all');
        Route::post('/store', [BarangController::class, 'storeBarangAjax'])->name('barang.api.store');
        Route::put('/update/{id}', [BarangController::class, 'updateBarangAjax'])->name('barang.api.update');
        Route::delete('/delete/{id}', [BarangController::class, 'destroyBarangAjax'])->name('barang.api.delete');
    });

    // Wilayah Routes - Cascading Select with Axios
    Route::get('/wilayah/cascading-form', [WilayahController::class, 'showCascadingForm'])
        ->name('wilayah.cascading-form');
    
    Route::get('/wilayah/debug-api', [WilayahController::class, 'debugApi'])
        ->name('wilayah.debug-api');

    // AJAX API Routes for Wilayah
    Route::prefix('api/wilayah')->group(function () {
        Route::get('/provinsi', [WilayahController::class, 'getProvinsi'])->name('wilayah.api.provinsi');
        Route::get('/kota', [WilayahController::class, 'getKota'])->name('wilayah.api.kota');
        Route::get('/kecamatan', [WilayahController::class, 'getKecamatan'])->name('wilayah.api.kecamatan');
        Route::get('/kelurahan', [WilayahController::class, 'getKelurahan'])->name('wilayah.api.kelurahan');
    });

    // POS/Kasir Routes
    Route::get('/kasir', [PenjualanController::class, 'index'])->name('kasir.index');
    Route::get('/kasir/history', function () {
        return view('penjualan.history');
    })->name('kasir.history');

    // AJAX API Routes for POS
    Route::prefix('api/penjualan')->group(function () {
        Route::post('/save', [PenjualanController::class, 'savePenjualan'])->name('penjualan.api.save');
        Route::get('/search/{kode}', [PenjualanController::class, 'searchBarang'])->name('penjualan.api.search');
        Route::get('/all-barang', [PenjualanController::class, 'getAllBarang'])->name('penjualan.api.all-barang');
        Route::get('/history', [PenjualanController::class, 'history'])->name('penjualan.api.history');
    });
    // KANTIN ADMIN ROUTES
    Route::prefix('kantin')->name('kantin.')->group(function () {
        // Customer side
        Route::get('pos', [\App\Http\Controllers\CustomerKantinController::class, 'index'])->name('pos');
        Route::get('api/menus/{vendor}', [\App\Http\Controllers\CustomerKantinController::class, 'getMenus'])->name('api.menus');
        Route::post('checkout', [\App\Http\Controllers\CustomerKantinController::class, 'checkout'])->name('checkout');
        Route::post('api/sync-payment/{order_id}', [\App\Http\Controllers\CustomerKantinController::class, 'syncPayment'])->name('sync-payment');

        // Admin side (Vendor)
        Route::resource('vendor', \App\Http\Controllers\VendorKantinController::class);
        Route::resource('menu', \App\Http\Controllers\MenuKantinController::class);
        Route::get('pesanan', [\App\Http\Controllers\PesananKantinController::class, 'index'])->name('pesanan.index');
    });

});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');

})->name('logout');


// Kota Select Route
    Route::get('/kota', function () {
        return view('auth.kota.index');
    })->name('kota.index');