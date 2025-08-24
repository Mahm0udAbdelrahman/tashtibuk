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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('description_ar')->nullable();
            $table->string('description_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('percentage')->nullable();
            $table->string('delivery_distance')->nullable();
            $table->string('price_per_km')->nullable();
            $table->string('balance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
