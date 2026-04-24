<?php

use App\Features\Manager\Enums\BookTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_groups', function (Blueprint $table) {
            
            $table->uuid('id')->primary();
            $table->foreignUuid('manager_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('meta_name')->comment('The name of the meta tag that contains the server group');

            // -- Como no existe servicio financiero para esto, se almacena en el grupo temporalmente.
            $table->jsonb('currency')->default(json_encode(['code' => 'USD', 'precision' => 2]))->comment('The currency of the server group');
            // --

            $table->integer('min_deposit')->default(0)->comment('The minimum deposit amount for this server group. Expressed in cents.');
            $table->integer('min_withdrawal')->default(0)->comment('The minimum withdrawal amount for this server group. Expressed in cents.');
            $table->integer('account_limits')->default(0)->comment('The maximum number of accounts that can be created for this server group');
            $table->integer('default_credit')->default(0)->comment('The default credit amount for this server group. Expressed in cents.');
            $table->integer('currency_denomination_factor')->default(1)->comment('The factor to convert the currency to the base currency. Cents accounts (USC)');

            $table->boolean('is_private')->default(false)->comment('If true, the server group is private and can only be used by the platform owner');
            $table->boolean('is_default')->default(false)->comment('If true, is used to create a new account when a new user is registered');
            $table->boolean('is_active')->default(false)->comment('If true, the server group is active and can be used');
            $table->boolean('is_deposit_enabled')->default(false)->comment('If true, the server group allows deposits');
            $table->boolean('is_withdrawal_enabled')->default(false)->comment('If true, the server group allows withdrawals');
            $table->boolean('use_countries_restrictions')->default(false)->comment('If true, the server group uses countries restrictions');

            $table->json('restricted_countries')
            ->nullable()
            ->comment('The countries restrictions for this server group. If use_countries_restrictions is true, 
            this field is used to store the countries restrictions. 
            The format is an array of objects with the following properties: { "code": "string", "name": "string" }');
            
            $table->string('book_type', 16)
            ->default(BookTypeEnum::B_BOOK->value)
            ->comment('The type of book to use for this server group. See ' . BookTypeEnum::class . ' for possible values');

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'manager_id'], 'server_groups_unique');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('server_groups');
    }
};
