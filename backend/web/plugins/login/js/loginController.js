/**
 * 后台 - 登录js
 * @auther 梁可可 
 * create 2017-3-28
 *@update author Hou kaiXin houkaixin@itsport.club
 *@update 2017/3/30
 */
angular.module('App').controller('loginCtrl', function($scope,$http){
   $scope.login = function(){};
    $scope.loginForm  =  {};
    $scope.getInfo    = {};
    $scope.loginHome   = function () {
        $('body').data('selectStart',false);
        $scope.getMember();
        $http({
            url     : '/login/login',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            $scope.datas = response;
            if($scope.datas.status  == "success"){
                 $scope.logining = false;
                 // 执行跳转主页面
                 window.location.replace('/site/index');
            }else{
                 $scope.dataTestAf= true;
                 Message.warning('用户名或密码有误');
                 $scope.logining = false;
                 $('#drag').html('');
                 $('#drag').drag();
            }
        });
        $scope.credentials = {
            username : '',
            password : ''
        };
    };
    $scope.getMember = function () {
        $scope.loginForm = {
            username:$scope.credentials.username,
            password:$scope.credentials.password
        };
        $scope.getInfo = {
            LoginForm : $scope.loginForm
        };
    };
   $scope.login = function(){
       var selectStart = $('body').data('selectStart');
       $scope.getMember();
       $scope.logining = true;
       if($scope.credentials.username!=''&&$scope.credentials.username!=undefined)
       {
           if($scope.credentials.password!=""&&$scope.credentials.password!=undefined)
           {
               $scope.dataTestBef=false;
               if(!selectStart){
                   Message.warning('您还未验证');
                   $scope.logining = false;
               }else{
                   //进行账号密码验证
                   $scope.loginHome();
               }
           }else{
               Message.warning('用户名或密码不能为空');
               $scope.logining = false;
           }
       }else{
               Message.warning('用户名或密码不能为空');
               $scope.logining = false;
       }
   };

/**
 * @云运动 - 后台 - 注册页面
 * @author Zhu Mengke <zhumengke@itsports.club>
 * @create 2017/3/29
 * @inheritdoc
 */
}).controller('resCtrl',function($scope,$http,$interval){
    $http.get("/login/get-venue").success(function (response){
        $scope.company = response.venue;

    });
    $scope.getVenue = function(companyId){
        if(companyId){
            $http.get("/login/get-venue-data?venueId=" + companyId).success(function (response){
                $scope.venues = response.venue;
            })
        }else{
            $http.get("/login/get-venue-all-data").success(function (response){
                $scope.venues = response.venues;
            });
        }
    };
    $scope.getDepartment= function(venueId){
        if(venueId){
            //循环输出部门
            $http.get("/login/get-department?depId=" + venueId).success(function (response){
                $scope.records = response.venue;
            });
        }else{
            //循环输出部门
            $http.get("/login/cloud-organization").success(function (response){
                $scope.datas = response;
                $scope.records = $scope.datas;
            });
        }
    };



    //注册
    $scope.res = {
        _csrf             : "",
        username          : "",
        mobile            : "",
        code              : "",
        name              : "",
        organization_id  : "",
        company_id        :"",
        venue_id          :"",
        password          : "",
        rePassword        : ""
    };
    $scope.getDatas = function () {
        $scope.SignupForm = {
            _csrf              :$("#_csrf").val(),
            username           :$scope.res.username,
            mobile             :$scope.res.mobile,
            code               :$scope.res.code,
            name               :$scope.res.name,
            organization_id   :$scope.res.organization_id,
            company_id         :$scope.res.company_id,
            venue_id           :$scope.res.venue_id,
            password           :$scope.res.password,
            rePassword         :$scope.res.rePassword
        };
        $scope.formInfo   = {
            SignupForm : $scope.SignupForm
        };
    };
    $scope.SignUpForm = function () {
        // $scope.register = true;
        $scope.getDatas();
        $scope.data = angular.toJson($scope.formInfo);
        if($scope.res.username == '' || $scope.res.username == undefined){
            Message.warning('用户名不能为空');
            return
        }
        $http({
            url     : '/login/signup',
            method  : 'POST',
            data    : $.param($scope.formInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            $scope.datas = response;
            //console.log(response)
            if($scope.datas.status == 'success'){
                Message.success($scope.datas.data);
                setTimeout("window.location.replace('/login/index')",1000);
            }else{
                angular.forEach($scope.datas.data,function (value,key) {
                    Message.warning(value);
                })
            }
            // $scope.register = false;
        });
    };

    //注册验证码
    $scope.paracont  = "获取验证码";
    $scope.disabled  = false;

    $scope.newCode = function(){
        if(!$scope.res.mobile){
            Message.warning('请填写正确的手机号！');
        }else{
            $http({
                url     : '/login/create-code',
                method  : 'POST',
                data    : $.param({'mobile' : $scope.res.mobile}),
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
            $scope.isRigisterCodeButtonClick = true;
            var second = 60,
                timePromise = undefined;
            timePromise = $interval(function(){
                if(second<=0){
                    $scope.isRigisterCodeButtonClick = false;
                    $interval.cancel(timePromise);
                    timePromise = undefined;
                    second = 60;
                    $scope.paracont  = "获取验证码";
                    $scope.disabled  = false;
                }else{
                    $scope.paracont = second + "秒后重新获取";
                    $scope.disabled =true;
                    second--;
                    $scope.isRigisterCodeButtonClick = false;
                }
            },1000);
        }
    };

/**
 * @云运动 - 后台 - 重置密码
 * @author Zhu Mengke <zhumengke@itsports.club>
 * @create 2017/4/5
 * @inheritdoc
 */
}).controller('pasCtrl',function($scope,$http,$interval){
    //重置密码
    $scope.pas = {
        _csrf_backend     : "",
        mobile             : "",
        code               : "",
        password           : "",
        rePassword         : ""
    };
    $scope.getDatas = function () {
        $scope.rePasswordForm = {
            _csrf_backend      :$("#_csrf").val(),
            mobile             :$scope.pas.mobile,
            code               :$scope.pas.code,
            password           :$scope.pas.password,
            rePassword         :$scope.pas.rePassword
        };
        $scope.formInfo   = {
            PasswordResetRequestForm : $scope.rePasswordForm
        }
    };

    $scope.RePasswordForm = function () {
        //$scope.password = true;
        $scope.getDatas();
        $scope.data = angular.toJson($scope.formInfo);
        if($scope.pas.password != $scope.pas.rePassword){
            Message.warning("两次输入的密码不一致");
            return false;
        }
        if($scope.pas.password == '' || $scope.pas.password == undefined){
            Message.warning("请输入长度为6-18位的新密码");
            return false;
        }
        if($scope.pas.code != $scope.code){
            Message.warning("验证码错误");
            return false;
        }
        $http({
            url     : '/login/reset-password',
            method  : 'POST',
            data    : $.param($scope.formInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            //$scope.password = false;
            $scope.datas = response;
            //console.log(response)
            if($scope.datas.status == 'success'){
                Message.success($scope.datas.data);
                setTimeout("window.location.replace('/login/index')",1000);
            }else{
                //$scope.password = false;
                // angular.forEach($scope.datas.data,function (value,key) {
                //     Message.warning(value);
                // })
                if($scope.datas.data.mobile != undefined || $scope.datas.data.mobile != null ||$scope.datas.data.mobile != ''){
                    Message.warning($scope.datas.data.mobile);
                    return false;
                }
            }
        });
    };

    //重置密码验证码
    $scope.paracont  = "获取验证码";
    $scope.disabled  = false;

    $scope.newCode = function(){
        if(!$scope.pas.mobile){
            Message.warning('请填写正确的手机号！');
        }else{
            $http({
                url     : '/login/create-code',
                method  : 'POST',
                data    : $.param({'mobile' : $scope.pas.mobile}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function (result){
                $scope.code = result.data.data;
                //console.log(result)
            });

            //重置密码验证码倒计时
            $scope.isCodeButtonClick = true;
            var second = 60,
                timePromise = undefined;
            timePromise = $interval(function(){
                if(second<=0){
                    $scope.isCodeButtonClick = false;
                    $interval.cancel(timePromise);
                    timePromise = undefined;
                    second = 60;
                    $scope.paracont  = "获取验证码";
                    $scope.disabled  = false;
                }else{
                    $scope.paracont = second + "秒后重新获取";
                    $scope.disabled =true;
                    second--;
                    $scope.isCodeButtonClick = false;
                }
            },1000);
        }
    };

});