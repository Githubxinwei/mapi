/**
 * Created by 杨大侠 on 2017/8/26.
 */

angular.module('App').controller('refundApprovalCtrl', function($scope,$http) {
    //搜索擦承诺书
    $scope.searchParams =function() {
            return{
                pending:2,
                keywords: $scope.keywords != undefined ? $scope.keywords : '',
                sortType: $scope.sortType != undefined ? $scope.sortType : '',
                sortName: $scope.sort != undefined ? $scope.sort : '',
            }
    }
    $scope.pageInitUrl = '/order/get-order-data?';
    //获取列表
    $scope.listData = function () {
        var searchParams = $scope.searchParams();
        $.loading.show();
        $http({method:"get",url:$scope.pageInitUrl+$.param(searchParams)}).then(function (data) {
            if(data.data.data != undefined && data.data.data != ""){
                $scope.listDateItem = data.data.data
                $scope.pages = data.data.pages;                   //分页
                $scope.dataInfo = false;                           //没有数据时的样式
            }else{
                $scope.listDateItem = data.data.data
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide();
        },function (error) {console.log(error);Message.error("系统错误请联系工作人员"); $.loading.hide();})
    }
    $scope.listData();
    
    $scope.reasonsForRefund = function (i) {
        
        $(".noneblock").eq(i).removeClass("none");
        $(".noneblock").eq(i).addClass("block");
    }
    $scope.reasonsForReFundLeave = function (i) {
        $(".noneblock").eq(i).removeClass("block");
        $(".noneblock").eq(i).addClass("none");
    }
    //搜索
    $scope.searchAbout = function () {
        $scope.listData();
    }
    /**
    * author:张亚鑫
    * date:2017-11-24
    * 函数描述：点击清空按钮，清空左侧搜索框内的值，并重新调用获取列表信息的函数。
    *
     * */
    $scope.cleanSearchButton = function () {
        $scope.keywords = '';//清空左侧搜索框值
        $scope.listData();//重新调用获取列表信息的函数
    }
    //回车搜索触发
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.listData();
        }
    };

    //列表排序
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);
        $scope.listData();
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
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.listData();
    };
    // 同意申请成功
    $scope.agree = function (id) {
        $http({method:"get",url:"/order/agree-apply?orderId="+id}).then(function (data) {
            if(data.data.status == 'success'){
                Message.success("同意退款");
            }
            if(data.data.status == 'error'){
                Message.warning("退款失败")
            }
            $scope.listData();
        },function (error) {console.log(error);Message.error("系统错误请联系工作人员");})
    }
    //不同意申请
    $scope.refunded = function (id) {
        $scope.refundedOrderId = id;
    }
    //提交不同意申请
    $scope.refundedOk = function (refund) {
        var data = {
            orderId:$scope.refundedOrderId,
            refuseNote:refund
        }
        $http({method:'post',url:'/order/refuse-apply',data :  $.param(data),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status == 'success'){
                Message.success("回复申请成功");
                $("#applyForRefund").hide();
                $(".modal-backdrop").hide();
                $scope.refund = ''
                $scope.listData();
                return;
            }
            if(data.data.status ==  'error'){
                Message.success(data.data.status);
                $("#applyForRefund").hide();
                $(".modal-backdrop").hide();
                $scope.refund = '';
                $scope.listData();
                return;
            }
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员")
        })
    }
    //已同意详情 和 已拒绝详情
    // agreeRefuse
    $scope.agreeRefuse = function (id,start) {
        $scope.refundedsStart = start;
        $http({method:"get",url:"/order/get-order-info?id="+id}).then(function (data) {
            $scope.refundedsItem = data.data;
        },function (error) {console.log(error);Message.error("系统错误请联系工作人员");})
    }
})