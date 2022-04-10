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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 80)->index();
            $table->string('alias_name', 80)->index();
            $table->string('company_ein', 20)->unique()->index();
            $table->string('state_registration', 20)->nullable();
            $table->integer('icms_taxpayer')->default(0)->comment('[0=false, 1=true]');
            $table->string('municipal_registration', 20)->nullable();
            $table->text('note_general')->nullable();
            $table->string('internet_page', 255)->nullable();
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
        Schema::dropIfExists('company');
    }
};
