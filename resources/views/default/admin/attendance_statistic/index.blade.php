@extends('admin.layout.collapsed-sidebar') @section('styles')
@include('admin.layout.datatable-css')
<link rel="stylesheet" href="/assets/plugins/bootstrap-treeview/bootstrap-treeview.min.css">
<link rel="stylesheet" href="/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
#allmap {
	height: 500px;
	width: 100%;
}

</style>
@endsection @section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		用户权限管理 <small>角色管理</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/admin/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">用户权限管理</a></li>
		<li class="active">角色管理</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-3" >
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">组织架构信息</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div id="tree"></div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">考勤地图定位</h3>
						<div class="form-group col-md-3 pull-right">
			                <label class="col-md-5 control-label" style="margin-top: 5px"><h3 class="box-title">日期查询</h3></label>
			                <div class="input-group date">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
			                  <input type="text" class="form-control pull-right" id="datepicker">
			                </div>
			                <!-- /.input group -->
			              </div>
					</div>
					<!-- /.box-header -->
					<div class="box-body" style="margin-top: -20px;">

						<div id="allmap"></div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<div class="box">
					<div class="box-header">
						<h3 class="box-title">考勤信息</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="moduleTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>序号</th>
									<th>姓名</th>
									<th>日期</th>
									<th>签到时间</th>
									<th>签退时间</th>
								</tr>
							</thead>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
		</div>

</section>

@endsection 
@section('js') 
@include('admin.layout.datatable-js')
<script src="/assets/plugins/bootstrap-treeview/bootstrap-treeview.min.js"></script>
<script src="/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/assets/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=D4Bi3270ydgA5HsnWDnmBVwF3zaPdoMC"></script>
<script type="text/javascript">

        $(function () {
            seajs.use('app-attendance', function (attendance) {
            	attendance.index($, 'moduleTable','tree','allmap');
            });
        	
        });


	
</script>
@endsection
