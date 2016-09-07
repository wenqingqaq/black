/**
 * Created by XiaohuiTian on 2015-03-23.
 */
var pagenation = function () {
	var pagenation = this;
	pagenation.controller = {
		/**
		 * 初始化方法
		 * @param defaultPage 设置展示第几页 eg:1
		 * @param pageNums 设置每页展示的条数 eg:5
		 * @param pageToolID 设置分页按钮放在什么位置 eg:pageToolID(代表分页html要放在id为pageToolID中的标签中)
		 * @param postUrl 请求的url
		 * @param showPageData 展示分页数据的回调函数
		 */
		init: function (defaultPage, pageNums, pageToolID, postUrl, showPageData, params) {

			pagenation.view.defaultPage = defaultPage;

			pagenation.view.pageNums = pageNums;

			//设置展示分页框的容器
			pagenation.view.pageToolID = pageToolID;

			//设置请求的url
			pagenation.model.postUrl = postUrl;

			//设置展示的方法
			pagenation.view.showPageData = showPageData;

			//设置请求的参数
			pagenation.model.params = params;

			//pagenation.view.start();
		},
		/**
		 * 获取数据
		 * @param nextPage 请求的页码 eg:1
		 * @param pageNums 每页显示的条数 eg:5
		 */
		getPageData: function (nextPage, pageNums) {
			pagenation.model.getData(nextPage, pageNums);
		},
		/**
		 * 展示分页数据和分页的按钮
		 */
		showPage: function () {
			pagenation.view.showPageData(pagenation.model.pageData);
			pagenation.view.showPageTool(pagenation.model.currentPage, pagenation.model.allPageNums);
		}
	};

	pagenation.model = {
		currentPage: 1,
		dataNums: 2,
		allPageNums: 0,
		pageData: {},
		postUrl: '',
		params: {},
		/**
		 * 存放请求回来的分页数据
		 * @param data array
		 */
		setPageData: function (data) {
			pagenation.pageData = data;
		},
		/**
		 * 请求分页中一页的数据
		 * @param nextPage 请求的页码
		 * @param pageNums 每页显示的条数
		 * @param params 筛选的条件
		 */
		getData: function (nextPage, pageNums) {
			$.post(pagenation.model.postUrl, {
				nextPage: nextPage,
				pageNums: pageNums,
				params: pagenation.model.params
			}, function (res) {
				pagenation.model.pageData = res.data;
				pagenation.model.dataNums = parseInt(res.count);
				pagenation.model.currentPage = nextPage;
				pagenation.model.allPageNums = pagenation.model.dataNums % pageNums == 0 ? pagenation.model.dataNums / pageNums : (Math.floor(pagenation.model.dataNums / pageNums) + 1);
				//展示分页
				pagenation.controller.showPage();
			});
		}
	};

	pagenation.view = {
		defaultPage: 1,
		pageNums: 5,
		pageToolID: 'pageTool',
		/**
		 * 加载数据
		 */
		start: function () {
			pagenation.controller.getPageData(pagenation.view.defaultPage, pagenation.view.pageNums);
		},
		/**
		 * 展示数据的回调函数
		 */
		showPageData: function () {
		},
		/**
		 * 展示分页的按钮
		 * @param currentPage 当前页
		 * @param allPageNums 一共有多少页
		 */
		showPageTool: function (currentPage, allPageNums) {
			//摆放控件
			pagenation.view.putPageLabel(allPageNums);
			//控制标签状态
			pagenation.view.controlLabelStatus(currentPage, allPageNums);
			//添加事件
			$("#" + pagenation.view.pageToolID + " a").on("click", function () {
				if ($(this).attr('id') == 'previous') {
					pagenation.view.defaultPage = currentPage > 1 ? (currentPage - 1) : 1;
				} else if ($(this).attr('id') == 'next') {
					pagenation.view.defaultPage = currentPage < allPageNums ? currentPage + 1 : allPageNums;
				} else {
					pagenation.view.defaultPage = parseInt($(this).text());
				}
				pagenation.view.start();
			});
		},
		/**
		 * 生成分页的按钮，并把html放到相应的标签中
		 * @param allPageNums 一共有多少页
		 */
		putPageLabel: function (allPageNums) {
			$('#' + pagenation.view.pageToolID).html('');
			if(allPageNums > 0){
				var pageToolHtml = '';
				pageToolHtml += '<a class="upage_a1" id="previous" href="javascript:;">上一页</a>';
				pageToolHtml += '<a class="upage_a2" id="pn1" href="javascript:;">1</a>';
				pageToolHtml += '<span id="first_apostrophe">...</span>';
				for (var i = 2; i <= allPageNums; i++) {
					pageToolHtml += '<a class="upage_a2" style="display:none" id="pn' + i + '" href="javascript:;">' + i + '</a>';
				}
				pageToolHtml += '<span id="last_apostrophe">...</span>';
				pageToolHtml += '<a class="upage_a1" id="next" href="javascript:;">下一页</a>';

				$('#' + pagenation.view.pageToolID).html(pageToolHtml);
			}
		},
		/**
		 * 控制标签展示的状态
		 * @param currentPage 当前页
		 * @param allPageNums 一共有多少页
		 */
		controlLabelStatus: function (currentPage, allPageNums) {
			//上一页
			if (currentPage == 1) {
				$('#previous').attr('class', 'upage_a3');
			} else {
				$('#previous').attr('class', 'upage_a1');
			}
			//下一个
			if (currentPage == allPageNums) {
				$('#next').attr('class', 'upage_a3');
			} else {
				$('#next').attr('class', 'upage_a1');
			}

			//当前页选中
			$('#pn' + currentPage).attr("class", "upage_a2 upage_cuta2");

			//控制第一个省略号
			if (allPageNums > 5 && currentPage > 3) {
				$("#first_apostrophe").show();
			} else {
				$("#first_apostrophe").hide();
			}

			//控制最后一个省略号
			if (allPageNums - currentPage >= 3) {
				$("#last_apostrophe").show();
			} else {
				$("#last_apostrophe").hide();
			}

			//控制中间部分的页码
			if (allPageNums > 5 && currentPage > 3) {
				if (allPageNums - currentPage >= 2) {
					$("#pn" + (currentPage - 1)).show();
					$("#pn" + currentPage).show();
					$("#pn" + (currentPage + 1)).show();
					$("#pn" + (currentPage + 2)).show();
				} else {
					$("#pn" + allPageNums).show();
					$("#pn" + (allPageNums - 1)).show();
					$("#pn" + (allPageNums - 2)).show();
					$("#pn" + (allPageNums - 3)).show();
				}

			} else if (allPageNums <= 5 || (allPageNums > 5 && currentPage <= 3)) {
				for (var j = 1; j <= 5; j++) {
					$("#pn" + j).show();
				}
			}
		}
	}
}