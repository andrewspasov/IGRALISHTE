<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToDiscountsTable extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('discount_category_id')->references('id')->on('discount_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['discount_category_id']);
        });
    }
}
