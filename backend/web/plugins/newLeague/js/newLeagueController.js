/**
 * 约课列表页
 * @type {string} 2017-5-19 苏雨
 */

angular.module('App').controller('leagueCtrl', function ($scope, $http, Upload,$timeout) {

    /****************************** 团课表***************************/
    //初始化场馆数据 场馆 教练 课程 教室
    $scope.initVenue = function () {
        $http({
            url: '/site/get-auth-venue',
            method: 'get'
        }).then(function (data) {
            $scope.venueAll = data.data;
            //默认选中
            $scope.venueId = $scope.venueAll[0].id;
            //选择教室
            $scope.chooseAClassroomSearch($scope.venueId);

            $scope.courseTimeStamp = new Date().getTime();
            //select课程
            $http({
                method: 'get',
                url: "/new-league/get-course"
            }).then(function (data) {
                $scope.choiceCourseNames = data.data;
            }, function (error) {
                console.log(error);
                Message.error('系统错误请联系工作人员');
            });
            //select 教练
            $http({
                method: 'get',
                url: '/new-league/get-coach',
            }).then(function (success) {
                $scope.ChangeCoach = success.data;
            }, function (error) {
                console.log(error);
                Message.error('系统错误请联系工作人员');
            });
            $scope.initDatas();
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    $scope.initVenue();
    // 课程表 表头搜索数据
    $scope.searchLeagueData = function () {
        if ($scope.isBOOLDate == 1) {
            return {
                classroomId: $scope.classRoomId != undefined ? $scope.classRoomId : null,
                organizationId: $scope.venueId != undefined ? $scope.venueId : null,               //场馆搜索
                courseId: $scope.courseId != undefined ? $scope.courseId : null,           //课程搜索
                startCourse: $scope.timeLostFocusStart != undefined ? $scope.timeLostFocusStart : null,            //课程未开始
                endCourse: $scope.timeLostFocusEnd != undefined ? $scope.timeLostFocusEnd : null,
                coachId: $scope.newChangeCoachIds != undefined ? $scope.newChangeCoachIds : null,
            };
        };
        if ($scope.isBOOLDate == 2) {
            $scope.dataS = new Date($scope.dateSta1).toLocaleDateString();
            $scope.dataE = new Date($scope.dateSta2).toLocaleDateString();
            return {
                classroomId: $scope.classRoomId != undefined ? $scope.classRoomId : null,
                organizationId: $scope.venueId != undefined ? $scope.venueId : null,               //场馆搜索
                courseId: $scope.courseId != undefined ? $scope.courseId : null,           //课程搜索
                startCourse: $scope.timeLostFocusStart != undefined ? $scope.timeLostFocusStart : null,            //课程未开始
                endCourse: $scope.timeLostFocusEnd != undefined ? $scope.timeLostFocusEnd : null,
                weekStart: $scope.dataS != undefined ? $scope.dataS : null,
                weekEnd: $scope.dataE != undefined ? $scope.dataE : null,
                coachId: $scope.newChangeCoachIds != undefined ? $scope.newChangeCoachIds : null,
            };
        };
    };
    //课程搜索
    $scope.courseClassification = function (id) {
        $scope.courseId = id;
        if($scope.sortClassType==1){
            //调用搜索主页参数
            $scope.templateData();
            $scope.searchDateCourse($scope.searchDate,$scope.chinaDate);
            return false;
        }
        var url = '/new-league/group-class?' + $.param($scope.searchLeagueData());
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            if ($scope.isBOOLDate == 1) {
                $scope.listData = data.data;
            };
            if ($scope.isBOOLDate == 2) {
                $scope.listDataw = data.data;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //教室搜索
    $scope.newClassroom = function (id) {
        $scope.classRoomId = id;
        if($scope.sortClassType==1){
            //调用搜索主页参数
            $scope.templateData();
            $scope.searchDateCourse($scope.searchDate,$scope.chinaDate);
            return false;
        }
        $http({
            method: 'get',
            url: '/new-league/group-class?' + $.param($scope.searchLeagueData())
        }).then(function (data) {
            if ($scope.isBOOLDate == 1) {
                $scope.listData = data.data;
            };
            if ($scope.isBOOLDate == 2) {
                $scope.listDataw = data.data;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //教练搜索
    $scope.newChangeCoach = function (id) {
        if($scope.sortClassType==1){
            //调用搜索主页参数
            $scope.templateData();
            $scope.searchDateCourse($scope.searchDate);
            return false;
        }
        $http({
            method: 'get',
            url: '/new-league/group-class?' + $.param($scope.searchLeagueData())
        }).then(function (data) {
            if ($scope.isBOOLDate == 1) {
                $scope.listData = data.data;
            };
            if ($scope.isBOOLDate == 2) {
                $scope.listDataw = data.data;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    // 公共 按周表头搜索
    $scope.publicWeekSearch = function () {
        $http({
            method: 'get',
            url: '/new-league/group-class?' + $.param($scope.searchLeagueData())
        }).then(function (data) {
            if ($scope.isBOOLDate == 1) {
                $scope.listData = data.data;
            };
            if ($scope.isBOOLDate == 2) {
                $scope.listDataw = data.data;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    }

    // 按照结束时间搜索（开始时间）
     $scope.timeLostFocusBegin = function(){
         if($scope.sortClassType==1){
             //调用搜索主页参数
             $scope.templateData();
             $scope.searchDateCourse($scope.searchDate);
             return false;
         }
         return true;
     };

    //选择时间搜索（结束时间）
    $scope.timeLostFocus = function () {
        if($scope.sortClassType==1){
            //调用搜索主页参数
            $scope.templateData();
            $scope.searchDateCourse($scope.searchDate);
            return false;
        }
        if ($scope.timeLostFocusStart != undefined && $scope.timeLostFocusEnd != undefined) {
            $http({
                method: 'get',
                url: '/new-league/group-class?' + $.param($scope.searchLeagueData())
            }).then(function (data) {
                if ($scope.isBOOLDate == 1) {
                    $scope.listData = data.data;
                };
                if ($scope.isBOOLDate == 2) {
                    $scope.listDataw = data.data;
                };
            }, function (error) {
                console.log(error);
                Message.error("系统错误请联系工作人员");
            });
        };
    };
    //初始化课程
    $scope.initDatas = function () {
        $http({
            method: 'get',
            url: '/new-league/group-class?organizationId=' + $scope.venueId
        }).then(function (success) {
            $scope.listData = success.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    //选择场馆
    $scope.chooseVenues = function (id) {
        $scope.venueId = id;
        $http({
            method: 'get',
            url: '/new-league/group-class?'+ $.param($scope.searchLeagueData())
        }).then(function (success) {
            $scope.listData = success.data;
            $scope.listDataw = success.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
        //选择场馆之后 默认出现对应的教室
        $scope.chooseAClassroomSearch(id);
        // 初始化 月模板数据搜索
        if($scope.sortClassType == 1){
            //初始化场馆按月调用模板
            $scope.templateData();
        }
    };
    //所有教室
    $scope.chooseAClassroomSearch = function (id) {
        $http({method: 'get', url: "/new-league/all-classroom?venueId=" + id}).then(function (data) {
            $scope.venueCourseCoachSearch = data.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员')
        });
    };
    $scope.isBOOLDate = 1;
    // 日期跳转下周
    $scope.dateNext = function () {
        $('#myModals1').modal('hide');
        if ($scope.isBOOLDate == 1) {
            $scope.isBOOLDate = 2;
        };
        // $scope.theNewCurriculumCurriculum();
        $scope.initTemplate();
        var date = $scope.listData.week7[0].class_date
        date = new Date(date);
        var date1 = date.valueOf();
        $scope.dateSta1 = date1 + 24 * 60 * 60 * 1000;
        $scope.dateSta2 = date1 + 24 * 60 * 60 * 1000 * 7;
        $scope.initTemplatew();
        $('.nowWeek').hide();
        $('.nextWeek').show();
        $('.modelBox').hide();
        $('.modelNext').show();
        $("#myModals1").modal("hide");
    };

    // 日期跳转上周
    $scope.dateBack = function () {
        if ($scope.isBOOLDate == 2) {
            $scope.isBOOLDate = 1;
        };
        $scope.initDatas();
        $('.nowWeek').show();
        $('.nextWeek').hide();
        $('.modelBox').show();
        $('.modelNext').hide();
    };
    // 点击添加课程model
    $scope.classAdd = function () {
        $("#myModal3").modal("show");
        var iconHeight = $('.iconTitleBox').height();
        $('.boxText').height(iconHeight);
    };
    // 空白页面点击添加课程的js
    $scope.classAdd2 = function () {
        $("#myModal3").modal("show");
        var iconHeight = $('.iconTitleBox').height();
        $('.boxText').height(iconHeight);
    };
    // 模态框1的关闭js
    $scope.close = function () {
        $("#myModal").modal("hide");
        $scope.initDatas()
    };
    // 模态框2的关闭js
    $scope.close2 = function () {
        $("#myModal2").modal("hide");
    };
    // 模态框3的关闭js
    $scope.close3 = function () {
        $("#myModal3").modal("hide");
        $scope.choiceCourseNameChangeId = '';
        $scope.dayStart = '';
        $scope.dayEnd = '';
    };
    // 模态框4的关闭js
    $scope.close4 = function () {
        $("#myModal4").modal("hide");
    };
    // 模态框5的关闭js
    $scope.close5 = function () {
        $("#myModal5").modal("hide");
    };
    $scope.amendModal = function () {
        $("#amendModal").modal("hide");
    };
    if (boxHeight < 0) {
        $('.addBox').height(83);
        $('.hoverBox').height(83);
    } else {
        $('.addBox').height(boxHeight);
        $('.hoverBox').height(boxHeight);
    };
    // 点击添加新的添加课程的盒子js
    var boxHeight = $('.infoBox').height();
    $scope.hoverAdd = function () {
        $('.contentBoxAdd').append("<div class='addBox' data-toggle='modal' data-target='#myModal3' ng-click=‘classClick()’><p class='hoverP'>点击添加课程</p></div>");
        if (boxHeight < 0) {
            $('.addBox').height(83);
            $('.hoverBox').height(83);
        } else {
            $('.addBox').height(boxHeight);
            $('.hoverBox').height(boxHeight);
        };
    };
    $scope.hoverAdd2 = function () {
        $('.contentBoxAdd2').append("<div class='addBox2' data-toggle='modal' data-target='#myModal3' ng-click=‘classClick()'><p class='hoverP'>点击添加课程</p><p style='font-size: 14px;color: #666;font-weight: bold;margin-bottom: 4px;margin-top: 10px;display: none;'>哈他瑜伽课程</p><p style='color: #999;font-weight: bold;margin-bottom: 4px;display: none;'>07:00-09:00</p><p style='color: #999;font-size: 12px;margin-bottom: 10px;display: none;'>教练：阿波/教室：A瑜伽室</p></div>");
        if (boxHeight < 0) {
            $('.addBox2').height(83);
            $('.hoverBox2').height(83);
        } else {
            $('.addBox2').height(boxHeight);
            $('.hoverBox2').height(boxHeight);
        };
    };
    $scope.activeText = function () {
        $(this).addClass("activeText");
    };
    //点击查看单个课程详情
    $scope.courseDetails = function (id,Coursestart) {
        // 控制显示按钮是否展示
        $scope.displayContrally   = $scope.updateDisplayControl(Coursestart);
        // 控制后 其它逻辑
        $scope.courseDetailsID = id;
        $.loading.show();
        $http({
            method: 'get',
            url: '/member/get-about-member-detail?id=' + $scope.courseDetailsID + "&sign=group&venueId="+$scope.venueId,
        }).then(function (success) {
            //console.log(success)
            $scope.reservationDetails = success.data.data;
            if (success.data.data.aboutClass.length == 0) {
                $scope.reservationDetailsAboutClass = true;
            } else {
                $scope.reservationDetailsAboutClass = false;
            }
            $scope.cardNumber = success.data.data.card_number;// 会员卡号
            $scope.classSutaus = success.data.data.classStatus;// 课程状态
            $scope.classSutausStart = success.data.data.start;// 课程开始时间
            $scope.classSutausEnd = success.data.data.end;// 课程结束时间
            $scope.timestampX = Date.parse(new Date()) / 1000;// 当前时间戳
            $.loading.hide();
           /* console.log($scope.timestampX < $scope.classSutausEnd)
            console.log($scope.timestampX);
            console.log( $scope.classSutausEnd)*/
            if ($scope.timestampX > $scope.reservationDetails.start) {
                $scope.cancelReservationBool = true;
            }
            $('#myModal').modal("show");
            if (success.data.data.aboutClass.length != 0) {
                $scope.dataInfo = false;
                $scope.aboutDetailShow = false;
            } else {
                $scope.aboutDetailShow = true;
            }
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    //点击修改课程 查看显示按钮是否展示
    $scope.updateDisplayControl = function (Coursestart) {
        var mydate  = new Date();
        var nowTime =  mydate.getTime()/1000;
        if(Coursestart==undefined||Coursestart==""){
            $scope.displaySign = false;
        }
        // 获取上课开始时间
        if(Coursestart<nowTime){
            $scope.displaySign = true;
        }
        return true;
    };

    // 团课上课
    $scope.leagueClassStart = function (id, coachId, date,teacherStart,personalAttr) {
        var $personalAttr = parseInt(personalAttr);
        var $teacherStart = parseInt(teacherStart);
        if($personalAttr < $teacherStart){
            Message.warning('预约人数小于'+$teacherStart+'人，不能进行打卡');
            return;
        }
        $scope.startStatus = 2
        var a = $scope.timestampConvertedToDate(date * 1000);
        console.log('时间1',a);
        var b = $scope.timestampConvertedToDate(new Date().getTime())
        console.log('时间1',b);
        if (a > b) {
            if(!$scope.messageWarning('',"只能当天打卡")){return};
        };
        if (a < b) {
            if(!$scope.messageWarning('',"时间已经过了，不能打卡了")){return};
        };
        if (a == b) {
            $http.get('/new-league/edit-coach-class-status?id=' + id + '&status=' + $scope.startStatus + '&coachId=' + coachId).success(function (data) {
                if (data.status == "success") {
                    $scope.messageSuccessData = data.data;
                    $("#workModalSuccess").modal("show");
                } else {
                    Message.warning(data.data.message);
                    return false;
                };
            });
        };
    };
    // 团课下课
    $scope.leagueClassEnd = function (id, coachId) {
        $scope.endStatus = 3;
        $http.get('/new-league/edit-coach-class-status?id=' + id + '&status=' + $scope.endStatus + '&coachId=' + coachId).then(function (success) {
            if (success.data.status == 'success') {
                Message.success(success.data.data);
            } else {
                Message.warning(success.data.data);
            };
            $scope.closeSuccessModalWork();
        });
    };
    // 关闭团课模态框
    $scope.closeSuccessModalWork = function () {
        $("#myModal").modal("hide");
    };
    // 课程表 单个课程取消课程
    $scope.cancelCourse = function () {
        $http({
            url: '/new-league/cancel-course?courseId=' + $scope.courseDetailsID,
            method: 'get'
        }).then(function (data) {
            if (data.data.status == "success") {
                $("#myModal").modal("hide");
                $scope.initDatas();
                $scope.initTemplatew();
            };
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系管理人员');
        });
    };
    //课程表 单个课程 信息 取消预约课程
    $scope.cancelReservation = function (id) {
        $http({
            url: '/check-card/cancel-about-class?id=' + id,
            method: 'get',
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success(data.data.data.data);
            };
            if (data.data.status == "error") {
                Message.warning(data.data.data);
            };
            //重新加载数据
            $scope.courseDetails($scope.courseDetailsID);
        }, function (error) {
            console.log(error)
            Message.error('系统错误请联系管理人员');
        });
    };
    // 点击添加课程
    $(document).ready(function () {
        $('.addBox').on('click', function () {
            $scope.classroomID = "";
            $scope.selectionClassificationId = ''
            $("#myModal3").modal("show");
        })
        $(".js-example-basic-single").select2();
    });
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
    })
    //初始化 修改课程数据
    $scope.revisedCurriculumData = {
        start: '',//课程开始时间
        end: '',//课程结束时间
        classroom_id: '',//教室id
        courseId: '',// 团课排课课程id
        coach_id: '',//教练id
        class_id: '',//课程id
        employeeName: '', // 教练名字
    }
    // 课表 单个课程 修改课程模态框
    $scope.updateCourse = function (date) {
        $scope.numberBlur = 1;
        $scope.changeCoachsBOOL = true;
        if ($scope.listData.week7[0].class_date > date && $scope.listData.week1[0].class_date < date) {
            $scope.isBOOLShow = 1;
        } else {
            $scope.isBOOLShow = 2;
        };
        $("#myModals5").modal("show");
        //调用数据所有课种
        $scope.initClassification2();
        $http({
            method: 'get',
            url: '/new-league/get-one-data?id= '+$scope.courseDetailsID
        }).then(function (data) {
            //console.log('data',data);
            $scope.revisedCurriculumData.start = data.data.start;
            $scope.revisedCurriculumData.end = data.data.end * 1000;
            var start = $scope.timeChange(data.data.end);     // 再次重新赋值 修正
            $('#dayEnd2').val(start);
            var end  =  $scope.timeChange(data.data.start);  // 再次重新赋值修正
            $("#dayStartss").val(end);
            $scope.revisedCurriculumDataLength = data.data.course_duration;
            //教练
            $scope.revisedCurriculumData.changeCoachsName = data.data.employeeName;
            $scope.revisedCurriculumData.coach_id = data.data.coach_id;
            // $scope.changeCoachsID                   = data.data.coach_id;  // 字段不知道被谁更改 教练赋值
            // if($scope.changeCoachsID){
            //     console.log("1111");
            //     $scope.changeCoachsBOOL = true;
            //     $scope.changeCoachsID   = $scope.revisedCurriculumData.coach_id;
            // }else{
            //     $scope.changeCoachsBOOL = false;
            // }
            $scope.changeCoachsBOOL = true;
            if(data.data.pic){
                $scope.revisedCurriculumData.changeCoachsPic = data.data.pic;
            }else{
                $scope.revisedCurriculumData.changeCoachsPic = "/plugins/user/images/pt.png";
            }
            //课种
            $scope.revisedCurriculumData.courseTypeName = data.data.courseTypeName;
            $scope.revisedCurriculumData.courseTypeId = data.data.topCourseId;

            //课程
            $scope.revisedCurriculumData.theCourseName = data.data.theCourseName;
            $scope.revisedCurriculumData.class_id = data.data.course_id;
            //教室
            $scope.revisedCurriculumData.classroom_id = data.data.classroom_id;
            $scope.revisedCurriculumData.classroomName = data.data.classroomName;
            //座位
            $scope.revisedCurriculumData.theSeatTypeName = data.data.theSeatTypeName;

            $scope.theSeatingOrderAllUpdateId = data.data.seat_type_id;
            localStorage.setItem('seatTypeId', JSON.stringify({
                id: data.data.classroom_id,
                time: $scope.phaseDifferenceTime
            }));
            //选择课种
            $scope.selectionClassification2($scope.revisedCurriculumData.courseTypeId);
            //选择课程
            $scope.choiceCourseNameChange2($scope.revisedCurriculumData.class_id)
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };

    $scope.timeChange = function(theTime){
        var date = new Date(theTime * 1000);
        var h = date.getHours();
        h = h < 10 ? ('0' + h) : h;
        var minute = date.getMinutes();
        minute = minute < 10 ? ('0' + minute) : minute;
        return h+":"+minute;
    };



    //初始化修改课程所有课种
    $scope.initClassification2 = function () {
        $http({method: 'get', url: "/new-league/top-course"}).then(function (data) {
            $scope.classification2 = data.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    // 修改课程 选择课种
    $scope.selectionClassification2 = function (id) {
        //选择的课种 根据id初始化课程数据
        $http({method: 'get', url: "/new-league/bottom-data?id=" + id}).then(function (data) {
            $scope.choiceCourseName2 = data.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    // 修改课程 选择课程
    $scope.choiceCourseNameChange2 = function (id) {
        $scope.courseNameID2 = id;
        //调用 选择开始时间之后 结束时间自动计算 方法
        $scope.getSingleData($scope.courseNameID2);
        //场馆所有教室
        $http({
            method: 'get',
            url: "/new-league/all-classroom?venueId=" + $scope.venueId
        }).then(function (data) {
            $scope.venueCourseCoach2 = data.data;
            $scope.chooseAClassroom2($scope.revisedCurriculumData.classroom_id);
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    //修改课程  获取课程的时长
    $scope.getSingleData = function (id) {
        $http({method: 'get', url: "/new-league/get-course-one?courseId=" + id}).then(function (data) {
            $scope.revisedCurriculumDataLength = data.data.course_duration
            var date = $scope.getNowFormatDates() + " " + $("#dayStartss").val();
            var timestamp = Date.parse(new Date(date));
            $scope.revisedCurriculumData.end = parseInt(timestamp) + ($scope.revisedCurriculumDataLength * 60 * 1000)
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    //修改课程 选择开始时间之后 结束时间自动计算
    $scope.revisedCurriculumDataStartBlur = function () {
            var date = $scope.getNowFormatDates() + " " + $("#dayStartss").val();
            var timestamp = Date.parse(new Date(date));
            $scope.revisedCurriculumData.end = parseInt(timestamp) + ($scope.revisedCurriculumDataLength * 60 * 1000);
            $scope.numberBlur++;
    };
    //修改课程 选择教室
    $scope.chooseAClassroom2 = function (id) {
        var getModuleId = localStorage.getItem("seatTypeId");
        var seatTypeId = JSON.parse(getModuleId).id;
        if (seatTypeId != $scope.revisedCurriculumData.classroom_id) {
            $scope.theSeatingOrderAllUpdateId = '';
            if (seatTypeId == $scope.revisedCurriculumData.classroom_id) {
                $scope.theSeatingOrderAllUpdateId = '';
            }
        }
        //根据教室 获取对应的座次
        $http({method: 'get', url: '/new-league/get-seat-type?roomId=' + id}).then(function (data) {
            $scope.theSeatingOrderAllUpdate = data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };
    //修改课程 完成按钮
    $scope.revisedCurriculum = function () {
        var data = {
            date: $scope.reservationDetails.class_date,//课程排课日期
            start: $("#dayStartss").val(),//课程开始时间
            end: $("#dayEnd2").val(),//课程结束时间
            classroom_id: $scope.revisedCurriculumData.classroom_id,//教室id
            courseId: $scope.courseDetailsID,// 团课排课课程id
            coach_id: $scope.revisedCurriculumData.coach_id,//教练id
            class_id: $scope.revisedCurriculumData.class_id,//课程id
            seatTypeId: $scope.theSeatingOrderAllUpdateId, //  座次
            venueId   :$scope.venueId,//场馆id
            _csrf_backend: $('#_csrf').val()
        };
        $http({
            url: '/new-league/update-course-detail',
            method: 'post',
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            $scope.classUpsateDataData = data.data.status;
            if ($scope.classUpsateDataData == "success") {
                $("#myModals5").modal("hide");
                $("#myModal").modal("hide");
                $scope.initDatas();
                $scope.initTemplatew();
                Message.success(data.data.data);
                $scope.revisedCurriculumData.end = '';
                //$scope.templateData();
                //按月排课重新加载
                $scope.searchDateCourse($scope.searchDate,$scope.chinaDate);
            } else {
                Message.warning(data.data.data, '错误');
            };
        });
    };


    // 课程表 添加课程
    $scope.classClick = function (data) {
        // $("#myModal3").modal("show");
        //获取点击的时间
        $scope.listDataWeek1Class_date = data;
        $scope.initClassification();
    };
    // 点击选择教练的模态框显示
    $scope.boxText = function (id) {
        $scope.boxTextId = id;
        if (id == null || id == undefined || id == null) {
            Message.warning("请选择课程");
            return;
        };
        $http({
            method: 'get',
            url: "/new-league/get-coach-by-course?courseId=" + id
        }).then(function (success) {
            $scope.ChangeCoachsBoxText = success.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
        $("#myModal4").modal("show");
    };
    // 添加课表 获取所有课种
    $scope.initClassification = function () {
        $http({
            method: 'get',
            url: "/new-league/top-course"
        }).then(function (data) {
            $scope.classification = data.data;
        }, function (error) {
            console.log(error);
            console.log("系统错误请联系管理人员");
        });
        $scope.choiceCourseNameBOOL = true;
    };
    // 添加课表 选择课种
    $scope.selectionClassification = function (id) {
        //选择课种之后  获取课程
        $scope.initChoiceCourseName(id);
    };
    //添加课表  获取课程
    $scope.initChoiceCourseName = function (id) {
        $http({
            method: 'get',
            url: "/new-league/bottom-data?id=" + id
        }).then(function (data) {
            $scope.choiceCourseName = data.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
        if ($scope.choiceCourseNameChangeId == null) {
            $scope.choiceCourseNameChangeId = '';
        } else {
            $scope.choiceCourseNameChangeId = '';
        };
    };
    //添加课表 选择课程
    $scope.choiceCourseNameChange = function (id) {
        var obj = JSON.parse(id);
        if (obj == null || obj == undefined || obj == '') {
            $scope.choiceCourseNameBOOL = true;
            return;
        } else {
            $scope.choiceCourseNameBOOL = false;
        };
        $scope.courseNameID = obj.id;
        $scope.courseNameDate = obj.course_duration;
        // 添加课表 场馆教室
        var url = "/new-league/all-classroom?venueId=" + $scope.venueId;
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            $scope.venueCourseCoach = data.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    $scope.changeCoachsBOOL = true;
    //选择单个教练
    $scope.changeCoachsClick = function (id, name, pic) {
        $scope.changeCoachsBOOL = false;
        $scope.changeCoachsID = id;
        $scope.revisedCurriculumData.coach_id = id;
        $scope.changeCoachsName = name;
        $scope.changeCoachsPic = pic;
        $("#myModal4").modal("hide");
    };
    //鼠标进入
    $scope.changeCoachs = function (event, i) {
        $a = event.target;
        $(".changeCoachsName").eq(i).addClass("fontColor");
        $(".changeCoachsDiv").eq(i).addClass("rgb248");
        $(".changeCoachs").eq(i).addClass("displayInlineBlock");
        $(".changeCoachs").eq(i).removeClass("displayInlineBlocks");
    };
    //鼠标离开
    $scope.changeCoachLeave = function (e, i) {
        $(".changeCoachsName").removeClass("fontColor");
        $(".changeCoachsDiv").removeClass("rgb248");
        $(".changeCoachs").eq(i).removeClass("displayInlineBlock");
        $(".changeCoachs").eq(i).addClass("displayInlineBlocks");
    };
    // 添加课表 选择教室id
    $scope.chooseAClassroom = function (id) {
        $scope.chooseAClassroomID = id;
        $scope.theSeatingOrderAllId = '';
        $http({method: 'get', url: '/new-league/get-seat-type?roomId=' + id}).then(function (data) {
            $scope.theSeatingOrderAll = data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };

    //快速添加教练  点击新增教练
    $scope.addCoach = function () {
        $("#addCoachArray").select2({
            placeholder: '请选择教练',
            dropdownParent: $("#addCoach")
        });
        $http({
            method: 'get',
            url: '/new-league/get-all-company-coach?venueId=' + $scope.venueId
        }).then(function (data) {
            $scope.addCoachData = data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };
    // 快速添加教练 点击确认添加按钮
    $scope.addCoachOk = function () {
        $http({
            method: 'get',
            url: '/new-league/add-group-coach?courseCategoryId=' + $scope.boxTextId + "&addCoachId=" + $("#addCoachArray").val()
        }).then(function (success) {
            if (success.data.status == 'success') {
                Message.success(success.data.data);
                $http({
                    method: 'get',
                    url: '/new-league/get-coach-by-course?courseId=' + $scope.boxTextId
                }).then(function (data) {
                    $scope.ChangeCoachsBoxText = data.data;
                }, function (error) {
                    console.log(error);
                    Message.error('系统错误请联系工作人员');
                });
                $("#addCoach").modal('hide');
            };
            if (success.data.status == 'error') {
                Message.warning(success.data.data);
            };
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
        $("#addCoachArray").val("");
        $scope.addCoachArray = '';
    };
    //课程表` 添加课程 开始时间
    $scope.dayStartModel = function (data) {
        var date = $scope.getNowFormatDates() + " " + data;
        var timestamp = Date.parse(new Date(date));
        $scope.dayEnd = timestamp + ( $scope.courseNameDate * 60 * 1000);
        $scope.dayEnd = $scope.getMyDates($scope.dayEnd);
    };
    // 课程表 添加课程完成
    $scope.classChange = function () {
        if(!$scope.messageWarning($scope.selectionClassificationId,"请选择课种")){ return};
        if(!$scope.messageWarning($scope.courseNameID,"请选则课程")){ return};
        if(!$scope.messageWarning($scope.dayStart,"请选则开始时间")){ return};
        if(!$scope.messageWarning($scope.dayEnd,"请选则结束时间")){ return};
        if ($scope.dayStart != '' && $scope.dayStart != null && $scope.dayEnd != '' && $scope.dayEnd != null) {
            var current = new Date().getTime();
            var currentTime = $scope.getMyDate(current);
            var start = currentTime + ' ' + $scope.dayStart + ':00';
            var end = currentTime + ' ' + $scope.dayEnd + ':00';
            var endTime = new Date(end).getTime();
            var startTime = new Date(start).getTime();
            if (startTime >= endTime) {
                Message.warning('您选择的课程结束时间有误，请重新选择!');
                return;
            };
        };
        if(!$scope.messageWarning($scope.changeCoachsID,"请选择教练")){ return};
        if(!$scope.messageWarning($scope.chooseAClassroomID,"请选择教室")){ return};
        if(!$scope.messageWarning($scope.theSeatingOrderAllId,"请选择座次")){ return};
        var data = {
            date: $scope.listDataWeek1Class_date,//课程日期
            start: $scope.dayStart, //上课 开始时间
            end: $scope.dayEnd,//上课 结束时间
            courseId: $scope.courseNameID,//课程id
            coach_id: $scope.changeCoachsID, // 教练id
            classroom_id: $scope.chooseAClassroomID,//教室id
            seatTypeId: $scope.theSeatingOrderAllId, //  座次
            venue_id: $scope.venueId,
            _csrf_backend: $('#_csrf').val(),
        }
        $http({
            method: 'post',
            url: "/new-league/insert-data",
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function (data) {
            if (data.data.status == 'success') {
                if ($scope.classificationId != '' && $scope.classificationId != null && $scope.classificationId != undefined) {
                    $scope.newClassroom($scope.classificationId);
                } else {
                    $scope.initDatas();
                    $scope.initTemplatew();
                    $scope.courseNameID = '';
                    $scope.dayStart = '';
                    $scope.dayEnd = '';
                    $scope.choiceCourseNameChangeId = '';
                    $scope.changeCoachsID = '';
                    $scope.chooseAClassroomID = '';
                    $scope.changeCoachsBOOL = true
                    $scope.classificationName1 = '';
                    $scope.classificationId = '';
                    $scope.theSeatingOrderAllId = '';
                };
            };
            if (data.data.status == 'error') {
                Message.warning(data.data.data);
                return;
            };
            $("#myModal3").modal("hide");
            // 如果是修改月的话（刷新 月模板数据）
            if($scope.sortClassType==1){
                //调用搜索主页参数
                $scope.templateData();
                $scope.searchDateCourse($scope.searchDate,$scope.chinaDate);
                return false;
            }
        }, function (error) {
            Message.error('系统错误请联系管理人员');
        });
    };

    // 课程模板  添加课程模板详情
    $scope.initTemplate = function () {
        $http({method: 'get', url: '/new-league/add-template?organizationId=' + $scope.venueId}).then(function (data) {
            $scope.listDatas = data.data;
        }, function (error) {
            Message.error('系统错误请联系工作人员')
        });
        $scope.getDateFullYear();
    };
    // 课程模板   获取当前月份
    $scope.getDateFullYear = function () {
        $http({method: 'get', url: '/new-league/get-month'}).then(function (data) {
            $scope.FullYear0 = data.data[0];
            $scope.FullYear1 = data.data[1];
            $scope.FullYear2 = data.data[2];
        }, function (error) {
            Message.error('系统错误请联系工作人员');
        });
    };
    // 课程模板  获取周
    $scope.getMonth = function (date) {
        $http({method: 'get', url: '/new-league/get-week?date=' + date}).then(function (data) {
            $scope.Month = data.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系管理人员');
        });
    };
    //课程模板 点击切换模板数据
    $scope.initTemplates = function (data1, data2) {
        $scope.initTemplateDate = data1;
        $scope.initTemplateDate1 = data2;
        $http({
            method: 'get',
            url: '/new-league/add-template?organizationId=' + $scope.venueId + '&weekStart=' + data1 + '&weekEnd=' + data2
        }).then(function (data) {
            if (data.data.week1[0].data == false && data.data.week2[0].data == false && data.data.week3[0].data == false && data.data.week4[0].data == false && data.data.week5[0].data == false && data.data.week6[0].data == false && data.data.week7[0].data == false) {
                Message.success('抱歉 ! 暂无数据');
                return;
            };
            $scope.listDatas = data.data;
        }, function (error) {
            Message.error('系统错误请联系工作人员');
        });
    };
    //课程模板    完成模板
    $scope.addTemplates = function () {
        var data = {
            weekStart: $scope.initTemplateDate,
            weekEnd: $scope.initTemplateDate1,
            organizationId: $scope.venueId,
            orgWeekStart: $scope.listDataw.week1[0].class_date,
            orgWeekEnd: $scope.listDataw.week7[0].class_date,
            _csrf_backend: $('#_csrf').val(),
        };
        $http({
            method: 'post',
            url: '/new-league/add-template-data',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(data)
        }).then(function (data) {
            if (data.data.status == "success") {
                $scope.initTemplatew();
                $("#myModal5").modal("hide");
            }
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };

    // 下周课表数据
    $scope.initTemplatew = function () {
        var dataSta = new Date($scope.dateSta1).toLocaleDateString();
        var dataEnd = new Date($scope.dateSta2).toLocaleDateString();
        $http({
            method: 'get',
            url: '/new-league/add-template?organizationId=' + $scope.venueId + '&weekStart=' + dataSta + '&weekEnd=' + dataEnd
        }).then(function (data) {
            $scope.listDataw = data.data;
            //console.log( $scope.listDataw)
            $.loading.hide();
        }, function (error) {
            Message.error('系统错误请联系工作人员')
        });
    };
    //清空课程模板
    $scope.changeCourseTemplate = function () {
        var dataSta = new Date($scope.dateSta1).toLocaleDateString();
        var dataEnd = new Date($scope.dateSta2).toLocaleDateString();
        $http({
            method: 'get',
            url: "/new-league/delete-course?venueId=" + $scope.venueId + "&startDate=" + dataSta + "&endDate=" + dataEnd
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success(data.data.data)
            };
            if (data.data.status == "error") {
                Message.warning(data.data.data)
            };
            $scope.initTemplatew()
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        });
    };
    /**
     * author:张亚鑫
     * create:2017-12-1
     * 函数描述:选择查看排课方式下拉框change事件
     * param: value:下拉框的值，根据值可以判断是按月还是按周来排课
     *
     *
     * */
    $scope.chooseSortClassType = function (value) {
        // console.log(value);
        if(value == '') {
            //按周
            $('.addClassMonthStatus1').css('display','none');
            $('.addClassMonthStatus2').css('display','none');
            $('.modelBox').css('display','block');
            $('.titleDate-1').css('display','block');
            // 数据重载搜索
            $scope.overLoad();
        }else if(value == '1') {
            //按月
            $('.addClassMonthStatus1').css('display','block');
            $('.addClassMonthStatus2').css('display','block');
            $('.modelBox').css('display','none');
            $('.titleDate-1').css('display','none');
            $('.nextWeek').hide();
            $(".modelNext").hide();
            //同时调用 按月排课的团课排课模板
            $scope.templateData();
        }else {
            Message.warning('操作失败，请刷新页面后重试！')
        }
    };
    
    $scope.overLoad = function () {
        var url = '/new-league/group-class?' + $.param($scope.searchLeagueData());
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            if ($scope.isBOOLDate == 1) {
                $scope.listData = data.data;
            };
            if ($scope.isBOOLDate == 2) {
                $scope.listDataw = data.data;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    }

    /****************************** 团课课程(按月排课)***************************/

    $scope.publicParam = function () {
        $scope.venueId = ($scope.venueId!="")&&($scope.venueId!=undefined)?$scope.venueId:null;
    };
    /**
     * 后台 - 团课管理  - 调用按月团课排课数据
     * @create 2017/12/2
     * @author houkaixin<houkaixin@itsports.club>
     * @param model    // 显示的模板数据
     * @return array
     */
    $scope.templateData = function (model) {
        // 获取isBOOLDate =1 的参数
        $scope.isBOOLDate = 1;
        // 获取搜索参数
        var searchData  =  $scope.searchLeagueData();
        // 获取 搜索月 并且注入参数
        var searchMonth = ($scope.mySearchTemplateMonth==""||$scope.mySearchTemplateMonth==undefined)?null:$scope.mySearchTemplateMonth;
        searchData.searchMonth = searchMonth;
        //拼接访问url
        var url = '/new-league/accord-month-template?' + $.param(searchData);
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            $scope.monthTemplate = data.data.data;
            // 底部月模板按钮是否显示 是否显示控制
            $scope.isHaveData     = data.data.isHaveData;
            // 初始化 中国日期
            if($scope.chinaDate==""||$scope.chinaDate==undefined){
                // 初始化 搜索月
               if($scope.mySearchTemplateMonth == ""||$scope.mySearchTemplateMonth ==undefined){
                   $scope.mySearchTemplateMonth        = data.data.date["searchMonth"];
                   $scope.mySearchTemplateChinaMonth  = data.data.date["chinaSearchMonth"];
               }

            }
            var _allNowMonthDayLen =  $scope.monthTemplate.length;
            for(var i=0;i<_allNowMonthDayLen;i++){
                if($scope.monthTemplate[i].sign == true){
                    $scope.chinaDate     = data.data.data[i].classDate;                      // 初始化默认日期
                    $scope.searchDateCourse(data.data.data[i].class_date,$scope.chinaDate);   // 第一次 初始化 载入
                    $timeout(function(){
                        $('.monthDayActive').eq(i).addClass('activeBg').siblings('.monthDayActive').removeClass('activeBg')
                    },100);
                    break;
                }
            }

        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
        // //定义场馆id
        // $http({method: 'get', url: '/new-league/accord-month-template?course=' + $scope.venueId}).then(function (data) {
        //     $scope.monthTemplate = data.data.data;
        // }, function (error) {
        //     console.log(error);
        //     Message.error("系统错误请联系管理人员")
        // });
    };
    /**
     * 后台 - 团课管理  - 按月模板搜索
     * @create 2017/12/13
     * @author houkaixin<houkaixin@itsports.club>
     * @param direction    // left 向左  right 向右
     * @return array
     */
    $scope.topTable = function (direction) {
        var mySearchTemplateMonth = new Date($scope.mySearchTemplateMonth);
        if(direction=="right"){
            mySearchTemplateMonth.setMonth(mySearchTemplateMonth.getMonth()+1);
        }else{
            mySearchTemplateMonth.setMonth(mySearchTemplateMonth.getMonth()-1);
        }
        $scope.mySearchTemplateMonth        =  mySearchTemplateMonth.getFullYear()+"-"+((mySearchTemplateMonth.getMonth()<9?'0':'')+(mySearchTemplateMonth.getMonth()+1));
        $scope.mySearchTemplateChinaMonth  =  mySearchTemplateMonth.getFullYear()+"年"+((mySearchTemplateMonth.getMonth()<9?'0':'')+(mySearchTemplateMonth.getMonth()+1))+"月";
        // 初始化 按月模板重载
        $scope.templateData();
        $scope.searchDateCourse($scope.searchDate,$scope.chinaDate);
    };
    /**
     * 后台 - 团课管理  - 按照日期搜索团课排课课程
     * @create 2017/12/2
     * @author houkaixin<houkaixin@itsports.club>
     * @param date    // 搜索日期
     * @param chinaDate  // 中国日期
     * @return array
     */
    $scope.searchDateCourse = function(date,chinaDate,monthIndex){
        //console.log(date,chinaDate)
        if(date==""||date==undefined){
             return false;
        }
        if(chinaDate!=""||chinaDate!=undefined){
             $scope.chinaDate = chinaDate;
        }else{
             $scope.chinaDate = "";
        }
        // 获取isBOOLDate =1 的参数
        $scope.isBOOLDate = 1;
        // 获取搜索参数
        var searchData  =  $scope.searchLeagueData();
        // 搜索日期
        searchData.searchDate = date;
        $scope.searchDate      = date;
        //点击当前day盖面颜色
        $scope.monthIndexFlag = monthIndex;
        if($scope.monthIndexFlag == 0){
            $('.monthDayActive').eq($scope.monthIndexFlag).addClass('activeBg').siblings('.monthDayActive').removeClass('activeBg')
        }else if($scope.monthIndexFlag != '' && $scope.monthIndexFlag != undefined && $scope.monthIndexFlag != null){
            $('.monthDayActive').eq($scope.monthIndexFlag).addClass('activeBg').siblings('.monthDayActive').removeClass('activeBg')
        }
        var time = new Date($scope.searchDate.replace(/-/g,'/')).getTime();
        time =parseInt(time/1000+21*24*3600);
        function showTime(){
            var show_day=new Array('week1','week2','week3','week4','week5','week6','week7');
            var time = new Date($scope.searchDate.replace(/-/g,'/'));
            var day=time.getDay();
            var now_time=show_day[day-1];
            //console.log(now_time);
            if(now_time == undefined){
                $scope.weekType = 'week7';
            }else{
                $scope.weekType = now_time;
            }
        }
        showTime();
        //拼接访问url
        /*var url = '/new-league/search-class?' + $.param(searchData);
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            console.log(data.data.data)
            $scope.searchTheCourse = data.data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });*/
        var dataSta = new Date($scope.searchDate).toLocaleDateString();
        var dataEnd = new Date($scope.searchDate).toLocaleDateString();
        $http({
            method: 'get',
            url: '/new-league/add-template?organizationId=' + $scope.venueId + '&weekStart=' + dataSta + '&weekEnd=' + dataEnd
        }).then(function (data) {
            $scope.searchTheCourse = data.data;
            //console.log( $scope.searchTheCourse)
        }, function (error) {
            Message.error('系统错误请联系工作人员')
        });
    };
    //添加月课程模态框
    $scope.addMonthCourse123 = function(){
        //初始化日期
        var searchDate  =  $scope.gainInsertDate();
        // 初始化 添加课程 默认数据
        if(searchDate===false){
            Message.warning("参数错误,请联系管理员");
            return false;
        }
        $scope.classClick(searchDate);
        // 初始化模态框数据
        $scope.initAddCourseModal();
        $('#myModal3').modal('show');
    };
    /**
     * 后台 - 团课排课  -  初始化模态框数据
     * @create 2017/12/14
     * @author houkaixin<houkaixin@itsports.club>
     * @return string
     */
    $scope.initAddCourseModal = function () {
         $scope.selectionClassificationId = "";  // 请选择课种赋空
         $scope.choiceCourseNameChangeId  = "";  // 请选择课程 赋值为空
         $scope.dayStart                    = "";  // 课程开始时间赋值为空
         $scope.dayEnd                      = "";  // 课程结束时间赋值为空
         $scope.changeCoachsBOOL           = true;  // 初始化 请选择教练
         $scope.classroomID                 = "";   // 请选择教室赋空
         $scope.theSeatingOrderAllId       = "";  // 请选择座次浮空
    };

    /**
     * 后台 - 团课排课  - 对2017年 - 08月 -12日 格式转化处理
     * @create 2017/12/2
     * @author houkaixin<houkaixin@itsports.club>
     * @return string
     */
    $scope.gainInsertDate = function () {
        var year  = $scope.chinaDate.substr(0,4);
        var month = $scope.chinaDate.substr(5,2);
        var day   = $scope.chinaDate.substr(8,2);
        if(year==""||year==undefined||month==""||month==undefined||day==""||day==undefined){
               return false;
        }
        var combineDate = year+"-"+month+"-"+day;
        return combineDate;
    };
    /**
     * 后台 - 团课管理  - 搜索模板 左侧栏数据
     * @create 2017/12/2
     * @param  templateMonth   //模板月
     * @author houkaixin<houkaixin@itsports.club>
     * @return array
     */
    $scope.addMonthTemplate = function (templateMonth) {
        $('#addClassMonthModal').modal('show');
        var templateMonth = (templateMonth==""||templateMonth==undefined)?null:templateMonth;
        var url = '/new-league/get-all-month';
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            // 获取当前年的所有月
            var allMonth = data.data.data;
            $scope.theAllMonth      = data.data.data;
            //连带当前月 所有数据显示出来
            $scope.myTemplateMonth  = data.data.courseMonth;
            //遍历显示当选月数据
            allMonth.forEach(function(item,index){
                if(item.standard == $scope.myTemplateMonth){
                    $timeout(function(){
                        $scope.searchTemplateData(item.standard,index);
                    },100)
                }
            });
            // 搜索模板数据
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    /**
     * 后台 - 团课管理  -  获取对应月模板数据
     * @create 2017/12/12
     * @param  month   // 搜索月
     * @author houkaixin<houkaixin@itsports.club>
     * @return array
     */
    $scope.searchTemplateData = function (month,key){
        if(key == 0){
            $('.addClassMonthChoose li').eq(key).addClass('activeTemBg').siblings('.addClassMonthChoose li').removeClass('activeTemBg');
        }else if(key != ''&& key !=  undefined && key != null){
            $('.addClassMonthChoose li').eq(key).addClass('activeTemBg').siblings('.addClassMonthChoose li').removeClass('activeTemBg');
        }
     var param = $scope.gainTemplateParam(month);
     if(month!=""&&(month!=undefined)){
          $scope.myTemplateMonth = month;
     }
     var url = '/new-league/accord-month-template?' + $.param(param);
        $http({
            method: 'get',
            url: url
        }).then(function (data){
            $scope.accordMonth = data.data.data;
            // 按照日期  搜索
            // 日期重置
            var _allNowMonthDayLen =  $scope.accordMonth.length;
            for(var i=0;i<_allNowMonthDayLen;i++){
                if($scope.accordMonth[i].sign == true){
                    $scope.templateSearchDate = $scope.accordMonth[i].classDate;                     // 初始化默认日期
                    $scope.searchCourseAccordMonth($scope.accordMonth[i].class_date,i);   // 第一次 初始化 载入
                    $timeout(function(){
                        $('.activeTem').eq(i).addClass('activeBg').siblings('.activeTem').removeClass('activeBg');
                    },100);
                    break;
                }
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    /**
     * 后台 - 团课管理  - 根据模板搜索数据
     * @create 2017/12/12
     * @param  date   // 收搜日期
     * @author houkaixin<houkaixin@itsports.club>
     * @return array
     */
    $scope.searchCourseAccordMonth = function (date,key) {
        if(key == 0){
            $('.activeTem').removeClass('activeBg');
            $('.activeTem').eq(0).addClass('activeBg');
        }else if(key != undefined && key != '' && key != null){
            $('.activeTem').removeClass('activeBg');
            $('.activeTem').eq(key).addClass('activeBg');
        }
           // 获取搜索参数
        var param             = $scope.gainTemplateParam();
        param.searchDate     = (date==undefined)?null:date;
        if(date!=undefined||date!=""){
            $scope.templateSearchDate = date;
        }
        //拼接访问url
        var url = '/new-league/search-class?' + $.param(param);
        $http({
            method: 'get',
            url: url
        }).then(function (data) {
            $scope.templateCourseData = data.data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };

    // 添加模板收索公共参数
    $scope.gainTemplateParam = function (month) {
        $scope.templateSearchMonth = (month!=undefined)?month:null;
        return {
            searchMonth: $scope.templateSearchMonth != undefined ? $scope.templateSearchMonth : null,
            organizationId: $scope.venueId != undefined ? $scope.venueId : null              //场馆搜索
        };
    };
    // 模板数据录入
    $scope.templateDataInsert = function () {
        // 1:参数判断
        var param = $scope.paramJudge();
        if(param===false){
            Message.warning('参数获取失败,请联系管理员！');
            return false;
        }
        // 2:模板数据录入
        //拼接访问url
        var url = '/new-league/insert-all-data';
        $http({
            method: 'post',
            url: url,
            data:$.param(param),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if(data.data.data.status!="success"){
               Message.warning('模板录入失败！');
               return false;
            }
            // 再次回调数据
            $scope.templateData();
            $scope.searchDateCourse($scope.searchDate,$scope.chinaDate);
            //关闭模态框
            $('#addClassMonthModal').modal('hide');
            Message.success('数据录入成功');
        });
    };
    /**
     * 后台 - 团课管理  - 模板数据录入 参数判断
     * @create 2017/12/13
     * @author houkaixin<houkaixin@itsports.club>
     * @return array
     */
    $scope.paramJudge = function () {
        var templateMonth  = $scope.myTemplateMonth;           // 被赋值的月份
        var insertMonth    = $scope.mySearchTemplateMonth;    // 被录入的月份
        if(templateMonth==undefined||templateMonth==""||insertMonth==undefined||insertMonth==""){
            return false;
        }
        return {
            templateDate:templateMonth,
            insertDate  :insertMonth,
            venueId     :($scope.venueId==""||$scope.venueId==undefined)?null:$scope.venueId,
            _csrf_backend: $('#_csrf').val()
        }
    };







    /****************************** 团课课程(按月排课)***************************/

    //团课课程  按下回车搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchButton($scope.keywords)
        };
    };
    //团课课程 按下按钮搜索
    $scope.searchButton = function (data) {
        $http({method: 'get', url: '/new-league/group-class-data?course=' + data}).then(function (data) {
            if (data.data.data.length != 0) {
                $scope.classCurriculumData = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
                $scope.classListShow = false;
            } else {
                $scope.classCurriculumData = data.data.data;
                $scope.pages =  data.data.pages;
                $scope.classListShow = true;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        });
    };
    //列表排序
    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);            //准备排序状态
        $scope.changeSectle();              //调用搜索方法
    };
    //处理正序、倒序
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        }
        $scope.sort = sort;             //排序状态
    };
    //处理排序搜索
    $scope.changeSectle = function (value) {
        var array = {
            sortType: $scope.sortType != undefined ? $scope.sortType : null,
            sortName: $scope.sort != undefined ? $scope.sort : null,
            memberCard: $scope.missDetailsCardId != undefined ? $scope.missDetailsCardId : null,
            venueId   :  $scope.venueId != undefined ?  $scope.venueId : null,
        };
        $http({method: 'get', url: "/new-league/mem-absent-record?" + $.param(array)}).then(function (data) {
            $scope.classCurriculumData = data.data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        });
    }
    $scope.pageInitUrl = '/new-league/group-class-data';
    //初始化团课课程数据
    $scope.classCurriculuminit = function () {
        $.loading.show();
        $http({method: 'get', url: $scope.pageInitUrl}).then(function (data) {
            if (data.data.data.length != 0) {
                $scope.classCurriculumData = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }else {
                $scope.classCurriculumData = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide();
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        });
    };

    //添加搜索页数
    $scope.skipPage = function (value) {
        if (value != undefined) {
            $scope.pageInitUrl = '/new-league/group-class-data?page=' + value;
            $scope.classCurriculuminit();
        };
    };

    $scope.classCurriculuminit();
    $scope.getDataPage = function () {
        $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.listDate = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.listDate = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            };
            $.loading.hide();
        });
    };
    $scope.replacementPages = function (urlPages) {
        $.loading.show();
        $scope.pageInitUrl = urlPages;
        $scope.classCurriculuminit();
    };
    // 团课课程 团课课程修改
    $scope.classCurriculumRevision = function (id, name, pid) {
        $scope.classCurriculumRevisionid = id;
        $scope.classChangid = pid;
        $http.get('/new-league/update-init-data?id=' + id + '&name=' + name).success(function (response) {
            $scope.cardStatusUpdate = JSON.parse(response.courseData.coach_id)
        });
        $http({method: 'get', url: '/new-league/update-init-data?id=' + id + '&name=' + name}).then(function (data) {
            $scope.updateInitSectle = data.data
            $("#userHeader2").select2({
                dropdownParent: $("#amendModal"),
                'width': '80%'
            });
            $scope.addCurriculumCoachChoice();
            $('#amendModal').show();
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系管理人员')
        });
    };
    //对图片格式的限制
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
                    $scope.updateInitSectle.courseData.pic = result.data.imgLink;
                };
                if (text == 'add') {
                    $scope.EmployeeDataPic = result.data.imgLink;
                };
            } else if (result.data.data == '"图片格式出现错误"') {
                if (text == 'update') {
                    $scope.updateInitSectle.courseData.pic = result.data.imgLink;
                };
                if (text == 'add') {
                    $scope.EmployeeDataPic = result.data.imgLink;
                };
            };
        }, function (error) {
            console.log(error);
            Message.warning("系统错误请联系工作人员");
        });
    };
    //团课课程 获取修改的课种分类
    $scope.classCurriculuminitChange = function (id) {
        $scope.classCurriculuminitChanges = id;
    };
    //团课课程 更新修改课程
    $scope.reviseTheCourse = function () {
        $scope.courseTime = $scope.updateInitSectle.courseData.course_duration;
        $scope.courseDifficult = $scope.updateInitSectle.courseData.course_difficulty;
        var data = {
            id: $scope.classCurriculumRevisionid,
            categoryId: $scope.classChangid,
            courseName: $scope.updateInitSectle.courseData.name,
            courseTime: $scope.updateInitSectle.courseData.course_duration,
            personLimit: $scope.updateInitSectle.courseData.people_limit,
            courseDifficult: $scope.updateInitSectle.courseData.course_difficulty,
            des: $scope.updateInitSectle.courseData.course_desrc,
            pic: $scope.updateInitSectle.courseData.pic,
            coachId: $scope.cardStatusUpdate,
            _csrf_backend: $('#_csrf').val()
        };
        if($scope.updateInitSectle.courseData.course_duration < 0) {
            Message.warning('请输入大于零的课程时长！');
            return;
        }
        if(!$scope.messageWarning($scope.classChangid,"请输入选择分类")){return};
        if(!$scope.messageWarning($scope.courseTime,"请输入课程时长")){return};
        if(!$scope.messageWarning($scope.courseDifficult,"请选择课程难度")){return};
        $scope.inputWarningTips1 = false;
        $http({
            method: 'post',
            url: '/new-league/update-group-data',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(data)
        }).then(function (data) {
            if (data.data.status === "success") {
                $("#amendModal").hide();
                $(".modal-backdrop").hide();
                $scope.classCurriculuminit();
                Message.success(data.data.data);
                $('#inputCheck').css('margin-left','77px');
            }else {
                Message.warning('完成失败');
                $scope.inputWarningTips1 = true;
                $('#inputCheck').css('margin-left', '225px');
            }
        });
    };
    //团课课程 新增课程
    $scope.theNewCurriculumCurriculum = function () {

        //获取 添加的课种
        $http({method: 'get', url: '/new-league/get-course-type'}).then(function (data) {
            $scope.theChgent = data.data;
            $("#userHeader1").select2({
                placeholder: '请选择教练',
                'width': '80%',
                dropdownParent: $("#myModals1")
            });
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系管理员')
        });
        //所有教练
        $scope.addCurriculumCoachChoice();
        $("#myModals1").modal("show");
    };
    //团课课程  添加课程 下拉框 课种id
    $scope.theNewCurriculumCurriculumID = function (id) {
        $scope.addClassifications = id;
    };
    //所有教练
    $scope.addCurriculumCoachChoice = function () {
        $http({
            method: 'get',
            url: '/new-league/get-coach',
        }).then(function (success) {
            $scope.ChangeCoachsmyModals1 = success.data;
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员');
        });
    };
    // 卡种筛选的selcet多选事件
    //完成课程
    $scope.theNewCurriculumCurriculums = function () {
        var data = {
            categoryId: $scope.addClassifications,
            courseName: $scope.addClassName,
            courseTime: $scope.durationOfCurriculum,
            personLimit: $scope.upperIimit,
            des: $scope.addCurriculum,
            courseDifficult: $scope.curriculumDifficulty,
            pic: $scope.EmployeeDataPic,
            coachId: $scope.cardStatus,
            _csrf_backend: $('#_csrf').val(),
        };
        if($scope.durationOfCurriculum < 0) {
            Message.warning('请输入大于零的课程时长数！');
            return;
        }
        if(!$scope.messageWarning(data.categoryId,"请选择课种分类")){return};
        if(!$scope.messageWarning(data.courseName,"请选择课种名字")){return};
        if(!$scope.messageWarning(data.courseTime,"请选择输入课程时长")){return};
        if(!$scope.messageWarning(data.courseDifficult,"请选择课课程难度")){return};
        if(!$scope.messageWarning($scope.cardStatus,"请选择教练")){return};
        $scope.inputWarningTips = false;
        $http({
            method: 'post',
            url: '/new-league/insert-group-data',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(data)
        }).then(function (data) {
            if (data.data.status == "success") {
                $("#myModals1").modal("hide");
                $('#name').val('');
                $('#time').val('');
                $('#person').val('');
                $scope.classCurriculuminit();
                Message.success(data.data.data)
                $scope.addClassification = '';
                $scope.addClassName = '';
                $scope.durationOfCurriculum = '';
                $scope.curriculumDifficulty = '';
                $scope.addCurriculum = '';
                $scope.EmployeeDataPic = ''
                $scope.cardStatus = '';
                $('#time').css('margin-left','77px');
            }else {
                Message.warning('完成失败');
                $scope.inputWarningTips = true;
                $('#time').css('margin-left','225px');
            };
        }, function (error) {
            console.log(error);
            Message.error('系统错误请联系工作人员')
        });
    };
    /***************预约设置************/
    $http({method: 'get', url: '/new-league/order-setting'}).then(function (data) {
        $scope.appointmentSettingsNetData = {
            appointmentSettings: data.data.before_class==0 ? '' : data.data.before_class, //预约设置
            cancelSettings: data.data.cancel_time == 0 ? '' : data.data.cancel_time, //取消设置
            floorCeiling: data.data.personLowerLimit == 0 ? '' : data.data.personLowerLimit,
            printSettings: data.data.printSettings == 0 ? '' : data.data.printSettings, //打印小票设置
        };
        $scope.thaw = data.data.thaw;
        $scope.absent = data.data.absent;
    }, function (error) {
        console.log(error);
        Message.error("系统错误请联系工作人员");
    });
    $scope.appointmentSettingsNetClick = function () {
        var data = {
            before_class: $scope.appointmentSettingsNetData.appointmentSettings,//课程开始前多长时间不可约课
            venue_id: parseInt($scope.venueId),//预约设置 场馆id
            cancel_time: $scope.appointmentSettingsNetData.cancelSettings,//课程开始前多长时间不可取消
            personLowerLimit: $scope.appointmentSettingsNetData.floorCeiling,//开课人数最少不得低于人数
            printSettings: $scope.appointmentSettingsNetData.printSettings,   //开课后多长时间不能打印小票
            // absent: $scope.absent, // 连续缺课多少节后不可预约
            // thaw: $scope.thaw, // 不可预约冻结后多长时间解冻
            _csrf_backend: $('#_csrf').val()
        }
        if(data.before_class < 0 || data.cancel_time < 0 ) {
            Message.warning('时间不能为负数！');
            return;
        }
        if(data.personLowerLimit < 0) {
            Message.warning('人数不能为负数！');
            return;
        }
        if(!$scope.messageWarning(data.before_class,"请您输入课程开始前多长时间不可约课")){return};
        if(!$scope.messageWarning(data.cancel_time,"请您输入课程开始前多长时间不可取消")){return};
        // if(!$scope.messageWarning(data.absent,"请您输入缺课不可预约节数")){return};
        // if(!$scope.messageWarning(data.thaw,"请您输入解冻时间")){return};
        $http({
            method: 'post',
            url: '/new-league/course-config',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(data),
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success(data.data.data);
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        });
    };
    $scope.appointmentSettingsNetClickReset = function () {
        var data = {
            before_class: 0,//课程开始前多长时间不可约课
            venue_id: parseInt($scope.venueId),//预约设置 场馆id
            cancel_time: 0,//课程开始前多长时间不可取消
            personLowerLimit:0 ,//开课人数最少不得低于人数
            printSettings: 0,   //开课后多长时间不能打印小票
            // absent: $scope.absent, // 连续缺课多少节后不可预约
            // thaw: $scope.thaw, // 不可预约冻结后多长时间解冻
            _csrf_backend: $('#_csrf').val()
        }
        $http({
            method: 'post',
            url: '/new-league/course-config',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(data),
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success('重置成功');
                $scope.appointmentSettingsNetData.appointmentSettings='';//课程开始前多长时间不可约课
                $scope.appointmentSettingsNetData='';//课程开始前多长时间不可取消
                $scope.appointmentSettingsNetData.floorCeiling='';//开课人数最少不得低于人数
                $scope.appointmentSettingsNetData.printSettings='';   //开课后多长时间不能打印小票
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        });

    }

    // $scope.missRecordsData();
    $scope.changeSortMiss = function (attr, sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);            //准备排序状态
        $scope.searchMissRecordsButton();
    };
    $scope.enterMissRecordsSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchMissRecordsButton();
        };
    };
    //点击搜索
    $scope.searchMissRecordsButton = function () {
        $scope.missRecordsData();
        //$scope.missRecordsListData();
    };
    //状态的 change 事件
    $scope.missRecordStartChange = function () {
        $scope.missRecordsData();
    };

    // 爽约记录 列表
    $scope.missRecordsListData = function () {
        $.loading.show();
        $http({
            method: 'get',
            url: $scope.missRecordsUrl
        }).then(function (data) {
            if (data.data.data != '' && data.data.data.length != 0) {
                $scope.missRecordsDatas = data.data.data;
                $scope.homePageNow = data.data.nowPage;
                $scope.missRecordsDataBool = false;
                $scope.detailPages = data.data.pages;
                $scope.punishMoneys = data.data.punishMoney
            } else {
                $scope.missRecordsDatas = data.data.data;
                $scope.homePageNow = data.data.nowPage;
                $scope.missRecordsDataBool = true;
                $scope.detailPages = data.data.pages;
                $scope.punishMoneys = data.data.punishMoney
            };
            $.loading.hide();
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        });
    };
    // $scope.missRecordsListData();
    /****************爽约记录******************/
    $scope.missRecordsData = function () {
        $http({method:'get',url:'/new-league/get-freeze-mode'}).then(function (datas) {
            $scope.ruleWay = datas.data.ruleWay
            $scope.initUrl234 = {
                keywords: $scope.missRecordsKeywords != undefined ? $scope.missRecordsKeywords : '',
                cardStatus: $scope.missRecordStart != undefined ? $scope.missRecordStart : '',
                venueId: $scope.venueId != undefined ? $scope.venueId : '',
                sortType: $scope.sortType != undefined ? $scope.sortType : '',
                sortName: $scope.sort != undefined ? $scope.sort : '',
                freeze_way :$scope.ruleWay.freeze_way != undefined ? $scope.ruleWay.freeze_way:'',
                freeze_day :$scope.ruleWay.freeze_day != undefined ? $scope.ruleWay.freeze_day :'',
                punish_money:$scope.ruleWay.punish_money != undefined ? $scope.ruleWay.punish_money:''
            }
            $scope.missRecordsUrl = '/new-league/mem-freeze-situation?' + $.param($scope.initUrl234);
            $scope.missRecordsListData();
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员")
        });

    };
    $scope.missRecordsData();
    $scope.skipPages = function (pages) {
        if (pages != undefined) {
            $scope.missRecordsUrl = '/new-league/mem-freeze-situation?page=' + pages;
            $scope.missRecordsListData();
        };
    };
    $scope.missOrders = function (urlPages) {
        $.loading.show();
        $http({
            method: 'get',
            url: urlPages
        }).then(function (data) {
            if (data.data.data != '' && data.data.data.length != 0) {
                $scope.missRecordsDatas = data.data.data;
                $scope.homePageNow = data.data.nowPage;
                $scope.missRecordsDataBool = false;
                $scope.detailPages = data.data.pages;
                $scope.punishMoneys = data.data.punishMoney
            } else {
                $scope.missRecordsDatas = data.data.data;
                $scope.missRecordsDataBool = true
                $scope.homePageNow = data.data.nowPage;
                $scope.detailPages = data.data.pages;
                $scope.punishMoneys = data.data.punishMoney
            };
            $.loading.hide();
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        });
    };
    //获取爽约里面的设置数据
    $scope.getMissSet123 = function(){
        $http({method:'get',url:'/new-league/get-freeze-mode'}).then(function (response) {
            $scope.historyMissSet123 = response.data.ruleWay;
            if(response.data.ruleWay.miss_about_times == undefined || response.data.ruleWay.miss_about_times ==''){
                $scope.monthlyNumberMiss = '';
            }else{
                $scope.monthlyNumberMiss = parseInt(response.data.ruleWay.miss_about_times);
            }
            var data =parseInt(response.data.ruleWay.freeze_way);
            if(data == 3){
                $scope.freezingMode = '';
                $scope.freezingModeFlag = false;
            }else if(data == 2){
                $scope.freezingModeFlag = true;
                $scope.freezingMode       = response.data.ruleWay.freeze_way;
                if(response.data.ruleWay.freeze_day == 0 ||response.data.ruleWay.freeze_day == null){
                    $('#freezingDays').val('');
                }else{
                    $('#freezingDays').val(parseInt(response.data.ruleWay.freeze_day));
                }
            }else{
                $scope.freezingModeFlag = false;
                $scope.freezingMode       = response.data.ruleWay.freeze_way;
                $('#freezingDays').val('');
            }

            if(response.data.ruleWay.punish_money != null && response.data.ruleWay.punish_money != ''){
                $('#penaltyAmount').val(parseInt(response.data.ruleWay.punish_money));
            }else{
                $('#penaltyAmount').val('');
            }
        });
    }
    //改变爽约类型
    $scope.freezingModeChange123 = function(id){
        if(id=='2'){
            $scope.freezingMode= '2';
            $scope.freezingModeFlag = true;
            if($scope.historyMissSet123.freeze_day == 0 ||$scope.historyMissSet123.freeze_day == null){
                $('#freezingDays').val('');
            }else{
                $scope.getMissSet123();
            }
        }else if(id=='1'){
            $scope.freezingMode= '1';
            $scope.freezingModeFlag = false;
            $('#freezingDays').val('');
        }else{
            $scope.freezingModeFlag = false;
            $('#freezingDays').val('');
        }
    }


    $scope.missSet = function () {
        $scope.getMissSet123();
        $('#missSet').modal('show');
        $timeout(function(){
            $scope.getMissSet123();
            $scope.freezingModeChange123();
        },2)
        // console.log($scope.historyMissSet123)
        // if($scope.ruleWay.freeze_way == '3') {
        //     $scope.freezingModeFlag = '';
        // }
        // $("#monthlyNumberMiss").val($scope.ruleWay.miss_about_times);
        // if($scope.ruleWay.freeze_way == '1') {
        //     $scope.freezingModeFlag = 1;
        //     $scope.freezingMode = $scope.ruleWay.freeze_way;
        // }
        // if($scope.ruleWay.freeze_way == '2') {
        //     $scope.freezingModeFlag = 2;
        //     $scope.freezingDays = parseInt($scope.ruleWay.freeze_day)
        //     $scope.freezingMode = $scope.ruleWay.freeze_way;
        // }
        // if($scope.ruleWay.punish_money == '0') {
        //     $('#penaltyAmount').val();
        //     return
        // }
        // $("#penaltyAmount").val($scope.ruleWay.punish_money)
    };
    //删除爽约设置
    $scope.missSetRemoveButton = function () {
        $("#penaltyAmount").val('')
        $http({method:'get',url:'/new-league/del-miss-about-set'}).then(function (data) {
            Message.success(data.data.message);
            $("#missSet").modal("hide");
            $scope.missRecordsData();

        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    }

    //爽约添加设置
    $scope.missSetButton = function () {
        if(!$scope.messageWarning($("#monthlyNumberMiss").val(), "请输入每月爽约次数")){return};
        if(! $scope.messageWarning($scope.freezingMode, "请选择冻结方式")){return};
        if ($scope.freezingMode == '2') {
            if ($("#freezingDays").val() == null || $("#freezingDays").val() == '' || $("#freezingDays").val() == undefined) {
                Message.warning("请输入冻结天数");
                return;
            };
        };
        var data ={
            freezeWay:$scope.freezingMode,
            missAboutTimes:$("#monthlyNumberMiss").val(),
            punishMoney:$("#penaltyAmount").val(),
            freezeNumDay:$('#freezingDays').val() == undefined ? "":$('#freezingDays').val(),
        }
        $http({method:'get',url:"/new-league/miss-about-set?"+$.param(data)}).then(function (data) {
            if(data.data.status == 'success'){
                Message.success(data.data.data);
                $("#missSet").modal("hide");
            };
            if(data.data.status == 'error'){
                Message.warning(data.data.data)
            };
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
        $scope.missRecordsData();
    };
    //详情数据
    $scope.memMissRecord = function (url) {
        $http({method: 'get', url: url}).then(function (data) {
            if(data.data.data != '' && data.data.data.length != ''){
                $scope.missDetailsData = data.data.data;
                $scope.memMissRecords = data.data.pages;
                $scope.nowPages = data.data.nowPage;
                $scope.missRecordsDataBools = false;
                $scope.missDeatilsNumber = data.data.totalCount;
            }else {
                $scope.missDetailsData = data.data.data;
                $scope.memMissRecords = data.data.pages;
                $scope.nowPages = data.data.nowPage;
                $scope.missRecordsDataBools = true;
                $scope.missDeatilsNumber = data.data.totalCount;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        });
    };
    //详情搜索
    $scope.missDetailsSearch = function () {
        if(!$scope.messageWarning($("#peopleNumberReservation").val(),"请选择时间")){return}
        $http({method: 'get', url: '/new-league/mem-absent-record?dateStart='+$("#peopleNumberReservation").val().substr(0,10)+"&dateEnd="+$("#peopleNumberReservation").val().substr(13,18)+"&venueId="+$scope.venueId+"&memberCard="+$scope.missDetailsCardId}).then(function (data) {
            if(data.data.data != '' && data.data.data.length != ''){
                $scope.missDetailsData = data.data.data
                $scope.nowPages = data.data.nowPage;
                $scope.memMissRecords = data.data.pages;
                $scope.missRecordsDataBools = false;
                $scope.missDeatilsNumber = data.data.totalCount;
            }else {
                $scope.missDetailsData = data.data.data;
                $scope.memMissRecords = data.data.pages;
                $scope.missRecordsDataBools = true;
                $scope.nowPages = data.data.nowPage;
                $scope.missDeatilsNumber = data.data.totalCount;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        });
    };

    //单个会员详情
    $scope.missDetails = function (id) {
        $scope.missDetailsCardId = id;
        $("#missDetails").modal("show");
        var display = $('#missDetails').css('display');
        if (display == 'none') {
            $('#peopleNumberReservation').val("");
        };
        $http({method: 'get', url: '/new-league/mem-absent-record?memberCard='+$scope.missDetailsCardId+"&venueId="+$scope.venueId}).then(function (data) {
            if(data.data.data != '' && data.data.data.length != ''){
                $scope.missDetailsData = data.data.data;
                $scope.nowPages = data.data.nowPage;
                $scope.memMissRecords = data.data.pages;
                $scope.missRecordsDataBools = false;
                $scope.missDeatilsNumber = data.data.totalCount;
            }else {
                $scope.missDetailsData = data.data.data;
                $scope.nowPages = data.data.nowPage;
                $scope.memMissRecords = data.data.pages;
                $scope.missRecordsDataBools = true;
                $scope.missDeatilsNumber = data.data.totalCount;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        });
    };

    $scope.Thaw = function (id,status,object) {
        $scope.thawId = id;
        $scope.thawStatus = status;
        $scope.punish_money123 = object.punish_money;
    };
    //解冻
    $scope.defrostAndPay = function (id,status) {
        $http({method:"get",url:'/new-league/thaw-member-card?memberCardId='+$scope.thawId +"&price="+$scope.punish_money123}).then(function (data) {
            if(data.data.status == 'success'){
                $("#Thaw").hide();
                Message.success("解冻成功")
            }else {
                $("#Thaw").hide();
                Message.warning("解冻失败");
                $(".modal-backdrop").hide();
            }
            $(".modal-backdrop").hide();
            $scope.missRecordsListData();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
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
        return theDate;
    };

    $scope.getNowFormatDates = function () {
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
        var currentdate = year + seperator1 + month + seperator1 + strDate + " " + hour + ":" + minute + ":" + second;
        return currentdate;
    }
    $scope.timestampConvertedToDate = function (nS) {
        var Y, M, D, W, H, I, S;

        function fillZero(v) {
            if (v < 10) {
                v = '0' + v;
            }
            return v;
        }

        var d = new Date(parseInt(nS / 1000) * 1000);
        Y = d.getFullYear();
        M = fillZero(d.getMonth() + 1);
        D = fillZero(d.getDate());
        H = fillZero(d.getHours());
        I = fillZero(d.getMinutes());
        S = fillZero(d.getSeconds());
        var localTimes = Y + '-' + M + '-' + D
        return localTimes;
    }

    $scope.getMyDates = function (str) {
        function fillZero(v) {
            if (v < 10) {
                v = '0' + v;
            }
            return v;
        }

        var d = new Date(parseInt(str / 1000) * 1000);
        H = fillZero(d.getHours());
        I = fillZero(d.getMinutes());

        var localTimes = H + ':' + I; //转换当前时间 yyyy-MM-dd hh:mm:ss
        return localTimes;
    }
    //定义公共验证方法
    $scope.messageWarning = function (data, success) {
        if (data == null || data == undefined || data == '') {
            Message.warning(success);
            return false;
        }
        else {
            return true;
        };
    };
});