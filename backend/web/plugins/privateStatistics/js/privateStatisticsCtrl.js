/**
 * Created by DELL on 2017/8/10.
 * 私教统计页面js
 */

/**
 财务管理模块-私教统计页面
 内容：统计上课量、会员上课量、私教上课量、卖课量、私教卖课详情、生日会员的数据
 * @author zhujunzhe@itsports.club
 * @create 2017.11.30
 */

$(function(){
    $('#homePageVenueSelect123').select2();
    //私教统计首页日历插件
    $('#privateStatisticsDate').daterangepicker(null, function(start, end, label) {
    });

    //上课量日历插件
    $('#attendClassDate').daterangepicker(null, function(start, end, label) {
    });

    //私教上课量日历插件
    $('#pTAttendClassDate').daterangepicker(null, function(start, end, label) {
    });

    //会员上课量量日历插件
    $('#memberAttendClassDate').daterangepicker(null, function(start, end, label) {
    });

    //卖课量日历插件
    $('#sellClassDate').daterangepicker(null, function(start, end, label) {
    });

    //私教卖课量日历插件
    $('#pTSellClassDate').daterangepicker(null, function(start, end, label) {
    });
    //生日会员日历插件birthdayDate
    $('#birthdayDate').daterangepicker(null, function(start, end, label) {
    });
});
// $(document).ready(function(){
//     //静态select渲染
//     $('#test1').select2({
//         placeholder: "请选择",
//         language: "zh-CN"
//     });
// });

