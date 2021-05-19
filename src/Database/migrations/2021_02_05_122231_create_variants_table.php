<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->boolean('default_variant')->nullable()->default(false);
            $table->string('title',255);
            $table->string('option',255)->nullable();
            $table->float('price', 8, 2)->default(0);
            $table->float('compare_price',8,2)->default(0);
            $table->string('sku')->nullable();
            $table->boolean('track_qty')->default(false);
            $table->integer('qty')->default(0);
            $table->boolean('continue_selling')->default(false);
            $table->integer('weight')->nullable();
            $table->string('weight_unit',3)->default('lb');
            $table->string('seo_title')->default('')->nullable();
            $table->string('seo_handle')->default('')->nullable();
            $table->string('seo_description',320)->default('')->nullable();
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
        Schema::dropIfExists('variants');
    }
}
