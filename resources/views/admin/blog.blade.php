<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title>全部博客</title>
    @include('admin.common')
    <script type="text/javascript">
        CONFIG.GRIDTITLE = '全部博客列表';
        CONFIG.getlisturl = "{{url('blog/getList')}}";
        CONFIG.addurl = "{{url('blog/add')}}";
        CONFIG.editurl = "{{url('blog/edit')}}";
        CONFIG.disurl = "{{url('blog/dis')}}";
        CONFIG.COLUMNS = [
            {
                field : 'ck',
                checkbox : true
            },
            {
                field : 'id',
                title : '编号',
                width : 80
            },
            {
                field : 'title',
                title : '博客标题',
                width : 100
            },
            {
                field : 'name',
                title : '分类',
                width : 200
            },
            {
                field : 'body',
                title : '缩略文字',
                width : 500
            },
            {
                field : 'create_time',
                title : '创建时间',
                width : 200
            },
            {
                field : 'update_time',
                title : '更新时间',
                width : 200
            }
        ];

        $(function(){

            //搜索状态的下拉菜单
            $("#rlStatus").combobox({
                url:"{{url('blog/getCategory')}}",
                valueField:'id',
                textField:'name'
            });

            //博客分类获取
            $("#category").combobox({
                url:"{{url('blog/getCategory')}}",
                valueField:'id',
                textField:'name'
            });

        });

        function strip_tags(input) {
            if (input) {
                var tags = /<\/?[^>]+>/gi;
                var i = input.replace(tags, '');
                return i;
            } else {
                return input;
            }
        }
        $(function() {
            objs.datagrid = $("#datagrid").datagrid({
                title : CONFIG.GRIDTITLE,
                toolbar : '#toolbar',
                fit : true,
                border : false,
                striped : true,
                loadMsg : '加载中。。',
                rownumbers : true,
                pagination : true,
                nowrap : false,
                singleSelect : false,
                pageList : [ 20, 50, 100, 150, 200 ],
                pageSize : 200,
                url : CONFIG.getlisturl,
                sortName : CONFIG.GRIDSORTNAME,
                sortOrder : CONFIG.GRIDSORTORDER,
                columns : [ CONFIG.COLUMNS ],
                onRowContextMenu : function(e, row, data) {
                    e.preventDefault();
                    $(this).datagrid('unselectAll');
                    $(this).datagrid('selectRow', row);
                    $('#contentmenu').menu('show', {
                        left : e.pageX,
                        top : e.pageY
                    });
                },
                onLoadSuccess : function(data) {
                    if (data.rows.length > 0) {
                        $(this).datagrid("unselectAll");
                        $(this).datagrid("selectRow", 0);
                    }
                    $(".easyui-tips").tooltip();
                    $(".remark").each(function() {
                        $(this).tooltip({
                            position : 'right',
                            content : $(this).find("p").html(),
                            trackMouse : true,
                            onShow : function() {
                                $(this).tooltip('tip').css({
                                    backgroundColor : '#666',
                                    color : '#FFF',
                                    'word-wrap' : 'break-word',
                                    'max-width' : '400px'
                                });
                            }
                        });
                    });
                }
            });
            objs.datawindow = $("#datawindow").window({
                width : 800,
                height : 680,
                closed : true,
                modal : true,
                collapsible : false,
                minimizable : false,
                maximizable : false
            });
            objs.dataform = $('#dataform');
            var dataformdata = objs.dataform.serializeArray();
            objs.dataformdefault = [];
            $.each(dataformdata, function(i, o) {
                objs.dataformdefault[o.name] = o.value;
            });

        });

        function addData()
        {
            objs.dataform.attr('action', CONFIG.addurl);
            objs.datawindow.window('open');
            objs.dataform.form('load', objs.dataformdefault);
        }

        function editData() {
            var row = objs.datagrid.datagrid('getSelected');
            if (row) {
                objs.dataform.attr('action', CONFIG.editurl);
                objs.datawindow.window('open');
                objs.dataform.form('load', objs.dataformdefault);
                objs.dataform.form('load', row);
                $("#wuye_contacter_id").combobox({
                    url:"{:U('getLxrForSelect')}",
                    valueField:'contacter_id',
                    textField:'name',
                    onBeforeLoad : function(param) {
                        param.wuYeCompanyId = row.wuye_company_id;// 参数:CodeType值.
                    }
                });
                $("#wuye_contacter_id").combobox('setValue',row.wuye_contacter_id); //设置一下默认value值
            } else {
                showMsg('请选择要编辑的数据！');
            }
        }

        //禁用
        function disData() {
            var row = objs.datagrid.datagrid('getSelections'); //选取多个数据
            if (row) {
                loading();

                $.post(CONFIG.disurl, {row : row}, function(data) {
                    loading(1);
                    showMsg(data.message);
                    reloaddatagrid();
                }, 'json');
            } else {
                showMsg('选择要禁用的数据');
            }
        }

        function reloaddatagrid() {
            var searchformdata = $('#searchform').serializeArray();
            if(searchformdata){
                objs.searchformdefault = [];
                $.each(searchformdata, function(i, o) {
                    objs.searchformdefault[o.name] = o.value;
                });
            }
            //表单提交[成功]后的回调，可以添加自己的逻辑刷新combobox等
            objs.datagrid.datagrid({
                queryParams : objs.searchformdefault
            });
        }
    </script>
