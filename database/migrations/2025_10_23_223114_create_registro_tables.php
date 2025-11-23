<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // ... en create_registro_tables.php
public function up(): void
{
    // NOTAS
    Schema::create('notas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matricula_id')->constrained('matriculas')->onDelete('cascade');
        $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->decimal('nota_practica', 5, 2)->nullable();
        $table->decimal('nota_teoria', 5, 2)->nullable();
        $table->decimal('nota_final', 5, 2);
        $table->enum('tipo_evaluacion', ['Parcial', 'Final', 'Práctica', 'Oral', 'Trabajo'])->default('Parcial');
        $table->text('descripcion')->nullable();
        $table->text('observaciones')->nullable();
        $table->date('fecha_evaluacion')->nullable();
        $table->boolean('visible_tutor')->default(false);
        $table->timestamp('fecha_publicacion')->nullable();
        $table->timestamps();
    });

    // ASISTENCIAS
    Schema::create('asistencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
        $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('set null');
        $table->date('fecha');
        $table->enum('estado', ['Presente', 'Ausente', 'Tardanza', 'Justificado']);
        $table->text('observaciones')->nullable();
        $table->timestamps();
    });

    // COMPORTAMIENTOS
    Schema::create('comportamientos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
        $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('set null');
        $table->date('fecha');
        $table->text('descripcion');
        $table->enum('tipo', ['Positivo', 'Negativo', 'Neutro'])->default('Neutro');
        $table->string('sancion', 255)->nullable();
        $table->boolean('notificado_tutor')->default(false);
        $table->timestamp('fecha_notificacion')->nullable();
        $table->timestamps();
    });

    // REPORTES
    Schema::create('reportes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
        $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
        $table->enum('tipo', ['Bimestral', 'Trimestral', 'Anual']);
        $table->decimal('promedio_general', 5, 2)->nullable();
        $table->decimal('porcentaje_asistencia', 5, 2)->nullable();
        $table->text('comentario_final')->nullable();
        $table->string('archivo_pdf', 255)->nullable();
        $table->boolean('visible_tutor')->default(false);
        $table->timestamp('fecha_generacion')->nullable();
        $table->timestamp('fecha_publicacion')->nullable();
        $table->timestamps();
    });
}
};
