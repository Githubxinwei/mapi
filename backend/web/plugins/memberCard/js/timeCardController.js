/**
 * Created by Administrator on 2017/3/25.
 */

//时间卡 js文件
$(function(){
    //第一步添加售卖场馆
    function createSellVenue(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;"><span class=""><strong style="color: red;">*</strong>选择场馆&emsp;&emsp;</span>';
        cont1 += '<select class="form-control cp"  ><option value="大上海" >大上海</option><option value="大卫城">大卫城</option><option value="大学路">大学路</option></select></div>';
        cont1 += ' <div class="fl  w32"><span class=""><strong style="color: red;">*</strong>售卖张数&emsp;&emsp;</span><input type="number" name="sheets" placeholder="0张" class="form-control"></div>';
        cont1 += '<div class="fl  w32 input-daterange cp" style="position: relative;"><span class=""><strong style="color: red;">*</strong>售卖日期&emsp;&emsp;</span><b><input type="text" id = "" class="input-sm form-control datetimeStart" name="start" placeholder="起始日期"  style="width: 100px;text-align:left;font-size: 13px;cursor: pointer;"></b>';
        cont1 +='<b><input type="text" id="" class="input-sm form-control datetimeEnd" name="end" placeholder="结束日期" style="width: 100px;text-align: left;font-size: 13px;cursor: pointer;"></b></div></div>'
        // $(cont1).appendTo($('#sellVenue'));
    }

    function inputUnlimited(){
    //（数量不限）点击单选框，输入框添加限制
        $('.inputUnlimited').on('ifChecked',function(){
            $(this).children('input').attr('disabled','disabled');
            $(this).children('input').val("");
        });
    //（数量不限）点击单选框，输入框解除限制
        $('.inputUnlimited').on('ifUnchecked',function(){
            $(this).children('input').removeAttr('disabled');
        })
    }
    function checkboxLimit(){
        $('.inputUnlimited').on(function(){
              $(this).children()
        })
    }
    $('#HSClass').select2({
        width:'100%'
    });
    $('#PTClass').select2({
        width:'100%'
    });
    $('#BirthdayClass').select2({
        width:'100%'
    });

    $('#addSellVenue').on('click',function(){
        //添加时间段和排期后调用插件
        //i用来记录对应的日期开始和结束
        createSellVenue();
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
        iCheck();
        setHeight(0);
        inputUnlimited();
    });

    //第一步添加场馆
    function createVenue(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;"><span class=""><strong style="color: red;">*</strong>选择场馆&emsp;&emsp;</span><select class="form-control cp"  >';
        cont1+='<option value="大上海" >大上海</option><option value="大卫城">大卫城</option><option value="大学路">大学路</option></select></div>';
        cont1+='<div class="fl  w32 h32"><span class=""><strong style="color: red;">*</strong>每月通店限制</span><div class="clearfix cp inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;border-radius: 3px;">';
        cont1+='<input  style="width: 130px;border: none;margin-left: 0;" type="text" placeholder="通店次数" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top:4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>'
        // $(cont1).appendTo($('#venue'));
    }
    $('#addVenue').on('click',function(){
        createVenue();
        iCheck();
        setHeight(0);
        inputUnlimited();
    });
    inputUnlimited();

    $('#addDiscount').on('click',function(){
        iCheck();
        setHeight(0);
        inputUnlimited();
    });

    $('#addCardValidityBtn').on('click',function(){
        setHeight(0);
    });
    //调用开始日期
    $(".datetimeStart").datetimepicker({
        format: 'yyyy-mm-dd',
        minView:'month',
        language: 'zh-CN',
        autoclose:true,
        startDate:'2008-08-08'
    }).on("click",function(){
        $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").val());
    });
//调用结束日期
    $(".datetimeEnd").datetimepicker({
        format: 'yyyy-mm-dd',
        minView:'month',
        language: 'zh-CN',
        autoclose:true,
        startDate:new Date()
    }).on("click",function(){
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
            radioClass: 'iradio_square-green'
        });
    }
    //checkBox插件引
    iCheck();

    // 设置每一步向导内容的高度，参数是第几步（索引从0开始）
    function setHeight(step){
        $('.wizard > .content').height($('.con1').eq(step).outerHeight()+200);
    }
    //表单向导加载完毕后计算第一步内容的高度
    setHeight(0);
    $('#addCardValidityBtn').on('click',function(){
        setHeight(0);
    });

    //选择固定日，特定星期不可选
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
    $('.weekSelect').on('ifChecked', function () {
        $('.clockpicker').find("input[name='dayStart']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayStart']").attr('');
        $('.clockpicker').find("input[name='dayEnd']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayEnd']").attr('');
        $table.find('td').css('cursor','not-allowed');
        $table.addClass('noneClick');
    });
    $('.weekSelect').on('ifUnchecked', function () {
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

    //带人卡选择 - 带人
    $("#bring-true").on('click',function(){
        $("#bring-num").attr("disabled",false);
    });

    //带人卡选择 - 不带人
    $("#bring-false").on('click',function(){
        $("#bring-num").attr("disabled",true).val(0);
    });

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
        var i = $(this).index('.week');
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
        cont1+= '<span class="">课程名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="" >请选择课程</option>';
        cont1+= '<option value="1">瑜伽</option><option value="2">单车</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">每日节数&emsp;&emsp;</span><div class="clearfix cp h32 inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;"  name="times"    type="number" placeholder="0节" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>'
        // $(cont1).appendTo($('.course'));
    }
    //第二步点击添加课程
    $('.addCourse').on('click',function(){
        createCourse();
        iCheck();
        setHeight(1);
        inputUnlimited();
    });
    //第二步添加服务
    function createServe(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">服务名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="" >请选择服务</option>';
        cont1+= '<option value="毛巾">毛巾</option><option value="茶水">茶水</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">每日数量&emsp;&emsp;</span><div class="clearfix cp h32 inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;"  name="times"   type="number" placeholder="0" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>'
        // $(cont1).appendTo($('.serve'));
    }
    //第二步添加商品
    $('.addServe').on('click',function(){
        createServe();
        iCheck();
        setHeight(1);
        inputUnlimited();
    });

    //第二步添加赠品
    function createDonation(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">商品名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="" >请选择商品</option>';
        cont1+= '<option value="1">A商品</option><option value="2">B商品</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">商品数量&emsp;&emsp;</span><div class="clearfix cp h32 inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 100px;border: none;margin-left: 0;" type="number"  name="times" placeholder="0个" class="fl form-control pT0">';
        cont1+='<div class="checkbox i-checks checkbox-inline"style="top: 4px;"> <label><input type="checkbox" value=""> <i></i> 不限</label></div></div></div></div>'
        // $(cont1).appendTo($('.donation'));
    }
    $('.addDonation').on('click',function(){
        createDonation();
        iCheck();
        setHeight(1);
        inputUnlimited();
    });

    //第三步添加请假函数
    function createAddLeave(){
        var cont1 = '<div class="clearfix"><div class="fl  w32"><span class="">请假次数&emsp;&emsp;</span>';
        cont1 += '<input type="text" placeholder="0次" class="form-control"></div>';
        cont1 += '<div class="fl  w32"><span class="">每次请假天数</span><input type="text" placeholder="0天" class="form-control"></div></div>'
        // $(cont1).appendTo($('.leaveDay'));
    }
    //第三步添加请假
    $('.addLeave').on('click',function(){
        createAddLeave();
        setHeight(2);
    });

    //初始显示通用合同
    $('#bargainContent>li').eq(0).css('display','block').siblings('#bargainContent>li').css('display','none');
    //点击出来对应相应的合同
    $('#bargain4 ').on('change',function(){

        var i = $('#bargain4 option').index($('#bargain4 option:selected'))
        $('#bargainContent>li').eq(i).css('display','block').siblings('#bargainContent>li').css('display','none');
        $('.wizard > .content').height($('.con1').eq(3).outerHeight()+100);
    })
});

