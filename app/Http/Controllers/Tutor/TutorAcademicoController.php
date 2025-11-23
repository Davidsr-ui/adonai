<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Horario;
use App\Models\Curso;
use App\Models\Mensaje;
use App\Models\MensajeDestinatario;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TutorAcademicoController extends Controller
{
    /**
     * Obtener el tutor actual (CORREGIDO)
     */
    private function obtenerTutorActual()
    {
        $user = Auth::user();
        
        // CAMBIO: Buscar persona por user_id
        $persona = Persona::where('user_id', $user->id)->first();
        
        if (!$persona) {
            \Log::error('Usuario sin persona vinculada', ['user_id' => $user->id]);
            return null;
        }
        
        // Buscar el tutor usando persona_id
        $tutor = Tutor::where('persona_id', $persona->id)->first();
        
        if (!$tutor) {
            \Log::error('No se encontró tutor', [
                'user_id' => $user->id,
                'persona_id' => $persona->id
            ]);
        }
        
        return $tutor;
    }

    /**
     * CUS14 - Consultar Horarios - CORREGIDO: Solo cursos matriculados
     */
    public function horarios()
    {
        $tutor = $this->obtenerTutorActual();
        
        if (!$tutor) {
            return redirect()->route('tutor.dashboard')
                ->with('mensaje', 'No se encontró información de tutor')
                ->with('icono', 'error');
        }

        $tutor->load(['estudiantes.persona', 'estudiantes.grado.nivel']);
        
        $estudiante = $tutor->estudiantes->first();
        $estudiante_id = request('estudiante_id', $estudiante?->id);
        
        if ($estudiante_id) {
            $estudiante = Estudiante::with(['grado.nivel', 'persona'])->find($estudiante_id);
        }
        
        $horarios = [];
        if ($estudiante && $estudiante->grado) {
            // ✅ CORRECCIÓN: Obtener solo IDs de cursos donde el estudiante está matriculado
            $cursosMatriculadosIds = DB::table('matriculas')
                ->where('estudiante_id', $estudiante->id)
                ->where('estado', 'Matriculado')
                ->pluck('curso_id');
            
            // ✅ Filtrar horarios SOLO de esos cursos
            if ($cursosMatriculadosIds->isNotEmpty()) {
                $horarios = Horario::where('grado_id', $estudiante->grado_id)
                                  ->whereIn('curso_id', $cursosMatriculadosIds)  // ✅ FILTRO CRÍTICO
                                  ->with(['curso', 'docente.persona'])
                                  ->orderBy('dia_semana')
                                  ->orderBy('hora_inicio')
                                  ->get();
            }
        }
        
        $horarioSemanal = $this->organizarHorarioSemanal($horarios);
        
        return view('tutor.horarios', compact('tutor', 'estudiante', 'horarioSemanal'));
    }

    /**
     * CUS4 - Consultar Cursos - CORREGIDO: Solo cursos matriculados
     */
    public function cursos()
    {
        $tutor = $this->obtenerTutorActual();
        
        if (!$tutor) {
            return redirect()->route('tutor.dashboard')
                ->with('mensaje', 'No se encontró información de tutor')
                ->with('icono', 'error');
        }

        $tutor->load(['estudiantes.persona', 'estudiantes.grado.nivel']);
        
        $estudiante = $tutor->estudiantes->first();
        $estudiante_id = request('estudiante_id', $estudiante?->id);
        
        if ($estudiante_id) {
            $estudiante = Estudiante::with(['grado.nivel', 'persona'])->find($estudiante_id);
        }
        
        $cursos = [];
        if ($estudiante && $estudiante->grado) {
            // ✅ CORRECCIÓN: Filtrar solo cursos donde el estudiante está matriculado
            $cursos = DB::table('matriculas as m')
                ->join('cursos as c', 'm.curso_id', '=', 'c.id')
                ->join('docente_curso as dc', function($join) use ($estudiante) {
                    $join->on('dc.curso_id', '=', 'c.id')
                         ->where('dc.grado_id', '=', $estudiante->grado_id);
                })
                ->join('docentes as d', 'dc.docente_id', '=', 'd.id')
                ->join('personas as p', 'd.persona_id', '=', 'p.id')
                ->where('m.estudiante_id', $estudiante->id)
                ->where('m.estado', 'Matriculado')
                ->select(
                    'c.id',
                    'c.nombre as curso_nombre',
                    'c.codigo',
                    'c.horas_semanales',
                    'c.area_curricular',
                    'p.nombres',
                    'p.apellidos',
                    'p.user_id',
                    'd.id as docente_id'
                )
                ->distinct()
                ->get();
        }
        
        return view('tutor.cursos', compact('tutor', 'estudiante', 'cursos'));
    }

    /**
     * CUS19 - Mensajería
     */
    public function mensajes()
    {
        $user = Auth::user();
        
        $tutor = $this->obtenerTutorActual();
        
        if (!$tutor) {
            return redirect()->route('tutor.dashboard')
                ->with('mensaje', 'No se encontró información de tutor')
                ->with('icono', 'error');
        }

        $tutor->load(['estudiantes.persona']);
        
        $mensajesEnviados = Mensaje::enviadosPor($user->id)
                                   ->with(['estudiante.persona', 'destinatarios.destinatario.persona'])
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        $mensajesRecibidos = Mensaje::recibidosPor($user->id)
                                    ->with(['remitente.persona', 'estudiante.persona'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        
        $mensajesNoLeidos = Mensaje::noLeidosPor($user->id)->count();
        
        $docentes = $this->obtenerDocentesDeEstudiantes($tutor);
        
        return view('tutor.mensajes', compact('tutor', 'mensajesEnviados', 'mensajesRecibidos', 'mensajesNoLeidos', 'docentes'));
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

            return redirect()->route('tutor.mensajeria')
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

        return view('tutor.mensaje-detalle', compact('mensaje'));
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

            return redirect()->route('tutor.mensajeria')
                ->with('mensaje', 'Respuesta enviada correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al enviar la respuesta')
                ->with('icono', 'error');
        }
    }

    // =========================================
    // MÉTODOS AUXILIARES
    // =========================================

    private function organizarHorarioSemanal($horarios)
    {
        $horarioSemanal = [];

        foreach ($horarios as $horario) {
            $dia = $horario->dia_semana;
            $hora = substr($horario->hora_inicio, 0, 5) . ' - ' . substr($horario->hora_fin, 0, 5);
            
            if (!isset($horarioSemanal[$hora])) {
                $horarioSemanal[$hora] = [];
            }
            
            $horarioSemanal[$hora][$dia] = [
                'curso' => $horario->curso->nombre ?? 'N/A',
                'docente' => $horario->docente->persona->nombres ?? 'N/A',
                'aula' => $horario->aula ?? '-'
            ];
        }

        return $horarioSemanal;
    }

    private function obtenerDocentesDeEstudiantes($tutor)
    {
        $docentes = collect();

        foreach ($tutor->estudiantes as $estudiante) {
            // ✅ CORRECCIÓN: Obtener solo IDs de cursos donde el estudiante está matriculado
            $cursosMatriculados = DB::table('matriculas')
                ->where('estudiante_id', $estudiante->id)
                ->where('estado', 'Matriculado')
                ->pluck('curso_id');

            if ($cursosMatriculados->isNotEmpty()) {
                // ✅ Obtener SOLO docentes de esos cursos específicos
                $docentesMatriculados = DB::table('docente_curso as dc')
                    ->join('docentes as d', 'dc.docente_id', '=', 'd.id')
                    ->join('personas as p', 'd.persona_id', '=', 'p.id')
                    ->join('cursos as c', 'dc.curso_id', '=', 'c.id')
                    ->whereIn('dc.curso_id', $cursosMatriculados)  // ✅ Solo cursos matriculados
                    ->where('dc.grado_id', $estudiante->grado_id)
                    ->select(
                        'd.id as docente_id',
                        'p.user_id',
                        'p.nombres',
                        'p.apellidos',
                        'c.nombre as curso',
                        DB::raw($estudiante->id . ' as estudiante_id')
                    )
                    ->get();
                
                $docentes = $docentes->merge($docentesMatriculados);
            }
        }

        return $docentes->unique('user_id');
    }
}