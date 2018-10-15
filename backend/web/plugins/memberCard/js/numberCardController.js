/**
 * Created by Administrator on 2017/3/25.
 */

//次卡js文件

/**
 * @云运动 - 后台 - 添加次卡信息
 * @author 朱梦珂 <zhumengke@itsports.club>
 * @create 2017/4/14
 */

var app = angular.module('App');
app.controller('cardController',function($scope, $http, $rootScope,Upload) {

    $.loading.show();                                       //打开页面
    //初始化方法
    $scope.init = function () {
        $scope.totalPrice = 0;
        $scope.num         = 0;
        $scope.venueHttp   = [];
        $scope.applyHttp   = [];
        $scope.classHttp   = [];
        $scope.serverHttp   = [];
        $scope.shopHttp     = [];
        $scope.donationHttp = [];
        $scope.applyStart   = [];     //通店开始时间
        $scope.applyEnd     = [];     //通店结束时间
        $scope.AllValidRenewArr = [];
        $scope.venueId      = "";
        $scope.activationType = '1';
        $scope.renewUnit = '30';
        $scope.getCardTheVenue();//获取所属场馆
        $scope.getGiveCourseData();//获取所有赠送私课
        $scope.addCardValidityHtml();//初始添加有效期模板
        $scope.saleVenue();
        $scope.applyVenue();
        $scope.weekFun();
        $scope.class();
        $scope.server();
        $scope.goods();
        $scope.donation();
        $scope.leaveArr();
        $scope.addVenueHtml();
        $scope.addApplyHtml();
        $scope.getVenueOptions();
        $scope.getApplyOptions();
        $scope.getCardAttr();
        $scope.getDeal();
        $scope.OrdinaryRenewal = '';// 普通续费价格
        $scope.classKey    = "";
        $scope.serverKey   = "";
        $scope.shopKey     = "";
        $scope.donationKey = "";
        $scope.leaveNums   = "";
        $scope.everyLeaveDays = "";
        $scope.addClassHtml();
        $scope.addServerHtml();
        $scope.addDonationHtml();
        $scope.addShopHtml();
        $scope.addLeaveHtml();
        $scope.getClassOptions();
        $scope.getServerOptions();
        $scope.getShopOptions();
        $scope.getDonationOptions();
        setTimeout("$.loading.hide()",1000);                    //隐藏页面
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
    //获取售卖场馆信息
    $scope.getVenueOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-venue?status=card').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionVenue = result.data.venue;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue =  '暂无数据';
                $scope.venueStatus = false;
            }
        });
    };
    //获取通店场馆信息
    $scope.getApplyOptions = function () {
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
                $scope.serverShow = true;
            }else{
                $scope.optionServer = '暂无数据';
                $scope.serverStatus = false;
                $scope.serverShow = false;
            }

        });
    };
    //获取商品信息
    $scope.getShopOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-shopping-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionShop = result.data.venue;
                $scope.shopStatus   = true;
                $scope.shopShow = true;
            }else{
                $scope.optionShop   = '暂无数据';
                $scope.shopStatus   = false;
                $scope.shopShow = false;
            }

        });
    };
    //获取赠送商品信息
    $scope.getDonationOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            if(result.data.goods != undefined && result.data.goods != ""){
                $scope.optionDonation = result.data.goods;
                $scope.donationStatus       = true;
                $scope.donationShow = true;
            }else{
                $scope.optionDonation       = '暂无数据';
                $scope.donationStatus       = false;
                $scope.donationShow = false;
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
    //获取合同内容
    $scope.getDealId = function (data) {
        $scope.dealIds = data;
        $http.get('/contract/get-deal-one?id='+$scope.dealIds).then(function (result) {
            $scope.dataDeal = result.data.data;
        });
    };
    //售卖场馆改变触发事件（选过的场馆，下次禁用）现在此方法在视图页面没有用 ng-change="selectVenue(venueId)"
    // $scope.selectVenue     = function (id) {
    //     $scope.venueHttp.push(id);
    //     $scope.getVenueId();
    //     if($scope.sellVenue != undefined && $scope.sellVenue.length){
    //         $scope.venueHttp = $.array.arrayIntersection($scope.venueHttp,$scope.sellVenue);
    //     }
    // };
    //场馆改变触发事件
    $scope.selectApply     = function (id) {
        if (id && id != undefined){
            $scope.applyHttp.push(id);
        }
        $scope.getVenueApply();
        if($scope.currencyVenue != undefined && $scope.currencyVenue.length){
            $scope.applyHttp = $.array.arrayIntersection($scope.applyHttp,$scope.currencyVenue);
        }
    };
    //课程改变触发事件
    $scope.selectClass     = function (id) {
        if(id && id != undefined) {
            $scope.classHttp.push(id);
        }
        $scope.classServer();
        if($scope.leagueClass != undefined && $scope.leagueClass.length){
            $scope.classHttp = $.array.arrayIntersection($scope.classHttp,$scope.leagueClass);
        }
    };
    //服务改变触发事件
    $scope.selectServer     = function (id) {
        if(id && id != undefined) {
            $scope.serverHttp.push(id);
        }
        $scope.serverCombo();
        if($scope.service != undefined && $scope.service.length){
            $scope.serverHttp = $.array.arrayIntersection($scope.serverHttp,$scope.service);
        }
    };
    //商品改变触发事件
    $scope.selectShop     = function (id) {
        if(id && id != undefined) {
            $scope.shopHttp.push(id);
        }
        $scope.goodsPay();
        if($scope.goodsId != undefined && $scope.goodsId.length){
            $scope.shopHttp = $.array.arrayIntersection($scope.shopHttp,$scope.goodsId);
        }
    };
    //赠品改变触发事件
    $scope.selectDonation     = function (id) {
        if(id && id != undefined) {
            $scope.donationHttp.push(id);
        }
        $scope.donationGoods();
        if($scope.giftId != undefined && $scope.giftId.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.giftId);
        }
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
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.saleHtml = result.data.html;
            $scope.addDiscountHtml();
        });
    };

    //添加售卖折扣模板
    $scope.addDiscountHtml  = function () {
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.htmlAttr = 'discount123';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.discountHtml123 = result.data.html;
        });
    };
    //添加通店模板
    $scope.addApplyHtml = function () {
        //时间插件启动
        $('.clockpicker').clockpicker()
            .find('input').on('change',function() {
        });
        $scope.htmlAttr = 'applyVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.applyHtml = result.data.html;
        });
    };
    //添加课程模板
    $scope.addClassHtml = function () {
        $scope.htmlAttr = 'class';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.classHtml = result.data.html;
        });
    };
    //添加服务模板
    $scope.addServerHtml = function () {
        $scope.htmlAttr = 'server';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.serverHtml = result.data.html;
        });
    };
    //添加产品模板
    $scope.addShopHtml = function () {
        $scope.htmlAttr = 'shopping';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.shopHtml = result.data.html;
        });
    };
    //添加赠品模板
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'donation';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.donationHtml = result.data.html;
        });
    };
    //添加假期模板
    $scope.addLeaveHtml = function () {
        $scope.htmlAttr = 'leaveVenue';
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr).then(function (result) {
            $scope.leaveHtml = result.data.html;
        });
    };
    //公共处理数组
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
            }
        }
    };

    //定义售卖数组
    $scope.saleVenue = function () {
        $scope.sellVenue     = [];
        $scope.sheetsNum     = [];
        $scope.sellStartTime = [];
        $scope.sellEndTime   = [];
        $scope.discountArr   = [];
    };
    //定义通店数组
    $scope.applyVenue = function () {
        $scope.currencyVenue = [];
        $scope.currencyTimes = [];
        // $scope.applyGrade = [];
        $scope.openShopWeekChecked = [];
        $scope.applyStart    = [];     //通店开始时间
        $scope.applyEnd      = [];     //通店结束时间
        $scope.aboutLimit    = [];     //团课预约设置
    };
    //定义周数组
    $scope.weekFun = function () {
        $scope.week        = [];
        $scope.weekStartTime   = [];
        $scope.weekEndTime     = [];
    };
    //定义课程数组
    $scope.class = function () {
        $scope.leagueClass      = [];
        $scope.leagueTimes     = [];
    };
    //定义服务数组
    $scope.server   = function () {
        $scope.service     = [];
        $scope.serviceTimes    = [];
    };
    //定义商品数组
    $scope.goods     = function () {
        $scope.goodsId       = [];
        $scope.goodsNum   = [];
    };
    //定义赠品数组
    $scope.donation = function () {
        $scope.giftId  = [];
        $scope.giftNum = [];
    };
    //定义请假数组
    $scope.leaveArr  = function () {
        $scope.leaveTimes    = [];
        $scope.leaveDays   = [];
    };
    // 定义有效期默认值
    $scope.validTimeType = "1";
    //公共属性判断
    $scope.commonProof = function (data) {
        if(data == undefined || data == null){
            return false;
        }else{
            return true;
        }
    };
    //调用初始化函数
    $scope.init();

    //每月固定日
    $scope.getDays  = function () {
        var table   = angular.element(document.getElementById('day'));
        var td      = table.find('td.bColor');
        if(td.length != undefined && td.length != 0){
            $scope.day = [];
            td.each(function (i) {
                $scope.day.push(parseInt(td[i].innerHTML));
            })
        }else{
            $scope.day = [];
        }
    };
    //特定星期选择
    $scope.getWeek = function (id,child,attr) {
        var li    = angular.element(document.getElementById('week'));
        var input = li.find('input:checked');
        $scope.weekFun();
        if (input.length != undefined && input.length != 0) {
            input.each(function (i) {
                $scope.week.push(parseInt($(this).val()));
                $scope.weekStartTime.push($(this).parents('div.i-checks').find('span.timeSpan').html());
            })
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

    //售卖场馆
    $scope.getVenueId = function () {
        var select = $('div#sellVenue');
        var div    = select.children('div.inputBox1');
        $scope.saleVenue();
        if ($scope.commonProof(div)) {
            div.each(function (i) {
                $scope.discountNumArr = [];
                var $sellVenue = $(this).find('option:selected').val();
                var $sheetsNum = $(this).find("input[name='sheetsNum']").val();
                var   $checked    = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $discountBox  = $(this).find('.discountLists').children('.discountBox');//获取折扣box
                if ($sellVenue) {
                    $scope.sellVenue.push($sellVenue);
                    if ($sheetsNum || $checked) {
                        if($sheetsNum){
                            $scope.sheetsNum.push($sheetsNum);
                        }else{
                            $scope.sheetsNum.push(-1);
                        }
                    }else{
                        $scope.sheetsNum.push($sheetsNum);
                    }
                    $scope.sellStartTime.push($(this).find("input[name='sellStartTime']").val());
                    $scope.sellEndTime.push($(this).find("input[name='sellEndTime']").val());
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
        } else {
            $scope.saleVenue();
        }
    };
    
    //通用场馆
    $scope.getVenueApply = function () {
        var div   = $('div#venue');
        var apply = div.children('div.inputBox2');
        $scope.applyVenue();
        if ($scope.commonProof(apply)) {
            apply.each(function (i) {
                var $applyVenue = $(this).children(".divs1").find('option:selected').val();
                var $checked = $(this).children(".currencyTime").find('input').is(':checked');
                var $applyTimes = $(this).find("input[name='currencyTimes']").val();
                var $applyStart =$(this).find("input[name='applyStart']").val();
                var $applyEnd   =$(this).find("input[name='applyEnd']").val();
                var applyGrade = $(this).children(".divs2").find('option:selected').val();
                // var weekArray = $(this).find("input[name='currencyTimesr']").val();
                // var weekChecked = $(this).children(".currencyTimes").find('input').is(':checked');
                var week    = $(this).children(".month").find('option:selected').val();
                var $aboutLimit = $(this).children(".about").find('input').is(':checked');
                $scope.openShopWeekChecked.push(week);
                $scope.applyStart.push($applyStart);
                $scope.applyEnd.push($applyEnd);
                // $scope.applyGrade.push(applyGrade)
                if ($applyVenue) {
                    $scope.currencyVenue.push($applyVenue);
                    if ($checked || $applyTimes) {
                        if ($checked) {
                            $scope.currencyTimes.push(-1);
                        } else {
                            $scope.currencyTimes.push($applyTimes);
                        }
                    }
                    if($aboutLimit){
                        $scope.aboutLimit.push(-1);
                    }else{
                        $scope.aboutLimit.push(1);
                    }
                    // if(weekArray || weekChecked){
                    //     if(weekChecked){
                    //         $scope.weekArray.push(-1);
                    //     } else {
                    //         $scope.weekArray.push(weekArray);
                    //     }
                    // }
                }
            });
        } else {
            $scope.applyVenue();
        }
    };
    //绑定课程
    $scope.classServer = function () {
        var div = $('div#course');
        var course = div.find('div.inputBox3');
        $scope.class();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $select = $(this).find('option:selected').val();
                var $check  = $(this).find('input').is(':checked');
                var $class  = $(this).find("input[name='leagueTimes']").val();
                if($select){
                    $scope.leagueClass.push($select);
                    if($check || $class){
                        if($check){
                            $scope.leagueTimes.push(-1)
                        }else{
                            $scope.leagueTimes.push($class);
                        }
                    }else{
                        $scope.leagueTimes.push(-1)
                    }
                }
            });
        }else{
            $scope.class();
        }
    };
    //绑定服务
    $scope.serverCombo = function () {
        var div = $('div#server');
        var course = div.find('div.inputBox4');
        $scope.server();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected = $(this).find('option:selected').val();
                var $checked   = $(this).find('input').is(':checked');
                var $num       = $(this).find("input[name='serviceTimes']").val();
                if($selected){
                    $scope.service.push($selected);
                    if ($checked || $num){
                        if($checked){
                            $scope.serviceTimes.push(-1)
                        }else{
                            $scope.serviceTimes.push(parseInt($num));
                        }
                    }else{
                        $scope.serviceTimes.push(-1)
                    }
                }
            });
        }else{
            $scope.server();
        }
    };
    //绑定商品
    $scope.goodsPay = function () {
        var div = $('div#shopping');
        var course = div.find('div.inputBox5');
        $scope.goods();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected = $(this).find('option:selected').val();
                var $checked   = $(this).find('input').is(':checked');
                var $num       = $(this).find("input[name='goodsNum']").val();
                if($selected){
                    $scope.goodsId.push($selected);
                    if($checked || $num){
                        if($checked){
                            $scope.goodsNum.push(-1)
                        }else{
                            $scope.goodsNum.push(parseInt($num));
                        }
                    }else{
                        $scope.goodsNum.push(-1)
                    }
                }
            });
        }else{
            $scope.goods();
        }
    };
    //赠品
    $scope.donationGoods = function () {
        var div = $('div#gift');
        var course = div.find('div.inputBox6');
        $scope.donation();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected = $(this).find('option:selected').val();
                var $checked   = $(this).find('input').is(':checked');
                var $num       = $(this).find("input[name='giftNum']").val();
                if($selected){
                    $scope.giftId.push($selected);
                    if($checked || $num){
                        if($checked){
                            $scope.giftNum.push(-1)
                        }else{
                            $scope.giftNum.push(parseInt($num));
                        }
                    }else{
                        $scope.giftNum.push(-1)
                    }
                }
            });
        }else{
            $scope.donation();
        }
    };
    //请假
    $scope.getLeaveDays = function () {
        $scope.leaveSetDayArr = [];
        var div = $('div#leaveDay');
        var leaveDay = div.find('div.inputBox7');
        $scope.leaveArr();
        $scope.learveArr = [];
        if($scope.commonProof(leaveDay)){
            leaveDay.each(function (i) {
                var $day   = $(this).find("input[name='leaveDays']").val();
                var $times = $(this).find("input[name='leaveTimes']").val();
                if($day && $times){
                    $scope.learveArr.push($day);
                    $scope.learveArr.push($times);
                    $scope.leaveSetDayArr.push($scope.learveArr);
                    $scope.learveArr = [];
                }else{
                    $scope.leaveArr();
                }
            });
        }else{
            $scope.leaveArr();
        }
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

    //判断卡种名称是否存在
    $scope.setCardName = function () {
        $http.get('/member-card/set-card-name?name='+$scope.cardName +'&venueId=' + $scope.cardTheVenueId).success(function (result) {
            $scope.status = result.status;
        });
    };
    //根据选择场馆判断名称是否重复
    $scope.cardTheVenue = function(){
        $scope.setCardName();
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

    //云运动 - 前台 - 添加次卡信息(第一步表单数据接收)
    $scope.getOneData = function () {
        $scope.getWeek();
        $scope.getDays();
        $scope.getVenueId();
        $scope.getVenueApply();
        $scope.getAllValidRenew();
        return {
            attributes           : $scope.attributes,            //卡的属性
            cardName             : $scope.cardName,              //卡的名称
            anotherName          : $scope.anotherName,           //卡的别名
            cardTimes            : $scope.cardTimes,            //卡的次数
            timesMethod          : $scope.timesMethod,           //扣次方式
            activationTime       : $scope.activationTime,       //激活期限
            activationType       : $scope.activationType,       //激活期限类型
            validTime            : $scope.validTime,             //有效天数
            validTimeType        : $scope.validTimeType,             //有效天数类型
            pic                  : $scope.pic,                    //图片
            originalPrice        : $scope.originalPrice,         //一口原价
            sellPrice            : $scope.sellPrice,              //一口售价
            regionOriginalPrice  : $scope.regionOriginalPrice,   //区域原价
            regionSellPrice      : $scope.regionSellPrice,      //区域售价
            onceRenew            : $scope.onceRenew,            //单次续费价
            offerPrice           : $scope.offerPrice,            //优惠价
            appSellPrice         : $scope.appSellPrice,      //移动端售价
            renewUnit            : $scope.renewUnit,       //续费多长时间
            ordinaryRenewal      :$scope.OrdinaryRenewal,  //普通续费
            validityRenewal      :$scope.AllValidRenewArr, //有效期续费数组
            sellVenue            : $scope.sellVenue.length != 0 ? $scope.sellVenue : [],            //售卖场馆
            sheetsNum            : $scope.sheetsNum,            //售卖张数
            sellStartTime        : $scope.sellStartTime,        //售卖开始时间
            sellEndTime          : $scope.sellEndTime,          //售卖结束时间
            discount              :$scope.discountArr,         //售卖折扣
            currencyVenue        : $scope.currencyVenue,         //通用场馆
            currencyTimes        : $scope.currencyTimes,         //通店次数
            level                 :$scope.applyGrade,
            applyStart            : $scope.applyStart,         //通店开始时间
            applyEnd              : $scope.applyEnd,           //通店结束时间
            aboutLimit           : $scope.aboutLimit,          //团课预约设置
            single                :$scope.Singular,//单数
            venueId               : $scope.cardTheVenueId,//卡种所属场馆
            applyType             :$scope.openShopWeekChecked,
            cardType              : $scope.cardType,//卡的类型
            day                  : $scope.day,                     //特定日
            dayStartTime         : $scope.dayStartTime,          //特定日的开始时间
            dayEndTime           : $scope.dayEndTime,           //特定日的结束时间
            week                 : $scope.week,                  //周
            weekStartTime        : $scope.weekStartTime,         //周开始时间
            weekEndTime          : $scope.weekEndTime,            //周结束时间
            cardStartTime        : $scope.cardStartTime,        //卡的开始时间
            cardEndTime          : $scope.cardEndTime,          //卡的结束时间
            scenario             : $scope.scenario,             //操作步骤一
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
        if($scope.regionOriginalPrice != undefined || $scope.regionSellPrice != undefined){
            $scope.disabled = true;
        }else{
            $scope.disabled = false;
        }
    };
    //特定星期不可填写
    $scope.setDayTime = function () {
        var $weekSelect = angular.element(document.getElementById('week'));
        var $table = $('#day');
        var $dayCheck = $table.find('.bColor');
        var $sum = $dayCheck.length - 1;
        if($scope.dayStartTime != undefined || $scope.dayEndTime != undefined){
            $weekSelect.find('input').iCheck('disable',true);
            $weekSelect.find('input').css('left','23px').css('z-index','1000');
        }
        if(($scope.dayStartTime == "" || $scope.dayStartTime == undefined) && ($scope.dayEndTime == "" || $scope.dayEndTime == undefined) && $sum == -1){
            $weekSelect.find("input[name='weeksTime']").removeAttr('disabled').css('left','0').css('z-index','0');
            $weekSelect.find('div.checkBoxClick').removeAttr('disabled');
        }
    };

    //计算总金额
    $scope.calculation = function (cardTimes,sellPrice) {
        if(cardTimes == undefined){
            cardTimes = null;
            $scope.totalPrice = 0;
            return true;
        }
        if(cardTimes != undefined && sellPrice != undefined){
            $scope.totalPrice = cardTimes * sellPrice;
        }else {
            $scope.totalPrice = 0;
        }
    };
    //验证第一步字段
    $scope.oneRules = function (data) {
        if($scope.cardTheVenueId == '' || $scope.cardTheVenueId == null ||$scope.cardTheVenueId == undefined){
            Message.warning("请选择卡种所属场馆");
            return;
        }
        if(!$scope.commonRule(data.attributes,'请选择卡种属性！')){ return false; }
        if(!$scope.commonRule(data.cardName,'卡种名称不能为空！')){ return false; }
        if($scope.status == 'error'){ Message.warning('卡名称已经存在'); return false; }
        if(!$scope.cardNameLength(data.cardName,'卡种名称不能少于两个字')){ return false; }
        if(!$scope.commonRule(data.cardTimes,'请填写卡种次数！')){ return false; }
        // if(!$scope.commonRule(data.single,'请填写单数！')){ return false; }
        // if($("#Singular").val() == '' || $("#Singular").val()  == undefined || $("#Singular").val()   == null ){
        //     Message.warning("单数不能为空");
        //     return;
        // }
        
        if(!$scope.commonRule(data.timesMethod,'请选择扣次方式！')){ return false; }
        if(!$scope.commonRule(data.validTime,'有效时间不能为空！')){ return false; }
        if(!$scope.commonRule(data.sellVenue,'请选择售卖场馆！')){ return false; }
        if(!$scope.commonRule(data.sheetsNum,'请填写售卖张数！')){ return false; }
        if(!$scope.commonRule(data.currencyVenue,'请选择通用场馆！')){ return false; }
        if(!$scope.commonRule(data.currencyTimes,'请填写售卖张数！')){ return false; }
        // if(!data.regionOriginalPrice){
        //     if (!data.originalPrice || !data.sellPrice){
        //         $scope.commonRule('','请填写一口价或者区间价');return false;
        //     }
        // }

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
        if($('#OrdinaryRenewal').val() == ''){
            Message.warning("普通续费不能为空");
            return;
        }
        if($scope.regionOriginalPrice != '' && $scope.regionSellPrice != ''){
            if($scope.regionSellPrice < $scope.regionOriginalPrice){
                Message.warning('最高价不能低于最低价');
                return;
            }
        }
        if(!$scope.twoCommonRules(data.sellVenue,data.sheetsNum,'请填写相应的售卖张数')){ return false; }
        if(!$scope.venueRule(data.sellVenue,data.sheetsNum,data.sellStartTime,data.sellEndTime,'请填写售卖场馆数据')){return false;}
        if(!$scope.sellDateRule(data.sellStartTime,'请填写售卖开始日期',data.sellEndTime,'请填写售卖结束日期')){return false;}
        // if(!$scope.twoCommonRules(data.currencyVenue,data.currencyTimes,'请填写相应的通店次数')){ return false; }
        // if(!$scope.applyVenueRule(data.currencyVenue,data.currencyTimes,'请填写通用场馆数据')){ return false; }
        if(!$scope.getFindNumberTime(data.dayStartTime,data.dayEndTime)){
            if(!$scope.getDayStatus(data.day)){
                if(!$scope.commonRule('','请先选择固定日')){ return false;}
            }else{
                if(!$scope.commonRule('','特定天的结束时间点不正确')){ return false;}
            }
        }
        if(!$scope.getFindNumberTime(data.cardStartTime,data.cardEndTime)){
            if(!$scope.commonRule('','结束时间点不正确')){ return false;}
        }
        if(!$scope.getFindNumberWeekTime(data.weekStartTime)){
            if(!$scope.commonRule('','特定星期的结束时间点不正确')){ return false;}
        }
        if(data.applyStart != '' && data.applyEnd != ''){
            if(!$scope.getFindNumberTime(data.applyStart,data.applyEnd)){
                if(!$scope.commonRule('','通用场馆进馆时间的结束时间点不正确')){ return false;}
            }
        }
        return true;
    };
    //判断特定天是否存在
    $scope.getDayStatus = function (day) {
        var length = day.length;
        if(length == 0){
            return false;
        }else{
            return true;
        }
    };
    //判断开始时间点和结束时间点是否正确
    $scope.getFindNumberTime            = function (start,end) {
        var dateTime    = new Date().toLocaleDateString();         //获取今天日期2017/4/24
        var startTime   = dateTime +" "+ start;                    //拼接开始日期时间
        var endTime     = dateTime +" "+ end;                      //拼接结束日期时间
        var startTimes  = Date.parse(new Date(startTime));         //开始日期格式化时间戳
        startTimes = startTimes / 1000;
        var endTimes     = Date.parse(new Date(endTime));          //结束日期格式化时间戳
        endTimes    = endTimes / 1000;
        if(startTimes != "NaN" && endTimes != "NaN"){
            if(startTimes > endTimes){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    };
    //判断周的开始时间点和结束时间点是否正确
    $scope.getFindNumberWeekTime            = function (start) {
        var length = start.length;
        for(var i=0; i<length;i++){
            if(start[i] || start[i] != ""){
                if(!$scope.getFindNumberTime(start[i].substr(0,5),start[i].substr(-5,5))){
                    return false;
                }
            }else{
                return true;
            }
        }
        return true;
    };
    //卡名长度判断
    $scope.cardNameLength = function (cardName,text) {
        if(cardName.length < 2) {
            Message.warning(text);return false;
        }
        return true;
    };
    //售卖场馆判断
    $scope.venueRule = function(venueId,sheets,saleStart,saleEnd,text){
        if(venueId.length==0||sheets.length==0||saleStart.length==0||saleEnd.length==0){
            Message.warning(text);return false;
        }
        return true;
    };
    //通用场馆判断
    $scope.applyVenueRule = function(applyVenueId,applyTimes,text){
        if(applyVenueId != undefined){
            if(applyVenueId.length==0||applyTimes.length==0){
                Message.warning(text);return false;
            }
        }
        return true;
    };
    //定义售卖日期验证方法
    $scope.sellDateRule           = function (attr1,text1,attr2,text2) {
        if(!attr1[(attr1.length - 1) ] || attr1[(attr1.length - 1)]==""){
            $scope.commonRule('',text1);return false;
        }else if(!attr2[(attr2.length -1)] || attr2[(attr2.length - 1)]==""){
            $scope.commonRule('',text2);return false;
        }
        return true;
    };

    //云运动 - 前台 - 添加次卡信息(第二步表单数据接收)
    $scope.getTwoData = function () {
        $scope.classServer();
        $scope.serverCombo();
        $scope.goodsPay();
        $scope.donationGoods();
        return {
            leagueClass      : $scope.leagueClass,       //课程名称
            leagueTimes      : $scope.leagueTimes,       //每日课程节数
            service           : $scope.service,           //服务名称
            serviceTimes     : $scope.serviceTimes,      //每日服务数量
            goodsId           : $scope.goodsId,         //扣次项目名称
            goodsNum          : $scope.goodsNum,          //扣次数量
            giftId            : $scope.giftId,              //赠品名称
            giftNum           : $scope.giftNum,           //赠品数量
            scenario          : $scope.scenario,          //操作步骤二
            //绑定私课课程
            hsId                :$('#HSClass').val(),                 //hs课程id
            hsNum               : $scope.HSClassNum  != undefined && $scope.HSClassNum  != ''? $scope.HSClassNum :null,             //hs课程节数
            ptId                : $('#PTClass').val(),                //pt课程id
            ptNum               : $scope.PTClassNum != undefined && $scope.PTClassNum != '' ? $scope.PTClassNum : null,             //pt课程节数
            birthId             : $('#BirthdayClass').val(),         //生日课程id
            birthNum            : $scope.birthClassNum  != undefined && $scope.birthClassNum != '' ? $scope.birthClassNum : null,         //生日课程节数
            _csrf_backend     : $('#_csrf').val()
        }
    };

    //验证第二步字段
    $scope.twoRules = function (data) {
        if(!$scope.twoCommonRules(data.leagueClass,data.leagueTimes,'请填写相应的课程名称或课程节数')){ return false; }
        if(!$scope.twoCommonRules(data.service,data.serviceTimes,'请填写相应的服务名称或服务数量')){ return false; }
        if(!$scope.twoCommonRules(data.goodsId,data.goodsNum,'请填写相应的商品名称或商品数量')){ return false; }
        if(!$scope.twoCommonRules(data.giftId,data.giftNum,'请填写相应的赠品名称或赠品数量')){ return false; }
        return true;
    };


    //请假次数不可填写
    $scope.setLeaveNumDisabled1 = function () {
        if($scope.totalLeaveDays != undefined || $scope.minLeaveDays != undefined){
            $scope.leaveNumsFlag = true;
        }
        if($scope.totalLeaveDays == undefined && $scope.minLeaveDays == undefined){
            $scope.leaveNumsFlag = false;
        }
    };
    //请假天数不可填写
    $scope.setLeaveDaysDisabled1 = function () {
        if($scope.leaveNums != undefined || $scope.everyLeaveDays != undefined){
            $scope.leaveDaysFlag1 = true;
        }else{
            $scope.leaveDaysFlag1 = false;
        }
    };

    //云运动 - 前台 - 添加次卡信息(第三步表单数据接收)
    $scope.getThreeData = function () {
        $scope.getLeaveDays();
        return  {
            transferTimes  : $scope.transferTimes,   //转让次数
            transferPrice  : $scope.transferPrice,   //转让金额
            totalLeaveDays : $scope.totalLeaveDays,  //请假总天数
            minLeaveDays   : $scope.minLeaveDays,    //每次最低天数
            leaveSetDayArr  : $scope.leaveSetDayArr, //请假次数、每次请假天数
            scenario        : $scope.scenario,         //操作步骤三
            _csrf_backend   : $('#_csrf').val()
        }
    };
    //验证第三步字段
    $scope.threeRules = function (data) {
        if(!$scope.twoCommonRules(data.leaveTimes,data.leaveDays,'请填写相应的请假天数')){ return false; }
        return true;
    };
    //云运动 - 前台 - 添加次卡信息(第四步表单数据接收)
    $scope.getFourData = function () {
        return  {
            deal             : $scope.dealId,     //合同
            scenario         : $scope.scenario,
            _csrf_backend   : $('#_csrf').val()
        }
    };
    // //验证第四步字段
    // $scope.fourRules = function (data) {
    //     if(!$scope.commonRule(data.deal,'请选择合同')){ return false; }
    //     return true;
    // };

    //云运动 - 前台 - 卡种信息添加（取消操作）
    $scope.getCancelData = function () {
        return  {
            scenario        : $scope.scenario,        //操作步骤
            _csrf_backend   : $('#_csrf').val()
        }
    };

    //云运动 - 前台 - 添加次卡信息(和后台数据交互)
    $scope.setHttp = function () {
        $http({
            url        : '/member-card/times-card',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if (result.data.status == 'success') {
                // Message.success('添加成功');
            } else if (result.data.status == 'cancel') {
                // Message.success('取消成功');
                setTimeout("window.location.replace('/member-card/index')",1000);
            } else{
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                })
            }
        });
    };
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
        //     if(!$scope.fourRules(data)){return false;}
        }
        // else{
        //     if(!$scope.fourRules(data)){return false;}
        // }
        return true;
    };
    //获取第二步模板
    // $rootScope.getTwoTemplate = function () {
    //     $scope.classKey    = "";
    //     $scope.serverKey   = "";
    //     $scope.shopKey     = "";
    //     $scope.donationKey = "";
    //     $scope.addClassHtml();
    //     $scope.addServerHtml();
    //     $scope.addDonationHtml();
    //     $scope.addShopHtml();
    //     $scope.addLeaveHtml();
    //     $scope.getClassOptions();
    //     $scope.getServerOptions();
    //     $scope.getShopOptions();
    //     $scope.getDonationOptions();
    // };

    //每一步调用
    $rootScope.onStepChange = function (index) {
        if (index == 1) {
            $('#HSClass').select2({
                // width:'100%'
            });
            $('#PTClass').select2({
                // width:'100%'
            });
            $('#BirthdayClass').select2({
                // width:'100%'
            });
            $scope.scenario  = 'one';
            $scope.oneParams = $scope.getOneData();
            if(!$scope.assignParams($scope.oneParams,'one')){return false;}
        } else if (index == 2) {
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
            $scope.scenario  = 'two';
            $scope.twoParams = $scope.getTwoData();
            if(!$scope.assignParams($scope.twoParams,'two')){return false;}
        } else if (index == 3) {
            //判断每次请假天数不能大于总天数
            if($scope.totalLeaveDays != undefined && $scope.totalLeaveDays != 0 && ($scope.minLeaveDays == undefined || $scope.minLeaveDays == 0)){
                Message.warning('每次最低天数不能为0');
                return false;
            }else if($('#totalLeaveDays').val() < $('#minLeaveDays').val()){
                Message.warning('每次最低天数不能大于请假总天数');
                $('#minLeaveDays').val('');
                //$('.actions > ul').find('li').eq(1).prop('disabled',true);
                return false;
            }
            $scope.scenario    = 'three';
            $scope.threeParams = $scope.getThreeData();
            if(!$scope.assignParams($scope.threeParams,'three')){return false;}
        } else if (index == 4) {
            $scope.scenario   = 'four';
            $scope.fourParams = $scope.getFourData();
            if(!$scope.assignParams($scope.fourParams,'four')){return false;}
        } else if (index == -1) {
            $scope.scenario     = 'cancel';
            $scope.params = $scope.getCancelData();
        } else {
            $scope.scenario   = 'finish';
            $scope.fourParams = $scope.getFourData();
            // if(!$scope.assignParams($scope.fourParams,'four')){return false;}
        }
        $scope.setHttp();
        return true;
    };

}).run(function ($rootScope) {
    $rootScope.onStepChanging = function () {
        return {
            //1.每一步表单验证
            onStepChanging: function (event, currentIndex, newIndex) {
                // if(currentIndex == 1){
                //     $rootScope.getTwoTemplate();
                // }
                // var index = currentIndex + 1;
                if(!$rootScope.onStepChange(newIndex)){
                    return false;
                }
                return true;
            },
            //2.每一步提交数据
            onStepChanged: function (event, currentIndex, priorIndex) {
                var $boxHeight =  $('.formBox1').eq(currentIndex).outerHeight();
                $('.wizard > .content').height($boxHeight + 100);
                if(currentIndex == 3){
                    $('.wizard > .content').height(800);
                }

            },
            //3.完成前验证
            onFinishing: function (event, currentIndex) {
                var index = currentIndex + 1;
                if(index == 4){
                    if(!$rootScope.onStepChange(index)){
                        return false;
                    }
                }

                return true;
            },
            //4.完成跳转
            onFinished: function (event, currentIndex) {
                if(currentIndex == 3){
                    $.loading.show();
                    location.href = '/member-card/index';
                }
            },
            //5.点击取消
            onCanceled : function(event){
                var index = '-1';
                $rootScope.onStepChange(index);
            }
        };
    };

    var setting = $rootScope.onStepChanging();
    $("#wizard").steps(setting);
    $("#form").steps({
        bodyTag: "fieldset",
        onStepChanging: function (event, currentIndex, newIndex) {
            // Always allow going backward even if the current step contains invalid fields!
            if (currentIndex > newIndex) {
                return true;
            }

            // Forbid suppressing "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age").val()) < 18) {
                return false;
            }

            var form = $(this);

            // Clean up if user went backward before
            if (currentIndex < newIndex) {
                // To remove error styles
                $(".body:eq(" + newIndex + ") label.error", form).remove();
                $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
            }

            // Disable validation on fields that are disabled or hidden.
            form.validate().settings.ignore = ":disabled,:hidden";

            // Start validation; Prevent going forward if false
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            //点击每一步计算当前内容高度
            var $boxHeight =  $('.formBox1').eq(currentIndex).outerHeight();
            alert($boxHeight);
            $('.wizard > .content').eq(currentIndex).height($boxHeight + 100);
            // Suppress (skip) "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age").val()) >= 18) {
                $(this).steps("next");
            }

            // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                $(this).steps("previous");
            }
        },
        onFinishing: function (event, currentIndex) {
            var form = $(this);

            // Disable validation on fields that are disabled.
            // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
            form.validate().settings.ignore = ":disabled";

            // Start validation; Prevent form submission if false
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            var form = $(this);

            // Submit form input
            form.submit();
        }
    }).validate({
        errorPlacement: function (error, element) {
            element.before(error);
        },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }
    });

});