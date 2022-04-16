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
        Schema::create('state', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('name', 50)->index();
            $table->char('abbreviation', 2)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('state')->truncate();
        DB::table('state')->insert([
            ['id' => 1, 'name' => 'Acre', 'abbreviation' => 'AC'],
            ['id' => 2, 'name' => 'Alagoas', 'abbreviation' => 'AL'],
            ['id' => 3, 'name' => 'Amapá', 'abbreviation' => 'AP'],
            ['id' => 4, 'name' => 'Amazonas', 'abbreviation' => 'AM'],
            ['id' => 5, 'name' => 'Bahia', 'abbreviation' => 'BA'],
            ['id' => 6, 'name' => 'Ceará', 'abbreviation' => 'CE'],
            ['id' => 7, 'name' => 'Distrito Federal', 'abbreviation' => 'DF'],
            ['id' => 8, 'name' => 'Espírito Santo', 'abbreviation' => 'ES'],
            ['id' => 9, 'name' => 'Goiás', 'abbreviation' => 'GO'],
            ['id' => 10, 'name' => 'Maranhão', 'abbreviation' => 'MA'],
            ['id' => 11, 'name' => 'Mato Grosso', 'abbreviation' => 'MT'],
            ['id' => 12, 'name' => 'Mato Grosso do Sul', 'abbreviation' => 'MS'],
            ['id' => 13, 'name' => 'Minas Gerais', 'abbreviation' => 'MG'],
            ['id' => 14, 'name' => 'Pará', 'abbreviation' => 'PA'],
            ['id' => 15, 'name' => 'Paraíba', 'abbreviation' => 'PB'],
            ['id' => 16, 'name' => 'Paraná', 'abbreviation' => 'PR'],
            ['id' => 17, 'name' => 'Pernambuco', 'abbreviation' => 'PE'],
            ['id' => 18, 'name' => 'Piauí', 'abbreviation' => 'PI'],
            ['id' => 19, 'name' => 'Rio de Janeiro', 'abbreviation' => 'RJ'],
            ['id' => 20, 'name' => 'Rio Grande do Norte', 'abbreviation' => 'RN'],
            ['id' => 21, 'name' => 'Rio Grande do Sul', 'abbreviation' => 'RS'],
            ['id' => 22, 'name' => 'Rondônia', 'abbreviation' => 'RO'],
            ['id' => 23, 'name' => 'Roraima', 'abbreviation' => 'RR'],
            ['id' => 24, 'name' => 'Santa Catarina', 'abbreviation' => 'SC'],
            ['id' => 25, 'name' => 'São Paulo', 'abbreviation' => 'SP'],
            ['id' => 26, 'name' => 'Sergipe', 'abbreviation' => 'SE'],
            ['id' => 27, 'name' => 'Tocantins', 'abbreviation' => 'TO'],
        ]);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state');
    }
};
