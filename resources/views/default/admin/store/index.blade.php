@extends('admin.layout.collapsed-sidebar') @section('styles')

@include('admin.layout.datatable-css') @endsection @section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		基础信息管理 <small>部门信息</small>
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
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">门店基础信息</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

					<table id="moduleTable" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>序号</th>
								<th>门店全称</th>
								<th>门店简称</th>
								<th>详细地址</th>
								<th>负责人</th>
								<th>联系电话</th>
								<th>负责业代</th>
							</tr>
						</thead>
					</table>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="storeinfo">
	<div class="modal-dialog" role="document" style="width: 90%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">门店信息</h4>
			</div>
			<form class="form-horizontal" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="box-body">
						<div class="col-md-6">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">门店名称</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">门店简称</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">法人</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">负责人</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">联系电话</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">门店编码</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">省份</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">城市</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">县/区</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">街道/乡/镇</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">客户详址</label>
									<div class="col-md-9">
										<input type="text" class="form-control">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">邮政编码</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">地图定位</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">渠道分类</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 text-red">客户分级</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
						</div>
						
						<!-- -----------------------------更多信息---------------------------------- -->
						<div class="col-md-6">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">营业执照</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">税号</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">开户银行</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">账号</label>
									<div class="col-md-9">
										<input type="text" class="form-control" >
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
									<label class="control-label col-md-3" >备注</label>
									<div class="col-md-9">
										<textarea class="form-control" ></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
									<label class="control-label col-md-3" >门店图片</label>
									<div class="col-md-9">
										<input id="storepic" type="file" >
									</div>
								</div>
							</div>
							
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" class="btn btn-primary">保存</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection 
@section('js') 
<script src="/assets/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>
<script src="/assets/plugins/bootstrap-fileinput/js/fileinput_locale_zh_CN.js"></script>
@include('admin.layout.datatable-js')
<script type="text/javascript">

        $(function () {
            seajs.use('app-store', function (store) {
            	store.index($, 'moduleTable');
            });
            $("#storepic").fileinput({
            	overwriteInitial: true,
                maxFileSize: 1500,
                showClose: false,
                showCaption: false,
                showBrowse: false,
                browseOnZoneClick: true,
                removeLabel: '',
                removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
                removeTitle: '删除',
                browseLabel: '',
                browseIcon: '<i class="fa fa-fw fa-cloud-upload"></i>',
                browseTitle: '选择你要上传的图片',
                elErrorContainer: '#kv-avatar-errors-2',
                msgErrorClass: 'alert alert-block alert-danger',
                defaultPreviewContent: '',
                layoutTemplates: {main2: '{preview} ' + ' {remove} {browse}'},
                allowedFileExtensions: ["jpg", "png", "gif"]
           	});
        });

        

    </script>
@endsection