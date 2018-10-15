/**
 * Created by Administrator on 2017/3/25.
 */
//混合卡js文件
$(function(){
    inputUnlimited();
    $('#addSellVenue').on('click',function(){
        iCheck();
        setHeight(0);
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
    });
    $('#HSClass').select2({
        // width:'100%'
    });
    $('#PTClass').select2({
        // width:'100%'
    });
    $('#BirthdayClass').select2({
        // width:'100%'
    });

    $('#addVenue').on('click',function(){
        iCheck();
        setHeight(0);
        inputUnlimited();
    });
    $('#addDiscount').on('click',function(){
        iCheck();
        setHeight(0);
        inputUnlimited();
    });

    $('#addSellVenue').on('click',function () {
        inputUnlimited();

    })
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
    $('.weekSelect').on('ifChecked', function () {
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
    $('.week').on('ifChecked',function(){
        ind = $(this).index('.week');
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
        cont1+= '<span class="">课程名称&emsp;&emsp;</span><select class="form-control cp"  >';
        cont1+= '<option value="" >请选择课程</option><option value="1">单车</option><option value="2">瑜伽</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">每日节数&emsp;&emsp;</span><div class="clearfix cp inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input name="times" style="width: 101px;border: none;margin-left: 0;" type="text" placeholder="0节" class="fl form-control">';
        cont1+='<div style="margin: 5px 5px 0 10px;position: relative;left: -5px;"  class=" cp checkbox i-checks checkbox-inline fl"><input style="width: 16px;height: 16px;margin-left: 0;" type="checkbox" id="unlimited" value="option1" >';
        cont1+='<label for="shoppingUnlimited" style="padding-left: 3px;">不限</label></div></div></div><span style="margin-top: 20px;margin-left: -41px" class="btn btn-default">删除</span></div>';
        $(cont1).appendTo($('.course'));
    }
    //第二步点击添加课程
    $('.addCourse').on('click',function(){
        iCheck();
        setHeight(1);
        inputUnlimited();
    });
    //第二步添加服务
    function createServe(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">服务名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择服务" >请选择服务</option>';
        cont1+= '<option value="1">毛巾</option><option value="2">茶水</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">每日数量&emsp;&emsp;</span><div class="clearfix cp inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 101px;border: none;margin-left: 0;" type="text" placeholder="0" class="fl form-control">';
        cont1+='<div style="margin: 5px 5px 0 10px;"  class=" cp checkbox i-checks checkbox-inline fl"><input style="width: 16px;height: 16px;margin-left: 0;" type="checkbox" id="unlimited" value="option1" >';
        cont1+='<label for="shoppingUnlimited" style="padding-left: 3px;">不限</label></div></div></div><span style="margin-top: 20px;margin-left: -41px" class="btn btn-default">删除</span></div>';
        $(cont1).appendTo($('.serve'));
    }
    //第二步添加商品
    $('.addServe').on('click',function(){
        iCheck();
        setHeight(1);
        inputUnlimited();
    });

    //第二步添加shop
    function createShop(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">商品名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择商品" >请选择商品</option>';
        cont1+= '<option value="1">A商品</option><option value="2">B商品</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">商品数量&emsp;&emsp;</span><div class="clearfix cp inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 101px;border: none;margin-left: 0;" type="text" placeholder="0" class="fl form-control">';
        cont1+='<div style="margin: 5px 5px 0 10px;"  class=" cp checkbox i-checks checkbox-inline fl"><input style="width: 16px;height: 16px;margin-left: 0;" type="checkbox" id="unlimited" value="option1" >';
        cont1+='<label for="shoppingUnlimited" style="padding-left: 3px;">不限</label></div></div></div>';
        cont1+='<div class="fl " style="margin: 26px 0 0 -40px"><span class="glyphicon glyphicon-yen"><b style="font-size: 18px;">0</b></span> 总金额</div><span style="margin-top: 20px;margin-left: 19px" class="btn btn-default">删除</span></div>';
        $(cont1).appendTo($('.shopping'));
    }
    //第二步添加商品
    $('.addShop').on('click',function(){
        iCheck();
        setHeight(1);
        inputUnlimited();
    });


    //第二步添加赠品
    function createDonation(){
        var cont1 = '<div class="clearfix" ><div class="fl  w32" style="position: relative;">';
        cont1+= '<span class="">商品名称&emsp;&emsp;</span><select class="form-control cp"  ><option value="请选择商品" >请选择商品</option>';
        cont1+= '<option value="A商品">A商品</option><option value="B商品">B商品</option></select></div>';
        cont1+= '<div class="fl  w32"><span class="">商品数量&emsp;&emsp;</span><div class="clearfix cp inputUnlimited" style="border: solid 1px #cfdadd;margin-left: 15px;">';
        cont1+='<input  style="width: 101px;border: none;margin-left: 0;" type="text" placeholder="0" class="fl form-control">';
        cont1+='<div style="margin: 5px 5px 0 10px;"  class=" cp checkbox i-checks checkbox-inline fl"><input style="width: 16px;height: 16px;margin-left: 0;" type="checkbox" id="unlimited" value="option1" >';
        cont1+='<label for="shoppingUnlimited" style="padding-left: 3px;">不限</label></div></div></div><span style="margin-top: 20px;margin-left: -41px" class="btn btn-default">删除</span></div>';
        $(cont1).appendTo($('.donation'));
    }
    $('.addDonation').on('click',function(){
        iCheck();
        setHeight(1);
        inputUnlimited();
    });
    //数量和不限选择函数
    function inputUnlimited(){
        //（数量不限）点击单选框，输入框添加限制
        $('.inputUnlimited').on('ifChecked',function(){
            $(this).children('input').attr('disabled','disabled');
            $(this).children('input').val('');
        });
        //（数量不限）点击单选框，输入框解除限制
        $('.inputUnlimited').on('ifUnchecked',function(){
            $(this).children('input').removeAttr('disabled');
        })
    }
    //第三步添加请假函数
    function createAddLeave(){
        var cont1 = '<div class="clearfix"><div class="fl  w32"><span class="">请假次数&emsp;&emsp;</span>';
        cont1 += '<input name="times"  type="number" min="0" placeholder="0次" class="form-control"></div>';
        cont1 += '<div class="fl  w32"><span class="">每次请假天数</span><input name="days" type="number" min="0" placeholder="0天" class="form-control"></div><span style="margin-top: 20px;margin-left: 19px" class="btn btn-default">删除</span></div>'
        $(cont1).appendTo($('.leaveDay'));
    }
    //第三步添加请假
    $('.addLeave').on('click',function(){
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
app.controller('blendCardController',function($scope,$rootScope,$http,Upload) {
    
    $.loading.show();                                       //打开页面
//初始化方法
    $scope.init                   = function () {
        $scope.renewUnit   = '30';                          //优惠多长时间默认选择
        $scope.num         = 0;                             //用于添加通用模版，
        $scope.totalPrice  = 0;                             //充值总价格，默认值
        $scope.venueHttp   = [];                            //定义空数组，存被选中的售卖场馆id
        $scope.applyHttp   = [];                            //定义空数组，存被选中的通店场馆id
        $scope.classHttp   = [];                            //定义空数组，存被选中的id
        $scope.serverHttp  = [];                            //定义空数组，存被选中的id
        $scope.shopHttp    = [];                            //定义空数组，存被选中的id
        $scope.donationHttp= [];                            //定义空数组，存被选中的id
        $scope.getCardTheVenue();                           //获取所属场馆
        $scope.addCardValidityHtml();                       //添加有效期续费
        $scope.addVenueHtml();                              //添加模版
        $scope.addApplyHtml();                              //添加模版
        $scope.addClassHtml();                              //添加模版
        $scope.addServerHtml();                             //添加模版
        $scope.addShopHtml();                               //添加模版
        $scope.addDonationHtml();                           //添加模版
        $scope.addLeaveHtml();                              //添加模版
        $scope.VenueArr();                                  //调用售卖场馆定义数组
        $scope.ApplyArr();                                  //调用通店场馆定义数组
        $scope.classArr();                                  //调用课程项目定义数组
        $scope.serverArr();                                 //调用服务项目定义数组
        $scope.TollArr();                                   //调用扣费项目定义数组
        $scope.giftArr();                                   //调用赠送项目定义数组

        $scope.getCardAttr();                               //请求Func中的卡种属性
        $scope.getVenueInfo();                              //请求数据库中的场馆
        $scope.getApplyInfo();                              //请求数据库中的场馆
        $scope.getClassOptions();                           //请求数据库中的课程
        $scope.getServerOptions();                          //请求数据库中的服务
        $scope.getShopOptions();                            //请求数据库中的扣费
        $scope.getDonationOptions();                        //请求数据库中的赠送
        $scope.getDeal();                                   //请求数据库中的合同

        $scope.status          = "";
        $scope.dealIds         = "";                        //合同id用
        $scope.leaveNumsFlag = false;
        $scope.leaveDaysFlag = false;
        setTimeout("$.loading.hide()",1000);                //隐藏页面

    };
//获取卡种所属场馆
    $scope.getCardTheVenue = function(){
        $http.get('/member-card/get-venue-data-by-id').then(function(response){
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
//请求售卖场馆
    $scope.getVenueInfo    = function () {
        $http.get('/rechargeable-card-ctrl/get-venue?status=card').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionVenue = result.data.venue;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue = '暂无数据';
                $scope.venueStatus = false;
            }
        });
    };
//请求通店场馆
    $scope.getApplyInfo    = function () {
        $http.get('/rechargeable-card-ctrl/get-venue?status=card').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionApply = result.data.venue;
                $scope.applyStauts = true;
            }else{
                $scope.optionApply = '暂无数据';
                $scope.applyStauts = false;
            }
        });
    };
//获取课程信息
    $scope.getClassOptions = function () {
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
    $scope.getServerOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-server-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionServer = result.data.venue;
                $scope.serverStatus = true;
                $scope.serverShow   = true;
            }else{
                $scope.optionServer = '暂无数据';
                $scope.serverStatus = false;
                $scope.serverShow   = false;
            }

        });
    };
//获取商品信息
    $scope.getShopOptions    = function () {
        $http.get('/rechargeable-card-ctrl/get-shopping-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionShop   = result.data.venue;
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
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionDonation = result.data.venue;
                $scope.donationStatus       = true;
                $scope.donationShow         = true;
            }else{
                $scope.optionDonation       = '暂无数据';
                $scope.donationStatus       = false;
                $scope.donationShow         = false;
            }
        });
    };
    //获取合同信息
    $scope.getDeal = function () {
        $http.get('/contract/get-deal?type=1').then(function (result) {
            // if(result.data.deal != undefined && result.data.deal != ""){
            //     $scope.dealData   = result.data.deal;
            //     $scope.dealStauts = true;
            // }else{
            //     $scope.dealData   = '暂无数据';
            //     $scope.dealStauts = false;
            // }
            $scope.dealData = result.data;
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



//添加售卖模板
    $scope.addVenueHtml   = function () {
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

//售卖场馆改变触发事件（选过的场馆，下次禁用）现在此方法在视图页面没有用 ng-change="selectVenue(venueId)"
//     $scope.selectVenue     = function (id) {
//         if(id && id != undefined){
//             $scope.venueHttp.push(id);                             //如果有选中的就存起来(id)
//         }
//         $scope.getSellVenueData();                                 //取出jQ获取的id
//         if($scope.sellVenueArr != undefined && $scope.sellVenueArr.length){
//             $scope.venueHttp = $.array.arrayIntersection($scope.venueHttp,$scope.sellVenueArr); //返回对比相同的id
//         }
//     };
//添加通店模板
    $scope.addApplyHtml   = function () {
        $scope.htmlAttr = 'applyVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.applyHtml = result.data.html;
        });
    };
//通店场馆改变触发事件
    $scope.selectApply     = function (id) {
        if(id && id != undefined){
            $scope.applyHttp.push(id);
        }
        $scope.getApplyVenueData();
        if($scope.applyVenueArr != undefined && $scope.applyVenueArr.length){
            $scope.applyHttp = $.array.arrayIntersection($scope.applyHttp,$scope.applyVenueArr);
        }
    };
//添加课程模板
    $scope.addClassHtml   = function () {
        $scope.htmlAttr = 'class';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.classHtml = result.data.html;
        });
    };
//课程改变触发事件
    $scope.selectClass     = function (id) {
        if(id && id != undefined){
            $scope.classHttp.push(id);
        }
        $scope.getBlendClassData();
        if($scope.classIdArr != undefined && $scope.classIdArr.length){
            $scope.classHttp = $.array.arrayIntersection($scope.classHttp,$scope.classIdArr);
        }
    };
//添加服务模板
    $scope.addServerHtml   = function () {
        $scope.htmlAttr = 'server';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.serverHtml = result.data.html;
        });
    };
