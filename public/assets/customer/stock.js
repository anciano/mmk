/**
 *
 */
define(function (require, exports, module) {

    var zhCN = require('datatableZh');
    var editorCN = require('i18n');
    exports.index = function ($, tableId) {
        var editor = new $.fn.dataTable.Editor({
            ajax: {
                create: {
                    type: 'POST',
                    url: '/customer/stock',
                    data: {_token: $('meta[name="_token"]').attr('content')},
                },
                edit: {
                    type: 'PUT',
                    url: '/customer/stock/_id_',
                    data: {_token: $('meta[name="_token"]').attr('content')},
                },
                remove: {
                    type: 'DELETE',
                    url: '/customer/stock/_id_',
                    data: {_token: $('meta[name="_token"]').attr('content')},
                }
            },
            i18n: editorCN,
            table: "#" + tableId,
            idSrc: 'id',
            fields: [
                {'label': 'fbase_eqty', 'name': 'fbase_eqty',},
                {'label': 'fcreate_date', 'name': 'fcreate_date',},
                {'label': 'fcreator_id', 'name': 'fcreator_id',},
                {'label': 'fdocument_status', 'name': 'fdocument_status',},
                {'label': 'feqty', 'name': 'feqty',},
                {'label': 'fhqty', 'name': 'fhqty',},
                {'label': 'flog_id', 'name': 'flog_id',},
                {'label': 'fmaterial_id', 'name': 'fmaterial_id',},
                {'label': 'fmodify_date', 'name': 'fmodify_date',},
                {'label': 'fmodify_id', 'name': 'fmodify_id',},
                {'label': 'fold_eqty', 'name': 'fold_eqty',},
                {'label': 'fsale_hqty', 'name': 'fsale_hqty',},
                {'label': 'fstore_id', 'name': 'fstore_id',},
                {'label': 'ftime', 'name': 'ftime',},
            ]
        });

        var table = $("#" + tableId).DataTable({
            dom: "lBfrtip",
            language: zhCN,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            rowId: "id",
            ajax: '/customer/stock/pagination',
            columns: [
                {'data': 'id'},
                {
                    'data': 'fstore_id',
                    render: function (data, type, full) {
                        if (full.store != null)
                            return full.store.fnumber
                        else
                            return "";
                    }
                },
                {
                    'data': 'femp_id',
                    render: function (data, type, full) {
                        if (full.store != null)
                            return full.store.ffullname
                        else
                            return "";
                    }
                },
                {'data': 'ftime'},
                {
                    'data': 'fstore_id',
                    render: function (data, type, full) {
                        if (full.store.employee != null)
                            return full.store.employee.fnumber
                        else
                            return "";
                    }
                },
                {
                    'data': 'flog_id',
                    render: function (data, type, full) {
                        if (full.store.employee != null)
                            return full.store.employee.fname
                        else
                            return "";
                    }
                },
                {
                    'data': 'fmaterial_id',
                    render: function (data, type, full) {
                        if (full.material != null)
                            return full.material.fnumber
                        else
                            return "";
                    }
                },
                {
                    'data': 'fcreator_id',
                    render: function (data, type, full) {
                        if (full.material != null)
                            return full.material.fname
                        else
                            return "";
                    }
                },
                {
                    'data': 'fmodify_id',
                    render: function (data, type, full) {
                        if (full.material != null)
                            return full.material.fspecification
                        else
                            return "";
                    }
                },
                {'data': 'fhqty'},
                {'data': 'feqty'},
                {'data': 'fsale_hqty'},
            ],
            columnDefs: [
                {
                    "targets": [0],
                    "visible": false
                }
            ],
            buttons: [
                // { text: '新增', action: function () { }  },
                // { text: '编辑', className: 'edit', enabled: false },
                // { text: '删除', className: 'delete', enabled: false },
                // {extend: "create", text: '新增<i class="fa fa-fw fa-plus"></i>', editor: editor},
                // {extend: "edit", text: '编辑<i class="fa fa-fw fa-pencil"></i>', editor: editor},
                {extend: "remove", text: '删除<i class="fa fa-fw fa-trash"></i>', editor: editor},
                {extend: 'excel', text: '导出Excel<i class="fa fa-fw fa-file-excel-o"></i>'},
                {extend: 'print', text: '打印<i class="fa fa-fw fa-print"></i>'},
                {extend: 'colvis', text: '列显示'}
            ]
        });

        // table.on( 'select', checkBtn).on( 'deselect', checkBtn);
        //
        // function checkBtn(e, dt, type, indexes) {
        //     var count = table.rows( { selected: true } ).count();
        //     table.buttons( ['.edit', '.delete'] ).enable(count > 0);
        // }

    }

});