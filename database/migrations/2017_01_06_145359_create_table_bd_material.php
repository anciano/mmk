<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBdMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bd_material', function (Blueprint $table) {
            $table->increments('id');
			$table->string('fnumber')->default('')->comment('物料编码');
			$table->string('fname')->default('')->comment('物料名称');
			$table->string('fsale_unit')->default('箱')->comment('销售单位');
			$table->string('fbase_unit')->default('瓶')->comment('基本单位');
			$table->integer('fratio')->default('')->comment('换算成销售单位乘数');
			$table->string('fspecification')->default('')->comment('规格');

	        $table->integer('fcreator_id')->default(0)->comment('创建人');
	        $table->timestamp('fcreate_date')->nullable()->comment('创建时间');
	        $table->integer('fmodify_id')->default(0)->comment('修改人');
	        $table->timestamp('fmodify_date')->nullable()->comment('修改时间');
	        $table->string('fdocument_status')->default('A')->comment('数据状态');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bd_material');
    }
}
