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
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenant')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 60)->index();
            $table->timestamps();
        });

        DB::table('role')->insert([
            [
                'id' => 1,
                'tenant_id' => 1,
                'name' => 'Colaborador',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'tenant_id' => 2,
                'name' => 'Colaborador',
                'created_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
};