<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVisitStoreCalendar extends Migration
{
    /**
     * 巡防门店状态
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('visit_store_calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('forg_id')->default(0)->comment('组织id');
            $table->timestamp('fdate')->nullable()->comment('日期');
	        $table->integer('fleader_id')->default(0)->comment('员工上级id');
            $table->integer('femp_id')->default(0)->comment('员工id');
            $table->integer('fline_calendar_id')->default(0)->comment('线路巡防日历id');
            $table->integer('fstore_id')->default(0)->comment('门店id');
            $table->integer('fstatus')->default(1)->comment('巡访状态（1-未开始， 2-进行中， 3-已完成）');

            $table->integer('fcreator_id')->default(0)->comment('创建人');
            $table->timestamp('fcreate_date')->nullable()->comment('创建时间');
            $table->integer('fmodify_id')->default(0)->comment('修改人');
            $table->timestamp('fmodify_date')->nullable()->comment('修改时间');
            $table->string('fdocument_status')->default('A')->comment('审核状态');

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
        Schema::drop('visit_store_calendar');
    }
}
