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
        Schema::create('refund_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_request_id')->nullable()->constrained('refund_requests', 'id')->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->foreignId('item_id')->nullable()->constrained('order_items', 'id')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_request_details');
    }
};
