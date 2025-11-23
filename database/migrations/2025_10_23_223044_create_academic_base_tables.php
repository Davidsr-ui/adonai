<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ... en create_academic_base_tables.php
public function up(): void
{
    Schema::create('gestions', function (Blueprint $table) {
        $table->id();
        $table->year('año')->nullable();
        $table->string('nombre', 100);
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->enum('estado', ['Activo', 'Finalizado', 'Planificado'])->default('Planificado');
        $table->timestamps();
    });

    Schema::create('periodos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
        $table->string('nombre', 50);
        $table->integer('numero');
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->enum('estado', ['Activo', 'Finalizado', 'Planificado'])->default('Planificado');
        $table->timestamps();
        $table->unique(['gestion_id', 'numero']);
    });

    Schema::create('nivels', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 50)->unique();
        $table->text('descripcion')->nullable();
        $table->integer('orden')->default(0);
        $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
        $table->timestamps();
    });

    Schema::create('turnos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 50);
        $table->time('hora_inicio');
        $table->time('hora_fin');
        $table->enum('estado', ['activo', 'inactivo'])->default('activo');
        $table->text('descripcion')->nullable();
        $table->timestamps();
    });
}
};
