/**
 财务管理模块-营运统计页面
   内容：统计到店人数、会员上课统计、卡种统计
 * @author  zhujunzhe@itsports.club
 * @create  2017.11.30
 */


angular.module('App').controller('indexUpgradeCtrl', function ($scope, $http) {
    // 获取当前日期
        $scope.mDate = $('#dateSpan');
        $scope.today = function (){
            var newToday = new Date();
            var y = newToday.getFullYear();
            var m = newToday.getMonth() + 1;//获取当前月份的日期
            var d = newToday.getDate();
            var dd = newToday.getDate() - 1;
            $scope.mDate.val(y+"-"+m+"-"+d);
                $('#comeToday').val(y+"-"+m+"-"+d);
                var comeToday = $('#comeToday').val();         //今天的
                $('#comeYesterday').val(y+"-"+m+"-"+dd);
                var comeYesterday = $('#comeYesterday').val(); //昨天的
            $http.get("/site/entry-num?date="+comeToday)
                .then(function (date){
                    $scope.todayAdd = date.data.allNum;
                });

            // 下拉框变化的函数
            $("#mySelectDate").change(function (){
                if($("#mySelectDate").val() == comeToday){
                    $http.get("/site/entry-num?date="+comeToday)
                        .then(function (date){
                            $scope.todayAdd = date.data.allNum;
                        });
                }else {
                    $http.get("/site/entry-num?date="+comeYesterday)
                        .then(function (date){
                            $scope.todayAdd = date.data.allNum;
                        });
                }
            })
        };
        $scope.today();
    // 下拉获取今日到店人数
    $scope.dateSelect = function (){
        var changeSelect = $("#changeSelect").val();
        if(changeSelect == 2){
            $('.dataAllPeople').show();
            $scope.date = $scope.mDate.val();
            $http.get("/site/entry-num?date="+$scope.date)
                .then(function (date){
                    $scope.datePeopleNum = date.data.allNum;
                    $scope.datePicMen = date.data.men;
                    $scope.datePicWomen = date.data.women;
                    // 获取详情的js
                    $scope.dateDetails = function () {
                        $http.get("/site/entry-record?date="+$scope.date)
                            .then(function (date){
                                $scope.userItems = date.data;
                            });
                    };
                    $scope.calendar = $('#dateSpan')
                    // 触发日期选择器的js
                    $("#dateIndex").datetimepicker({
                        minView: "month",//设置只显示到月份
                        format: 'yyyy-mm-dd',
                        language: 'zh-CN',
                        autoclose:true,
                        todayBtn: true,//今日按钮
                        setStartDate: "2017-5-25"
                    }).on('changeDate', function(ev){
                        $scope.calendar = $("#dateSpan").val();
                        // 传送日期数据给后台
                        $http.get("/site/entry-num?date="+$scope.calendar)
                            .then(function (dd){
                                $scope.datePeopleNum = dd.data.allNum;
                                $scope.datePicMen = dd.data.men;
                                $scope.datePicWomen = dd.data.women;
                                chart9();
                                // 获取详情的js
                                $scope.dateDetails = function () {
                                    $http.get("/site/entry-record?date="+$scope.calendar)
                                        .then(function (date){
                                            $scope.userItems = date.data;
                                        });
                                };
                            });
                    });
                    // 到店人数折线统计图
                    var chart9 = function () {
                        var myChart = echarts.init(document.getElementById('main9'));
                        option = {
                            color: colors,

                            tooltip: {
                                trigger: 'none',
                                axisPointer: {
                                    type: 'cross'
                                }
                            },
                            legend: {
                                data:['男', '女']
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
                                            color: colors[1]
                                        }
                                    },
                                    axisPointer: {
                                        label: {
                                            formatter: function (params) {
                                                return '男，进店人数' + params.value
                                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "人";
                                            }
                                        }
                                    },
                                    data: ["07:00","08:00", "09:00", "10:00", "11:00", "12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00"]
                                },
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
                                                return '女，进店人数' + params.value
                                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "人";
                                            }
                                        }
                                    },
                                    data: ["07:00","08:00", "09:00", "10:00", "11:00", "12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00"]
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value'
                                }
                            ],
                            series: [
                                {
                                    name:'女',
                                    type:'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: [$scope.datePicWomen.time7,$scope.datePicWomen.time8, $scope.datePicWomen.time9, $scope.datePicWomen.time10, $scope.datePicWomen.time11, $scope.datePicWomen.time12, $scope.datePicWomen.time13, $scope.datePicWomen.time14, $scope.datePicWomen.time15, $scope.datePicWomen.time16, $scope.datePicWomen.time17, $scope.datePicWomen.time18, $scope.datePicWomen.time19, $scope.datePicWomen.time20,$scope.datePicWomen.time21]
                                },
                                {
                                    name:'男',
                                    type:'line',
                                    smooth: true,
                                    data: [$scope.datePicMen.time7, $scope.datePicMen.time8, $scope.datePicMen.time9, $scope.datePicMen.time10, $scope.datePicMen.time11, $scope.datePicMen.time12, $scope.datePicMen.time13, $scope.datePicMen.time14, $scope.datePicMen.time15, $scope.datePicMen.time16, $scope.datePicMen.time17, $scope.datePicMen.time18, $scope.datePicMen.time19, $scope.datePicMen.time20,$scope.datePicMen.time21]
                                }
                            ]
                        };

