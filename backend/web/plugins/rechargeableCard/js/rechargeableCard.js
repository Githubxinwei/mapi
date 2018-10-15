/**
 * 充值卡页面js
 */
$(function(){
   function setStyleCheckbox() {
       //（数量不限）点击单选框，输入框添加限制
       $('.inputUnlimited').on('ifChecked',function(){
           $(this).children('input').attr('disabled','disabled');
           $(this).children('input').val('');
       });
//（数量不限）点击单选框，输入框解除限制
       $('.inputUnlimited').on('ifUnchecked',function(){
           $(this).children('input').removeAttr('disabled');
       });
   }

    $('#HSClass').select2({
        // width:'100%'
    });
    $('#PTClass').select2({
        // width:'100%'
    });
    $('#BirthdayClass').select2({
        // width:'100%'
    });
    //新增课程中调用开始日期
    function starDate(){
        $(".datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        });
    }
    starDate();
    $(".datetimeStart").on("click",function(){
        $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").val());
    });
//新增课程中调用结束日期
    function endDate(){
        $(".datetimeEnd") .datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:new Date()
        })
    }
    endDate();
    $(".datetimeEnd").on("click",function(){
        $(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").val());
    });

    //时间插件启动
    $('.clockpicker').clockpicker()
        .find('input').on('change',function() {
    });

    //checkBox插件引用函数
    function iCheck(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    }
    //checkBox插件引
    iCheck();
    setStyleCheckbox();
    //第一步添加售卖场馆
    function createSellVenue(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;"><span class=""><strong class=" red">*</strong>选择场馆&emsp;&emsp;</span>';
        cont1 += '<select class="form-control cp"  ><option value="1" >大上海</option><option value="2">大卫城</option><option value="3">大学路</option></select></div>';
        cont1 += ' <div class="fl  w32"><span class=""><strong class=" red">*</strong>售卖张数&emsp;&emsp;</span><input type="number" min="0" placeholder="0张" class="form-control"></div>';
        cont1 += '<div class="fl  w32 input-daterange cp" style="position: relative;"><span class=""><strong class=" red">*</strong>售卖日期&emsp;&emsp;</span><b><input type="text" id = "" class="input-sm form-control datetimeStart" name="start" placeholder="起始日期"  style="width: 100px;text-align:left;font-size: 13px;cursor: pointer;"></b>';
        cont1 +='<b><input type="text" id="" class="input-sm form-control datetimeEnd" name="end" placeholder="结束日期" style="width: 100px;text-align: left;font-size: 13px;cursor: pointer;"></b></div></div>'
        // $(cont1).appendTo($('#sellVenue'));
    }
    $('#addSellVenue').on('click',function(){
        createSellVenue();
        iCheck();
        setHeight(0);
        setStyleCheckbox();
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
    });

    //第一步添加场馆
    function createVenue(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;"><span class=""><strong class=" red">*</strong>选择场馆&emsp;&emsp;</span><select class="form-control cp"  >';
        cont1+='<option value="1" >大上海</option><option value="2">大卫城</option><option value="3">大学路</option></select></div>';
        cont1+='<div class="fl  w32"><span class=""><strong class=" red">*</strong>每月通店限制</span><div class="clearfix cp h32" style="border: solid 1px #cfdadd;margin-left: 15px;border-radius: 3px;">';
        cont1+='<input  style="width: 130px;border: none;margin-left: 0;" type="number" min="0" placeholder="通店次数" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>'
        // $(cont1).appendTo($('#venue'));
    }
    $('#addVenue').on('click',function(){
        createVenue();
        iCheck();
        setHeight(0);
        setStyleCheckbox();
    });
    $('#addDiscount').on('click',function(){
        iCheck();
        setHeight(0);
        setStyleCheckbox();
    });
    $('#addCardValidityBtn').on('click',function(){
        setHeight(0);
    });


    // 设置每一步向导内容的高度，参数是第几步（索引从0开始）
    function setHeight(step){
        $('.wizard > .content').height($('.con1').eq(step).outerHeight()+100);
    }
    //表单向导加载完毕后计算第一步内容的高度
    setHeight(0);
    //第一步点击td团中每月固定日
    $('table td').on('click',function(){
        var $week = $('.weekSelect');
        if(!$(this).parents('table').hasClass('noneClick')){
            var check = $(this).toggleClass('bColor');
            $week.find('input').iCheck('disable');
            $week.find('input').css('left','23px').css('z-index','1000');
        }
        var $table = $('#table');
        var $dayCheck = $table.find('.bColor');
        var $sum = $dayCheck.length - 1;
        if($sum == -1){
            $week.find("input[name='weeksTime']").removeAttr('disabled').css('left', '0').css('z-index', '0');
            $week.find('div.icheckbox_square-green').removeAttr('disabled');
        }
    });
    //选择特定星期，固定日不可选
    var $input = $('.weekSelect');
    var $table = $('#table');
    $('.weekSelect').on('ifChecked',function () {
        $('.clockpicker').find("input[name='dayStart']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayStart']").attr('');
        $('.clockpicker').find("input[name='dayEnd']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayEnd']").attr('');
        $table.find('td').css('cursor','not-allowed');
        $table.addClass('noneClick');
    });
    $('.weekSelect').on('ifUnchecked',function () {
        var  $weekChecked = $input.find("div.checked");
        var  $num         = $weekChecked.length - 1;
        if($num == undefined || $num == ""){
            $('.clockpicker').find("input[name='dayStart']").removeAttr('disabled');
            $('.clockpicker').find("input[name='dayEnd']").removeAttr('disabled');
            $table.find('td').css('cursor','pointer');
            $table.removeClass('noneClick');
        }
    });

    //第一步当week被选中时显示
    //记录点击的是星期几
    var ind ;
    var btnIndex;
    //获取被点击的索引
    $('.addTime').on('click',function(){
        btnIndex =  $(this).index('.addTime');
    });
    //被选中时触发的时间
    $('.week').on('ifChecked',function(){
        ind = $(this).index('.week');
        //按钮隐藏
        $('.addTime').addClass('disNone');
        $('.addTime').eq(ind).removeClass('disNone');
    });
    //取消选中时触发的事件
    $('.week').on('ifUnchecked',function(){
        //按钮隐藏
        var i = $(this).index('.week')
        $('.addTime').addClass('disNone');
        $('.weekTime').eq(i).text('');
    });

    //点击取消
    $('.cancelTime').on('click',function(){
        $('#addTime').addClass('disNone')
    });
    //点击确定
    $('.successBtn').on('click',function(){
        if($('#weekStart').val()!='' &&$('#weekEnd').val()!=''){
            $('.weekTime').eq(ind).text($('#weekStart').val()+'--'+$('#weekEnd').val());
            $('.addTime').eq(ind).addClass('disNone');
        }else{
            // alert('时间不能为空，请重新输入');
        }
    });

    //第二步添加课程
    function createCourse(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">课程名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择课程" >请选择课程</option>';
        cont1+= '<option value="瑜伽">瑜伽</option><option value="单车">单车</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">每日数量&emsp;&emsp;</span><div class="clearfix cp h32" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;" type="text" placeholder="0节" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>'
        // $(cont1).appendTo($('.course'));
    }
    //第二步点击添加课程
    $('.addCourse').on('click',function(){
        createCourse();
        iCheck();
        setHeight(1);
        setStyleCheckbox();
    });
    //第二步添加服务
    function createServe(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">服务名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择服务" >请选择服务</option>';
        cont1+= '<option value="毛巾">毛巾</option><option value="茶水">茶水</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">每日数量&emsp;&emsp;</span><div class="clearfix cp h32" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;" type="text" placeholder="0" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>';
        // $(cont1).appendTo($('.serve'));
    }
    //第二步添加服务
    $('.addServe').on('click',function(){
        createServe();
        iCheck();
        setHeight(1);
        setStyleCheckbox();
    });

    //第二步添加shop
    function createShop(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">商品名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择商品" >请选择商品</option>';
        cont1+= '<option value="A商品">A商品</option><option value="B商品">B商品</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">商品数量&emsp;&emsp;</span><div class="clearfix cp h32" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;" type="text" placeholder="0个" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div>'
        cont1+='<div class="fl "style="margin: 26px 0 0 -60px"><span class="glyphicon glyphicon-yen"><b style="font-size: 18px;">0</b></span> 总金额</div>';
        // $(cont1).appendTo($('.shopping'));
    }
    //第二步添加商品
    $('.addShop').on('click',function(){
        createShop();
        iCheck();
        setHeight(1);
        setStyleCheckbox();
    });


    //第二步添加赠品
    function createDonation(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
            cont1+= '<span class="">商品名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择商品" >请选择商品</option>';
            cont1+= '<option value="A商品">A商品</option><option value="B商品">B商品</option></select></div>';
            cont1+= '<div class="fl  w32"><span class="">商品数量&emsp;&emsp;</span><div class="clearfix cp h32" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;" type="text" placeholder="0个" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>';
        // $(cont1).appendTo($('.donation'));
    }
    $('.addDonation').on('click',function(){
        createDonation();
        iCheck();
        setHeight(1);
        setStyleCheckbox();
    });

    //第三步添加请假函数
    function createAddLeave(){
        var cont1 = '<div class="clearfix"><div class="fl  w32"><span class="">请假次数&emsp;&emsp;</span>';
        cont1 += '<input type="number" placeholder="0次" class="form-control"></div>';
        cont1 += '<div class="fl  w32"><span class="">每次请假天数</span><input type="number" placeholder="0天" class="form-control"></div></div>'
        // $(cont1).appendTo($('.leaveDay'));
    }
    //第三步添加请假
    $('.addLeave').on('click',function(){
        createAddLeave();
        setHeight(2);
        setStyleCheckbox();
    });

    //初始显示通用合同
    $('#bargainContent>li').eq(0).css('display','block').siblings('#bargainContent>li').css('display','none');
    //点击出来对应相应的合同
    $('#bargain4 ').on('change',function(){

        var i = $('#bargain4 option').index($('#bargain4 option:selected'))
        $('#bargainContent>li').eq(i).css('display','block').siblings('#bargainContent>li').css('display','none');
        $('.wizard > .content').height($('.con1').eq(3).outerHeight()+100);
    })

})
