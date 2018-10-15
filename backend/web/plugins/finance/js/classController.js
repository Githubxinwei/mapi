/**
 * Created by suyu on 2017/8/28.
 * 上课收入的控制器
 */

/**
 财务管理模块-上课统计页面
 内容：场馆选择、日期选择、课程分类、上课节数、上课金额、会员姓名、手机号、教练姓名、手机号

 * @author zhujunzhe@itsports.club
 * @create 2017.12.2
 */

var appModule = angular.module('App');
appModule.controller('classCtrl',function ($scope,$http){

    /*** 日期插件 ***/
    // 插件启动
    $(function(){
        $('#classDateSelect').daterangepicker(null, function(start, end, label) {
        });
        $('#couserDateSelect').daterangepicker(null, function(start, end, label) {
        });
        $('#usserDateSelect').daterangepicker(null, function(start, end, label) {
        });
    });

    $(document).ready(function (){
        $scope.dateInit();
        $scope.initPath();
        $scope.searchOp();
    });
    // 总列表日期插件设置默认值
    $scope.dateInit = function () {
        if($('#classDateSelect').val() == ""){
            var dd = new Date();
            var y = dd.getFullYear();
            var m = dd.getMonth()+1;//获取当前月份的日期
            var d = dd.getDate();
            $scope.date = y+"-"+m+"-"+d;
            $("#classDateSelect").val($scope.date);
            $scope.orderStartDate = $scope.date + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.date + ' ' + "23:59:59"; //结束日期
        }
        else{
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    };


    // 总列表日期插件点击确定时
    $('#classDateSelect').on('apply.daterangepicker', function(ev, picker){
        if($('#classDateSelect').val() != ""){
            $scope.startTime = $("#classDateSelect").val().substr(0, 10);
            $scope.endTime   = $("#classDateSelect").val().substr(-10, 10);
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    });

    // 私教列表日期插件设置默认值
    $scope.courserDateInit = function () {
        if( $('#couserDateSelect').val() != ""){
            $scope.startCouserTime = $("#couserDateSelect").val().substr(0, 10);
            $scope.endCouserTime   = $("#couserDateSelect").val().substr(-10, 10);
            $scope.corserOrderStartDate = $scope.startCouserTime + ' ' + "00:00:00"; //开始日期
            $scope.couserOrderEndDate   = $scope.endCouserTime   + ' ' + "23:59:59"; //结束日期
        }
        else {
            $('#couserDateSelect').val($('#classDateSelect').val());
            $scope.corserOrderStartDate = $scope.orderStartDate;
            $scope.couserOrderEndDate   = $scope.orderEndDate;
        }

    };

    // 私教列表日期插件点击确定时
    $('#couserDateSelect').on('apply.daterangepicker', function(ev, picker){
        if($('#couserDateSelect').val() != ""){
            $scope.couserStartTime      = $("#couserDateSelect").val().substr(0, 10);
            $scope.couserEndTime        = $("#couserDateSelect").val().substr(-10, 10);
            $scope.corserOrderStartDate = $scope.couserStartTime + ' ' + "00:00:00"; //开始日期
            $scope.couserOrderEndDate   = $scope.couserEndTime   + ' ' + "23:59:59"; //结束日期
        }
    });


    /*** 日期插件结束 ***/

    /*** 设置初始化url ***/
    // 筛选条件搜索
    $scope.searchList = function(){
        // 整理搜索数据
        return{
            keywords  : $scope.searchAll       != undefined  ? $scope.searchAll       : null,     //搜索框
            venueId   : $scope.venueCheck      != undefined  ? $scope.venueCheck      : null,     //场馆ID
            startTime : $scope.orderStartDate  != undefined  ? $scope.orderStartDate  : null,     //开始日期
            endTime   : $scope.orderEndDate    != undefined  ? $scope.orderEndDate    : null,     //结束日期
            type      : $scope.classTypeSelect != undefined  ? $scope.classTypeSelect : null,     //课程类型
            lowest    : $scope.startMoney      != undefined  ? $scope.startMoney      : null,     //课程最小节数
            highest   : $scope.endMoney        != undefined  ? $scope.endMoney        : null,     //课程最大节数
            sortType  : $scope.sortType        != undefined  ? $scope.sortType        : null,
            sortName  : $scope.sort            != undefined  ? $scope.sort            : null
        }
    };

    $scope.searchCouserList = function(){
        // 整理搜索数据
        return{
            startTime : $scope.corserOrderStartDate != undefined ? $scope.corserOrderStartDate : null,
            endTime   : $scope.orderEndDate         != undefined ? $scope.orderEndDate         : null,
            type      : $scope.couserTypeClass      != undefined ? $scope.couserTypeClass      : null,
            id        : $scope.couserId             != undefined ? $scope.couserId             : null
        }
    };

    // 设置初始化url
    $scope.initPath = function () {
        $scope.pageUrl = "/finance/get-token-class?" + $.param($scope.searchList());
    };
    $scope.initCouserPath = function () {
        $scope.pageCouserUrl = "/finance/get-coach-class?" + $.param($scope.searchCouserList());
    };
    /*** 设置初始化url结束 ***/
    /*** 头部筛选条件 ***/
    // 获取场馆信息
    $scope.getVenueINfo = function (){
        $http.get("/site/get-auth-venue").success(function (data){
            $scope.venueList = data;
        });
    };
    $scope.getVenueINfo();
    // 清空
    $scope.initSearch = function (){
        $scope.venueCheck      = "";
        $scope.orderStartDate  = "";
        $scope.orderEndDate    = "";
        $scope.startMoney      = "";
        $scope.endMoney        = "";
        $scope.classTypeSelect = "";
        $("#classDateSelect").val("");
        $scope.searchOpClick();
    };

    $scope.searchOpClick = function (){
        $scope.initPath();
        $scope.searchOp();
    };

    $scope.searchOp = function (){
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.classList         = data.data;
                $scope.pages             = data.pages;
                $scope.nowPage           = data.now;
                $scope.classNoDataShow   = true;
                $scope.allClass          = data.total_num;
                $scope.classMoney        = data.total_money;
                $scope.classSurplusMoney = data.total_left_money;
            }
            else{
                $scope.classList         = data.data;
                $scope.pages             = data.pages;
                $scope.nowPage           = data.now;
                $scope.classNoDataShow   = false;
                $scope.allClass          = data.total_num;
                $scope.classMoney        = data.total_money;
                $scope.classSurplusMoney = data.total_left_money;
            }
            $.loading.hide();
        });
    };

    /*** 头部筛选条件结束 ***/

    /*** 总列表渲染 ***/

    // 分页
    $scope.replacementPages = function (urlPages){
        $scope.initPath();
        $scope.pageUrl = urlPages;
        $scope.searchOp();
    };

    // 回车搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchOpClick();
        }
    };

    // 排序
    $scope.changeSort = function (attr,sort){
        $scope.sortType   = attr;
        $scope.swicthSort(sort);
        $scope.initPath();
        $scope.getAllList();
    };

    $scope.swicthSort = function (sort){
        if(!sort){
            sort = 'DES'
        }
        else if(sort == 'DES'){
            sort = 'ASC';
        }
        else {
            sort = 'DES'
        }
        $scope.sort = sort;
    };

    /*** 总列表渲染结束 ***/

    /*** 私教筛选条件 ***/

    // 清空
    $scope.initCouserSearch = function (){
        $scope.corserOrderStartDate = "";
        $scope.couserOrderEndDate   = "";
        $scope.couserTypeClass      = "";
        $scope.couserTypeSelect     = "";
        $("#couserDateSelect").val("");
        $scope.searchCouerOp();
    };

    $scope.searchCouerOp = function (){
        $scope.initCouserPath();
        $scope.getCouserList();
    };

    /*** 私教筛选条件结束 ***/

    /*** 私教列表渲染 ***/

    $scope.showCouserList = function (id){
        $scope.couserId = id;
        $scope.courserDateInit();
        $scope.initCouserPath();
        $scope.getCouserInfo(id);
    };

    $scope.getCouserList = function (){
        $.loading.show();
        $http.get($scope.pageCouserUrl).success(function (data){
            if( data.data == "" || data.data == undefined){
                $scope.classCouserList   = data.data;
                $scope.attendClassPages  = data.pages;
                $scope.nowPage2          = data.now;
                $scope.couserNoDataShow  = true;
                $scope.allCouserClass    = data.total_num;
                $scope.classCouserMoney  = data.total_money;
            }
            else{
                $scope.classCouserList   = data.data;
                $scope.attendClassPages  = data.pages;
                $scope.nowPage2          = data.now;
                $scope.couserNoDataShow  = false;
                $scope.allCouserClass    = data.total_num;
                $scope.classCouserMoney  = data.total_money;
            }
            $.loading.hide();
        });
    };

    // 分页
    $scope.coachClass = function (urlPages){
        $scope.pageCouserUrl = urlPages;
        $scope.getCouserList();
    };

    // 获取教练信息
    $scope.getCouserInfo = function (id){
        $http.get("/personnel/employee-details?EmployeeId=" + id).success(function (data){
            $scope.couserInfoList = data;
        });
        $scope.getCouserList();
    };

    // 模态框关闭时
    $("#classDetailsModal").on("hide.bs.modal",function () {
        //$scope.initCouserSearch();
        $('#couserDateSelect').val('');
        $scope.couserTypeClass = '';
        $scope.courserDateInit();
    });
    /*** 私教列表渲染结束 ***/

    /*** 打印统计状态 ***/
    // 打印
    $scope.printExcel = function (){
        location.href = '/finance/get-class-excel';
    };
    /*** 打印统计状态结束 ***/
});