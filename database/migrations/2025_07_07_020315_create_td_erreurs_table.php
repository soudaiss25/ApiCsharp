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
        Schema::create('td_erreurs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_erreur')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('titre_erreur', 200)->nullable();
            $table->string('description_erreur', 2000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('td_erreurs');
    }
};
