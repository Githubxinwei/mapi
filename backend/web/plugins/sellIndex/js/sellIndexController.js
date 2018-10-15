// 苏雨 2017-6-6
// 销售主页
/**
   财务管理模块-销售统计页面
   内容：销售额统计、员工业绩、课程预约饼状图、销售额排行榜、客户渠道来源
        即将到期会员、未签到会员、生日会员、新增会员、销售额、客流量
 * @author  zhujunzhe@itsports.club
 * @create 2017.11.30
 * @param $name
 * @return array
 */
angular.module('App').controller('sellIndexCtrl', function($scope,$http,$timeout) {
    
    // 载入动画的js
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('main').className += 'loaded';
    });

    // 载入动画显示1.
    $scope.loderAnimateShow = function () {
        $(".loader-animate1").show();
    };
    // 载入动画显示2
    $scope.loderAnimateShow2 = function () {
        $(".loader-animate2").show();
    };
    // 载入动画显示3
    $scope.loderAnimateShow3 = function () {
        $(".loader-animate3").show();
    };
    // 载入动画显示4
    $scope.loderAnimateShow4 = function () {
        $(".loader-animate4").show();
    };
    // 载入动画关闭1
    $scope.loderAnimateHide = function () {
        $(".loader-animate1").hide();
    };
    // 载入动画关闭2
    $scope.loderAnimateHide2 = function () {
        $(".loader-animate2").hide();
    };
    // 载入动画关闭3
    $scope.loderAnimateHide3 = function () {
        $(".loader-animate3").hide();
    };
    // 载入动画关闭4
    $scope.loderAnimateHide4 = function () {
        $(".loader-animate4").hide();
    };

    // select2插件载入
    $(function (){
        $("#expireModal").on("shown.bs.modal", function(){
            $("#expireAdviserSelect2").select2({
                language: "zh-CN",
                dropdownParent:$("#expireModal")
            });
        });
        $("#noSignModal").on("shown.bs.modal", function(){
            $("#noSignListIdSelect2").select2({
                language: "zh-CN",
                dropdownParent:$("#noSignModal")
            });
        });
        $("#birthdayModal").on("shown.bs.modal", function(){
            $("#birthdayIdSelect2").select2({
                language: "zh-CN",
                dropdownParent:$("#birthdayModal")
            });
        });
        $("#addUserModal").on("shown.bs.modal", function(){
            $("#addUserAdviserSelect2").select2({
                language: "zh-CN",
                dropdownParent:$("#addUserModal")
            });
        });
        $("#peopleNumberModal").on("shown.bs.modal", function(){
            $("#peopleNumAdviserIdSelect2").select2({
                language: "zh-CN",
                dropdownParent:$("#peopleNumberModal")
            });
        });
    });

    //获取当前日期 20170721
    $scope.getDateTime = function () {
        var oDate = new Date();
        var oYear = oDate.getFullYear();
        var oMonth = oDate.getMonth() + 1;
        oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
        var oDay = oDate.getDate();
        oDay = oDay >= 10 ? oDay : '0' + oDay;
        var oHours = oDate.getHours();
        oHours = oHours >= 10 ? oHours : '0' + oHours;
        var oMinutes = oDate.getMinutes();
        oMinutes = oMinutes >= 10 ? oMinutes : '0' + oMinutes;
        var oSeconds = oDate.getSeconds();
        oSeconds = oSeconds >= 10 ? oSeconds : '0' + oSeconds;
        var theDate = oYear + "-" + oMonth + "-" + oDay;
        // var theDateTime = oYear+"-"+oMonth+"-"+oDay +" "+ oHours +':'+ oMinutes+":"+oSeconds;

        return theDate;
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
        return theDate
    };

    $scope.currentNowTimeS = new Date().getTime() / 1000;
    //销售当日、当周、当月筛选下拉
    $scope.filtrateSelect = [
        {
            value: 'd',
            name: '当日'
        },
        {
            value: 'w',
            name: '当周'
        },
        {
            value: 'm',
            name: '当月'
        }
    ]


    /*------------新增会员start---------------*/

    // 新增会员的点击方法
    $scope.addMemberSelectedId = $scope.filtrateSelect[0].value;

    /**处理搜索数据***/
    $scope.searchCardData = function () {
        return {
            sortType: $scope.sortType != undefined ? $scope.sortType : null,
            sortName: $scope.sort != undefined ? $scope.sort : null
        }
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

    $scope.initPath = function () {
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:'get',url:'/user/get-adviser?venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.addUserAdviserListData = data.data
        },function (error) {
            // console.log(error);
            Message.error("系统错误请联系工作人员")
        })
        $http({method:'get',url:'/member-card/get-type'}).then(function (data) {
            // console.log(data.data.type);
            $scope.addUserMemberTypeListData = data.data.type
        },function (error) {
            // console.log(error);
            Message.error("系统错误请联系工作人员")
        })
        $scope.searchAddMemberParams = $scope.searchCardData();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $scope.addMemberUrl = '/sell-index/new-member?type=' + $scope.addMemberSelectedId+'&venueId='+$scope.venueChangeId + '&' + $.param($scope.searchAddMemberParams);
    };

    // $scope.addUserAdviserChange = function (id) {
    //     $scope.addUserAdviser = id;
    // }
    // $scope.addUserMemberTypeChange = function (id) {
    //     $scope.addUserMemberType = id;
    // }
    // 新增会员排序
    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.AddMemberDataInit();
    };

    //获取新增会员人数
    $scope.getAddMemberDataCount = function () {
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/new-members?type=' + $scope.addMemberSelectedId+'&venueId='+$scope.venueChangeId).then(function (data) {
            // console.log('新增',data);
            $scope.allCountsAllNum = data.data.replace(/\"/g, "");
            if($scope.allCountsAllNum.length <= 5){
                $scope.countsAllNum = $scope.allCountsAllNum;
            }
            else{
                $scope.countsAllNum = $scope.allCountsAllNum.substring(0,$scope.allCountsAllNum.length-4) + 'W';
            }
        });
    };

    $scope.getAddMemberDataCount();
    //select新增会员人数
    $scope.addMemberSelectCount = function () {
        $scope.getAddMemberDataCount();
    };


    $scope.enterSearchAddUser = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchCardAddUser();
        }
    }
    
    //新增会员筛选清空按钮
    $scope.searchCardAddUserClear = function(){
        $("#addUserReservation").val('');
        $scope.keywordAddUser = '';
        $scope.addUserMemberType = '';
        $scope.addUserAdviser = '';
        $('#select2-addUserAdviserSelect2-container').text('选择顾问');
        $scope.searchCardAddUser();
    }
    
    $scope.searchCardAddUser = function () {
        var startTime = $("#addUserReservation").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+"00:00:00"}
        var endTime = $("#addUserReservation").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime+"23:59:59"}
        if($scope.keywordAddUser == undefined){$scope.keywordAddUser = ''};
        if($scope.addUserMemberType == undefined){$scope.addUserMemberType = ''};
        if($scope.addUserAdviser == undefined){$scope.addUserAdviser = ''};
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $.loading.show();
        $http({method:'get',url:'/sell-index/new-member?keywords='+$scope.keywordAddUser+"&startTime="+startTime+"&endTime="+endTime+"&cardType="+$scope.addUserMemberType+"&sellId="+$scope.addUserAdviser+'&venueId='+$scope.venueChangeId}).then(function (data) {
            // console.log('新增',data)
            if(data.data.data.length != 0){
                $scope.addUserList      = data.data.data;
                $scope.addMemberPages   = data.data.pages;
                $scope.counts            = data.data.count;
                $scope.addMemberInfo     = false;
                $scope.addMemberPageNow  = data.data.now;
            }else{
                $scope.addUserList      = data.data.data;
                $scope.addMemberPages   = data.data.pages;
                $scope.addMemberPageNow  = 1;
                $scope.counts             = 0;
                $scope.addMemberInfo     = true;
            }
            $.loading.hide();
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员");
            $.loading.hide();
        })


    }

    //获取新增会员
    $scope.getAddMemberData = function (){
        $.loading.show();
        $http.get($scope.addMemberUrl).success(function (data){
            // console.log('新增会员',data)
            if(data.data.length != 0){
                $scope.addUserList      = data.data;
                $scope.addMemberPages   = data.pages;
                $scope.counts            = data.count;
                $scope.addMemberInfo     = false;
                $scope.addMemberPageNow  = data.now;
            }else{
                $scope.addUserList      = data.data;
                $scope.addMemberPages   = data.pages;
                $scope.addMemberPageNow  = 1;
                $scope.counts             = 0;
                $scope.addMemberInfo     = true;
            }
            $.loading.hide();
        });
    };
    /**搜索方法***/
    $scope.AddMemberDataInit = function () {
        $scope.initPath();
        $scope.getAddMemberData();
    };
    $scope.AddMemberDataInit();
    //点击新增会员时调用
    $scope.addUserFun = function(){
        // $scope.AddMemberDataInit();
        $scope.getAddMemberDataCount();
        $scope.getAllTimeShowModal($scope.addMemberSelectedId,$("#addUserReservation"));
        $scope.searchCardAddUser();
    }
    //筛选新增会员
    $scope.addMemberSelect = function(){
        $scope.AddMemberDataInit();
        $scope.getAddMemberDataCount();
    }

    // 分页加载的js
    $scope.newPages = function (urlPages) {
        $scope.addMemberUrl = urlPages;
        $scope.getAddMemberData();
    };
    /*------------新增会员end---------------*/



    /*------------生日会员start--------------*/
    //初始调用
    $scope.birthdayMemberSelectId = $scope.filtrateSelect[0].value;
    /**处理搜索数据***/
    $scope.searchBirCardData = function () {
        return {
            sortType       : $scope.sortType  != undefined  ? $scope.sortType : null,
            sortName       : $scope.sort      != undefined  ? $scope.sort :null
        }
    };
    //生日会员列表升降序
    $scope.switchBirSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };

    //获取生日会员人数
    $scope.getBirthdayMemberCount = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/birthday-members?type='+$scope.birthdayMemberSelectId+"&venueId="+$scope.venueChangeId).then(function(response){
            $scope.beforeBirCountAll          = response.data.replace(/\"/g, "");
            if($scope.beforeBirCountAll.length <= 5){
                $scope.birCountAll = $scope.beforeBirCountAll;
            }
            else{
                $scope.birCountAll = $scope.beforeBirCountAll.substring(0,$scope.beforeBirCountAll.length-4) + 'W';
            }
        });
    };
    $scope.getBirthdayMemberCount();

    //select获取会员生日人数
    $scope.birthdayMemberSelectCount = function(){
        $scope.getBirthdayMemberCount();
    };

    //初始生日会员url
    $scope.birthdayMemberUrlInit = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:'get',url:'/user/get-adviser?venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.adviserBirthdayListData = data.data;
        },function (error) {
            // console.log(error);
            Message.error("系统错误请联系工作人员")
        });
       $scope.searchBirthdayMemberData =   $scope.searchBirCardData();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $scope.pageBirInitUrl =  '/sell-index/birthday-member?type='+$scope.birthdayMemberSelectId+'&venueId='+$scope.venueChangeId +'&'+ $.param($scope.searchBirthdayMemberData);
    };


    $scope.enterSearchBirthday = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13){
            $scope.searchCardBirthday()
        }
    }
    //生日会员筛选清空按钮
    $scope.searchCardBirthdayClear = function(){
        $scope.birthdayId        = '';
        $scope.venueChangeId     = '';
        $scope.keywordBirthday   = '';
        $scope.memberType        = '';
        $("#datetimeStart1").val('');
        $("#datetimeStart2").val('');
        $('#select2-birthdayIdSelect2-container').text('选择顾问');
        $scope.searchCardBirthday();
    }

    $scope.searchCardBirthday = function () {
        if ($scope.keywordBirthday == undefined){$scope.keywordBirthday = ''}
        var startTime = $("#datetimeStart1").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+ ' '+"00:00:00"}
        var endTime = $("#datetimeStart2").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime +' '+"23:59:59"}
        if($scope.birthdayId == undefined){$scope.birthdayId ='';}
        if($scope.memberType == undefined){$scope.memberType ='';}
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $.loading.show();
        $http({method:"get",url:'/sell-index/birthday-member?keywords='+$scope.keywordBirthday+"&startTime="+startTime+"&endTime="+endTime+"&sellId="+$scope.birthdayId+'&venueId='+$scope.venueChangeId+'&memberType='+$scope.memberType}).then(function (data) {
            // console.log('生日会员',data)
            if(data.data.data.length != 0) {
                $scope.birthdayUserList = data.data.data;
                $scope.birCount          = data.data.count;
                $scope.birPages          = data.data.pages;
                $scope.birMemberInfo     = false;
                $scope.birMemberPageNow  = data.data.now;
            }else{
                $scope.birPages          = data.data.pages;
                $scope.birthdayUserList = data.data.data;
                $scope.birMemberPageNow  = 1;
                $scope.birCount           = 0;
                $scope.birMemberInfo      = true;
            }
            $.loading.hide();
        },function (error) {
            // console.log(error);
            Message.error("系统错误请联系管理人员");
            $.loading.hide();
        })
    }
    //获取生日会员数据
    $scope.getBirData = function (){
        $.loading.show();
        $http.get($scope.pageBirInitUrl).success(function (response){
            // console.log('生日会员123',response);
            if(response.data.length != 0){
                $scope.birthdayUserList = response.data;
                $scope.birCount          = response.count;
                $scope.birPages          = response.pages;
                $scope.birMemberInfo     = false;
                $scope.birMemberPageNow  = response.now;
            }else{
                $scope.birPages          = response.pages;
                $scope.birthdayUserList = response.data;
                $scope.birMemberPageNow  = 1;
                $scope.birCount           = 0;
                $scope.birMemberInfo      = true;
            }
            $.loading.hide();
        })
    };
    //初始化调用生日会员
    $scope.BirDataInit = function(){
        $scope.birthdayMemberUrlInit();
        $scope.getBirData();
    }


    //点击生日会员图标
    $scope.birthdayMemberClick = function(){
        $scope.BirDataInit();
        $scope.getBirthdayMemberCount();
        $scope.getAllTimeShowModal($scope.birthdayMemberSelectId,$("#datetimeStart1"),$("#datetimeStart2"));
    }

    //筛选不同时间段生日会员
    $scope.birthdayMemberSelect = function(){
        $scope.BirDataInit();
    }
    // 生日会员排序
    $scope.changeBirSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchBirSort(sort);
        $scope.BirDataInit();
    };
    // 分页加载的js
    // birthPages soonPages notPages 分页方法名 第一个生日 第二个过期 第三个未签到
    $scope.birthPages = function (urlPages) {
        $scope.pageBirInitUrl = urlPages;
        $scope.getBirData();
    };

    /*------------生日会员end--------------*/


    /*-----------------------即将到期start--------------------*/
    // 初始化设置默认值
    $scope.expireSelectId = $scope.filtrateSelect[0].value;
    $scope.expireUser = 0;
    /**处理搜索数据***/
    $scope.searchEXCardData = function () {
        return {
            sortType       : $scope.sortType  != undefined  ? $scope.sortType : null,
            sortName       : $scope.sort      != undefined  ? $scope.sort :null
        }
    };
    $scope.switchEXSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };

    // 获取场馆信息
    $http.get('/site/get-auth-venue').success(function (data){
        $scope.venueList = data;
    });
    // 场馆切换的触发
    $scope.venueChange = function (id){
        $scope.venueChangeId = id;
        $scope.currentSellMoneyCount = [];
        $scope.getExpireUserCounts();
        $scope.getNoSignChangeCount();
        $scope.getBirthdayMemberCount();
        $scope.getAddMemberDataCount();
        $scope.getAllMoney();
        $scope.getPeopleNumSelectCount();
        $scope.getCurrentDayMoney();
        $scope.getMemberClass();
        $scope.rankSaleFun();
        $scope.getSourceDitch();
        $scope.employeePerformance();
    };

    //初始化即将到期url
    $scope.expireUserUrlInit = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:'get',url:'/user/get-adviser?venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.adviserListData = data.data;
            // console.log(data.data)
        },function (error) {
            // console.log(error);
            Message.error("系统错误请联系工作人员")
        });
        $scope.searchParams =  $scope.searchBirCardData();
        $scope.expireUserUrl = '/sell-index/soon-due-card?type='+$scope.expireSelectId +"&venueId="+$scope.venueChangeId+'&'+$.param($scope.searchParams);
    };

    // //获取即将到期人数
    $scope.getExpireUserCounts = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/soon-due-member?type='+$scope.expireSelectId+'&venueId='+$scope.venueChangeId).then(function(data){
            $scope.beforeExpireUserAll = data.data.replace(/\"/g, "");
            if($scope.beforeExpireUserAll.length <= 5){
                $scope.expireUserAll = $scope.beforeExpireUserAll;
            }
            else{
                $scope.expireUserAll = $scope.beforeExpireUserAll.substring(0,$scope.beforeExpireUserAll.length-4) + 'W';
            }
            // console.log($scope.expireUser);
        })
    };
    $scope.getExpireUserCounts();
    
    //select即将到期统计人数
    $scope.expireUserSelectCount = function(){
        $scope.getExpireUserCounts();
    };
    $scope.enterSearchExpire = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13){
            $scope.searchCardExpire();
        }
    };
    //清空即将到期筛选
    $scope.searchCardExpireClear = function(){
        $(".reservationExpire").val('');
        $scope.expireKeywords = '';
        $scope.expireAdviser  = '';
        $('#select2-expireAdviserSelect2-container').text('选择顾问');
        $scope.searchCardExpire();
    }
    $scope.searchCardExpire = function () {
        var startTime = $(".reservationExpire").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+"00:00:00"}
        var endTime = $(".reservationExpire").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime+"23:59:59"}
        if($scope.expireKeywords == undefined){$scope.expireKeywords = ''}
        if($scope.expireAdviser == undefined){$scope.expireAdviser = '';}
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $.loading.show();
        $http({method:"get",url:'/sell-index/soon-due-card?keywords='+$scope.expireKeywords+"&startTime="+ startTime+"&endTime="+endTime+"&sellId="+$scope.expireAdviser+'&venueId='+$scope.venueChangeId}).then(function (data) {
            // console.log(data)
            if(data.data.data.length != 0) {
                $scope.expireUserList = data.data.data;
                $scope.expireUser = data.data.count;
                $scope.EXPages = data.data.pages;
                $scope.EXPageNow = data.data.now;
                $scope.expireUserInfo = false;
            }else{
                $scope.EXPages = data.data.pages;
                $scope.expireUserList = data.data.data;
                $scope.expireUser = 0;
                $scope.expireUserInfo = true;
            }
            $.loading.hide();
        },function (error) {
            // console.log(error);
            Message.error("系统错误请联系管理人员");
            $.loading.hide();
        })
    };
    //获取即将到期数据
    $scope.getExpireUserData = function(){
        $.loading.show();
        $http.get($scope.expireUserUrl).then(function (data){
           if(data.data.data.length != 0) {
                $scope.expireUserList = data.data.data;
                $scope.expireUser = data.data.count;
                $scope.EXPages = data.data.pages;
                $scope.EXPageNow = data.data.now;
                $scope.expireUserInfo = false;
            }else{
                $scope.EXPages = data.data.pages;
                $scope.expireUserList = data.data.data;
                $scope.expireUser = 0;
                $scope.expireUserInfo = true;
            }
            $.loading.hide();
        });
    }

    //初始化调用即将到期
    $scope.expireUserInit = function(){
        $scope.expireUserUrlInit();
        $scope.getExpireUserData();
    }

    //点击即将到期时调用
    $scope.expireUserFun = function(){
        $scope.expireUserInit();
        $scope.getAllTimeShowModal($scope.expireSelectId,$(".reservationExpire"));
    }
    // 即将到期的会员下拉框事件
    $scope.expireUserSelect = function (){
        $scope.getExpireUserCounts();
        $scope.expireUserInit();
    };


    // 即将到期会员排序
    $scope.changeEXSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchEXSort(sort);
        $scope.expireUserInit();
    };
    // 分页加载的js
    $scope.soonPages = function (urlPages) {
        $scope.expireUserUrl = urlPages;
        $scope.getExpireUserData();
    };
    $scope.getAllTimeShowModal = function(selectId,obj_dom,obj_dom2) {
        //new一个date对象
        var expireModelDateToday = new Date();
        //获取年
        var expireModelDateYear = expireModelDateToday.getFullYear();
        //获取月
        var expireModelDateMonth = expireModelDateToday.getMonth()+1;
        //显示要显示成两位数
        var expireModelDateDay = expireModelDateToday.getDate() < 10 ?'0'+expireModelDateToday.getDate():expireModelDateToday.getDate();
        //获取今天的年月日
        var expireModelDateNow = expireModelDateYear+'-'+expireModelDateMonth+'-'+expireModelDateDay;
        //获取今日所在的   年-月
        var expireModelMonthCurrent = $scope.currentDate();
        $scope.getCurrentWeek();
        //取当年当月中的第一天
        var new_date = new Date(expireModelDateYear,expireModelDateMonth,1);
        //获取当月最后一天日期
        var expireModelDateLastDay = new Date(new_date.getTime()-1000*60*60*24).getDate();
        if( selectId== 'd') {
            if(obj_dom2 != undefined) {
                obj_dom.val(expireModelDateNow);
                obj_dom2.val(expireModelDateNow)
            }else{
                obj_dom.val(expireModelDateNow+' - '+expireModelDateNow);
            }
        }else if(selectId == 'w') {
            if(obj_dom2 != undefined) {
                obj_dom.val($scope.currentWeekMonday);
                obj_dom2.val($scope.currentWeekSunday)
            }else{
                obj_dom.val($scope.currentWeekMonday+' - '+$scope.currentWeekSunday);
            }
        }else if(selectId == 'm') {
            if(obj_dom2 != undefined) {
                obj_dom.val(expireModelMonthCurrent+'-01');
                obj_dom2.val(expireModelMonthCurrent+'-'+expireModelDateLastDay)
            }else{
                obj_dom.val(expireModelMonthCurrent+'-01'+' - '+expireModelMonthCurrent+'-'+expireModelDateLastDay);
            }
        }
    }
    /*-----------------------即将到期end--------------------*/


    /*-----------------------未签到start--------------------*/

    // 未签到人数的点击方法
    $scope.noSignSelectId = "30"; // 设置默认值
    //未签到列表升降序
    $scope.switchNotSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    /**处理搜索数据***/
    $scope.searchNotCardData = function () {
        return {
            sortType       : $scope.sortType  != undefined  ? $scope.sortType : null,
            sortName       : $scope.sort      != undefined  ? $scope.sort :null
        }
    };
    //获取未签到人数
    $scope.getNoSignChangeCount = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/not-entry-data?type=' + $scope.noSignSelectId+"&venueId="+$scope.venueChangeId).then(function(data){
            // console.log('未签到',data)
            $scope.beforeNoSignCountAll   = data.data.replace(/\"/g, "");
            if($scope.beforeNoSignCountAll.length <= 5){
                $scope.noSignCountAll = $scope.beforeNoSignCountAll;
            }
            else{
                $scope.noSignCountAll = $scope.beforeNoSignCountAll.substring(0,$scope.beforeNoSignCountAll.length-4) + 'W';
            }
            // console.log($scope.noSignCountAll)
        });
    };
    $scope.getNoSignChangeCount();

    //select未签到人数
    $scope.noSignChangeCount = function(){
        $scope.getNoSignChangeCount();
    };
    //未签到默认url
    $scope.noSignUrlInit = function(){
        $scope.noSignSearchParams = $scope.searchNotCardData();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $scope.noSignUrl ='/sell-index/not-entry?type=' + $scope.noSignSelectId+"&venueId="+$scope.venueChangeId+'&'+ $.param($scope.noSignSearchParams);
    };
    //获取未签到数据
    $scope.getNoSignData = function(){
        $.loading.show();
        $http.get($scope.noSignUrl).then(function (data){
            // console.log('未签到',data);
            if(data.data.data.length != 0){
                $scope.noSignList    = data.data.data;
                $scope.noSignCount   = data.data.count;
                $scope.reNotPages    = data.data.pages;
                $scope.noSignPageNow = data.data.now;
                $scope.noSignInfo = false;
            }else{
                $scope.reNotPages    = data.data.pages;
                $scope.noSignList    = data.data.data;
                $scope.noSignPageNow = 1;
                $scope.noSignCount   = 0;
                $scope.noSignInfo    = true;
            }
            $.loading.hide();
        });
    }
    $scope.enterSearchNoSign = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13){
            $scope.searchCardNoSign();
        }
    };
    //清空未签到筛选按钮
    $scope.searchCardNoSignClear = function(){
        $scope.keywordsNoSign = '';
        $scope.noSignListId   = '';
        $("#noSignReservation").val('');
        $('#select2-noSignListIdSelect2-container').text('选择顾问');
        $scope.searchCardNoSign();
    }

    $scope.searchCardNoSign = function () {
        var startTime = $("#noSignReservation").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+' '+"00:00:01"}
        var endTime = $("#noSignReservation").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime+' '+"23:59:59"}
        if($scope.keywordsNoSign == undefined){$scope.keywordsNoSign = '';}
        if($scope.noSignListId == undefined){$scope.noSignListId = '';}
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $.loading.show();
        $http({method:'get',url:'/sell-index/not-entry?keywords='+$scope.keywordsNoSign+"&startTime="+startTime+"&endTime="+endTime+"&sellId="+$scope.noSignListId+'&venueId='+$scope.venueChangeId}).then(function (data) {
            if(data.data.data.length != 0){
                $scope.noSignList    = data.data.data;
                $scope.noSignCount   = data.data.count;
                $scope.reNotPages    = data.data.pages;
                $scope.noSignPageNow = data.data.now;
                $scope.noSignInfo = false;
            }else{
                $scope.reNotPages    = data.data.pages;
                $scope.noSignList    = data.data.data;
                $scope.noSignPageNow = 1;
                $scope.noSignCount   = 0;
                $scope.noSignInfo    = true;
            }
            $.loading.hide();
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        })
    }
    //初始化默认选中当天
    $scope.noSignSelectId =$scope.filtrateSelect[0].value;
    //初始化调用未签到数据
    $scope.noSignInit = function(){
        /*$scope.noSignUrlInit();
        $scope.getNoSignData();*/
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:'get',url:'/user/get-adviser?venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.noSignListData = data.data
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }


    //点击未签到图标
    $scope.noSignChangeLists = function(){
        $scope.noSignInit();
        //$scope.getNoSignChangeCount();
        $scope.getAllTimeShowModal($scope.noSignSelectId,$("#noSignReservation"));
        $scope.searchCardNoSign();
       /* $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:'get',url:'/user/get-adviser?venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.noSignListData = data.data
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系工作人员")
        })*/
    }

    // 未签到的会员下拉框事件
    $scope.noSignChange = function (){
        $scope.noSignInit();
        $scope.getNoSignChangeCount();
    };
    // 未签到会员排序
    $scope.changeNotSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchNotSort(sort);
        $scope.noSignInit();
    };
    // 未签到分页加载的js
    $scope.notPages = function (urlPages){
        $scope.noSignUrl = urlPages;
        $scope.getNoSignData();
    };

    /*-----------------------未签到end--------------------*/



    /*----------------------  销售、客流量start-----------------------------*/

    //客流量搜索排序和销售额
    $scope.searchPeopleNumData = function () {
        return {
            searchGood     :$scope.shopTypeId != undefined  ? $scope.shopTypeId : null,
            sortType       : $scope.sortType  != undefined  ? $scope.sortType : null,
            sortName       : $scope.sort      != undefined  ? $scope.sort :null
        }
    };

    

    //初始化该销售url
    $scope.selectSaleMoneyId = $scope.filtrateSelect[0].value;
    $scope.saleMoneyUrlInit = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $scope.saleMoneyUrl = '/sell-index/sales-money?type='+ $scope.selectSaleMoneyId+'&venueId='+$scope.venueChangeId +'&'+ $.param($scope.searchPeopleNumData());
    };
    $scope.enterSearchSaleMoney = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13){
            $scope.searchCardSaleMoney();
        }
    };
    //销售额筛选清空按钮
    $scope.searchCardSaleMoneyClear = function(){
        $("#saleMoneyReservation").val('');
        $scope.keywordSaleMoney ='';
        $scope.shopTypeId = '';
        $scope.searchCardSaleMoney();
    }

    $scope.searchCardSaleMoney = function () {
        // $scope.getAllMoney();
        var startTime = $("#saleMoneyReservation").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+ ' ' + "00:00:00"}
        var endTime = $("#saleMoneyReservation").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime+ ' '+"23:59:59"}
        if($scope.keywordSaleMoney == undefined){
            $scope.keywordSaleMoney ='';
        }
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $.loading.show();
        $http({method:'get',url:'/sell-index/sales-money?keywords='+$scope.keywordSaleMoney+"&startTime="+startTime +"&endTime="+endTime+"&searchGood="+$scope.shopTypeId+'&venueId='+$scope.venueChangeId}).then(function (response) {
            if(response.data.data.length != 0) {
                $scope.saleMoneyLists = response.data.data;
                $scope.saleMoneyPages = response.data.pages;
                $scope.saleMoneyCount = response.data.count;
                $scope.saleMoneyNow   = response.data.now;
                $scope.saleMoneyInfo  = false;
                $scope.modalAllMoney  = response.data.totalMoney;
            }else{
                $scope.saleMoneyLists = response.data.data;
                $scope.saleMoneyPages = response.data.pages;
                $scope.saleMoneyNow   = 1;
                $scope.saleMoneyInfo  = true;
                $scope.modalAllMoney  = response.data.totalMoney;
            }
            $.loading.hide();
        },function (error) {
            // console.log(error)
            Message.error('系统错误请联系管理人员');
            $.loading.hide();
        })
    }
    //获取销售数据
    $scope.getSaleMoneyData = function(){
        $.loading.show();
        //销售接口
        $http.get($scope.saleMoneyUrl).then(function(response){
            // console.log('销售',response);
            if(response.data.data.length != 0) {
                $scope.saleMoneyLists = response.data.data;
                $scope.saleMoneyPages = response.data.pages;
                $scope.saleMoneyCount = response.data.count;
                $scope.saleMoneyNow   = response.data.now;
                // $scope.modalAllMoney  = response.data.sales.totalMoney;
                // console.log($scope.saleMoneyNow);
                $scope.saleMoneyInfo = false;
            }else{
                $scope.saleMoneyLists = response.data.data;
                $scope.saleMoneyPages = response.data.pages;
                $scope.saleMoneyNow   = 1;
                $scope.saleMoneyInfo  = true;
                // $scope.modalAllMoney  = response.data.sales.totalMoney;
            }
            $.loading.hide();
        });
    };

    //获取销售额
    $scope.getAllMoney = function (value){
        // $scope.loderAnimateShow();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get("/sell-index/total-price?type=" + $scope.selectSaleMoneyId+'&venueId='+$scope.venueChangeId).success(function (data){
            // console.log('销售额总钱数',data);
            $scope.beforeAllMoney = String(data.data);
            // console.log($scope.beforeAllMoney);
            if($scope.beforeAllMoney.length <= 5){
                $scope.allMoney = String(data.data);
            }
            else{
                $scope.allMoney = String($scope.beforeAllMoney).substring(0,$scope.beforeAllMoney.length-4) + 'W';
            }
            $scope.saleMoneyCount = data.data;
            // $scope.loderAnimateHide();
        })
    };
    $scope.getAllMoney();
    $scope.saleMoneySelectCount = function(){
        $scope.getAllMoney();
    };

    //商品类型筛选
    $scope.shopTypeSelect = function(id){
        // if($scope.shopTypeId != ''){
        //     $scope.changeSaleMoneySort();
        // }
        $scope.shopTypeId = id;
    }

    //销售额分页
    $scope.salesPages = function(urlPages){
        $scope.saleMoneyUrl = urlPages;
        $scope.getSaleMoneyData();
    }


    //销售额列表升降序
    $scope.changeSaleMoneySort = function(attr,sort){
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.saleMoneyUrlInit();
        $scope.getSaleMoneyData();
    }

    //下拉选择销售
    $scope.saleMoneySelect = function(value){
        $scope.selectSaleMoneyId = value;
        $scope.getAllMoney();
        $scope.saleMoneyUrlInit();
        $scope.getSaleMoneyData();
    }
    //初始化调用销售额
    $scope.saleMoneyInit = function(){
        $scope.saleMoneyUrlInit();
        $scope.getSaleMoneyData();
        $scope.getAllMoney();
    }


    //点击销售额
    $scope.saleMoneyModal = function(){
        $scope.sortType = '';
        $scope.shopTypeId = '';
        $('#saleMoneyModal').modal('show');
        $scope.getAllTimeShowModal($scope.selectSaleMoneyId,$("#saleMoneyReservation"));
        // $scope.saleMoneyInit();
        $scope.searchCardSaleMoney();
    }

    //初始化默认选中当天
    $scope.selectPeopleNumId =$scope.filtrateSelect[0].value;

    //点击客流量
    $scope.peopleNum = function(){
        $scope.sortType = '';
        $scope.peopleNumInit();
        $('#peopleNumberModal').modal('show');
        $scope.getAllTimeShowModal($scope.selectPeopleNumId,$("#peopleNumberReservation"));
        $scope.searchCardPeopleNumber();
    };

    //获取客流量人数
    $scope.getPeopleNumSelectCount = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/member-traffics?type='+ $scope.selectPeopleNumId+'&venueId='+$scope.venueChangeId).then(function(response){
            $scope.beforePeopleNumCountAll   = response.data.replace(/\"/g, "");
            if($scope.beforePeopleNumCountAll.length <= 5){
                $scope.peopleNumCountAll = $scope.beforePeopleNumCountAll;
            }
            else{
                $scope.peopleNumCountAll = $scope.beforePeopleNumCountAll.substring(0,$scope.beforePeopleNumCountAll.length-4) + 'W';
            }
        });
    };
    $scope.getPeopleNumSelectCount();

    //select客流量人数
    $scope.peopleNumSelectCount = function(){
        $scope.getPeopleNumSelectCount();
    };

    // //客流量下拉筛选
    // $scope.peopleNumSelect  = function(){
    //     $scope.peopleNumUrl = '/sell-index/member-traffic?type='+ $scope.selectPeopleNumId  +'&'+ $.param($scope.searchPeopleNumData());
    //     $scope.getPeopleNumData();
    // }
    
    //获取客流量列表
    $scope.getPeopleNumData = function(){
        $.loading.show();
        $http.get($scope.peopleNumUrl).then(function(response){
            // console.log('客流量列表数据',response);
            if(response.data.data.length != 0) {
                $scope.peopleNumLists   = response.data.data;
                $scope.peopleNumPages   = response.data.pages;
                $scope.peopleNumCount   = response.data.count;
                $scope.peopleNumNowPage = response.data.now;
                $scope.peopleNumInfo = false;
            }else{
                $scope.peopleNumLists   = response.data.data;
                $scope.peopleNumPages   = response.data.pages;
                $scope.peopleNumCount   = 0;
                $scope.peopleNumNowPage = 1;
                $scope.peopleNumInfo = true;
            }
            $.loading.hide();
        });
    }
    //初始化客流量url
    $scope.peopleNumUrlInit = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:"get",url:'/user/get-adviser?venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.peopleNumAdviserLIstData = data.data
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员")
        })
        $http({method:"get",url:'/member-card/get-type'}).then(function (data) {
            $scope.peopleNumCardListData = data.data.type
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员")
        })
        $http({method:"get",url:'/private-teach/employee-info'}).then(function (data) {
            $scope.peopleNumInfoListData = data.data
        },function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员")
        })
    }
    /**处理搜索数据***/
    $scope.searchPeopleNumberData = function () {
        return {
            sortType       : $scope.sortType  != undefined  ? $scope.sortType : null,
            sortName       : $scope.sort      != undefined  ? $scope.sort :null
        }
    };



    $scope.enterSearchPeopleNumber = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13){
            $scope.searchCardPeopleNumber();
        }
    }
    //客流量筛选清空按钮
    $scope.searchCardPeopleNumberClear = function(){
        $scope.keywordPeopleNumber = '';
        $("#peopleNumberReservation").val('');
        $scope.peopleNumAdviserId = '';
        $scope.peopleNumCoachId = ''
        $scope.peopleNumCardId = '';
        $('#select2-peopleNumAdviserIdSelect2-container').text('选择顾问');
        $scope.searchCardPeopleNumber();
    }

    $scope.searchCardPeopleNumber = function () {
        if($scope.keywordPeopleNumber == undefined){$scope.keywordPeopleNumber = ''};
        var startTime = $("#peopleNumberReservation").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+ ' ' +"00:00:00"}
        var endTime = $("#peopleNumberReservation").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime+ ' '+" 23:59:59"}
        if($scope.keywordPeopleNumber == undefined){
            $scope.keywordPeopleNumber = '';
        }
        if($scope.peopleNumAdviserId == undefined){
            $scope.peopleNumAdviserId = '';
        }

        if($scope.peopleNumCoachId == undefined){
            $scope.peopleNumCoachId = '';
        }
        if($scope.peopleNumCardId == undefined){
            $scope.peopleNumCardId = '';
        }
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $scope.peopleNumUrl = '/sell-index/member-traffic?keywords='+$scope.keywordPeopleNumber+"&startTime="+startTime +"&endTime="+endTime+"&sellId="+$scope.peopleNumAdviserId+"&cardType="+$scope.peopleNumCardId+"&coachId="+$scope.peopleNumCoachId+'&venueId='+$scope.venueChangeId;
        $scope.getPeopleNumData();
    };
    //初始化客流量
    $scope.peopleNumInit = function(){
        $scope.peopleNumUrlInit();
        $scope.searchCardPeopleNumber();
    };

    //客流量分页
    $scope.entryPages = function(urlPages){
        $scope.peopleNumUrl = urlPages;
        $scope.peopleNumUrlInit();
        $scope.getPeopleNumData();
    };

    //客流量升降序
    $scope.changePeopleNumSort = function(attr,sort){
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.peopleNumUrl = $scope.peopleNumUrl +'&'+  $.param($scope.searchPeopleNumberData());
        $scope.getPeopleNumData();
    }
    /*----------------------  销售、客流量end-----------------------------*/


    // 设置默认值
    $scope.rankSale = $scope.filtrateSelect[0].value;
    // 销售排行榜
    $scope.rankSaleFun = function (){
        $scope.loderAnimateShow3();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get("/sell-index/sales-staff?type=" + $scope.rankSale+'&venueId='+$scope.venueChangeId).then(function (data){
            // console.log('销售排行榜数据',data);
            $scope.sellListsRanking = data.data.data;
            // console.log('销售排行榜liebiao数据',$scope.sellListsRanking);
            $scope.loderAnimateHide3();
        })
    };
    $scope.rankSaleFun();


