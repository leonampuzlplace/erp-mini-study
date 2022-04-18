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
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenant')->onUpdate('cascade')->onDelete('cascade');
            $table->string('business_name', 80)->index();
            $table->string('alias_name', 80)->nullable()->index();
            $table->string('ein', 20)->nullable()->index();
            $table->string('state_registration', 20)->nullable();
            $table->integer('icms_taxpayer')->nullable()->comment('[0=false, 1=true]');
            $table->string('municipal_registration', 20)->nullable();
            $table->text('note')->nullable();
            $table->string('internet_page', 255)->nullable();
            $table->integer('is_customer')->nullable();
            $table->integer('is_seller')->nullable();
            $table->integer('is_supplier')->nullable();
            $table->integer('is_carrier')->nullable();
            $table->integer('is_technician')->nullable();
            $table->integer('is_employee')->nullable();
            $table->integer('is_other')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person');
    }
};
