<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    // ==========================================
    // MÉTODOS PARA EL PANEL DE ADMINISTRACIÓN
    // ==========================================

    /**
     * Listar posts (Admin)
     */
    public function index()
    {
        $posts = Blog::orderBy('fecha', 'desc')->get();
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Guardar nuevo post
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'categoria' => 'required',
            'descripcion_corta' => 'required',
            'contenido' => 'required',
            'fecha' => 'required|date',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('portada')) {
            $data['portada'] = $request->file('portada')->store('blog', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Publicación creada correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $post = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('post'));
    }

    /**
     * Actualizar post
     */
    public function update(Request $request, $id)
    {
        $post = Blog::findOrFail($id);

        $request->validate([
            'titulo' => 'required',
            'categoria' => 'required',
            'descripcion_corta' => 'required',
            'contenido' => 'required',
            'fecha' => 'required|date',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('portada')) {
            // Eliminar imagen anterior
            if ($post->portada && Storage::disk('public')->exists($post->portada)) {
                Storage::disk('public')->delete($post->portada);
            }

            $data['portada'] = $request->file('portada')->store('blog', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Publicación actualizada correctamente');
    }

    /**
     * Eliminar post
     */
    public function destroy($id)
    {
        $post = Blog::findOrFail($id);

        // Eliminar imagen si existe
        if ($post->portada && Storage::disk('public')->exists($post->portada)) {
            Storage::disk('public')->delete($post->portada);
        }

        $post->delete();

        return back()->with('success', 'Publicación eliminada correctamente');
    }

    // ==========================================
    // MÉTODOS PARA LA VISTA PÚBLICA
    // ==========================================

    /**
     * Mostrar blog público (lista de posts)
     */
    public function mostrarPublico()
    {
        $posts = Blog::orderBy('fecha', 'desc')->paginate(9);
        return view('blog', compact('posts'));
    }

    /**
     * Mostrar detalle de un post (público)
     */
    public function mostrarDetalle($id)
    {
        $post = Blog::findOrFail($id);
        
        // Posts relacionados (misma categoría)
        $relacionados = Blog::where('categoria', $post->categoria)
            ->where('id', '!=', $post->id)
            ->orderBy('fecha', 'desc')
            ->limit(3)
            ->get();
        
        return view('blog-detalle', compact('post', 'relacionados'));
    }
}