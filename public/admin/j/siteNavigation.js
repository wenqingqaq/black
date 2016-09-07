
$(function(){
	objs.sitenavigationwindow = $("#sitenavigationwindow").window({
		width:827,
		height:528,
		closed:true,
		modal:true,
		draggable:false,
		resizable:false,
		collapsible:false,
		minimizable:false,
		maximizable:false
	});
	
	
	$('#sitenavigation').mouseover(function(){
		//getSiteNavigation();
	});
	
	$('#sitenavigation').click(function(){
		getSiteNavigation();
	});
	
	function getSiteNavigation(){
		objs.sitenavigationwindow.window('open');
		var chtml = '<table width="100%" class="cat-form site_navigation_list">';
		$.each(objs.menudata.one, function(i, o) {
			chtml += '<tr><td><img height="18px" width="18px" src="/common/c/bootstrap/img/'+o.icon+'"><span class="menu_'+o.icon.slice(0,-5)+'" style="font-family:宋体;font-weight:bold;font-size:16px">'+o.nickname+'<br></span>';
			chtml += '<p style="border-bottom:dashed 1px '+o.color+';margin:0px;padding:0px;height:1px;width:780px" class="menu_'+o.icon.slice(0,-5)+'"></p></td>';
			var menuTwo = objs.menudata.two['menu' + o.meid];
			for(var j in menuTwo){
				chtml += '<tr><td class="menu_'+o.icon.slice(0,-5)+'" style="font-family:宋体;font-size:14px;font-weight:bold">&nbsp;&nbsp;•'+menuTwo[j].mname+'</td></tr>';
				for(var k in menuTwo[j].children){
					chtml += '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-family:宋体;font-size:14px;color:gray;" href="javascript:void(0);" onclick=addTag("'+menuTwo[j].children[k].text+'","'+menuTwo[j].children[k].id+'")>'+menuTwo[j].children[k].text+'</a></td>';
				}
			}
			chtml += '</tr>';
		});
		chtml += '</table>';
		$('#data').html(chtml);
		
		$('#sitenavigationwindow').find('a').click(function(){
			objs.sitenavigationwindow.window('close');
		});
	}
	
});