//服务改变触发事件
    $scope.selectServer     = function (id) {
        if(id && id != undefined){
            $scope.serverHttp.push(id);
        }
        $scope.getBlendServerData();
        if($scope.serverIdArr != undefined && $scope.serverIdArr.length){
            $scope.serverHttp = $.array.arrayIntersection($scope.serverHttp,$scope.serverIdArr);
        }
    };
//添加产品模板
    $scope.addShopHtml     = function () {
        $scope.htmlAttr = 'shopping';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.shopHtml = result.data.html;
        });
    };
//商品改变触发事件
    $scope.selectShop     = function (id) {
        if(id && id != undefined){
            $scope.shopHttp.push(id);
        }
        $scope.getBlendTollData();
        if($scope.tollIdArr != undefined && $scope.tollIdArr.length){
            $scope.shopHttp = $.array.arrayIntersection($scope.shopHttp,$scope.tollIdArr);
        }
    };
//添加赠品模板
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'donation';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.donationHtml = result.data.html;
        });
    };
//赠品改变触发事件
    $scope.selectDonation     = function (id) {
        if(id && id !=undefined){
            $scope.donationHttp.push(id);
        }
        $scope.getBlendGiftData();
        if($scope.giftIdArr != undefined && $scope.giftIdArr.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.giftIdArr);
        }
    };
