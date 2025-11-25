<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

// ✅ CONTROLADORES DE DOCENTE
use App\Http\Controllers\Docente\DashboardController as DocenteDashboardController;
use App\Http\Controllers\Docente\AsistenciaController as DocenteAsistenciaController;
use App\Http\Controllers\Docente\NotaController as DocenteNotaController;
use App\Http\Controllers\Docente\ComportamientoController as DocenteComportamientoController;
use App\Http\Controllers\Docente\ReporteController as DocenteReporteController;

// ✅ CONTROLADORES DE TUTOR
use App\Http\Controllers\Tutor\DashboardController as TutorDashboardController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/run-seeder', function () {
    Artisan::call('db:seed', [
        '--class' => 'Database\\Seeders\\RolesPermisosSeeder',
        '--force' => true,
    ]);

    return 'Seeder ejecutado en producción ✅';
});

Route::get('/', function () {
    return view('welcome');
});

// ✅ RUTAS DEL BLOG PÚBLICO
Route::get('/blog', [App\Http\Controllers\Admin\Blog\BlogController::class, 'mostrarPublico'])->name('blog');
Route::get('/blog/{id}', [App\Http\Controllers\Admin\Blog\BlogController::class, 'mostrarDetalle'])->name('blog.detalle');

Route::get('/talleres', fn() => view('talleres'))->name('talleres');
Route::get('/cursos', fn() => view('cursos'))->name('cursos');
Route::get('/docentes', fn() => view('docentes'))->name('docentes');
Route::get('/tour', fn() => view('tour'))->name('tour');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS POR ROL (ACTUALIZADAS A MINÚSCULAS)
|--------------------------------------------------------------------------
*/

