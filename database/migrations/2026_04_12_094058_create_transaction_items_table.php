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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_item_id')->nullable()->constrained('product_items')->nullOnDelete();
            $table->integer('qty');
            $table->string('nama_produk_history');
            $table->string('varian_history')->nullable();
            $table->decimal('harga_modal_history', 12, 2);
            $table->decimal('harga_jual_history', 12, 2);
            $table->decimal('diskon_item', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
