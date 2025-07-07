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
        Schema::create('contrat_locations', function (Blueprint $table) {
            $table->id('id_contrat_location');
            $table->string('numero');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->date('date_creation')->nullable();

            $table->unsignedBigInteger('id_appartement');
            $table->foreign('id_appartement')->references('id_appartement')->on('appartements')->onDelete('cascade');
            $table->foreignId('id_locataire')->constrained('locataires')->onDelete('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_locations');
    }
};
