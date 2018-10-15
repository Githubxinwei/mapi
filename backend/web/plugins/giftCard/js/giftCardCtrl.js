/**
 * Created by DELL on 2017/9/6.
 * content:赠卡页面控制器js
 */
(function($) {
    'use strict';
    $('.venueSelect').select2();
    $('.giftCardList').select2();
    //时间选择器
    function applyDatePicker(dom) {dom.daterangepicker(null, function(start, end, label) {});}
    //赠卡首页 搜索区域
    applyDatePicker($('#giftDate'));
    //赠卡详情
    applyDatePicker($('.giftDetailDate'));
    //更柜有效期
    applyDatePicker($('#cabinetDate'));
    //新增赠送
    applyDatePicker($('#addGiftTime'));
})(jQuery);
angular.module('App').controller('giftCardCtrl',function($http,$scope,$interval){

    //初始化数据
    $scope.init = function () {
        //列表页model
        $scope.keywords             = '';
        $scope.venue                = '';
        $scope.giftStartTime        = '';
        $scope.giftEndTime          = '';
        //新增模态框model
        $scope.addNewGiftVenue      = '';
        $scope.addGiftName          = '';
        $scope.addGiftStartTime     = '';
        $scope.addGiftEndTime       = '';
        $scope.addGiftNote          = '';
        $scope.noGiftCardShow       = false;
        $scope.CompleteAddGiftFlag  = false;
        //赠卡详情模态框
        // $scope.addCardActivityId    = '';
        //绑定会员
        // $scope.memberBindCode       = '';
        $scope.memberBindName       = '';
        $scope.memberBindIdCard     = '';
        $scope.memberBindIdAddress  = '';
        $scope.memberBindNowAddress = '';
        $scope.memberBindPhone      = '';
        $scope.memberBindCheckCode  = '';
        $scope.gift_card_list_id    = '';
        //验证码
        $scope.code                 = '';
        $scope.codeText             = "验证码";
        $scope.disabledFlag         = false;
    };
    $scope.init();

    //获取所有场馆
    $scope.getAllVenue = function(){
        $http.get('/site/get-auth-venue').then(function(data){
            $scope.allVenueLists = data.data;
        });
    };
    $scope.getAllVenue();


    //获取卡类型
    $scope.getCardType = function () {
        $http.get('/member-card/get-type').then(function (data) {
            $scope.cardTypeLists = data.data.type;
        })
    };
    $scope.getCardType();


    //首页列表数据准备
    $scope.getGiftCardListData = function(){
        var date = $('#giftDate').val();
        if(date != '' && date != undefined){
            var startTime = date.substr(0, 10);
            var endTime = date.substr(-10, 10);
            $scope.giftStartTime   = startTime +' '+ '00:00:01';
            $scope.giftEndTime     = endTime   +' '+ '23:59:59';
        }
        //keywords关键字、venue_id场馆id、start_time开始时间、end_time结束时间
        return{
            keywords    : $scope.keywords       != undefined && $scope.keywords      != '' ? $scope.keywords        : null,
            venue_id    : $scope.venue          != undefined && $scope.venue         != '' ? $scope.venue           : null,
            start_time  : $scope.giftStartTime  != undefined && $scope.giftStartTime != '' ? $scope.giftStartTime   : null,
            end_time    : $scope.giftEndTime    != undefined && $scope.giftEndTime   != '' ? $scope.giftEndTime     : null
        }
    };
    //获取首页列表数据
    $scope.getGiftCardList = function (urlPages) {
        $http.get(urlPages).then(function (data) {
            if(data.data.data.length == 0 || data.data.data == undefined || data.data.data == null) {
                $scope.noGiftCardShow = true;
                $scope.giftCardLists = data.data.data;
                $scope.giftCardPages = data.data.pages;
            }else {
                $scope.noGiftCardShow = false;
                $scope.giftCardLists = data.data.data;
                $scope.giftCardPages = data.data.pages;
            }
        })
    };
    $scope.getGiftCardUrl = '/gift-card/get-activity-list?'+$.param($scope.getGiftCardListData());
    $scope.getGiftCardList($scope.getGiftCardUrl);
    //分页
    $scope.activityList = function (urlPages) {
        $scope.getGiftCardList(urlPages);
    };
    //搜索按钮
    $scope.searchGift = function () {
        $scope.getGiftCardUrl = '/gift-card/get-activity-list?'+$.param($scope.getGiftCardListData());
        $scope.getGiftCardList($scope.getGiftCardUrl);
    };
    //确定按钮
    $scope.searchDateVenue = function () {
        $scope.searchGift();
    };
    //首页清空按钮
    $scope.clearSearch = function(){
        $('#giftDate').val('');
        $scope.venue            = '';
        $scope.keywords         = '';
        $scope.giftStartTime    = '';
        $scope.giftEndTime      = '';
        $('.venueSelect').select2({'val':''});
        $('.select2-selection__rendered').text('请选择场馆').attr('title','请选择场馆');
        $scope.searchGift();
    };

    //新增赠送
    $scope.addGiftButton = function(){
        //初始化数据
        $scope.addGiftName          = '';
        $('#addGiftTime').val('');
        $scope.addGiftStartTime     = '';
        $scope.addGiftEndTime       = '';
        $scope.addGiftNote          = '';
        $scope.CompleteAddGiftFlag  = false;
        $('#addGiftModal').modal('show');
    };
    //赠送完成
    $scope.addGift = function(){
        var date = $('#addGiftTime').val();
        if(date != '' && date != undefined){
            var startTime = date.substr(0, 10);
            var endTime = date.substr(-10, 10);
            $scope.addGiftStartTime = startTime + ' ' + '00:00:01';
            $scope.addGiftEndTime   = endTime   + ' ' + '23:59:59';
        }
        var request = {
            _csrf_backend   : $('#_csrf').val(),
            venueId         : $scope.addNewGiftVenue,//选择场馆
            active_name     : $scope.addGiftName,//赠送名称
            start_time      : $scope.addGiftStartTime,//开始时间
            end_time        : $scope.addGiftEndTime,//结束时间
            note            : $scope.addGiftNote//备注
        };
        if(!$scope.addNewGiftVenue) {
            Message.warning('请选择场馆！');
            return;
        }
        if($scope.addGiftName == '' || $scope.addGiftName == undefined || $scope.addGiftName ==null){
            Message.warning('请输入赠送名称！');
            return;
        }
        if($('#addGiftTime').val() == ''){
            Message.warning('请选择有效期限！');
            return;
        }
        if($scope.addGiftNote == null || $scope.addGiftNote == undefined){
            $scope.addGiftNote = '';
        }
        $scope.CompleteAddGiftFlag = true;
        $http({
            url     : '/gift-card/save-gift',
            method  : 'POST',
            data    : $.param(request),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == 'success'){
                Message.success(response.data.data);
                $('#addGiftModal').modal('hide');
                $scope.getGiftCardUrl = '/gift-card/get-activity-list?'+$.param($scope.getGiftCardListData());
                $scope.getGiftCardList($scope.getGiftCardUrl);
            }
            if(response.data.status == 'error'){
                Message.warning(response.data.data);
                $scope.CompleteAddGiftFlag = false;
            }
        });
    };

    //赠卡模态框列表数据
    $scope.getGiftDetailListData = function () {
        var date = $('.giftDetailDate').val();
        if(date != '' && date != undefined){
            var startTime = date.substr(0, 10);
            var endTime = date.substr(-10, 10);
            $scope.giftDetailStartTime  = startTime +' '+ '00:00:01';
            $scope.giftDetailEndTime    = endTime   +' '+ '23:59:59';
        }
        return {
            id               : $scope.addCardActivityId,//新建赠送活动表id
            start_time       : $scope.giftDetailStartTime,//开始时间
            end_time         : $scope.giftDetailEndTime,//结束时间
            keywords         : $scope.cardDetailKeywords,//搜索的关键词
            category_type_id : $scope.giftCardType //卡类型
        }
    };
    $scope.getGiftDetailList = function (urlPage) {
        $http.get(urlPage).then(function (response) {
            if(response.data.data.length == 0 || response.data.data == undefined || response.data.data == null) {
                $scope.noGiftDetailShow     = true;
                $scope.getGiftDetailLists   = response.data.data;
                $scope.giftDetailPages      = response.data.pages;
                $scope.nowPage              = response.data.now;
            }else {
                $scope.noGiftDetailShow     = false;
                $scope.getGiftDetailLists   = response.data.data;
                $scope.giftDetailPages      = response.data.pages;
                $scope.nowPage              = response.data.now;
            }
            $.loading.hide();
            $('#giftDetailModal').modal('show');
        })
    };
    //赠卡详情
    $scope.giftDetailClick = function(id, $id){
        $.loading.show();
        //获取所有卡种
        $scope.getAllCard = function ($id) {
            $http.get('/sell-card/card-category?venueId=' + $id).then(function(data){
                $scope.allCardLists = data.data;
            });
        };
        $scope.getAllCard($id);
        $scope.addCardActivityId = id;
        $scope.getGiftDetailListUrl = '/gift-card/get-card-list?' + $.param($scope.getGiftDetailListData());
        $scope.getGiftDetailList($scope.getGiftDetailListUrl);
    };
    //赠卡详情列表搜索
    $scope.cardDetailSearch = function () {
        $scope.getGiftDetailListUrl = '/gift-card/get-card-list?' + $.param($scope.getGiftDetailListData());
        $scope.getGiftDetailList($scope.getGiftDetailListUrl);
    };
    //赠卡详情模态框确定按钮
    $scope.giftCardDetailSearch = function () {
        $scope.cardDetailSearch();
    };
    //分页
    $scope.cardList = function (urlPages) {
        $scope.getGiftDetailList(urlPages);
    };
    //赠卡页面清空
    $scope.giftCardDetailClear = function(){
        $('.giftDetailDate').val('');
        $scope.cardDetailKeywords   = '';
        $scope.giftCardType         = '';
        $scope.giftDetailStartTime  = '';
        $scope.giftDetailEndTime    = '';
        $scope.cardDetailSearch();
    };
    //添加会员卡按钮
    $scope.giftCardDetailAddCard = function(){
        $scope.addCardType          = '';
        $scope.addCardGoodNum       = '';
        $scope.CompleteAddMemberCardFlag = false;
        $('.giftCardList').select2({'val':''});
        $('.select2-selection__rendered').text('请选择卡种').attr('title','请选择卡种');
        //柜子暂时不做，相关代码注释
        // $scope.giftCabinetType = '';
        // $('#cabinetDate').val('');
        $('#addMemberCardModal').modal('show');
    };

    //添加会员卡
    $scope.addMemberCard = function(){
        //柜子暂时不做，相关代码注释
        // var date = $('#cabinetDate').val();
        // if(date != '' && date != undefined){
        //     var startTime = date.substr(0, 10);
        //     var endTime = date.substr(-10, 10);
        //     $scope.addCabinetStartTime   = startTime +' '+ '00:00:01';
        //     $scope.addCabinetStartEnd    = endTime   +' '+ '23:59:59';
        // }
        var request = {
            _csrf_backend           : $('#_csrf').val(),
            card_amount             : $scope.addCardGoodNum, //赠卡数量
            card_category_id        : $scope.addCardType, //卡种id
            gift_card_activity_id   : $scope.addCardActivityId //对应的赠卡活动的id
            //柜子暂时不做，相关代码注释
            // starDate            : $scope.addCabinetStartTime
            // endDate             : $scope.addCabinetStartEnd
            // cabinetType         : $scope.giftCabinetType
        };
        if($scope.addCardType == '' || $scope.addCardType == undefined || $scope.addCardType ==null){
            Message.warning('请选择卡种！');
            return;
        }
        if($scope.addCardGoodNum == '' || $scope.addCardGoodNum == undefined || $scope.addCardGoodNum ==null){
            Message.warning('请输入赠卡数量！');
            return;
        }
        $scope.CompleteAddMemberCardFlag = true;
        $http({
            url     : '/gift-card/make-card',
            method  : 'POST',
            data    : $.param(request),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == 'success'){
                Message.success(response.data.data);
                $scope.cardDetailSearch();
                $('#addMemberCardModal').modal('hide');
                $scope.searchGift();
            }
            if(response.data.status == 'error'){
                Message.warning(response.data.data);
                $scope.CompleteAddMemberCardFlag = false;
            }
        });
    };

    //绑定会员
    $scope.getMemberBind = function(id){
        $scope.init();
        $('#BoundMemberModal').modal('show');
        $scope.gift_card_list_id = id;
    };
    //绑定会员到卡
    $scope.memberBindCard = function () {
        var request = {
            _csrf_backend       : $('#_csrf').val(),
        //  识别码 : $scope.memberBindCode     // 识别码暂时不加
            memberName          : $scope.memberBindName       != undefined && $scope.memberBindName       != '' ? $scope.memberBindName       : null, //姓名
            idCard              : $scope.memberBindIdCard     != undefined && $scope.memberBindIdCard     != '' ? $scope.memberBindIdCard     : null, //身份证号
            idCardAddress       : $scope.memberBindIdAddress  != undefined && $scope.memberBindIdAddress  != '' ? $scope.memberBindIdAddress  : null, //身份证地址
            presentAddress      : $scope.memberBindNowAddress != undefined && $scope.memberBindNowAddress != '' ? $scope.memberBindNowAddress : null, //现居地
            memberMobile        : $scope.memberBindPhone      != undefined && $scope.memberBindPhone      != '' ? $scope.memberBindPhone      : null, //手机号
            sendCode            : $scope.memberBindCheckCode  != undefined && $scope.memberBindCheckCode  != '' ? $scope.memberBindCheckCode  : null, //验证码
            gift_card_list_id   : $scope.gift_card_list_id    != undefined && $scope.gift_card_list_id    != '' ? $scope.gift_card_list_id    : null  //赠送会员卡列表数据表id
        };
        if(!$scope.memberBindName) {Message.warning('请输入会员名称！');return ;}
        if($scope.memberBindIdCard != null && $scope.memberBindIdCard != undefined && $scope.memberBindIdCard != '') {
            var $patternId = /^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/;
            if(!$patternId.test($scope.memberBindIdCard)) {Message.warning('您输入的身份证有误！');return ;}
        }
        // if(!$scope.memberBindIdAddress) {Message.warning('请输入身份证地址！');return ;}
        // if(!$scope.memberBindNowAddress) {Message.warning('请输入现居住地址！');return ;}
        if(!$scope.memberBindPhone) {Message.warning('请输入手机号！');return ;}
        // var $patternPhone = /^1[34578]\d{9}$/;
        // if(!$patternPhone.test($scope.memberBindPhone)) {Message.warning('请输入正确的手机号！');return ;}
        if(!$scope.memberBindCheckCode) {Message.warning('请输入验证码！');return ;}
        if(!(parseInt($scope.memberBindCheckCode) === parseInt($scope.code))) {Message.warning('您输入的验证码错误！请重新输入！');return ;}
        $http({
            url     : '/gift-card/bind-member',
            method  : 'POST',
            data    : $.param(request),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (response) {
            if(response.data.status == 'success') {
                Message.success(response.data.data);
                $('#BoundMemberModal').modal('hide');
                $scope.getGiftDetailListUrl = '/gift-card/get-card-list?' + $.param($scope.getGiftDetailListData());
                $scope.getGiftDetailList($scope.getGiftDetailListUrl);
                $scope.searchGift();
            }else {
                Message.warning(response.data.data);
            }
        })
    };

    //获取验证码
    $scope.getCode = function(){
        if(!$scope.memberBindPhone){
            Message.warning('请输入手机号！');
        }else{
            $scope.disabledFlag =true;
            $http({
                url     : '/v1/api-member/create-code',
                method  : 'POST',
                data    : $.param({mobile : $scope.memberBindPhone}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function (result){
                $scope.code = result.data.data;
            });
            //注册验证码倒计时
            var second = 60,
                timePromise = undefined;
            timePromise = $interval(function(){
                if(second<=0){
                    $interval.cancel(timePromise);
                    timePromise = undefined;
                    second = 60;
                    $scope.codeText  = "验证码";
                    $scope.disabledFlag  = false;
                }else{
                    $scope.codeText = second + "秒";
                    $scope.disabledFlag =true;
                    second--;
                }
            },1000,100);
        }
    };

    //获取会员详情
    $scope.getMemberDetail = function (id,cardNum) {
        $scope.memberDetailCardNumber = cardNum;
        $http.get('/user/member-details-card?MemberId=' + id).then(function (result) {
            $scope.MemberData = result.data;
        });
        $('#memberDetailModal').modal('show');
    }
});
