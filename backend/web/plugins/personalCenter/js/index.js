/**
 * Created by 杨大侠 on 2017/7/6.
 */
angular.module('App').controller('personalCenterCtrl', function ($scope, $http,Upload,$interval) {
    $scope.paracont  = "获取验证码";
    $scope.detailedInformation = function () {
        $http({method:'get',url:"/personnel/employee-center"}).then(function (data) {
            // console.log(data)
            $scope.listData = data.data;

            if($scope.listData.pic != '' || $scope.listData.pic != null){
                $(".addCss").css("display","none");
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.detailedInformation();
    $scope.selectValue = '';

    //上传头像
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
                if (text == 'update') {
                    $scope.listData.pic = result.data.imgLink;
                    var data = {
                        id:$scope.listData.id,
                        pic:$scope.listData.pic,
                        _csrf_backend: $('#_csrf').val()
                    }
                    $http({method:'post',url:"/personnel/update-pic",data: $.param(data), headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {

                    },function (error) {
                        console.log(error)
                        Message.error("系统错误请联系管理人员")
                    })
                } else {
                    $scope.listData.pic = result.data.imgLink;
                    var data = {
                        id:$scope.listData.id,
                        pic:$scope.listData.pic,
                        _csrf_backend: $('#_csrf').val()
                    }
                    $http({method:'post',url:"/personnel/update-pic",data: $.param(data), headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
                    },function (error) {
                        console.log(error)
                        Message.error("系统错误请联系管理人员")
                    })
                }
            } else {
                Message.warning(result.data.data);
            }
        });
    };


    $scope.modifyPassword ={
        oldPassword:'', //旧密码
        newPassword:'', //新密码
        confirmTheNewPassword:'',// 确认新密码
        cellPhoneNumber:'',//手机号
        verificationCode:'', // 验证码
        newPassword1:'',
        confirmTheNewPassword1:''

    }
    //旧密码修改
    $scope.oldPasswordChanges = function () {
        var  data ={
            id:$scope.listData.admin_user_id,
            oldPassword:$scope.modifyPassword.oldPassword,//旧密码
            password:$scope.modifyPassword.newPassword,//
            rePassword:$scope.modifyPassword.confirmTheNewPassword,//
            _csrf_backend: $('#_csrf').val(),
        }
        if(data.oldPassword == '' || data.oldPassword == undefined){
            Message.warning("请输入长度为6-18位的旧密码")
            return
        }
        if(data.password == '' || data.password == undefined){
            Message.warning("请输入长度为6-18位的新密码")
            return
        }
        if(data.rePassword == '' || data.rePassword == undefined){
            Message.warning("请确认长度为6-18位的新密码")
            return
        }
        if (data.password == data.oldPassword){
            Message.warning("新旧密码不能一致")
            return
        }
        $http({method:'post',url:"/personnel/update-password", data : $.param(data),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }}).then(function (data) {
            console.log(data.data.data)
            if (data.data.status == "error"){
                for (var key in data.data.data) {
                    if (data.data.data.hasOwnProperty(key)) {
                        var element = data.data.data[key];
                        Message.warning(element);
                    }
                }
            }
            if (data.data.status == "success"){
                Message.success(data.data.data);
                setInterval(function () {
                    location.href = '/login/index'
                },2*1000)
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }

    $scope.newCode = function () {
        if($scope.modifyPassword.cellPhoneNumber == '' || $scope.modifyPassword.cellPhoneNumber == null || $scope.modifyPassword.cellPhoneNumber == undefined){
            Message.warning("请输入手机号")
            return
        }
        var data = {
            mobile:$scope.modifyPassword.cellPhoneNumber,
        }
        $http({
            url     : '/personnel/create-code',
            method  : 'POST',
            data    : $.param(data),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (response){
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        });
        //修改密码的验证码倒计时
        $scope.isParacontButtonClick = true;
        var second = 60,
            timePromise = undefined;
        timePromise = $interval(function(){
            if(second<=0){
                $scope.isParacontButtonClick = false;
                $interval.cancel(timePromise);
                timePromise = undefined;
                second = 60;
                $scope.paracont  = "获取验证码";
                $scope.disabled  = false;
            }else{
                $scope.paracont = second + "秒后重新获取";
                $scope.disabled =true;
                second--;
                $scope.isParacontButtonClick = false;
            }
        },1000);
    }
    // 验证码修改
    $scope.validationCodeModification = function () {
        var data = {
            mobile             : $scope.modifyPassword.cellPhoneNumber,
            code               : $scope.modifyPassword.verificationCode,
            password           : $scope.modifyPassword.newPassword1,
            rePassword         : $scope.modifyPassword.confirmTheNewPassword1,
            _csrf_backend: $('#_csrf').val(),
        };
        if(data.mobile == '' || data.mobile == undefined){
            Message.warning("请输入手机号")
            return
        }
        if(data.code == '' || data.code == undefined){
            Message.warning("请输入验证码")
            return
        }
        if(data.password == '' || data.password == undefined){
            Message.warning("请输入长度为6-18位的新密码")
            return
        }
        if(data.rePassword == '' || data.rePassword == undefined){
            Message.warning("请确认长度为6-18位的新密码")
            return
        }
        if (data.password != data.rePassword){
            Message.warning("两次输入的密码不一致")
            return
        }
        $http({
            url     : '/personnel/reset-password',
            method  : 'POST',
            data    : $.param(data),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (data){
            if(data.data.status == 'success'){
                Message.success(data.data.data);
                setTimeout(function () {
                    location.href=("/login/index")
                },2000);
            }
            if (data.data.status == "error"){
                for (var key in data.data.data) {
                    if (data.data.data.hasOwnProperty(key)) {
                        var element = data.data.data[key];
                        Message.warning(element);
                    }
                }
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        });
    }
})