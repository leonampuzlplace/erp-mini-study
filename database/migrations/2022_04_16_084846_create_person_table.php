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
            $table->foreignId('company_id')
                ->constrained('company')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('business_name', 80)->index();
            $table->string('alias_name', 80)->nullable()->index();
            $table->string('person_ein', 20)->nullable()->index();
            $table->string('state_registration', 20)->nullable();
            $table->integer('icms_taxpayer')->nullable()->comment('[0=false, 1=true]');
            $table->string('municipal_registration', 20)->nullable();
            $table->text('person_note')->nullable();
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
        Schema::dropIfExists('person');
    }
};