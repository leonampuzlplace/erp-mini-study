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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->nullable()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->truncate();
        DB::table('users')->insert([
            [   'id' => 1,
                'tenant_id' => 'd4dba6f9-d941-4af6-bf8f-093b149bb99f',
                'name' => 'adm',
                'email' => 'adm@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('erp-8aa73d34'),
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],[
                'id' => 2,
                'tenant_id' => '1ee9b1bc-bdb9-4ad0-8eeb-ff986ad4febc',
                'name' => 'user',
                'email' => 'user@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('erp-fb484e7d'),
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'tenant_id' => null,
                'name' => 'null',
                'email' => 'null@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('erp-xo932p1j'),
                'remember_token' => \Illuminate\Support\Str::random(10),
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
        Schema::dropIfExists('users');
    }
};