angular.module('App').controller('privateStatisticsCtrl',function($scope,$http,$timeout){
    $scope.homeSelectDate132 = 'd';
    $scope.classNumSelected = '1';
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

    //初始化获取当月的第一天和最后一天
    $scope.getMonthOneAndMonthLast = function(){
        var date = new Date();
        $scope.homeStartDate =$scope.getMyDate(date.setDate(1));

        var currentMonth=date.getMonth();
        var nextMonth=++currentMonth;
        var nextMonthFirstDay=new Date(date.getFullYear(),nextMonth,1);
        var oneDay=1000*60*60*24;
        $scope.homeEndDate = $scope.getMyDate(nextMonthFirstDay-oneDay);
        $('#privateStatisticsDate').val($scope.homeStartDate+' - '+ $scope.homeEndDate);
    }
    //home搜索参数
    $scope.homePageSearchData = function(){
        return{
            venueId        :$scope.homePageVenue != undefined && $scope.homePageVenue != ''?$scope.homePageVenue : null,
            dateStart      :$scope.homeStartDate != undefined && $scope.homeStartDate       != '' ? $scope.homeStartDate: null,
            dateEnd        :$scope.homeEndDate   != undefined && $scope.homeEndDate         != '' ? $scope.homeEndDate: null,
        }
    }
    //home生日搜索参数
    $scope.homePageSearchBirthdayData = function(){
        return{
            venueId        :$scope.homePageVenue != undefined && $scope.homePageVenue != ''?$scope.homePageVenue : null,
            startTime      :$scope.homeStartDate != undefined && $scope.homeStartDate       != '' ? $scope.homeStartDate: null,
            endTime        :$scope.homeEndDate   != undefined && $scope.homeEndDate         != '' ? $scope.homeEndDate: null,
        }
    }

    $scope.getMonthOneAndMonthLast();
    //首页上课量统计
    $scope.homeAttendClassNum = function(){
        $http.get('/private-statistics/private-attend-class?'+ $.param($scope.homePageSearchData())).then(function(response){
            $scope.AttendClassNum = response.data.sum.totalNum;
        });
    }

    //首页卖课量统计
    $scope.homeSellClassNum = function(){
        $http.get('/private-statistics/sell-private-class-all?'+ $.param($scope.homePageSearchData())).then(function(response){
            $scope.SellClassNum = response.data.sum.totalNum;
        });
    }

    //生日会员统计
    $scope.homeBirthdayNum = function(){
        $http.get('/private-statistics/birthday-member?'+ $.param($scope.homePageSearchBirthdayData())).then(function(response){
            $scope.AllBirthdayNum = response.data.totalNum;
            $.loading.hide();
        });
    }

    //开始统计图局部加载动画
    $scope.classNumLoadingShow = function(){
        $('.loader-animateOne').show();
    }

    //取消统计图局部加载动画
    $scope.classNumLoadingHide = function(){
        $('.loader-animateOne').hide();
    }

    //首页清空筛选按钮
    $scope.homeSearchClear = function(){
        $('#privateStatisticsDate').val('');
        $scope.homeStartDate = '';
        $scope.homeEndDate   = '';
        $scope.homePageVenue = '';
        $('#select2-homePageVenueSelect123-container').text('请选择场馆');
        $scope.homeSearchSubmit();
    }


    //首页确定筛选按钮
    $scope.homeSearchSubmit = function(){
        $.loading.show();
        $scope.classNumLoadingShow();
        var homeDate = $('#privateStatisticsDate').val();
        var startTime = homeDate.substr(0, 10);
        var endTime = homeDate.substr(-10, 10);
        $scope.homeStartDate = startTime;
        $scope.homeEndDate = endTime;
        $scope.homeAttendClassNum();
        $scope.homeSellClassNum();
        $scope.homeBirthdayNum();
        // 初始化调用统计图
        $scope.getHomeStatistics();
    }

    // 销售额统计折线统计图
    var classNumStatistics = function () {
        //销售统计图返回数据
        var myChart = echarts.init(document.getElementById('classNum'));

        var colors = ['#ff9900'];
        option = {
            color: colors,

            tooltip: {
                trigger: 'none',
                axisPointer: {
                    type: 'cross'
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
            legend: {
                data:$scope.classNumArr
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
                            color: colors[0]
                        }
                    },
                    axisPointer: {
                        label: {
                            formatter: function (params) {
                                return $scope.classNumSelectedName123 + params.value
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '');
                            }
                        }
                    },
                    data: $scope.abscissaArr
                }
            ],
            yAxis: [
                {
                    // name:'单位:人',
                    type: 'value'
                }
            ],
            series: [
                {
                    name:$scope.classNumSelectedName123,
                    type:'line',
                    smooth: true,
                    data: $scope.classCount
                }
            ]
        };

// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    };


    //点击上课量图标
    $scope.attendClassClick = function(){
        $scope.attendClassKeyword   = '';
        $scope.attendClassVenue     = '';
        $scope.attendClassCoach     = '';
        $scope.attendClassType      = '';
         $scope.attendClassVenue = $scope.homePageVenue;
        $scope.getAllPrivateEducation();
        var date = $('#privateStatisticsDate').val();
        $('#attendClassDate').val(date);
        $scope.classScreening();
        $('#attendClassModal').modal('show');
    }
    //私教列表中点击私教
    $scope.pTMessageClick = function(id){
        $scope.onePtCoachId = id;
        $scope.pTAttendClassType = '';
        $scope.pTCourseType      = '';
        $scope.getPtMessage();
        var privateDate = $('#attendClassDate').val();
        $('#pTAttendClassDate').val(privateDate);
        $scope.getOnePtDate();
        $scope.onePtSubmit();
        $('#attendClassModal').modal('hide');
        $('#pTAttendClassModal').modal('show');
    }

    //返回上一个模态框
    $scope.backAttendClassModal = function(){
        $('#attendClassModal').modal('show');
        $('#pTAttendClassModal').modal('hide');
    }

    //跳转到会员上课量模态框
    $scope.memberMessageClick = function(id){
        $scope.memberPtCourseType = '';
        $scope.PtMemberId = id;
        $scope.getMemberMessage();
        var memberDate = $('#pTAttendClassDate').val();
        $('#memberAttendClassDate').val(memberDate);
        $scope.MemberSubmit();
        $('#pTAttendClassModal').modal('hide');
        $('#memberAttendClassModal').modal('show');
    }
    
    //返回私教模态框
    $scope.backPTClassModal = function(){
        $('#pTAttendClassModal').modal('show');
        $('#memberAttendClassModal').modal('hide');
    }

    //点击卖课量图标
    $scope.sellClassClick = function(){
        $scope.sellClassKeywords = '';
        $scope.sellClassVenue     = '';
        $scope.sellClassCoach     = '';
        $scope.sellClassVenue      = $scope.homePageVenue;
        var date = $('#privateStatisticsDate').val();
        $('#sellClassDate').val(date);
        $scope.getAllPrivateEducation();
        $scope.allSellClassSubmit();
        $('#sellClassModal').modal('show');
    }

    //点击卖课量教练
    $scope.pTSellMessageClick = function(id){
        $scope.onePtSellClassType = '';
        $scope.DealStatus      = '';
        $scope.onePtSellClassCoachId = id;
        var SellDate = $('#sellClassDate').val();
        $('#pTSellClassDate').val(SellDate);
        $scope.onePtSellClassSubmit();
        $scope.getOnePtSellCoachMessage();
        $('#sellClassModal').modal('hide');
        $('#pTSellClassModal').modal('show');
    }

    //点击返回卖课量统计
    $scope.backSellPTClassModal = function(){
        $('#sellClassModal').modal('show');
        $('#pTSellClassModal').modal('hide');
    }
    //上课量教练
    $("#attendClassModal").on("shown.bs.modal", function(){
        $("#AttendClassSelect123").select2({
            language: "zh-CN",
            dropdownParent:$("#attendClassModal")
        });
    });

    //上课量场馆
    $("#attendClassModal").on("shown.bs.modal", function(){
        $("#attendClassVenueSelect123").select2({
            language: "zh-CN",
            dropdownParent:$("#attendClassModal")
        });
    });

    //卖课量场馆
    $("#sellClassModal").on("shown.bs.modal", function(){
        $("#sellClassVenueSelect123").select2({
            language: "zh-CN",
            dropdownParent:$("#sellClassModal")
        });
    });

    //卖课量教练
    $("#sellClassModal").on("shown.bs.modal", function(){
        $("#sellClassSelect123").select2({
            language: "zh-CN",
            dropdownParent:$("#sellClassModal")
        });
    });

    //生日教练
    $("#birthdayModal").on("shown.bs.modal", function(){
        $("#birthdayCoachSelect").select2({
            language: "zh-CN",
            dropdownParent:$("#birthdayModal")
        });
    });
    //会员生日图标点击
    $scope.birthdayClick = function(){
        $scope.memberBirKeywords   = '';
        $scope.birthdayCoach = '';
        $scope.ptClassStatus  = '';
        $scope.birthdayClass123 = '';
        var date = $('#privateStatisticsDate').val();
        $('#birthdayDate').val(date);
        $scope.getAllPrivateEducation();
        $scope.memberBirSubmit();
        $('#birthdayModal').modal('show');

    }

    //获取所有场馆
    $scope.getAllVenue = function(){
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.allVenueLists = response.data;
        });
    }
    $scope.getAllVenue();

    //获取所有私教
    $scope.getAllPrivateEducation = function(){
        $http.get('/private-teach/employee-info').then(function(response){
            $scope.allPrivateEducation = response.data;
        });
    }

    /*--------------统计图Start----------------*/
    $scope.dayStatisticsArry = ['00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'];
    $scope.weekStatisticsArry = ['星期一','星期二','星期三','星期四','星期五','星期六','星期日'];
    $scope.monthStatisticsArry = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];

    $scope.homeSelectDate132 = 'd';
    $scope.classNumSelected = '1';

    $scope.abscissaArr      = $scope.dayStatisticsArry;
    $scope.getHomeStatistics = function(){
        $scope.classNumArr = [];
        if($scope.classNumSelected =='1'){
            $scope.classNumSelectedName123 = '上课量';
            $scope.classNumArr.push($scope.classNumSelectedName123);
        }
        if($scope.classNumSelected =='2'){
            $scope.classNumSelectedName123 = '卖课量';
            $scope.classNumArr.push($scope.classNumSelectedName123);
        }
        $scope.classCount = [];
        if($scope.homePageVenue == undefined || $scope.homePageVenue == ''){
            $scope.homePageVenue = '';
        }
        $http.get('/private-statistics/attend-class?searchType='+ $scope.classNumSelected + '&timeType='+$scope.homeSelectDate132+'&venueId='+ $scope.homePageVenue).then(function(response){
            var Data123 = response.data;
            for(var item in Data123){
                $scope.classCount.push(Data123[item]);
            }
            classNumStatistics();
            $scope.classNumLoadingHide();
        });

    }

    //课量类型选择
    $scope.selectClassNum = function(){
        $scope.getHomeStatistics();
    }
    //time类型选择
    $scope.selectedTimeType = function(value){
        if(value == 'd'){
            $scope.abscissaArr      = $scope.dayStatisticsArry;
        }
        if(value == 'w'){
            $scope.abscissaArr      = $scope.weekStatisticsArry;
        }
        if(value == 'm'){
            $scope.abscissaArr      = $scope.monthStatisticsArry;
        }
        $scope.getHomeStatistics();
    }




    /*--------------统计图End----------------*/


    /*-------------上课量Start-------------*/
    //升降序
    $scope.switchSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };

    //获取所有私课教练的上课量
    $scope.attendClassSearchData = function(){
        return {
            venueId        : $scope.attendClassVenue    != undefined && $scope.attendClassVenue           != '' ? $scope.attendClassVenue :null,
            dateStart      :$scope.attendClassStartDate != undefined && $scope.attendClassStartDate       != '' ? $scope.attendClassStartDate: null,
            dateEnd        :$scope.attendClassEndDate   != undefined && $scope.attendClassEndDate         != '' ? $scope.attendClassEndDate: null,
            coachId        :$scope.attendClassCoach       != undefined && $scope.attendClassCoach             !=''  ? $scope.attendClassCoach:null,
            classType      :$scope.attendClassType      != undefined && $scope.attendClassType            != '' ? $scope.attendClassType:null,
            keywords       : $scope.attendClassKeyword  != undefined && $scope.attendClassKeyword         != '' ? $scope.attendClassKeyword : null,
            sortType       : $scope.sortType           != undefined && $scope.sortType                   != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort                != undefined && $scope.sort                       != ''  ? $scope.sort       : null
        }
    }
    //关键字搜索
    $scope.attendClassKeywordsData = function(){
        return {
            dateStart      :$scope.attendClassStartDate != undefined && $scope.attendClassStartDate       != '' ? $scope.attendClassStartDate: null,
            dateEnd        :$scope.attendClassEndDate   != undefined && $scope.attendClassEndDate         != '' ? $scope.attendClassEndDate: null,
            keywords       : $scope.attendClassKeyword  != undefined && $scope.attendClassKeyword         != '' ? $scope.attendClassKeyword : null,
            sortType       : $scope.sortType           != undefined && $scope.sortType                   != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort                != undefined && $scope.sort                       != ''  ? $scope.sort       : null
        }
    }

    $scope.getAllPrivateAttendClass =  function(){
        var attendClassDate = $('#attendClassDate').val();
        var startTime = attendClassDate.substr(0, 10);
        var endTime = attendClassDate.substr(-10, 10);
        $scope.attendClassStartDate   = startTime;
        $scope.attendClassEndDate     = endTime;
        // $scope.homeStartDate = '2017-06-01';
        $.loading.show();
        $http.get($scope.attendClassUrlInit).then(function(response){
        // $http.get('/private-statistics/private-attend-class?dateStart='+ $scope.homeStartDate+'&dateEnd='+$scope.homeEndDate).then(function(response){
            if(response.data.data.length != 0){
                $scope.attendClassLists    = response.data.data;
                $scope.attendClassNowPage  = response.data.now;
                $scope.attendClassFlag     = false;
                $scope.attendClassPages    = response.data.pages;
                $scope.attendClassAllNums   = response.data.sum.totalNum;
                $scope.attendClassAllMoney   = response.data.sum.totalMoney;
            }else{
                $scope.attendClassLists    = response.data.data;
                $scope.attendClassNowPage  = 1;
                $scope.attendClassFlag     = true;
                $scope.attendClassPages    = response.data.pages;
                $scope.attendClassAllNums   = response.data.sum.totalNum;
                $scope.attendClassAllMoney   = response.data.sum.totalMoney;
            }
            $.loading.hide();
        });
    }
    //清空上课量筛选
    $scope.classScreeningClear = function(){
        $scope.attendClassVenue     = '';
        $scope.attendClassStartDate = '';
        $scope.attendClassKeyword   =  '';
        $scope.attendClassCoach     =  '';
        $scope.attendClassType       = '';
        $('#attendClassDate').val('');
        $('#select2-attendClassVenueSelect123-container').text('请选择场馆');
        $('#select2-AttendClassSelect123-container').text('请选择私教');
        $scope.classScreening();
    }


    //上课量筛选
    $scope.classScreening = function(){
        var attendClassDate = $('#attendClassDate').val();
        var startTime = attendClassDate.substr(0, 10);
        var endTime = attendClassDate.substr(-10, 10);
        $scope.attendClassStartDate   = startTime;
        $scope.attendClassEndDate     = endTime;
        $scope.attendClassUrlInit = '/private-statistics/private-attend-class?'+ $.param($scope.attendClassSearchData());
        $scope.getAllPrivateAttendClass();
    };
    //关键字搜索
    $scope.searchAttendClass = function(){
        var attendClassDate = $('#attendClassDate').val();
        var startTime = attendClassDate.substr(0, 10);
        var endTime = attendClassDate.substr(-10, 10);
        $scope.attendClassStartDate   = startTime;
        $scope.attendClassEndDate     = endTime;
        $scope.attendClassUrlInit = '/private-statistics/private-attend-class?'+ $.param($scope.attendClassKeywordsData());
        $scope.getAllPrivateAttendClass();
    }

    //分页
    $scope.allPrivatePages = function(urlPages){
        $scope.attendClassUrlInit = urlPages;
        $scope.getAllPrivateAttendClass();
    }


    /*-------------上课量End-------------*/

    /*---------私教上课量Start-----------*/
    $scope.OnePrivateAttendClassSearchData = function(){
        return {
            coachId        :$scope.onePtCoachId != undefined && $scope.onePtCoachId    != '' ? $scope.onePtCoachId : null,
            dateStart      :$scope.OnePtStartDate != undefined && $scope.OnePtStartDate    != '' ? $scope.OnePtStartDate: null,
            dateEnd        :$scope.OnePtEndDate   != undefined && $scope.OnePtEndDate      != '' ? $scope.OnePtEndDate: null,
            classType      :$scope.pTAttendClassType      != undefined && $scope.pTAttendClassType            != '' ? $scope.pTAttendClassType:null,
            // keywords       : $scope.pTAttendClassKeyword  != undefined && $scope.pTAttendClassKeyword         != '' ? $scope.pTAttendClassKeyword : null,
            type            :$scope.pTCourseType      != undefined && $scope.pTCourseType            != '' ? $scope.pTCourseType:null,
            sortType       : $scope.sortType           != undefined && $scope.sortType                   != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort                != undefined && $scope.sort                       != ''  ? $scope.sort       : null
        }
    }

    //获取私教上课日期
    $scope.getOnePtDate = function(){
        var OnePtDate = $('#pTAttendClassDate').val();
        var startTime = OnePtDate.substr(0, 10);
        var endTime = OnePtDate.substr(-10, 10);
        $scope.OnePtStartDate = startTime;
        $scope.OnePtEndDate   = endTime;
    }

    //获取私教的上课量
    $scope.getOnePrivateAttendClass = function(){
        $.loading.show();
        $http.get($scope.onePtUrlInit).then(function(response){
            if(response.data.data.length != 0){
                $scope.onePtLists      = response.data.data;
                $scope.onePtNowPages   = response.data.now;
                $scope.onePtFlag       = false;
                $scope.onePtPages      = response.data.pages;
                $scope.onePtAllCount  = response.data.sum.totalNum;
                $scope.onePtAllMoney  = response.data.sum.totalMoney;
            }else{
                $scope.onePtLists     = response.data.data;
                $scope.onePtNowPages  = 1;
                $scope.onePtFlag      = true;
                $scope.onePtPages     = response.data.pages;
                $scope.onePtAllCount  = response.data.sum.totalNum;
                $scope.onePtAllMoney  = response.data.sum.totalMoney;
            }
            $.loading.hide();
        });
    }

    //获取私教的信息
    $scope.getPtMessage = function(){
        $http.get('/private-statistics/private-coach-info?coachId='+ $scope.onePtCoachId).then(function(response){
            $scope.onePtCoachMessage = response.data;
        });
    }

    //清空私教上课量筛选
    $scope.onePtSubmitClear = function(){
        $('#pTAttendClassDate').val('');
        $scope.OnePtStartDate = '';
        $scope.OnePtEndDate   = '';
        $scope.pTAttendClassType  = '';
        $scope.pTCourseType   = '';
        $scope.onePtSubmit();
    }


    //筛选获取私教的上课量
    $scope.onePtSubmit = function(){
        $scope.getOnePtDate();
        $scope.onePtUrlInit = '/private-statistics/one-private-attend-class?'+$.param($scope.OnePrivateAttendClassSearchData());
        $scope.getOnePrivateAttendClass();
    }

    //私教上课量分页
    $scope.onePrivatePages = function(urlPages){
        $scope.onePtUrlInit = urlPages;
        $scope.getOnePrivateAttendClass();
    }
    /*---------私教上课量End-----------*/

    /*---------------会员上下课Start----------------*/
    //member的搜索参数
    $scope.memberAttendClassSearchData = function(){
        return {
            coachId        :$scope.onePtCoachId       != undefined && $scope.onePtCoachId      != '' ? $scope.onePtCoachId : null,
            memberId        :$scope.PtMemberId        != undefined && $scope.PtMemberId         != '' ? $scope.PtMemberId : null,
            dateStart      :$scope.memberStartDate     != undefined && $scope.memberStartDate    != '' ? $scope.memberStartDate: null,
            dateEnd        :$scope.memberEndDate       != undefined && $scope.memberEndDate       != '' ? $scope.memberEndDate: null,
            type            :$scope.memberPtCourseType       != undefined && $scope.memberPtCourseType       != '' ? $scope.memberPtCourseType:null,
            sortType       : $scope.sortType           != undefined && $scope.sortType            != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort               != undefined && $scope.sort                 != ''  ? $scope.sort       : null
        }
    }

    //获取会员的信息
    $scope.getMemberMessage = function(){
        $http.get('/private-statistics/member-info?memberId='+ $scope.PtMemberId).then(function(response){
            $scope.pTMemberMessage = response.data;
        });
    }
    //获取会员日历时间
    $scope.getMemberDate = function(){
        var OnePtDate = $('#memberAttendClassDate').val();
        var startTime = OnePtDate.substr(0, 10);
        var endTime = OnePtDate.substr(-10, 10);
        $scope.memberStartDate = startTime;
        $scope.memberEndDate   = endTime;
    }

    //获取会员上课量列表
    $scope.getMemberAttendClass = function(){
        $http.get($scope.memberAttendClassUrlInit).then(function(response){
            if(response.data.data.length != 0){
                $scope.memberAttendClassLists = response.data.data;
                $scope.memberAttendClassNow   = response.data.now;
                $scope.memberAttendClassCount = response.data.totalNum;
                $scope.memberAttendClassPages = response.data.pages;
                $scope.memberAttendClassFlag  = false;
                $scope.memberAttendClassAllMoney  = response.data.sum.totalMoney;

            }else{
                $scope.memberAttendClassLists = response.data.data;
                $scope.memberAttendClassNow   = 1;
                $scope.memberAttendClassCount = response.data.totalNum;
                $scope.memberAttendClassPages = response.data.pages;
                $scope.memberAttendClassFlag  = true;
                $scope.memberAttendClassAllMoney  = response.data.sum.totalMoney;
            }
        });
    }
    //会员上课量筛选清空
    $scope.MemberSubmitClear = function(){
        $('#memberAttendClassDate').val('');
        $scope.memberStartDate = '';
        $scope.memberEndDate   = '';
        $scope.memberPtCourseType = '';
        $scope.MemberSubmit();
    }


    //筛选会员上课量
    $scope.MemberSubmit = function(){
        $scope.getMemberDate();
        $scope.memberAttendClassUrlInit = '/private-statistics/member-attend-class?'+ $.param($scope.memberAttendClassSearchData());
        $scope.getMemberAttendClass();
    }

    //会员上课量分页
    $scope.oneMemberPages = function(urlPages){
        $scope.memberAttendClassUrlInit = urlPages;
        $scope.getMemberAttendClass();
    }
    /*---------------会员上下课Start----------------*/

    /******---------所有私教的卖课量Starts------********/

    //卖课量搜索数据
    $scope.getSearchSellClassData = function(){
        return {
            venueId        :$scope.sellClassVenue       != undefined && $scope.sellClassVenue      != '' ? $scope.sellClassVenue : null,
            coachId        :$scope.sellClassCoach        != undefined && $scope.sellClassCoach         != '' ? $scope.sellClassCoach : null,
            dateStart      :$scope.AllSellClassStartDate     != undefined && $scope.AllSellClassStartDate    != '' ? $scope.AllSellClassStartDate: null,
            dateEnd        :$scope.AllSellClassEndDate       != undefined && $scope.AllSellClassEndDate       != '' ? $scope.AllSellClassEndDate: null,
            keywords       :$scope.sellClassKeywords       != undefined && $scope.sellClassKeywords       != '' ? $scope.sellClassKeywords:null,
            sortType       : $scope.sortType           != undefined && $scope.sortType            != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort               != undefined && $scope.sort                 != ''  ? $scope.sort       : null
        }
    }

    //获取所有卖课量
    $scope.getAllSellClassData = function(){
        $.loading.show();
        $http.get($scope.AllSellClassUrlInit).then(function(response){
            if(response.data.data.length != 0){
                $scope.allSellClassLists   = response.data.data;
                $scope.allSellClassNow     = response.data.now;
                $scope.allSellClassNum     = response.data.totalNum;
                $scope.allSellClassPages   = response.data.pages;
                $scope.allSellClassFlag    = false;
                $scope.allSellClassSum       = response.data.sum;
            }else{
                $scope.allSellClassLists   = response.data.data;
                $scope.allSellClassNow     = 1;
                $scope.allSellClassNum     = 0;
                $scope.allSellClassPages   = response.data.pages;
                $scope.allSellClassFlag    = true;
                $scope.allSellClassSum     = response.data.sum;
            }
            $.loading.hide();
        });
    }
    //卖课量筛选清空
    $scope.allSellClassSubmitClear = function(){
        $('#sellClassDate').val('');
        $scope.sellClassVenue = '';
        $scope.sellClassCoach = '';
        $scope.AllSellClassStartDate = '';
        $scope.AllSellClassEndDate  = '';
        $scope.sellClassKeywords    = '';
        $('#select2-sellClassVenueSelect123-container').text('请选择场馆');
        $('#select2-sellClassSelect123-container').text('请选择私教');
        $scope.allSellClassSubmit();
    }
    

    //所有售课量筛选
    $scope.allSellClassSubmit = function(){
        var OnePtDate = $('#sellClassDate').val();
        var startTime = OnePtDate.substr(0, 10);
        var endTime = OnePtDate.substr(-10, 10);
        $scope.AllSellClassStartDate  = startTime;
        $scope.AllSellClassEndDate    = endTime;
        $scope.AllSellClassUrlInit    = '/private-statistics/sell-private-class-all?'+$.param($scope.getSearchSellClassData())
        $scope.getAllSellClassData();
    }

    //所有私教卖课量分页
    $scope.allSellPages = function(urlPages){
        $scope.AllSellClassUrlInit  = urlPages;
        $scope.getAllSellClassData();
    }
    /******---------所有私教的卖课量End------********/

    /*-----------------私教卖课详情Start------------------*/
    $scope.OnePtSellClassSearchData = function(){
        return {
            classType      :$scope.onePtSellClassType      != undefined && $scope.onePtSellClassType                 != ''  ? $scope.onePtSellClassType       : null,
            status         :$scope.DealStatus               != undefined && $scope.DealStatus                 != ''  ? $scope.DealStatus       : null,
            coachId        :$scope.onePtSellClassCoachId        != undefined && $scope.onePtSellClassCoachId         != '' ? $scope.onePtSellClassCoachId : null,
            dateStart      :$scope.pTSellClassStartDate     != undefined && $scope.pTSellClassStartDate    != '' ? $scope.pTSellClassStartDate: null,
            dateEnd        :$scope.pTSellClassEndDate       != undefined && $scope.pTSellClassEndDate       != '' ? $scope.pTSellClassEndDate: null,
            sortType       : $scope.sortType           != undefined && $scope.sortType            != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort               != undefined && $scope.sort                 != ''  ? $scope.sort       : null
        }
    }

    //获取一个私教的卖课量
    $scope.getOnePtSellClassData = function(){
        $http.get($scope.onePtSellClassUrlInit).then(function(response){
            if(response.data.data.length != 0){
                $scope.onePtSellClassLists = response.data.data;
                $scope.onePtSellClassNow   = response.data.now;
                $scope.onePtSellClassPages   = response.data.pages;
                $scope.onePtSellClassFlag   = false;
                $scope.onePtSellClassSum    = response.data.sum;
                $scope.onePtSellClassNum    = response.data.totalNum;
            }else{
                $scope.onePtSellClassLists = response.data.data;
                $scope.onePtSellClassNow   = response.data.now;
                $scope.onePtSellClassPages   = response.data.pages;
                $scope.onePtSellClassFlag   = true;
                $scope.onePtSellClassSum    = response.data.sum;
                $scope.onePtSellClassNum    = response.data.totalNum;
            }
        })
    }

    //获取卖课量私教的信息
    $scope.getOnePtSellCoachMessage = function(){
        $http.get('/private-statistics/private-coach-info?coachId='+ $scope.onePtSellClassCoachId).then(function(response){
            $scope.OnePtSellCoachMessage = response.data;
        });
    }

    //卖课量详情筛选清空
    $scope.onePtSellClassSubmitClear = function(){
        $('#pTSellClassDate').val('');
        $scope.pTSellClassStartDate = '';
        $scope.pTSellClassEndDate   = '';
        $scope.onePtSellClassType   = '';
        $scope.DealStatus            = '';
        $scope.onePtSellClassSubmit();

    }

    //卖课量详情筛选
    $scope.onePtSellClassSubmit = function(){
        var OnePtDate = $('#pTSellClassDate').val();
        var startTime = OnePtDate.substr(0, 10);
        var endTime = OnePtDate.substr(-10, 10);
        $scope.pTSellClassStartDate  = startTime;
        $scope.pTSellClassEndDate    = endTime;
        $scope.onePtSellClassUrlInit = '/private-statistics/sell-private-class-one?'+$.param($scope.OnePtSellClassSearchData());
        $scope.getOnePtSellClassData();
    }

    //关键字上搜索
    $scope.sellClassSearch = function(){
        var OnePtDate = $('#pTSellClassDate').val();
        var startTime = OnePtDate.substr(0, 10);
        var endTime = OnePtDate.substr(-10, 10);
        $scope.pTSellClassStartDate  = startTime;
        $scope.pTSellClassEndDate    = endTime;
        $scope.onePtSellClassUrlInit = '/private-statistics/sell-private-class-one?'+$.param($scope.OnePtSellClassSearchData());
        $scope.getOnePtSellClassData();
    }

    //卖课量详情分页
    $scope.oneSellPages = function(urlPages){
        $scope.onePtSellClassUrlInit = urlPages;
        $scope.getOnePtSellClassData();
    }
    /*-----------------私教卖课详情End------------------*/

    /*-----------------生日会员Start------------------*/
    //筛选生日会员的数据
    $scope.getBirthdaySearchData = function(){
        return {
            status         :$scope.ptClassStatus        != undefined && $scope.ptClassStatus         != '' ? parseInt($scope.ptClassStatus) : null,
            keywords       :$scope.memberBirKeywords        != undefined && $scope.memberBirKeywords         != '' ? $scope.memberBirKeywords : null,
            venueId        :$scope.homePageVenue        != undefined && $scope.homePageVenue         != '' ? $scope.homePageVenue : null,
            coachId        :$scope.birthdayCoach        != undefined && $scope.birthdayCoach         != '' ? $scope.birthdayCoach : null,
            startTime      :$scope.memberStartDate     != undefined && $scope.memberStartDate    != '' ? $scope.memberStartDate: null,
            endTime        :$scope.memberEndDate       != undefined && $scope.memberEndDate       != '' ? $scope.memberEndDate: null,
            birthdayClass  : $scope.birthdayClass123   != undefined && $scope.birthdayClass123   != ''  ? $scope.birthdayClass123   : null,
            sortType       : $scope.sortType           != undefined && $scope.sortType            != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort               != undefined && $scope.sort                 != ''  ? $scope.sort       : null,
            memberType     : $scope.memberType != undefined && $scope.memberType != '' ? $scope.memberType : null
        }
    }

    //获取所有的生日会员
    $scope.getBirthdayDate = function(){
        $.loading.show();
        $http.get($scope.memberBirUrlInit).then(function(response){
            if(response.data.data.length != 0){
                $scope.allMemberBirthdayLists = response.data.data;
                $scope.allMemberNow = response.data.now;
                $scope.allMemberPages = response.data.pages;
                $scope.allMemberFlag   = false;
            }else{
                $scope.allMemberBirthdayLists = response.data.data;
                $scope.allMemberNow   = 1;
                $scope.allMemberPages = response.data.pages;
                $scope.allMemberFlag  = true;
            }
            $.loading.hide();
        });
    }
    //清空生日筛选
    $scope.memberBirSubmitClear = function(){
        $('#birthdayDate').val('');
        $scope.ptClassStatus = '';
        $scope.memberBirKeywords = '';
        $scope.homePageVenue = '';
        $scope.birthdayCoach = '';
        $scope.memberStartDate = '';
        $scope.memberEndDate  = '';
        $scope.birthdayClass123 = '';
        $scope.memberType = '';
        $('#select2-birthdayCoachSelect-container').text('请选择私教');
        $scope.memberBirSubmit();
    }

    //筛选生日会员
    $scope.memberBirSubmit = function(){
        var memberDate = $('#birthdayDate').val();
        var startTime = memberDate.substr(0, 10);
        var endTime = memberDate.substr(-10, 10);
        $scope.memberStartDate  = startTime;
        $scope.memberEndDate    = endTime;
        $scope.memberBirUrlInit = '/private-statistics/birthday-member?'+ $.param($scope.getBirthdaySearchData());
        $scope.getBirthdayDate();
    }
    //搜索生日会员
    $scope.searchMemberBir = function(){
        $scope.memberBirSubmit();
    }
    //keyup输入搜索
    $scope.enterSearchMemberBir123 = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){
            $scope.searchMemberBir();
        }
    }

    //生日会员分页
    $scope.birthdayPages = function(urlPages){
        $scope.memberBirUrlInit = urlPages;
        $scope.getBirthdayDate();
    }

    /*-----------------生日会员End------------------*/
    //选择首页场馆
    $scope.homePagesPtVenueChange = function(){
        $scope.homeSearchSubmit();
    }
    //初始化调用
    $scope.homeSearchSubmit();
});


