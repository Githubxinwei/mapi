  //场地添加 js
angular.module('App').controller('addSiteCtrl', function($scope,$http,Upload){

    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".js-example-basic-single1").select2();
    })

  //初始化数据
    $scope.init = function(){
        $scope.allVenues();
        $scope.topType();
    };
    //查询顶级所属于公司
    $scope.topType = function(){
        $http.get("/main/company-check").then(function (result) {
            $scope.companyS = result.data;
        })
    };
    //查询所有场馆
    $scope.allVenues = function(){
        $http.get("/main/all-venues").then(function (result) {
            $scope.venueS= result.data;
        })
    };
    //根据公司查询场馆
    $scope.searchVenue = function(id){
        if($scope.companyId!=""){
            $http.get("/main/bel-venues?id="+id).then(function (result) {
                $scope.venueS= result.data;
            })
        }
    };
    $scope.init();
    /**
     * 后台 - 组织架构管理 - 新增场馆js
     * @author 侯凯新
     * @create 2017-4-26
     */
    //获取前台数据并提交给后台
    $scope.add = function() {
        //整理发送后的数据
        $scope.addData = function () {
            return {
                _csrf_backend        :$('#_csrf').val(),
                companyId            :$scope.companyId         != undefined &&$scope.companyId      != "" ? $scope.companyId       : null,//公司id
                venueId              : $("#venue").val()       != undefined &&$("#venue").val()      != "" ? $("#venue").val()      : null,//场馆id
                name                  : $scope.venueName        != undefined &&$scope.venueName      != "" ? $scope.venueName       : null,//场地名称
                classroom_area       : $scope.classroom_area   != undefined &&$scope.classroom_area != "" ? $scope.classroom_area  : null,//面积
                total_seat            : $scope.total_seat       != undefined &&$scope.total_seat     != "" ? $scope.total_seat      : null,//座位数
                venueDescribe          : $scope.classroom_desrc   != undefined &&$scope.classroom_desrc != "" ? $scope.classroom_desrc : null,//描述
                pic                    : $scope.pic              != undefined &&$scope.pic             != "" ? $scope.pic              : null//图片
            }
        };

        if($scope.companyId==""||$scope.companyId==null){
            Message.warning("所属公司不能为空");
            return false;
        }
        if($("#venue").val()  ==""||$("#venue").val() ==null){
            Message.warning("所属场馆不能为空");
            return false;
        }
        if($scope.venueName==""||$scope.venueName==null){
            Message.warning("场地名字不能为空");
            return false;
        }
        //场地面积必须输入数据类型
        if($scope.classroom_area!=""&&$scope.classroom_area!=null){
            var reg = /^\+?[1-9]\d*$/;
            if(!reg.test($scope.classroom_area)){
                Message.warning("场地面积请输入正整数");
                return false;
            }
        }
        var reg = new RegExp("^(\\d|[1-9]\\d|100)$");
        if($scope.total_seat != undefined && !reg.test($scope.total_seat)){
            Message.warning("场地座位:请输入0到100的正整数");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/main/add-classroom-data",
            method: 'POST',
            data: $.param($scope.addData()),
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
                $scope.pic = result.data.imgLink;
            } else {
                Message.warning(result.data.data);
            }
        });
    }
















});