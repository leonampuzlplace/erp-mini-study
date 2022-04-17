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
        Schema::create('tenant_contact', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tenant_id')
                ->constrained('tenant')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('is_default')->default(0)->comment('[0=false, 1=true]');
            $table->string('name', 60)->nullable();
            $table->string('ein', 20)->nullable();
            $table->string('type', 60)->nullable();
            $table->text('note')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
        });

        DB::table('tenant')->insert([[
                'id' => 'd4dba6f9-d941-4af6-bf8f-093b149bb99f',
                'business_name' => 'Company 1',
                'alias_name' => 'Company 1',
                'ein' => '95.857.276/0001-05',
            ],
            [
                'id' => '1ee9b1bc-bdb9-4ad0-8eeb-ff986ad4febc',
                'business_name' => 'Company 2',
                'alias_name' => 'Company 2',
                'ein' => '56.611.459/0001-86',
            ],
        ]);
        DB::table('tenant_address')->insert([[
                'id' => 1,
                'tenant_id' => 'd4dba6f9-d941-4af6-bf8f-093b149bb99f',
                'is_default' => 1,
                'address' => 'no address',
                'city_id' => 1,
            ],
            [
                'id' => 2,
                'tenant_id' => '1ee9b1bc-bdb9-4ad0-8eeb-ff986ad4febc',
                'is_default' => 1,
                'address' => 'no address',
                'city_id' => 1,
            ],
        ]);
        DB::table('tenant_contact')->insert([[
                'id' => 1,
                'tenant_id' => 'd4dba6f9-d941-4af6-bf8f-093b149bb99f',
                'is_default' => 1,
                'name' => 'no name',
            ],
            [
                'id' => 2,
                'tenant_id' => '1ee9b1bc-bdb9-4ad0-8eeb-ff986ad4febc',
                'is_default' => 1,
                'name' => 'no name',
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
        Schema::dropIfExists('tenant_contact');
    }
};
