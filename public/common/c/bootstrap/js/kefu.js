/**
 * @author qiu
 */
objs.titles = [];
function setMenu() {
	$("#topmenus").html('');
	$.each(objs.menudata.one, function(i, o) {
		var html = '<div class="cat-admin-topmenu">' + '<div class="cat-admin-topicon">' + '	<img src= "' + CONFIG.PUBLIC_PATH + '/'+CONFIG.DEFAULT_THEME_CSS+'/img/' + o.icon + '.png" />' + '</div>' + '<div class="cat-admin-topmenutitle">' + o.nickname + '</div>' + '</div>';

		if (i == 0) {
			$(html).appendTo("#topmenus").bind({
				click : function() {
					addTag(o.mname,o.id);
					$(".cat-admin-topmenu").removeClass('cat-admin-topmenuhover');
					$(this).addClass('cat-admin-topmenuhover');
				}
			}).click();
		} else {
			$(html).appendTo("#topmenus").bind({
				click : function() {
					addTag(o.mname,o.id);
					$(".cat-admin-topmenu").removeClass('cat-admin-topmenuhover');
					$(this).addClass('cat-admin-topmenuhover');
				}
			});
		}
	})
}
function setTwoMenu(meid) {
	try {
		var pp = $('#leftmenu').accordion('panels');
		for (var i = 0 in pp) {
			objs.titles[i] = pp[i].panel('options').title;
		}
		$.each(objs.titles, function(i, obj) {
			$('#leftmenu').accordion('remove', obj);
		})
	} catch(e) {
	};
	//设置弹拉式菜单
	var menutwo = objs.menudata.two['menu' + meid];
	var menuone = '', j = 0;
	for (var i = 0 in menutwo) {
		if (menutwo[i].meid) {
			if (j == 0)
				menuone = menutwo[i].mname;
			j++;
			$('#leftmenu').accordion('add', {
				title : menutwo[i].mname,
				iconCls : menutwo[i].icon,
				content : '<div id="leftmenus' + menutwo[i].meid + '" style="padding:10px 5px;"></div>'
			});

		}
	}
	$('#leftmenu').accordion('add', {
		title : '通用管理',
		iconCls : 'icon-tip',
		content : '<div id="leftmenuspublic" style="padding:10px 5px;"></div>',
		selected : false
	});
	if (menuone != '') {
		$('#leftmenu').accordion('select', menuone);
	} else {
		$('#leftmenu').accordion('select', '通用管理');
	}
	for (var i = 0 in menutwo) {
		if (menutwo[i].meid) {
			var ij = 0;
			var menuthree = [];
			$.each(menutwo[i].children, function(ii, o) {
				menuthree[ij] = o;
				ij++;
			})
			$('#leftmenus' + menutwo[i].meid).tree({
				data : menuthree,
				onClick : function(node) {
					addTag(node.text, node.id);
				}
			});
		}
	}
}

function addTag(title, url) {
	//检测是否存在选项卡
	if ($('#windowtabs').tabs('exists', title)) {
		$('#windowtabs').tabs('select', title);
	} else {
		$('#windowtabs').tabs('add', {
			title : title,
			content : '<iframe src="' + url + '" frameborder=0 height=100% width=100% scrolling=no></iframe>',
			closable : true,
			selected : true
		});

	}
}
function reloadTab(){
	var currTab = $('#windowtabs').tabs('getSelected');
	var url = $(currTab.panel('options').content).attr('src');
	if (url != undefined) {
		$('#windowtabs').tabs('update', {
			tab : currTab,
			options : {
				content : '<iframe src="' + url + '" frameborder=0 height=100% width=100% scrolling=no></iframe>'
			}
		});
	}
}
function setpass(){
	objs.setpassform.attr('action',objs.setpassurl);
	objs.setpasswindow.window('open');
	objs.setpassform.form('load',objs.setpassformdefault);
}
function reloadsetpassgrid(){
	alert('密码已经被修改，请重新登陆');
	location.href = objs.loginouturl;
}

