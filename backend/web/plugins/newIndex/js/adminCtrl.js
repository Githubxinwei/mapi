angular.module('App').controller('adminCtrl',function ($scope,$http) {
    //初始化方法
    $scope.init = function () {
        $scope.getMenuPath = '/jurisdiction/get-auth-module-all';
        // $scope.getMenuHttp();
    };
    //调用菜单
    $scope.getMenuHttp = function () {
        $http.get($scope.getMenuPath).then(function (result) {
            $scope.menuItems = result.data;
            console.log('result.data.data',$scope.menuItems);
        });
    };
    $scope.init();
});
