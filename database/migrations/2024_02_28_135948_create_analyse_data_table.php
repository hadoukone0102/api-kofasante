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
        Schema::create('analyse_data', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact');
            $table->string('email');
            $table->string('sexe');
            $table->string('age');
            $table->string('type');
            $table->string('taille')->nullable();
            $table->string('poids')->nullable();
            $table->string('systolique')->nullable();
            $table->string('diastolique')->nullable();
            $table->string('valeurGly')->nullable();
            $table->string('valeurTemp')->nullable();
            $table->string('condition')->nullable();
            $table->string('unite')->nullable();
            $table->string('interpretation')->nullable();
            $table->string('conseil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analyse_data');
    }
};
