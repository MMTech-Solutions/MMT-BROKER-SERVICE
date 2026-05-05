<?php

use App\Features\TradingServer\Enums\EnvironmentEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trading_servers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('platform_id')->constrained('platforms')->cascadeOnDelete();
            $table->string('host');
            $table->integer('port')->default(0);
            $table->string('username');
            $table->string('password');
            $table->string('connection_id')->nullable();
            $table->integer('environment')->default(EnvironmentEnum::DEMO->value)->comment('See '.EnvironmentEnum::class.' enum for possible values');
            $table->boolean('is_active')->default(false);
            $table->timestamp('initialized_at')->nullable()->comment('Set once the trading server hierarchy has been synced successfully at least once');
            $table->timestamps();

            $table->unique(['host', 'username']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_servers');
    }
};