var app = angular.module('App');
app.controller('timeCardController',function($scope,$rootScope,$http,$timeout,Upload) {

    $.loading.show();                                       //打开页面
    //初始化定义初始值
    $scope.init = function () {
        $scope.renewUnit = '30';
        $scope.venueIds      = [];     //售卖场馆id
        $scope.sheets        = [];     //售卖张数
        $scope.saleStart     = [];     //售卖开始时间
        $scope.saleEnd       = [];     //售卖结束时间
        $scope.applyVenueId  = [];     //通用场馆id
        $scope.applyTimes    = [];     //售卖张数限制
        $scope.applyGrade    = [];     //通店卡的等级
        $scope.openShopWeek  = [];     //每周通店次数
        $scope.applyStart    = [];     //通店开始时间
        $scope.applyEnd      = [];     //通店结束时间
        $scope.bring         = 0;      //带人卡 0代表不带人
        $scope.OrdinaryRenewal = '';// 普通续费价格
        $scope.day           = [];     //按天限制
        $scope.week          = [];     //按周限制
        $scope.num           = 0;
        $scope.venueHttp     = [];
        $scope.venueArr      = [];
        $scope.applyHttp     = [];
        $scope.classHttp     = [];
        $scope.serverHttp    = [];
        $scope.shopHttp      = [];
        $scope.donationHttp  = [];
        $scope.weekTimeLimit = [];       // 周时间点限制
        $scope.unit          = true;
        $scope.activeUnit    ="1";       //激活初始单位为1
        $scope.durationUnit  ="1";       //有效期单位为1
        $scope.pact          = true;    //合同默认选中状态
        $scope.deal          ="1";       //默认合同初始值
        // $scope.venueApply();
        $scope.getCardTheVenue();        //获取卡种所属场馆
        $scope.venue();                 //初始化场馆加载
        $scope.groupCourse();           //获取所有团课信息
        $scope.getServerOptions();      //获取服务信息
        $scope.getShopOptions();        //获取赠品所有信息
        $scope.getDonationOptions();    //获取赠品所有信息
        $scope.addVenueHtml();         //添加场馆模板
        $scope.addApplyHtml();          //添加适用场馆模板
        $scope.getCardAttr();
        // $scope.getDeal();
        $scope.addCardValidityHtml();   //初始化添加有效期模板
        $scope.getGiveCourseData();     //获取所有私课课程
        $scope.leaveNums   = "";
        $scope.everyLeaveDays = "";
        $scope.winterNum   = "";        //初始化寒假天数
        $scope.summerNum = "";          //初始化暑假天数
        $scope.classKey    = "";
        $scope.serverKey   = "";
        $scope.shopKey     = "";
        $scope.donationKey = "";
        $scope.leaveNumsFlag = false;
        $scope.leaveDaysFlag = false;
        $scope.addClassHtml();
        $scope.addServerHtml();
        $scope.addDonationHtml();
        $scope.addLeaveHtml();
        // $scope.groupCourse();
        // $scope.getServerOptions();
        // $scope.getShopOptions();
        // $scope.getDonationOptions();
        setTimeout("$.loading.hide()",1000);                    //隐藏页面
    };
    //获取卡种所属场馆
    $scope.getCardTheVenue = function(){
        $http.get('/member-card/get-venue-data-by-id').then(function(response){
            // console.log('response所属场馆',response);
            var len = response.data.data.length;
            if(len !=0 ){
                $scope.cardTheVenueListsFlag = true;
                $scope.cardTheVenueLists = response.data.data;
            }else{
                $scope.cardTheVenueListsFlag = false;
                $scope.cardTheVenueLists = response.data.data;
            }
        });
    }


    //带人卡不带人卡
    $scope.withoutHuman = function(){
        $scope.bring         = 0;
    }

    $scope.withPeople = function(){
        $scope.bring         = 1;
    }

//判断卡种名称是否存在
    $scope.setCardName = function () {
        $http.get('/member-card/set-card-name?name='+$scope.cardName +'&venueId=' + $scope.cardTheVenueId).success(function (result) {
            $scope.status = result.status;
        });
    };
    $scope.applyTypeDefaultSelectFlag = '';
    $scope.applyType = '';
    //根据选择场馆判断名称是否重复
    $scope.cardTheVenue = function(){
        $scope.applyTypeDefaultSelectFlag = '';
        $scope.applyType = '';
        $scope.setCardName();
        var $type = $('.cardTheVenueDefault').find('option:selected').data('type');
        if($type != undefined && $type != ''){
            $scope.applyType = $type;
            $scope.applyTypeDefaultSelectFlag = $type;

            $scope.selectApplyVenueTypeOne($type,$scope.cardTheVenueId);
            $timeout(function(){
                var $len =$('#applyTypeDefaultSelect').find('option').length;
                $('#applyTypeDefaultSelect').find('option:first').remove();
            },100)
        }
    }
//获取卡种的属性
    $scope.getCardAttr   = function () {
        $http.get('/member-card/get-card-attributes').then(function (result) {
            if(result.data.attributes != undefined && result.data.attributes != ""){
                $scope.attributesVal    = result.data.attributes;
                $scope.attributesStatus = true;
            }else{
                $scope.attributesVal    = '暂无数据';
                $scope.attributesStatus = false;
            }
        });
    };
    //获取所有场馆数据加载
    $scope.venue     = function(){
        $http.get('/rechargeable-card-ctrl/get-venue?status=card').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionVenue = result.data.venue;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue = '暂无数据';
                $scope.venueStatus = false;
            }
        })
    };

    //根据场馆等级获取场馆
    $scope.selectApplyVenueType = function(id){
        var typeId = id;
        if(id !=''){
            if(typeId == 1){
                $http.get('/rechargeable-card-ctrl/get-venue?status=card&type='+ id).then(function(response){
                    // console.log('普通获取场馆',response);
                        $scope.applyTypeVenueLists1 = response.data.venue;
                        $scope.applyVenueLists123 = response.data.venue;
                });
            }else if(typeId == 2){
                $http.get('/rechargeable-card-ctrl/get-venue?status=card&type='+ id).then(function(response){
                    // console.log('尊爵获取场馆',response);
                        $scope.applyTypeVenueLists2 = response.data.venue;
                        $scope.applyVenueLists123 = response.data.venue;
                });
            }
        }
    }
    //根据场馆等级获取场馆
    $scope.selectApplyVenueTypeOne = function(id,venueId){
        var typeId = id;
        $scope.allVenueApplyArr = [];
        if(id !=''){
            if(typeId == 1){
                $http.get('/rechargeable-card-ctrl/get-venue?status=card&type='+ id).then(function(response){
                    // console.log('普通获取场馆',response);
                    $scope.applyTypeVenueLists1 = response.data.venue;
                    $scope.applyVenueLists123 = response.data.venue;
                    $scope.applyTypeVenueLists1.forEach(function(item,index){
                        $scope.allVenueApplyArr.push(item.id);
                    });
                    $timeout(function(){
                        var $defaultId = [];
                        if($scope.allVenueApplyArr.indexOf(venueId) != -1){
                            $defaultId.push(venueId);
                            $('#selectApplyOne').select2();
                            var data = angular.fromJson($defaultId)
                            $('#selectApplyOne').val(data).trigger("change")
                        }
                    },100)
                });
            }else if(typeId == 2){
                $http.get('/rechargeable-card-ctrl/get-venue?status=card&type='+ id).then(function(response){
                    // console.log('尊爵获取场馆',response);
                    $scope.applyTypeVenueLists2 = response.data.venue;
                    $scope.applyVenueLists123 = response.data.venue;
                    $scope.applyTypeVenueLists2.forEach(function(item,index){
                        $scope.allVenueApplyArr.push(item.id);
                    });
                    $timeout(function(){
                        var $defaultId = [];
                        if($scope.allVenueApplyArr.indexOf(venueId) != -1){
                            $defaultId.push(venueId);
                            $('#selectApplyOne').select2();
                            var data = angular.fromJson($defaultId)
                            $('#selectApplyOne').val(data).trigger("change")
                        }
                    },100)
                });
            }

        }
    }

    //获取所有场馆数据加载
    $scope.venueApply     = function(){
        $http.get('/rechargeable-card-ctrl/get-venue?status=card').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionApply = result.data.venue;
                $scope.applyStauts = true;
                $timeout(function(){
                    $('.applySelectVenue').select2();
                },1)
            }else{
                $scope.optionApply = '暂无数据';
                $scope.applyStauts = false;
            }
        })
    };

    //获取所有课种信息
    $scope.groupCourse  = function(){
        $http.get('/new-league/top-course').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionClass = result.data;
                $scope.classStatus = true;
            }else{
                $scope.optionClass = '暂无数据';
                $scope.classStatus = false;
            }
        });
    };
    //获取服务信息
    $rootScope.getServerOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-server-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.myService = true;
                $scope.serverStatus = true;
                $scope.optionServer = result.data.venue;
            }else{
                $scope.serverStatus = true;
                $scope.optionServer = '暂无数据';
                $scope.myService = false;
            }
        });
    };
    //获取商品信息
    $scope.getShopOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-shopping-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionShop = result.data.venue;
                $scope.shopStatus   = true;
                $scope.shopShow     = true;
            }else{
                $scope.optionShop   = '暂无数据';
                $scope.shopStatus   = false;
                $scope.shopShow     = false;
            }
        });
    };

    //获取赠送商品信息
    $scope.getDonationOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            if(result.data.goods != undefined && result.data.goods != ""){
                $scope.optionDonation = result.data.goods;
                $scope.donationStatus       = true;
                $scope.myDonation         = true;
            }else{
                $scope.optionDonation       = '暂无数据';
                $scope.donationStatus       = false;
                $scope.myDonation         = false;
            }
        });
    };
