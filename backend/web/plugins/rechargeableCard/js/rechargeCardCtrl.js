/**
 * Created by Administrator on 2017/4/14.
 * content 充值卡页面控制器
 * author :程丽明
 */
var app = angular.module('App');
app.controller('rechargeCardCtrl',function($scope,$http,$rootScope,Upload){
    $.loading.show();                                       //打开页面
    //初始化方法 加载控制器执行方法
    $scope.init = function () {
        $scope.renewUnit   = '30';
        $scope.total_price = 0;
        $scope.num         = 0;
        $scope.venueHttp   = [];
        $scope.venueArr    = [];
        $scope.applyHttp   = [];
        $scope.classHttp   = [];
        $scope.serverHttp   = [];
        $scope.shopHttp     = [];
        $scope.donationHttp = [];
        $scope.weekArray = [];
        $scope.venueId     = "";
        $scope.leaveNums   = "";
        $scope.everyLeaveDays = "";
        $scope.leaveNumsFlag = false;
        $scope.leaveDaysFlag = false;
        $scope.getCardTheVenue();
        $scope.leaveArr();
        $scope.applyVenue();
        $scope.saleVenue();
        $scope.donation();
        $scope.shop();
        $scope.server();
        $scope.class();
        $scope.addVenueHtml();
        $scope.addApplyHtml();
        $scope.getVenueOptions();
        $scope.getApplyOptions();
        $scope.durationType   = '1';
        $scope.activeTimeType = '1';
        $scope.deal           = '1';
        $scope.status    = "";
        $scope.addCardValidityHtml();//添加有效期模板
        $scope.getCardAttr();           //获取卡种属性
        $scope.getDeal();               //卡种合同
        $scope.classKey    = "";
        $scope.serverKey   = "";
        $scope.shopKey     = "";
        $scope.donationKey = "";
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
                $scope.optionVenue = '暂无数据';
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
                $scope.optionApply  = '暂无数据';
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
    $scope.commonRemoveId = function (id,data) {
        data.splice($.inArray(id,data), 1);
        return data;
    };
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
    //获取服务信息
    $scope.getServerOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-server-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionServer = result.data.venue;
                $scope.serverShow = true;
                $scope.serverStatus = true;
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
    //     if($scope.venueIds != undefined && $scope.venueIds.length){
    //         $scope.venueHttp = $.array.arrayIntersection($scope.venueHttp,$scope.venueIds);
    //     }
    // };
    //场馆改变触发事件
    $scope.selectApply     = function (id) {
        if(id && id !=  undefined){
            $scope.applyHttp.push(id);
        }
        $scope.getVenueApply();
        if($scope.applyVenueId != undefined && $scope.applyVenueId.length){
            $scope.applyHttp = $.array.arrayIntersection($scope.applyHttp,$scope.applyVenueId);
        }
    };
    //课程改变触发事件
    $scope.selectClass     = function (id) {
        if(id && id !=  undefined) {
            $scope.classHttp.push(id);
        }
        $scope.classServer();
        if($scope.classId != undefined && $scope.classId.length){
            $scope.classHttp = $.array.arrayIntersection($scope.classHttp,$scope.classId);
        }
    };
    //服务改变触发事件
    $scope.selectServer     = function (id) {
        if(id && id !=  undefined) {
            $scope.serverHttp.push(id);
        }
        $scope.serverCombo();
        if($scope.serverId != undefined && $scope.serverId.length){
            $scope.serverHttp = $.array.arrayIntersection($scope.serverHttp,$scope.serverId);
        }
    };
    //商品改变触发事件
    $scope.selectShop     = function (id) {
        if(id && id !=  undefined) {
            $scope.shopHttp.push(id);
        }
        $scope.shopPay();
        if($scope.shopId != undefined && $scope.shopId.length){
            $scope.shopHttp = $.array.arrayIntersection($scope.shopHttp,$scope.shopId);
        }
    };
    //赠品改变触发事件
    $scope.selectDonation     = function (id) {
        if(id && id !=  undefined) {
            $scope.donationHttp.push(id);
        }
        $scope.donationGoods();
        if($scope.donationId != undefined && $scope.donationId.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.donationId);
        }
    };
    //定义赠送数组
    $scope.donation = function () {
        $scope.donationId  = [];
        $scope.donationNum = [];
    };
    //定义请假数组
    $scope.leaveArr  = function () {
        $scope.leaveDays    = [];
        $scope.leaveTimes   = [];
    };
    //定义产品数组
    $scope.shop     = function () {
        $scope.shopId       = [];
        $scope.shopNum      = [];
    };
    //定义服务数组
    $scope.server   = function () {
        $scope.serverId     = [];
        $scope.serverNum    = [];
    };
    //定义课程数组
    $scope.class = function () {
        $scope.classId      = [];
        $scope.pitchNum     = [];
    };
    //定义售卖数组
    $scope.saleVenue = function () {
        $scope.venueIds    = [];
        $scope.sheets      = [];
        $scope.saleStart   = [];
        $scope.saleEnd     = [];
        $scope.discountArr = [];
        $scope.discountNumArr = [];
    };
    //定义通店数组
    $scope.applyVenue = function () {
        $scope.applyVenueId = [];
        $scope.applyTimes   = [];
        $scope.applyGrade = [];
        $scope.openShopWeekChecked = [];
    };
    //定义周数组
    $scope.weekFun = function () {
        $scope.week        = [];
        $scope.weekStart   = [];
        $scope.weekEnd     = [];
    };
    //公共属性判断
    $scope.commonProof = function (data) {
        if(data == undefined || data == null){
            return false;
        }else{
            return true;
        }
    };
    //计算总金额
    $scope.calculation = function () {
          if($scope.rechargePrice == undefined){
              $scope.rechargePrice = null;
              $scope.total_price = 0;
              return true;
          }
          if($scope.rechargePrice != undefined && $scope.rechargeGivePrice != undefined){
               $scope.total_price = $scope.rechargePrice + $scope.rechargeGivePrice;
          }else if($scope.rechargePrice != undefined && $scope.rechargeGivePrice == undefined){
              $scope.total_price = $scope.rechargePrice;
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
    //添加售卖模板
    $scope.addVenueHtml  = function () {
        $scope.htmlAttr = 'saleVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.saleHtml = result.data.html;
            $scope.addDiscountHtml();
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
        $scope.htmlAttr = 'applyVenue';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.applyHtml = result.data.html;
        });
    };
    //添加课程模板
    $scope.addClassHtml = function () {
        $scope.htmlAttr = 'class';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.classHtml = result.data.html;
        });
    };
    //添加服务模板
    $scope.addServerHtml = function () {
        $scope.htmlAttr = 'server';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.serverHtml = result.data.html;
        });
    };
    //添加产品模板
    $scope.addShopHtml = function () {
        $scope.htmlAttr = 'shopping';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.shopHtml = result.data.html;
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

    //调用初始化函数
    $scope.init();
    //js获取天数数组
    $scope.getDays    = function () {
        var table   = angular.element(document.getElementById('table'));
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
    //js获取周数数组
    $scope.getWeek = function () {
        var li    = $('ul.weekSelect');
        var input = li.find('input:checked');
        $scope.weekFun();
        if(input.length != undefined && input.length != 0){
            input.each(function (i) {
                $scope.week.push(parseInt($(this).val()));
                $scope.weekStart.push($(this).parents('div.week').next().html());
            })
        }
    };
    //js获取售卖数组
    $scope.getVenueId = function () {
        var select = $('div#sellVenue');
        var div    = select.children('div.sellVenueLists123');

        $scope.saleVenue();
        if($scope.commonProof(div)){

            div.each(function (i) {
                // $scope.discountArr = [];
                $scope.discountNumArr = [];
                var $saleVenue = $(this).find('option:selected').val();//获取场馆id
                var $sheet     = $(this).find("input[name='sheets']").val();//售卖张数
                var $checked    = $(this).find('div.icheckbox_square-green').hasClass('checked');//是否不限
                var $discountBox  = $(this).children('.discountLists').children('.discountBox');//获取折扣box
                if($saleVenue){
                    $scope.venueIds.push($saleVenue);
                    if($sheet || $checked){
                        if($sheet){
                            $scope.sheets.push($sheet);
                        }else{
                            $scope.sheets.push(-1);
                        }
                    }else{
                        if($sheet != ''){
                            $scope.sheets.push($sheet);
                        }
                    }
                    $scope.saleStart.push($(this).find("input[name='start']").val());
                    $scope.saleEnd.push($(this).find("input[name='end']").val());
                    
                }$discountBox.each(function(i){
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
            });
        }else{
            $scope.saleVenue();
        }
    };

    //js获取通店数组
    $scope.getVenueApply = function () {
        var div   = $('div#venue');
        var apply = div.children('div.clearfix');
        $scope.applyVenue();
        if($scope.commonProof(apply)){
            apply.each(function (i) {
                var $applyVenue = $(this).children(".pRelative").find('option:selected').val();
                var $checked    = $(this).children(".month").find('div.icheckbox_square-green').hasClass('checked');
                var $applyTime  = $(this).find("input[name='times']").val();
                var applyGrade = $(this).children(".times1").find('option:selected').val();
                var week    = $(this).children(".month").find('option:selected').val();
                $scope.openShopWeekChecked.push(week);
                // var $weekChecked  = $(this).children(".month2").find('div.icheckbox_square-green').hasClass('checked');
                // var $weekArray =  $(this).find("input[name='weeks']").val();
                //
                // $scope.applyGrade.push(applyGrade)

                if($applyVenue){
                    $scope.applyVenueId.push($applyVenue);
                    if($checked  || $applyTime){
                        if($checked){
                            $scope.applyTimes.push(-1)
                        }else{
                            $scope.applyTimes.push($applyTime);
                        }
                    }
                //     if($weekChecked  || $weekArray){
                //         if($weekChecked){
                //             $scope.weekArray.push(-1)
                //         }else{
                //             $scope.weekArray.push($weekArray);
                //         }
                //     }else {
                //         $scope.weekArray.push($weekArray);
                //     }
                }
            });
        }else{
            $scope.applyVenue();
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
                var $check  = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $class  = $(this).find("input[name='times']").val();
                if($select){
                    $scope.classId.push($select);
                    if($check || $class){
                        if($check){
                            $scope.pitchNum.push(-1)
                        }else{
                            $scope.pitchNum.push($class);
                        }
                    }else{
                        $scope.pitchNum.push(-1)
                    }
                }
            });
        }else{
            $scope.class();
        }
    };
    //js获取服务数组
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
                            $scope.serverNum.push(-1)
                        }else{
                            $scope.serverNum.push(parseInt($num));
                        }
                    }else{
                        $scope.serverNum.push(-1)
                    }
                }
            });
        }else{
            $scope.server();
        }
    };
    //js获取产品数组
    $scope.shopPay = function () {
        var div = $('div#shopping');
        var course = div.children('div.clearfix');
        $scope.shop();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected = $(this).find('option:selected').val();
                var $checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $num       = $(this).find("input[name='times']").val();
                if($selected){
                    $scope.shopId.push($selected);
                    if($checked || $num){
                        if($checked){
                            $scope.shopNum.push(-1)
                        }else{
                            $scope.shopNum.push(parseInt($num));
                        }
                    }else{
                        $scope.shopNum.push(-1)
                    }
                }
            });
        }else{
            $scope.shop();
        }
    };
    //js获取赠送数组
    $scope.donationGoods = function () {
        var div = $('div#donation');
        var course = div.children('div.clearfix');
        $scope.donation();
        if($scope.commonProof(course)){
            course.each(function (i) {
                var $selected = $(this).find('option:selected').val();
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
                        $scope.donationNum.push(-1)
                    }
                }
            });
        }

    };
    //定义公共验证方法
    $scope.commonRule = function (attr,text) {
        if(!attr){Message.warning(text);return false;}return true;
    };
    //js获取假期数组
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
                    $scope.leaveDays.push($scope.learve);$scope.learve = [];
                }
            });
        }else{
            $scope.learve = [];
        }

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
    }


    //请假次数不可填写
    $scope.setLeaveNumDisabled = function () {
        if($scope.leaveDaysTotal != undefined || $scope.leaveTimesTotal != undefined){
            $scope.leaveNumsFlag = true;
        }
        if($scope.leaveTimesTotal == undefined && $scope.leaveDaysTotal == undefined){
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
        $scope.getWeek();
        $scope.getDays();
        $scope.getVenueId();
        $scope.getVenueApply();
        return  {
            attributes            : $scope.attributes,        //属性
            cardName              : $scope.cardName,          //名称
            anotherName           : $scope.anotherName,       //卡别名
            activeTime            : $scope.activeTime,        //激活期限
            activeTimeType        : $scope.activeTimeType,    //激活类型
            rechargePrice         : $scope.rechargePrice,      //充值金额
            rechargeGivePrice     : $scope.rechargeGivePrice,  //充值赠送金额
            pic                   : $scope.pic,                //图片
            originalPrice         : $scope.originalPrice,     //原价
            sellPrice             : $scope.sellPrice,         //售价
            minPrice              : $scope.minPrice,          //最低价
            maxPrice              : $scope.maxPrice,          //最高价
            renewPrice            : $scope.renewPrice,        //续费价
            offerPrice            : $scope.offerPrice,        //优惠价
            appSellPrice          : $scope.appSellPrice,      //移动端售价
            renewUnit             : $scope.renewUnit,         //续费多长时间
            duration              : $scope.duration,          //有效时间
            durationType          : $scope.durationType,      //有效时间类型
            hoursStart            : $scope.hoursStart,        //每天开始时间段
            hoursEnd              : $scope.hoursEnd,          // 结束时间段
            applyVenueId          : $scope.applyVenueId,      // 通店ID
            applyTimes            : $scope.applyTimes,        // 通店次数
            level: $scope.applyGrade,
            single:$scope.Singular,
            applyType :$scope.openShopWeekChecked,
            venueId               : $scope.cardTheVenueId,//卡种所属场馆
            cardType              : $scope.cardType,//卡的类型
            day                   : $scope.day,               //选择天数
            week                  : $scope.week,              // 选择周数
            weekStart             : $scope.weekStart,          //周开始的几点
            weekEnd               : $scope.weekEnd,            //周结束的几点
            scenario              : $scope.scenario,           //场景
            venueIds              : $scope.venueIds.length != 0 ? $scope.venueIds : [],           //售卖场馆ID
            sheets                : $scope.sheets,            //张数
            saleStart             : $scope.saleStart,          //售卖开始日期
            saleEnd               : $scope.saleEnd,            //售卖开始日期
            discount              :$scope.discountArr,         //售卖折扣
            ordinaryRenewal      :$scope.OrdinaryRenewal,  //普通续费
            validityRenewal      :$scope.AllValidRenewArr, //有效期续费数组
            start                 : $scope.start,              //通用开始时间段
            end                   : $scope.end,                //通用时间段
            _csrf_backend         : $('#_csrf').val()
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
    //js数据校验
    $scope.oneRules = function (data) {
        if($scope.cardTheVenueId == '' || $scope.cardTheVenueId == null ||$scope.cardTheVenueId == undefined){
            Message.warning("请选择卡种所属场馆");
            return;
        }
        if($('#rechargeCardType123').val() == ''){
            Message.warning("请选择卡种类型");
            return;
        }
        if(!$scope.commonRule(data.attributes,'属性不能为空')){ return false; }
        if(!$scope.commonRule(data.cardName,'卡种名称不能为空')){ return false; }
        if(!$scope.commonRule(data.rechargePrice,'充值金额不能为空')){ return false; }
        // if(!$scope.commonRule(data.single,'输入单数')){ return false; }
        // if($("#Singular").val() == '' || $("#Singular").val()  == undefined || $("#Singular").val()   == null ){
        //     Message.warning("单数不能为空");
        //     return;
        // }

        if($scope.OrdinaryRenewal == '' || $scope.OrdinaryRenewal == undefined || $scope.OrdinaryRenewal == null ){
            Message.warning("普通续费不能为空");
            return;
        }
        if($scope.minPrice != '' && $scope.maxPrice != ''){
            if($scope.maxPrice < $scope.minPrice){
                Message.warning('最高价不能低于最低价');
                return;
            }
        }
        if(!$scope.commonRule(data.duration,'有效时间不能为空')){ return false; }
        if($scope.status == 'error'){ Message.warning('卡名称已经存在'); return false; }
        if(!data.minPrice){
            if (!data.originalPrice || !data.sellPrice){
                $scope.commonRule('','请填写一口价或者区间价');return false;
            }
        }
        // if(!$scope.twoCommonRules(data.venueIds,data.sheets,'请选择相应的售卖张数')){ return false; }
        // if(!$scope.venueRule(data.venueIds,data.sheets,data.saleStart,data.saleEnd,'售卖场馆数据不能为空')){
        //     return false;
        // }
        if(!$scope.reSellVenueRule(data.venueIds,'请选择售卖场馆'))
        {
            return false;
        }else{
            if(!$scope.twoCommonRules(data.venueIds,data.sheets,'请填写售卖张数'))
            {
                return false;
            }else{
                // if(!$scope.BlendSellDateRule(data.sellStartTimeArr,'请填写售卖开始日期',data.sellEndTimeArr,'请填写售卖结束日期')){return false;}
                if(!$scope.reSellVenueRule(data.saleStart,'请填写售卖开始日期'))
                {
                    return false;
                }else{
                    if(!$scope.reSellVenueRule(data.saleEnd,'请填写售卖结束日期'))
                    {
                        return false;
                    }
                }
            }
        }
        // if(!$scope.applyVenueRule(data.applyVenueId,data.applyTimes,'通用场馆或通店次数不能为空')){ return false; }
        if(!$scope.getReFindTime(data.hoursStart,data.hoursEnd)){
            if(!$scope.getReDayStatus(data.day)){
                if(!$scope.commonRule('','请先选择固定日')){ return false;}
            }else{
                if(!$scope.commonRule('','特定天的结束时间点不正确')){ return false;}
            }
        }
        if(!$scope.getReFindTime(data.start,data.end)){
            if(!$scope.commonRule('','结束时间点不正确')){ return false;}
        }
        if(!$scope.getReFindWeekTime(data.weekStart)){
            if(!$scope.commonRule('','特定星期的结束时间点不正确')){ return false;}
        }
        return true;
    };
    //定义售卖场馆验证方法
    $scope.reSellVenueRule          = function (attr,text) {
        var len = attr.length;
        if(len == 0){
            $scope.commonRule('',text);return false;
        }else{
            for(i=0;i<len;i++){
                if(!attr[i] || attr[i]==""){
                    $scope.commonRule('',text);return false;
                }
            }
        }
        return true;
    };
    //判断特定天是否存在
    $scope.getReDayStatus = function (day) {
        var length = day.length;
        if(length == 0){
            return false;
        }else{
            return true;
        }
    };
    //判断开始时间点和结束时间点是否正确
    $scope.getReFindTime            = function (start,end) {
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
    $scope.getReFindWeekTime            = function (start) {
        var length = start.length;
        for(var i=0; i<length;i++){
            if(start[i] || start[i] != ""){
                if(!$scope.getReFindTime(start[i].substr(0,5),start[i].substr(-5,5))){
                    return false;
                }
            }else{
                return true;
            }
        }
        return true;
    };
    //售卖场馆判断
    $scope.venueRule =function(venueId,sheets,saleStart,saleEnd,text){
        if(venueId.length==0||sheets.length==0||saleStart.length==0||saleEnd.length==0){
            Message.warning(text);return false;
        }
        return true;
    };
    //使用场馆判断
    $scope.applyVenueRule = function(applyVenueId,applyTimes,text){
        if(applyVenueId != undefined){
            if(applyVenueId.length==0||applyTimes.length==0){
                Message.warning(text);return false;
            }
        }
        return true;
    };
    //js第二步
    $scope.twoCommonRules = function (attr,num,text) {
        if(attr.length){
            if(!num.length){
                $scope.commonRule('',text);return false;
            }else if(attr.length != num.length){
                $scope.commonRule('',text);return false;
            }
        }
        return true;
    };
    //js数据校验
    $scope.twoRules = function (data) {
        if(!$scope.twoCommonRules(data.classId,data.pitchNum,'请选择相应的课程节数')){ return false; }
        if(!$scope.twoCommonRules(data.serverId,data.serverNum,'请选择相应的服务数量')){ return false; }
        if(!$scope.twoCommonRules(data.shopId,data.shopNum,'请选择相应的产品数量')){ return false; }
        if(!$scope.twoCommonRules(data.donationId,data.donationNum,'请选择相应的赠送数量')){ return false; }
        return true;
    };
    //云运动 - 前台 - 卡种信息添加(第二步表单数据接收)
    $scope.getTwoData = function () {
        $scope.classServer();
        $scope.serverCombo();
        $scope.shopPay();
        $scope.donationGoods();
        return  {
            pitchNum            : $scope.pitchNum,
            classId             : $scope.classId,
            serverId            : $scope.serverId,
            serverNum           : $scope.serverNum,
            shopId              : $scope.shopId,
            shopNum             : $scope.shopNum,
            donationId          : $scope.donationId,
            donationNum         : $scope.donationNum,
            scenario            : $scope.scenario,
            //绑定私课课程
            hsId                :$('#HSClass').val(),                 //hs课程id
            hsNum               : $scope.HSClassNum  != undefined && $scope.HSClassNum  != ''? $scope.HSClassNum :null,             //hs课程节数
            ptId                : $('#PTClass').val(),                //pt课程id
            ptNum               : $scope.PTClassNum != undefined && $scope.PTClassNum != '' ? $scope.PTClassNum : null,             //pt课程节数
            birthId             : $('#BirthdayClass').val(),         //生日课程id
            birthNum            : $scope.birthClassNum  != undefined && $scope.birthClassNum != '' ? $scope.birthClassNum : null,         //生日课程节数
            _csrf_backend       : $('#_csrf').val()
        }
    };
    //云运动 - 前台 - 卡种信息添加(第三步表单数据接收)
    $scope.getThreeData = function () {
        // $scope.getApplyVenue();
        $scope.getLeaveDays();
        return  {
            transferNumber      : $scope.transferNumber,    //转让次数
            transferPrice       : $scope.transferPrice,     //转让天数
            leaveTimes          : $scope.leaveTimes,
            leaveDays           : $scope.leaveDays,
            leaveTimesTotal     : $scope.leaveTimesTotal, //请假总天数
            leaveDaysTotal      : $scope.leaveDaysTotal,  //请假总次数
            scenario            : $scope.scenario,
            _csrf_backend       : $('#_csrf').val()
        }
    };
    //js数据校验
    $scope.threeRules = function (data) {
        if(!$scope.commonRule(data.transferNumber,'转让次数不能为空')){ return false; }
        if(!$scope.commonRule(data.transferPrice,'转让金额不能为空')){ return false; }
        return true;
    };
    //云运动 - 前台 - 卡种信息添加(第四步表单数据接收)
    $scope.getFourData = function () {
        return  {
            deal            : $scope.dealId,
            scenario        : $scope.scenario,
            _csrf_backend   : $('#_csrf').val()
        }
    };
    // //js第四步验证
    // $scope.fourRules = function (data) {
    //     if(!$scope.commonRule(data.deal,'请选择合同')){ return false; }
    //     return true;
    // };
    $scope.getCancelData = function () {
        return {
            scenario        : $scope.scenario,
            _csrf_backend   : $('#_csrf').val()
        }
    };
    //云运动 - 前台 - 卡种信息添加(和后台数据交互)
    $scope.setHttp = function () {
        $http({
            url        : '/member-card/recharge-card-rule',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                // Message.success('数据成功');
            }else{
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                });
                return false;
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
        //     if(!$scope.fourRules(data)){return false;}

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
    /********向导入口**********/
    $rootScope.onStepChange = function (index) {
        if(index == 1){
            $scope.scenario = 'one';
            $scope.OneParams   = $scope.getOneData();
            $scope.getGiveCourseData();
            $('#HSClass').select2({
                // width:'100%'
            });
            $('#PTClass').select2({
                // width:'100%'
            });
            $('#BirthdayClass').select2({
                // width:'100%'
            });
            if(!$scope.assignParams($scope.OneParams,$scope.scenario)){return false;}
        }else if(index == 2){
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
            $scope.scenario = 'two';
            $scope.OneParams   = $scope.getTwoData();
            if(!$scope.assignParams($scope.OneParams,'two')){return false;}
        }else if(index == 3){
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
            $scope.scenario  = 'three';
            $scope.OneParams = $scope.getThreeData();
            if(!$scope.assignParams($scope.OneParams,'three')){return false;}
        }else if(index == 4){
            $scope.scenario     = 'four';
            $scope.OneParams    = $scope.getFourData();
            if(!$scope.assignParams($scope.OneParams,'four')){return false;}
        }else if(index == -1){
            $scope.scenario = 'cancel';
            $scope.params = $scope.getCancelData();
        }else{
            $scope.scenario = 'finish';
            $scope.OneParams  = $scope.getFourData();
            // if(!$scope.assignParams($scope.OneParams,'four')){return false;}
        }
        $scope.setHttp();
        return true;
    };
}).run(function ($rootScope) {
    //表单向导启动
    $("#example-async").steps({
        headerTag: "h3",
        bodyTag: "section",
        onInit:function (event, currentIndex) {

        },
        //动态设置表单向导的高度
        onStepChanging: function (event, currentIndex, newIndex) {
            // var index    = currentIndex + 1;
            // if(currentIndex == 1){
            //     $rootScope.getTwoTemplate();
            // }
            if(!$rootScope.onStepChange(newIndex)){
                return false;
            }
            return true;
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            contentHeight(currentIndex);

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
        //点击取消页面时跳转页面
        onCanceled:function (event,currentIndex) {
            $rootScope.onStepChange('-1');
            location.href = '/member-card/index';
        },
        //点击完成按钮时跳转页面
        onFinished:function (event, currentIndex) {
            if(currentIndex == 3){
                $.loading.show();
                location.href = '/member-card/index';
            }
        }
    });
    function contentHeight(index) {
        $('.wizard > .content').height($('.con1').eq(index).outerHeight()+100);
    }
});