// 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    };
                    chart9();
                });
        }
    };

    // 折线统计图
// 基于准备好的dom，初始化echarts实例

    var colors = ['#ff6700', '#00cc00', '#69e'];

    var chart1 = function () {
        var myChart = echarts.init(document.getElementById('main'));
        option = {
            color: colors,

            tooltip: {
                trigger: 'none',
                axisPointer: {
                    type: 'cross'
                }
            },
            legend: {
                data:['2015 销售量', '2016 销售量']
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
                            color: colors[1]
                        }
                    },
                    axisPointer: {
                        label: {
                            formatter: function (params) {
                                return '销售量  ' + params.value
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "元";
                            }
                        }
                    },
                    data: ["2016-1", "2016-2", "2016-3", "2016-4", "2016-5", "2016-6","2016-7","2016-8","2016-9","2016-10","2016-11","2016-12"]
                },
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
                                return '销售量  ' + params.value
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "元";
                            }
                        }
                    },
                    data: ["2016-1", "2016-2", "2016-3", "2016-4", "2016-5", "2016-6","2016-7","2016-8","2016-9","2016-10","2016-11","2016-12"]
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name:'2015 销售量',
                    type:'line',
                    xAxisIndex: 1,
                    smooth: true,
                    data: [100000, 100080, 100090, 99999, 108688, 200000, 168000, 18000, 200000, 100000, 100080, 100090]
                },
                {
                    name:'2016 销售量',
                    type:'line',
                    smooth: true,
                    data: [100500, 190080, 100010, 99999, 18688, 100000, 122222, 100080, 100090, 99999, 108688, 68222]
                }
            ]
        };

// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    };
    
    chart1();


// 折线统计图2
    var chart2 = function () {
        var myChart = echarts.init(document.getElementById('main2'));
        option = {
            color: colors,

            tooltip: {
                trigger: 'none',
                axisPointer: {
                    type: 'cross'
                }
            },
            legend: {
                data:['2015 销售量', '2016 销售量']
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
                            color: colors[1]
                        }
                    },
                    axisPointer: {
                        label: {
                            formatter: function (params) {
                                return '销售量  ' + params.value
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "元";
                            }
                        }
                    },
                    data: ["2016-1", "2016-2", "2016-3", "2016-4", "2016-5", "2016-6"]
                },
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
                                return '销售量  ' + params.value
                                    + (params.seriesData.length ? '：' + params.seriesData[0].data : '') + "元";
                            }
                        }
                    },
                    data: ["2015-1", "2015-2", "2015-3", "2015-4", "2015-5", "2015-6"]
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name:'2015 销售量',
                    type:'line',
                    xAxisIndex: 1,
                    smooth: true,
                    data: [100000, 100080, 100090, 99999, 108688, 200000]
                },
                {
                    name:'2016 销售量',
                    type:'line',
                    smooth: true,
                    data: [100500, 190080, 100010, 99999, 18688, 100000]
                }
            ]
        };

// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    };
    chart2();
// select下拉做出改变事件
    $('#mySelectChange').change(function (){
        var select = $('#mySelectChange').val();
        if(select == 6){
            $("#main").hide();
            $("#main2").show();
        }else{
            $("#main2").hide();
            $("#main").show();
        }
    });
    // 到店人数和总收入下拉做出改变事件
    $('#changeSelect').change(function (){
        var select2 = $('#changeSelect').val();
        if(select2 == 2){
            $("#main2").hide();
            $("#main").hide();
            $("#main9").show();
            $("#mySelectChange").hide();
            $("#dateIndex").show();
            $(".btnDetails").show();
        }else {
            $("#main9").hide();
            $("#main").hide();
            $("#main2").show();
            $("#mySelectChange").show();
            $("#dateIndex").hide();
            $(".btnDetails").hide();
        }
    });
