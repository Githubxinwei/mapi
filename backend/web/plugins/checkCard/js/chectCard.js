/**
 * Created by DELL on 2017/4/17.
 * content 验卡页面中js
 */
$(function() {

    //验卡页面点击回车跳转详情页
    $("#checkBtn").click(function(){
        window.location.href='/check-card/detail?mid=0&c=3';
    });
    $('.backPre').on('click',function(){
        history.go(-1);
    });

    // 请假开始日期插件的js
    $("#dataLeave1").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true,//今日按钮
    });
    // 场地约开始日期插件的js
    $("#dataLeave12").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true,//今日按钮
    });
    // 请假结束日期插件的js
    $("#dataLeaveEnd").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true//今日按钮
    });

    // 页面加载时自动获取焦点
    window.onload = function (){
        $(".cardCheckNumberInput").focus();
    };

});