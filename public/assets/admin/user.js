/**
*
*/
define(function(require, exports, module) {

    var zhCN = require('datatableZh');
    var editorCN = require('i18n');
    exports.index = function ($, tableId) {
        var editor = new $.fn.dataTable.Editor({
            ajax: {
                create: {
                    type: 'POST',
                    url: '/admin/user',
                    data: {_token: $('meta[name="_token"]').attr('content')},
                },
                edit: {
                    type: 'PUT',
                    url: '/admin/user/_id_',
                    data: {_token: $('meta[name="_token"]').attr('content')},
                },
                remove: {
                    type: 'DELETE',
                    url: '/admin/user/_id_',
                    data: {_token: $('meta[name="_token"]').attr('content')},
                }
            },
            i18n: editorCN,
            table: "#" + tableId,
            idSrc: 'id',
            fields: [
                { 'label':  '用户名', 'name': 'name', },
                { 'label':  '昵称', 'name': 'nick_name', },
                { 'label':  'Email', 'name': 'email', },
                { 'label':  '密码', 'name': 'password', 'type':'password'},
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
            ajax: '/admin/user/pagination',
            columns: [
                // {  'data': 'id' },
                {  'data': 'name' },
                {  'data': 'nick_name' },
                // {  'data': 'email' },
                {  'data': 'reference_type' },
                {  'data': 'status', render: function (data, type, full) {
                    return data == 1 ? '启用':'禁用';
                } },
                {  'data': 'created_at' },
                {  'data': 'updated_at' },
                {  'data': 'id' },
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/user/'+data+'/set-role">设置角色</a>&nbsp;<a href="/admin/user/'+data+'/set-position">设置职位</a>'
                        },
                    "targets": 6,
                    "searchable": false,
                    "sortable": false
                }
            ],
            buttons: [
                // { text: '新增', action: function () { }  },
                // { text: '编辑', className: 'edit', enabled: false },
                // { text: '删除', className: 'delete', enabled: false },
                {extend: "create", text: '新增<i class="fa fa-fw fa-plus"></i>', editor: editor},
                {extend: "edit", text: '编辑<i class="fa fa-fw fa-pencil"></i>', editor: editor},
                {extend: "remove", text: '删除<i class="fa fa-fw fa-trash"></i>', editor: editor},
                {extend: 'excel', text: '导出Excel<i class="fa fa-fw fa-file-excel-o"></i>'},
                {extend: 'print', text: '打印<i class="fa fa-fw fa-print"></i>'},
                //{extend: 'colvis', text: '列显示'}
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