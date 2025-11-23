<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Mensaje;
use App\Models\MensajeDestinatario;
use App\Models\Tutor;
use App\Models\Asistencia;
use App\Models\Nota;
use App\Models\Comportamiento;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DocenteAcademicoController extends Controller
{
    /**
     * Obtener el docente actual (CORREGIDO)
     */
    private function obtenerDocenteActual()
    {
        $user = Auth::user();
        
        // CAMBIO: Buscar persona por user_id
        $persona = Persona::where('user_id', $user->id)->first();
        
        if (!$persona) {
            \Log::error('Usuario sin persona vinculada', ['user_id' => $user->id]);
            return null;
        }
        
        // Buscar el docente usando persona_id
        $docente = Docente::where('persona_id', $persona->id)->first();
        
        if (!$docente) {
            \Log::error('No se encontró docente', [
                'user_id' => $user->id,
                'persona_id' => $persona->id
            ]);
        }
        
        return $docente;
    }

    /**
     * CUS24 - Consultar Ficha de Alumno - CORREGIDO CON ELOQUENT
     */
    public function fichaAlumno($id)
    {
        $docente = $this->obtenerDocenteActual();

        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'No se encontró información de docente')
                ->with('icono', 'error');
        }

        // ✅ USAR ELOQUENT para cargar estudiante
        $estudiante = Estudiante::with([
            'persona',
            'grado.nivel',
            'tutores.persona'
        ])->findOrFail($id);

        // Verificar acceso
        $tieneAcceso = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->where('grado_id', $estudiante->grado_id)
            ->exists();

        if (!$tieneAcceso) {
            abort(403, 'No tiene acceso a este estudiante');
        }

        // Obtener IDs de cursos del docente
        $cursosDocente = DB::table('docente_curso as dc')
            ->join('cursos as c', 'dc.curso_id', '=', 'c.id')
            ->where('dc.docente_id', $docente->id)
            ->where('dc.grado_id', $estudiante->grado_id)
            ->pluck('c.id');

        // ✅ USAR ELOQUENT para asistencias
        $asistencias = Asistencia::where('estudiante_id', $estudiante->id)
            ->whereIn('curso_id', $cursosDocente)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Calcular estadísticas de asistencia
        $totalAsistencias = Asistencia::where('estudiante_id', $estudiante->id)
            ->whereIn('curso_id', $cursosDocente)
            ->count();
        $asistenciasPresente = Asistencia::where('estudiante_id', $estudiante->id)
            ->whereIn('curso_id', $cursosDocente)
            ->where('estado', 'Presente')
            ->count();
        $porcentajeAsistencia = $totalAsistencias > 0 
            ? round(($asistenciasPresente / $totalAsistencias) * 100, 2) 
            : 0;

        // ✅ USAR ELOQUENT para comportamientos
        $comportamientos = Comportamiento::where('estudiante_id', $estudiante->id)
            ->where('docente_id', $docente->id)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        // ✅ CAMBIO CRÍTICO: Usar Eloquent para notas en lugar de DB::table()
        $matriculasIds = Matricula::where('estudiante_id', $estudiante->id)
            ->whereIn('curso_id', $cursosDocente)
            ->pluck('id');

        $notas = Nota::whereIn('matricula_id', $matriculasIds)
            ->with(['matricula.curso', 'periodo'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ USAR ELOQUENT para tutores (mejor obtenerlos desde el estudiante)
        $tutores = DB::table('tutor_estudiante as te')
            ->join('tutores as t', 'te.tutor_id', '=', 't.id')
            ->join('personas as p', 't.persona_id', '=', 'p.id')
            ->where('te.estudiante_id', $estudiante->id)
            ->where('te.estado', 'Activo')
            ->select(
                't.id',
                'p.user_id',
                'p.nombres',
                'p.apellidos',
                'p.telefono',
                'p.telefono_emergencia',
                'te.relacion_familiar',
                'te.tipo'
            )
            ->get();

        return view('docente.ficha-alumno', compact(
            'estudiante',
            'docente',
            'asistencias',
            'porcentajeAsistencia',
            'comportamientos',
            'notas',
            'tutores'
        ));
    }

    /**
     * Listar alumnos - CORREGIDO: Solo estudiantes en cursos del docente
     */
    public function misAlumnos()
    {
        $docente = $this->obtenerDocenteActual();

        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'No se encontró información de docente')
                ->with('icono', 'error');
        }

        // ✅ CORRECCIÓN: Obtener IDs de los CURSOS del docente (no grados)
        $cursosIds = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->pluck('curso_id')
            ->unique();

        // ✅ CORRECCIÓN: Traer SOLO estudiantes matriculados en esos cursos
        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)
                  ->where('estado', 'Matriculado');
        })
        ->with(['persona', 'grado.nivel'])
        ->orderBy('grado_id')
        ->get();

        return view('docente.mis-alumnos', compact('estudiantes', 'docente'));
    }

    /**
     * CUS26 - Mensajería
     */
    public function mensajes()
    {
        $user = Auth::user();
        
        $docente = $this->obtenerDocenteActual();

        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'No se encontró información de docente')
                ->with('icono', 'error');
        }

        $mensajesEnviados = Mensaje::enviadosPor($user->id)
            ->with(['estudiante.persona', 'destinatarios.destinatario.persona'])
            ->orderBy('created_at', 'desc')
            ->get();

        $mensajesRecibidos = Mensaje::recibidosPor($user->id)
            ->with(['remitente.persona', 'estudiante.persona'])
            ->orderBy('created_at', 'desc')
            ->get();

        $mensajesNoLeidos = Mensaje::noLeidosPor($user->id)->count();

        $tutores = $this->obtenerTutoresDeAlumnos($docente);

        return view('docente.mensajes', compact(
            'docente',
            'mensajesEnviados',
            'mensajesRecibidos',
            'mensajesNoLeidos',
            'tutores'
        ));
    }

    /**
     * Enviar mensaje
     */
    public function enviarMensaje(Request $request)
    {
        $request->validate([
            'destinatario_user_id' => 'required|exists:users,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'asunto' => 'required|max:255',
            'contenido' => 'required',
            'prioridad' => 'nullable|in:Baja,Normal,Alta,Urgente',
            'archivos.*' => 'nullable|file|max:10240'
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $archivosGuardados = [];
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                    $path = $archivo->store('mensajes/archivos', 'public');
                    $archivosGuardados[] = [
                        'nombre' => $archivo->getClientOriginalName(),
                        'path' => $path,
                        'tamaño' => $archivo->getSize()
                    ];
                }
            }

            $mensaje = Mensaje::create([
                'remitente_id' => $user->id,
                'estudiante_id' => $request->estudiante_id,
                'asunto' => $request->asunto,
                'contenido' => $request->contenido,
                'prioridad' => $request->prioridad ?? 'Normal',
                'tipo' => 'Individual',
                'archivos' => !empty($archivosGuardados) ? $archivosGuardados : null
            ]);

            $mensaje->agregarDestinatarios([$request->destinatario_user_id]);

            DB::commit();

            return redirect()->route('docente.mensajeria')
                ->with('mensaje', 'Mensaje enviado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al enviar el mensaje: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Responder mensaje
     */
    public function responderMensaje(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required',
            'archivos.*' => 'nullable|file|max:10240'
        ]);

        $user = Auth::user();
        $mensajeOriginal = Mensaje::with('remitente')->findOrFail($id);

        if (!$mensajeOriginal->destinatarios->contains('destinatario_id', $user->id)) {
            abort(403, 'No autorizado');
        }

        DB::beginTransaction();
        try {
            $archivosGuardados = [];
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                    $path = $archivo->store('mensajes/archivos', 'public');
                    $archivosGuardados[] = [
                        'nombre' => $archivo->getClientOriginalName(),
                        'path' => $path,
                        'tamaño' => $archivo->getSize()
                    ];
                }
            }

            $mensaje = Mensaje::create([
                'remitente_id' => $user->id,
                'estudiante_id' => $mensajeOriginal->estudiante_id,
                'asunto' => 'RE: ' . $mensajeOriginal->asunto,
                'contenido' => $request->contenido,
                'prioridad' => $mensajeOriginal->prioridad,
                'tipo' => 'Individual',
                'archivos' => !empty($archivosGuardados) ? $archivosGuardados : null
            ]);

            $mensaje->agregarDestinatarios([$mensajeOriginal->remitente_id]);

            DB::commit();

            return redirect()->route('docente.mensajeria')
                ->with('mensaje', 'Respuesta enviada correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al enviar la respuesta')
                ->with('icono', 'error');
        }
    }

    /**
     * Ver mensaje
     */
    public function verMensaje($id)
    {
        $user = Auth::user();

        $mensaje = Mensaje::with(['remitente.persona', 'estudiante.persona', 'destinatarios.destinatario.persona'])
                          ->findOrFail($id);

        if ($mensaje->remitente_id != $user->id && !$mensaje->destinatarios->contains('destinatario_id', $user->id)) {
            abort(403, 'No autorizado');
        }

        if ($mensaje->destinatarios->contains('destinatario_id', $user->id)) {
            $mensaje->marcarComoLeido($user->id);
        }

        return view('docente.mensaje-detalle', compact('mensaje'));
    }

    // =========================================
    // MÉTODOS AUXILIARES
    // =========================================

    private function obtenerTutoresDeAlumnos($docente)
    {
        $gradosAsignados = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->pluck('grado_id');

        $estudiantesIds = Estudiante::whereIn('grado_id', $gradosAsignados)
            ->pluck('id');

        $tutores = DB::table('tutor_estudiante as te')
            ->join('tutores as t', 'te.tutor_id', '=', 't.id')
            ->join('personas as p', 't.persona_id', '=', 'p.id')
            ->join('estudiantes as e', 'te.estudiante_id', '=', 'e.id')
            ->join('personas as pe', 'e.persona_id', '=', 'pe.id')
            ->whereIn('te.estudiante_id', $estudiantesIds)
            ->where('te.estado', 'Activo')
            ->select(
                't.id as tutor_id',
                'p.user_id',
                'p.nombres as tutor_nombres',
                'p.apellidos as tutor_apellidos',
                'pe.nombres as estudiante_nombres',
                'pe.apellidos as estudiante_apellidos',
                'e.id as estudiante_id',
                'te.relacion_familiar'
            )
            ->get();

        return $tutores;
    }
}