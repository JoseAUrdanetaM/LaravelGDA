<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsCommunesCustomersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear tabla 'regions'
        Schema::create('regions', function (Blueprint $table) {
            $table->id('id_reg');
            $table->string('description', 90);
            $table->enum('status', ['A', 'I', 'trash'])->default('A');
            $table->primary('id_reg');
        });

        // Crear tabla 'communes'
        Schema::create('communes', function (Blueprint $table) {
            $table->id('id_com');
            $table->unsignedBigInteger('id_reg');
            $table->string('description', 90);
            $table->enum('status', ['A', 'I', 'trash'])->default('A');
            $table->primary(['id_com', 'id_reg']);
            $table->foreign('id_reg')->references('id_reg')->on('regions')->onDelete('cascade');
        });

        // Crear tabla 'customers'
        Schema::create('customers', function (Blueprint $table) {
            $table->string('dni', 45)->comment('Documento de Identidad');
            $table->unsignedBigInteger('id_reg');
            $table->unsignedBigInteger('id_com');
            $table->string('email', 120)->unique()->comment('Correo Electrónico');
            $table->string('name', 45)->comment('Nombre');
            $table->string('last_name', 45)->comment('Apellido');
            $table->string('address', 255)->nullable()->comment('Dirección');
            $table->dateTime('date_reg')->comment('Fecha y hora del registro');
            $table->enum('status', ['A', 'I', 'trash'])->default('A')->comment('estado del registro: A : Activo, I : Desactivo, trash : Registro eliminado');
            $table->primary(['dni', 'id_reg', 'id_com']);
            $table->foreign('id_reg')->references('id_reg')->on('regions')->onDelete('cascade');
            $table->foreign('id_com')->references('id_com')->on('communes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('communes');
        Schema::dropIfExists('regions');
    }
}
