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
        Schema::create('consultations_en_ligne', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs');
            $table->foreignId('consultant_id')->constrained('consultants_medicaux');
            $table->string('specialite');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('lien_consultation')->nullable();
            $table->string('statut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations_en_ligne');
    }
};