// 饼状统计图
    var chart3 = function () {
        var myChart = echarts.init(document.getElementById('main3'));
        option = {
            color:['#00cc00', '#69e','#ff6700','#fff143'],

            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['私教13.8%','健康食品12.77%','饮料茶水9.64%','会员卡63.78%']
            },
            series: [
                {
                    name:'各项收入',
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
                    data:[
                        {value:335, name:'私教13.8%'},
                        {value:310, name:'健康食品12.77%'},
                        {value:234, name:'饮料茶水9.64%'},
                        {value:1548, name:'会员卡63.78%'}
                    ]
                }
            ]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    chart3();
// 饼状统计图2
    var chart4 = function () {
        var myChart = echarts.init(document.getElementById('main4'));
        option = {
            color:['#00cc00', '#69e','#ff6700','#fff143'],

            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['私教63.78%','健康食品9.64%','饮料茶水13.8%','会员卡12.77%']
            },
            series: [
                {
                    name:'各项收入',
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
                    data:[
                        {value:1548, name:'私教63.78%'},
                        {value:234, name:'健康食品9.64%'},
                        {value:335, name:'饮料茶水13.8%'},
                        {value:310, name:'会员卡12.77%'}
                    ]
                }
            ]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    chart4();
// select下拉做出改变事件2
    $('#mySelectChange2').change(function (){
        var select = $('#mySelectChange2').val();
        if(select == 6){
            $("#main4").hide();
            $("#main3").show();
        }else {
            $("#main3").hide();
            $("#main4").show();
        }
        return false;
    });
// select下拉做出改变事件4
    $('#mySelectChange4').change(function (){
        var select = $('#mySelectChange4').val();
        if(select == 6){
            $("#main8").hide();
            $("#main5").show();
        }else {
            $("#main5").hide();
            $("#main8").show();
        }
        return false;
    });
// 柱状图
    var chart5 = function () {
        var myChart = echarts.init(document.getElementById('main5'));
        option = {
            color: ['#3398DB'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
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
                    data : ['广告', '网络', '推荐', '体验卡', '礼券', '团购', '其他'],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'人',
                    type:'bar',
                    barWidth: '60%',
                    data:[10, 52, 200, 334, 390, 330, 220]
                }
            ]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    chart5();
    var chart8 = function () {
        var myChart = echarts.init(document.getElementById('main8'));
        option = {
            color: ['#3398DB'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
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
                    data : ['广告', '网络', '推荐', '体验卡', '礼券', '团购', '其他'],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'人',
                    type:'bar',
                    barWidth: '60%',
                    data:[200, 12, 60, 134, 290, 400, 120]
                }
            ]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    chart8();
// 雷达图
    var chart6 = function () {
        var myChart = echarts.init(document.getElementById('main6'));
        option = {
            title: {
                text: ''
            },
            tooltip: {},
            legend: {
                data: ['店铺指标']
            },
            radar: {
                // shape: 'circle',
                indicator: [
                    { name: '员工工作', max: 6500},
                    { name: '事故发生', max: 16000},
                    { name: '课程管理', max: 30000},
                    { name: '卫生状况', max: 38000},
                    { name: '收入支出', max: 52000},
                    { name: '机械运转', max: 25000}
                ]
            },
            series: [{
                name: '店铺指标',
                type: 'radar',
                // areaStyle: {normal: {}},
                data : [
                    {
                        value : [4300, 10000, 28000, 35000, 50000, 19000],
                        name : '店铺指标'
                    }
                ]
            }]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    chart6();
// 雷达图2
    var chart7 = function () {
        var myChart = echarts.init(document.getElementById('main7'));
        option = {
            title: {
                text: ''
            },
            tooltip: {},
            legend: {
                data: ['店铺指标']
            },
            radar: {
                // shape: 'circle',
                indicator: [
                    { name: '员工工作', max: 500},
                    { name: '事故发生', max: 6000},
                    { name: '课程管理', max: 30000},
                    { name: '卫生状况', max: 8000},
                    { name: '收入支出', max: 2000},
                    { name: '机械运转', max: 25000}
                ]
            },
            series: [{
                name: '店铺指标',
                type: 'radar',
                // areaStyle: {normal: {}},
                data : [
                    {
                        value : [300, 4000, 8000, 5000, 2000, 19000],
                        name : '店铺指标'
                    }
                ]
            }]
        };
// 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option)
    };
    chart7();
    $('#mySelectChange3').change(function (){
        var select = $('#mySelectChange3').val();
        if(select == 6){
            $("#main7").hide();
            $("#main6").show();
        }else {
            $("#main6").hide();
            $("#main7").show();
        }
        return false;
    });

});
