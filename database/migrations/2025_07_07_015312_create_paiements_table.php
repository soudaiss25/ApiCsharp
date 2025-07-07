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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id('id_paiement');
            $table->date('date_paiement');
            $table->float('montant');
            $table->string('numero_facture');

            $table->unsignedBigInteger('id_contrat_location');
            $table->foreign('id_contrat_location')->references('id_contrat_location')->on('contrat_locations')->onDelete('cascade');

            $table->unsignedBigInteger('id_mode_paiement');
            $table->foreign('id_mode_paiement')->references('id_mode_paiement')->on('mode_paiements')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
