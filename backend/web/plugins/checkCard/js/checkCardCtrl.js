/**
 * 验卡详情页页面控制器js
 */
var app = angular.module('App');
app.controller('checkIndexCtrl', function ($scope, $http, $document, $rootScope, $timeout) {

    angular.element(document).ready(function () {
        var a = localStorage.getItem("true")
        if (a = a) {
            var memberids = localStorage.getItem('success');
            var $memberIdArr = angular.fromJson(memberids);
            var us = localStorage.getItem('successw');
            var uw = angular.fromJson(us);
            if ($memberIdArr == 'success') {
                $scope.Towel = localStorage.getItem('serverData')
                Swal.success(uw, '验卡成功');
                localStorage.removeItem("true");//删除名称为“key”的信息。
                localStorage.removeItem("success");//删除名称为“key”的信息。
                localStorage.removeItem("successw");//删除名称为“key”的信息。
            }
            if ($memberIdArr == 'error') {
                Swal.error(uw);
                localStorage.removeItem("true");//删除名称为“key”的信息。
                localStorage.removeItem("success");//删除名称为“key”的信息。
                localStorage.removeItem("successw");//删除名称为“key”的信息。
            }
        }
    })
    $scope.checkbigTi = function () {
        $(".cardCheckNumberInput").focus();
    };
    $scope.checkbigTi();
    $scope.init = function () {
        $scope.cardNumber = '';
    };
    $document.on('click', '#checkCardFocus', function () {
        $('input').focus();
    });
    $document.bind("keypress", function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.checkCardNumberMember();
        }
    });

    angular.element(document).ready(function () {
        $('#selectCardModal').keydown(function (e) {
            if (!e) var e = window.event;
            if (e.keyCode == 13) {
                $.loading.show();
                $scope.checkCardSelect($scope.selectCardId);
            }
        })

    })

    $scope.checkCardNumberMember = function () {
        if ($scope.cardNumber == '' || $scope.cardNumber == undefined) {
            Message.warning('请输入会员卡编号');
            return false;
        }
        if ($scope.cardNumber.indexOf(':') > 0) {
            $scope.cardNumber = $scope.cardNumber.substr(0, 0);
            $scope.checkCardNumber = $scope.cardNumber;
        }
        if ($scope.cardNumber.indexOf('?') > 0 || $scope.cardNumber.indexOf('？') > 0) {
            $scope.cardNumber = $scope.cardNumber.substr(0, $scope.cardNumber.length - 1);
            $scope.checkCardNumber = $scope.cardNumber;
        }
        // $scope.cardNumber        = $scope.cardNumber.replace(/[^0-9]*/g,'');
        var $pattern = /^1((3[0-9]|4[57]|5[0-35-9]|7[03678]|8[0-9])\d{8}$)/;
        $scope.numType = 'num';
        if (($pattern.test($scope.cardNumber))) {
            $scope.numType = 'mobile';

            $http.get('/user/get-member-info?mobile=' + $scope.cardNumber).then(function (response) {
                if (response.data != null && response.data != '' && response.data != undefined && response.data != 'null') {
                    $http.get('/user/member-card-info?MemberId=' + response.data.id +'&type=2').then(function (response) {
                        if (response.data.item.length > 1) {
                            $scope.allMemberCard = response.data.item;
                            $('#selectCardModal').modal('show');
                        }
                        if (response.data.item.length == 1) {
                            $scope.checkCardNumber = response.data.item[0].id
                            window.location.href = '/check-card/detail?c=33&id=' + $scope.checkCardNumber;
                        }
                        if (response.data.item.length == 0) {
                            Swal.error('该会员手机号不存在，请前去购卡!');
                            $scope.cardNumber = '';
                        }
                    })
                }else{
                    Swal.error('会员不存在或该会员卡不能通店');
                }
            })
        } else {
            $scope.numType = 'num';
        }

        if ($scope.numType == 'num') {
            $scope.checkButton = true;
            $http.get('/check-card/check-card-number?num=' + $scope.cardNumber + '&type=num').then(function (result) {
                if (result.data.status == 'success') {
                    $scope.memberId = result.data.data;
                    if (angular.isObject($scope.memberId) === true) {
                        window.location.href = '/check-card/detail?c=33&id=' + $scope.memberId.id;
                    } else {
                        window.location.href = '/check-card/detail?c=33&id=' + $scope.memberId;
                    }
                } else {
                    Swal.error(result.data.message);
                    $scope.cardNumber = '';
                }
                $scope.checkButton = false;
            });
        }

        return false;
    };
    $scope.init();
    $scope.checkCardSelect = function (cardId) {
        if (cardId != '' && cardId != undefined) {
            $scope.checkButton = false;
            window.location.href = '/check-card/detail?c=33&id=' + cardId;
            $timeout(function () {
                $('#selectCardModal').modal('hide');
            }, 500)
            $.loading.hide();
        } else {
            Message.warning('请选择您的会员卡！');
            $.loading.hide();
            return;
        }
    }


});


