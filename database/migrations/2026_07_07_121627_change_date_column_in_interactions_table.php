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
        Schema::table('interactions', function (Blueprint $blueprint) {
            // Altera a coluna 'date' para aceitar data E hora
            $blueprint->dateTime('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interactions', function (Blueprint $blueprint) {
            // Caso precises de reverter, ela volta a ser apenas date
            $blueprint->date('date')->change();
        });
    }
};
