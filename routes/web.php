
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Asistente\AsistenteDashboardController;
use App\Http\Controllers\AsistenteCotizacionesController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ReporteTrabajoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ContraseñaPagoController;
use App\Http\Controllers\PagoController;
use App\Models\OrdenCompra;


Route::get('/debug-rol', function () {
    return view('debug-rol');
})->middleware('auth');


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // ADMIN
    Route::middleware(['role:admin', 'check.user.type:admin'])->prefix('admin')->group(function () {
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
    Route::middleware(['role:asistente', 'check.user.type:asistente'])->prefix('asistente')->group(function () {
    Route::get('/dashboard', [AsistenteDashboardController::class, 'index'])->name('asistente.dashboard');
    });

    // Accesible para admin y asistente
    Route::middleware(['auth', 'check.user.type:admin,asistente'])->group(function () {
        // COTIZACIONES
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
        // ÓRDENES DE COMPRA
        Route::get('/ordenes-compra', [OrdenCompraController::class, 'index'])->name('ordenes-compra.index');   
        Route::get('/ordenes-compra/create', [OrdenCompraController::class, 'create'])->name('ordenes-compra.create'); 
        Route::post('/ordenes-compra', [OrdenCompraController::class, 'store'])->name('ordenes-compra.store');  
        Route::get('/ordenes-compra/{orden}', [OrdenCompraController::class, 'show'])->name('ordenes-compra.show'); 
        Route::get('/ordenes-compra/{orden}/edit', [OrdenCompraController::class, 'edit'])->name('ordenes-compra.edit'); 
        Route::put('/ordenes-compra/{orden}', [OrdenCompraController::class, 'update'])->name('ordenes-compra.update'); 
        Route::delete('/ordenes-compra/{orden}', [OrdenCompraController::class, 'destroy'])->name('ordenes-compra.destroy'); 
        // FACTURAS
        Route::resource('facturas', FacturaController::class);

        // PAGOS
        Route::resource('pagos', App\Http\Controllers\PagoController::class)->only(['index', 'create', 'store']);

        // CONTRASEÑAS DE PAGO
        Route::resource('contraseñas', App\Http\Controllers\ContraseñaPagoController::class)->only(['index', 'create', 'store']);
        Route::patch('contraseñas/{id}/validar', [App\Http\Controllers\ContraseñaPagoController::class, 'validar'])->name('contraseñas.validar');

        // REPORTES DE TRABAJO
        Route::resource('reportes-trabajo', ReporteTrabajoController::class);

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
