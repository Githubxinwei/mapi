/**
 * 系统管理-菜单管理-菜单管理页面控制器
 * menuController by suyu on 2017/6/17.
 * author:苏雨
 * content:菜单管理页面控制器
 */
var app = angular.module('App').controller('menuCtrl', function ($scope, $http,$location, $rootScope,$interval) {
    
    // 新增顶级菜单的点击方法
    $scope.addtopMenu = function (){
        // ng-model初始化
        $scope.topName = "";
        $scope.topEName = "";
        $scope.topIcon = "";
        $scope.addMenuButtonFlag = false;
    };
    // 新增顶级菜单的提交方法
    $scope.addTopMenuSuccess = function () {
        if(!$scope.addMenuButtonFlag){
            // 整理提交数据
            $scope.addTopMenuSuccessData = function () {
                return {
                    topName: $scope.topName != undefined && $scope.topName != "" ? $scope.topName : null,//菜单名称 （string）
                    topEName: $scope.topEName != undefined && $scope.topEName != "" ? $scope.topEName : null,//英文名称（string）
                    topIcon: $scope.topIcon != undefined && $scope.topIcon != "" ? $scope.topIcon : null,//菜单图标(string）
                    _csrf_backend: $('#_csrf').val()
                };
            }


            // 保存之前的数据验证
            if ($scope.topName == null || $scope.topName == "") {
                Message.warning("请输入菜单名");
                return false;
            }
            if ($scope.topEName == null || $scope.topEName == "") {
                Message.warning("请输入英文名");
                return false;
            }
                $scope.addMenuButtonFlag = true;
                // 发送数据
                $http({
                    url: "/menu/top-module",
                    method: 'POST',
                    data: $.param($scope.addTopMenuSuccessData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
                    if(data.status == "success"){
                        Message.success('添加成功');
                        $('#addTopMenu').modal('hide');
                        // 数据初始化
                        $scope.topName = "";
                        $scope.topEName = "";
                        $scope.topIcon = "";
                        $scope.getTopMenu();
                    }else {
                        Message.warning('请联系管理员');
                        $scope.addMenuButtonFlag = false;

                    }
                });
        }
    };




    // 获取顶级菜单的方法
    $scope.getTopMenu = function (){
        $.loading.show();
        $http.get('/menu/get-top-module').success(function (data){
            $scope.allNumTopMenu = data.data.length;
            if(data.data.length <= 0){
                $scope.topMenuList = data.data;
                $scope.noTopMenuShow = true;
            }else {
                $scope.topMenuList = data.data;
                $scope.noTopMenuShow = false;
            }
            $.loading.hide();
        });
    };
    $scope.getTopMenu();




    // 修改顶级菜单的方法
    $scope.amendTopMenu = function (id){
        $scope.amendTopMenuId = id;
        $http.get('/menu/get-module-data?moduleId='+$scope.amendTopMenuId).then(function (data){
            $scope.amendTopMenuInput = data.data;
            $scope.topNameUp = $scope.amendTopMenuInput.name;
            $scope.topENameUp = $scope.amendTopMenuInput.e_name;
            $scope.topIconUp = $scope.amendTopMenuInput.icon;
        })
    };




    // 修改顶级菜单提交的方法
    $scope.amendTopMenuSuccess = function (){
        // 整理发送数据
        $scope.amendTopMenuData = function (){
            $scope.topId = $scope.amendTopMenuId;
            return{
                topNameUp: $scope.topNameUp != undefined && $scope.topNameUp != "" ? $scope.topNameUp : null,//菜单名称 (string)
                topENameUp: $scope.topENameUp != undefined && $scope.topENameUp != "" ? $scope.topENameUp : null,//英文名称 (string)
                topIconUp: $scope.topIconUp != undefined && $scope.topIconUp != "" ? $scope.topIconUp : null,//菜单图标 (string)
                topId: $scope.topId != undefined && $scope.topId != "" ? $scope.topId : null,//修改菜单id (string)
                _csrf_backend: $('#_csrf').val()
            }
        };
        // 保存之前的数据验证
        if ($scope.topNameUp == null || $scope.topNameUp == "") {
            Message.warning("请输入菜单名");
            return false;
        }
        if ($scope.topENameUp == null || $scope.topENameUp == "") {
            Message.warning("请输入英文名");
            return false;
        }
        else {
            // 发送数据
            $http({
                url: "/menu/update-top",
                method: 'POST',
                data: $.param($scope.amendTopMenuData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (data) {
                if (data.status == "success") {
                    Message.success('添加成功');
                    $('#amendTopMenu').modal('hide');
                    // 数据初始化
                    $scope.topNameUp = "";
                    $scope.topENameUp = "";
                    $scope.topIconUp = "";
                } else {
                    Message.warning('请联系管理员');
                }
            });
            $scope.getTopMenu();
        }
    };




    // 删除顶级菜单的方法
    $scope.deleteMenu = function (id,name){
        $scope.moduleId = id;
        Sweety.remove({
            url              : '/menu/del-module?moduleId='+$scope.moduleId,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        },function () {
            $scope.getTopMenu();
        },function () {

        },true)
    };



    $(".transitionBox").hide();
    // subShowList
    // 查看子菜单的点击方法
    $scope.lookSecondMenu = function (id,name){
        $(".transitionBox").show();
        $scope.lookSecondMenuPostId = id;
        $scope.getlookSecondMenu();
    };




    // 获取子菜单信息的方法
    $scope.getlookSecondMenu = function(){
        $http.get('/menu/get-sub-module?topId='+$scope.lookSecondMenuPostId).then(function (data){
            $scope.allNumSubMenu = data.data.length;
            if(data.data.length <= 0){
                $scope.sencondMenuList = data.data;
                $scope.noSubMenuShow = true;
                $(".transitionBox").hide();
            }else {
                $scope.sencondMenuList = data.data;
                $scope.noSubMenuShow = false;
                $(".transitionBox").hide();
            }
        });
    };
    $scope.getlookSecondMenu();


    // 新增子菜单的点击方法
    $scope.addSecondMenuBtn = function (){
        $scope.subName = "";
        $scope.subEName = "";
        $scope.subIcon = "";
        $scope.subUrl = "";
        $scope.addChildMenuButton = false;
    };




    // 新增子菜单的完成点击方法
    $scope.addSecondMenuSuccess = function (){
        if(!$scope.addChildMenuButton){
            // 整理发送数据
            $scope.addSecondMenuData = function (){
                $scope.topId = $scope.lookSecondMenuPostId;
                return{
                    subName: $scope.subName != undefined && $scope.subName != "" ? $scope.subName : null,//子菜单名称(string)
                    subEName: $scope.subEName != undefined && $scope.subEName != "" ? $scope.subEName : null,//子菜单英文名称(string)
                    subIcon: $scope.subIcon != undefined && $scope.subIcon != "" ? $scope.subIcon : null,//子菜单图标(string)
                    subUrl: $scope.subUrl != undefined && $scope.subUrl != "" ? $scope.subUrl : null,//子菜菜单地址(string)
                    topId: $scope.topId != undefined && $scope.topId != "" ? $scope.topId : null,//顶菜单id(string)
                    _csrf_backend: $('#_csrf').val()
                }
            };

            // 保存之前的数据验证
            if ($scope.subName == null || $scope.subName == "") {
                Message.warning("请输入菜单名");
                return false;
            }
            if ($scope.subEName == null || $scope.subEName == "") {
                Message.warning("请输入英文名");
                return false;
            }
            if ($scope.subUrl == null || $scope.subUrl == "") {
                Message.warning("请输入菜单地址");
                return false;
            }
            else {
                $scope.addChildMenuButton = true;
                // 发送数据
                $http({
                    url: "/menu/sub-module",
                    method: 'POST',
                    data: $.param($scope.addSecondMenuData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
                    if (data.status == "success") {
                        Message.success('添加成功');
                        $('#addSecondMenu').modal('hide');
                        // 数据初始化
                        $scope.subName = "";
                        $scope.subIcon = "";
                        $scope.subUrl = "";
                        $scope.subEName = "";
                        $scope.getlookSecondMenu();
                    } else {
                        Message.warning('请联系管理员')
                        $scope.addChildMenuButton = false;
                    }
                });
            }
        }

    };




    // 修改子菜单的点击获取ID方法
    $scope.amendSecondMenu = function (id){
        $scope.amendSecondMenuId = id;
        $http.get('/menu/get-module-data?moduleId='+$scope.amendSecondMenuId).then(function (data){
            $scope.amendSecondMenuInput = data.data;
            $scope.subNameUp = $scope.amendSecondMenuInput.name;
            $scope.subENameUp = $scope.amendSecondMenuInput.e_name;
            $scope.subIconUp = $scope.amendSecondMenuInput.icon;
            $scope.subUrlUp = $scope.amendSecondMenuInput.url;
        })
    };




    // 修改子菜单成功的点击方法
    $scope.amendSecondMenuSuccess = function (){
        // 整理发送数据
        $scope.addSecondMenuData = function (){
            $scope.subId = $scope.amendSecondMenuId;
            return{
                subNameUp: $scope.subNameUp != undefined && $scope.subNameUp != "" ? $scope.subNameUp : null,//菜单名称（string）
                subENameUp: $scope.subENameUp != undefined && $scope.subENameUp != "" ? $scope.subENameUp : null,//英文名称（string）
                subIconUp: $scope.subIconUp != undefined && $scope.subIconUp != "" ? $scope.subIconUp : null,//子菜单图标（string）
                subUrlUp: $scope.subUrlUp != undefined && $scope.subUrlUp != "" ? $scope.subUrlUp : null,//菜单地址（string）
                subId: $scope.subId != undefined && $scope.subId != "" ? $scope.subId : null,//修改菜单id（string）
                _csrf_backend: $('#_csrf').val()
            }
        };
        // 保存之前的数据验证
        if ($scope.subNameUp == null || $scope.subNameUp == "") {
            Message.warning("请输入菜单名");
            return false;
        }
        if ($scope.subENameUp == null || $scope.subENameUp == "") {
            Message.warning("请输入英文名");
            return false;
        }
        if ($scope.subUrlUp == null || $scope.subUrlUp == "") {
            Message.warning("请输入菜单地址");
            return false;
        }
        else {
            // 发送数据
            $http({
                url: "/menu/update-sub",
                method: 'POST',
                data: $.param($scope.addSecondMenuData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (data) {
                if (data.status == "success") {
                    Message.success('添加成功');
                    $('#amendSecondMenu').modal('hide');
                    // 数据初始化
                    $scope.subNameUp = "";
                    $scope.subENameUp = "";
                    $scope.subIconUp = "";
                    $scope.subUrlUp = "";
                    $scope.getlookSecondMenu();
                } else {
                    Message.warning('请联系管理员');
                }
            });
        }
    };




    // 删除子菜单的方法
    $scope.deleteSencondMenu = function (id,name){
        $scope.moduleId = id;
        Sweety.remove({
            url              : '/menu/del-module?moduleId='+$scope.moduleId,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        },function () {
            $scope.getlookSecondMenu();
        })
    };

    /**
     * author:张亚鑫
     * date:2017-11-24
     * 函数描述：显示按钮，点击后对应的模块就不显示。
     * 默认值是模块显示
     *
     * */

    $scope.showMenuButton = function (id) {
        $http.get('/menu/update-status?moduleId='+id).then(function (data) {
            if(data.data.status == 'success') {
                Message.success('操作成功！');
                $scope.getlookSecondMenu();
                return;
            }else if(data.data.status != 'error') {
                Message.warning('操作失败！');
                return;
            }
        })
    }
    // 子菜单获取功能ID的方法
    $scope.functionSecondMenu = function (id){
        $scope.functionSecondMenuId = id;
        $scope.functionSecondMenuGet();
    };




    // 子菜单功能获取的方法
    $scope.functionSecondMenuGet = function (){
        $http.get('/menu/get-all-func').then(function (data) {
            $scope.functionSecondMenuGetName = data.data;
            $scope.getSecondMenuFunc();
        });
    };




    // 子菜单功能完成的方法
    $scope.functionSecondMenuSuccess = function (){
        // 数组
        $scope.func = [];
        // 选择checkbox时的状态
        var $menuCheck = $('.menuCheck').find('input:checked');
        if($menuCheck.length > 0) {
            $menuCheck.each(function (i) {
                $scope.func.push($(this).val());
            });
        }
        // 发送数据
        $http({
            url: "/menu/save-func",
            method: 'POST',
            data: $.param($scope.functionSecondMenuData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            if (data.status == "success") {
                Message.success('添加成功');
                $('#functionSecondMenu').modal('hide');
                $scope.getlookSecondMenu();
            } else {
                Message.warning('请联系管理员');
            }
        });
    };



    // 子菜单功能保存数据
    $scope.functionSecondMenuData = function (){
        return{
            subId : $scope.functionSecondMenuId !=  undefined && $scope.functionSecondMenuId != "" ? $scope.functionSecondMenuId : null,//菜单id(string)
            funcId : $scope.func                  !=  undefined && $scope.func                                     != "" ? $scope.func : null,//功能id(array)
            _csrf_backend: $('#_csrf').val()
        }
    };



    // 获取子菜单功能的方法
    $scope.getSecondMenuFunc = function (){
        $http.get('/menu/get-func-data?subId='+$scope.functionSecondMenuId).then(function (data){
            $scope.funIdArr = data.data;
        })
    };

    //顶级菜单排序
    $scope.mainMenuSorting = function () {
        $http({method:"get",url:'/menu/get-top-module'}).then(function (data) {
            $scope.mainMenuSortingData = data.data.data;
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员");
        })
    }
    $scope.mainMenuSortingOk = function () {
        var topNumArr = [];
        var topIdArr = [];
        for (var index = 0; index < $(".submenuSortingData").find("div").find('input').length; index++) {
            var element = $(".submenuSortingData").find("div").find('input')[index];
            topNumArr.push(element.value);
        }
        for(var i = 0;i<$scope.mainMenuSortingData.length;i++){
            var a = $scope.mainMenuSortingData[i];
            topIdArr.push(a.id);
        }
        var data = {
            topNumArr:topNumArr, // topNumArr序号数组
            topIdArr:topIdArr, // topIdArr顶级菜单id数组
        };
        $scope.isRepeat = function(arr){
            var hash = {};
            for(var i in arr) {
                if(hash[arr[i]])
                    return true;
                hash[arr[i]] = true;
            }
            return false;
        };
        if($scope.isRepeat(topNumArr)) {
            Message.warning('排序序号不能重复！');
            return;
        };
        $http({method:'post',url:'/menu/top-sort',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if(success.data.status == "success"){
                $("#mainMenuSorting").hide();
                $(".modal-backdrop").hide();
                Message.success(success.data.data)
            }
            if(success.data.status == "error"){
                $("#mainMenuSorting").hide();
                $(".modal-backdrop").hide();
                Message.success(success.data.data)
            }
            $scope.getTopMenu();
            $scope.getlookSecondMenu();
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
    };
    //子菜单排序
    $scope.submenuSorting = function () {

        $http({method:"get",url:'/menu/get-top-module'}).then(function (data) {
            var success = data.data.data;
            for(var i = 0;i<success.length;i++){
                var a = success[i];
                if (a.id == $scope.lookSecondMenuPostId){
                    $scope.submenuSortingData = a.module;
                }
            }
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员");
        });
    };

    // 模态框关闭事件
    $("#lookSecondMenu").on('hide.bs.modal',function(){
        $scope.sencondMenuList = [];
    });

    //子菜单排序
    $scope.submenuSortingOk = function () {
        var subNumArr = [];//序号数组，
        var subIdArr = [] ;//子菜单id数组
        for(var i =0;i<$(".submenuSortingDatas").find('input').length;i++ ){
            var a = $(".submenuSortingDatas").find('input')[i]
            subNumArr.push(a.value);
        }
        for (var key in $scope.submenuSortingData) {
            if ($scope.submenuSortingData.hasOwnProperty(key)) {
                var i = $scope.submenuSortingData[key];
                subIdArr.push(i.id);
            }
        }
        var data = {
            subNumArr:subNumArr,//序号数组，
            subIdArr:subIdArr,//子菜单id数组
        };
        $http({method:'post',url:'/menu/sub-sort',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if(success.data.status == "success"){
                $("#submenuSorting").hide();
                $(".modal-backdrop").hide();
                Message.success(success.data.data);
                $scope.getTopMenu();
                $scope.getlookSecondMenu();
                window.location.href = '/menu/index?';
            }else{
                Message.warning(success.data.data);
            }
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    };
    //移动
    $scope.moveModal = function (id) {
        $scope.moveModalIds = id;
        $http({method:"get",url:'/menu/get-top-module'}).then(function (data) {
            $scope.moveModalData = data.data.data;
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员");
        });
    };
    //选择移动模块
    $scope.moveModalListItem = function (e,i,id) {
        $(".spanName").addClass("colorOpacity")
        $scope.moveModalId = id;
        if (!$(".moveModalArray").find(".span").find("span").eq(i).hasClass("colorBlue")){
            $(".moveModalArray").find(".span").find("span").removeClass('colorBlue');
            $(".moveModalArray").find(".span").find("span").eq(i).addClass('colorBlue');
        };
    };
    //菜单移动完成
    $scope.moveModalOk = function () {
        var data  = {
            subModuleId:$scope.moveModalIds,
            topModuleId:$scope.moveModalId,
        };
        $http({method:'post',url:'/menu/move-sub-module',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if(success.data.status == "success"){
                $("#moveModal").hide();
                $(".modal-backdrop").hide();
                $(".lookSecondMenu").hide();
                Message.success(success.data.data)
            }
            if(success.data.status == "error"){
                $("#moveModal").hide();
                $(".modal-backdrop").hide();
                $(".lookSecondMenu").hide();
                Message.success(success.data.data)
            }
            $scope.getTopMenu();
            $scope.getlookSecondMenu()
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        });
    };



}).filter('inArr',function () {
    return function (value,idArr) {
        if(!value) return false;
        if($.inArray(value,idArr) >= 0){
            return true;
        }
        return false;
    };
});
