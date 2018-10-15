/*私教小团体课-状态栏显示问题*/
$(function () {
    $('.titleBoxChoose>h2').on('click',function () {
        $('.titleBoxChoose>h2').removeClass('active');
        $(this).addClass('active');
    });
    //购买私课-缴费日期日历插件
    $("#registerDate").datetimepicker({
        minView: "month",
        format: 'yyyy-mm-dd hh:ii',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: false//今日按钮
    });
});
