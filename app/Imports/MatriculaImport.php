<?php

namespace App\Imports;

use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Tutor;
use App\Models\Grado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class MatriculaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Lee una fila del Excel y crea/vincula todo.
     */
    public function model(array $row)
    {
        // =========================================================
        // ⚠️ CORRECCIÓN COMPLETA: BÚSQUEDA Y RESTAURACIÓN
        // =========================================================

        // ---------------------------------------------------------
        // 1. GESTIONAR AL TUTOR (PADRE/APODERADO)
        // ---------------------------------------------------------

        // a) Buscamos si la persona ya existe (incluso si está borrada)
        $personaTutor = Persona::withTrashed()->where('dni', $row['tutor_dni'])->first();

        if (!$personaTutor) {
            // b) Si NO existe, la creamos desde cero
            $personaTutor = Persona::create([
                'dni'              => $row['tutor_dni'],
                'nombres'          => $row['tutor_nombres'],
                'apellidos'        => $row['tutor_apellidos'],
                'telefono'         => $row['tutor_telefono'],
                'genero'           => $row['tutor_genero'] ?? 'Otro',
                'estado'           => 'Activo',
                'fecha_nacimiento' => '1980-01-01',
            ]);
        } else {
            // c) Si SÍ existe...
            
            // 1. Si estaba en la papelera, la RESTAURAMOS (Revivimos)
            if ($personaTutor->trashed()) {
                $personaTutor->restore(); 
            }

            // 2. Actualizamos sus datos por si cambiaron en el Excel
            $personaTutor->update([
                'nombres'   => $row['tutor_nombres'],
                'apellidos' => $row['tutor_apellidos'],
                'telefono'  => $row['tutor_telefono'],
                'estado'    => 'Activo' // Nos aseguramos que quede activo
            ]);
        }

        // Aseguramos registro en tabla 'tutores'
        $tutor = Tutor::updateOrCreate(
            ['persona_id' => $personaTutor->id],
            [
                'ocupacion'    => $row['tutor_ocupacion'] ?? null,
                'codigo_tutor' => 'T-'.$row['tutor_dni'],
            ]
        );

        // ---------------------------------------------------------
        // 2. BUSCAR EL GRADO
        // ---------------------------------------------------------
        $grado = Grado::where('nombre', $row['estudiante_grado'])
                      ->where('seccion', $row['estudiante_seccion'])
                      ->first();
        
        $gradoId = $grado ? $grado->id : null;

        // ---------------------------------------------------------
        // 3. GESTIONAR AL ESTUDIANTE (HIJO)
        // ---------------------------------------------------------
        
        // a) Buscamos persona (incluyendo borrados)
        $personaEstudiante = Persona::withTrashed()->where('dni', $row['estudiante_dni'])->first();

        if (!$personaEstudiante) {
            // b) Crear nuevo
            $personaEstudiante = Persona::create([
                'dni'              => $row['estudiante_dni'],
                'nombres'          => $row['estudiante_nombres'],
                'apellidos'        => $row['estudiante_apellidos'],
                'fecha_nacimiento' => $row['estudiante_nacimiento'] ?? '2010-01-01',
                'genero'           => $row['estudiante_genero'] ?? 'Otro',
                'direccion'        => $row['estudiante_direccion'] ?? null,
                'estado'           => 'Activo',
            ]);
        } else {
            // c) Si existe...
            
            // 1. Restaurar si está borrado
            if ($personaEstudiante->trashed()) {
                $personaEstudiante->restore();
            }

            // 2. Actualizar datos básicos
            $personaEstudiante->update([
                'nombres'   => $row['estudiante_nombres'],
                'apellidos' => $row['estudiante_apellidos'],
                'estado'    => 'Activo'
            ]);
        }

        // Creamos/Actualizamos estudiante
        $estudiante = Estudiante::updateOrCreate(
            ['persona_id' => $personaEstudiante->id],
            [
                'grado_id'          => $gradoId,
                'codigo_estudiante' => $row['estudiante_codigo'] ?? 'E-'.$row['estudiante_dni'],
                'año_ingreso'       => date('Y'),
                'condicion'         => 'Regular'
            ]
        );

        // ---------------------------------------------------------
        // 4. VINCULAR TUTOR-ESTUDIANTE
        // ---------------------------------------------------------
        $estudiante->tutores()->syncWithoutDetaching([
            $tutor->id => [
                'relacion_familiar'   => $row['relacion'] ?? 'Padre',
                'tipo'                => 'Principal',
                'autorizacion_recojo' => 1,
                'estado'              => 'Activo'
            ]
        ]);

        return $estudiante;
    }

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'tutor_dni'          => 'required',
            'tutor_nombres'      => 'required',
            'estudiante_dni'     => 'required',
            'estudiante_nombres' => 'required',
        ];
    }
}