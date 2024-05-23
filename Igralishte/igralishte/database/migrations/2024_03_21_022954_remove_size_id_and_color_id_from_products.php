<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSizeIdAndColorIdFromProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['size_id']);
            $table->dropForeign(['color_id']);

            $table->dropColumn('size_id');
            $table->dropColumn('color_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();

            $table->foreign('size_id')->references('id')->on('sizes');
            $table->foreign('color_id')->references('id')->on('colors');
        });
    }
}

