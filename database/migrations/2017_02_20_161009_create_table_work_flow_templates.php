<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWorkFlowTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	    Schema::create('work_flow_templates', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('name')->default('')->comment('名称');
		    $table->string('table')->nullable()->comment('表名');
		    $table->integer('status')->default(1)->comment('状态（0-未启用, 1-启用）');
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
        //
	    Schema::dropIfExists('work_flow_templates');
    }
}
