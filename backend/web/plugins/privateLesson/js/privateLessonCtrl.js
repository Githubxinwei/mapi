/**
 * Created by DELL on 2017/5/24.
 */
var app = angular.module('App');

//私课排期页面
app.controller('privateTimetableCtrl', function ($scope, $rootScope, $http) {
    $scope.skipDetail = function (id) {
        $scope.coachId = id;
        location.href = '/private-lesson/schedule-detail?id=' + id;
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
    });
    $scope.goLog = function (orderServiceId) {
        window.location.href = "#/serviceLogs?orderServiceId=" + orderServiceId;
    };
    $scope.goLog($scope.coachId);

    //获取所有的场馆
    $http.get('/site/get-auth-venue').then(function (result) {
        $scope.venuesLists = result.data;
    });
    //获取场馆对应的教练
    $scope.venueChange = function () {
        var id = $('#venueChange').val();
        if (id != '') {
            $scope.pageInitUrl = '/private-teach/employee-info?pid=' + id;
            $scope.getData();
        }
    };

    //私课课程
    $(function () {
        $(document).on('mouseover', '#tbody tr', function () {
            $(this).find('.displayBlock').css('opacity', '1');
        });
        $(document).on('mouseleave', '#tbody tr', function () {
            $(this).find('.displayBlock').css('opacity', '0');
        });
    });

    /*
     *私课排期
     */
    $scope.init = function () {
        /*所有私教列表*/
        $scope.pageInitUrl = '/private-teach/employee-info';
        $scope.getData();

    };
    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data == "" || result.data == undefined || result.data.length == undefined) {
                $scope.datas = result.data;
                $scope.pages = result.pages;
                $scope.dataInfo = true;
            } else {
                $scope.datas = result.data;
                $scope.pages = result.pages;
                $scope.dataInfo = false;
            }
            $.loading.hide();
        });
    };
    $scope.init();
});
// 爽约
app.controller('privateRecordCtrl', function ($scope, $http, $rootScope, $timeout) {
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
    });
    $(function () {
        $('#unAppointmentDate').daterangepicker(null, function (start, end, label) {
        });
    });
    //获取所有的场馆
    $http.get('/site/get-auth-venue').then(function (result) {
        $scope.venuesLists = result.data;
    });
    //点击跳转会员爽课详情
    $scope.record = function (id, object) {
        $scope.memberDetail = object;
        $scope.memberDetailId = id;
        $scope.unAppointmentUrlInit = '/private-lesson/member-miss-record-detail?memberId=' + $scope.memberDetailId + '&' + $.param($scope.getMemberUnAppointmentData());
        $scope.getUnAppointmentDetail();
        $timeout(function () {
            $('#boundCabinet').modal('show');
        }, 200)
    };

    $scope.getMemberUnAppointmentData = function () {
        return {
            keyword: $scope.keyWords != undefined && $scope.keyWords != '' ? $scope.keyWords : null,
            type: $scope.classListType132 != undefined && $scope.classListType132 != '' ? $scope.classListType132 : null,
            sort: $scope.unAppointmentType != undefined && $scope.unAppointmentType != '' ? $scope.unAppointmentType : null,
            startTime: $scope.unAppointmentStartDate != undefined && $scope.unAppointmentStartDate != '' ? $scope.unAppointmentStartDate : null,
            endTime: $scope.unAppointmentEndDate != undefined && $scope.unAppointmentEndDate != '' ? $scope.unAppointmentEndDate : null,
            venueId :$scope.myValue != undefined && $scope.myValue != ''? $scope.myValue :null,
        }
    };
    //所有会员的爽约记录
    $scope.getAllMemberUnAppointment = function () {
        $.loading.show();
        $http.get($scope.searchUnAppointmentUrlInit).then(function (response) {
            if (response.data.data.length != 0) {
                $scope.unAppointmentLists = response.data.data;
                $scope.unAppointmentNow = response.data.now;
                $scope.unAppointmentPages = response.data.pages;
                $scope.unAppointmentFlag = false;
            } else {
                $scope.unAppointmentLists = response.data.data;
                $scope.unAppointmentNow = response.data.now;
                $scope.unAppointmentPages = response.data.pages;
                $scope.unAppointmentFlag = true;
            }
            $.loading.hide();
        });
    };
    $scope.searchUnAppointmentSubmit = function () {
        var date = $('#unAppointmentDate').val();
        if (date != '') {
            var startTime = date.substr(0, 10);
            var endTime = date.substr(-10, 10);
            $scope.unAppointmentStartDate = startTime + ' ' + '00:00:01';
            $scope.unAppointmentEndDate = endTime + ' ' + '23:59:59';
        }
        $scope.searchUnAppointmentUrlInit = '/private-lesson/member-miss-record?' + $.param($scope.getMemberUnAppointmentData());
        $scope.getAllMemberUnAppointment();
    };
    //关键字搜索
    $scope.keyWordsSearch = function () {
        $scope.searchUnAppointmentSubmit();
        // $scope.searchUnAppointmentUrlInit = '/private-lesson/member-miss-record?keyword=' + $scope.keyWords;
        // $scope.getAllMemberUnAppointment();
    };
    //input键盘事件
    $scope.keyWordsEnter = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.keyWordsSearch();
        }
    };

    //初始化调用
    $scope.searchUnAppointmentSubmit();

    //分页
    $scope.missPage = function (urlPages) {
        $scope.searchUnAppointmentUrlInit = urlPages;
        $scope.getAllMemberUnAppointment();
    };

    //获取爽约记录详情
    $scope.getUnAppointmentDetail = function (id) {
        $.loading.show();
        $http.get($scope.unAppointmentUrlInit).then(function (response) {
            if (response.data.data.length != 0) {
                $scope.memberUnAppointmentLists = response.data.data;
                $scope.memberUnAppointmentNow = response.data.now;
                $scope.memberUnAppointmentPages = response.data.pages;
                $scope.totalNum = response.data.totalNum;
                $scope.memberUnAppointmentFlag = false;
            } else {
                $scope.memberUnAppointmentLists = response.data.data;
                $scope.memberUnAppointmentNow = response.data.now;
                $scope.memberUnAppointmentPages = response.data.pages;
                $scope.totalNum = response.data.totalNum;
                $scope.memberUnAppointmentFlag = true;
            }
            $.loading.hide();
        });
    };

    $scope.missDetailPage = function (urlPages) {
        $scope.unAppointmentUrlInit = urlPages;
        $scope.getUnAppointmentDetail();
    };

    //私课爽约清空功能
    $scope.searchUnAppointmentSubmitClear = function(){
        $scope.keyWords = '';
        $scope.myValue  = '';
        $('#select2-missVenueChange-container').text('请选择场馆');
        $('#unAppointmentDate').val('');
        $scope.unAppointmentStartDate = '';
        $scope.unAppointmentEndDate   = '';
        $scope.classListType132  = '';
        $scope.unAppointmentType = '';
        $scope.searchUnAppointmentSubmit();
    }
});
//私教上课页面
app.controller('privateClassCtrl', function ($scope, $http, Upload, $rootScope, $interval) {
    $scope.currentTimeS = new Date().getTime() / 1000;
    /**
     *将时间戳转为日期
     * @create 2017/5/29
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
    /**
     *后台员工管理 - 员工信息查询 - 访问Personnel控制器的actionEmployeeInfo()
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/24
     * @return bool|string
     */
    $scope.classStatus = '3';
    //获取所有的场馆
    $http.get('/site/get-auth-venue').then(function (result) {
        $scope.venuesLists = result.data;
    });
    $scope.init = function () {
        // $scope.pageInitUrl = '/private-teach/coach-class';
        // $scope.getData();
        // $scope.initPath();
        $scope.searchCard();
    };

    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getData();
    };

    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchEmployee();
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

    /**获取员工表数据***/
    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.privateClass = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.privateClass = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide();
        });
    };
    /**处理搜索数据***/
    $scope.searchEmployeeData = function () {
        return {
            keywords: $scope.keywords != undefined ? $scope.keywords : null,
            sortType: $scope.sortType != undefined ? $scope.sortType : null,
            sortName: $scope.sort != undefined ? $scope.sort : null,
            venueId : $scope.myValue != undefined ? $scope.myValue : null,
            status  : $scope.classStatus != undefined && $scope.classStatus != '' ? $scope.classStatus :null,
        }
    };
    $scope.initPath = function () {
        $scope.searchParams = $scope.searchEmployeeData();
        $scope.pageInitUrl = '/private-teach/coach-class?' + $.param($scope.searchParams);
    };
    /**搜索方法***/
    $scope.searchEmployee = function () {
        $scope.initPath();
        $scope.getData();
    };

    //点击列表中课程
    $scope.courseMemberDetail = function (object, ind) {
        $scope.id = object.memberId;
        $scope.courseId = object.id;
        $scope.courseClickDetail = object;
        $scope.getClassData();
        $('#memberDetail').removeClass('displayNone');
    };
    //获取对应的会员详情
    $scope.getClassData = function () {
        $http.get("/private-teach/get-about-data?id=" + $scope.id + '&aboutId=' + $scope.courseId).then(function (result) {
            $scope.memberDetail = result.data;
            $scope.couserDetails = $scope.memberDetail.memberCourseOrderDetails.product_name;
            $scope.currentNowTime = new Date().getTime();
            $scope.courseAboutTime = new Date(($scope.getMyDate($scope.memberDetail.start * 1000) + " " + "00:00:00")).getTime();
        });
    };

    //登记上课
    $scope.registerClass = function (id) {
        $scope.confirmFinishClassBOOL = 1
        $scope.registerClassid = parseInt(id);
        // fpVerification("指纹比对", "请安装指纹驱动或启动服务", true, globalContext)
        // $("#fingerprintVerification").show();
        // $scope.id
        // $http.get("/private-teach/get-fingerprint?id=" + $scope.id).then(function (data) {
        //     $scope.fingerprintData = data.data.fingerprint
        //     if ($scope.fingerprintData == null) {
        //         Message.warning("您还没有录入指纹");
        //         return;
        //     }
        //     console.log($scope.fingerprintData)
        // }, function (errer) {
        //     console.log(errer);
        //     Message.error("系统错误请联系工作人员");
        // })

        $http.get('/private-teach/get-member-id?id=' + $scope.memberDetail.member_id).then(function (response) {
            if (response.data.data == true) {
                $http.get("/private-teach/update-status?id=" + $scope.registerClassid).then(function (result) {
                    $scope.getData();
                    $scope.getClassData();
                    $("#fingerprintVerification").hide();
                    $(".modal-backdrop").hide();
                    if(result.data.status == 'success'){
                        Message.success("上课打卡成功");
                    }else{
                        Message.warning(result.data.data);
                    }
                    // localStorage.removeItem('fpTemplate');
                });
            } else {
                Message.warning('该会员今天没有进馆记录，不能进行登记上课!');
                return;
            }
        })
    };
    //验证按钮
    $scope.closeCompas = function () {
        var www = localStorage.getItem("fpTemplate")
        if (www == '' || www == undefined || www == null) {
            Message.warning("请验证指纹");
            return;
        }
        var regTemplate = JSON.parse(www).fingerprintData;
        var fpTemplates = $scope.fingerprintData;
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1:22001/ZKBIOOnline/fingerprint/verify",
            dataType: "json",
            data: JSON.stringify({
                'reg': regTemplate,
                'ver': fpTemplates
            }),
            async: true,
            success: function (data) {
                //返回码
                var ret = null;
                ret = data.ret;
                //接口调用成功返回时接口调用成功返回时
                if (ret == 0 && data.score != undefined) {
                    $http.get('/private-teach/get-member-id?id=' + $scope.memberDetail.member_id).then(function (response) {
                        // console.log('登记上课时的场馆记录返回信息',response.data)
                        if (response.data.data == true) {
                            $http.get("/private-teach/update-status?id=" + $scope.registerClassid).then(function (result) {
                                $scope.getData();
                                $scope.getClassData();
                                $("#fingerprintVerification").hide();
                                $(".modal-backdrop").hide();
                                if(result.data.status == 'success'){
                                    Message.success("上课打卡成功");
                                }else{
                                    Message.warning(result.data.data);
                                }
                                localStorage.removeItem('fpTemplate');
                            });
                        } else {
                            Message.warning('该会员今天没有进馆记录，不能进行登记上课!');
                            return;
                        }
                    })
                }
                else if (data.score == 'undefined') {
                    Message.warning("上课打卡失败")
                    // $("#fingerprintVerification").hide();
                    // $(".modal-backdrop").hide();
                }
                else {
                    // alert("ret:" + data.ret);
                    // $("#fingerprintVerification").hide();
                    // $(".modal-backdrop").hide();
                    Message.warning("上课打卡失败")
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                Message.warning("请安装指纹驱动或启动该服务!")
            }
        });
    };

    //登记上课按钮 关闭
    /*$scope.fingerprintVerification = function () {
        $scope.selectionMethod = '';
    };*/
    //取消课程
    $scope.cancelClass = function (id) {
        var id = parseInt(id);
        $http.get("/private-teach/update-class-status?id=" + id).then(function (result) {
            Message.success('操作成功');
            $scope.getData();
            $scope.getClassData();
            $('#memberDetail').addClass('displayNone');
        });
    };

    // 确认下课
    $scope.confirmFinishClass = function (mobile,id,end) {
        // var myDate  = new Date().getTime();
        // var endDate = end*1000 + 2*60*60*1000;
        // if(myDate > endDate){
        //     Message.warning('此课程已过下课时间2小时，无法下课');
        //     return;
        // }
        $("#finishClassModal").modal("show");
        $('#codeInput').val('');
        $scope.selectionMethod = '';
        $scope.confirmFinishClassBOOL = 2;
        $scope.idd = parseInt(id);
        $http.get("/private-teach/get-fingerprint?id=" +$scope.id).then(function (data) {
            $scope.fingerprintData = data.data.fingerprint;
        }, function (errer) {
            console.log(errer);
            Message.error("系统错误请联系工作人员");
        });
        $scope.currentTime = new Date().getTime();
        $scope.classEndTime = $scope.memberDetail.end * 1000;
        $scope.userMobile = mobile;
        fpVerification("指纹比对", "请安装指纹驱动或启动服务", true, globalContext)
    };

    $scope.selectionMethod = "2";

    $scope.selectionChange = function () {
        //console.log($scope.selectionMethod)
        if ($scope.selectionMethod == "3") {
            // localStorage.removeItem("fpTemplate");
            //$("#finishClassModal").hide();
            $(".modal-backdrop").hide();
            $("#fingerprintVerification").modal("show");
            $(".modal-backdrop").hide();
            //$scope.selectionMethod = "2";
        }
        // var domdiv = document.getElementById('fingerprintInput');
        // domdiv.style.display = 'none';
    };
    //关闭指纹下课页面 
    /*$scope.verifySuccess = function () {
        $("#cancelClassVerification").modal("hide");
        $scope.selectionMethod = '';
        //console.log($scope.selectionMethod)
    };*/
    //验证指纹
    $scope.clickFingerprint = function () {
        //取出指纹
        var regTemplate = JSON.parse(localStorage.getItem('fpTemplateAdd')).fingerprintData;
        // 发送请求保存指纹
        $http({method:'post',url:'/private-teach/input-fingerprint',data:$.param({
            fingerprint:regTemplate,
            memberId:$scope.id
        }), headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
             //console.log(data.data.data)
             Message.success(data.data.data);
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员");
        });
        fpVerification("指纹比对", "请安装指纹驱动或启动服务", true, globalContext)
        $("#fingerprintInput").modal("hide");
        //$('#finishClassModal').modal('show');
        //$('#fingerprintVerification').modal('show');
    };
    //下课验证按钮
    $scope.closeCompas2 = function () {
        var www = localStorage.getItem("fpTemplate");
        if (www == '' || www == undefined || www == null) {
            Message.warning("请验证指纹");
            return;
        }
        var regTemplate = JSON.parse(www).fingerprintData;
        $http.get("/private-teach/get-fingerprint?id=" +$scope.id).then(function (data) {
            $scope.fingerprintData = data.data.fingerprint;
        }, function (errer) {
            console.log(errer);
            Message.error("系统错误请联系工作人员");
        });
        var fpTemplates = $scope.fingerprintData;
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1:22001/ZKBIOOnline/fingerprint/verify",
            dataType: "json",
            data: JSON.stringify({
                'reg': regTemplate,
                'ver': fpTemplates
            }),
            async: true,
            success: function (data) {
                //返回码
                var ret = null;
                ret = data.ret;
                //接口调用成功返回时接口调用成功返回时
                if (ret == 0 && data.score != undefined) {
                    if ($scope.currentTime >= $scope.classEndTime) {
                        $http.get(
                            "/private-teach/update-status?id="
                            + $scope.idd
                            + "&courseOrderId="
                            + $scope.memberDetail.memberCourseOrderDetails.id
                            + "&type="
                            + $scope.selectionMethod
                            + "&course="
                            + $scope.couserDetails
                        ).then(function (result) {
                            $scope.getData();
                            $scope.getClassData();
                            if(result.data.status == 'success'){
                                Message.success('下课打卡成功');
                            }else{
                                Message.warning(result.data.data);
                            }
                            $("#fingerprintVerification").hide();
                            $(".modal-backdrop").hide();
                            localStorage.removeItem('fpTemplate');
                            window.location.replace('/private-lesson/class?');
                        });
                    } else {
                        Message.warning('课程还没有结束，请继续上课！');
                        return;
                    }
                }
                else {
                    if(data.score == 'undefined'){
                        Message.warning("请重新放置手指");return;
                    }
                    if(ret == -6){
                        Message.warning("您的指纹无效");return;
                    }
                    // $("#fingerprintVerification").hide();
                    // $(".modal-backdrop").hide();
                    Message.warning("重新放置手指或检查是否录入指纹")
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                Message.warning("请安装指纹驱动或启动该服务!")
            }
        });
    };

    $scope.paracont = "获取验证码";
    $scope.disabled = false;
    // 获取验证码
    $scope.getCode = function () {
        $http({
            url: '/sell-card/create-code',
            method: 'POST',
            data: $.param({'mobile': $scope.userMobile, '_csrf_backend': $('#_csrf').val()}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            $scope.codeInfo = data.data.data;
            //获取验证码倒计时
            $scope.getCodeLoad = true;
            var second = 60,
                timePromise = undefined;
            timePromise = $interval(function(){
                if(second<=0){
                    $scope.getCodeLoad = false;
                    $interval.cancel(timePromise);
                    timePromise = undefined;
                    second = 60;
                    $scope.paracont  = "获取验证码";
                    $scope.disabled  = false;
                }else{
                    $scope.paracont = second + "S后获取";
                    $scope.disabled =true;
                    second--;
                    $scope.getCodeLoad = false;
                }
            },1000);
        })
    };
    // 完成下课验证
    $scope.finishCode = function () {
        if ($scope.selectionMethod == "1") {
            var $code = parseInt($('#codeInput').val());
            if($code == '' || $code == undefined){
                Message.warning('请输入验证码');
                return;
            }
            if ($code == $scope.codeInfo ) {
                if ($scope.currentTime >= $scope.classEndTime) {
                    $http.get(
                        "/private-teach/update-status?id="
                        + $scope.idd
                        + "&courseOrderId="
                        + $scope.memberDetail.memberCourseOrderDetails.id
                        + "&type="
                        + $scope.selectionMethod
                        + "&course="
                        + $scope.couserDetails
                    ).then(function (result) {
                        if(result.data.status == 'success'){
                            Message.success('操作成功');
                        }else{
                            Message.warning(result.data.data);
                        }
                        $scope.getData();
                        $scope.getClassData();
                        $("#finishClassModal").modal("hide");
                    });
                }else {
                    Message.warning('课程还没有结束，请继续上课！');
                    return;
                }
            }else {
                Message.warning("验证码输入错误");
                return;
            }
        }
        else if ($scope.selectionMethod == "2") {
            if ($scope.currentTime >= $scope.classEndTime) {
                $http.get(
                    "/private-teach/update-status?id="
                    + $scope.idd
                    + "&courseOrderId="
                    + $scope.memberDetail.memberCourseOrderDetails.id
                    + "&type="
                    + $scope.selectionMethod
                    + "&course="
                    + $scope.couserDetails
                ).then(function (result) {
                    if(result.data.status == 'success'){
                        Message.success('操作成功');
                    }else{
                        Message.warning(result.data.data);
                    }
                    $scope.getData();
                    $scope.getClassData();
                    $("#finishClassModal").modal("hide");
                });
            }
            else {
                Message.warning('课程还没有结束，请继续上课！');
                return;
            }
        }
    };
    //指纹打卡模态框关闭事件
    $scope.fingerprintVerification = function(){
        $('#fingerprintVerification').modal('hide');
        $('#finishClassModal').modal('hide');
        //$scope.selectionMethod = '';
    };
    /**搜索方法***/
    $scope.searchCard = function () {
        // $scope.searchCarding = true;
        $scope.initPath();
        $scope.getData();
    };

    //选择场馆筛选
    $scope.venueChange = function(){
        $scope.searchCard();
    };
    //状态筛选
    $scope.classStatusChange = function(){
        $scope.searchCard();
    };
    $scope.classStatusChange();
    /******Enter键搜索*******/
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchCard();
        }
    };
    /**处理搜索数据***/
    $scope.searchCardData = function () {
        return {
            keywords: $scope.keywords != undefined ? $scope.keywords : null,
        }
    };

    $scope.init();
});