app.controller('checkCardCtrl', function ($scope, $http, $rootScope, $filter, $timeout, Upload) {
    // var getModuleId = localStorage.getItem("moduleId");
    // $rootScope.moduleId = JSON.parse(getModuleId).id;
    $(document).ready(function (){
        // 获取今日时间戳
        $scope.getTodayTimesTamp = function (){
            var timetemps = new Date();
            timetemps.setHours(0);
            timetemps.setMinutes(0);
            timetemps.setSeconds(0);
            timetemps.setMilliseconds(0);
            $scope.todaytimetemps = Date.parse(timetemps)/1000; //获取当前时间戳
        };

        // 请假开始日期插件的js
        $("#dataLeave1").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true//今日按钮
        }).on('changeDate',function (ev){
            $scope.getTodayTimesTamp();
            if($scope.leaveTypeChecked != ''){
                if($(".leaveDateStartInput").val() != ''){
                    var leaveStart = $(".leaveDateStartInput").val() + " " + "00:00:00";
                    $scope.leaveStartTimesTamp = Date.parse(leaveStart)/1000;
                    // if($scope.leaveStartTimesTamp < $scope.todaytimetemps){
                    //     Message.warning("请假开始日期不能小于今天");
                    //     $(".leaveDateStartInput").val("");
                    //     $(".leaveDateEndInput").val("");
                    // }
                }
                else{
                    Message.warning("请选择开始日期");
                    $scope.leaveStartTimesTamp = '';
                }
            }
            else{
                Message.warning("请先选择请假类型");
                $(".leaveDateStartInput").val("");
                $(".leaveDateEndInput").val("");
            }
        });
        // 请假结束日期插件的js
        $("#dataLeaveEnd").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true//今日按钮
        }).on('changeDate',function (ev){
            if($scope.leaveTypeChecked !=  ''){
                if($(".leaveDateEndInput").val() != '' && $(".leaveDateStartInput").val() != ''){
                    var leaveEnd = $(".leaveDateEndInput").val() + " " + "23:59:59";
                    $scope.leaveEndTimesTamp = Date.parse(leaveEnd)/1000;
                    if($scope.leaveStartTimesTamp > $scope.leaveEndTimesTamp){
                        Message.warning("开始日期不能大于结束日期");
                        $scope.leaveStartTimesTamp = '';
                        $scope.leaveEndTimesTamp   = '';
                        $(".leaveDateStartInput").val("");
                        $(".leaveDateEndInput").val("");
                    }
                    // else if($scope.leaveEndTimesTamp < $scope.todaytimetemps){
                    //     Message.warning("请假结束日期不能小于今天");
                    //     $(".leaveDateStartInput").val("");
                    //     $(".leaveDateEndInput").val("");
                    // }
                    else{
                        $scope.mathAroundLeaveDate();
                    }
                }
                else if($(".leaveDateStartInput").val() == ''){
                    Message.warning("请先选择开始日期");
                    $scope.leaveStartTimesTamp = '';
                    $scope.leaveEndTimesTamp   = '';
                    $(".leaveDateEndInput").val("");
                }
                else{
                    Message.warning("请选择结束日期");
                    $scope.leaveEndTimesTamp = '';
                }
            }
            else{
                Message.warning("请先选择请假类型");
                $(".leaveDateStartInput").val("");
                $(".leaveDateEndInput").val("");
            }
        });
    });
    var timestemp = new Date();
    timestemp.setHours(0);
    timestemp.setMinutes(0);
    timestemp.setSeconds(0);
    timestemp.setMilliseconds(0);
    var todayStartTime = Date.parse(timestemp) / 1000;
    $scope.todayTimesTemp = todayStartTime;
    $scope.wwwwwww = $filter('date')(new Date(), 'yyyy-MM-dd');
    $scope.path = function () {
        $scope.MAIN = {
            'API': {
                'memberPath': '/check-card/get-check-card-data?',
                'aboutPath': '/check-card/get-about-class-data?',
                'entryPath': '/check-card/get-member-entry-record-data?',
                'makePath': '/check-card/make-sure-member-card?',
                'viceCardPath': '/check-card/get-vice-cards?'
            }
        };
    };

    $scope.memberPath = function () {
        $scope.MAIN = {
            'MEMBER_PATH': {
                'memberCardPath': '/user/member-card-info?' + "MemberId=" + $scope.memberId +'&type=1',
                'groupPath': '/user/group-class-info?' + "MemberId=" + $scope.memberId,
                'giftPAth': '/user/gift-record-info?' + "MemberId=" + $scope.memberId,
                'cabinetPath': '/cabinet/member-consum-list?cabinetId=&type=&memberId='+$scope.memberId,
                'leavePath': "/user/leave-record-info?MemberId=" + $scope.memberId,
                'consumptionPath': "/user/consumption-info?MemberId=" + $scope.memberId,
                'entryRecordPath': "/member/entry-record-info?MemberId=" + $scope.memberId,
                'memberPath': "/user/member-detail?MemberId=" + $scope.memberId,
                'chargePath': "/user/charge-class-info?MemberId=" + $scope.memberId
            }
        }
    };

    //添加预加载
    $.loading.show();
    //初始化页面数据
    $scope.init = function () {
        $scope.getId();
        $scope.path();
        $scope.getAboutClassData();
        $scope.getAboutAllOneClassData();
        $scope.getViceCards();
        $scope.getMembersData();
        $scope.getEntryData();
        $scope.timeMethod = '';
        $scope.payNoDataShow = true;
        $scope.privateNoDataShow = true;
        $scope.entryTime = "";
        $scope.checkNum = 1;
    };
    angular.element(document).ready(function () {
        $(document).keydown(function (e) {
            if (!e) var e = window.event;
            if (e.keyCode == 32 && $scope.memberData.main_card_status == 1) {
                $scope.path();
                if ($scope.checkNum == 1 && $scope.memberData != null && $scope.memberData != undefined) {
                    $scope.checkNum = $scope.checkNum + 1;
                    $scope.makeSure($scope.memberData.memberCard_id);
                }
            }else if($scope.memberData.main_card_status == 0){
                Message.warning('此副卡主卡未到无法验卡');
            }
            else {

            }
            if ((event.keyCode || event.which) == 27) {
                window.location.href = '/check-card/index?c=33';
            }
            if((event.keyCode || event.which) == 13){
                $scope.printTicket('day');
            }
        });
    });
    //上传头像
    $scope.uploadCover = function (file, text) {
        if (file) {
            if (file.size > 2000000) {
                Message.warning('文件太大不能上传')
            } else {
                $scope.upload(file, text);
            }
        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
        }
    };
    $scope.upload = function (file, text) {
        Upload.upload({
            url: '/class/upload',
            method: 'POST',
            data: {UploadForm: {imageFile: file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                if (text == 'update') {
                    $scope.memberData.pic = result.data.imgLink;
                    var data = {
                        id: $scope.memberData.id,
                        pic: $scope.memberData.pic,
                        _csrf_backend: $('#_csrf').val()
                    }
                    $http({
                        method: 'post',
                        url: "/user/update-member-pic",
                        data: $.param(data),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (data) {
                    }, function (error) {
                        console.log(error)
                        Message.error("系统错误请联系管理人员")
                    })
                } else {
                    $scope.memberData.pic = result.data.imgLink;
                    var data = {
                        id: $scope.memberData.id,
                        pic: $scope.memberData.pic,
                        _csrf_backend: $('#_csrf').val()
                    }
                    $http({
                        method: 'post',
                        url: "/user/update-member-pic",
                        data: $.param(data),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (data) {
                    }, function (error) {
                        console.log(error)
                        Message.error("系统错误请联系管理人员")
                    })
                }
            } else {
                Message.warning(result.data.data);
            }
        });
    };
    //取消预约课程
    $scope.removeAboutClass = function (id) {
        Sweety.remove({
            url: "/check-card/cancel-about-class?id=" + id,
            http: $http,
            title: '确定要取消约课?',
            text: '取消约课后无法恢复',
            confirmButtonText: '确定',
            confirmButton: '取消约课',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getAboutClassData();
        }, function () {

        }, true, true);
    }

    // $scope.removeAboutClass = function (id) {
    //         var url = '/check-card/cancel-about-class?id='+id;
    //         $http({
    //             method: 'GET',
    //             url: url
    //         }).then(function (data) {
    //             console.log(data)
    //             if (data.data.status == "success"){
    //                 Message.success(data.data.data);
    //                 $scope.getAboutClassData();
    //             }
    //             if (data.data.status == "error"){
    //                 Message.warning(data.data.data);
    //                 $scope.getAboutClassData();
    //             }
    //         }, function (error) {
    //             Message.warning('系统错误请联系工作人员');
    //         })
    // }
    $scope.getId = function () {
        $scope.id = $('.dataId').attr('data-id');
    };

    $scope.getAboutClassData = function (item) {
        if(item == undefined || item == '' || item == null){
            item = '';
        }
        $http.get($scope.MAIN.API.aboutPath + 'id=' + $scope.id +'&type='+item ).then(function (result) {
            $scope.aboutDatas = result.data.data;
            if ($scope.aboutDatas == undefined || $scope.aboutDatas == '') {
                $scope.printer = null;
                $scope.aboutNoData = true;
            } else {
                $scope.printer = $scope.aboutDatas[0];
                $scope.aboutNoData = false;
            }
        });
    };
    //获取团课预约配置
    $scope.getOrderSetting = function () {
        $http.get('/new-league/order-setting').then(function (result) {
                $scope.printSetting = result.data.printSettings != '' ? result.data.printSettings : 0;
                //console.log('$scope.printSetting',$scope.printSetting);
        });
    }
    $scope.getOrderSetting();
    //获取全天的预约课程
    $scope.getAboutAllOneClassData = function () {
        $http.get('/check-card/get-about-class-data?id='+ $scope.id +'&type=day').then(function (result) {
            // console.log('result123',result);
            var $len = result.data.data.length;
            if( $len != 0){
                $scope.printerOneAllCourse = result.data.data;
                $scope.aboutNoOneData = false;
            }else{
                $scope.printerOneAllCourse = result.data.data;
                $scope.aboutNoOneData = true;
            }
        });
    };

    $scope.getViceCards = function () {
        $http.get($scope.MAIN.API.viceCardPath + 'id=' + $scope.id).then(function (result) {
            $scope.viceCards = result.data.data;
            if ($scope.viceCards == undefined || $scope.viceCards == '') {
                $scope.printer = null;
                $scope.viceData = false;
            } else {
                $scope.printer = $scope.viceCards[0];
                $scope.viceData = true;
            }
        });
    };

    /****** 打印机start *****/
    //打印单条
    $scope.aboutPrints = function (item) {
        $scope.printer = item;
        $scope.printTicket();
        $scope.allDayCourse = '';
    };
    //end
    $scope.printTicket = function (item) {
        $.loading.show();
        $scope.allDayCourse = item;
        if(item == 'day'){
            if($scope.printerOneAllCourse == undefined || !$scope.printerOneAllCourse ){
                Message.warning('没有预约的课程无法打印');
                return false;
            }
            $scope.getPrintHtml();
        }else{
            if ($scope.printer == undefined || !$scope.printer) {
                Message.warning('没有预约的课程无法打印');
                return false;
            }
            $scope.aboutId = $scope.printer.id;
            $scope.getPrintHtml();
        }
    };
    $scope.getPrintHtml = function () {
        $timeout(function () {
            $.loading.hide();
            if($scope.allDayCourse == 'day'){
                // $scope.getAboutClassData($scope.allDayCourse);
                var open = 1;
                if (open < 10) {
                    var $prints = $('#printsOneDay');
                    var bodyHtml = $('body').html();
                    $scope.bdhtml = $prints.html();//获取当前页的html代码
                    window.document.body.innerHTML = $scope.bdhtml;
                    $scope.printerOneAllCourse.forEach(function(item,index){
                        $scope.aboutId = item.id;
                        $scope.updateAboutStatus();
                    });
                    window.print();
                    window.document.body.innerHTML = bodyHtml;
                    location.replace(location.href);
                } else {
                    window.print();
                }
            }else{
                var open = 1;
                if (open < 10) {
                    var $prints = $('#prints');
                    var bodyHtml = $('body').html();
                    $scope.bdhtml = $prints.html();//获取当前页的html代码
                    window.document.body.innerHTML = $scope.bdhtml;
                    $scope.updateAboutStatus();
                    window.print();
                    window.document.body.innerHTML = bodyHtml;
                    location.replace(location.href);
                } else {
                    window.print();
                }
            }
        }, 100);
    };
    $scope.updateAboutStatus = function () {
        $http.get('/check-card/update-about-print?id=' + $scope.aboutId).then(function (result) {

        });
    };
    /****** 打印机end ******/
    //获取会员卡的请假信息
    $scope.getMemberCardLeaveMessage = function () {
        var id = $('.dataId').attr('data-id');
        $http.get('/check-card/get-check-card?id=' + id).then(function (response) {
            $scope.memberLeaveCardDetail = response.data.data.leaveStatus;
        });
    }
    $scope.getMemberCardLeaveMessage();

    $scope.getMembersData = function () {
        $http.get('/check-card/get-check-card-data?' + 'id=' + $scope.id).then(function (result) {
            // console.log('card:',$scope.leaveType);
            $scope.memberId    = result.data.data.id;
            $scope.leaveType  = result.data.data.leave_type;
            $scope.getLeagueAutoNoFreeze();
            $scope.memberData  = result.data.data;
            $scope.siteReservationList();
            if(result.data.data.absent_times != null){
                $scope.absentTimes = result.data.data.absent_times;
            }
            else{
                $scope.absentTimes = 0;
            }
            $rootScope.backDetailId = $scope.memberData.memberCard_id;

            $scope.memberData.consumption_times = parseInt($scope.memberData.consumption_times);
            $scope.memberData.total_times = parseInt($scope.memberData.total_times);

            $scope.getAllPrivateCourse($scope.memberData.id);
            //十五天的时间戳
            var fifteenTime = parseInt(15 * 24 * 60 * 60);
            //当前时间戳
            var now = new Date().getTime();
            $scope.currentTime = Math.ceil(now / 1000);
            //柜子到期时间
            if ($scope.memberData.cabinet != null) {
                $scope.cabinetExpire = parseInt($scope.memberData.cabinet.memberCabinet.end_rent);
                //柜子到期前第十五天
                $scope.cabinetFifteenDate = $scope.cabinetExpire - fifteenTime;
                //柜子距离到期时间
                $scope.cabinetExpireDay = Math.ceil(($scope.cabinetExpire - $scope.currentTime) / 86400);
                $scope.cabinetExpireDate = $scope.getMyDate($scope.cabinetExpire * 1000);
                $scope.cabinetExpireDateCh = $scope.getMyDateCh($scope.cabinetExpire * 1000);
            }
            $scope.getMyDate($scope.memberData.invalid_time);
            localStorage.setItem('serverData', result.data.data.serverData);
            $scope.expireDate = $scope.getMyDate($scope.memberData.invalid_time * 1000);
            $scope.expireDateCh = $scope.getMyDateCh($scope.memberData.invalid_time * 1000);
            $scope.intervalDay = Math.round($scope.memberData.fewDays);

            //会员卡到期前的第十五天
            $scope.carFifteenDate = $scope.memberData.invalid_time - fifteenTime;
            //失效的时间
            $scope.disabledDate = parseInt($scope.memberData.invalid_time);
            //距离失效的天数
            var intervalExpire = ($scope.memberData.invalid_time - $scope.currentTime) / 86400;
            var b = (intervalExpire + '').split(".");
            var x = b[0];
            var y = b[1];

            $scope.intervalExpire = parseInt(x);
            $scope.intervalExpireHours = Math.round(('0.' + y) * 24);
            localStorage.setItem('member', JSON.stringify({
                memberDataid: $scope.memberData.id, memberCard_id: $scope.memberData.memberCard_id,
                identify: $scope.memberData.identify, venueId: $scope.memberData.venue_id,
                invalid_time: $scope.memberData.invalid_time
            }));
            //取消预加载
            $.loading.hide();
        });
    };
    // 会员卡团课爽约自动解冻
    $scope.getLeagueAutoNoFreeze = function (){
        $http.get("/new-league/card-automatic-thaw?memberId=" + $scope.memberId + "&isRequestMember=isMember").success(function (data){
        });
    };
    //获取私课课程
    $scope.getAllPrivateCourse = function (id) {
        $http.get('/private-teach/expire-time?memberId=' + id).then(function (response) {
            $scope.AllExpirePrivateCourse = response.data;
        });
    }
    $scope.getEntryData = function () {
        $http.get($scope.MAIN.API.entryPath + 'id=' + $scope.id).then(function (result) {
            $scope.entryDatas = result.data.data;
            if ($scope.entryDatas == undefined || $scope.entryDatas == '') {
                $scope.dataInfo = true;
            } else {
                $scope.dataInfo = false;
            }
            $scope.pages = result.data.pages;
        });
    };
    /****卡种分页***/
    $scope.replacementPages = function (urlPages) {
        $scope.MAIN.API.entryPath = urlPages + '&';
        $scope.getEntryData();
    };
    $scope.init();
    $scope.getCheckCardStatus = function (id) {
        $scope.timeMethod = id
    };
    $scope.clickNumFlag = 1;
    $scope.makeSure = function (id) {
        if ($scope.clickNumFlag == 1) {
            $scope.clickNumFlag = $scope.clickNumFlag + 1;
            localStorage.setItem('true', $rootScope.isBOOL);
            if ($scope.timeMethod != undefined || $scope.timeMethod != '') {
                var path = 'id=' + id + '&timeMethod=' + $scope.timeMethod;
            } else {
                path = 'id=' + id
            }
            $http.get($scope.MAIN.API.makePath + path).then(function (result) {
                localStorage.setItem('success', JSON.stringify(result.data.status));
                localStorage.setItem('successw', JSON.stringify(result.data.message));
                if (result.data.status == 'success') {
                    location.href = '/check-card/index?c=33';
                    $(".cardCheckNumberInput").focus();
                } else {
                    location.href = '/check-card/index?c=33';
                    $(".cardCheckNumberInput").focus();
                }
            });
        }
    };
    /**********会员详情开始*****************/
    $scope.getMemberDataCard = function (id, memberCardId) {
        $scope.memberId = id;
        $scope.selectState = ''
        $scope.memberPath();
        $scope.getMemberData(id);
    };

    $scope.getMemberData = function (id) {
        $http.get("/user/member-details-card?MemberId=" + id).then(function (result) {
            $scope.MemberData = result.data;
        });
    };
    /******点击资料选项触发事件*******/
    $scope.getMemberDetail = function (id) {
        $scope.getDetailData(id);
    };

    $scope.myCardClose = function () {
        $('#myCardModals12').modal('hide');
    }
    //卡号交换 数据测试
    $scope.exchangeNumber = function () {
        $http.get('/site/set-exchange-number?numberOne=' + $scope.numberOne + '&numberTwo=' + $scope.numberTwo).then(function (result) {
            $scope.getCardData();
            $scope.getMembersData();
            $("#myCardModals12").css("display", "none");
            $(".modal-backdrop").css("display", "none");
            Message.success(result.data.data);
        });
    };

    /******获取会员详细信息*******/
    $scope.getDetailData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.memberPath).success(function (result) {
        });
    };
    /******获取会员卡信息*******/
    $scope.getMemCard = function () {
        $scope.getCardData();
    };
    $scope.getCardData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.memberCardPath).success(function (response) {
            if (response.item == "" || response.item == undefined || response.item.length <= 0) {
                $scope.items = response.item;
                $scope.memberCardPages = response.pages;
                $scope.memberCardNoDataShow = true;
            } else {
                $scope.items = response.item;
                $scope.memberCardPages = response.pages;
                $scope.memberCardNoDataShow = false;
            }
        });
    };
    $scope.replaceMemberCard = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.memberCardPath = urlPages;
        $scope.getCardData();
    };
    $scope.delMemberCard = function (id) {
        Sweety.remove({
            url: "/user/member-card-del?memberId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getCardData();
        });
    };
    /**
     *后台会员管理 - 会员卡 - 将时间戳转化成固定时间
     * @author HuangHua <houhua@itsports.club>
     * @create 2017/5/12
     */
    $scope.getMyDate = function (str) {
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
    $scope.getMyDateCh = function (str) {
        str = parseInt(str);
        if (str != "" || str != null) {
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth() + 1;
            oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
            var oDay = oDate.getDate();
            oDay = oDay >= 10 ? oDay : '0' + oDay;
            var theDate = oYear + "年" + oMonth + "月" + oDay + "日";
        } else {
            theDate = "";
        }
        return theDate
    };
    /**
     *后台会员管理 - 会员卡 - 查询所有顾问
     * @author HuangHua <huanghua@itsports.club>
     * @create 2017/5/12
     */
    $scope.updateMemCard = function (id, invalidTime, memCardId, memId) {
        //初始化数据
        $scope.adviser = id;
        $scope.memId = memId;
        $scope.memCardId = memCardId;
        var MyDate = $scope.getMyDate(invalidTime * 1000);
        $scope.myDate = MyDate;
        //默认值赋值
        $("#datetimeEnd").val(MyDate);
        $http.get("/user/get-adviser").success(function (response) {
            $scope.allAdviser = response;
        });
    };
    //修改会员卡返回
    $scope.back = function () {
        $("#myModals6").css("display", "none");
        $(".modal-backdrop").css("display", "none");
        $scope.getMembersData();       //更新主页信息
        $scope.getCardData();   //更新分页信息
    };
    //接受数据表单数据 执行修改
    $scope.refer = function () {
        //发送数据请求指令
        $scope.cardData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                memCardId: $scope.memCardId,
                memId: $scope.memId,
                invalidDate: $("#datetimeEnd").val(),
                adviserId: $scope.adviser
            }
        };
        var sendData = $scope.cardData();
        if (sendData.invalidDate == null || sendData.invalidDate == "") {
            Message.warning("失效日期不能为空");
            return false;
        }
        if (sendData.adviserId == "" || sendData.adviserId == null) {
            Message.warning("销售顾问不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/user/update-card",
            method: 'POST',
            data: $.param($scope.cardData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            if (result.status == "success") {
                Message.success(result.data);
                $("#myModals6").css("display", "none");
                $(".modal-backdrop").css("display", "none");
                $scope.getMembersData();       //更新主页信息
                $scope.getCardData();   //更新分页信息
            } else {
                Message.warning(result.data);
            }
        });
    };
    //判断是否点击回车按钮
    $('#myModals').keydown(function (event) {
        if ((event.keyCode || event.which) == 13) {
            // 修改手机号页面中的完成按钮
            $("#submitMobile").click();
        }
    });
    //修改手机号和性别
    $scope.phoneAndSexEdit = function(phone,sex){
        console.log(sex);
        if(sex == 1 || sex == 2 ){
            $scope.memberDefaultSex = sex;
        }else{
            $scope.memberDefaultSex = '';
        }
        $('#myModals').modal('show');
    }
