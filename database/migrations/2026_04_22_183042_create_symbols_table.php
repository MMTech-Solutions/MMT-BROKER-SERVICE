<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('symbols', function (Blueprint $table) {

            $table->comment('Lista de instrumentos de trading por plataforma');

            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('alpha');
            $table->integer('stype');
            $table->foreignUuid('manager_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['alpha', 'manager_id'], 'symbols_unique');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('symbols');
    }
};
