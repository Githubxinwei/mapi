/**
 * Created by DELL on 2017/6/7.
 * 手机端购卡页面控制器
 */

var app = angular.module('App',['starter.services']).controller('memberRegisterCtrl',function($http,$scope,$rootScope,$interval,dialogsManager,$location){
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
    $scope.companyId = $scope.getRequest().companyId;

    //获取该公司的所有场馆
    $scope.getCompany = function(){
        $http.get('/purchase-card/get-venue-by-company-id?companyId='+ $scope.companyId  ).then(function(result){
            $scope.companyDatas = result.data;
        });
    }
    $scope.getCompany();

    //选择场馆
    $scope.selectCompany = function(id,name,pid){
        $('#company').removeClass('js-show');
        $('#companyI').removeClass('icon-35').addClass('icon-74');
        $scope.venueId = id;
        $scope.companyName = name;
        $scope.createId = pid;
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
        if($scope.idCard != undefined && $scope.idCard !=''&& $scope.idCard.length >= 14){
            var year = $scope.idCard.substring(6,10);
            var month = $scope.idCard.substring(10,12);
            var day = $scope.idCard.substring(12,14);
            if(month != '' && day != '' && parseInt(month) <= 12 && parseInt(day) <= 31 && parseInt(month) > 0 && parseInt(day) > 0 && parseInt(year) > 1500 && parseInt(year) < 2500){
                $scope.birthDay =year+"-" + month+"-"+day;
            }
            if(parseInt(month)>12 || parseInt(month) == 0 && parseInt(day) == 0 || parseInt(day) > 31 || parseInt(year)<= 1500 || parseInt(year)>= 2500){
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

    //提交标志
    $scope.submitButtonFlag = false;
    //点击确定提交数据
    $scope.confirm  = function(){
        var absurl = $location.absUrl();
        $scope.url = absurl + '/contract';
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
                        // birthDay         :birthday,
                        birthDay        :$scope.birthDay,
                        _csrf_backend   :$('#_csrf').val()
                }
            }
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

        //验证验证码
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


        if($scope.venueId == undefined || $scope.venueId == null || $scope.venueId == ''){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择公司");
            }
            $scope.showMessage();
            return ;
        }

        if(!$scope.submitButtonFlag){
            $http.get('/potential-members/get-mobile-info?mobile='+ $scope.mobile +'&companyId='+$scope.companyId).then(function(response) {
            if (response.data.status == "error") {
                $scope.submitButtonFlag = false;
                $scope.showMessage = function () {
                    dialogsManager.showMessage("该用户已登记!");
                }
                $scope.showMessage();
                return;
            } else {
                $http({
                    url: '/purchase-card/sell-card',
                    method: 'POST',
                    data: $.param($scope.submitData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (result) {
                    if (result.data.status == 'success') {
                        $scope.showMessage = function () {
                            dialogsManager.showMessage("会员登记成功!");
                        }
                        $scope.showMessage();
                        location.href = '/purchase-card/complete';
                    } else {
                        $scope.submitButtonFlag = false;
                        $scope.showMessage = function () {
                            dialogsManager.showMessage(result.data.message);
                        }
                        $scope.showMessage();
                        return;
                    }

                });
            }
        });
        }
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
}).filter('to_Html', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        }
    }]
);
