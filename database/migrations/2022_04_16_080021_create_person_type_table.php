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
            $table->string('person_type_name', 60)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('person_type')->truncate();
        DB::table('person_type')->insert([
            ['id' => 1, 'person_type_name' => 'Cliente'],
            ['id' => 2, 'person_type_name' => 'Fornecedor'],
            ['id' => 3, 'person_type_name' => 'Funcionário'],
            ['id' => 4, 'person_type_name' => 'Outros'],
            ['id' => 5, 'person_type_name' => 'Técnico'],
            ['id' => 6, 'person_type_name' => 'Transportador'],
            ['id' => 7, 'person_type_name' => 'Vendedor'],
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
