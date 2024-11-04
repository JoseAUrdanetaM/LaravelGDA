<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relación con la tabla de usuarios
            $table->string('token')->unique(); // Almacena el token SHA-1
            $table->timestamp('expires_at'); // Tiempo de expiración
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Relación con usuarios
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
