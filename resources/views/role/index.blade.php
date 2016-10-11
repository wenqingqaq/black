<!DOCTYPE html>
<html>
<head>
    <title>角色首页</title>
    <meta charset="UTF-8">
    @include('admin.common')
    <script type="text/javascript">
        //获取角色列表
        Obj.getRoleListUrl = "{{url('role/getRoleList')}}";
        //获取权限列表
        Obj.getAuthListUrl = "{{url('role/getAuthList')}}";
        Obj.addUrl = "{{url('role/add')}}";
        Obj.editUrl = "{{url('role/edit')}}";
        Obj.delUrl = "{{url('role/delete')}}";
        Obj.roleTitle = "平台角色管理";
        Obj.roleColumns = [ {
            field : 'role',
            title : '角色名称',
            width : '200'
        }, {
            field : 'remark',
            title : '备注',
            width : '400'
        } ];
        $(function() {
            //左侧角色列表
            Obj.datagrid = $("#datagrid").datagrid({
                title : Obj.roleTitle,
                toolbar : '#toolbar',
                fit : true,
                border : false,
                striped : true,
                loadMsg : '数据加载中',
                rownumbers : true,
                pagination : true,
                nowrap : false,
                singleSelect : true,
                pageSize : 200,
                pageList : [ 50, 100, 200 ],
                url : Obj.getRoleListUrl,
                columns : [ Obj.roleColumns ],
                onLoadSuccess : function(data) {
                    if (data.total > 0) {
                        $(this).datagrid('selectRow', 0);
                    }
                },
                onSelect : function(data) {
                    reloaddatatree();
                }
            });
            Obj.dataform = $("#dataform");
            Obj.datatree = $("#datatree").tree({
                checkbox : true,
                animate : true
            });
            Obj.datawindow = $("#datawindow").window({
                width : '415',
                height : '300',
                closed : true,
                modal : true,
                collapsible : false,
                minimizable : false,
                maximizable : false
            });
        });

        function reloaddatatree() {
            var row = Obj.datagrid.datagrid('getSelected');
            Obj.datatree.tree({
                url : Obj.getAuthListUrl + "?role_id=" + row.role_id
            });
        }

        function addData() {
            $("#role").val('');
            $("#role_id").val('');
            $("#remark").val('');
            Obj.dataform.attr('action', Obj.addUrl);
            Obj.datawindow.window('open');
        }

        function editData() {
            var row = Obj.datagrid.datagrid('getSelected');
            if (row) {
                $("#role").val(row.role);
                $("#role_id").val(row.role_id);
                $("#remark").val(row.remark);
                Obj.dataform.attr('action', Obj.editUrl);
                Obj.datawindow.window('open');
            } else {
                showMsg("请选择一个角色");
            }
        }

        function delData() {
            var row = Obj.datagrid.datagrid('getSelected');
            if (row) {
                $.messager.confirm("提醒", "确定删除角色？", function(r) {
                    if (r) {
                        $.post(Obj.delUrl, {
                            role_id : row.role_id
                        }, function(data) {
                            showMsg(data.msg);
                            reloaddatagrid();
                        });
                    }
                });
            } else {
                showMsg("请选择一个角色");
            }
        }

        function reloaddatagrid() {
            Obj.datagrid.datagrid({
                url : Obj.getRoleListUrl
            });
        }

        function saveData() {
            var all = Obj.datatree.tree('getChecked');
            var row = Obj.datagrid.datagrid('getSelected');
            var param = {};
            if (row) {
                if(!isEmptyObject(all)){
                    $.each(all, function(i, n) {
                        if (Obj.datatree.tree('isLeaf', n.target)) {
                            param[n.id] = n.text;
                        }
                    });
                    $.post("{{url('role/saveRoleAuthority')}}", {
                        roleId : row.role_id,
                        access : param
                    }, function(data) {
                        showMsg(data.msg);
                    });
                }else{
                    showMsg("请选择权限！");
                    var index = Obj.datagrid.datagrid('getRowIndex',row);
                    Obj.datagrid.datagrid('selectRow',index);
                }
            } else {
                showMsg("请选择角色！");
            }
        }

        function dosubmit(id) {
            $('#' + id + 'form').form("submit", {
                onSubmit : function() {
                    if ($('#' + id + 'form').form('validate')) {
                        loading();
                        return true;
                    } else {
                        return false;
                    }
                },
                success : function(data) {
                    loading(1);
                    try {
                        var dataobj = jQuery.parseJSON(data);
                    } catch (e) {
                        showMsg(e.name + ":" + e.message);
                        return;
                    }
                    showMsg(dataobj.msg);
                    if (dataobj.type == 'suc') {
                        try {
                            $('#' + id + 'window').window("close");
                            eval('reload' + id + 'grid();');
                        } catch (e) {

                        }
                    }
                }
            });
        }

        function isEmptyObject(obj) {
            for ( var n in obj) {
                return false;
            }
            return true;
        }
    </script>
    <style type="text/css">
        #remark {
            width: 170px !important;
            height: 150px !important
        }
    </style>
</head>
<body class="easyui-layout">
<div data-options="region:'center',border:false,title:'角色列表'">
    <div id="datagrid"></div>
    <div id="toolbar">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addData()">增加角色</a>
        <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editData()">编辑角色</a>
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delData()">删除角色</a>
    </div>
</div>
<div data-options="region:'east',border:false,title:'权限列表'" style="width: 300px" class="easyui-layout">
    <div data-options="region:'center',title:''">
        <div id="datatree"></div>
    </div>
    <div data-options="region:'south',border:false,title:''" style="height: 50px">
        <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="saveData()">保存权限</a>
    </div>
</div>
<div id="datawindow" title="角色管理">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false">
            <form id="dataform" method="post" onsubmit="return false;">
                <table width="100%" class="cat-form">
                    <tr>
                        <td class="cat-label">角色名</td>
                        <td><input type="text" id="role" name="role"
                                   class="easyui-validatebox" data-options="required:true">
                            <input type="hidden" id="role_id" name="role_id"></td>
                    </tr>
                    <tr>
                        <td class="cat-label">备注</td>
                        <td><textarea id="remark" name="remark"></textarea></td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south',height:40,border:false" style="border-top: 1px solid #CCCCCC; text-align: right; padding-top: 5px; padding-right: 10px">
            <a class="easyui-linkbutton" iconCls="icon-save" onclick="dosubmit('data')">提交</a>
        </div>
    </div>
</div>
</body>
</html>
