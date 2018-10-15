/**
 * Created by 杨大侠 on 2017/8/29.
 */

angular.module('App').controller('indexCtrl', function ($scope, $http, Upload, $timeout,$interval) {
    $scope.pageInitUrl ="/league-statistics/get-coach-league-all?"

    $scope.getVenue = function (data) {
        $http({
            url: '/rechargeable-card-ctrl/get-venue',
            method: 'get'
        }).then(function (data) {
            $scope.venueData = data.data.venue
            $scope.Venue = data.data.venue[0].id
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    }

    $scope.searchDatas = function () {
        $scope.startTime = $("#reservation").val().substr(0, 10);
        $scope.endTime = $("#reservation").val().substr(-10, 10);
        return{
            keyword:$scope.keywords != undefined ? $scope.keywords : '',
            start:$scope.startTime  != undefined ? $scope.startTime : '',
            end:$scope.endTime  != undefined ? $scope.endTime : '',
            venueId:$scope.Venue != undefined ? $scope.Venue : '',
            sortType: $scope.sortType != undefined ? $scope.sortType : '',
            sortName: $scope.sort != undefined ? $scope.sort : '',
        }
    }
    $scope.listData = function () {
        $scope.data = $scope.searchDatas();
        $http({method: 'get', url: $scope.pageInitUrl+ $.param($scope.data)}).then(function (response) {
            if (response.data.data == "" || response.data.data == undefined || response.data.data.length == '') {
                $scope.listDataItem = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = true;
                $scope.listDataSum = response.data.sum;
            } else {
                $scope.listDataItem = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = false;
                $scope.listDataSum = response.data.sum;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    }
    $scope.searchDatas2 = function () {
        $scope.startTime = $("#reservation").val().substr(0, 10);
        $scope.endTime = $("#reservation").val().substr(-10, 10);
        return{
            keyword:$scope.keywords != undefined ? $scope.keywords : '',
            start:$scope.startTime  != undefined ? $scope.startTime : '',
            end:$scope.endTime  != undefined ? $scope.endTime : '',
            venueId:$scope.Venue != undefined ? $scope.Venue : '',
            sortType: $scope.sortType != undefined ? $scope.sortType : '',
            sortName: $scope.sort != undefined ? $scope.sort : '',
        }
    }
    angular.element(document).ready(function () {
            var date_ = new Date();
            var year = date_.getFullYear();
            var month = date_.getMonth() + 1;
            $scope.startTime = year + '-' + month + '-01';//当月第一天
            var day = new Date(year,month,0);
            $scope.endTime = year + '-' + month + '-' + day.getDate();//当月最后一天
            var stringDate = $scope.startTime + ' - ' + $scope.endTime
            $("#reservation").val(stringDate)
            $(".memberHeaderVenue").select2();
            $scope.getVenue();
        $scope.listData();
    })


    //点击按钮按条件搜索
    $scope.searchOk = function () {
        $scope.data = $scope.searchDatas2();
        $http({method: 'get', url: $scope.pageInitUrl+ $.param($scope.data)}).then(function (response) {
            if (response.data.data == "" || response.data.data == undefined || response.data.data.length == '') {
                $scope.listDataItem = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = true;
                $scope.listDataSum = response.data.sum;
            } else {
                $scope.listDataItem = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = false;
                $scope.listDataSum = response.data.sum;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    }
    //回车事件
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.listData();
        }
    }
    $scope.searchCard = function () {
        $scope.listData();
    }
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.listData();
    };
    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.listData();
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
    
    $scope.leagueStatistics = function (coachId) {

        localStorage.setItem("coachId",JSON.stringify({
            id:coachId,
        }))
        location.href = '/league-statistics/league-chart'
    }

    $scope.preview = function(oper) {
        if (oper < 10) {
            bdhtml = window.document.body.innerHTML;//获取当前页的html代码
            sprnstr = "<!--startprint" + oper + "-->";//设置打印开始区域
            eprnstr = "<!--endprint" + oper + "-->";//设置打印结束区域
            prnhtml = bdhtml.substring(bdhtml.indexOf(sprnstr) + 18); //从开始代码向后取html

            prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));//从结束代码向前取html
            window.document.body.innerHTML = prnhtml;
            window.print();
            window.document.body.innerHTML = bdhtml;
        } else {
            window.print();
        }
    }
})