// ==========================================
// ADMINISTRADOR (solo rol 'administrador')
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:administrador'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\Sistema\DashboardController::class, 'index'])->name('dashboard');

    // Talleres
    Route::resource('talleres', App\Http\Controllers\Admin\TallerController::class)
        ->parameters(['talleres' => 'taller']);

    // ==========================================
    // ✅ BLOG - Gestión de Publicaciones
    // ==========================================
    Route::resource('blog', App\Http\Controllers\Admin\Blog\BlogController::class);

    // Configuración
    Route::resource('configuracion', App\Http\Controllers\Admin\Sistema\ConfiguracionController::class);
    Route::get('configuracion-global', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'global'])->name('configuracion.global');
    Route::post('configuracion-global', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'actualizarGlobal'])->name('configuracion.actualizar-global');
    Route::get('configuracion-restaurar', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'restaurarDefecto'])->name('configuracion.restaurar-defecto');
    Route::post('configuracion/{id}/valor', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'actualizarValor'])->name('configuracion.actualizar-valor');
    Route::get('configuracion/exportar', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'exportar'])->name('configuracion.exportar');
    Route::get('api/configuracion/categoria/{categoria}', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'porCategoria'])->name('configuracion.por-categoria');
    Route::get('api/configuracion/valor/{clave}', [App\Http\Controllers\Admin\Sistema\ConfiguracionController::class, 'obtenerValor'])->name('configuracion.obtener-valor');

    // Gestiones (sin método show)
    Route::resource('gestiones', App\Http\Controllers\Admin\Academico\GestionController::class)
        ->except(['show']);

    // Períodos
    Route::resource('periodos', App\Http\Controllers\Admin\Academico\PeriodoController::class);

    // Niveles
    Route::get('niveles', [App\Http\Controllers\Admin\Academico\NivelController::class, 'index'])->name('niveles.index');
    Route::post('niveles/create', [App\Http\Controllers\Admin\Academico\NivelController::class, 'store'])->name('niveles.store');
    Route::put('niveles/{id}', [App\Http\Controllers\Admin\Academico\NivelController::class, 'update'])->name('niveles.update');
    Route::delete('niveles/{id}', [App\Http\Controllers\Admin\Academico\NivelController::class, 'destroy'])->name('niveles.destroy');

    // Turnos
    Route::resource('turnos', App\Http\Controllers\Admin\Academico\TurnoController::class);

    // Horarios
    Route::resource('horarios', App\Http\Controllers\Admin\Procesos\HorarioController::class);

    // Personal
    Route::resource('docentes', App\Http\Controllers\Admin\Roles\DocenteController::class);
    Route::resource('tutores', App\Http\Controllers\Admin\Roles\TutorController::class)->parameters(['tutores' => 'id']);
    Route::resource('administradores', App\Http\Controllers\Admin\Roles\AdministradorController::class)->parameters(['administradores' => 'id']);
    Route::post('administradores/{id}/activar', [App\Http\Controllers\Admin\Roles\AdministradorController::class, 'activar'])->name('administradores.activar');
    Route::post('administradores/{id}/desactivar', [App\Http\Controllers\Admin\Roles\AdministradorController::class, 'desactivar'])->name('administradores.desactivar');

    // Estudiantes
    Route::resource('estudiantes', App\Http\Controllers\Admin\Roles\EstudianteController::class);

    // Cursos y Grados
    Route::resource('cursos', App\Http\Controllers\Admin\Academico\CursoController::class);
    Route::resource('grados', App\Http\Controllers\Admin\Academico\GradoController::class);

    // Asignaciones
    Route::get('Asignaciones', [App\Http\Controllers\Admin\Procesos\AsignacionDocenteController::class, 'index'])->name('Asignaciones.index');
    Route::get('Asignaciones/{id}', [App\Http\Controllers\Admin\Procesos\AsignacionDocenteController::class, 'show'])->name('Asignaciones.show');
    Route::post('Asignaciones/create', [App\Http\Controllers\Admin\Procesos\AsignacionDocenteController::class, 'store'])->name('Asignaciones.store');
    Route::put('Asignaciones/{id}', [App\Http\Controllers\Admin\Procesos\AsignacionDocenteController::class, 'update'])->name('Asignaciones.update');
    Route::delete('Asignaciones/{id}', [App\Http\Controllers\Admin\Procesos\AsignacionDocenteController::class, 'destroy'])->name('Asignaciones.destroy');

    // Matrículas
    Route::get('matriculas', [App\Http\Controllers\Admin\Procesos\MatriculaController::class, 'index'])->name('matriculas.index');
    Route::get('matriculas/{id}', [App\Http\Controllers\Admin\Procesos\MatriculaController::class, 'show'])->name('matriculas.show');
    Route::post('matriculas/create', [App\Http\Controllers\Admin\Procesos\MatriculaController::class, 'store'])->name('matriculas.store');
    Route::put('matriculas/{id}', [App\Http\Controllers\Admin\Procesos\MatriculaController::class, 'update'])->name('matriculas.update');
    Route::delete('matriculas/{id}', [App\Http\Controllers\Admin\Procesos\MatriculaController::class, 'destroy'])->name('matriculas.destroy');

    // Tutor-Estudiante
    Route::get('tutor-estudiante', [App\Http\Controllers\Admin\Registros\TutorEstudianteController::class, 'index'])->name('tutor-estudiante.index');
    Route::get('tutor-estudiante/{id}', [App\Http\Controllers\Admin\Registros\TutorEstudianteController::class, 'show'])->name('tutor-estudiante.show');
    Route::post('tutor-estudiante/create', [App\Http\Controllers\Admin\Registros\TutorEstudianteController::class, 'store'])->name('tutor-estudiante.store');
    Route::put('tutor-estudiante/{id}', [App\Http\Controllers\Admin\Registros\TutorEstudianteController::class, 'update'])->name('tutor-estudiante.update');
    Route::delete('tutor-estudiante/{id}', [App\Http\Controllers\Admin\Registros\TutorEstudianteController::class, 'destroy'])->name('tutor-estudiante.destroy');

    // Asistencias
    Route::get('asistencias', [App\Http\Controllers\Admin\Registros\AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::get('asistencias/{id}', [App\Http\Controllers\Admin\Registros\AsistenciaController::class, 'show'])->name('asistencias.show');
    Route::post('asistencias/create', [App\Http\Controllers\Admin\Registros\AsistenciaController::class, 'store'])->name('asistencias.store');
    Route::put('asistencias/{id}', [App\Http\Controllers\Admin\Registros\AsistenciaController::class, 'update'])->name('asistencias.update');
    Route::delete('asistencias/{id}', [App\Http\Controllers\Admin\Registros\AsistenciaController::class, 'destroy'])->name('asistencias.destroy');
    Route::post('asistencias/registro-masivo', [App\Http\Controllers\Admin\Registros\AsistenciaController::class, 'registroMasivo'])->name('asistencias.registro-masivo');

    // Notas
    Route::get('notas', [App\Http\Controllers\Admin\Registros\NotaController::class, 'index'])->name('notas.index');
    Route::get('notas/{id}', [App\Http\Controllers\Admin\Registros\NotaController::class, 'show'])->name('notas.show');
    Route::post('notas/create', [App\Http\Controllers\Admin\Registros\NotaController::class, 'store'])->name('notas.store');
    Route::put('notas/{id}', [App\Http\Controllers\Admin\Registros\NotaController::class, 'update'])->name('notas.update');
    Route::delete('notas/{id}', [App\Http\Controllers\Admin\Registros\NotaController::class, 'destroy'])->name('notas.destroy');
    Route::post('notas/{id}/publicar', [App\Http\Controllers\Admin\Registros\NotaController::class, 'publicar'])->name('notas.publicar');
    Route::post('notas/{id}/despublicar', [App\Http\Controllers\Admin\Registros\NotaController::class, 'despublicar'])->name('notas.despublicar');

    // Comportamientos
    Route::get('comportamientos', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'index'])->name('comportamientos.index');
    Route::get('comportamientos/{id}', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'show'])->name('comportamientos.show');
    Route::post('comportamientos/create', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'store'])->name('comportamientos.store');
    Route::put('comportamientos/{id}', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'update'])->name('comportamientos.update');
    Route::delete('comportamientos/{id}', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'destroy'])->name('comportamientos.destroy');
    Route::post('comportamientos/{id}/notificar', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'notificar'])->name('comportamientos.notificar');
    Route::post('comportamientos/{id}/cancelar-notificacion', [App\Http\Controllers\Admin\Registros\ComportamientoController::class, 'cancelarNotificacion'])->name('comportamientos.cancelar-notificacion');

    // Reportes
    Route::get('reportes', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/{id}', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'show'])->name('reportes.show');
    Route::post('reportes/create', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'store'])->name('reportes.store');
    Route::put('reportes/{id}', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'update'])->name('reportes.update');
    Route::delete('reportes/{id}', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'destroy'])->name('reportes.destroy');
    Route::post('reportes/{id}/publicar', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'publicar'])->name('reportes.publicar');
    Route::post('reportes/{id}/despublicar', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'despublicar'])->name('reportes.despublicar');
    Route::post('reportes/{id}/calcular-datos', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'calcularDatos'])->name('reportes.calcular-datos');
    Route::get('reportes/{id}/descargar-pdf', [App\Http\Controllers\Admin\Registros\ReporteController::class, 'descargarPdf'])->name('reportes.descargar-pdf');

    // Permisos
    Route::get('permissions', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/{id}', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'show'])->name('permissions.show');
    Route::post('permissions/create', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'store'])->name('permissions.store');
    Route::put('permissions/{id}', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('permissions/{id}', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::post('permissions/{id}/asignar-rol', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'asignarRol'])->name('permissions.asignar-rol');
    Route::post('permissions/{id}/remover-rol', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'removerRol'])->name('permissions.remover-rol');
    Route::post('permissions/crear-crud', [App\Http\Controllers\Admin\Roles\PermissionController::class, 'crearCRUD'])->name('permissions.crear-crud');

    // Roles
    Route::get('roles', [App\Http\Controllers\Admin\Roles\RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{id}', [App\Http\Controllers\Admin\Roles\RoleController::class, 'show'])->name('roles.show');
    Route::post('roles/create', [App\Http\Controllers\Admin\Roles\RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{id}', [App\Http\Controllers\Admin\Roles\RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [App\Http\Controllers\Admin\Roles\RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('roles/{id}/asignar-permiso', [App\Http\Controllers\Admin\Roles\RoleController::class, 'asignarPermiso'])->name('roles.asignar-permiso');
    Route::post('roles/{id}/remover-permiso', [App\Http\Controllers\Admin\Roles\RoleController::class, 'removerPermiso'])->name('roles.remover-permiso');
    Route::post('roles/{id}/asignar-usuario', [App\Http\Controllers\Admin\Roles\RoleController::class, 'asignarUsuario'])->name('roles.asignar-usuario');
    Route::post('roles/{id}/remover-usuario', [App\Http\Controllers\Admin\Roles\RoleController::class, 'removerUsuario'])->name('roles.remover-usuario');

    // Usuarios
    Route::get('usuarios', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/{id}', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'show'])->name('usuarios.show');
    Route::post('usuarios/create', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'store'])->name('usuarios.store');
    Route::put('usuarios/{id}', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'update'])->name('usuarios.update');
    Route::delete('usuarios/{id}', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::post('usuarios/{id}/verificar-email', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'verificarEmail'])->name('usuarios.verificar-email');
    Route::post('usuarios/{id}/quitar-verificacion', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'quitarVerificacionEmail'])->name('usuarios.quitar-verificacion');
    Route::post('usuarios/{id}/cambiar-password', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'cambiarPassword'])->name('usuarios.cambiar-password');
    Route::post('usuarios/{id}/activar', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'activar'])->name('usuarios.activar');
    Route::post('usuarios/{id}/desactivar', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'desactivar'])->name('usuarios.desactivar');
    Route::post('usuarios/{id}/vincular-persona', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'vincularPersona'])->name('usuarios.vincular-persona');
    Route::post('usuarios/{id}/desvincular-persona', [App\Http\Controllers\Admin\Seguridad\UserController::class, 'desvincularPersona'])->name('usuarios.desvincular-persona');

    // Carta de presentación
    Route::get('carta', [App\Http\Controllers\CartaController::class, 'index'])->name('carta.index');
});


