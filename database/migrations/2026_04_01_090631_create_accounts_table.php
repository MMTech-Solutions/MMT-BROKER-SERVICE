<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('custom_name')->nullable();
            $table->uuid('user_id');
            $table->string('password');
            $table->string('investor_password');

            $table->foreignUuid('server_group_id')->constrained('server_groups');
            $table->foreignUlid('leverage_id')->constrained('leverages');

            $table->decimal('initial_deposit');
            $table->decimal('current_balance');
            $table->decimal('current_equity');
            $table->decimal('current_credit');
            $table->decimal('margin');
            $table->decimal('free_margin');

            $table->boolean('is_active')->default(false)->comment('If true, the account is active and can be used');
            $table->boolean('is_trading_enabled')->default(false)->comment('If true, the account is trading enabled and can be used');
            
            $table->string('comments')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
