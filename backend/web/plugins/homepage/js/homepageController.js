/**
 * Created by Administrator on 2017/6/24.
 */
// console.log($('body').height());
$('.bgImg').height(
    $('body').height()
);

angular.module('App').controller('homepageController', function ($scope,$http) {
    $scope.signOut = function () {
        location.href = "/login/index";
        localStorage.clear();
        return false;
    }
    $http({method:'get',url:"/personnel/employee-center"}).then(function (data) {
        $scope.listData = data.data;
    },function (error) {
        console.log(error)
        Message.error("系统错误请联系工作人员")
    })
})