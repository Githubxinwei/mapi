angular.module('App').controller('groupClassCtrl',function ($scope,$rootScope,$http,Upload) {
    $.loading.show();                                       //打开页面
    $scope.init                  = function () {
        $scope.coachName     = "";             //教练名称
        $scope.venueIds      = "";             //场馆id
        $scope.depId         = "";             //部门id
        $scope.position      = "";             //职位
        $scope.mobile        = "";             //手机号
        $scope.email         = "";             //邮箱
        $scope.salary        = "";             //薪资
        $scope.intro         = "";             //个人简介
        $scope.getVenueOptions();                               //查询所有场馆
        $scope.getClassOptions();                               //查询所有课程
        $scope.coach();                                         //查询所有教练
        $scope.search();                                        //查询所有教室
        $scope.getDepartment();                                 //查询所有部门
        setTimeout("$.loading.hide()",1000);                    //隐藏页面

    };
    //添加团课获取场馆信息
    $scope.getVenueOptions       = function () {
        $http.get('/rechargeable-card-ctrl/get-venue').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionVenue = result.data.venue;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.optionVenue = '暂无数据';
            }
        });
    };
    //场馆关联查寻所属教室
    $scope.search                = function(venueIds){
        var     vId                       =  venueIds;
        $scope.classroom = "";
        if(vId != undefined){
            $http.get('/league/venue-detail?venueId='+vId).then(function (result) {
                if(result.data && result.data != ""){
                    $scope.classRoom       = result.data;
                    $scope.classRoomStauts = true;
                }else{
                    $scope.classRoomStauts = false;
                    $scope.classRoom       = '暂无数据';
                }
            });
        }else{
            $scope.classRoomStauts = false;
            $scope.classRoom       = '请先选择场馆';
        }
    };
    //座位数
    $scope.setTotalSeat          = function (room) {
        var rooms = angular.fromJson(room);
        if(rooms.total_seat == null){
            $scope.totalSeat = 30;                                  //判断座位表中的座位是否为空，给默认值
        }else{
            $scope.totalSeat   = rooms.total_seat;
        }
        $scope.classroomId = rooms.id;
    };
    //获取部门信息
    $scope.getDepartment         = function (venueId) {
        if(venueId != undefined){
            $http.get('/league/get-venue?venueId='+venueId).then(function (result) {
                if(result.data.venue != undefined && result.data.venue != ""){
                    $scope.Department       = result.data.venue;
                    $scope.departmentStauts = true;
                }else{
                    $scope.departmentStauts = false;
                    $scope.Department       = '暂无数据';
                }
            });
        }else{
            $scope.departmentStauts         = false;
            $scope.Department               = '请先选择场馆';
        }
    };
    //验证手机号是否存在
    $scope.getMobile            = function (mobile) {
        if(mobile != undefined){
            $http.get('/league/get-mobile-info?mobile='+mobile).then(function (result) {
                if(result.data.status == 'error'){
                    $scope.mobileInfo = true;
                }else{
                    $scope.mobileInfo = false;
                }
            });
        }
    };
    //接收新添加教练信息
    $scope.getCoachInfo          = function () {
        return{
            coachName          : $scope.coachName,             //教练名称
            depId              : $scope.depId,                 //部门id
            position           : $scope.position,              //职位
            mobile             : $scope.mobile,                //手机号
            email              : $scope.email,                 //邮箱
            salary             : $scope.salary,                //薪资
            intro              : $scope.intro,                 //个人简介
            _csrf_backend      : $('#_csrf').val()
        }
    };
    //保存教练信息方法
    $scope.setCoachData          = function () {
        $scope.coachParams = $scope.getCoachInfo();
        $http({
            url        : '/league/set-coach-data',
            method     : 'POST',
            data       :  $.param($scope.coachParams),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                 Message.success('添加成功');
                 // location.href = '/league/add';
                $scope.init();
                    $('.modal').hide();              //关闭模态框
                    $('.modal-backdrop').hide();     //关闭模态框遮罩层
            }
        });
    }
    //获取课程信息（最低级的课程）
    $scope.getClassOptions       = function () {
        $http.get('/class/group-class-type?type=2').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.classStauts = true;
                $scope.optionClass = result.data;
            }else{
                $scope.classStauts = false;
                $scope.optionClass = '暂无数据';
            }
        });
    };
    //获取所有教练数据
    $scope.coach                 = function(){
        $http.get('/league/coach').then(function (result) {
            if(result.data && result.data != ""){
                $scope.classCoach = result.data;
                $scope.coachStauts = true;
            }else{
                $scope.coachStauts = false;
                $scope.classCoach = '暂无数据';
            }
        });
    };

    //公共判断方法
    $scope.commonProof           = function (data) {
        if(data == undefined || data == null){
            return false;
        }else{
            return true;
        }
    };
    //接收团课第一步数据
    $scope.getGroupDataOne       = function () {
        return{
            className       : $scope.className,             //课程名
            classCoach      : $scope.coachId,               //教练名
            classRoom       : $scope.classroomId,           //教室
            venueId         : $scope.venueId,               //场馆
            difficulty      : $scope.difficulty,            //课程难度
            classDesc       : $scope.desc,                  //课程描述
            classPic        : $scope.pic,                   //课程图片
            scenario        : $scope.scenario,             //操作步骤
            _csrf_backend   : $('#_csrf').val()
        }
    }
    //接收课程安排时间
    $scope.getClassTime          = function () {
        var div                                        =  $('div.groupAddDate');           //获取时间div块的元素对象
        var    dateStartTime                           =  [];
        $scope.dateTimeArr                             =  [];               //接收课程所有日期
        if($scope.commonProof(div)){
            div.each(function (i) {
                var $self                              =  $(this);
                var dateStart                          =  $self.find('input.datetimeStart').val();    //获取开始日期
                var dateEnd                            =  $self.find('input.datetimeEnd').val();      //获取结束日期
                dateStartTime.push(dateStart);
                dateStartTime.push(dateEnd);
                var input                              =  $self.find('div.groupTime');                //开始结束点时间元素对象
                var startArr = [];
                var endArr   = [];
                input.each(function (n) {
                    var times                          =  $(this);
                    var start                          =  times.find('input.start').val();            //获取具体开始时间
                    var end                            =  times.find('input.end').val();              //获取具体结束时间
                    startArr.push(start);
                    endArr.push(end);
                });
                dateStartTime.push(startArr);
                dateStartTime.push(endArr);
                $scope.dateTimeArr.push(dateStartTime);                                                 //日期数组
                dateStartTime                          = [];
            });
        }
    };

    $scope.init();
    //接收团课第二步数据
    $scope.getGroupDataTwo       = function () {
        $scope.getClassTime();
        return{
            classDate           : $scope.dateTimeArr,              //上课开始日期
            classLimitTime      : $scope.classLimitTime,           //上课前多长时间不能预约
            cancelLimitTime     : $scope.cancelLimitTime,          //上课前多长时间不能取消预约
            leastPeople         : $scope.leastPeople,              //开课最少人数
            scenario            : $scope.scenario,                 //操作步骤
            _csrf_backend       : $('#_csrf').val()
        }
    };
    //接收混合卡取消操作数据
    $scope.getGroupDataCancel    = function () {
        return {
            scenario           : $scope.scenario,                                        //操作步骤
            _csrf_backend      : $('#_csrf').val()
        }
    }
    //上传图片大小判断
    $scope.setCover              = function (file) {
        if(file){
            if(file.size > 2000000){
                Message.warning('图片太大');
            }else{
                $scope.setPic(file);
            }
        }
    }
    //图片上传方法
    $scope.setPic                = function (file) {
        Upload.upload({
            url    : '/league/upload',
            method : 'POST',
            data   : {UploadForm:{imageFile:file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result){
            if(result.data.status == 'success'){
                $scope.pic  = result.data.imgLink;
            }else{
                Message.warning(result.data.data);
            }
        })
    };
    //后台交互方法
    $scope.setGroupCtrHttp       = function () {
        $http({
            url        : '/league/group-class-rule',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                Message.success('添加成功');
                location.href = '/league/index';
            }else if(result.data.status == 'cancel'){
                Message.success('取消成功');
                // setTimeout("window.location.replace('/member-card/index')",100);
            } else if(result.data.status == 'repeat'){
                    Message.warning(result.data.data);
            }
        });
    };
    //公工提示方法
    $scope.commonGroupRule       = function (attr,text) {
        if(!attr){Message.warning(text);return false;}return true;
    };
    //公共时间数组
    $scope.commonGroupDateRule   = function (attr) {
        var len = attr.length;
        for(var i=0;i<len;i++){
            var length = attr[i].length;
            for(var k=0;k<length;k++){
                if(!attr[i][0] || attr[i][0] == ""){
                    $scope.commonGroupRule('','您还没选择开始日期');return false;
                }
                if(!attr[i][1] || attr[i][1] == ""){
                    $scope.commonGroupRule('','您还没选择结束日期');return false;
                }else if(attr[i][0] || attr[i][0] != "" && attr[i][1] || attr[i][1] != ""){
                    if(!$scope.getDateMaxDay(attr[i][0],attr[i][1])){
                        $scope.commonGroupRule('','排课日期限制1个月内');return false;
                    }else{
                        var lengths = attr[i][2].length;
                        $scope.startArr = [];       //开始时间存放数组
                        $scope.endArr   = [];       //结束时间存放数组
                        for(var m=0;m<lengths;m++){
                            if(!attr[i][2][m] || attr[i][2][m] == ""){
                                $scope.commonGroupRule('','请选择上课时间');return false;
                            }else if(!attr[i][3][m] || attr[i][3][m] == ""){
                                $scope.commonGroupRule('','请选择下课时间');return false;
                            }else{
                                if(m >= 1 ){
                                    $scope.startArr.push(attr[i][2][m-1]);       //把开始时间存到数组中（不存最后一次的）
                                    $scope.endArr.push(attr[i][3][m-1]);         //把接收时间存到数组中(不存最后一次的)
                                }
                                if(!$scope.findDay(attr[i][0],attr[i][2][m])){
                                    $scope.commonGroupRule('','请不要选择当天以前的时间');return false;
                                }else if(!$scope.getDateTime(attr[i][0],attr[i][2][m],attr[i][3][m])){
                                    $scope.commonGroupRule('','上课时间段的下课时间不正确');return false;
                                }
                            }
                            if(lengths >= 2){
                                var startC = $.inArray(attr[i][2][m],$scope.startArr);
                                var endC   = $.inArray(attr[i][3][m],$scope.endArr);
                                if(startC != -1 && endC != -1){
                                    $scope.commonGroupRule('','你选的上课时间段重复了');return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    };
    //判断排课时间是不是当天之前
    $scope.findDay               = function (dateStart,start) {
        var startTime   = dateStart +" "+ start;                    //拼接开始日期
        var startTimes  = Date.parse(new Date(startTime));          //开始日期格式化时间戳
        startTimes = startTimes / 1000;
        var d = new Date();                                               //获取当前时间戳
        var dates = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes(); //获取当前时间的时间日期
        var dayTime = Date.parse(new Date(dates))/1000;                   //转换成时间戳
        if(startTimes >= dayTime){
            return true;
        }else{
            return false;
        }
    };
    //限制选课天数最长31天
    $scope.getDateMaxDay         = function (startDay,endDay) {
        var startTimes  = Date.parse(new Date(startDay));          //开始日期格式化时间戳
        startDay = startTimes / 1000;
        var endTimes     = Date.parse(new Date(endDay));           //结束日期格式化时间戳
        endDay = endTimes / 1000;
        var dayTimes = endDay - startDay;                          //求出时间戳之差(31天的时间戳：2592000)
        if(dayTimes>2592000){                                      //时间戳比较
            return false;
        }else{
            return true;
        }
    };
    //把日期转换成时间戳(判断开始时间点是否合法)
    $scope.getDateTime           = function (dateStart,start,end) {
        var startTime   = dateStart +" "+ start;                    //拼接开始日期
        var endTime     = dateStart +" "+ end;                      //拼接结束日期
        var startTimes  = Date.parse(new Date(startTime));          //开始日期格式化时间戳
        startTimes = startTimes / 1000;
        var endTimes     = Date.parse(new Date(endTime));           //结束日期格式化时间戳
        endTimes    = endTimes / 1000;
       if(startTimes >= endTimes){
          return false;
       }else{
          return true;
       }
    };
    //检测描述字数是否超出长度
    $scope.getDescLength         = function (attr,text) {
       var length = attr.length;
        if(length >= 199){
            $scope.commonGroupRule('',text);return false;
        }else{
            return true;
        }
    }
    //第一步数据检测方法
    $scope.oneGroupClassRule     = function (data) {
        if(!$scope.commonGroupRule(data.className,'请选择课程')){return false;}
        if(!$scope.commonGroupRule(data.classCoach,'请选择教练')){return false;}
        if(!$scope.commonGroupRule(data.venueId,'请选择场馆')){return false;}
        if(!$scope.commonGroupRule(data.classRoom,'请选择教室')){return false;}
        // if(!$scope.commonGroupRule(data.difficulty,'请选择课程难度')){return false;}
        // if(!$scope.commonGroupRule(data.classDesc,'请填写课程描述')){return false;}
        // if(!$scope.getDescLength(data.classDesc,'描述字数过多')){return false;}
        // if(!$scope.commonGroupRule(data.classPic,'请上传图片')){return false;}
        return true;
    }
    //第二步数据检测方法
    $scope.twoGroupClassRule     = function (data) {
        if(!$scope.commonGroupDateRule(data.classDate)){return false;}                                  //判断课程时间
        if($scope.totalSeat < data.leastPeople){Message.warning('人数不能超过教室座位数');return false;}
        return true;
    };

    //公共验证方法
    $scope.setGroupHttp          = function (data,attr) {
        $scope.params  = data;
        if(attr == 'one')
        {
            if(!$scope.oneGroupClassRule(data)){return false;}
        }else if(attr == 'two'){
            if(!$scope.twoGroupClassRule(data)){return false;}
        }
        return true;
    };
    //操作步骤赋值，和调取各步骤表单方法
    $rootScope.onGroupStepChange = function (newIndex) {
        if(newIndex == 1){
            $scope.scenario    = 'one';
            $scope.OneParams   = $scope.getGroupDataOne();
            if(!$scope.setGroupHttp($scope.OneParams,$scope.scenario)){return false;}
        }else if(newIndex == 2){
            $scope.scenario    = 'two';
            $scope.TwoParams   = $scope.getGroupDataTwo();
            $scope.params       = $scope.TwoParams;
            if(!$scope.setGroupHttp($scope.TwoParams,$scope.scenario)){return false;}
        }else if(newIndex == 'cancel'){
            $scope.scenario     = 'cancel';
            $scope.paramsCancel = $scope.getGroupDataCancel();
            $scope.params       = $scope.paramsCancel;
        }
        $scope.setGroupCtrHttp();
        return true;
    }

}).run(function ($rootScope) {
    $rootScope.onGroupClassChanging = function () {
     return  {
            headerTag        : "h3",
                bodyTag      : "section",
            transitionEffect : "none",
         //1.每一步表单验证
         onStepChanging: function (event, currentIndex, newIndex) {
             if(!$rootScope.onGroupStepChange(newIndex))
             {
                 return false;
             }
             return true;
         },
         //2.每一步提交数据
         onStepChanged: function (event, currentIndex, priorIndex) {
         },
         //3点击取消页面时跳转页面
         onCanceled:function (event) {
             $rootScope.onGroupStepChange('cancel');
             location.href = '/league/index';
        },
         //4.点击完成按钮时跳转页面
         onFinished:function (event, currentIndex) {
             var index = currentIndex + 1;
             if(index == 2){
                 $("ul[role='menu']").find("a[href='#finish']").attr('href','javascript:void(0)');
             }
             // if(index == 2){location.href = '/league/index';}
         },
         //5.完成前验证
         onFinishing: function (event, currentIndex) {
             var index = currentIndex + 1;
             if(index == 2){
                 if(!$rootScope.onGroupStepChange(index))
                 {
                     return false;
                 }
             }
             return true;
         }
        }
    };
    var setting = $rootScope.onGroupClassChanging();
    //表单向导启动
    $("#example-async").steps(setting);
})