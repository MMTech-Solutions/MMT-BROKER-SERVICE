<?php

use App\Features\Finance\Enums\TransactionTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('from_account_id')->constrained('accounts')->restrictOnDelete();
            $table->decimal('amount');
            $table->integer('type')->comment('1: Deposit, 2: Withdrawal. See '.TransactionTypeEnum::class.' for possible values');
            $table->foreignUuid('to_account_id')->constrained('accounts')->restrictOnDelete();
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_transactions');
    }
};
