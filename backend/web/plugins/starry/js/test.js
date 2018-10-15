/**
 * Created by Administrator on 2017/10/31 0031.
 */

console.log(333);
angular.module('App', []).controller('testController', function ($scope) {
    $scope.name = "王康";
    console.log(2);
    $scope.menuState = {show: false};
    $scope.toggleMenu = function () {
        $scope.menuState.show = !$scope.menuState.show;
        // console.log($scope.name);
    }

});

