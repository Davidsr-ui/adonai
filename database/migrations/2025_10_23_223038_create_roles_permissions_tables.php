<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ... en create_roles_permissions_tables.php
public function up(): void
{
    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name', 50)->unique();
        $table->string('display_name', 100);
        $table->text('description')->nullable();
        $table->timestamps();
    });

    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100)->unique();
        $table->string('display_name', 150);
        $table->text('description')->nullable();
        $table->string('module', 50)->nullable()->index();
        $table->timestamps();
    });

    Schema::create('role_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
        $table->timestamps();
        $table->unique(['user_id', 'role_id']);
    });

    Schema::create('permission_role', function (Blueprint $table) {
        $table->id();
        $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
        $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
        $table->timestamps();
        $table->unique(['permission_id', 'role_id']);
    });
}
};
