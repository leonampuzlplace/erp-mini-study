<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('person_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('person_type')->truncate();
        DB::table('person_type')->insert([
            ['id' => 1, 'name' => 'Cliente'],
            ['id' => 2, 'name' => 'Fornecedor'],
            ['id' => 3, 'name' => 'Funcionário'],
            ['id' => 4, 'name' => 'Outros'],
            ['id' => 5, 'name' => 'Técnico'],
            ['id' => 6, 'name' => 'Transportador'],
            ['id' => 7, 'name' => 'Vendedor'],
        ]);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_type');
    }
};
