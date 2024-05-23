<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandBrandCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('brand_brand_category', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('brand_category_id');
            
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('brand_category_id')->references('id')->on('brand_categories')->onDelete('cascade');

            $table->primary(['brand_id', 'brand_category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_brand_category');
    }
}
