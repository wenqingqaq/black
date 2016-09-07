<link rel="stylesheet" type="text/css" href="/common/j/jquery-easyui-1.4.1/themes/bootstrap/easyui.css">
<link rel="stylesheet" type="text/css" href="/common/j/jquery-easyui-1.4.1/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/common/c/style.css">
<link rel="stylesheet" type="text/css" href="/common/c/bootstrap/style.css">
<link rel="stylesheet" type="text/css" href="/common/c/bootstrap/icon.css">
<script type="text/javascript" src="/common/j/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/common/j/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/common/j/easyui/viewjs/datagrid-groupview.js"></script>
<script type="text/javascript" src="/common/j/common.func.js"></script>
<script type="text/javascript" src="/common/j/php.full.namespaced.min.js"></script>
<script type="text/javascript" src="/common/j/jquery-easyui-1.4.1/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/common/j/plupload-2.0.0/js/plupload.full.min.js"></script>
<script>
    var Obj= {};
    var objs = { };
    var CONFIG = { };
    var PHP = { };
    var L = top.L;
    var FILEUPLOAD = [];

    /* 鍛ㄩ懌娣诲姞锛屾椂闂存埑鍜屾牸寮忔椂闂磋浆鎹�
     *
     * console.log($.myTime.DateToUnix('2014-5-15 20:20:20'));
     * console.log($.myTime.UnixToDate(1325347200));
     *
     * */
    (function($) {
        $.extend({
            myTime: {
                /**
                 * 褰撳墠鏃堕棿鎴�
                 * @return <int>        unix鏃堕棿鎴�绉�
                 */
                CurTime: function(){
                    return Date.parse(new Date())/1000;
                },
                /**
                 * 鏃ユ湡 杞崲涓�Unix鏃堕棿鎴�
                 * @param <string> 2014-01-01 20:20:20  鏃ユ湡鏍煎紡
                 * @return <int>        unix鏃堕棿鎴�绉�
                 */
                DateToUnix: function(string) {
                    var f = string.split(' ', 2);
                    var d = (f[0] ? f[0] : '').split('-', 3);
                    var t = (f[1] ? f[1] : '').split(':', 3);
                    return (new Date(
                                    parseInt(d[0], 10) || null,
                                    (parseInt(d[1], 10) || 1) - 1,
                                    parseInt(d[2], 10) || null,
                                    parseInt(t[0], 10) || null,
                                    parseInt(t[1], 10) || null,
                                    parseInt(t[2], 10) || null
                            )).getTime() / 1000;
                },
                /**
                 * 鏃堕棿鎴宠浆鎹㈡棩鏈�
                 * @param <int> unixTime    寰呮椂闂存埑(绉�
                 * @param <bool> isFull    杩斿洖瀹屾暣鏃堕棿(Y-m-d 鎴栬� Y-m-d H:i:s)
                 * @param <int>  timeZone   鏃跺尯
                 */
                UnixToDate: function(unixTime, isFull, timeZone) {
                    if (typeof (timeZone) == 'number')
                    {
                        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                    }

                    var time = new Date(unixTime * 1000);
                    var ymdhis = "";
                    ymdhis += time.getFullYear() + "-";
                    ymdhis += (time.getMonth()+1) + "-";
                    ymdhis += time.getDate();
                    if (isFull === true)
                    {
                        ymdhis += " " + time.getHours() + ":";
                        ymdhis += time.getMinutes() + ":";
                        ymdhis += time.getSeconds();
                    }
                    return ymdhis;
                }
            }
        });
    })(jQuery);


</script>
<script type="text/javascript" src="/common/j/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/common/j/ueditor/ueditor.all.js"></script>