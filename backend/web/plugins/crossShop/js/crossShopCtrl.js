/**
 * Created by Administrator on 2018/1/12 0012.
 */
angular.module('App').controller('crossShopCtrl',function($http,$scope){

    $(document).ready(function (){
        $("#sellerSelect2").select2({
            width: "100%",
        });
    });

    // 显示模态框
    $scope.searchCardNumber = function() {
        $http.get("/cross-shop/member-info?cardNumber=" + $scope.cardNumber).then(function (result) {
             $scope.cardData = result.data.card;
            if ($scope.cardData != null && $scope.cardData !=undefined && $scope.cardData !='') {
                $("#memberUpModal").modal("show");
                $scope.updateCardButtonFlag = false;
                $scope.getAllSaleMan();
                if($scope.upsateCardType == undefined) {
                    $scope.upsateCardType == '';
                }
                $scope.upSateCardTypeChange();
            }else{
                Message.warning('您输入的卡号有误或卡异常过期或不是跨店的卡号,请重新输入!');
                return;
            }
        });
    }

    /** 获取场馆**/
    $scope.getVenue=function () {
        $http({
            url:"/rechargeable-card-ctrl/get-venue?status=card",
            method:"GET"
        })
            .then(function(data){
                $scope.venueItems=data.data.venue
                // console.log(data.data.venue)
            })

    }
    //获取不同场馆下卡种
    $scope.venueSelectChange=function (id) {
        if(id!=""&&id!=[]){
            var items = angular.fromJson(id);
        }else{
            var items ="无数据"
        }
        $scope.cardVenueId    = items.id;
        $scope.getMoreMoneyUpsateCard($scope.upSateCardTypeValue,$scope.cardVenueId);//空 期限升级 老卡开始时间  1折线升级 当前时间为新卡开始时间 场馆id
        $scope.getVenueCard(items.id)
    }
    $scope.getVenueCard = function (id){
        $http.get('/sell-card/card-category?venueId=' +id).success(function (data){
            $scope.getVenueCardItems = data;
        });
    };
    $scope.getVenue();

    // 获取销售人员信息
    $scope.getAllSaleMan = function () {
        $http.get("/user/get-all-adviser").success(function (response) {
            $scope.saleInfoList = response;
        });
    };
    //选取销售人员的change事件
    $scope.selectSeller = function(employeeId){
        $http.get("/user/get-all-adviser?employeeId="+ employeeId).success(function (response) {
            $scope.sellerVenue = response[0].venue_id;
        });
    };
    //升级方式change事件
    $scope.upSateCardTypeChange = function(){
        $scope.venue = "";
        if($scope.upSateCardTypeValue == ''){
            $scope.upsateCardTimeStart   = $scope.cardData.create_at; //升级卡的开始时间
        }
        else{
            $scope.upsateCardTimeStart   = $scope.todaytimetemps; //升级卡的开始时间
        }
        if($scope.todaytimetemps != '' && $scope.todaytimetemps != null && $scope.todaytimetemps !=undefined ) {
            if($scope.upSateCardTypeValue == ''){
                $scope.upsateCardTimeStart = $scope.cardData.create_at; //升级卡的开始时间
                $scope.upsateCardEnd = parseInt($scope.cardData.create_at) + $scope.upsateDuration; //升级卡的结束时间
            }
            else{
                $scope.upsateCardTimeStart = $scope.todaytimetemps; //升级卡的开始时间
                $scope.upsateCardEnd = parseInt($scope.todaytimetemps) + $scope.upsateDuration; //升级卡的结束时间
            }
        }
        if($scope.upsateType != null || $scope.upsateType != undefined) {
            $scope.getMoreMoneyUpsateCard(angular.fromJson($scope.upsateType));
        }
    };

    // 获取升级价格更高的卡
    $scope.getMoreMoneyUpsateCard = function (value,venueId){
        if(value == ''|| value == undefined){
            $http.get('/sell-card/new-card-category?oldCardCategory=' + $scope.cardData.id + '&cardTypeId=' + $scope.cardData.cardCategoryTypeId + '&venueId=' + venueId).success(function (data) {
                if ($scope.cardList == [] || $scope.cardList == null || $scope.cardList == '') {
                    $scope.cardList = data;
                }
                else {
                    $scope.cardList = data;
                }
            });
        }
        else{
            $http.get('/sell-card/all-card-category?oldCardCategory=' + $scope.cardData.id + '&venueId=' + venueId).success(function (data) {
                if ($scope.cardList == [] || $scope.cardList == null || $scope.cardList == '') {
                    $scope.cardList = data;
                }
                else {
                    $scope.cardList = data;
                }
            });
        }
    };

    // 卡种选择事件
    $scope.getUpsateCardType = function (data) {
        $('.newCardPricePay').text("");
        $("#upcardConversionMoneyInput").val("");
        $scope.upsateCardRecondMoney = 0;
        $scope.upsateType            = angular.fromJson(data); //转换格式
        $scope.upsateTypeId          = $scope.upsateType.id; // 新卡的卡种id
        $scope.oldCardSellPrice      = parseFloat($scope.cardData.amount_money).toFixed(2); // 老卡价格
        if ($scope.upsateType.sell_price == undefined || $scope.upsateType.sell_price == null) {
            $(".upsateCardMoneySelect").removeAttr("disabled");
            $(".upsateCardMoneySelect").val("");
            $scope.upsateCardMoneyMax = parseFloat($scope.upsateType.max_price).toFixed(2); //新卡最高售价
            $scope.upsateCardMoneyMin = parseFloat($scope.upsateType.min_price).toFixed(2); //新卡最低售价
            $scope.newCardSellPrice   = parseFloat($scope.upsateType.min_price).toFixed(2) + "-" + parseFloat($scope.upsateType.max_price).toFixed(2); // 新卡价格
        }
        else if ($scope.upsateType.sell_price != 'null' && $scope.upsateType.sell_price != null) {
            $(".upsateCardMoneySelect").attr("disabled", true);
            $scope.upsateCardMoney = parseFloat($scope.upsateType.sell_price).toFixed(2);        //新卡售价
            $scope.newCardSellPrice = parseFloat($scope.upsateCardMoney).toFixed(2);   //新卡价格
            $(".upsateCardMoneySelect").val($scope.newCardSellPrice);
            $(".newCardPricePay").text($scope.newCardSellPrice);
        }
        $scope.upsateDuration = parseInt($scope.upsateType.duration.replace(/[^0-9]/ig, "")) * 24 * 60 * 60; //计算卡种有效期
        var timetemps = new Date();
        timetemps.setHours(0);
        timetemps.setMinutes(0);
        timetemps.setSeconds(0);
        timetemps.setMilliseconds(0);
        $scope.todaytimetemps = Date.parse(timetemps) / 1000; //获取当前时间戳
        if($scope.upSateCardTypeValue == ''){
            $scope.upsateCardTimeStart = $scope.cardData.create_at; //升级卡的开始时间
            $scope.upsateCardEnd = parseInt($scope.cardData.create_at) + $scope.upsateDuration; //升级卡的结束时间
        }
        else if($scope.upSateCardTypeValue == '1'){
            $scope.upsateCardTimeStart = $scope.todaytimetemps; //升级卡的开始时间
            $scope.upsateCardEnd = parseInt($scope.todaytimetemps) + $scope.upsateDuration; //升级卡的结束时间
        }else if($scope.upSateCardTypeValue == undefined){
            $scope.upSateCardTypeValue = '';
            $scope.upsateCardTimeStart = $scope.cardData.create_at; //升级卡的开始时间
            $scope.upsateCardEnd = parseInt($scope.cardData.create_at) + $scope.upsateDuration; //升级卡的结束时间
        }
        $scope.getDiscount($scope.upsateTypeId);
        if ($scope.upsateCardRecondMoney < 0) {
            Message.warning("新卡价格小于当前卡，无法升级");
        }
        else if ($scope.upsateCardEnd < $scope.todaytimetemps || $scope.upsateCardEnd == $scope.todaytimetemps) {
            Message.warning("新卡升级时间有误，无法升级");
        }
        $(".cardTimeUpsateWords").show();
        $scope.getDiscountListValue = '';
    };

    // 提交升级
    $scope.successUpdate = function () {
        $scope.upsateCardRecondMoney = $(".newCardPricePay").text();
        $scope.updateCardButtonFlag = true;
        $scope.addUpdate = function () {
            $scope.cardId = $scope.cardData.id;
            // 整理提交的数据
            return {
                newCardId     : $scope.upsateTypeId          != undefined && $scope.upsateTypeId          != "" ? $scope.upsateTypeId          : null, //新卡卡种id
                cardId        : $scope.cardId                != undefined && $scope.cardId                != "" ? $scope.cardId                : null, //老卡卡id
                payAmount     : $scope.upsateCardRecondMoney != undefined && $scope.upsateCardRecondMoney != "" ? $scope.upsateCardRecondMoney : null, //售价
                seller        : $scope.seller                != undefined && $scope.seller                != "" ? $scope.seller                : null, //销售
                card_number   : $scope.upcardNumber          != undefined && $scope.upcardNumber          != "" ? $scope.upcardNumber          : null, //卡号
                amountMoney   : $scope.upsateCardMoney       != undefined && $scope.upsateCardMoney       != "" ? $scope.upsateCardMoney       : null, //价格
                discount      : $scope.discountId            != undefined && $scope.discountId            != "" ? $scope.discountId            : null, //折扣
                dueTime       : $scope.upsateCardEnd         != undefined && $scope.upsateCardEnd         != "" ? $scope.upsateCardEnd         : null, //卡升级的到期时间戳
                upStartTime   : $scope.upsateCardTimeStart   != undefined && $scope.upsateCardTimeStart   != "" ? $scope.upsateCardTimeStart   : null, //卡升级的开始时间戳
                note          : $scope.upSateNote            != undefined && $scope.upSateNote            != "" ? $scope.upSateNote            : null, //升级备注
                mobile        : $scope.cardData.mobile       != undefined && $scope.cardData.mobile       != "" ? $scope.cardData.mobile       : null, //手机号
                sellerVenueId : $scope.sellerVenue           != undefined && $scope.sellerVenue           != "" ?  $scope.sellerVenue          : null, //销售场馆Id
                cardVenueId   : $scope.cardVenueId           != undefined && $scope.cardVenueId           != "" ?  $scope.cardVenueId          : null, //选择场馆Id
                _csrf_backend : $('#_csrf').val()
            }
        };
        // 保存数据前的验证
        if ($scope.upsateCardRecondMoney < 0) {
            Message.warning("新卡价格小于当前卡，不能升级");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.upsateCardRecondMoney == 0 || $scope.upsateCardRecondMoney == '') {
            Message.warning("新卡价格有误，无法升级");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.upsateCardEnd < $scope.todaytimetemps || $scope.upsateCardEnd == $scope.todaytimetemps) {
            Message.warning("新卡升级时间有误，无法升级");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.seller == null || $scope.seller == "") {
            Message.warning("请选择销售");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.cardVenueId == null || $scope.cardVenueId == "") {
            Message.warning("请选择场馆");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.upsateTypeId == null || $scope.upsateTypeId == "") {
            Message.warning("请选择新的会员卡");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.upsateCardMoney == null || $scope.upsateCardMoney == "") {
            Message.warning("卡价格有误，请重新选择");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if (parseInt($scope.upsateMoneyVal) > parseInt($scope.upsateCardMoneyMax) || parseInt($scope.upsateMoneyVal) < parseInt($scope.upsateCardMoneyMin)) {
            var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
            Message.warning(upsateCardMoneyWords);
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if($scope.upcardConversionMoney == "" || $scope.upcardConversionMoney < 0){
            Message.warning("请输入折算金额");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        // 发送数据
        $http({
            url: '/cross-shop/new-across-upgrade',
            method: 'POST',
            data: $.param($scope.addUpdate()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success("升级成功");
                // 数据初始化
                $scope.upsateCardType        = "";
                $scope.discount              = "";
                $scope.upcardNumber          = "";
                $scope.seller                = "";
                $scope.upsateTypePrice       = "";
                $scope.upsateCardRecondMoney = "";
                $scope.upcardConversionMoney = "";
                $scope.cardNumber            = "";
                $scope.cardVenueId           = "";
                $scope.sellerVenue           = "";
                $("#memberUpModal").modal("hide");
                // $("#acrossUpgrade").val("");
            } else {
                $scope.updateCardButtonFlag = false;
                Message.warning(data.data.data);
            }
        });
    }
    // 判断升级的区间价格
    $scope.upsateCardMoneySelectBlur = function(){
        $scope.upsateMoneyVal = parseFloat($(".upsateCardMoneySelect").val()).toFixed(2);
        $scope.upsateCardRecondMoney = parseFloat($scope.upsateCardMoney).toFixed(2);
        $('.newCardPricePay').text($scope.upsateCardRecondMoney);
        if ($(".discountSelect").val() == '' || $(".discountSelect").val() == undefined) {
            if (parseInt($scope.upsateMoneyVal) > parseInt($scope.upsateCardMoneyMax) || parseInt($scope.upsateMoneyVal) < parseInt($scope.upsateCardMoneyMin)) {
                var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin +'元';
                Message.warning(upsateCardMoneyWords);
                $(".upsateCardMoneySelect").val("");
                $('.newCardPricePay').text("");
                $scope.upsateCardRecondMoney = '';
            }
        }
        else {
            $scope.discountMath();
            if (parseInt($scope.upsateMoneyVal) > parseInt($scope.upsateCardMoneyMax) || parseInt($scope.upsateMoneyVal) < parseInt($scope.upsateCardMoneyMin)) {
                var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
                Message.warning(upsateCardMoneyWords);
                $(".upsateCardMoneySelect").val("");
                $('.newCardPricePay').text("");
                $scope.upsateCardRecondMoney = '';
            }
        }
    }

    // 获取卡折扣
    $scope.getDiscount = function (id){
        $http.get('/member-card/new-card-category?newCardCategory=' + id).success(function (data) {
            $scope.getDiscountList = data;
        });
    };

    $scope.residueCard = '不限';

    // 卡折扣剩余判断
    $scope.discountMath = function () {
        $("#upcardConversionMoneyInput").val("");
        $('.newCardPricePay').text("");
        $scope.upcardConversionMoney = "";
        if ($scope.getDiscountListValue != undefined && $scope.getDiscountListValue != '') {
            $scope.discountCardList = angular.fromJson($scope.getDiscountListValue);
            $scope.discountCardNum  = $scope.discountCardList.surplus;
            $scope.discountId       = $scope.discountCardList.id;
            if ($(".upsateCardMoneySelect").val() != '' && $scope.discountCardList.discount != '' && $scope.discountCardList.discount != undefined && $scope.discountCardList.discount != null) {
                if ($scope.discountCardNum == '' || $scope.discountCardNum == undefined || $scope.discountCardNum == '-1') {
                    $scope.residueCard = '不限';
                }
                else {
                    $scope.residueCard = $scope.discountCardNum;
                }
                $scope.upsateCardRecondMoney = parseFloat($(".upsateCardMoneySelect").val()).toFixed(2) * parseFloat($scope.discountCardList.discount) / 10;
                $('.newCardPricePay').text($scope.upsateCardRecondMoney);
            }
        }
        else {
            $scope.discountId = '';
            $scope.residueCard = '不限';
            $scope.upsateCardRecondMoney = parseInt($(".upsateCardMoneySelect").val());
            $('.newCardPricePay').text($scope.upsateCardRecondMoney);
        }
    };
    // 模态框关闭事件
    // 升级使用期限默认关闭
    $(".cardTimeUpsateWords").hide();
    // 折算金额的计算
    $("#upcardConversionMoneyInput").blur(function (){
        if($scope.upsateCardMoney != '' && $scope.upcardConversionMoney > 0 && $scope.upcardConversionMoney != undefined){
            var $conversionMoney = parseFloat($scope.upsateCardMoney).toFixed(2) - parseFloat($scope.upcardConversionMoney).toFixed(2);
            if($conversionMoney > 0 && $conversionMoney != undefined){
                $(".newCardPricePay").text($conversionMoney);
            }
            else{
                Message.warning("补交金额小于等于0，无法升级");
                $scope.upcardConversionMoney = "";
                $("#upcardConversionMoneyInput").val("");
                $(".newCardPricePay").text("");
            }
        }
        else if($("#upcardConversionMoneyInput").val() == '0'){
            $(".newCardPricePay").text($scope.newCardSellPrice);
        }
        else{
            Message.warning("请输入折算金额");
            $scope.upcardConversionMoney = "";
            $("#upcardConversionMoneyInput").val("");
        }
    });
    // 升级
    $('#memberUpModal').on('hide.bs.modal', function () {
        $scope.upsateCardType        = "";
        $scope.upcardNumber          = "";
        $scope.seller                = "";
        $scope.upsateTypePrice       = "";
        $scope.upsateCardMoney       = "";
        $scope.upsateCardRecondMoney = "";
        $scope.upsateCardEnd         = "";
        $scope.upcardConversionMoney = "";
        $scope.sellerVenue           = "";
        $scope.cardVenueId           = "";
        $('.newCardPricePay').text("");
        $(".cardTimeUpsateWords").hide();
        $(".discountSelect").val("");
    });
    /**** 升级结束 ****/
});
