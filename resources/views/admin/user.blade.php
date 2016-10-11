<!DOCTYPE html>
<html>
<head>
    <title>用户列表</title>
    <meta charset="UTF-8">
    @include('admin.common')
    <script type="text/javascript">
        //获取权限列表
        Obj.getAuthListUrl = "{{url('role/getAuthList')}}";
        //获取用户列表
        Obj.getUserListUrl = "{{url('user/getUserList')}}";
        Obj.addUrl = "{{url('user/add')}}";
        Obj.editUrl = "{{url('user/edit')}}";
        Obj.delUrl = "{{url('user/delete')}}";
        Obj.userTitle = "平台用户管理";
        Obj.userColumns = [ {
            field : 'user',
            title : '用户名',
            width : '100'
        }, {
            field : 'status_name',
            title : '状态',
            width : '60'
        }, {
            field : 'name',
            title : '姓名',
            width : '60'
        }, {
            field : 'phone',
            title : '手机号',
            width : '80'
        }, {
            field : 'email',
            title : '邮箱',
            width : '140'
        }, {
            field : 'isadmin',
            title : '是否管理员',
            align : "center",
            width : '80',
            formatter : function(v) {
                return returnStatus(v);
            }
        } ];
        $(function() {
            //左侧角色列表
            Obj.datagrid = $("#datagrid").datagrid({
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
                url : Obj.getUserListUrl,
                columns : [ Obj.userColumns ],
                onLoadSuccess : function(data) {
                    if (data.total > 0) {
                        $(this).datagrid('selectRow', 0);
                    }
                },
                onSelect : function(index, row) {
                    $("#eastdatagrid").datagrid({
                        url : "{{url('user/getRoleUserList')}}",
                        queryParams : {
                            uid : row.uid
                        }
                    });

                    reloadMarketList();
                    reloadBusinessStoreList();
                }
            });
            Obj.eastdatagrid = $("#eastdatagrid").datagrid({
                toolbar : '#easttoolbar',
                fit : true,
                border : false,
                striped : true,
                loadMsg : '数据加载中',
                rownumbers : true,
                pagination : true,
                nowrap : false,
                singleSelect : false,
                pageSize : 200,
                pageList : [ 50, 100, 200 ],
                columns : [ [ {
                    field : 'check',
                    title : '邮箱',
                    width : '200',
                    checkbox : true
                }, {
                    field : 'role',
                    title : '角色',
                    width : '200'
                } ] ],
                onLoadSuccess : function(data) {
                    $.each(data.rows, function(i, r) {
                        if (r.check) {
                            $("#eastdatagrid").datagrid('checkRow', i);
                        }
                    });
                    reloaddatatree();
                },
                onCheck : function(i, r) {
                    reloaddatatree();
                },
                onUncheck : function(i, r) {
                    reloaddatatree();
                },
                onCheckAll : function(i, r) {
                    reloaddatatree();
                },
                onUncheckAll : function(i, r) {
                    reloaddatatree();
                }
            });
            Obj.datatree = $("#datatree").tree({
                checkbox : true,
                animate : true
            });
            Obj.supermarketTree = $("#supermarketTree").tree({
                checkbox : true,
                animate : true
            });
            Obj.businessStoreTree = $("#businessStoreTree").tree({
                checkbox : true,
                cascadeCheck : true,
                animate : true,
                url :"{:U('getBusinessStoreTreeList')}"
            });
            Obj.dataform = $("#dataform");
            Obj.passform = $("#passform");
            Obj.datawindow = $("#datawindow").window({
                width : '500',
                height : '380',
                closed : true,
                modal : true,
                collapsible : false,
                minimizable : false,
                maximizable : false
            });
            Obj.passwindow = $("#passwindow").window({
                width : '350',
                height : '140',
                closed : true,
                modal : true,
                collapsible : false,
                minimizable : false,
                maximizable : false
            });

        });

        function reloaddatatree() {
            var allrole = $("#eastdatagrid").datagrid('getChecked');
            var role_ids = 0;
            if (allrole) {
                $.each(allrole, function(i, r) {
                    if (role_ids == 0) {
                        role_ids = r.role_id;
                    } else {
                        role_ids += ',' + r.role_id;
                    }
                });
            }
            Obj.datatree.tree({
                url : Obj.getAuthListUrl + "?role_id=" + role_ids
            });
        }

        function addData() {
            $("#pwd").show();
            $("#user").val('');
            $("#uid").val('');
            $("#pass").val('');
            $("#name").val('');
            $("#phone").numberbox('clear');
            $("#email").val('');
            Obj.dataform.attr('action', Obj.addUrl);
            Obj.datawindow.window('open');
        }

        function editData() {
            var row = Obj.datagrid.datagrid('getSelected');
            console.log(row)
            if (row) {
                $("#user").val(row.user);
                $("#uid").val(row.uid);
                $("#name").val(row.name);
                $("#pass").val(row.pass);
                $("input[name=isadmin]").removeAttr('checked');
                $("input[name=isadmin][value=" + row.isadmin + "]").prop('checked',
                        true);
                var busIds = [];
                var storeIds = [];
                var marketIds = [];
                for(var i in row.busstoremarketids){
                    var num = row.busstoremarketids[i].toString().substr(0,1);
                    if(num == '1'){
                        busIds.push(row.busstoremarketids[i]);
                    }else if(num == '2'){
                        storeIds.push(row.busstoremarketids[i]);
                    }else{
                        marketIds.push(row.busstoremarketids[i]);
                    }
                }
                $("#phone").numberbox('setValue', row.phone);
                $("#email").val(row.email);
                $("#pwd").hide();
                Obj.dataform.attr('action', Obj.editUrl);
                Obj.datawindow.window('open');
            } else {
                showMsg("请选择一个用户");
            }
        }

        function delData() {
            var row = Obj.datagrid.datagrid('getSelected');
            if (row) {
                $.messager.confirm("提醒", "确定删除用户？", function(r) {
                    if (r) {
                        $.post(Obj.delUrl, {
                            uid : row.uid
                        }, function(data) {
                            showMsg(data.msg);
                            reloaddatagrid();
                        });
                    }
                });
            } else {
                showMsg("请选择一个用户");
            }
        }

        function reloaddatagrid() {
            Obj.datagrid.datagrid({
                url : Obj.getRoleListUrl
            });
        }

        function saveData() {
            var row = Obj.datagrid.datagrid('getSelected');
            var eastrow = Obj.eastdatagrid.datagrid('getChecked');
            if (row) {
                var role_ids = 0;
                if (eastrow) {
                    $.each(eastrow, function(i, r) {
                        if (role_ids == 0) {
                            role_ids = r.role_id;
                        } else {
                            role_ids += ',' + r.role_id;
                        }
                    });
                }
                $.post("{:U('bindUserRole')}", {
                    uid : row.uid,
                    role_ids : role_ids
                }, function(data) {
                    showMsg(data.msg);
                    reloaddatagrid();
                });
            } else {
                showMsg("请选择用户!");
            }
        }

        function dosubmit(id) {
            if( notspace($('#pass')) ){
                return;
            };
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

        //重置密码
        function resetPass() {
            var row = Obj.datagrid.datagrid('getSelected');
            if (row) {
                $("#uid2").val(row.uid);
                Obj.passform.attr('action', "{:U('resetPass')}");
                Obj.passwindow.window('open');
            } else {
                showMsg("请选择一个用户");
            }
        }

        //更改状态
        function resetStatus() {
            var row = Obj.datagrid.datagrid('getSelected');
            if (row) {
                var param;
                if (row.status == 0) {
                    param = {
                        uid : row.uid,
                        status : 1
                    };
                } else {
                    param = {
                        uid : row.uid,
                        status : 0
                    };
                }
                $.post("{:U('resetStatus')}", param, function(data) {
                    showMsg(data.msg);
                    reloaddatagrid();
                });
            } else {
                showMsg("请选择一个用户");
            }
        }

        function selectAll(element,id){
            var roots=$("#"+id).tree("getRoots");
            var children=$("#"+id).tree("getChildren");

            if(element.checked){
                for(var i in roots){
                    $("#"+id).tree('check',roots[i].target);
                }
                for(var i in children){
                    $("#"+id).tree('check',children[i].target);
                }
            }else{
                for(var i in roots){
                    $("#"+id).tree('uncheck',roots[i].target);
                }
                for(var i in children){
                    $("#"+id).tree('uncheck',children[i].target);
                }
            }



        }

    </script>
</head>
<body class="easyui-layout">
<div data-options="region:'center',border:false,title:'用户列表'">
    <div id="datagrid"></div>
    <div id="toolbar">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true"
           onclick="addData()">增加用户</a> <a class="easyui-linkbutton"
                                           iconCls="icon-edit" plain="true" onclick="editData()">编辑用户</a> <a
                class="easyui-linkbutton" iconCls="icon-remove" plain="true"
                onclick="delData()">删除用户</a> <a class="easyui-linkbutton"
                                                iconCls="icon-edit" plain="true" onclick="resetPass()">重置密码</a> <a
                class="easyui-linkbutton" iconCls="icon-edit" plain="true"
                onclick="resetStatus()">启用/禁用</a>
    </div>
</div>
<div id="east" class="easyui-layout"
     data-options="region:'east',border:false"
     style="width: 1200px">
    <div style="border-left:1px solid #D4D4D4;border-right:1px solid #D4D4D4;" data-options="region:'center',border:false,title:'角色列表'">
        <div id="eastdatagrid"></div>
        <div id="easttoolbar">
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true"
               onclick="saveData()">保存</a>
        </div>
    </div>
    <div data-options="region:'east',border:false,title:'权限列表'"
         style="width: 300px">
        <div id="datatree"></div>
    </div>
</div>
<div id="datawindow" title="用户管理">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false">
            <form id="dataform" method="post" onsubmit="return false;">
                <table width="100%" class="cat-form">
                    <tr>
                        <td class="cat-label">用户名</td>
                        <td><input type="text" id="user" name="user"
                                   class="easyui-validatebox" data-options="required:true">
                            <input type="hidden" id="uid" name="uid">
                            <span id="userTip"></span>
                        </td>
                    </tr>
                    <tr id="pwd">
                        <td class="cat-label">密码</td>
                        <td><input type="text" id="pass" name="pass"
                                   class="easyui-validatebox" data-options="required:true,validType:'length[6,32]'">
                            <span id="passTip"></span>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td class="cat-label">所属商家门店超市</td>
                        <td><input type="text" style="width: 350px"
                            id="busstoremarketids" name="busstoremarketids[]"
                            class="easyui-combotree"></td>
                    </tr>-->
                    <tr>
                        <td class="cat-label">姓名</td>
                        <td><input type="text" id="name" name="name"
                                   class="easyui-validatebox" data-options="required:true">
                            <span id="nameTip"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">手机号</td>
                        <td><input type="text" id="phone" name="phone"
                                   class="easyui-numberbox" data-options="required:true"></td>
                        <span id="phoneTip"></span>
                    </tr>
                    <tr>
                        <td class="cat-label">邮箱</td>
                        <td><input type="text" id="email" name="email"
                                   class="easyui-validatebox" data-options="validType: 'email'">
                            <span id="emailTip"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">是否管理员</td>
                        <td><input type="radio" name="isadmin" value="1">是 <input
                                    type="radio" name="isadmin" value="0">否</td>
                    </tr>
                </table>
            </form>
            <div data-options="region:'south',height:40,border:false"
                 style="border-top: 1px solid #CCCCCC; text-align: right; padding-top: 5px; padding-right: 10px">
                <a class="easyui-linkbutton" iconCls="icon-save"
                   onclick="dosubmit('data')">提交</a>
            </div>
        </div>
    </div>
</div>
<div id="passwindow" title="重置密码">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false">
            <form id="passform" method="post" onsubmit="return false;">
                <table width="100%" class="cat-form">
                    <tr>
                        <td class="cat-label">密码：</td>
                        <td><input type="text" id="newpass" name="newpass"
                                   class="easyui-validatebox" data-options="required:true">
                            <input type="hidden" id="uid2" name="uid"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south',height:40,border:false"
             style="border-top: 1px solid #CCCCCC; text-align: right; padding-top: 5px; padding-right: 10px">
            <a class="easyui-linkbutton" iconCls="icon-save"
               onclick="dosubmit('pass')">提交</a>
        </div>
    </div>
</div>
</body>
</html>
