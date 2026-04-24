<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_symbol', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('security_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('symbol_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_symbol');
    }
};
