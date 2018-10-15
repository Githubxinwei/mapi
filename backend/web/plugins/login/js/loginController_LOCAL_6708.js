/**
 * 后台 - 登录js
 * @auther 梁可可
 * create 2017-3-28
 */
angular.module('app',[]).controller('loginCtrl', function($scope,$http){
   $scope.login = function(){};


    

})
/**
 * 后台 - 找回密码js
 * @auther 梁可可
 * create 2017-3-28
 */

/**
 * @云运动 - 后台 - 注册页面
 * @author Zhu Mengke <zhumengke@itsports.club>
 * @create 2017/3/29
 * @inheritdoc
 */
    .controller('resCtrl',function($scope,$http){

    //循环输出部门
    $http.get("/login/cloud-organization").success(function (response){
        $scope.datas = response;
        $scope.records = $scope.datas;
    });

    //注册
    $scope.res = {
        _csrf             : "",
        username          : "",
        mobile            : "",
        code              : "",
        name              : "",
        organization_id  : "",
        password          : "",
        repassword        : ""
    };
    $scope.getDatas = function () {

        $scope.SignupForm = {
            _csrf              :$("#_csrf").val(),
            username           :$scope.res.username,
            mobile             :$scope.res.mobile,
            code               :$scope.res.code,
            name               :$scope.res.name,
            organization_id   :$scope.res.organization_id,
            password           :$scope.res.password,
            repassword         :$scope.res.repassword
        };
        $scope.formInfo   = {
            SignupForm : $scope.SignupForm
        }
    };

    $scope.SignUpForm = function () {
        $scope.getDatas();
        $scope.data = angular.toJson($scope.formInfo);
        $http({
            url     : '/login/signup',
            method  : 'POST',
            data    : $.param($scope.formInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            $scope.datas = response;
            if($scope.datas.status == 'success'){
                Message.success($scope.datas.data);
                window.location.replace('/login/index');
            }else{
                angular.forEach($scope.datas.data,function (value,key) {
                    Message.warning(value);
                    window.location.replace('/login/register');
                })
            }
        });
    }

});