//修改手机号和性别
    $scope.updateMemberInVenue = function(currentVenueId)
    {
        $scope.currentVenueId = currentVenueId;
        $('#myModalsVenue').modal('show');
        $http.get('/site/get-auth-venue').success(function (result) {
            $scope.currentVenue = result;
        });
    }
//点击修改页面的完成触发（修改手机号）
    $scope.updateMobile = function (id) {
        var mobileData = {
            memberMobile: parseInt($('#editMobile').val()),
            memberId: parseInt(id),
            sex:$scope.memberDefaultSex,
            _csrf_backend: $('#_csrf').val(),
        };
        if($scope.memberDefaultSex == '' || $scope.memberDefaultSex == undefined){
            Message.warning('请选择性别');
            return;
        }
        var newMobileLength = $('#editMobile').val().length;
        var oldMobile = $scope.memberData.mobile;
        var $pattern = /^1[34578]\d{9}$/;
        if ($pattern.test(mobileData.memberMobile)) {
            // if (oldMobile != $('#editMobile').val()) {
                //手机号去重
                $http({
                    url: '/user/get-mobile-info?mobile=' + mobileData.memberMobile +'&memberId='+id ,
                    method: 'GET'
                }).then(function (data) {
                    if (data.data.status == 'success') {
                        $http({
                            url: "/check-card/update-mobile",
                            method: 'POST',
                            data: $.param(mobileData),
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        }).success(function (result) {
                            if (result.status == "success") {
                                $('#myModals').modal('hide');
                                $scope.memberData.mobile = mobileData.memberMobile;
                                $scope.memberData.sex = mobileData.sex;
                                Message.success(result.data);
                            } else {
                                Message.warning(result.data);
                            }
                        });
                    } else if (data.data.status == 'error') {
                        Message.warning('手机号已存在');
                    }
                })
            // } else {
            //     Message.warning('手机号未修改');
            // }
        } else {
            Message.warning('请输入正确的手机号');
            return false;
        }
    }
    //点击修改页面的完成触发（修改手机号）
    $scope.updateCurrentVenue = function (id) {
        var mobileData = {
            venueId: parseInt($scope.currentVenueId),
            memberId: parseInt(id),
            _csrf_backend: $('#_csrf').val(),
        };
        if ($scope.currentVenueId == '' || $scope.currentVenueId == undefined) {
            Message.warning('请选择所属场馆');
            return;
        }
        $http({
            url: "/check-card/update-current-venue",
            method: 'POST',
            data: $.param(mobileData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            if (result.status == "success") {
                $('#myModalsVenue').modal('hide');
                $scope.getMembersData();
                Message.success(result.message);
            } else {
                Message.warning(result.message);
            }
        });
    }
    //点击约课按钮   
    $scope.aboutCourse = function (id) {
        if ($scope.memberData.mc_status == 1 && $scope.memberData.nowLeaveStatus.status != 1 && $scope.memberData.status != 2) {
            location.href = '/check-card/course?c=33?id=' + id;
        } else {
            Message.warning('您的卡处于非正常状态，无法进行约课!');
            return;
        }
    }

    //点击请假按钮，传送数据
    $scope.leaveBut = function (id,cardid) {
        $scope.memberLeaveCardId = cardid;
        $scope.leaveTypeChecked  = '';
        $scope.card_id           = '';
        $scope.startLeaveDay     = '';
        $scope.endLeaveDay       = '';
        $scope.endLeaveFlag      = false;
        $scope.leaveData1        = false;
        $scope.leaveData2        = false;
        $scope.card_id           = '';
        $scope.leaveMemberId     = parseInt(id);
        $('#leaveCause').val('');
        $http.get("/user/check-card-details?MemberId=" + $scope.leaveMemberId+'&memberCardId='+$scope.memberLeaveCardId).then(function (result) {
            $scope.MemberData = result.data;
            if ($scope.memberData.mc_status == 1 && $scope.memberData.nowLeaveStatus.status != 1 && $scope.memberData.status != 2 ) {
                $('#myModalsLeave').modal('show');
                //会员卡失效时间
                $scope.invalidTime = parseInt($scope.MemberData.memberCard[0].invalid_time);
                //会员卡生效时间
                $scope.activeTime = parseInt($scope.MemberData.memberCard[0].active_time);
                var now = Math.ceil(new Date().getTime() / 1000);
                $scope.mathAllDays = function (){
                    var mathDay = Math.ceil(($scope.invalidTime - $scope.activeTime) / (60 * 60 * 24));
                    if(!isNaN(mathDay)){
                        return mathDay.toFixed(0);
                    }
                    else {
                        return 0;
                    }
                };
                $scope.allDays = $scope.mathAllDays();
                $scope.limitedDays = Math.ceil(($scope.invalidTime - now) / (60 * 60 * 24));

                //根据返回的数字对应不同的状态
                var len = $scope.MemberData.memberCard.length;
                for (var i = 0; i < len; i++) {
                    var memberState = parseInt($scope.MemberData.memberCard[i].status);
                    switch (memberState) {
                        case 1:
                            $scope.memberFlag = '正常';
                            break;
                        case 2:
                            $scope.memberFlag = '禁用';
                            break;
                        case 3:
                            $scope.memberFlag = '冻结';
                            break;
                        case 4:
                            $scope.memberFlag = '未激活';
                            break;
                    }
                }
            } else {
                Message.warning('此卡处于异常状态或请假状态，不能请假！');
            }
        });
        $scope.selectOneMemberCard();
    };

    //根据不同的时间算出天数
    $scope.getDateDays = function (start, end) {
        if (start != '' && end != '' && $scope.leaveTypeChecked == '2') {
            var startTimes = new Date(start + ' ' + '00:00:00').getTime();
            var endTimes = new Date(end + ' ' + '23:59:59').getTime();
            var daysTime = parseInt(endTimes) - parseInt(startTimes);
            var days = Math.round((endTimes - startTimes) / (24 * 60 * 60 * 1000));
            $scope.leaveDays123 = days;
        }
    };

    //根据请假开始时间获取请假到期时间
    $scope.startLeaveDate = function (starDate) {
        $scope.getDateDays($scope.startLeaveDay, $scope.endLeaveDay);
        $scope.startTime11Date = starDate;
        if ($scope.endLeaveDate111 != undefined && $scope.endLeaveDate111 != '') {
            var endTimes = new Date($scope.endLeaveDate111).getTime();
            var startTimes = new Date($scope.startTime11Date).getTime();
            if (startTimes > endTimes) {
                Message.warning('您选择的请假时间选择有误请重新选择！');
                $scope.endLeaveDay = '';
                $scope.endLeaveDate111 = '';
                return;
            }
        }
        var starLeaveTime = new Date(starDate + ' ' + '00:00:00').getTime();
        $scope.startLeaveDate11 = parseInt(starLeaveTime);
        $scope.startLeaveDateTime = starDate + ' ' + '00:00:00';
        if ($scope.selectLeaveDays != undefined && $scope.selectLeaveDays != '' && $scope.selectLeaveDays != null) {
            var endTime = starLeaveTime + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 - 1000;
            $scope.endLeaveDay = $scope.getMyDate(endTime);
            $scope.endLeaveFlag = true;
        }
    };
    $scope.endLeaveDate = function (endDate) {
        $scope.getDateDays($scope.startLeaveDay, $scope.endLeaveDay);
        $scope.endLeaveDate111 = endDate;
        var endTimes = new Date($scope.endLeaveDate111).getTime();
        var startTimes = new Date($scope.startTime11Date).getTime();
        if (startTimes > endTimes) {
            Message.warning('您选择的请假时间选择有误请重新选择！');
            $scope.endLeaveDay = '';
            return;
        }
    };

    $scope.selectLeaveDaysOne = function (ind) {
        $scope.leaveIndex = ind;
        $scope.leave1 = ind;
        if (ind == '') {
            $scope.startLeaveDay = "";
            $scope.endLeaveDay = "";
            return;
        }
        // if ($scope.leaveStyle.leave_days_times != null) {
        //     var leave = $scope.leaveStyle.leave_days_times[ind];
        //     $scope.selectLeaveDays = leave[0];
        //     // console.log(leave[0])
        //     // console.log('11111',$scope.leaveStyle.leave_days_times[ind])
        //     if ($scope.startLeaveDate11 != null && $scope.startLeaveDate11 != '' && $scope.startLeaveDate11 != undefined && $scope.leaveTypeChecked == '1') {
        //         var endTime = parseInt($scope.startLeaveDate11) + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 -2000;
        //         $scope.endLeaveDay = $scope.getMyDate(endTime);
        //         $scope.endLeaveFlag = true;
        //     }
        //
        // }
        // if($scope.leaveStyle.leave_least_days != null && $scope.leaveStyle.leave_least_days != ' ' && $scope.leaveTypeChecked == '1'){
        //     var days = $scope.leaveStyle.leave_least_days;
        //     var allDays = $scope.leaveStyle.leave_total_days;
        //     if(parseInt(days) <= parseInt(allDays)){
        //         $scope.selectLeaveDays  = parseInt(days);
        //         if($scope.startLeaveDate11 != null && $scope.startLeaveDate11 != '' && $scope.startLeaveDate11 != undefined ){
        //             var endTime =  parseInt($scope.startLeaveDate11) + $scope.selectLeaveDays*24*60*60*1000-1000;
        //             $scope.endLeaveDay =  $scope.getMyDate(endTime);
        //             $scope.endLeaveFlag = true;
        //         }
        //     }else{
        //         Message.warning('请假天数小于可请假天数，请走特殊请假功能');
        //         return;
        //     }
        // }
    };

    //根据不同的时间算出天数
    $scope.getDateDays = function(start,end){
        if(start != '' &&  end !='' && $scope.leaveTypeChecked =='2'){
            var startTimes = new Date(start + ' '+'00:00:00').getTime();
            var endTimes   = new Date(end + ' '+'23:59:59').getTime();
            var daysTime = parseInt(endTimes) - parseInt(startTimes);
            var days = Math.ceil(daysTime/(24*60*60*1000));
            $scope.leaveDays123 = days;
        }
    };

    //选择请假类型
    $scope.selectLeaveType = function(val){
        if(val == '1'){
            $scope.endLeaveFlag = true;
            $scope.leaveTypeCheckedA = '1';
        }else if (val == '2'){
            $scope.endLeaveFlag = false;
            $scope.leaveTypeCheckedA = '2';
            $scope.getDateDays($scope.startLeaveDay,$scope.endLeaveDay);
        }else if(val == '3'){
            $scope.endLeaveFlag = true;
            $scope.leaveTypeCheckedA = '1';
        }
    };
    $scope.selectOneMemberCard = function () {
        $scope.leave1 = "";
        $scope.LeaveDays = '';
        $scope.leaveTotalDays = '';
        $scope.leaveLeastDays = '';
        $scope.endLeaveFlag = false;
        $scope.startLeaveDay = '';
        $scope.endLeaveDay = '';
        $scope.selectLeaveDays = '';
        $scope.endLeaveDateTime = '';
        $scope.leaveTotalDays = '';
        $scope.LeaveDays = '';
        $scope.leaveData1 = false;
        // $scope.cardAttribute='';
        $scope.studentLeaveType='';
        $scope.leaveTypeChecked = '';
        if ($scope.memberLeaveCardId != '') {
            $scope.morenFlag = true;
            $http.get('/user/get-leave-limit?memberCardId=' + $scope.memberLeaveCardId).then(function (response) {
                $scope.leaveStyle = response.data;
                if (response.data.invalid_time != undefined) {
                    $scope.memberCardEndTime = response.data.invalid_time;
                }
                // if (response.data.attributes != undefined) {
                //     $scope.cardAttribute = response.data.attributes;
                // }
                if (response.data.leave_type == null || response.data.leave_type == '' || response.data.leave_type == 1) {
                    $scope.studentLeaveType = response.data.leave_type;
                }
                if ($scope.leaveStyle.leave_days_times != null && $scope.studentLeaveType == null) {
                    $scope.LeaveDays = response.data.leave_days_times;
                    $scope.leaveLimitStatus = 2;
                    $scope.leaveData1 = true;
                    $scope.leaveData2 = false;

                }
                if ($scope.leaveStyle.leave_least_days != null && $scope.leaveStyle.leave_total_days != null && $scope.studentLeaveType == null) {
                    $scope.leaveLeastDays = parseInt(response.data.leave_least_days);
                    $scope.leaveTotalDays = parseInt(response.data.leave_total_days);
                    $scope.leaveLimitStatus = 1;
                    $scope.leaveData1 = true;
                    $scope.leaveData2 = true;
                }
                if ($scope.studentLeaveType == 1 && $scope.leaveStyle.student_leave_limit != null) {
                    $scope.LeaveDays = response.data.student_leave_limit;
                    $scope.leaveLimitStatus = 3;
                    $scope.leaveData2 = true;
                    $scope.leaveData1 = false;
                }
                if ($scope.studentLeaveType == 1 && $scope.leaveStyle.cardStudentLeaveType != null) {
                    $scope.LeaveDays = response.data.cardStudentLeaveType;
                    $scope.leaveLimitStatus = 3;
                    $scope.leaveData2 = true;
                    $scope.leaveData1 = false;
                }
                if ($scope.studentLeaveType == 1 && $scope.leaveStyle.student_leave_limit == null && $scope.leaveStyle.cardStudentLeaveType == null) {
                    Message.warning('请设置学生请假天数!');
                    return;
                }
            })
        } else {
            $scope.leaveData1 = false;
            $scope.leaveData2 = false;
        }

    };

    //请假结束时间变化
    $scope.endLeaveDate = function (endLeaveDay) {
        $scope.getDateDays($scope.startLeaveDay,$scope.endLeaveDay);
        if ($scope.leaveStyle == undefined) {

        } else {
            var endTime = new Date(endLeaveDay).getTime() / 1000;
            if (endTime > parseInt($scope.memberCardEndTime)) {
                Message.warning('请假的结束日期不能大于会员卡的到期日期！');
                return;
            }
        }

    };

    //实现请假功能
    //    会员卡状态：1、正常 2、冻结 3、过期 4、未激活
    $scope.submitLeave = function () {
        var leaveData = {
            leaveType    :$scope.leaveTypeChecked != undefined && $scope.leaveTypeChecked != '' ? $scope.leaveTypeChecked : null,
            leavePersonId: parseInt($scope.leaveMemberId),
            leaveReason: $('#leaveCause').val(),
            _csrf_backend: $('#_csrf').val(),
            leaveStartTime: $scope.leaveStartTimesTamp,
            leaveEndTime: $scope.leaveEndTimesTamp,
            leaveTotalDays: $scope.leaveAllDays,
            leaveArrayIndex: $scope.leaveIndex != undefined && $scope.leaveIndex != '' ? $scope.leaveIndex : null,
            leaveLimitStatus: $scope.leaveLimitStatus,
            memberCardId: $scope.memberLeaveCardId
        };
        if ($scope.leaveTypeChecked == '' || $scope.leaveTypeChecked == null || $scope.leaveTypeChecked == undefined) {
            Message.warning('请选择请假类型');
            return;
        }

        if (leaveData.memberCardId == '' || leaveData.memberCardId == null || leaveData.memberCardId == undefined) {
            Message.warning('请选择您的会员卡');
            return;
        }
        if ($scope.leaveStartTimesTamp == '' || $scope.leaveStartTimesTamp == undefined || $scope.leaveStartTimesTamp == '') {
            Message.warning('请选择请假开始时间');
            return;
        }
        // if($scope.leaveTypeChecked == '1'){
        //     if($scope.leaveAllDays > $scope.leaveTotalDays){
        //         Message.warning('请假时间过长，请重新选择');
        //         return;
        //     }
        //     else if($scope.leaveAllDays < $scope.leaveLeastDays){
        //         Message.warning('请假时间过短，请重新选择');
        //         return;
        //     }
        // }
        if ($scope.leaveEndTimesTamp == '' || $scope.leaveEndTimesTamp == undefined || $scope.leaveEndTimesTamp == '') {
            Message.warning('请选择请假结束时间');
            return;
        }
        $scope.leavePost = function (){
            $scope.laddaButton = true;
            $http({
                url: '/check-card/leave-record',
                method: 'POST',
                data: $.param(leaveData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result) {
                if (result.data.status == "success") {
                    $('#myModalsLeave').modal('hide');
                    Message.success(result.data);
                    $scope.memberPath();
                    $scope.getLeaveData();
                    $scope.laddaButton = false;
                    $scope.getMemberCardLeaveMessage();
                    $scope.getMembersData();
                    $scope.getLeaveData();
                } else {
                    Message.warning(result.data.message);
                    $scope.laddaButton = false;
                }
            });
        };
        $scope.leavePost();
    };

    // 计算正常请假时长
    $scope.mathAroundLeaveDate = function (){
        $scope.leaveAllDays = (($scope.leaveEndTimesTamp - $scope.leaveStartTimesTamp)/24/60/60).toFixed(0);
        if(!isNaN($scope.leaveAllDays)){
            $(".leaveAllDaysSpans").text($scope.leaveAllDays);
        }
        else{
            $scope.leaveAllDays = 0;
        }
    };

    /******点击私课触发事件*******/
    $scope.getChargeClass = function (id) {
        $scope.getChargeClassData(id);
    };
    /******获取私课表信息*******/
    $scope.getChargeClassData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.chargePath).success(function (response) {
            if (response.charge == undefined || response.charge == '') {
                $scope.privateNoDataShow = true;
            } else {
                $scope.privateNoDataShow = false;
            }
            $scope.charges = response.charge;
            $scope.privatePages = response.pages;
        });
    };
    $scope.chargeClass = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.chargePath = urlPages;
        $scope.getChargeClassData();
    };
    /******点击私课触发详细事件*******/
    $scope.getChargeClassDetail = function (id, charge_id, name) {
        $scope.getChargeData(id, charge_id);
        $scope.productName = name;
    };
    /******获取私课上课记录表数据*******/
    // $scope.getChargeData = function (id,charge_id) {
    //     $http.get("/user/class-record-info?MemberId="+$scope.id+'&charge_id='+charge_id).success(function(response) {
    //         $scope.records   =  response.record;
    //         console.log('私课上课详情',$scope.records);
    //     });
    // };
    $scope.getChargeData = function (id, charge_id) {
        $http.get("/user/class-record-info?MemberId=" + $scope.memberId + '&charge_id=' + charge_id).success(function (response) {
            $scope.records = response.record;
        });
    };
    /******点击请假选项触发事件*******/
    $scope.getLeaveRecord = function (id) {
        $scope.getLeaveData(id);
    };
    /******获取请假表信息*******/
    $scope.getLeaveData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.leavePath).success(function (response) {
            if (response.vacate == undefined || response.vacate == '') {
                $scope.leaveNoDataShow = true;
            } else {
                $scope.leaveNoDataShow = false;
            }
            $scope.vacates = response.vacate;
            $scope.leavePages = response.pages;
        });
    };
    $scope.replaceLeavePage = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.leavePath = urlPages;
        $scope.getLeaveData();
    };
    /*****点击消除请假*****/
    $scope.removeLeave = function (id, status) {
        if (status == 1) {
            Sweety.remove({
                url: "/check-card/del-leave-record?id=" + id,
                http: $http,
                title: '确定要消除请假吗?',
                text: '消除请假后信息无法恢复',
                confirmButtonText: '确定',
                confirmButton: '消假',
                data: {
                    action: 'unbind'
                }
            }, function () {
                $scope.getMemberCardLeaveMessage();
                $scope.getMembersData()
                $scope.getLeaveData();
            }, function () {

            }, true, true);
        } else {
            return;
        }

    };

    $scope.removeMemberLeave = function (id) {
        Sweety.remove({
            url: "/check-card/del-leave-record?id=" + id,
            http: $http,
            title: '确定要消除请假吗?',
            text: '消除请假后信息无法恢复',
            confirmButtonText: '确定',
            confirmButton: '消假',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getMemberCardLeaveMessage();
            $scope.getMembersData();
        }, function () {

        }, true, true);
    }

    /******点击团课选项触发事件*******/
    $scope.getGroupClass = function (id) {
        $scope.getGroupClassData(id);
    };
    /******获取团课表信息*******/
    $scope.getGroupClassData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.groupPath).success(function (response) {
            if (response.group == undefined || response.group == '') {
                $scope.groupNoDataShow = true;
            } else {
                $scope.groupNoDataShow = false;
            }
            $scope.groups = response.group;
            $scope.groupPages = response.pages;
        });
    };
    $scope.replaceGroupPage = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.groupPath = urlPages;
        $scope.getGroupClassData();
    };
    /******点击消费选项触发事件*******/
    $scope.getHistory = function (id) {
        $scope.getHistoryData(id);
    };
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    //消费记录排序
    $scope.recordsOfConsumption = function (type, sort) {
        $scope.recordsOfConsumptionType = type;
        $scope.switchSort(sort);
        $scope.getHistoryData()
    }
    $scope.recordsOfConsumptionData = function () {
        return {
            memberId: $scope.memberId,
            sortType: $scope.recordsOfConsumptionType,
            sortName: $scope.sort
        }
    }
    /******获取消费记录表信息*******/
    $scope.getHistoryData = function () {
        var data = $scope.recordsOfConsumptionData();
        $http.get('/user/consumption-info?' + $.param(data)).success(function (response) {
            if (response.expense == undefined || response.expense == '') {
                $scope.payNoDataShow = true;
            } else {
                $scope.payNoDataShow = false;
            }
            $scope.expenses = response.expense;
            $scope.payPages = response.pages;
        });
    };
    $scope.replacePayPage = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.consumptionPath = urlPages;
        $scope.getHistoryData();
    };
    /******点击柜子选项触发事件*******/
    $scope.getCabinet = function (id) {
        $scope.getCabinetData(id);
    };
    /******获取柜子表信息*******/
    $scope.getCabinetData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.cabinetPath).then(function (response) {
            $scope.cabinets = response.data.data;
            $scope.cabinetPages = response.data.page;
            if ($scope.cabinets.length > 0) {
                $scope.cabinetNoDataShow = false;
            } else {
                $scope.cabinetNoDataShow = true;
            }
        });
    };
    $scope.replaceCabinetPages = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.cabinetPath = urlPages;
        $scope.getCabinetData();
    };
    /******点击赠品选项触发事件*******/
    $scope.getGift = function (id) {

        //调用开始日期
        $("#datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            startDate: '2008-08-08'
        }).on("click", function () {
            $("#datetimeStart").datetimepicker("setEndDate", $("#datetimeEnd").val());
        });

        $scope.getGiftRecordData(id);
        $scope.searchParams = '';
        $scope.InitUrl = '&entryTime=';
        $scope.getEntryRecordData(id);
