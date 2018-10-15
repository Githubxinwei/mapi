angular.module('App').controller('orderCtrl', function($scope,$http){
    
    $(function(){
        $('#sellDate').daterangepicker(null, function(start, end, label) {});
        $("#orderTime").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true //今日按钮
        })
    });
    // 清空搜索
    $scope.searchClear = function () {
        $scope.clearData();
        $scope.init();
    };
    $scope.clearData = function () {
        $scope.keywords = null;
        $scope.orderStatus = null;
        $scope.paymentType = null;
        $scope.sellVenueId = null;
        $scope.floorPrice  = null;
        $scope.ceilingPrice= null;
        $scope.orderStartDate   = '';
        $scope.orderEndDate     = '';
        $scope.businessBehavior = '';
        $('#sellDate').val("");
    }
    //初始化方法
    $scope.init  = function () {
        $scope.getAllSellVenue();
        $scope.initPaths();                 //获取路径
        $scope.getOrderAllInfo();              //查询所有订单
    };
    //连接后台查询订单信息
    $scope.getOrderAllInfo = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function (result) {
            if(result.data.data != undefined && result.data.data != ""){
                $scope.itemsLength = result.data.data.length;
                $scope.items = result.data.data;                    //数据
                $scope.pages = result.data.pages;                   //分页
                $scope.dataInfo = false;                           //没有数据时的样式
                $scope.itemsAllMoney = result.data.sum;
            }else{
                $scope.itemsLength = result.data.data.length;
                $scope.items = result.data.data;
                $scope.pages = result.data.pages;
                $scope.dataInfo = true;
                $scope.itemsAllMoney = result.data.sum;
            }
            $.loading.hide();
        });
    };
    /**删除订单表数据***/
    $scope.deletingOrder = function(orderId) {
        Sweety.remove({
            url              : "/order/deleting-order?orderId="+orderId,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.getOrderAllInfo();
        });
    };
    //获取售卖场馆
    $scope.getAllSellVenue = function(){
        $http.get('/site/get-auth-venue').then(function (response) {
            if (response.data != undefined && response.data != "") {
                $scope.allVenueSellLists = response.data;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.VenueStautsLength = true;
                $scope.optionVenue = '暂无数据';
            }
        });
    };

    //初始化获取当月的第一天和最后一天
    $scope.getMonthOneAndMonthLast = function(){
        var date = new Date();
        $scope.orderStartDate =$scope.getMyDate(date.setDate(1));
        var currentMonth=date.getMonth();
        var nextMonth=++currentMonth;
        var nextMonthFirstDay=new Date(date.getFullYear(),nextMonth,1);
        var oneDay=1000*60*60*24;
        $scope.orderEndDate = $scope.getMyDate(nextMonthFirstDay-oneDay);
        $('#sellDate').val($scope.orderStartDate+' - '+ $scope.orderEndDate);
    };
    //时间戳转换为年月日
    $scope.getMyDate = function(str){
        str = parseInt(str);
        if(str!=""||str!=null){
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth()+1;
            oMonth = oMonth>=10? oMonth:'0'+oMonth;
            var oDay = oDate.getDate();
            oDay = oDay>=10? oDay:'0'+oDay;
            var theDate = oYear+"-"+oMonth+"-"+oDay;
        }else{
            theDate = "";
        }
        return theDate
    };
    $scope.getMonthOneAndMonthLast();

    //定义公共路径方法
    $scope.initPaths = function () {
        $scope.searchParams =  $scope.searchOrderData();                               //接收数据
        $scope.pageInitUrl = '/order/get-order-data?'+ $.param($scope.searchParams);   //后台路径
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            // $scope.pageInitUrl = '/user/member-info?page='+value;
            $scope.pageInitUrl = '/order/get-order-data?'+ $.param($scope.searchParams) + '&page=' + value;
            $scope.getOrderAllInfo();
        }
    };

    //订单信息分页
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getOrderAllInfo();          //再次调用后台查询
    };
    //点击搜索触发
    $scope.searchAbout = function () {
        $scope.orderStartDate = '';
        $scope.orderEndDate = '';
        if($("#sellDate").val() != ''){
            var startTime = $("#sellDate").val().substr(0, 10);
            $scope.orderStartDate = startTime+' '+ "00:00:00";
            var endTime = $("#sellDate").val().substr(-10, 10);
            $scope.orderEndDate  = endTime+' '+"23:59:59";
        }

        $scope.initPaths();
        $scope.getOrderAllInfo();       //向后台发送数据
    };
    //回车搜索触发
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.searchAbout();
        }

    };
    //列表排序
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);
        $scope.searchAbout();
    };
    //处理正序、倒序
    $scope.switchSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;             //排序状态
    };
    //接收搜索条件
    $scope.searchOrderData = function () {
        return {
            venueId             : $scope.sellVenueId != undefined && $scope.sellVenueId != '' ? $scope.sellVenueId : null,//售卖场馆
            lowest              : $scope.floorPrice != undefined && $scope.floorPrice != '' ? $scope.floorPrice : null,//最低价
            highest             : $scope.ceilingPrice != undefined && $scope.ceilingPrice != '' ? $scope.ceilingPrice : null,//最高价
            startTime           : $scope.orderStartDate != undefined && $scope.orderStartDate != '' ? $scope.orderStartDate : null,//开始时间
            endTime             : $scope.orderEndDate != undefined&& $scope.orderEndDate != '' ? $scope.orderEndDate : null,//结束时间
            payWay              : $scope.paymentType != undefined&& $scope.paymentType != '' ? $scope.paymentType : null,//支付方式
            status              : $scope.orderStatus != undefined&& $scope.orderStatus != '' ? $scope.orderStatus : null,//支付状态
            keywords            : $scope.keywords != undefined ? $scope.keywords : null,      //搜索框数据
            sortType            : $scope.sortType != undefined ? $scope.sortType : null,      //排序字段
            sortName            : $scope.sort     != undefined ? $scope.sort : null,           //排序状态
            businessBehavior    : $scope.businessBehavior != undefined && $scope.businessBehavior != '' ? $scope.businessBehavior : null,   //业务行为
        }
    };
    //调用初始化方法
    $scope.init();
    //查询单条订单的信息
    $scope.getOrderInfo = function (id,name) {
        $scope.payMoneyMode = '';
        $scope.orderId = id;
        $http.get('/order/get-order-info?id='+id).then(function (result) {
            if(result.data != 'null' && result.data != ""){
                $scope.orderInfo = result.data;
                $scope.manyPayMode = angular.fromJson(result.data.many_pay_mode);
            }
        });
    };
    //取消订单 id是订单的id
    $scope.cancelOrder = function (id) {
        $http.get('/order/set-order-status?id='+id).then(function (result) {
            if(result.data.status == "success"){
                $scope.init();
            }
        });
    };
    //确定付款订单
    $scope.orderPayment = function (id) {
        $scope.params = $scope.getOrderPaymentData();
        $scope.params['orderId'] = id;
        // $scope.getDataRule($scope.params,'请选择支付方式');               //验证规则
        if($scope.getDataRule($scope.params,'请选择支付方式')){
            $http({
                url        : '/order/order-payment',
                method     : 'POST',
                data       :  $.param($scope.params),                               //
                headers    : { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result) {
                if(result.data.data == true){
                    $scope.init();
                    // location.href = '/order/index';
                    $('.modal').hide();              //关闭模态框
                    $('.modal-backdrop').hide();     //关闭模态框遮罩层
                }else{
                    Message.warning('确认订单失败！');
                }
            });
        }
    };
    //定义验证规则
    $scope.getDataRule = function (attr,text) {
        if(!attr['payMoneyMode']){
            Message.warning(text);
            return false;
        }
        // else if(!attr['payPeopleName']){
        //     Message.warning('请填写付款人');
        //     return false;
        // }
        // else if(!attr['payeeName']){
        //     Message.warning('请填写收款人');
        //     return false;
        // }
        return true;
    };
    //接收确认订单数据
    $scope.getOrderPaymentData = function () {
        return {
            payMoneyMode  : $scope.payMoneyMode != undefined ? $scope.payMoneyMode : null,        //付款方式
            payPeopleName : $scope.payPeopleName != undefined ? $scope.payPeopleName: null,       //付款人姓名
            payeeName     : $scope.payeeName != undefined ? $scope.payeeName : null,              //收款人姓名
            note          : $scope.note != undefined ? $scope.note : null,                        //订单备注
            _csrf_backend      : $('#_csrf').val()                                                 //csrf
        }
    }
    //申请退款
    $scope.applyForRefund = function () {
        $("#applyForRefund").show();
    }
    //申请退款完成
    $scope.submitARefund = function (refund) {
        var data = {
            orderId:$scope.orderId,
            refundNote:refund
        }
        $http({method:'post',url:'/order/apply-refund', data :  $.param(data),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if(success.data.status == "success" ){
                Message.success("提交申请成功");
                $scope.refund  = '';
                $("#my").hide();
                $("#applyForRefund").hide();
            }
            if(success.data.status == "error" ){
                Message.success(success.data.data);
                $scope.refund  = '';
                $("#my").hide();
                $("#applyForRefund").hide();
            }
            $scope.getOrderAllInfo();
            $(".modal-backdrop").hide();
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    //申请退款详情
    $scope.applyForRefundModal = function (id) {
        $http({method:'get',url:'/order/get-order-info?id='+id}).then(function (data) {
            $scope.applyForRefundModalData = data.data
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
    }
    //已拒绝
    $scope.rejected = function (id,status) {
        $http({method:'get',url:'/order/get-order-info?id='+id}).then(function (data) {
            $scope.rejectedData = data.data;
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
    }
    //已退款
    $scope.refunded = function (id,status) {
        $scope.refundedStatus = status;
        $http({method:'get',url:'/order/get-order-info?id='+id}).then(function (data) {
            $scope.refundedData = data.data;
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
    };
    // 订单修改界面
    $scope.updateOrderInfo = function(id,memberName,venueId,note,price,orderTime,employeeId,consumptionType){
         //1：场馆赋值
         $scope.getAllVenue();
         //2: 获取默认场馆
         $scope.venueId       = venueId;
         //3: 定义业务行为
         $scope.businessNote = note;
        //4:订单价格
         $scope.orderPrice   = price;
        //5:订单下单时间
         $scope.orderTime    = orderTime;
        // 时间的相互转换
        $scope.getChangeDate(orderTime);
        // 6:获取指定场馆下销售部的 所有员工
        //获取销售的类型
        $scope.consumptionType = consumptionType;
        //判断销售类型是办卡还是私教  != -1就是办卡的销售 =-1是销售的私教
        if($scope.consumptionType.search("card") != -1 ){
            $scope.getAllEmployee();
        }
        if($scope.consumptionType.search("charge") != -1){
            $scope.getPrivateCoach();
        }else{
            $scope.getAllEmployee();
        }
        //7: 获取订单id
        $scope.orderId    = id;
        //8： 售卖人员id
        if(employeeId==null){
            employeeId="";
        }
        $scope.employeeId = employeeId;

    };
    // 将时间戳 转化为时间
    $scope.getChangeDate = function (orderTime) {
        var date = new Date(orderTime*1000);
        var Y = date.getFullYear() + '-';
        var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
        $scope.endDate = Y+M+D;
        // input框赋值
        $("#dateSpan").val(Y+M+D);
    };
    // 获取指定场馆所有员工
    $scope.getAllEmployee = function(){
        $http({method:'get',url:'/user/get-adviser'}).then(function (data) {
            $scope.employee = data.data;
            //console.log("employee",data);
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });

    };
    //获取指定场馆的销售私教
    $scope.getPrivateCoach = function(){
         $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
         $scope.employee = data.data
         }, function (error) {
         // console.log(error)
         Message.error("系统错误请联系工作人员")
         });
    };
    //获取指定公司所有场馆
    $scope.getAllVenue = function(){
        $http({method:'get',url:'/order/gain-all-venue'}).then(function (data) {
            $scope.allVenueS = data.data.data;
            //console.log("jjjj",$scope.allVenueS);
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
    };
    // 修改订单信息接口
    $scope.update = function (id) {
        $scope.params = $scope.getUpdateParam();
        var  endResult=  $scope.judgeParam();
        if(endResult!==true){
            Message.warning(endResult);
            return false;
        }
        console.log($scope.params);
            $http({
                url        : '/order/update-order',
                method     : 'POST',
                data       :  $.param($scope.params),                               //
                headers    : { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result) {
                console.log("ssssss",result.data.status);
                if(result.data.status == "success"){
                    $scope.getOrderAllInfo();
                    $('.modal').hide();              //关闭模态框
                    $('.modal-backdrop').hide();     //关闭模态框遮罩层
                    Message.success("修改成功");
                }else{
                    Message.warning('订单修改失败');
                }
            });
        };
    // 对传送参数的判断
    $scope.judgeParam = function () {
        console.log($scope.params);
        if($scope.params.venueId==null){
            return "售卖场馆不能为空";
        }
        if($scope.params.businessNote==null){
            return "业务行为不能为空";
        }
        if($scope.params.orderPrice==null){
            return "订单价格不能为空";
        }
        if($scope.params.orderTime==null){
            return "订单创建时间不能为空";
        }
        return true;
    };
    // 修改订单信时获取发送参数
    $scope.getUpdateParam = function () {
       return {
            orderId        :$scope.orderId != undefined && $scope.orderId != '' ? $scope.orderId : null,//订单id
            venueId        :$scope.venueId != undefined && $scope.venueId != '' ? $scope.venueId : null,//售卖场馆
            businessNote   :$scope.businessNote != undefined && $scope.businessNote != '' ? $scope.businessNote : null,//业务行为
            orderPrice     :$scope.orderPrice != undefined && $scope.orderPrice != '' ? $scope.orderPrice : null,//订单价格
            orderTime      :$("#dateSpan").val() != undefined && $("#dateSpan").val() != '' ? $("#dateSpan").val() : null,//开始时间
            employeeId     :$scope.employeeId != undefined && $scope.employeeId != '' ? $scope.employeeId : null//开始时间
        }
    }



});