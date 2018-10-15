/**
 * Created by DELL on 2017/6/7.
 * 手机端购卡页面控制器
 */

var app = angular.module('App',['starter.services']).controller('accessRegisterCtrl',function($http,$scope,$rootScope,$interval,dialogsManager,$location,$timeout){
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

    //选择该公司的所有场馆
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
        $scope.sourceName='';
        $scope.consultantName='';
        $scope.venueId = id;
        $scope.companyName = name;
        $scope.createId = pid;
        $http.get('/purchase-card/get-source-data?companyId='+$scope.companyId +'&venueId='+$scope.venueId).then(function(response){
            $scope.sourceLists  = response.data;
        });
        $http.get('/purchase-card/get-adviser-data?companyId='+$scope.companyId +'&venueId='+$scope.venueId).then(function(response){
            $scope.membershipConsultantLists  = response.data;
        });
    }

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
    }//选择来源渠道
    $scope.consultantSource  = function(id,name){
        $('#consultantKind').removeClass('js-show');
        $('#consultantKindI').removeClass('icon-35').addClass('icon-74');
        $scope.consultantName = name;
        $scope.adviserId   = id;
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
                mobile          :$scope.mobile        != undefined && $scope.mobile        != "" ? parseInt($scope.mobile)       : null,
                code            :$scope.code        != undefined && $scope.code        != "" ? parseInt($scope.code)      : null,
                // newCode         :$scope.newCode        != undefined && $scope.newCode        != "" ? parseInt($scope.newCode)       : null,
                companyId       :$scope.companyId,
                venueId         :$scope.venueId,
                adviserId       :$scope.adviserId,
                sourceId         :$scope.sourceId,
                url              :$scope.url,
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
                dialogsManager.showMessage("请选择场馆!");
            }
            $scope.showMessage();
            return ;
        }

        if($scope.sourceId == undefined || $scope.sourceId == null || $scope.sourceId ==''){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择来源渠道!");
            }
            $scope.showMessage();
            return;
        }

        if($scope.adviserId == undefined || $scope.adviserId == null || $scope.adviserId ==''){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择会籍顾问!");
            }
            $scope.showMessage();
            return;
        }


        if(!$scope.submitButtonFlag){
            $scope.submitButtonFlag = true;

            $http.get('/potential-members/get-mobile-info?mobile='+ $scope.mobile +'&companyId='+$scope.companyId+'&venueId='+$scope.venueId).then(function(response) {
            if (response.data.status == "error") {
                $scope.submitButtonFlag = false;
                $scope.showMessage = function () {
                    dialogsManager.showMessage("该用户已登记!");
                }
                $scope.showMessage();
                return;
            } else {
                $http({
                    url: '/purchase-card/registration-venue',
                    method: 'POST',
                    data: $.param($scope.submitData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (result) {
                    if (result.data.status == 'success') {
                        $scope.showMessage = function () {
                            dialogsManager.showMessage("登记成功!");
                        }
                        $scope.showMessage();
                        location.href = '/purchase-card/complete-register';
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
}).filter('to_Html', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        }
    }]
);
