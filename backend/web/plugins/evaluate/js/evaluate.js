$(function(){
	//返回上一页
	$('.backBut').on('click',function(){
		window.history.back(-1);
	});
	//点击tr页面进行页面跳转
	$('tbody tr').on('click', function() {
		window.location.href = '/evaluate/evaluate-detail?c=7';
	});
});