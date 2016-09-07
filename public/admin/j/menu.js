/**
 * @author sun zhao xin
 */
var objs = {};
var CONFIG = { };
objs.titles = [];
function setMenu() {
	try{
		$("#topmenus").html('');
		var timer = null;
        var num = 0;
		//console.log(objs.menudata.one);
		$.each(objs.menudata.one, function(i, o) {
			var src = "/common/c/bootstrap/img/"+o.icon;
			var html = '<div class="cat-admin-topmenu menu'+o.meid+'" class="" icon="'+o.icon+'" onclick="replaceMenuPic(this)">' + '<div class="cat-admin-topicon">' + '	<img src= "'+src+'" />' + '</div>' + '<div class="cat-admin-topmenutitle">' + o.nickname + '</div>' + '</div>';
            if (num == 0) {
				$(html).appendTo("#topmenus").bind('click',function(){
						setTwoMenu(o.meid);
						$(".cat-admin-topmenu").removeClass('cat-admin-topmenuhover');
						$(this).addClass('cat-admin-topmenuhover');
						
				}).click();
			} else {
				$(html).appendTo("#topmenus").bind('click',function(){
						setTwoMenu(o.meid);
						$(".cat-admin-topmenu").removeClass('cat-admin-topmenuhover');
						$(this).addClass('cat-admin-topmenuhover');
					
				});
			}
            ++num;
		});
	}catch(e){
	}
}
//点击顶部菜单后更换图标、
function replaceMenuPic(o){
	
	//遍历所有一级菜单，依次还原图标
	$.each(objs.menudata.one, function(i, ob) {
		var obj = " .menu"+ob.meid+" img";
		$(obj).attr("src","/common/c/bootstrap/img/"+ob.icon);
	});
	
	
	
	$(o).siblings().find('.cat-admin-topicon').css('padding-top','8px');
	$(o).siblings().find('.cat-admin-topicon').css('height','28px');
	$(o).find('.cat-admin-topicon').css('padding-top','0px');
	$(o).find('.cat-admin-topicon').css('height','35px');
	$(o).find('img').attr('src',"/common/c/bootstrap/img/"+$(o).attr('icon')+'.png');
}

function setTwoMenu(meid) {
	//$('body').layout('expand','west');
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

    // menutwo 是左侧导航菜单中的所有菜单
    // menuone 是左侧导航菜单中的第一个菜单

	var menutwo = objs.menudata.two['menu' + meid];
	var menuone = '', j = 0;
	for (var i = 0 in menutwo) {
		if (menutwo[i].meid) {
			if (j == 0){
                menuone = menutwo[i].mname;
            }
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


	for (var i in menutwo) {
		if (menutwo[i].meid) {
			var ij = 0;
			var menuthree = [];
            if(menutwo[i].children)
            {
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
    $('#leftmenu').accordion('remove', '通用管理');
}

function addTag(title, url) {
	//TODO 处理登录后总是嵌套多个问题（主要是登录造成的）
	var urlArr = url.split('/');
	urlArr[2] = PHP.ucfirst(urlArr[2]); //首字母大写
	url = PHP.implode('/',urlArr);
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
function removeTag(title){
    $('#windowtabs').tabs('close',title);
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
	//crossDomainAjax(objs.getmessageurl,{},'setmessage');
}
$(function (){
	//loading(false, '正在加载基础数据...');
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
		onContextMenu : function(e, title) {
			$('#tabmenus').menu('show', {
				left : e.pageX,
				top : e.pageY
			});
			$('#windowtabs').tabs('select', title);
		}
	})
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
			showMsg('没有可关闭的选项卡了！')
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
			showMsg('右侧没有可关闭的选项卡了！')
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
			showMsg('左侧侧没有可关闭的选项卡了！')
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
	$.getJSON(objs.menuurl, function(data) {
		objs.menudata = data;
		setMenu();
		//loading(false, '正在加载语言包...');
		//$.getJSON(objs.getlangurl, function(data) {
			//L = data;
			//loading(true);
			setInterval('loadmessage();',5000);//TODO LZT
		//})
	});
	
});