// ==========================================
// DOCENTE (usa 'docente' en minúscula)
// ==========================================
Route::prefix('docente')->name('docente.')->middleware(['auth', 'role:docente'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Docente\DashboardController::class, 'index'])->name('dashboard');

    // Mis Cursos
    Route::get('/mis-cursos', function () {
        return view('docente.mis-cursos');
    })->name('mis-cursos');

    // ==========================================
    // MIS ESTUDIANTES (filtrados por cursos del docente)
    // ==========================================
    Route::get('/estudiantes', [App\Http\Controllers\Docente\MisEstudiantesController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/{id}', [App\Http\Controllers\Docente\MisEstudiantesController::class, 'show'])->name('estudiantes.show');

    // ==========================================
    // ASISTENCIAS (con controlador - filtradas por docente)
    // ==========================================
    Route::get('/asistencias', [App\Http\Controllers\Docente\AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::post('/asistencias', [App\Http\Controllers\Docente\AsistenciaController::class, 'store'])->name('asistencias.store');
    Route::get('/asistencias/{id}', [App\Http\Controllers\Docente\AsistenciaController::class, 'show'])->name('asistencias.show');
    Route::put('/asistencias/{id}', [App\Http\Controllers\Docente\AsistenciaController::class, 'update'])->name('asistencias.update');
    Route::delete('/asistencias/{id}', [App\Http\Controllers\Docente\AsistenciaController::class, 'destroy'])->name('asistencias.destroy');
    Route::post('/asistencias/registro-masivo', [App\Http\Controllers\Docente\AsistenciaController::class, 'registroMasivo'])->name('asistencias.registro-masivo');

    // ==========================================
    // NOTAS (con controlador - filtradas por docente)
    // ==========================================
    Route::get('/notas', [App\Http\Controllers\Docente\NotaController::class, 'index'])->name('notas.index');
    Route::post('/notas', [App\Http\Controllers\Docente\NotaController::class, 'store'])->name('notas.store');
    Route::get('/notas/{id}', [App\Http\Controllers\Docente\NotaController::class, 'show'])->name('notas.show');
    Route::put('/notas/{id}', [App\Http\Controllers\Docente\NotaController::class, 'update'])->name('notas.update');
    Route::delete('/notas/{id}', [App\Http\Controllers\Docente\NotaController::class, 'destroy'])->name('notas.destroy');
    Route::post('/notas/{id}/publicar', [App\Http\Controllers\Docente\NotaController::class, 'publicar'])->name('notas.publicar');
    Route::post('/notas/{id}/despublicar', [App\Http\Controllers\Docente\NotaController::class, 'despublicar'])->name('notas.despublicar');

    // ==========================================
    // COMPORTAMIENTOS (con controlador - filtrados por docente)
    // ==========================================
    Route::get('/comportamientos', [App\Http\Controllers\Docente\ComportamientoController::class, 'index'])->name('comportamientos.index');
    Route::post('/comportamientos', [App\Http\Controllers\Docente\ComportamientoController::class, 'store'])->name('comportamientos.store');
    Route::get('/comportamientos/{id}', [App\Http\Controllers\Docente\ComportamientoController::class, 'show'])->name('comportamientos.show');
    Route::put('/comportamientos/{id}', [App\Http\Controllers\Docente\ComportamientoController::class, 'update'])->name('comportamientos.update');
    Route::delete('/comportamientos/{id}', [App\Http\Controllers\Docente\ComportamientoController::class, 'destroy'])->name('comportamientos.destroy');
    Route::post('/comportamientos/{id}/notificar', [App\Http\Controllers\Docente\ComportamientoController::class, 'notificar'])->name('comportamientos.notificar');
    Route::post('/comportamientos/{id}/cancelar-notificacion', [App\Http\Controllers\Docente\ComportamientoController::class, 'cancelarNotificacion'])->name('comportamientos.cancelar-notificacion');

    // ==========================================
    // REPORTES (con controlador - filtrados por docente)
    // ==========================================
    Route::get('/reportes', [App\Http\Controllers\Docente\ReporteController::class, 'index'])->name('reportes.index');
    Route::post('/reportes', [App\Http\Controllers\Docente\ReporteController::class, 'store'])->name('reportes.store');
    Route::get('/reportes/{id}', [App\Http\Controllers\Docente\ReporteController::class, 'show'])->name('reportes.show');
    Route::put('/reportes/{id}', [App\Http\Controllers\Docente\ReporteController::class, 'update'])->name('reportes.update');
    Route::delete('/reportes/{id}', [App\Http\Controllers\Docente\ReporteController::class, 'destroy'])->name('reportes.destroy');
    Route::post('/reportes/{id}/publicar', [App\Http\Controllers\Docente\ReporteController::class, 'publicar'])->name('reportes.publicar');
    Route::post('/reportes/{id}/despublicar', [App\Http\Controllers\Docente\ReporteController::class, 'despublicar'])->name('reportes.despublicar');
    Route::get('/reportes/{id}/descargar-pdf', [App\Http\Controllers\Docente\ReporteController::class, 'descargarPdf'])->name('reportes.descargar-pdf');
    Route::post('/reportes/{id}/calcular-datos', [App\Http\Controllers\Docente\ReporteController::class, 'calcularDatos'])->name('reportes.calcular-datos');

    // ==========================================
    // 🆕 NUEVAS FUNCIONALIDADES ACADÉMICAS
    // ==========================================

    // Mis Alumnos
    Route::get('/mis-alumnos', [App\Http\Controllers\Docente\DocenteAcademicoController::class, 'misAlumnos'])->name('mis-alumnos');

    // CUS24 - Ficha de Alumno
    Route::get('/alumno/{id}/ficha', [App\Http\Controllers\Docente\DocenteAcademicoController::class, 'fichaAlumno'])->name('alumno.ficha');

    // CUS26 - Mensajería con Tutores
    Route::get('/mensajeria', [App\Http\Controllers\Docente\DocenteAcademicoController::class, 'mensajes'])->name('mensajeria');
    Route::post('/mensajeria/enviar', [App\Http\Controllers\Docente\DocenteAcademicoController::class, 'enviarMensaje'])->name('mensajeria.enviar');
    Route::post('/mensajeria/{id}/responder', [App\Http\Controllers\Docente\DocenteAcademicoController::class, 'responderMensaje'])->name('mensajeria.responder');
    Route::get('/mensajeria/{id}', [App\Http\Controllers\Docente\DocenteAcademicoController::class, 'verMensaje'])->name('mensajeria.ver');
});

// ==========================================
// TUTOR (usa 'tutor' en minúscula)
// ==========================================
Route::prefix('tutor')->name('tutor.')->middleware(['auth', 'role:tutor'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [TutorDashboardController::class, 'index'])->name('dashboard');

    // Mis Estudiantes
    Route::get('/mis-estudiantes', function () {
        return view('tutor.mis-estudiantes');
    })->name('mis-estudiantes');

    // Notas de mis Estudiantes (solo lectura)
    Route::get('/notas', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'notas'])->name('notas');

    // Asistencias de mis Estudiantes (solo lectura)
    Route::get('/asistencias', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'asistencias'])->name('asistencias');

    // ==========================================
    // COMPORTAMIENTOS (solo lectura)
    // ==========================================
    Route::get('/comportamientos', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'comportamientos'])->name('comportamientos');

    // ==========================================
    // REPORTES (con controlador - vista completa)
    // ==========================================

    Route::get('/reportes', [App\Http\Controllers\Tutor\ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/{id}', [App\Http\Controllers\Tutor\ReporteController::class, 'show'])->name('reportes.show');
    Route::get('/reportes/{id}/descargar-pdf', [App\Http\Controllers\Tutor\ReporteController::class, 'descargarPdf'])->name('reportes.descargar-pdf');

    // ==========================================
    // 🆕 NUEVAS FUNCIONALIDADES
    // ==========================================

    // CUS14 - Consultar Horarios
    Route::get('/horarios', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'horarios'])->name('horarios');

    // CUS4 - Consultar Cursos Matriculados
    Route::get('/cursos-matriculados', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'cursos'])->name('cursos-matriculados');

    // CUS19 - Mensajería con Docentes
    Route::get('/mensajeria', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'mensajes'])->name('mensajeria');
    Route::post('/mensajeria/enviar', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'enviarMensaje'])->name('mensajeria.enviar');
    Route::get('/mensajeria/{id}', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'verMensaje'])->name('mensajeria.ver');
    Route::post('/mensajeria/{id}/responder', [App\Http\Controllers\Tutor\TutorAcademicoController::class, 'responderMensaje'])->name('mensajeria.responder');
});

