<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABLA ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            // REQUERIDO POR SPATIE:
            $table->string('name')->unique();       
            $table->string('guard_name')->default('web'); 
            
            // TUS CAMPOS PERSONALIZADOS (Los mantengo intactos):
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            
            $table->timestamps();
        });

        // 2. TABLA PERMISOS
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            // REQUERIDO POR SPATIE:
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            
            // TUS CAMPOS PERSONALIZADOS (Los mantengo intactos):
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->string('module')->nullable()->index();
            
            $table->timestamps();
        });

        // 3. TABLA PIVOTE: USUARIOS <-> ROLES
        // Spatie la llama 'model_has_roles' obligatoriamente
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type'); // Spatie necesita saber si es User u otro modelo
            $table->unsignedBigInteger('model_id'); // El ID del usuario
            $table->index(['model_id', 'model_type']);

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // 4. TABLA PIVOTE: ROLES <-> PERMISOS
        // Spatie la llama 'role_has_permissions' obligatoriamente
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        // 5. TABLA PIVOTE: USUARIOS <-> PERMISOS DIRECTOS
        // Spatie la llama 'model_has_permissions'
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type']);

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
};