//获取合同信息
    $scope.getDeal= function () {
        $http.get('/contract/get-deal?type=1').then(function (result) {
            // if(result.data.deal != undefined && result.data.deal != ""){
                $scope.dealData   = result.data;
                // $scope.dealStauts = true;
            // }else{
                // $scope.dealData   = '暂无数据';
                // $scope.dealStauts = false;
            
            // }
        });
    };
    //添加模板时调用icheck
    $scope.getICheck = function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    }

    $scope.setStyleCheckbox = function() {
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

//获取合同内容
    $scope.getDealId = function (data) {
        $scope.dealIds = data;
        $http.get('/contract/get-deal-one?id='+$scope.dealIds).then(function (result) {
            $scope.dataDeal = result.data.data;
        });
    };
    //添加有效期模板
    $scope.addCardValidityHtml = function(){
        $scope.htmlAttr = 'addNewValidRenew';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.addNewRenewHtml = result.data.html;
        });
    }



    //添加售卖模板
    $scope.addVenueHtml  = function () {
        $scope.htmlAttr = 'saleVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.saleHtml = result.data.html;
            $scope.addDiscountHtml();
        });
    };

    //添加售卖折扣模板
    $scope.addDiscountHtml  = function () {
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.htmlAttr = 'discount';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.discountHtml = result.data.html;
        });
    };
    //添加通店模板addVenueHtml
    $scope.addApplyHtml = function () {
        //时间插件启动
        $('.clockpicker').clockpicker()
            .find('input').on('change',function() {
        });
        $('.applySelectVenue').select2();
        $scope.htmlAttr = 'applyVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.applyHtml = result.data.html;
        });
        $('.applySelectVenue').select2();
    };
    //添加课程模板
    $scope.addClassHtml = function () {
        $scope.htmlAttr = 'class';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.classHtml = result.data.html;
        });
        $('.leagueCourseList').select2();
    };
    //添加服务模板
    $scope.addServerHtml = function () {
        $scope.htmlAttr = 'server';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.serverHtml = result.data.html;
        });
    };
    //添加赠品模板
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'donation';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.donationHtml = result.data.html;
        });
    };

    //添加假期模板
    $scope.addLeaveHtml = function () {
        $scope.htmlAttr = 'leaveVenue';
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr).then(function (result) {
            $scope.leaveHtml = result.data.html;
        });
    };
    // //场馆改变触发事件
    $scope.selectVenue     = function (id) {
        if(id && id != undefined) {
            $scope.venueHttp.push(id);
        }
        $scope.getVenue();
        if($scope.venueIds != undefined && $scope.venueIds.length){
            $scope.venueHttp = $.array.arrayIntersection($scope.venueHttp,$scope.venueIds);
        }
    };
    //场馆改变触发事件
    $scope.selectApply     = function (id) {
        var $len = id.length ;
        if($len <= $scope.selectApplyClickLen && $scope.selectApplyClickLen != undefined){
            
        }
        if(id && id != undefined) {
            $scope.applyHttp.push(id);
        }
        $scope.getVenueApply();
        if($scope.applyVenueId != undefined && $scope.applyVenueId.length){
            $scope.applyHttp = $.array.arrayIntersection($scope.applyHttp,$scope.applyVenueId);
        }
    };

    $scope.selectApplyClick123 = function(id){
        $("body>.select2-container--default").css('display','block');
        // console.log($scope.selectApplyClickLen)
        if(id != undefined){
            $scope.selectApplyClickLen = id.length;
            // console.log($scope.selectApplyClickLen)
        }

    }


    //获取赠送的私课课程
    $scope.getGiveCourseData = function(){
        $http.get('/user/get-course?courseType=5').then(function(response){
            if(response.data.data.length != 0){
                $scope.GiveCourseDataLists = response.data.data;
                $scope.GiveCourseFlag    = true;
            }else{
                $scope.GiveCourseDataLists = response.data.data;
                $scope.GiveCourseFlag    = false;
            }
        });
    }

    //课程改变触发事件
    $scope.selectClass     = function (id) {
        if(id && id != undefined) {
            $scope.classHttp.push(id);
        }
        $scope.classServer();
        if($scope.classId != undefined && $scope.classId.length){
            $scope.classHttp = $.array.arrayIntersection($scope.classHttp,$scope.classId);
        }
        $scope.groupCourse();
    };
    //服务改变触发事件
    $scope.selectServer     = function (id) {
        if(id && id != undefined) {
            $scope.serverHttp.push(id);
        }
        $scope.serverCombo();
        if($scope.serverId != undefined && $scope.serverId.length){
            $scope.serverHttp = $.array.arrayIntersection($scope.serverHttp,$scope.serverId);
        }
    };
    //赠品改变触发事件
    $scope.selectDonation     = function (id) {
        $scope.donationHttp.push(id);
        $scope.donationGoods();
        if($scope.donationId != undefined && $scope.donationId.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.donationId);
        }
    };


    $scope.applyVenue = function () {
        $scope.applyVenueId = [];
        $scope.applyStart = [];
        $scope.applyEnd = [];
        $scope.applyTimes   = [];
        $scope.applyGrade = [];
        $scope.applyVenueTypeArr = [];//场馆类型
        $scope.venueIsArr = [];//通店场馆类型1、单个2、多个
        $scope.venueListsArr = [];//所有通店场馆
        $scope.generalVenuesNoRepeat = [];//普通场馆选择数组，判断去重
        $scope.extrawellVenuesNoRepeat = [];//尊爵场馆选择数组，判断去重
        // $scope.openShopWeek = [];
        $scope.openShopWeekChecked = [];
        $scope.aboutLimit = [];
    };

    $scope.init();
    //公共删除ID
    $scope.commonRemoveId = function (id,data) {
        data.splice($.inArray(id,data), 1);
        return data;
    };
    //删除元素
    $scope.removeVenueId = function (id,attr) {
        if(id != undefined){
            if(attr == 'venue'){
                $scope.venueHttp = $scope.commonRemoveId(id,$scope.venueHttp);
            }else if (attr == 'apply'){
                $scope.applyHttp = $scope.commonRemoveId(id,$scope.applyHttp);
            }else if (attr == 'class'){
                $scope.classHttp = $scope.commonRemoveId(id,$scope.classHttp);
            }else if (attr == 'server'){
                $scope.serverHttp = $scope.commonRemoveId(id,$scope.serverHttp);
            }else if (attr == 'shop'){
                $scope.shopHttp = $scope.commonRemoveId(id,$scope.shopHttp);
            }else if (attr == 'donation'){
                $scope.donationHttp = $scope.commonRemoveId(id,$scope.donationHttp);
            }else if(attr == 'addValid'){

            }else if(attr == 'discount'){

            }
        }
    };

    //获取所有的有效期续费
    $scope.getAllValidRenew = function(){
        $scope.AllValidRenewArr = [];
        var $AllValidRenewDiv = $('.cardValidity123').children('.cardValidityBox');
        $AllValidRenewDiv.each(function(index,item){
            var $validNum = $(this).find('input[name="cardValidityNum"]').val();
            var $validCompany = $(this).find('.cardValidityCompany').val();
            var $validMoney  = $(this).find('input[name="cardValidityMoney"]').val();

            if($validNum != ''&& $validMoney != ''&& $validCompany != ''){
                var data ={
                    day :$validNum,
                    type:$validCompany,
                    price:$validMoney
                }
                $scope.AllValidRenewArr.push(data);
            }
        });
    }

    // 获取所有销售场馆
    $scope.getVenue = function () {
        var select = $('div#sellVenue');
        var div    = select.find('div.clearfix');
        $scope.venueIds  = [];
        $scope.sheets    = [];
        $scope.saleStart = [];
        $scope.saleEnd   = [];
        $scope.discountArr = [];

        div.each(function (i) {
            $scope.discountNumArr = [];
            var   $venueIds  =$(this).find('option:selected').val();
            if($venueIds != undefined && $venueIds != ""){
                $scope.venueIds.push($venueIds);

                var   $checked    = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var   $sheets     =  ($(this).find("input[name='sheets']").val());
                var $discountBox  = $(this).children('.discountLists').children('.discountBox');//获取折扣box
                if($checked || $sheets ){
                    if($sheets){
                        $scope.sheets.push($sheets);
                    }else{
                        $scope.sheets.push(-1);
                    }
                }else{
                    $scope.sheets.push($sheets);
                }
                var   $saleStart =$(this).find("input[name='start']").val();
                var   $saleEnd   =$(this).find("input[name='end']").val();
                $scope.saleStart.push($saleStart);
                $scope.saleEnd.push($saleEnd);
                $discountBox.each(function(i){
                    var $discount = $(this).find('input[name="discount"]').val();//获取打折
                    var $discountCardNum = $(this).find('input[name="discountNum"]').val();//获取打card张数
                    var $discountChecked = $(this).find('div.icheckbox_square-green').hasClass('checked');//是否不限
                    if($discountChecked && $discount != ''){
                        var $discountData = {
                            discount:$discount,
                            surplus :-1
                        }
                        $scope.discountNumArr.push($discountData);
                    }
                    if($discount != '' && $discountCardNum != ''){
                        var $discountData = {
                            discount:$discount,
                            surplus :$discountCardNum
                        }
                        $scope.discountNumArr.push($discountData);
                    }
                })
                $scope.discountArr.push($scope.discountNumArr);
            }
        });
    };
    //获取所有适用场馆
    $scope.getVenueApply = function () {
        var div = $('div#venue');
        var apply = div.children('div.clearfix');
        $scope.applyVenue();
        if ($scope.commonProof(apply)) {
            apply.each(function (i) {
                // $scope.generalVenuesNoRepeat = [];//普通场馆选择数组，判断去重
                // $scope.extrawellVenuesNoRepeat = [];//尊爵场馆选择数组，判断去重
                var $applyVenue = $(this).children(".pRelative").find('option:selected').val();
                var $applyStart =$(this).find("input[name='applyStart']").val();
                var $applyEnd   =$(this).find("input[name='applyEnd']").val();
                var $checked    = $(this).children(".month").find('div.icheckbox_square-green').hasClass('checked');
                var $applyTime  = $(this).find("input[name='times']").val();
                var $applyGrade = $(this).children(".times1").find('option:selected').val();
                var $applyVenueType = $(this).find("select[name='applyVenueType']").val();
                var $applyVenue123 = $(this).find('.applySelectVenue').val();
                var $resList =    $(this).find('.applySelectVenue').select2("data");
                var $aboutLimit = $(this).children(".about").find('div.icheckbox_square-green').hasClass('checked');
                $scope.everyLen  = $resList.length;
                $scope.everClassArr  =[];
                $resList.forEach(function(item,index,arr){
                    $scope.everClassArr.push(item.id);
                    if($applyVenueType == 1){
                        $scope.generalVenuesNoRepeat.push(item.id);
                        if((index + 1) == $scope.everyLen){
                            $scope.venueListsArr.push($scope.everClassArr);
                            $scope.selectApplyVenueType($applyVenueType);
                        }
                    }
                    if($applyVenueType == 2){
                        $scope.extrawellVenuesNoRepeat.push(item.id);
                        if((index + 1) == $scope.everyLen){
                            $scope.venueListsArr.push($scope.everClassArr);
                            $scope.selectApplyVenueType($applyVenueType);
                        }
                    }
                });
                $scope.applyVenue123 = $applyVenue123;
                var week    = $(this).children(".month").find('select[name="weeks"]').val();
                // $scope.openShopWeekChecked.push(week);
                // var $openShopWeek = $(this).children(".month2").find('div.icheckbox_square-green').hasClass('checked');
                // var $openShopWeekChecked  = $(this).find("input[name='weeks']").val();
                // $scope.applyGrade.push($applyGrade)
                //     $scope.applyVenueId.push($applyVenue);
                //     if($checked || $applyTime){
                //         if($applyTime){
                //             $scope.applyTimes.push($applyTime);
                //         }else{
                //             $scope.applyTimes.push(-1);
                //         }
                //     }else{
                //         $scope.applyTimes.push($applyTime);
                //     }

                if($scope.everyLen != ''&& $scope.everyLen>=1 && $scope.everyLen != null && $scope.everyLen != undefined && ($applyTime !=''||$checked)){
                    $scope.applyVenueTypeArr.push($applyVenueType);
                    $scope.applyGrade.push($applyGrade);
                    $scope.applyVenueId.push($applyVenue);
                    $scope.applyStart.push($applyStart);
                    $scope.applyEnd.push($applyEnd);
                    $scope.openShopWeekChecked.push(week);
                    if($checked || $applyTime){
                        if($applyTime){
                            $scope.applyTimes.push($applyTime);
                        }else{
                            $scope.applyTimes.push(-1);
                        }
                    }else{
                        $scope.applyTimes.push($applyTime);
                    }
                    if($scope.everyLen ==1){
                        $scope.venueIsArr.push(1)
                    }else if($scope.everyLen >=1){
                        $scope.venueIsArr.push(2)
                    }
                    if($aboutLimit){
                        $scope.aboutLimit.push(-1);
                    }else{
                        $scope.aboutLimit.push(1);
                    }
                }
                // if($openShopWeek || $openShopWeekChecked){
                //     if($openShopWeekChecked){
                //         $scope.openShopWeekChecked.push($openShopWeekChecked);
                //     }else{
                //         $scope.openShopWeekChecked.push(-1);
                //     }
                // }else{
                //     $scope.openShopWeekChecked.push($openShopWeekChecked);
                // }
               //反向判断
               //  if($checked || $applyTime){
               //      if($checked){
               //          $scope.applyTimes.push(-1);
               //      }else{
               //          $scope.applyTimes.push($applyTime);
               //      }
               //      if($applyVenue){
               //          $scope.applyVenueId.push($applyVenue);
               //      }else{
               //          $scope.applyVenue();
               //          return false;
               //      }
               //  }
            });
        }else{
            $scope.applyVenue();
        }
    };

    //获取通电店场馆
    $scope.getApplyAll123 = function(){
        $scope.getVenueApply();
        console.log('$scope.applyVenueTypeArr',$scope.applyVenueTypeArr);
        // console.log('$scope.applyVenue123',$scope.applyVenue123);
        console.log('$scope.venueIsArr',$scope.venueIsArr);
        console.log('$scope.venueListsArr',$scope.venueListsArr);
        console.log('$scope.openShopWeekChecked',$scope.openShopWeekChecked);
        console.log('$scope.applyGrade',$scope.applyGrade);
        console.log('$scope.applyTimes',$scope.applyTimes);
        console.log('$scope.generalVenuesNoRepeat',$scope.generalVenuesNoRepeat);
        console.log('$scope.extrawellVenuesNoRepeat',$scope.extrawellVenuesNoRepeat);
        $scope.getOneData();
        console.log($scope.getOneData())
    }
    //进馆时间限制（按天限制）
    $scope.getDays  = function () {
        $scope.day = [];
        var table   = angular.element(document.getElementById('table'));
        var td      = table.find('td.bColor');
        if(td.length != undefined && td.length != 0){
            td.each(function (i){
                $scope.day.push(parseInt(td[i].innerHTML));
            })
        }else{
            $scope.day = [];
        }
    };
    //进入场馆限制（按星时间限制）
    $scope.getWeek = function () {
        $scope.weekTimeLimit =[];
        var li    = $('ul.weekSelect');
        var input = li.find('input:checked');
        if(input.length != undefined && input.length != 0){
            input.each(function (i) {
                $scope.week.push(parseInt($(this).val()));
                $scope.weekTimeLimit.push($(this).parents('div.week').next().html());
            })
        }
    };

    //上传图片大小判断
    $scope.setCover = function (file) {
        if(file){
            if(file.size > 2000000){
                Message.warning('图片太大');
            }else{
                $scope.setPic(file);
            }
        }
    };
    //图片上传方法
    $scope.setPic = function (file) {
        Upload.upload({
            url    : '/private-teach/upload',
            method : 'POST',
            data   : {UploadForm:{imageFile:file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result){
            if(result.data.status == 'success'){
                $scope.pic  = result.data.imgLink;
            }else{
                Message.warning(result.data.data);
            }
        })
    };

    //云运动 - 前台 - 卡种信息添加(第一步表单数据接收)
    $scope.getOneData = function () {
        $scope.getAllValidRenew();
        $scope.getVenue();
        $scope.getVenueApply();
        $scope.getWeek();
        $scope.getDays();
        return  {
            //  1.卡的属性（卡的属性）
            attributes     : $scope.attributes,         //卡种属性
            cardName       : $scope.cardName,           //卡种名字
            anotherName    : $scope.anotherName,        //卡种别名
            activeTime     : $scope.activeTime,         //卡激活数据
            activeUnit     : $scope.activeUnit,         //卡激活单位
            duration       : $scope.duration,           //卡的有效数据
            durationUnit   : $scope.durationUnit,       //卡的有效单位
            originalPrice  : $scope.originalPrice,      //一口原价
            sellPrice      : $scope.sellPrice,          //一口售价
            level          : $scope.applyGrade,         //卡等级
            single         : $scope.Singular,           //单数
            bring          : $scope.bring,              //带人卡
            pic            : $scope.pic,                //图片
            applyType      : $scope.openShopWeekChecked,
            cardType       : $scope.cardType,//卡的类型
            venueId        : $scope.cardTheVenueId,//卡种所属场馆
            // 2.定价和售卖（一口价和区域价二选择一）
            areaMinPrice   : $scope.areaMinPrice,       //区域最低价
            areaMaxPrice   : $scope.areaMaxPrice,       //区域最高价
            renewPrice     : $scope.renewPrice,        //续费价
            offerPrice     : $scope.offerPrice,        //优惠价
            appSellPrice   : $scope.appSellPrice,      //移动端售价
            renewUnit      : $scope.renewUnit,    //续费多长时间
            ordinaryRenewal:$scope.OrdinaryRenewal,  //普通续费
            validityRenewal:$scope.AllValidRenewArr, //有效期续费数组
            // 3.售卖场馆（数组，里边值一一对应）
            venueIds       : $scope.venueIds ,          //获取所有售卖场馆id
            sheets         : $scope.sheets,             //获取指定场馆张数
            saleStart      : $scope.saleStart,          //指定场馆售卖开始时间
            saleEnd        : $scope.saleEnd,            //指定场馆售卖结束时间
            discount       : $scope.discountArr,        //售卖折扣
            //4..通用场馆限制(数组，里边值一一对应)
            applyVenueId   : $scope.venueListsArr,       //通用场馆id
            applyTimes     : $scope.applyTimes,         //场馆通店次数
            venueType      : $scope.applyVenueTypeArr,  //场馆通店场馆类型
            venueIsArr     :$scope.venueIsArr,          //通店场馆选择类型1、单个 2、多个
            applyStart     : $scope.applyStart,         //通店开始时间
            applyEnd       : $scope.applyEnd,           //通店结束时间
            aboutLimit     : $scope.aboutLimit,         //预约团课设置
           //5.进馆时间限制（范围中）
            day            : $scope.day,                //卡种月中 对 几号的限制
            dayStart       : $scope.dayStart,           //按月的时间点开始
            dayEnd         : $scope.dayEnd,             //按月的时间点结束
            //5-1.进馆时间限制（范围小）
            week           : $scope.week,               //卡种周限制
            weekTimeLimit  : $scope.weekTimeLimit,      //卡种周种 时间点限制
            //5-2.进馆限制（范围大）
            start          : $scope.start,              //卡的开始时间段
            end            : $scope.end,                //卡的结束时间
            _csrf_backend  : $('#_csrf').val()
        }
    };
    $scope.withPeopleNum = function(){
        var $peopleNum = $('#bring-num').val();
        if(parseInt($peopleNum) >=5){
            $scope.bring = 5;
        }
    }
    //区域价不可填写
    $scope.setDisabled = function () {
        if($scope.originalPrice != undefined || $scope.sellPrice != undefined){
            $scope.unable = true;
        }
        if($scope.originalPrice == undefined && $scope.sellPrice == undefined){
            $scope.unable = false;
        }
    };
    //一口价不可填写
    $scope.setUnable = function () {
        if($scope.areaMinPrice != undefined || $scope.areaMaxPrice != undefined){
            $scope.disabled = true;
        }else{
            $scope.disabled = false;
        }
    };

    //特定星期不可填写
    $scope.setDayTime = function () {
        var $weekSelect = $('.weekSelect');
        var $table = $('#table');
        var $dayCheck = $table.find('.bColor');
        var $sum = $dayCheck.length - 1;
        if($scope.dayStart != undefined || $scope.dayEnd != undefined){
            $weekSelect.find('input').iCheck('disable',true);
            $weekSelect.find('input').css('left','23px').css('z-index','1000');
        }
        if(($scope.dayStart == "" || $scope.dayStart == undefined) && ($scope.dayEnd == "" || $scope.dayEnd == undefined) && $sum == -1){
            $weekSelect.find("input[name='weeksTime']").removeAttr('disabled').css('left','0').css('z-index','0');
            $weekSelect.find('div.icheckbox_square-green').removeAttr('disabled');
        }
    };

    //定义公共验证方法
    $scope.commonRule = function (attr,text) {
        if(!attr||attr==""){Message.warning(text);return false;}return true;
    };
    //售卖场馆判断
    $scope.venueRule =function(venueId,sheets,saleStart,saleEnd,text){
        if(venueId.length==0||sheets.length==0||saleStart.length==0||saleEnd.length==0){
            Message.warning(text);return false;
        }
        return true;
    };
    //使用场馆判断
    $scope.applyVenueRules = function(applyVenueId,applyTimes,text){
        if(applyVenueId != undefined){
            if(applyVenueId.length==0||applyTimes.length==0){
                Message.warning(text);return false;
            }
        }
        return true;
    };
//定义售卖场馆验证方法（attr数据数组，text提示内容）
    $scope.BlendSellVenueRule          = function (attr,text) {
        var len = attr.length;
        for(var i=0;i<len;i++){
            if((!attr[i] || attr[i]=="")){
                $scope.commonRule('',text);return false;
            }
        }
        return true;
    };
    //第一步：前台表单数据验证
    $scope.oneRules = function (data) {
        if($scope.cardTheVenueId == '' || $scope.cardTheVenueId == null ||$scope.cardTheVenueId == undefined){
            Message.warning("请选择卡种所属场馆");
            return;
        }
        if(!$scope.commonRule(data.attributes,'请选择卡种属性')){ return false; }
        if(!$scope.commonRule(data.cardName,'卡种名称不能为空')){ return false; }
        // if($("#Singular").val() == '' || $("#Singular").val()  == undefined || $("#Singular").val()   == null ){
        //     Message.warning("单数不能为空");
        //     return;
        // }
        if(!$scope.commonRule(data.duration,'有效时间不能为空')){ return false; }
        if(!$scope.commonRule(data.durationUnit,'有效期单位不能为空')){ return false; }
        if($scope.status == 'error'){ Message.warning('卡名称已经存在'); return false; }
        // if(data.areaMinPrice){
        //     if (data.originalPrice || data.sellPrice){
        //         $scope.commonRule('','请填写一口价或者区间价');return false;
        //     }
        // }
        if($scope.cardType == '' || $scope.cardType == undefined){
            Message.warning("请选择卡的类型");
            return;
        }
        if($('#areaMinPrice1s').val() == '' && $('#areaMinPrice2s').val() == '' && $('#areaMinPrice1').val() == ''&& $('#areaMinPrice2').val() == '' ){
            Message.warning('请输入一口价或区域定价');
            return;
        }
        if($('#areaMinPrice1s').val() == '' && $('#areaMinPrice2s').val() == '' &&($('#areaMinPrice1').val() == ''||$('#areaMinPrice2').val() == '' )){
            Message.warning('请输入完整的原价和或售价');
            return;
        }
        if(($('#areaMinPrice1s').val() == '' || $('#areaMinPrice2s').val()) == '' &&$('#areaMinPrice1').val() == ''&& $('#areaMinPrice2').val() == '' ){
            Message.warning('请输入完整的区域定价');
            return;
        }
        if($scope.areaMinPrice != '' && $scope.areaMaxPrice != ''){
            if($scope.areaMaxPrice < $scope.areaMinPrice){
                Message.warning('最高价不能低于最低价');
                return;
            }
        }
        // if(!data.areaMinPrice){
        //     if (!data.originalPrice || !data.sellPrice){
        //         $scope.commonRule('','请填写一口价或者区间价');return false;
        //     }
        // }
        if($scope.bring == undefined || $scope.bring == null){
            Message.warning("请输入可带人数量");
            return;
        }
        if($('#OrdinaryRenewal').val() == ''){
            Message.warning("普通续费不能为空");
            return;
        }
        if($scope.venueIds.length == 0){
            Message.warning('请选择售卖场馆');
            return;
        }

        // if(data.areaMinPrice == '' || data.areaMinPrice == undefined || data.areaMinPrice == null){ Message.warning('请填写一口价或者区间价'); return false;}
        // if(!$scope.venueRule(data.venueIds,data.sheets,data.saleStart,data.saleEnd,'请将售卖信息填写完整')){
        //     return false;
        // }
        // if(!$scope.applyVenueRules(data.applyVenueId,data.applyTimes,'通用场馆或通店次数不能为空')){ return false; }
        if(!$scope.BlendSellVenueRule(data.venueIds,'请选择售卖场馆'))
        {
            return false;
        }
        if(!$scope.BlendSellVenueRule(data.sheets,'请填写售卖张数'))
        {
            return false;
        }
        if(!$scope.BlendSellVenueRule(data.saleStart,'请填写售卖开始日期'))
        {
            return false;
        }
        if(!$scope.BlendSellVenueRule(data.saleEnd,'请填写售卖结束日期'))
        {
            return false;
        }
        // if(!$scope.BlendSellVenueRule(data.applyVenueId,'请选择通店场馆')){
        //     return false;
        // }
        // if(!$scope.BlendSellVenueRule(data.applyTimes,'请填写通店次数')){
        //     return false;
        // }
        // if(data.applyStart != '' && data.applyEnd != ''){
        //     if(!$scope.getTimeFindTime(data.applyStart,data.applyEnd)){
        //         if(!$scope.commonRule('','通用场馆进馆时间的结束时间点不正确')){ return false;}
        //     }
        // }
        if(!$scope.getTimeFindTime(data.dayStart,data.dayEnd)){
            if(!$scope.getTimeDayStatus(data.day)){
                if(!$scope.commonRule('','请先选择固定日')){ return false;}
            }else{
                if(!$scope.commonRule('','特定天的结束时间点不正确')){ return false;}
            }
        }
        if(!$scope.getTimeFindTime(data.start,data.end)){
            if(!$scope.commonRule('','结束时间点不正确')){ return false;}
        }
        if(!$scope.getTimeFindWeekTime(data.weekTimeLimit)){
            if(!$scope.commonRule('','特定星期的结束时间点不正确')){ return false;}
        }
        var div = $('div#venue');
        var $applyVenLen = div.children('div.clearfix').length;
        var $typeLen = $scope.applyVenueTypeArr.length;
        var $venLen = $scope.venueListsArr.length;
        var $timesLen = $scope.applyTimes.length;
        if($typeLen < 1 || $venLen < 1 || $timesLen<1){
            Message.warning("请填写完成的通店信息");
            return;
        }
        if($typeLen != $venLen || $typeLen != $timesLen || $venLen != $applyVenLen || $timesLen != $applyVenLen){
            Message.warning("请填写完成的通店信息");
            return;
        }

        return true;
    };
    //判断特定天是否存在
    $scope.getTimeDayStatus = function (day) {
        var length = day.length;
        if(length == 0){
            return false;
        }else{
            return true;
        }
    };
    //判断开始时间点和结束时间点是否正确
    $scope.getTimeFindTime            = function (start,end) {
        var dateTime    = new Date().toLocaleDateString();         //获取今天日期2017/4/24
        var startTime   = dateTime +" "+ start;                    //拼接开始日期时间
        var endTime     = dateTime +" "+ end;                      //拼接结束日期时间
        var startTimes  = Date.parse(new Date(startTime));         //开始日期格式化时间戳
        startTimes = startTimes / 1000;
        var endTimes     = Date.parse(new Date(endTime));          //结束日期格式化时间戳
        endTimes    = endTimes / 1000;
        if(startTimes != "NaN" && endTimes != "NaN"){
            if(startTimes >= endTimes){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    };
    //判断周的开始时间点和结束时间点是否正确
    $scope.getTimeFindWeekTime            = function (start) {
        var length = start.length;
        for(var i=0; i<length;i++){
            if(start[i] || start[i] != ""){
                if(!$scope.getTimeFindTime(start[i].substr(0,5),start[i].substr(-5,5))){
                    return false;
                }
            }else{
                return true;
            }
        }
        return true;
    };


    /** 时间卡：第二步数据接收   **/
       // （js获取课程数组）
    $scope.class = function () {
        $scope.classId      = [];
        $scope.pitchNum     = [];
        $scope.binkClassIsArr = [];
        $scope.classArr1234  = [];
    };
    $scope.commonProof = function (data) {
        if(data == undefined || data == null){
            return false;
        }else{
            return true;
        }
    };
    //js获取课程数组
    $scope.classServer = function () {
        var div = $('#course');
        var course = div.children('div.clearfix');
        $scope.class();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $select = $(this).find('option:selected').val();
                var $moreSelect = $(this).find('.leagueCourseList').val();
                var $reslist =$(this).find('.leagueCourseList').select2("data");
                var $check  = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $class  = $(this).find("input[name='times']").val();
                if($moreSelect){
                    $scope.everyLen  = $reslist.length;
                    $scope.everClassArr  =[];
                    $reslist.forEach(function(item,index,arr){
                        var $item = $(this);
                        $scope.classArr1234.push(item.id);
                        $scope.everClassArr.push(item.id);
                        if((index + 1) == $scope.everyLen){
                            $scope.classId.push($scope.everClassArr);
                        }
                    })

                    // console.log('$moreSelect',$moreSelect.replace(/\[|]/g,''))
                    // $scope.classArr1234.push($moreSelect.replace(/\[|]/g,''))
                    var $len =$reslist.length;
                    if($len == 1){
                       $scope.binkClassIsArr.push(1)
                    }
                    if($len > 1){
                        $scope.binkClassIsArr.push(2)
                    }
                    if($check || $class){
                        if($check){
                            $scope.pitchNum.push(-1)
                        }else{
                            $scope.pitchNum.push($class);
                        }
                    }else{
                        // //给出错误值
                        // $scope.classId=[1];
                        // $scope.pitchNum=[];
                        // return false;
                        $scope.pitchNum.push(-1);
                    }
                }
                // if($check || $class){
                //     if($check){
                //         $scope.pitchNum.push(-1)
                //     }else{
                //         $scope.pitchNum.push($class);
                //     }
                //     if($select){
                //         $scope.classId.push($select);
                //     }else{
                //         //抛出错误数据
                //         $scope.classId=[1];
                //         $scope.pitchNum=[];
                //         return false;
                //     }
                // }
            });
        }else{
            $scope.class();
        }
    };


    /** 时间卡：第二步数据接收（绑定服务）
     * js获取服务数组
     */
    $scope.server   = function () {
        $scope.serverId     = [];
        $scope.serverNum    = [];
    };
    $scope.serverCombo = function () {
        var div = $('div#server');
        var course = div.children('div.clearfix');
        $scope.server();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected = $(this).find('option:selected').val();
                var $checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $num       = $(this).find("input[name='times']").val();
                if($selected){
                    $scope.serverId.push($selected);
                    if($checked || $num){
                        if($checked){
                            $scope.serverNum.push(-1);
                        }else{
                            $scope.serverNum.push(parseInt($num));
                        }
                    }else{
                        // //抛出错误值、
                        // $scope.serverNum=[1];
                        // $scope.serverId =[];
                        // return false;
                        $scope.serverNum.push(-1);
                    }
                }

                // if($checked || $num){
                //         if($checked){
                //             $scope.serverNum.push(-1);
                //         }else{
                //             $scope.serverNum.push(parseInt($num));
                //         }
                //         if($selected){
                //             $scope.serverId.push($selected);
                //         }else{
                //             //抛出错误数据
                //             $scope.serverId =[];
                //             $scope.serverNum=[1];
                //             return false;
                //         }
                // }
            });
        }else{
            $scope.server();
        }
    };


    /** 时间卡：第二步数据接收（赠品）**/
    //js获取赠送数组
    $scope.donation = function () {
        $scope.donationId  = [];
        $scope.donationNum = [];
    };
    $scope.donationGoods = function () {
        var div = $('div#donation');
        var course = div.children('div.clearfix');
        $scope.donation();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected  = $(this).find('option:selected').val();
                var $checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $num       = $(this).find("input[name='times']").val();
                if($selected){
                    $scope.donationId.push($selected);
                    if($checked || $num){
                        if($checked){
                            $scope.donationNum.push(-1)
                        }else{
                            $scope.donationNum.push(parseInt($num));
                        }
                    }else{
                        // //抛出错误数据
                        // $scope.donationId=[];
                        // $scope.donationNum=[1];
                        // return false;
                        $scope.donationNum.push(-1)
                    }
                }
                // if($checked || $num){
                //     if($checked){
                //         $scope.donationNum.push(-1)
                //     }else{
                //         $scope.donationNum.push(parseInt($num));
                //     }
                //     if($selected){
                //         $scope.donationId.push($selected);
                //     }else{
                //         //抛出错误数据
                //         $scope.donationId=[];
                //         $scope.donationNum=[1];
                //         return false;
                //     }
                // }
            });
        }else{
            $scope.donation();
        }
    };
