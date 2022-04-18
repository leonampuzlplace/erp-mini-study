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
        Schema::create('unit', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation', 10)->unique();
            $table->string('description', 60)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('unit')->insert([
            [
                'id' => 1,
                'abbreviation' => 'UN',
                'description' => 'Unidade',
            ],
            [
                'id' => 2,
                'abbreviation' => 'KG',
                'description' => 'Quilograma',
            ],
            [
                'id' => 3,
                'abbreviation' => 'S',
                'description' => 'Segundo',
            ],
            [
                'id' => 4,
                'abbreviation' => 'KM',
                'description' => 'Quilômetro',
            ],
            [
                'id' => 5,
                'abbreviation' => 'M',
                'description' => 'Metro',
            ],
            [
                'id' => 6,
                'abbreviation' => 'CM',
                'description' => 'Centímetro',
            ],
            [
                'id' => 7,
                'abbreviation' => 'MM',
                'description' => 'Milímetro',
            ],
            [
                'id' => 8,
                'abbreviation' => 'G',
                'description' => 'Grama',
            ],
            [
                'id' => 9,
                'abbreviation' => 'MG',
                'description' => 'Miligrama',
            ],
            [
                'id' => 10,
                'abbreviation' => 'L',
                'description' => 'Litro',
            ],
            [
                'id' => 11,
                'abbreviation' => 'ML',
                'description' => 'Mililitro',
            ],
            [
                'id' => 12,
                'abbreviation' => 'M²',
                'description' => 'Metro quadrado',
            ],
            [
                'id' => 13,
                'abbreviation' => 'C²',
                'description' => 'Centímetro quadrado',
            ],
            [
                'id' => 14,
                'abbreviation' => 'MM²',
                'description' => 'Milímetro quadrado',
            ],
            [
                'id' => 15,
                'abbreviation' => 'M³',
                'description' => 'Metro cúbico',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit');
    }
};
