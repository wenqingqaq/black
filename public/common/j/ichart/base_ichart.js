/**
 * @author Administrator
 */
        ///饼状图
		function showPieChart(){
			var height = objs.height;
			var width = objs.width;
			var title = objs.title;
			var market_name = objs.market_name;
			var seldate = objs.seldate;
			var data = objs.ichartdata;
	    	
			var chart = new iChart.Pie3D({
				render : 'canvasDiv',
				data: data,
				title : {
					text : title,
					height:40,
					fontsize : 30,
					color : '#282828'
				},
				sub_option : {
					mini_label_threshold_angle : 40,//迷你label的阀值,单位:角度
					mini_label:{//迷你label配置项
						fontsize:20,
						fontweight:600,
						color : '#ffffff'
					},
					label : {
						background_color:null,
						sign:false,//设置禁用label的小图标
						padding:'0 4',
						border:{
							enable:false,
							color:'#666666'
						},
						fontsize:11,
						fontweight:600,
						color : '#4572a7'
					},
					listeners:{
						parseText:function(d, t){
							return d.get('value')+"%";//自定义label文本
						}
					} 
				},
				legend:{
					enable:true,
					padding:0,
					offsetx:120,
					offsety:50,
					color:'#3e576f',
					fontsize:20,//文本大小
					sign_size:20,//小图标大小
					line_height:28,//设置行高
					sign_space:10,//小图标与文本间距
					border:false,
					align:'left',
					background_color : null//透明背景
				},
				animation : true,//开启过渡动画
				animation_duration:800,//800ms完成动画 
				shadow : true,
				shadow_blur : 6,
				shadow_color : '#aaaaaa',
				shadow_offsetx : 0,
				shadow_offsety : 0,
				background_color:'#f1f1f1',
				align:'right',//右对齐
				offsetx:-100,//设置向x轴负方向偏移位置60px
				offset_angle:-90,//逆时针偏移120度
				width : width,
				height : height,
				radius:150
			});
			//利用自定义组件构造右侧说明文本
			chart.plugin(new iChart.Custom({
					drawFn:function(){
						//在右侧的位置，渲染说明文字
						chart.target.textAlign('start')
						.textBaseline('top')
						.textFont('600 20px Verdana')
						.fillText(market_name,120,80,false,'#be5985',false,24)
						.textFont('600 12px Verdana')
						.fillText(seldate,120,110,false,'#999999');
					}
			}));
			
			chart.draw();
		}
		//折线图
		function showLineBasicChart(){
			var height = objs.height;
			var width = objs.width;
			var title = objs.title;
			//var cabname = objs.cabname;
            var market_name = objs.market_name;
            var seldate = objs.seldate;
			//var data = objs.ichartdata;
			var flow= objs.flow;
			var labels = objs.labels;
			// for(var i=0;i<25;i++){
				// flow.push(Math.floor(Math.random()*(10+((i%16)*5)))+10);
			// }
			// console.dir(flow);return;
			var data = objs.flow_items;
			//var labels = ["00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"];
			
			var chart = new iChart.LineBasic2D({
				render : 'canvasDiv',
				data: data,
				align:'center',
				title : {
					text:market_name+seldate+objs.title,
					font : '微软雅黑',
					fontsize:24,
					color:'black'
				},
				width : width,
				height : height,
				shadow:true,
				shadow_color : '#202020',
				shadow_blur : 8,
				shadow_offsetx : 0,
				offsetx:-40,
				shadow_offsety : 0,
				background_color:'#ffffff',
				animation : true,//开启过渡动画
				animation_duration:200,//200ms完成动画
				tip:{
					enable:true,
					shadow:true,
					listeners:{
						 //tip:提示框对象、name:数据名称、value:数据值、text:当前文本、i:数据点的索引
						parseText:function(tip,name,value,text,i){
							 return '<div style="none;height:20px;width:180px;line-height:1.5em" >'+labels[i]+objs.y_title+'：'+value+objs.unit+'</div>';
						}
					}
				},
				legend : {
					enable : true,
					row:5,//设置在一行上显示，与column配合使用
					column : 'max',
					valign:'top',
					sign:'square',
					offsetx:-80,//设置x轴偏移，满足位置需要
					listeners:{
						click:function(l,e,m){
							//console.log(l,e,m);
							// alert(m.index);
							// delete objs.flow_items[m.index];
							// console.log(objs.flow_items);
							// //手动调用重绘
							// showLineBasicChart();
						}
					}
				},
				crosshair:{
					enable:true,
					line_color:'#ec4646'
				},
				sub_option : {
					smooth : true,
					label:true,
					hollow:true,
					hollow_inside:true,
					point_size:8
				},
				coordinate:{
					width:width*0.7,
					height:height*0.58,
					striped_factor : 0.18,
					grid_color:'#DBE1E1',
					axis:{
						color:'#252525',
						width:[0,0,4,4]
					},
					scale:[{
						 position:'left',	
						 start_scale:0,
						 end_scale:objs.min,
						 scale_space:objs.diff,
						 scale_size:30,
						 scale_enable : false,
						 label : {color:'black',font : '微软雅黑',fontsize:11,fontweight:600},
						 scale_color:'#9f9f9f'
					}
					,{
						 position:'bottom',	
						 label : {textAlign:'right',textBaseline:'middle',color:'#9d987a',font : '微软雅黑',fontsize:11,fontweight:600,rotate:objs.rotate},
						 scale_enable : false,
						 labels:labels,
						 scaleAlign:'top'
					}
					]
				}
			});
			//利用自定义组件构造左侧说明文本
			chart.plugin(new iChart.Custom({
					drawFn:function(){
						//计算位置
						var coo = chart.getCoordinate(),
							x = coo.get('originx'),
							y = coo.get('originy'),
							w = coo.width,
							h = coo.height;
						//在左上侧的位置，渲染一个单位的文字
						chart.target.textAlign('start')
						.textBaseline('bottom')
						.textFont('600 11px 微软雅黑')
						.fillText(objs.y_title+objs.unit,x-40,y-12,false,'black')
						
					}
			}));
			//开始画图
			chart.draw();
		}
	//柱状图
 	function showColumnMultiChart(){
		        var data = objs.flow_items;
				var chart = new iChart.ColumnMulti3D({
						render : 'canvasDiv',
						data: data,
						labels:objs.labels,
						title : {
							text : objs.market_name+objs.seldate+objs.title,
							color : '#3e576f'
							
						},
						width : objs.width,
						height : objs.height,
						offsetx:0,
           				offsety:-50,
						background_color : '#ffffff',
						legend:{
							enable:true,
							valign : 'right',
							row:3,
							column:'max',
							border:{
		                        color:"#BCBCBC",
		                        width:1
			                }
						},
						label:{
							fontweight:600,
							fontsize:11,
							textAlign:'right',
							textBaseline:'middle',
							rotate:objs.rotate,
							color : '#666666'
						},
						sub_option:{
							label :false
						},
						tip:{
							enable:true,
							shadow:true,
							listeners:{
								 //tip:提示框对象、name:数据名称、value:数据值、text:当前文本、i:数据点的索引
								parseText:function(tip,name,value,text,i){
									return '<div style="none;height:20px;top:10px;line-height:1.5em" >'+name+objs.y_title+'：'+value+objs.unit+'</div>';							}
							}
				        },
						text_space : 16,//坐标系下方的label距离坐标系的距离。
						coordinate:{
							background_color : '#d7d7d5',
							grid_color : '#a4a4a2',
							color_factor : 0.24,
							board_deep:10,
							offsety:-10,
							pedestal_height:10,
							left_board:false,//取消左侧面板
							width:objs.width*0.8,
							height:objs.height*0.7,
							scale:[{
								 position:'left',	
								 start_scale:0,
								 end_scale:objs.min,
								 scale_space:objs.diff,
								 scale_enable : false,
								 label:{
									color:'#4c4f48'
								 }
							}]
						}
				});

				//利用自定义组件构造左侧说明文本
				chart.plugin(new iChart.Custom({
						drawFn:function(){
							//计算位置
							var coo = chart.getCoordinate(),
								x = coo.get('originx'),
								y = coo.get('originy');
							//在左上侧的位置，渲染一个单位的文字
							chart.target.textAlign('start')
							.textBaseline('bottom')
							.fillText(objs.title+objs.unit,x-40,y-28,false,'#6d869f');
							
						}
				}));
				
				chart.draw();
	}	

