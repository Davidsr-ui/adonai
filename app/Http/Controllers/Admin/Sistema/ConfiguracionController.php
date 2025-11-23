<?php

namespace App\Http\Controllers\Admin\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Configuracion::query();

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por editables
        if ($request->filled('editable')) {
            $query->where('editable', $request->editable);
        }

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%")
                  ->orWhere('clave', 'like', "%{$buscar}%");
            });
        }

        // Ordenamiento
        $orden = $request->get('orden', 'categoria');
        $direccion = $request->get('direccion', 'asc');
        $query->orderBy($orden, $direccion);

        $configuraciones = $query->paginate(15);

        // Estadísticas
        $estadisticas = [
            'total' => Configuracion::count(),
            'editables' => Configuracion::where('editable', true)->count(),
            'no_editables' => Configuracion::where('editable', false)->count(),
            'por_categoria' => Configuracion::select('categoria', DB::raw('count(*) as total'))
                ->groupBy('categoria')
                ->get(),
        ];

        // Categorías y tipos disponibles
        $categorias = ['Academico', 'Calificacion', 'General', 'Seguridad', 'Notificaciones'];
        $tipos = ['Texto', 'Numero', 'Fecha', 'Boolean', 'JSON'];

        return view('admin.configuracion.index', compact(
            'configuraciones',
            'estadisticas',
            'categorias',
            'tipos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = ['Academico', 'Calificacion', 'General', 'Seguridad', 'Notificaciones'];
        $tipos = ['Texto', 'Numero', 'Fecha', 'Boolean', 'JSON'];

        return view('admin.configuracion.create', compact('categorias', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clave' => 'required|string|max:100|unique:configuracion,clave',
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'valor' => 'nullable',
            'tipo' => 'required|in:Texto,Numero,Fecha,Boolean,JSON',
            'categoria' => 'required|in:Academico,Calificacion,General,Seguridad,Notificaciones',
            'editable' => 'boolean',
            
            // Campos adicionales opcionales
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'divisa' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'web' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'clave.required' => 'La clave es obligatoria.',
            'clave.unique' => 'Esta clave ya existe.',
            'tipo.required' => 'El tipo es obligatorio.',
            'categoria.required' => 'La categoría es obligatoria.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser jpeg, png, jpg o gif.',
            'logo.max' => 'El logo no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['logo']);
            
            // Procesar el valor según el tipo
            $data['valor'] = $this->procesarValor($request->valor, $request->tipo);

            // Manejar el logo
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $nombreLogo = time() . '_' . $logo->getClientOriginalName();
                $rutaLogo = $logo->storeAs('logos', $nombreLogo, 'public');
                $data['logo'] = $rutaLogo;
            }

            $configuracion = Configuracion::create($data);

            DB::commit();

            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuración creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear la configuración: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $configuracion = Configuracion::findOrFail($id);
        
        return view('admin.configuracion.show', compact('configuracion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $configuracion = Configuracion::findOrFail($id);
        $categorias = ['Academico', 'Calificacion', 'General', 'Seguridad', 'Notificaciones'];
        $tipos = ['Texto', 'Numero', 'Fecha', 'Boolean', 'JSON'];

        return view('admin.configuracion.edit', compact('configuracion', 'categorias', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $configuracion = Configuracion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'clave' => 'required|string|max:100|unique:configuracion,clave,' . $id,
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'valor' => 'nullable',
            'tipo' => 'required|in:Texto,Numero,Fecha,Boolean,JSON',
            'categoria' => 'required|in:Academico,Calificacion,General,Seguridad,Notificaciones',
            'editable' => 'boolean',
            
            // Campos adicionales opcionales
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'divisa' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'web' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'clave.required' => 'La clave es obligatoria.',
            'clave.unique' => 'Esta clave ya existe.',
            'tipo.required' => 'El tipo es obligatorio.',
            'categoria.required' => 'La categoría es obligatoria.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser jpeg, png, jpg o gif.',
            'logo.max' => 'El logo no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['logo']);
            
            // Procesar el valor según el tipo
            $data['valor'] = $this->procesarValor($request->valor, $request->tipo);

            // Manejar el logo
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if ($configuracion->logo && Storage::disk('public')->exists($configuracion->logo)) {
                    Storage::disk('public')->delete($configuracion->logo);
                }

                $logo = $request->file('logo');
                $nombreLogo = time() . '_' . $logo->getClientOriginalName();
                $rutaLogo = $logo->storeAs('logos', $nombreLogo, 'public');
                $data['logo'] = $rutaLogo;
            }

            $configuracion->update($data);

            DB::commit();

            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuración actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $configuracion = Configuracion::findOrFail($id);

            // Verificar si es editable
            if (!$configuracion->editable) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar una configuración no editable.');
            }

            // Eliminar logo si existe
            if ($configuracion->logo && Storage::disk('public')->exists($configuracion->logo)) {
                Storage::disk('public')->delete($configuracion->logo);
            }

            $configuracion->delete();

            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Configuración eliminada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la configuración: ' . $e->getMessage());
        }
    }

    /**
     * Procesar valor según el tipo
     */
    private function procesarValor($valor, $tipo)
    {
        if (is_null($valor)) {
            return null;
        }

        switch ($tipo) {
            case 'Numero':
                return is_numeric($valor) ? $valor : 0;
            
            case 'Boolean':
                return filter_var($valor, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
            
            case 'JSON':
                if (is_array($valor)) {
                    return json_encode($valor);
                }
                if (is_string($valor)) {
                    // Verificar si es JSON válido
                    json_decode($valor);
                    return (json_last_error() === JSON_ERROR_NONE) ? $valor : json_encode(['valor' => $valor]);
                }
                return json_encode($valor);
            
            case 'Fecha':
                try {
                    return \Carbon\Carbon::parse($valor)->toDateString();
                } catch (\Exception $e) {
                    return null;
                }
            
            default: // Texto
                return $valor;
        }
    }

    /**
     * Actualización rápida de valor
     */
    public function actualizarValor(Request $request, $id)
    {
        try {
            $configuracion = Configuracion::findOrFail($id);

            if (!$configuracion->editable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta configuración no es editable.'
                ], 403);
            }

            $valor = $this->procesarValor($request->valor, $configuracion->tipo);
            $configuracion->valor = $valor;
            $configuracion->save();

            return response()->json([
                'success' => true,
                'message' => 'Valor actualizado exitosamente.',
                'valor' => $configuracion->valor_formateado
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener configuración global del sistema
     */
    public function global()
    {
        $configuracionGlobal = Configuracion::where('clave', 'GLOBAL_SETTINGS')->first();

        if (!$configuracionGlobal) {
            // Crear configuración global por defecto
            $configuracionGlobal = Configuracion::create([
                'clave' => 'GLOBAL_SETTINGS',
                'nombre' => config('app.name'),
                'descripcion' => 'Configuración global del sistema',
                'categoria' => 'General',
                'tipo' => 'Texto',
                'editable' => true,
            ]);
        }

        return view('admin.configuracion.global', compact('configuracionGlobal'));
    }

    /**
     * Actualizar configuración global
     */
    public function actualizarGlobal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'divisa' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'web' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $configuracion = Configuracion::where('clave', 'GLOBAL_SETTINGS')->first();

            if (!$configuracion) {
                $configuracion = new Configuracion();
                $configuracion->clave = 'GLOBAL_SETTINGS';
            }

            $configuracion->nombre = $request->nombre;
            $configuracion->descripcion = $request->descripcion;
            $configuracion->direccion = $request->direccion;
            $configuracion->telefono = $request->telefono;
            $configuracion->divisa = $request->divisa;
            $configuracion->email = $request->email;
            $configuracion->web = $request->web;
            $configuracion->categoria = 'General';
            $configuracion->tipo = 'Texto';

            // Manejar el logo
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if ($configuracion->logo && Storage::disk('public')->exists($configuracion->logo)) {
                    Storage::disk('public')->delete($configuracion->logo);
                }

                $logo = $request->file('logo');
                $nombreLogo = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
                $rutaLogo = $logo->storeAs('logos', $nombreLogo, 'public');
                $configuracion->logo = $rutaLogo;
            }

            $configuracion->save();

            return redirect()->back()
                ->with('success', 'Configuración global actualizada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exportar configuraciones
     */
    public function exportar()
    {
        $configuraciones = Configuracion::all();

        $data = [];
        foreach ($configuraciones as $config) {
            $data[] = [
                'Clave' => $config->clave,
                'Nombre' => $config->nombre,
                'Descripción' => $config->descripcion,
                'Valor' => $config->valor_formateado,
                'Tipo' => $config->tipo,
                'Categoría' => $config->categoria,
                'Editable' => $config->editable ? 'Sí' : 'No',
            ];
        }

        return response()->json([
            'data' => $data,
            'filename' => 'configuraciones_' . date('Y-m-d_His') . '.json'
        ]);
    }

    /**
     * Restaurar configuraciones por defecto
     */
    public function restaurarDefecto()
    {
        try {
            DB::beginTransaction();

            // Aquí puedes definir las configuraciones por defecto
            $configuracionesDefecto = [
                [
                    'clave' => 'NOTA_MINIMA_APROBACION',
                    'nombre' => 'Nota Mínima de Aprobación',
                    'descripcion' => 'Nota mínima para aprobar un curso',
                    'valor' => '11',
                    'tipo' => 'Numero',
                    'categoria' => 'Academico',
                    'editable' => true,
                ],
                [
                    'clave' => 'NOTA_MAXIMA',
                    'nombre' => 'Nota Máxima',
                    'descripcion' => 'Nota máxima posible',
                    'valor' => '20',
                    'tipo' => 'Numero',
                    'categoria' => 'Academico',
                    'editable' => true,
                ],
                [
                    'clave' => 'PERIODOS_POR_GESTION',
                    'nombre' => 'Periodos por Gestión',
                    'descripcion' => 'Cantidad de periodos académicos por gestión',
                    'valor' => '4',
                    'tipo' => 'Numero',
                    'categoria' => 'Academico',
                    'editable' => true,
                ],
            ];

            foreach ($configuracionesDefecto as $config) {
                Configuracion::updateOrCreate(
                    ['clave' => $config['clave']],
                    $config
                );
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Configuraciones restauradas exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al restaurar configuraciones: ' . $e->getMessage());
        }
    }

    /**
     * Obtener configuración por categoría (API)
     */
    public function porCategoria($categoria)
    {
        $configuraciones = Configuracion::where('categoria', $categoria)->get();

        return response()->json([
            'success' => true,
            'data' => $configuraciones
        ]);
    }

    /**
     * Obtener valor de configuración (API)
     */
    public function obtenerValor($clave)
    {
        $valor = Configuracion::obtenerValor($clave);

        return response()->json([
            'success' => true,
            'clave' => $clave,
            'valor' => $valor
        ]);
    }
}