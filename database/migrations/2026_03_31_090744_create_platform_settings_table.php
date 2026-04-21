<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Features\Platform\Enums\PlatformEnvironment;

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
            $table->string('connection_id')->nullable();
            $table->integer('environment')->default(PlatformEnvironment::DEMO->value)->comment('See ' . PlatformEnvironment::class . ' enum for possible values');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['host', 'username']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
