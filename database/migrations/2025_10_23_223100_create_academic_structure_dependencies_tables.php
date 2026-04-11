<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // GRADOS (Depende de Niveles y Turnos)
        Schema::create('grados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('cascade');
            $table->foreignId('turno_id')->nullable()->constrained('turnos')->onDelete('set null');
            $table->string('nombre', 100);
            $table->string('seccion', 10)->nullable();
            $table->integer('capacidad_maxima')->default(30);
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // CURSOS (Ahora depende de Grados y Niveles)
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('cascade');
            // CAMBIO CLAVE: Agregamos la relación con grados
            $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade'); 
            
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique()->nullable();
            $table->integer('creditos')->default(1);
            $table->integer('horas_semanales')->default(2);
            $table->string('area_curricular', 100)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // ESTUDIANTES (Depende de Personas y Grados)
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade')->unique();
            $table->foreignId('grado_id')->nullable()->constrained('grados')->onDelete('set null'); 
            $table->string('codigo_estudiante', 50)->unique();
            $table->year('año_ingreso');
            $table->enum('condicion', ['Regular', 'Irregular', 'Retirado'])->default('Regular');
            $table->timestamps();
        });

        // TUTOR_ESTUDIANTE (Depende de Tutores y Estudiantes)
        Schema::create('tutor_estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutores')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->enum('relacion_familiar', ['Padre', 'Madre', 'Tutor Legal', 'Abuelo/a', 'Tío/a', 'Hermano/a', 'Otro']);
            $table->enum('tipo', ['Principal', 'Secundario'])->default('Principal');
            $table->boolean('autorizacion_recojo')->default(true);
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
            $table->unique(['tutor_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_estudiante');
        Schema::dropIfExists('estudiantes');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('grados');
    }
};