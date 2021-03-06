/**
*
*/
define(function(require, exports, module) {

    var zhCN = require('datatableZh');
    var editorCN = require('i18n');
    exports.index = function ($, tableId) {

        var table = $("#" + tableId).DataTable({
            dom: "lBfrtip",
            language: zhCN,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            rowId: "id",
            ajax: '/admin/message/pagination',
            columns: [
                {  'data': 'id' },
                {
                    'data': 'from_id',
                    render: function ( data, type, full ) {
                        return full.from.name;
                    }
                },
                {
                    'data': 'to_id',
                    render: function ( data, type, full ) {
                        return full.to.name;
                    }
                },
                {
                    'data': 'message_content_id',
                    render: function ( data, type, full ) {
                        return full.content.title;
                    }
                },
                {
                    'data': 'read',
                    render: function ( data, type, full ) {
                        return data==0?'未读':'已读';
                    }
                },
                {  'data': 'fcreate_date' },
            ],
            buttons: [
                // { text: '新增', action: function () { }  },
                // { text: '编辑', className: 'edit', enabled: false },
                // { text: '删除', className: 'delete', enabled: false },
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