<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('blogs', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->string('categoria'); // Premios, Concurso, Académico, etc.
        $table->string('portada')->nullable(); // imagen
        $table->date('fecha');
        $table->string('autor')->nullable();
        $table->text('descripcion_corta');
        $table->longText('contenido'); // texto largo del post
        $table->json('tags')->nullable();
        $table->timestamps();
    });
}
};