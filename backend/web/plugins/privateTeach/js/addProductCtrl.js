/**
 * Created by DELL on 2017/4/17.
 * content:新增多课种产品页面js
 */

$(function(){
    //调用开始日期
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
//调用结束日期
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

//    添加课种
    function createCourse(){
        var  con = '<div class="row"><div class="col-sm-4 clearfix"><span class="fl mT5"  ><strong class=" red">*</strong>选择课程&emsp;&emsp;</span>';
        con+='<select class="form-control cp fl" style="padding: 4px 12px;" ><option value="">请选择课程</option><option value="1">瑜伽</option><option value="2">单车</option><option value="3">热瑜伽</option></select></div>';
        con+='<div class="col-sm-4 clearfix"><span class="fl mT5"><strong class="red">*</strong>课程时长&emsp;&emsp;</span><input type="number" inputnum min="0" name="classTime" placeholder="0分钟" class="form-control cp fl"></div>';
        con+='<div class="col-sm-4 clearfix"> <span class="fl mT5"><strong class="red">*</strong>课程节数&emsp;&emsp;</span><input type="number" inputnum min="0" name="classNum" placeholder="0节" class="form-control cp fl"></div>';
        con+='<div class="col-sm-4 clearfix"><span class="fl mT5"><strong class="red">*</strong>单节原价&emsp;&emsp;</span><input type="number" inputnum min="0" name="unitPrice" placeholder="0元" class="form-control cp fl"></div>';
        con+='<div class="col-sm-4 "></div><span style="margin-top: 2px;margin-left: 17px" class="btn btn-default">删除</span></div>';
        $(con).appendTo($('#course'));
    }
    //点击添加课种
    $('#addCourse').on('click',function(){
        // createCourse()
    });

    //添加场馆
    function createProductPrice(){
        var cont = '<div class="row"> <div class="col-sm-4 clearfix"><span class="fl mT5">&nbsp;售卖场馆&emsp;&emsp;</span>';
        cont+='<select class="form-control cp fl" style="padding: 4px 12px;" ><option value="">&nbsp;请选择场馆</option> <option value="1">大上海</option><option value="2">大卫城</option><option value="3">大学路</option></select></div>';
        cont+=' <div class="col-sm-4 clearfix"><span class="fl mT5">&nbsp;单馆售卖数量</span><input type="number" inputnum min="0" name="venueSaleNum" placeholder="0套" class="form-control cp fl"></div></div>';
        $(cont).appendTo($('#productPrice'));
    }
    //点击添加场馆
    $('#addProductPrice').on('click', function () {
        // createProductPrice();
    });

    //点击取消按钮跳转私教首页
    $('.btnCancel').on('click', function () {
        // location.href = '/private-teach/index?mid=4&c=2';
        window.history.go(-1);
    });

    var $input = $('.inputSellNum');
    //（数量不限）点击单选框，输入框添加限制
    $('#unLimit').on('ifChecked', $input, function () {
        $(this).find("input[name='sellNum']").attr('disabled', 'disabled');
        $(this).find("input[name='sellNum']").val('');
    });
    $('#venueSaleNum').on('ifChecked', $input, function () {
        $(this).find("input[name='venueSaleNum']").attr('disabled', 'disabled');
        $(this).find("input[name='venueSaleNum']").val('');
    });
    //（数量不限）点击单选框，输入框解除限制
    $('#unLimit').on('ifUnchecked', $input, function () {
        $(this).find("input[name='sellNum']").removeAttr('disabled');
    });

    $('#venueSaleNum').on('ifUnchecked', $input, function () {
        $(this).find("input[name='venueSaleNum']").removeAttr('disabled');
    })
});
var  app = angular.module('App');
app.controller('addProductCtrl',function($scope,$http,$rootScope,Upload,$timeout){
    $scope.setHttpButtonFlag = false;
    //初始化方法
    $scope.init = function () {
        $scope.classTotalPrice    = 0;
        $scope.classTotalPrices   = 0;
        $scope.num                 = 0;
        $scope.validTimeType      = '1';
        $scope.activatedTimeType = '1';
        $scope.classId            = '';
        $scope.venueId            = '';
        $scope.serverId           = '';
        $scope.donationKey        = '';
        $scope.classHttp          = [];
        $scope.venueHttp          = [];
        $scope.serverHttp         = [];
        $scope.donationHttp       = [];
        $scope.classCategory();
        $scope.saleVenue();
        $scope.server();
        $scope.gift();
        $scope.getVenueOptions();
        $scope.getClassOptions();
        $scope.getServerOptions();
        $scope.getDonationOptions();
        $scope.addVenueHtml();
        $scope.addClassHtml();
        $scope.addServerHtml();
        $scope.addDonationHtml();
        $scope.getVenueLists();
    };

    //获取所属场馆信息
    $scope.getVenueLists = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            $scope.venueLists = result.data;
        });
    };
    //获取课程信息
    $scope.getClassOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-private-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionClass = result.data.venue;
                $scope.classStatus = true;
            }else{
                $scope.optionClass = '暂无数据';
                $scope.classStatus = false;
            }
        });
    };
    //获取售卖场馆信息
    $scope.getVenueOptions = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue = '暂无数据';
                $scope.venueStatus = false;
            }
        });
    };
    //获取服务信息
    $scope.getServerOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-server-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionServer = result.data.venue;
                $scope.serverStatus = true;
                // $scope.serverShow = true;
            }else{
                $scope.optionServer = '暂无数据';
                $scope.serverStatus = false;
                // $scope.serverShow = false;
            }
        });
    };
    //获取选择赠品信息
    $scope.getShopLists = function(){
        $http.get('/rechargeable-card-ctrl/get-shopping-data').then(function(response){
            $scope.shopLists = response.data.venue;
        });
    }
    $scope.getShopLists();

    //获取赠送商品信息
    $scope.getDonationOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            if(result.data.goods != undefined && result.data.goods != ""){
                $scope.optionDonation = result.data.goods;
                $scope.donationStatus = true;
                // $scope.donationShow   = true;
            }else{
                $scope.optionDonation = '暂无数据';
                $scope.donationStatus = false;
                // $scope.donationShow = false;
            }
        });
    };

    //课程改变触发事件
    $scope.selectClass     = function (id) {
        $scope.classHttp.push(id);
        $scope.getClassId();
        if($scope.classId != undefined && $scope.classId.length){
            $scope.classHttp = $.array.arrayIntersection($scope.classHttp,$scope.classId);
        }
    };
    //场馆改变触发事件
    $scope.selectVenue     = function (id) {
        $scope.venueHttp.push(id);
        $scope.getVenueId();
        if($scope.venueId != undefined && $scope.venueId.length){
            $scope.venueHttp = $.array.arrayIntersection($scope.venueHttp,$scope.venueId);
        }
    };
    //服务改变触发事件
    $scope.selectServer     = function (id) {
        $scope.serverHttp.push(id);
        $scope.getServerId();
        if($scope.serverId != undefined && $scope.serverId.length){
            $scope.serverHttp = $.array.arrayIntersection($scope.serverHttp,$scope.serverId);
        }
    };
    //赠品改变触发事件
    $scope.selectDonation     = function (id) {
        $scope.donationHttp.push(id);
        $scope.getGiftId();
        if($scope.giftId != undefined && $scope.giftId.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.giftId);
        }
    };

    //添加课程模板
    $scope.addClassHtml = function () {
        $scope.htmlAttr = 'class';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.classPrivateHtml = result.data.html;
        });
    };
    //添加售卖模板
    $scope.addVenueHtml  = function () {
        // $('.i-checks').iCheck({
        //     checkboxClass: 'icheckbox_square-green',
        //     radioClass: 'iradio_square-green',
        // });
        // var $input = $('.inputSellNum');
        // $('.venueSaleNum').on('ifChecked', $input, function () {
        //     $(this).find("input[name='venueSaleNum']").attr('disabled', 'disabled');
        //     $(this).find("input[name='venueSaleNum']").val('');
        // });
        // $('.venueSaleNum').on('ifUnchecked', $input, function () {
        //     $(this).find("input[name='venueSaleNum']").removeAttr('disabled');
        // });
        $scope.htmlAttr = 'saleVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.saleHtml = result.data.html;
        });

    };
    //添加服务模板
    $scope.addServerHtml = function () {
        $scope.htmlAttr = 'server';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.serverHtml = result.data.html;
        });
    };
    //添加赠品模板
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'donation';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.donationHtml = result.data.html;
        });
    };

    //公共处理数组
    $scope.commonRemoveId = function (id,data) {
        data.splice($.inArray(id,data), 1);
        return data;
    };

    //定义课种数组
    $scope.classCategory = function () {
        $scope.classId   = [];
        $scope.classTime = [];
        $scope.classNum  = [];
        $scope.unitPrice = [];
        $scope.appUnitPrice = [];
    };
    //定义售卖场馆数组
    $scope.saleVenue = function () {
        $scope.venueId      = [];
        $scope.venueSaleNum = [];
    };
    //定义服务数组
    $scope.server = function () {
        $scope.serverId  = [];
        $scope.serverNum = [];
    };
    //定义赠品数组
    $scope.gift = function () {
        $scope.giftId  = [];
        $scope.giftNum = [];
    };

    //公共属性判断
    $scope.commonProof = function (data) {
        if (data == undefined || data == null) {
            return false;
        } else {
            return true;
        }
    };
    //调用初始化方法
    $scope.init();

    //获取售卖总数量
    $scope.getTotalNum = function () {
        var div   = $('div#unLimit');
        var $checked =  div.find('div.icheckbox_square-green').hasClass('checked');
        var $totalNum = div.find("input[name='sellNum']").val();
        if ($checked || $totalNum) {
            if ($checked) {
                $scope.sellNum = -1;
            } else {
                $scope.sellNum = $totalNum;
            }
        }
    };
    //获取课种
    $scope.getClassId = function () {
        var select = $('div#course');
        var div    = select.children('div.row');
        $scope.classCategory();
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $classId  = $(this).find('option:selected').val();
                var $classTime  = $(this).find("input[name='classTime']").val();
                var $classNum  = $(this).find("input[name='classNum']").val();
                var $unitPrice  = $(this).find("input[name='unitPrice']").val();
                var $appUnitPrice = $(this).find("input[name='appUnitPrice']").val();
                if($classId){
                    $scope.classId.push($classId);
                    if ($classTime && $classNum) {
                        $scope.classTime.push($classTime);
                        $scope.classNum.push($classNum);
                    }
                    if($unitPrice){
                        $scope.unitPrice.push($unitPrice);
                    }else{
                        $scope.unitPrice.push("暂无数据")
                    }
                    $scope.appUnitPrice.push($appUnitPrice);
                }

            });
        }else{
            $scope.classCategory();
        }
    };
    //获取售卖场馆
    $scope.getVenueId = function () {
        var select = $('div#productPrice');
        var div    = select.children('div.row');
        $scope.saleVenue();
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $saleVenue  = $(this).find('option:selected').val();
                var $venueSaleNum  = $(this).find("input[name='venueSaleNum']").val();
                if($saleVenue){
                    $scope.venueId.push($saleVenue);
                    if ($venueSaleNum){
                        $scope.venueSaleNum.push($venueSaleNum);
                    }
                }
            });
        }else{
            $scope.saleVenue();
        }
    };
    //获取服务
    $scope.getServerId = function () {
        var select = $('div#productServe');
        var div    = select.children('div.row');
        $scope.server();
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $server  = $(this).find('option:selected').val();
                var $serverNum  = $(this).find("input[name='serverNum']").val();
                if($server){
                    $scope.serverId.push($server);
                    if ($serverNum){
                        $scope.serverNum.push($serverNum);
                    }
                }
            });
        }else{
            $scope.server();
        }
    };
    //获取赠品
    $scope.getGiftId = function () {
        var select = $('div#productGift');
        var div    = select.children('div.row');
        $scope.gift();
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $gift  = $(this).find('option:selected').val();
                var $giftNum  = $(this).find("input[name='giftNum']").val();
                if($gift){
                    $scope.giftId.push($gift);
                    if ($giftNum){
                        $scope.giftNum.push($giftNum);
                    }
                }
            });
        }else{
            $scope.gift();
        }
    };
    //获取合同信息
    $scope.getDeal = function (){
        $http.get('/contract/get-deal?type=2').then(function (result) {
            //console.log(result)
            $scope.contactList = result.data;
        });
    };
    $scope.getDeal();
    $scope.getContact = function (id){
       /* var $arr = angular.fromJson(arr);
        $scope.contentTeach = $arr.intro;
        $scope.contentId = $arr.id;*/
        //获取合同详情
        $http.get('/contract/get-deal-one?id='+id).then(function (result) {
            //console.log(result.data)
            if(result.data.data != null){
                $scope.contentTeach = result.data.data.intro;
            }else{
                Message.warning('请选择合同');
                $scope.contentTeach = '';
            }
        })
    };
    //获取所有数据
    $scope.getData = function () {
        $scope.getTotalNum();
        // $scope.getOneVenueNum();
        $scope.getClassId();
        $scope.getVenueId();
        $scope.getServerId();
        $scope.getGiftId();
        return {
            name                 : $scope.name,              //产品名称
            productType          : $scope.productType,       //产品类型
            validTime            : $scope.validTime,         //有效期
            validTimeType        : $scope.validTimeType,
            activatedTimeType    : $scope.activatedTimeType,
            activatedTime        : $scope.activatedTime,     //激活期限
            sellNum              : $scope.sellNum,           //售卖总数量
            saleStart            : $scope.saleStart,         //售卖起始日期
            saleEnd              : $scope.saleEnd,           //售卖结束日期
            totalSalePrice       : $scope.totalSalePrice,    //总售价
            totalPosPrice        : $scope.totalPosPrice,     //总POS价
            appTotalPrice        : $scope.appTotalPrice,     //移动端总售价
            notReservation       : $scope.notReservation,    //不可预约
            notCancel            : $scope.notCancel,         //不可取消
            reservationDays      : $scope.reservationDays,   //可预约天数
            classId              : $scope.classId,           //课程id
            classTime            : $scope.classTime,         //课程时长
            classNum             : $scope.classNum,          //课程节数
            unitPrice            : $scope.unitPrice,         //课程原价
            appUnitPrice         : $scope.appUnitPrice,      //移动端单节原价
            venueId              : $scope.venueId,           //场馆id
            venueSaleNum         : $scope.venueSaleNum,      //售卖数量
            serverId             : $scope.serverId,          //服务id
            serverNum            : $scope.serverNum,         //服务数量
            giftId               : $scope.giftId,       //赠品id
            giftNum              : $scope.giftNum,           //赠品数量
            transferNum          : $scope.transferNum,       //转让次数
            transferPrice        : $scope.transferPrice,     //转让金额
            desc                 : $scope.desc,              //课程结束
            pic                  : $scope.pic,               //图片
            totalPrice           : $scope.classTotalPrice,   //计算的总原价
            appTotalOriginal     : $scope.classAppTotalPrice,//计算的移动端总原价
            dealId               : $scope.contactAdd,         //合同ID
            courseType           :$scope.courseType,         //课程类型
            belongVenue          : $scope.venueTypeId,      //所属场馆id
            _csrf_backend        : $('#_csrf').val()
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
    $scope.classTotalPrice = 0;
    //计算总金额
    $scope.getPriceNum   = function (price,num) {
        var select = $('div#course');
        var div    = select.children('div.row');
        var $price = 0;
        div.each(function (i) {
            var $classNum  = $(this).find("input[name='classNum']").val();
            var $unitPrice  = $(this).find("input[name='unitPrice']").val();
            if($classNum != '' && $unitPrice != ''){
                $price = $price + ($classNum * $unitPrice);
            }
        });
        $scope.classTotalPrice = $price;
    };
    //计算app总金额
    $scope.classAppTotalPrice = 0;
    $scope.appPriceNum   = function (price,num) {
        var select = $('div#course');
        var div    = select.children('div.row');
        var $price = 0;
        div.each(function (i) {
            var $classNum  = $(this).find("input[name='classNum']").val();
            var $unitPrice  = $(this).find("input[name='appUnitPrice']").val();
            if($classNum != '' && $unitPrice != ''){
                $price = $price + ($classNum * $unitPrice);
            }
        });
        $scope.classAppTotalPrice = $price;
    };

    //删除元素
    $scope.removeVenueId = function (id,attr,prices,total,appPrice) {
        $scope.removePrice = prices * total;
        $scope.removeAppPrice = appPrice * total;
        $scope.classTotalPrice = $scope.classTotalPrice - $scope.removePrice;
        $scope.classAppTotalPrice = $scope.classAppTotalPrice - $scope.removeAppPrice;
        if(attr == 'venue'){
            $scope.venueHttp = $scope.commonRemoveId(id,$scope.venueHttp);
        }else if (attr == 'class'){
            $scope.classHttp = $scope.commonRemoveId(id,$scope.classHttp);
        }else if (attr == 'server'){
            $scope.serverHttp = $scope.commonRemoveId(id,$scope.serverHttp);
        }else if (attr == 'donation'){
            $scope.donationHttp = $scope.commonRemoveId(id,$scope.donationHttp);
        }
    };
    //获取模板
    $scope.getTwoTemplate = function () {
        $scope.addClassHtml();
        $scope.addVenueHtml();
        $scope.addServerHtml();
        $scope.addDonationHtml();
        $scope.getClassOptions();
        $scope.getVenueOptions();
        $scope.getServerOptions();
        $scope.getDonationOptions();
    };

    //定义公共验证方法
    $scope.commonRule = function (attr,text) {
        if(!attr){Message.warning(text);return false;}return true;
    };
    //定义公共数组验证方法
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
    //课程判断
    $scope.classRule = function(classId,classTime,classNum,text){
        if(classId.length==0||classTime.length==0||classNum.length==0){
            Message.warning(text);return false;
        }
        return true;
    };
    //课程介绍长度判断
    $scope.descLength = function (desc,text) {
        if(desc.length > 200) {
            Message.warning(text);return false;
        }
        return true;
    };

    //验证规则提示
    $scope.rules = function () {
        if(!$scope.commonRule($scope.name,'请填写产品名称!'))          { return false; }
        if(!$scope.commonRule($scope.productType,'请选择产品类型!'))    { return false; }
        if(!$scope.commonRule($scope.validTime,'请填写有效期限!'))     { return false; }
        if(!$scope.commonRule($scope.activatedTime,'请填写激活期限!')) { return false; }
        if(!$scope.commonRule($scope.saleStart,'请选择售卖起始日期!'))  { return false; }
        if(!$scope.commonRule($scope.saleEnd,'请选择售卖结束日期!'))    { return false; }
        if(!$scope.commonRule($scope.courseType,'请选择课程类型!'))    { return false; }
        if(!$scope.commonRule($scope.venueTypeId,'请选择所属场馆！')){ return false; }
        // if(!$scope.commonRule($scope.price,'请填写单节原价!'))           { return false; }
        if(!$scope.twoCommonRules($scope.classId,$scope.classTime,'请将相应的课程数据填写完整')){ return false; }
        if(!$scope.classRule($scope.classId,$scope.classTime,$scope.classNum,'请选择课程')){return false;}
        if($scope.price == null || $scope.price == undefined){
            Message.warning("请填写单节原价");
            return
        }
        // if(!$scope.commonRule($scope.totalSalePrice,'请填写总售价！')){ return falsse; }
        // if(!$scope.commonRule($scope.totalPosPrice,'请填写总POS价！')){ return false; }
        if(!$scope.twoCommonRules($scope.venueId,$scope.venueSaleNum,'请将相应的售卖场馆数据填写完整')){ return false; }
        if(!$scope.twoCommonRules($scope.serverId,$scope.serverNum,'请将相应的服务数据填写完整')){ return false; }
        if(!$scope.twoCommonRules($scope.giftId,$scope.giftNum,'请将相应的赠品数据填写完整')){ return false; }
        // if(!$scope.descLength($scope.desc,'课程介绍内容过多')){ return false; }
        return true;
    };

    //添加私教课程(和后台数据交互 POST)
    $scope.setHttp = function () {
        $scope.params = $scope.getData();
        $scope.getTwoTemplate();
        if($scope.rules($scope.params)){
            $scope.setHttpButtonFlag = true;
            $http({
                url        : '/private-teach/add-private-teach',
                method     : 'POST',
                data       :  $.param($scope.params),
                headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function (result) {
                if (result.data.status == 'success') {
                    Message.success('添加成功');
                    window.history.go(-1);
                    // $timeout(function(){
                    //     $scope.setHttpButtonFlag = false;
                    // },3000)
                    // var getModuleId = localStorage.getItem("moduleId");
                    // $scope.moduleId = JSON.parse(getModuleId).id
                } else{
                    $scope.setHttpButtonFlag = false;
                    for (var key in result.data.data) {
                        if (result.data.data.hasOwnProperty(key)) {
                            var element = result.data.data[key];
                            Message.warning(element);
                        }
                    }
                    return
                }
            });
        }
    };
});
