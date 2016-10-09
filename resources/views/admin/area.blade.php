<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>区域管理</title>
    <link rel="stylesheet" type="text/css" href="/common/j/jquery-easyui-1.4.1/themes/bootstrap/easyui.css">
    <link rel="stylesheet" type="text/css" href="/common/j/jquery-easyui-1.4.1/themes/icon.css">
    @include('admin.common')
    <script type="text/javascript">
        var CONFIG = {};
        var objs = {};
        CONFIG.WINDOWWIDTH = '500';
        CONFIG.WINDOWHEIGHT = '350';
        CONFIG.WESTGRIDTITLE = '省份列表';
        CONFIG.WESTWINDOWWIDTH = '200';
        CONFIG.WESTWINDOWHEIGHT = '200';
        CONFIG.WESTGRIDSORTNAME = 'province';
        CONFIG.WESTGRIDSORTORDER = 'asc';
        CONFIG.getProvince = "{{url('area/getProvince')}}";
        CONFIG.getCity = "{{url('area/getCity')}}";
        CONFIG.getRegion = "{{url('area/getRegion')}}";
        CONFIG.addRegion = "{{url('area/addRegion')}}";
        CONFIG.editRegion = "{:U('editRegion')}";
        CONFIG.delRegion = "{:U('delRegion')}";
        CONFIG.addCity = "{:U('addCity')}";
        CONFIG.editCity = "{:U('editCity')}";
        CONFIG.delCity = "{:U('delCity')}";
        CONFIG.addProvince = "{:U('addProvince')}";
        CONFIG.editProvince = "{:U('editProvince')}";
        CONFIG.delProvince = "{:U('delProvince')}";
        CONFIG.WESTCOLUMNS = [{
            field : 'province',
            title : '省份名称',
            width : 120
        }];
        CONFIG.CENTERGRIDTITLE = '城市列表';
        CONFIG.CENTERGRIDSORTNAME = 'ordering';
        CONFIG.CENTERGRIDSORTORDER = 'asc';
        CONFIG.CENTERWINDOWWIDTH = '200';
        CONFIG.CENTERWINDOWHEIGHT = '350';
        CONFIG.CENTERCOLUMNS = [{
            field : "city",
            title : "城市名称",
            width : 100
        },{
            field : "city_code",
            title : "城市编码",
            width : 100
        },{
            field : "ordering",
            title : "排  序",
            width : 100
        }];

        CONFIG.GRIDTITLE = '区域列表';
        CONFIG.GRIDSORTNAME = 'ordering';
        CONFIG.GRIDSORTORDER = 'asc';
        CONFIG.COLUMNS = [{
            field : "region",
            title : "区域名称",
            width : 100
        },{
            field : "ordering",
            title : "排  序",
            width : 100
        }];
        $(function (){
            objs.westdatagrid = $("#westdatagrid").datagrid({
                title : CONFIG.WESTGRIDTITLE,
                toolbar : '#westtoolbar',
                fit : true,
                border : false,
                striped : true,
                rownumbers : true,
                pagination:true,
                nowrap:false,
                singleSelect:true,
                pageSize : 200,
                pageList : [ 50, 100, 200 ],
                url:CONFIG.getProvince,
                sortName : CONFIG.WESTGRIDSORTNAME,
                sortOrder : CONFIG.WESTGRIDSORTORDER,
                columns : [CONFIG.WESTCOLUMNS],
                onRowContextMenu:function (e,row,data){
                    e.preventDefault();
                    $(this).datagrid('unselectAll');
                    $(this).datagrid('selectRow', row);
                    $('#contentmenu').menu('show', {
                        left: e.pageX,
                        top: e.pageY
                    });
                },
                onSelect : function(index, row) {
                    reloadcenterdatagrid();
                },
                onLoadSuccess : function(row, data) {
                    $(this).datagrid("unselectAll");
                    $(this).datagrid("selectRow", 0);
                }
            });
            objs.centerdatagrid = $("#centerdatagrid").datagrid({
                title : CONFIG.CENTERGRIDTITLE,
                toolbar : '#centertoolbar',
                fit : true,
                border : false,
                striped : true,
                rownumbers : true,
                pagination : true,
                nowrap : false,
                singleSelect : true,
                pageSize : 200,
                pageList : [ 50, 100, 200 ],
                sortName : CONFIG.CENTERGRIDSORTNAME,
                sortOrder : CONFIG.CENTERGRIDSORTORDER,
                columns : [CONFIG.CENTERCOLUMNS],
                onSelect : function(index, row) {
                    reloaddatagrid();
                }
            });
            objs.datagrid = $("#datagrid").datagrid({
                title : CONFIG.GRIDTITLE,
                toolbar : '#toolbar',
                fit : true,
                border : false,
                striped : true,
                rownumbers : true,
                pagination : true,
                nowrap : false,
                singleSelect : true,
                pageSize : 200,
                pageList : [ 50, 100, 200 ],
                sortName : CONFIG.GRIDSORTNAME,
                sortOrder : CONFIG.GRIDSORTORDER,
                columns : [CONFIG.COLUMNS],
            });
            objs.westdatawindow = $("#westdatawindow").window({
                width : CONFIG.WINDOWWIDTH,
                height : CONFIG.WINDOWHEIGHT,
                closed : true,
                modal : true,
                collapsible : false,
                minimizable : false,
                maximizable : false
            });
            objs.westdataform = $('#westdataform');
            var westdataformdata = objs.westdataform.serializeArray();
            objs.westdataformdefault = [];
            $.each(westdataformdata, function(i, o) {
                objs.westdataformdefault[o.name] = o.value;
            });
            objs.centerdatawindow = $("#centerdatawindow").window({
                width : CONFIG.WINDOWWIDTH,
                height : CONFIG.WINDOWHEIGHT,
                closed : true,
                modal : true,
                collapsible : false,
                minimizable : false,
                maximizable : false
            });
            objs.centerdataform = $('#centerdataform');
            var centerdataformdata = objs.centerdataform.serializeArray();
            objs.centerdataformdefault = [];
            $.each(centerdataformdata, function(i, o) {
                objs.centerdataformdefault[o.name] = o.value;
            });
            objs.datawindow = $("#datawindow").window({
                width : CONFIG.WINDOWWIDTH,
                height : CONFIG.WINDOWHEIGHT,
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
            //初始化首字母和拼音
            $("#city").change(function() {
                $("#py").val($(this).toPinyinIndex());
            });
        });
        function addProvince(){
            objs.westdataform.attr('action', CONFIG.addProvince);
            objs.westdatawindow.window('open');
            objs.westdataform.form('load', objs.westdataformdefault);
        }
        function editProvince() {
            var row = objs.westdatagrid.datagrid('getSelected');
            if (row) {
                objs.westdataform.attr('action', CONFIG.editProvince);
                objs.westdatawindow.window('open');
                objs.westdataform.form('load', objs.westdataformdefault);
                objs.westdataform.form('load', row);
            } else {
                showMsg('请先选中一条记录');
            }
        }
        function delProvince() {
            $.messager.confirm('系统提示','是否确定删除！',function(r){
                if (r){
                    var row = objs.westdatagrid.datagrid('getSelected');
                    if (row) {
                        loading();
                        $.post(CONFIG.delProvince,{'province_id':row.province_id},function (data){
                            loading(1);
                            showMsg(data.message);
                            reloadwestdatagrid();
                        },'json');
                    } else {
                        showMsg('请先选中一条记录');
                    }
                }
            });
        }

        function addCity(){
            objs.centerdataform.attr('action', CONFIG.addCity);
            objs.centerdatawindow.window('open');
            objs.centerdataform.form('load', objs.centerdataformdefault);
            var row = objs.westdatagrid.datagrid('getSelected');
            $('#province').val(row.province_id);
            $('#provincename').html(row.province);
            setImg_pic_e('');
        }
        function editCity() {
            var row1 = objs.westdatagrid.datagrid('getSelected');
            var row = objs.centerdatagrid.datagrid('getSelected');
            if (row) {
                objs.centerdataform.attr('action', CONFIG.editCity);
                objs.centerdatawindow.window('open');
                objs.centerdataform.form('load', objs.centerdataformdefault);
                objs.centerdataform.form('load', row);
                $('#provincename').html(row1.province);
                if (row.city_pic) {
                    setImg_pic_e(row.city_pic);
                } else {
                    setImg_pic_e('');
                }
            } else {
                showMsg('请先选中一条记录');
            }
        }
        function delCity() {
            $.messager.confirm('系统提示','是否确定删除！',function(r){
                if (r){
                    var row = objs.centerdatagrid.datagrid('getSelected');
                    if (row) {
                        loading();
                        $.post(CONFIG.delCity,{'city_id':row.city_id},function (data){
                            loading(1);
                            showMsg(data.message);
                            reloadcenterdatagrid();
                        },'json');
                    } else {
                        showMsg('请先选中一条记录');
                    }
                }
            });
        }

        function addRegion(){
            objs.dataform.attr('action', CONFIG.addRegion);
            objs.datawindow.window('open');
            objs.dataform.form('load', objs.dataformdefault);
            var row = objs.centerdatagrid.datagrid('getSelected');
            $('#cityId').val(row.city_id);
            $('#cityname').html(row.city);
        }
        function editRegion() {
            var row1 = objs.centerdatagrid.datagrid('getSelected');
            var row = objs.datagrid.datagrid('getSelected');
            if (row) {
                objs.dataform.attr('action', CONFIG.editRegion);
                objs.datawindow.window('open');
                objs.dataform.form('load', objs.dataformdefault);
                objs.dataform.form('load', row);
                $('#cityname').html(row1.city);
            } else {
                showMsg('请先选中一条记录');
            }
        }
        function delRegion() {
            $.messager.confirm('系统提示','是否确定删除！',function(r){
                if (r){
                    var row = objs.datagrid.datagrid('getSelected');
                    if (row) {
                        loading();
                        $.post(CONFIG.delRegion,{'region_id':row.region_id},function (data){
                            loading(1);
                            showMsg(data.message);
                            reloaddatagrid();
                        },'json');
                    } else {
                        showMsg('请先选中一条记录');
                    }
                }
            });
        }
        //清空所有列表
        function clear(){
            var data={};
            data.rows=[];
            data.total=0;
            objs.datagrid.datagrid('loadData',data);
        }

        function reloadwestdatagrid(){
            var searchformdata = $('#searchform').serializeArray();
            objs.searchformdefault = [];
            $.each(searchformdata,function (i,o){
                objs.searchformdefault[o.name] = o.value;
            })
            //表单提交[成功]后的回调，可以添加自己的逻辑刷新combobox等
            objs.westdatagrid.datagrid({
                queryParams:objs.searchformdefault
            });
        }
        //加载城市
        function reloadcenterdatagrid() {
            var row = objs.westdatagrid.datagrid('getSelected');
            if (row) {
                objs.centerdatagrid.datagrid({
                    url : CONFIG.getCity,
                    queryParams : {
                        province_id : row.province_id,
                    },
                    onLoadSuccess : function(row, data) {
                        if (row.total > 0) {
                            $(this).datagrid('selectRow', 0);
                        }else{
                            clear();
                        }
                    }
                });
            } else {
                return;
            }
        }
        //加载区域
        function reloaddatagrid() {
            var row = objs.centerdatagrid.datagrid('getSelected');
            if (row) {
                objs.datagrid.datagrid({
                    url : CONFIG.getRegion,
                    queryParams : {
                        city_id : row.city_id,
                    }
                });
            } else {
                return;
            }
        }
    </script>
</head>
<body class="easyui-layout">
<div data-options="region:'west',border:false,split:true,width:300">
    <div id="westdatagrid"></div>
    <div id="westtoolbar">
        <div class="cat-toobarmenus">
            <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addProvince()">添加</a>
            <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editProvince()">编辑</a>
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delProvince()">删除</a>
        </div>
        <div class="cat-toobarmenus">
            <form id="searchform" onsubmit="return false;">
                省份：
                <input type="text" name="name" class="cat-input cat-searchinput" style="width:100px" />
                <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="reloadwestdatagrid();">搜索</a>
            </form>
        </div>
    </div>
</div>
<div id="westdatawindow" title="省份管理">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false" >
            <form id="westdataform" method="post" onsubmit="return false;">
                <table width="100%" class="cat-form">
                    <tr>
                        <td class="cat-label">省份名称： </td>
                        <td>
                            <input type="hidden" name="province_id" id="province_id" />
                            <input type="text" name="province" class="cat-input easyui-validatebox" data-options="required:true"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south',height:40,border:false" style="border-top:1px solid #CCCCCC;text-align: right; padding-top: 5px; padding-right: 10px" >
            <a class="easyui-linkbutton" iconCls="icon-save" onclick="dosubmit('westdata')">保存</a>
        </div>
    </div>
</div>
<!-- 右键菜单 -->
<div id="westcontentmenu" class="easyui-menu" style="width:150px;">
    <div iconCls="icon-reload" onclick="reloadwestdatagrid();">刷新</div>
    <div class="menu-sep"></div>
    <div iconCls="icon-add" onclick="addProvince();">添加</div>
    <div iconCls="icon-edit" onclick="editProvince();">编辑</div>
    <div iconCls="icon-tip" onclick="delProvince()">删除</div>
</div>
<div data-options="region:'center',border:false">
    <div class="easyui-layout" fit="true" >
        <div data-options="region:'center',border:false">
            <div id="centerdatagrid"></div>
            <div id="centertoolbar">
                <div class="cat-toobarmenus">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addCity()">添加</a>
                    <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCity()">编辑</a>
                    <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delCity()">删除</a>
                    <font color="red">( 如果您对 省份列表 或者 城市列表 进行了操作，一定要操作一下区域列表哦！)</font>
                </div>
            </div>
        </div>
        <div data-options="region:'east',border:false,split:true,width:450">
            <div class="easyui-layout" fit="true">
                <div data-options="region:'center',border:false">
                    <div id="datagrid"></div>
                    <div id="toolbar">
                        <div class="cat-toobarmenus">
                            <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addRegion()">添加</a>
                            <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editRegion()">编辑</a>
                            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delRegion()">删除</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="centerdatawindow" title="城市管理">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false" >
            <form id="centerdataform" method="post" onsubmit="return false;">
                <table width="100%" class="cat-form">
                    <tr>
                        <td class="cat-label">所属省份： </td>
                        <td>
                            <span id="provincename"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">城市名称： </td>
                        <td>
                            <input type="hidden" name="city_id" id="city_id" />
                            <input type="hidden" name="province_id" id="province" />
                            <input type="text" name="city" id="city" class="cat-input easyui-validatebox" data-options="required:true"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">城市编码： </td>
                        <td>
                            <input type="text" name="city_code" class="cat-input easyui-validatebox" data-options="required:true"/>
                            <font color="red">如:北京010，上海021</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">排&nbsp;&nbsp;序： </td>
                        <td>
                            <input type="text" name="ordering" class="cat-input easyui-validatebox" data-options="required:true"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">拼音： </td>
                        <td>
                            <input type="text" name="py" id="py" class="cat-input easyui-validatebox" />
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">城市图片：</td>
                        <td><input type="hidden" id="city_pic" name="city_pic"
                                   style="width: 260px;" data-options="required:true" />
                            <div id="upload_image_city_pic"></div>
                            <div style="margin-top: 0px; padding-left: 0px;"
                                 id="plcontainer">
                                <a class="easyui-linkbutton" id="pickfiles">选择城市图片</a>
                                <div style="color: red">(只能上传jpg,gif,png格式的图片)</div>
                            </div>
                            <div id="upload_percent" style="width: 220px;"></div></td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south',height:40,border:false" style="border-top:1px solid #CCCCCC;text-align: right; padding-top: 5px; padding-right: 10px" >
            <a class="easyui-linkbutton" iconCls="icon-save" onclick="dosubmit('centerdata')">保存</a>
        </div>
    </div>
</div>
<!-- 右键菜单 -->
<div id="centercontentmenu" class="easyui-menu" style="width:150px;">
    <div iconCls="icon-reload" onclick="reloadcenterdatagrid();">刷新</div>
    <div class="menu-sep"></div>
    <div iconCls="icon-add" onclick="addCity();">添加</div>
    <div iconCls="icon-edit" onclick="editCity();">编辑</div>
    <div iconCls="icon-tip" onclick="delCity()">删除</div>
</div>
<div id="datawindow" title="地区管理">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false" >
            <form id="dataform" method="post" onsubmit="return false;">
                <table width="100%" class="cat-form">
                    <tr>
                        <td class="cat-label">所属城市： </td>
                        <td>
                            <span id="cityname"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">地区： </td>
                        <td>
                            <input type="hidden" id="region_id" name="region_id"/>
                            <input type="hidden" id="cityId" name="city_id"/>
                            <input type="text" id="region" name="region"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="cat-label">排序： </td>
                        <td>
                            <input type="text" id="ordering" name="ordering"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div data-options="region:'south',height:40,border:false" style="border-top:1px solid #CCCCCC;text-align: right; padding-top: 5px; padding-right: 10px" >
            <a class="easyui-linkbutton" iconCls="icon-save" onclick="dosubmit('data')">保存</a>
        </div>
    </div>
</div>
<!-- 右键菜单 -->
<div id="contentmenu" class="easyui-menu" style="width:150px;">
    <div iconCls="icon-reload" onclick="reloaddatagrid();">刷新</div>
    <div class="menu-sep"> </div>
    <div iconCls="icon-add" onclick="addRegion();">添加</div>
    <div iconCls="icon-edit" onclick="editRegion();">编辑</div>
    <div iconCls="icon-tip" onclick="delRegion()">删除</div>
</div>
</body>
<script>
    function setImg_pic_e(picpath) {
        $("#upload_image_city_pic")
                .html(
                        '<img src="http://image.lz517.com/image/' + picpath
                        + '" width="200" height="150" style="background: #FFFFFF url(/common/i/loading.gif) no-repeat center;" />');
        $('#city_pic').val(picpath);//????周鑫这里应该是个bug
    }
    function setImg_pic(picpath, picname) {
        $("#upload_image_city_pic")
                .html(
                        '<img src="http://image.lz517.com/image/' + picpath + picname
                        + '" width="200" height="150" style="background: #FFFFFF url(/common/i/loading.gif) no-repeat center;" />');
        $('#city_pic').val(picpath + picname);
    }

    //图片上传;
    var uploader = new plupload.Uploader({
        runtimes : 'html5,html4,flash,silverlight',
        browse_button : 'pickfiles',
        container : document.getElementById('plcontainer'),
        url : "{:U('upload')}",
        flash_swf_url : '/common/j/js/plupload-2.0.0/Moxie.swf',
        silverlight_xap_url : '/common/j/plupload-2.0.0/js/Moxie.xap',
        filters : {
            max_file_size : '1mb',
            mime_types : [ {
                title : "Image files",
                extensions : "jpg,gif,png,jpeg",
                prevent_duplicates : true
            } ]
        },
        init : {
            PostInit : function() {
            },
            FilesAdded : function(up, files) {
                uploader.start();
                $('#upload_percent').show().progressbar({
                    value : 0
                });
            },
            UploadProgress : function(up, file) {
                //文件上传过程中不断触发，可以用此事件来显示上传进度;
                $('#upload_percent').progressbar({
                    value : file.percent
                });
            },
            FileUploaded : function(up, file, res) {
                //console.log(res.response);
                //队列中的某一个文件上传完成后触发;
                var obj = eval('(' + res.response + ')');
                //console.log(obj);
                setImg_pic_e(obj.url);
                $('#upload_percent').slideUp('fast');
            },
            Error : function(up, err) {
                $.messager.alert(err.code, err.message);
            }
        }
    });
    uploader.init();

</script>
</html>

