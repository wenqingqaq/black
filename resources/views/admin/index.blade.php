<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>后台首页</title>
    @include('admin.common')
    <script type="text/javascript" src="/admin/j/menu.js"></script>
    <script type="text/javascript" src="/admin/j/siteNavigation.js"></script>
    <link rel="stylesheet" type="text/css" href="/common/c/bootstrap/admin_index.css">
    <!-- Date: 2014-12-15 -->
    <style>
        .checkbutton_wrap{
            margin: 200px 600px;
        }
        .checkbutton{
            width : 120px;
            height : 40px;
            float : left;
            margin-right: 20px;
        }
        .easyui-linkbutton span{
            width : 120px;
            height : 40px;
            font-size: 20px;
            text-align: center;
            line-height : 40px;
        }
    </style>
</head>
<body class="easyui-layout">
<div data-options="region:'north',border:false" class="cat-admin-top"
     style="height: 60px;">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'west',border:false"
             style="width: 150px; border: none;">
            <div class="cat-admin-logo" style="overflow: hidden; border: none;">
                <img src="/common/c/bootstrap/img/logo.png" />
            </div>
        </div>
        <div data-options="region:'center',border:false" id="topmenus">

        </div>
        <div data-options="region:'east',border:false" style="width: 200px;">
            <div class="easyui-layout" fit="true">
                <div data-options="region:'west',border:false" style="width: 120px;">
                    <a id="sitenavigation" href="javascript:void(0);"><img
                                src="/common/c/bootstrap/img/site.png" height="50px" width="50px" style="cursor: pointer;"
                                title="网站导航" class="easyui-tooltip" /></a>

                    <a href="{:U('Index/Index/logout')}"> <img
                                src="/common/c/bootstrap/img/close.png" style="cursor: pointer;"
                                class="easyui-tooltip" title="退出系统" />
                    </a>
                </div>
                <div data-options="region:'center',border:false">
                    <div class="cat-admin-topname">{$userName}</div>
                    <div class="cat-admin-topname" onclick="resetPassword()"><font color="red">修改密码</font></div>
                </div>
                <div data-options="region:'center',border:false">
                </div>
            </div>
        </div>
    </div>
</div>
<div data-options="region:'west',split:true,iconCls:'icon-nav'"
     title="导航菜单" style="width: 150px;">
    <div id="leftmenu" class="easyui-accordion" fit="true" border="false">
    </div>
</div>
<div data-options="region:'center'">
    <div class="easyui-tabs" fit="true" id="windowtabs"
         data-options="border:false">
        <div title="系统首页" data-options="iconCls:'icon-index'">
        </div>
    </div>
    <div data-options="region:'south',border:false" style="height: 25px;"
         class="cat-admin-bottom">{:L('COPY_RIGHT')}</div>
    <!-- tab右键菜单 -->
    <div id="tabmenus" class="easyui-menu" style="width: 120px;">
        <div id="reloadtab" iconCls="icon-reload">刷新</div>
        <div class="menu-sep"></div>
        <div id="closethis">关闭当前</div>
        <div id="closeall">关闭全部</div>
        <div id="closeright">关闭右侧全部</div>
        <div id="closeleft">关闭左侧全部</div>
        <div id="closeother">关闭除此全部</div>
        <div class="menu-sep"></div>
        <div id="mmindex" iconCls="icon-save">系统首页</div>
    </div>
    <div id="setpasswindow" title="修改密码">
        <div class="easyui-layout" fit="true">
            <div data-options="region:'center',border:false">
                <form id="setpassform" method="post">
                    <table width="100%" class="cat-form">
                        <tr>
                            <td class="cat-label">原密码：</td>
                            <td><input type="password" name="oldPass"
                                       class="cat-input easyui-validatebox"
                                       data-options="required:true,validType:'length[6,20]'" /></td>
                        </tr>
                        <tr>
                            <td class="cat-label">新密码：</td>
                            <td><input type="password" name="newPass"
                                       class="cat-input easyui-validatebox"
                                       data-options="required:true,validType:'length[6,20]'" /></td>
                        </tr>
                        <tr>
                            <td class="cat-label">确认密码：</td>
                            <td><input type="password" name="newPass2"
                                       class="cat-input easyui-validatebox"
                                       data-options="required:true,validType:'length[6,20]'" /></td>
                        </tr>

                    </table>
                </form>
            </div>
            <div data-options="region:'south',height:40,border:false"
                 style="border-top: 1px solid #CCCCCC; text-align: right; padding-top: 5px; padding-right: 10px">
                <a class="easyui-linkbutton"
                   onclick="dosubmit('setpass')">保存</a>
            </div>
        </div>
    </div>

</div>
<div id="messagewindow" title="我的消息">
    <iframe id="messageframe" src="" width="486px" height="364px"
            frameborder="0"></iframe>
</div>

<div id="sitenavigationwindow" title="网站导航">
    <div class="easyui-layout" fit="true">
        <div data-options="region:'center',border:false" id="data">

        </div>
    </div>
</div>

<script type="text/javascript">
    objs.menuurl = "{{url('getMenuList')}}";
    objs.getlangurl = "{:U('Index/getlang')}";
    objs.loginouturl = "{:U('logout')}";
    objs.setpassurl = "{:U('Index/setpass')}";
    //    objs.messageurl = "{:U('Index/message')}";
    //    objs.messagelisturl = "{:U('Index/messagelist')}";
    //    objs.messageallurl = "{:U('Index/messageall')}";
    //    var L = {};

    objs.dataWindow = $("#setpasswindow").window({
        width : 500,
        height : 400,
        closed : true,
        modal : true,
        collapsible : false,
        minimizable : false,
        maximizable : false
    });

    objs.dataform = $("#setpassform");
    var dataformdata = objs.dataform.serializeArray();
    objs.dataformdefault = [];
    $.each(dataformdata, function(i, o) {
        objs.dataformdefault[o.name] = o.value;
    });

    /**
     * 重置密码
     */
    function resetPassword()
    {
        objs.dataform.attr('action', "{:U('resetPassword')}");
        objs.dataWindow.window("open");
        objs.dataform.form('load', objs.dataformdefault);
    }
</script>
</body>
</html>