// 到场离场记录日期插件调用
        $("#datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        }).on('changeDate', function(ev){
            $scope.backListTime = $("#datetimeStart").val();
            $scope.InitUrl      = '&entryTime=' + $scope.backListTime;
            $scope.getEntryRecordData();
        });
        // 清空到场离场记录
        $scope.initBackDateTimeInfo = function (){
            $("#datetimeStart").val("");
            $scope.backListTime = "";
            $scope.InitUrl      = '&entryTime=' + $scope.backListTime;
            $scope.getEntryRecordData();
        };
        var url = '/user/information-records?memberId=' + $scope.memberId;
        $http.get(url).then(function (response) {
            if (response.data.data.length != 0) {
                $scope.behaviorRecordLists = response.data.data;
                $scope.behaviorRecordPages = response.data.page;
                $scope.behaviorRecordFlag = false;
            } else {
                $scope.behaviorRecordLists = response.data.data;
                $scope.behaviorRecordPages = response.data.page;
                $scope.behaviorRecordFlag = true;
            }
        });
    };

    //获取会籍变更记录
    $scope.getSellChangeRecords = function () {
        $.loading.show();
        $http.get('/member/consultant-change?memberId=' + $scope.memberId).then(function (response) {
            if(response.data.data.length === 0) {
                $scope.consultantChangeRecordNoData = true;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }else {
                $scope.consultantChangeRecordNoData = false;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }
            $.loading.hide();
        })
    };
    //会籍变更记录的分页
    $scope.consultantPages = function (url) {
        $.loading.show();
        $http.get(url).then(function (response) {
            if(response.data.data.length === 0) {
                $scope.consultantChangeRecordNoData = true;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }else {
                $scope.consultantChangeRecordNoData = false;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }
            $.loading.hide();
        })
    };
    $scope.depositMoneyShow = false;
    /*** 信息记录的改变事件 ***/
    $scope.SelectMessage = function (value){
        if(value == '3'){
            $scope.getMemberSendCardRecord();
        }
        if(value == '4'){
            $scope.getDelayPrivateRecord();
        }
        if(value == '5'){
            $scope.getGiftDaysInfoRecond();
        }
        if(value == '6') {
            $scope.depositSelect = '';
            $scope.depositAllMoney = '';
            $scope.depositSelectChange($scope.depositSelect);
            $scope.depositMoneyShow = true;
        }else {
            $scope.depositMoneyShow = false;
        }
        if(value == '7') {
            $scope.getSellChangeRecords();
        }
    };
    /***
     *
     * 
     */
    $scope.depositNoDataShow = false;
    $scope.getDepositAllMoney = function() {
        if($scope.depositSelect == undefined || $scope.depositSelect == null) {
            $scope.depositSelect = '';
        }
        $http.get('/member/member-deposit-list?memberId='+ $scope.memberId +'&type='+ $scope.depositSelect).then(function (data) {
            if(data != null && data !=undefined && data != '') {
                $scope.getDepositInfoData = data.data.deposit;
                if($scope.getDepositInfoData.length == 0) {
                    $scope.depositAllMoney = 0;
                    $scope.depositNoDataShow = true;
                }else {
                    $scope.depositAllMoney = data.data.allPrice;
                    $scope.depositNoDataShow = false;
                }
            }
        })
    };
    //订金的信息记录
    $scope.depositSelectChange = function (val) {
        $scope.depositSelect = val;
        $scope.getDepositAllMoney();

    };
    /******获取赠品表信息*******/
    $scope.getGiftRecordData = function () {
        $scope.getGiftUrl = '/user/gift-record-info?memberId=' + $scope.id;
        $http.get($scope.getGiftUrl).success(function (response) {
            if (response.gift == undefined || response.gift == null || response.gift == "") {
                $scope.giftNoDataShow = true;
                $scope.giftList = response.gift;
                $scope.pages = response.pages;
            }
            else {
                $scope.giftNoDataShow = false;
                $scope.giftList = response.gift;
                $scope.pages = response.pages;
            }
        });
    };
    $scope.replaceGiftPage = function (urlPages) {
        $scope.getGiftUrl = urlPages;
        $scope.getGiftRecordData();
    };

    // 点击领取赠品
    $scope.receiveGift = function (id) {
        $scope.giftIdReceive = id;
        $http.get("/user/update-gift-status?id=" + $scope.giftIdReceive).success(function (data) {
            if (data.status == "success") {
                Message.success(data.data);
                $scope.getGiftRecordData();
            } else {
                Message.warning(data.data);
            }

        })
    };
    /*** 获取送人记录 ***/
    // 获取潜在会员送人卡信息记录
    $scope.getMemberSendCardRecord = function () {
        $http.get("/user/get-member-send-record?memberId=" + $scope.id).success(function (data) {
            $scope.memberSendCardList = data.data;
            if ($scope.memberSendCardList.length == 0) {
                $scope.payNoSendCardRecordDataShow = true; //暂无数据图像显示
            }
            else {
                $scope.payNoSendCardRecordDataShow = false; //暂无数据图像关闭
            }
        });
    };
    /*** 获取私课延期记录 ***/
    $scope.getDelayPrivateRecord = function (){
        $http.get("/member/extension-record-info?memberId=" + $scope.id).success(function (data){
            $scope.delayPrivateRecordList = data.extension;
            if($scope.delayPrivateRecordList.length != 0){
                $scope.priDelayNoDataShow = false;
            }
            else{
                $scope.priDelayNoDataShow = true;
            }
        });
    };
    /*** 获取赠送天数记录 ***/
    // 获取赠送天数记录
    $scope.getGiftDaysInfoRecond = function (){
        $http.get("/member/give-day-info?memberId=" + $scope.id).success(function (data){
            if(data.data != '' && data.data != null && data.data != [] && data.data != undefined){
                $scope.giftDaysInfoRecondData = data.data;
                $scope.giftNoDataInfoHaShow   = false;
            }
            else{
                $scope.giftDaysInfoRecondData = data.data;
                $scope.giftNoDataInfoHaShow   = true;
            }
        });
    };
    /******点击进场选项触发事件*******/
    // $scope.getEntryRecord = function(id){
    //     $scope.searchParams = '';
    //     $scope.InitUrl      = '&entryTime=';
    //     $scope.getEntryRecordData(id);
    // };
    /******获取进场表信息*******/
    $scope.getEntryRecordData = function () {
        $http.get($scope.MAIN.MEMBER_PATH.entryRecordPath + $scope.InitUrl).success(function (response) {
            if (response.entry == undefined || response.entry == '') {
                $scope.entryNoDataShow = true;
            } else {
                $scope.entryNoDataShow = false;
            }
            $scope.entrys = response.entry;
            $scope.entryPages = response.pages;
            $scope.count = response.count;
        });
    };
    $scope.replaceEntryPages = function (urlPages) {
        $scope.MAIN.MEMBER_PATH.entryRecordPath = urlPages + $scope.InitUrl;
        $scope.getEntryRecordData();
    };
    /**处理搜索到场数据***/
    $scope.searchEntryData = function () {
        return {
            entryTime: $scope.entryTime != undefined ? $scope.entryTime : null
        }
    };
    $scope.initPaths = function () {

        $scope.searchParams = $scope.searchEntryData();
        if ($scope.searchParams != '' && $scope.searchParams != undefined) {
            $scope.InitUrl = '&entryTime=' + $scope.searchParams.entryTime;
        }
    };
    /**搜索到场方法***/
    $scope.searchEntry = function () {
        $scope.initPaths();
        $scope.getEntryRecordData();
    };


    /**************************场地预约****************************/

    $scope.siteReservationList = function () {
        $http({
            method: 'get',
            url: '/site-management/get-yard-about-record?cardNum=' + $scope.memberData.card_number
        }).then(function (data) {
            if(data.data.data != ''){
                $scope.siteReservationListDataBool = false
            }
            if(data.data.data == ''){
                $scope.siteReservationListDataBool = true;
            }
            $scope.siteReservationListData = data.data;

        }, function (error) {
            Message.error("系统错误请联系工作人员");
        })
    }

    $scope.listDataItems = [];
    $scope.fieldModals = function () {

        if($scope.listDataItems != ''){
            $scope.listDataItems.splice(0,$scope.listDataItems.length)
        }
        if ($scope.memberData.mc_status == 1 && $scope.memberData.nowLeaveStatus.status != 1 && $scope.memberData.status != 2) {
            $("#fieldModals").modal('show');
            $http({method: 'get', url: '/site-management/get-venue-yard-page'}).then(function (data) {
                $scope.initYardPageId = data.data.data[0].id;
                $scope.page = data.data.nowPage + 1;
                $scope.totalPage = data.data.totalPage;
                for (var key in data.data.data) {
                    $scope.listDataItems.push(data.data.data[key])
                }
                $scope.selectSiteManagement($scope.initYardPageId, 0)
            }, function (error) {
                console.log(error);
                Message.error("系统错误请联系工作人员");
            });
        } else {
            Message.warning('您的卡处于非正常状态，无法进行约课!');
            return;
        }
        $('.SiteManagement').eq(0).addClass("cursor")
        $('.venueDateStartInput').val('');

    };
    //监听滚动条加载,
    $("#contain").scroll(function () {
        var $this = $(this),
            viewH = $(this).height(),
            contentH = $(this).get(0).scrollHeight,
            scrollTop = $(this).scrollTop();
        if (scrollTop / (contentH - viewH) >= 0.95) {
            $scope.page = $scope.page + 1;
            if ($scope.page > $scope.totalPage) {
                $scope.page = $scope.totalPage;
                return;
            }
            if ($scope.page <= $scope.totalPage) {
                $http({
                    method: 'get',
                    url: '/site-management/get-venue-yard-page?page=' + $scope.page
                }).then(function (data) {
                    $scope.page = data.data.nowPage + 1;
                    $scope.totalPage = data.data.totalPage;
                    for (var key in data.data.data) {
                        $scope.listDataItems.push(data.data.data[key])
                    }
                }, function (error) {
                    Message.error("系统错误请联系工作人员");
                })
            }
        }
    });

    //选择场地
    $scope.selectSiteManagement = function (id, i) {
        if($('.venueDateStartInput').val() == ''){
            $scope.venueTime =  $scope.getNowFormatDate();
        }else{
            $scope.venueTime = $scope.startVenue;
        }
        $scope.initYardPageId = id;
        $scope.initYardPageNumber = i;
        $http({
            method: 'get',
            url: '/site-management/yard-detail?yardId='+ $scope.initYardPageId + "&memberAboutDate=" + $scope.venueTime + "&cardNumber=" + $scope.memberData.card_number
        }).then(function (data) {
            if (data.data.data.params != '') {
                $scope.siteDetailsLeft = data.data.data;
                $scope.dataParams = false;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
        if($('.venueDateStartInput').val() == ''){
            $scope.venueTime =  $scope.getNowFormatDate();
        }else{
            $scope.venueTime = $scope.startVenue;
        }
        //判断次卡能不能预约此场地
        $http({
            method: 'get',
            url: '/site-management/is-about-yard?yardId=' + id + "&cardNum=" + $scope.memberData.card_number +"&memberAboutDate=" + $scope.venueTime　
        }).then(function (data) {
            if(data.data.status == true){
                $scope.isAboutYardStatus = false;
            }else {
                $scope.isAboutYardStatus = true;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
        $(".SiteManagement").removeClass("cursor")
        $(".SiteManagement").eq(i).addClass("cursor")
    };
    //预约场地时间插件的好js
    $('#dataLeave12').datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true//今日按钮
    }).on('changeDate',function(ev){
        $scope.getTodayTimesTamp();
        if($('.venueDateStartInput').val() != ''){
            $scope.startVenue = $('.venueDateStartInput').val();
            $scope.selectSiteManagement($scope.initYardPageId, $scope.initYardPageNumber);
        }
    })
    //取消预约
    $scope.cancelReservation = function (id, num, key) {
        $scope.aboutIntervalSection = key;
        if($('.venueDateStartInput').val() == ''){
            $scope.venueTime =  $scope.getNowFormatDate();
        }else{
            $scope.venueTime = $scope.startVenue;
        }
        var d = $scope.venueTime + ' ' + $scope.aboutIntervalSection.substr(0, 5)
        if (d < $scope.venueTime) {
            Message.warning("当前时间已经过期，不能取消预约!");
            return;
        } else {
            Sweety.remove({
                url: "/site-management/deal-yard-about-class?yardId="+$scope.initYardPageId+"&memberAboutDate="+$scope.venueTime+"&cardId="+$scope.siteReservationListData.cardId+"&intervalSection="+key,
                http: $http,
                title: '确定要取消预约吗?',
                text: '取消后所有信息无法恢复',
                confirmButtonText: '确定',
                data: {
                    action: 'unbind'
                }
            }, function () {
                if (num == 1) {
                    if($('.venueDateStartInput').val() == ''){
                        $scope.venueTime =  $scope.getNowFormatDate();
                    }else{
                        $scope.venueTime = $scope.startVenue;
                    }
                    $http({
                        method: 'get',
                        url: '/site-management/yard-detail?yardId=' + $scope.initYardPageId + "&memberAboutDate=" + $scope.venueTime + "&cardNumber=" + $scope.memberData.card_number
                    }).then(function (data) {
                        if (data.data.data.params != '') {
                            $scope.siteDetailsLeft = data.data.data;
                            $scope.dataParams = false;
                        }
                    }, function (error) {
                        console.log(error);
                        Message.error("系统错误请联系工作人员");
                    });
                }
                if (num == 2) {
                    if($('.venueDateStartInput').val() == ''){
                        $scope.venueTime =  $scope.getNowFormatDate();
                    }else{
                        $scope.venueTime = $scope.startVenue;
                    }
                    $http({
                        method: 'get',
                        url: '/site-management/get-about-data-detail?memberAboutDate=' + $scope.venueTime + "&aboutIntervalSection=" + $scope.aboutIntervalSection + "&yardId=" + $scope.initYardPageId
                    }).then(function (data) {
                        if (data.data.data != "" || data.data.data != undefined) {
                            $scope.selectionTimeList = data.data.data;
                            $scope.detailPages = data.data.pages;
                            $scope.detailInfos = false;
                            $scope.nowPage = data.data.nowPage;
                        }
                        if (data.data.data == "") {
                            $scope.selectionTimeList = data.data.data;
                            $scope.detailPages = data.data.pages;
                            $scope.detailInfos = true;
                        }
                        $scope.siteReservationList();
                        $scope.selectSiteManagement($scope.initYardPageId, $scope.initYardPageNumber);
                    }, function (error) {
                        console.log(error);
                        Message.error("系统错误请联系工作人员");
                    })
                }
                $scope.siteReservationList();
            }, function () {
            }, true);
        }
    }
    $scope.removeDataSite = function (yardId,cardId,key) {
        Sweety.remove({
            url: "/site-management/cancel-yard-about-class?id="+yardId,
            http: $http,
            title: '确定要取消预约吗?',
            text: '取消后所有信息无法恢复',
            confirmButtonText: '取消',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.siteReservationList();
        }, function () {
        }, true);
    }
    $scope.memberAboutDetail = function (urlPages) {
        $http({method:'get',url:urlPages}).then(function (data) {
            if (data.data.data != "" || data.data.data != undefined ) {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = false;
                $scope.nowPage      = data.data.nowPage;
            } else {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = true;
            }
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    }
    //详情数据
    $scope.siteManagementDetails = function (key, start) {
        $scope.aboutIntervalSection = key;
        if($('.venueDateStartInput').val() == ''){
            $scope.venueTime =  $scope.getNowFormatDate();
        }else{
            $scope.venueTime = $scope.startVenue;
        }
        // if (start == 1) {
        //     $scope.valueTimeStatus = true;
        // };
        // if (start == 2) {
        //     $scope.valueTimeStatus = false;
        // };
        $http({
            method: 'get',
            url: '/site-management/get-about-data-detail?memberAboutDate=' + $scope.venueTime + "&aboutIntervalSection=" + key + "&yardId=" + $scope.initYardPageId
        }).then(function (data) {
            if (data.data.data != "" || data.data.data != undefined) {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = false;
                $scope.nowPage = data.data.nowPage;
            };
            if (data.data.data == "") {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = true;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };

    //预约场地
    $scope.siteReservation = function (key,num) {
        if($('.venueDateStartInput').val() == ''){
            $scope.venueTime =  $scope.getNowFormatDate();
        }else{
            $scope.venueTime = $scope.startVenue;
        }
        $scope.aboutIntervalSection = key;
        var data = {
            yardId: $scope.initYardPageId,
            memberId: $scope.memberId,
            memberCardId: $scope.siteReservationListData.cardId,
            aboutIntervalSection: $scope.aboutIntervalSection,
            aboutDate: $scope.venueTime,
        };
        $http({
            method: 'post',
            url: '/site-management/member-yard-about',
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (success) {
            if (success.data.status == "success") {
                Message.success(success.data.data);
            }
            if (success.data.status == "error") {
                Message.warning(success.data.data);
                return;
            }
            if(num == 2){
                if($('.venueDateStartInput').val() == ''){
                    $scope.venueTime =  $scope.getNowFormatDate();
                }else{
                    $scope.venueTime = $scope.startVenue;
                }
                $http({
                    method: 'get',
                    url: '/site-management/get-about-data-detail?memberAboutDate=' + $scope.venueTime + "&aboutIntervalSection=" + $scope.aboutIntervalSection + "&yardId=" + $scope.initYardPageId
                }).then(function (data) {
                    if (data.data.data != "" || data.data.data != undefined) {
                        $scope.selectionTimeList = data.data.data;
                        $scope.detailPages = data.data.pages;
                        $scope.detailInfos = false;
                        $scope.nowPage = data.data.nowPage;
                    }
                    if (data.data.data == "") {
                        $scope.selectionTimeList = data.data.data;
                        $scope.detailPages = data.data.pages;
                        $scope.detailInfos = true;
                    }
                })
            }
            $scope.siteReservationList();
            $scope.selectSiteManagement($scope.initYardPageId, $scope.initYardPageNumber);
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };


    //打印单条 
    $scope.aboutPrintsField = function (printer) {
        $scope.printerField = printer;
        $scope.newDate =$scope.getNowFormatDates()
        if(printer == undefined || !printer){
            Message.warning('没有预约的场地无法打印');
            return false;
        }
        $timeout(function () {
            $.loading.hide();
            var open = 1;
            if (open < 10) {
                var $prints = $('#field');
                var bodyHtml = $('body').html();
                var bdhtml = $prints.html();//获取当前页的html代码
                window.document.body.innerHTML = bdhtml;
                window.print();
                window.document.body.innerHTML = bodyHtml;
                location.replace(location.href);
            } else {
                window.print();
            }
        }, 100);
        // }
    }
    $scope.getNowFormatDate = function () {
        var date = new Date();
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var hour = date.getHours();
        var minute = date.getMinutes();
        var second = date.getSeconds();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate
        return currentdate;
    }
    $scope.getNowFormatDates = function () {
        var date = new Date();
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var hour = date.getHours();
        var minute = date.getMinutes();
        var second = date.getSeconds();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var hour = date.getHours();
        if (hour >= 1 && hour <= 9) {
            hour = "0" + hour;
        }
        var minute = date.getMinutes();
        if (minute >= 1 && minute <= 9) {
            minute = "0" + minute;
        }
        var second = date.getSeconds();
        if (second >= 1 && second <= 9) {
            second = "0" + second;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate + " " + hour + ":" + minute;
        return currentdate;
    }
    /****************end****************/
})




