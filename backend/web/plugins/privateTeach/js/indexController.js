$(function () {
    $(document).on('mouseover','#tbody tr',function () {
        $(this).find('.displayBlock').css('opacity','1');
    });
    $(document).on('mouseleave','#tbody tr',function () {
        $(this).find('.displayBlock').css('opacity','0');
    });
});
var app = angular.module('App');
app.controller('indexController',function($scope,$http) {
    // $scope.elementTd = function () {
    //     /**
    //      * 覆盖显示 右侧按钮
    //      * @type {NodeList}
    //      */
    //     var tr =  document.querySelector('#tbody').querySelectorAll('tr');
    //     var  block = document.getElementsByClassName('displayBlock');
    //     tr.forEach(function(item,index){
    //
    //         item.addEventListener('mouseover',function (event) {
    //             block[index].style.opacity = "1";
    //         });
    //         item.addEventListener('mouseleave',function (event) {
    //             block[index].style.opacity = "0";
    //         });
    //     })
    // };
    /**
     *后台私教课程管理 - 课程信息查询 - 访问PrivateTeach控制器的actionChargeClass()
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/10
     *  @return bool|string
     */
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

    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchCard();
    };
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

    $scope.getData = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            if(result.data.data == "" || result.data.data == undefined || result.data.data.length == undefined){
                $scope.datas    = result.data.data;
                $scope.pages    = result.data.pages;
                $scope.dataInfo = true;
            }else{
                $scope.datas    = result.data.data;
                $scope.pages    = result.data.pages;
                $scope.dataInfo = false;
            }
        });
    };
    $scope.delClass = function(id) {
        Sweety.remove({
            url              : "/private-teach/delete-data?classId="+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.getData();
        });
    };
    //分隔搜索时间
    $scope.formatDates    = function () {
        $scope.startTime =   $("#reservation").val().substr(0,10);
        $scope.endTime   =   $("#reservation").val().substr(-10,10);
    };
    /**处理搜索数据***/
    $scope.searchCardData = function () {
        $scope.formatDates();
        return {
            keywords       : $scope.keywords  != undefined  ? $scope.keywords : null,
            venueId        : $scope.venueId    != undefined ? $scope.venueId : null,
            startTime      : $scope.startTime != undefined  ? $scope.startTime : null,
            endTime        : $scope.endTime   != undefined  ? $scope.endTime : null,
            sortType       : $scope.sortType  != undefined  ? $scope.sortType : null,
            sortName       : $scope.sort      != undefined  ? $scope.sort :null
        }
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchDatas();
        $scope.init();
    };
    
    $scope.searchDatas = function () {
        $scope.keywords  = null;
        $scope.venueId   = null;
        $scope.dateTime  = null;
        $scope.startTime = null;
        $scope.endTime   = null;
    };

    $scope.initPath = function () {
        $scope.searchParams =  $scope.searchCardData();
        $scope.pageInitUrl =  '/private-teach/charge-class?' + $.param($scope.searchParams);
    };

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
    /**搜索方法***/
    $scope.searchCard = function () {
        $scope.initPath();
        $scope.getData();
    };

    /******Enter键搜索*******/
    $scope.enterSearch = function(e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.searchCard(); }
    };

    /**修改状态**/
    $scope.editStatus = function (id,$index,text) {
        $http.get("/private-teach/edit-class-status?id="+id+"&text="+text).then(function (result) {
            if(result.data.status == 'success'){
                Message.success(result.data.data);
                $scope.datas[$index].status = result.data.edit;
            }else{
                Message.warning(result.data.data[0])
            }
        });
    };
    $scope.init();

});
















