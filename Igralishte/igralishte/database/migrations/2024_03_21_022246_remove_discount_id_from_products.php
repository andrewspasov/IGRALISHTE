<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDiscountIdFromProducts extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['discount_id']); // Drop foreign key constraint
            $table->dropColumn('discount_id'); // Drop the column
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->foreign('discount_id')->references('id')->on('discounts');
        });
    }
}
