<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf_cnpj')->unique()->after('email');
            $table->enum('type', ['common', 'merchant'])->after('cpf_cnpj');
            $table->decimal('balance', 10, 2)->default(0)->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cpf_cnpj', 'type', 'balance']);
        });
    }
};
