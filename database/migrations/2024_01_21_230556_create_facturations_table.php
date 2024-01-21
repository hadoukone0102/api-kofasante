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
        Schema::create('facturations', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact');
            $table->string('email');
            $table->string('details')->nullable();
            $table->string('type')->nullable();
            $table->integer('couts')->nullable();
            $table->string('document')->nullable();
            $table->string('autreTypeDocs')->nullable();
            $table->string('rdv')->nullable();
            $table->string('autreTypeRDV')->nullable();
            $table->dateTime('dateRdv')->nullable();
            $table->string('consultant')->nullable();
            $table->string('tyeConsultation')->nullable();
            $table->dateTime('dateTot')->nullable();
            $table->dateTime('dateTard')->nullable();
            $table->string('services')->nullable();
            $table->string('typeServices')->nullable();
            $table->string('forfait')->nullable();
            $table->string('nombreVisite')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturations');
    }
};
