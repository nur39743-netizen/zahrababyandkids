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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_bruto', 12, 2)->default(0);
            $table->decimal('total_diskon', 12, 2)->default(0);
            $table->decimal('biaya_ongkir', 12, 2)->default(0);
            $table->decimal('biaya_packing', 12, 2)->default(0);
            $table->string('status_ongkir')->default('Ditanggung Customer');
            $table->string('status_packing')->default('Ditanggung Customer');
            $table->decimal('total_netto', 12, 2)->default(0);
            $table->string('payment_method')->default('Cash');
            $table->text('catatan')->nullable();
            $table->string('status')->default('Sukses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
