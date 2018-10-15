/**
 * Created by DELL on 2017/9/28.
 * author:程丽明
 * content:审核管理页面控制器
 */
$(function(){
    //场馆
    $('#allVenues').select2();
    //商品品牌
    $('#addCompanySelect').select2({
        width:'100%'
    });
    $('#addVenueSelect').select2({
        width:'100%'
    });
    $('#addDepartmentSelect').select2({
        width:'100%'
    });
    $('#addStaffSelect').select2({
        width:'100%'
    });
    $('#addRoleSelect').select2({
        width:'100%'
    });

    //时间
    $('#storeDate').daterangepicker(null, function(start, end, label) {
    });
    $('#applyForDate').daterangepicker(null, function(start, end, label) {
    });
});
angular.module('App').controller('approvalManagementCtrl',function($scope,$http,$timeout) {
    //应为页面公用、有些地方需要显示有些不需要显示、添加标识
    $scope.approvalHomepage123 = 1;
    $scope.myLaunchApprovalFlag123 = 1;
    /*****待审核页面控制器*****/
    /**
     *审批管理-待审核页面控制器-页待审核页面控制器
     * @author 程丽明 chengliming@itsports.club
     * @create 2017/09/28
     * @content 待审核页面js
     */
    $.loading.show();
    //获取所有的场馆
    $scope.getAllVenue = function(){
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.allVenueLists = response.data;
        })
    };
    $scope.getAllVenue();
    //初始化列表
    $scope.init = function () {
        $scope.initPath = '/approval-management/get-approval-data-list?status=1';
        $scope.getDataList();
    };
    //获取所有审批类型
    $scope.getApprovalType = function () {
        $http.get('/approval-management/get-approval-type').then(function (result) {
            $scope.itemTypes  = result.data;
        })
    };
    $scope.getApprovalType();
    /**页面搜索（搜索栏）***/
    $scope.search = function () {
        return {
            keypords        : $scope.stayApprovalKeywords  != undefined ? $scope.stayApprovalKeywords  : null,//关键字
        }
    };
    //搜索url接口
    $scope.initPathSearch = function () {
        $scope.searchParams = $scope.search();
        $scope.initPath = '/approval-management/get-approval-data-list?statusType=1&' + $.param($scope.searchParams);
    };
    //关键字搜索
    $scope.approvalKeywordsSearch = function () {
        $scope.initPathSearch();
        $scope.getDataList();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.initPath = urlPages;
        $scope.getDataList();
    };
    // 获取后台组织架构数据
    $scope.getDataList = function () {
        $.loading.show();
        $http.get($scope.initPath).then(function (result) {
            //console.log(result)
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.dataInfo   = false;
                $scope.searchData = false;
            }else{
                $scope.dataInfo = true;
            }
            $scope.homePageNow = result.data.now;
            $scope.items  = result.data.data;
            $scope.pages  = result.data.pages;
            $.loading.hide();
        })
    };
    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //获取审批详情
    $scope.getApprovalDetails = function (id) {
        $http.get('/approval-management/get-approval-details-data-by-id?id='+id).then(function (result) {
            $scope.itemDetails  = result.data.data;
            if($scope.itemDetails[$scope.speed] != undefined){
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }else{
                $scope.speed     = $scope.speed - 1;
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }
            $scope.coach        = result.data.coach;
        })
    };
    //获取卡种详情
    $scope.approvalDetailClick123 = function () {
        $scope.getIsOperation($scope.approvalTypeId123,$scope.cuurentDetailId);
        //console.log($scope.typeNote)
        if($scope.typeNote == '移动端卡种折扣价审批'){
            $('#discountsDetailModal').modal('show');
            //$('#pendingAuditDetailModal').modal('hide');
            $http.get('/phone-discounts/app-discount-details?discountId=' + $scope.getPolymorphicId).then(function(result){
                //console.log(result)
                if(result != undefined){
                    $scope.noDiscountVenueName = result.data.venueName; //场馆名字
                    $scope.noDiscountDiscount  = result.data.discount;  //折扣
                    $scope.noDiscountStart     = result.data.start;     //折扣售卖开始日期
                    $scope.noDiscountEnd       = result.data.end;       //折扣售卖结束日期
                    $scope.noDiscountCards     = result.data.no_discount_card;//没有折扣的卡种
                }
            })
        }else{
            $('#approvalDetailModel').modal('show');
            $http.get('/member-card/get-card-detail?id='+$scope.getPolymorphicId).then(function (result){
                $scope.cardDetails = result.data;
                $scope.theData = result.data;
                if($scope.cardDetails.cardTime.day){
                    var qqqq = $scope.cardDetails.cardTime.day;
                    $scope.cardTimeDay = JSON.parse(qqqq);
                }
                if($scope.cardDetails.cardTime.week){
                    var qqqq = $scope.cardDetails.cardTime.week;
                    $scope.cardTimeDay1 = JSON.parse(qqqq);
                }
                if ($scope.cardDetails.leave_total_days == null){
                    var awww = $scope.cardDetails.leave_long_limit;
                    $scope.leaveLongIimit = JSON.parse(awww);
                }
            })
        }

    };
    
    //添加评论
    $scope.approvalDetailCommentButton = function () {
        $scope.getInfo = {
            detailId      : $scope.detailsId,//详情id
            content       : $scope.content,//内容
            _csrf_backend : $('#_csrf').val()
        };
        $http({
            url     : '/approval-management/add-approval-comment',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
             if(response.status == 'success'){
                 Message.success('成功');
                 $scope.getApprovalDetails($scope.commentPpprovalId)
             }
            $('#operationModal').modal('hide');
            $scope.approvalDetailClick($scope.itemOne);

        });
    };
    //同意
    $scope.approvalDetailAgreeButton = function () {
        $scope.updateApprovalDetail(2);
    };
    //拒绝审批
    $scope.approvalDetailRefuseButton = function () {
        $scope.updateApprovalDetail(3);
    };
    //同意打卡
    $scope.updateApprovalDetail = function (status) {
        $scope.getInfo = {
            status        : status,//状态
            describe      : $scope.content,//内容
            id            : $scope.detailsId,//详情id
            _csrf_backend : $('#_csrf').val()
        };
        $scope.approvalDetailRefuseButtonFlag = true;
        $scope.approvalDetailAgreeButtonFlag = true;
        $http({
            url     : '/approval-management/add-approval-details',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            if(response.status == 'success'){
                Message.success('成功');
                $('#operationModal').modal('hide');
                $('#approvalDetailModel').modal('hide');
                $('#pendingAuditDetailModal').modal('hide')
                $scope.approvalKeywordsSearch();
            }else{
                $scope.approvalDetailAgreeButtonFlag = false;
                $scope.approvalDetailRefuseButtonFlag = false;
            }

        });
    };
    //审批详情页面点击同意按钮
    $scope.approvalDetailAgreeClick = function(){
        $('#contentBox123').val('');
        $scope.operationTitleName = '审批同意意见';
        $scope.approvalDetailAgreeButtonFlag = false;
        $scope.approvalDetailAgreeFlag123 = true;
        $scope.approvalDetailRefuseFlag123 = false;
        $scope.approvalDetailCommentFlag123 = false;
        $('#operationModal').modal('show');
    };

    //审批详情页面点击拒绝按钮
    $scope.approvalDetailRefuseClick = function(){
        $('#contentBox123').val('');
        $scope.operationTitleName = '审批拒绝意见';
        $scope.approvalDetailRefuseButtonFlag = false;
        $scope.approvalDetailRefuseFlag123 = true;
        $scope.approvalDetailAgreeFlag123 = false;
        $scope.approvalDetailCommentFlag123 = false;
        $('#operationModal').modal('show');
    };
    //列表详情
    $scope.approvalDetailClick = function(item){
        //console.log(item)
        $scope.typeNote = item.note;
        $scope.approvalTypeId123 = item.approval_type_id;
        $scope.cuurentDetailId = item.id;
        $scope.getIsOperation($scope.approvalTypeId123,$scope.cuurentDetailId);
        $scope.getPolymorphicId = item.polymorphic_id;
        var itemArr = angular.fromJson(item);
        $scope.itemOne = itemArr;
        $scope.commentPpprovalId = itemArr.id;
        $scope.speed   = $scope.itemOne.progress;
        $scope.getApprovalDetails(itemArr.id);
        $('#pendingAuditDetailModal').modal('show');
    };

    //判断登录人是否有操作权限
    $scope.getIsOperation = function(typeId,id){
        $http.get('/approval-management/can-approval-role?typeId='+typeId + '&id='+id).then(function(response){
            $scope.approvalTypeFlag = response.data.data;
        })
    }

    //审批详情页面点击评论按钮
    $scope.approvalDetailCommentClick = function(){
        $('#contentBox123').val('');
        $scope.operationTitleName = '评论';
        $scope.approvalDetailCommentButtonFlag = false;
        $scope.approvalDetailCommentFlag123 = true;
        $scope.approvalDetailAgreeFlag123 = false;
        $scope.approvalDetailRefuseFlag123 = false;
        $('#operationModal').modal('show');
    };
    $scope.init();
    //获取搜索数据
    $scope.getApprovalSearchData = function(){
        var homeDate = $('#storeDate').val();
        if(homeDate != ''){
            var startTime = homeDate.substr(0, 10);
            $scope.homeStartDate = startTime+' '+ '00:00:01';
            var endTime = homeDate.substr(-10, 10);
            $scope.homeEndDate = endTime +' '+ '23:59:59';
        }else{
            $scope.homeStartDate = '';
            $scope.homeEndDate = '';
        }

        return{
            venueId :$scope.homePageWarehouseVenue != '' && $scope.homePageWarehouseVenue != undefined ? $scope.homePageWarehouseVenue:null,//场馆id
            keywords:$scope.stayApprovalKeywords != '' && $scope.stayApprovalKeywords != undefined ? $scope.stayApprovalKeywords:null,//关键词
            type     :$scope.approvalType != '' && $scope.approvalType != undefined ? $scope.approvalType:null,//审批类型
            status   :1,//待审批状态
            statusType:1,//表示待审批
            startTime:$scope.homeStartDate != '' && $scope.homeStartDate != undefined ? $scope.homeStartDate:null,//开始时间
            endTime  :$scope.homeEndDate != '' && $scope.homeEndDate != undefined ? $scope.homeEndDate:null,//结束时间
        }
    }
    //列表筛选确定按钮
    $scope.approvalKeywordsSearch = function(){
        $scope.initPath = '/approval-management/get-approval-data-list?' + $.param($scope.getApprovalSearchData());
        $scope.getDataList();
    }

    //筛选键盘搜索
    $scope.stayApprovalSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.approvalKeywordsSearch();
        }
    }
    //清空筛选按钮
    $scope.ClearApprovalSearchSelect = function(){
        $('#storeDate').val('');
        $scope.homePageWarehouseVenue = '';
        $('#select2-allVenues-container').text('请选择场馆');
        $scope.stayApprovalKeywords     = '';
        $scope.approvalType  = '';
        $scope.approvalKeywordsSearch();
    }

}).controller('setApprovalCtrl',function($scope,$http,$timeout){
    /**
     *审批管理-审批设置-设置审批人和抄送人
     * @author 程丽明 chengliming@itsports.club
     * @create 2017/09/28
     * content:设置审批人和抄送人js
     */
    /*****设置审核流程页面控制器*****/
    $(document).ready(function (){
        $scope.getApprovalType(); //获取审批详情
        $scope.getAllCompany();   //获取审批公司
    });
    //通过选择审批类型获取角色
    $scope.selectAppType = function(id){
        $scope.getSetDefaultApproval(1);
        $scope.getSetDefaultApproval(2);
    }

    //获取设置的审批人
    $scope.getSetDefaultApproval = function(typeNum){
        if($scope.appType != undefined && $scope.appType != null){
            if(typeNum == 1){
                $http.get('/approval-management/get-approval-role?appType='+ $scope.appType+'&roleType='+ typeNum).then(function(response){
                    $scope.addApprovalNameArr = response.data;
                });
            }else{
                $http.get('/approval-management/get-approval-role?appType='+ $scope.appType+'&roleType='+ typeNum).then(function(response){
                    $scope.addCopyNameArr         = response.data;
                });
            }
        }
    }
    // 审批人初始化
    $scope.addApprovalArr     = [];
    $scope.addApprovalSmArr   = [];
    // 抄送人初始化
    $scope.addCopyArr         = [];
    $scope.addCopySmArr       = [];
    //返回上一页
    $scope.setBackPre = function(){
        history.go(-1);
    };
    /*** 添加审批、抄送 ***/

    //获取所有审批类型
    $scope.getApprovalType = function () {
        $http.get('/approval-management/get-approval-type').then(function (result) {
            $scope.itemTypes  = result.data;
        })
    };
    //获取审批公司
    $scope.getAllCompany = function () {
        $http.get('/site/get-auth-company').then(function (data) {
            $scope.companyItem = data.data;
        })
    };
    // 获取审批公司下的信息
    $scope.companyChange = function (id){
        $scope.companyId = id;
        $scope.getVenue(); //获取场馆
        $scope.getRole();  //获取角色
    };
    // 获取场馆
    $scope.getVenue = function (){
        $http.get("/personnel/get-venue-data?companyId=" + $scope.companyId).success(function (data){
             $scope.venueItem = data.venue;
        });
    };
    // 获取角色
    $scope.getRole = function (){
        $http.get("/approval-management/get-role?companyId=" + $scope.companyId).success(function (data){
            $scope.roleItem = data;
        })
    };
    // 角色的change事件
    $scope.roleChange = function (data){
        if(data != null && data != undefined && data != ''){
            $scope.roleChangeItem = angular.fromJson(data);
        }
        $scope.getEmployee($scope.roleChangeItem.id);
    };
    $scope.getEmployee = function(roleId){
        $http.get("/role/get-employee-member?roleId=" + roleId).success(function (data){
            $scope.employeeList = data.data;
        })
    };
    // 场馆的change事件
    // $scope.venueChange = function (value){
    //     $scope.getDepartment(value); //获取部门
    // };
    // 获取部门
    // $scope.getDepartment = function (value){
    //     $http.get("/personnel/get-department?depId=" + value).success(function (data){
    //         $scope.deparmentItem = data.venue;
    //     })
    // };
    // 部门的change事件
    // $scope.deparmentChange = function (value){
    //     $scope.getStaff(value);
    // };
    // 获取员工
    // $scope.getStaff = function (value){
    //     $http.get("/approval-management/get-employee?departmentId=" + value).success(function (data){
    //         $scope.staffItem = data;
    //     })
    // };
    // 角色的change事件
    $scope.staffChange = function (data){
        if(data != null && data != undefined && data != ''){
            $scope.staffChangeItem = angular.fromJson(data);
        }
    };
    // 初始化添加审批模态框select2
    $scope.initCopySelect2 = function (){
        // 公司
        $scope.companyValue = '';
        $('#select2-addCompanySelect-container').text('请选择公司');
        // 场馆
        $scope.venueValue = '';
        $('#select2-addVenueSelect-container').text('请选择场馆');
        // 部门
        $scope.deparmentValue = '';
        $('#select2-addDepartmentSelect-container').text('请选择部门');
        // 角色
        $scope.roleValue = '';
        $('#select2-addRoleSelect-container').text('请选择角色');
        // 人员
        $scope.staffValue = '';
        $('#select2-addStaffSelect-container').text('请选择人员');
    };
    //添加审批人
    $scope.addApprovalPeopleBtn = function(){
        $scope.initCopySelect2();
        $scope.completeAddApprovalFlag = false;
        $scope.completeAddCCPeopleFlag = false;
        $scope.addApprovalName = '审批';
        $scope.addApprovalFlag = true;
        $scope.addCCPeopleFlag = false;
        if($scope.appType != undefined && $scope.appType != '' && $scope.appType != null){
            $('#addApprovalPeopleModal').modal('show');
        }else{
            Message.warning('请选择审批类型');
        }
    };
    //添加抄送人
    $scope.addCCPeopleBtn = function(){
        $scope.initCopySelect2();
        $scope.completeAddApprovalFlag = false;
        $scope.completeAddCCPeopleFlag = false;
        $scope.addApprovalFlag = false;
        $scope.addCCPeopleFlag = true;
        $scope.addApprovalName = '抄送';
        if($scope.appType != undefined && $scope.appType != '' && $scope.appType != null){
            $('#addApprovalPeopleModal').modal('show');
        }else{
            Message.warning('请选择审批类型');
        }
    };

    //添加审批人和抄送人博保存的数据
    $scope.getAddTPData = function(roleTypeNum){
            return{
                _csrf_backend : $('#_csrf').val(),
                approvalType  : $scope.appType        != undefined && $scope.appType        != "" ? $scope.appType        : null, //审批类型
                roleType       : roleTypeNum    != undefined && roleTypeNum   != "" ? roleTypeNum : null, //审批人或抄送人
                companyId      : $scope.companyValue      != undefined && $scope.companyValue     != "" ? $scope.companyValue     : null, //公司
                venueId        : $scope.venueValue      != undefined && $scope.venueValue     != "" ? $scope.venueValue     : null, //场馆
                departId       : $scope.deparmentValue      != undefined && $scope.deparmentValue     != "" ? $scope.deparmentValue     : null, //部门
                roleId         : $scope.roleChangeItem.id      != undefined && $scope.roleChangeItem.id     != "" ? $scope.roleChangeItem.id     : null, //角色
                employeeId     : $scope.staffChangeItem.id      != undefined && $scope.staffChangeItem.id     != "" ? $scope.staffChangeItem.id     : null, //员工
            }
    };

    // 添加审批人完成
    $scope.completeAddApproval = function (){
        if($scope.companyValue == '' || $scope.companyValue == undefined || $scope.companyValue == null){
            Message.warning("请选择公司");
            return;
        }
        if($scope.venueValue == '' || $scope.venueValue == undefined || $scope.venueValue == null){
            Message.warning("请选择场馆");
            return;
        }
        // if($scope.deparmentValue == '' || $scope.deparmentValue == undefined || $scope.deparmentValue == null){
        //     Message.warning("请选择部门");
        //     return;
        // }
        if($scope.roleChangeItem == '' || $scope.roleChangeItem == undefined || $scope.roleChangeItem == null){
            Message.warning("请选择角色");
            return;
        }
        if($scope.staffChangeItem == '' || $scope.staffChangeItem == undefined || $scope.staffChangeItem == null){
            Message.warning("请选择员工");
            return;
        }
        $scope.completeAddApprovalFlag = true;
        $http({
            url: "/approval-management/set-approval-process",
            method: 'POST',
            data: $.param($scope.getAddTPData(1)),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data){
            if(data.data.status == "success"){
                Message.success(data.data.data);
                $scope.selectAppType();
                $("#addApprovalPeopleModal").modal("hide");
            }else{
                Message.warning(data.data.data);
                $scope.completeAddApprovalFlag = false;
            }
        });
    };
    // 添加抄送人完成
    $scope.completeAddCCPeople = function (){
        if($scope.companyValue == '' || $scope.companyValue == undefined || $scope.companyValue == null){
            Message.warning("请选择公司");
            return;
        }
        if($scope.venueValue == '' || $scope.venueValue == undefined || $scope.venueValue == null){
            Message.warning("请选择场馆");
            return;
        }
        // if($scope.deparmentValue == '' || $scope.deparmentValue == undefined || $scope.deparmentValue == null){
        //     Message.warning("请选择部门");
        //     return;
        // }
        if($scope.roleValue == '' || $scope.roleValue == undefined || $scope.roleValue == null){
            Message.warning("请选择角色");
            return;
        }
        if($scope.staffValue == '' || $scope.staffValue == undefined || $scope.staffValue == null){
            Message.warning("请选择员工");
            return;
        }
        $scope.completeAddCCPeopleFlag = true;
        $http({
            url: "/approval-management/set-approval-process",
            method: 'POST',
            data: $.param($scope.getAddTPData(2)),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data){
            if(data.data.status == "success"){
                Message.success(data.data.data);
                $scope.selectAppType();
                $("#addApprovalPeopleModal").modal("hide");
            }else{
                Message.warning(data.data.data);
                $scope.completeAddCCPeopleFlag = true;
            }
        });
    };
    //删除审批人和抄送人
    $scope.removeApprovalOrCC = function(id){
        $http.get('/approval-management/del-approval-role?appRoleId='+id).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $scope.selectAppType();
            }else{
                Message.warning(response.data.data);
            }
        });
    };

    /*** 添加审批、抄送结束 ***/


}).controller('alreadyApprovalCtrl',function($scope,$http,$timeout) {
    /**
     *审批管理-已审批页面-页面控制器
     * @author 程丽明 chengliming@itsports.club
     * @create 2017/09/28
     * @content 已审批页面js
     */
    /*****已审核页面控制器*****/
    //添加标识是否显示筛选
    $scope.approvalHomepage123 = 1;
    //获取所有的场馆
    $scope.getAllVenue = function(){
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.allVenueLists = response.data;
        })
    };
    $scope.getAllVenue();

    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //初始化调用
    $scope.init = function () {
        $scope.initPath = '/approval-management/get-approval-data-list?statusType=2';
        $scope.getDataList();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.initPath = urlPages;
        $scope.getDataList();
    };
    // 获取后台组织架构数据
    $scope.getDataList = function () {
        $.loading.show();
        $http.get($scope.initPath).then(function (result) {
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.dataInfo   = false;
                $scope.searchData = false;
            }else{
                $scope.dataInfo = true;
            }
            $scope.homePageNow = result.data.now;
            $scope.items  = result.data.data;
            $scope.pages  = result.data.pages;
            $.loading.hide();
        })
    };
    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //获取审批详情
    $scope.getApprovalDetails = function (id) {
        $http.get('/approval-management/get-approval-details-data-by-id?id='+id).then(function (result) {
            $scope.itemDetails  = result.data.data;
            if($scope.itemDetails[$scope.speed] != undefined){
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }else{
                $scope.speed     = $scope.speed - 1;
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }
            $scope.coach        = result.data.coach;
        })
    };
    //获取所有审批类型
    $scope.getApprovalType = function () {
        $http.get('/approval-management/get-approval-type').then(function (result) {
            $scope.itemTypes  = result.data;
        })
    };
    $scope.getApprovalType();

    //添加评论
    $scope.approvalDetailCommentButton = function () {
        $scope.getInfo = {
            detailId      : $scope.detailsId,//id
            content       : $scope.content,//内容
            _csrf_backend : $('#_csrf').val()
        };
        $http({
            url     : '/approval-management/add-approval-comment',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            if(response.status == 'success'){
                Message.success('成功');
                $scope.getApprovalDetails($scope.commentPpprovalId);
            }
            $('#operationModal').modal('hide');
        });
    };
    //待审核详情模态框
    $scope.approvalDetailClick = function(item){
        console.log(item)
        $scope.typeNote = item.note;
        $scope.getPolymorphicId = item.polymorphic_id;
        var itemArr = angular.fromJson(item);
        $scope.itemOne = itemArr;
        $scope.speed   = $scope.itemOne.progress;
        $scope.commentPpprovalId = itemArr.id;
        $scope.getApprovalDetails(itemArr.id);
        $('#pendingAuditDetailModal').modal('show');
    };
    //获取卡种详情
    $scope.approvalDetailClick123 = function (id) {
        if($scope.typeNote == '移动端卡种折扣价审批'){
            $('#discountsDetailModal').modal('show');
            $('#pendingAuditDetailModal').modal('hide');
            $http.get('/phone-discounts/app-discount-details?discountId=' + $scope.getPolymorphicId).then(function(result){
                //console.log(result)
                if(result != undefined){
                    $scope.noDiscountVenueName = result.data.venueName; //场馆名字
                    $scope.noDiscountDiscount  = result.data.discount;  //折扣
                    $scope.noDiscountStart     = result.data.start;     //折扣售卖开始日期
                    $scope.noDiscountEnd       = result.data.end;       //折扣售卖结束日期
                    $scope.noDiscountCards     = result.data.no_discount_card;//没有折扣的卡种
                }
            })
        }else{
            $('#approvalDetailModel').modal('show');
            $http.get('/member-card/get-card-detail?id='+$scope.getPolymorphicId).then(function (result){
                $scope.cardDetails = result.data;
                $scope.theData = result.data;
                if($scope.cardDetails.cardTime.day){
                    var qqqq = $scope.cardDetails.cardTime.day;
                    $scope.cardTimeDay = JSON.parse(qqqq);
                }
                if($scope.cardDetails.cardTime.week){
                    var qqqq = $scope.cardDetails.cardTime.week;
                    $scope.cardTimeDay1 = JSON.parse(qqqq);
                }
                if ($scope.cardDetails.leave_total_days == null){
                    var awww = $scope.cardDetails.leave_long_limit;
                    $scope.leaveLongIimit = JSON.parse(awww);
                }
            });
        }
    };
    //审批详情页面点击评论按钮
    $scope.approvalDetailCommentClick = function(){
        $scope.content = '';
        $scope.operationTitleName = '评论';
        $scope.approvalDetailCommentButtonFlag = false;
        $scope.approvalDetailCommentFlag123 = true;
        $scope.approvalDetailAgreeFlag123 = false;
        $scope.approvalDetailRefuseFlag123 = false;
        $('#operationModal').modal('show');
    };
    $scope.init();

    //获取搜索数据
    $scope.getApprovalSearchData = function(){
        var homeDate = $('#storeDate').val();
        if(homeDate != ''){
            var startTime = homeDate.substr(0, 10);
            $scope.homeStartDate = startTime+' '+ '00:00:01';
            var endTime = homeDate.substr(-10, 10);
            $scope.homeEndDate = endTime +' '+ '23:59:59';
        }else{
            $scope.homeStartDate = '';
            $scope.homeEndDate = '';
        }

        return{
            venueId :$scope.homePageWarehouseVenue != '' && $scope.homePageWarehouseVenue != undefined ? $scope.homePageWarehouseVenue:null,//场馆id
            keywords:$scope.stayApprovalKeywords != '' && $scope.stayApprovalKeywords != undefined ? $scope.stayApprovalKeywords:null,//关键字
            type     :$scope.approvalType != '' && $scope.approvalType != undefined ? $scope.approvalType:null,//审批类型
            status   :2,//该页面没有筛选，只能表示已审批
            statusType:2,//表示已审批页面
            startTime:$scope.homeStartDate != '' && $scope.homeStartDate != undefined ? $scope.homeStartDate:null,//开始时间
            endTime  :$scope.homeEndDate != '' && $scope.homeEndDate != undefined ? $scope.homeEndDate:null,//结束时间
        }
    }
    //列表筛选确定按钮
    $scope.approvalKeywordsSearch = function(){
        $scope.initPath = '/approval-management/get-approval-data-list?' + $.param($scope.getApprovalSearchData());
        $scope.getDataList();
    }

    //筛选键盘搜索
    $scope.stayApprovalSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.approvalKeywordsSearch();
        }
    }
    //关键字搜索
    $scope.initPathSearch = function(){
        $scope.approvalKeywordsSearch();
    }
    //清空筛选按钮
    $scope.ClearApprovalSearchSelect = function(){
        $('#storeDate').val('');
        $scope.homePageWarehouseVenue = '';
        $('#select2-allVenues-container').text('请选择场馆');
        $scope.stayApprovalKeywords     = '';
        $scope.approvalType  = '';
        $scope.approvalKeywordsSearch();
    }
}).controller('myLaunchApprovalCtrl',function($scope,$http,$timeout) {
    /*****我发起的页面控制器*****/
    /**
     *审批管理-我发起的页面-页面控制器js
     * @author 程丽明 chengliming@itsports.club
     * @create 2017/09/28
     * @content 我发起的页面js
     */
    //获取所有的场馆
    $scope.getAllVenue = function(){
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.allVenueLists = response.data;
        })
    }
    $scope.getAllVenue();

    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //初始化调用获取列表
    $scope.init = function () {
        $scope.initPath = '/approval-management/get-approval-data-list?statusType=3';
        $scope.getDataList();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.initPath = urlPages;
        $scope.getDataList();
    };
    // 获取后台组织架构数据
    $scope.getDataList = function () {
        $.loading.show();
        $http.get($scope.initPath).then(function (result) {
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.dataInfo   = false;
                $scope.searchData = false;
            }else{
                $scope.dataInfo = true;
            }
            $scope.homePageNow = result.data.now;
            $scope.items  = result.data.data;
            $scope.pages  = result.data.pages;
            $.loading.hide();
        })
    };
    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //获取审批详情
    $scope.getApprovalDetails = function (id) {
        $http.get('/approval-management/get-approval-details-data-by-id?id='+id).then(function (result) {
            $scope.itemDetails  = result.data.data;
            if($scope.itemDetails[$scope.speed] != undefined){
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }else{
                $scope.speed     = $scope.speed - 1;
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }
            $scope.coach        = result.data.coach;
        })
    };
    //获取所有审批类型
    $scope.getApprovalType = function () {
        $http.get('/approval-management/get-approval-type').then(function (result) {
            $scope.itemTypes  = result.data;
        })
    };
    $scope.getApprovalType();

    //添加评论
    $scope.approvalDetailCommentButton = function () {
        $scope.getInfo = {
            detailId      : $scope.detailsId,
            content       : $scope.content,
            _csrf_backend : $('#_csrf').val()
        };
        $http({
            url     : '/approval-management/add-approval-comment',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            if(response.status == 'success'){
                Message.success('成功');
                $scope.getApprovalDetails($scope.commentPpprovalId);
            }
            $('#operationModal').modal('hide');
        });
    };
    //我发起的详情模态框
    $scope.approvalDetailClick = function(item){
        $scope.typeNote = item.note;
        $scope.approvalStatus132 = item.status;
        $scope.getPolymorphicId = item.polymorphic_id;
        var itemArr = angular.fromJson(item);
        $scope.itemOne = itemArr;
        $scope.speed   = $scope.itemOne.progress;
        $scope.commentPpprovalId = itemArr.id;
        $scope.getApprovalDetails(itemArr.id);
        $('#pendingAuditDetailModal').modal('show');
    };

    //审批详情页面点击评论按钮
    $scope.approvalDetailCommentClick = function(){
        $scope.operationTitleName = '评论';
        $scope.content = '';
        $scope.approvalDetailCommentButtonFlag = false;
        $scope.approvalDetailCommentFlag123 = true;
        $scope.approvalDetailAgreeFlag123 = false;
        $scope.approvalDetailRefuseFlag123 = false;
        $('#operationModal').modal('show');
    }

    //点击撤销按钮
    $scope.approvalDetailRevokeClick = function(){
        $('#pendingAuditDetailModal').modal('hide');
        $('#RevokeModal').modal('show');
    }
    $scope.init();
    //获取卡种详情
    $scope.approvalDetailClick123 = function (id) {
        if($scope.typeNote == '移动端卡种折扣价审批'){
            $('#discountsDetailModal').modal('show');
            $('#pendingAuditDetailModal').modal('hide');
            $http.get('/phone-discounts/app-discount-details?discountId=' + $scope.getPolymorphicId).then(function(result){
                //console.log(result)
                if(result != undefined){
                    $scope.noDiscountVenueName = result.data.venueName; //场馆名字
                    $scope.noDiscountDiscount  = result.data.discount;  //折扣
                    $scope.noDiscountStart     = result.data.start;     //折扣售卖开始日期
                    $scope.noDiscountEnd       = result.data.end;       //折扣售卖结束日期
                    $scope.noDiscountCards     = result.data.no_discount_card;//没有折扣的卡种
                }
            })
        }else{
            $('#approvalDetailModel').modal('show');
            $http.get('/member-card/get-card-detail?id='+$scope.getPolymorphicId).then(function (result){
                $scope.cardDetails = result.data;
                $scope.theData = result.data;
                if($scope.cardDetails.cardTime.day){
                    var qqqq = $scope.cardDetails.cardTime.day;
                    $scope.cardTimeDay = JSON.parse(qqqq);
                }

                if($scope.cardDetails.cardTime.week){
                    var qqqq = $scope.cardDetails.cardTime.week;
                    $scope.cardTimeDay1 = JSON.parse(qqqq);
                }

                if ($scope.cardDetails.leave_total_days == null){
                    var awww = $scope.cardDetails.leave_long_limit;
                    $scope.leaveLongIimit = JSON.parse(awww);
                }
            })
        }

    };

    //撤销审批
    $scope.approvalDetailRevokeButton = function () {
        $scope.updateApprovalDetail(4);
    };
    //同意打卡
    $scope.updateApprovalDetail = function (status) {
        $scope.getInfo = {
            status        : status,
            describe      : $scope.describe,
            id            : $scope.detailsId,
            _csrf_backend : $('#_csrf').val()
        };

        $http({
            url     : '/approval-management/add-approval-details',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            if(response.status == 'success'){
                Message.success('成功');
            }
            $('#RevokeModal').modal('hide');
            $scope.approvalKeywordsSearch();
        });
    };

    //获取搜索数据
    $scope.getApprovalSearchData = function(){
        var homeDate = $('#storeDate').val();
        if(homeDate != ''){
            var startTime = homeDate.substr(0, 10);
            $scope.homeStartDate = startTime+' '+ '00:00:01';
            var endTime = homeDate.substr(-10, 10);
            $scope.homeEndDate = endTime +' '+ '23:59:59';
        }else{
            $scope.homeStartDate = '';
            $scope.homeEndDate = '';
        }

        return{
            venueId :$scope.homePageWarehouseVenue != '' && $scope.homePageWarehouseVenue != undefined ? $scope.homePageWarehouseVenue:null,//场馆id
            keywords:$scope.stayApprovalKeywords != '' && $scope.stayApprovalKeywords != undefined ? $scope.stayApprovalKeywords:null,//关键词
            type     :$scope.approvalType != '' && $scope.approvalType != undefined ? $scope.approvalType:null,//审批类型
            status   :$('#approvalStatusId').val() != '' && $('#approvalStatusId').val() != undefined ? $('#approvalStatusId').val():null,//页面中搜索的状态
            statusType:3,//表示我发起状态
            startTime:$scope.homeStartDate != '' && $scope.homeStartDate != undefined ? $scope.homeStartDate:null,//开始时间
            endTime  :$scope.homeEndDate != '' && $scope.homeEndDate != undefined ? $scope.homeEndDate:null,//结束时间
        }
    }
    //列表筛选确定按钮
    $scope.approvalKeywordsSearch = function(){
        $scope.initPath = '/approval-management/get-approval-data-list?' + $.param($scope.getApprovalSearchData());
        $scope.getDataList();
    }

    //筛选键盘搜索
    $scope.stayApprovalSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.approvalKeywordsSearch();
        }
    }
    //关键字搜索
    $scope.initPathSearch = function(){
        $scope.approvalKeywordsSearch();
    }

    //清空筛选按钮
    $scope.ClearApprovalSearchSelect = function(){
        $('#storeDate').val('');
        $('#approvalStatusId').val('');
        $scope.homePageWarehouseVenue = '';
        $('#select2-allVenues-container').text('请选择场馆');
        $scope.stayApprovalKeywords     = '';
        $scope.approvalType  = '';
        $scope.approvalStatus  = '';
        $scope.approvalKeywordsSearch();
    }
}).controller('approvalSendMeCtrl',function($scope,$http,$timeout) {
    /*****抄送我的页面控制器*****/
    /**
     *审批管理-抄送我的页面-页面控制器js
     * @author 程丽明 chengliming@itsports.club
     * @create 2017/09/28
     * @content 抄送我的页面
     */

    //获取所有的场馆
    $scope.getAllVenue = function(){
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.allVenueLists = response.data;
        })
    };
    $scope.getAllVenue();
    //获取所有审批类型
    $scope.getApprovalType = function () {
        $http.get('/approval-management/get-approval-type').then(function (result) {
            $scope.itemTypes  = result.data;
        })
    };
    $scope.getApprovalType();

    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //初始化获取列表数据
    $scope.init = function () {
        $scope.initPath = '/approval-management/get-approval-data-list?statusType=4';
        $scope.getDataList();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.initPath = urlPages;
        $scope.getDataList();
    };
    // 获取后台组织架构数据
    $scope.getDataList = function () {
        $.loading.show();
        $http.get($scope.initPath).then(function (result) {
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.dataInfo   = false;
                $scope.searchData = false;
            }else{
                $scope.dataInfo = true;
            }
            $scope.homePageNow = result.data.now;
            $scope.items  = result.data.data;
            $scope.pages  = result.data.pages;
            $.loading.hide();
        })
    };
    //跳转设置审批流程
    $scope.setApprovalButton = function(){
        location.href='/approval-management/set'
    };
    //抄送我的页面详情模态框
    $scope.approvalDetailClick = function(){
        $('#pendingAuditDetailModal').modal('show');
    };
    //获取审批详情
    $scope.getApprovalDetails = function (id) {
        $http.get('/approval-management/get-approval-details-data-by-id?id='+id).then(function (result) {
            $scope.itemDetails  = result.data.data;
            if($scope.itemDetails[$scope.speed] != undefined){
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }else{
                $scope.speed     = $scope.speed - 1;
                $scope.detailsId = $scope.itemDetails[$scope.speed].id;
            }
            $scope.coach        = result.data.coach;
        })
    };

    //添加评论
    $scope.approvalDetailCommentButton = function () {
        $scope.getInfo = {
            detailId      : $scope.detailsId,
            content       : $scope.content,
            _csrf_backend : $('#_csrf').val()
        };
        $http({
            url     : '/approval-management/add-approval-comment',
            method  : 'POST',
            data    : $.param($scope.getInfo),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (response){
            if(response.status == 'success'){
                Message.success('成功');
            }
            $scope.getApprovalDetails($scope.commentPpprovalId);
            $('#operationModal').modal('hide');
        });
    };
    //抄送我的页面详情模态框
    $scope.approvalDetailClick = function(item){
        $scope.typeNote = item.note;
        var itemArr = angular.fromJson(item);
        $scope.getPolymorphicId = item.polymorphic_id;
        $scope.itemOne = itemArr;
        $scope.speed   = $scope.itemOne.progress;
        $scope.commentPpprovalId = itemArr.id;
        $scope.getApprovalDetails(itemArr.id);
        $('#pendingAuditDetailModal').modal('show');
    };
    //获取卡种详情
    $scope.approvalDetailClick123 = function (id) {
        if($scope.typeNote == '移动端卡种折扣价审批'){
            $('#discountsDetailModal').modal('show');
            $('#pendingAuditDetailModal').modal('hide');
            $http.get('/phone-discounts/app-discount-details?discountId=' + $scope.getPolymorphicId).then(function(result){
                //console.log(result)
                if(result != undefined){
                    $scope.noDiscountVenueName = result.data.venueName; //场馆名字
                    $scope.noDiscountDiscount  = result.data.discount;  //折扣
                    $scope.noDiscountStart     = result.data.start;     //折扣售卖开始日期
                    $scope.noDiscountEnd       = result.data.end;       //折扣售卖结束日期
                    $scope.noDiscountCards     = result.data.no_discount_card;//没有折扣的卡种
                }
            })
        }else{
            $('#approvalDetailModel').modal('show');
            $http.get('/member-card/get-card-detail?id='+$scope.getPolymorphicId).then(function (result){
                $scope.cardDetails = result.data;
                $scope.theData = result.data;
                if($scope.cardDetails.cardTime.day){
                    var qqqq = $scope.cardDetails.cardTime.day;
                    $scope.cardTimeDay = JSON.parse(qqqq);
                }
                if($scope.cardDetails.cardTime.week){
                    var qqqq = $scope.cardDetails.cardTime.week;
                    $scope.cardTimeDay1 = JSON.parse(qqqq);
                }
                if ($scope.cardDetails.leave_total_days == null){
                    var awww = $scope.cardDetails.leave_long_limit;
                    $scope.leaveLongIimit = JSON.parse(awww);
                }
            });
        }

    };
    //审批详情页面点击评论按钮
    $scope.approvalDetailCommentClick = function(){
        $scope.operationTitleName = '评论';
        $scope.approvalDetailCommentButtonFlag = false;
        $scope.approvalDetailCommentFlag123 = true;
        $scope.approvalDetailAgreeFlag123 = false;
        $scope.approvalDetailRefuseFlag123 = false;
        $('#operationModal').modal('show');
    }
    $scope.init();

    //获取搜索数据
    $scope.getApprovalSearchData = function(){
        var homeDate = $('#storeDate').val();
        if(homeDate != ''){
            var startTime = homeDate.substr(0, 10);
            $scope.homeStartDate = startTime+' '+ '00:00:01';
            var endTime = homeDate.substr(-10, 10);
            $scope.homeEndDate = endTime +' '+ '23:59:59';
        }else{
            $scope.homeStartDate = '';
            $scope.homeEndDate = '';
        }

        return{
            venueId :$scope.homePageWarehouseVenue != '' && $scope.homePageWarehouseVenue != undefined ? $scope.homePageWarehouseVenue:null,//场馆id
            keywords:$scope.stayApprovalKeywords != '' && $scope.stayApprovalKeywords != undefined ? $scope.stayApprovalKeywords:null,//关键词
            type     :$scope.approvalType != '' && $scope.approvalType != undefined ? $scope.approvalType:null,//审批类型
            status   :$('#approvalStatusId').val() != '' && $('#approvalStatusId').val() != undefined ? $('#approvalStatusId').val():null,//列表搜索状态
            statusType:4,//表示抄送我的状态
            startTime:$scope.homeStartDate != '' && $scope.homeStartDate != undefined ? $scope.homeStartDate:null,//开始时间
            endTime  :$scope.homeEndDate != '' && $scope.homeEndDate != undefined ? $scope.homeEndDate:null,//结束时间
        }
    }
    //列表筛选确定按钮
    $scope.approvalKeywordsSearch = function(){
        $scope.initPath = '/approval-management/get-approval-data-list?' + $.param($scope.getApprovalSearchData());
        $scope.getDataList();
    }

    //筛选键盘搜索
    $scope.stayApprovalSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.approvalKeywordsSearch();
        }
    }
    //关键字搜索
    $scope.initPathSearch = function(){
        $scope.approvalKeywordsSearch();
    }
    //清空筛选按钮
    $scope.ClearApprovalSearchSelect = function(){
        $('#storeDate').val('');
        $scope.homePageWarehouseVenue = '';
        $('#select2-allVenues-container').text('请选择场馆');
        $scope.stayApprovalKeywords     = '';
        $scope.approvalType  = '';
        $scope.approvalStatus  = '';
        $('#approvalStatusId').val('');
        $scope.approvalKeywordsSearch();
    }
});