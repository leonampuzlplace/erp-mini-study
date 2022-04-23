<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenant')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 120)->index();
            $table->string('reference_code', 36)->index()->nullable();
            $table->string('ean_code', 36)->index()->nullable();
            $table->decimal('cost_price', 15, 4)->nullable();
            $table->decimal('sale_price', 15, 4)->nullable();
            $table->decimal('minimum_quantity', 15, 4)->nullable();
            $table->decimal('current_quantity', 15, 4)->nullable();
            $table->integer('move_stock')->nullable();
            $table->text('note')->nullable();
            $table->integer('discontinued')->nullable();
            $table->foreignId('unit_id')->constrained('unit');
            $table->foreignId('category_id')->nullable()->constrained('category');
            $table->foreignId('brand_id')->nullable()->constrained('brand');
            $table->integer('is_service')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
};
