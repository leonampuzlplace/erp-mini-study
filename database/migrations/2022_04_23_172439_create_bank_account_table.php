<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenant')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name', 80)->index();
            $table->foreignId('bank_id')
                ->constrained('bank')
                ->onUpdate('cascade');                
            $table->string('agency', 20);
            $table->string('agency_digit', 10)->nullable();
            $table->string('account', 20)->index();
            $table->string('account_digit', 10)->nullable();
            $table->string('account_type', 80)->nullable();
            $table->text('note')->nullable();
        });
    }

    // payment types

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_account');
    }
};
