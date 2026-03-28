<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TecnicoDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ElectroBol
|--------------------------------------------------------------------------
*/

// --- RUTAS PARA INVITADOS ---
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// --- RUTAS PROTEGIDAS ---
Route::middleware('auth')->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [ReporteController::class, 'dashboard'])->name('dashboard');
    
    // --- RECURSOS PRINCIPALES ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('articulos', ArticuloController::class);
    Route::resource('reparaciones', ReparacionController::class);
    Route::resource('tecnicos', TecnicoController::class);
    Route::resource('repuestos', RepuestoController::class);
    
    // --- RUTAS ESPECÍFICAS ---
    Route::post('/articulos/{articulo}/asignar-tecnico', [ArticuloController::class, 'asignarTecnico'])->name('articulos.asignar-tecnico');
    
    Route::post('/reparaciones/{reparacione}/evaluar', [ReparacionController::class, 'evaluar'])->name('reparaciones.evaluar');
    Route::post('/reparaciones/{reparacione}/aceptar', [ReparacionController::class, 'aceptarReparacion'])->name('reparaciones.aceptar');
    Route::post('/reparaciones/{reparacione}/rechazar', [ReparacionController::class, 'rechazarReparacion'])->name('reparaciones.rechazar');
    Route::post('/reparaciones/{reparacione}/avance', [ReparacionController::class, 'actualizarAvance'])->name('reparaciones.avance');
    Route::post('/reparaciones/{reparacione}/esperar', [ReparacionController::class, 'esperarRepuesto'])->name('reparaciones.esperar');
    Route::post('/reparaciones/{reparacione}/finalizar', [ReparacionController::class, 'finalizar'])->name('reparaciones.finalizar');
    Route::post('/reparaciones/{reparacione}/entregar', [ReparacionController::class, 'entregar'])->name('reparaciones.entregar');
    Route::get('/reparaciones/{reparacione}/informe', [ReparacionController::class, 'generarInforme'])->name('reparaciones.informe');
    Route::get('/reparaciones/{reparacione}/factura', [ReparacionController::class, 'generarFactura'])->name('reparaciones.factura');
    
    // Repuestos
    Route::post('/repuestos/{repuesto}/retirar', [RepuestoController::class, 'retirar'])->name('repuestos.retirar');
    Route::post('/repuestos/{repuesto}/pedido', [RepuestoController::class, 'pedido'])->name('repuestos.pedido');
    Route::post('/repuestos/{repuesto}/recibir', [RepuestoController::class, 'recibir'])->name('repuestos.recibir');
    
    // Reportes
    Route::get('/reportes/buscar-tecnico', [ReporteController::class, 'buscarPorTecnico'])->name('reportes.buscar-tecnico');
    Route::get('/reportes/historial-tecnico', [ReporteController::class, 'historialTecnico'])->name('reportes.historial-tecnico');
    Route::get('/tecnicos/{tecnico}/reporte', [TecnicoController::class, 'reportePorTecnico'])->name('tecnicos.reporte');
    Route::get('/tecnicos/{tecnico}/historial', [TecnicoController::class, 'reporteHistorial'])->name('tecnicos.historial');
    
    // --- PANEL DE TÉCNICOS ---
    Route::prefix('tecnico')->name('tecnico.')->group(function () {
        Route::get('/dashboard', [TecnicoDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reparaciones', [TecnicoDashboardController::class, 'misReparaciones'])->name('reparaciones');
        Route::post('/reparaciones/{reparacion}/avance', [TecnicoDashboardController::class, 'registrarAvance'])->name('registrar-avance');
        Route::get('/reparaciones/{reparacion}/evaluar', [TecnicoDashboardController::class, 'evaluarForm'])->name('evaluar-form');
    });

    // --- RUTAS PARA VINCULAR TÉCNICO CON USUARIO ---
    Route::get('/tecnicos/{tecnico}/vincular', [TecnicoController::class, 'vincularUsuarioForm'])->name('tecnicos.vincular-form');
    Route::post('/tecnicos/{tecnico}/vincular', [TecnicoController::class, 'vincularUsuario'])->name('tecnicos.vincular');
    Route::post('/tecnicos/{tecnico}/crear-usuario', [TecnicoController::class, 'crearUsuarioYVincular'])->name('tecnicos.crear-usuario');
    Route::post('/tecnicos/{tecnico}/desvincular', [TecnicoController::class, 'desvincularUsuario'])->name('tecnicos.desvincular');
    
    // --- SECCIÓN ADMINISTRADOR (COMPLETA CON RESPALDOS Y CONFIGURACIÓN) ---
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Dashboard principal
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Gestión de usuarios
        Route::post('/users/store', [AdminController::class, 'store'])->name('admin.users.store');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggle'])->name('admin.users.toggle');
        Route::post('/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.role');
        
        // Reportes y estadísticas
        Route::get('/reportes', [AdminController::class, 'reportesGenerales'])->name('admin.reportes');
        
        // Auditoría del sistema
        Route::get('/auditoria', [AdminController::class, 'auditoria'])->name('admin.auditoria');
        
        // Respaldos
        Route::get('/respaldos', [AdminController::class, 'respaldos'])->name('admin.respaldos');
        Route::post('/backup/generate', [AdminController::class, 'generarBackup'])->name('admin.backup.generate');
        Route::get('/backup/download/{file}', [AdminController::class, 'descargarBackup'])->name('admin.backup.download');
        Route::get('/backup/restore/{file}', [AdminController::class, 'restaurarBackup'])->name('admin.backup.restore');
        Route::get('/backup/delete/{file}', [AdminController::class, 'eliminarBackup'])->name('admin.backup.delete');
        Route::get('/backup/clean', [AdminController::class, 'limpiarBackups'])->name('admin.backup.clean');
        Route::post('/backup/schedule', [AdminController::class, 'programarBackup'])->name('admin.backup.schedule');
        Route::post('/backup/config', [AdminController::class, 'configurarBackup'])->name('admin.backup.config');
        
        // Configuración del sistema
        Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('admin.configuracion');
        Route::post('/config/update', [AdminController::class, 'updateConfiguracion'])->name('admin.config.update');
        Route::post('/config/facturacion', [AdminController::class, 'updateFacturacion'])->name('admin.config.facturacion');
        Route::post('/config/notificaciones', [AdminController::class, 'updateNotificaciones'])->name('admin.config.notificaciones');
        Route::post('/config/seguridad', [AdminController::class, 'updateSeguridad'])->name('admin.config.seguridad');
    });
    
    // --- SECCIÓN USUARIO REGULAR ---
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');
    });
    
    // --- SALIR DEL SISTEMA ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});