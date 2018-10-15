/**
 * Created by admin on 2018/2/1.
 */
$(document).ready(function () {
    $('select#venueChoose,select#statusChoose,select#addDiscountVenue,select#updateVenueSelect,select#discountCardSelect,select#updateNoDiscount').select2({
        width   : '130px'
    });
    function useDatePicker(dom) {
        dom.datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true//今日按钮
        });
    };
    useDatePicker($(".startTime"));
    useDatePicker($(".endTime"));
});
angular.module('App').controller('phoneDiscountCtrl', function ($scope, $http) {
    $.loading.show();
    // 初始化变量
    $scope.init = function () {
        //搜索框 & 下拉框
        $scope.searchDiscount           = '';//搜索框
        $scope.venueChooseDiscount      = '';//场馆选择下拉框
        $scope.statusChooseDiscount     = '';//状态选择下拉框
        //新增
        $scope.addDiscountVenue         = '';//场馆
        $scope.addDiscountDiscount      = '';//移动端折扣
        $scope.addDiscountStartTime     = '';//折扣开始时间
        $scope.addDiscountEndTime       = '';//折扣截止时间
        $scope.noDiscountCardType       = '';//没有折扣的卡种
        //修改
        $scope.updateDiscountVenue      = '';//场馆
        $scope.updateDiscountDiscount   = '';//移动端折扣
        $scope.updateDiscountStartTime  = '';//折扣开始时间
        $scope.updateDiscountEndTime    = '';//折扣截止时间
        $scope.updateNoDiscountCardType = '';//没有折扣的卡种
        //初始化列表
        $scope.initPath();
        $scope.getDiscountList();
    };
    //获取列表的参数
    $scope.param1 = function () {
        return {
            venueId   : $scope.venueChooseDiscount  != '' && $scope.venueChooseDiscount  != undefined ? $scope.venueChooseDiscount  : null, //场馆Id
            status    : $scope.statusChooseDiscount != '' && $scope.statusChooseDiscount != undefined ? $scope.statusChooseDiscount : null, //状态
            keywords  : $scope.searchDiscount       != '' && $scope.searchDiscount       != undefined ? $scope.searchDiscount       : null  //关键字
        }
    };
    //根据不同参数获取不同的路径
    $scope.initPath = function () {
        $scope.pageInitUrl ='/phone-discounts/app-discount-list?' + $.param($scope.param1());
    };
    //列表
    $scope.getDiscountList = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function(res){
            $scope.searchDataBtnClickFlag = false;
            //console.log(res)
            if(res.data.data.length != 0){
                $scope.discountsList = res.data.data;  //列表数据
                $scope.discountsPage = res.data.pages; //分页
                $scope.dataInfo      = false;          //暂无数据
            }else{
                $scope.discountsList = res.data.data;  //列表数据
                $scope.discountsPage = res.data.pages; //分页
                $scope.dataInfo      = true;           //暂无数据
            }
            $.loading.hide();
        })
    };
    $scope.init();

    //根据账号权限获取场馆列表
    $scope.getVenueList = function () {
        $http.get('/site/get-auth-venue').then(function (response) {
            // console.log(response.data);
            $scope.venueList = response.data;
        })
    };
    $scope.getVenueList();
    //搜索框事件
    $scope.searchPhoneDiscount = function () {
        $scope.searchDataBtnClickFlag = true;
        $scope.initPath();
        $scope.getDiscountList();
    };
    //场馆下拉框事件
    $scope.venueChangeDiscount = function (id) {
        $scope.initPath();
        $scope.getDiscountList();
    };
    //所有状态下拉框事件
    $scope.statusChangeDiscount = function (id) {
        $scope.initPath();
        $scope.getDiscountList();
    };
    //分页事件
    $scope.appDiscountPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getDiscountList();
    };
    //清除按钮事件
    $scope.clearBtn = function(){
        $scope.searchDiscount       = '';//搜索框
        $scope.venueChooseDiscount  = '';//场馆选择下拉框
        $scope.statusChooseDiscount = '';//状态选择下拉框
        $('#select2-venueChoose-container').text('选择场馆').attr('title', '选择场馆');
        $('#select2-statusChoose-container').text('所有状态').attr('title', '所有状态');
        $scope.initPath();
        $scope.getDiscountList();
    };
    //新增按钮
    $scope.addNewPhoneDiscount = function () {
        $scope.addDiscountVenue         = '';//场馆
        $scope.addDiscountDiscount      = '';//移动端折扣
        $scope.addDiscountStartTime     = '';//折扣开始时间
        $scope.addDiscountEndTime       = '';//折扣截止时间
        $scope.noDiscountCardType       = '';//没有折扣的卡种
        //$('.select2-selection__rendered').text('选择场馆').attr('title', '选择场馆');
        $('#select2-addDiscountVenue-container').text('选择场馆').attr('title', '选择场馆');
        $('#select2--container').text('选择卡种').attr('title', '选择卡种');
        $('#addNewPhoneDiscountsModal').modal('show');
        $('.payMoneyType').attr('disabled',true); //初始化不打折卡种禁止点击
        $('.startTime').val('');//折扣有效期限开始日期
        $('.endTime').val('');    //折扣有效期限结束日期
    };

    //获取选中场馆下的所有卡种
    $scope.venueChange = function(id){
        if(id == ''){
            $('.payMoneyType').attr('disabled',true);
        }else{
            $('.payMoneyType').attr('disabled',false);
            $http.get('/sell-card/card-category?venueId='+ id).then(function (response) {
                $scope.venueCardList = response.data;
            })
        }
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".payMoneyType").select2({
            multiple: true,
            dropdownParent: $("#addNewPhoneDiscountsModal"),
            width: "100%"
        });
        $(' ').css('width','100%')
    });
    //获取不打折卡种的id(数组)
    $scope.noDiscountChange = function(arr){
        $scope.noDiscountsIdArr = arr;  //不打折卡种的id
    };
    //新增移动端折扣
    $scope.addDiscount = function () {
        var request = {
            scenarios      : 'add',
            venueId        : $scope.addDiscountVenue,
            discount       : $scope.addDiscountDiscount,
            start          : $scope.addDiscountStartTime,
            end            : $scope.addDiscountEndTime,
            noDiscountCard : $scope.noDiscountsIdArr,
            _csrf_backend: $('#_csrf').val(),
        };
        // console.log(request);
        if(!$scope.addDiscountVenue) {
            Message.warning('请选择场馆！');
            return false;
        }
        if(!$scope.addDiscountDiscount) {
            Message.warning('请输入移动端折扣！');
            return false;
        }
        var startTimeStr = Date.parse($scope.addDiscountStartTime + ' 00:00:00');
        var endTimeStr = Date.parse($scope.addDiscountEndTime + ' 23:59:59');
        if( endTimeStr < startTimeStr ) {
            Message.warning('结束日期不能小于开始日期！');
            return false;
        }
        $http({
            url: '/phone-discounts/add-app-discount',
            method: 'post',
            data: $.param(request),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response != undefined){
                if(response.data.status == 'success'){
                    Message.success('添加成功');
                    $('#addNewPhoneDiscountsModal').modal('hide');
                    $scope.getDiscountList();
                }else{
                    Message.warning(response.data.data);
                }
            }
        })
    };
    //冻结折扣
    $scope.frozenDiscount = function (id) {
        $http.get('/phone-discounts/frozen-app-discount?discountId=' + id).then(function (response) {
            //console.log(response);
            if(response.data.status == 'success'){
                $scope.getDiscountList();
            }
        })
    };

    //折扣详情
    $scope.detailDiscountBtn = function (id) {
        $('#discountsDetailModal').modal('show');
        $http.get('/phone-discounts/app-discount-details?discountId=' + id).then(function(result){
            //console.log(result)
            if(result != undefined){
                $scope.detailVenueName = result.data.venueName; //场馆名称
                $scope.detailDiscount = result.data.discount;   //折扣
                $scope.detailStartDate = result.data.start;     //折扣有效期限开始日期
                $scope.detailEndDate  = result.data.end;        //折扣有效期限结束日期
                $scope.detailNoDiscountList = result.data.no_discount_card; //不打折卡种
            }
        })
    };
