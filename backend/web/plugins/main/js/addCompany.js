angular.module('App').controller('addCompanyCtrl',function($scope,$http,Upload){
    /**
     * 后台 - 组织架构管理 - 新增公司js
     * @author 侯凯新
     * @create 2017-4-24
     */
    //获取前台数据并提交给后台
    $scope.add = function() {
        //整理发送后的数据
        $scope.addData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                name                 : $scope.name != undefined &&$scope.name!= "" ? $scope.name: null,//名称
                pid                  :0
            }
        };
        if($scope.name==null||$scope.name==""){
            Message.warning("公司名字不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/main/add-company-data",
            method: 'POST',
            data: $.param($scope.addData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
            window.location.replace('/main/company?mid=21&c=20');
        });
    };
});