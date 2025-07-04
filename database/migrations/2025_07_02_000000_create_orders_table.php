<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('pending');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->boolean('verification')->default(false);
            $table->string('delivery_method')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->longText('details')->nullable();
            $table->integer('cash_payment_amount')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
