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
            $table->string('description', 80)->nullable()->index();
            $table->timestamps();
        });

        DB::table('unit')->truncate();
        DB::table('unit')->insert([
            [
                'id' => 1,
                'abbreviation' => 'UN',
                'description' => 'Unidade',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'abbreviation' => 'KG',
                'description' => 'Quilograma',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'abbreviation' => 'S',
                'description' => 'Segundo',
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'abbreviation' => 'KM',
                'description' => 'Quilômetro',
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'abbreviation' => 'M',
                'description' => 'Metro',
                'created_at' => now(),
            ],
            [
                'id' => 6,
                'abbreviation' => 'CM',
                'description' => 'Centímetro',
                'created_at' => now(),
            ],
            [
                'id' => 7,
                'abbreviation' => 'MM',
                'description' => 'Milímetro',
                'created_at' => now(),
            ],
            [
                'id' => 8,
                'abbreviation' => 'G',
                'description' => 'Grama',
                'created_at' => now(),
            ],
            [
                'id' => 9,
                'abbreviation' => 'MG',
                'description' => 'Miligrama',
                'created_at' => now(),
            ],
            [
                'id' => 10,
                'abbreviation' => 'L',
                'description' => 'Litro',
                'created_at' => now(),
            ],
            [
                'id' => 11,
                'abbreviation' => 'ML',
                'description' => 'Mililitro',
                'created_at' => now(),
            ],
            [
                'id' => 12,
                'abbreviation' => 'M²',
                'description' => 'Metro quadrado',
                'created_at' => now(),
            ],
            [
                'id' => 13,
                'abbreviation' => 'C²',
                'description' => 'Centímetro quadrado',
                'created_at' => now(),
            ],
            [
                'id' => 14,
                'abbreviation' => 'MM²',
                'description' => 'Milímetro quadrado',
                'created_at' => now(),
            ],
            [
                'id' => 15,
                'abbreviation' => 'M³',
                'description' => 'Metro cúbico',
                'created_at' => now(),
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
