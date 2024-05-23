<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandImagesTable extends Migration
{
    public function up()
    {
        Schema::create('brand_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->binary('image');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_images');
    }
}
