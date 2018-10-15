/**
 * Created by DELL on 2017/5/13.
 * 验卡页面中预约课程页面
 */
app.controller('checkCourseCtrl',function($scope,$rootScope,$http,$location){
    // var getModuleId = localStorage.getItem("moduleId");
    // $rootScope.moduleId = JSON.parse(getModuleId).id
    var memberids = localStorage.getItem('member');
    var $memberIdArr = angular.fromJson(memberids);
    $scope.memberId  = $memberIdArr.memberDataid;
    $scope.memberCardId  = $memberIdArr.memberCard_id;
    $scope.invalid_time = $memberIdArr.invalid_time;
    //会员卡类型
    $scope.identify = $memberIdArr.identify;
    $('.backButton').click(function(){
        window.history.go(-1);
    })
    $scope.getTheDate = function (str) {
        str = parseInt(str);
        if (str != "" || str != null) {
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth() + 1;
            oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
            var oDay = oDate.getDate();
            oDay = oDay >= 10 ? oDay : '0' + oDay;
            var theDate = oYear + "-" + oMonth + "-" + oDay;
        } else {
            theDate = "";
        }
        return theDate
    };
    //本周周一和周日
    var MondayTimeAdd = 0;
    $scope.getCurrentWeekLast = function(){
        var oDate = new Date();
        var day = oDate.getDay();
        var nowTime = oDate.getTime();
        var oneDayLong = 24*60*60*1000 ;
        MondayTimeAdd = MondayTimeAdd-7;
        var MondayTime = nowTime - (day-1-MondayTimeAdd)*oneDayLong;
        $scope.currentWeekMonday = $scope.getTheDate(MondayTime);
    }
    $scope.getCurrentWeekNext = function(){
        var oDate = new Date();
        var day = oDate.getDay();
        var nowTime = oDate.getTime();
        var oneDayLong = 24*60*60*1000 ;
        MondayTimeAdd = MondayTimeAdd+7;
        var MondayTime = nowTime - (day-1-MondayTimeAdd)*oneDayLong;
        $scope.currentWeekMonday = $scope.getTheDate(MondayTime);
    }
    $scope.getData = function () {
        var url = '/check-card/group-class-data?memberId='+$scope.memberId
        $http({
            url: url,
            method: 'GET'
        }).then(function (data) {
            $scope.data = data.data;
        },function (error) {
            console.log(error);
            Message.warning('系统错误请联系工作人员')
        })
    };
    $scope.getData();
    $scope.getWeekData = function (fangxiang) {
        if(fangxiang == 'lastWeek'){
            $scope.getCurrentWeekLast();
        }else if(fangxiang == 'NextWeek') {
            $scope.getCurrentWeekNext();
        }
        var url = '/check-card/group-class-data?memberId='+$scope.memberId+'&weekStart='+$scope.currentWeekMonday;
        $http({
            url: url,
            method: 'GET'
        }).then(function (data) {
            $scope.data = data.data;
        },function (error) {
            console.log(error);
            Message.warning('系统错误请联系工作人员')
        })
    };
    $scope.dateCurrentw = myDate();
    $scope.selectCourseSeat = function(id,time) {
        if (time > $scope.invalid_time){
            Message.warning("您的卡"+$scope.getMyDate($scope.invalid_time)+"到期！！！暂时不能预约课程")
            return
        }
        $scope.classId = id
        $http({
            method: 'get',
            url: '/check-card/group-class-member-rule-data?memberId='+$scope.memberId+'&classId='+$scope.classId+'&memberCardId='+$scope.memberCardId,
        }).then(function (data) {
            $scope.isCanClass = data.data.isCanClass;
            $scope.isAboutClass = data.data.isAboutClass;
            $scope.isDance = data.data.isDance;
            if ($scope.isCanClass == false){
                Message.warning('您还不能约这节课！')
                $('#myModal').modal('hide');
                return
            }
            $scope.getAboutSeatRule();
        },function (err) {
            Message.warning('系统错误请联系工作人员')
            console.log(err)
        });
    };
    $scope.totalRows = [];
    $scope.getAboutSeatRule = function () {
        $scope.items = '';
        if ($scope.isAboutClass == true){
            Message.warning('该时间段已约课，不可重复预约');
            $('#myModal').modal('hide');
            return
        }
        if ($scope.isDance === true) {
            var url = '/check-card/get-seat-detail?id=' + $scope.classId + '&memberId='+$scope.memberId
            $http({
                url: url,
                method: 'GET'
            }).then(function (data) {
                if (data.data.status == 'success') {
                    $scope.items = data.data.message;
                    console.log(' $scope.items', $scope.items);
                    for(var i = 1;i<=$scope.items.total_rows;i++){
                        $scope.totalRows.push(i);
                    }
                    $('#myModal').modal('show');
                }
            }, function (error) {
                console.log(error)
                Message.warning('系统错误请联系工作人员')
                $('#myModal').modal('hide');
            })
        }
    };

    /**
     * 选择座位
     */
    var seatNum;
    var notPass = false;
    $scope.seatSelect = function (id, num,type,i) {
        $scope.memberSeatType = type;
        if ($('.courseSeates').eq(i).hasClass('notSelect') || $('.selectedVip').eq(i).hasClass('notSelect')) {
            return;
        }
        else {
            seatNum = id;
            notPass = false;
            var number = parseInt(seatNum);
            $scope.seatID = number;
            var $vip = $('.vip');
            if($scope.memberSeatType == 1){
                $('.courseSeates').removeClass('selected');
                $('.courseSeates').eq(i).addClass('selected');
                if(!$vip.hasClass('selected')){
                    $vip.addClass('selectedVip');
                    $vip.removeClass('vip');
                }
            }
            if($scope.memberSeatType == 2){
                if ($scope.memberSeatType != $scope.identify){
                    Message.warning("不好意思请升级，您的卡");
                    notPass = true;
                    return
                }
                $('.courseSeates').removeClass('selected');
                $('.courseSeates').eq(i).removeClass('selectedVip');
                $('.courseSeates').eq(i).addClass('selected');
                $('.courseSeates').eq(i).addClass('vip');
                if(!$vip.hasClass('selected')){
                    $vip.addClass('selectedVip');
                    $vip.removeClass('vip');
                }
            }
        }
    }
    $scope.modalClose = function () {
        $("#myModal").hide();
        $scope.totalRows.splice(0,$scope.totalRows.length);
    }
    //跳转预约成功
    $scope.appointment = function(){
        var orderData = {
            classId: $scope.classId, //课程ID
            _csrf_backend:$('#_csrf').val(),
            seatId:$scope.seatID, //选择座位
            memberCardId:$scope.memberCardId, // 会员卡ID
            memberId: $scope.memberId,//会员ID
            classType: 'group', //课程类型
            aboutT:'mobile',
        }
        if (notPass) {
            Message.warning("不好意思请升级，您的卡");
            return
        }
        if (orderData.seatId == '' || orderData.seatId == undefined){
            Message.warning('请选择座位')
            return;
        }
            $http({
                method: 'POST',
                url: '/check-card/set-about-class-record',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param(orderData)
            }).then(function (success) {
                if(orderData.seatId != ''){
                    if (success.data.message == '预约成功'){
                        var classsids = success.data.data
                        $('#myModal').modal('hide');
                        $('#myModalComplete').modal('show');
                        $scope.totalRows.splice(0,$scope.totalRows.length);
                        var  urls = '/check-card/get-about-class-detail?id='+ classsids
                        $http({
                            method: 'GET',
                            url:urls,
                        }).then(function (data) {
                            $scope.datase = data.data.data;
                            $scope.printers = data.data.data;
                        })
                    }
                    //预约重复
                    else if(success.data.status == 'repeat'){
                        Message.warning(success.data.message);
                        return
                    }
                    //该卡不能预约任何课程
                    else if(success.data.status == 'noClass'){
                        Message.warning(success.data.message);
                        return
                    }
                    //该卡不能预约此课程
                    else if(success.data.status == 'noBindClass'){
                        Message.warning(success.data.message);
                        return
                    }
                    //该课程今日预约次数已用完
                    else if(success.data.status == 'classOver'){
                        Message.warning(success.data.message);
                        return
                    }
                    //该时间点已预约其它课程
                    else if(success.data.status == 'hadClass'){
                        Message.warning(success.data.message);
                        return
                    }
                    //开课前多少分钟不能预约
                    else if(success.data.data.status == "endAboutLimit"){
                        Message.warning("开课"+ success.data.data.endClassLimit +"分钟前不能预约");
                        return
                    }
                    else {
                        Message.warning(success.data.message);
                        $('#myModal').modal('hide');
                    }
                }
            }, function (error) {
                console.log(error);
                Message.warning('系统错误请联系工作人员');
                $('#myModal').modal('hide');
            })
    }
    /****** 打印机start *****/
    //打印单条
    $scope.aboutPrints = function (item) {
        $scope.printers = item;
        $scope.printTicket();
    };
    //end
    $scope.printTicket = function () {
        if($scope.printers == undefined || !$scope.printers){
            Message.warning('没有预约的课程无法打印');
            return false;
        }
        $scope.aboutId = $scope.printers.id;
        $scope.getPrintHtml();
    };
    $scope.getPrintHtml = function () {
        var open = 1;
        if (open < 10) {
            var $prints = $('#coursePrints');
            var bodyHtml  = $('body').html();
            $scope.bdhtml = $prints.html();//获取当前页的html代码
            window.document.body.innerHTML= $scope.bdhtml;
            $scope.updateAboutStatus();
            window.print();
            window.document.body.innerHTML= bodyHtml;
            location.replace(location.href);
        } else {
            window.print();
        }
    };
    $scope.updateAboutStatus = function () {
        $http.get('/check-card/update-about-print?id='+$scope.aboutId).then(function (result) {

        });
    };
    /****** 打印机end ******/
    $scope.backPre = function(){
        history.go(-1);
    }
    //判断是否点击回车按钮
    angular.element(document).ready(function () {
        //点击ESC键返回详情页面
        $('body').keydown(function (event) {
            if ((event.keyCode || event.which)  == 27) {
                window.location.href='/check-card/detail?c=33&id='+$scope.memberCardId;
            }
        });
        //点击回车立即预约
        $("#myModal").keydown(function (event) {
            if ((event.keyCode || event.which)  == 13) {
                $scope.appointment();
            }
        });
        //点击回车关闭模态框
        $('#myModalComplete').keydown(function(event){
            if ((event.keyCode || event.which)  == 13) {
                $('#myModalComplete').modal('hide');
            }
        });
    });

    $scope.getMyDate = function(str){
        var Y,M,D,W,H,I,S;
        function fillZero(v){
            if(v<10){v='0'+v;}
            return v;
        }
        var d = new Date(parseInt(str/1000) * 1000 * 1000);
        Y = d.getFullYear();
        M = fillZero(d.getMonth()+1);
        D = fillZero(d.getDate());
        H = fillZero(d.getHours());
        I = fillZero(d.getMinutes());
        S = fillZero(d.getSeconds());
        var localTimes = Y+'-'+M+'-'+D
        return localTimes;
    };
});


function myDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
    return currentdate;
}