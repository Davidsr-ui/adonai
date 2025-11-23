<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // ... en create_system_tables.php
public function up(): void
{
    // MENSAJES
    Schema::create('mensajes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('remitente_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes')->onDelete('set null');
        $table->string('asunto', 255);
        $table->text('contenido');
        $table->enum('prioridad', ['Baja', 'Normal', 'Alta', 'Urgente'])->default('Normal');
        $table->enum('tipo', ['Individual', 'Grupal'])->default('Individual');
        $table->json('archivos')->nullable();
        $table->timestamps();
    });

    // MENSAJE_DESTINATARIOS
    Schema::create('mensaje_destinatarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mensaje_id')->constrained('mensajes')->onDelete('cascade');
        $table->foreignId('destinatario_id')->constrained('users')->onDelete('cascade');
        $table->boolean('leido')->default(false);
        $table->timestamp('fecha_lectura')->nullable();
        $table->timestamps();
    });

    // NOTIFICACIONES
    Schema::create('notificaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->enum('tipo', ['Nota Nueva', 'Asistencia', 'Comportamiento', 'Mensaje', 'Comunicado', 'Sistema']);
        $table->string('titulo', 255);
        $table->text('descripcion')->nullable();
        $table->bigInteger('referencia_id')->unsigned()->nullable();
        $table->string('referencia_tabla', 50)->nullable();
        $table->string('url', 255)->nullable();
        $table->boolean('leido')->default(false);
        $table->timestamp('fecha_lectura')->nullable();
        $table->timestamps();
    });

    // CONFIGURACION
    Schema::create('configuracion', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 255)->nullable(); 
        $table->string('descripcion', 255)->nullable();
        $table->string('direccion', 255)->nullable();
        $table->string('telefono', 20)->nullable();
        $table->string('divisa', 10)->nullable();
        $table->string('email', 255)->nullable();
        $table->string('web', 255)->nullable();
        $table->string('logo', 255)->nullable();
        $table->string('clave', 100)->unique()->default('GLOBAL_SETTINGS');
        $table->text('valor')->nullable();
        $table->enum('tipo', ['Texto', 'Numero', 'Fecha', 'Boolean', 'JSON'])->default('Texto');
        $table->enum('categoria', ['Academico', 'Calificacion', 'General', 'Seguridad', 'Notificaciones'])->default('General');
        $table->boolean('editable')->default(true);
        $table->timestamps();
    });

    // ACTIVITY_LOG
    Schema::create('activity_log', function (Blueprint $table) {
        $table->id();
        $table->string('log_name', 255)->nullable();
        $table->text('description');
        $table->string('subject_type', 255)->nullable();
        $table->bigInteger('subject_id')->unsigned()->nullable();
        $table->string('causer_type', 255)->nullable();
        $table->bigInteger('causer_id')->unsigned()->nullable();
        $table->json('properties')->nullable();
        $table->timestamps();
    });
}
};
