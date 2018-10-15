$(function(){
	//点击删除按钮，阻止事件向下一级传递
	$('.tdBtn').on('click',function(e){
		e.stopPropagation();
	});
	//动态计算让模态框垂直居中
	function mtk(){
		var h = $(window).height();
		var w = $(window).width();
		var mW = $('#mutaikuang').outerWidth();
		var mH = $('#mutaikuang').outerHeight();
		var top = (h-mH)/2+'px';
		var left = (w-mW)/2+'px';
		$('#mutaikuang').css({'marginTop':top,'marginLeft':left,'zIndex':1000000});
	}
	//点击弹出模态框
	$('#tmk').on('click',function(e){
		mtk();
	});
	//表单上的hover效果
	$('.w60Auto>.ha_xz>.ha_xznr>div').hover(function(){
		var index = $(this).index('.w60Auto .ha_xz .ha_xznr>div');
		$('.list_hove').eq(index).css("display",'block');
	},function(){
		var index = $(this).index('.w60Auto .ha_xz .ha_xznr>div');
		$('.list_hove').eq(index).css("display",'none');
	});
	//返回上一页
	$('.h50 h6').on('click',function(){
		window.history.back(-1);
	});
	//返回上一页
	$('.backBut').on('click',function(){
		window.history.back(-1);
	});
	//团课详情预约
	// $('.subscribeSelect li>div').on('click',function(){
	// 	$(this).toggleClass('selectYes');
	// 	var i = $(this).index('.subscribeSelect li>div');
	// 	if($(this).hasClass('selectYes')){
	// 		$('.selectName').eq(i).css('display','block');
	// 		$(this).children('span').addClass('glyphicon glyphicon-ok');
	// 	}else{
	// 		$('.selectName').eq(i).css('display','none');
	// 		$(this).children('span').removeClass('glyphicon glyphicon-ok');
	// 	}
	// });

	//团课详情页面中遍历座位是否被订购，订购的改变背景颜色
	$('.subscribeSelect li>div').each(function(index){
		if($(this).hasClass('selectYes')){
			$('.selectName').eq(index).css('display','block');
			$(this).children('span').addClass('glyphicon glyphicon-ok');
		}
	});
	//点击跳转详情页面
	// $('.table>tbody>tr').on('click',function(){
	// 	window.location.href = '/league/league-detail?mid=6&c=3';
	// });


	//新增课程中调用开始日期
	$(".datetimeStart").datetimepicker({
		format: 'yyyy-mm-dd',
		minView:'month',
		language: 'zh-CN',
		autoclose:true,
		startDate:'2008-08-08'
	}).on("click",function(){
		$(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").val());
	});
//新增课程中调用结束日期
	$(".datetimeEnd").datetimepicker({
		format: 'yyyy-mm-dd',
		minView:'month',
		language: 'zh-CN',
		autoclose:true,
		startDate:new Date()
	}).on("click",function(){
		$(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").val());
	});
	


	

	
});
