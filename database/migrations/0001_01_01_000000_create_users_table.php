<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('role', ['customer', 'admin', 'call_center', 'mechanic', 'accountant'])->default('customer');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('out_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
