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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->decimal('cost_delivery', 10, 2)->nullable();
            $table->string('price_before_percentage')->nullable();
            $table->string('price_after_percentage')->nullable();
            $table->string('order_balance')->nullable();
            $table->enum('type',['vendor','admin'])->default('admin');
            $table->string('total')->nullable();
            $table->enum('status',['pending','cancelled','preparation','delivery','completed'])->default('pending');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('address')->nullable();
            $table->string('building_number')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('number_product')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->enum('payment_status', ['pending','faild','paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
