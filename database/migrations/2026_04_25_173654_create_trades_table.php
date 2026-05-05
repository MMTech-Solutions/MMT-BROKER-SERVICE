<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('login');
            $table->string('symbol');
            $table->integer('position_command')->comment('0: Buy, 1: Sell. See PositionTradeCommandEnum for possible values in the trading service SDK');

            $table->decimal('volume');
            $table->decimal('open_price');
            $table->decimal('close_price');
            $table->decimal('profit');
            $table->decimal('stop_loss');
            $table->decimal('take_profit');
            $table->decimal('margin_rate');
            $table->decimal('swap');
            $table->decimal('commission');

            $table->integer('unix_opened_at');
            $table->integer('unix_closed_at');
            $table->integer('unix_read_at');

            $table->string('comments')->nullable();
            $table->integer('is_open')->default(1)->comment('1: Open, 0: Closed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
