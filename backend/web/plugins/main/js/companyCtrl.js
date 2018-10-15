//组织管理中场地页面控制器
angular.module('App').controller('companyCtrl', function($scope,$http){
    /**
     * 后台 - 组织架构管理 - 公司（数据遍历和分页）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */
    //初始化页面加载数据
    $scope.init = function () {
        $scope.pageInitUrl = '/main/my-company';
        $scope.getClassModel();
        $scope.getCompany();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single1").select2();
    });
    // 获取后台组织架构数据
    $scope.getClassModel = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.nowPage    = result.data.now;
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
     * 后台 - 组织架构管理 - 公司 - 单条数据删除
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */
    $scope.del=function(id,name){
        Sweety.remove({
            url: "/main/del-company-data?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '提示：公司下若有未删除信息，将无法删除',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getClassModel();
        },function(){

        },true);
    };

    //获取公司信息
    $scope.getCompany       = function () {
        $http.get('/rechargeable-card-ctrl/get-company').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.companys      = result.data.venue;
                $scope.companyStauts = true;
            }else{
                $scope.companyStauts = false;
                $scope.companys = '暂无数据';
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
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.companyId       = null;
        $scope.topSearch       = null;
        $scope.courseName      = null;
        $scope.coachName      = null;
        $("#reservation").val("");
    };
    /**
     * 后台 - 组织架构管理 - 公司 （执行主界面数据搜索）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */
    //接受搜索参数
    $scope.searchModel = function(){
        return {
            topSearch    : $scope.topSearch    != undefined &&$scope.topSearch!=""? $scope.topSearch   : null    //综合字段搜索
            // startTime    : startTime            != undefined&&startTime         !=""?startTime           : null,    // 创建时间开始
            // endTime      : endTime              != undefined&&endTime           !=""?endTime              : null,     // 上课时间结束
            // sortType     : $scope.sortType     != undefined ? $scope.sortType                            : null,    //需要排序的字段名字
            // sortName     : $scope.sort          != undefined ? $scope.sort                                : null,    //排序的类型
            // companyId    : $scope.companyId    != undefined ? $scope.companyId                            : null    //公司id
        }
    };
    /**整理搜索参数以及搜索url**/
    $scope.initPath = function (){
        $scope.searchParams   =  $scope.searchModel();
        $scope.pageInitUrl    =  '/main/my-company?' + $.param($scope.searchParams);
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
     * 后台 - 组织架构管理 - 公司（执行数据修改（公司名称））
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */
    //点击的时候获取数据，同时将数据赋值文本框
    $scope.update=function(id,name){
        $("#companyId").val(id);
        $scope.companyName = name;
        $scope.transfer    = name;
    };
    $scope.companyUpdate = function() {
        //整理发送后的数据
          $scope.initData = function(){
              return {
                  _csrf_backend: $('#_csrf').val(),
                  companyId    : $("#companyId").val(),
                  name         : $scope.companyName  != undefined && $scope.name!= "" ? $scope.companyName  : null
              };
          };
        //保存数据之前数据验证
        if($scope.companyName==null||$scope.companyName==""){
            Message.warning("场馆名字不能为空");
            return false;
        }
        if($scope.companyName==$scope.transfer){
            Message.warning("提示:你未进行任何修改");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/main/update-company",
            method: 'POST',
            data: $.param($scope.initData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
           window.location.replace('/main/company');
        });
    };





});