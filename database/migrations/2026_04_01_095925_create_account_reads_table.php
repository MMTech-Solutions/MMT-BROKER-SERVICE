<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_reads', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('account_id')
                ->constrained('accounts')
                ->onDelete('cascade');

            $table->decimal('balance');
            $table->decimal('equity');
            $table->decimal('credit');
            $table->decimal('margin');
            $table->decimal('free_margin');
            $table->decimal('profit');

            $table->integer('unix_read_at');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('account_reads');
    }
};
