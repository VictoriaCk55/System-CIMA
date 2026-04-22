<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinancieroController;
use App\Http\Controllers\UserController;

// ==================== RUTAS PÚBLICAS ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ==================== RUTAS PROTEGIDAS ====================
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ========== PERFIL DE USUARIO ==========
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
    
    // ========== RUTAS DE BÚSQUEDA - PRIMERO (MUY IMPORTANTE) ==========
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');
    Route::get('/parametros/buscar', [ParametroController::class, 'buscar'])->name('parametros.buscar');
    Route::get('/informes/buscar-proformas', [InformeController::class, 'buscarProformas'])->name('informes.buscar-proformas');
    
    // ========== RUTAS DE ADMIN (SOLO ADMINISTRADORES) ==========
    Route::middleware(['admin'])->group(function () {
        
        // ===== USUARIOS (SOLO ADMIN) =====
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/users/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        
        // Rutas de eliminación con protección de administrador único
        Route::middleware(['ensure.admin'])->group(function () {
            Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        });
        
        // ===== CLIENTES =====
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        Route::post('/clientes/api', [ClienteController::class, 'storeApi'])->name('clientes.api.store');
        
        // PAPELERA DE CLIENTES
        Route::get('/clientes/trash', [ClienteController::class, 'trash'])->name('clientes.trash');
        Route::post('/clientes/{id}/restore', [ClienteController::class, 'restore'])->name('clientes.restore');
        Route::delete('/clientes/{id}/force-delete', [ClienteController::class, 'forceDelete'])->name('clientes.force-delete');
        
        // NUEVAS RUTAS PARA PAGOS MANUALES Y ACTUALIZAR SALDO
        Route::post('/clientes/{id}/registrar-pago', [ClienteController::class, 'registrarPago'])->name('clientes.registrar-pago');
        Route::post('/clientes/{id}/actualizar-saldo', [ClienteController::class, 'actualizarSaldo'])->name('clientes.actualizar-saldo');
        
        // ===== PARÁMETROS =====
        Route::get('/parametros/create', [ParametroController::class, 'create'])->name('parametros.create');
        Route::post('/parametros', [ParametroController::class, 'store'])->name('parametros.store');
        Route::get('/parametros/{parametro}/edit', [ParametroController::class, 'edit'])->name('parametros.edit');
        Route::put('/parametros/{parametro}', [ParametroController::class, 'update'])->name('parametros.update');
        Route::delete('/parametros/{parametro}', [ParametroController::class, 'destroy'])->name('parametros.destroy');
        
        // PAPELERA DE PARÁMETROS
        Route::get('/parametros/trash', [ParametroController::class, 'trash'])->name('parametros.trash');
        Route::post('/parametros/{id}/restore', [ParametroController::class, 'restore'])->name('parametros.restore');
        Route::delete('/parametros/{id}/force-delete', [ParametroController::class, 'forceDelete'])->name('parametros.force-delete');
        
        // ===== PROFORMAS =====
        Route::get('/proformas/create', [ProformaController::class, 'create'])->name('proformas.create');
        Route::post('/proformas', [ProformaController::class, 'store'])->name('proformas.store');
        Route::get('/proformas/{proforma}/edit', [ProformaController::class, 'edit'])->name('proformas.edit');
        Route::put('/proformas/{proforma}', [ProformaController::class, 'update'])->name('proformas.update');
        Route::delete('/proformas/{proforma}', [ProformaController::class, 'destroy'])->name('proformas.destroy');
        Route::post('/proformas/{proforma}/cambiar-estado', [ProformaController::class, 'cambiarEstado'])->name('proformas.cambiar-estado');
        
        // RUTA PARA ACTUALIZAR SOLO ADELANTO
        Route::put('/proformas/{proforma}/actualizar-adelanto', [ProformaController::class, 'actualizarAdelanto'])->name('proformas.actualizar-adelanto');
        
        // PAPELERA DE PROFORMAS
        Route::get('/proformas/trash', [ProformaController::class, 'trash'])->name('proformas.trash');
        Route::post('/proformas/{id}/restore', [ProformaController::class, 'restore'])->name('proformas.restore');
        Route::delete('/proformas/{id}/force-delete', [ProformaController::class, 'forceDelete'])->name('proformas.force-delete');
        
        // ===== INFORMES =====
        Route::get('/informes/create', [InformeController::class, 'create'])->name('informes.create');
        Route::post('/informes', [InformeController::class, 'store'])->name('informes.store');
        Route::get('/informes/{informe}/edit', [InformeController::class, 'edit'])->name('informes.edit');
        Route::put('/informes/{informe}', [InformeController::class, 'update'])->name('informes.update');
        Route::delete('/informes/{informe}', [InformeController::class, 'destroy'])->name('informes.destroy');
        Route::post('/informes/{informe}/cambiar-estado', [InformeController::class, 'cambiarEstado'])->name('informes.cambiar-estado');
        
        // PAPELERA DE INFORMES
        Route::get('/informes/trash', [InformeController::class, 'trash'])->name('informes.trash');
        Route::post('/informes/{id}/restore', [InformeController::class, 'restore'])->name('informes.restore');
        Route::delete('/informes/{id}/force-delete', [InformeController::class, 'forceDelete'])->name('informes.force-delete');
    });
    
    // ========== RUTAS DE LECTURA (TODOS LOS USUARIOS) ==========
    
    // CLIENTES - Lectura
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    
    // PARÁMETROS - Lectura
    Route::get('/parametros', [ParametroController::class, 'index'])->name('parametros.index');
    Route::get('/parametros/{parametro}', [ParametroController::class, 'show'])->name('parametros.show');
    
    // PROFORMAS - Lectura
    Route::get('/proformas', [ProformaController::class, 'index'])->name('proformas.index');
    Route::get('/proformas/{proforma}', [ProformaController::class, 'show'])->name('proformas.show');
    Route::get('/proformas/{proforma}/pdf', [ProformaController::class, 'pdf'])->name('proformas.pdf');
    
    // INFORMES - Lectura
    Route::get('/informes', [InformeController::class, 'index'])->name('informes.index');
    Route::get('/informes/{informe}', [InformeController::class, 'show'])->name('informes.show');
    Route::get('/informes/{informe}/pdf', [InformeController::class, 'pdf'])->name('informes.pdf');
    Route::get('/informes/{informe}/descargar/{tipo}', [InformeController::class, 'descargarArchivo'])->name('informes.descargar');
    
    // ========== RUTAS DEL MÓDULO FINANCIERO ==========
    Route::prefix('financiero')->name('financiero.')->group(function () {
        Route::get('/', [FinancieroController::class, 'index'])->name('index');
        Route::get('/cliente/{cliente}', [FinancieroController::class, 'cliente'])->name('cliente');
        Route::get('/exportar', [FinancieroController::class, 'exportar'])->name('exportar');
    });
});

// ========== RUTA DE FALLBACK ==========
Route::fallback(function () {
    return redirect()->route('home')
        ->with('error', '⛔ La página que buscas no existe.');
});