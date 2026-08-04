<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\FinancieroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ==================== RUTAS PÚBLICAS ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ==================== RUTAS PROTEGIDAS ====================
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ========== PERFIL DE USUARIO ==========
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit')->middleware('permission:edit.profile');
        Route::put('/', [ProfileController::class, 'update'])->name('update')->middleware('permission:update.profile');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password')->middleware('permission:update.password');
    });

    // ========== RUTAS DE BÚSQUEDA - PRIMERO (MUY IMPORTANTE) ==========
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar')->middleware('permission:ver clientes');
    Route::get('/parametros/buscar', [ParametroController::class, 'buscar'])->name('parametros.buscar')->middleware('permission:ver parametros');
    Route::get('/informes/buscar-proformas', [InformeController::class, 'buscarProformas'])->name('informes.buscar-proformas')->middleware('permission:ver informes');

    // ========== RUTAS DE ADMIN (SOLO ADMINISTRADORES) ==========
    Route::middleware(['admin'])->group(function () {

        // ===== USUARIOS =====
        Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:ver usuarios');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:crear usuarios');
        Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:crear usuarios');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:editar usuarios');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update')->middleware('permission:editar usuarios');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->middleware('permission:ver usuarios');

        Route::get('/users/trash', [UserController::class, 'trash'])->name('users.trash')->middleware('permission:ver papelera usuarios');
        Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore')->middleware('permission:restore usuarios');

        // Rutas de eliminación con protección de administrador único
        Route::middleware(['ensure.admin'])->group(function () {
            Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:eliminar usuarios');
            Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete')->middleware('permission:force-delete usuarios');
        });

        // ===== ROLES Y PERMISOS (SPATIE) =====
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:ver roles');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:crear roles');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:crear roles');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:editar roles');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:editar roles');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:eliminar roles');

        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:ver permisos');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:crear permisos');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:crear permisos');
        Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:editar permisos');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:editar permisos');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:eliminar permisos');

        // ===== CLIENTES =====
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create')->middleware('permission:crear clientes');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store')->middleware('permission:crear clientes');
        Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit')->middleware('permission:editar clientes');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update')->middleware('permission:editar clientes');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy')->middleware('permission:eliminar clientes');
        Route::post('/clientes/api', [ClienteController::class, 'storeApi'])->name('clientes.api.store')->middleware('permission:crear clientes');

        // PAPELERA DE CLIENTES
        Route::get('/clientes/trash', [ClienteController::class, 'trash'])->name('clientes.trash')->middleware('permission:ver papelera clientes');
        Route::post('/clientes/{id}/restore', [ClienteController::class, 'restore'])->name('clientes.restore')->middleware('permission:restaurar clientes');
        Route::delete('/clientes/{id}/force-delete', [ClienteController::class, 'forceDelete'])->name('clientes.force-delete')->middleware('permission:forzar eliminar clientes');

        // NUEVAS RUTAS PARA PAGOS MANUALES Y ACTUALIZAR SALDO
        Route::post('/clientes/{id}/registrar-pago', [ClienteController::class, 'registrarPago'])->name('clientes.registrar-pago')->middleware('permission:registrar pago clientes');
        Route::post('/clientes/{id}/actualizar-saldo', [ClienteController::class, 'actualizarSaldo'])->name('clientes.actualizar-saldo')->middleware('permission:actualizar saldo clientes');

        // ===== PARÁMETROS =====
        Route::get('/parametros/create', [ParametroController::class, 'create'])->name('parametros.create')->middleware('permission:crear parametros');
        Route::post('/parametros', [ParametroController::class, 'store'])->name('parametros.store')->middleware('permission:crear parametros');
        Route::get('/parametros/{parametro}/edit', [ParametroController::class, 'edit'])->name('parametros.edit')->middleware('permission:editar parametros');
        Route::put('/parametros/{parametro}', [ParametroController::class, 'update'])->name('parametros.update')->middleware('permission:editar parametros');
        Route::delete('/parametros/{parametro}', [ParametroController::class, 'destroy'])->name('parametros.destroy')->middleware('permission:eliminar parametros');

        // PAPELERA DE PARÁMETROS
        Route::get('/parametros/trash', [ParametroController::class, 'trash'])->name('parametros.trash')->middleware('permission:ver papelera parametros');
        Route::post('/parametros/{id}/restore', [ParametroController::class, 'restore'])->name('parametros.restore')->middleware('permission:restaurar parametros');
        Route::delete('/parametros/{id}/force-delete', [ParametroController::class, 'forceDelete'])->name('parametros.force-delete')->middleware('permission:forzar eliminar parametros');

        // ===== PROFORMAS =====
        Route::get('/proformas/create', [ProformaController::class, 'create'])->name('proformas.create')->middleware('permission:crear proformas');
        Route::post('/proformas', [ProformaController::class, 'store'])->name('proformas.store')->middleware('permission:crear proformas');
        Route::get('/proformas/{proforma}/edit', [ProformaController::class, 'edit'])->name('proformas.edit')->middleware('permission:editar proformas');
        Route::put('/proformas/{proforma}', [ProformaController::class, 'update'])->name('proformas.update')->middleware('permission:editar proformas');
        Route::delete('/proformas/{proforma}', [ProformaController::class, 'destroy'])->name('proformas.destroy')->middleware('permission:eliminar proformas');
        Route::post('/proformas/{proforma}/cambiar-estado', [ProformaController::class, 'cambiarEstado'])->name('proformas.cambiar-estado')->middleware('permission:revision de proformas');

        // RUTA PARA ACTUALIZAR SOLO ADELANTO
        Route::put('/proformas/{proforma}/actualizar-adelanto', [ProformaController::class, 'actualizarAdelanto'])->name('proformas.actualizar-adelanto')->middleware('permission:editar adelanto de proformas');

        // PAPELERA DE PROFORMAS
        Route::get('/proformas/trash', [ProformaController::class, 'trash'])->name('proformas.trash')->middleware('permission:ver papelera proformas');
        Route::post('/proformas/{id}/restore', [ProformaController::class, 'restore'])->name('proformas.restore')->middleware('permission:restore proformas');
        Route::delete('/proformas/{id}/force-delete', [ProformaController::class, 'forceDelete'])->name('proformas.force-delete')->middleware('permission:forzar eliminar proformas');

        // ===== INFORMES =====
        Route::get('/informes/create', [InformeController::class, 'create'])->name('informes.create')->middleware('permission:crear informes');
        Route::post('/informes', [InformeController::class, 'store'])->name('informes.store')->middleware('permission:crear informes');
        Route::get('/informes/{informe}/edit', [InformeController::class, 'edit'])->name('informes.edit')->middleware('permission:editar informes');
        Route::put('/informes/{informe}', [InformeController::class, 'update'])->name('informes.update')->middleware('permission:editar informes');
        Route::delete('/informes/{informe}', [InformeController::class, 'destroy'])->name('informes.destroy')->middleware('permission:eliminar informes');
        Route::post('/informes/{informe}/cambiar-estado', [InformeController::class, 'cambiarEstado'])->name('informes.cambiar-estado')->middleware('permission:cambiar estado informes');

        // PAPELERA DE INFORMES
        Route::get('/informes/trash', [InformeController::class, 'trash'])->name('informes.trash')->middleware('permission:ver papelera informes');
        Route::post('/informes/{id}/restore', [InformeController::class, 'restore'])->name('informes.restore')->middleware('permission:restore informes');
        Route::delete('/informes/{id}/force-delete', [InformeController::class, 'forceDelete'])->name('informes.force-delete')->middleware('permission:force-delete informes');

        // ===== CONFIGURACIONES =====
        Route::get('/configuraciones/{documento?}', [ConfiguracionController::class, 'index'])->name('configuraciones.index');
        Route::put('/configuraciones/{documento}', [ConfiguracionController::class, 'update'])->name('configuraciones.update');
    });

    // ========== RUTAS DE LECTURA (TODOS LOS USUARIOS) ==========

    // CLIENTES - Lectura
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index')->middleware('role:admin|tecnico|analista');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show')->middleware('permission:ver clientes');

    // PARÁMETROS - Lectura
    Route::get('/parametros', [ParametroController::class, 'index'])->name('parametros.index')->middleware('role:admin|tecnico|analista');
    Route::get('/parametros/{parametro}', [ParametroController::class, 'show'])->name('parametros.show')->middleware('permission:ver parametros');

    // PROFORMAS - Lectura
    Route::get('/proformas', [ProformaController::class, 'index'])->name('proformas.index')->middleware('role:admin|tecnico|analista');
    Route::get('/proformas/{proforma}', [ProformaController::class, 'show'])->name('proformas.show')->middleware('permission:ver proformas');
    Route::get('/proformas/{proforma}/pdf', [ProformaController::class, 'pdf'])->name('proformas.pdf')->middleware('permission:generar pdf proformas');

    // ========== RUTA DE RESULTADOS DE ENSAYO ==========
    Route::get('/resultados/{id}', [ResultadosController::class, 'index'])->name('resultados.index')->middleware('role:admin|analista');
    Route::post('/resultados/{id}/guardar', [ResultadosController::class, 'guardarResultados'])->name('resultados.guardar')->middleware('permission:guardar resultados');
    Route::get('/resultados/{id}/cargar', [ResultadosController::class, 'cargarResultados'])->name('resultados.cargar')->middleware('permission:cargar resultados');
    Route::post('/proformas/{id}/limpiar-resultados', [ResultadosController::class, 'limpiarResultados'])->name('proformas.limpiar-resultados')->middleware('permission:limpiar resultados');

    Route::get('/proformas/{id}/resultados', [ResultadosController::class, 'index'])->name('proformas.resultados')->middleware('role:admin|analista');
    Route::post('/proformas/{id}/resultados/guardar', [ResultadosController::class, 'guardarResultados'])->name('proformas.resultados.guardar')->middleware('permission:guardar resultados');
    Route::get('/proformas/{id}/resultados/cargar', [ResultadosController::class, 'cargarResultados'])->name('proformas.resultados.cargar')->middleware('permission:cargar resultados');
    Route::post('/proformas/{id}/resultados/modificar-generales', [ResultadosController::class, 'modificarDatosGenerales'])->name('proformas.resultados.modificar-generales')->middleware('permission:guardar resultados');
    Route::post('/proformas/{id}/resultados/guardar-generales', [ResultadosController::class, 'guardarTodosGenerales'])->name('proformas.resultados.guardar-generales')->middleware('permission:guardar resultados');
    Route::post('/proformas/{id}/resultados/modificar-parametro', [ResultadosController::class, 'modificarParametro'])->name('proformas.resultados.modificar-parametro')->middleware('permission:guardar resultados');
    Route::get('/proformas/{id}/resultados/historial/{parametroId?}', [ResultadosController::class, 'historial'])->name('proformas.resultados.historial')->middleware('permission:cargar resultados');
    Route::get('/proformas/{id}/resultados/estado-bloqueo', [ResultadosController::class, 'estadoBloqueo'])->name('proformas.resultados.estado-bloqueo')->middleware('permission:cargar resultados');
    Route::get('/proformas/{id}/resultados/pdf', [ResultadosController::class, 'generarPdfResultados'])->name('proformas.resultados.pdf')->middleware('permission:generar pdf resultados');
    Route::get('/proformas/{id}/imprimir-resultados', [ResultadosController::class, 'imprimirResultados'])->name('proformas.informe-resultados-pdf')->middleware('permission:generar informe resultados');
    Route::get('/proformas/{id}/imprimir-permisibles/{tipo}', [ResultadosController::class, 'imprimirResultadosPermisibles'])->name('proformas.informe-permisibles-pdf')->middleware('permission:generar informe resultados');

    // INFORMES - Lectura
    Route::get('/informes', [InformeController::class, 'index'])->name('informes.index')->middleware('role:admin|tecnico|analista');
    Route::get('/informes/{informe}', [InformeController::class, 'show'])->name('informes.show')->middleware('permission:ver informes');
    Route::get('/informes/{informe}/pdf', [InformeController::class, 'pdf'])->name('informes.pdf')->middleware('permission:generar pdf informes');
    Route::get('/informes/{informe}/descargar/{tipo}', [InformeController::class, 'descargarArchivo'])->name('informes.descargar')->middleware('permission:descargar informes');

    // ========== RUTAS DEL MÓDULO FINANCIERO ==========
    Route::prefix('financiero')->name('financiero.')->group(function () {
        Route::get('/', [FinancieroController::class, 'index'])->name('index')->middleware('role:admin|tecnico|analista');
        Route::get('/cliente/{cliente}', [FinancieroController::class, 'cliente'])->name('cliente')->middleware('role:admin|tecnico|analista');
        Route::get('/exportar', [FinancieroController::class, 'exportar'])->name('exportar')->middleware('permission:exportar financiero');
    });
});

