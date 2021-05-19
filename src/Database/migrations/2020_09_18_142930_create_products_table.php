<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('has_variants')->default(false);
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->JSON('options')->nullable();
            $table->string('seo_title')->default('')->nullable();
            $table->string('seo_handle')->default('')->nullable();
            $table->string('seo_description',320)->default('')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