function openmessage(url){
	if(!url || url == ''){
		url = objs.messageallurl;
	}
	$("#messageframe").attr('src',url);
	objs.messagewindow.window('open');
	reloadmessage();
}
function reloadmessage(){
	$.get(objs.messagelisturl,function (data){
		$('#messagenum').tooltip({
			onUpdate: function(content){
		        content.panel({
		            width: 200,
		            height:80,
		            border: false,
		            content: data
		        });
		    }
	    });
	})
}
function setmessage(data){
	if(data.data != CONFIG.MESSAGE_NUM){
		CONFIG.MESSAGE_NUM = data.data;
		if(data.data > 0){
			$('#messagenum').html(data.data);
			$('#messagenum').addClass('cat-admin-topmessagehave');
		}else{
			$('#messagenum').html('');
			$('#messagenum').removeClass('cat-admin-topmessagehave');
		}
		reloadmessage();
	}else{

	}
}
function loadmessage(){
	crossDomainAjax(objs.getmessageurl,{},'setmessage');
}
//获取通信消息的回调函数演示
function testmsg(data){

}
//统一获取服务器需要返回的消息
function callmsg(json){
	if(json.status != '1'){
		return false;
	}
	$.each(json.data,function (i,oo){
		try{
			var str = oo.callback+'(oo.data)';
			eval(str);
		}catch(e){
		}
	});
}
function loadcallmsg(){
	crossDomainAjax(objs.getcallmessageurl,{},'callmsg');
}
function getgoodsdata(){
	loading(false, '正在加载商品数据...');
	$.getJSON(objs.getgoodsurl,{},function (json){
		loading(true);
		if(json.status && json.status != 1){
			showMsg(json.data,'系统提示','topcenter');
			return false;
		}else{
			//生成一级菜单
			CONFIG.GOODSDATA = json;
			$.each(json.type,function (i,o){
				$("#typeone").tabs('add',{
					title: o.calltypename,
					selected: false
				})
			})
			$("#typeone").tabs('select',0);
			//设置门店下拉菜单
			$('#addressstore').combobox({
			    data:CONFIG.GOODSDATA.storelist,
			    required:true,
			    valueField:'id',
			    textField:'text'
			});
		}
	})
}
function setgooodslists(tid){
	CONFIG.CLICKTMP.type2id = tid;
	creategoodslisthtml();
}
function creategoodslisthtml(){
	var html = '';
	$.each(CONFIG.GOODSDATA.goods[CONFIG.CLICKTMP.type2id],function (i,o){
		if(CONFIG.WEBORDERTMP.SOLDOUT && CONFIG.WEBORDERTMP.SOLDOUT[o.goodsid]){
			html += '<div class="goodslist">'+
				'<div class="goodsname" style="background: '+
				'#CCCCCC'+
				';">'+
				o.goodsname+
				'<b style="color:red">[罄]</b></div>'+
				'<div class="goodprice">￥'+
				o.price+
				'</div>'+
				'</div>';
		}else{
			html += '<div class="goodslist" onclick="clickgoods('+o.goodsid+');">'+
				'<div class="goodsname" style="background: '+
				o.color+
				';">'+
				o.goodsname+
				'</div>'+
				'<div class="goodprice">￥'+
				o.price+
				'</div>'+
				'</div>';
		}
	})
	$("#goodslits").html('');
	$("#goodslits").html(html);
}
function clickgoods(goodsid){
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS) == 'undefined'){
		showMsg('没有设置送餐信息','系统提示','topcenter');
		return false;
	}
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid) == 'undefined'){
		showMsg('没有设置送餐信息','系统提示','topcenter');
		return false;
	}
	var tmpsuit =  JSON.stringify(CONFIG.GOODSDATA.package[goodsid]);
	objs.havesomegrid.datagrid('appendRow',{
		goodsid: goodsid,
		goodsname: CONFIG.GOODSDATA.allgoods[goodsid].goodsname,
		oneprice: parseFloat(CONFIG.GOODSDATA.allgoods[goodsid].price),
		oldprice: parseFloat(CONFIG.GOODSDATA.allgoods[goodsid].price),
		numbers: 1,
		discount: CONFIG.CLICKTMP.ALLDISCOUNT,
		price: parseFloat(CONFIG.GOODSDATA.allgoods[goodsid].price),
		suitflag: CONFIG.GOODSDATA.allgoods[goodsid].suitflag,
		suitflagdata: $.parseJSON(tmpsuit),
		remarks: ''
	});
	onClickCell(0,'goodsname');
}
function clearGoods(){
	$.messager.confirm('清空已点菜单', '真的要清空已点菜单吗？', function(r){
		if (r){
			objs.havesomegrid.datagrid('loadData',[]);
			setreloadFooter();
		}else{
			showMsg('您取消了清空已点菜单的操作！','系统提示','topcenter');
		}
	});
}
function editGoods(){
	var row = objs.havesomegrid.datagrid('getSelected');
	if(row){
		if(row.suitflag != 1){
			showMsg('您选择的商品不是套餐！','系统提示','topcenter');
			return false;
		}
		$("#package").html("");
		var index = objs.havesomegrid.datagrid('getRowIndex',row);
		objs.suitflaggoodswindow.window('open');
		CONFIG.CLICKTMP.suitflagrowindex = index;
		CONFIG.CLICKTMP.suitflagdata = row.suitflagdata;
		CONFIG.CLICKTMP.openindex = 0;
		setSuitGoods();
	}else{
		showMsg('请选择要编辑的商品！');
	}
}
function setSuitGoods(){
	var html = '';
	$.each(CONFIG.CLICKTMP.suitflagdata,function (i,ob){
		html += '<div class="goodslist" onclick="lookReplyGoods('+
		i+
		');"><div class="goodsname" style="background: #00D5FF;">'+
		CONFIG.GOODSDATA.allgoods[ob.goodsid].goodsname+
		' X '+
		ob.goodsno+
		'</div><div class="goodprice">'+
		ob.addprice+
		'</div></div>';
	})
	$("#package").html(html);
	lookReplyGoods(CONFIG.CLICKTMP.openindex);
}
//生成可换小项，参数为点击的选项卡的索引
function lookReplyGoods(openindex){
	CONFIG.CLICKTMP.openindex = openindex;
	var nowdata = CONFIG.CLICKTMP.suitflagdata[openindex];
	//
	var replydata = CONFIG.GOODSDATA.packagereply[nowdata.packageid];
	var html = '';
	$.each(replydata,function (i,ob){
		if(nowdata.goodsid != ob.goodsid){
			html += '<div class="goodslist" onclick="setReplyGoods('+
			nowdata.packageid+','+i+
			');"><div class="goodsname" style="background: #00D5FF;">'+
			CONFIG.GOODSDATA.allgoods[ob.goodsid].goodsname+
			' X '+
			ob.goodsno+
			'</div><div class="goodprice"> + '+
			ob.addprice+
			'</div></div>';
		}
	})
	$("#packagereply").html(html);
}
function setReplyGoods(packageid,replyindex){
	CONFIG.CLICKTMP.suitflagdata[CONFIG.CLICKTMP.openindex] = CONFIG.GOODSDATA.packagereply[packageid][replyindex];
	setSuitGoods();
	var addprice = 0.00;
	$.each(CONFIG.CLICKTMP.suitflagdata,function (i,ok){
		addprice += parseFloat(ok.addprice);
	})
	//更新商品列表
	var data = objs.havesomegrid.datagrid('getData');
	$.each(data.rows ,function (i,ob){
		if(typeof(ob) == 'object'){
			if(i == CONFIG.CLICKTMP.suitflagrowindex){
				data.rows[i].oneprice =  ob.oldprice + addprice;
				data.rows[i].suitflagdata = CONFIG.CLICKTMP.suitflagdata;
			}
		}
	})
	objs.havesomegrid.datagrid('loadData',data);
	setreloadFooter();
}
//必需是整数
function isInt(str){
	var reg = /^[0-9]*[1-9][0-9]*$/;
	return reg.test(str);
}
function allDiscount(){
	$.messager.prompt('全单打折', '请设置折扣。例如：九折填写90！', function(r){
		if (r){
			if(isInt(r) && r > 0 && r < 101){
				CONFIG.CLICKTMP.ALLDISCOUNT = r;
			}else{
				CONFIG.CLICKTMP.ALLDISCOUNT = 100;
				showMsg('输入的值错误，自动恢复为不打折!','系统提示','topcenter');
			}
		}else{
			CONFIG.CLICKTMP.ALLDISCOUNT = 100;
			showMsg('没有设置折扣，自动恢复为不打折!','系统提示','topcenter');
		}
		var data = objs.havesomegrid.datagrid('getData');
		for(var i=0 ;i<data.rows.length; i++){
			data.rows[i].discount = CONFIG.CLICKTMP.ALLDISCOUNT;
		}
		objs.havesomegrid.datagrid('loadData',data);
		setreloadFooter();
	});

}
function removeData(){
	var row = objs.havesomegrid.datagrid('getSelected');
	if(row){
		var index = objs.havesomegrid.datagrid('getRowIndex',row);
		objs.havesomegrid.datagrid('deleteRow',index);
		setreloadFooter();
	}else{
		showMsg('请选择要删除的商品！','系统提示','topcenter');
	}
}
function confirmationOrder(){
	var data = objs.havesomegrid.datagrid('getData');
}
//设置价格样式
function setFormatPrice(val){
	return parseFloat(val).toFixed(2);
}
//设置小计价格
function setFormatAllPrice(val,row){
	var price = row.oneprice*(row.discount/100);
	if(CONFIG.ROUNDING == 'roundingup'){
		//向上取整
		price = Math.ceil(price);
	}else if(CONFIG.ROUNDING == 'roundingdown'){
		//向下取整
		price = Math.floor(price);
	}else if(CONFIG.ROUNDING == 'fourfive'){
		//四舍五入取整
		price = Math.round(price);
	}
	return (price*row.numbers).toFixed(2);
}
//根据电话查询送餐地址
function openaddresswindow(){
	CONFIG.CLICKTMP.TELPHONENUMBER = $("#telphonenumber").val();
	if(CONFIG.CLICKTMP.TELPHONENUMBER == ""){
		showMsg("电话号码不能为空",'系统提示','topcenter');
		return ;
	}
	objs.addresswindow.window('open');
	objs.addressform.attr('action',objs.saveaddressurl);
	objs.addressform.form('load',objs.addressformdefault);
	var partten = /^1[3,4,5,8]\d{9}$/;
	if (partten.test(CONFIG.CLICKTMP.TELPHONENUMBER)) {
		$("#addressphone").val(CONFIG.CLICKTMP.TELPHONENUMBER);
		getAddresslistToPhone();
	} else {
		$("#addresstelphone").val(CONFIG.CLICKTMP.TELPHONENUMBER);
		getAddresslistToTelphone();
	}
	//设置默认城市
	var d = $('#cityid').combobox('getData');
	$('#cityid').combobox('setValue',d[0].id);
	$('#addressstore').combobox({
	    data:CONFIG.GOODSDATA.storelist,
	    required:true,
	    valueField:'id',
	    textField:'text'
	});
}
//根据电话查询送餐地址
function openeditaddresswindow(){
	 $.messager.confirm('修改配送信息', '修改配送信息可能会导致已点菜单里部分菜品出现售罄，您确定要修改吗？', function(r){
        if (r){
			CONFIG.CLICKTMP.TELPHONENUMBER = $("#telphonenumber").val();
			if(CONFIG.CLICKTMP.TELPHONENUMBER == ""){
				showMsg("电话号码不能为空",'系统提示','topcenter');
				return ;
			}
			objs.addresswindow.window('open');
			objs.addressform.attr('action',objs.saveaddressurl);
			objs.addressform.form('load',objs.addressformdefault);
			var partten = /^1[3,4,5,8]\d{9}$/;
			if (partten.test(CONFIG.CLICKTMP.TELPHONENUMBER)) {
				$("#addressphone").val(CONFIG.CLICKTMP.TELPHONENUMBER);
				getAddresslistToPhone(true);
			} else {
				$("#addresstelphone").val(CONFIG.CLICKTMP.TELPHONENUMBER);
				getAddresslistToTelphone(true);
			}
			//设置默认城市
			var d = $('#cityid').combobox('getData');
			$('#cityid').combobox('setValue',d[0].id);
        }
    });
}
//添加地址
function clearaddressform(){
	objs.addressform.attr('action',objs.saveaddressurl);
	objs.addressform.form('load',objs.addressformdefault);
	var partten = /^1[3,4,5,8]\d{9}$/;
	if (partten.test(CONFIG.CLICKTMP.TELPHONENUMBER)) {
		$("#addressphone").val(CONFIG.CLICKTMP.TELPHONENUMBER);
	} else {
		$("#addresstelphone").val(CONFIG.CLICKTMP.TELPHONENUMBER);
	}
	//设置默认城市
	var d = $('#cityid').combobox('getData');
	$('#cityid').combobox('setValue',d[0].id);
}
//编辑地址
function editaddress(){
	var row = objs.addresslistdatagird.datagrid('getSelected');
	if(row){
		objs.addressform.attr('action',objs.editaddressurl);
		objs.addressform.form('load',objs.addressformdefault);
		objs.addressform.form('load',row);
	}else{
		showMsg('请下面的表格中选择要编辑的地址','系统提示','topcenter');
	}
}
//刷新地址列表
function reloadaddressgrid(){
	clearaddressform();
	var partten = /^1[3,4,5,8]\d{9}$/;
	if (partten.test(CONFIG.CLICKTMP.TELPHONENUMBER)) {
		getAddresslistToPhone();
	} else {
		getAddresslistToTelphone();
	}
}
//根据手机号获取地址
function getAddresslistToPhone(edit){
	loading();
	$.getJSON(objs.getaddresslisturl,{phone:CONFIG.CLICKTMP.TELPHONENUMBER},function (json){
		loading(true);
		objs.addresslistdatagird.datagrid('loadData',json);
		if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS) != 'undefined'){
			if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS.memaid) != 'undefined'){
				objs.addresslistdatagird.datagrid('selectRecord',CONFIG.WEBORDERTMP.SONGCANADDRESS.memaid);
			}
		}
		if(edit){
			editaddress();
		}
	})
}
//根据电话获取地址
function getAddresslistToTelphone(edit){
	loading();
	$.getJSON(objs.getaddresslisturl,{telphone:CONFIG.CLICKTMP.TELPHONENUMBER},function (json){
		loading(true);
		objs.addresslistdatagird.datagrid('loadData',json);
		if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS) != 'undefined'){
			if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS.memaid) != 'undefined'){
				objs.addresslistdatagird.datagrid('selectRecord',CONFIG.WEBORDERTMP.SONGCANADDRESS.memaid);
			}
		}
		if(edit){
			editaddress();
		}
	})
}
//设置门店城市
function setStoreAndCity(str){
	var data = str.split(',');
	var sotrid = data[0];
	var baidu = data[1]=='true'?1:0;
	var cityid = data[2];
	var baidutmp = data[3];
	var stores = data[3].split('|');
	var s = [ ];//缓存可用门店
	for(var i in stores){
		s[stores[i]] = true;
	}
	var ss = []; //缓存门店数据
	$.each(CONFIG.GOODSDATA.storelist,function (ii,oo){
		if(s[oo.id] === true){
			ss.push(oo);
		}
	})
	//设置可选门店
	$('#addressstore').combobox({
	    data:ss,
	    required:true,
	    valueField:'id',
	    textField:'text'
	});
	$("#cityid").combobox('setValue',cityid);
	$("#addressstore").combobox('setValue',sotrid);
	$("#addressbaidu").val(baidu);
	$("#addressbaidutmp").val(baidutmp);
	$("#addressdetailed").focus();
}
//选择地址
function selectAddress(){
	var row = objs.addresslistdatagird.datagrid('getSelected');
	if(row){
		CONFIG.WEBORDERTMP.SONGCANADDRESS = row;
		openSelectTimer();
	}else{
		showMsg('请在地址列表选择地址，如果没有地址请添加！','系统提示','topcenter');
	}
}
function openSelectTimer(){
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS) == 'undefined'){
		showMsg('没有选择送餐地址','系统提示','topcenter');
		return false;
	}
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid) == 'undefined'){
		showMsg('没有选择送餐地址','系统提示','topcenter');
		return false;
	}
	$("#selecttime").dialog('open');
	//根据门店ID获取销售时间段
	CONFIG.CLICKTMP.STORETIME = CONFIG.GOODSDATA.storetimer[CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid];
	$("#songcanstore").val(CONFIG.GOODSDATA.storeindex[CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid].name);
	$("#timermssage").html("请选择预约时间");
	$("#yydate").val(PHP.date('Y-m-d'));
	CONFIG.CLICKTMP.TODAYSTARTIME = PHP.date('H:i',PHP.time()+CONFIG.DELIVERY_YYSTIME);
	//如果获取的时间小于开始时间，就自动设置为开始时间
	if(PHP.strtotime(PHP.date('Y-m-d')+' '+CONFIG.CLICKTMP.TODAYSTARTIME ) < PHP.strtotime(PHP.date('Y-m-d')+' '+CONFIG.CLICKTMP.STORETIME.webstime)){
		CONFIG.CLICKTMP.TODAYSTARTIME = CONFIG.CLICKTMP.STORETIME.webstime;
	}

	//特殊情况判断
	$("#yytime").timespinner({
		min:CONFIG.CLICKTMP.TODAYSTARTIME,
		max:CONFIG.CLICKTMP.STORETIME.webttime,
		showSeconds:false
	});

	$("#yytime").timespinner('setValue',CONFIG.CLICKTMP.TODAYSTARTIME);

	//如果获取的时间大于今天的结束时间，则自动加一天
	if(PHP.strtotime(PHP.date('Y-m-d') +' '+CONFIG.CLICKTMP.TODAYSTARTIME) > PHP.strtotime(PHP.date('Y-m-d')+' '+CONFIG.CLICKTMP.STORETIME.webttime)){
		$("#yydate").val(PHP.date('Y-m-d',PHP.strtotime('+1 day')));
		setTimerSection();
		return ;
	}
	if(typeof(CONFIG.WEBORDERTMP.SONGCANDATE) != 'undefined'){
		$("#yydate").val(CONFIG.WEBORDERTMP.SONGCANDATE);
		setTimerSection();
		if(typeof(CONFIG.WEBORDERTMP.SONGCANTIMER) != 'undefined'){
			$("#yytime").timespinner('setValue',CONFIG.WEBORDERTMP.SONGCANTIMER);
		}
	}
	$("#timemsg").html('可选时间：'+CONFIG.CLICKTMP.TODAYSTARTIME+'-'+CONFIG.CLICKTMP.STORETIME.webttime);
}
function setTimerSection(){
	var nowdate = $("#yydate").val();
	if(PHP.date('Y-m-d') == nowdate){
		CONFIG.CLICKTMP.TODAYSTARTIME = PHP.date('H:i',PHP.time()+CONFIG.DELIVERY_YYSTIME);
		//如果获取的时间小于开始时间，就自动设置为开始时间
		if(PHP.strtotime(PHP.date('Y-m-d')+' '+CONFIG.CLICKTMP.TODAYSTARTIME ) < PHP.strtotime(PHP.date('Y-m-d')+' '+CONFIG.CLICKTMP.STORETIME.webstime)){
			CONFIG.CLICKTMP.TODAYSTARTIME = CONFIG.CLICKTMP.STORETIME.webstime;
		}
		$("#yytime").timespinner({
			min:CONFIG.CLICKTMP.TODAYSTARTIME,
			max:CONFIG.CLICKTMP.STORETIME.webttime,
			showSeconds:false
		});
		$("#yytime").timespinner('setValue',CONFIG.CLICKTMP.TODAYSTARTIME);
		$("#timemsg").html('可选时间：'+CONFIG.CLICKTMP.TODAYSTARTIME+'-'+CONFIG.CLICKTMP.STORETIME.webttime);
		//判断选择的时间是否在配送时间范围内
		if(PHP.strtotime(nowdate+' '+CONFIG.CLICKTMP.TODAYSTARTIME) > CONFIG.CLICKTMP.STORETIME.webttime){
			$("#yydate").val(PHP.date('Y-m-d',PHP.strtotime('+1 day')));
			setTimerSection();
		}
	}else{
		$("#yytime").timespinner({
			min:CONFIG.CLICKTMP.STORETIME.webstime,
			max:CONFIG.CLICKTMP.STORETIME.webttime,
			showSeconds:false
		});
		$("#yytime").timespinner('setValue',CONFIG.CLICKTMP.STORETIME.webstime);
		$("#timemsg").html('可选时间：'+CONFIG.CLICKTMP.STORETIME.webstime+'-'+CONFIG.CLICKTMP.STORETIME.webttime);
	}
	//判断今天是否还允许点餐
}
function judgmentMenu(){

}
function ressoldout(json){
	var nowstr = PHP.md5(PHP.json_encode(json));
	if(CONFIG.WEBORDERTMP.SOLDOUTSTR != nowstr){
		CONFIG.WEBORDERTMP.SOLDOUTSTR = nowstr;
		CONFIG.WEBORDERTMP.SOLDOUT = json;
		creategoodslisthtml();
	}
	setTimeout(loadsoldout,5000);
}
function loadsoldout(){
	if(CONFIG.WEBORDERTMP.LOADSOLDOUT){
		var row = CONFIG.WEBORDERTMP.SONGCANADDRESS;
		var timer = CONFIG.WEBORDERTMP.SONGCANDATE + ' ' + CONFIG.WEBORDERTMP.SONGCANTIMER;
		crossDomainAjax(objs.storeclearApiurl ,{stid:row.storeid,time:timer},'ressoldout')
	}
}
function confirmationTimer(){
	var row = CONFIG.WEBORDERTMP.SONGCANADDRESS;
	var timer = $("#yydate").val()+' '+ $("#yytime").timespinner('getValue');
	loading(false,'正在验证设置，请稍候...');
	$.getJSON(objs.storestopurl ,{stid:row.storeid,time:timer},function (json){
		if(json.status == 1){
			//计算选取的门店是否有估清商品
			$.getJSON(objs.storeclearurl ,{stid:row.storeid,time:timer},function (json){
				loading(true);
				setTimeout(loadsoldout,5000);
				CONFIG.WEBORDERTMP.LOADSOLDOUT = true;
				CONFIG.WEBORDERTMP.SOLDOUTSTR = PHP.md5(PHP.json_encode(json));
				CONFIG.WEBORDERTMP.SOLDOUT = json;
				$('#selecttime').dialog('close');
				objs.addresswindow.window('close');
				CONFIG.WEBORDERTMP.SONGCANDATE = $("#yydate").val();
				CONFIG.WEBORDERTMP.SONGCANTIMER = $("#yytime").timespinner('getValue');
				//设置配送信息
				$("#peisongmessage").html('<tr><td style="width:60px;">预约时间： </td><td>'+
						CONFIG.WEBORDERTMP.SONGCANDATE + ' ' + CONFIG.WEBORDERTMP.SONGCANTIMER +
						' <a href="javascript:openSelectTimer();"> 修改</a></td></tr><tr><td>配送门店： </td><td>'+
						CONFIG.GOODSDATA.storeindex[CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid].name +
						' <a href="javascript:openeditaddresswindow();"> 修改</a></td></tr>	<tr><td>订单客户： </td><td>'+
						CONFIG.WEBORDERTMP.SONGCANADDRESS.name +
						'</td></tr><tr><td valign="top">联系电话： </td><td>'+
						CONFIG.WEBORDERTMP.SONGCANADDRESS.phone +
						'<br/>'+
						CONFIG.WEBORDERTMP.SONGCANADDRESS.telphone +
						'</td></tr><tr><td valign="top">送餐地址：</td><td>'+
						CONFIG.WEBORDERTMP.SONGCANADDRESS.address +
						'</td></tr><tr><td valign="top">需要发票：</td><td>'+
						(CONFIG.WEBORDERTMP.SONGCANADDRESS.receiptsatus == 1?'需要':'不需要') +
						'</td></tr><tr><td valign="top">发票抬头：</td><td>'+
						CONFIG.WEBORDERTMP.SONGCANADDRESS.receipttitle +
						'</td></tr>');
				setDetailAddress();
				creategoodslisthtml();
				showMsg('设置符合要求，请点餐！','系统提示','topcenter');
			})
		}else{
			loading(true);
			$("#timermssage").html(json.data);
		}
	})
}
$.extend($.fn.datagrid.defaults.editors, {
    numberspinner: {
        init: function(container, options){
            var input = $('<input type="text">').appendTo(container);
            return input.numberspinner(options);
        },
        destroy: function(target){
            $(target).numberspinner('destroy');
        },
        getValue: function(target){
            return $(target).numberspinner('getValue');
        },
        setValue: function(target, value){
            $(target).numberspinner('setValue',value);
        },
        resize: function(target, width){
            $(target).numberspinner('resize',width);
        }
    }
});
$.extend($.fn.datagrid.methods, {
    editCell: function(jq,param){
        return jq.each(function(){
            var opts = $(this).datagrid('options');
            var fields = $(this).datagrid('getColumnFields',true).concat($(this).datagrid('getColumnFields'));
            for(var i=0; i<fields.length; i++){
                var col = $(this).datagrid('getColumnOption', fields[i]);
                col.editor1 = col.editor;
                if (fields[i] != param.field){
                    col.editor = null;
                }
            }
            $(this).datagrid('beginEdit', param.index);
            for(var i=0; i<fields.length; i++){
                var col = $(this).datagrid('getColumnOption', fields[i]);
                col.editor = col.editor1;
            }
        });
    }
});
var editIndex = undefined;
function endEditing(){
    if (editIndex == undefined){return true}
    if (objs.havesomegrid.datagrid('validateRow', editIndex)){
        objs.havesomegrid.datagrid('endEdit', editIndex);
        editIndex = undefined;
        return true;
    } else {
        return false;
    }
}
function onClickCell(index, field){
    if (endEditing()){
        objs.havesomegrid.datagrid('selectRow', index)
                .datagrid('editCell', {index:index,field:field});
        editIndex = index;
    }
    setreloadFooter();
}
//计算合计
function setreloadFooter(){
	var data = objs.havesomegrid.datagrid('getData');
	if(data.rows.lenght == 0){
		$("#nowpricenotice").html('暂无商品数据');
		return ;
	}
	var price = 0.00;
	var allprice = 0.00;
	var songprice = 0.00;
	var huiprice = 0.00;
	$.each(data.rows ,function (i,ob){
		if(typeof(ob) == 'object'){
			price += (parseFloat(ob.oneprice)*parseFloat(ob.numbers));
			allprice += parseFloat(setFormatAllPrice(i,ob));
		}
	})
	huiprice = price-allprice;
	if(CONFIG.DELIVERY_DELIVERY){
		if(price<CONFIG.DELIVERY_PRICE){
			songprice = CONFIG.DELIVERY_GOODSPRICE;
		}
	}else{
		if(allprice<CONFIG.DELIVERY_PRICE){
			songprice = CONFIG.DELIVERY_GOODSPRICE;
		}
	}
	//是否强制免外送
	if(CONFIG.WEBORDERTMP.MIANWAISONGFEI){
		songprice = 0.00;
	}
	if(CONFIG.WEBORDERTMP.ZIQU){
		songprice = 0.00;
	}
	allprice += songprice;
	var html = '商品总价：<b>'+
				price.toFixed(2)+
				'</b> 优惠价格：<span style="color:green">'+
				huiprice.toFixed(2)+
				'</span> 外送费：<span style="color:red">'+
				songprice.toFixed(2)+
				'</span> <br />应收价格：<span style="color:blue"><b>'+
				allprice.toFixed(2)+
				'</b></span>';
	$("#nowpricenotice").html(html);
	$("#alldicount").html(CONFIG.CLICKTMP.ALLDISCOUNT);
	//设置商品详细页
	var html2 = '商品总价：<b>'+
				price.toFixed(2) + '</b> '+
				' 优惠价格：<b style="color:green">'+
				huiprice.toFixed(2)+
				'</b> '+
				' 外送费：<b style="color:red">'+
				songprice.toFixed(2)+
				'</b>'+
				' 应收价格：<span style="color:blue"><b>'+
				allprice.toFixed(2)+
				'</b></span>'+
				'<span> 全单折扣：<b style="color:#0066FF;">'+
				CONFIG.CLICKTMP.ALLDISCOUNT+
				'</b><b style="color:#0066FF;">%</b></span>';
	$("#detailpricenotice").html(html2);
	//记录价格
	CONFIG.WEBORDERTMP.PRICEDATA = {};
	CONFIG.WEBORDERTMP.PRICEDATA.price = price.toFixed(2);
	CONFIG.WEBORDERTMP.PRICEDATA.huiprice = huiprice.toFixed(2);
	CONFIG.WEBORDERTMP.PRICEDATA.songprice = songprice.toFixed(2);
	CONFIG.WEBORDERTMP.PRICEDATA.allprice = allprice.toFixed(2);
	CONFIG.WEBORDERTMP.PRICEDATA.alldiscount = CONFIG.CLICKTMP.ALLDISCOUNT;
	//设置确认商品页面显示商品信息
	setDetailGoods();
}
function hideminpanle(){
	if(CONFIG.CLICKTMP.fullsreen){
		$("body").layout('expand','north');
		CONFIG.CLICKTMP.fullsreen = false;
	}else{
		$("body").layout('collapse','north');
		CONFIG.CLICKTMP.fullsreen = true;
	}
}
function setDetailAddress(){
	var h1 = '<input type="radio" name="receiptsatusdetail" checked=true value="1" />需要'+
		'<input type="radio" name="receiptsatusdetail" value="0" />不需要';

	var h2 = '<input type="radio" name="receiptsatusdetail" value="1" />需要'+
		'<input type="radio" name="receiptsatusdetail" checked=true  value="0" />不需要';

	$("#orderdetailed").html('<tr><td style="width:60px;">预约时间： </td><td style="width:250px;">'+
		CONFIG.WEBORDERTMP.SONGCANDATE + ' ' + CONFIG.WEBORDERTMP.SONGCANTIMER +
		' <a href="javascript:openSelectTimer();"> 修改 </a></td><td style="width:60px;">配送门店： </td><td>'+
		CONFIG.GOODSDATA.storeindex[CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid].name +
		' <a href="javascript:openeditaddresswindow();"> 修改配送信息</a></td></tr>	<tr><td>订单客户： </td><td>'+
		CONFIG.WEBORDERTMP.SONGCANADDRESS.name +
		'</td><td valign="top">联系电话： </td><td>'+
		CONFIG.WEBORDERTMP.SONGCANADDRESS.phone +
		'/'+
		CONFIG.WEBORDERTMP.SONGCANADDRESS.telphone +
		'</td></tr><tr><td valign="top">需要发票：</td><td>'+
		(CONFIG.WEBORDERTMP.SONGCANADDRESS.receiptsatus == 1? h1:h2) +
		'</td><td valign="top">发票抬头：</td><td><input type="text" class="cat-search-input" id="fapiaotaitou" value="'+
		CONFIG.WEBORDERTMP.SONGCANADDRESS.receipttitle +
		'" /></td></tr><tr><td valign="top">送餐地址：</td><td colspan="3">'+
		CONFIG.WEBORDERTMP.SONGCANADDRESS.address +
		'</td></tr>');
}
function setMianwaisong(now){
	CONFIG.WEBORDERTMP.MIANWAISONGFEI = now;
	if($('#detailmianwaisongfei').attr('checked') != now){
		$('#detailmianwaisongfei').attr('checked',now);
	}
	if($('#mianwaisongfei').attr('checked') != now){
		$('#mianwaisongfei').attr('checked',now);
	}
	setreloadFooter();
}
function setZiqu(now){
	CONFIG.WEBORDERTMP.ZIQU = now;
	if($('#detailziqu').attr('checked') != now){
		$('#detailziqu').attr('checked',now);
	}
	if($('#ziqu').attr('checked') != now){
		$('#ziqu').attr('checked',now);
	}
	setreloadFooter();
}
//设置订单详情里的显示数据
function setDetailGoods(){
	var data = objs.havesomegrid.datagrid('getData');
	var objdata = [];
	var j = 0;
	$.each(data.rows,function (i,on){
		objdata[j] = on;
		objdata[j].fcolor = 1;
		j++;
		if(on.suitflag == '1'){
			objdata[j-1].fcolor = 2;
			$.each(on.suitflagdata,function (ii,onn){
				objdata[j] = {
					goodsname: ' ┕━━  '+CONFIG.GOODSDATA.allgoods[onn.goodsid].goodsname,
					oneprice: onn.addprice / onn.goodsno,
					oldprice: onn.addprice / onn.goodsno,
					numbers: parseFloat(onn.goodsno) * parseFloat(on.numbers),
					discount: 100,
					price: onn.addprice,
					suitflag: 2,
					suitflagdata: [],
					remarks: '',
				};
				j++;
			})
		}
	})
	objs.detailgoodsgrid.datagrid('loadData',objdata);
}
function getBaiduCode(){
	return $("#cityid").combobox('getValue');
}
function lookOrderDetailed(){
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS) == 'undefined'){
		showMsg('没有选择送餐地址','系统提示','topcenter');
		return false;
	}
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid) == 'undefined'){
		showMsg('没有选择送餐地址','系统提示','topcenter');
		return false;
	}
	var data = objs.havesomegrid.datagrid('getData');
	if(data.total<1){
		showMsg('还没有点菜？','系统提示','topcenter');
		return false;
	}
	onClickCell(0,'goodsname');
	objs.orderdetailedwindow.window('open');
}
function saveOrder(){
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS) == 'undefined'){
		showMsg('没有选择送餐地址','系统提示','topcenter');
		return false;
	}
	if(typeof(CONFIG.WEBORDERTMP.SONGCANADDRESS.storeid) == 'undefined'){
		showMsg('没有选择送餐地址','系统提示','topcenter');
		return false;
	}
	var data = objs.havesomegrid.datagrid('getData');
	if(data.total<1){
		showMsg('还没有点菜？','系统提示','topcenter');
		return false;
	}
	//组织数据
	var alldata = { };
	alldata.goods = data; //商品数据
	alldata.orderid = CONFIG.WEBORDERTMP.ORDERID?CONFIG.WEBORDERTMP.ORDERID:''; //商品数据
	alldata.address = CONFIG.WEBORDERTMP.SONGCANADDRESS;//地址信息
	alldata.yytime = CONFIG.WEBORDERTMP.SONGCANDATE + ' ' + CONFIG.WEBORDERTMP.SONGCANTIMER;//送餐时间
	alldata.mianwaisong = $('#mianwaisongfei').attr('checked')?'0':'1';//是否免外送
	alldata.fapiao = $('input[name=receiptsatusdetail]:checked').val();//是否有发票
	alldata.fapiaotaitou = $('#fapiaotaitou').val();//是否有发票抬头，如果不要发票，则自动忽略
	alldata.price = CONFIG.WEBORDERTMP.PRICEDATA;//价格信息
	alldata.remarks = $("#orderremarks").val();//整单备注信息
	alldata.ziqu = CONFIG.WEBORDERTMP.ZIQU?2:1;//自取或者外送
	var strdata = JSON.stringify(alldata);
	loading();
	$.post(objs.saveorderurl,{data:strdata},function (data){
		loading(true);
		try{
			var json = jQuery.parseJSON(data);
			showMsg(json.data,'系统提示','topcenter');
			if(json.status==1){
				//清空数据
				clearClickGoods();
				objs.orderdetailedwindow.window('close');
			}
		}catch(e){
			showMsg('返回数据出现错误，请联系管理员','系统提示','topcenter');
		}
	})

}
function clearClickGoods(){
	CONFIG.CLICKTMP.ALLDISCOUNT = 100;
	CONFIG.CLICKTMP.TELPHONENUMBER = '';
	CONFIG.WEBORDERTMP = { };//清空所有的缓存数据
	CONFIG.WEBORDERTMP.MIANWAISONGFEI = false;
	CONFIG.WEBORDERTMP.ZIQU = false;
	CONFIG.WEBORDERTMP.PRICEDATA = {};
	objs.havesomegrid.datagrid('loadData',[]);
	setreloadFooter();
	$('#detailziqu').attr('checked',false);//设置自取不选中
	$('#ziqu').attr('checked',false);//设置自取不选中
	$('#mianwaisongfei').attr('checked',false);//设置外送费不选中
	$('#detailmianwaisongfei').attr('checked',false);//设置外送费不选中
	$('#fapiaotaitou').val('');//发票抬头为空
	$('#peisongmessage').html('');//左下角配送信息清空
	$("#orderdetailed").html('');//设置确认订单里的送餐信息取消
	$("#alldicount").html('100');//还原折扣
	$("#telphonenumber").val('');
}
function reloadorderlistgird(){
	var stime = $('#orderliststime').datebox('getValue');
	var ttime = $('#orderlistttime').datebox('getValue');
	if(stime > ttime){
		showMsg('开始时间不能大于结束时间！','系统提示','topcenter');
		return;
	}
	var odata = {
		stime:stime,
		ttime:ttime,
		phone:$('#orderphonecode').val(),
		orderno:$("#ordercode").val()
	}
	objs.orderlistgrid.datagrid({
		url:objs.getorderlisturl,
		queryParams:odata
	});
}
function opemorderlistwindow(){
	//判断是否有用户
	objs.orderlistwindow.window('open');
	//查询订单
	reloadorderlistgird();
}

