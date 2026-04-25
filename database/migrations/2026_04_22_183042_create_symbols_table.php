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
            $table->foreignUuid('trading_server_id')->constrained("trading_servers")->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['alpha', 'trading_server_id'], 'symbols_unique');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('symbols');
    }
};
