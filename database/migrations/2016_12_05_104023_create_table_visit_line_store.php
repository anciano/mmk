<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMsLineStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_line_store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fline_id')->default('')->comment('线路id');
            $table->integer('fstore_id')->default('')->comment('门店id');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('visit_line_store');
    }
}