//修改订单
function editOrder(){
	var row = objs.orderlistgrid.datagrid("getSelected");
	if(row){
		var st = 'CALL_EDIT'+row.status;
		if(CONFIG.EDITORSTATUS[st]){
			editorder(row.orderid);
		}else{
			showMsg('当前订单状态不允许修改！','系统提示','topcenter');
		}
	}else{
		showMsg('选择一个订单才可以修改呢！','系统提示','topcenter');
	}
}
//订单编辑函数
function editorOrder(oid,status){
	var st = 'CALL_EDIT'+status;
	if(CONFIG.EDITORSTATUS[st]){
		//设置选项卡到订餐页面
		if ($('#windowtabs').tabs('exists', '电话订餐')) {
			$('#windowtabs').tabs('select', '电话订餐');
			editorder(oid);
		}else{
			showMsg('您没有权限修改订单！','系统提示','topcenter');
		}
	}else{
		showMsg('当前订单状态不允许修改！','系统提示','topcenter');
	}
}
function setEditOrder(json){
	if(json.status){
		showMsg('查无此订单信息，请检查！','系统提示','topcenter');
		return false;
	}
	CONFIG.CLICKTMP.ALLDISCOUNT = json.order.alldiscount; //设置订单全单打折
	CONFIG.WEBORDERTMP = json.webtmp;
	CONFIG.WEBORDERTMP.ZIQU = json.order.ziqu;
	CONFIG.WEBORDERTMP.MIANWAISONGFEI = json.order.mianwaisong;
	$('#detailziqu').attr('checked',json.order.ziqu);//设置自取不选中
	$('#ziqu').attr('checked',json.order.ziqu);//设置自取不选中
	$('#mianwaisongfei').attr('checked',json.order.mianwaisong);//设置外送费不选中
	$('#detailmianwaisongfei').attr('checked',json.order.mianwaisong);//设置外送费不选中
	$('#fapiaotaitou').val(json.order.fapiaotaitou);//发票抬头为空
	$("#alldicount").html(json.order.alldiscount);//还原折扣
	objs.havesomegrid.datagrid('loadData',json.goodsdata);
	setreloadFooter();
	$("#yydate").val(json.webtmp.SONGCANDATE)
	$("#yytime").timespinner('setValue',json.webtmp.SONGCANTIMER);
	confirmationTimer();
	//loadsoldout();
	objs.orderlistwindow.window('close');
}
function editorder(orderid){
	if(!orderid){
		showMsg('没有传订单ID','系统提示','topcenter');
		return false;
	}
	var data = objs.havesomegrid.datagrid('getData');
	if(data.total > 0){
		$.messager.confirm('修改订单', '您正在点菜，确定要修改订单吗，确定将清空已点菜单和当前加载的用户配送信息。', function(r){
            if (r){
            	loading();
                $.getJSON(objs.getorderDataurl,{orderid:orderid},function (json){
	            	loading(true);
					setEditOrder(json);
				})
            }
        });
	}else{
		$.getJSON(objs.getorderDataurl,{orderid:orderid},function (json){
        	loading(true);
			setEditOrder(json);
		})
	}

}
//复制订单
function copyOrder(){
	var row = objs.orderlistgrid.datagrid("getSelected");
	if(row){
		copyorder(row.orderid);
	}else{
		showMsg('选择一个订单才可以复制呢！','系统提示','topcenter');
	}
}
function setCopyOrder(json){
	CONFIG.CLICKTMP.ALLDISCOUNT = json.order.alldiscount; //设置订单全单打折
	CONFIG.WEBORDERTMP = json.webtmp;
	CONFIG.WEBORDERTMP.ORDERID = false;
	CONFIG.WEBORDERTMP.ZIQU = json.order.ziqu;
	CONFIG.WEBORDERTMP.MIANWAISONGFEI = json.order.mianwaisong;
	$('#detailziqu').attr('checked',json.order.ziqu);//设置自取不选中
	$('#ziqu').attr('checked',json.order.ziqu);//设置自取不选中
	$('#mianwaisongfei').attr('checked',json.order.mianwaisong);//设置外送费不选中
	$('#detailmianwaisongfei').attr('checked',json.order.mianwaisong);//设置外送费不选中
	$('#fapiaotaitou').val(json.order.fapiaotaitou);//发票抬头为空
	$("#alldicount").html(json.order.alldiscount);//还原折扣
	objs.havesomegrid.datagrid('loadData',json.goodsdata);
	setreloadFooter();
	confirmationTimer();
	//loadsoldout();
	objs.orderlistwindow.window('close');
}
function copyorder(orderid){
	if(!orderid){
		showMsg('没有传订单ID','系统提示','topcenter');
		return false;
	}
	var data = objs.havesomegrid.datagrid('getData');
	if(data.total > 0){
		$.messager.confirm('修改订单', '您正在点菜，确定要修改订单吗，确定将清空已点菜单和当前加载的用户配送信息。', function(r){
            if (r){
            	loading(false,'正在打开订单，请稍候。。。');
                $.getJSON(objs.getorderDataurl,{orderid:orderid},function (json){
	            	loading(true);
					setCopyOrder(json);
				})
            }
        });
	}else{
		loading(false,'正在打开订单，请稍候。。。');
		$.getJSON(objs.getorderDataurl,{orderid:orderid},function (json){
        	loading(true);
			setCopyOrder(json);
		})
	}

}
//查看详细
function lookOrderDetail(){
	var row = objs.orderlistgrid.datagrid("getSelected");
	if(row){
		try{
			$.each(row,function(i,obj){
				$("#lookorders_goodlist").html('');
				$("#"+i+'detailetext').html(obj);
				if(i=='orderitems'){
					var htm = '<tr><td width="40">序号</td><td width="150">名称</td><td width="60">数量</td><td width="60">单价</td><td width="60">小计</td><td>备注</td></tr>' ;
					var suit = '';
					$.each(obj,function(ii,ob){
						htm += "<tr style='border-top:1px solid #CCCCCC'>";
						htm +="<td>"+(ii+1)+"</td>"; //序号
						htm +="<td>"+ob.goodsname+"</td>"; //名称
						htm +="<td>"+ob.goodsno+"</td>"; //数量
						htm +="<td>"+ob.goodsprice+"</td>"; //单价
						htm +="<td>"+ob.aprice+"</td>"; //小计
						htm +="<td>"+(ob.remarks==null?'':ob.remarks)+"</td>"; //备注
						htm += "</tr>";
						suit = '';
						if(ob.suitflag==1){ //套餐
							htm += "<tr>";
							htm +="<td></td><td  colspan='5'> &nbsp;┕━━ ";
							$.each(ob.pitems,function(iii,o){
								suit +="&nbsp;|&nbsp;"+o.goodsname + ' x '+ (o.goodsno * ob.goodsno) + ' '; //名称
							});
							htm += suit+"</td></tr>";
						}
					});
					$("#lookorders_goodlistdetailetext").html(htm);
				}
			});
			objs.orderlistdetailwindow.window('open');
		}catch(e){
			showMsg('哎呀，出错了，问问管理员吧！','系统提示','topcenter')
		}

	}
}
function tousuWindow(){
	var row = objs.orderlistgrid.datagrid("getSelected");
	if(row){
		var telp = $("#telphonenumber").val();
		$("#telphone").val(telp);
		objs.tousuform.attr('action',objs.complaintaddurl);
		objs.tousuwindow.window('open');
		objs.tousuform.form('load',objs.tousuformdefault);
		$('#ordercode_tousu').val(row.orderno);
		$('#uid_tousu').val(row.uid);
	}else{
		showMsg('选择一个订单才可以进行订单投诉！','系统提示','topcenter');
	}
}
function sendMessage(){
}
$(function (){
	loading(false, '正在加载基础数据...');
	$(document).keydown(function(event){
		if(event.keyCode == 112){ //F1
			setCheckin();
			return false;
		}
		if(event.keyCode == 113){ //F2
			setBusy();
			return false;
		}
		if(event.keyCode == 114){ //F3
			setDialouter();
			return false;
		}
		if(event.keyCode == 115){ //F4
			setHold();
			return false;
		}
		if(event.keyCode == 118){ //F7
			opemorderlistwindow();
			return false;
		}
		if(event.keyCode == 119){ //F8
			sendMessage();
			return false;
		}
		if(event.keyCode == 27){ //Esc  清空当前视图所有信息
			clearClickGoods();
			return false;
		}
	});
	$("#typeone").tabs({
		onSelect:function(title,index){
			try{
			   CONFIG.CLICKTMP.type1 = CONFIG.GOODSDATA.type[index];
			   var html = '';
			   html += '<div class="typetwo" onclick="setgooodslists('+ CONFIG.CLICKTMP.type1.calltype_id+');"><a style="background: #60D978;">全部</a></div>';
			   $.each(CONFIG.CLICKTMP.type1.children,function (i,o){
			   		html += '<div class="typetwo" onclick="setgooodslists('+o.calltype_id+');"><a style="background: '+o.color+';">'+o.calltypename+'</a></div>';
			   })
			   $("#typetwo").html('');
			   $("#typetwo").html(html);
			   setgooodslists(CONFIG.CLICKTMP.type1.calltype_id);
			}catch(e){

			}
    	}
    })
	objs.setpasswindow = $("#setpasswindow").window({
		width:400,
		height:250,
		closed:true,
		modal:true,
		draggable:false,
		resizable:false,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})
	objs.tousuwindow = $("#tousuwindow").window({
		width:550,
		height:300,
		closed:true,
		modal:true,
		draggable:false,
		resizable:false,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})
	objs.tousuform = $('#tousuform');
	var tousuformdata = objs.tousuform.serializeArray();
	objs.tousuformdefault = [];
	$.each(tousuformdata,function (i,o){
		objs.tousuformdefault[o.name] = o.value;
	})
	objs.orderlistdetailwindow = $("#orderlistdetailwindow").window({
		width:700,
		height:450,
		closed:true,
		modal:true,
		draggable:false,
		resizable:false,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})

	objs.messagewindow = $("#messagewindow").window({
		width:500,
		height:400,
		cache:false,
		left:$(window).width()-500,
		top:$(window).height()-400,
		closed:true,
		draggable:false,
		resizable:false,
		collapsible:false,
		minimizable:true,
		maximizable:false
	})
	 $('#messagenum').tooltip({
        content: $('<div></div>'),
        onUpdate: function(content){
            content.panel({
                width: 200,
                height:80,
                border: false,
                href: objs.messagelisturl
            });
        },
        onShow: function(){
            var t = $(this);
            t.tooltip('tip').unbind().bind('mouseenter', function(){
                t.tooltip('show');
            }).bind('mouseleave', function(){
                t.tooltip('hide');
            });
        }
    });
	objs.setpassform = $('#setpassform');
	var setpassformdata = objs.setpassform.serializeArray();
	objs.setpassformdefault = [];
	$.each(setpassformdata,function (i,o){
		objs.setpassformdefault[o.name] = o.value;
	})
	$('#windowtabs').tabs({
		/*
		onContextMenu : function(e, title) {
			$('#tabmenus').menu('show', {
				left : e.pageX,
				top : e.pageY
			});
			$('#windowtabs').tabs('select', title);
		}
		*/
	})
	objs.havesomegrid = $("#havesomegrid").datagrid({
		title : '已点菜单',
		toolbar : '#havesometoolbar',
		fit : true,
		border : false,
		striped : true,
		rownumbers : true,
		nowrap:false,
		singleSelect:true,
		fitColumns:true,
		columns : [[{
			field : 'goodsname',
			title : '商品名称',
			width : 150
		}, {
			field : 'oneprice',
			title : '单价',
			width : 48,
			formatter : setFormatPrice
		}, {
			field : 'numbers',
			title : '数量',
			editor: {type:'numberspinner',options:{required:true,precision:0,min:1,max:10000}},
			width : 60
		}, {
			field : 'discount',
			title : '折扣%',
			editor: {type:'numberbox',options:{required:true,precision:0,disabled:CONFIG.DELIVERY_DISCOUNT,min:1,max:100,onChange:function(v,ov){
				if(CONFIG.CLICKTMP.ALLDISCOUNT != 100){
					$(this).numberbox('disable');
				}
			}}},
			width : 40
		}, {
			field : 'price',
			title : '小计',
			width : 50,
			formatter : setFormatAllPrice
		}, {
			field : 'remarks',
			title : '备注',
			editor: 'text',
			width : 45
		}]],
		onClickCell:onClickCell

	});
	objs.orderlistgrid = $("#orderlistgrid").datagrid({
		toolbar : '#orderlistgridtoolbar',
		fit : true,
		border : false,
		striped : true,
		rownumbers : true,
		nowrap:false,
		singleSelect:true,
		pagination:true,
		pageSize:20,
		sortName : 'ctime',
		sortOrder : 'desc',
		columns : [[{
			field : 'status',
			title : '状态',
			width : 70,
			formatter:function (v,row){
				return row.stavalue;
			}
		}, {
			field : 'orderno',
			title : '订单编号',
			width : 150
		}, {
			field : 'uid',
			title : '会员',
			width : 120,
			formatter:function (v,row){
				return row.memidtext;
			}
		}, {
			field : 'cuid',
			title : '下单人',
			width : 60,
			formatter : function (v,row){
				return row.cuidtext;
			}
		}, {
			field : 'ctime',
			title : '下单时间',
			width : 130
		}, {
			field : 'price',
			title : '金额',
			width : 130
		}]]
	});
	objs.detailgoodsgrid = $("#detailgoodsgrid").datagrid({
		fit : true,
		border : false,
		striped : true,
		nowrap:false,
		singleSelect:true,
		fitColumns:true,
		columns : [[{
			field : 'goodsname',
			title : '商品名称',
			width : 200
		}, {
			field : 'numbers',
			title : '数量',
			width : 60
		}, {
			field : 'oneprice',
			title : '单价',
			width : 48,
			formatter : setFormatPrice
		}, {
			field : 'discount',
			title : '折扣%',
			width : 40
		}, {
			field : 'price',
			title : '小计',
			width : 50,
			formatter : setFormatAllPrice
		}, {
			field : 'remarks',
			title : '备注',
			editor: 'text',
			width : 45
		}]],
		rowStyler: function(index,row){
			if (row.fcolor == 1){
				return 'background-color:#6293BB;color:#fff;';
			}
			if (row.fcolor == 2){
				return 'background-color:#000000;color:#fff;';
			}
			return 'background-color:#FFFFFF;color:#000000;';
		}
	});
	objs.addresslistdatagird = $("#addresslistdatagird").datagrid({
		fit : true,
		border : false,
		striped : true,
		rownumbers : true,
		nowrap:false,
		idField:'memaid',
		singleSelect:true,
		columns : [[{
			field : 'name',
			title : '姓名',
			width : 100
		}, {
			field : 'uid',
			title : '会员',
			width : 60,
			formatter : function(v,row){
				return row.uidtext
			}
		}, {
			field : 'sex',
			title : '性别',
			width : 40,
			formatter : function(v,row){
				return row.sextext
			}
		}, {
			field : 'phone',
			title : '手机',
			width : 120
		}, {
			field : 'telphone',
			title : '电话',
			width : 120,
			formatter : function(v,row){
				return row.telphone+'  '+row.branchnum;
			}
		}, {
			field : 'storeid',
			title : '门店',
			width : 80,
			formatter : function(v,row){
				return row.storeidtext
			}
		}, {
			field : 'address',
			title : '地址',
			width : 350
		}, {
			field : 'receiptsatus',
			title : '发票',
			width : 45,
			formatter : function(v,row){
				return returnStatus(v);
			}
		}]],
		onSelect:function (index,row){
			$("#addressgoto").html(' 姓名：'
				+row.name+' 地址：'
				+row.address+' 电话：'
				+row.phone+' / '+row.telphone+' '
				+row.branchnum);
		},
		onLoadSuccess:function (data){
			$("#addressgoto").html("");
			if(data.rows.length>0){
				$(this).datagrid("unselectAll");
				$(this).datagrid("selectRow",0);
			}
		},
		onDblClickRow:selectAddress
	});
	objs.suitflaggoodswindow = $("#suitflaggoodswindow").window({
		width:550,
		height:450,
		closed:true,
		modal:true,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})
	objs.addresswindow = $("#addressinfowindow").window({
		width:550,
		height:450,
		closed:true,
		modal:true,
		maximized:true,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})
	objs.orderdetailedwindow = $("#orderdetailedwindow").window({
		width:700,
		height:600,
		closed:true,
		modal:true,
		//maximized:true,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})
	objs.orderlistwindow = $("#orderlistwindow").window({
		width:900,
		height:500,
		closed:true,
		modal:true,
		collapsible:false,
		minimizable:false,
		maximizable:false
	})
	$('#cityid').combobox({
	    url:objs.getcitylisturl,
	    required:true,
	    valueField:'id',
	    textField:'text'
	});
	objs.addressform = $('#addressform');
	var addressformdata = objs.addressform.serializeArray();
	objs.addressformdefault = [];
	$.each(addressformdata,function (i,o){
		objs.addressformdefault[o.name] = o.value;
	})
	$('#testbaidu').autoCmpt({url:objs.getaddressurl});
	//为TAB右键菜单绑定事件
	// 刷新
	$('#reloadtab').click(function() {
		var currTab = $('#windowtabs').tabs('getSelected');
		var url = $(currTab.panel('options').content).attr('src');
		if (url != undefined) {
			$('#windowtabs').tabs('update', {
				tab : currTab,
				options : {
					content : '<iframe src="' + url + '" frameborder=0 height=100% width=100% scrolling=no></iframe>'
				}
			});
		}
	});
	// 关闭当前
	$('#closethis').click(function() {
		var currTab = $('#windowtabs').tabs('getSelected');
		if (currTab.panel('options').closable) {
			$('#windowtabs').tabs('close', currTab.panel('options').title);
		}
	});
	// 全部关闭
	$('#closeall').click(function() {
		var taball = $('#windowtabs').tabs('tabs');
		var title = [];
		var j = 0;
		for (var i = 0 in taball) {
			if (taball[i].panel('options').closable) {
				title[j] = taball[i].panel('options').title;
				j++;
				title.length = j;
			}
		}
		for (var v = 0 in title) {
			$('#windowtabs').tabs('close', title[v]);
		}
	});
	// 关闭除当前之外的TAB
	$('#closeother').click(function() {
		var taball = $('#windowtabs').tabs('tabs');
		var currTab = $('#windowtabs').tabs('getSelected');
		var currTitle = currTab.panel('options').title;
		var title = [];
		var j = 0;
		for (var i = 0 in taball) {
			if (taball[i].panel('options').title != currTitle) {
				if (taball[i].panel('options').closable) {
					title[j] = taball[i].panel('options').title;
					j++;
					title.length = j;
				}
			}
		}
		if (j == 0) {
			showMsg('没有可关闭的选项卡了！','系统提示','topcenter')
		} else {
			for (var v = 0 in title) {
				$('#windowtabs').tabs('close', title[v]);
			}
		}
		return false;
	});
	// 关闭当前右侧的TAB
	$('#closeright').click(function() {
		var taball = $('#windowtabs').tabs('tabs');
		var currTab = $('#windowtabs').tabs('getSelected');
		var currTitle = currTab.panel('options').title;
		var title = [];
		var j = 0;
		var st = false;
		for (var i = 0 in taball) {
			if (currTitle == taball[i].panel('options').title) {
				st = true;
			}
			if (st) {
				if (taball[i].panel('options').title != currTitle) {
					if (taball[i].panel('options').closable) {
						title[j] = taball[i].panel('options').title;
						j++;
						title.length = j;
					}
				}
			}
		}
		if (j == 0) {
			showMsg('右侧没有可关闭的选项卡了！','系统提示','topcenter')
		} else {
			for (var v = 0 in title) {
				$('#windowtabs').tabs('close', title[v]);
			}
		}
		return false;
	});
	// 关闭当前左侧的TAB
	$('#closeleft').click(function() {
		var taball = $('#windowtabs').tabs('tabs');
		var currTab = $('#windowtabs').tabs('getSelected');
		var currTitle = currTab.panel('options').title;
		var title = [];
		var j = 0;
		var st = true;
		for (var i = 0 in taball) {
			if (currTitle == taball[i].panel('options').title) {
				st = false;
			}
			if (st) {
				if (taball[i].panel('options').closable) {
					title[j] = taball[i].panel('options').title;
					j++;
					title.length = j;
				}
			}
		}
		if (j == 0) {
			showMsg('左侧侧没有可关闭的选项卡了！','系统提示','topcenter')
		} else {
			for (var v = 0 in title) {
				$('#windowtabs').tabs('close', title[v]);
			}
		}
	});
	// 退出
	$("#mmexit").click(function() {
		$('#tabmenus').menu('hide');
	});
	$('#mmindex').click(function() {
		$('#windowtabs').tabs('select', '系统首页');
	})

	//设置幻灯片


	$.getJSON(objs.menuurl, function(data) {
		objs.menudata = data;
		setMenu();
		loading(false, '正在加载语言包...');
		$.getJSON(objs.getlangurl, function(data) {
			L = data;
			//加载商品数据
			loading(true);
			getgoodsdata();
			//setInterval('loadmessage();',CONFIG.mssagetime);//TODO LZT
			//setInterval('loadcallmsg();',CONFIG.mssagetime);//TODO LZT
		})
	})
})