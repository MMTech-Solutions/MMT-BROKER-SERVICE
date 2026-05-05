<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initial_amount_server_group', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('server_group_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('initial_amount_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initial_amount_server_group');
    }
};
