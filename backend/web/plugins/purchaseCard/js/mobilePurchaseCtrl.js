/**
 * Created by DELL on 2017/9/1.
 * 手机端购卡页面控制器
 */

var app = angular.module('App',['starter.services']).controller('buyCardCtrl',function($http,$scope,$rootScope,$interval,dialogsManager,$location){
    //扇叶窗
    $(function(){
        $('.js-category').click(function(){
            $parent = $(this).parent('li');
            if($parent.hasClass('js-show')){
                $parent.removeClass('js-show');
                $(this).children('i').removeClass('icon-35').addClass('icon-74');
            }else{
                $parent.siblings().removeClass('js-show');
                $parent.addClass('js-show');
                $(this).children('i').removeClass('icon-74').addClass('icon-35');
                $parent.siblings().find('i').removeClass('icon-35').addClass('icon-74');
            }
        });
        // $('#date').mobiscroll().date({
        //     theme: "android-holo-light",
        //     mode: "scroller",
        //     display: "bottom",
        //     lang: "zh"
        // });
    });


    //获取url后面的参数
    $scope.getRequest= function() {
        var url = location.search; //获取url中"?"符后的字串
        // var url =
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }

    //获取卡合同
    $scope.getCardIntroMess = function(id){
        $http.get('/purchase-card/deal?cardId=' + id).then(function(result){
            $scope.introMessName = result.data.name;
            $scope.introMess = result.data.intro;
            localStorage.setItem('contractData',JSON.stringify({
                name:result.data.name,contractContent:result.data.intro
            }));
        });
    }
    var buyCardData1 = localStorage.getItem('buyCardData');
    var buyCardData2 = angular.fromJson(buyCardData1);
    if(buyCardData1 != null && buyCardData1 !='' && buyCardData1 !=undefined ){
        $scope.name         = buyCardData2.name;
        $scope.idCard       = buyCardData2.idCard;
        $scope.idAddress    = buyCardData2.idAddress       ;
        $scope.nowAddress   = buyCardData2.nowAddress      ;
        $scope.mobile       = buyCardData2.mobile        ;
        $scope.adviserId    = buyCardData2.seller;
        $scope.giftTypeId   = buyCardData2.giftStatus      ;
        $scope.cardId        = buyCardData2.cardId          ;
        $scope.sourceId      =buyCardData2.sourceId;
        $scope.selectMemberCardName         = buyCardData2.cardName;
        $scope.PaymentMethodType            = buyCardData2.payMoneyMode    ;
        $scope.birthDay                   = buyCardData2.birthDay;
        $scope.PaymentMethodName =buyCardData2.PaymentMethodName;
        $scope.giftTypeName =buyCardData2.giftTypeName;
        $scope.consultantName = buyCardData2.consultantName;
        $scope.sourceName = buyCardData2.sourceName
        $scope.getCardIntroMess($scope.cardId)

    }
    $scope.PaymentMethodTypeIsWx = false;
    $scope.PaymentMethodArr = [];
    var browser = {
        versions: function () {
            var u = navigator.userAgent, app = navigator.appVersion;
            return {         //移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
                iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1, //是否iPad
                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
            };
        }(),
        language: (navigator.browserLanguage || navigator.language).toLowerCase()
    }

    if (browser.versions.mobile) {//判断是否是移动设备打开。browser代码在下面
        var ua = navigator.userAgent.toLowerCase();//获取判断用的对象
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            //在微信中打开
            $scope.PaymentMethodType = 'wx';
            $scope.PaymentMethodTypeIsWx = true;
            $scope.PaymentMethodName    = '微信';
            $scope.PaymentMethodArr = [
                {
                    type:'wx',
                    name:'微信',
                }
            ]
        }else if(ua.match(/Alipay/i)=="alipay"){
            $scope.PaymentMethodType = 'pay';
            $scope.PaymentMethodTypeIsWx = true;
            $scope.PaymentMethodName    = '支付宝';
            $scope.PaymentMethodArr = [
                {
                    type:'pay',
                    name:'支付宝',
                }
            ]
        }else{
            $scope.PaymentMethodTypeIsWx = false;
            $scope.PaymentMethodArr = [
                {
                    type:'wx',
                    name:'微信',
                },
                {
                    type:'pay',
                    name:'支付宝',
                }
            ]
        }
        if (ua.match(/WeiBo/i) == "weibo") {
            //在新浪微博客户端打开
        }
        if (ua.match(/QQ/i) == "qq") {
            //在QQ空间打开
        }
        if (browser.versions.ios) {
            //是否在IOS浏览器打开
        }
        if(browser.versions.android){
            //是否在安卓浏览器打开
        }
    } else {
        //否则就是PC浏览器打开
        // console.log('否则就是PC浏览器打开');
        
    }


    $scope.venueId = $scope.getRequest().venueId;
    $scope.companyId = $scope.getRequest().companyId;

    $http.get('/purchase-card/get-source-data?companyId='+$scope.companyId +'&venueId='+$scope.venueId).then(function(response){
        $scope.sourceLists  = response.data;
    });
    $http.get('/purchase-card/get-adviser-data?companyId='+$scope.companyId +'&venueId='+$scope.venueId).then(function(response){
        $scope.membershipConsultantLists  = response.data;
    });


    //选择卡种
    $scope.selectCard = function(id,name){
        $('#cardKind').removeClass('js-show');
        $('#cardKindI').removeClass('icon-35').addClass('icon-74');
        $scope.cardId = id;
        $scope.selectMemberCardName = name;
        $scope.getCardIntroMess($scope.cardId);
    }

    //获取该场馆下所有的卡的种类
    $scope.cardLists = function(){
        if($scope.venueId != undefined && $scope.venueId != null && $scope.venueId != ''){
            $http({
                url: "/purchase-card/card-category?venueId="+ $scope.venueId,
                method: 'GET'
            }).then(function (date) {
                $scope.cardItems = date.data;
            })
        }
    }
    $scope.cardLists();

    //选择会籍顾问选时提示
    $scope.selectVenueWarn = function(){
        if($scope.venueId  == undefined){
            $timeout(function(){
                $('#consultantKind').removeClass('js-show');
                $('#consultantKindI').removeClass('icon-35').addClass('icon-74');
            },1);
            $scope.showMessage = function () {
                dialogsManager.showMessage("请先选择场馆!");
            }
            $scope.showMessage();
            return;
        }
    }

    //选择销售来源渠道时执行
    $scope.selectSourceWarn  = function(){
        if($scope.venueId  == undefined){
            $timeout(function(){
                $('#sourceKind').removeClass('js-show');
                $('#sourceKindI').removeClass('icon-35').addClass('icon-74');
            },1)
            $scope.showMessage = function () {
                dialogsManager.showMessage("请先选择场馆!");
            }
            $scope.showMessage();
            return;
        }
    }

    //选择来源渠道
    $scope.selectSource  = function(id,name){
        $('#sourceKind').removeClass('js-show');
        $('#sourceKindI').removeClass('icon-35').addClass('icon-74');
        $scope.sourceName = name;
        $scope.sourceId   = id;
    }//选择会籍顾问
    $scope.consultantSource  = function(id,name){
        $('#consultantKind').removeClass('js-show');
        $('#consultantKindI').removeClass('icon-35').addClass('icon-74');
        $scope.consultantName = name;
        $scope.adviserId   = id;
    }

    //选择赠品方式
    $scope.giftTypeSelect = function(val,name){
        $('#giftTypeKind').removeClass('js-show');
        $('#giftTypeKindI').removeClass('icon-35').addClass('icon-74');
        $scope.giftTypeName = name;
        $scope.giftTypeId   = val;
    }
    $scope.selectPaymentType = function(){
        if($scope.PaymentMethodTypeIsWx){
            $('#PaymentTypeKind').removeClass('js-show');
            $('#PaymentTypeKindI').removeClass('icon-35').addClass('icon-74');
        }
    }

    //支付方式选择
    $scope.PaymentMethodSelect = function(type,typeName){
        $('#PaymentTypeKind').removeClass('js-show');
        $('#PaymentTypeKindI').removeClass('icon-35').addClass('icon-74');
        $scope.PaymentMethodName   = typeName;
        $scope.PaymentMethodType   = type;
    }

    //点击新会员入会协议获取不同公司的合同
    $scope.getDealName = function(){
        $http.get('/purchase-card/get-deal-name?companyId='+ $scope.companyId).then(function(result){
            if(result.data != '' || result.data != null || result.data != undefined){
                $scope.newMemberProtocol = result.data;
                $scope.newIntroMess      = result.data.intro;
                localStorage.setItem('newContractData',JSON.stringify({
                    name:result.data.name,contractContent:result.data.intro
                }));
            }else{
                $scope.showMessage = function () {
                    dialogsManager.showMessage("请先添加新会员入会协议");
                }
                $scope.showMessage();
                return;
            }
        });
    }
    //输入身份证号时触发
    $scope.inputIdCard = function(){
        if($scope.idCard != undefined && $scope.idCard !=''&& $scope.idCard.length > 14){
            var year = $scope.idCard.substring(6,10);
            var month = $scope.idCard.substring(10,12);
            var day = $scope.idCard.substring(12,14);
            if(month != '' && day != '' && parseInt(month)<=12 && parseInt(day)<=31){
                $scope.birthDay =year+"-" + month+"-"+day;
            }
            if(parseInt(month)>12 || parseInt(month) == 0 && parseInt(day) == 0 || parseInt(day) > 31){
                $scope.showMessage = function () {
                    dialogsManager.showMessage("您的身份证号输入有误，请重新输入");
                }
                $scope.showMessage();
                return;
            }
        }else{
            $scope.birthDay ="";
        }
    }

    $scope.buyCardSubmitFlag = false;
    //点击确定提交数据
    $scope.confirm  = function(){
        var absurl = $location.absUrl();
        $scope.url = absurl + '/contract';
        $scope.HomeUrl = absurl;
        $scope.submitData = function(){
            return{
                name            :$scope.name        != undefined && $scope.name        != "" ? $scope.name       : null,
                idCard          :$scope.idCard        != undefined && $scope.idCard        != "" ? $scope.idCard       : null,
                idAddress       :$scope.idAddress        != undefined && $scope.idAddress        != "" ? $scope.idAddress       : null,
                nowAddress      :$scope.nowAddress        != undefined && $scope.nowAddress        != "" ? $scope.nowAddress       : null,
                mobile          :$scope.mobile        != undefined && $scope.mobile        != "" ? parseInt($scope.mobile)       : null,
                code            :$scope.code        != undefined && $scope.code        != "" ? parseInt($scope.code)      : null,
                newCode         :$scope.newCode        != undefined && $scope.newCode        != "" ? parseInt($scope.newCode)       : null,
                cardId          :$scope.cardId        != undefined && $scope.cardId        != "" ? parseInt($scope.cardId)      : null,
                companyId       :$scope.companyId,
                venueId         :$scope.venueId,
                url              :$scope.url,
                birthDay        :$scope.birthDay,
                sourceId         :$scope.sourceId         != undefined && $scope.sourceId        != "" ? $scope.sourceId      : null,
                seller           :$scope.adviserId         != undefined && $scope.adviserId        != "" ? $scope.adviserId      : null,
                giftStatus      :$scope.giftTypeId        != undefined && $scope.giftTypeId        != "" ? $scope.giftTypeId      : null,
                cardName        :$scope.selectMemberCardName        != undefined && $scope.selectMemberCardName        != "" ? $scope.selectMemberCardName      : null,
                amountMoney     :'',
                data             :[],
                merchantOrderNumber  :'',
                payMoneyMode    :$scope.PaymentMethodType        != undefined && $scope.PaymentMethodType        != "" ? $scope.PaymentMethodType      : null,
                _csrf_backend   :$('#_csrf').val()
            }
        }
        localStorage.setItem('buyCardData',JSON.stringify({
            name            :$scope.name        != undefined && $scope.name        != "" ? $scope.name       : null,
            idCard          :$scope.idCard        != undefined && $scope.idCard        != "" ? $scope.idCard       : null,
            idAddress       :$scope.idAddress        != undefined && $scope.idAddress        != "" ? $scope.idAddress       : null,
            nowAddress      :$scope.nowAddress        != undefined && $scope.nowAddress        != "" ? $scope.nowAddress       : null,
            mobile          :$scope.mobile        != undefined && $scope.mobile        != "" ? parseInt($scope.mobile)       : null,
            cardId          :$scope.cardId        != undefined && $scope.cardId        != "" ? parseInt($scope.cardId)      : null,
            companyId       :$scope.companyId,
            venueId         :$scope.venueId,
            url              :$scope.url,
            homeUrl          :$scope.HomeUrl,
            birthDay        :$scope.birthDay,
            PaymentMethodName:$scope.PaymentMethodName,
            giftTypeName     :$scope.giftTypeName,
            consultantName   :$scope.consultantName,
            sourceName       :$scope.sourceName,
            sourceId         :$scope.sourceId         != undefined && $scope.sourceId        != "" ? $scope.sourceId      : null,
            seller           :$scope.adviserId         != undefined && $scope.adviserId        != "" ? $scope.adviserId      : null,
            giftStatus      :$scope.giftTypeId        != undefined && $scope.giftTypeId        != "" ? $scope.giftTypeId      : null,
            cardName        :$scope.selectMemberCardName        != undefined && $scope.selectMemberCardName        != "" ? $scope.selectMemberCardName      : null,
            payMoneyMode    :$scope.PaymentMethodType        != undefined && $scope.PaymentMethodType        != "" ? $scope.PaymentMethodType      : null,
        }));
        var buyCardName = /^([a-zA-Z0-9\u4e00-\u9fa5 ]+)$/;
        if($scope.name == '' || $scope.name == null || $scope.name == undefined || !(buyCardName.test($scope.name))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请输入正确的姓名");
            }
            $scope.showMessage();
            return;
        }

        if($scope.idAddress == '' || $scope.idAddress == null || $scope.idAddress == undefined || !(buyCardName.test($scope.idAddress))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请输入合法的身份证地址!");
            }
            $scope.showMessage();
            return false;
        }

        if($scope.nowAddress == '' || $scope.nowAddress == null || $scope.nowAddress == undefined || !(buyCardName.test($scope.nowAddress))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请输入合法的现居住地址!");
            }
            $scope.showMessage();
            return false;
        }

        //验证身份证号
        var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        if ($scope.idCard == null || $scope.idCard == "" || !(idCardP.test($scope.idCard))) {
            $scope.showMessage = function () {
                dialogsManager.showMessage("请输入18位有效身份证号");
            }
            $scope.showMessage();
            return ;
        }

        //手机号验证
        var $pattern = /^1[34578]\d{9}$/;
        if ($scope.mobile == null || $scope.mobile == undefined || !($pattern.test($scope.mobile))) {
            $scope.showMessage = function () {
                dialogsManager.showMessage("请填写正确的手机号!");
            }
            $scope.showMessage();
            return false;
        }

        // 验证验证码
        if (parseInt($scope.newCode) != parseInt($scope.code)) {
            $scope.showMessage = function () {
                dialogsManager.showMessage("验证码输入错误");
            }
            $scope.showMessage();
            return ;
        }

        if(parseInt($scope.mobile) != parseInt($scope.oldMobile)){
            $scope.showMessage = function () {
                dialogsManager.showMessage("您的验证码和手机号不匹配！");
            }
            $scope.showMessage();
            return;
        }


        if($scope.sourceId == undefined || $scope.sourceId == null || $scope.sourceId == ''){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择来源渠道");
            }
            $scope.showMessage();
            return ;
        }

        if($scope.adviserId == undefined || $scope.adviserId == null || $scope.adviserId == ''){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择会籍顾问");
            }
            $scope.showMessage();
            return ;
        }

        if ($scope.cardId == null || $scope.cardId == "" || $scope.cardId == undefined) {
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择卡类型");
            }
            $scope.showMessage();
            return ;
        }

        if ($scope.giftTypeId == null || $scope.giftTypeId == "" || $scope.giftTypeId == undefined) {
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择赠品领取状态");
            }
            $scope.showMessage();
            return ;
        }

        if ($scope.PaymentMethodType == null || $scope.PaymentMethodType == "" || $scope.PaymentMethodType == undefined) {
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择支付方式");
            }
            $scope.showMessage();
            return ;
        }

        $scope.buyCardSubmitFlag = true;
        $http({
            url     : '/payment/sell-card',
            method  : 'POST',
            data    : $.param($scope.submitData()),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result){
            if(result.data.status == 'success'){
                if($scope.PaymentMethodType == 'wx'){
                    location.href = '/payment/js-api';
                }else if($scope.PaymentMethodType == 'pay'){
                    location.href = '/payment/pay';
                }
            }else{
                $scope.buyCardSubmitFlag = false;
                $scope.showMessage = function () {
                    dialogsManager.showMessage(result.data.message);
                }
                $scope.showMessage();
                return;
            }
        });
    }



    //注册验证码
    $scope.paracont  = "获取验证码";
    $scope.disabled  = false;

    $scope.getCode = function(){
        var $pattern = /^1[34578]\d{9}$/;
        if(!($pattern.test($scope.mobile))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请填写正确的手机号!");
            }
            $scope.showMessage();
            return ;
        }else{
            $scope.oldMobile = $scope.mobile;
            $http({
                url     : '/v1/api-member/create-code',
                method  : 'POST',
                data    : $.param({'mobile' : $scope.mobile}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function (result){
                $scope.code = result.data.data;
            });

            /**
             * @云运动 - 后台 - 注册页面
             * @author Zhu Mengke <zhumengke@itsports.club>
             * @create 2017/4/5
             * @inheritdoc
             */
            //注册验证码倒计时
            var second = 60,
                timePromise = undefined;

            timePromise = $interval(function(){
                if(second<=0){
                    $interval.cancel(timePromise);
                    timePromise = undefined;

                    second = 60;
                    $scope.paracont  = "获取验证码";
                    $scope.disabled  = false;
                }else{
                    $scope.paracont = second + "秒后重新获取";
                    $scope.disabled =true;
                    second--;
                }
            },1000,100);
        }
    };
}).controller('contractCtrl',function($scope,$http,$rootScope,$location){
    //新会员入会协议单独页面
    var contractData = localStorage.getItem('contractData');
    var $memberIdArr = angular.fromJson(contractData);
    $scope.getContractData = $memberIdArr.contractContent;
    $scope.contractName = $memberIdArr.name;
}).controller('newContractCtrl',function($scope,$http,$rootScope,$location){
    //根据不同的卡获取不同合同单独页面
    var newContractData = localStorage.getItem('newContractData');
    var $newContract = angular.fromJson(newContractData);
    $scope.getNewContractData = $newContract.contractContent;
    $scope.newContractName = $newContract.name;
}).controller('paymentOrderCtrl',function($scope,$http,$rootScope,$location){

}).controller('buyCardSuccessCtrl',function($scope,$http,$rootScope){
    localStorage.clear();
}).controller('orderCompleteCtrl',function($scope,$http,$rootScope,$location){
    var buyCardData1 = localStorage.getItem('buyCardData');
    var buyCardData2 = angular.fromJson(buyCardData1);
    if(buyCardData2 != null && buyCardData2 !='' && buyCardData2 != undefined){
        $scope.homeUrl123 = buyCardData2.homeUrl;
    }
    $scope.backHomePageClick = function(){
        if(buyCardData2 != null && buyCardData2 !='' && buyCardData2 != undefined){
            location.href = $scope.homeUrl123;
        }else{
        }
    }
    localStorage.clear();
    
}).filter('to_Html', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        }
    }]
);

