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
        Schema::create('proprietaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_personne'); // clé étrangère brute
            $table->foreign('id_personne')              // définition manuelle de la contrainte
            ->references('id_personne')
                ->on('personnes')
                ->onDelete('cascade');
            $table->string('ninea', 20);
            $table->string('rccm', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proprietaires');
    }
};
