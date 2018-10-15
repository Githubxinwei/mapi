/**
 * Created by suyu on 2017/8/25.
 * 卖课统计的控制器
 */
/**
 财务管理模块-卖课统计页面
 内容：场馆、会员姓名、卡号、销售渠道、课程名称、课程类型、节数、课时费、私教、缴费日期、总金额
 * @author zhujunzhe@itsports.club
 * @create 2017.11.30
 */

var appModule = angular.module('App');
appModule.controller('sellCtrl',function ($scope,$http){
    /*** 日期插件 ***/
    // 插件启动
    $(function(){
        $('#sellDateSelect').daterangepicker(null, function(start, end, label) {
        });
        $scope.getSaleChannel();
    });

    // 获取销售渠道
    $scope.getSaleChannel = function (){
        $http.get("/finance/get-source").success(function (data){
           $scope.saleChannelList = data;
        })
    };
    //改变场馆获取销售途径
    $scope.changeVenue = function () {
        $http.get("/finance/get-source?venueId="+$scope.venueCheck).success(function (data){
            $scope.saleChannelList = data;
        })
    }

    // 日期插件设置默认值
    $scope.dateInit = function () {
        if($('#sellDateSelect').val() == ""){
            var dd = new Date();
            var y = dd.getFullYear();
            var m = dd.getMonth()+1;//获取当前月份的日期
            var d = dd.getDate();
            $scope.date = y+"-"+m+"-"+d;
            $("#sellDateSelect").val($scope.date);
            $scope.orderStartDate = $scope.date + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.date + ' ' + "23:59:59"; //结束日期
        }
        else{
            $scope.orderStartDate = $scope.startTime + ' ' + "00:00:00"; //开始日期
            $scope.orderEndDate   = $scope.endTime   + ' ' + "23:59:59"; //结束日期
        }
    };


    // 日期插件点击确定时
    $('#sellDateSelect').on('apply.daterangepicker', function(ev, picker){
        if($('#sellDateSelect').val() != ""){
            $scope.startTime = $("#sellDateSelect").val().substr(0, 10);
            $scope.endTime   = $("#sellDateSelect").val().substr(-10, 10);
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
        $scope.venueCheck     = '';
        $scope.orderStartDate = '';
        $scope.orderEndDate   = '';
        $scope.startMoney     = '';
        $scope.endMoney       = '';
        $scope.channelCheck   = '';
        $scope.productType    = '';
        $scope.courseType     = '';
        $("#sellDateSelect").val('');
        $scope.searchOp();
    };

    // 筛选条件搜索
    $scope.searchList = function(){
        // 整理搜索数据
        return{
            keywords        : $scope.searchAll      != undefined  ? $scope.searchAll      : null,
            venueId         : $scope.venueCheck     != undefined  ? $scope.venueCheck     : null,
            startTime       : $scope.orderStartDate != undefined  ? $scope.orderStartDate : null,
            endTime         : $scope.orderEndDate   != undefined  ? $scope.orderEndDate   : null,
            lowest          : $scope.startMoney     != undefined  ? $scope.startMoney     : null,
            highest         : $scope.endMoney       != undefined  ? $scope.endMoney       : null,
            sortType        : $scope.sortType       != undefined  ? $scope.sortType       : null,
            sortName        : $scope.sort           != undefined  ? $scope.sort           : null,
            businessRemarks : $scope.channelCheck   != undefined  ? $scope.channelCheck   : null,
            productType     : $scope.productType    != undefined  ? $scope.productType    : null,//产品类型
            courseType      : $scope.courseType     != undefined  ? $scope.courseType     : null//课程类型
        }
    };

    // 设置初始化url
    $scope.initPath = function () {
        $scope.pageUrl = "/finance/get-sell-class?" + $.param($scope.searchList());
    };

    $scope.dateInit();
    $scope.initPath();

    // 点击搜索按钮
    $scope.searchOp = function (){
        $scope.initPath();
        $.loading.show();
        $http.get($scope.pageUrl).success(function (data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.sellClassList   = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.sellNoDataShow  = true;
                $scope.allClass        = data.total_class;
                $scope.allMoney        = data.total_money;
                $scope.overageNum      = data.overageNum;
            }
            else{
                $scope.sellClassList   = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.sellNoDataShow  = false;
                $scope.allClass        = data.total_class;
                $scope.allMoney        = data.total_money;
                $scope.overageNum      = data.overageNum;
            }
            $.loading.hide();
        });
    };

    /*** 头部筛选条件结束 ***/

    /*** 获取列表信息 ***/
    //数据展示
    $scope.getAllList = function (){
        $.loading.show();
        $http.get($scope.pageUrl).success(function(data){
            if(data.data.length == false || data.data == "" || data.data == undefined){
                $scope.sellClassList   = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.sellNoDataShow  = true;
                $scope.allClass        = data.total_class;
                $scope.allMoney        = data.total_money;
                $scope.overageNum      = data.overageNum;
            }
            else{
                $scope.sellClassList   = data.data;
                $scope.pages           = data.pages;
                $scope.nowPage         = data.now;
                $scope.sellNoDataShow  = false;
                $scope.allClass        = data.total_class;
                $scope.allMoney        = data.total_money;
                $scope.overageNum      = data.overageNum;
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

    // 点击表格
    $scope.sellTableClick = function(id) {
        $scope.memberCourseOrderId = id;
        $http.get("/finance/class-order-detail?memberCourseOrderId=" + $scope.memberCourseOrderId).success(function (data){
            if(data.data == null || data.data == undefined) {
                Message.warning('获取失败，请刷新重试！')
            }else{
                $scope.memberCourseDetails = data.data;
                $scope.manyPayMode = angular.fromJson(data.data.many_pay_mode);
                $("#getSellClassDetailModal").modal("show"); //模态框显示
            }
        });
    }
    /*** 获取列表信息结束 ***/

    /*** 打印统计状态 ***/
    // 打印
    $scope.printExcel = function (){
        location.href = '/finance/get-sell-excel';
    };
    /*** 打印统计状态结束 ***/

}).filter('removeWords',function (){
    return function (value){
        if(!value) return '无';
        if(value.indexOf("销售渠道是：") == -1){
            return value;
        }
        else {
           var newVal = value.substr(6);
            return newVal;
        }
    };
});