//添加假期模板
    $scope.addLeaveHtml    = function () {
        $scope.htmlAttr = 'leaveVenue';
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr).then(function (result) {
            $scope.leaveHtml = result.data.html;
        });
    };
//公共处理数组(id，需要删除的id,data操作的数组)
    $scope.commonRemoveId = function (id,data) {
        data.splice($.inArray(id,data), 1);
        return data;
    };
//删除元素（删除添加的场馆，课程等，需要清除数组中的id,解除禁用状态）
    $scope.removeVenueId = function (id,attr) {
        if(attr == 'venue'){
            if(id != undefined){
                    $scope.venueHttp = $scope.commonRemoveId(id,$scope.venueHttp);
                  }
        }else if (attr == 'apply'){
            if(id != undefined){
                $scope.applyHttp = $scope.commonRemoveId(id,$scope.applyHttp);
            }
        }else if (attr == 'class'){
            if(id != undefined){
                    $scope.classHttp = $scope.commonRemoveId(id,$scope.classHttp);
                  }
        }else if (attr == 'server'){
            if(id != undefined){
                    $scope.serverHttp = $scope.commonRemoveId(id,$scope.serverHttp);
                  }
        }else if (attr == 'shop'){
            if(id != undefined){
                     $scope.shopHttp = $scope.commonRemoveId(id,$scope.shopHttp);
                  }
        }else if (attr == 'donation'){
            if(id != undefined){
                 $scope.donationHttp = $scope.commonRemoveId(id,$scope.donationHttp);
            }
        }
    };
//定义售卖场馆空数组
    $scope.VenueArr               = function () {
        $scope.sellVenueArr     = [];           //存场馆id
        $scope.sellNumArr       = [];           //售卖数量
        $scope.sellStartArr     = [];           //售卖开始时间
        $scope.sellEndTimeArr   = [];           //售卖结束时间
        $scope.discountArr      = [];            //售卖折扣
    };
