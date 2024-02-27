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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact');
            $table->string('email');
            $table->string('document');
            $table->string('rdv');
            $table->dateTime('dateRdv');
            $table->string('typeServices')->nullable();
            $table->string('consultVar')->nullable();
            $table->string('prog')->nullable();
            $table->string('details');
            $table->string('type');
            $table->integer('couts');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
