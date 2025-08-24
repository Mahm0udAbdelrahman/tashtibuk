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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors','id')->cascadeOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries','id')->cascadeOnDelete();
            $table->string('vendor_wallet')->nullable();
            $table->string('delivery_wallet')->nullable();
            $table->string('cost_delivery')->nullable();
            $table->string('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
