<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Persona;
use App\Models\Administrador;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. LIMPIEZA DE CACHÉ (Vital para evitar errores 403)
        Artisan::call('permission:cache-reset');

        /*
        |--------------------------------------------------------------------------
        | 2. DEFINICIÓN DE ROLES
        |--------------------------------------------------------------------------
        | NOTA: Agregamos 'guard_name' => 'web' para evitar el error de base de datos anterior.
        */
        $rolAdmin   = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'], ['display_name' => 'Administrador']);
        $rolDocente = Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'web'], ['display_name' => 'Docente']);
        $rolTutor   = Role::firstOrCreate(['name' => 'tutor', 'guard_name' => 'web'], ['display_name' => 'Tutor']);

        /*
        |--------------------------------------------------------------------------
        | 3. GENERACIÓN AUTOMÁTICA DE PERMISOS
        |--------------------------------------------------------------------------
        */
        
        // A. Módulos con CRUD Completo
        $modulosCrudCompleto = [
            'gestiones', 'periodos', 'niveles', 'grados', 'turnos', 
            'cursos', 'asignacion-docentes',                        
            'estudiantes', 'matriculas', 'tutor-estudiante',        
            'docentes', 'tutores', 'administradores',               
            'usuarios', 'roles', 'permisos'                         
        ];

        foreach ($modulosCrudCompleto as $modulo) {
            // Usamos una función auxiliar para no repetir código y asegurar el guard_name
            $this->crearPermiso("$modulo.view", "Ver $modulo", $modulo);
            $this->crearPermiso("$modulo.create", "Crear $modulo", $modulo);
            $this->crearPermiso("$modulo.edit", "Editar $modulo", $modulo);
            $this->crearPermiso("$modulo.delete", "Eliminar $modulo", $modulo);
        }

        // B. Módulos solo operativos
        $modulosOperativos = ['notas', 'asistencias', 'comportamientos'];
        
        foreach ($modulosOperativos as $modulo) {
            $this->crearPermiso("$modulo.view", "Ver $modulo", $modulo);
            $this->crearPermiso("$modulo.create", "Registrar $modulo", $modulo);
            $this->crearPermiso("$modulo.edit", "Editar $modulo", $modulo);
        }

        // C. Módulos únicos
        $this->crearPermiso('dashboard.view', 'Ver Dashboard', 'dashboard');
        $this->crearPermiso('configuracion.view', 'Ver Configuración', 'configuracion');
        $this->crearPermiso('reportes.view', 'Ver Reportes', 'reportes');
        $this->crearPermiso('horarios.view', 'Ver Horarios', 'academico');

        /*
        |--------------------------------------------------------------------------
        | 4. ASIGNACIÓN DE PERMISOS A ROLES
        |--------------------------------------------------------------------------
        */

        // ADMIN: Tiene todo
        $rolAdmin->syncPermissions(Permission::all());

        // DOCENTE
        $rolDocente->syncPermissions([
            'dashboard.view', 'horarios.view', 'estudiantes.view',
            'asistencias.view', 'asistencias.create', 'asistencias.edit',
            'notas.view', 'notas.create', 'notas.edit',
            'comportamientos.view', 'comportamientos.create', 'comportamientos.edit',
        ]);

        // TUTOR
        $rolTutor->syncPermissions([
            'dashboard.view', 'horarios.view',
            'estudiantes.view', 'tutor-estudiante.view',
            'notas.view', 'asistencias.view', 'comportamientos.view'
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. USUARIO ADMINISTRADOR (Tu lógica personalizada)
        |--------------------------------------------------------------------------
        */
        
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@adonai.edu.pe'],
            [
                'name'              => 'Administrador General',
                'password'          => Hash::make('Admin123'), // Asegúrate que esta sea la clave que quieres
                'email_verified_at' => now(),
                'estado'            => 'Activo' // Agregué esto por si tu tabla usuarios lo pide
            ]
        );

        $personaAdmin = Persona::firstOrCreate(
            ['dni' => '00000001'],
            [
                'user_id'          => $userAdmin->id,
                'nombres'          => 'Super',
                'apellidos'        => 'Admin',
                'fecha_nacimiento' => '1980-01-01',
                'genero'           => 'M',
                'direccion'        => 'Colegio Adonai',
                'estado'           => 'Activo'
            ]
        );

        Administrador::firstOrCreate(
            ['persona_id' => $personaAdmin->id],
            [
                'cargo'            => 'Director',
                'area'             => 'Dirección General',
                'fecha_asignacion' => now()
            ]
        );

        // Asignación de rol SEGURA
        if (!$userAdmin->hasRole('admin')) {
            $userAdmin->assignRole($rolAdmin);
        }

        $this->command->info('✅ Roles, Permisos y Usuario Admin generados correctamente.');
    }

    /**
     * Pequeña función auxiliar para crear permisos de forma segura y limpia
     */
    private function crearPermiso($name, $display, $module)
    {
        Permission::firstOrCreate(
            ['name' => $name, 'guard_name' => 'web'], 
            ['display_name' => $display, 'module' => $module]
        );
    }
}