// 基于准备好的dom，初始化echarts实例
    var colors = ['#6699FF', '#FF6700'];

    /*--------销售额统计图---------*/
    //当前月
    $scope.currentDate = function(){
        var oDate = new Date();
        var oYear = oDate.getFullYear();
        var oMonth = oDate.getMonth()+1;
        oMonth = oMonth>=10? oMonth:'0'+oMonth;
        nowDate =oYear +'-'+ oMonth
        return  nowDate;
    }
    //上一月
    $scope.preDate = function(){
        var oDate = new Date();
        var oYear = oDate.getFullYear();
        var oMonth = oDate.getMonth();
        oMonth = oMonth>=10? oMonth:'0'+oMonth;
        nowDate =oYear +'-'+oMonth
        return  nowDate;
    }

    //本周周一和周日
    $scope.getCurrentWeek = function(){
        var oDate = new Date();
        var day = oDate.getDay();
        var nowTime = oDate.getTime();
        var oneDayLong = 24*60*60*1000 ;
        var MondayTime = nowTime - (day-1)*oneDayLong  ;
        $scope.currentWeekMonday = $scope.getMyDate(MondayTime);
        var SundayTime =  nowTime + (7-day)*oneDayLong ;
        $scope.currentWeekSunday = $scope.getMyDate(SundayTime);
        $scope.loderAnimateShow();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/entry-num-week?month=week&start='+ $scope.currentWeekMonday +'&end='+$scope.currentWeekSunday+'&venueId='+$scope.venueChangeId).then(function(response){
            // console.log('当周当周当周当周',response);
            var currentWeekMoney = response.data;
            // $scope.allMoney1212 = 0;
            for(var item in currentWeekMoney){
                // console.log('item',currentWeekMoney[item]);
                $scope.currentSellMoneyCount.push(currentWeekMoney[item]);
                // $scope.allMoney1212 +=currentWeekMoney[item];
                $scope.loderAnimateHide();
             }
            saleStatistics();
        });
    }

    //上周周一和周日
    $scope.getPreWeek = function(){
        var oDate = new Date();
        var day = oDate.getDay();
        var nowTime = oDate.getTime();
        var oneDayLong = 24*60*60*1000 ;
        var MondayTime = nowTime - 7*oneDayLong - (day-1)*oneDayLong  ;
        $scope.preWeekMonday = $scope.getMyDate(MondayTime);
        // console.log('上周一',$scope.preWeekMonday );
        var SundayTime =  nowTime -7*oneDayLong + (7-day)*oneDayLong ;
        $scope.preWeekSunday = $scope.getMyDate(SundayTime);
        // console.log('上周日',$scope.preWeekSunday);
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/entry-num-week?month=week&start='+ $scope.preWeekMonday +'&end='+$scope.preWeekSunday+'&venueId='+$scope.venueChangeId).then(function(response){
            // console.log('上周',response);
            var preWeekMoney = response.data;
            for(var item in preWeekMoney){
                $scope.preSellMoneyCount.push(preWeekMoney[item]);
            }
            saleStatistics();
        });
    };

    $scope.getCurrentDayMoney = function(){
        $scope.loderAnimateShow();
        $scope.getDateTime();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/entry-num-day?month=day&date='+$scope.getDateTime()+'&venueId='+$scope.venueChangeId).then(function(response){
            // console.log('当日',response);
            var day = response.data;
            // $scope.allMoney1212 = 0;
            for(var item in day){
                $scope.currentSellMoneyCount.push(day[item]);
                // $scope.allMoney1212 +=day[item];
                $scope.loderAnimateHide();
            }
            saleStatistics();
        });
    };
    //上个月的销售额
    $scope.preSellMoneyCount = [];
    $scope.getPreMonthSellMoneyData = function(){
        $scope.loderAnimateShow();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/entry-num?month=month&date='+ $scope.preDate()+'&venueId='+$scope.venueChangeId).then(function(response){
            // console.log('上一月销售额返回数据',response);
            var preSellMoney = response.data;
            for(var item in preSellMoney){
                $scope.preSellMoneyCount.push(preSellMoney[item]);
            }
            $scope.loderAnimateHide();
            saleStatistics();
        });
    }
    $scope.getCurrentSellMoneyData = function(){
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/entry-num?month=month&date='+ $scope.currentDate()+'&venueId='+$scope.venueChangeId).then(function(response){
            // console.log('当前月销售额统计返回数据',response);
            var data123 = response.data;
            // $scope.allMoney1212 = 0;
            for(var item in data123){
                // console.log('item',data123[item]);
                $scope.currentSellMoneyCount.push(data123[item]);
                // $scope.allMoney1212 +=data123[item];
            }
            saleStatistics();
        });
    }

    //销售额统计图筛选
    $scope.marketChartId = $scope.filtrateSelect[0].value;
    $scope.marketChartSelect = function(value){
        $scope.currentColor ='';
        $scope.preColor     ='';
        $scope.preMoneyArr  = [];
        $scope.currentMoneyArr =[];
        $scope.monthListsArr =[];
        $scope.marketChartFlag= [];
        $scope.preSellMoneyCount= [];
        $scope.currentSellMoneyCount = [];
        //获取当日的数据
        if($scope.marketChartId == 'd'){
            $scope.currentMoneyArr   = ['00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'];
            $scope.marketChartFlag = ['当日'];
            $scope.currentColor ='#6699FF';
            $scope.getCurrentDayMoney();

        }
        //获取本周的销售额数据
        if($scope.marketChartId == 'w'){
            $scope.marketChartFlag = ['当周','上一周'];
            $scope.currentMoneyArr  = ['星期一','星期二','星期三','星期四','星期五','星期六','星期日'];
            $scope.preMoneyArr  = ['星期一','星期二','星期三','星期四','星期五','星期六','星期日'];
            $scope.currentColor ='#6699FF';
            $scope.preColor     = '#FF6700';
            $scope.getCurrentWeek();
            $scope.getPreWeek();
        }
        //获取本月的销售额数据
        if($scope.marketChartId == 'm'){
            $scope.marketChartFlag = ['当月','上一月'];
            $scope.currentMoneyArr = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
            $scope.preMoneyArr = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
            $scope.currentColor ='#6699FF';
            $scope.preColor     = '#FF6700';
            $scope.getCurrentSellMoneyData();
            $scope.getPreMonthSellMoneyData();
        }
    }
    $scope.marketChartSelect();


    //当月的销售额
    // $scope.sellMoneyCount.series[0].data


