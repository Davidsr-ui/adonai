<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // ... en create_personas_and_roles_specific_tables.php
public function up(): void
{
    // PERSONAS (Vínculo con users)
    Schema::create('personas', function (Blueprint $table) {
        $table->id();
        // VITAL: user_id es NULLABLE para personas que no tienen cuenta de login (ej. un estudiante menor)
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->index(); 
        $table->string('dni', 20)->unique();
        $table->string('nombres', 100);
        $table->string('apellidos', 100);
        $table->date('fecha_nacimiento');
        $table->enum('genero', ['M', 'F', 'Otro']);
        $table->string('direccion', 255)->nullable();
        $table->string('telefono', 20)->nullable();
        $table->string('telefono_emergencia', 20)->nullable();
        $table->string('foto_perfil', 255)->nullable();
        $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo')->index();
        $table->timestamps();
        $table->softDeletes(); // Para el campo deleted_at
    });

    // TUTORES
    Schema::create('tutores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade')->unique();
        $table->string('codigo_tutor', 50)->nullable()->unique();
        $table->string('ocupacion', 100)->nullable();
        $table->timestamps();
    });

    // DOCENTES
    Schema::create('docentes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade')->unique();
        $table->string('codigo_docente', 50)->unique();
        $table->string('especialidad', 100)->nullable();
        $table->date('fecha_contratacion');
        $table->enum('tipo_contrato', ['Nombrado', 'Contratado', 'Temporal'])->default('Contratado');
        $table->timestamps();
    });

    // ADMINISTRADORES
    Schema::create('administradores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade')->unique();
        $table->enum('cargo', ['Director', 'Subdirector', 'Secretario', 'Administrativo']);
        $table->string('area', 100)->nullable();
        $table->date('fecha_asignacion')->nullable();
        $table->timestamps();
    });
}
};
