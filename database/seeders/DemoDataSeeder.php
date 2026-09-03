<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

use App\Models\User;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\Tutor;
use App\Models\Estudiante;
use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Nivel;
use App\Models\Turno;
use App\Models\Grado;
use App\Models\Curso;
use App\Models\Configuracion;
use App\Models\Blog;
use App\Models\Taller;

class DemoDataSeeder extends Seeder
{
    private $faker;

    // Contenedores de IDs para no perder referencias
    private array $docentesIds = [];
    private array $tutoresIds = [];
    private array $gradosData = []; // grado_id => ['nivel_id'=>, 'cursos'=>[curso_id,...]]
    private array $periodosGestionActiva = [];
    private int $gestionActivaId;

    public function run(): void
    {
        $this->faker = FakerFactory::create('es_ES');
        $this->faker->seed(12345);

        $this->command->info('🚀 Iniciando seeding masivo de datos de prueba...');

        DB::disableQueryLog();

        // Ordenar por dependencias
        $this->seedConfiguracion();
        [$gestiones, $periodos] = $this->seedGestionesYPeriodos();
        $niveles = $this->seedNiveles();
        $turnos = $this->seedTurnos();
        $this->seedGradosYCursos($niveles, $turnos);
        $this->seedDocentes(22);
        $this->seedTutores(80);
        $estudiantes = $this->seedEstudiantesYMatricula(180);
        $this->seedTutorEstudiante($estudiantes);
        $this->seedDocenteCursoYHorarios();
        $matriculas = $this->seedMatriculas($estudiantes);
        $this->seedNotas($matriculas);
        $this->seedAsistencias($matriculas);
        $this->seedComportamientos($estudiantes);
        $this->seedReportes($estudiantes);
        $this->seedMensajesYNotificaciones();
        $this->seedBlogs();
        $this->seedTalleres();

        $this->command->info('✅ Base de datos poblada completamente. Lista para pruebas manuales.');
        $this->printResumen();
    }

    // =====================================================================
    // CONFIGURACION
    // =====================================================================
    private function seedConfiguracion(): void
    {
        Configuracion::firstOrCreate(
            ['clave' => 'GLOBAL_SETTINGS'],
            [
                'nombre'      => 'Colegio Adonai',
                'descripcion' => 'Institución Educativa Particular Adonai',
                'direccion'   => 'Av. Los Educadores 456, Lamas, San Martín, Perú',
                'telefono'    => '042-556677',
                'divisa'      => 'PEN',
                'email'       => 'contacto@adonai.edu.pe',
                'web'         => 'https://www.adonai.edu.pe',
                'logo'        => null,
                'valor'       => null,
                'tipo'        => 'Texto',
                'categoria'   => 'General',
                'editable'    => true,
            ]
        );
        $this->command->info('  • Configuración global creada.');
    }

    // =====================================================================
    // GESTIONES Y PERIODOS
    // =====================================================================
    private function seedGestionesYPeriodos(): array
    {
        $gestiones = [];
        $periodos = [];

        $definiciones = [
            ['año' => 2024, 'nombre' => 'Año Escolar 2024', 'inicio' => '2024-03-01', 'fin' => '2024-12-15', 'estado' => 'Finalizado'],
            ['año' => 2025, 'nombre' => 'Año Escolar 2025', 'inicio' => '2025-03-01', 'fin' => '2025-12-15', 'estado' => 'Finalizado'],
            ['año' => 2026, 'nombre' => 'Año Escolar 2026', 'inicio' => '2026-03-01', 'fin' => '2026-12-15', 'estado' => 'Activo'],
        ];

        foreach ($definiciones as $def) {
            $gestion = Gestion::create([
                'año' => $def['año'],
                'nombre' => $def['nombre'],
                'fecha_inicio' => $def['inicio'],
                'fecha_fin' => $def['fin'],
                'estado' => $def['estado'],
            ]);
            $gestiones[] = $gestion;

            $nombresPeriodo = ['I Bimestre', 'II Bimestre', 'III Bimestre'];
            $inicioAño = Carbon::parse($def['inicio']);
            for ($i = 0; $i < 3; $i++) {
                $pInicio = $inicioAño->copy()->addMonths($i * 3);
                $pFin = $pInicio->copy()->addMonths(3)->subDay();
                $estadoPeriodo = $def['estado'] === 'Finalizado'
                    ? 'Finalizado'
                    : ($i === 0 ? 'Activo' : 'Planificado');

                $periodo = Periodo::create([
                    'gestion_id' => $gestion->id,
                    'nombre' => $nombresPeriodo[$i],
                    'numero' => $i + 1,
                    'fecha_inicio' => $pInicio->format('Y-m-d'),
                    'fecha_fin' => $pFin->format('Y-m-d'),
                    'estado' => $estadoPeriodo,
                ]);
                $periodos[] = $periodo;

                if ($def['estado'] === 'Activo') {
                    $this->periodosGestionActiva[] = $periodo->id;
                }
            }

            if ($def['estado'] === 'Activo') {
                $this->gestionActivaId = $gestion->id;
            }
        }

        $this->command->info('  • ' . count($gestiones) . ' gestiones y ' . count($periodos) . ' periodos creados.');
        return [$gestiones, $periodos];
    }

