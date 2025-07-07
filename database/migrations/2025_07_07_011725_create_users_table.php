<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_personne'); // clé étrangère brute
            $table->foreign('id_personne')              // définition manuelle de la contrainte
            ->references('id_personne')
                ->on('personnes')
                ->onDelete('cascade');

            $table->string('identifiant', 20)->unique();
            $table->string('mot_de_passe', 255);
            $table->string('status', 20)->nullable();
            $table->string('role', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