// 销售额统计折线统计图
    var saleStatistics = function () {
        //销售统计图返回数据
        // console.log($scope.monthListsArr)
        // console.log($scope.preSellMoneyCount)
        var myChart = echarts.init(document.getElementById('sellMain'));
        option = {
            title: {
                // text: '销售额统计'
            },
            color: colors,
            toolbox: {
                show: true,
                feature: {
                    saveAsImage: {
                        show:true,
                        excludeComponents :['toolbox'],
                        pixelRatio: 2
                    }
                }
            },
            tooltip: {
                trigger: 'none',
                axisPointer: {
                    type: 'cross'
                }
            },
            legend: {
                data:$scope.marketChartFlag
            },
            grid: {
                top: 70,
                bottom: 50
            },
            xAxis: [
                {
                    type: 'category',
                    axisTick: {
                        alignWithLabel: true
                    },
                    axisLine: {
                        onZero: false,
                        lineStyle: {
                            color: $scope.preColor
                        }
                    },
                    axisPointer: {
                        label: {
                            formatter: function (params) {
                                return $scope.marketChartFlag[0] + params.value+'日'
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "元";
                            }
                        }
                    },
                    data: $scope.currentMoneyArr
                },
                {
                    type: 'category',
                    axisTick: {
                        alignWithLabel: true
                    },
                    axisLine: {
                        onZero: false,
                        lineStyle: {
                            color: $scope.currentColor
                        }
                    },
                    axisPointer: {
                        label: {
                            formatter: function (params) {
                                return $scope.marketChartFlag[1] + params.value +'日'
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data  : '') + "元";
                            }
                        }
                    },
                    data: $scope.preMoneyArr
                }
            ],
            yAxis: [
                {
                    name:'金额:元',
                    type: 'value',
                    // splitNumber:10,
                }
            ],
            series: [
                {
                    name:$scope.marketChartFlag[1] ,
                    type:'line',
                    xAxisIndex: 1,
                    smooth: true,
                    data: $scope.preSellMoneyCount
                },
                {
                    name:$scope.marketChartFlag[0],
                    type:'line',
                    smooth: true,
                    data: $scope.currentSellMoneyCount
                }
            ]
        };

// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    };
    $scope.mySelectChange3 =  $scope.filtrateSelect[0].value;
    $scope.mySelectChange3q = function () {
        $scope.employeePerformance();
    };
    $scope.employeePerformance = function () {
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http({method:'get',url:'/sell-index/employee-performance?type='+$scope.mySelectChange3+'&venueId='+$scope.venueChangeId}).then(function (data) {
            $scope.employeePerformanceData = data.data.data;
            // console.log($scope.employeePerformanceData);
            $scope.employeeMoneyList   = [];
            $scope.employeeuserList   = [];
            // 价钱
            for(var i = 0;i < $scope.employeePerformanceData.length;i++){
                $scope.employeeuserList[i]  = $scope.employeePerformanceData[i].position;
                $scope.employeeMoneyList[i] = {
                    value:$scope.employeePerformanceData[i].money,
                    name:$scope.employeePerformanceData[i].position
                };
            }
            // console.log($scope.employeeMoneyList);
            chart3();
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})

    }

    // 员工业绩饼状统计图
    var chart3 = function () {
        var myChart = echarts.init(document.getElementById('staffMian'));
        // console.log('csad2331131o:',$scope.employeeMoneyList);
        option = {
            color:['#00cc00', '#69e','#9966FF','#FFCC00','#00cc00', '#DC143C','#4B0082','#000080','#00FF7F', '#006400'],

            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            toolbox: {
                show: true,
                feature: {
                    saveAsImage: {
                        show:true,
                        excludeComponents :['toolbox'],
                        pixelRatio: 2
                    }
                }
            },
            legend: {
                x : 'left',
                y : 'top',
                data:  $scope.employeeuserList
            },
            series: [
                {
                    name:'员工业绩',
                    type:'pie',
                    radius: ['50%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '16',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data: $scope.employeeMoneyList

                }
            ]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    $scope.employeePerformance();

    // 获取今日日期
    $scope.getToday = function (){
        var today = new Date();
        var y = today.getFullYear();
        var m = today.getMonth() + 1;//获取当前月份的日期
        var d = today.getDate();
        $scope.dateInput = y+"-"+m+"-"+d;
    };
    $scope.getToday();

    // 设置默认上课日期
    $scope.classDate = $scope.filtrateSelect[0].value;
    // 获取会员上课统计
    $scope.getMemberClass = function (){
        $scope.loderAnimateShow2();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get("/operation-statistics/about-class-count?date=" + $scope.classDate +'&venueId='+$scope.venueChangeId).success(function (data){
            $scope.classChartNameList = [];
            $scope.classChartNumList  = [];
            if(data.length == 0){
                $scope.classChartNameList = [];
                $scope.classChartNumList  = [];
            }
            else {
                var classList = data;
                for(var item in classList){
                    var itemList = classList[item];
                    for(var list in itemList){
                        $scope.classChartNameList.push(list);
                        $scope.classChartNumList.push({name:list,value:parseInt(itemList[list])});
                    }
                }
                // 应用方法
                chartClass();
            }
            $scope.loderAnimateHide2();
        })
    };
    $scope.getMemberClass();

    // 会员上课饼状统计图
    var chartClass = function (){
        var myChart = echarts.init(document.getElementById('classMain'));
        option = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x : 'left',
                y : 'top',
                data: $scope.classChartNameList
            },
            toolbox: {
                show: true,
                feature: {
                    saveAsImage: {
                        show:true,
                        excludeComponents :['toolbox'],
                        pixelRatio: 2
                    }
                }
            },
            // toolbox: {
            //
            //     show: true,
            //
            //     feature: {
            //
            //         saveAsImage: {
            //
            //             show:true,
            //
            //             excludeComponents :['toolbox'],
            //
            //             pixelRatio: 2
            //
            //         }
            //
            //     }
            //
            // },
            series : [
                {
                    name: '预约统计',
                    type: 'pie',
                    radius: ['50%', '70%'],
                    avoidLabelOverlap: false,
                    data: $scope.classChartNumList,
                    itemStyle: {
                        normal: {
                            label: {
                                show: true,
                                formatter: '{b} : {c} \n ({d}%)'
                            },
                            labelLine: {
                                show: true
                            }
                        },
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ],
            color: ['#60C9F9','#9933FF','#51D76A','#FF6600','#FECB2F']
        }
        myChart.setOption(option);
    };





    //默认是当日
    $scope.sourceDitchId = $scope.filtrateSelect[0].value;
    //筛选来源渠道统计
    $scope.getSourceDitch = function(){
        $scope.sourceDitchNum =[];
        $scope.sourceDitchName =[];
        $scope.loderAnimateShow4();
        $scope.venueChangeId = $scope.venueChangeId != undefined ? $scope.venueChangeId : '';
        $http.get('/sell-index/source-config?type='+$scope.sourceDitchId+'&venueId='+$scope.venueChangeId).then(function(response){
            // console.log('来源统计',response);
            var source = response.data.data;
            for(var i=0;i<source.length;i++){
                $scope.sourceDitchNum.push(source[i].totalSum);
                $scope.sourceDitchName.push(source[i].value);
            }
            $scope.loderAnimateHide4();
            sourceStatistics();
            // $.loading.hide();
        });
    };
    $scope.getSourceDitch();

    //来源渠道统计图
    var sourceStatistics  = function(){
        // console.log('sourceDitchNum',$scope.sourceDitchNum)
        // console.log('sourceDitchName',$scope.sourceDitchName)
        var myChart = echarts.init(document.getElementById('sourceDitchMain'));

        option = {
            color: ['#00e09e'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'line'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            toolbox: {
                show: true,
                feature: {
                    saveAsImage: {
                        show:true,
                        excludeComponents :['toolbox'],
                        pixelRatio: 2
                    }
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : $scope.sourceDitchName,
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    name :'单位:人',
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'人数',
                    type:'bar',
                    barWidth: '20%',
                    data:$scope.sourceDitchNum
                }
            ]
        };
        myChart.setOption(option);
    };
});
