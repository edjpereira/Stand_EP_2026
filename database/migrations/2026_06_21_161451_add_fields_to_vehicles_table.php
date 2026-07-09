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
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'year')) {
                $table->integer('year')->after('model')->default(2020);
            }
            if (!Schema::hasColumn('vehicles', 'kms')) {
                $table->integer('kms')->after('year')->default(0);
            }
            if (!Schema::hasColumn('vehicles', 'fuel')) {
                $table->string('fuel')->after('kms')->default('Gasolina');
            }
            if (!Schema::hasColumn('vehicles', 'engine_details')) {
                $table->string('engine_details')->after('fuel')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('vehicles', 'year'))
                $columns[] = 'year';
            if (Schema::hasColumn('vehicles', 'kms'))
                $columns[] = 'kms';
            if (Schema::hasColumn('vehicles', 'fuel'))
                $columns[] = 'fuel';
            if (Schema::hasColumn('vehicles', 'engine_details'))
                $columns[] = 'engine_details';

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
