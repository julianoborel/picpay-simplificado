<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payee_id')->constrained('users')->onDelete('cascade');
            $table->decimal('value', 10, 2);
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index('payer_id');
            $table->index('payee_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
