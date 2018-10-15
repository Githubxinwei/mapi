/**
 * Created by DELL on 2017/6/17.
 */
var app = angular.module('App');
app.controller('jurisdictionCtrl',function($http,$scope,$timeout){
    $.loading.show();
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".js-example-basic-single1").select2();

    })
    $scope.init = function () {
        $scope.companyId = '';
        $scope.status    = '';
        $scope.initPath = '/authority/get-auth';
        $scope.getInitPath();
        $scope.getAuthRole();
        $scope.getCompanyArr();
    };
    $scope.getAuthRole = function () {
      $http.get($scope.initPath).then(function (result) {
          if(result.data.data == undefined || result.data.data == '' || result.data.data == null){
              $scope.dataInfo = true;
          }else{
              $scope.dataInfo = false;
          }
          $scope.authItems = result.data.data;
          $scope.pages     = result.data.pages;
          $.loading.hide();
      });
    };
    /****权限分页***/
    $scope.replacementPages = function (urlPages) {
        $scope.initPath     = urlPages;
        $scope.getAuthRole();
    };
    //调用分配权限模态框
    $scope.allocationBtn = function(id){
        $scope.roleId     = id;
        $scope.getAuthDetail(id);
        $scope.auth       = [];
        $scope.module     = [];
        $scope.modFunc    = [];
        $scope.funcId     = [];
        $scope.functional = [];
        $timeout(function () {
            $scope.getModuleAll();
            $('#allocationModal').modal('show');
        },1000);
    };
    $scope.getAuthDetail = function (id) {
        $http.get('/jurisdiction/get-auth-by-role-id?id='+id).then(function (result) {
            $scope.authData = result.data;
            $scope.moduleIdArr   = angular.fromJson($scope.authData.module_id);
            var arr = [];
            for(var item in $scope.moduleIdArr){
                arr.push($scope.moduleIdArr[item]);
            }
            $scope.arrIdModule = arr;
            $scope.functionIdArr = angular.fromJson($scope.authData.function_id);
        });
    };
    /**处理搜索数据***/
    $scope.searchCardData = function () {
        return {
            companyId      : $scope.companyId != undefined ? $scope.companyId : null,
            status         : $scope.status != undefined ? $scope.status : null
        }
    };
    $scope.getInitPath = function () {
        $scope.searchParams =  $scope.searchCardData();
        $scope.initPath =  '/authority/get-auth?' + $.param($scope.searchParams);
    };
    $scope.getRoleList = function () {
        $scope.getInitPath();
        $scope.getAuthRole();
    };
    $scope.getModuleAll = function () {
        $http.get('/jurisdiction/get-module-functional-data-all').then(function (result) {
            $scope.moduleData = result.data;
            $.loading.hide();
        });
    };
    $scope.getCompanyArr = function () {
        $http.get('/rechargeable-card-ctrl/get-company').then(function (result) {
            $scope.companyData = result.data.venue;
        });
    };
    //获取模块ID 功能ID
    $scope.enabled = function () {
       var $gTop = $('div.mGTop').find('span.checked');
       if($gTop.length > 0){
           $gTop.each(function (i) {
               var self = $(this);
               var $pdLr = self.parents('.moduleTop').next().children('.subModule');
               if($pdLr.length > 0){
                   $pdLr.each(function (i) {
                       var $moduleThis = $(this);
                      var $checkbox = $moduleThis.find(':checked');
                      if($checkbox.length > 0){
                          $scope.auth.push(self.attr('value'));
                          $scope.module.push($moduleThis.data('module'));
                           $checkbox.each(function (i) {
                               var $checkedThis = $(this);
                               $scope.functional.push($checkedThis.val());
                               $scope.funcId.push($checkedThis.val());
                           });
                          $scope.modFunc[$moduleThis.data('module')] = $scope.funcId;
                          $scope.funcId = [];
                      }
                   })
               }
           });
       }

    };
    //点击分配权限完成方法
    $scope.assignAuth = function () {
        $scope.assignLoading = true;
        $scope.enabled();
        $scope.params = {
            roleId          : $scope.roleId != undefined ? $scope.roleId : null,
            authId          : $scope.auth   != undefined ? $scope.auth   : null,
            moduleId        : $scope.module != undefined ? $scope.module : null,
            functionalId    : $scope.functional != undefined ? $scope.functional : null,
            modFuncId       : $scope.modFunc    != undefined ? $scope.modFunc :null,
            _csrf_backend : $('#_csrf').val()
        };
        if($scope.params.moduleId.length <= 0){
            $scope.assignLoading = false;
            Message.warning('请选择模块');
            return false;
        }
        if($scope.params.functionalId.length <= 0){
            $scope.assignLoading = false;
            Message.warning('请选择模块');
            return false;
        }
        $scope.setPostModel();
    };
    //修改权限
    $scope.setPostModel = function () {
        $http({
            url        : '/authority/authority',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                Message.success('修改权限成功');
            }else{
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
            $scope.assignLoading = false;
            $scope.getAuthRole();
            $('#allocationModal').modal('hide');
        });
    };
    //调用权限详情模态框
    $scope.checkDetailBtn = function(id){
        $scope.roleId  = id;
        $('#checkDetailModal').modal('show');
    };
    $scope.init();
})
    .filter('inArr',function () {
        return function (value,idArr,mid) {
            if(!value) return false;
            if(!mid) return false;
            if(!idArr) return false;
            if(idArr[mid] != undefined){
                if($.inArray(value,idArr[mid]) != -1){
                    return true;
                }
            }
            return false;
        }
    });
