<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('talleres', function (Blueprint $table) {
            $table->id();

            // Datos básicos
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('instructor');

            // DURACIÓN (solo fechas)
            $table->date('duracion_inicio')->nullable();
            $table->date('duracion_fin')->nullable();

            // HORARIO (solo horas)
            $table->time('horario_inicio')->nullable();
            $table->time('horario_fin')->nullable();

            // Extras
            $table->string('categoria')->nullable();
            $table->decimal('costo', 8, 2)->nullable();
            $table->integer('cupos_maximos')->default(0);

            // Imagen y estado
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talleres');
    }
};