//第二步-第二步验证
    $scope.twoCommonRules = function (attr,num,text) {
        if(attr.length){
            if(!num.length){
                $scope.commonRule('',text);return false;
            }else if(attr.length != num.length){
                $scope.commonRule('',text);return false;
            }
        }
        if(num.length){
            if(!attr.length){
                $scope.commonRule('',text);return false;
            }else if(attr.length != num.length){
                $scope.commonRule('',text);return false;
            }
        }
        return true;
    };
    // js过滤验证
    $scope.twoRules = function (data) {
        if(!$scope.twoCommonRules(data.classId,data.pitchNum,'课程名称或节数不能为空')){ return false; }
        if(!$scope.twoCommonRules(data.serverId,data.serverNum,'服务名称或节数不能为空')){ return false; }
        if(!$scope.twoCommonRules(data.donationId,data.donationNum,'商品名称或数量不能为空')){ return false; }
        return true;
    };

    //云运动 - 前台 - 绑定套餐(第二步表单数据接收)
    $scope.getTwoData = function () {
        $scope.classServer();
        $scope.serverCombo();
        $scope.donationGoods();
        return  {
                 //   绑定团课课程（数组值一一对应）
            classId             : $scope.classId,                //课程id
            pitchNum            : $scope.pitchNum,              //课程节数
            binkClassIsArr      : $scope.binkClassIsArr,       //课程数组类型
            //绑定私课课程
            hsId                :$('#HSClass').val(),                 //hs课程id
            hsNum               : $scope.HSClassNum  != undefined && $scope.HSClassNum  != ''? $scope.HSClassNum :null,             //hs课程节数
            ptId                : $('#PTClass').val(),                //pt课程id
            ptNum               : $scope.PTClassNum != undefined && $scope.PTClassNum != '' ? $scope.PTClassNum : null,             //pt课程节数
            birthId             : $('#BirthdayClass').val(),         //生日课程id
            birthNum            : $scope.birthClassNum  != undefined && $scope.birthClassNum != '' ? $scope.birthClassNum : null,         //生日课程节数


                //    绑定服务（数组值一一对应）
            serverId            : $scope.serverId,              //服务id
            serverNum           : $scope.serverNum,             //服务数量
                // 赠品
            donationId          : $scope.donationId,            //获取赠送id
            donationNum         : $scope.donationNum,           //获取赠送数量
            _csrf_backend         : $('#_csrf').val()
        }
    };
    /** 时间卡：第三步数据接收（转让请假设置）**/
    //js获取假期数组
    $scope.leaveArr  = function () {
        $scope.leaveDaysType    = [];
    };
    $scope.studentLeaveArr  = function () {
        $scope.studentLeaveDaysType    = [];
    };
    $scope.getLeaveDays = function () {
        var div = $('div#leaveDay');
        var leaveDay = div.children('div.clearfix');
        $scope.leaveArr();
        $scope.learve = [];
        if($scope.commonProof(leaveDay)){
            leaveDay.each(function (i) {
                var $day   = $(this).find("input[name='days']").val();
                var $times = $(this).find("input[name='times']").val();
                if($day && $times){
                    $scope.learve.push($day);
                    $scope.learve.push($times);
                    $scope.leaveDaysType.push($scope.learve);$scope.learve = [];
                }else{
                    $scope.learve = [];
                }
            });
        }else{
            $scope.learve = [];
        }
};
    $scope.getStudentLeaveDays = function () {
        var div = $('div#studentLeaveDay');
        var $wDay = div.find("input[name='winterNum']").val();
        var $sDay = div.find("input[name='summerNum']").val();
        var $input =  div.find('input');
        $scope.studentLeaveArr();
        $scope.studentLearve = [];
        // console.log($wDay)
        // console.log($sDay)
        if($wDay != '' &&  $sDay != ''){
            $input.each(function(){
                var $day = $(this).val();
                var $time = 1;
                $scope.studentLearve.push($day);
                $scope.studentLearve.push($time);
                $scope.studentLeaveDaysType.push($scope.studentLearve);
                $scope.studentLearve = [];
            });
            // console.log($scope.leaveDaysType);
        }
    };
    

    //请假次数不可填写
    $scope.setLeaveNumDisabled = function () {
        if($scope.leaveTimesTotal != undefined || $scope.leaveDaysTotal != undefined){
            $scope.leaveNumsFlag = true;
        }
        if($scope.leaveTimesTotal == undefined && $scope.leaveDaysTotal == undefined){
            $scope.leaveNumsFlag = false;
        }
    };
    //请假天数不可填写
    $scope.seLeaveDaysDisabled = function () {
        if($scope.leaveNums != undefined || $scope.everyLeaveDays != undefined){
            $scope.leaveDaysFlag1 = true;
        }else{
            $scope.leaveDaysFlag1 = false;
        }
    };
    //寒假暑假不可填写
    // $scope.winterDisabled = function () {
    //     if($scope.winterNum != undefined || $scope.summerNum != undefined){
    //         $scope.studentNumFlag = true;
    //     }else{
    //         $scope.studentNumFlag = false;
    //     }
    // };
    //云运动 - 前台 - 绑定套餐(第三步表单数据接收)
    $scope.getThreeData = function () {
        $scope.getLeaveDays();
        // if($scope.leaveDaysType == null || $scope.leaveDaysType.length == 0){
        $scope.getStudentLeaveDays();
        // }
        return {
                   //1.转让设置
            transferNumber      : $scope.transferNumber,    //转让次数
            transferPrice       : $scope.transferPrice,     //转让天数
                   //2.请假设置
            leaveTimesTotal     : $scope.leaveTimesTotal, //请假每次最低天数
            leaveDaysTotal      : $scope.leaveDaysTotal,  //请假总天数
            leaveDaysType       : $scope.leaveDaysType,   // 请假类型（请假次数，每次请假天数）
            studentLeaveDaysType: $scope.studentLeaveDaysType,//学生请假(暑假天数,次数,寒假天数,次数)
            _csrf_backend: $('#_csrf').val()
        }
    };
    // 第三步：前台表单数据过滤验证
    $scope.threeRules = function (data) {
        if(!$scope.commonRule(data.transferNumber,'转让次数不能为空')){ return false; }
        if(!$scope.commonRule(data.transferPrice,'转让金额不能为空')){ return false; }
        return true;
    };

    //云运动 - 前台 - 合同(第四步表单数据接收)
    $scope.getFourData = function () {
        return  {
            deal            : $scope.dealId,
            _csrf_backend   : $('#_csrf').val()
        }
    };


    //云运动 - 前台 - 卡种信息添加（取消操作）
    $scope.getCancelData = function () {
        return  {
            scenario        : $scope.scenario,              //操作步骤
            _csrf_backend   : $('#_csrf').val()
        }
    };
    //云运动 - 前台 - 卡种信息添加(和后台数据交互)
    $scope.setHttp = function () {
        $http({
            url        : '/member-card/verification',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {

        });
    };
    // //js第四步验证
    // $scope.fourRules = function (data) {
    //     if(!$scope.commonRule(data.deal,'请选择合同')){ return false;}
    //     return true;
    // };

    //公告连接后台方法
    $scope.assignParams = function (data,attr) {
        $scope.params = data;
        if(attr == 'one'){
            if(!$scope.oneRules(data)){return false;}
        }else if(attr == 'two'){
            // if(!$scope.twoRules(data)){return false;}
        }else if(attr == 'three'){
            // if(!$scope.threeRules(data)){return false;}
        }
        else if(attr == 'four'){
            // if(!$scope.fourRules(data)){return false;}
        }
        return true;
    };

    $scope.getEnddata = function () {
        return  {
            _csrf_backend   : $('#_csrf').val()
        }
    };
    // //获取第二步模板
    // $rootScope.getTwoTemplate = function () {
    //     $scope.classKey    = "";
    //     $scope.serverKey   = "";
    //     $scope.shopKey     = "";
    //     $scope.donationKey = "";
    //     $scope.addClassHtml();
    //     $scope.addServerHtml();
    //     $scope.addDonationHtml();
    //     $scope.addLeaveHtml();
    //     $scope.groupCourse();
    //     $scope.getServerOptions();
    //     $scope.getShopOptions();
    //     $scope.getDonationOptions();
    // };
    //操作步骤赋值，和调取各步骤表单方法
    $rootScope.onStepChange = function (newIndex) {
        if(newIndex == 1){
            $('#HSClass').select2({
                // width:'100%'
            });
            $('#PTClass').select2({
                // width:'100%'
            });
            $('#BirthdayClass').select2({
                // width:'100%'
            });
            $('.leagueCourseList').select();
            $scope.OneParams             = $scope.getOneData();
            $scope.OneParams.scenario    = 'one';
           if(!$scope.assignParams($scope.OneParams,"one")){return false;}
        }else if(newIndex == 2){
            //第二步赠送课程验证
            //hs课程
            if($('#HSClass').val() == ''&& ($scope.HSClassNum  != undefined && $scope.HSClassNum  != '' && $scope.HSClassNum  != null) ){
                Message.warning('HS课程或节数不能为空');
                return false;
            }
            if($('#HSClass').val() != ''&& ($scope.HSClassNum  == undefined || $scope.HSClassNum  == ''|| $scope.HSClassNum  == null) ){
                Message.warning('HS课程或节数不能为空');
                return false;
            }
            //pt课程
            if($('#PTClass').val() == ''&& ($scope.PTClassNum  != undefined && $scope.PTClassNum  != '' && $scope.PTClassNum  != null) ){
                Message.warning('PT课程或节数不能为空');
                return false;
            }
            if($('#PTClass').val() != ''&& ($scope.PTClassNum  == undefined || $scope.PTClassNum  == ''|| $scope.PTClassNum  == null) ){
                Message.warning('PT课程或节数不能为空');
                return false;
            }
            //生日课程
            if($('#BirthdayClass').val() == ''&& ($scope.birthClassNum  != undefined && $scope.birthClassNum  != '' && $scope.birthClassNum  != null) ){
                Message.warning('生日课程或节数不能为空');
                return false;
            }
            if($('#BirthdayClass').val() != ''&& ($scope.birthClassNum  == undefined || $scope.birthClassNum  == ''|| $scope.birthClassNum  == null) ){
                Message.warning('生日课程或节数不能为空');
                return false;
            }
            $scope.TwoParams            = $scope.getTwoData();
            $scope.TwoParams.scenario  = 'two';
           if(!$scope.assignParams($scope.TwoParams,'two')){return false;}
            $scope.getDeal();
        }else if(newIndex == 3){
            //判断每次请假天数不能大于总天数
            if($scope.leaveDaysTotal != undefined && $scope.leaveDaysTotal != 0 && ($scope.leaveTimesTotal == undefined || $scope.leaveTimesTotal == 0)){
                Message.warning('每次最低天数不能为0');
                return false;
            }else if($('#leaveDaysTotal').val() < $('#leaveTimesTotal').val()){
                Message.warning('每次最低天数不能大于请假总天数');
                $('#leaveTimesTotal').val('');
                //$('.actions > ul').find('li').eq(1).prop('disabled',true);
                return false;
            }
            $scope.ThreeParams            = $scope.getThreeData();
            $scope.ThreeParams.scenario  = 'three';
            if(!$scope.assignParams($scope.ThreeParams,'three')){return false;}
        }else if(newIndex == 4){
            $scope.fourParams            = $scope.getFourData();
            $scope.fourParams.scenario  = 'four';
            if(!$scope.assignParams($scope.fourParams,'four')){return false;}
        }else if(newIndex=="cancel"){
            $scope.getEnddata.scenario   = 'cancel';
            $scope.endParams             =$scope.getEnddata;
            $scope.assignParams($scope.endParams);
        }else{
            $scope.fourParams            = $scope.getFourData();
            $scope.fourParams.scenario   = 'finish';
            // if(!$scope.assignParams($scope.fourParams,'four')){return false;}
        }
        $timeout(function () {
            $scope.setHttp();
        },1000);
        return true;
    }
}).run(function($rootScope,$timeout){
   // 表单向导启动
    $("#example-async").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "none",
        //动态设置表单向导的高度
        onStepChanging: function (event, currentIndex, newIndex) {
            // var index    = currentIndex + 1;
            if(!$rootScope.onStepChange(newIndex)){
                return false;
            }
            return true;
        },
        //动态设置表单向导的高度
        onStepChanged: function (event, currentIndex, priorIndex) {
            $('.wizard > .content').height($('.con1').eq(currentIndex).outerHeight()+100);
            if(currentIndex == 1){
                // $rootScope.getTwoTemplate();
                $rootScope.getServerOptions();      //获取服务信息
                // $scope.getShopOptions();        //获取赠品所有信息
                // $scope.getDonationOptions();    //获取赠品所有信息
            }
            if(currentIndex == 3){
                // $rootScope.getTwoTemplate();
            }
        },
        //点击取消页面时跳转页面
        onCanceled:function (event) {
            $rootScope.onStepChange("cancel");
            location.href = '/member-card/index';
        },
        onFinishing:function (event, currentIndex) {
            var index = currentIndex + 1;
            if(index == 4){
                if(!$rootScope.onStepChange(index)){
                   return false;
                }else{
                    if(currentIndex == 3){
                        $.loading.show();
                        $timeout(function () {
                            location.href = '/member-card/index';
                        },2000);

                    }
                }
            }
        },
        //点击完成按钮时跳转页面
        onFinished:function (event, currentIndex) {
            if(currentIndex == 3){
                $.loading.show();
                $timeout(function () {
                    location.href = '/member-card/index';
                },2000);
            }
        }
    });
});
    

    




