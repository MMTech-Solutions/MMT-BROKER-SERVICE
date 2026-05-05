<?php

use App\Features\Finance\Enums\TransactionTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount');

            $table->integer('type')
                ->comment(
                    '1: Deposit, 2: Withdrawal. See '.TransactionTypeEnum::class.' for possible values. 
                    Deposits is made to the account, withdrawals are made from the account.'
                );

            $table->string('external_account_id');
            $table->string('external_transaction_id');
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_transactions');
    }
};
