angular.module('App').controller('mainCtrl', function($scope,$http,Upload) {
    //执行回车搜索
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
        }
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single1").select2();
    })
    /**
     * 后台 - 组织架构管理 - 分页（组织架构主界面数据遍历）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */

    //初始化页面加载数据
    $scope.init = function () {
        $scope.pageInitUrl = '/main/main';
        $scope.getClassModel();
        $scope.companyDetail();
        $scope.getVenueOptions();  //获取场馆
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };
    // 获取后台组织架构数据
    $scope.getClassModel = function () {
        $.loading.show();
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
            $.loading.hide();
        })
    };
    // 点击修改场馆，获取所有公司的名称
    $scope.companyDetail = function(){
        //初始化获取所有公司信息
        $http.get("/main/company-check").then(function (result) {
            $scope.companyS = result.data;
        })
    };

    /**
     * 后台 - 组织架构管理 - 单条数据删除
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */
    $scope.del=function(id,name){
        Sweety.remove({
            url: "/main/del-venue-data?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '若场馆下有部门，或者教室数据，将无法删除',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
             $scope.getClassModel();
        },function () {

        },true);
    };

    /*场馆显示功能*/
    $scope.isShow = function(id){
        $http.get('/main/update-status?id='+ id).then(function(result){
            if(result.data.status == "success"){
                Message.success(result.data.data);
                $scope.getClassModel();
            }
        });
    }

    //获取场馆信息
    $scope.getVenueOptions       = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.venues      = result.data;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.venues = '暂无数据';
            }
        });
    };
    
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.venueId      = null;
        $scope.courseName      = null;
        $scope.coachName      = null;
        $scope.topSearch      = null;
        $("#reservation").val("");
    };
    /**
     * 后台 - 组织架构管理 - 执行主界面数据搜索
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */
    //接受搜索参数
    $scope.searchModel = function(){
        var startTime = $("#reservation").val().split(' - ')[0];
        var endTime   = $("#reservation").val().split(' - ')[1];
        return {
            topSearch    : $scope.topSearch    != undefined ? $scope.topSearch   : null,    //综合字段搜索
            startTime    : startTime            != undefined ? startTime           : null,    // 创建时间开始
            endTime      : endTime              != undefined ? endTime             : null,     // 上课时间结束
            sortType     : $scope.sortType     != undefined ? $scope.sortType     : null,    //需要排序的字段名字
            sortName     : $scope.sort          != undefined ? $scope.sort         : null,    //排序的类型
            venueId      : $scope.venueId      != undefined ? $scope.venueId      : null    //场馆id
        }
    };
    /**整理搜索参数以及搜索url**/
    $scope.initPath = function (){
        $scope.searchParams   =  $scope.searchModel();
        $scope.pageInitUrl       =  '/main/main?' + $.param($scope.searchParams);
    };
    //执行最终搜索
    $scope.search = function() {
        $scope.initPath();
        $scope.getClassModel();
    };
    /**
     * 后台 -组织架构管理 - 主界面各个字段排序
     *author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
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
     * 后台 - 组织架构管理 - 公司（执行场馆单条数据修改（场馆名称））
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/26
     */

    //点击修改的时候将对应数据赋值给弹框
    $scope.updateVenue=function(venueId,companyId,venueName,address,area,phone,pic,describe,object){
        $scope.companyId             = companyId;
        $scope.venueId               = venueId;
        $scope.venueName             = venueName;
        $scope.transferVenueName     = venueName;
        $scope.venueAddress          = address;
        $scope.venueArea             = area;
        $scope.venuePhone            = phone;
        $scope.pic                   = pic;
        $scope.describe              = describe;
        console.log(object);
        $scope.venueEditLongitude    = object.longitude;
        $scope.venueEditLatitude     = object.latitude;
        $scope.selectVenue123         = object.identity;
        $scope.editVenue123            = object.identity;  // 场馆属性初始化赋值
    };
    $scope.venueUpdate = function() {
        //整理发送后的数据
        $scope.initData = function(){
            return {
                _csrf_backend: $('#_csrf').val(),
                pid           : $("#companyId").val(),//公司id
                venueId       : $("#venueId").val(),//场馆id
                pic           : $scope.pic,//图片
                identity      :$scope.editVenue123 != undefined && $scope.editVenue123 != ''? $scope.editVenue123 : null,//场馆属性
                venueType     :$scope.venueType != undefined && $scope.venueType!= ''? $scope.venueType : null,//场馆类型
                address       :  $scope.venueAddress != undefined && $scope.venueAddress!= "" ? $scope.venueAddress  : null,//场馆地址
                phone         :  $scope.venuePhone != undefined && $scope.venuePhone!= "" ? $scope.venuePhone  : null,//场馆电话
                area          :  $scope.venueArea != undefined && $scope.venueArea!= "" ? $scope.venueArea  : null,//场馆面积
                name          : $scope.venueName != undefined && $scope.venueName!= "" ? $scope.venueName  : null,//场馆名称
                describe      : $scope.describe != undefined && $scope.describe != "" ? $scope.describe : null,//场馆描述
                latitude      :$scope.venueEditLatitude != undefined && $scope.venueEditLatitude != ''? $scope.venueEditLatitude:null,//场馆纬度
                longitude     : $scope.venueEditLongitude != undefined &&$scope.venueEditLongitude != ''? $scope.venueEditLongitude :null,//场馆经度
            };
        };
        //保存数据之前数据验证
        if($scope.venueName ==null||$scope.venueName ==""){
            Message.warning("场馆名字不能为空");
            return false;
        }
        // 场馆属性不能为空
        if($scope.editVenue123==null||$scope.editVenue123 ==""){
            Message.warning("场馆属性不能为空");
            return false;
        }
        // 场馆类别不能为空
        if($scope.venueType==null||$scope.venueType ==""){
            Message.warning("场馆类别不能为空");
            return false;
        }
        //场地面积必须输入数据类型
        if($scope.venueArea != "" && $scope.venueArea != null){
            var reg = /^\+?[1-9]\d*$/;
            if(!reg.test($scope.venueArea)){
                Message.warning("场馆面积必须为正整数");
                return false;
            }
        }
        if($scope.venueEditLongitude != undefined &&$scope.venueEditLongitude != '' ){
            if($scope.venueEditLatitude == undefined || $scope.venueEditLatitude == ''){
                Message.warning('场馆经度不能为空!');
                return;
            }
        }
        if($scope.venueEditLatitude != undefined &&$scope.venueEditLatitude != '' ){
            if($scope.venueEditLongitude == undefined || $scope.venueEditLongitude == ''){
                Message.warning('场馆纬度不能为空!');
                return;
            }
        }

        // if($scope.companyId ==$("#companyId").val()&&$scope.venueName==$scope.transferVenueName){
        //     Message.warning("提示:你未进行任何修改");
        //     return false;
        // }
        //发送客户端数据
        $http({
            url: "/main/update-venue",
            method: 'POST',
            data: $.param($scope.initData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
            window.location.replace('/main/index');
        });
    };
    $scope.getDetail = function (id) {
        $http.get('/main/get-venue-detail?id='+id).then(function (result) {
            $scope.venueData = result.data;
            $scope.venueType = result.data.venue_type;
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
    //长传图片
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

});