//接收售卖场馆数据
    $scope.getSellVenueData       = function () {
        $scope.VenueArr();
        var select = $("div#sellVenue");
        var div    = select.find("div.clearfix");
        if($scope.commonProof(div)) {
            div.each(function (i) {
                $scope.discountNumArr = [];
                var venue      = $(this).find('option:selected').val();             //接收场馆id
               if(venue != undefined && venue != ""){
                   $scope.sellVenueArr.push(venue);
                   var sellNumArr = $(this).find('input[name="sheets"]').val();        //接收售卖数量
                   var   $checked    = $(this).find('div.icheckbox_square-green').hasClass('checked');
                   var $discountBox  = $(this).children('.discountLists').children('.discountBox');//获取折扣box
                   if(sellNumArr || $checked ){
                       if(sellNumArr){
                           $scope.sellNumArr.push(sellNumArr);
                       }else{
                           $scope.sellNumArr.push(-1);
                       }
                   }else{
                       $scope.sellNumArr.push(sellNumArr);
                   }
                   var sellStart  = $(this).find('input[name="start"]').val();         //接收售卖开始时间
                   var sellEnd    = $(this).find('input[name="end"]').val();           //接收售卖结束时间
                   $scope.sellStartArr.push(sellStart);
                   $scope.sellEndTimeArr.push(sellEnd);
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
        }else{
            $scope.VenueArr();
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
        })
    }

    // //获取所有的有效期续费
    // $scope.huoqusuoyou = function(){
    //     $scope.getAllValidRenew();
    //     $scope.getBlendDataTwo();
    // }
    
//定义售卖场馆空数组
    $scope.ApplyArr               = function () {
        $scope.applyVenueArr =  [];     //存场馆id
        $scope.applyTimesArr =  [];     //通电次数
        $scope.applyGrade = [];
        $scope.openShopWeekChecked = [];
    };
//接收通店场馆数据
    $scope.getApplyVenueData      = function () {
        $scope.ApplyArr();
        var div   = $('div#venue');
        var apply = div.children('div.clearfix');
        if($scope.commonProof(apply)) {
            apply.each(function (i) {
                var applyVenues = $(this).children(".pRelative").find('option:selected').val();
                var checked = $(this).children("month").find('div.icheckbox_square-green').hasClass('checked');
                var applyTimes = $(this).find('input[name="times"]').val();
                var weekArray =  $(this).find("input[name='weeks']").val();
                var weekChecked = $(this).children("month2").find('div.icheckbox_square-green').hasClass('checked');
                var aaa =  $(this).children(".times1").find('option:selected').val();
                    $scope.applyVenueArr.push(applyVenues);
                    $scope.applyGrade.push(aaa)
                var week    = $(this).children(".month").find('option:selected').val();
                $scope.openShopWeekChecked.push(week);
                    if (checked || applyTimes) {
                        if (checked) {
                            $scope.applyTimesArr.push(-1);
                        } else {
                            $scope.applyTimesArr.push(applyTimes);
                        }
                    }else{
                        $scope.applyTimesArr.push(applyTimes);
                    }
                // if (weekChecked || weekArray) {
                //     if (weekChecked) {
                //         $scope.weekArray.push(-1);
                //     } else {
                //         $scope.weekArray.push(weekArray);
                //     }
                // }else{
                //     $scope.weekArray.push(weekArray);
                // }
            });
        }else{
            $scope.ApplyArr();
        }
    };
//接收月中的指定天
    $scope.getDaysData            = function () {
        $scope.dayArr        = [];//存每月的天
        var table            = angular.element(document.getElementById('table'));
        var td               = table.find('td.bColor');
        if(td.length != undefined && td.length != 0){
            td.each(function (i) {
                $scope.dayArr.push(parseInt(td[i].innerHTML));
            })
        }else{
            $scope.dayArr     = [];
        }
    };
//接收指定周
    $scope.getWeeksData           = function () {
        $scope.weekArr       = [];//存指定的周
        $scope.weekStartArr  = [];//存指定的周的开始时间
        var li    = $('ul.weekSelect');
        var input = li.find('input:checked');
        if(input.length != undefined && input.length != 0){
            input.each(function (i) {
                $scope.weekArr.push(parseInt($(this).val()));
                $scope.weekStartArr.push($(this).parents('div.week').next().html());
            })
        }
    };
//公告判断方法
    $scope.commonProof            = function (data) {
        if(data == undefined || data == null){
            return false;
        }else{
            return true;
        }
    };
//定义课程空数组
    $scope.classArr               = function () {
        $scope.classIdArr         = [];
        $scope.classNumArr        = [];
    };
//获取课程数组
    $scope.getBlendClassData      = function () {
        $scope.classArr();
        var div = $('div#course');
        var course = div.children('div.clearfix');
        if($scope.commonProof(course)){
            course.each(function (i) {
                var select = $(this).find('option:selected').val();
                var check  = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var classNum  = $(this).find('input[name="times"]').val();
                if(select){
                    $scope.classIdArr.push(select);
                    if(check || classNum) {
                        if (check) {
                            $scope.classNumArr.push(-1);
                        } else {
                            $scope.classNumArr.push(classNum);
                        }
                    }else{
                        $scope.classNumArr.push(-1);//默认给-1
                    }
                }
            });
        }else{
            $scope.classArr();
        }
    };
//定义服务空数组
    $scope.serverArr              = function () {
        $scope.serverIdArr   = [];
        $scope.serverNumArr  = [];
    };
//获取服务数组
    $scope.getBlendServerData     = function () {
        $scope.serverArr();
        var div = $('div#server');
        var course = div.children('div.clearfix');
        if($scope.commonProof(course)){
            course.each(function (i) {
                var selected = $(this).find('option:selected').val();
                var checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var num       = $(this).find('input[name="times"]').val();
                if(selected){
                    $scope.serverIdArr.push(selected);
                    if(checked || num){
                        if(checked){
                            $scope.serverNumArr.push(-1)
                        }else{
                            $scope.serverNumArr.push(num);
                        }
                    }else{
                        $scope.serverNumArr.push(-1)
                    }
                }
            });
        }else{
            $scope.serverArr();
        }
    };
//定义产品空数组
    $scope.TollArr                = function () {
        $scope.tollIdArr     = [];
        $scope.tollNumArr    = [];
    };
//获取产品数组
    $scope.getBlendTollData       = function () {
        $scope.TollArr();
        var div = $('div#shopping');
        var course = div.children('div.clearfix');
        if($scope.commonProof(course)){
            course.each(function (i) {
                var selected = $(this).find('option:selected').val();
                var checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var num       = $(this).find('input[name="times"]').val();
                if(selected){
                    $scope.tollIdArr.push(selected);
                    if(checked || num) {
                        if (checked) {
                            $scope.tollNumArr.push(-1);
                        }else {
                            $scope.tollNumArr.push(num);
                        }
                    }else{
                        $scope.tollNumArr.push(-1);
                    }
                }
            });
        }else{
            $scope.TollArr();
        }
    };
//定义赠品空数组
    $scope.giftArr                = function () {
        $scope.giftIdArr     = [];
        $scope.giftNumArr    = [];
    };
//获取赠品数组
    $scope.getBlendGiftData       = function () {
        $scope.giftArr();
        var div = $('div#donation');
        var course = div.children('div.clearfix');
        if($scope.commonProof(course)){
            course.each(function (i) {
                var selected = $(this).find('option:selected').val();
                var checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var num       = $(this).find('input[name="times"]').val();
                if(selected){
                    $scope.giftIdArr.push(selected);
                    if(checked || num){
                        if(checked){
                            $scope.giftNumArr.push(-1)
                        }else{
                            $scope.giftNumArr.push(num);
                        }
                    }else{
                        $scope.giftNumArr.push(-1)
                    }
                }
            });
        }else{
            $scope.giftArr();
        }
    };
//获取假期数组
    $scope.getBlendLeaveData      = function () {
        $scope.leaveSetDayArr    = [];
        $scope.learve            = [];
        var div = $('div#leaveDay');
        var leaveDay = div.children('div.clearfix');
        $scope.learveArr = [];
        if($scope.commonProof(leaveDay)){
            leaveDay.each(function (i) {
                var day   = $(this).find('input[name="days"]').val();
                var times = $(this).find('input[name="times"]').val();
                if(day && times){
                    $scope.learveArr.push(day);
                    $scope.learveArr.push(times);
                    $scope.leaveSetDayArr.push($scope.learveArr);
                    $scope.learveArr = [];
                }
            });
        }else{
            $scope.learve = [];
        }
    };
//计算总金额
    $scope.calculation            = function () {
        if($scope.rechargePrice == undefined){
            $scope.rechargePrice = null;
            $scope.totalPrice   = 0;
            return true;
        }
        if($scope.rechargePrice != undefined && $scope.rechargeGivePrice != undefined){
            $scope.totalPrice   = $scope.rechargePrice + $scope.rechargeGivePrice;
        }else if($scope.rechargePrice != undefined && $scope.rechargeGivePrice == undefined){
            $scope.totalPrice   = $scope.rechargePrice;
        }
    };

    ///判断卡种名称是否存在
    $scope.setCardName = function () {
        $http.get('/member-card/set-card-name?name='+$scope.cardName +'&venueId=' + $scope.cardTheVenueId).success(function (result) {
            $scope.status = result.status;
        });
    };
    //根据选择场馆判断名称是否重复
    $scope.cardTheVenue = function(){
        $scope.setCardName();
    }
//调用初始化数组
    $scope.init();

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

//接收混合卡第一步数据
    $scope.getBlendDataOne        = function () {
        $scope.getAllValidRenew();
        $scope.getSellVenueData();                                                    //接收售卖场馆所有数据
        $scope.getApplyVenueData();                                                   //接收通店场馆所有数据
        $scope.getDaysData();
        $scope.getWeeksData();
        return {
            attributes           : $scope.attributes,                                  //卡种属性
            cardName             : $scope.cardName,                                    //卡种名称
            anotherName          : $scope.anotherName,                                 //卡种别名
            activeTime           : $scope.activeTime,                                  //激活期限
            activeTimeUnit       : $scope.activeTimeUnit != undefined ? $scope.activeTimeUnit :1,        //期限单位
            cardDuration         : $scope.cardDuration,                                //有效天数
            cardDurationUnit     : $scope.cardDurationUnit != undefined ? $scope.cardDurationUnit :1,      //有效天数单位
            pic                  : $scope.pic,                                         //图片
            originalPrice        : $scope.originalPrice ,                              //一口原价
            sellPrice            : $scope.sellPrice ,                                  //一口售价
            minPrice             : $scope.minPrice,                                    //最低价
            maxPrice             : $scope.maxPrice ,                                   //最高价
            renewPrice           : $scope.renewPrice,                                  //续费价
            offerPrice           : $scope.offerPrice,                                  //优惠价
            appSellPrice         : $scope.appSellPrice,                                //移动端售价
            renewUnit            : $scope.renewUnit,                                   //优惠多长时间
            timeDuration         : $scope.timeDuration,                                //时间卡属性有效天数
            timeDurationUnit     : $scope.timeDurationUnit != undefined ? $scope.timeDurationUnit :1,      //时间卡属性有效天数单位
            times                : $scope.times,                                       //次卡属性次数
            countMethod          : $scope.countMethod,                                 //次卡属性扣次方式
            timesDuration        : $scope.timesDuration,                               //次卡属性有效天数
            timesDurationUnit    : $scope.timesDurationUnit != undefined ? $scope.timesDurationUnit :1,     //次卡属性有效天数单位
            rechargePrice        : $scope.rechargePrice,                               //充值卡充值金额
            rechargeGivePrice    : $scope.rechargeGivePrice,                           //充值卡赠送金额
            rechargeDuration     : $scope.rechargeDuration,                            //充值卡属性有效天数
            rechargeDurationUnit : $scope.rechargeDurationUnit != undefined ?$scope.rechargeDurationUnit :1,  //充值卡属性有效天数单位
            sellVenueArr         : $scope.sellVenueArr,                                //售卖场馆
            sellLimitArr         : $scope.sellNumArr,                                  //售卖数量
            discount              :$scope.discountArr,                                  //售卖折扣
            ordinaryRenewal      :$scope.OrdinaryRenewal,                             //普通续费
            validityRenewal      :$scope.AllValidRenewArr,                            //有效期续费数组
            sellStartTimeArr     : $scope.sellStartArr,                                //售卖开始时间
            sellEndTimeArr       : $scope.sellEndTimeArr,                              //售卖结束时间
            applyVenueArr        : $scope.applyVenueArr,                               //通店场馆
            venueTimesArr        : $scope.applyTimesArr,                               //通店次数
            level:$scope.applyGrade,
            single:$scope.Singular,
            applyType :$scope.openShopWeekChecked,
            cardType       : $scope.cardType,//卡的类型
            venueId        : $scope.cardTheVenueId,//卡种所属场馆
            dayArr               : $scope.dayArr,                                      //一月中指定的天
            dayStart             : $scope.dayStart,                                    //指定天的开始时间
            dayEnd               : $scope.dayEnd,                                      //指定天的结束时间
            weekArr              : $scope.weekArr,                                     //指定的周
            weekStart            : $scope.weekStartArr,                                //指定的周的开始时间
            start                : $scope.start,                                       //指定开始时间
            end                  : $scope.end,                                         //指定结束时间
            scenario             : $scope.scenario,                                    //操作步骤
            _csrf_backend        : $('#_csrf').val()
        }
    };
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
        if($scope.minPrice != undefined || $scope.maxPrice != undefined){
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

    //接收混合卡第二步数据
    $scope.getBlendDataTwo        = function () {
        $scope.getBlendClassData();
        $scope.getBlendServerData();
        $scope.getBlendTollData();
        $scope.getBlendGiftData();
        return {
            //绑定私课课程
            hsId                :$('#HSClass').val(),                 //hs课程id
            hsNum               : $scope.HSClassNum  != undefined && $scope.HSClassNum  != ''? $scope.HSClassNum :null,             //hs课程节数
            ptId                : $('#PTClass').val(),                //pt课程id
            ptNum               : $scope.PTClassNum != undefined && $scope.PTClassNum != '' ? $scope.PTClassNum : null,             //pt课程节数
            birthId             : $('#BirthdayClass').val(),         //生日课程id
            birthNum            : $scope.birthClassNum  != undefined && $scope.birthClassNum != '' ? $scope.birthClassNum : null,         //生日课程节数
            classIdArr         : $scope.classIdArr,                                     //课程id
            classNumArr        : $scope.classNumArr,                                    //课程数量
            serverIdArr        : $scope.serverIdArr,                                    //服务id
            serverNumArr       : $scope.serverNumArr,                                   //服务数量
            tollIdArr          : $scope.tollIdArr,                                      //扣费项目id
            tollNumArr         : $scope.tollNumArr,                                     //扣费项目数量
            giftIdArr          : $scope.giftIdArr,                                      //赠品id
            giftNumArr         : $scope.giftNumArr,                                     //赠品数量
            scenario           : $scope.scenario,                                       //操作步骤
            _csrf_backend      : $('#_csrf').val()
        }
    };


    //请假次数不可填写
    $scope.setLeaveNumDisabled = function () {
        if($scope.leaveTotalDays != undefined || $scope.leastLeaveDays != undefined){
            $scope.leaveNumsFlag = true;
        }
        if($scope.leaveTotalDays == undefined && $scope.leastLeaveDays == undefined){
            $scope.leaveNumsFlag = false;
        }
    };
    //请假天数不可填写
    $scope.setLeaveDaysDisabled = function () {
        if($scope.leaveNums != undefined || $scope.everyLeaveDays != undefined){
            $scope.leaveDaysFlag = true;
        }else{
            $scope.leaveDaysFlag = false;
        }
    };


//接收混合卡第三步数据
    $scope.getBlendDataThree      = function () {
        $scope.getBlendLeaveData();
        return {
            transferNumber     : $scope.transferNumber,                                  //转让次数
            transferPrice      : $scope.transferPrice,                                   //转让金额
            leaveTotalDays     : $scope.leaveTotalDays,                                  //请假总天数
            leastLeaveDays     : $scope.leastLeaveDays,                                  //每次请假最少天数
            leaveSetDayArr     : $scope.leaveSetDayArr,                                  //请假的天，次数
            scenario           : $scope.scenario,                                        //操作步骤
            _csrf_backend      : $('#_csrf').val()
        }
    };
//接收混合卡第四步数据
    $scope.getBlendDataFour       = function () {
        return {
            deal               : $scope.dealId,                                           //合同id
            scenario           : $scope.scenario,                                         //操作步骤
            _csrf_backend      : $('#_csrf').val()
        }
    };
//接收混合卡取消操作数据
    $scope.getBlendDataCancel     = function () {
        return {
            scenario           : $scope.scenario,                                        //操作步骤
            _csrf_backend      : $('#_csrf').val()
        }
    };
//定义公共验证方法
    $scope.commonBlendRule        = function (attr,text) {
        if(!attr){Message.warning(text);return false;}return true;
    };
//定义售卖场馆验证方法（attr数据数组，text提示内容）
    $scope.BlendSellVenueRule          = function (attr,text) {
      var len = attr.length;
        for(var i=0;i<len;i++){
            if((!attr[i] || attr[i]=="")){
                $scope.commonBlendRule('',text);return false;
            }
        }
        return true;
    };
//定义售卖日期验证方法
    $scope.BlendSellDateRule           = function (attr1,text1,attr2,text2) {
        if(!attr1[(attr1.length - 1) ] || attr1[(attr1.length - 1)]==""){
            $scope.commonBlendRule('',text1);return false;
        }else if(!attr2[(attr2.length -1)] || attr2[(attr2.length - 1)]==""){
            $scope.commonBlendRule('',text2);return false;
        }
        return true;
    };
//第一步数据检测
    $scope.oneBlendRule           = function (data) {
        if($scope.cardTheVenueId == '' || $scope.cardTheVenueId == null ||$scope.cardTheVenueId == undefined){
            Message.warning("请选择卡种所属场馆");
            return;
        }
        if(!$scope.commonBlendRule(data.attributes,'请选择卡种属性')){ return false;}
        if(!$scope.commonBlendRule(data.cardName,'卡种名称不能为空')){return false;}
        if($scope.status == 'error'){ Message.warning('卡名称已经存在'); return false; }
        if(!$scope.commonBlendRule(data.cardDuration,'有效时间不能为空')){return false;}
        // if(!$scope.commonBlendRule(data.single,'单数不能为空')){return false;}
        // if($("#Singular").val() == '' || $("#Singular").val()  == undefined || $("#Singular").val()   == null ){
        //     Message.warning("单数不能为空");
        //     return;
        // }
        if($scope.cardType == '' || $scope.cardType == undefined){
            Message.warning("请选择卡的类型");
            return;
        }
        if($scope.OrdinaryRenewal == '' || $scope.OrdinaryRenewal == undefined || $scope.OrdinaryRenewal == null ){
            Message.warning("普通续费不能为空");
            return;
        }
        
        if(!data.minPrice){
            if (!data.originalPrice || !data.sellPrice){
                $scope.commonBlendRule('','请填写一口价或者区间价');return false;
            }
        }
        if(!$scope.getJudge(data.times)){
            if(!$scope.commonBlendRule(data.countMethod,'请选择扣次方式'))
            {
                return false;
            }
        }
        if($scope.minPrice != '' && $scope.maxPrice != ''){
            if($scope.maxPrice < $scope.minPrice){
                Message.warning('最高价不能低于最低价');
                return;
            }
        }
        if($scope.sellVenueArr.length == 0){
            Message.warning('请选择售卖场馆');
            return;
        }
        if(!$scope.BlendSellVenueRule(data.sellVenueArr,'请选择售卖场馆'))
        {
            return false;
        }
        if(!$scope.BlendSellVenueRule(data.sellLimitArr,'请填写售卖张数'))
        {
            return false;
        }
        if(!$scope.BlendSellVenueRule(data.sellStartTimeArr,'请填写售卖开始日期'))
        {
            return false;
        }
        if(!$scope.BlendSellVenueRule(data.sellEndTimeArr,'请填写售卖结束日期'))
        {
            return false;
        }
        // if(!$scope.BlendSellVenueRule(data.applyVenueArr,'请选择售卖场馆')){
        //     return false;
        // }
        // if(!$scope.BlendSellVenueRule(data.venueTimesArr,'请填写售卖张数')){
        //         return false;
        // }
        if(!$scope.getFindTime(data.dayStart,data.dayEnd)){
            if(!$scope.getBlendDayStatus(data.dayArr)){
                if(!$scope.commonBlendRule('','请先选择固定日')){ return false;}
            }else{
                if(!$scope.commonBlendRule('','固定日结束时间不正确')){ return false;}
            }
        }
        if(!$scope.getFindTime(data.start,data.end)){
            if(!$scope.commonBlendRule('','结束时间点不正确')){ return false;}
        }
        if(!$scope.getFindWeekTime(data.weekStart)){
            if(!$scope.commonBlendRule('','特定星期的结束时间点不正确')){ return false;}
        }
        return true;
    };
//判断次卡的次数、充值金额是否存在
    $scope.getJudge         = function (attr) {
        if(!attr || attr == undefined){
            return true;
        }
        return false;
    };
//判断特定天是否存在
    $scope.getBlendDayStatus = function (day) {
        var length = day.length;
        if(length == 0){
            return false;
        }else{
            return true;
        }
    };
//判断开始时间点和结束时间点是否正确
    $scope.getFindTime            = function (start,end) {
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
    $scope.getFindWeekTime            = function (start) {
        var length = start.length;
        for(var i=0; i<length;i++){
            if(start[i] || start[i] != ""){
                if(!$scope.getFindTime(start[i].substr(0,5),start[i].substr(-5,5))){
                    return false;
                }
            }else{
                return true;
            }
        }
        return true;
    };
//第二步数据检测
    $scope.twoBlendRule           = function (data) {
        // if(!$scope.BlendArrRule(data.classIdArr,data.classNumArr,'请填写课程数量')){ return false; }
        if(!$scope.BlendSellVenueRule(data.classIdArr,'请选择课程')){
            return false;
        }else{
            if(!$scope.BlendArrRule(data.classIdArr,data.classNumArr,'请填写课程数量')){return false;}
        }
        return true;
    };
// //第四步数据检测
//     $scope.fourBlendRule          = function (data) {
//         if(!$scope.commonBlendRule(data.deal,'请选择合同')){return false;}
//         return true;
//     };
//准备后台POST连接
    $scope.setHttpStep            = function () {
        $http({
            url        : '/member-card/blend-card-rule',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                // Message.success('添加成功');
            }else if(result.data.status == 'cancel'){
                // setTimeout("window.location.replace('/member-card/index')",100);
            } else{
                angular.forEach(result.data.data,function (value,key) {
                    // Message.warning(value);
                })
            }
        });
    };
//准备公共方法（把接收的数据和检测方法进行验证）
    $scope.setDataHttp            = function (data,attr) {
        if(attr == 'one')
        {
            if(!$scope.oneBlendRule(data)){return false;}
        }else if(attr == 'two'){
            // if(!$scope.twoBlendRule(data)){return false;}
        }else if(attr == 'three')
        {
            // if(!$scope.threeBlendRule(data)){return false;}
        }
        else if(attr == 'four')
        {
        //     if(!$scope.fourBlendRule(data)){return false;}
        }
        return true;
    };
//准备run()调用的方法
    $rootScope.onBlendStepChange  = function (newIndex) {
        if(newIndex == 1){
            $scope.getGiveCourseData();
            $scope.scenario   = 'one';
            $scope.paramsOne  = $scope.getBlendDataOne();
            $scope.params       = $scope.paramsOne;
            $('#HSClass').select2({
                // width:'100%'
            });
            $('#PTClass').select2({
                // width:'100%'
            });
            $('#BirthdayClass').select2({
                // width:'100%'
            });
            if(!$scope.setDataHttp($scope.paramsOne,$scope.scenario)){return false;}
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
            $scope.scenario   = 'two';
            $scope.paramsTwo  = $scope.getBlendDataTwo();
            $scope.params       = $scope.paramsTwo;
            if(!$scope.setDataHttp($scope.paramsTwo,$scope.scenario)){return false;}
        }else if(newIndex == 3){
            //判断每次请假天数不能大于总天数
            if($scope.leaveTotalDays != undefined && $scope.leaveTotalDays != 0 && ($scope.leastLeaveDays == undefined || $scope.leastLeaveDays == 0)){
                Message.warning('每次最低天数不能为0');
                return false;
            }else if($('#leaveTotalDays').val() < $('#leastLeaveDays').val()){
                Message.warning('每次最低天数不能大于请假总天数');
                $('#leastLeaveDays').val('');
                //$('.actions > ul').find('li').eq(1).prop('disabled',true);
                return false;
            }
            //console.log($scope.leaveTotalDays,$scope.leastLeaveDays);
            // return false;
            $scope.scenario    = 'three';
            $scope.paramsThree = $scope.getBlendDataThree();
            $scope.params       = $scope.paramsThree;
            if(!$scope.setDataHttp($scope.paramsThree,$scope.scenario)){return false;}
        }else if(newIndex == 4){
            $scope.scenario    = 'four';
            $scope.paramsFour  = $scope.getBlendDataFour();
            $scope.params       = $scope.paramsFour;
            if(!$scope.setDataHttp($scope.paramsFour,$scope.scenario)){return false;}
        }else if(newIndex == 'cancel'){
            $scope.scenario     = 'cancel';
            $scope.paramsCancel = $scope.getBlendDataCancel();
            $scope.params       = $scope.paramsCancel;
        }
        $scope.setHttpStep();
        return true;
    }
}).run(function ($rootScope) {
    //表单向导启动
    $("#example-async").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "none",
        //动态设置表单向导的高度
        onStepChanged: function (event, currentIndex, priorIndex) {
            $('.wizard > .content').height($('.con1').eq(currentIndex).outerHeight()+100);

        },
        //1.每一步表单验证
        onStepChanging: function (event, currentIndex, newIndex) {
            if(!$rootScope.onBlendStepChange(newIndex))
            {
                return false;
            }
            return true;
        },
        //3.点击取消页面时跳转页面
        onCanceled:function (event) {
            $rootScope.onBlendStepChange('cancel');
            location.href = '/member-card/index'

        },
        //4.点击完成按钮时跳转页面
        onFinished:function (event, currentIndex) {
            if(currentIndex == 3){
                $.loading.show();
                location.href = '/member-card/index';
            }
        },
        //5.完成前验证
        onFinishing: function (event, currentIndex) {
            var index = currentIndex + 1;
            if(index == 4){
                if(!$rootScope.onBlendStepChange(index))
                {
                    return false;
                }
            }
            return true;
        }
    });
});
