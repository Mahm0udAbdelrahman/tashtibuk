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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('balance')->nullable();
            $table->string('shop_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('shop_phone')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('background')->nullable();
            $table->string('to')->nullable();
            $table->string('form')->nullable();
            $table->string('code')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->text('fcm_token')->nullable();
            $table->enum('status',[0,1])->default(0);
            $table->enum('is_delivery',[0,1])->default(0);
            $table->string('id_card')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
