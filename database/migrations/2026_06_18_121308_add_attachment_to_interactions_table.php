<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('interactions', function (Blueprint $table) {
            // Coluna string nullable para guardar o caminho do anexo
            $table->string('attachment')->nullable()->after('comment');
        });
    }

    public function down(): void
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
