<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('talleres', function (Blueprint $table) {

            // NUEVOS CAMPOS
            $table->date('duracion_inicio')->nullable()->after('instructor');
            $table->date('duracion_fin')->nullable()->after('duracion_inicio');

            $table->time('horario_inicio')->nullable();
            $table->time('horario_fin')->nullable();

            $table->string('categoria')->nullable()->after('horario_fin');

            // ELIMINAR CAMPOS ANTIGUOS
            if (Schema::hasColumn('talleres', 'duracion')) {
                $table->dropColumn('duracion');
            }
            if (Schema::hasColumn('talleres', 'horario')) {
                $table->dropColumn('horario');
            }
        });
    }

    public function down()
    {
        Schema::table('talleres', function (Blueprint $table) {

            // Restaurar campos viejos si hiciera falta
            $table->string('duracion')->nullable();
            $table->string('horario')->nullable();

            // Quitar nuevos campos
            $table->dropColumn([
                'duracion_inicio',
                'duracion_fin',
                'horario_inicio',
                'horario_fin',
                'categoria'
            ]);
        });
    }
};
