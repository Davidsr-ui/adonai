<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NivelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $niveles = Nivel::orderBy('orden', 'asc')->get();
        return view('admin.niveles.index', compact('niveles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.niveles.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_create' => 'required|max:50|unique:nivels,nombre',
            'descripcion_create' => 'nullable',
            'orden_create' => 'nullable|integer|min:0',
            'estado_create' => 'required|in:Activo,Inactivo',
        ], [
            'nombre_create.required' => 'El nombre del nivel es obligatorio',
            'nombre_create.unique' => 'Ya existe un nivel con ese nombre',
        ]);
        
        $nivel = new Nivel();
        $nivel->nombre = $request->nombre_create;
        $nivel->descripcion = $request->descripcion_create;
        $nivel->orden = $request->orden_create ?? 0;
        $nivel->estado = $request->estado_create;
        $nivel->save();
        
        return redirect()->route('admin.niveles.index')
            ->with('mensaje', 'Nivel creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nivel $nivel)
    {
        return view('admin.niveles.show', compact('nivel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nivel $nivel)
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.niveles.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nivel = Nivel::findOrFail($id);
        
        // ✅ VALIDACIÓN MEJORADA usando Rule::unique
        $validate = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'max:50',
                Rule::unique('nivels', 'nombre')->ignore($nivel->id)
            ],
            'descripcion' => 'nullable',
            'orden' => 'nullable|integer|min:0',
            'estado' => 'required|in:Activo,Inactivo',
        ], [
            'nombre.required' => 'El nombre del nivel es obligatorio',
            'nombre.unique' => 'Ya existe otro nivel con ese nombre',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);  // ✅ Usa $id en lugar de $nivel->id
        }

        $nivel->nombre = $request->nombre;
        $nivel->descripcion = $request->descripcion;
        $nivel->orden = $request->orden ?? 0;
        $nivel->estado = $request->estado;
        $nivel->save();

        return redirect()->route('admin.niveles.index')
            ->with('mensaje', 'Nivel actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $nivel = Nivel::findOrFail($id);
            
            // ✅ VALIDACIÓN: Verificar si tiene grados asociados
            if ($nivel->grados()->count() > 0) {
                return redirect()->route('admin.niveles.index')
                    ->with('mensaje', 'No se puede eliminar: el nivel tiene ' . $nivel->grados()->count() . ' grado(s) asociado(s)')
                    ->with('icono', 'error');
            }
            
            $nivel->delete();

            return redirect()->route('admin.niveles.index')
                ->with('mensaje', 'Nivel eliminado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.niveles.index')
                ->with('mensaje', 'Error al eliminar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}