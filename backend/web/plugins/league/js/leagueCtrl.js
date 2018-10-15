//团课页面控制器
var app = angular.module('App');
app.controller('leagueCtrl',function($scope,$http){
    
    /**
     * 后台 - 团课管理 - 分页（团课数据遍历）
     *@update author Hou kaiXin houkaixin@itsport.club
     *@update 2017/4/13
     */
    $scope.aboutDetail =function(id){
        window.location.href = '/league/league-detail?mid=6&c=3&id='+id;
    };
    $scope.init = function () {
        $scope.pageInitUrl = '/league/course';
        $scope.getClassModel();
        // $scope.venue();
        $scope.classroom();
        $scope.getVenueOptions();  //获取场馆
    };
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };
    //  分页数据信息
    $scope.getClassModel = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data.data.length != 0) {
                $scope.items      = result.data.data;
                $scope.dataInfo   = false;
                $scope.searchData = false;
            }else{
                $scope.dataInfo = true;
           }
            $scope.items  = result.data.data;
            $scope.pages  = result.data.pages;
        })
    };
    /**
     * 后台 - 团课管理 - 删除(删除弹框提示)
     *@update author Hou kaiXin houkaixin@itsport.club
     *@update 2017/4/13
     */
    $scope.delete = function (id) {
        Sweety.remove({
            url:  '/league/delete?id='+id,
            http: $http,
            title: '确定要删除吗?',
            text: '课种删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getClassModel();
        });
    };
    /**
     * 后台 - 团课管理 - 下拉列表数据（场馆表）
     *@update author Hou kaiXin houkaixin@itsport.club
     *@update 2017/4/14
     */
     // $scope.venue = function(){
    //     $http.get('/league/venue').then(function (result) {
    //         if(result.data != undefined && result.data != ""){
    //             $scope.venues = result.data;
    //             $scope.VenueStauts = true;
    //         }else{
    //             $scope.VenueStauts = false;
    //             $scope.venues = '暂无数据';
    //         }
    //     })
    // };
    //添加团课获取场馆信息
    $scope.getVenueOptions       = function () {
        $http.get('/rechargeable-card-ctrl/get-venue').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.venues      = result.data.venue;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.venues = '暂无数据';
            }
        });
    };
    /**
     * 后台 - 团课管理 - 下拉列表数据（教室表）
     *author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/14
     */
    $scope.classroom = function(){
        $http.get('/league/class-room').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.classrooms = result.data;
                $scope.classroomsStauts = true;
            }else{
                $scope.classroomsStauts = false;
                $scope.classrooms = '暂无数据';
            }
        })
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.classroomId       = null;
        $scope.venueId      = null;
        $scope.courseName      = null;
        $scope.coachName      = null;
        $("#reservation").val("");
    };
    //分隔搜索时间
    $scope.formatDates    = function () {
        $scope.startTime =   $("#reservation").val().substr(0,10);
        $scope.endTime   =   $("#reservation").val().substr(-10,10);
    };
    /**
     * 后台 - 团课管理 - 搜索框搜索
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/14
     */

    /**处理搜索数据**/
    $scope.searchModel = function(){
         // var startTime = $("#reservation").val().split(' - ')[0];
         // var endTime   = $("#reservation").val().split(' - ')[1];
        $scope.formatDates();
         return {
             courseName   : $scope.courseName   != undefined ? $scope.courseName  : null,    //课程名字
             venueId      : $scope.venueId      != undefined ? $scope.venueId     : null,    //场馆id
             classroomId  : $scope.classroomId  != undefined ? $scope.classroomId : null,    // 教室id
             startTime    :  $scope.startTime   != undefined ?  $scope.startTime  : null,   // 上课开始时间
             endTime      :  $scope.endTime     != undefined ? $scope.endTime     : null,     // 上课结束
             coachName    : $scope.coachName    != undefined ? $scope.coachName   : null,    // 教练名字
             classrooms   : $scope.classrooms   != undefined ? $scope.classrooms  : null,    //教室名字
             courseDate   : $scope.courseDate   != undefined ? $scope.courseDate  : null,    //课程日期
             sortType     : $scope.sortType     != undefined ? $scope.sortType    : null,    //需要排序的字段名字
             sortName     : $scope.sort         != undefined ? $scope.sort        : null    //排序的类型
         }
    };
   /**搜索参数以及方法**/
    $scope.initPath = function (){
        $scope.searchParams   =  $scope.searchModel();
        $scope.pageInitUrl       =  '/league/course?' + $.param($scope.searchParams);
    };

    //执行最终搜索
    $scope.search = function() {
         $scope.initPath();
        $scope.getClassModel();
    };
    //执行回车搜索
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
        }
    };

    /**
     * 后台 - 团课管理 - 主界面各个字段排序
     *author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/14
     */
    //排序入口
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.search();
    };
    //asc desc轮流转换
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

    $scope.init();
});

