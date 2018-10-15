/**
 * Created by suyu on 2017/10/23.
 */
angular.module('App').controller('memberShipController', function($scope,$http,$timeout,$interval){

    // 定义背景高度等于浏览器高度
    $(".registerBgBox").height($(window).height());
    $(".indexBgBox").height($(window).height());
    $(".cardBgBox").height($(window).height());
    $(".detailsBgBox").height($(window).height());

    // 点击卡种类型分类的事件
    $(document).ready(function (){
        $(".cardCategoryTitleBox").click(function (){
            var $delect = $(".cardCategoryTitleBox").not($(this));
            $(this).addClass("addCardCategoryTitleBox"); //添加自身样式
            $delect.removeClass("addCardCategoryTitleBox"); //删除别的样式
        });
    });

    // 注册登录切换
    $scope.old = function (){
        window.location.href = "/member-ship/login";
    };
    $scope.new = function (){
        window.location.href = "/member-ship/register";
    };

    // 关闭按钮
    $scope.close = function (){
        history.go(-1);
    };
    // 主页按钮
    $scope.home = function (){
        $scope.id = "";
        $scope.cardCategoryId = "";
        window.location.href  = "/member-ship/index";
    };
    /*** 会员注册 ***/
    // 根据手机号获取信息
    $(".mobileInput").blur(function (){
        $scope.getMobileInfo();
    });
    $scope.getMobileInfo = function (){
        $http.get("/member-ship/potential-member?mobile=" + $scope.memberMobile).success(function (data){
            if(data.data.length != 0 && data.isNotPotentialMember == 2){
                if(data.data != []){
                    $scope.penMemberInfo = data.data;
                    if(data.data.name != null){
                        $scope.memberName = data.data.name;
                    }
                    if(data.data.sex != null){
                        $scope.memberSex = data.data.sex;
                    }
                    if(data.data.id_card != null){
                        $scope.memberIDcard = data.data.id_card;
                    }
                }
            }
            else if(data.isNotPotentialMember == 3){
                Message.warning("您已经是有卡会员，请从有卡会员处登录");
                return false;
            }
            else{

            }
        })
    };
    $scope.paracont = "获取验证码";
    // 获取验证码
    $scope.getCode = function (){
        if(!(/^1[0-9]{10}$/.test($scope.memberMobile))){
            Message.warning("手机号填写有误，请重新填写");
            return;
        }
        else {
            var second = 15,
            timePromise = undefined;
            timePromise = $interval(function () {
                if (second <= 0) {
                    $interval.cancel(timePromise);
                    timePromise = undefined;
                    second = 15;
                    $scope.paracont = "获取验证码";
                    $scope.disabled = false;
                }
                else {
                    $scope.paracont = second + "S后获取";
                    $scope.disabled = true;
                    second--;

                }
            }, 1000, 100);
            // 发送验证码
            $http({
                url: '/v1/api-member/create-code',
                method: 'POST',
                data: $.param({'mobile': $scope.memberMobile, '_csrf_backend': $('#_csrf').val()}),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result){
                $scope.code = result.data.data;
            });
        }
    };
    // 整理注册信息
    $scope.memberData = function (){
        return{
            memberName : $scope.memberName    != undefined && $scope.memberName   != "" ? $scope.memberName   : null, //会员姓名
            sex        : $scope.memberSex     != undefined && $scope.memberSex    != "" ? $scope.memberSex    : null, //会员性别
            mobile     : $scope.memberMobile  != undefined && $scope.memberMobile != "" ? $scope.memberMobile : null, //手机号
            code       : $scope.memberCode    != undefined && $scope.memberCode   != "" ? $scope.memberCode   : null, //验证码
            IDNumber   : $scope.memberIDcard  != undefined && $scope.memberIDcard != "" ? $scope.memberIDcard : null, //身份证号
            _csrf_backend : $('#_csrf').val() // csrf
        }
    };
    // 提交注册信息
    $scope.postMemberInfo = function (){
        // console.log($scope.memberSex);
        // 保存数据前的验证
        if($scope.memberName == "" || $scope.memberName == null){
            Message.warning("请输入会员姓名");
            return;
        }
        if($scope.memberSex == "" || $scope.memberSex == null){
            Message.warning("请选择会员性别");
            return;
        }
        if(!(/^1[0-9]{10}$/.test($scope.memberMobile)) || $scope.memberMobile == null){
            Message.warning("手机号填写有误，请重新填写");
            return;
        }
        if($scope.memberCode != $scope.code || $scope.memberCode == null){
            Message.warning("验证码错误");
            return;
        }
        var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        if(!(idCardP.test($scope.memberIDcard)) || $scope.memberIDcard == null){
            Message.warning("身份证输入有误，请重新填写");
            return;
        }
        $http({
                url: "/member-ship/member-register",
                method: 'POST',
                data: $.param($scope.memberData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data){
                if(data.data.status == "success"){
                    Message.success(data.data.data);
                    $scope.id = data.data.member.id;
                    $scope.memberLoginName = data.data.member.memberName;
                    localStorage.setItem("memberId",$scope.id);
                    localStorage.setItem("memberName",$scope.memberLoginName);
                    $scope.memberName    = "";
                    $scope.memberMobile  = "";
                    $scope.memberSex     = "";
                    $scope.memberCode    = "";
                    $scope.memberIDcard  = "";
                    window.location.href = "/member-ship/card"
                }else {
                    Message.warning(data.data.mobile[0]);
                }
            })


    };
    /*** 会员注册结束 ***/

    /*** 有卡会员登录 ***/
    // 有卡会员获取验证码
    $scope.getCardCode = function (){
        if(!(/^1[0-9]{10}$/.test($scope.oldMemberMobile))){
            Message.warning("手机号填写有误，请重新填写");
            return;
        }
        else {
            var second = 15,
                timePromise = undefined;
            timePromise = $interval(function () {
                if (second <= 0) {
                    $interval.cancel(timePromise);
                    timePromise = undefined;
                    second = 15;
                    $scope.paracont = "获取验证码";
                    $scope.disabled = false;
                }
                else {
                    $scope.paracont = second + "S后获取";
                    $scope.disabled = true;
                    second--;

                }
            }, 1000, 100);
            // 发送验证码
            $http({
                url: '/v1/api-member/create-code',
                method: 'POST',
                data: $.param({'mobile': $scope.oldMemberMobile, '_csrf_backend': $('#_csrf').val()}),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result){
                $scope.oldCode = result.data.data;
            });
        }
    };
    // 整理登录信息
    $scope.memberOldData = function (){
        return{
            mobile : $scope.oldMemberMobile != undefined && $scope.oldMemberMobile != "" ? $scope.oldMemberMobile : null, //手机号
            code   : $scope.oldMemberCode   != undefined && $scope.oldMemberCode   != "" ? $scope.oldMemberCode   : null, //验证码
            _csrf_backend : $('#_csrf').val() // csrf
        }
    };
    // 提交登录
    $scope.postOldMemberInfo = function (){
        // 保存数据前的验证
        if(!(/^1[0-9]{10}$/.test($scope.oldMemberMobile))){
            Message.warning("手机号填写有误，请重新填写");
            return false;
        }
        if($scope.oldMemberCode != $scope.oldCode){
            Message.warning("验证码错误");
            return false;
        }
        else {
            $http({
                url: "/member-ship/member-login",
                method: 'POST',
                data: $.param($scope.memberOldData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data){
                if(data.data.status == "success"){
                    Message.success(data.data.message);
                    $scope.id = data.data.member.id;
                    $scope.memberLoginName = data.data.member.memberName;
                    localStorage.setItem("memberId",$scope.id);
                    localStorage.setItem("memberName",$scope.memberLoginName);
                    $scope.oldMemberCode   = "";
                    $scope.oldMemberMobile = "";
                    window.location.href   = "/member-ship/card"
                }
                else{
                    Message.warning(data.data.message);
                    return false;
                }
            })
        }

    };
    /*** 会员登录结束 ***/

    /*** 获取相应类型会员卡 ***/
    // 瑜伽
    $scope.cardTypeYoga = function (){
        $scope.cardList = [];
        $scope.memberLoginName = localStorage.getItem("memberName");
        $http.get("/member-ship/get-card-category?cardType=1" + "&page=").success(function (data){
            if(data.data != [] && data.data != ""){
                $scope.cardList = data.data;
                $(".noCardInfoShow").hide();
            }
            else{
                $(".noCardInfoShow").show();
            }
        })
    };
    $scope.cardTypeYoga();
    // 健身
    $scope.cardTypeBody = function (){
        $scope.cardList = [];
        $http.get("/member-ship/get-card-category?cardType=2" + "&page=").success(function (data){
            if(data.data != [] && data.data != ""){
                $scope.cardList = data.data;
                $(".noCardInfoShow").hide();
            }
            else{
                $(".noCardInfoShow").show();
            }
        })
    };
    // 舞蹈
    $scope.cardTypeDance = function (){
        $scope.cardList = [];
        $http.get("/member-ship/get-card-category?cardType=3" + "&page=").success(function (data){
            if(data.data != [] && data.data != ""){
                $scope.cardList = data.data;
                $(".noCardInfoShow").hide();
            }
            else{
                $(".noCardInfoShow").show();
            }
        })
    };
    // 综合
    $scope.cardTypeMore = function (){
        $scope.cardList = [];
        $http.get("/member-ship/get-card-category?cardType=4" + "&page=").success(function (data){
            if(data.data != [] && data.data != ""){
                $scope.cardList = data.data;
                $(".noCardInfoShow").hide();
            }
            else{
                $(".noCardInfoShow").show();
            }
        })
    };
    // VIP
    $scope.cardTypeVip = function (){
        $scope.cardList = [];
        $http.get("/member-ship/get-card-category?cardType=5" + "&page=").success(function (data){
            if(data.data != [] && data.data != ""){
                $scope.cardList = data.data;
                $(".noCardInfoShow").hide();
            }
            else{
                $(".noCardInfoShow").show();
            }
        })
    };
    /*** 获取卡结束 ***/

    /*** 选择卡后购卡 ***/
    $scope.choiceBuyCard = function (data){
        $scope.cardCategoryId = data.id;
        window.location.href  = "/member-ship/details";
        localStorage.setItem("buyInfo",$scope.cardCategoryId);
    };
    /*** 选择卡后购卡 ***/

}).controller('memberShipBuyCardController', function($scope,$http,$timeout,$interval){
    // 二维码设置参数方式
    var qrcode = new QRCode('qrcode', {
        width: 256,
        height: 256,
        colorDark : '#000000',
        colorLight : '#ffffff',
        correctLevel : QRCode.CorrectLevel.H
    });
    // // 二维码设置参数方式
    // var qrcodes = new QRCode('alipay', {
    //     width: 256,
    //     height: 256,
    //     colorDark : '#000000',
    //     colorLight : '#ffffff',
    //     correctLevel : QRCode.CorrectLevel.H
    // });
    $(document).ready(function (){
        $scope.cardCategoryId  = localStorage.getItem("buyInfo");
        $scope.id              = localStorage.getItem("memberId");
        $scope.memberLoginName = localStorage.getItem("memberName");
        $scope.getCardDetails();
    });
    // 获取卡信息
    $scope.getCardDetails = function (){
        $http.get("/member-ship/card-category-message?id=" + $scope.cardCategoryId).success(function (data){
            $scope.detailsList      = data.data;
            $scope.detailsVenueList = data.data.theLimitCardNumber;
            $scope.detailsClassList = data.data.leagueBindPack;
            $scope.detailsProList   = data.data.privateLessonPack;
            $scope.detailsGiftList  = data.data.giftPack;
            if($scope.detailsList.dealIntro != null && $scope.detailsList.dealIntro != undefined){
                $(".dealTiele").html($scope.detailsList.dealName);
                $(".dealBox").html($scope.detailsList.dealIntro);
                $(".noDetailsInfoShow").hide();
            }
            else{
                $(".dealTiele").hide();
                $(".dealBox").hide();
                $(".noDetailsInfoShow").show();
            }
        });
    };
    // 提交购卡
    $scope.goBuy = function (){
        var $check = $(".checkProtocol").is(':checked');
        if($check == false){
            Message.warning("请查看并同意协议");
            return false;
        }
        else{
            $("#paymentModal").modal("show");
        }
    };
    /*** 选择支付方式 ***/
    // 微信
    $scope.wechatClick = function (){
        $("#erweimaModal").modal("show");
        $scope.getWechatPic();
    };
    // 支付宝
    $scope.payClick = function (){
        // $("#aliPayModal").modal("show");
        $scope.getAliPayPic();
        // Message.warning("功能正在开发中！");
    };
    /*** 选择支付方式结束 ***/
    /*** 二维码 ***/
    // 整理微信支付
    $scope.wechatData = function (){
        return{
            paymentType : "wx", //支付类型 微信
            type        : "machineCard", //支付类型 微信
            typeId      : $scope.cardCategoryId != undefined && $scope.cardCategoryId != "" ? $scope.cardCategoryId : null, //卡id
            memberId    : $scope.id             != undefined && $scope.id             != "" ? $scope.id             : null, //会员id
            _csrf_backend : $('#_csrf').val() // csrf
        }
    };
    // 微信
    $scope.getWechatPic = function (){
        $http({
            url: "/member-ship-pay/sell-card",
            method: 'POST',
            data: $.param($scope.wechatData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data){
            if(data.data.status == "success"){
                qrcode.makeCode(data.data.data);
                $scope.orderId = data.data.orderId;
                var i = 0;
                var end = setInterval(function(){
                    if(i>=20){
                        clearInterval(end);
                        $("#erweimaModal").modal("hide");
                        $("#errorModal").modal("show");
                    }
                    $scope.getWechatOrderInfo();
                    i++;
                },3000);
            }
            else{
                Message.warning("支付信息错误，请刷新页面")
            }
        });
    };
    // 微信查询订单状态
    $scope.getWechatOrderInfo = function (){
        $http.get("/member-ship-pay/check-order-status?orderId=" + $scope.orderId).success(function (data){
            if(data.orderStatus == 2){
                $("#erweimaModal").modal("hide");
                $("#successModal").modal("show");
                $timeout(function (){
                    window.location.href = "/member-ship/index";
                },5000);
            }
        });
    };
    // 整理支付宝信息
    $scope.aliPayData = function (){
        return{
            paymentType : "zfbScanCode", //支付类型 支付宝
            type        : "card", //支付类型 支付宝
            typeId      : $scope.cardCategoryId != undefined && $scope.cardCategoryId != "" ? $scope.cardCategoryId : null, //卡id
            memberId    : $scope.id             != undefined && $scope.id             != "" ? $scope.id             : null, //会员id
            _csrf_backend : $('#_csrf').val() // csrf
        }
    };
    // 支付宝
    $scope.getAliPayPic = function (){
        // 动态创建from表单代替ajax去提交请求
        function MakeForm(str,str2){
            var form1 = document.createElement("form");
            form1.id   = "form1";
            form1.name = "form1";
            document.body.appendChild(form1);
            var input  = document.createElement("input");
            input.type  = "text";
            input.name  = "paymentType";
            input.value = "zfbScanCode";
            form1.appendChild(input);
            var input2  = document.createElement("input");
            input2.type  = "text";
            input2.name  = "type";
            input2.value = "card";
            form1.appendChild(input2);
            var input3  = document.createElement("input");
            input3.type  = "text";
            input3.name  = "typeId";
            input3.value = $scope.cardCategoryId;
            form1.appendChild(input3);
            var input4   = document.createElement("input");
            input4.type  = "text";
            input4.name  = "memberId";
            input4.value = $scope.id;
            form1.appendChild(input4);
            form1.method = "POST";
            form1.action = "/member-ship-pay/ali-sell-card";
            form1.submit();
            document.body.removeChild(form1);
        }
        MakeForm();
    };
    $scope.backIndex = function (){
        window.location.href = "/member-ship/index"
    };
    /*** 二维码结束 ***/
});