    // =====================================================================
    // NIVELES Y TURNOS
    // =====================================================================
    private function seedNiveles(): array
    {
        $niveles = [];
        $data = [
            ['nombre' => 'Inicial', 'orden' => 1, 'descripcion' => 'Educación Inicial (3 a 5 años)'],
            ['nombre' => 'Primaria', 'orden' => 2, 'descripcion' => 'Educación Primaria (1° a 6° grado)'],
            ['nombre' => 'Secundaria', 'orden' => 3, 'descripcion' => 'Educación Secundaria (1° a 5° año)'],
        ];
        foreach ($data as $d) {
            $niveles[$d['nombre']] = Nivel::create([
                'nombre' => $d['nombre'],
                'descripcion' => $d['descripcion'],
                'orden' => $d['orden'],
                'estado' => 'Activo',
            ]);
        }
        $this->command->info('  • ' . count($niveles) . ' niveles creados.');
        return $niveles;
    }

    private function seedTurnos(): array
    {
        $turnos = [];
        $turnos['Mañana'] = Turno::create([
            'nombre' => 'Mañana',
            'hora_inicio' => '07:30:00',
            'hora_fin' => '13:00:00',
            'estado' => 'activo',
            'descripcion' => 'Turno de la mañana',
        ]);
        $turnos['Tarde'] = Turno::create([
            'nombre' => 'Tarde',
            'hora_inicio' => '13:15:00',
            'hora_fin' => '18:30:00',
            'estado' => 'activo',
            'descripcion' => 'Turno de la tarde',
        ]);
        $this->command->info('  • 2 turnos creados.');
        return $turnos;
    }

    // =====================================================================
    // GRADOS Y CURSOS
    // =====================================================================
    private function seedGradosYCursos(array $niveles, array $turnos): void
    {
        $mallaPorNivel = [
            'Inicial' => ['Personal Social', 'Comunicación', 'Psicomotricidad', 'Descubrimiento del Mundo', 'Arte'],
            'Primaria' => ['Matemática', 'Comunicación', 'Ciencia y Tecnología', 'Personal Social', 'Arte y Cultura', 'Educación Física', 'Inglés'],
            'Secundaria' => ['Matemática', 'Comunicación', 'Inglés', 'Ciencia y Tecnología', 'Historia, Geografía y Economía', 'Educación Física', 'Arte y Cultura', 'Persona, Familia y RRHH', 'Educación para el Trabajo'],
        ];

        $estructura = [
            'Inicial' => ['grados' => ['3 años', '4 años', '5 años'], 'secciones' => ['Única']],
            'Primaria' => ['grados' => ['1er Grado', '2do Grado', '3er Grado', '4to Grado', '5to Grado', '6to Grado'], 'secciones' => ['A', 'B']],
            'Secundaria' => ['grados' => ['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'], 'secciones' => ['A', 'B']],
        ];

        $codigoCurso = 1;

        foreach ($estructura as $nombreNivel => $conf) {
            $nivel = $niveles[$nombreNivel];
            $turno = $this->faker->boolean(70) ? $turnos['Mañana'] : $turnos['Tarde'];

            foreach ($conf['grados'] as $nombreGrado) {
                foreach ($conf['secciones'] as $seccion) {
                    $grado = Grado::create([
                        'nivel_id' => $nivel->id,
                        'turno_id' => $turno->id,
                        'nombre' => $nombreGrado,
                        'seccion' => $seccion,
                        'capacidad_maxima' => 30,
                        'estado' => 'Activo',
                    ]);

                    $cursosIds = [];
                    foreach ($mallaPorNivel[$nombreNivel] as $areaCurricular) {
                        $curso = Curso::create([
                            'nivel_id' => $nivel->id,
                            'grado_id' => $grado->id,
                            'nombre' => $areaCurricular,
                            'codigo' => 'CUR-' . str_pad((string) $codigoCurso, 4, '0', STR_PAD_LEFT),
                            'horas_semanales' => $this->faker->numberBetween(2, 6),
                            'area_curricular' => $areaCurricular,
                            'estado' => 'Activo',
                        ]);
                        $codigoCurso++;
                        $cursosIds[] = $curso->id;
                    }

                    $this->gradosData[$grado->id] = [
                        'nivel_id' => $nivel->id,
                        'nombre_completo' => "{$nombreGrado} \"{$seccion}\" - {$nombreNivel}",
                        'cursos' => $cursosIds,
                    ];
                }
            }
        }

        $totalCursos = array_sum(array_map(fn ($g) => count($g['cursos']), $this->gradosData));
        $this->command->info('  • ' . count($this->gradosData) . ' grados y ' . $totalCursos . ' cursos creados.');
    }

