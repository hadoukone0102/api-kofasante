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
        Schema::create('media_teches', function (Blueprint $table) {
            $table->id();
            $table->integer('id_admin');
            $table->string('titre');
            $table->string('categorie');
            $table->string('media')->nullable();
            $table->string('type')->nullable();
            $table->longText('desc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_teches');
    }
};
