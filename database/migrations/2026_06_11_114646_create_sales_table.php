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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // Relação com Clientes (Um cliente pode ter várias vendas)
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // Relação com Viaturas (Uma viatura pertence a apenas uma venda - 1 para 1)
            $table->foreignId('vehicle_id')->unique()->constrained()->onDelete('cascade');

            $table->date('sale_date');
            $table->decimal('sale_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
