/**
 * 后台 - 登录js
 * @auther 梁可可 
 * create 2017-3-28
 *@update author Hou Kaixin houkaixin@itsport.club
 *@update 2017/3/30
 */
angular.module('app',[]).controller('loginCtrl', function($scope,$http){
    $scope.loginForm  =  {};
    $scope.getInfo    = {};
    $scope.loginHome   = function () {
        $scope.getMember();
        $http({
            url     : '/login/login',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            $scope.datas = response;
            if($scope.datas.status  == "success"){
                 $scope.dataTestAf= false;
                 $scope.dataTestBef=false;
                 // 执行跳转主页面
                 window.location.replace('/site/index');
            }else{
                 $scope.dataTestAf= true;
                 $scope.dataTestBef=false;
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
       $scope.getMember();
       if($scope.credentials.username!=''&&$scope.credentials.username!=undefined)
       {
           if($scope.credentials.password!=""&&$scope.credentials.password!=undefined)
           {
               $scope.dataTestBef=false;
              //数据审核过后进行 账号或密码验证
               $scope.loginHome();
           }else{
               $scope.dataTestBef=true;
               $scope.dataTestAf=false;
           }
       }else{
               $scope.dataTestBef=true;
               $scope.dataTestAf=false;
       }
   };
/**
* 后台 - 找回密码js
* @auther 梁可可
* create 2017-3-28
 */
}).controller('resCtrl',function($scope,$http){
    $scope.resgiter = function(){}
});