/**
 * Created by suyu on 2017/8/24.
 * 分摊收入的控制器
 */
/**
 财务管理模块-分摊统计页面
 内容：场馆、会员姓名、卡号、卡种、总金额、会籍顾问、缴费日期、分摊金额
 * @author zhujunzhe@itsports.club
 * @create 2017.11.30
 */
var appModule = angular.module('App');
appModule.controller('shareCtrl',function ($scope,$http){

    /*** 日期插件 ***/
    // 插件启动
    $(function(){
        $('#shareDateSelect').daterangepicker(null, function(start, end, label) {
        });
    });
    
    // 日期插件设置默认值
    $scope.dateInit = function () {
        if($('#shareDateSelect').val() == ""){
            var dd = new Date();
            var y = dd.getFullYear();
            var m = dd.getMonth()+1;//获取当前月份的日期
            var d = dd.getDate();
            $scope.date = y+"-"+m+"-"+d;
            $("#shareDateSelect").val($scope.date);
            $scope.orderStartDate = $scope.date + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.date + ' ' + "23:59:59"; //结束日期
        }
        else{
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    };


    // 日期插件点击确定时
    $('#shareDateSelect').on('apply.daterangepicker', function(ev, picker){
        if($('#shareDateSelect').val() != ""){
            $scope.startTime = $("#shareDateSelect").val().substr(0, 10);
            $scope.endTime   = $("#shareDateSelect").val().substr(-10, 10);
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    });
    /*** 日期插件结束 ***/

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
        $scope.venueSelect    = "";
        $scope.orderStartDate = "";
        $scope.orderEndDate   = "";
        $("#shareDateSelect").val("");
        $scope.searchOp();
    };

    // 筛选条件搜索
    $scope.searchList = function(){
        // 整理搜索数据
        return{
            keywords  : $scope.searchAll      != undefined  ? $scope.searchAll      : null,
            venueId   : $scope.venueSelect    != undefined  ? $scope.venueSelect  : null,
            startTime : $scope.orderStartDate != undefined  ? $scope.orderStartDate : null,
            endTime   : $scope.orderEndDate   != undefined  ? $scope.orderEndDate   : null,
            sortType  : $scope.sortType       != undefined  ? $scope.sortType       : null,
            sortName  : $scope.sort           != undefined  ? $scope.sort           : null
        }
    };

    // 设置初始化url
    $scope.initPath = function () {
        $scope.pageUrl = "/finance/get-share-money?" + $.param($scope.searchList());
    };

    $scope.dateInit();
    $scope.initPath();

    // 点击搜索按钮
    $scope.searchOp = function (){
        $scope.initPath();
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.shareIncomeList = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.shareNoDataShow = true;
                $scope.allShare        = data.total;
            }
            else{
                $scope.shareIncomeList = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.shareNoDataShow = false;
                $scope.allShare        = data.total;
            }
            $.loading.hide();
        });
    };

    /*** 头部筛选条件结束 ***/

    /*** 渲染列表 ***/
    $scope.getList = function (){
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.shareIncomeList = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.shareNoDataShow = true;
                $scope.allShare        = data.total;
            }
            else{
                $scope.shareIncomeList = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.shareNoDataShow = false;
                $scope.allShare        = data.total;
            }
            $.loading.hide();
        });
    };
    $scope.getList();

    // 分页
    $scope.replacementPages = function (urlPages){
        $scope.initPath();
        $scope.pageUrl = urlPages;
        $scope.getList();
    };

    // 回车搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchOp();
        }
    };

    // 排序
    $scope.changeSort = function (attr,sort){
        $scope.sortType   = attr;
        $scope.swicthSort(sort);
        $scope.initPath();
        $scope.getList();
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

    /*** 渲染列表结束 ***/

    /*** 打印统计状态 ***/
    // 打印
    $scope.printExcel = function (){
        location.href = '/finance/get-share-excel';
    };
    /*** 打印统计状态结束 ***/
});