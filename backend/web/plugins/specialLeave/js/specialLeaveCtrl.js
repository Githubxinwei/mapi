/**
 * Created by DELL on 2017/8/24.
 * content : 特殊请假管理页面js
 */

$(function(){
    $('#memberCardSelect123').select2();
})

angular.module('App').controller('specialLeaveCtrl',function($scope,$http,$timeout){

    $scope.venueCheck = '';
    $scope.approvalStatus  = '';
    $scope.cardListsSelect = '';
    $scope.memberNameKeywords = '';
    $scope.leaveStatus= '';
    //安装时间插件
    $('#leaveStatisticsDate').daterangepicker(null, function(start, end, label) {
    });
    // $scope.init=function () {
    //     $("#leaveStatisticsDate").value("");
    //     $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
    //     $scope.getAllLeaveData();
    // }
        //获取点击事件
        $(".applyBtn").click(function(){
           $timeout(function(){
               var homesDate = $('#leaveStatisticsDate').val();
               var startTime = homesDate.substr(0, 10);
               var endTime = homesDate.substr(-10, 10);
               $scope.leaveStartTime  = startTime;
               $scope.leaveEndTime = endTime;
               $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
               $scope.getAllLeaveData();
           },300);
        });

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
    //列表排序
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
        $scope.getAllLeaveData();
    };
    //日期的选择
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

    //初始化获取当月的第一天和最后一天
    $scope.getMonthOneAndMonthLast = function(){
        var date = new Date();
        $scope.homesStartDate =$scope.getMyDate(date.setDate(1));

        var currentMonth=date.getMonth();
        var nextMonth=++currentMonth;
        var nextMonthFirstDay=new Date(date.getFullYear(),nextMonth,1);
        var oneDay=1000*60*60*24;
        $scope.homesEndDate = $scope.getMyDate(nextMonthFirstDay-oneDay);
        $('#leaveStatisticsDate').val($scope.homesStartDate+' - '+ $scope.homesEndDate);
    }
    $scope.getMonthOneAndMonthLast()
    //获取筛选数据
    $scope.getSelectData = function(){
        return {
            sortType      : $scope.sortType != undefined ? $scope.sortType : null,      //排序字段
            sortName      : $scope.sort     != undefined ? $scope.sort : null,          //排序状态
            name:$scope.memberNameKeywords != undefined && $scope.memberNameKeywords != '' ? $scope.memberNameKeywords :null,
            venueId        : $scope.venueCheck     != undefined  ? $scope.venueCheck     : null,
            memberCardId:$scope.cardListsSelect != undefined && $scope.cardListsSelect != '' ? $scope.cardListsSelect :null,
            leavePropertyId:$scope.approvalStatus != undefined && $scope.approvalStatus != '' ? $scope.approvalStatus :null,
            leaveType: $scope.leaveStatus !=undefined &&$scope.leaveStatus != ''? $scope.leaveStatus :null, //请假状态
            leaveStartTime: $scope.leaveStartTime !=undefined&&$scope.leaveStartTime != ''? $scope.leaveStartTime :null, //请假状态
            leaveEndTime: $scope.leaveEndTime !=undefined&&$scope.leaveEndTime != ''? $scope.leaveEndTime :null, //请假状态
        }
    }
    //获取所有会员卡种
    $scope.getAllCards = function(){
        $http.get('/special-leave/get-card-category').then(function(response){
            $scope.cardLists = response.data.type;
        });
    }
    $scope.getAllCards();
    //获取权限场馆
    $scope.getVenueInfo = function (){
        $http.get("/site/get-auth-venue").success(function (data){
            $scope.venueList = data;
        });
    };
    $scope.getVenueInfo();
    $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
    //获取特殊请假列表
    $scope.getAllLeaveData = function(){
        $.loading.show();
        $http.get($scope.specialLeaveUrlInit).then(function(response){
            if(response.data.data.length != 0 ){
                $scope.specialLeaveLists = response.data.data;
                $scope.specialLeaveFlag  = false;
                $scope.specialLeavePages = response.data.pages;
            }else{
                $scope.specialLeaveLists = response.data.data;
                $scope.specialLeaveFlag  = true;
                $scope.specialLeavePages = response.data.pages;
            }
            $.loading.hide();
        })
    }
    //input 为空
    $scope.input1="";
    $scope.getAllLeaveData();

    // 获取场馆信息
    $scope.getVenueInfo = function (){
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?'+$.param($scope.getSelectData());
        $scope.getAllLeaveData();
    };
    //选择卡种
    $scope.cardSelectChange = function(){
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
        $scope.getAllLeaveData();
    }

    //审批状态选择
    $scope.approvalStatusSelect = function(){
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
        $scope.getAllLeaveData();
    }

    //请假类型改变
    $scope.leaveStatusSelect = function(){
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
        $scope.getAllLeaveData();
    }
    //会员名字进行搜索
    $scope.memberNameSearchClick = function(){
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
        $scope.getAllLeaveData();
    }

    //keyup按键搜索
    $scope.memberNameSearchBtn = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.memberNameSearchClick(); }
    }

    //分页跳转
    $scope.replacementPages = function(urlPages){
        $scope.specialLeaveUrlInit = urlPages;
        $scope.getAllLeaveData();
    }
    /**
     * author：张亚鑫<zhangyaxin@itsports.club>
     * date:2017-11-24
     * 函数描述：点击清空按钮，将搜索条件全部初始化，
     *         清空前边搜索条件下拉框的值，并调用获取信息的函数，刷新列表。
     */
    $scope.cleanSearchButton = function () {
        $('#leaveStatisticsDate').val('');
        $scope.input1 = '';//清空时间选择器值;
        $scope.leaveStartTime = '';//起始时间清空
        $scope.leaveEndTime = '';//结束时间清空
        $scope.venueCheck = '';//场馆选择清空
        $scope.leaveStatus = '';//请假状态选择  ‘正常请假’/‘特殊请假’
        $scope.approvalStatus = '';//处理状态  ‘待处理’/‘已同意’/‘已拒绝’
        $scope.memberNameKeywords = '';//搜索框清空
        $scope.cardListsSelect = '';//清空选择卡种下拉框
        $('#select2-memberCardSelect123-container').text('请选择卡种');
        $scope.specialLeaveUrlInit = '/special-leave/leave-info?' +$.param($scope.getSelectData());
        $scope.getAllLeaveData();//调用获取列表的函数，刷新列表信息
    }

    //同意请假
    // $scope.agree = function(id){
    //     $http.get('/special-leave/update-status?id='+id).then(function(response){
    //         // console.log(response);
    //         if(response.data.status == "success"){
    //             Message.success(response.data.data);
    //             $scope.approvalStatusSelect();
    //         }else{
    //             Message.warning(response.data.data);
    //             return;
    //         }
    //     });
    // }
    $scope.agree = function (id){
        Sweety.remove({
            url              : '/special-leave/update-status?id='+id,
            http             : $http,
            title            : '确定要同意吗?',
            text             : '同意后将无法撤销哦！',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        },function () {
            $scope.approvalStatusSelect();
        })
    };


    //撤销申请记录
    $scope.revocation = function(id){
        Sweety.remove({
            url : "/sell-card/leave-record",
            http : $http,
            title : '确定要撤销申请吗?',
            text : '未确定请点击取消',
            // buttonColor : '#27c24c',
            confirmButtonText : '确定',
            confirmButton : '确定',
            data : {
                action: 'unbind'
            }
        }, function () {
            $http.get( '/special-leave/leave-revocation?id=' + id).then(function(response){
                if(response.data.status == "success"){
                    Message.success(response.data.data);
                    $scope.approvalStatusSelect();
                }else{
                    Message.warning(response.data.data);
                    return;
                }
            });
        },function(){

        },true,true);

    }


    //不同意请假
    $scope.noAgree = function(id){
        $scope.noAgreeId = id;
        $scope.noAgreeContent ='';
        $('#noAgreeModal').modal('show');
    }

    //不同意请假原因填写
    $scope.noAgreeComplete = function(){
        var data = function(){
            return {
                _csrf_backend :$('#_csrf').val(),
                rejectNote    :$scope.noAgreeContent != undefined && $scope.noAgreeContent != ''?$scope.noAgreeContent:null,
                id             :$scope.noAgreeId,
            }
        }
        var noAgreeContent123 = $('#noAgreeContent').val().replace(/(^\s*)|(\s*$)/g, "");
        if( noAgreeContent123 == ''){
            Message.warning('请填写不同意申请原因');
            return;
        }
        $http({
            url:'/special-leave/update-leave',
            method:'POST',
            data: $.param(data()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#noAgreeModal').modal('hide');
                $scope.approvalStatusSelect();
            }else{
                Message.warning(response.data.data);
                return;
            }
        })

    }

    //查看不同意原因
    $scope.checkNoAgreeDetail = function(id){
        $http.get('/special-leave/leave-details?id=' + id).then(function(response){
            $scope.rejectNoteContent = response.data.reject_note;
            $('#checkNoAgreeModal').modal('show');
        });
    }
});
