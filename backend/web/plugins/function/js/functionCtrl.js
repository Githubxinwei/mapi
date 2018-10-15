angular.module('App').controller('functionCtrl', function($scope,$http){


    /****分页***/
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getData();
    };
    $scope.initPath = function () {
        $scope.searchParams = $scope.search();
        $scope.pageInitUrl = '/function/function-info?' + $.param($scope.searchParams);
    };
    //  分页数据信息
    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function (result) {;
            if (result.data.data.length!=0) {
                $scope.allFunction        = result.data.data;
                $scope.pages        = result.data.pages;
                $scope.dataInfo     = false;
                $scope.searchData   = false;
            } else {
                $scope.allFunction = result.data.data;
                $scope.pages = result.data.pages;
                if($scope.searchParams!=null){
                    $scope.searchData = true;
                    $scope.dataInfo  = false;
                }else{
                    $scope.dataInfo = true;
                }
            }
            $.loading.hide();
        })
    };

    /**主界面(字段)搜索**/
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.initFunction();
    };

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

    /**页面搜索（搜索栏）***/
    $scope.search = function () {
        return {
            fun_name            : $scope.fun_name  != undefined ? $scope.fun_name  : null,//功能名
            fun_eName           : $scope.fun_eName        != undefined ? $scope.fun_eName        : null,//英文名
            fun_createAt       : $scope.fun_createAt != undefined ? $scope.fun_createAt : null,//创建时间
            fun_note            : $scope.fun_note   != undefined ? $scope.fun_note    : null,//功能描述
            sortType           : $scope.sortType   != undefined ? $scope.sortType   : null,
            sortName           : $scope.sort       != undefined ? $scope.sort       : null,
        }
    };
    //初始化调用雷彪数据
    $scope.initFunction = function(){
        $scope.initPath();
        $scope.getData();
    }

    $scope.initFunction();

    // 新增功能
    $scope.addFunction = function(){
        $('#addFunctionModal').modal('show');
    }
    //取消新增功能
    $scope.cancelAddFunction = function(){
        $('#addFunctionModal').modal('hide');
    }
    //完成新增功能
    $scope.completeAddFunction = function(){
        $scope.addFunctionInit = function(){
            $scope.addFunctionName = '';
            $scope.addFunctionEnName = '';
            $scope.addFunctionNote = '';
        }

        $scope.addFunctionData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                name         : $scope.addFunctionName        != undefined && $scope.addFunctionName        != "" ? $scope.addFunctionName       : null,//功能名称
                eName     : $scope.addFunctionEnName    != undefined && $scope.addFunctionEnName    != "" ? $scope.addFunctionEnName   : null,//功能英文名
                note       : $scope.addFunctionNote      != undefined && $scope.addFunctionNote      != "" ? $scope.addFunctionNote     : null,//功能描述
             }
        };
        if($scope.addFunctionName == null  || $scope.addFunctionName == undefined || $scope.addFunctionName == ""){
            Message.warning("请输入功能名称!");
            return;
        }
        if($scope.addFunctionNote == null  || $scope.addFunctionNote == undefined || $scope.addFunctionNote == ""  ){
            Message.warning("请输入功能描述!");
            return;
        }
        $http({
            url: "/function/add-function",
            method: 'POST',
            data: $.param($scope.addFunctionData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result){
            if(result.data.status == 'success'){
                Message.success(result.data.data);
                //执行关闭模态框
                $scope.addFunctionInit();
                $scope.initFunction();
                $('#addFunctionModal').modal('hide');
            }
            if(result.data.status == 'error'){
                Message.warning("新增功能失败!");
                return;
            }
        });
    }
    // 修改功能
    $scope.updateFunction = function(object){
        $scope.functionDetail = object;
        $scope.editFunctionName = $scope.functionDetail.name;
        $scope.editFunctionEnName = $scope.functionDetail.e_name;
        $scope.editFunctionNote = $scope.functionDetail.note;
        $scope.editFunctionId = $scope.functionDetail.id;
        $('#updateFunctionModal').modal('show');
    }
    //取消修改功能
    $scope.cancelEditFunction = function(){
        $('#updateFunctionModal').modal('hide');
    }
    //完成修改功能
    $scope.completeEditFunction = function() {
        $scope.editFunctionInit = function () {
            $scope.editFunctionName = '';
            $scope.editFunctionEnName = '';
            $scope.editFunctionNote = '';
        }

        $scope.editFunctionData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                id            :$scope.editFunctionId != undefined && $scope.editFunctionId != "" ? $scope.editFunctionId : null,//功能id
                name          : $scope.editFunctionName != undefined && $scope.editFunctionName != "" ? $scope.editFunctionName : null,//功能名称
                eName         : $scope.editFunctionEnName != undefined && $scope.editFunctionEnName != "" ? $scope.editFunctionEnName : null,//功能英文名
                note          : $scope.editFunctionNote != undefined && $scope.editFunctionNote != "" ? $scope.editFunctionNote : null,//功能描述
            }
        };
        if ($scope.editFunctionName == null || $scope.editFunctionName == undefined || $scope.editFunctionName == "") {
            Message.warning("请输入功能名称!");
            return;
        }
        if ($scope.editFunctionNote == null || $scope.editFunctionNote == undefined || $scope.editFunctionNote == "") {
            Message.warning("请输入功能描述!");
            return;
        }
        if($scope.editFunctionNote == $scope.functionDetail.note && $scope.editFunctionEnName == $scope.functionDetail.e_name  && $scope.editFunctionName == $scope.functionDetail.name){
            Message.warning("您没有修改请重新输入!");
            return;
        }
        $http({
            url: "/function/update-function",
            method: 'POST',
            data: $.param($scope.editFunctionData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                //执行关闭模态框
                $scope.editFunctionInit();
                $scope.initFunction();
                $('#updateFunctionModal').modal('hide');
            }
            if (result.data.status == 'error') {
                Message.warning("修改功能失败!");
                return;
            }
        });
    }

    // 删除功能
    $scope.delMem = function(id,name) {
        var id = parseInt(id);
        Sweety.remove({
            url              : '/function/delete-function?functionId='+ id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.initFunction();
        });
    };
});