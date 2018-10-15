/**
 * Created by DELL on 2017/9/8.
 */
/**
 * Created by 程丽明 on 2017/7/5.
 * 权限设置页面控制器
 */


angular.module('App').controller('jurisdictionSetCtrl',function($scope,$http,$timeout){
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
        },500);

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
            },500)
        });
    }


    /**处理搜索数据***/
    $scope.searchEmployeeData = function () {
        return {
            keywords       : $scope.keywords    != undefined  ? $scope.keywords   : null,
            sortType       : $scope.sortType    != undefined  ? $scope.sortType   : null,
            sortName       : $scope.sort        != undefined  ? $scope.sort       : null
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
        });
    }
    // $scope.init();
    //停用公司品牌
    $scope.blockUpBrand     = function(id){
        $http.get('/authority/update-status?organId='+id ).then(function(response){
            $scope.getAllBrand();
        })
    }


    //获取所有的角色
    $scope.getAllRole       = function(id){
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

    }
    $scope.completeSetFlag  = false;

    $.loading.show();
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

    //点击对应的功能开关
    $scope.switchToggle    = function(ind){
        var $funcModule = $('.funcLists');
        var $length = $('.funcLists').length;
        $funcModule.each(function(i,item){
            var $dataModule = $('.funcLists').eq(i).data('module');
            if(parseInt($dataModule) == parseInt(ind)){
                if(!$('.funcLists').eq(i).children('.switch').hasClass('checked')){
                    //console.log('000')
                    var $checkBox = $('.funcLists').eq(i).parents('.moduleTop').next('.subModule').find('input');
                    $checkBox.each(function(i,item){
                        //$checkBox.eq(i).removeAttr('checked');
                        //设置checked属性需要用prop，不能使用attr
                        $checkBox.eq(i).prop('checked',false);
                    });
                }else{
                    //console.log('111')
                    var $checkBox = $('.funcLists').eq(i).parents('.moduleTop').next('.subModule').find('input');
                    $checkBox.each(function(i,item){
                        $checkBox.eq(i).prop('checked',true);
                        //console.log( $checkBox.eq(i).prop('checked'))
                    });
                }
            }
        });
    }

    //点击input框判断是否被选中
    $scope.inputToggle   = function($event){
        var $input = $event.target;
        var $parent = $($input).parents('.subModule');
        var $parentPre = $parent.siblings('.moduleTop');
        var $allInput = $parent.find('input');
        $scope.numFlag = false;
        $allInput.each(function(i){
            var $inputCheckBox = $(this);
            if($(this).prop('checked')){
                $scope.numFlag = true;
            }
            if($scope.numFlag){
                $parentPre.children('.funcLists').children('.switch').addClass('checked');
            }else{
                $parentPre.children('.funcLists').children('.switch').removeClass('checked');
            }
        });
    }

    //获取其它场馆对应的角色
    $scope.getCompanyAllRole = function(){
        $http.get('/authority/auth-role').then(function(response){
            $scope.allCompanyRoleLists = response.data.data;
        });
    }

    //获取默认的权限设置
    $scope.getDefaultJurisdiction = function(){
        $http.get('/jurisdiction/get-module-functional-data-all').then(function(response){
            $scope.jurisdictionSetData = response.data;
            $.loading.hide();
        });
    }

    //删除不关联的场馆
    $scope.removeVenue   =  function(ind){
        $scope.getSynchronizationVenue.splice(ind,1)
    }

    //返回权限模板
    $scope.getRoleModule  =function(){
        $timeout(function(){
            $http.get('/authority/get-auth-by-role?roleId='+ $scope.venueModuleId).then(function(response){
                if(response.data.data != null){
                    // if(response.data.data.sync_role_id != null && response.data.data.sync_role_id != undefined){
                    $scope.authData = response.data.data;
                    $scope.moduleIdArr   = angular.fromJson($scope.authData.module_id);
                    var arr = [];
                    for(var item in $scope.moduleIdArr){
                        arr.push($scope.moduleIdArr[item]);

                        $scope.arrIdModule = arr;
                        $scope.functionIdArr = angular.fromJson($scope.authData.function_id);
                        for(var i in $scope.functionIdArr){
                            if($.inArray(i,arr) != -1){
                                for( var j=0;j<$('.funcLists').length;j++){
                                    if(parseInt(i) == $('.funcLists').eq(j).data('module')){
                                        $('.funcLists').eq(j).children('.switch').addClass('checked');
                                        var funcLists = $('.funcLists').eq(j).parents('.moduleTop').next('.subModule').find('input');
                                        var $func = $scope.functionIdArr[i];
                                        for(var g=0;g<funcLists.length;g++){
                                            for(var f = 0;f<=g;f++){
                                                if(parseInt($func[f]) == funcLists.eq(g).val()){
                                                    funcLists.eq(g).attr('checked','checked');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $.loading.hide();
                    }
                }
            });
        },500);
    }

    //选择角色渲染对应的功能模块模板
    $scope.selectRoleTemplate    = function(id){

        $('#isSynchronization').removeClass('checked');
        $scope.syncRoleId    = id;
        $scope.getDefaultJurisdiction();
        $scope.venueModuleId = id;
        $scope.getRoleModule();
        $scope.getSelectVenues();
    }

    //获取公司对应的场馆
    // $scope.getCompanyVenues    = function(){
    //     $http.get('/authority/get-venue?companyId='+ $scope.brandId).then(function(response){
    //         $scope.getSynchronizationVenue   = response.data.data;
    //
    //     });
    // }


    //将关联的品牌显示出来
    $scope.getSelectVenues = function(){
        $http.get('/super-jurisdiction-set-ctrl/get-venue?roleId='+ $scope.venueModuleId).then(function(response){
            if(response.data == 'null'){
                $scope.getSynchronizationVenue   = [];
                $scope.checkedVenues();
            }else{
                $scope.getSynchronizationVenue   = response.data;
                $scope.checkedVenues();
            }
        });
    }


    //selcet关联的场馆
    $scope.checkedVenues = function(){
        $timeout(function(){
            var $length = $scope.getSynchronizationVenue.length;
            if($length > 0){
                $('#isSynchronization').addClass('checked');
                for(var i=0;i<$length;i++){
                    $('.isSynchronizationVenue ').eq(i).find('.checkBoxW').attr('checked','checked');
                }
            }
        },500);
    }

    //将选中的功能公司等存入数组
    $scope.functionModule     = function(){
        $scope.authIds       = [];//顶级模块ID
        $scope.moduleIds     = [];//模块ID
        $scope.modFuncIds    = [];//功能ID
        $scope.venueIds      = [];//场馆ID
        $scope.modFunc       = [];
        $scope.funcId        = [];

        //查找所有的顶级功能
        var $auth  = $('.authLists');
        if($auth.length > 0){
            $auth.each(function(i){
                var self= $(this);
                var $funcModule = self.find('section span.checked');
                $funcModule.each(function(i){
                    var $moduleThis = $(this);
                    var $checkbox = $moduleThis.parents('.funcLists').parents('.moduleTop').next('.subModule').find(':checked');
                    if($checkbox.length >0){
                        $scope.authIds.push(self.data('value'));
                        $scope.moduleIds.push($moduleThis.parents('.funcLists').data('module'));
                        $checkbox.each(function(i){
                            var $checkedThis = $(this);
                            $scope.modFuncIds.push($checkedThis.val());
                            $scope.funcId.push($checkedThis.val())
                        });
                        $scope.modFunc[$moduleThis.parents('.funcLists').data('module')] = $scope.funcId;
                        $scope.funcId = [];
                    }
                });
            });
        }
    }

    //判断是否同步
    $scope.isSynchronization    = function(){
        if(!$scope.isFlag){
            $('#isSynchronization').addClass('checked');
            $scope.isFlag = true;
        }else{
            $('#isSynchronization').removeClass('checked');
            $scope.isFlag = false;
        }
    }
    //当同步时将对应的公司和场馆存入数组
    $scope.getSynchronizationVenueLists  = function(){
        // $scope.companyIds.push($scope.brandId);
        if($('#isSynchronization').hasClass('checked')){
            var $synchronizationVenue  = $('.isSynchronizationVenue').find(':checked');
            if($synchronizationVenue.length > 0){
                $synchronizationVenue.each(function(i){
                    var $checkedThis = $(this);
                    $scope.venueIds.push($checkedThis.val());
                    $scope.companyIds.push($checkedThis.data('company'));
                });
            }
        }else{
            $scope.venueIds   =[];
            $scope.companyIds =[];
        }
    }

    //关闭选择关联的公司场馆
    $scope.selectRelevance   =  function(){
        $('#otherBrand').modal('hide');
        // var $relevanceVenueInput = $('.relevanceInputs').find(':checked');
        // if($relevanceVenueInput.length >0 ){
        //         $relevanceVenueInput.each(function(i){
        //             var $venueThis = $(this);
        //             var companyId  = $venueThis.data('company');
        //             var venueId    = $venueThis.val();
        //             var venueName  = $venueThis.data('venue');
        //             $scope.getSynchronizationVenue.push({'pid':companyId,'id':venueId,'name':venueName});
        //         });
        // }
    };
    $scope.isChecked = function (id) {
        var idArr = $scope.getSynchronizationVenue;
        var $length = idArr.length;
        if($length > 0){
            for (var i = 0;i<$length;i++){
                if(parseInt(id) == parseInt(idArr[i].id)){
                    return true;
                }
            }
        }
        return false;
    };
    //选择添加场馆
    $scope.addCompanyVenue = function ($event,venue) {
        if($event.target.checked){
            var companyId  = venue.pid;
            var venueId    = venue.id;
            var venueName  = venue.name;
            $scope.getSynchronizationVenue.push({'pid':companyId,'id':venueId,'name':venueName});
            $scope.checkedVenues();
            return true;
        }else{
            var $length = $scope.getSynchronizationVenue.length;
            if($length > 0){
                companyId  = venue.pid;
                venueId    = venue.id;
                venueName  = venue.name;
                for(var i = 0;i<$length;i++){
                    if(venueId == $scope.getSynchronizationVenue[i].id){
                        $scope.getSynchronizationVenue.splice(i,1)
                        $scope.checkedVenues();
                        return;
                    }
                };
            }
        }
    };

    //完成权限设置
    $scope.completeSet           = function(){
        $scope.functionModule();
        $scope.getSynchronizationVenueLists();
        $scope.setData = function(){
            return{
                roleId:  $scope.roleId         != undefined ? $scope.roleId    : null,//角色id
                authId: $scope.authIds         != undefined ? $scope.authIds    : null,//顶级模块ID
                moduleId:$scope.moduleIds      != undefined ? $scope.moduleIds  : null,//模块ID
                // modFuncId:$scope.modFuncIds    != undefined ? $scope.modFuncIds :null,//单个功能id功能ID
                companyId:$scope.companyIds    != undefined ? $scope.companyIds :null,//公司ID
                venueId  :$scope.venueIds       != undefined ? $scope.venueIds   :null,//场馆ID
                modFuncId :$scope.modFunc         != undefined ? $scope.modFunc   :null,//键值对功能ID
                syncRoleId :$scope.syncRoleId != undefined ? $scope.syncRoleId    :null,//同步角色ID
                _csrf_backend:$('#_csrf').val()
            }
        }

        if($scope.moduleIds.length <= 0){
            Message.warning('请选择模块');
            return ;
        }

        if($scope.modFunc.length <= 0){
            Message.warning('请选择模块');
            return ;
        }
        $scope.completeSetFlag  = true;
        $http({
            url        : '/authority/auth-brand',
            method     : 'POST',
            data       :  $.param($scope.setData()),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                $scope.backPages();
                Message.success(result.data.data);
            }else{
                $scope.completeSetFlag  = false;
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        });
    }

    var buyCardData1 = localStorage.getItem('venueModuleData');
    var buyCardData2 = angular.fromJson(buyCardData1);
    if(buyCardData2 != '' && buyCardData2 != undefined && buyCardData2 !=null){
        $scope.venueModuleId = buyCardData2.id;
        $scope.isFlag        = false;
        $scope.roleId        = buyCardData2.id;//角色ID
        $scope.roleName      = buyCardData2.name;//角色name
        $scope.authIds       = [];//顶级模块ID
        $scope.moduleIds     = [];//模块ID
        $scope.modFuncIds    = [];//功能ID
        $scope.companyIds    = [];//公司ID
        $scope.venueIds      = [];//场馆ID
        $scope.modFunc       = [];
        $scope.funcId        = [];
        $scope.syncRoleId    = null;
        $scope.getRoleModule()
        $scope.getCompanyAllRole();
        $scope.getDefaultJurisdiction();
        $scope.getSelectVenues();
    }
    //返回上一页
    $scope.backPages = function(){
        history.back(-1);
    }
    //点击取消
    $scope.clearSet = function(){
        window.location.href = "/super-jurisdiction-set-ctrl/index?class='templetA'"
    }

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
