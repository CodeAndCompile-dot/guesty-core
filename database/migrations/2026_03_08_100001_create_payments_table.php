<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('booking_id');
            $table->text('receipt_url')->nullable();
            $table->string('customer_id', 1025)->nullable();
            $table->text('balance_transaction')->nullable();
            $table->text('tran_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('status', 1025)->default('pending');
            $table->string('type')->default('stripe');
            $table->double('amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
