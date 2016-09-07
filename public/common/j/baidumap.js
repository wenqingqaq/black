//创建和初始化地图函数：
function initMap(){
	createMap();//创建地图
	setMapEvent();//设置地图事件
	addMapControl();//向地图添加控件
}

//创建地图函数：
function createMap(){ 
	var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
	var point = new BMap.Point(116.394783,39.93397);//定义一个中心点坐标
	map.centerAndZoom(point,12);//设定地图的中心点和坐标并将地图显示在地图容器中
	window.map = map;//将map变量存储在全局
}
//通过地址获取位置
function addressMap(){
	// 将地址解析结果显示在地图上，并调整地图视野
	if($('#address').val() == ''){
		showMsg('请填写地址！');
		return;
	}
	var myGeo = new BMap.Geocoder();
	myGeo.getPoint($('#address').val(), function(point){
		if (point) { 
			createPoint(point);
		}else{
			showMsg('查无此地址！请检查！');
		}
	}, "北京市");  
}
//通过坐标返回地址信息（鼠标点击时调用）
function pointAddress(point){
	var myGeo = new BMap.Geocoder();  
	myGeo.getLocation(point, function(GeocoderResult){
		if(GeocoderResult){
			$("#address").val(GeocoderResult.address);
		}
	}); 
}

//创建地图标注
function createPoint(point){
	$("#latitude").val(point.lat);
	$("#longitude").val(point.lng);
	map.clearOverlays();
	map.centerAndZoom(point, 20);
	var marker = new BMap.Marker(point);        // 创建标注  
	map.addOverlay(marker);                     // 将标注添加到地图中  
}
//地图事件设置函数：
function setMapEvent(){
	map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
	map.enableScrollWheelZoom();//启用地图滚轮放大缩小
	map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
	map.enableKeyboard();//启用键盘上下左右键移动地图
}

//地图控件添加函数：
function addMapControl(){
	//向地图中添加缩放控件
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_SMALL});
	map.addControl(ctrl_nav);
	//向地图中添加缩略图控件
	var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:0});
	map.addControl(ctrl_ove);
	map.addEventListener("click", function(e){  
		var point = new BMap.Point( e.point.lng, e.point.lat);
		createPoint(point);
		pointAddress(point);
	});  
	//向地图中添加比例尺控件
	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
	map.addControl(ctrl_sca);
}
$(function (){
    initMap();//创建和初始化地图
});