
/**
 *
 * 初始化js
 */
var PHP = { }; //实例化 php_js 代码，实例化后可以在js代码里面使用php的函数 例如：获取当前日期 php date('ymd') js PHP.date('ymd')
$(function(){
	PHP = new PHP_JS();
	$('body').bind("contextmenu", function(e){ return false; })


})
/**
 * 显示进度条，当传入true时关闭，传入false时显示
 * text参数是在提示过程中显示的提示信息， 不填写会默认显示处理中，请稍候
 */
function loading(status,text){
	if(status){
		$.messager.progress('close');
	}else{
		if(text){
		}else{
			text = '处理中，请稍后...';
		}
		$.messager.progress({
			title:'请稍候',
			text:text
		});
	}
}

/**
 * 实现状态判断
 * @param value
 * @returns {String}
 */
function returnStatus(value){
	if(value == 1) return '<span style="color:green"> √ </span>';
	if(value == 0)return '<span style="color:red"> × </span>';
};


/**
 * 显示右下角提示框
 */
function showMsg(content,title,postion){
	top.$.messager.alert("提示",content);
}

/**
 * 提交表单，传入表单的ID，会自动更新datagrid
 */
function dosubmit(id){
	$('#'+id+'form').form("submit",{
		onSubmit:function (){
			if($('#'+id+'form').form('validate')){
				loading();
				return true;
			}else{
				return false;
			}
		},
		success:function(data){
			loading(1);
			try{
				var dataobj = jQuery.parseJSON(data);
			}catch(e){
				showMsg(e.name + ":" + e.message);
				return;
			}
			showMsg(dataobj.message);
			if(dataobj.status==1){
				try{
					$('#'+id+'window').window("close");
				}catch(e){
				}
				try{
					eval('reload'+id+'grid();');
				}catch(e){
				}
			}
		}
	});
}
/**
 * 关闭打开的窗口
 */
function windowclose(id){
	$("#"+id+"window").window({closed: true});
}

/**
 * 按ID得到DOM元素
 * @param id
 * @returns
 */
function $$(id){
	return document.getElementById(id);
}
/**
 * [检测是否为空]
 * @param  {[string]}  val [值]
 * @return {Boolean}     [布尔]
 */
function isNotNull( val ){
	if( val=='' ){
		return true;
	}else{
		return false;
	}
}
/**
 * [检测开始时间和结束时间]
 * @param  {[string]} startdate [开始时间]
 * @param  {[string]} enddate   [结束时间]
 * @return {Boolean}           [符合条件返回true]
 */
function checkTime( startdate, enddate ){
	if( startdate=='' ){
		$.messager.alert('注意：','开始时间不能为空');
		return false;
	}else if( startdate>enddate ){
		$.messager.alert('注意：','开始时间不能大于结束时间');
		return false;
	}else{
		return true;
	}
}
/**
 * [检测输入内不能有空格]
 * @param  {[object]} obj [输入框]
 * @return {Boolean}     [符合条件返回true]
 */
function notspace( obj ){
	var val = obj.val();
	if( val.match(/[\s]+/) ){
		showMsg("密码中不能有空格");
		return true;
	}
}