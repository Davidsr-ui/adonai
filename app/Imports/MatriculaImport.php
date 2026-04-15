<?php

namespace App\Imports;

use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Tutor;
use App\Models\Grado;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Gestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatriculaImport implements ToModel, WithHeadingRow, WithValidation
{
    private static $studentCounter = null;
    private static $tutorCounter = null;

    private function parseDate($value)
    {
        if (empty($value)) return null;
        if (is_numeric($value)) {
            $unix = ($value - 25569) * 86400;
            return date('Y-m-d', $unix);
        }
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function initCounters()
    {
        if (self::$studentCounter === null) {
            $maxStudentId = Estudiante::max('id') ?? 0;
            self::$studentCounter = $maxStudentId + 1;
        }
        if (self::$tutorCounter === null) {
            $maxTutorId = Tutor::max('id') ?? 0;
            self::$tutorCounter = $maxTutorId + 1;
        }
    }

    /**
     * Matricula automáticamente al estudiante en todos los cursos de su grado.
     */
    private function matricularEnCursosDelGrado(Estudiante $estudiante)
    {
        // Obtener la gestión activa
        $gestionActiva = Gestion::where('estado', 'Activo')->first();
        if (!$gestionActiva) {
            return;
        }

        // Obtener todos los cursos que pertenecen al grado del estudiante
        $cursosDelGrado = Curso::where('grado_id', $estudiante->grado_id)->get();

        foreach ($cursosDelGrado as $curso) {
            Matricula::firstOrCreate(
                [
                    'estudiante_id' => $estudiante->id,
                    'curso_id'      => $curso->id,
                    'gestion_id'    => $gestionActiva->id,
                ],
                [
                    'grado_id'      => $estudiante->grado_id,
                    'estado'        => 'Matriculado',
                ]
            );
        }
    }

    public function model(array $row)
    {
        $this->initCounters();

        // ---------------------------------------------------------
        // 1. TUTOR
        // ---------------------------------------------------------
        $personaTutor = Persona::withTrashed()->where('dni', $row['tutor_dni'])->first();

        if (!$personaTutor) {
            $personaTutor = Persona::create([
                'dni'              => $row['tutor_dni'],
                'nombres'          => $row['tutor_nombres'],
                'apellidos'        => $row['tutor_apellidos'],
                'telefono'         => $row['tutor_telefono'],
                'genero'           => $row['tutor_genero'],
                'estado'           => 'Activo',
                'fecha_nacimiento' => '1980-01-01',
            ]);
        } else {
            if ($personaTutor->trashed()) $personaTutor->restore();
            $personaTutor->update([
                'nombres'   => $row['tutor_nombres'],
                'apellidos' => $row['tutor_apellidos'],
                'telefono'  => $row['tutor_telefono'],
                'genero'    => $row['tutor_genero'],
                'estado'    => 'Activo',
            ]);
        }

        // Crear usuario para el tutor (si no existe)
        $personaTutor->crearUsuarioSiNoExiste('tutor', $row['tutor_dni']);

        // Buscar o crear tutor
        $tutor = Tutor::where('persona_id', $personaTutor->id)->first();
        if (!$tutor) {
            $codigoTutor = str_pad(self::$tutorCounter++, 4, '0', STR_PAD_LEFT);
            $tutor = Tutor::create([
                'persona_id'   => $personaTutor->id,
                'ocupacion'    => $row['tutor_ocupacion'] ?? null,
                'codigo_tutor' => $codigoTutor,
            ]);
        } else {
            $tutor->update([
                'ocupacion' => $row['tutor_ocupacion'] ?? $tutor->ocupacion,
            ]);
        }

        // ---------------------------------------------------------
        // 2. GRADO
        // ---------------------------------------------------------
        $gradoQuery = Grado::where('nombre', $row['estudiante_grado']);
        if (empty($row['estudiante_seccion'])) {
            $gradoQuery->whereNull('seccion');
        } else {
            $gradoQuery->where('seccion', $row['estudiante_seccion']);
        }
        $grado = $gradoQuery->first();
        $gradoId = $grado ? $grado->id : null;

        // ---------------------------------------------------------
        // 3. ESTUDIANTE
        // ---------------------------------------------------------
        $fechaNacimiento = $this->parseDate($row['estudiante_nacimiento']);
        if (!$fechaNacimiento) {
            throw new \Exception("Fecha inválida para estudiante DNI {$row['estudiante_dni']}");
        }

        $personaEstudiante = Persona::withTrashed()->where('dni', $row['estudiante_dni'])->first();

        if (!$personaEstudiante) {
            $personaEstudiante = Persona::create([
                'dni'              => $row['estudiante_dni'],
                'nombres'          => $row['estudiante_nombres'],
                'apellidos'        => $row['estudiante_apellidos'],
                'fecha_nacimiento' => $fechaNacimiento,
                'genero'           => $row['estudiante_genero'],
                'direccion'        => $row['estudiante_direccion'] ?? null,
                'estado'           => 'Activo',
            ]);
        } else {
            if ($personaEstudiante->trashed()) $personaEstudiante->restore();
            $personaEstudiante->update([
                'nombres'          => $row['estudiante_nombres'],
                'apellidos'        => $row['estudiante_apellidos'],
                'fecha_nacimiento' => $fechaNacimiento,
                'genero'           => $row['estudiante_genero'],
                'direccion'        => $row['estudiante_direccion'] ?? $personaEstudiante->direccion,
                'estado'           => 'Activo',
            ]);
        }

        // Buscar o crear estudiante
        $estudiante = Estudiante::where('persona_id', $personaEstudiante->id)->first();
        if (!$estudiante) {
            $codigoEstudiante = str_pad(self::$studentCounter++, 4, '0', STR_PAD_LEFT);
            $estudiante = Estudiante::create([
                'persona_id'        => $personaEstudiante->id,
                'grado_id'          => $gradoId,
                'codigo_estudiante' => $codigoEstudiante,
                'año_ingreso'       => date('Y'),
                'condicion'         => 'Regular',
            ]);
        } else {
            $estudiante->update([
                'grado_id'    => $gradoId,
                'año_ingreso' => date('Y'),
                'condicion'   => 'Regular',
            ]);
        }

        // ---------------------------------------------------------
        // 4. VINCULAR TUTOR-ESTUDIANTE
        // ---------------------------------------------------------
        $estudiante->tutores()->syncWithoutDetaching([
            $tutor->id => [
                'relacion_familiar'   => $row['relacion'] ?? 'Padre',
                'tipo'                => 'Principal',
                'autorizacion_recojo' => 1,
                'estado'              => 'Activo',
            ]
        ]);

        // ---------------------------------------------------------
        // 5. MATRICULAR AL ESTUDIANTE EN LOS CURSOS DEL GRADO (AUTOMÁTICO)
        // ---------------------------------------------------------
        if ($estudiante->grado_id && $personaEstudiante->estado == 'Activo') {
            $this->matricularEnCursosDelGrado($estudiante);
        }

        return $estudiante;
    }

    public function rules(): array
    {
        return [
            'tutor_dni'          => 'required',
            'tutor_nombres'      => 'required',
            'tutor_apellidos'    => 'required',
            'tutor_telefono'     => 'required',
            'tutor_genero'       => 'required|in:M,F,Otro',
            'tutor_ocupacion'    => 'nullable',
            'estudiante_dni'     => 'required',
            'estudiante_nombres' => 'required',
            'estudiante_apellidos' => 'required',
            'estudiante_nacimiento' => 'required',
            'estudiante_genero'  => 'required|in:M,F,Otro',
            'estudiante_direccion' => 'nullable',
            'estudiante_grado'   => 'required',
            'estudiante_seccion' => 'nullable',
            'relacion'           => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tutor_genero.required' => 'El género del tutor es obligatorio',
            'tutor_genero.in' => 'El género del tutor debe ser M, F u Otro',
            'estudiante_nacimiento.required' => 'La fecha de nacimiento del estudiante es obligatoria',
            'estudiante_genero.required' => 'El género del estudiante es obligatorio',
            'estudiante_genero.in' => 'El género del estudiante debe ser M, F u Otro',
        ];
    }
}