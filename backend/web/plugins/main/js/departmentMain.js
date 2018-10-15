
angular.module('App').controller('departmentMainCtrl', function($scope,$http){
    /**
     * 后台 - 组织架构管理 - 分页（组织架构主界面数据遍历）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */
    angular.element(document).ready(function () {
        $(".js-example-basic-single1").select2();
    });
    //初始化页面加载数据
    $scope.init = function () {
        $scope.pageInitUrl = '/main/departments';
        $scope.getClassModel();
        $scope.getDepartment();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };
    // 获取后台组织架构数据
    $scope.getClassModel = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.dataInfo   = false;
                $scope.searchData = false;
            }else{
                $scope.dataInfo = true;
            }
            $scope.items  = result.data.data;
            $scope.pages  = result.data.pages;
        })
    };

    /**
     * 后台 - 组织架构管理 - 部门 - 单条数据删除
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/28
     */
    $scope.del=function(id,name){
        Sweety.remove({
            url: "/main/del-data?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '部门删除后无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getClassModel();
        },function(){

        },true);
    };

    //获取部门信息
    $scope.getDepartment = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.venues      = result.data;
                $scope.departmentStauts = true;
            }else{
                $scope.departmentStauts = false;
                $scope.departments = '暂无数据';
            }
        });
    };
    //执行回车搜索
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
        }
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.venueId = '';
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.departId    = null;
        $scope.topSearch       = null;
        $scope.courseName      = null;
        $scope.coachName       = null;
        $("#reservation").val("");
    };
    /**
     * 后台 - 组织架构管理 - 执行主界面数据搜索
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */
    //接受搜索参数
    $scope.searchModel = function(){
        return {
            topSearch : $scope.topSearch != undefined ? $scope.topSearch  : null,    //综合字段搜索
            // startTime    : startTime           != undefined&&startTime!="" ? startTime                 : null,    // 创建时间开始
            // endTime      : endTime             != undefined&&endTime  !="" ? endTime                   : null,     // 上课时间结束
            // sortType     : $scope.sortType     != undefined ? $scope.sortType                          : null,    //需要排序的字段名字
            // sortName     : $scope.sort         != undefined ? $scope.sort                              : null,    //排序的类型
            venueId   : $scope.venueId != undefined && $scope.venueId != '' ? $scope.venueId      : null    //场馆id
        }
    };
    /**整理搜索参数以及搜索url**/
    $scope.initPath = function (){
        $scope.searchParams   =  $scope.searchModel();
        $scope.pageInitUrl    =  '/main/departments?' + $.param($scope.searchParams);
    };
    //执行最终搜索
    $scope.search = function() {
        $scope.initPath();
        $scope.getClassModel();
    };
    //排序入口
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.search();
    };
    //asc desc轮流转换
    $scope.switchSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    //初始化加载数据
    $scope.init();

    /**
     * 后台 - 组织架构管理 - 执行主界面数据修改
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/28
     */
    $scope.update=function(id,depName,code){
        //点击的时候初始化数据
        $scope.initData = function (){
            $scope.DepartmentId = id;
            $scope.name          = depName;
            $scope.Tranfername   = depName;
            $scope.code          = code;
            $scope.Tranfercode   = code;
        };
        $scope.initData();
    };
    //点击修改的时候将对应数据赋值给弹框
    $scope.depUpdate = function() {
        //整理发送后的数据
        $scope.initData = function(){
            return {
                _csrf_backend: $('#_csrf').val(),
                DepartmentId  : $("#departId").val(),
                code          : $scope.code  != undefined && $scope.code!= "" ? $scope.code  : null,
                name          : $scope.name  != undefined && $scope.name!= "" ? $scope.name  : null
            };
        };
        //保存数据之前数据验证
        if($scope.name ==null||$scope.name==""){
            Message.warning("部门名字不能为空");
            return false;
        }
        if($scope.code ==null||$scope.code==""){
            Message.warning("部门识别码不能为空");
            return false;
        }
        if($scope.name ==$scope.Tranfername&&$scope.code ==$scope.Tranfercode){
            Message.warning("提示:你未进行任何修改");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/main/update-department",
            method: 'POST',
            data: $.param($scope.initData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
             window.location.replace('/main/department');
        });
    };











});