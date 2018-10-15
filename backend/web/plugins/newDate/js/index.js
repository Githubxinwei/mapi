//调用开始日期
$("#datetimeStart").datetimepicker({
    format: 'yyyy-mm-dd',
    minView:'month',
    language: 'zh-CN',
    autoclose:true,
    startDate:'2008-08-08'
}).on("click",function(){
    $("#datetimeStart").datetimepicker("setEndDate",$("#datetimeEnd").val());
});

$("#datetimeStart1").datetimepicker({
    format: 'yyyy-mm-dd',
    minView:'month',
    language: 'zh-CN',
    autoclose:true,
    startDate:'1900-08-08'
}).on("click",function(){
    $("#datetimeStart").datetimepicker("setEndDate",$("#datetimeEnd").val());
});

$("#datetimeStart2").datetimepicker({
    format: 'yyyy-mm-dd',
    minView:'month',
    language: 'zh-CN',
    autoclose:true,
    startDate:'1900-08-08'
}).on("click",function(){
    $("#datetimeStart").datetimepicker("setEndDate",$("#datetimeEnd").val());
});

//调用结束日期
$("#datetimeEnd").datetimepicker({
    format: 'yyyy-mm-dd',
    minView:'month',
    language: 'zh-CN',
    autoclose:true,
    startDate:new Date()
}).on("click",function(){
    $("#datetimeEnd").datetimepicker("setStartDate",$("#datetimeStart").val());
});
//
// $("#datetimeAge").datetimepicker({
//     format: 'yyyy-mm-dd',
//     minView:'month',
//     language: 'zh-CN',
//     autoclose:true,
//     startDate:new Date()
// }).on("click",function(){
//     $("#datetimeAge").datetimepicker("setAgeDate",$("#datetimeAge").val());
// });
//点击图标出发input日历
$('.leftDate').on('click',function(){
    $("#datetimeStart").focus();
});
$('.rightDate').on('click',function(){
    $("#datetimeEnd").focus();
});
// $(document).on('click','.start',function () {
//     $("#datetimeAge").focus();
// });

<!--js日期插件加载-->
$(document).ready(function () {
    var $datetimepicker = $('#datetimepicker');
    $datetimepicker.datetimepicker({
        format: 'yyyy-mm-dd',
        minView:'month',
        language: 'zh-CN',
        autoclose:true,
        startDate:'2008-08-08'
    }).on("click",function(){
        $datetimepicker.datetimepicker("setEndDate",$datetimepicker.val());
    });
});

