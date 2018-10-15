/**
 * Created by DELL on 2017/4/17.
 * content:新增单课种产品页面js
 */

$(function() {
    //调用开始日期
    function starDate() {
        $(".datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
        });
    }

    starDate();
    $(".datetimeStart").on("click", function () {
        $(".datetimeStart").datetimepicker("setEndDate", $(".datetimeEnd").val());
    });
    //调用结束日期
    function endDate() {
        $(".datetimeEnd").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            startDate: new Date()
        })
    }

    endDate();
    $(".datetimeEnd").on("click", function () {
        $(".datetimeEnd").datetimepicker("setStartDate", $(".datetimeStart").val());
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
app.controller('addServeCtrl',function($scope,$http,$rootScope,Upload,$timeout){
    $scope.setHttpButtonFlag = false;
    //初始化方法
    $scope.init = function () {
        $scope.classTotalPrice    = 0;
        $scope.classTotalPrices   = 0;
        $scope.num                 = 0;
        $scope.validTimeType      = '1';
        $scope.activatedTimeType = '1';
        $scope.venueId            = '';
        $scope.serverId           = '';
        $scope.donationKey        = '';
        $scope.classHttp          = [];
        $scope.venueHttp          = [];
        $scope.serverHttp         = [];
        $scope.donationHttp       = [];
        $scope.price();
        $scope.saleVenue();
        $scope.server();
        $scope.gift();
        $scope.getVenueOptions();
        $scope.getClassOptions();
        $scope.getServerOptions();
        $scope.getDonationOptions();
        $scope.addVenueHtml();
        $scope.addPriceHtml();
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
    //
    $scope.getNums = function(){
        //获取所有区间中的最后一个
        var $sections = $('.sectionNumber .xxxx:last-child');
        console.log($sections)
        var $startNum = $sections.find('input[name="intervalStart"]').val();
        var $endNum = $sections.find('input[name="intervalEnd"]').val();
        console.log('$startNum',$startNum)
        console.log('$endNum',$endNum)
    }
    //获取最后一个产品区间价格,并渲染
    $scope.getLastCourseSection = function(){
        var courseSecBox =  $('.sectionNumber');
        var $lastTwo   = courseSecBox.find('.xxxx').eq(-2);
        var $lastOne   = courseSecBox.find('.xxxx').last();
        var $twoSecEnd = $lastTwo.find('input[name="intervalEnd"]').val();
        var $lastSecStart = $lastOne.find('input[name="intervalStart"]');
        if($twoSecEnd != '' && $twoSecEnd != undefined){
            $lastSecStart.val(parseInt($twoSecEnd) + 1);
        }
    }
    //添加区间模板
    $scope.addPriceHtml = function () {
        $scope.htmlAttr = 'price';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.pricePrivateHtml = result.data.html;
            $timeout(function(){
                $scope.getLastCourseSection();
            },100)
        });
    };
    //添加售卖场馆模板
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
    //删除元素
    $scope.removeVenueId = function (id,attr) {
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

    //定义区间数组
    $scope.price = function () {
        $scope.intervalStart = [];
        $scope.intervalEnd   = [];
        $scope.unitPrice     = [];
        $scope.posPrice      = [];
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

    // //单管售卖数量
    // $scope.getOneVenueNum = function(){
    //     var div   = $('div#venueSaleNum');
    //     var $checked =  div.find('div.icheckbox_square-green').hasClass('checked');
    //     var $oneNum = div.find("input[name='venueSaleNum']").val();
    //     if ($checked || $oneNum) {
    //         if ($checked) {
    //             $scope.venueSaleNum = -1;
    //         } else {
    //             $scope.venueSaleNum = $oneNum;
    //         }
    //     }
    // }
    //获取区间
    $scope.getPrice = function () {
        var $div = $('.sectionNumber').children('.xxxx');
        $scope.price();
        if($scope.commonProof($div)){
            $div.each(function (i) {
                var $intervalStart  = $(this).find("input[name='intervalStart']").val();
                var $intervalEnd  = $(this).find("input[name='intervalEnd']").val();
                var $unitPrice  = $(this).find("input[name='unitPrice']").val();
                var $posPrice  = $(this).find("input[name='posPrice']").val();
                if($intervalStart){
                    $scope.intervalStart.push($intervalStart);
                }
                if($intervalEnd){
                    $scope.intervalEnd.push($intervalEnd);
                }
                if($unitPrice){
                    $scope.unitPrice.push($unitPrice);
                }
                if($posPrice){
                    $scope.posPrice.push($posPrice);
                }
            });
        }else{
            $scope.price();
        }
        // if($scope.intervalStart.length > $scope.unitPrice.length||$scope.intervalEnd.length > $scope.unitPrice.length||$scope.intervalStart.length < $scope.unitPrice.length||$scope.intervalEnd.length < $scope.unitPrice.length || $scope.intervalEnd.length<= $scope.intervalStart.length){
        //     Message.warning("区间填写有误，请重新填写");
        //     return;
        // }
    };
    // 改变课程节数
    // $scope.numChange = function ($event,num) {
    //     console.log('$event',$event);
    //     console.log('num',num);
    //     var currentNum = num;
    //     var $num     = num + 1;
    //     var $preNum  =parseInt($($event.target).parents('.xxxx').find('.sectionInput').val());
    //     console.log(currentNum,$preNum)
    //     if(currentNum > $preNum && $preNum!=''){
    //         var $parents = $($event.target).parents('.xxxx').next();
    //         var $nextBox = $parents.find('.sectionInput').val($num);
    //     }else{
    //         var $parents = $($event.target).parents('.xxxx').next();
    //         var $nextBox = $parents.find('.sectionInput').val('');
    //     }
    // };
    $scope.courseType = "";
    //初始化添加区间禁止点击
    //$('#addPrivatePrice').attr('disabled','disabled');
    //区间失去焦点判断
    $scope.numChangeBlur = function ($event,num) {
        var currentNum = num;
        var $num     = num + 1;
        var $preNum  =parseInt($($event.target).parents('.xxxx').find('.sectionInput').val());
        if(currentNum >= $preNum && $preNum!=''){
            var $parents = $($event.target).parents('.xxxx').next();
            var $nextBox = $parents.find('.sectionInput').val($num);
            //$('#addPrivatePrice').removeAttr('disabled','disabled');
        }else{
            $($event.target).val('');
            var $parents = $($event.target).parents('.xxxx').next();
            var $nextBox = $parents.find('.sectionInput').val('');
            var $nextBox = $parents.find('.sectionInput2').val('');
            Message.warning('区间填写有误，请重新输入！');
            //$('#addPrivatePrice').attr('disabled','disabled');
            return;
        }
    }
    

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
        //console.log(id)
        /*var $arr = angular.fromJson(arr);
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
        $scope.getPrice();
        $scope.getVenueId();
        $scope.getServerId();
        $scope.getGiftId();
        return {
            name                 : $scope.name,             //产品名称
            // validTime            : $scope.validTime,        //有效期
            productType          : $scope.productType,      //产品类型
            monthUpNum:            $scope.monthUpNum, //有效课量
            validTimeType        : $scope.validTimeType,
            activatedTimeType    : $scope.activatedTimeType,
            activatedTime        : $scope.activatedTime,    //激活期限
            sellNum              : $scope.sellNum,          //售卖总数量
            saleStart            : $scope.saleStart,        //售卖起始日期
            saleEnd              : $scope.saleEnd,          //售卖结束日期
            totalUnitPrice       : $scope.totalUnitPrice,   //总售价
            totalPosPrice        : $scope.totalPosPrice,    //总POS价
            classKey             : $scope.classKey,         //课程id
            classTime            : $scope.classTime,        //课程时长
            onePrice             : $scope.onePrice,         //单节原价
            appUnitPrice         : $scope.appUnitPrice,     //移动端单节原价
            intervalStart        : $scope.intervalStart,    //开始节数
            intervalEnd          : $scope.intervalEnd,      //结束节数
            unitPrice            : $scope.unitPrice,        //优惠单价
            posPrice             : $scope.posPrice,         //POS价
            venueId              : $scope.venueId,          //场馆id
            venueSaleNum         : $scope.venueSaleNum,     //售卖数量
            serverId             : $scope.serverId,         //服务id
            serverNum            : $scope.serverNum,        //服务数量
            giftId               : $scope.giftId,      //赠品id
            giftNum              : $scope.giftNum,          //赠品数量
            transferNum          : $scope.transferNum,      //转让次数
            transferPrice        : $scope.transferPrice,    //转让金额
            desc                 : $scope.desc,             //课程介绍
            pic                  : $scope.pic,              //图片
            dealId               : $scope.contactAdd,        //合同ID
            courseType           :$scope.courseType,        //课程类型
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

    //计算总金额
    $scope.getPriceNum   = function (price,num) {
        if(price == undefined){
            price = null;
            $scope.classTotalPrice = 0;
            return true;
        }
        if(price != undefined && num != undefined){
            $scope.classTotalPrice = price * num;
        }else {
            $scope.classTotalPrice = 0;
        }
    };
    //计算总金额(模版)
    $scope.getPriceNums   = function (prices,nums) {
        if(prices == undefined){
            prices = null;
            $scope.classTotalPrices = 0;
            return true;
        }
        if(prices != undefined && nums != undefined){
            $scope.classTotalPrices = prices * nums;
        }else {
            $scope.classTotalPrices = 0;
        }
    };


    //获取模板
    $scope.getTwoTemplate = function () {
        $scope.addPriceHtml();
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
    //产品价格判断
    $scope.priceRule = function(unitPrice,text){
        if(unitPrice == ""||unitPrice == null||unitPrice == undefined){
            Message.warning(text);
            return false;
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
        // if($startClassNum != null && $endClassNum ==null || $startClassNum != "" && $endClassNum == "" || $startClassNum == null && $endClassNum !=null ||$startClassNum == "" && $endClassNum != ""){
        //     Message.warning("请输入课程开始区间和结束区间");
        //     return false;
        // }
        // if($startClassNum2 != null && $endClassNum2 ==null || $startClassNum2 != "" && $endClassNum2 == "" || $startClassNum2 == null && $endClassNum2 !=null ||$startClassNum2 == "" && $endClassNum2 != ""){
        //     Message.warning("请输入课程开始区间和结束区间");
        //     return false;
        // }
        // if($startClassNum != null && $endClassNum !=null ||$startClassNum != "" && $endClassNum != ""){
        //     var $unitPrice = $(".unitPrice").val();
        //     if($unitPrice == null || $unitPrice == ""){
        //         Message.warning("请输入优惠单价");
        //         return false;
        //     }
        // }
        // if($startClassNum2 != null && $endClassNum2 !=null ||$startClassNum2 != "" && $endClassNum2 != ""){
        //     var $unitPrice2 = $(".unitPrice2").val();
        //     if($unitPrice2 == null || $unitPrice2 == ""){
        //         Message.warning("请输入优惠单价");
        //         return false;
        //     }
        // }
        if(!$scope.commonRule($scope.name,'请填写产品名称！')){ return false; }
        if(!$scope.commonRule($scope.productType,'请选择产品类型！')){ return false; }
        if(!$scope.commonRule($scope.monthUpNum,'请填写每月上课数量！')){ return false; }
        if(!$scope.commonRule($scope.activatedTime,'请填写激活期限！')){ return false; }
        if(!$scope.commonRule($scope.saleStart,'请选择售卖起始日期！')){ return false; }
        if(!$scope.commonRule($scope.saleEnd,'请选择售卖结束日期！')){ return false; }
        if(!$scope.commonRule($scope.courseType,'请选择课程类型！')){ return false; }
        if(!$scope.commonRule($scope.venueTypeId,'请选择所属场馆！')){ return false; }
        if(!$scope.commonRule($scope.classKey,'请选择课程！')){ return false; }
        if(!$scope.commonRule($scope.classTime,'请填写课程时长')){ return false; }
        if($scope.onePrice == null || $scope.onePrice == undefined){
            Message.warning("请填写单节原价");
            return false;
        }
        if($scope.intervalStart.length != $scope.intervalEnd .length || $scope.intervalStart.length != $scope.unitPrice.length || $scope.intervalStart.length != $scope.posPrice.length){
            Message.warning("请将区间、优惠单价或POS价填写完整");
            return false;
        }
        if($scope.intervalEnd.length != $scope.unitPrice .length && $scope.intervalEnd.length != $scope.posPrice.length && $scope.posPrice.length != $scope.unitPrice .length){
            Message.warning("请将区间、优惠单价或POS价填写完整");
            return false;
        }

        // if($scope.intervalStart.length != 0 && $scope.intervalEnd != 0 && $scope.unitPrice.length != 0 && $scope.posPrice.length != 0){
        //     if($scope.intervalStart.length > $scope.intervalEnd.length ||
        //         $scope.intervalStart.length > $scope.unitPrice.length ||
        //         $scope.intervalStart.length > $scope.posPrice.length
        //     ){
        //         Message.warning("区间填写有误，请重新填写");
        //         return
        //     }
        // }
        //获取所有的区间元素
        $scope.SectionMoneyFlag = true;
        var $getSectionMoneys = $('.xxxx');
        $getSectionMoneys.each(function(i,item1){
            var item = $(this);
            var $startInput = $getSectionMoneys.eq(i).find('.sectionInput').val();
            var $endInput = $getSectionMoneys.eq(i).find('.sectionInput2').val();
            if($startInput !='' && $endInput ==''){
                Message.warning('区间结束填写不完整!');
                $scope.SectionMoneyFlag = false;
                return false;
            }
        });


        // if(!$scope.commonRule($scope.totalUnitPrice,'请填写总优惠价！')){ return false; }
        // if(!$scope.commonRule($scope.totalPosPrice,'请填写总POS价！')){ return false; }
        if(!$scope.twoCommonRules($scope.venueId,$scope.venueSaleNum,'请将相应的售卖场馆数据填写完整')){ return false; }
        if(!$scope.twoCommonRules($scope.serverId,$scope.serverNum,'请将相应的服务数据填写完整')){ return false; }
        if(!$scope.twoCommonRules($scope.giftId,$scope.giftNum,'请将相应的赠品数据填写完整')){ return false; }
        else {
            return true;
        }
        // if(!$scope.descLength($scope.desc,'课程介绍内容过多')){ return false; }
    };
    //添加私教课程(和后台数据交互 POST)
    $scope.setHttp = function () {
        $scope.params = $scope.getData();
        $scope.getTwoTemplate();
        if($scope.rules($scope.params)){
            if($scope.SectionMoneyFlag){
                $scope.setHttpButtonFlag = true;
                $http({
                    url        : '/private-teach/add-private-server',
                    method     : 'POST',
                    data       :  $.param($scope.params),
                    headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).then(function (result) {
                    if (result.data.status == 'success') {
                        Message.success('添加成功');
                        window.history.go(-1);
                        // var getModuleId = localStorage.getItem("moduleId");
                        // $scope.moduleId = JSON.parse(getModuleId).id
                    } else{
                        $scope.setHttpButtonFlag = false;
                        angular.forEach(result.data.data,function (value,key) {
                            Message.warning(value);
                        })
                    }
                });
            }

        }
    };
});