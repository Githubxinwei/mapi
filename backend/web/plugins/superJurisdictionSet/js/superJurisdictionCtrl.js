/**
 * Created by 程丽明 on 2017/7/5.
 * author:程丽明 chengliming@itsports.club
 * content:权限设置页面控制器
 */

angular.module('App').controller('superJurisdictionCtrl',function($scope,$http,$timeout){

    //初始化url,获取所欲的公司，初始化搜索路径
    $scope.init = function () {
        $scope.pageInitUrl = '/authority/get-company';
        $scope.getAllBrand();
        $scope.initPath();
    };

    $scope.backPages = function(){
        history.back(-1);
    }
    //设置权限
    $scope.selectRole2    = function(){
        $('#setRelevance').modal('show');
    }
    //获取其它品牌其它品牌
    $scope.getAllOtherCompany    = function(){
        $http.get('/authority/get-brand?companyId='+$scope.brandId).then(function(response){
            if(response.data.data.length >0){
                $scope.relevanceCompanyLists = response.data.data;
                $scope.initCompanyId = response.data.data[0].id;
                $scope.getAllVenue($scope.initCompanyId,0);
            }else{
                Message.warning('您好，没有可关联的公司！')
                return;
            }
        });
    }

    //关联其它品牌
    $scope.otherBrandClick = function(){
        $('#isSynchronization').addClass('checked');
        $scope.isFlag = true;
        $scope.getAllOtherCompany();

        $timeout(function(){
            $('#otherBrand').modal('show');
            $('.companyLists').eq(0).addClass('colorGreen').siblings('.companyLists').removeClass('colorGreen');
        },800);

    };
    //获取关联公司对应的场馆
    $scope.getAllVenue  = function(id,ind){
        // $scope.venueInfo         = false;
        $('.companyLists').eq(ind).addClass('colorGreen').siblings('.companyLists').removeClass('colorGreen');
        $http.get('/authority/get-venue?companyId=' + id).then(function(response){
            $timeout(function(){
                var $venueLen = $('.relevanceInputs').length;
                if(response.data.data.length != 0){
                    $scope.relevanceVenueLists    =  response.data.data;
                    $scope.venueInfo         = false;
                }else{
                    $scope.relevanceVenueLists    =  response.data.data;
                    $scope.venueInfo         = true;
                }
            },800)
        });
    }


    /**处理搜索数据***/
    $scope.searchEmployeeData = function () {
        return {
            keywords       : $scope.keywords    != undefined  ? $scope.keywords   : null,//关键字
            sortType       : $scope.sortType    != undefined  ? $scope.sortType   : null,//升？降？
            sortName       : $scope.sort        != undefined  ? $scope.sort       : null//升降序名称
        }
    };
    //根据不同参数获取不同的路径
    $scope.initPath = function () {
        $scope.searchParams =  $scope.searchEmployeeData();
        $scope.pageInitUrl =  '/authority/get-company?' + $.param($scope.searchParams);
    };

    //点击分页
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getAllBrand();
    };

    //实现品牌搜索功能
    $scope.searchBrand      = function(){
        $scope.initPath();
        $scope.getAllBrand();
    }
    /******Enter键搜索*******/
    $scope.enterSearch = function(e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.searchBrand(); }
    };

    //获取所有的公司
    $scope.getAllBrand      = function(){
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function(response){
            if(response.data.data.length != 0){
                $scope.brandLists      = response.data.data;
                $scope.pages           = response.data.pages;
                $scope.dataInfo        = false;
                $scope.searchData      = false;
            }else{
                $scope.brandLists       = response.data.venue;
                $scope.pages            = response.data.pages;
                if($scope.searchParams != null){
                    $scope.searchData  = true;
                    $scope.dataInfo    = false;
                }else{
                    $scope.dataInfo    = true;
                }
            }
            $.loading.hide();
        });
    }
    $scope.init();
    //停用公司品牌
    $scope.blockUpBrand     = function(id){
        $http.get('/authority/update-status?organId='+id ).then(function(response){
            $scope.getAllBrand();
            window.location.reload();
        })
    };


    //获取所有的角色
    $scope.getAllRole       = function(id){
        $scope.roleKeywords = '';
        $scope.searchRoleButtonFlag = false;
        $('#selectRole').modal('show');
        $scope.brandId = id;
        $http.get('/role/get-role?companyId='+ $scope.brandId).then(function(response){
            if(response.data.data.length != 0){
                $scope.roleLists        = response.data.data;
                $scope.roleInfo         = false;
            }else{
                $scope.roleLists        = response.data.data;
                $scope.roleInfo         = true;
            }
        });
    };

    //角色搜索功能
    $scope.searchRoleButton = function(){
        $scope.searchRoleButtonFlag = true;
        $http.get('/role/get-role?companyId='+ $scope.brandId +'&name=' + $scope.roleKeywords).then(function(response){
            $scope.searchRoleButtonFlag = false;
            if(response.data.data.length != 0){
                $scope.roleLists        = response.data.data;
                $scope.roleInfo         = false;
            }else{
                $scope.roleLists        = response.data.data;
                $scope.roleInfo         = true;
            }
        });
    }

    //聚焦后角色搜索
    $scope.enterRoleSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.searchRoleButton(); }

    }

    //  选择角色中停用角色
    $scope.blockUpRole       = function(id){
        $http.get('/role/update-status?roleId='+ id).then(function(response){
            $scope.getAllRole($scope.brandId);
            window.location.reload();
        });
    }

    //设置角色权限
    $scope.setRole        = function(id,name){
        $scope.isFlag        = false;
        $scope.roleId        = id;//角色ID
        $scope.roleName      = name;//角色name
        $scope.authIds       = [];//顶级模块ID
        $scope.moduleIds     = [];//模块ID
        $scope.modFuncIds    = [];//功能ID
        $scope.companyIds    = [];//公司ID
        $scope.venueIds      = [];//场馆ID
        $scope.modFunc       = [];
        $scope.funcId        = [];
        $scope.syncRoleId    = null;
        $scope.venueModuleId = id;
        localStorage.setItem('venueModuleData',JSON.stringify({
            id:$scope.venueModuleId,
            name:$scope.roleName
        }));
        window.location.href='/super-jurisdiction-set-ctrl/set';
        //添加返回数组为空时即可隐藏
        // $scope.getSynchronizationVenue =[];

        $scope.getRoleModule()
        $scope.getCompanyAllRole();
        $scope.getDefaultJurisdiction();
        $scope.getSelectVenues();
        // $('#setJurisdiction').modal('show');
        $('#selectRole').modal('hide');

    }
    // //点击确定不同步
    // $scope.isOutSync = function(){
    //     $http.get('/authority/no-sync?roleId='+ $scope.venueModuleId).then(function(response){
    //         if(response.data.status == 'success'){
    //             Message.success(response.data.data);
    //             $('#setRelevance').modal('hide');
    //             $('#setJurisdiction').modal('show');
    //             $scope.getDefaultJurisdiction();
    //         }else{
    //             angular.forEach(response.data.data,function (value,key) {
    //                 Message.warning(value);
    //             });
    //         }
    //     });
    // }
}).filter('venueChecked',function ($timeout) {
    return function (id,idArr) {
        var $length = idArr.length;
        if($length > 0){
            $.each(idArr,function (i) {
                if(parseInt(id) == parseInt(idArr[i].id)){
                    return true;
                }
            });
        }
        return false;
    }
});
