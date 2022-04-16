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
        Schema::create('person_contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')
                ->constrained('person')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('is_default')->default(0)->comment('[0=false, 1=true]');
            $table->string('person_contact_name', 60)->nullable();
            $table->string('person_contact_ein', 20)->nullable();
            $table->string('person_contact_type', 60)->nullable();
            $table->text('person_contact_note')->nullable();
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
        Schema::dropIfExists('person_contact');
    }
};
