<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>内部管理系统</title>
    <meta charset="UTF-8">
    @include('admin.common')
    <link rel="stylesheet" type="text/css" href="/common/c/bootstrap/admin_login.css">
    <script type="text/javascript">

        var objs = {};
        objs.loginCheckURL = "{:U('loginCheck', '', false)}"; // 提交登录信息的 URL，不带伪静态后缀

        $(function(){
            $("#user").focus();
            $(document).keypress(function(e) {
                switch (e.which) {
                    case 13:
                        dosubmit('login');
                        break;
                }
            });
            $("#user").change(function() {
                $("#pass").val('');
            });
        });
        function reloadlogingrid() {
            location.href = "{:U('index')}";
            $.messager.progress({
                title: '登陆成功',
                msg: '登陆成功，系统正在进入后台...'
            });
        }

        /**
         * 提交表单，传入表单的ID，会自动更新datagrid
         */
        function dosubmit(id) {

            var loginForm = $('#' + id + 'form');

            if (loginForm.form('validate')) {

                var param = loginForm.serialize();

                $.ajax({
                    url        : objs.loginCheckURL + '?' + param,
                    type       : 'GET',
                    dataType   : 'JSON',
                    beforeSend : function(){
                        loading(0);
                    },
                    success    : function(data, textStatus, jqXHR){
                        loading(1);

                        $.messager.alert('提示', data.msg);

                        if (data.type == 'suc') {
                            try {
                                $('#' + id + 'window').window("close");
                            } catch (e) {
                            }

                            try {
                                eval('reload' + id + 'grid();');
                            } catch (e) {
                            }

                        } else {
                            $('#img_verify')[0].src = '{$src=U(\'img_verify\')}?_=' + new Date().getTime();
                        }
                    }
                });

                return true;
            } else {
                return false;
            }
        }


        /**
         * 显示进度条，当传入true时关闭，传入false时显示
         * text参数是在提示过程中显示的提示信息， 不填写会默认显示处理中，请稍候
         */
        function loading(status, text) {
            if (status) {
                $.messager.progress('close');
            } else {
                if (!text) {
                    text = '处理中，请稍后...';
                }
                $.messager.progress({
                    title: '请稍候',
                    text: text
                });
            }
        }
    </script>
    <style type="text/css">
        html {
            background-color: #CCCCCC;
        }
    </style>
</head>
<body>
<div class="mlogin_top"><img src="/common/c/bootstrap/img/mlogin_logo.png" width="400" height="52" /></div>
<div class="mlogin_box">
    <img class="mlogin_Img" src="/common/c/bootstrap/img/login_bg.jpg" width="425" height="339" />
    <div class="mlogin_fm">
        <form id="loginform" method="post" action="{:U('loginCheck')}">
            <ul>
                <li><span class="mlogin_s1">用户名</span><input class="mlogin_int" name="user" type="text" id="user" data-options="required:true"/></li>
                <li><span class="mlogin_s1">密 &nbsp;&nbsp; 码</span><input class="mlogin_int" name="pass" type="password" id="pass" data-options="required:true"/></li>
                <li class="mlogin_li01"><span class="mlogin_s1">验证码</span><input class="mlogin_int mlogin_int2" id="vcode" name="vcode" type="text" data-options="required:true"/><a style='display: inline-block;margin-left:1em;' href="javascript:void($('#img_verify')[0].src='{$src=U(\'img_verify\')}?_='+new Date().getTime())"><img id='img_verify' src="{$src}" style="vertical-align:sub;" title='看不清,换一张'/></a></li>
                <li class="mlogin_li02"><span class="mlogin_s1">&nbsp;</span><input class="mlogin_chk" name="auto" id="auto" type="checkbox" value="on" /><span class="mlogin_red"><label for="auto">自动登录</label></span><a style='display: inline-block;margin-left:1em;' href="javascript:void($('#img_verify')[0].src='{$src=U(\'img_verify\')}?_='+new Date().getTime())">看不清，换一张</a></li>
                <li class="mlogin_li03"><button type="button" class="mLogin_submit" onclick="dosubmit('login');"><span>登陆</span></button></li>
            </ul>
        </form>
    </div>
</div>
<div class="mlogin_footer">内部管理系统</div>
<script language="javascript">
    var magTop=($(window).height()-153-415)/2;
    $(".mlogin_box").css("margin-top",magTop);
    $(window).resize(function(){var magTop=($(window).height()-153-415)/2;$(".mlogin_box").css("margin-top",magTop);});
</script>
</body>
</html>