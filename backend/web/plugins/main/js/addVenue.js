angular.module('App').controller('addVenue',function($scope,$http,Upload){
    /**
     * 后台 - 组织架构管理 - 新增场馆js
     * @author 侯凯新
     * @create 2017-4-24
     */
    $(function(){
        $("#establishDate").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true,//今日按钮
        });
    })
    //查询顶级所属于公司
    $scope.topType = function(){
        $http.get("/main/company-check").then(function (result) {
            $scope.items = result.data;
        })
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
    })
    // 初始化场馆数据
    $scope.topType();

    //获取前台数据并提交给后台
    $scope.add = function() {
        //整理发送后的数据
        $scope.addData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                identity      :$scope.addVenue123 != undefined && $scope.addVenue123 != ''? $scope.addVenue123 : null,//场馆类型
                venueType     :$scope.venueType != undefined && $scope.venueType != ''? $scope.venueType : null,//场馆类型
                name          : $scope.name     != undefined && $scope.name     != "" ? $scope.name      : null,//名称
                venueArea     : $scope.venueArea     != undefined && $scope.venueArea     != "" ? $scope.venueArea      : null,//面积
                venueAddress  : $scope.venueAddress != undefined && $scope.venueAddress  != "" ? $scope.venueAddress   : null,//地址
                venuePhone    : $scope.venuePhone    != undefined && $scope.venuePhone    != "" ? $scope.venuePhone     : null,//电话
                venueDescribe : $scope.venueDescribe != undefined && $scope.venueDescribe!= "" ? $scope.venueDescribe  : null,//描述
                pic            :$scope.pic            !=undefined && $scope.pic             != "" ? $scope.pic            : null,//图片
                pid            : $scope.pid           != undefined && $scope.pid            != "" ? $scope.pid             : null,//公司id
                longitude      :$scope.venueLongitude  != undefined && $scope.venueLongitude != ''?$scope.venueLongitude   :null,//纬度
                latitude       :$scope.venueLatitude   != undefined && $scope.venueLatitude  != ''? $scope.venueLatitude   :null,//经度
                establishTime  :$scope.establishDate  !=undefined   && $scope.establishDate != "" ?$scope.establishDate   :null //成立时间
            }
        };
        //保存数据之前数据验证
         if($scope.pid==null||$scope.pid==""){
             Message.warning("所属公司不能为空");
             return false;
         }
        if($scope.name==null||$scope.name==""){
            Message.warning("场馆名字不能为空");
            return false;
        }
        if($scope.addVenue123==null||$scope.addVenue123==""){
            Message.warning("场馆属性不能为空");
            return false;
        }
        if($scope.venueType == null||$scope.venueType == ""){
            Message.warning("场馆类型不能为空");
            return false;
        }
        if($scope.establishDate==null||$scope.establishDate=="" ||$scope.establishDate == undefined){
            Message.warning("请选择场馆成立时间！");
            return false;
        }
        if($scope.venueLongitude  != undefined && $scope.venueLongitude != ''){
            if($scope.venueLatitude   == undefined || $scope.venueLatitude  == ''){
                Message.warning('场馆纬度不能为空');
                return;
            }else{
                // var regularLongitude = /^[\-\+]?(0?\d{1,2}\.\d{1,5}|1[0-7]?\d{1}\.\d{1,5}|180\.0{1,5})$/;
                // if(!regularLatitude.test($scope.venueLongitude)){
                //     Message.warning('您的场馆经度输入有误');
                //     return;
                // }
            }
        }
        if($scope.venueLatitude  != undefined && $scope.venueLatitude != ''){
            if($scope.venueLongitude   == undefined || $scope.venueLongitude  == ''){
                Message.warning('场馆经度不能为空');
                return;
            }else{
                // var regularLatitude = /^[\-\+]?([0-8]?\d{1}\.\d{1,5}|90\.0{1,5})$/;
                // if(!regularLatitude.test($scope.venueLatitude)){
                //     Message.warning('您的场馆纬度输入有误');
                //     return;
                // }
            }
        }

        //场地面积必须输入数据类型
        if($scope.venueArea!=""&&$scope.venueArea!=null){
            var reg = /^\+?[1-9]\d*$/;
            if(!reg.test($scope.venueArea)){
                Message.warning("场馆面积必须为正整数");
                return false;
            }
        }
        //发送客户端数据
        $http({
            url: "/main/add-data",
            method: 'POST',
            data: $.param($scope.addData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
           window.location.replace('/main/index');
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
                $scope.pic = result.data.imgLink;
            } else {
                Message.warning(result.data.data);
            }
        });
    }
});
