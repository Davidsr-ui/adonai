<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Persona;
use App\Models\Administrador;

class RolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $administrador = Role::create([
            'name'         => 'administrador',     // <--- SLUG del rol
            'display_name' => 'Administrador',
            'description'  => 'Acceso total al sistema',
        ]);

        $docente = Role::create([
            'name'         => 'docente',
            'display_name' => 'Docente',
            'description'  => 'Acceso para docentes',
        ]);

        $tutor = Role::create([
            'name'         => 'tutor',
            'display_name' => 'Tutor',
            'description'  => 'Acceso para tutores',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PERMISOS
        |--------------------------------------------------------------------------
        */

        $permisos = [];

        // === MÓDULO: DASHBOARD ===
        $permisos[] = Permission::create([
            'name'         => 'dashboard.view',
            'display_name' => 'Ver Dashboard',
            'description'  => 'Acceso al panel principal',
            'module'       => 'dashboard'
        ]);

        // === MÓDULO: CONFIGURACIÓN ===
        $permisos[] = Permission::create([
            'name'         => 'configuracion.view',
            'display_name' => 'Ver Configuración',
            'description'  => 'Acceso a configuración del sistema',
            'module'       => 'configuracion'
        ]);

        // === MÓDULO: GESTIONES ===
        $permisos[] = Permission::create([
            'name'         => 'gestiones.view',
            'display_name' => 'Ver Gestiones',
            'description'  => 'Ver listado de gestiones',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'gestiones.create',
            'display_name' => 'Crear Gestiones',
            'description'  => 'Crear nuevas gestiones',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'gestiones.edit',
            'display_name' => 'Editar Gestiones',
            'description'  => 'Editar gestiones existentes',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'gestiones.delete',
            'display_name' => 'Eliminar Gestiones',
            'description'  => 'Eliminar gestiones',
            'module'       => 'academico'
        ]);

        // === MÓDULO: PERÍODOS ===
        $permisos[] = Permission::create([
            'name'         => 'periodos.view',
            'display_name' => 'Ver Períodos',
            'description'  => 'Ver listado de períodos',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'periodos.create',
            'display_name' => 'Crear Períodos',
            'description'  => 'Crear nuevos períodos',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'periodos.edit',
            'display_name' => 'Editar Períodos',
            'description'  => 'Editar períodos existentes',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'periodos.delete',
            'display_name' => 'Eliminar Períodos',
            'description'  => 'Eliminar períodos',
            'module'       => 'academico'
        ]);

        // === MÓDULO: NIVELES ===
        $permisos[] = Permission::create([
            'name'         => 'niveles.view',
            'display_name' => 'Ver Niveles',
            'description'  => 'Ver listado de niveles',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'niveles.create',
            'display_name' => 'Crear Niveles',
            'description'  => 'Crear nuevos niveles',
            'module'       => 'academico'
        ]);

        // === MÓDULO: TURNOS ===
        $permisos[] = Permission::create([
            'name'         => 'turnos.view',
            'display_name' => 'Ver Turnos',
            'description'  => 'Ver listado de turnos',
            'module'       => 'academico'
        ]);

        // === MÓDULO: HORARIOS ===
        $permisos[] = Permission::create([
            'name'         => 'horarios.view',
            'display_name' => 'Ver Horarios',
            'description'  => 'Ver listado de horarios',
            'module'       => 'academico'
        ]);

        // === MÓDULO: DOCENTES ===
        $permisos[] = Permission::create([
            'name'         => 'docentes.view',
            'display_name' => 'Ver Docentes',
            'description'  => 'Ver listado de docentes',
            'module'       => 'personal'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'docentes.create',
            'display_name' => 'Crear Docentes',
            'description'  => 'Registrar nuevos docentes',
            'module'       => 'personal'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'docentes.edit',
            'display_name' => 'Editar Docentes',
            'description'  => 'Editar datos de docentes',
            'module'       => 'personal'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'docentes.delete',
            'display_name' => 'Eliminar Docentes',
            'description'  => 'Eliminar docentes',
            'module'       => 'personal'
        ]);

        // === MÓDULO: TUTORES ===
        $permisos[] = Permission::create([
            'name'         => 'tutores.view',
            'display_name' => 'Ver Tutores',
            'description'  => 'Ver listado de tutores',
            'module'       => 'personal'
        ]);

        // === MÓDULO: ESTUDIANTES ===
        $permisos[] = Permission::create([
            'name'         => 'estudiantes.view',
            'display_name' => 'Ver Estudiantes',
            'description'  => 'Ver listado de estudiantes',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'estudiantes.create',
            'display_name' => 'Crear Estudiantes',
            'description'  => 'Registrar nuevos estudiantes',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'estudiantes.edit',
            'display_name' => 'Editar Estudiantes',
            'description'  => 'Editar datos de estudiantes',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'estudiantes.delete',
            'display_name' => 'Eliminar Estudiantes',
            'description'  => 'Eliminar estudiantes',
            'module'       => 'estudiantes'
        ]);

        // === MÓDULO: CURSOS ===
        $permisos[] = Permission::create([
            'name'         => 'cursos.view',
            'display_name' => 'Ver Cursos',
            'description'  => 'Ver listado de cursos',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'cursos.create',
            'display_name' => 'Crear Cursos',
            'description'  => 'Crear nuevos cursos',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'cursos.edit',
            'display_name' => 'Editar Cursos',
            'description'  => 'Editar cursos existentes',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'cursos.delete',
            'display_name' => 'Eliminar Cursos',
            'description'  => 'Eliminar cursos',
            'module'       => 'academico'
        ]);

        // === MÓDULO: GRADOS ===
        $permisos[] = Permission::create([
            'name'         => 'grados.view',
            'display_name' => 'Ver Grados',
            'description'  => 'Ver listado de grados',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'grados.create',
            'display_name' => 'Crear Grados',
            'description'  => 'Crear nuevos grados',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'grados.edit',
            'display_name' => 'Editar Grados',
            'description'  => 'Editar grados existentes',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'grados.delete',
            'display_name' => 'Eliminar Grados',
            'description'  => 'Eliminar grados',
            'module'       => 'academico'
        ]);

        // === MÓDULO: ASIGNACIÓN DOCENTES ===
        $permisos[] = Permission::create([
            'name'         => 'asignacion-docentes.view',
            'display_name' => 'Ver Asignación Docentes',
            'description'  => 'Ver asignaciones de docentes a cursos',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'asignacion-docentes.create',
            'display_name' => 'Crear Asignación Docentes',
            'description'  => 'Asignar docentes a cursos',
            'module'       => 'academico'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'asignacion-docentes.delete',
            'display_name' => 'Eliminar Asignación Docentes',
            'description'  => 'Eliminar asignaciones de docentes',
            'module'       => 'academico'
        ]);

        // === MÓDULO: MATRÍCULAS ===
        $permisos[] = Permission::create([
            'name'         => 'matriculas.view',
            'display_name' => 'Ver Matrículas',
            'description'  => 'Ver listado de matrículas',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'matriculas.create',
            'display_name' => 'Crear Matrículas',
            'description'  => 'Matricular estudiantes',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'matriculas.edit',
            'display_name' => 'Editar Matrículas',
            'description'  => 'Editar matrículas existentes',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'matriculas.delete',
            'display_name' => 'Eliminar Matrículas',
            'description'  => 'Eliminar matrículas',
            'module'       => 'estudiantes'
        ]);

        // === MÓDULO: TUTOR-ESTUDIANTE ===
        $permisos[] = Permission::create([
            'name'         => 'tutor-estudiante.view',
            'display_name' => 'Ver Tutor-Estudiante',
            'description'  => 'Ver relaciones tutor-estudiante',
            'module'       => 'estudiantes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'tutor-estudiante.create',
            'display_name' => 'Asignar Tutor-Estudiante',
            'description'  => 'Asignar tutores a estudiantes',
            'module'       => 'estudiantes'
        ]);

        // === MÓDULO: ASISTENCIAS ===
        $permisos[] = Permission::create([
            'name'         => 'asistencias.view',
            'display_name' => 'Ver Asistencias',
            'description'  => 'Ver registro de asistencias',
            'module'       => 'docentes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'asistencias.create',
            'display_name' => 'Registrar Asistencias',
            'description'  => 'Registrar asistencias de estudiantes',
            'module'       => 'docentes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'asistencias.edit',
            'display_name' => 'Editar Asistencias',
            'description'  => 'Editar registros de asistencias',
            'module'       => 'docentes'
        ]);

        // === MÓDULO: NOTAS ===
        $permisos[] = Permission::create([
            'name'         => 'notas.view',
            'display_name' => 'Ver Notas',
            'description'  => 'Ver calificaciones de estudiantes',
            'module'       => 'docentes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'notas.create',
            'display_name' => 'Registrar Notas',
            'description'  => 'Registrar calificaciones',
            'module'       => 'docentes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'notas.edit',
            'display_name' => 'Editar Notas',
            'description'  => 'Editar calificaciones',
            'module'       => 'docentes'
        ]);

        // === MÓDULO: COMPORTAMIENTOS ===
        $permisos[] = Permission::create([
            'name'         => 'comportamientos.view',
            'display_name' => 'Ver Comportamientos',
            'description'  => 'Ver evaluaciones de comportamiento',
            'module'       => 'docentes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'comportamientos.create',
            'display_name' => 'Registrar Comportamientos',
            'description'  => 'Registrar evaluaciones de comportamiento',
            'module'       => 'docentes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'comportamientos.edit',
            'display_name' => 'Editar Comportamientos',
            'description'  => 'Editar evaluaciones de comportamiento',
            'module'       => 'docentes'
        ]);

        // === MÓDULO: REPORTES ===
        $permisos[] = Permission::create([
            'name'         => 'reportes.view',
            'display_name' => 'Ver Reportes',
            'description'  => 'Acceso a módulo de reportes',
            'module'       => 'reportes'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'reportes.generate',
            'display_name' => 'Generar Reportes',
            'description'  => 'Generar reportes del sistema',
            'module'       => 'reportes'
        ]);

        // === MÓDULO: ADMINISTRADORES ===
        $permisos[] = Permission::create([
            'name'         => 'administradores.view',
            'display_name' => 'Ver Administradores',
            'description'  => 'Ver listado de administradores',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'administradores.create',
            'display_name' => 'Crear Administradores',
            'description'  => 'Registrar nuevos administradores',
            'module'       => 'administracion'
        ]);

        // === MÓDULO: PERMISOS ===
        $permisos[] = Permission::create([
            'name'         => 'permisos.view',
            'display_name' => 'Ver Permisos',
            'description'  => 'Ver listado de permisos',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'permisos.create',
            'display_name' => 'Crear Permisos',
            'description'  => 'Crear nuevos permisos',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'permisos.edit',
            'display_name' => 'Editar Permisos',
            'description'  => 'Editar permisos existentes',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'permisos.delete',
            'display_name' => 'Eliminar Permisos',
            'description'  => 'Eliminar permisos',
            'module'       => 'administracion'
        ]);

        // === MÓDULO: ROLES ===
        $permisos[] = Permission::create([
            'name'         => 'roles.view',
            'display_name' => 'Ver Roles',
            'description'  => 'Ver listado de roles',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'roles.create',
            'display_name' => 'Crear Roles',
            'description'  => 'Crear nuevos roles',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'roles.edit',
            'display_name' => 'Editar Roles',
            'description'  => 'Editar roles existentes',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'roles.delete',
            'display_name' => 'Eliminar Roles',
            'description'  => 'Eliminar roles',
            'module'       => 'administracion'
        ]);

        // === MÓDULO: USUARIOS ===
        $permisos[] = Permission::create([
            'name'         => 'usuarios.view',
            'display_name' => 'Ver Usuarios',
            'description'  => 'Ver listado de usuarios',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'usuarios.create',
            'display_name' => 'Crear Usuarios',
            'description'  => 'Crear nuevos usuarios',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'usuarios.edit',
            'display_name' => 'Editar Usuarios',
            'description'  => 'Editar usuarios existentes',
            'module'       => 'administracion'
        ]);
        $permisos[] = Permission::create([
            'name'         => 'usuarios.delete',
            'display_name' => 'Eliminar Usuarios',
            'description'  => 'Eliminar usuarios',
            'module'       => 'administracion'
        ]);

        /*
        |--------------------------------------------------------------------------
        | ASIGNAR PERMISOS A ROLES
        |--------------------------------------------------------------------------
        */

        // ADMINISTRADOR: todos los permisos
        $todosPermisos = Permission::all()->pluck('id');
        $administrador->permissions()->sync($todosPermisos);

        // DOCENTE: permisos específicos
        $permisosDocente = Permission::whereIn('name', [
            'dashboard.view',
            'asistencias.view',
            'asistencias.create',
            'asistencias.edit',
            'notas.view',
            'notas.create',
            'notas.edit',
            'comportamientos.view',
            'comportamientos.create',
            'comportamientos.edit',
            'reportes.view',
            'estudiantes.view',
        ])->pluck('id');
        $docente->permissions()->sync($permisosDocente);

        // TUTOR: permisos más limitados
        $permisosTutor = Permission::whereIn('name', [
            'dashboard.view',
            'tutor-estudiante.view',
            'asistencias.view',
            'notas.view',
            'reportes.view',
            'estudiantes.view',
        ])->pluck('id');
        $tutor->permissions()->sync($permisosTutor);

        /*
        |--------------------------------------------------------------------------
        | USUARIO ADMINISTRADOR POR DEFECTO
        |--------------------------------------------------------------------------
        */

        // Usuario (tabla users)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@colegioadonai.edu.pe'],
            [
                'name'     => 'Administrador General',
                'password' => Hash::make('Admin123*'), // cámbiala luego en producción
            ]
        );

        // Persona vinculada
        $personaAdmin = Persona::firstOrCreate(
            ['dni' => '00000000'],
            [
                'user_id'             => $adminUser->id,
                'nombres'             => 'Administrador',
                'apellidos'           => 'General',
                'fecha_nacimiento'    => '1990-01-01',
                'genero'              => 'M',
                'direccion'           => 'Trujillo',
                'telefono'            => '000000000',
                'telefono_emergencia' => null,
                'estado'              => 'Activo',
            ]
        );

        // Registro en tabla administradores
        Administrador::firstOrCreate(
            ['persona_id' => $personaAdmin->id],
            [
                'cargo'            => 'Director',
                'area'             => 'Dirección',
                'fecha_asignacion' => now(),
            ]
        );

        // Asignar rol administrador al usuario
        if (method_exists($adminUser, 'roles')) {
            $adminUser->roles()->syncWithoutDetaching([$administrador->id]);
        }

        $this->command?->info('✅ Roles, permisos y usuario administrador creados.');
    }
}
