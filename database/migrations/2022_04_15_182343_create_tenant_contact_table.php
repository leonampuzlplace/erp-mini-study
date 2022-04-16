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
        Schema::create('tenant_contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenant')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('is_default')->default(0)->comment('[0=false, 1=true]');
            $table->string('name', 60)->nullable();
            $table->string('ein', 20)->nullable();
            $table->string('type', 60)->nullable();
            $table->text('note')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_contact');
    }
};
