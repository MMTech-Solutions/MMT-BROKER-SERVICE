<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


/**
 * 
 * Security: categoría de instrumentos heredada de MT4.
 * El término "Security" proviene del legado de MT4, pero al agrupar los instrumentos en categorías pierde parte de su sentido original.
 * Se mantiene este nombre únicamente por compatibilidad y motivos históricos.
 * 
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('securities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();

            // -- Solo MT4. Lo marco como configuracion temporal y pendiente de eliminar / confirmar.
            $table->integer('position')->default(0);
            // --

            $table->foreignUuid('trading_server_id')->constrained("trading_servers")->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['name', 'trading_server_id'], 'securities_unique');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('securities');
    }
};