// ==========================================
// FALLBACK - Redirigir /home según rol
// ==========================================
Route::get('/home', function () {
    $user = Auth::user();

    // Verificación básica de autenticación
    if (!$user) {
        return redirect()->route('login');
    }

    // Redirecciones según rol (nombres exactos de la BD)
    if ($user->tieneRol('administrador')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->tieneRol('docente')) {
        return redirect()->route('docente.dashboard');
    }
    if ($user->tieneRol('tutor')) {
        return redirect()->route('tutor.dashboard');
    }

    // Si no tiene ningún rol, redirigir al login con mensaje
    return redirect()->route('login')
        ->with('mensaje', 'Tu cuenta no tiene un rol asignado. Contacta al administrador.')
        ->with('icono', 'warning');
})->middleware('auth')->name('home');


// ==========================================
// DIAGNÓSTICO (BORRAR DESPUÉS DE VERIFICAR)
// ==========================================
Route::get('/diagnostico-menu', function () {
    if (!Auth::check()) {
        return 'Usuario no autenticado. Por favor inicia sesión primero.';
    }

    $user = Auth::user();

    return response()->json([
        'usuario' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'roles' => [
            'count' => $user->roles->count(),
            'nombres' => $user->roles->pluck('name')->toArray(),
        ],
        'verificaciones' => [
            'tieneRol_administrador' => $user->tieneRol('administrador'),
            'tieneRol_docente'       => $user->tieneRol('docente'),
            'tieneRol_tutor'         => $user->tieneRol('tutor'),
        ],
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('web');