    // =====================================================================
    // DOCENTES (Persona + User + Docente)
    // =====================================================================
    private function seedDocentes(int $cantidad): void
    {
        $especialidades = ['Matemática', 'Comunicación', 'Ciencias', 'Inglés', 'Educación Física', 'Arte', 'Historia', 'Psicología Educativa', 'Computación'];
        $tiposContrato = ['Nombrado', 'Contratado', 'Temporal'];
        $rows = [];
        $now = now();

        for ($i = 1; $i <= $cantidad; $i++) {
            $genero = $this->faker->randomElement(['M', 'F']);
            $nombres = $genero === 'M' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();
            $apellidos = $this->faker->lastName() . ' ' . $this->faker->lastName();
            $dni = str_pad((string) (60000000 + $i), 8, '0', STR_PAD_LEFT);
            $email = Str::slug($nombres) . '.' . Str::slug(explode(' ', $apellidos)[0]) . $i . '@adonai.edu.pe';

            $user = User::create([
                'name' => "$nombres $apellidos",
                'email' => $email,
                'password' => Hash::make('Docente123'),
                'email_verified_at' => $now,
                'estado' => 'Activo',
            ]);
            $user->assignRole('docente');

            $persona = Persona::create([
                'user_id' => $user->id,
                'dni' => $dni,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $this->faker->dateTimeBetween('-58 years', '-26 years')->format('Y-m-d'),
                'genero' => $genero,
                'direccion' => $this->faker->streetAddress(),
                'telefono' => '9' . $this->faker->numerify('########'),
                'telefono_emergencia' => '9' . $this->faker->numerify('########'),
                'estado' => 'Activo',
            ]);

            $docente = Docente::create([
                'persona_id' => $persona->id,
                'codigo_docente' => 'DOC-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'especialidad' => $this->faker->randomElement($especialidades),
                'fecha_contratacion' => $this->faker->dateTimeBetween('-8 years', '-1 months')->format('Y-m-d'),
                'tipo_contrato' => $this->faker->randomElement($tiposContrato),
            ]);

            $this->docentesIds[] = $docente->id;
        }

        $this->command->info('  • ' . $cantidad . ' docentes creados (con usuario, contraseña: Docente123).');
    }

