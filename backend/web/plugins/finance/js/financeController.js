/**
 * Created by suyu on 2017/8/22.
 * 卡种收入的控制器
 */
/**
 财务管理模块-卡种统计页面
 内容：场馆、会员姓名、会员手机号、卡种、缴费行为、缴费方式、总金额、开票状态、会籍顾问、缴费日期
 * @author zhujunzhe@itsports.club
 * @create 2017.11.30
 */

var appModule = angular.module('App');
appModule.controller('cardCtrl',function ($scope,$http){

    /*** 日期插件 ***/
    // 插件启动
    $(function(){
        $('#cardDateSelect').daterangepicker(null, function(start, end, label) {
        });
    });
    // 日期插件设置默认值
    $scope.dateInit = function () {
        if($('#cardDateSelect').val() == ""){
            var dd = new Date();
            var y = dd.getFullYear();
            var m = dd.getMonth()+1;//获取当前月份的日期
            var d = dd.getDate();
            $scope.date = y+"-"+m+"-"+d;
            $("#cardDateSelect").val($scope.date);
            $scope.orderStartDate = $scope.date + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.date + ' ' + "23:59:59"; //结束日期
        }
        else{
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    };
    $scope.dateInit();

    
    // 日期插件点击确定时
    $('#cardDateSelect').on('apply.daterangepicker', function(ev, picker){
        if($('#cardDateSelect').val() != ""){
            $scope.startTime = $("#cardDateSelect").val().substr(0, 10);
            $scope.endTime   = $("#cardDateSelect").val().substr(-10, 10);
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    });
    /*** 日期插件结束 ***/

    /*** 头部筛选条件 ***/

    // 筛选条件搜索
    $scope.searchList = function(){
        // 整理搜索数据
        return{
            venueId   : $scope.venueCheck     != undefined  ? $scope.venueCheck     : null,     //场馆ID
            keywords  : $scope.searchAll      != undefined  ? $scope.searchAll      : null,     //搜索
            startTime : $scope.orderStartDate != undefined  ? $scope.orderStartDate : null,     //开始日期
            endTime   : $scope.orderEndDate   != undefined  ? $scope.orderEndDate   : null,     //结束日期
            note      : $scope.behavior       != undefined  ? $scope.behavior       : null,     //缴费行为
            payWay    : $scope.payMode        != undefined  ? $scope.payMode        : null,     //缴费方式
            isReceipt : $scope.payStatus      != undefined  ? $scope.payStatus      : null,     //开票状态
            lowest    : $scope.startMoney     != undefined  ? $scope.startMoney     : null,     //开始金额
            highest   : $scope.endMoney       != undefined  ? $scope.endMoney       : null,     //结束金额
            sortType  : $scope.sortType       != undefined  ? $scope.sortType       : null,
            sortName  : $scope.sort           != undefined  ? $scope.sort           : null
        }
    };
    $scope.initPath = function () {
        $scope.pageUrl = '/finance/get-card-order?' + $.param($scope.searchList());
    };
    $scope.initPath();
    // 获取场馆信息
    $scope.getVenueINfo = function (){
        $http.get("/site/get-auth-venue").success(function (data){
            $scope.venueList = data;
        });
    };
    $scope.getVenueINfo();

    // 搜索框搜索
    $scope.search = function ($event){
        $scope.initPath();
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.cardIncomeList  = data.data;
                $scope.groupNoDataShow = true;
                $scope.searchData      = false;
                $scope.pages           = data.pages;
                $scope.receiptMoney    = data.total_receipt_money;
                $scope.receipt         = data.total_single;
                $scope.totalMoney      = data.total_money;
                $scope.receiptNum      = data.receipt_num;
            }
            else{
                $scope.cardIncomeList  = data.data;
                $scope.groupNoDataShow = false;
                $scope.searchData      = false;
                $scope.pages           = data.pages;
                $scope.receiptMoney    = data.total_receipt_money;
                $scope.receipt         = data.total_single;
                $scope.totalMoney      = data.total_money;
                $scope.receiptNum      = data.receipt_num;
                $scope.nowPage         = data.now;
            }
            $.loading.hide();
        });
    };

    // 回车搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.search();
        }
    };

    // 点击搜索按钮
    $scope.searchOp = function (){
        $scope.initPath();
        $.loading.show();
       $http.get($scope.pageUrl).success(function (data){
           if(data.data.length == false || data.data == "" || data.data == undefined){
               $scope.cardIncomeList = data.data;
               $scope.groupNoDataShow = true;
               $scope.searchData      = false;
               $scope.pages           = data.pages;
               $scope.receiptMoney    = data.total_receipt_money;
               $scope.receipt         = data.total_single;
               $scope.totalMoney      = data.total_money;
               $scope.receiptNum      = data.receipt_num;
           }
           else{
               $scope.cardIncomeList = data.data;
               $scope.groupNoDataShow = false;
               $scope.searchData      = false;
               $scope.pages           = data.pages;
               $scope.receiptMoney    = data.total_receipt_money;
               $scope.receipt         = data.total_single;
               $scope.totalMoney      = data.total_money;
               $scope.receiptNum      = data.receipt_num;
               $scope.nowPage         = data.now;
           }
           $.loading.hide();
       });
    };

    // 清空
    $scope.initSearch = function (){
        $scope.venueCheck     = "";
        $scope.behavior       = "";
        $scope.payMode        = "";
        $scope.payStatus      = "";
        $scope.startMoney     = "";
        $scope.endMoney       = "";
        $scope.orderStartDate = "";
        $scope.orderEndDate   = "";
        $("#cardDateSelect").val("");
        $scope.searchOp();
    };
    /*** 头部筛选条件结束 ***/

    /*** 列表渲染 ***/
    // 获取列表
    $scope.getAllList = function (){
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.cardIncomeList = data.data;
                $scope.groupNoDataShow = true;
                $scope.searchData      = false;
                $scope.pages           = data.pages;
                $scope.receiptMoney    = data.total_receipt_money;
                $scope.receipt         = data.total_single;
                $scope.totalMoney      = data.total_money;
                $scope.receiptNum      = data.receipt_num;
            }
            else{
                $scope.cardIncomeList = data.data;
                $scope.groupNoDataShow = false;
                $scope.searchData      = false;
                $scope.pages           = data.pages;
                $scope.receiptMoney    = data.total_receipt_money;
                $scope.receipt         = data.total_single;
                $scope.totalMoney      = data.total_money;
                $scope.receiptNum      = data.receipt_num;
                $scope.nowPage         = data.now;
            }
            $.loading.hide();
        });
    };
    $scope.getAllList();

    // 分页
    $scope.replacementPages = function (urlPages){
        $scope.initPath();
        $scope.pageUrl = urlPages;
        $scope.getAllList();
    };

    // 开票
    $scope.cardReceipt = function (id){
        Sweety.remove({
            url: "/finance/receipt?id=" + id,
            http: $http,
            title: '确定要开票吗?',
            text: '是否确定开票',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        },function(data){
            if(data.data.status == 'success'){
                Message.success("开票成功");
                $scope.getAllList();
            }
            else{
                Message.warning("开票失败")
            }
        });
        $scope.getAllList();
    };

    /*** 列表渲染结束 ***/

    /*** 卡种收入详情 ***/
    $scope.lookDetails = function (id){
        $scope.listId = id;
        $http.get("/finance/get-order-detail?id=" + $scope.listId).success(function (data){
            $scope.cardDetailsList = data;
            $scope.manyMode = angular.fromJson(data.many_pay_mode);
            if(data.duration != null){
                $scope.duration = data.duration.replace(/[^0-9]/ig,"");
            }
            else{
                $scope.duration = '0';
            }
        });
        $("#detailsModal").modal("show"); //模态框显示
    };

    // 排序
    $scope.changeSort = function (attr,sort){
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.initPath();
        $scope.getAllList();
    };
    $scope.switchSort = function (sort){
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };

    /*** 卡种收入详情结束 ***/

    /*** 打印统计状态 ***/
    // 打印
    $scope.printExcel = function (){
        location.href = '/finance/get-card-excel';
    };
    /*** 打印统计状态结束 ***/

});