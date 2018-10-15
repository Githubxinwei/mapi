/**
 * Created by 张亚鑫
 * Date: 2017/12/8
 * Time: 9:43
 * content:私教小团体课页面
 */
(function($) {
    'use strict';
    $(document).ready(function () {
        //时间插件启动
        $('.clockpicker').clockpicker()
            .find('input').on('change',function() {
        });
        //调用日期插件
        $('#chooseDate').daterangepicker(null, function(start, end, label) {});
    })
})(jQuery);
angular.module('App').controller('privateLessonGroupCtrl',function($scope,$http,$timeout){
    /***************************私教排课*************************************/
    //初始化场馆、课种
    $scope.initVenue = function () {
        //select场馆
        $http({
            url: '/site/get-auth-venue',
            method: 'get'
        }).then(function (data) {
            $scope.venueAll = data.data;
            //默认选中场馆
            // $scope.venueId = $scope.venueAll[0].id;
        }, function (error) {
            Message.error("系统错误请联系工作人员");
        });
        //select课种
        $http({
            method: 'get',
            url: "/rechargeable-card-ctrl/get-private-data"
        }).then(function (data) {
            $scope.choiceCourseNames = data.data.venue;
        }, function (error) {
            Message.error('系统错误请联系工作人员');
        });
    };
    $scope.initVenue();

    //标签页的点击切换
    $scope.tabsShow = 1;
    $scope.tabsToggle = function (_tabId) {
        switch (_tabId)
        {
            case 1:
                $scope.tabsShow = 1;
                $scope.arrangeClassData($scope.arrangeUrl);
                break;
            case 2:
                $scope.tabsShow = 2;
                $scope.attendClassData($scope.url);
                break;
            case 3:
                $scope.tabsShow = 3;
                $scope.getLessonGroupData();
                break;
        }
    };

    //获取私教排课列表数据
    $scope.arrangeClassData = function (url) {
        $.loading.show();
        $http.get(url).then(function (result) {
            // console.log(result.data.data)
            // $scope.arrangeClassList = result.data.data;
            // $scope.arrangeClassPage = result.data.pages;
            if(result.data.data == "" || result.data.data == undefined || result.data.data.length == undefined){
                $scope.arrangeClassList = result.data.data;
                $scope.arrangeClassPage = result.data.pages;
                $scope.arrangeDataInfo = true;
            } else {
                $scope.arrangeClassList = result.data.data;
                $scope.arrangeClassPage = result.data.pages;
                $scope.arrangeDataInfo = false;
            }
            $.loading.hide();
        });
    };
    $scope.chooseVenue  = '';
    $scope.chooseCourse = '';
    $scope.keywords     = '';
    $scope.arrangeUrl = '/private-lesson-group/arrange-class-list?keywords='+$scope.keywords +'&venueId='+ $scope.chooseVenue+'&courseId='+$scope.chooseCourse;
    $scope.arrangeClassData($scope.arrangeUrl);
    //私教排课列表分页
    $scope.arrangeClassPages = function (urlPages) {
        $scope.arrangeClassData(urlPages);
    };

    //回车键搜索
    $scope.enterSearchA = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){
            $scope.buttonSearchA();
        }
    };
    //搜索按钮 搜索
    $scope.buttonSearchA = function () {
        if($scope.keywords == null || $scope.keywords == undefined ){
            $scope.keywords = '';
        }
        if($scope.chooseVenue == null || $scope.chooseVenue == undefined ){
            $scope.chooseVenue = '';
        }
        if($scope.chooseCourse == null || $scope.chooseCourse == undefined ){
            $scope.chooseCourse = '';
        }
        $.loading.show();
        $http({method:"get",url:'/private-lesson-group/arrange-class-list?keywords='+$scope.keywords +'&venueId='+ $scope.chooseVenue+'&courseId='+$scope.chooseCourse}).then(function (result) {
            $scope.arrangeClassList = result.data.data;
            $scope.arrangeClassPage = result.data.pages;
            if(result.data.data == "" || result.data.data == undefined || result.data.data.length == undefined){
                $scope.arrangeDataInfo = true;
            } else {
                $scope.arrangeDataInfo = false;
            }
            $.loading.hide();
        },function (error) {
            Message.error('系统错误请联系工作人员');
            $.loading.hide();
        })
    };
    /***清空搜索*****/
    $scope.searchClearA = function () {
        $scope.chooseVenue  = '';
        $scope.chooseCourse = '';
        $scope.keywords     = '';
        $scope.arrangeClassData($scope.arrangeUrl);
    };

    //点击 排课
    $scope.weekClassData = function (id,start_time,time) {
        var timestamp = Date.parse(new Date());
            timestamp = timestamp / 1000;
        if(time < timestamp && start_time != null){
            Message.warning('此课程已过期');
            return;
        }
        $scope.classNumberId = id;
        $scope.weekData();
        $("#weekClassModal").modal("show");
    };
    //获取课程表数据
    $scope.weekData = function () {
        if($scope.chooseVenue == null || $scope.chooseVenue == undefined ){
            $scope.chooseVenue = '';
        }
        console.log($scope.chooseVenue)
        $http({
            method: 'get',
            url: '/private-lesson-group/charge-group-class?venueId=' + $scope.chooseVenue
        }).then(function (result) {
            $scope.listData = result.data;
        },function (error) {
            Message.error('系统错误请联系工作人员');
        });
    };

    //日期跳转下周
    $scope.isBOOLDate = 1;
    $scope.dateNext = function () {
        if ($scope.isBOOLDate == 1) {
            $scope.isBOOLDate = 2;
        }
        var date = $scope.listData.week7[0].class_date;
        date = new Date(date);
        var date1 = date.valueOf();
        $scope.dateSta1 = date1 + 24 * 60 * 60 * 1000;
        $scope.dateSta2 = date1 + 24 * 60 * 60 * 1000 * 7;
        $scope.initTemplatew();
        $('.nowWeek').hide();
        $('.nextWeek').show();
        $('.modelBox').hide();
        $('.modelNext').show();
    };
    //下周课表数据
    $scope.initTemplatew = function () {
        var dataSta = new Date($scope.dateSta1).toLocaleDateString();
        var dataEnd = new Date($scope.dateSta2).toLocaleDateString();
        if($scope.chooseVenue == null || $scope.chooseVenue == undefined ){
            $scope.chooseVenue = '';
        }
        $http({
            method: 'get',
            url: '/private-lesson-group/week-data-next?weekStart=' + dataSta + '&weekEnd=' + dataEnd +'&venueId='+ $scope.chooseVenue
        }).then(function (data) {
            $scope.listDataN = data.data;
        }, function (error) {
            Message.error('系统错误请联系工作人员');
        });
    };
    //日期跳转上周
    $scope.dateBack = function () {
        if ($scope.isBOOLDate == 2) {
            $scope.isBOOLDate = 1;
        }
        $scope.weekData();
        $('.nowWeek').show();
        $('.nextWeek').hide();
        $('.modelBox').show();
        $('.modelNext').hide();
    };
    //点击添加排课列表
    var boxHeight = $('.infoBox').height();
    $scope.hoverAdd = function () {
        $('.contentBoxAdd').append(
            "<div class='addBox' data-toggle='modal' data-target='#myModal3' ng-click=‘classClick()’>" +
                "<p class='hoverP hoverPs' style='margin-top: 35px;'>点击进行排课</p>" +
            "</div>");
        if (boxHeight < 0) {
            $('.addBox').height(83);
            $('.hoverBox').height(83);
        } else {
            $('.addBox').height(boxHeight);
            $('.hoverBox').height(boxHeight);
        }
    };
    // 课程表 点击进行排课
    $scope.classClick = function (data) {
        //判断课程是否已上完或排完
        $http.get('/private-lesson-group/is-over-class?classNumId=' + $scope.classNumberId + '&date=' + data).then(function (result) {
            if (result.data.data != true) {
                Message.warning(result.data.data);
                return;
            }else{
                //初始化页面
                $scope.changeCoachsBOOL = true;
                $scope.dayStart         = '';
                $scope.dayEnd           = '';
                $scope.numAndLength     = '';
                //模态框显示
                $("#myModal3").modal("show");
            }
        });
        //获取点击的日期
        $scope.listDataWeekClass_date = data;
    };
    // 点击选择教练的模态框显示
    $scope.boxText = function () {
        $http({
            method: 'get',
            url: "/private-teach/employee-info"
        }).then(function (success) {
            $scope.ChangeCoachsBoxText = success.data;
        }, function (error) {
            Message.error('系统错误请联系工作人员');
        });
        $("#myModal4").modal("show");
    };
    //选择单个教练
    $scope.changeCoachsClick = function (id, name, pic) {
        $scope.changeCoachsBOOL = false;
        $scope.changeCoachsID   = id;
        $scope.changeCoachsName = name;
        $scope.changeCoachsPic  = pic;
        $("#myModal4").modal("hide");
    };
    //选择教练 鼠标进入
    $scope.changeCoachs = function (event, i) {
        $a = event.target;
        $(".changeCoachsName").eq(i).addClass("fontColor");
        $(".changeCoachsDiv").eq(i).addClass("rgb248");
        $(".changeCoachs").eq(i).addClass("displayInlineBlock");
        $(".changeCoachs").eq(i).removeClass("displayInlineBlocks");
    };
    //选择教练 鼠标离开
    $scope.changeCoachLeave = function (e, i) {
        $(".changeCoachsName").removeClass("fontColor");
        $(".changeCoachsDiv").removeClass("rgb248");
        $(".changeCoachs").eq(i).removeClass("displayInlineBlock");
        $(".changeCoachs").eq(i).addClass("displayInlineBlocks");
    };

    //选择开始时间 自动获取结束时间
    $scope.dayStartModel = function (dayStart) {
        if(dayStart != undefined && dayStart != null && dayStart != ''){
            var end = $scope.listDataWeekClass_date + " " + dayStart;
            //获取课程时长
            $http.get('/private-lesson-group/get-end-time?classNumId=' + $scope.classNumberId + '&dayStart=' + end).then(function (result) {
                $scope.numAndLength = result.data;
                //获取结束时间
                var date = $scope.getNowFormatDates() + " " + dayStart;
                var timestamp = Date.parse(new Date(date));
                $scope.dayEnd = timestamp + ( $scope.numAndLength.length * 60 * 1000);
                $scope.dayEnd = $scope.getMyDates($scope.dayEnd);
            });
        }
    };
    //整理时间
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
        var currentDate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        return currentDate;
    };
    //转换时间
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
    };

    //排课 点击完成
    $scope.classChange = function () {
        var data = {
            classNumberId : $scope.classNumberId,//私课编号id
            date          : $scope.listDataWeekClass_date,//排课日期
            start         : $scope.dayStart, //上课 开始时间
            end           : $scope.dayEnd,//上课 结束时间
            coachId       : $scope.changeCoachsID, //教练id
            _csrf_backend : $('#_csrf').val()
        };
        if($scope.changeCoachsID == null || $scope.changeCoachsID == undefined || $scope.changeCoachsID ==''){
            Message.warning('请选择教练');
            return;
        }
        if($scope.dayStart == null || $scope.dayStart == undefined || $scope.dayStart ==''){
            Message.warning('请选择开始时间');
            return;
        }
        if($scope.dayEnd == null || $scope.dayEnd == undefined || $scope.dayEnd ==''){
            Message.warning('请选择结束时间');
            return;
        }
        if($scope.dayEnd <= $scope.dayStart){
            Message.warning('结束时间点不正确');
            return;
        }
        $http({
            method  : 'post',
            url     : "/private-lesson-group/add-arrange-class",
            data    : $.param(data),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == 'success') {
                Message.success(data.data.data);
                $("#myModal3").modal("hide");
                //刷新本周数据
                $scope.weekData();
                //刷新下周数据
                $scope.initTemplatew();
                //刷新私教上课列表数据
                $scope.attendClassData($scope.url);
            }else{
                Message.warning(data.data.data);
                return;
            }
        }, function (error) {
            Message.error('系统错误请联系管理人员');
        });
    };
    /***************************私教上课*************************************/
    //初始化获取当月的第一天和最后一天
    $scope.getMonthOneAndMonthLast = function(){
        var date = new Date();
        $scope.startDate =$scope.getMyDate(date.setDate(1));
        var currentMonth=date.getMonth();
        var nextMonth=++currentMonth;
        var nextMonthFirstDay=new Date(date.getFullYear(),nextMonth,1);
        var oneDay=1000*60*60*24;
        $scope.endDate = $scope.getMyDate(nextMonthFirstDay-oneDay);
        $('#chooseDate').val($scope.startDate+' - '+ $scope.endDate);
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

    //获取私教上课列表数据
    $scope.attendClassData = function (url) {
        $.loading.show();
        $http.get(url).then(function (result) {
            $scope.attendClassList = result.data.data;
            $scope.aboutNum        = result.data.aboutNum;
            $scope.attendClassPage = result.data.pages;
            $scope.pagesNow        = result.data.now;
            $.loading.hide();
        });
    };
    $scope.searchWords = '';
    $scope.dateStart   = $scope.startDate;
    $scope.dateEnd     = $scope.endDate;
    $scope.venueIds    = '';
    $scope.url = '/private-lesson-group/attend-class-list?keyword='+$scope.searchWords +'&startTime='+ $scope.dateStart+'&endTime='+$scope.dateEnd+'&venueId='+$scope.venueIds;
    $scope.attendClassData($scope.url);
    //私教上课列表分页
    $scope.replacementPages = function (urlPages) {
        $scope.attendClassData(urlPages);
    };

    //截取时间 分为开始 和结束时间
    $scope.getTimeDate = function () {
        var date = $("#chooseDate").val();
        if (date == ''){
            $scope.dateStart = "";
            $scope.dateEnd   = "";
        }else {
            $scope.dateStart = $("#chooseDate").val().substr(0,10);
            $scope.dateEnd   = $("#chooseDate").val().substr(-10,10);
        }
    };

    //回车键搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){
            $scope.buttonSearch();
        }
    };
    //搜索按钮 搜索
    $scope.buttonSearch = function () {
        $scope.getTimeDate();
        if($scope.searchWords == null || $scope.searchWords == undefined ){
            $scope.searchWords = '';
        }
        if($scope.venueIds == null || $scope.venueIds == undefined ){
            $scope.venueIds = '';
        }
        $.loading.show();
        $http({method:"get",url:'/private-lesson-group/attend-class-list?keyword='+$scope.searchWords +'&startTime='+ $scope.dateStart+'&endTime='+$scope.dateEnd+'&venueId='+$scope.venueIds}).then(function (result) {
            $scope.attendClassList = result.data.data;
            $scope.aboutNum        = result.data.aboutNum;
            $scope.attendClassPage = result.data.pages;
            $scope.pagesNow        = result.data.now;
            if(result.data.data == "" || result.data.data == undefined || result.data.data.length == undefined){
                $scope.attendDataInfo = true;
            } else {
                $scope.attendDataInfo = false;
            }
            $.loading.hide();
        },function (error) {
            Message.error('系统错误请联系工作人员');
            $.loading.hide();
        })
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchWords = '';
        $scope.venueIds    = '';
        $("#chooseDate").val('');
        $scope.attendClassData($scope.url);
    };

    //点击查看详情
    $scope.aboutDetailsClick = function (id) {
        $scope.chargeGroupId = id;
        //查看详情 获取此课程信息
        $http.get('/private-lesson-group/one-class-data?chargeGroupId=' + id).then(function (result) {
            $scope.classDetails = result.data;
        });
        //查看详情 获取预约详情
        $http.get('/private-lesson-group/about-detail?chargeGroupId=' + id).then(function (result) {
            $scope.aboutDetails = result.data;
        });
        $("#aboutDetailsModal").modal("show");
    };

    //登记上课
    $scope.registerClass = function () {
        $http.get('/private-lesson-group/register-class?chargeGroupId=' + $scope.chargeGroupId).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                //刷新预约详情数据
                $scope.aboutDetailsClick($scope.chargeGroupId);
                //刷新私教上课列表数据
                $scope.attendClassData($scope.url);
            }else{
                Message.warning(result.data.data);
                return;
            }
        });
    };
    //取消课程
    $scope.cancelClass = function () {
        $http.get('/private-lesson-group/cancel-class?chargeGroupId=' + $scope.chargeGroupId).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                $("#aboutDetailsModal").modal("hide");
                //刷新私教上课列表数据
                $scope.attendClassData($scope.url);
            }else{
                Message.warning(result.data.data);
                return;
            }
        });
    };
    //提前下课
    $scope.advanceClass = function () {
        $http.get('/private-lesson-group/advance-class?chargeGroupId=' + $scope.chargeGroupId).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                //刷新预约详情数据
                $scope.aboutDetailsClick($scope.chargeGroupId);
                //刷新私教上课列表数据
                $scope.attendClassData($scope.url);
            }else{
                Message.warning(result.data.data);
                return;
            }
        });
    };
    //会员下课打卡
    $scope.overClassModal = function (id,member_id,end) {
        localStorage.removeItem('fpTemplate');
        var myDate = new Date().getTime();
        if(end*1000 >= myDate){
            Message.warning('课程还没有结束，请继续上课');
            return;
        }
        $scope.aboutId  = id;
        $scope.memberId = member_id;
        //获取会员指纹
        $http.get("/private-teach/get-fingerprint?id=" + member_id).then(function (data) {
            $scope.fingerprintData = data.data.fingerprint;
            if($scope.fingerprintData == null || $scope.fingerprintData == undefined){
                $("#fingerprintInput").modal("show");
                var domdiv = document.getElementById('box');
                domdiv.style.display = 'none';
            }else{
                $('#fingerprintVerification').modal('show');
            }
        },function (errer) {
            console.log(errer);
            Message.error("系统错误请联系工作人员");
        });
        fpVerification("指纹比对", "请安装指纹驱动或启动服务", true, globalContext)
    };

    //指纹登记
    $scope.clickFingerprint = function () {
        //取出指纹
        var regTemplate = JSON.parse(localStorage.getItem('fpTemplateAdd')).fingerprintData;
        //发送请求保存指纹
        $http({method:'post',url:'/private-teach/input-fingerprint',data:$.param({
            fingerprint:regTemplate,
            memberId:$scope.memberId
        }), headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            // console.log(data)
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员");
        });
        fpVerification("指纹比对", "请安装指纹驱动或启动服务", true, globalContext);
        $("#fingerprintInput").modal("hide");
        $('#fingerprintVerification').modal('show');
    };
    //指纹下课验证
    $scope.overClass = function () {
        var newF = localStorage.getItem("fpTemplate");
        if (newF == '' || newF == undefined || newF == null) {
            Message.warning("请验证指纹");
            return;
        }
        var newFinger = JSON.parse(newF).fingerprintData;
        var oldFinger = $scope.fingerprintData;
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1:22001/ZKBIOOnline/fingerprint/verify",
            dataType: "json",
            data: JSON.stringify({
                'reg': newFinger,
                'ver': oldFinger
            }),
            async: true,
            success: function (data) {
                //返回码
                var ret = null;
                ret = data.ret;
                //接口调用成功返回时接口调用成功返回时
                if (ret == 0 && data.score != undefined) {
                    $http.get('/private-lesson-group/over-class?aboutId=' + $scope.aboutId).then(function (result) {
                        if (result.data.status == 'success') {
                            Message.success(result.data.data);
                            $("#fingerprintVerification").modal("hide");
                            //刷新预约详情数据
                            $scope.aboutDetailsClick($scope.chargeGroupId);
                            localStorage.removeItem('fpTemplate');
                            // window.location.replace('/private-lesson-group');
                        }else{
                            Message.warning(result.data.data);
                            return;
                        }
                    });
                } else {
                    if(data.score == 'undefined'){
                        Message.warning("请重新放置手指");return;
                    }
                    if(ret == -6){
                        Message.warning("您的指纹无效");return;
                    }
                    Message.warning("检查指纹是否录入或指纹是否匹配")
                }
            },error: function (XMLHttpRequest, textStatus, errorThrown) {
                Message.warning("请安装指纹驱动或启动该服务!")
            }
        });
    };
    /***************************私教多人课*************************************/

    var peopleId = '';           //获取人数区间表中的数据
    //初始化
    $scope.initLessonGroup = function () {
        $scope.pageInitUrl = '/private-lesson-group/charge-class';
        $scope.getLessonGroupData();          //获取购买私课区间
        $scope.getPrivateCoach();             //购买私课-获取销售私教下拉框数据
        $scope.getSellSources();              //私课购买-获取销售渠道
        $scope.num = 0;
        $scope.addPayWaysHtml();
    }
    //搜索按钮 搜索
    $scope.buttonSearchC = function () {
        if($scope.keywords == null || $scope.keywords == undefined ){
            $scope.keywords = '';
        }
        if($scope.chooseVenue == null || $scope.chooseVenue == undefined ){
            $scope.chooseVenue = '';
        }
        if($scope.chooseCourse == null || $scope.chooseCourse == undefined ){
            $scope.chooseCourse = '';
        }
        $.loading.show();
        $http({method:"get",url:'/private-lesson-group/charge-class?keywords='+$scope.keywords +'&venueId='+ $scope.chooseVenue+'&courseId='+$scope.chooseCourse}).then(function (result) {
            $scope.datas = result.data.data;
            $scope.pageClass = result.data.pages;
            if(result.data.data == "" || result.data.data == undefined || result.data.data.length == undefined){
                $scope.dataInfo = true;
            } else {
                $scope.dataInfo = false;
            }
            $.loading.hide();
        },function (error) {
            Message.error('系统错误请联系工作人员');
            $.loading.hide();
        })
        //$scope.searchClearC();
    };
    /***清空搜索*****/
    $scope.searchClearC = function () {
        $scope.chooseVenue  = '';
        $scope.chooseCourse = '';
        $scope.keywords     = '';
        $scope.buttonSearchC();
    };
    //获取数据
    $scope.getLessonGroupData = function () {
        $.loading.show();
        $http({
            method : 'get',
            url    : $scope.pageInitUrl,
        }).then(function (result) {
            if (result.data.data == '' || result.data.data == undefined || result.data.data.length == undefined) {
                $scope.datas = result.data.data;
                $scope.pageClass = result.data.pages;
                //console.log('$scope.datas',$scope.datas);
                $scope.dataInfo = true;
            } else {
                $scope.datas = result.data.data;
                $scope.pageClass = result.data.pages;
                $scope.dataInfo = false;
                //console.log('$scope.datas',$scope.datas);
            }
            $.loading.hide();
        })
    }
    
    $scope.classPageFun       = function (url) {
        $scope.pageInitUrl = url;
        $scope.getLessonGroupData();
    };

    //获取购买私课区间
    $scope.getPriceLimitData = function (chargeId,name,pic,type,original_price) {
        //类型:1多课程，2单课程
        if (type == 1) {
            $scope.httpUrl = '/private-lesson-group/get-server-limit?chargeId='+chargeId;
        }
        if (type == 2) {
            $scope.httpUrl = '/private-lesson-group/get-lesson-limit?chargeId='+chargeId;
        }
        $http.get($scope.httpUrl).then(function (result) {
            if (result.data != '' || result.data != undefined || result.data.length != undefined){
                $scope.limitDatas = result.data;
                $scope.limitDatas.name = name;
                $scope.limitDatas.pic = pic;
                $scope.type = type;
                $scope.limitDatas.original_price = original_price;
                $scope.limitDatas.type = type;
                if(result.data.length == 0){
                    $scope.nolimitDatas = true;
                }else{
                    $scope.nolimitDatas = false;
                }
            } else  {
                $scope.nolimitDatas = true;
            }
        })
    }
    //私课购买-获取人数区间id
    $scope.dataInfos = '';
    $scope.primePrice = '';
    $scope.clickSelector = function ($peopleId,$type,total_class_num,numberId) {
        peopleId = $peopleId;
        $scope.cardNumber = '';
        $http.get('/private-lesson-group/get-charge-info?peopleId='+peopleId).then(function (result) {
            var price = 0;
            if (result.data[0]) {
                $scope.dataInfos = result.data[0];
                $scope.dataInfos.total_class_num = total_class_num;
                $scope.dataInfos.numberId = numberId;
                $http.get('/member/judge-member?numberId='+numberId).then(function (reponse) {
                    if (reponse.data.status == 'success') {
                        price = parseFloat($scope.dataInfos.pos_price);
                    }else{
                        price = parseFloat($scope.dataInfos.unit_price);
                    }
                    if ($type == 1) {
                        $scope.primePrice = price;
                    }
                    if ($type == 2) {
                        $scope.primePrice = price * parseInt(total_class_num);
                    }
                })
            }

        })
    }



    //购买私课-获取销售私教下拉框数据
    $scope.getPrivateCoach = function () {
        $http.get('/private-teach/private-coach').then(function (data) {
            $scope.privateCoach = data.data;
        },function (error) {
            Message.error("系统错误请联系工作人员");
        })
    }

    //私课购买-获取销售渠道
    $scope.getSellSources = function () {
        $http({
            url: '/potential-members/get-source?configType=charge',
            method: 'get'
        }).then(function (data) {
            $scope.memberSearchData = data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };

    //购买私课-新增付款途径
    $scope.addPayWaysHtml = function () {
        $scope.attr = 'payWaysHtml';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.attr+'&num='+$scope.num).then(function (result) {
            $scope.payWaysHtml = result.data.html;
        })
    }

    //购买私课-获取支付价格
    $scope.totalPayPrice = 0;
    $scope.getPayTotalPrice = function () {
        $scope.payWays = [];
        $scope.payPrice = [];
        var ul = $('#payWays').children('.removeDiv');
        ul.each(function (i,v) {
            var payMethod = $(this).find('select').val();
            var payPrice = $(this).find('input').val();
            //console.log(payPrice);
            if (payPrice == '') {
                payPrice = 0;
            }
            $scope.payWays.push(payMethod);
            $scope.payPrice.push(payPrice);
        })
    }
    //付款途径初始化
    $scope.getPayTotalPriceInit = function () {
        var ul = $('#payWays').children('.removeDiv');
        ul.each(function (i,v) {
            var payMethod = $(this).find('select').val('');
            var payPrice = $(this).find('input').val('');
        })
    }
    $scope.waysRepeat = function (ways) {
        $timeout(function(){
            $scope.getPayTotalPrice();
            if($scope.isRepeat($scope.payWays)) {
                Message.warning('付款途径不能有重复！');
                return;
            }
        },100)
    }
    $scope.countPayPrice = function () {
        $scope.getPayTotalPrice();
        $scope.totalPayPrice = parseFloat($scope.isPrice($scope.payPrice));
    }
    $scope.getTotalPrice = function (price) {
        $timeout(function () {
            $scope.countPayPrice();
        },100)
    }
    //判断支付途径是否重复
    $scope.isRepeat = function (arr) {
        var hash = {};
        for(var i in arr) {
            if(hash[arr[i]])
                return true;
            hash[arr[i]] = true;
        }
        return false;
    }
    $scope.isPrice = function (arr) {
        var result = 0;
        for(var i = 0; i < arr.length; i++) {
            result += parseFloat(arr[i]);
        }
        return result;
    }

    $scope.initLessonGroup();
    //购买私课-搜索会员
    $scope.userInfo = '';
    //初始化私课购买数据
    $scope.payMoneyDataInit = function () {
        $scope.create_at = '';
        $scope.coachId = '';
        $scope.saleType = '';
        $scope.payType = '';
        $scope.giftType = '';
        $scope.buyNote = '';
        $scope.totalPayPrice = '';
    }
    var member_id = '';
    $scope.searchCardMember = function () {
        $scope.payMoneyDataInit();
        $scope.getPayTotalPriceInit();
        if ($scope.cardNumber == '' || $scope.cardNumber == undefined) {
            Message.warning('请输入会员卡卡号');
            return false;
        }
        $http.get('/private-lesson-group/check-member-info?cardNumber=' + $scope.cardNumber).then(function (response) {
            if (response.data.status == 'success') {
                $scope.userInfo = response.data.data;
                member_id = $scope.userInfo[0].member_id;
                $('#henghengModal').modal('show');
            } else {
                Message.warning('会员卡不存在,请输入正确的卡号!');
            }
        })
    }
    //私课购买-获取表单数据
    $scope.getDatas = function () {
        $scope.getPayTotalPrice();
        return {
            peopleId            : peopleId,                                 //人数区间id
            coachId             : $scope.coachId,                               //销售私教id
            saleType            : $scope.saleType,                             //销售渠道
            payType             : $scope.payType,                               //收款方式
            giftType            : $scope.giftType,                           //赠品:1.未领取2.已领取
            primePrice          : $scope.primePrice,                         //最终应付金额
            buyNote             : $scope.buyNote,                               //私课备注
            create_at           : $scope.create_at,
            product_name        : $scope.dataInfos.name,
            course_amount       : $scope.dataInfos.total_class_num,
            memberId            : member_id,                             //会员id
            numberId            : $scope.dataInfos.numberId,             //私课编号id
            _csrf_backend       : $('#_csrf').val()
        }
    }

    /**
     * 数据验证规则
     */
    //公共规则验证
    $scope.commonRule = function (attr,text) {
        if(!attr){Message.warning(text);return false;}return true;
    };
    //金额比较
    $scope.comparePrice = function (a,b,text) {
        if (parseFloat(a) != parseFloat(b)) {
            Message.warning(text);return false;
        }
        return true;
    }
    $scope.dataRules = function () {
        if (!$scope.commonRule($scope.create_at,'请填写缴费日期!')) { return false; }
        if (!$scope.commonRule($scope.coachId,'请选择销售私教!')) { return false; }
        if (!$scope.commonRule($scope.saleType,'请选择销售渠道!')) { return false; }
        if (!$scope.comparePrice($scope.primePrice,$scope.totalPayPrice,'金额不符,请重新填写!')) { return false; }
        if (!$scope.commonRule($scope.payType,'请选择收款方式!')) { return false; }
        if (!$scope.commonRule($scope.giftType,'请选择赠品领取状态!')) { return false; }
        if (!$scope.commonRule($scope.giftType,'请填写应付金额!')) { return false; }
        return true;
    }
    $scope.postHttp = function () {
        $scope.data = $scope.getDatas();
        var url = '/private-lesson-group/save-member-charge';
        if ($scope.dataRules()) {
            $http({
                method : 'POST',
                url : url,
                data : $.param($scope.data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (response) {
                if (response.data.status === 'success') {
                    Message.success('购买成功');
                    window.location.href='/private-lesson-group/index?';
                }else {
                    Message.warning(response.data.data);
                }
            })
        }
        }

});