    // =====================================================================
    // TUTORES (Persona + User + Tutor)
    // =====================================================================
    private function seedTutores(int $cantidad): void
    {
        $ocupaciones = ['Comerciante', 'Ingeniero/a', 'Enfermero/a', 'Agricultor/a', 'Docente', 'Contador/a', 'Chofer', 'Ama de casa', 'Comerciante independiente', 'Técnico/a', 'Abogado/a', 'Policía'];
        $now = now();

        for ($i = 1; $i <= $cantidad; $i++) {
            $genero = $this->faker->randomElement(['M', 'F']);
            $nombres = $genero === 'M' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();
            $apellidos = $this->faker->lastName() . ' ' . $this->faker->lastName();
            $dni = str_pad((string) (70000000 + $i), 8, '0', STR_PAD_LEFT);
            $email = Str::slug($nombres) . '.' . Str::slug(explode(' ', $apellidos)[0]) . $i . '@tutores.adonai.edu.pe';

            // Solo el 70% de los tutores tiene cuenta de usuario (login al portal de padres)
            $userId = null;
            if ($this->faker->boolean(70)) {
                $user = User::create([
                    'name' => "$nombres $apellidos",
                    'email' => $email,
                    'password' => Hash::make('Tutor123'),
                    'email_verified_at' => $now,
                    'estado' => 'Activo',
                ]);
                $user->assignRole('tutor');
                $userId = $user->id;
            }

            $persona = Persona::create([
                'user_id' => $userId,
                'dni' => $dni,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $this->faker->dateTimeBetween('-65 years', '-25 years')->format('Y-m-d'),
                'genero' => $genero,
                'direccion' => $this->faker->streetAddress(),
                'telefono' => '9' . $this->faker->numerify('########'),
                'telefono_emergencia' => '9' . $this->faker->numerify('########'),
                'estado' => 'Activo',
            ]);

            $tutor = Tutor::create([
                'persona_id' => $persona->id,
                'codigo_tutor' => 'TUT-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'ocupacion' => $this->faker->randomElement($ocupaciones),
            ]);

            $this->tutoresIds[] = $tutor->id;
        }

        $this->command->info('  • ' . $cantidad . ' tutores creados (' . round($cantidad * 0.7) . ' con usuario, contraseña: Tutor123).');
    }

    // =====================================================================
    // ESTUDIANTES (Persona + Estudiante, asignados a un grado)
    // =====================================================================
    private function seedEstudiantesYMatricula(int $cantidad): array
    {
        $gradoIds = array_keys($this->gradosData);
        $estudiantes = [];
        $condiciones = ['Regular', 'Regular', 'Regular', 'Regular', 'Irregular', 'Retirado'];

        for ($i = 1; $i <= $cantidad; $i++) {
            $genero = $this->faker->randomElement(['M', 'F']);
            $nombres = $genero === 'M' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();
            $apellidos = $this->faker->lastName() . ' ' . $this->faker->lastName();
            $dni = str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT);
            $gradoId = $gradoIds[array_rand($gradoIds)];

            $persona = Persona::create([
                'user_id' => null,
                'dni' => $dni,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $this->faker->dateTimeBetween('-18 years', '-4 years')->format('Y-m-d'),
                'genero' => $genero,
                'direccion' => $this->faker->streetAddress(),
                'telefono' => null,
                'telefono_emergencia' => '9' . $this->faker->numerify('########'),
                'estado' => 'Activo',
            ]);

            $estudiante = Estudiante::create([
                'persona_id' => $persona->id,
                'grado_id' => $gradoId,
                'codigo_estudiante' => 'EST-2026-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'año_ingreso' => $this->faker->numberBetween(2019, 2026),
                'condicion' => $this->faker->randomElement($condiciones),
            ]);

            $estudiantes[] = ['id' => $estudiante->id, 'grado_id' => $gradoId];
        }

