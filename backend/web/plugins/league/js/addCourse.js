/**
 * Created by DELL on 2017/4/11.
 * 表单向导启动js，新增课程页面js
 */
$(function(){
    //新增课程中调用开始日期
    function starDate(){
        $(".datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        })
    }
    starDate();
    $(".datetimeStart").on("click",function(){
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
    //动态添加时间段
    function createTimeQuantum(){
        var $addTime = $('#addTime');
        var $add = $addTime.children('div.groupAddDate:last');
        var $div = $('<div/>').addClass('dateDiv');
        var con1 = ' <p style="text-align:left;width: 100%; ">新上课时间段</p>';
        var con2 = '<div class="stratEnd groupTime" style="margin: 10px 0 20px 0;position: relative;"><div class="input-group clockpicker fl" data-autoclose="true" style="width: 42%; "><input type="text" class="input-sm form-control text-center start"  placeholder="请选择开始时间" style="width: 100%;border-radius: 3px;"></div>';
        var con3 = '<span>至</span>';
        var con4 = '<div class="input-group clockpicker fl" data-autoclose="true" style="width: 42%; "><input type="text" class="input-sm form-control text-center end"  placeholder="请选择结束时间" style="width: 100%;border-radius: 3px;"></div><button style="position:absolute;right: -105px;height: 35px;border: solid 1px #ccc;text-align: center" type="button" class="btn btn-default text-center removeDom" data-remove="dateDiv">删除时间段</button>';
            $div.html(con1 + con2 + con3 + con4);
        $add.append($div);
    }
    //点击添加时间段按钮添加时间段,并改变面板内容的高度
    $('#addClassTimeQuantum').on('click',function(){
        createTimeQuantum();
        //添加时间段后调用时间插件
        $('.clockpicker').clockpicker()
        $('.wizard > .content').height($('#timeForm').outerHeight()+150);
    });
    //调用时间插件
    $('.clockpicker').clockpicker();
    //动态创建排期
    function createCourseSchedule(){
        var $class =$('<div/>').addClass('groupAddDate dateClass');
        var cont1 = '<p style="text-align:left;width: 100%; ">新课程排期</p>';
        cont1 += '<div style="position: relative;margin: 10px 0 20px 0;" class="input-daterange input-group cp stratEnd  ">';
        cont1 += '<input type="text"  class="input-sm form-control datetimeStart text-center" name="start" placeholder="起始日期"  style="width: 42%;font-size: 13px;">';
        cont1 += '<span >至</span>';
        cont1 += '<input type="text"  class="input-sm form-control datetimeEnd text-center" name="end" placeholder="结束日期" style="width: 42%;;font-size: 13px;"></div>'
        cont1 += '<p style="text-align:left;width: 100%; ">新上课时间段</p><div class="stratEnd groupTime" style="margin: 10px 0 20px 0;position: relative"><div class="input-group clockpicker fl" data-autoclose="true" style="width: 42%; "><input type="text" class="input-sm form-control text-center start"  placeholder="请选择开始时间" style="width: 100%;border-radius: 3px;"></div>';
        cont1 += '<span>至</span><div class="input-group clockpicker fl" data-autoclose="true" style="width: 42%; "><input type="text" class="input-sm form-control text-center end"  placeholder="请选择结束时间" style="width: 100%;border-radius: 3px;"></div><button style="position:absolute;right: -105px;height: 35px;border: solid 1px #ccc;text-align: center" type="button" class="btn btn-default text-center removeDom" data-remove="dateClass">删除排课期</button>';
            $class.html(cont1);
        $($class).appendTo($('#addTime'));
    }
    //点击添加排期按钮新增排期和时间段
    $('#addSchedule').on('click',function(){
        createCourseSchedule();
        // createTimeQuantum();
        //添加时间段和排期后调用插件
        //i用来记录对应的日期开始和结束
        var i ;
        $(".datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        }).on("click",function(){
            i = $(this).index('.datetimeStart');
            $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").eq(i).val());
        });
        $(".datetimeEnd").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:new Date()
        }).on("click",function(){
            $(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").eq(i).val());
        });
        $('.clockpicker').clockpicker();
        $('.wizard > .content').height($('#timeForm').outerHeight()+150);
    });
    $(document).delegate('button.removeDom','click',function(){
        var $attr = $(this).data('remove');
        $(this).parents('.'+$attr).remove();
        return false;
    });
    
});