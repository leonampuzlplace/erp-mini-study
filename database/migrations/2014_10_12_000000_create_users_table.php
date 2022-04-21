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
            $table->bigInteger('role_id')->nullable()->index();
            $table->integer('is_admin')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->truncate();
        DB::table('users')->insert([
            [   
                'id' => 1,
                'tenant_id' => 1,
                'name' => 'tenant1Adm',
                'email' => 'tenant1Adm@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('1-8aa73d34Adm'),
                'is_admin' => 1,
                'role_id' => null,
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'tenant_id' => 1,
                'name' => 'tenant1Colab',
                'email' => 'tenant1Colab@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('1-8aa73d34Colab'),
                'is_admin' => 0,
                'role_id' => 1,
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'tenant_id' => 2,
                'name' => 'tenant2Adm',
                'email' => 'tenant2Adm@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('2-fb484e7dAdm'),
                'remember_token' => \Illuminate\Support\Str::random(10),
                'is_admin' => 1,
                'role_id' => null,
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'tenant_id' => 2,
                'name' => 'tenant2Colab',
                'email' => 'tenant2Colab@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('2-8aa73d34Colab'),
                'is_admin' => 0,
                'role_id' => 2,
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'tenant_id' => null,
                'name' => 'tenantNullAdm',
                'email' => 'tenantNullAdm@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('null-xo932p1jAdm'),
                'remember_token' => \Illuminate\Support\Str::random(10),
                'is_admin' => 1,
                'role_id' => null,
                'created_at' => now(),
            ],
            [
                'id' => 6,
                'tenant_id' => null,
                'name' => 'tenantNullColab',
                'email' => 'tenantNullColab@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('null-8aa73d34Colab'),
                'is_admin' => 0,
                'role_id' => null,
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
