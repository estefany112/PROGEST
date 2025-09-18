
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Asistente\AsistenteDashboardController;
use App\Http\Controllers\ClienteController;

Route::get('/debug-rol', function () {
    return view('debug-rol');
})->middleware('auth');


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

         //CRUD USUARIOS
        Route::get('/usuarios',            [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/{id}/edit',  [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}',       [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}',    [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });

    // ASISTENTE
    Route::middleware(['role:asistente'])->prefix('asistente')->group(function () {
    Route::get('/dashboard', [AsistenteDashboardController::class, 'index'])->name('asistente.dashboard');
    Route::get('/cotizaciones', [\App\Http\Controllers\AsistenteCotizacionesController::class, 'index'])->name('cotizaciones');
    });

    // Accesible para admin y asistente
    Route::middleware(['auth', 'check.user.type:admin,asistente'])->group(function () {
        Route::resource('cotizaciones', CotizacionController::class);
        Route::post('cotizaciones/{cotizacion}/enviar-revision', [CotizacionController::class, 'enviarRevision'])->name('cotizaciones.enviar-revision');
        Route::post('cotizaciones/{cotizacion}/aprobar', [CotizacionController::class, 'aprobar'])->name('cotizaciones.aprobar');
        Route::post('cotizaciones/{cotizacion}/rechazar', [CotizacionController::class, 'rechazar'])->name('cotizaciones.rechazar');
        Route::get('cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'pdf'])->name('cotizaciones.pdf');
    Route::patch('cotizaciones/{cotizacion}/cambiar-estado', [CotizacionController::class, 'cambiarEstado'])->name('cotizaciones.cambiar-estado');

        // CLIENTES
        Route::get('/clientes/lista-json', [ClienteController::class, 'listaJson'])->name('clientes.lista-json');
        Route::post('/clientes',           [ClienteController::class, 'guardar'])->name('clientes.guardar');
        // CRUD de clientes
        Route::resource('clientes', ClienteController::class)->except(['store']);
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
