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
            $table->string('state_name', 50)->index();
            $table->char('state_abbreviation', 2)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('state')->truncate();
        DB::table('state')->insert([
            ['id' => 1, 'state_name' => 'Acre', 'state_abbreviation' => 'AC'],
            ['id' => 2, 'state_name' => 'Alagoas', 'state_abbreviation' => 'AL'],
            ['id' => 3, 'state_name' => 'Amapá', 'state_abbreviation' => 'AP'],
            ['id' => 4, 'state_name' => 'Amazonas', 'state_abbreviation' => 'AM'],
            ['id' => 5, 'state_name' => 'Bahia', 'state_abbreviation' => 'BA'],
            ['id' => 6, 'state_name' => 'Ceará', 'state_abbreviation' => 'CE'],
            ['id' => 7, 'state_name' => 'Distrito Federal', 'state_abbreviation' => 'DF'],
            ['id' => 8, 'state_name' => 'Espírito Santo', 'state_abbreviation' => 'ES'],
            ['id' => 9, 'state_name' => 'Goiás', 'state_abbreviation' => 'GO'],
            ['id' => 10, 'state_name' => 'Maranhão', 'state_abbreviation' => 'MA'],
            ['id' => 11, 'state_name' => 'Mato Grosso', 'state_abbreviation' => 'MT'],
            ['id' => 12, 'state_name' => 'Mato Grosso do Sul', 'state_abbreviation' => 'MS'],
            ['id' => 13, 'state_name' => 'Minas Gerais', 'state_abbreviation' => 'MG'],
            ['id' => 14, 'state_name' => 'Pará', 'state_abbreviation' => 'PA'],
            ['id' => 15, 'state_name' => 'Paraíba', 'state_abbreviation' => 'PB'],
            ['id' => 16, 'state_name' => 'Paraná', 'state_abbreviation' => 'PR'],
            ['id' => 17, 'state_name' => 'Pernambuco', 'state_abbreviation' => 'PE'],
            ['id' => 18, 'state_name' => 'Piauí', 'state_abbreviation' => 'PI'],
            ['id' => 19, 'state_name' => 'Rio de Janeiro', 'state_abbreviation' => 'RJ'],
            ['id' => 20, 'state_name' => 'Rio Grande do Norte', 'state_abbreviation' => 'RN'],
            ['id' => 21, 'state_name' => 'Rio Grande do Sul', 'state_abbreviation' => 'RS'],
            ['id' => 22, 'state_name' => 'Rondônia', 'state_abbreviation' => 'RO'],
            ['id' => 23, 'state_name' => 'Roraima', 'state_abbreviation' => 'RR'],
            ['id' => 24, 'state_name' => 'Santa Catarina', 'state_abbreviation' => 'SC'],
            ['id' => 25, 'state_name' => 'São Paulo', 'state_abbreviation' => 'SP'],
            ['id' => 26, 'state_name' => 'Sergipe', 'state_abbreviation' => 'SE'],
            ['id' => 27, 'state_name' => 'Tocantins', 'state_abbreviation' => 'TO'],
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