        $this->command->info('  • ' . $cantidad . ' estudiantes creados y distribuidos en grados.');
        return $estudiantes;
    }

    // =====================================================================
    // TUTOR_ESTUDIANTE (cada estudiante con 1 o 2 tutores)
    // =====================================================================
    private function seedTutorEstudiante(array $estudiantes): void
    {
        $relaciones = ['Padre', 'Madre', 'Tutor Legal', 'Abuelo/a', 'Tío/a', 'Hermano/a'];
        $rows = [];
        $now = now();

        foreach ($estudiantes as $est) {
            $cantidadTutores = $this->faker->boolean(65) ? 2 : 1;
            $tutoresAsignados = (array) array_rand(array_flip($this->tutoresIds), min($cantidadTutores, count($this->tutoresIds)));

            $primero = true;
            foreach ($tutoresAsignados as $tutorId) {
                $rows[] = [
                    'tutor_id' => $tutorId,
                    'estudiante_id' => $est['id'],
                    'relacion_familiar' => $primero ? $this->faker->randomElement(['Padre', 'Madre']) : $this->faker->randomElement($relaciones),
                    'tipo' => $primero ? 'Principal' : 'Secundario',
                    'autorizacion_recojo' => true,
                    'estado' => 'Activo',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $primero = false;
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('tutor_estudiante')->insert($chunk);
        }

        $this->command->info('  • ' . count($rows) . ' vínculos tutor-estudiante creados.');
    }

    // =====================================================================
    // DOCENTE_CURSO + HORARIOS (solo gestión activa)
    // =====================================================================
    private array $docenteCursoLookup = []; // "cursoId-gradoId" => docenteId

    private function seedDocenteCursoYHorarios(): void
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $bloques = [
            ['08:00:00', '08:45:00'], ['08:45:00', '09:30:00'], ['09:45:00', '10:30:00'],
            ['10:30:00', '11:15:00'], ['11:30:00', '12:15:00'], ['13:15:00', '14:00:00'],
            ['14:00:00', '14:45:00'], ['15:00:00', '15:45:00'],
        ];
        $aulas = ['Aula 101', 'Aula 102', 'Aula 103', 'Aula 201', 'Aula 202', 'Aula 203', 'Laboratorio', 'Sala de Cómputo', 'Patio', 'Sala de Arte'];

        $rowsDC = [];
        $rowsHorario = [];
        $now = now();

        foreach ($this->gradosData as $gradoId => $info) {
            // Un docente del grado será tutor de aula
            $docenteTutorAula = $this->docentesIds[array_rand($this->docentesIds)];
            $primerCurso = true;

            foreach ($info['cursos'] as $cursoId) {
                $docenteId = $this->faker->boolean(35) ? $docenteTutorAula : $this->docentesIds[array_rand($this->docentesIds)];

                $rowsDC[] = [
                    'docente_id' => $docenteId,
                    'curso_id' => $cursoId,
                    'grado_id' => $gradoId,
                    'gestion_id' => $this->gestionActivaId,
                    'es_tutor_aula' => $primerCurso ? true : false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $primerCurso = false;

                $this->docenteCursoLookup[$cursoId . '-' . $gradoId] = $docenteId;

                $dia = $this->faker->randomElement($dias);
                $bloque = $this->faker->randomElement($bloques);

                $rowsHorario[] = [
                    'gestion_id' => $this->gestionActivaId,
                    'curso_id' => $cursoId,
                    'grado_id' => $gradoId,
                    'docente_id' => $docenteId,
                    'dia_semana' => $dia,
                    'hora_inicio' => $bloque[0],
                    'hora_fin' => $bloque[1],
                    'aula' => $this->faker->randomElement($aulas),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rowsDC, 500) as $chunk) {
            DB::table('docente_curso')->insert($chunk);
        }
        foreach (array_chunk($rowsHorario, 500) as $chunk) {
            DB::table('horarios')->insert($chunk);
        }

        $this->command->info('  • ' . count($rowsDC) . ' asignaciones docente-curso y ' . count($rowsHorario) . ' horarios creados.');
    }

    // =====================================================================
    // MATRICULAS (gestión activa, un estudiante en todos los cursos de su grado)
    // =====================================================================
    private function seedMatriculas(array $estudiantes): array
    {
        $rows = [];
        $now = now();
        $matriculasInfo = []; // referencia liviana para pasos siguientes

        foreach ($estudiantes as $est) {
            if (!isset($this->gradosData[$est['grado_id']])) {
                continue;
            }
            foreach ($this->gradosData[$est['grado_id']]['cursos'] as $cursoId) {
                $rows[] = [
                    'estudiante_id' => $est['id'],
                    'curso_id' => $cursoId,
                    'grado_id' => $est['grado_id'],
                    'gestion_id' => $this->gestionActivaId,
                    'estado' => 'Matriculado',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('matriculas')->insert($chunk);
        }

        // Recuperar IDs reales insertados para poder generar notas/asistencias
        $matriculasDb = DB::table('matriculas')
            ->where('gestion_id', $this->gestionActivaId)
            ->get(['id', 'estudiante_id', 'curso_id', 'grado_id']);

        foreach ($matriculasDb as $m) {
            $matriculasInfo[] = [
                'id' => $m->id,
                'estudiante_id' => $m->estudiante_id,
                'curso_id' => $m->curso_id,
                'grado_id' => $m->grado_id,
                'docente_id' => $this->docenteCursoLookup[$m->curso_id . '-' . $m->grado_id] ?? $this->docentesIds[array_rand($this->docentesIds)],
            ];
        }

        $this->command->info('  • ' . count($rows) . ' matrículas creadas para la gestión activa.');
        return $matriculasInfo;
    }

    // =====================================================================
    // NOTAS (por matrícula x periodo de la gestión activa)
    // =====================================================================
    private function seedNotas(array $matriculas): void
    {
        $tipos = ['Parcial', 'Final', 'Práctica', 'Oral', 'Trabajo'];
        $rows = [];
        $now = now();

        foreach ($matriculas as $mat) {
            foreach ($this->periodosGestionActiva as $periodoId) {
                $practica = $this->faker->randomFloat(2, 8, 20);
                $teoria = $this->faker->randomFloat(2, 8, 20);
                $final = round(($practica + $teoria) / 2, 2);

                $rows[] = [
                    'matricula_id' => $mat['id'],
                    'periodo_id' => $periodoId,
                    'docente_id' => $mat['docente_id'],
                    'nota_practica' => $practica,
                    'nota_teoria' => $teoria,
                    'nota_final' => $final,
                    'tipo_evaluacion' => $this->faker->randomElement($tipos),
                    'descripcion' => 'Evaluación de periodo',
                    'observaciones' => $this->faker->boolean(20) ? $this->faker->sentence(8) : null,
                    'fecha_evaluacion' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                    'visible_tutor' => $this->faker->boolean(75),
                    'fecha_publicacion' => $this->faker->boolean(75) ? $now : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('notas')->insert($chunk);
        }

        $this->command->info('  • ' . count($rows) . ' notas registradas.');
    }

    // =====================================================================
    // ASISTENCIAS (últimos 15 días hábiles por matrícula)
    // =====================================================================
    private function seedAsistencias(array $matriculas): void
    {
        $estados = ['Presente', 'Presente', 'Presente', 'Presente', 'Presente', 'Presente', 'Presente', 'Presente', 'Tardanza', 'Ausente', 'Justificado'];
        $rows = [];
        $now = now();

        // Generar los últimos 15 días hábiles (lunes-viernes)
        $fechas = [];
        $cursor = Carbon::now();
        while (count($fechas) < 15) {
            if (!$cursor->isWeekend()) {
                $fechas[] = $cursor->format('Y-m-d');
            }
            $cursor->subDay();
        }

        foreach ($matriculas as $mat) {
            foreach ($fechas as $fecha) {
                if ($this->faker->boolean(15)) {
                    continue; // no todos los días tienen registro para todos los cursos (realista)
                }
                $rows[] = [
                    'estudiante_id' => $mat['estudiante_id'],
                    'curso_id' => $mat['curso_id'],
                    'docente_id' => $mat['docente_id'],
                    'fecha' => $fecha,
                    'estado' => $this->faker->randomElement($estados),
                    'observaciones' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (count($rows) > 3000) {
                foreach (array_chunk($rows, 500) as $chunk) {
                    DB::table('asistencias')->insert($chunk);
                }
                $rows = [];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('asistencias')->insert($chunk);
        }

        $total = DB::table('asistencias')->count();
        $this->command->info('  • ' . $total . ' registros de asistencia creados.');
    }

    // =====================================================================
    // COMPORTAMIENTOS
    // =====================================================================
    private function seedComportamientos(array $estudiantes): void
    {
        $positivos = ['Destacó en trabajo grupal', 'Ayudó a un compañero', 'Participación sobresaliente en clase', 'Excelente liderazgo en actividad escolar'];
        $negativos = ['Interrumpe constantemente la clase', 'No trajo tareas asignadas', 'Conflicto con un compañero', 'Uso de celular en horario de clase'];
        $neutros = ['Cambio de comportamiento observado', 'Reunión informativa con el tutor', 'Seguimiento conductual'];

        $rows = [];
        $now = now();
        $muestra = $this->faker->randomElements($estudiantes, min(220, count($estudiantes) * 2), true);

        foreach ($muestra as $est) {
            $tipo = $this->faker->randomElement(['Positivo', 'Positivo', 'Negativo', 'Neutro']);
            $descripcion = match ($tipo) {
                'Positivo' => $this->faker->randomElement($positivos),
                'Negativo' => $this->faker->randomElement($negativos),
                default => $this->faker->randomElement($neutros),
            };

            $rows[] = [
                'estudiante_id' => $est['id'],
                'docente_id' => $this->docentesIds[array_rand($this->docentesIds)],
                'fecha' => $this->faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'descripcion' => $descripcion,
                'tipo' => $tipo,
                'sancion' => $tipo === 'Negativo' && $this->faker->boolean(40) ? $this->faker->randomElement(['Llamada de atención verbal', 'Citación al tutor', 'Suspensión de un día']) : null,
                'notificado_tutor' => $this->faker->boolean(60),
                'fecha_notificacion' => $this->faker->boolean(60) ? $now : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('comportamientos')->insert($chunk);
        }

        $this->command->info('  • ' . count($rows) . ' registros de comportamiento creados.');
    }

    // =====================================================================
    // REPORTES (por estudiante x periodo de gestión activa)
    // =====================================================================
    private function seedReportes(array $estudiantes): void
    {
        $rows = [];
        $now = now();

        foreach ($estudiantes as $est) {
            foreach ($this->periodosGestionActiva as $periodoId) {
                $rows[] = [
                    'estudiante_id' => $est['id'],
                    'docente_id' => $this->docentesIds[array_rand($this->docentesIds)],
                    'periodo_id' => $periodoId,
                    'gestion_id' => $this->gestionActivaId,
                    'tipo' => 'Bimestral',
                    'promedio_general' => $this->faker->randomFloat(2, 10, 19),
                    'porcentaje_asistencia' => $this->faker->randomFloat(2, 70, 100),
                    'comentario_final' => $this->faker->boolean(50) ? $this->faker->sentence(12) : null,
                    'archivo_pdf' => null,
                    'visible_tutor' => $this->faker->boolean(70),
                    'fecha_generacion' => $now,
                    'fecha_publicacion' => $this->faker->boolean(70) ? $now : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('reportes')->insert($chunk);
        }

        $this->command->info('  • ' . count($rows) . ' reportes académicos creados.');
    }

    // =====================================================================
    // MENSAJES + NOTIFICACIONES
    // =====================================================================
    private function seedMensajesYNotificaciones(): void
    {
        $usuarios = DB::table('users')->pluck('id')->toArray();
        $estudiantesIds = DB::table('estudiantes')->pluck('id')->toArray();
        $now = now();

        $asuntos = [
            'Reunión de padres de familia', 'Citación por comportamiento', 'Recordatorio de pago de pensión',
            'Notas del bimestre disponibles', 'Actividad extracurricular', 'Cambio de horario de clases',
            'Comunicado sobre feriado', 'Entrega de libretas', 'Taller de padres', 'Aviso de simulacro',
        ];

        $mensajeRows = [];
        $mensajesCreados = [];
        for ($i = 1; $i <= 45; $i++) {
            $mensajeRows[] = [
                'remitente_id' => $this->faker->randomElement($usuarios),
                'estudiante_id' => $this->faker->boolean(70) ? $this->faker->randomElement($estudiantesIds) : null,
                'asunto' => $this->faker->randomElement($asuntos),
                'contenido' => $this->faker->paragraph(4),
                'prioridad' => $this->faker->randomElement(['Baja', 'Normal', 'Normal', 'Alta', 'Urgente']),
                'tipo' => $this->faker->randomElement(['Individual', 'Grupal']),
                'archivos' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('mensajes')->insert($mensajeRows);
        $mensajesIds = DB::table('mensajes')->orderByDesc('id')->limit(45)->pluck('id')->reverse()->values()->toArray();

        $destinatarioRows = [];
        foreach ($mensajesIds as $mensajeId) {
            $destinatarios = $this->faker->randomElements($usuarios, min($this->faker->numberBetween(1, 4), count($usuarios)));
            foreach ($destinatarios as $destId) {
                $leido = $this->faker->boolean(55);
                $destinatarioRows[] = [
                    'mensaje_id' => $mensajeId,
                    'destinatario_id' => $destId,
                    'leido' => $leido,
                    'fecha_lectura' => $leido ? $now : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach (array_chunk($destinatarioRows, 500) as $chunk) {
            DB::table('mensaje_destinatarios')->insert($chunk);
        }

        // Notificaciones
        $tiposNotif = ['Nota Nueva', 'Asistencia', 'Comportamiento', 'Mensaje', 'Comunicado', 'Sistema'];
        $titulos = [
            'Nota Nueva' => 'Se publicó una nueva nota',
            'Asistencia' => 'Registro de asistencia actualizado',
            'Comportamiento' => 'Nuevo reporte de comportamiento',
            'Mensaje' => 'Tienes un mensaje nuevo',
            'Comunicado' => 'Nuevo comunicado institucional',
            'Sistema' => 'Notificación del sistema',
        ];

        $notifRows = [];
        for ($i = 1; $i <= 350; $i++) {
            $tipo = $this->faker->randomElement($tiposNotif);
            $leido = $this->faker->boolean(45);
            $notifRows[] = [
                'user_id' => $this->faker->randomElement($usuarios),
                'tipo' => $tipo,
                'titulo' => $titulos[$tipo],
                'descripcion' => $this->faker->sentence(10),
                'referencia_id' => null,
                'referencia_tabla' => null,
                'url' => null,
                'leido' => $leido,
                'fecha_lectura' => $leido ? $now : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($notifRows, 500) as $chunk) {
            DB::table('notificaciones')->insert($chunk);
        }

        $this->command->info('  • ' . count($mensajeRows) . ' mensajes, ' . count($destinatarioRows) . ' destinatarios y ' . count($notifRows) . ' notificaciones creados.');
    }

    // =====================================================================
    // BLOGS
    // =====================================================================
    private function seedBlogs(): void
    {
        $categorias = ['Académico', 'Premios', 'Concurso', 'Deportes', 'Cultura', 'Comunicado'];
        for ($i = 1; $i <= 14; $i++) {
            Blog::create([
                'titulo' => $this->faker->sentence(6),
                'categoria' => $this->faker->randomElement($categorias),
                'portada' => null,
                'fecha' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'autor' => $this->faker->name(),
                'descripcion_corta' => $this->faker->sentence(15),
                'contenido' => $this->faker->paragraphs(4, true),
                'tags' => $this->faker->randomElements(['colegio', 'adonai', 'noticias', 'estudiantes', 'logros', 'eventos'], 3),
            ]);
        }
        $this->command->info('  • 14 posts de blog creados.');
    }

    // =====================================================================
    // TALLERES
    // =====================================================================
    private function seedTalleres(): void
    {
        $nombres = ['Robótica Educativa', 'Danza Folklórica', 'Fútbol Escolar', 'Pintura y Dibujo', 'Coro Institucional', 'Inglés Conversacional', 'Ajedrez', 'Teatro Escolar', 'Música y Banda', 'Voleibol'];
        foreach ($nombres as $nombre) {
            $inicio = $this->faker->dateTimeBetween('now', '+2 weeks');
            Taller::create([
                'nombre' => $nombre,
                'descripcion' => $this->faker->paragraph(3),
                'instructor' => $this->faker->name(),
                'duracion_inicio' => $inicio->format('Y-m-d'),
                'duracion_fin' => (clone $inicio)->modify('+3 months')->format('Y-m-d'),
                'horario_inicio' => $this->faker->randomElement(['15:00:00', '16:00:00', '17:00:00']),
                'horario_fin' => $this->faker->randomElement(['16:00:00', '17:00:00', '18:00:00']),
                'categoria' => $this->faker->randomElement(['Deportivo', 'Artístico', 'Académico', 'Cultural']),
                'costo' => $this->faker->randomElement([0, 20, 30, 50]),
                'cupos_maximos' => $this->faker->numberBetween(15, 40),
                'imagen' => null,
                'activo' => true,
            ]);
        }
        $this->command->info('  • 10 talleres creados.');
    }

    // =====================================================================
    private function printResumen(): void
    {
        $this->command->info('');
        $this->command->info('========== RESUMEN DE DATOS ==========');
        $tablas = [
            'users', 'personas', 'docentes', 'tutores', 'administradores', 'estudiantes',
            'gestions', 'periodos', 'nivels', 'turnos', 'grados', 'cursos',
            'docente_curso', 'matriculas', 'horarios', 'notas', 'asistencias',
            'comportamientos', 'reportes', 'tutor_estudiante', 'mensajes',
            'mensaje_destinatarios', 'notificaciones', 'blogs', 'talleres', 'configuracion',
        ];
        foreach ($tablas as $tabla) {
            $count = DB::table($tabla)->count();
            $this->command->info(str_pad($tabla, 25, ' ') . ": $count");
        }
        $this->command->info('=======================================');
        $this->command->info('Login admin:   admin@adonai.edu.pe / Admin123');
        $this->command->info('Login docente: (ver tabla users, rol docente) / Docente123');
        $this->command->info('Login tutor:   (ver tabla users, rol tutor) / Tutor123');
    }
}
