/**
 * 公共管理 - 属性匹配 - 会员卡属性匹配
 * @author zhujunzhe@itsports.club
 * @create 2018/1/31 am
 */
angular.module('App').controller('cardTypeCtrl',function($scope,$http){
    /******初始化方法*******/
    $scope.init = function () {
        $scope.pageInitUrl = '/card-type/card-info';
        $scope.initPath();
        $scope.getVenueOptions();//获取登录角色有权限的场馆
        $scope.getData();// 获取卡种匹配列表信息
    };
    //初始化变量
    $scope.memberCardId = '';
    //获取列表
    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).success(function (response) {
            // console.log('card',response);
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.datas  = response.data;
                $scope.pages =  response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.datas  = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide();

        });
    };
    $.loading.hide();

    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.venueId = null;
        $scope.type = null;
        $scope.keywords = null;
        $scope.attributes = null;
        $scope.status = null;
        $scope.isCheck    = null;
    };
    //分页
    $scope.replacementPages = function (urlPages) {
        $.loading.show();
        $scope.pageInitUrl = urlPages;
        $scope.getData();
    };
    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pageInitUrl =  '/card-type/card-info?' + $.param($scope.searchParams) + '&page=' + value;
            $scope.getData();
        }
    };

    // 数据整理
    $scope.searchCardData = function () {
        return {
            keywords    :$scope.keywords   != undefined  ? $scope.keywords : null,
            venueId     :$scope.venueId    != undefined  ? $scope.venueId : null,
            type        :$scope.type       != undefined  ? $scope.type : null,
            attribute   :$scope.attributes != undefined  ? $scope.attributes : null,
            isCheck     : $scope.isCheck   != undefined && $scope.isCheck  != false ? $scope.isCheck : null,
            status      :$scope.status     != undefined  ? $scope.status : null
        }
    };
    $scope.initPath = function () {
        $scope.searchParams = $scope.searchCardData();
        $scope.pageInitUrl = '/card-type/card-info?' + $.param($scope.searchParams);
    };

    //获取用户有权限的场馆
    $scope.getVenueOptions = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if (result.data != undefined && result.data != "") {
                $scope.optionVenue = result.data;
                $scope.VenueStauts = true;
            } else {
                $scope.VenueStauts = false;
                $scope.VenueStautsLength = true;
                $scope.optionVenue = '暂无数据';
            }
        });
    };

    /**搜索方法***/
    $scope.searchCard = function () {
        $.loading.show();
        $scope.initPath();
        $scope.getData();
    };

    /******Enter键搜索*******/
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $.loading.show();
            $scope.searchCard();
        }
    };
    $scope.init();

    //获取赠送的私课课程
    $scope.getGiveCourseData = function(){
        $http.get('/user/get-course?courseType=5').then(function(response){
            $scope.GiveCourseDataLists = response.data.data;
        });
    };
    //获取卡种所属场馆
    $scope.getCardTheVenue = function(){
        $http.get('/member-card/get-venue-data-by-id').then(function(response){
            $scope.cardTheVenueLists = response.data.data;
        });
    };
    //获取所有团课课种信息
    $scope.groupCourse  = function(){
        $scope.leagueAllListsArr = [];
        $http.get('/new-league/top-course').then(function (result) {
            $scope.optionClass = result.data;
            var $allleague = result.data;
            $allleague.forEach(function(item,index){
                var object = {
                    id:item.id,
                    text:item.name
                };
                $scope.leagueAllListsArr.push(object)
            });
        });
    };
    //获取赠送商品信息
    $scope.getDonationOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            $scope.optionDonation = result.data.goods;
        });
    };
    //点击列表事件
    $scope.getCardDetail = function(id){
        if(!id) {
            Message.warning('系统错误请联系工作人员！');
        }else {
            $.loading.show();
            $scope.dealId = '';
            $scope.dataDeal = '';
            $scope.theData = '';
            $http.get("/member-card/get-card-detail?id="+id).then(function (result) {
                $scope.theData = result.data;
                $scope.leaveLongIimit = angular.fromJson($scope.theData.leave_long_limit);
                $scope.studentLeaveLimit = angular.fromJson($scope.theData.student_leave_limit);
                if ($scope.theData.student_leave_limit != null && $scope.theData.student_leave_limit != '' && $scope.theData.student_leave_limit != undefined){
                    $scope.summer     = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[0] != undefined ? $scope.studentLeaveLimit[0] : null ;
                    $scope.winter     = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[1] != undefined ? $scope.studentLeaveLimit[1] : null ;
                    $scope.summerEdit = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[0] != undefined ? $scope.studentLeaveLimit[0] : null ;
                    $scope.winterEdit = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[1] != undefined ? $scope.studentLeaveLimit[1] : null ;
                }
                $scope.categoryTypeId = result.data.category_type_id;
                $scope.validLists = angular.fromJson(result.data.validity_renewal);
                if($scope.theData.cardTime != null){
                    $scope.theData.cardTime.day  = angular.fromJson($scope.theData.cardTime.day);
                    $scope.theData.cardTime.week = angular.fromJson($scope.theData.cardTime.week);
                    $scope.weekStartTimeListsArr = [];
                    if($scope.theData.cardTime.week.startTime != null && $scope.theData.cardTime.week.startTime != '' &&$scope.theData.cardTime.week.startTime != undefined){
                        var len = $scope.theData.cardTime.week.startTime.length;
                        for(var i=0;i<len;i++){
                            $scope.weekStartTimeListsArr.push(i);
                        }
                    }
                }else{
                    $scope.weekStartTimeListsArr = [];
                }
                $.loading.hide();
            });
        }
        $scope.getGiveCourseData();//获取私课课程
        $scope.getCardTheVenue();//获取所属场馆
        $scope.groupCourse();//获取所有团课
        $scope.getDonationOptions();//获取所有赠品
        $('#cardTypeModal').modal('show');
    };
    // $scope.cardTypeDetail = function(id){
    //     $('#cardTypeModal').modal('show');
    //     if (id != 'xixi') {
    //         $http.get('/member-card/get-card-detail?id=').then(function (data) {
    //
    //         });
            // $http({method: 'get', url: '/member/member-card-details?memberCardId=' + id}).then(function (data) {
            //     $scope.memberCardDetails   = data.data.data;
            //     $scope.memberClassNameList = [];
            //     $scope.memberClassNumList  = [];
            //     var $classArr    = $scope.memberCardDetails.cardCategory.class;
            //     var $classNumArr = $scope.memberCardDetails.cardCategory.bindPack;
            //     if($classArr != undefined && $classArr.length > 0) {
            //         angular.forEach($classArr,function (data){
            //             $scope.memberClassNameList.push(data.name);
            //         });
            //         $scope.memberClassNameList = $scope.memberClassNameList.join("/");
            //     }
            //
            //     if($classNumArr != undefined && $classNumArr.length > 0){
            //         angular.forEach($classNumArr,function (data){
            //             var p = data.number;
            //             if(data.number == '-1'&& data.polymorphic_type == "class"){
            //                 p = "不限"
            //             }
            //             else if(data.number == null && data.polymorphic_type == "class"){
            //                 p = ""
            //             }
            //             else if(data.number > '0'&& data.polymorphic_type == "class"){
            //                 p = data.number;
            //             }
            //             $scope.memberClassNumList.push(p);
            //         });
            //         $scope.memberClassNumList = $scope.memberClassNumList.join("");
            //     }
            //     if ($scope.memberCardDetails.cardTime != null && $scope.memberCardDetails.cardTime.day) {
            //         var qqqq = $scope.memberCardDetails.cardTime.day;
            //         $scope.cardTimeDay = JSON.parse(qqqq);
            //     }
            //
            //     if ($scope.memberCardDetails.cardTime != null && $scope.memberCardDetails.cardTime.week) {
            //         var qqqq = $scope.memberCardDetails.cardTime.week;
            //         $scope.cardTimeDay1 = JSON.parse(qqqq);
            //     }
            //
            //     if ($scope.memberCardDetails.leave_total_days == null) {
            //         var awww = $scope.memberCardDetails.leave_days_times;
            //         $scope.leaveLongIimit = JSON.parse(awww);
            //     }
            // }, function (error) {
            //     Message.error("系统错误请联系管理人员")
            // });
    //     }
    // };
    $scope.cardCateGoryArray = function(cardCategoryId){
        $scope.cardCategoryId = cardCategoryId;
    };
    $scope.matchCardType1   = false;
    $scope.matchCardType2   = false;
    $scope.matchWithPeople  = false;
    $scope.matchVenueLimit  = false;
    $scope.matchTimeLimit   = false;
    $scope.matchGroupClass  = false;
    $scope.matchLeave       = false;
    $scope.matchGift        = false;
    $scope.matchTransfer    = false;
    $scope.matchContract    = false;
    $scope.matchValidityRenew    = false;
    //点击属性匹配按钮
    $scope.matching = function(id){
        $scope.matchCardType1   = false;
        $scope.matchCardType2   = false;
        $scope.matchWithPeople  = false;
        $scope.matchVenueLimit  = false;
        $scope.matchTimeLimit   = false;
        $scope.matchGroupClass  = false;
        $scope.matchLeave       = false;
        $scope.matchGift        = false;
        $scope.matchTransfer    = false;
        $scope.matchContract    = false;
        $scope.matchValidityRenew    = false;
        $scope.chooseVenue = '';
        $scope.card = '';
        $scope.note = '';
        $scope.memberCardId = id;
        $scope.checkButton = false;
        $('#matchingModal').modal('show');
    };
    //选择场馆改变事件
    $scope.chooseVenueChange = function () {
        $scope.card = '';
        $http.get('/sell-card/card-category?venueId=' + $scope.chooseVenue).success(function (data){
            $scope.getVenueCardItems = data;
        });
    };
    //单击选择卡种验证
    $scope.isChooseVenue = function () {
        if(!$scope.chooseVenue) {
            Message.warning('请先选择所属场馆！');
        }
    };
    //点击属性匹配模态框完成按钮
    $scope.successBtn = function(){
        $scope.mateChooseArr = [];
        for(var i = 0; i < $(".chooseCardType>div>label>input").length ; i++) {
            if($(".chooseCardType>div>label>input").eq(i).is(':checked')) {
                $scope.mateChooseArr.push(i+1);
            }
        }
        $scope.matchingData = function () {
            return {
                oldCardId      : $scope.memberCardId   != undefined && $scope.memberCardId   != "" ? $scope.memberCardId   : null, //会员卡id
                cardCategoryId : $scope.cardCategoryId != undefined && $scope.cardCategoryId != "" ? $scope.cardCategoryId : null, //选择卡种id
                note           : $scope.note           != undefined && $scope.note           != "" ? $scope.note           : null, //备注
                checkArrId     : $scope.mateChooseArr,//匹配的属性的id(选中的复选框的id)的数组
                _csrf_backend: $('#_csrf').val()
            }
        };

        // 保存数据之前数据验证
        if(!$scope.chooseVenue) {
            Message.warning('请先选择所属场馆！');
            return ;
        }
        if ($scope.cardCategoryId == null || $scope.cardCategoryId == "" || $scope.cardCategoryId == undefined) {
            Message.warning("请选择卡种!");
            return false;
        }
        if(!$scope.mateChooseArr || (Object.prototype.toString.call($scope.mateChooseArr)==='[object Array]'&&$scope.mateChooseArr.length===0))            {
            Message.warning("请选择需要匹配的属性!");
            return false;
        }
        $scope.checkButton = true;
        // 发送数据
        $http({
            url: "/card-type/attribute-matching",
            method: 'POST',
            data: $.param($scope.matchingData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            console.log(data);
            if (data.data.status == "success") {
                Message.success("保存成功");
                $scope.getData();
                $('#matchingModal').modal('hide');
                $scope.cardCategoryId = '';
                $scope.note = '';
                $scope.mateChooseArr = [];
            }else {
                Message.warning(data.data.data);
            }
            $scope.checkButton = false;
        })
    };

    //点击匹配记录按钮
    $scope.record = function(id){
        $scope.memberCardId = id;
        $('#recordModal').modal('show');
        $scope.httpUrl = '/card-type/matching-record?id=' + $scope.memberCardId;
        $scope.getRecordList($scope.httpUrl);
    };

    //获取记录
    $scope.getRecordList = function (urlPages) {
        $http.get(urlPages).then(function (data) {
            if(data.data.data != undefined && data.data.data != null && data.data.data.length > 0) {
                $scope.mateRecordNoData = false;
                $scope.recordList = data.data.data;
                $scope.replaceMatchingPage = data.data.pages;
            }else {
                $scope.mateRecordNoData = true;
                $scope.recordList = data.data.data;
                $scope.replaceMatchingPage = data.data.pages;
            }
        })
    };

    //分页函数
    $scope.replaceMatchingPages = function (urlPages) {
        $scope.getRecordList(urlPages);
    };
});
