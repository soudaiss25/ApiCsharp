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
        Schema::create('appartements', function (Blueprint $table) {
            $table->id('id_appartement');
            $table->string('adresse_appartement', 100);
            $table->float('surface')->nullable();
            $table->integer('nombre_piece')->nullable();
            $table->foreignId('id_proprietaire')->nullable()->constrained('proprietaires')->onDelete('set null');
            $table->unsignedBigInteger('id_type_appartement');
            $table->foreign('id_type_appartement')->references('id_type')->on('type_appartements')->onDelete('cascade');

            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartements');
    }
};