/************修改按钮事件开始*************/
    //修改按钮
    $scope.updatePhoneDiscount = function (object) {
        $('#updatePhoneDiscountsModal').modal('show');
        $scope.discountId = object.id;                              //列表id
        $scope.updateDiscountVenue = object.venue_id;               //场馆id
        $('#select2-updateVenueSelect-container').text(object.name);//场馆名称
        $scope.updateDiscountDiscount = object.discount;            //折扣
        $scope.updateNoDiscount = JSON.parse(object.no_discount_card); //不打折的卡
        function timestampToTime(timestamp) {
            var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
            Y = date.getFullYear() + '-';
            M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
            D = date.getDate() + ' ';
            return Y+M+D;
        }
        $('.startTime').val(timestampToTime(object.start));//折扣有效期限开始日期
        $('.endTime').val(timestampToTime(object.end));    //折扣有效期限结束日期
        $http.get('/sell-card/card-category?venueId='+ $scope.updateDiscountVenue).then(function (response) {
            $scope.updateVenueCardList = response.data;
        });
    };
    //场馆下拉框事件
    $scope.updateVenueChange = function(id){
        if(id == ''){
            $('.updateNoDiscount').attr('disabled',true);
            $scope.updateNoDiscountsIdArr = [];
        }else{
            $('.updateNoDiscount').attr('disabled',false);
            $scope.updateDiscountVenue = id;
            $http.get('/sell-card/card-category?venueId='+ $scope.updateDiscountVenue).then(function (response) {
                //console.log(response)
                $scope.updateVenueCardList = response.data;
            })
        }
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".updateNoDiscount").select2({
            multiple: true,
            dropdownParent: $("#updatePhoneDiscountsModal"),
            width: "100%"
        });
    });
    //获取不打折卡种的id(数组)
    $scope.updateNoDiscountChange = function(arr){
        $scope.updateNoDiscountsIdArr = arr;  //不打折卡种的id
    };
    //修改折扣提交参数
    $scope.updateDiscount = function(){
        var request2 = {
            scenarios      : 'update',
            venueId        : $scope.updateDiscountVenue,    //场馆id
            discount       : $scope.updateDiscountDiscount, //折扣
            start          : $('#updateDiscountStartTime').val(),//折扣开始时间
            end            : $('#updateDiscountEndTime').val(),  //折扣结束时间
            noDiscountCard : $scope.updateNoDiscountsIdArr, //不打折卡的数组id
            discountId     : $scope.discountId,             //列表id
            _csrf_backend: $('#_csrf').val(),
        };
        if(!$scope.updateDiscountVenue) {
            Message.warning('请选择场馆！');
            return false;
        }
        if(!$scope.updateDiscountDiscount) {
            Message.warning('请输入移动端折扣！');
            return false;
        }
        var startTimeStr = Date.parse($('#updateDiscountStartTime').val() + ' 00:00:00');
        var endTimeStr = Date.parse($('#updateDiscountEndTime').val() + ' 23:59:59');
        if( endTimeStr < startTimeStr ) {
            Message.warning('结束日期不能小于开始日期！');
            return false;
        }
        $http({
            url: '/phone-discounts/add-app-discount',
            method: 'post',
            data: $.param(request2),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response != undefined){
                if(response.data.status == 'success'){
                    Message.success('修改成功');
                    $('#updatePhoneDiscountsModal').modal('hide');
                    $scope.getDiscountList();
                }else{
                    Message.warning(response.data.data);
                }
            }
        })
    };
/************修改按钮事件结束*************/
    //删除折扣
    $scope.deleteDiscount = function (id) {
        swal({
            title: "确定删除吗？",
            text: "删除后无法恢复该数据",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: false
        },function(isConfirm) {
            if (isConfirm) {
                $http.get('/phone-discounts/del-app-discount?discountId=' + id).then(function (response) {
                    //console.log(response);
                    if(response.data.status == 'success'){
                        swal('删除成功！','','success');
                        $scope.getDiscountList();
                    }
                })
            }else {
                swal.close();
            }
        });
    };

    //修改折扣
    // $scope.changeDiscount = function (id) {
    //     console.log(id);
    // };
});