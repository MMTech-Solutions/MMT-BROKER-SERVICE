<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Features\Platform\Enums\PlatformEnviroment;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('platform_id')->constrained('platforms')->cascadeOnDelete();
            $table->string('host');
            $table->integer('port')->default(0);
            $table->string('username');
            $table->string('password');
            $table->integer('enviroment')->default(PlatformEnviroment::DEMO->value)->comment('See ' . PlatformEnviroment::class . ' enum for possible values');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
