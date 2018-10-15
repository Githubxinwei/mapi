/**
 * Created by suyu on 2017/6/27.
 */
var app = angular.module('App').controller('corporateAllianceCtrl',function ($scope, $http,$location, $rootScope,$interval){
    // 获取今日时间戳
    $scope.todayTime = Date.parse(new Date())/1000;
    // 开始日期选择器的触发js
    $("#dateStartIndex").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose:true,
        todayBtn: true,//今日按钮
        startDate: new Date(),
        minDate: new Date()
    }).on('changeDate', function(ev){

    });
    // 结束日期选择器的触发js
    $("#dateEndIndex").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose:true,
        todayBtn: true,//今日按钮
        startDate: new Date(),
        minDate: new Date()
    }).on('changeDate', function(ev){

    });
    // 关闭申请数据初始化
    $scope.applyShopOkContactClose = function (){
        // 数据初始化
        $scope.beApply = '';
        $scope.startApply = '';
        $scope.endApply = '';
    };
    // 申请通店提交前的数据整理
    $scope.applyShopOkContactData = function (){
        return{
            beApply:     $scope.beApply    != undefined && $scope.beApply    != "" ? $scope.beApply :     null,
            startApply: $scope.startApply != undefined && $scope.startApply != "" ? $scope.startApply :   null,
            endApply:   $scope.endApply   != undefined && $scope.endApply    != "" ? $scope.endApply :    null,
            _csrf_backend: $('#_csrf').val()
        };
    };
    /**
     *author:苏雨
     *create:2017-6-28
     *函数描述:将申请通店的数据整理后发送到后台
     *param: beApply:公司名称
     *       startApply:开始时间
     *       endApply:结束时间
     * */
    // 确认申请的提交按钮事件
    $scope.applyShopOkContact = function (){
        if ($scope.beApply == null || $scope.beApply == "") {
            Message.warning("请输入公司名");
            return false;
        }
        if ($scope.startApply == null || $scope.startApply == "") {
            Message.warning("请选择开始时间");
            return false;
        }
        if ($scope.endApply == null || $scope.endApply == "") {
            Message.warning("请选择结束时间");
            return false;
        }
        if ($scope.startApply > $scope.endApply) {
            Message.warning("时间不对，请重新选择");
            return false;
        }
        else {
            $http({
                url: "/corporate-alliance/apply",
                method: "POST",
                data: $.param($scope.applyShopOkContactData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (status){
                if(status.data.status == 'success'){
                    Message.success('申请已提交，请等待对方审核');
                    $("#applyShopAddModal").modal("hide");
                    $("#waitForApplyShopModal").modal("show");
                    // 数据初始化
                    $scope.beApply = '';
                    $scope.startApply = '';
                    $scope.endApply = '';
                    $scope.getApplyShopList();
                }else {
                    Message.warning(status.data.data);
                    return false;
                }
            });
        }
    };
    // 获取申请列表的url
    $scope.applyUrl = '/corporate-alliance/get-apply';
    // 分页
    $scope.replacementPages = function (urlPages) {
        $scope.applyUrl = urlPages;
        $scope.getApplyShopList();
    };
    /**
     *author:苏雨
     *create:2017-6-29
     *函数描述:获取申请信息的列表数据  渲染到表格
     * */
    // 获取申请列表的信息
    $scope.getApplyShopList = function (){
        $.loading.show();
        $http.get($scope.applyUrl).success(function (response){
            // console.log(response);
            if(response.data != "" && response.data != undefined && response.data.length > 0){
                $scope.ApplyShopInfoList = response.data;
                $scope.pages    = response.pages;
                $scope.getApplyShopListNOdata = false;
            }else{
                $scope.ApplyShopInfoList = response.data;
                $scope.pages    = response.pages;
                $scope.getApplyShopListNOdata = true;
            }
            $.loading.hide();
        });
    };
    $scope.getApplyShopList();
    /**
     *author:苏雨
     *create:2017-6-28
     *函数描述:根据输入的搜索关键词进行查询
     *param: keywords:搜索的关键词
     * */
    // 搜索
    $scope.searchKeywords = function (){
        $http.get('/corporate-alliance/get-apply?keywords='+$scope.keywords).then(function (data){
            if(data.data.data != "" && data.data.data != undefined && data.data.data.length > 0){
                $scope.ApplyShopInfoList = data.data.data;
                $scope.pages    = data.data.pages;
                $scope.getApplyShopListNOdata = false;
            }else{
                $scope.ApplyShopInfoList = data.data.data;
                $scope.pages    = data.data.pages;
                $scope.getApplyShopListNOdata = true;
            }
        });
    };
    /**
     *author:苏雨
     *create:2017-6-29
     *函数描述:取消通店按钮点击后的事件，向后台发送公司的id
     *param: id:公司的id
     * */
    // 列表取消通店的按钮点击
    $scope.notApply = function (id,name){
        $scope.notId = id;
        Sweety.remove({
            url              : '/corporate-alliance/cancel-apply?applyRecordId=' +$scope.notId,
            http             : $http,
            title            : '确定要取消吗?',
            text             : '取消后则无法通店',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        },function () {
            $scope.getApplyShopList();
        });
    };
    /**
     *author:苏雨
     *create:2017-7-1
     *函数描述:根据id值获取通店的详情
     *param: id:公司id
     *       status:通店的状态
     *       name:通店的名称
     *       be_apply_id:被通店的id
     * */
    // 点击获取通店详情
    $scope.applyShopDetailsClick = function (id,status,name,be_apply_id){
        $scope.detailsId = id;
        $scope.detailsStatus = status;
        $scope.detailsName = name;
        $scope.beApplyId = be_apply_id;
        $http.get('/corporate-alliance/apply-details?applyRecordId=' + $scope.detailsId).success(function (data){
            // console.log(data);
            $scope.detailsApp = data;
            $scope.detailsAppId = data.id;
            $scope.end_apply = data.end_apply;
            $scope.start_apply = data.start_apply;
            $("#applyShopDetailsModal").modal("show");
            $scope.tabDetailsShop();
            $(".ApplyRecord").hide();
        });
        $scope.getApplyRecordInfo();
    };
    // 已申请后关闭按钮的事件
    $scope.waitForApplyShopModalClose = function (){
        $("#applyShopDetailsModal").modal("hide");
    };
    //关闭按钮的事件
    $scope.applyShopDetailsModalClose = function (){
        $("#applyShopDetailsModal").modal("hide");
    };
    //申请详情
    $scope.tabDetailsShop = function (){
        $(".tabBox1").addClass("tabBoxShopActive");
        $(".tabBox2").removeClass("tabBoxShopActive");
        $(".ApplyRecord").hide();
        $(".modalBox").show();
        return;
    };
    //申请记录
    $scope.tabRecordShop = function (){
        $(".tabBox2").addClass("tabBoxShopActive");
        $(".tabBox1").removeClass("tabBoxShopActive");
        $(".ApplyRecord").show();
        $(".modalBox").hide();
        return;
    };
    // 确定取消
    $scope.applyShopModalCloseNot = function (){
        $("#applyShopDetailsModal").modal("hide");
    };
    // 拒绝申请
    $scope.refuseApplyShop = function (){
        $http.get('/corporate-alliance/not-pass-apply?applyRecordId=' +$scope.detailsId).success(function (){
            $(".modalBody4GetShopFail").show();
            $(".modalFooter4GetShopFail").show();
            $('#myModalRecordApplyState').modal('show');
            $(".modalBody4GetShopSuccess").hide();
            $(".modalFooter4GetShopSuccess").hide();
        });
    };
    /**
     *author:苏雨
     *create:2017-6-28
     *函数描述:拒绝申请时，将各个数据传送到后台
     *param: recordId:公司id
     *       notApplyLength:不可以再次申请的时长
     *       note:被拒绝的原因的备注
     * */
    // 拒绝申请的数据整理
    $scope.noGetApplyShopFailData = function (){
        return{
            recordId:       $scope.detailsId       != undefined && $scope.detailsId       != "" ? $scope.detailsId :       null,
            notApplyLength: $scope.notApplyLength != undefined && $scope.notApplyLength != "" ? $scope.notApplyLength : null,
            note:           $scope.note           != undefined && $scope.note           != "" ? $scope.note :           null,
            _csrf_backend: $('#_csrf').val()
        };
    };
    // 拒绝申请的提交方法
    $scope.noGetApplyShopFail = function (){
        if ($scope.notApplyLength == null || $scope.notApplyLength == "") {
            Message.warning("请选择拒绝再次申请时长");
            return false;
        }
        else {
            $http({
                url: "/corporate-alliance/not-pass-apply",
                method: "POST",
                data: $.param($scope.noGetApplyShopFailData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (status){
                if(status.data.status == 'success'){
                    Message.success('操作成功');
                    // 数据初始化
                    $scope.notApplyLength = '';
                    $scope.note = '';
                    $("#myModalRecordApplyState").modal("hide");
                    $("#applyShopDetailsModal").modal("hide");
                    $scope.getApplyShopList();
                }else{
                    Message.warning('拒绝失败，请重新提交或者联系管理员');
                }
            });
        }
    };
    /**
     *author:苏雨
     *create:2017-6-28
     *函数描述:根据接口，获取公司的品牌名称
     * */
    // 获取品牌名称
    $scope.getContactName = function (){
        $http.get('/corporate-alliance/get-company').success(function (data){
            console.log(data)
            if(data.data == undefined || data.data == "" || data.data == null){

            }else{
                $scope.dataNa = data.data;
                $scope.contactNameddd = data.data.name;
                $scope.contactId = data.data.id;
            }
        });
    };
    $scope.getContactName();
    // 通过申请的点击方法
    $scope.argeeApplyShopSuccess = function (){
        $http.get('/corporate-alliance/pass-apply?applyRecordId=' +$scope.detailsId).success(function (data){
            // console.log(data);
            $("#myModalRecordApplyState").modal("show");
            $(".modalBody4GetShopSuccess").show();
            $(".modalFooter4GetShopSuccess").show();
            $(".modalBody4GetShopFail").hide();
            $(".modalFooter4GetShopFail").hide();
        });
        $scope.getApplyShopList();
    };
    // 同意对方申请之后的关闭按钮
    $scope.refuseApplyShopDetailsModalClose = function (){
        $("#myModalRecordApplyState").modal("hide");
        $("#applyShopDetailsModal").modal("hide");
        $scope.getApplyShopList();
    };
    /**
     *author:苏雨
     *create:2017-6-28
     *函数描述:通店成功之后，点击取消通店，向后台发送数据，并传入公司id值
     * */
    // 通店成功后取消通店
    $scope.cancelNot = function (){
        Sweety.remove({
            url              : '/corporate-alliance/cancel-apply?applyRecordId=' +$scope.detailsAppId,
            http             : $http,
            title            : '确定要取消吗?',
            text             : '取消后则无法通店',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        },function () {
            $scope.getApplyShopList();
            $scope.applyShopModalCloseNot();
        });
    };
    // 关闭申请详情模态框
    $scope.closeApplyModal = function (){
        $scope.getApplyShopList();
    };
    /**
     *author:苏雨
     *create:2017-6-28
     *函数描述:向后台发送公司id，获取申请的记录并渲染在页面
     * */
    // 获取申请记录
    $scope.getApplyRecordInfo = function (){
        $http.get('/corporate-alliance/apply-record?applyRecordId=' +$scope.detailsId).success(function (response){
            // console.log(response);
            if(response != "" && response != undefined && response.length > 0){
                $scope.applyRecordInfoList = response;
                $scope.noDataListShow = false;
            }else{
                $scope.applyRecordInfoList = response;
                $scope.noDataListShow = true;
            }
        });
    };
    $scope.waitForApplyShopModalClose3 = function (){
        $("#waitForApplyShopModal2").modal("hide");
    };
});
