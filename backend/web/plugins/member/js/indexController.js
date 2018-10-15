var app = angular.module('App');
app.controller('indexController',function($scope,$http) {

    // angular.element(document).ready(function () {
    //     var spanBig = $('.spanBig').text();
    //     var spanSmall = $('.spanSmall').text();
    //     var getData = localStorage.getItem("initBehaviorTrajectoryId");
    //     var getItemData = JSON.parse(getData)
    //     for (var key in getItemData) {
    //         if (getItemData.hasOwnProperty(key)) {
    //             var element = getItemData[key];
    //             for(var i in element){
    //                 if (element.hasOwnProperty(i)){
    //                     var elementModule = element[i];
    //                     if(elementModule.name == spanBig){
    //                         for(var a in elementModule.module){
    //                             if (elementModule.module.hasOwnProperty(a)){
    //                                 var elementSmallModule = elementModule.module[a];
    //                                 if (elementSmallModule.name == spanSmall){
    //                                     var id  =  elementSmallModule.id
    //                                     localStorage.setItem("moduleId",JSON.stringify({
    //                                         id:id
    //                                     }))
    //                                     return
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // })
    // var getModuleId = localStorage.getItem("moduleId");
    // $scope.moduleId = JSON.parse(getModuleId).id
    // $httpBehaviorTrajectory.http(1,$scope.moduleId,"约课管理")
    $scope.init = function () {
        $scope.pathApi ='/member/get-about-data?';
        $scope.initPath();
        $scope.getAboutClass();
        $scope.getVenueOptions();   //获取场馆
        $scope.getClassOptions();   //获取团课课程
    };
    //添加团课获取场馆信息
    $scope.getVenueOptions       = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.VenueStautsLength = true;
                $scope.optionVenue = '暂无数据';
            }
        });
    };

    //获取课程信息
    $scope.getClassOptions       = function () {
        $http.get('/rechargeable-card-ctrl/get-class-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != "" && result.data.venue != []){
                $scope.classStauts = true;
                $scope.optionClass = result.data.venue;
            }else{
                $scope.classStauts = false;
                $scope.optionClass = '暂无数据';
            }
        });
    };
    
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchAbout();
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
    $scope.searchDate= function (attr) {
        $scope.date = attr;
        $scope.searchAbout();
    };
    $scope.searchCheck = function () {
       var check = $('div.checkBigBox');
        check.each(function (i) {
            var self = $(this).children('div.icheckbox_square-green').hasClass('checked');
            var $this = $(this).children('div.icheckbox_square-green');
            // console.log('axa',self);
                var $name = $this.find('input').attr('name');
                if($name == 'notStart'){
                    if(self) {
                        $scope.notStart = true;
                    }else{
                        $scope.notStart = null;
                    }
                }else if($name == 'attendClass'){
                    if(self) {
                        $scope.attendClass = true;
                    }else{
                        $scope.attendClass = null;
                    }
                }else{
                    if(self) {
                        $scope.finish = true;
                    }else{
                        $scope.finish = null;
                    }
                }
        });
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchClearData();                   //清空搜索
        $scope.init();
        // window.location.href = '/member/index?mid=5&c=1';
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        // $scope.$parent.venueId   = '';
        // $scope.$parent.course_id = '';
        $scope.course_id          = '';            //课程搜索
        $scope.venueId            = '';            //场馆搜索
        $scope.keywords           = null;
        $('#select2-AboutClassVenue123-container').text('请选择场馆');
        $('#select2-AboutClassCourse-container').text('请选择课程');
        var check = $('div.checkBigBox');           //复选框搜索
        check.each(function (i) {
            var self = $(this).children('div.icheckbox_square-green').hasClass('checked');
            var $this = $(this).children('div.icheckbox_square-green');
            if(self === true){
                $this.removeClass('checked');       //移除复选框选中属性
            }
        });
        $("#reservation").val("");
    };
    //分隔搜索时间
    $scope.formatDates    = function () {
        $scope.startTime         =   $("#reservation").val().substr(0,10);
        $scope.endTime           =   $("#reservation").val().substr(-10,10);
    };
    /**处理搜索数据***/
    $scope.searchCardData = function () {
        $scope.searchCheck();
        $scope.formatDates();
        return {
            keywords      : $scope.keywords    != undefined ? $scope.keywords : null,
            venueId       : $scope.venueId   != undefined ? $scope.venueId : null,               //场馆搜索
            course_id     : $scope.course_id   != undefined ? $scope.course_id : null,           //课程搜索
            notStart      : $scope.notStart    != undefined ? $scope.notStart : null,            //课程未开始
            attendClass   : $scope.attendClass != undefined ? $scope.attendClass : null,         //课程正在上
            finish        : $scope.finish      != undefined ? $scope.finish : null,              //课程已结束
            startTime     : $scope.startTime   != "" ? $scope.startTime : null,                  //开始日期
            endTime       : $scope.endTime     != "" ? $scope.endTime : null,                    //结束日期
            sortType      : $scope.sortType    != undefined ? $scope.sortType : null,
            sortName      : $scope.sort        != undefined ? $scope.sort : null,
            date          : $scope.date        != undefined ? $scope.date : null
        }
    };
    //搜索数据和路径拼接
    $scope.initPath = function () {
        $scope.searchParams =  $scope.searchCardData();
        // console.log('aaa',$scope.searchParams);
        $scope.pathApi =  '/member/get-about-data?' + $.param($scope.searchParams);
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pathApi = '/member/get-about-data?'+ $.param($scope.searchParams) + '&page='+ value;
            $scope.getAboutClass();
        }
    };

    $scope.getAboutClass= function () {
        $.loading.show();
        $http.get($scope.pathApi).then(function (result) {
            $scope.items = result.data.data;
            // console.log('返回数据',result)
            if($scope.items == undefined || $scope.items == ''){
                $scope.dataInfo = true;
            }else{
                $scope.dataInfo = false;
            }
            $scope.pages = result.data.pages;
            $scope.searchCarding = false;
            $.loading.hide();
        });
    };
    /****卡种分页***/
    $scope.replacementPages = function (urlPages) {
        $scope.pathApi = urlPages;
        $scope.getAboutClass();
    };
    //点击搜索触发事件
    $scope.searchAbout = function () {
        $scope.initPath();                  //搜索数据和访问路径
        $scope.getAboutClass();             //和后台交互方法
    };
    /**
     * 按回车键进行搜索
     */
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.searchAbout();
        }
    };
    //获取点击的课程约课详情
    $scope.aboutDetail = function (id) {
        $scope.seatDetailRows = [];
       $http.get('/member/get-about-detail?id='+id).then(function (result) {
           // console.log('result',result)
           $scope.detailObj = result.data;
           var rows = result.data.seatTypeS.total_rows;
           for(var i=1;i<=rows;i++){
               $scope.seatDetailRows.push(i)
               // console.log($scope.seatDetailRows)
           }
           if($scope.detailObj.aboutClass == null || !$scope.detailObj.aboutClass.length){
               $scope.aboutNoSeat = true;
               // console.log($scope.aboutNoSeat)
           }else{
               $scope.aboutNoSeat = false;
               // console.log($scope.aboutNoSeat)
           }
       });  
    };
    $scope.init();
    angular.element(document).ready(function () {
        $(".js-example-basic-single1").select2();
        $(".js-example-basic-single3").select2();
    })
});

























