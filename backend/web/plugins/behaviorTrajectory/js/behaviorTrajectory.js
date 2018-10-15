/**
 * Created by 杨大侠 on 2017/7/13.
 */
angular.module('App').controller('behaviorTrajectoryCtrl', function ($scope, $http) {
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
    })

    $scope.initDataModel = {
        behavior:"",
        modules:"",
        keywords:"",
    }
    $scope.subModuleChangeId = '';
    $scope.startTime = '';
    $scope.endTime = '';
    $scope.pageInitUrl = '/user/member-behavior-trail?employeeName='+ $scope.initDataModel.keywords+"&startTime="+ $scope.startTime +"&endTime="+ $scope.endTime +"&employeeBehavior="+$scope.initDataModel.behavior+"&operateModuleId="+$scope.initDataModel.modules+"&id="+$scope.subModuleChangeId

    //主页数据
    $scope.initListData = function () {
        $http({method:'get',url:$scope.pageInitUrl}).then(function (data) {
            if (data.data.data == ''){
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }else {
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
            $scope.allModules();
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.initListData()
    //截取时间 分为开始 和结束时间
    $scope.getTimeDate = function () {
        var date = $("#reservation").val();
        if (date == ''){
            $scope.startTime =  ""
            $scope.endTime = ""
            return;
        }else {
            $scope.startTime =   $("#reservation").val().substr(0,10);
            $scope.endTime   =   $("#reservation").val().substr(-10,10);
            $scope.startTime = $scope.startTime+'00:00'
            $scope.endTime = $scope.endTime+'23:59';
        }
    }
    //回车键搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){
            $scope.searchName()
        }
    }
    
    //按下确定按钮搜索
    $scope.searchName = function () {
        $scope.getTimeDate();
        $http({method:'get',url:'/user/member-behavior-trail?employeeName='+ $scope.initDataModel.keywords+"&startTime="+ $scope.startTime +"&endTime="+ $scope.endTime +"&employeeBehavior="+$scope.initDataModel.behavior+"&operateModuleId="+$scope.initDataModel.modules+"&id="+$scope.subModuleChangeId}).then(function (response) {
            if (response.data.data == "") {
                $scope.listDatas = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = true;
            } else {
                $scope.listDatas = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = false;
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }



    //排序
    $scope.changeSort = function (type,name) {
        if ($scope.listDatas == ''){
            return
        }
        $scope.sortName = type;             //排序字段
        $scope.switchSort(name);            //准备排序状态
        $http({method:'get',url:"/user/member-behavior-trail?sortType="+$scope.sort+"&sortName="+$scope.sortName}).then(function (data) {
            $scope.listDatas = data.data.data;
            $scope.pages = data.data.pages;
            $scope.dataInfo = false;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        })
    }
    //处理正序、倒序
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        }
        $scope.sort = sort;//排序状态
    };

    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.initListData();
    };
    $scope.getDataPage = function () {
        $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data.data == "" ) {
                $scope.listDatas = response.data.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = true;
            } else {
                $scope.listDatas = response.data;
                $scope.pages = response.data.pages;
                $scope.dataInfo = false;
            }
            $.loading.hide()
        });
    };
    
    //全部行为 触发事件
    $scope.allBehaviorChange = function (id) {
        $http({method:'get',url:'/user/member-behavior-trail?employeeBehavior='+id}).then(function (data) {
            if (data.data.data == ''){
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }else {
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    //获取所有模块
    $scope.allModules = function () {
        $http({method:'get',url:'/user/all-modules'}).then(function (data) {
            $scope.allModulesData = data.data;
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    //全部模块 触发事件
    $scope.allModulesChange = function (id) {
        $scope.subModuleChangeId = '';
        $scope.subModule(id)
        $http({method:'get',url:'/user/member-behavior-trail?operateModuleId='+id+"&employeeBehavior="+$scope.initDataModel.behavior}).then(function (data) {
            if (data.data.data == ''){
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }else {
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    //子模块
    $scope.subModule = function (id) {


        $http({method:'get',url:"/user/all-modules?id="+ id}).then(function (data) {
            $scope.subModeleData = data.data;
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.subModuleChange = function (id) {

        $scope.subModuleChangeId = id;
        $http({method:'get',url:'/user/member-behavior-trail?operateModuleId='+id +"&employeeBehavior="+$scope.initDataModel.behavior}).then(function (data) {
            if (data.data.data == ''){
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }else {
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
})