//私教课程页面
app.controller('privateLessonCtrl', function ($scope, $rootScope, $http, $timeout, Upload) {
    $(function(){
        $("#datetimeStartServe").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        }).on("click",function(){

        });

        $("#datetimeEndServe").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        }).on("click",function(){

        });
    });
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2({
            'width':'90%'
        });
    });
    $scope.backPre = function () {
        history.go(-1);
    };
    //获取所有的场馆
    $http.get('/site/get-auth-venue').then(function (result) {
        $scope.venuesLists = result.data;
    });
    // 私课详情的点击事件
    $scope.privateModal = function (dataId, dataCategory) {
        $http.get('/private-teach/charge-class-details?chargeClassId=' + dataId).then(function (result) {
            //console.log('私课详情',result)
            $scope.privateClassDetails = result.data;
            $scope.privateClassDetailsListMoney = result.data.chargeClassPrice;
            $scope.classType = result.data.type; // 单课程是2 多课程是1
            if ($scope.classType == 1) {
                $("#privateServiceModal").modal('show');
            } else if ($scope.classType == 2) {
                $("#privateModal").modal('show');
            }
        });
    };

    //时间戳转换成时间公共方法
    $scope.getDateCh = function (str) {
        str = parseInt(str);
        if (str != "" && str != null && str != 0) {
            var oDate = new Date(str);
            var y = oDate.getFullYear();
            var m = oDate.getMonth()+1;
            var d = oDate.getDate();
            m = m >= 10 ? m : '0' + m;
            d = d >= 10 ? d : '0' + d;
            var theDate = y + "-" + m + "-" + d;
        } else {
            theDate = "";
        }
        return theDate;
    };
    //初始化模板参数
    $scope.num = 0;
    //私课详情修改 开始
    //情况售卖总数量
    $scope.clearSellNum = function(){
        $('.changeSellAllNumDiv').find('div.icheckbox_square-green').removeClass('checked');
        $('.changeSellAllNumDiv').find("input[name='sellNum']").val('');
    };
    //1.私教课程-基本属性 点击修改 获取数据
    $scope.basicTypeUpdate = function(object){
        console.log(object)
        $scope.venueTypeId = object.venue_id;
        $scope.clearSellNum();
        $.loading.show();
        $scope.getVenueLists();
        $scope.basicTypeUpdateLoad = false;
        $scope.name = $scope.privateClassDetails.name;
        $scope.monthNum = $scope.privateClassDetails.month_up_num;
        $scope.activatedTime = $scope.privateClassDetails.activated_time;
        var div = $('div.changeSellAllNumDiv');
        if($scope.privateClassDetails.total_sale_num == -1){
            div.find("input[name='sellNum']").attr('disabled', 'disabled');
            div.find("input[name='sellNum']").val('');
            $('.changeSellAllNumDiv').find('div.icheckbox_square-green').addClass('checked');
        }else{
            $scope.sellNum = $scope.privateClassDetails.total_sale_num;
            div.find("input[name='sellNum']").removeAttr('disabled');
            div.find("input[name='sellNum']").val($scope.sellNum);
        }
        $scope.setStyleCheckbox();
        $scope.sellStart = $scope.getDateCh($scope.privateClassDetails.sale_start_time*1000);
        $scope.sellEnd = $scope.getDateCh($scope.privateClassDetails.sale_end_time*1000);
        $scope.productType = [
            {'id':1,'type':'常规pt'},
            {'id':2,'type':'特色课'},
            {'id':3,'type':'游泳课'}
        ];
        $scope.courseType = [
            {'id':1,'type':'购买'},
            {'id':2,'type':'赠送'}
        ];
        $('#changeBasicTypeModal').modal('show');
        $timeout(function(){
            $.loading.hide();
        },500)
    };
    //获取所属场馆信息
    $scope.getVenueLists = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            //console.log(result.data)
            $scope.venueLists = result.data;
        });
    };
    //1.私教服务-基本属性 点击修改 获取数据
    $scope.basicTypeUpdateS = function(object){
        console.log(object)
        $scope.venueTypeId = object.venue_id;
        $scope.clearSellNum();
        $.loading.show();
        $scope.basicTypeUpdateLoad = false;
        $scope.name = $scope.privateClassDetails.name;
        $scope.validTime = $scope.privateClassDetails.valid_time;
        $scope.activatedTime = $scope.privateClassDetails.activated_time;
        var div = $('div.changeSellAllNumDiv');
        if($scope.privateClassDetails.total_sale_num == -1){
            div.find("input[name='sellNum']").attr('disabled', 'disabled');
            div.find("input[name='sellNum']").val('');
            $('.changeSellAllNumDiv').find('div.icheckbox_square-green').addClass('checked');
        }else{
            $scope.sellNum = $scope.privateClassDetails.total_sale_num;
            div.find("input[name='sellNum']").removeAttr('disabled');
            div.find("input[name='sellNum']").val($scope.sellNum);
        }
        $scope.setStyleCheckbox();
        $scope.sellStart = $scope.getDateCh($scope.privateClassDetails.sale_start_time*1000);
        $scope.sellEnd = $scope.getDateCh($scope.privateClassDetails.sale_end_time*1000);
        $scope.productType = [
            {'id':1,'type':'常规pt'},
            {'id':2,'type':'特色课'},
            {'id':3,'type':'游泳课'}
        ];
        $scope.courseType = [
            {'id':1,'type':'购买'},
            {'id':2,'type':'赠送'}
        ];
        $('#changeBasicTypeModalS').modal('show');
        $timeout(function(){
            $.loading.hide();
        },500)
    };
    $scope.unit = true;
    //不限输入框添加限制
    $scope.setStyleCheckbox = function() {
        //（数量不限）点击单选框，输入框添加限制
        $('.changeSellAllNumDiv').on('ifChecked',function(){
            $(this).children('input').attr('disabled','disabled');
            $(this).children('input').val('');
        });
        //（数量不限）点击单选框，输入框解除限制
        $('.changeSellAllNumDiv').on('ifUnchecked',function(){
            $(this).children('input').removeAttr('disabled');
        });
    };
    //私教课程-获取售卖总数量
    $scope.getTotalNum = function () {
        var div = $('div#unLimit');
        var $totalNum = div.find("input[name='sellNum']").val();
        if ($totalNum) {
            $scope.sellNum = $totalNum;
        } else {
            $scope.sellNum = -1;
        }
    };
    //私教服务-获取售卖总数量
    $scope.getTotalNumS = function () {
        var div = $('div#unLimit2');
        var $totalNum = div.find("input[name='sellNum']").val();
        if ($totalNum) {
            $scope.sellNum = $totalNum;
        } else {
            $scope.sellNum = -1;
        }
    };
    //1.基本属性 点击完成
    $scope.basicTypeUpdateData = function(){
        if($scope.privateClassDetails.type == 1){
            $scope.getTotalNumS();
        }else{
            $scope.getTotalNum();
        }
        var attributesData = {
            scenarios     : 'attributes',              //场景
            chargeId      : $scope.privateClassDetails.id,
            type          : $scope.privateClassDetails.type,
            name          : $scope.name,
            productType   : $scope.privateClassDetails.product_type,
            monthNum      : $scope.monthNum,
            validTime     : $scope.validTime,
            validUnit     : $scope.validUnit == undefined ? 1 : $scope.validUnit,
            activatedTime : $scope.activatedTime,
            activatedUnit : $scope.activatedUnit == undefined ? 1 : $scope.activatedUnit,
            sellNum       : $scope.sellNum,
            sellStart     : $scope.sellStart,
            sellEnd       : $scope.sellEnd,
            courseType    : $scope.privateClassDetails.course_type,
            belongVenue   : $scope.venueTypeId,
            _csrf_backend : $('#_csrf').val()
        };
        if($scope.name == null || $scope.name == undefined || $scope.name == ''){
            Message.warning('请输入产品名称');
            return false;
        }
        if($scope.privateClassDetails.product_type == null || $scope.privateClassDetails.product_type == undefined || $scope.privateClassDetails.product_type == ''){
            Message.warning('请选择产品类型');
            return false;
        }
        if($scope.activatedTime == null || $scope.activatedTime == undefined || $scope.activatedTime == '' || $scope.activatedTime == 0){
            Message.warning('请输入产品激活期限');
            return false;
        }
        if($scope.sellStart == null || $scope.sellStart == undefined || $scope.sellStart == ''){
            Message.warning('请选择售卖开始时间');
            return false;
        }
        if($scope.sellEnd == null || $scope.sellEnd == undefined || $scope.sellEnd == ''){
            Message.warning('请选择售卖结束时间');
            return false;
        }
        if($scope.privateClassDetails.course_type == null || $scope.privateClassDetails.course_type == undefined || $scope.privateClassDetails.course_type == ''){
            Message.warning('请选择课程类型');
            return false;
        }
        if($scope.venueTypeId == null || $scope.venueTypeId == undefined || $scope.venueTypeId == ''){
            Message.warning('请选择所属场馆');
            return false;
        }
        if($scope.privateClassDetails.type == 1){
            if($scope.validTime == null || $scope.validTime == undefined || $scope.validTime == ''){
                Message.warning('请输入产品有效期限');
                return false;
            }
        }else{
            if($scope.monthNum == null || $scope.monthNum == undefined || $scope.monthNum == '' || $scope.monthNum == 0){
                Message.warning('请输入每月上课数量');
                return false;
            }
        }
        $scope.basicTypeUpdateLoad = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(attributesData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $scope.searchCurriculum();
                $('#changeBasicTypeModal').modal('hide');
                $('#changeBasicTypeModalS').modal('hide');
            }else{
                $scope.basicTypeUpdateLoad = false;
                Message.warning(response.data.data);
            }
        });
    };

    //2.私教课程-课种 点击修改 获取数据
    $scope.changeClassChoose = function(){
        $.loading.show();
        $scope.courseUpdateLoad = false;
        $scope.getAllCourse();
        $('#changeClassChooseModal').modal('show');
        $timeout(function(){
            $.loading.hide();
            $scope.courseId      = $scope.privateClassDetails.course[0].course_id;
            $scope.courseLength  = $scope.privateClassDetails.course[0].course_length;
            $scope.originalPrice = $scope.privateClassDetails.course[0].original_price;
            $scope.appOriginalPrice = $scope.privateClassDetails.course[0].app_original;
        },500)
    };
    //2.私教服务-课种 点击修改 获取数据
    $scope.changeClassChooseS = function(){
        $('#ptServeClassBox').children('.removeDiv').remove();
        $scope.addServerClassHtml123();
        $.loading.show();
        $scope.courseUpdateLoad = false;
        $('#changeClassChooseModalS').modal('show');
        $scope.getAllCourse();
        $timeout(function(){
            $scope.getOldCourse();
            $.loading.hide();
        },500)
    };
    //动态添加课种模板
    $scope.addServerClassHtml123 = function () {
        $scope.htmlAttr = 'addServeClass123';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            console.log(result)
            $scope.serveClass123Html = result.data.html;
        });
    };

    //动态添加售卖场馆模板
    $scope.addPTSellVenueBtnHtml = function(){
        $scope.htmlAttr = 'addServeSellVenue123';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.serveSellVenue123Html = result.data.html;
        });
    };
    //获取所有课种
    $scope.getAllCourse = function(){
        $http.get('/rechargeable-card-ctrl/get-private-data').then(function (result) {
            $scope.allCourseData = result.data.venue;
        });
    };
    //默认显示之前设置的课种
    $scope.getOldCourse = function(){
        var oldLists = $('#ptServeClassBox').children('.ptServerBoxEdit');
        oldLists.each(function(index){
            var $courseId      = $(this).find('select[name="courseId"]');     //所有课种
            var $courseLength  = $(this).find('input[name="courseLength"]');  //课程时长
            var $courseNum     = $(this).find('input[name="courseNum"]');     //课程节数
            var $originalPrice = $(this).find('input[name="originalPrice"]'); //单节原价
            var $appOriginalPrice = $(this).find('input[name="appOriginalPrice"]'); //移动端单节原价
            var currentCourse = $scope.privateClassDetails.course[index];
            $courseLength.val(parseInt(currentCourse.course_length));
            $courseNum.val(parseInt(currentCourse.course_num));
            $originalPrice.val(parseInt(currentCourse.original_price));
            $appOriginalPrice.val(parseInt(currentCourse.app_original));
        });
    };
    //公共属性判断
    $scope.commonProof = function (data) {
        if (data == undefined || data == null) {
            return false;
        } else {
            return true;
        }
    };
    //定义课种数组
    $scope.courseArr = function () {
        $scope.courseId      = [];//课程id
        $scope.courseLength  = [];//课程时长
        $scope.originalPrice = [];//课程单价
        $scope.courseNum     = [];//课程总节数
        $scope.appOriginalPrice = [];//移动端课程单价
        $scope.oldCourseIdArr123 = [];//判断去重
    };
    //获取课种
    $scope.getCourseId = function () {
        var select = $('div#ptServeClassBox');
        var div    = select.children('div.ptServerBoxEdit');
        $scope.courseArr();
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $classId   = $(this).find('select[name="courseId"]').find('option:selected').val();
                var $classTime = $(this).find("input[name='courseLength']").val();
                var $classNum  = $(this).find("input[name='courseNum']").val();
                var $unitPrice = $(this).find("input[name='originalPrice']").val();
                var $appUnitPrice = $(this).find("input[name='appOriginalPrice']").val();
                if($classId !=''){
                    $scope.courseId.push($classId);
                    $scope.oldCourseIdArr123.push($classId);
                    if ($classTime!='' && $classNum !='') {
                        $scope.courseLength.push($classTime);
                        $scope.courseNum.push($classNum);
                    }
                    if($unitPrice){
                        $scope.originalPrice.push($unitPrice);
                    }
                    if($appUnitPrice){
                        $scope.appOriginalPrice.push($appUnitPrice);
                    }
                }
            });
        }else{
            $scope.courseArr();
        }
    };


    //课种选择去重
    $scope.oldCourseIdArrClick = function(){
        $timeout(function(){
            $scope.getCourseId();
        },10)
    };
    //2.课种 点击完成
    $scope.courseUpdateData = function(){
        if($scope.privateClassDetails.type == 1){
            $scope.getCourseId();
        }
        var courseData = {
            scenarios     : 'course',              //场景
            chargeId      : $scope.privateClassDetails.id,
            type          : $scope.privateClassDetails.type,
            courseId      : $scope.courseId,
            courseLength  : $scope.courseLength,
            originalPrice : $scope.originalPrice,
            appOriginalPrice : $scope.appOriginalPrice,
            courseNum     : $scope.courseNum != '' && $scope.courseNum != undefined ? $scope.courseNum :null ,
            _csrf_backend : $('#_csrf').val()
        };
        if($scope.privateClassDetails.type == 1){
            var $allcourseBoxLen = $('div#ptServeClassBox').children('div.ptServerBoxEdit').length;
            var courseIdLen = $scope.courseId.length;
            var courseLengthLen = $scope.courseLength.length;
            var originalPriceLen = $scope.originalPrice.length;
            var courseNumLen = $scope.courseNum.length;
            if($allcourseBoxLen !=courseIdLen || $allcourseBoxLen != courseNumLen || courseIdLen != courseLengthLen || originalPriceLen != courseIdLen  || courseIdLen < 1){
                Message.warning('请填写完整的课种信息');
                return;
            }
        }

        if($scope.privateClassDetails.type == 2){
            if($scope.courseId == '' || $scope.courseId == undefined || $scope.courseId == null){
                Message.warning('请选择课种');
                return;
            }
            if($scope.courseLength == '' || $scope.courseLength == undefined || $scope.courseLength == null){
                Message.warning('请填写课程时长');
                return;
            }
            if($scope.originalPrice == '' || $scope.originalPrice == undefined || $scope.originalPrice == null){
                Message.warning('请填写课程价格');
                return;
            }
        }
        $scope.courseUpdateLoad = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(courseData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $('#changeClassChooseModal').modal('hide');
                $('#changeClassChooseModalS').modal('hide');
            }else{
                $scope.courseUpdateLoad = false;
                Message.warning(response.data.data);
            }
        });
    };
    //动态添加课程区间模板
    $scope.addCourseSectionHtml = function(){
        $scope.htmlAttr = 'addCourseSection123';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.courseSectionHtml = result.data.html;
            $timeout(function(){
                $scope.getLastCourseSection();
            },100)
        });
    };

    //获取最后一个产品区间价格,并渲染
    $scope.getLastCourseSection = function(){
        var courseSecBox =  $('#courseSectionNumBox');
        var $lastTwo   = courseSecBox.find('.courseSectionNum').eq(-2);
        var $lastOne   = courseSecBox.find('.courseSectionNum').last();
        var $twoSecEnd = $lastTwo.find('input[name="endSec"]').val();
        var $lastSecStart = $lastOne.find('input[name="startSec"]');
        if($twoSecEnd != '' && $twoSecEnd != undefined){
            $lastSecStart.val(parseInt($twoSecEnd) + 1);
        }
    };
    //获取之前的产品区间价格并渲染
    $scope.getOldCourseSection = function(){
        var $courseSecBox = $('#courseSectionNumBox').children('.courseSectionNum');
        $courseSecBox.each(function (i) {
            var $startSec   = $(this).find('input[name="startSec"]');
            var $endSec = $(this).find("input[name='endSec']");
            var $disPrice  = $(this).find("input[name='discountPrice']");
            var $posPrice = $(this).find("input[name='posPrice']");
            var $priceData =  $scope.privateClassDetails.price;
            if($scope.privateClassDetails.price.length == 0){
                $startSec.val('');
                $endSec.val('');
                $disPrice.val('');
                $posPrice.val('');
            }
            if($scope.privateClassDetails.price.length > 0){
                $startSec.val($priceData[i].intervalStart);
                $endSec.val($priceData[i].intervalEnd);
                $disPrice.val($priceData[i].unitPrice);
                $posPrice.val($priceData[i].posPrice);
            }

        });
    };


    //3.私教课程-产品价格 点击修改 获取数据
    $scope.productPriceUpdate = function(){
        $('#courseSectionNumBox').children('.removeDiv').remove();
        $scope.addCourseSectionHtml();
        $.loading.show();
        $scope.courseSectionEditFlag = false;
        $('#changeProductPriceModal').modal('show');
        $timeout(function(){
            $.loading.hide();
            $scope.getOldCourseSection();
        },500)
    };
    //获取私课课程-产品区间数据
    $scope.getCourseSectionData = function(){
        $scope.courseSectionStartArr = [];
        $scope.courseSectionEndArr = [];
        $scope.courseSectionDisArr = [];
        $scope.courseSectionPosArr = [];
        var $courseSecBox = $('#courseSectionNumBox').children('.courseSectionNum');
        $courseSecBox.each(function (i) {
            var $startSec   = $(this).find('input[name="startSec"]').val();
            var $endSec = $(this).find("input[name='endSec']").val();
            var $disPrice  = $(this).find("input[name='discountPrice']").val();
            var $posPrice = $(this).find("input[name='posPrice']").val();
            if($startSec !=''){
                $scope.courseSectionStartArr.push($startSec);
            }
            if($endSec !=''){
                $scope.courseSectionEndArr.push($endSec);
            }
            if($disPrice !=''){
                $scope.courseSectionDisArr.push($disPrice);
            }
            if($posPrice !=''){
                $scope.courseSectionPosArr.push($posPrice);
            }
        });
    };
    //动态填写区间开始
    $scope.endSectionBlur = function($event){
        var currentNum = $(event.target).val();
        var $num     = parseInt(currentNum) + 1;
        var $preNum  =parseInt($($event.target).parents('.courseSectionNum').find('input[name="startSec"]').val());
        var $parents = $($event.target).parents('.courseSectionNum').next();
        if(currentNum != ''){
            if(currentNum >= $preNum){
                var $nextBox = $parents.find('input[name="startSec"]').val($num);
            }else {
                $($event.target).val('')
                $parents.find('input[name="startSec"]').val('')
                Message.warning('区间结束填写有误');
                return;
            }
        }
    };

    //私课课程-产品区间价格修改完成
    $scope.courseSectionEdit = function(){
        var $courseSecBoxLen = $('#courseSectionNumBox').children('.courseSectionNum').length;
        $scope.getCourseSectionData();
        var courseSection = {
            scenarios      : 'price',              //场景
            chargeId       : $scope.privateClassDetails.id,//私课id
            type            : $scope.privateClassDetails.type,//私课类型
            intervalStart  : $scope.courseSectionStartArr,//区间开始
            intervalEnd    : $scope.courseSectionEndArr,//区间结束
            unitPrice      :$scope.courseSectionDisArr,//优惠单价
            unitPos        :$scope.courseSectionPosArr,//POS价
            _csrf_backend : $('#_csrf').val()
        };
        var $StartArrLen = $scope.courseSectionStartArr.length;
        var $EndArrLen = $scope.courseSectionEndArr.length;
        var $DisArrLen = $scope.courseSectionDisArr.length;
        var $PosArrLen = $scope.courseSectionPosArr.length;
        if( $StartArrLen != $EndArrLen || $StartArrLen != $DisArrLen || $StartArrLen != $PosArrLen ||$DisArrLen != $PosArrLen){
            Message.warning('请填写完整的产品区间价格');
            return;
        }
        if($courseSecBoxLen > 1){
            if($StartArrLen != $courseSecBoxLen || $courseSecBoxLen != $DisArrLen || $courseSecBoxLen != $PosArrLen ){
                Message.warning('请填写完整的产品区间价格');
                return;
            }
        }
        $scope.courseSectionEditFlag = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(courseSection),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $scope.searchCurriculum();
                $('#changeProductPriceModal').modal('hide');
            }else{
                $scope.courseSectionEditFlag = false;
                Message.warning(response.data.data);
            }
        });
    };


    //3.私教服务-产品价格 点击修改 获取数据
    $scope.productPriceUpdateS = function(){
        $('#totalPirce123').find('input[name="servePosPirce123"]').val('');
        $('#totalPirce123').find('input[name="serveTotalPirce123"]').val('');
        $('#totalPirce123').find('input[name="appTotalPrice123"]').val('');
        $.loading.show();
        $scope.servePriceLoadFlag = false;
        $('#changeProductPriceModalS').modal('show');
        $timeout(function(){
            $.loading.hide();
            if($scope.privateClassDetails.total_pos_price != null){
                var totalPos = parseFloat($scope.privateClassDetails.total_pos_price);
                $('#totalPirce123').find('input[name="servePosPirce123"]').val(totalPos.toFixed(2))
            }
            if($scope.privateClassDetails.total_amount != null){
                var total = parseFloat($scope.privateClassDetails.total_amount);
                $('#totalPirce123').find('input[name="serveTotalPirce123"]').val(total.toFixed(2))
            }
            if($scope.privateClassDetails.app_amount != null){
                var total = parseFloat($scope.privateClassDetails.app_amount);
                $('#totalPirce123').find('input[name="appTotalPrice123"]').val(total.toFixed(2))
            }
        },500)
    };

    //私课服务-产品价格 修改完成按钮
    $scope.servePriceEdit = function(){
        if($scope.privateClassDetails.type == '1'){
            var $totalPrice = $('#totalPirce123').find('input[name="serveTotalPirce123"]').val();
            var $totalPos = $('#totalPirce123').find('input[name="servePosPirce123"]').val();
            var $appTotalPrice = $('#totalPirce123').find('input[name="appTotalPrice123"]').val();
            var currentPriceData = {
                scenarios     : 'price',              //场景
                chargeId      : $scope.privateClassDetails.id,//私课id
                type          : $scope.privateClassDetails.type,//私课类型
                totalPrice    : $totalPrice != '' && $totalPrice != undefined ?$totalPrice : null,//总售价
                totalPos      : $totalPos  != ''  && $totalPos  != undefined ?$totalPos  : null,//总pos价,
                appTotalPrice : $appTotalPrice != '' && $appTotalPrice != undefined ?$appTotalPrice : null,//移动端总售价
                _csrf_backend : $('#_csrf').val()
            }
        }

        $scope.servePriceLoadFlag = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(currentPriceData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $scope.searchCurriculum();
                $('#changeProductPriceModalS').modal('hide');
            }else{
                $scope.servePriceLoadFlag = false;
                Message.warning(response.data.data);
            }
        });
    };
    //获取售卖场馆信息
    $scope.getAllSellVenueMethod = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            // console.log('售卖场馆',result)
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue = '暂无数据';
                $scope.venueStatus = false;
            }
        });
    };
    //获取老的售卖场馆
    $scope.getOldSellVenueMethod = function(){
        var $sellVenueBox = $('.ptServeSellVenueBox').find('.ptServeSellVenue');
        if($scope.privateClassDetails.venue.length == 0){
            $sellVenueBox.find('select[name="sellVenue"]').val('');
            $sellVenueBox.find('input[name="num"]').val('');
        }
        if($scope.privateClassDetails.venue.length >= 1){
            $sellVenueBox.each(function(index){
                $(this).find('select[name="sellVenue"]').val(parseInt($scope.privateClassDetails.venue[index].venue_id));
                $(this).find('input[name="num"]').val(parseInt($scope.privateClassDetails.venue[index].sale_num));
            })
        }
    };

    //4.售卖场馆 点击修改 获取数据
    $scope.sellVenueUpdate = function(){
        $('.ptServeSellVenueBox').children('.removeDiv').remove();
        var $sellVenueBox = $('.ptServeSellVenueBox').find('.ptServeSellVenue');

        $scope.getAllSellVenueMethod();
        $scope.addPTSellVenueBtnHtml();
        $.loading.show();
        $scope.ptSellVenueEditFlag = false;
        $('#changeSellVenueModal').modal('show');
        $timeout(function(){
            $.loading.hide();
            $scope.getOldSellVenueMethod();
        },500)
    };
    //私课详情-售卖场馆-获取所有的售卖场馆
    $scope.getAllSellVenue = function(){
        $scope.sellVenueArr123 = [];
        $scope.allSellVenueIdArr = [];
        $scope.allSellVenueNumArr = [];
        var $sellVenueBox = $('.ptServeSellVenueBox');
        var $allSellVenueLists  =  $sellVenueBox.children('.ptServeSellVenue');
        $allSellVenueLists.each(function(index,item){
            var $venueId = $(this).find('option:selected').val();
            var $venueNum = $(this).find('input[name="num"]').val();
            if($venueId != ''){
                $scope.allSellVenueIdArr.push($venueId);
                $scope.sellVenueArr123.push($venueId)
            }
            if($venueNum != '' && $venueNum != undefined){
                $scope.allSellVenueNumArr.push($venueNum);
            }
        });
    };

    //选择售卖场馆
    $scope.selectSellVenue123 = function(){
        $timeout(function(){
            $scope.getAllSellVenue();
        },100)
    };
    
    //4、私课详情-售卖场馆-修改完成
    $scope.ptSellVenueEditComplete = function(){
        var $sellVenueBoxLen = $('.ptServeSellVenueBox').find('.ptServeSellVenue').length;
        $scope.getAllSellVenue();
        // /private-teach/charge-class-update      post    参数：scenarios=sellVenue场景，
        // chargeId私课id，sellVenueId售卖场馆id，sellVenueNum售卖数量
        var sellVenueData = {
            scenarios     : 'sellVenue',              //场景
            chargeId      : $scope.privateClassDetails.id,//私课id
            sellVenueId   :$scope.allSellVenueIdArr,//总售价
            sellVenueNum  : $scope.allSellVenueNumArr,//总pos价,
            _csrf_backend : $('#_csrf').val()
        };
        var $allSellVenueIdLen =  $scope.allSellVenueIdArr.length;
        var $allSellVenueNumLen =  $scope.allSellVenueNumArr.length;
        if($sellVenueBoxLen == 1){
            if($allSellVenueIdLen != $allSellVenueNumLen){
                Message.warning('请填写完整的售卖场馆信息');
                return;
            }
        }
        if($sellVenueBoxLen > 1){
            if($sellVenueBoxLen != $allSellVenueIdLen || $sellVenueBoxLen != $allSellVenueNumLen){
                Message.warning('请填写完整的售卖场馆信息');
                return;
            }
        }
        $scope.ptSellVenueEditFlag = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(sellVenueData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $('#changeSellVenueModal').modal('hide');
            }else{
                $scope.ptSellVenueEditFlag = false;
                Message.warning(response.data.data);
            }
        });
    };

    //获取之前的赠送商品进行渲染
    $scope.getAllOldShop = function(){
        var $allShopBox = $('#giftShopEdit123123Box').find('.giftShopBox123');
        if($scope.privateClassDetails.gift.length == 0){
            $allShopBox.find('select[name="giftShopEdit"]').val('');
            $allShopBox.find('input[name="giftNum"]').val('');
        }
        if($scope.privateClassDetails.gift.length >= 1){
            $allShopBox.each(function(index){
                $(this).find('select[name="giftShopEdit"]').val(parseInt($scope.privateClassDetails.gift[index].gift_id));
                $(this).find('input[name="giftNum"]').val(parseInt($scope.privateClassDetails.gift[index].gift_num));
            })
        }
    };

    //5.赠品 点击修改 获取数据
    $scope.changeFreeGifts = function(){
        $('#giftShopEdit123123Box').children('.removeDiv').remove();
        $scope.addGiftShopHtml1321();
        $.loading.show();
        $scope.giftUpdateLoad = false;
        $scope.getAllGift();
        $('#changeFreeGiftsModal').modal('show');
        $timeout(function(){
            $scope.getAllOldShop();
            $.loading.hide();
        },500)
    };
    //获取所有赠品数据
    $scope.getAllGift = function(){
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            $scope.allGiftData = result.data.goods;
        });
    };
    //添加赠品模板
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'donation';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.donationHtml = result.data.html;
        });
    };

    //添加赠品修改模板
    $scope.addGiftShopHtml1321 = function(){
        $scope.htmlAttr = 'giftShopEdit1111';
        $scope.num  = $scope.num + 1;
        $http.get('/private-teach/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.giftShopEditHtml123 = result.data.html;
        });
    };
    //赠品改变触发事件
    $scope.donationHttp = [];
    $scope.selectGift = function (id) {
        $scope.donationHttp.push(id);
        $scope.getGiftId();
        if($scope.giftId != undefined && $scope.giftId.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.giftId);
        }
    };
    //定义赠品数组
    $scope.gift = function () {
        $scope.giftId  = [];
        $scope.giftNum = [];
    };
    //获取赠品
    $scope.getGiftId = function () {
        var select = $('div#giftShopEdit123123Box');
        var div    = select.children('div.giftShopBox123');
        $scope.gift();
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $giftId  = $(this).find('option:selected').val();
                var $giftNum = $(this).find("input[name='giftNum']").val();
                if($giftId){
                    $scope.giftId.push($giftId);
                    if ($giftNum){
                        $scope.giftNum.push($giftNum);
                    }
                }
            });
        }else{
            $scope.gift();
        }
    };

    //赠品去重判断
    $scope.shopGiftSelectClick = function(){
        $scope.getGiftId();
    };
    //5.赠品修改 点击完成
    $scope.giftUpdate = function(){
        var giftBoxLen = $('#giftShopEdit123123Box').children('.giftShopBox123').length;
        $scope.getGiftId();
        var giftUpdateData = {
            scenarios     : 'gift',              //场景
            chargeId      : $scope.privateClassDetails.id,
            giftId        : $scope.giftId,
            giftNum       : $scope.giftNum,
            _csrf_backend : $('#_csrf').val()
        };
        var giftIdLen = $scope.giftId.length;
        var giftNumLen = $scope.giftNum.length;
        if(giftBoxLen >1 ){
            if(giftBoxLen != giftIdLen || giftBoxLen != giftNumLen){
                Message.warning('请填写完整的赠品信息');
                return;
            }
        }
        if(giftBoxLen == 0){
            if(giftNumLen != giftIdLen){
                Message.warning('请填写完整的赠品信息');
                return;
            }
        }
        $scope.giftUpdateLoad = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(giftUpdateData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $('#changeFreeGiftsModal').modal('hide');
            }else{
                $scope.giftUpdateLoad = false;
                Message.warning(response.data.data);
            }
        });
    };

    //6.转让设置 点击修改 获取数据
    $scope.changeTransferData = function(){
        $.loading.show();
        $scope.transferUpdateLoad = false;
        $scope.transferNum   = $scope.privateClassDetails.transfer_num;
        $scope.transferPrice = $scope.privateClassDetails.transfer_price;
        $('#changeTransferSettingModal').modal('show');
        $timeout(function(){
            $.loading.hide();
        },500)
    };
    //6.转让设置修改 点击完成
    $scope.changeTransferUpdate = function(){
        var transferUpdateData = {
            scenarios     : 'transfer',              //场景
            chargeId      : $scope.privateClassDetails.id,
            transferNum   : $scope.transferNum,
            transferPrice : $scope.transferPrice,
            _csrf_backend : $('#_csrf').val()
        };
        $scope.transferUpdateLoad = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(transferUpdateData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $('#changeTransferSettingModal').modal('hide');
            }else{
                $scope.transferUpdateLoad = false;
                Message.warning(response.data.data);
            }
        });
    };

    //7.课程详情 点击修改 获取数据
    $scope.classDetailData = function(){
        $.loading.show();
        $scope.classDetailUpdateLoad = false;
        $scope.note  = $scope.privateClassDetails.describe;
        $scope.photo = $scope.privateClassDetails.pic;
        $('#changeClassDetailModal').modal('show');
        $timeout(function(){
            $.loading.hide();
        },500)
    };
    //上传图片大小判断
    $scope.setCover = function (file) {
        if(file){
            if(file.size > 2000000){
                Message.warning('图片太大');
            }else{
                $scope.setPic(file);
            }
        }
    };
    //图片上传方法
    $scope.setPic = function (file) {
        Upload.upload({
            url    : '/private-teach/upload',
            method : 'POST',
            data   : {UploadForm:{imageFile:file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result){
            if(result.data.status == 'success'){
                $scope.photo  = result.data.imgLink;
            }else{
                Message.warning(result.data.data);
            }
        })
    };
    //7.课程详情修改 点击完成
    $scope.classDetailUpdate = function(){
        var noteAndPicData = {
            scenarios     : 'note',              //场景
            chargeId      : $scope.privateClassDetails.id,
            note          : $scope.note,
            pic           : $scope.photo,
            _csrf_backend : $('#_csrf').val()
        };
        $scope.classDetailUpdateLoad = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(noteAndPicData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $('#changeClassDetailModal').modal('hide');
            }else{
                $scope.classDetailUpdateLoad = false;
                Message.warning(response.data.data);
            }
        });
    };
    
    //8.点击修改 获取数据
    $scope.changeContractDetail = function(){
        $.loading.show();
        $scope.contractUpdateLoad = false;
        $scope.getAllContract();
        if($scope.privateClassDetails.deal_id != null && $scope.privateClassDetails.deal_id != ''){
            $scope.dealId = $scope.privateClassDetails.deal_id;
            $scope.getDealId($scope.privateClassDetails.deal_id);
        }else{
            $scope.dealId = '';
            $scope.dataDeal = '';
        }
        $('#changeContractDetailModal').modal('show');
        $timeout(function(){
            $.loading.hide();
        },500)
    };
    //获取所有的合同
    $scope.getAllContract = function () {
        $http.get('/contract/get-deal?type='+'2').then(function (result) {
            console.log(result)
            if(result.data != undefined && result.data != ""){
                $scope.dealData   = result.data;
                $scope.dealStauts = true;
                $.loading.hide();
            }else{
                $scope.dealData   = result.data;
                $scope.dealStauts = false;
                $.loading.hide();
            }
        });
    };
    //获取合同内容
    $scope.getDealId = function (data) {
        $scope.dealIds = data;
        $http.get('/contract/get-deal-one?id='+$scope.dealIds).then(function (result) {
            $scope.dataDeal = result.data.data;
        });
    };
    //8.合同修改 点击完成
    $scope.contractDetailUpdate = function(){
        var contractData = {
            scenarios     : 'deal',              //场景
            chargeId      : $scope.privateClassDetails.id,
            dealId        : $scope.dealId,
            _csrf_backend : $('#_csrf').val()
        };
        $scope.contractUpdateLoad = true;
        $http({
            method : 'POST',
            url    : '/private-teach/charge-class-update',
            data   : $.param(contractData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.privateModal($scope.privateClassDetails.id,$scope.privateClassDetails.category);
                Message.success(response.data.data);
                $('#changeContractDetailModal').modal('hide');
            }else{
                $scope.contractUpdateLoad = false;
                Message.warning(response.data.data);
            }
        });
    };
    //私课详情修改 结束

    /**
     *后台私教课程管理 - 课程信息查询 - 访问PrivateTeach控制器的actionChargeClass()
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/10
     *  @return bool|string
     */

    $scope.keywordsCurriculum = '';
    //回车
    $scope.enterSearchCurriculum = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchCurriculum();
        }
    };
    $scope.searchCurriculum = function () {
        if($scope.courseId == null || $scope.courseId == undefined ){
            $scope.courseId = '';
        }
        if($scope.myValue == undefined && $scope.myValue == null){
            $scope.myValue = '';
        }
        $http({method:"get",url:'/private-teach/charge-class?keywords='+$scope.keywordsCurriculum +'&courseId='+ $scope.courseId+'&venueId='+$scope.myValue}).then(function (data) {
            if(data.data.data == "" || data.data.data == undefined || data.data.data.length == undefined){
                $scope.datas    = data.data.data;
                $scope.pages    = data.data.pages;
                $scope.dataInfo = true;
            } else {
                $scope.datas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
        }, function (error) {
            console.log(error)
            Message.error('系统错误请联系工作人员')
        })
    };

    //表头sectle 搜索
    $scope.classAll = function () {
        $http({method: 'get', url: '/rechargeable-card-ctrl/get-private-data'}).then(function (data) {
            $scope.classAllVenue = data.data.venue
        }, function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    };
    $scope.classAll();
    $scope.classAllChange = function (id) {
        $http({method: 'get', url: '/private-teach/charge-class?courseId=' + id}).then(function (data) {
            if (data.data.data == "" || data.data.data == undefined || data.data.data.length == undefined) {
                $scope.datas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            } else {
                $scope.datas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };

    //添加搜索页数
    $scope.skipPage = function(value,id){
        if(value != undefined){
            $scope.pageInitUrl = '/user/member-info?page='+value;
            $scope.getData();
        }
    };

    $scope.init = function () {
        $scope.pageInitUrl = '/private-teach/charge-class';
        $scope.getData();
        $scope.initPath();
        $scope.getVenueOptions();
    };
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getData();
    };

    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchCard();
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

    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data.data == "" || result.data.data == undefined || result.data.data.length == undefined) {
                $scope.datas = result.data.data;
                $scope.pages = result.data.pages;
                $scope.dataInfo = true;
                $timeout(function () {
                    $http.get('/private-teach/overdue').then(function (result) {
                    });
                }, 10)
            } else {
                $scope.datas = result.data.data;
                $scope.pages = result.data.pages;
                $scope.dataInfo = false;
            }
            $.loading.hide();
        });
    };
    $scope.delClass = function (id) {
        Sweety.remove({
            url: "/private-teach/delete-data?classId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getData();
        });
    };
    /**处理搜索数据***/
    $scope.searchCardData = function () {
        // $scope.formatDates();
        return {
            keywords: $scope.keywords != undefined ? $scope.keywords : null,
            venueId: $scope.venueId != undefined ? $scope.venueId : null,
            startTime: $scope.startTime != undefined ? $scope.startTime : null,
            endTime: $scope.endTime != undefined ? $scope.endTime : null,
            sortType: $scope.sortType != undefined ? $scope.sortType : null,
            sortName: $scope.sort != undefined ? $scope.sort : null
        }
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchDatas();
        // $scope.init();
        $scope.initPath();
        $scope.getData();
    };

    $scope.searchDatas = function () {
        // $scope.keywords = null;
        // $scope.venueId = null;
        // $scope.dateTime = null;
        // $scope.startTime = null;
        // $scope.endTime = null;
        $scope.keywordsCurriculum = '';
        $scope.courseId   = '';
        $('#classCourseId').select2('val',"");
        $('#select2-classCourseId-container').text('请选择课种');
        $scope.myValue = '';
        $('#select2-CourseVenueChange-container').text('请选择场馆');

    };

    $scope.initPath = function () {
        $scope.searchParams = $scope.searchCardData();
        $scope.pageInitUrl = '/private-teach/charge-class?' + $.param($scope.searchParams);
    };

    //添加团课获取场馆信息
    $scope.getVenueOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-venue').then(function (result) {
            if (result.data.venue != undefined && result.data.venue != "") {
                $scope.venues = result.data.venue;
                $scope.VenueStauts = true;
            } else {
                $scope.VenueStauts = false;
                $scope.venues = '暂无数据';
            }
        });
    };
    /**搜索方法***/
    $scope.searchCard = function () {
        $scope.initPath();
        $scope.getData();
    };

    /******Enter键搜索*******/
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchCard();
        }
    };

    /**修改状态**/
    $scope.editStatus = function (id, $index, text) {
        $http.get("/private-teach/edit-class-status?id=" + id + "&text=" + text).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                $scope.datas[$index].status = result.data.edit;
                $scope.getData();
            } else {
                Message.warning(result.data.data[0])
            }
        });
    };
    /**修改移动端价格显示**/
    $scope.editShow = function (id) {
        $http.get("/private-teach/edit-class-show?chargeId=" + id).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                $scope.getData();
            } else {
                Message.warning(result.data.data);
            }
        });
    };
    $scope.init();
    //获取课程详情
    $scope.getClassDetail132 = function (id) {
        $http.get('/private-teach/charge-class-details?chargeClassId=' + id).then(function (response) {
            if (response.data.pic == '' || response.data.pic == null) {
                $scope.updateClassPic = '';
            } else {
                $scope.updateClassPic = response.data.pic;
            }
            if (response.data.describe == '' || response.data.describe == null) {
                $scope.classEditContent = '';
            } else {
                $scope.classEditContent = response.data.describe;
            }
        });
    };

    /**修改图片**/
    $scope.updateClassPicBtn = function (id) {
        $scope.updateClassPic = '';
        $scope.classEditContent = '';
        $('#classPicUpdateModal').modal('show');
        $scope.upClassPicId = id;
        $scope.getClassDetail132($scope.upClassPicId);
    };

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
                    $scope.updateClassPic = result.data.imgLink;
                } else {
                    $scope.updateClassPic = result.data.imgLink;
                }
            } else {
                Message.warning(result.data.data);
                return;
            }
        });
    };
    //需要上传的数据
    $scope.getUpdateData = function () {
        return {
            _csrf_backend: $('#_csrf').val(),
            classId: $scope.upClassPicId,
            pic: $scope.updateClassPic != undefined && $scope.updateClassPic != '' ? $scope.updateClassPic : null,
            describe: $scope.classEditContent != undefined && $scope.classEditContent != '' ? $scope.classEditContent : null
        }
    };

    //完成修改
    $scope.submitUpdate = function () {
        if ($scope.updateClassPic == '' && $('#classEditContent').val() == '') {
            Message.warning('请选择修改的内容');
            return;
        }
        $http({
            url: "/private-teach/update-charge-pic",
            method: 'POST',
            data: $.param($scope.getUpdateData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if (response.data.status == "success") {
                Message.success(response.data.data);
                $('#classPicUpdateModal').modal('hide');
            } else {
                Message.warning(response.data.data);
                return;
            }
        })
    }
});

