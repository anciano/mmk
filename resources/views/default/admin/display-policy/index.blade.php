@extends('admin.layout.collapsed-sidebar')
@section('styles')
    @include('admin.layout.datatable-css')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            top module
            <small>exp_display_policy</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">top module</a></li>
            <li class="active">exp_display_policy</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">exp_display_policy列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <table id="moduleTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>fact_store_num</th>
                                <th>famount</th>
                                <th>fbill_no</th>
                                <th>fcost_dept_id</th>
                                <th>fcreate_date</th>
                                <th>fcreator_id</th>
                                <th>fdocument_status</th>
                                <th>fend_date</th>
                                <th>fexp_type</th>
                                <th>fmodify_date</th>
                                <th>fmodify_id</th>
                                <th>forg_id</th>
                                <th>fsign_amount</th>
                                <th>fsign_store_num</th>
                                <th>fsketch</th>
                                <th>fstart_date</th>
                                <th>fstore_cost_limit</th>
                                <th>id</th>
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

@endsection
@section('js')
    @include('admin.layout.datatable-js')
    <script type="text/javascript">
        $(function () {
            seajs.use('admin/display_policy.js', function (app) {
                app.index($, 'moduleTable');
            });
        });
    </script>

@endsection