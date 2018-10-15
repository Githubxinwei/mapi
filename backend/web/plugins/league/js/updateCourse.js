//团课修改页面控制器
var app = angular.module('App');
app.controller('updateCourseCtrl',function($scope,$http,Upload){
    /**
     * 后台 - 团课修改 - 修改课程
     *@update author Hou kaiXin <<houkaixin@itsport.club>>
     *@update 2017/4/13
     */

    //获取团课课程单条信息(向表单添加默认信息)
    $scope.groupClass = function () {
        $http.get('/league/get-course').then(function (result) {
            $scope.myItem = result.data;
        })
    };

    //获取所有场馆数据
    // $scope.venue     = function(){
    //     $http.get('/league/venue').then(function (result) {
    //         $scope.venues = result.data;
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
   //获取所有教练数据
    $scope.coach     = function(){
        $http.get('/league/coach').then(function (result) {
            $scope.coach = result.data;
        })
    };
    //获取所有课种信息
    $scope.groupCourse  = function(){
        $http.get('/league/group-course').then(function (result) {
            $scope.groupCourse = result.data;
        })
    };


    //初始化所有所需数据（教室，场馆，教练）
    $scope.init = function(){
        $scope.groupClass();
        // $scope.venue();
        $scope.getVenueOptions();           //获取场馆
        $scope.coach();
        $scope.groupCourse();
    };


    $scope.init();

    //场馆关联查寻所属教室
    $scope.search = function(){
        var venueId   = $("#venue").val();
         $scope.myItem.classroom_id ='';
        $http.get('/league/venue-detail?venueId='+venueId).then(function (result) {
            $scope.myItem.classroom = result.data;
        });

    };
    //查询教室的座位数
    $scope.seat  = function (id) {
        var classroomId = id;
        $http.get('/league/class-room-seat?roomId='+classroomId).then(function (result) {
            $scope.myItem.total_seat = result.data.total_seat;
        })
    }
    //发送数据请求指令
      $scope.update = function(){
           $scope.courseData =function(){
               return {
                _csrf_backend   : $('#_csrf').val(),
                groupClassId    : $("#groupClassId").val(),        //团课课程id
                classDate       : $("#classDate").val(),            //上课日期
                courseId         :$scope.myItem.course_id,          //课程名称
                coachId          :$scope.myItem.coach_id,           //教练id
                classroomId      :$("#myClassroomId").val(),         //教室位置id
                difficulty       :$scope.myItem.difficulty,         //课程难度
                classDesc        : $scope.myItem.desc,              //课程描述
                courseStart      : $scope.myItem.start,             //上课开始前
                courseEnd        : $scope.myItem.end,                //上课结束时间
                classLimitTime   : $scope.myItem.class_limit_time,   //开课前多少分钟不能约课
                cancelLimitTime  : $scope.myItem.cancel_limit_time,  // 开课前多少分钟不能取消约
                leastPeople       :$scope.myItem.least_people,       //最少开课人数
                pic               : $scope.pic!=undefined?$scope.pic:null //获取上传图片
               }
           };
          //公工提示方法
          $scope.commonGroupRule       = function (attr,text) {
              if(!attr){Message.warning(text);return false;}return true;
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
          if(!$scope.getDescLength($scope.myItem.desc,'描述字数过多')){
              return false;
          }

          if(!$scope.myItem.start||!$scope.myItem.end){
              Message.warning("上课时间不能为空");
              return false;
          }
          if(!$scope.updateClassDate($scope.myItem.start,$scope.myItem.end))
          {
              $scope.commonGroupRule('','你选择的结束时间不合法');return false;
          }
          //开课前多少分钟不能预约课程不能为空
          // if(!$scope.myItem.class_limit_time){
          //     Message.warning("开课前多少分钟不能预约课程不能为空");
          //     return false;
          // }
          //开课前多少分钟不能取消约课不能为空
          // if(!$scope.myItem.cancel_limit_time){
          //     Message.warning("开课前多少分钟不能取消约课不能为空");
          //     return false;
          // }
          //最少开课人数
          // if(!$scope.myItem.least_people){
          //     Message.warning("最少开课人数");
          //     return false;
          // }
          // if(parseInt($scope.myItem.total_seat) < parseInt($scope.myItem.least_people)){
          //     Message.warning('人数不能超过教室座位数');
          //     return false;
          // }

          //发送客户端数据
          $http({
              url: "/league/update-data",
              method: 'POST',
              data: $.param($scope.courseData()),
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function () {
             window.location.replace('/league/index');
          });
      };
    //判断修改时间是否合法
    $scope.updateClassDate   = function (start,end) {
        var d = new Date();
        var dates = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate(); //获取当前时间的时间日期
        var startTime   = dates +" "+ start;                    //拼接开始日期
        var endTime     = dates +" "+ end;                      //拼接结束日期
        var startTimes  = Date.parse(new Date(startTime));          //开始日期格式化时间戳
        startTimes = startTimes / 1000;
        var endTimes     = Date.parse(new Date(endTime));           //结束日期格式化时间戳
        endTimes    = endTimes / 1000;
        if(startTimes >= endTimes){
            return false;
        }else{
            return true;
        }
    }
    $scope.uploadCover = function (file, text) {
        if (file) {
            if (file.size > 2000000) {
                Message.warning('文件太大不能上传')
            } else {
                $scope.upload(file, text);
            }

        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
        }
    };

    $scope.upload = function (file, text) {
        Upload.upload({
            url    : '/class/upload',
            method :'POST',
            data   : {UploadForm: {imageFile: file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                    $scope.pic = result.data.imgLink;
            } else {
                Message.warning(result.data.data);
            }
        });
    }

    //点击后加载 场馆信息，部门信息数据
    $scope.bombBox = function(){
        $scope.getVenueOptions();      //初始化场馆信息
        $scope.getDepartment();        //初始化部门信息
    };
    //获取场馆信息
    $scope.getVenueOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-venue?params='+angular.toJson($scope.venueHttp)).then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionVenue = result.data.venue;
            }else{
                // $scope.optionVenue = [
                //     {'id':1,'name':'大上海'},
                //     {'id':2,'name':'大卫城'},
                //     {'id':3,'name':'大学路'},
                //     {'id':4,'name':'花园路'}
                // ]
            }

        });
    };

    //获取部门信息
    $scope.getDepartment   = function (venueId) {
        if(venueId){
            $http.get('/league/get-venue?venueId='+venueId).then(function (result) {
                if(result.data.venue != undefined && result.data.venue != ""){
                    $scope.Department = result.data.venue;
                }
            });
        }
    };

    //接收新添加教练信息
    $scope.getCoachInfo  = function () {
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
    $scope.setCoachData  = function () {
        $scope.coachParams = $scope.getCoachInfo();
        $http({
            url        : '/league/set-coach-data',
            method     : 'POST',
            data       :  $.param($scope.coachParams),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                Message.success('添加成功');
                //执行关闭模态框
               // location.href = '/league/edit-course';
            }
        });
    }
});