//私教排期预约课程课程页面
app.controller('coachLessonCtrl', function ($scope, $rootScope, $http, $location, $timeout) {

    $scope.coachId = $('#courseListBack').data('id');

    $scope.backPre = function () {
        history.go(-1);
    };
    //获取所有的场馆
    $http.get('/site/get-auth-venue').then(function (result) {
        $scope.venuesLists = result.data;
    });
    $scope.getData = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data == "" || result.data == undefined || result.data.length == undefined) {
                $scope.data = result.data;
                $scope.pages = result.pages;

                $scope.dataInfo = true;
            } else {
                $scope.data = result.data;
                $scope.pages = result.pages;
                $scope.dataInfo = false;
            }
        });
    };
    //查看上一周课程
    $scope.preWeek = function (pre, next, id) {
        var date = new Date(pre);
        //上一周周日日期
        var preSunday = $scope.getMyDate(date.getTime() - 24 * 60 * 60 * 1000);
        var preMonday = $scope.getMyDate(date.getTime() - 24 * 60 * 60 * 1000 * 7)
        $scope.pageInitUrl = '/private-lesson/private-class?coachId=' + $scope.coachId + '&weekStart=' + preMonday + '&weekEnd=' + preSunday;
        $scope.getData();
    };
    //查看下一周预约课程
    $scope.nextWeek = function (pre, next, id) {
        var date = new Date(next)
        var nextSunday = $scope.getMyDate(date.getTime() + 24 * 60 * 60 * 1000 * 7);
        var nextMonday = $scope.getMyDate(date.getTime() + 24 * 60 * 60 * 1000);
        $scope.pageInitUrl = '/private-lesson/private-class?coachId=' + $scope.coachId + '&weekStart=' + nextMonday + '&weekEnd=' + nextSunday;
        $scope.getData();
    };

    $scope.init = function () {
        $scope.pageInitUrl = '/private-lesson/private-class?coachId=' + $scope.coachId;
        $scope.getData();
    };
    $scope.init();


    /**
     *将时间戳转为日期
     * @create 2017/5/29
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
    //获取今天的日期
    $scope.dateCurrentw = $scope.getMyDate(new Date().getTime());
    //点击显示课程详情
    $scope.scheduleCourseDetail = function (id, aboutId, object) {
        var memberId = parseInt(id);
        $scope.privateClassAboutId = aboutId;
        $scope.aboutCourseDetail = object;
        $http.get('/private-teach/member-details?MemberId=' + memberId + '&classId=' + $scope.privateClassAboutId + '&id=' + $scope.aboutCourseDetail.id).then(function (result) {
            $scope.courseDetail = result.data;
        })
        $('#scheduleCourseDetail').modal('show');
    };
    //课程排期中课程详情页面中点击取消预约
    $scope.cancelOrder = function (id) {
        var id = parseInt(id);
        $http.get("/private-teach/update-class-status?id=" + $scope.aboutCourseDetail.id).then(function (result) {
            Message.success('操作成功');
            $scope.getData();
            $('#scheduleCourseDetail').modal('hide');
        });
    };
    //点击添加课程
    $scope.addCourseList = function (classDate) {
        $scope.nameChooseBoxOpen = false;
        $scope.searchMemberCompleteFlag = false;
        $scope.keywords = '';
        $scope.selectOrderCourseClassDate = classDate;
        var currentDate = $scope.dateCurrentw + " " + '00:00:00'
        currentDate = new Date(currentDate).getTime();
        var courseTime = new Date($scope.selectOrderCourseClassDate).getTime();
        if (currentDate >= courseTime) {
            Message.warning("请预约当前时间之后的课程");
            return;
        } else {
            $('#searchMember').modal('show');
        }
    };
    function load(){
        $("#loading").fadeOut("slow");
    }


    //选择私课课程列表
    $scope.privateCourseLists = function (id) {
        $scope.privateCoursateCoursePic == null;
        //私课课程列表
        $http({method: "GET", url: '/private-teach/get-private-teach-class?MemberId=' + id}).then(function (result) {
            $.loading.show();
            $scope.aloneData = result.data.data.alone;
            $scope.manyData = result.data.data.many;
            //aloneData的索引值接收数组
            $scope.costIndexArr = [];
            $scope.freeIndexArr = [];
            //如果存在赠送课，要将赠送课上完后才能上付费购买的课
            for(var i in $scope.aloneData) {
                if(parseInt($scope.aloneData[i].type) === 1) {$scope.costIndexArr.push(i);}
                //遍历课程数据的type字段，看是否有赠送课
                //当免费赠送的课存在剩余节数，并且总节数不等于0的时候
                //即表示：赠送的课没了，这个时候才可以上购买的课
                if(parseInt($scope.aloneData[i].type) === 4 && $scope.aloneData[i].overage_section > 0 && $scope.aloneData[i].course_amount != 0) {
                    $scope.freeIndexArr.push(i);
                    if($scope.freeIndexArr.length > 0) {
                        $timeout(function () {
                            for(var costIndex in $scope.costIndexArr) {
                                $('.courseListDataBox').children()
                                    .eq($scope.costIndexArr[costIndex])
                                    .attr('title','请优先选择该门课的赠送课程！')
                                    .children().last().find('button')
                                    .attr('disabled','true');
                            }
                        },500);
                    }
                }
            }
            //manyData的索引值接收数组
            $scope.costIndexArr1 = [];
            $scope.freeIndexArr1 = [];
            //manyData的处理   方式与注释同上
            for(var j in $scope.manyData) {
                if(parseInt($scope.manyData[j].type) === 1) {$scope.costIndexArr1.push(j);}
                if(parseInt($scope.manyData[j].type) === 4 && $scope.manyData[j].overage_section > 0 && $scope.manyData[j].course_amount != 0) {
                    $scope.freeIndexArr1.push(j);
                    if($scope.freeIndexArr1.length > 0) {
                        $timeout(function () {
                            for(var costIndex in $scope.costIndexArr1) {
                                $('.courseListDataBox').children()
                                    .eq($scope.costIndexArr1[costIndex] + $scope.aloneData.length)
                                    .attr('title','请优先选择该门课的赠送课程！')
                                    .children().last().find('button')
                                    .attr('disabled','true');
                            }
                        },500);
                    }
                }
            }
            //暂无数据的调用方法
            if (parseInt(result.data.data.alone.length) == 0 && parseInt(result.data.data.many.length) == 0) {
                $scope.dataInfo = true;
            } else {
                $scope.items = result.data.data;
                $scope.dataInfo = false;
            }
            $('#addReservationCourse').modal('hide');
            $.loading.hide();
            $('#selectPrivateCourseModal').modal('show');
        });
    };

    $scope.selectPrivateClassLengthBOOL = true;

    //选择私课课程按钮
    $scope.selectPrivateCourse = function (id, pic, name, object) {
        if (pic != '') {
            $scope.privateCoursePic = pic;
        } else {
            $scope.privateCoursePic = null;
        }
        if (id != '') {
            $scope.selectCourseId = id;
            //时间插件启动
            $('.clockpicker').clockpicker()
                .find('input').on('change', function () {
            });
        } else {
            $scope.selectCourseId = id;
            //时间插件启动
            $('.clockpicker').clockpicker()
                .find('input').on('change', function () {
            });
        }
        $scope.selectPrivateCourseDetail = object;
        if ($scope.privateCoursePic == '' || $scope.privateCoursePic == undefined) {
            $scope.privateCoursePic == null;
        }
        $scope.selectCourseName = name;
        $scope.selectedCourseData = object;
        $http.get('/private-teach/choose-class?orderId=' + $scope.selectedCourseData.id).then(function (result) {
             $scope.selectPrivateClass = result.data.data;
            if (result.data.status == "success") {
                $scope.selectPrivateClassLength = result.data.data.class_length
                if ($scope.selectPrivateClassLength != undefined || $scope.selectPrivateClassLength != null || $scope.selectPrivateClassLength != '') {
                    $scope.selectPrivateClassLengthBOOL = false;
                    //$scope.blueDate();
                }
                $scope.selectPrivateClassId = $scope.selectPrivateClass.id;
                if (result.data == 'false') {
                    Message.warning("该课程已预约完！");
                    return;
                } else {
                    $('#selectPrivateCourseModal').modal('hide');
                    $('#addReservationCourse').modal('show');
                }
            } else {
                Message.warning(result.data.data);
                return;
            }

        });
    };
    $scope.blueDate = function (time) {
        if($scope.selectPrivateClassLength != '' && $scope.selectPrivateClassLength != undefined && $scope.selectPrivateClassLength != null){
            var date = $scope.getNowFormatDate() + " " + $scope.startClass;
            var timestamp = Date.parse(new Date(date));
            $scope.endClass = timestamp + ($scope.selectPrivateClassLength * 60 * 1000)
        }else{
            $scope.endClass = '';
        }
        //console.log($scope.endClass)
    };

    $scope.getNowFormatDate = function () {
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
    };
    //完成预约课程
    $scope.completeOrderCourse = function () {
        $scope.blueDate();
        var date = new Date($scope.endClass);
        Y = date.getFullYear() + '-';
        M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
        D = date.getDate() + ' ';
        h = date.getHours()<10?'0'+date.getHours():date.getHours();
        m = date.getMinutes()<10?'0'+date.getMinutes():date.getMinutes();
        $scope.endClass = h + ':' + m;
        //console.log( $scope.endClass);
        $scope.orderCourse = {
            _csrf_backend: $('#_csrf').val(),
            classId: parseInt($scope.selectPrivateClassId),//int
            aboutType: 'mobile',
            classDate: $scope.selectOrderCourseClassDate,
            coachId: $scope.coachId,
            start: $scope.startClass,
            end: $scope.endClass,
            classType: 'charge',
            type: 'PC',
            memberCardId: parseInt($scope.searchMemberDetail.memberCardId),
            memberId: parseInt($scope.searchMemberDetail.memberId)//int
        };
        //判断当前时间和预约时间
        var currentTime = new Date().getTime();
        var courseStartTime = new Date($scope.selectOrderCourseClassDate + " " + $scope.startClass).getTime();
        if (courseStartTime <= currentTime) {
            Message.warning("请预约当前时间之后的时间!");
            return
        }
        if ($scope.selectPrivateClassId == null || $scope.selectPrivateClassId == undefined) {
            Message.warning("请选择预约课程");
            return;
        }
        if($scope.startClass =='' || $scope.startClass == null ||$scope.startClass == undefined){
            Message.warning("上课时间输入有误");
            return;
        }

        if ($scope.orderCourse.start == undefined || $scope.orderCourse.end == undefined) {
            Message.warning("上课时间不能为空");
            return;
        }

        /*if ($scope.orderCourse.start != undefined && $scope.orderCourse.end != undefined) {
            var current = new Date().getTime();
            var currentTime = $scope.getMyDate(current);
            var start = currentTime + ' ' + $scope.orderCourse.start + ':00';
            var end = currentTime + ' ' + $scope.orderCourse.end + ':00';
            var endTime = new Date(end).getTime();
            var startTime = new Date(start).getTime();
            /!*if (startTime >= endTime) {
                Message.warning('您选择的上课结束时间有误，请重新选择!')
                return;
            }*!/
        }*/
        /*console.log(endTime)
        return;*/
        $scope.completeOrderCourseFlag = true;
        $http({
            url: "/private-teach/set-about-class-charge",
            method: 'POST',
            data: $.param($scope.orderCourse),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            if (result.status == 'error') {
                $scope.completeOrderCourseFlag = false;
                Message.warning(result.message);
            } else if (result.status == 'success') {
                $('#addReservationCourse').modal('hide');
                $scope.privateCoursePic = null;
                $scope.selectPrivateClassId = '';
                $scope.selectOrderCourseClassDate = '';
                $scope.startClass = '';
                $scope.endClass = '';
                $scope.searchMemberDetail.memberId = '';
                $scope.selectPrivateClass = '';
                $scope.selectPrivateClassLengthBOOL = true;
                Message.success(result.success);
                //预约成功的时候刷新列表
                $scope.getData();
                //重置选课动画防止动画溢出
                $scope.completeOrderCourseFlag = false;
            }
            if (result.status == 'repeat') {
                $scope.completeOrderCourseFlag = false;
                Message.warning("同一个时间段不能重复预约");
                return;
            }
        });
    };
    $scope.cancellationCourse = function () {
        $scope.privateCoursePic = null;
        $scope.selectPrivateClassId = '';
        $scope.selectOrderCourseClassDate = '';
        $scope.startClass = '';
        $scope.endClass = '';
        $scope.searchMemberDetail.memberId = '';
        $scope.selectPrivateClass = '';
        $scope.selectPrivateClassLengthBOOL = true;
    };
    //搜索会员
    $scope.searchCardData = function () {
        return {
            keyword: $scope.keywords != undefined ? $scope.keywords : null
        }
    };
    //添加自动解冻方法
    $scope.AutomaticThawingMember = function(id){
        $http.get('/new-league/card-automatic-thaw?memberId='+id +'&isRequestMember=isMember').then(function(response){
            // console.log(response);
        });
    };
    //选择会员卡号  点击完成
    $scope.chooseMemberCardIdOk = function(memberCardId) {
        $scope.searchMemberCompleteFlag = false;
        if($('#chooseMemberCardId').val() == '' || $('#chooseMemberCardId').val() == undefined || $('#chooseMemberCardId').val() == null){
            Message.warning('请选择您的会员卡');
            return;
        }
        $scope.chooseMemberCardIdBool = true;
        $http.get('/private-teach/member?keyword=' + memberCardId).then(function (result) {
            if(result.data == '"noApply"'){
                Message.warning('此会员卡不能通店本场馆！');
                return;
            }
            if (result.data != 'null') {
                $scope.searchMemberDetail = result.data;
                $scope.AutomaticThawingMember(result.data.memberDetails.member_id);
                $scope.privateCoursePic = null;
                $scope.startClass = '';
                $scope.endClass = '';
                $scope.selectPrivateClassLength = '';
                $scope.selectPrivateClass = '';
                $('#chooseMemberCardModal').modal('hide');
                $scope.selectCourseName = '请选择私教课程';
                $('#addReservationCourse').modal('show');
                $scope.chooseMemberCardIdBool = false;
            } else {
                $scope.chooseMemberCardIdBool = false;
                Message.warning('未搜索到匹配信息，请重新输入姓名/手机号/卡号！');
                return;
            }
        })
    };
    //页面初始时不出现选择名字的下拉框
    $scope.nameChooseBoxOpen = false;
    //搜索会员预约课程
    $scope.searchMember = function () {
        if ($scope.keywords != undefined ? $scope.keywords : null) {
            $scope.searchMemberCompleteFlag = true;
            var $pattern = /^[1][3,4,5,7,8,6,9][0-9]{9}$/;//手机号正则
            var $$pattern = /^[\u4e00-\u9fa5]+$/;//汉字正则
            if (($pattern.test($scope.keywords))) {
                $scope.nameChooseBoxOpen = false;
                $http.get('/user/get-member-info?mobile=' + $scope.keywords).then(function (result) {
                    if(result.data != 'null' && result.data != '') {
                        $scope.haha = result.data.id;
                        if (result.data.id != null && result.data.id != undefined && result.data.id != "null" && result.data.id != '') {
                            $http.get('/user/member-card-info?MemberId=' + $scope.haha + '&type=2').then(function (result) {
                                if (result.data.item.length > 1) {
                                    $('#searchMember').modal('hide');
                                    $scope.selectCourseName = '请选择私教课程';
                                    $('#chooseMemberCardModal').modal('show');
                                    $scope.allMemberCardList = result.data.item;
                                }
                                if (result.data.item.length == 1) {
                                    var $cardFlag = result.data.item;
                                    var $cardNum = $cardFlag[0].card_number;
                                    $http.get('/private-teach/member?keyword=' + $cardNum).then(function (result) {
                                        if(result.data == '"noApply"'){
                                            Message.warning('此会员卡不能通店本场馆！');
                                            return;
                                        }
                                        if (result.data != 'null') {
                                            $('#searchMember').modal('hide');
                                            var $currentTime = new Date().getTime()/1000;
                                            var $data = result.data.memberCard;
                                            $data.forEach(function(item,index){
                                                if($cardNum == item.card_number){
                                                    $scope.CardEndTimeFlag = item.invalid_time;
                                                    $scope.cardStatus = item.status;
                                                }
                                            });
                                            if($scope.cardStatus == 2){
                                                Message.warning('会员卡状态异常！');
                                                return;
                                            }
                                            if($scope.cardStatus == 3){
                                                Message.warning('会员卡已冻结！');
                                                return;
                                            }
                                            if($scope.cardStatus == 4){
                                                Message.warning('会员卡未激活！');
                                                return;
                                            }
                                            // console.log($currentTime >= $scope.CardEndTimeFlag)
                                            if($currentTime >= $scope.CardEndTimeFlag){
                                                $scope.completeOrderCourseFlag = false;
                                                Message.warning('您的会员卡已到期！');
                                                return;
                                            }else{
                                                $scope.searchMemberDetail = result.data;
                                                $scope.AutomaticThawingMember(result.data.memberDetails.member_id);
                                                $scope.selectCourseName = '请选择私教课程';
                                                $('#addReservationCourse').modal('show');
                                                $scope.privateCoursePic = null;
                                                $scope.startClass = '';
                                                $scope.endClass = '';
                                                $scope.selectPrivateClassLength = '';
                                                $scope.selectPrivateClass = '';
                                                $scope.completeOrderCourseFlag = false;
                                            }
                                        } else {
                                            $scope.searchMemberCompleteFlag = false;
                                            Message.warning('您输入的信息有误或卡状态异常，请重新输入！');
                                            return;
                                        }
                                    })
                                }
                                if (result.data.item.length == 0 || result.data.item.length == undefined) {
                                    $scope.searchMemberCompleteFlag = false;
                                    Message.warning('您输入的卡号或手机号有误，请重新输入！');
                                    return false;
                                }
                            })
                        }
                    }else{
                        $scope.searchMemberCompleteFlag = false;
                        Message.warning('您输入的信息有误或卡状态异常，请重新输入！');
                        return false;
                    }
                })
            }else if($$pattern.test($scope.keywords)) {
                $http.get('/user/get-member-by-name?name=' + $scope.keywords).then(function (response) {
                    if(response.data.length === 0 || response.data.length === undefined) {
                        Message.warning('您输入的会员名有误，请重新输入！');
                        $scope.nameChooseBoxOpen = false;
                        $scope.searchMemberCompleteFlag = false;
                        return false;
                    }else if(response.data.length === 1) {
                        $scope.nameChooseBoxOpen = false;
                        $scope.nameChooseMemberId = response.data[0].member_id;
                        $http.get('/user/member-card-info?MemberId=' + $scope.nameChooseMemberId + '&type=2').then(function (memberResult) {
                            if(!memberResult) {
                                $scope.nameChooseBoxOpen = false;
                                $scope.searchMemberCompleteFlag = false;
                                Message.warning('获取信息失败！请刷新后重试！');
                                return false;
                            }else {
                                if($scope.nameChooseMemberId != null && $scope.nameChooseMemberId != undefined && memberResult.data.item.length === 0) {
                                    $scope.nameChooseBoxOpen = false;
                                    $scope.searchMemberCompleteFlag = false;
                                    Message.warning('该会员的会员卡已过期或状态异常！');
                                    return false;
                                }else if(memberResult.data.item.length === 1) {
                                    var $cardFlag = memberResult.data.item;
                                    var $cardNum = $cardFlag[0].card_number;
                                    $http.get('/private-teach/member?keyword=' + $cardNum).then(function (result) {
                                        if(result.data == '"noApply"'){
                                            Message.warning('此会员卡不能通店本场馆！');
                                            return;
                                        }
                                        if (result.data != 'null') {
                                            $('#searchMember').modal('hide');
                                            var $currentTime = new Date().getTime()/1000;
                                            var $data = result.data.memberCard;
                                            $data.forEach(function(item,index){
                                                if($cardNum == item.card_number){
                                                    $scope.CardEndTimeFlag = item.invalid_time;
                                                    $scope.cardStatus = item.status;
                                                }
                                            });
                                            if($scope.cardStatus == 2){
                                                Message.warning('会员卡状态异常！');
                                                return;
                                            }
                                            if($scope.cardStatus == 3){
                                                Message.warning('会员卡已冻结！');
                                                return;
                                            }
                                            if($scope.cardStatus == 4){
                                                Message.warning('会员卡未激活！');
                                                return;
                                            }
                                            if($currentTime >= $scope.CardEndTimeFlag){
                                                $scope.completeOrderCourseFlag = false;
                                                Message.warning('您的会员卡已到期！');
                                                return false;
                                            }else{
                                                $scope.searchMemberDetail = result.data;
                                                $scope.AutomaticThawingMember(result.data.memberDetails.member_id);
                                                $scope.selectCourseName = '请选择私教课程';
                                                $('#addReservationCourse').modal('show');
                                                $scope.searchMemberCompleteFlag = false;
                                                $scope.privateCoursePic = null;
                                                $scope.startClass = '';
                                                $scope.endClass = '';
                                                $scope.selectPrivateClassLength = '';
                                                $scope.selectPrivateClass = '';
                                                $scope.completeOrderCourseFlag = false;
                                            }
                                        }else {
                                            $scope.nameChooseBoxOpen = false;
                                            $scope.searchMemberCompleteFlag = false;
                                            Message.warning('您输入的信息有误或卡状态异常，请重新输入！');
                                            return false;
                                        }
                                    })
                                }else {
                                    $('#searchMember').modal('hide');
                                    $scope.selectCourseName = '请选择私教课程';
                                    $('#chooseMemberCardModal').modal('show');
                                    $scope.allMemberCardList = memberResult.data.item;
                                }
                            }
                        })
                    }else if(response.data.length > 1) {
                        $scope.nameChooseBoxOpen = true;
                        $scope.searchMemberCompleteFlag = false;
                        $scope.nameChooseList = response.data;
                    }
                })
            }else {
                $http.get('/private-teach/member?keyword=' + $scope.keywords).then(function (result) {
                    if(result.data == '"noApply"'){
                        Message.warning('此会员卡不能通店本场馆！');
                        return;
                    }
                    if (result.data != 'null') {
                        var $currentTime = new Date().getTime()/1000;
                        var $data = result.data.memberCard;
                        $data.forEach(function(item,index){
                            if($scope.keywords == item.card_number){
                                $scope.CardEndTimeFlag = item.invalid_time;
                                $scope.cardStatus = item.status;
                            }
                        });
                        if($scope.cardStatus == 2){
                            Message.warning('会员卡状态异常！');
                            return;
                        }
                        if($scope.cardStatus == 3){
                            Message.warning('会员卡已冻结！');
                            return;
                        }
                        if($scope.cardStatus == 4){
                            Message.warning('会员卡未激活！');
                            return;
                        }
                        if($currentTime >= $scope.CardEndTimeFlag){
                            $scope.nameChooseBoxOpen = false;
                            $scope.searchMemberCompleteFlag = false;
                            Message.warning('您的会员卡已到期！');
                            return false;
                        }else{
                            $scope.searchMemberDetail = result.data;
                            $scope.AutomaticThawingMember(result.data.memberDetails.member_id);
                            $scope.privateCoursePic = null;
                            $scope.startClass = '';
                            $scope.endClass = '';
                            $scope.selectPrivateClassLength = '';
                            $scope.selectPrivateClass = '';
                            $('#searchMember').modal('hide');
                            $scope.selectCourseName = '请选择私教课程';
                            $('#addReservationCourse').modal('show');
                            $scope.nameChooseBoxOpen = false;
                            $scope.searchMemberCompleteFlag = false;
                        }
                    } else {
                        $scope.nameChooseBoxOpen = false;
                        $scope.searchMemberCompleteFlag = false;
                        Message.warning('您输入的信息有误或卡状态异常，请重新输入！');
                        return false;
                    }
                })
            }
        } else {
            $scope.nameChooseBoxOpen = false;
            $scope.searchMemberCompleteFlag = false;
            Message.warning("请输入会员姓名或会员卡号或手机号!");
            return false;
        }
    };
    //选择名字后获取对应用户下面的卡
    $scope.nameChooseClick = function (id) {
        $scope.nameChooseBoxOpen = false;
        $http.get('/user/member-card-info?MemberId=' + id + '&type=2').then(function (memberResult1) {
            if(!memberResult1) {
                $scope.searchMemberCompleteFlag = false;
                Message.warning('获取信息失败！请刷新后重试！');
                return false;
            }else {
                if(id != null && id != undefined && memberResult1.data.item.length === 0) {
                    $scope.searchMemberCompleteFlag = false;
                    Message.warning('该会员的会员卡已过期或状态异常！');
                    return false;
                }else if(memberResult1.data.item.length === 1) {
                    var $cardFlag = memberResult1.data.item;
                    var $cardNum = $cardFlag[0].card_number;
                    $http.get('/private-teach/member?keyword=' + $cardNum).then(function (result) {
                        if(result.data == '"noApply"'){
                            Message.warning('此会员卡不能通店本场馆！');
                            return;
                        }
                        if (result.data != 'null') {
                            $('#searchMember').modal('hide');
                            var $currentTime = new Date().getTime()/1000;
                            var $data = result.data.memberCard;
                            $data.forEach(function(item,index){
                                if($cardNum == item.card_number){
                                    $scope.CardEndTimeFlag = item.invalid_time;
                                    $scope.cardStatus = item.status;
                                }
                            });
                            if($scope.cardStatus == 2){
                                Message.warning('会员卡状态异常！');
                                return;
                            }
                            if($scope.cardStatus == 3){
                                Message.warning('会员卡已冻结！');
                                return;
                            }
                            if($scope.cardStatus == 4){
                                Message.warning('会员卡未激活！');
                                return;
                            }
                            if($currentTime >= $scope.CardEndTimeFlag){
                                $scope.completeOrderCourseFlag = false;
                                Message.warning('您的会员卡已到期！');
                                return false;
                            }else{
                                $scope.searchMemberDetail = result.data;
                                $scope.AutomaticThawingMember(result.data.memberDetails.member_id);
                                $scope.selectCourseName = '请选择私教课程';
                                $('#addReservationCourse').modal('show');
                                $scope.searchMemberCompleteFlag = false;
                                $scope.privateCoursePic = null;
                                $scope.startClass = '';
                                $scope.endClass = '';
                                $scope.selectPrivateClassLength = '';
                                $scope.selectPrivateClass = '';
                                $scope.completeOrderCourseFlag = false;
                            }
                        }else {
                            $scope.searchMemberCompleteFlag = false;
                            Message.warning('您输入的信息有误或卡状态异常，请重新输入！');
                            return false;
                        }
                    })
                }else {
                    $('#searchMember').modal('hide');
                    $scope.selectCourseName = '请选择私教课程';
                    $('#chooseMemberCardModal').modal('show');
                    $scope.allMemberCardList = memberResult1.data.item;
                }
            }
        })
    };
    //判断是否点击回车按钮
    angular.element(document).ready(function () {
        //点击回车立即预约
        $("#searchMember").keydown(function (event) {
            if ((event.keyCode || event.which) == 13) {
                $scope.searchMember();
            }
        });
    });
    //返回添加预约
    $scope.backAddPrivate = function () {
        $('#selectPrivateCourseModal').modal('hide');
        $('#addReservationCourse').modal('show');
    }
});

