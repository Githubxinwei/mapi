angular.module('App').controller('addDepartmentCtrl', function($scope,$http){
    /**
     * 后台 - 组织架构管理 - 新增部门js
     * @author 侯凯新
     * @create 2017-4-28
     */
    //初始化数据
    $scope.init = function(){
        $scope.allVenues();
        $scope.topType();
    };
    //查询顶级所属于公司
    $scope.topType = function(){
        $http.get("/main/company-check").then(function (result) {
            $scope.companyS = result.data;
        })
    };
    //查询所有场馆
    $scope.allVenues = function(){
        $http.get("/main/all-venues").then(function (result) {
            $scope.venueS= result.data;
        })
    };
    //根据公司查询场馆
    $scope.searchVenue = function(id){
        if($scope.companyId!=""){
            $http.get("/main/bel-venues?id="+id).then(function (result) {
                $scope.venueS= result.data;
            })
        }
    };
    $scope.init();
    /**
     * 后台 - 组织架构管理 - 数据提交js
     * @author 侯凯新
     * @create 2017-4-28
     */
    $scope.add = function() {
        //整理发送后的数据
        $scope.addData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                companyId   : $scope.companyId  != undefined &&$scope.companyId     != "" ? $scope.companyId   : null,//公司id
                venueId     : $("#venue").val() != undefined &&$("#venue").val()    != "" ? $("#venue").val()  : null,//场馆id
                name        : $scope.depName     != undefined &&$scope.depName        != "" ? $scope.depName      : null,//部门名称
                code        : $scope.code        != undefined &&$scope.code           != "" ? $scope.code        : null//识别码
            }
        };
        if($scope.companyId ==null||$scope.companyId ==""){
            Message.warning("请选择所属公司");
            return false;
        }
        if($("#venue").val()==null||$("#venue").val()==""){
            Message.warning("请选择所属场馆");
            return false;
        }
        if($scope.depName==null||$scope.depName==""){
            Message.warning("请输入部门名字");
            return false;
        }
        if($scope.code==null||$scope.code==""){
            Message.warning("识别码不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/main/add-depart-data",
            method: 'POST',
            data: $.param($scope.addData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
           window.location.replace('/main/department?mid=21&c=20');
        });
    };






});