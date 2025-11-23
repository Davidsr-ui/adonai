<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ... en create_academic_processes_tables.php
public function up(): void
{
    // DOCENTE_CURSO (Asignación)
    Schema::create('docente_curso', function (Blueprint $table) {
        $table->id();
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
        $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade');
        $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
        $table->boolean('es_tutor_aula')->default(false);
        $table->timestamps();
        $table->unique(['docente_id', 'curso_id', 'grado_id', 'gestion_id'], 'unique_asignacion');
    });

    // MATRICULAS (Registro de Estudiante en un Curso/Gestión)
    Schema::create('matriculas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
        $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade');
        $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
        $table->enum('estado', ['Matriculado', 'Retirado', 'Aprobado', 'Desaprobado'])->default('Matriculado');
        $table->timestamps();
        $table->unique(['estudiante_id', 'curso_id', 'gestion_id'], 'unique_matricula');
    });

    // HORARIOS
    Schema::create('horarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
        $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade');
        $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('set null');
        $table->enum('dia_semana', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']);
        $table->time('hora_inicio');
        $table->time('hora_fin');
        $table->string('aula', 20)->nullable();
        $table->timestamps();
    });
}
};