</head>
<body class="easyui-layout">
<div data-options="region:'center',border:false">
    <div id="datagrid"></div>
    <div id="toolbar">

        <div class="cat-toobarmenus">
            <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addData()">添加</a>
            <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editData()">编辑</a>
            <a onclick="disData()" class="easyui-linkbutton" iconCls="icon-tip" plain="true">启用/禁用</a>
            <a onclick="del()" class="easyui-linkbutton" iconCls="icon-edit" plain="true">认领</a>
        </div>
        <form id="searchform" onsubmit="return false;" style="padding: 5px 0 5px 8px">
            请输入博客名：
            <input type="text" name="project" class="cat-input cat-search-input" />
            博客分类：
            <input type="text" name="status" id="rlStatus" class="cat-search-input easyui-combobox" />
            <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="reloaddatagrid();">搜索</a>
        </form>
    </div>
</div>

<div id="datawindow" title="新增">
    <div class="easyui-layout" fit="true" style="border: 0;">
        <div data-options="region:'center'" style="width: 50px; border: 0;">
            <form id="dataform" class="cat-form" method="post">
                <table>
                    <tr>
                        <td>
                            <input type="hidden" id="id" name="id">
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">标题：</td>
                        <td><input name="title" id="title" type="text" class="cat-input easyui-validatebox" required="required"></td>
                    </tr>
                    <tr>
                        <td class="cat-label">作者：</td>
                        <td><input name="auth" id="auth" type="text" class="cat-input easyui-validatebox"></td>
                    </tr>
                    <tr>
                        <td class="cat-label">创建时间：</td>
                        <td>
                            <input name="create_time" id="create_time" type="text" class="cat-input easyui-datetimebox" data-options="showSeconds:false" value="{{date('Y-m-d H:i:s')}}" >
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">博客分类：</td>
                        <td><input name="category" id="category" class="easyui-combobox easyui-validatebox"  /></td>
                    </tr>
                    <tr>
                        <td class="cat-label">文章</td>
                        <td>
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="body" type="text/plain" style="width:630px; height:500px;">
                            </script>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south'" style="height: 50px; border-left: 0px; border-right: 0px; border-bottom: 0px; padding-top: 12px" align="center">
            <a onclick="dosubmit('data')" class="easyui-linkbutton" iconCls="icon-save">保存</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a onclick="windowclose('data')" class="easyui-linkbutton" iconCls="icon-undo">取消</a>
        </div>
    </div>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            toolbars: [
                ['fullscreen', 'source', 'undo', 'redo'],
                ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
            ],
            autoHeightEnabled: true,
            autoFloatEnabled: true
        });
    </script>
</div>

</div>
</body>
</html>