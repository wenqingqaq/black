<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title>全部博客</title>
    @include('admin.common')
    <script type="text/javascript">
        CONFIG.GRIDTITLE = '全部博客列表';
        CONFIG.getlisturl = "{:U('blog/getList')}";
        CONFIG.addurl = "{:U('add')}";
        CONFIG.editurl = "{:U('edit')}";
        CONFIG.disurl = "{:U('dis')}";
        CONFIG.COLUMNS = [
            {
                field : 'ck',
                checkbox : true
            },
            {
                field : 'project_id',
                title : '编号',
                width : 80
            },
            {
                field : 'project',
                title : '大厦名字',
                width : 100
            },
            {
                field : 'address',
                title : '地址',
                width : 200
            },
            {
                field : 'wuyecompanyname',
                title : '相关物业',
                width : 80
            },
            {
                field : 'contactername',
                title : '相关联系人',
                width : 80
            },
            {
                field : 'progress',
                title : '进度',
                width : 80
            },
            {
                field : 'status',
                title : '状态',
                width : 80
            },
            {
                field : 'brand_id',
                title : '认领人',
                width : 80
            },
            {
                field : 'brand_id',
                title : '相关记录',
                width : 80
            },
            {
                field : 'remark',
                title : '备注',
                width : 80
            },
            {
                field : 'create_time',
                title : '加入时间',
                width : 200
            }
        ];

        $(function(){

            //搜索状态的下拉菜单
            $("#rlStatus").combobox({
                url:"{:U('getRlStatus')}",
                valueField:'status_id',
                textField:'status_name'
            });

            //搜索进度的下拉菜单
            $("#hzProgress").combobox({
                url:"{:U('getProgressStatus')}",
                valueField:'status_id',
                textField:'status_name'
            });

            //新增大厦物业下拉框
            $("#wuye_company_id").combobox({
                url:"{:U('getWuYeList')}",
                valueField:'status_id',
                textField:'status_name',
                onSelect:function(record){
                    //触发选择下拉框改变的事件
                    var wuYeCompanyId = record.status_id;

                    $("#wuye_contacter_id").combobox({
                        url:"{:U('getLxrForSelect')}",
                        valueField:'contacter_id',
                        textField:'name',
                        onBeforeLoad : function(param) {
                            param.wuYeCompanyId = wuYeCompanyId;// 参数:CodeType值.
                        }
                    });
                }
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
                loadMsg : '{$Think.lang.LOADING_DATA}',
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

        function addData() {
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
            城市
            <input type="text" name="city_id" class="cat-search-input easyui-combobox" style="width:100px;" />
            区域
            <input type="text" name="region_id" class="cat-search-input easyui-combobox" style="width:100px;" />
            请输入大厦名：
            <input type="text" name="project" class="cat-input cat-search-input" />
            认领状态：
            <input type="text" name="status" id="rlStatus" class="cat-search-input easyui-combobox" />
            合作进度
            <input type="text" name="progress" id="hzProgress" class="cat-search-input easyui-combobox" />
            <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="reloaddatagrid();">搜索</a>
        </form>
    </div>
</div>

<div id="datawindow" title="新增楼盘">
    <div class="easyui-layout" fit="true" style="border: 0;">
        <div data-options="region:'center'" style="width: 50px; border: 0;">
            <form id="dataform" class="cat-form" method="post">
                <table>
                    <tr>
                        <td><input type="hidden" id="project_id" name="project_id">
                            <input type="hidden" id="bus_id" name="bus_id"> <!-- <input type="hidden" name="region_id"> -->
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">大厦名：</td>
                        <td><input name="project" id = "project" type="text" class="cat-input easyui-validatebox" required="required"></td>
                    </tr>
                    <tr>
                        <td class="cat-label">物业：</td>
                        <td><input name="wuye_company_id" id="wuye_company_id" class="easyui-combobox easyui-validatebox"  /></td>
                    </tr>
                    <tr>
                        <td class="cat-label">物业联系人：</td>
                        <td><input name="wuye_contacter_id" id = "wuye_contacter_id" class="easyui-combobox easyui-validatebox"></td>
                    </tr>
                    <tr>
                        <td class="cat-label">地区：</td>
                        <td style="width:500px;"><include file="Public/regional" /></td>
                    </tr>
                    <tr>
                        <td class="cat-label">大厦地址：</td>
                        <td><input type="text" id="address"
                                   onblur="javascript:addressMap()" name="address"
                                   class="cat-input  easyui-validatebox"
                                   data-options="required:true" /> <br>
                            <div class="cat-messeager" style="margin: 0; padding: 0;">请以地图选择的地址为准，否则无法正确计算距离</span></div>
                        <td><input type="hidden" name="lng" id="longitude"
                                   readonly="readonly" style="width: 110px;" /> <input
                                    type="hidden" name="lat" id="latitude" readonly="readonly"
                                    style="width: 110px;" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="cat-label">
                            <div id="dituContent" style="margin: 0px; width: 400px; height: 300px; border: 1px solid #808080;"></div>
                            <div class="cat-messeager" style=" margin: 0; padding: 0; width: 400px; text-align: left;">请在地图上点击云超市所在位置，可以通过放大地图选择更加精确的位置。系统会自动计算坐标和地址信息。</div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south'"
             style="height: 50px; border-left: 0px; border-right: 0px; border-bottom: 0px; padding-top: 12px"
             align="center">
            <a onclick="dosubmit('data')" class="easyui-linkbutton"
               iconCls="icon-save">保存</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                    onclick="windowclose('data')" class="easyui-linkbutton" iconCls="icon-undo">取消</a>
        </div>
    </div>
</div>

</div>
</body>
</html>