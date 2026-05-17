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
            $table->string('order_title');
            $table->string('location');
            $table->foreignId('vehicule_id')->nullable()->constrained('vehicles')->noActionOnDelete();
            $table->foreignId('customer_id')->constrained('users')->noActionOnDelete();
            $table->enum('status', [
                'Received', 
                'Cancelled',
                'In Progress...', 
                'Waiting Products...', 
                'Completed'
            ])->default('Received');
            $table->foreignId('leader_employee_id')->nullable()->constrained('users')->noActionOnDelete();
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->decimal('total_amount', 12, 2);
            $table->text('description')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
            
            // Indexes 
            $table->index('status');
            $table->index('location');
            $table->index('payment_status');
            $table->index('customer_id');
            $table->index('leader_employee_id');
            $table->index('created_at');
            $table->index('start_at');
            $table->index('end_at');
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