/**
 * Created by 杨大侠 on 2017/8/29.
 */

angular.module('App').controller('LeagueChartCtrl', function($scope,$http,$timeout) {

   var coachId = localStorage.getItem("coachId");
    $scope.coachId = JSON.parse(coachId).id
    $http({method:'get',url:'/personnel/employee-details?EmployeeId='+ $scope.coachId}).then(function (data) {
        $scope.coachName = data.data;
    },function (error) {
        console.log(error);Message.error("系统错误请联系管理人员")
    });

    // 上课的change事件
    $scope.classChange = function (){
        $scope.getClassNumData();
        $scope.getAllNumData();
    };

    // 获取上课人数统计的数据
    $scope.getClassNumData = function (){
        $http.get("/league-statistics/get-about-class-league?type=" +$scope.classValue + "&coach_id="+$scope.coachId).success(function(data){
            var $classNum = data.data;
            var $classKey = data.key;
            var $classArr = [];
            var $numArr = [];
            for(var item in $classNum){
                $classArr.push(item);
                $numArr.push($classNum[item]);
            }
            $scope.classTitArr = $classArr;
            $scope.numArr = $numArr;
            $scope.getClassCharts();
        });
    };

    // 获取总人数统计的数据
    $scope.getAllNumData = function (){
        $http.get('/league-statistics/get-about-class-league-total?type=' + $scope.classValue + "&coach_id="+$scope.coachId).success(function(data){
                $scope.member       = data.data;
                var $memberNum      = data.data.class;
                var $memberNameList = [];
                var $memberAllList  = [];
                if(data.data != '') {
                    for(var i=0;i < $memberNum.length;i++){
                        $memberNameList = $memberNum[i].name;
                        $memberAllList[i] = {value:$memberNum[i].classNum,name:$memberNum[i].name}
                    }
                    $(".nodataFeng").hide();
                }
                else {
                    $memberAllList  = [];
                    $memberNameList = [];
                    $(".nodataFeng").show();
                }
                $scope.memberNameList = $memberNameList;
                $scope.memberAllList  = $memberAllList;
            $scope.getAllNumCharts()
        });
    };

    // 初始化触发上课人数方法
    $scope.classValue = 'd';
    $scope.classChange();;

    // 基于准备好的dom，初始化echarts实例
    $scope.getClassCharts = function (){
        var classChart = echarts.init(document.getElementById('classMian'));
        var option ={
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['人数统计']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: $scope.classTitArr
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'人数统计',
                    type:'line',
                    stack: '总量',
                    data: $scope.numArr,
                    itemStyle: {
                        normal:{
                            color: '#00cc00'
                        }
                    }
                }
            ]
        };
        // 应用以上设定
        classChart.setOption(option);
    };

    // 基于准备好的dom，初始化echarts实例
    $scope.getAllNumCharts = function (){
        var allChart = echarts.init(document.getElementById('sellMain1'));
        option = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                bottom: 0,
                left: 'left',
                data: $scope.memberAllList
            },
            series: [
                {
                    name: $scope.memberNameList,
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    data:$scope.memberAllList
                }
            ],
            color: ['#60C9F9','#9933FF','#51D76A','#FF6600','#FECB2F']
        };
        // 应用以上设定
        allChart.setOption(option);
    };

    $(document).ready(function () {
        var date_ = new Date();
        var year = date_.getFullYear();
        var month = date_.getMonth() + 1;
        $scope.startTime = year + '-' + month + '-01';//当月第一天
        var day = new Date(year,month,0);
        $scope.endTime = year + '-' + month + '-' + day.getDate();//当月最后一天
        var stringDate = $scope.startTime+' - '+$scope.endTime;
        $("#noSignReservation").val(stringDate)
    })
    $scope.formatDate = function () {
        $scope.startTime = $("#noSignReservation").val().substr(0, 10);
        $scope.endTime = $("#noSignReservation").val().substr(-10, 10);
    };
    $scope.coachDetails = function () {
        $scope.coachDetailsData();
    }

    $scope.applyBtn = function (){
        $scope.startTime = $("#noSignReservation").val().substr(0, 10);
        $scope.endTime = $("#noSignReservation").val().substr(-10, 10);
        $http({method:'get',url:'/league-statistics/get-about-class-league-detail?start='+$scope.startTime+"&end="+$scope.endTime+"&coach_id="+ $scope.coachId}).then(function (data) {
            if(data.data.data == '' || data.data.data == undefined || data.data.data.length == ''){
                $scope.coachDetailsDataItem = data.data;
                $scope.pages = data.data.pages;
                $scope.classListShow = true;
            }else {
                $scope.coachDetailsDataItem = data.data;
                $scope.pages = data.data.pages;
                $scope.classListShow = false;
            }

        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }

    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.coachDetailsData();
    };
    $scope.changeNotSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.coachDetailsData();
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
    
    $scope.coachDetailsData = function () {
        $scope.formatDate()
        $http({method:'get',url:'/league-statistics/get-about-class-league-detail?start='+$scope.startTime+"&end="+$scope.endTime+"&coach_id="+ $scope.coachId}).then(function (data) {
                if(data.data.data == '' || data.data.data == undefined || data.data.data.length == ''){
                    $scope.coachDetailsDataItem = data.data;
                    $scope.pages = data.data.pages;
                    $scope.classListShow = true;
                }else {
                    $scope.coachDetailsDataItem = data.data;
                    $scope.pages = data.data.pages;
                    $scope.classListShow = false;
                }

        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }


})