// ========== RUTA DE FALLBACK ==========
Route::fallback(function () {
    return redirect()->route('home')
        ->with('error', 'La pagina que buscas no existe.');
});

// ========== CADENA DE CUSTODIA ==========
Route::get('proformas/{proforma}/cadena-custodia', [ProformaController::class, 'pdfCadenaCustodia'])->name('proformas.cadena-custodia')->middleware('permission:generar cadena custodia');

// ========== REPORTE AMBIENTAL ==========
Route::prefix('proformas/{proforma}/reporte-ambiental')->name('reportes.ambiental.')->middleware('role:admin|tecnico|analista')->group(function () {
    Route::get('/', [App\Http\Controllers\ReporteAmbientalController::class, 'index'])->name('index');
    Route::get('/aire', [App\Http\Controllers\ReporteAmbientalController::class, 'aire'])->name('aire');
    Route::get('/ruido', [App\Http\Controllers\ReporteAmbientalController::class, 'ruido'])->name('ruido');
    Route::get('/gases', [App\Http\Controllers\ReporteAmbientalController::class, 'gases'])->name('gases');
    Route::post('/', [App\Http\Controllers\ReporteAmbientalController::class, 'store'])->name('store');
});
Route::get('/reportes-ambientales/{reporte}/pdf', [App\Http\Controllers\ReporteAmbientalController::class, 'pdf'])->name('reportes.ambiental.pdf')->middleware('role:admin|tecnico');
Route::get('/reportes-ambientales/{reporte}/pdf/aire', [App\Http\Controllers\ReporteAmbientalController::class, 'pdfAire'])->name('reportes.ambiental.pdf.aire')->middleware('role:admin|tecnico|analista');
Route::get('/reportes-ambientales/{reporte}/pdf/ruido', [App\Http\Controllers\ReporteAmbientalController::class, 'pdfRuido'])->name('reportes.ambiental.pdf.ruido')->middleware('role:admin|tecnico|analista');
Route::get('/reportes-ambientales/{reporte}/pdf/gases', [App\Http\Controllers\ReporteAmbientalController::class, 'pdfGases'])->name('reportes.ambiental.pdf.gases')->middleware('role:admin|tecnico|analista');
Route::get('/reportes-ambientales/{reporte}/descargar', [App\Http\Controllers\ReporteAmbientalController::class, 'downloadPdf'])->name('reportes.ambiental.download')->middleware('role:admin|tecnico|analista');
