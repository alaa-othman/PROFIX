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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->foreignId('product_id_1')->nullable()->constrained('products')->noActionOnDelete();
            $table->foreignId('product_id_2')->nullable()->constrained('products')->noActionOnDelete();
            $table->foreignId('product_id_3')->nullable()->constrained('products')->noActionOnDelete();
            $table->foreignId('product_id_4')->nullable()->constrained('products')->noActionOnDelete();
            $table->foreignId('product_id_5')->nullable()->constrained('products')->noActionOnDelete();

            $table->foreignId('employee_id_1')->nullable()->constrained('users')->noActionOnDelete();
            $table->foreignId('employee_id_2')->nullable()->constrained('users')->noActionOnDelete();
            $table->foreignId('employee_id_3')->nullable()->constrained('users')->noActionOnDelete();
            $table->foreignId('employee_id_4')->nullable()->constrained('users')->noActionOnDelete();
            $table->foreignId('employee_id_5')->nullable()->constrained('users')->noActionOnDelete();

            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
