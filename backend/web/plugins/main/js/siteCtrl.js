//组织管理中场地页面控制器
angular.module('App').controller('siteCtrl', function($scope,$http,Upload){
    $scope.items = [
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'},
        {companyName:'我爱运动',venue:'大上海城',site:'瑜伽',siteName:'A教室',numberPeople:'50人'}
    ];
    angular.element(document).ready(function () {
        $(".js-example-basic-single1").select2();
    });
    /**
     * 后台 - 组织架构管理 - 分页（组织架构主界面数据遍历）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */

    //初始化页面加载数据
    $scope.init = function () {
        $scope.pageInitUrl = '/main/classroom';
        $scope.getClassModel();
        $scope.classroom();
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };
    // 获取后台组织架构数据
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
     * 后台 - 组织架构管理 - 单条数据删除
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */
    $scope.del=function(id,name){
        Sweety.remove({
            url: "/main/del-classroom-data?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '删除后*教室座位*将全部被删除',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getClassModel();
        });
    };

    //获取场地信息
    $scope.classroom = function(){
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.venues = result.data;
                $scope.classroomsStauts = true;
            }else{
                $scope.classroomsStauts = false;
                $scope.classrooms = '暂无数据';
            }
        })
    };
    //执行回车搜索
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
        }
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.venueId1 = '';
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.classroomId     = null;
        $scope.topSearch       = null;
        $scope.courseName      = null;
        $scope.coachName       = null;
        $("#reservation").val("");
    };
    /**
     * 后台 - 组织架构管理 - 教室 - 执行主界面数据搜索
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */
    //接受搜索参数
    $scope.searchModel = function(){
        // var startTime = $("#reservation").val().split(' - ')[0];
        // var endTime   = $("#reservation").val().split(' - ')[1];
        return {
            topSearch    : $scope.topSearch    != undefined ? $scope.topSearch   : null,     //综合字段搜索
            venueId      : $scope.venueId1     != undefined && $scope.venueId1 != '' ? $scope.venueId1   : null,
            // startTime    : startTime            != undefined ? startTime           : null,    // 创建时间开始
            // endTime      : endTime              != undefined ? endTime             : null,     // 上课时间结束
            sortType     : $scope.sortType     != undefined ? $scope.sortType     : null,    //需要排序的字段名字
            sortName     : $scope.sort         != undefined ? $scope.sort         : null,    //排序的类型
            classroomId  : $scope.classroomId  != undefined ? $scope.classroomId : null,     // 教室id
            classrooms   : $scope.classrooms   != undefined ? $scope.classrooms  : null      //教室名字
        }
    };
    /**整理搜索参数以及搜索url**/
    $scope.initPath = function (){
        $scope.searchParams   =  $scope.searchModel();
        $scope.pageInitUrl    =  '/main/classroom?' + $.param($scope.searchParams);
    };
    //执行最终搜索
    $scope.search = function() {
        $scope.initPath();
        $scope.getClassModel();
    };
    /**
     * 后台 -组织架构管理 - 主界面各个字段排序
     *author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
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
    //初始化加载数据
    $scope.init();

    /**
     * 后台 - 组织架构管理 - 场地 执行数据修改
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/27
     */
    //点击的时候获取数据，同时将数据赋值文本框
    $scope.update=function(classroomId,venueId,companyId,classroomName,classroomArea,totalSeat,classroomDes,classroomPic){
        //初始化数据(点击修改按钮，select框获取值)
        $scope.updateInit = function(){
            $scope.topType();
            $scope.allVenues();
            $scope.myData();
        };
        //点击的时候参数赋值
        $scope.myData=function(){
            $scope.classroomId     = classroomId;
            $scope.venueId          = venueId;
            $scope.theCompanyId    = companyId;
            $scope.classroomName   = classroomName;
            $scope.classroomArea   = classroomArea;
            $scope.totalSeat       = totalSeat;
            $scope.classroomDes    = classroomDes;
            $scope.classroomPic    = classroomPic;
        };
        //查询顶级所属于公司
        $scope.topType = function(){
            $http.get("/main/company-check").then(function (result) {
                $scope.companyS = result.data;
            })
        };
        //查询默认公司下的场馆
        $scope.allVenues = function(){
            $http.get("/main/appoint-venues?pid="+companyId).then(function (result) {
                $scope.venueS= result.data;
            })
        };
        $scope.updateInit();
    };

    //根据公司查询场馆
    $scope.searchVenue = function(id){
        if($scope.companyId!=""){
            $http.get("/main/bel-venues?id="+id).then(function (result) {
                $scope.venueS    = result.data;
                $scope.venueId  ="";
            })
        }
    };
    $scope.updateClassroom=function(){
        //整理发送后的数据
        $scope.updateMyData = function () {
            return {
                _csrf_backend        :$('#_csrf').val(),
                classroomId          :$scope.classroomId       != undefined &&$scope.classroomId    != "" ? $scope.classroomId      : null,
                companyId            :$scope.theCompanyId      != undefined &&$scope.theCompanyId != "" ? $scope.theCompanyId       : null,
                venueId               : $scope.venueId          != undefined &&$scope.venueId         != "" ? $scope.venueId         : null,
                name                  : $scope.classroomName    != undefined &&$scope.classroomName   != "" ? $scope.classroomName  : null,
                classroom_area       : $scope.classroomArea    != undefined &&$scope.classroomArea  != "" ? $scope.classroomArea   : null,
                total_seat            : $scope.totalSeat        != undefined &&$scope.totalSeat       != "" ? $scope.totalSeat       : null,
                venueDescribe           : $scope.classroomDes     != undefined &&$scope.classroomDes    != "" ? $scope.classroomDes   : null,
                pic                    : $scope.classroomPic    != undefined &&$scope.classroomPic    != "" ? $scope.classroomPic    : null
            }
        };
        if($scope.classroomId ==""||$scope.classroomId ==null){
            Message.warning("所属公司不能为空");
            return false;
        }
        if($scope.theCompanyId ==""||$scope.theCompanyId  ==null){
            Message.warning("所属场馆不能为空");
            return false;
        }
        if($scope.classroomName ==""||$scope.classroomName ==null){
            Message.warning("教室名字不能为空");
            return false;
        }
        //场地面积必须输入数据类型
        if($scope.classroomArea!=""&&$scope.classroomArea!=null){
            var reg = /^\+?[1-9]\d*$/;
            if(!reg.test($scope.classroomArea)){
                Message.warning("场地面积请输入正整数");
                return false;
            }
        }
        if($scope.totalSeat==""||$scope.totalSeat==null){
            Message.warning("座位数不能为空");
            return false;
        }else{
            var reg = new RegExp("^(\\d|[1-9]\\d|100)$");
            if(!reg.test($scope.totalSeat)){
                Message.warning("场地座位:请输入0到100的正整数");
                return false;
            }
        }
        //发送客户端数据
            $http({
                url: "/main/update-classroom",
                method: 'POST',
                data: $.param($scope.updateMyData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function () {
               window.location.replace('/main/site');
            });
        };
    //对图片格式的限制
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
                $scope.classroomPic = result.data.imgLink;
            } else {
                Message.warning(result.data.data);
            }
        });
    }
});
