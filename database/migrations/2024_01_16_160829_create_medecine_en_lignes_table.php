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
        Schema::create('medecine_en_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact');
            $table->string('email');
            $table->string('consultant');
            $table->string('tyeConsultation');
            $table->dateTime('dateTot');
            $table->dateTime('dateTard');
            $table->string('details');
            $table->string('type');
            $table->integer('couts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medecine_en_lignes');
    }
};
