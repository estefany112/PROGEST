<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Asistente\AsistenteDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // ADMIN
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/usuarios-pendientes', [UsuarioController::class, 'pendientes'])->name('admin.usuarios.pendientes');
        Route::put('/usuarios-aprobar/{id}', [UsuarioController::class, 'aprobar'])->name('admin.usuarios.aprobar');
        Route::put('/usuarios-rechazar/{id}', [UsuarioController::class, 'rechazar'])->name('admin.usuarios.rechazar');
    });

    // ASISTENTE
    Route::middleware(['role:asistente'])->prefix('asistente')->group(function () {
        Route::get('/dashboard', [AsistenteDashboardController::class, 'index'])->name('asistente.dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/acceso-denegado', function () {
    return view('auth.acceso_denegado', [
        'mensaje' => session('mensaje', 'Acceso denegado.')
    ]);
})->name('acceso.denegado');


require __DIR__.'/auth.php';
