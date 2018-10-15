/**
 * Created by suyu on 2017/8/28.
 * 其他收入的控制器
 */
/**
 财务管理模块-其他统计页面
 内容：场馆选择、选择日期、业务行为、缴费方式、订单编号、购买人、业务行为、价格、缴费方式、售卖人、缴费日期
 * @author zhujunzhe@itsports.club
 * @create 2017.12.2
 */
var appModule = angular.module('App');
appModule.controller('otherCtrl',function ($scope,$http){
    /*** 日期插件 ***/
    // 插件启动
    $(function(){
        $('#otherDateSelect').daterangepicker(null, function(start, end, label) {
        });
    });

    // 日期插件设置默认值
    $scope.dateInit = function () {
        if($('#otherDateSelect').val() == ""){
            var dd = new Date();
            var y = dd.getFullYear();
            var m = dd.getMonth()+1;//获取当前月份的日期
            var d = dd.getDate();
            $scope.date = y+"-"+m+"-"+d;
            $("#otherDateSelect").val($scope.date);
            $scope.orderStartDate = $scope.date + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.date + ' ' + "23:59:59"; //结束日期
        }
        else{
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    };


    // 日期插件点击确定时
    $('#otherDateSelect').on('apply.daterangepicker', function(ev, picker){
        if($('#otherDateSelect').val() != ""){
            $scope.startTime = $("#otherDateSelect").val().substr(0, 10);
            $scope.endTime   = $("#otherDateSelect").val().substr(-10, 10);
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
        $scope.sellBehavior   = "";
        $scope.sellType       = "";
        $("#otherDateSelect").val("");
        $scope.searchOp();
    };

    // 筛选条件搜索
    $scope.searchList = function(){
        // 整理搜索数据
        return{
            keywords       : $scope.searchAll      != undefined  ? $scope.searchAll      : null,
            venueId        : $scope.venueSelect    != undefined  ? $scope.venueSelect    : null,
            startTime      : $scope.orderStartDate != undefined  ? $scope.orderStartDate : null,
            endTime        : $scope.orderEndDate   != undefined  ? $scope.orderEndDate   : null,
            note           : $scope.sellBehavior   != undefined  ? $scope.sellBehavior   : null,
            pay_money_mode : $scope.sellType       != undefined  ? $scope.sellType       : null,
            sortType       : $scope.sortType       != undefined  ? $scope.sortType       : null,
            sortName       : $scope.sort           != undefined  ? $scope.sort           : null
        }
    };

    // 设置初始化url
    $scope.initPath = function () {
        $scope.pageUrl = "/finance/get-other-income?" + $.param($scope.searchList());
    };

    $scope.dateInit();
    $scope.initPath();

    // 点击搜索按钮
    $scope.searchOp = function (){
        $scope.initPath();
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.otherList       = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.otherNoDataShow = true;
                $scope.allOther        = data.total_money;
            }
            else{
                $scope.otherList       = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.otherNoDataShow = false;
                $scope.allOther        = data.total_money;
            }
            $.loading.hide();
        });
    };

    /*** 头部筛选条件结束 ***/

    /*** 获取列表信息 ***/

    $scope.getAllList = function (){
        $.loading.show();
        $http.get($scope.pageUrl).success(function(data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.otherList       = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.otherNoDataShow = true;
                $scope.allOther        = data.total_money;
            }
            else{
                $scope.otherList       = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.otherNoDataShow = false;
                $scope.allOther        = data.total_money;
            }
            $.loading.hide();
        });
    };
    $scope.getAllList();

    // 分页
    $scope.replacementPages = function (urlPages){
        $scope.pageUrl = urlPages;
        $scope.getAllList();
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

    /*** 获取列表信息结束 ***/

    /*** 打印统计状态 ***/
    // 打印
    $scope.printExcel = function (){
        location.href = '/finance/get-other-excel';
    };
    /*** 打印统计状态结束 ***/
});
