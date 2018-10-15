angular.module('App').controller('contractCtrl',function($scope,$http){

    /**
     * 后台 - 合同管理- 分页（合同主界面数据遍历）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/25
     */

    //初始化页面加载数据
    $scope.init = function () {
        $scope.pageInitUrl = '/contract/deal';
        $scope.getDealModel();
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pageInitUrl = '/contract/deal?page='+value;
            $scope.getDealModel();
        }
    };

    //获取公司信息
    $scope.getCompanyOptions = function (companyId) {
        $http.get('/site/get-auth-company').then(function (result) {
            if (result.data != undefined && result.data != "") {
                $scope.optionCompany = result.data;
            }
        });
    };

    // 初始化公司数据
    $scope.getCompanyOptions();

    //获取场馆信息
    $scope.getVenueOptions   = function (companyId) {
        $scope.optionVenue = [];
        if(companyId == ''){
            $scope.venue_id  = '';
        }else {
            $http.get('/rechargeable-card-ctrl/get-venue-data?companyId=' + companyId).then(function (result) {
                if (result.data.venue != undefined && result.data.venue != "") {
                    $scope.optionVenue = result.data.venue;
                }
            });
        }
    };
    // 初始化场馆数据
    // $scope.getVenueOptions();
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".js-example-basic-single2").select2();

    });
    //接收搜索参数
    $scope.searchModel = function(){
        return{
            nowVenueId :$scope.venue_id != undefined && $scope.venue_id != '' ? $scope.venue_id : null,
            nowCompanyId:$scope.company_id != undefined && $scope.company_id != '' ? $scope.company_id : null,
            searchContent : $scope.searchContent != undefined&&$scope.searchContent!= "" ? $scope.searchContent  : null,    //综合字段搜索、
            sortType     : $scope.sortType        != undefined ? $scope.sortType   : null,    //需要排序的字段名字
            sortName     : $scope.sort            != undefined ? $scope.sort   :null
        }
    };
    $scope.searchEmployee = function () {
        $.loading.show();
        $http({method:'get',url:'/contract/deal?'+$.param($scope.searchModel())}).then(function (result) {
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
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    };
    

    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getDealModel();
    };
    // 获取后台组织架构数据
    $scope.getDealModel = function () {
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
    //初始化加载数据
    $scope.init();
    /**
     * 后台 - 合同管理- 单条数据删除
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/25
     */
    $scope.del=function(id,name){
        Sweety.remove({
            url: "/contract/del-data?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '合同删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getDealModel();
        });
    };
    /**
     * 后台 - 组织架构管理 - 执行主界面数据搜索
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */
    //接受搜索参数
    // $scope.searchModel = function(){
    //     return {
    //         nowVenueId :$scope.venue_id != undefined && $scope.venue_id != '' ? $scope.venue_id : null,
    //         nowCompanyId:$scope.company_id != undefined && $scope.company_id != '' ? $scope.company_id : null
    //         searchContent : $scope.searchContent != undefined&&$scope.searchContent!= "" ? $scope.searchContent  : null,    //综合字段搜索、
    //         sortType     : $scope.sortType        != undefined ? $scope.sortType                                    : null,    //需要排序的字段名字
    //         sortName     : $scope.sort            != undefined ? $scope.sort                                        : null    //排序的类型
    //     }
    // };
    /**整理搜索参数以及搜索url**/
    $scope.initPath = function (){
        $scope.searchParams      =  $scope.searchModel();
        $scope.pageInitUrl       =  '/contract/deal?' + $.param($scope.searchParams);
    };
    //执行最终搜索
    $scope.search = function() {
        $scope.initPath();
        $scope.getDealModel();
    };
    /******Enter键搜索*******/
    $scope.enterSearch = function(e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.search();}
    };

    /**
     * 后台 - 合同管理- 主界面各个字段排序
     *author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/25
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
   /****添加合同***/
   $scope.getDealSaveData = function () {
       $scope.desc = $('.note-editable').eq(1).html();
        return {
           name     : $scope.name,
           dealType : $scope.dealType,
           desc     : $scope.desc,
            type    : $scope.dealClassType,
           _csrf_backend : $('#_csrf').val()
        };
   };
    //判断合同名称是否存在
    $scope.setDealName = function (name) {
        $http.get('/contract/set-deal-name?name='+name).success(function (result) {
            $scope.status = result.status;
        });
    };
    //添加合同
    $scope.dealSave = function () {
        $scope.params = $scope.getDealSaveData();
        if($scope.params.name == '' || $scope.params.name == undefined){
            Message.warning('合同名称不能为空');
            return false;
        }
        if($scope.params.dealType == '' || $scope.params.dealType == undefined){
            Message.warning('请选择合同类型');
            return false;
        }
        if($scope.params.type == '' || $scope.params.type == undefined || $scope.params.type == null ) {
            Message.warning('请选择合同分类！');
            return false;
        }
        if($scope.params.desc == '' || $scope.params.desc == undefined || $scope.params.desc == '<p><br></p>'){
            Message.warning('合同内容不能为空');
            return false;
        }
        $scope.twoInit();
        if ($scope.status == 'error'){
            Message.warning('合同名称已经存在');
            return false;
        }
        $scope.path   = '/contract/deal-save-post';
        $scope.setHttp();
        // $scope.getDealModel();
        $scope.init();
        $(".note-editable").text("");
        $("#myModals4").modal("hide");
    };
    //查询合同类型
    $scope.getDealTypeInfo = function () {
        $scope.twoInit();
        $('.note-editable').eq(1).html("")
        $http.get('/contract/get-deal-type-data').then(function (result) {
            if (result.data.data != undefined && result.data.data != "") {
                $scope.dealData = result.data.data;
                $('#exampleInputName2').val('');
                $('#textarea').val('');
                // $scope.dealStauts = true;
            } else {
                $scope.dealData = '暂无数据';
                // $scope.dealStauts = false;
            }
        })
    };
    //初始化
    $scope.twoInit = function () {
        $scope.dealTypeName = '';
        $scope.name          = '';
        $scope.dealType      = '';
        $scope.desc          = '';
        $scope.dealClassType = '';
    };
    //云运动 - 前台 - 卡种信息添加(和后台数据交互)
    $scope.setHttp = function () {
        $scope.twoInit();
        $http({
            url        : $scope.path,
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                Message.success(result.data.data);
            }else{
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                });
                return false;
            }
        });
    };
    /*********修改合同**********/
    $scope.dealOne = function (id) {
        $scope.getDealType();
        $http.get('/contract/get-deal-one?id='+id).then(function (result) {
            console.log(result);
              $scope.dataOne = result.data.data;
            $('.note-editable').html($scope.dataOne.intro)
        })
    };
    //获取合同修改数据
    $scope.getUpdateOne = function () {
        $scope.dataOne.desc = $('.note-editable').html();
       return {
           desc : $scope.dataOne.desc,
           name : $scope.dataOne.name,
           dealType : $scope.dataOne.deal_type_id,
           dealId : $('#dealId').val(),
           type : $scope.dataOne.type,
           _csrf_backend : $('#_csrf').val()
       }
    };
    //修改合同
    $scope.dealUpdate = function () {
        $scope.params = $scope.getUpdateOne();
        if($scope.params.name == '' || $scope.params.name == undefined || $scope.params.name == null){
            Message.warning('合同名称不能为空');
            return false;
        }else if($scope.params.dealType == '' || $scope.params.dealType == undefined || $scope.params.name == null){
            Message.warning('请选择合同类型');
            return false;
        }else if($scope.params.desc == '' || $scope.params.desc == undefined || $scope.params.name == null || $scope.params.desc == '<p><br></p>'){
            Message.warning('合同内容不能为空');
            return false;
        }
        if($scope.params.type == '' || $scope.params.type == undefined || $scope.params.type == null ) {
            Message.warning('请选择合同分类！');
            return false;
        }
        $scope.twoInit();
        if ($scope.status == 'error'){
            Message.warning('合同名称已经存在');
            return false;
        }
        $scope.path   = '/contract/deal-update-post';
        $scope.setHttp();
        $scope.getDealModel();
        $(".note-editable").text("");
        $("#myModals2").modal("hide");
        $scope.getDealModel();
    };
    /*********类型获取****************/
    $scope.getDealType = function () {
      $http.get('/contract/get-deal-type-data').then(function (result) {
          if(result.data.data == undefined || result.data.data == ''){
              $scope.dealTypeNoData = true;
          }else{
              $scope.dealTypeNoData = false;
          }
          $scope.dealTypeData = result.data.data;
      });
    };
    //获取新增数据
    $scope.getDealTypeInsertOne = function () {
        return {
            typeName : $scope.dealTypeName,
            _csrf_backend : $('#_csrf').val()
        }
    };
    //判断合同类型名称是否存在
    $scope.setDealTypeName = function (dealTypeName) {
        $http.get('/contract/deal-type-name?name='+dealTypeName).success(function (result) {
            $scope.status = result.status;
        });
    };
    //新增合同类型
    $scope.insertDealType = function () {
        $scope.params = $scope.getDealTypeInsertOne();
        if($scope.params.typeName == '' || $scope.params.typeName == undefined){
            Message.warning('合同类型名称不能为空');
            return false;
        }
        $scope.twoInit();
        if ($scope.status == 'error'){
            Message.warning('合同类型名称已经存在');
            return false;
        }
        $scope.path   = '/contract/deal-type-insert-post';
        $scope.setHttp();
        $scope.getDealType();
        $("#myModals12").modal("hide");
    };
    //删除合同类型
    $scope.delDealType = function (id,name) {
        Sweety.remove({
            url: '/contract/del-deal-type?id='+id,
            http: $http,
            title: '确定要删除吗?',
            text: '合同类型删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getDealType();
        },function () {

        },true);
    };
    //获取类型单条数据
    $scope.updateDealType = function (id) {
        $http.get('/contract/get-deal-type-one?id='+id).then(function (result) {
            $scope.dataTypeOne = result.data.data;
        })
    };
    //修改合同类型
    $scope.getDealTypeUpdateOne = function () {
        return {
            typeName : $scope.dataTypeOne.type_name,
            dealTypeId : $('#dealTypeId').val(),
            _csrf_backend : $('#_csrf').val()
        }
    };
    $scope.updateDealTypeOne = function () {
        $scope.params = $scope.getDealTypeUpdateOne();
        if($scope.params.typeName == '' || $scope.params.typeName == undefined || $scope.params.typeName == null){
            Message.warning('合同类型名称不能为空！');
            return false;
        }
        $scope.twoInit();
        if ($scope.status == 'error'){
            Message.warning('合同类型名称已经存在');
            return false;
        }
        $scope.path   = '/contract/deal-type-update-post';
        $scope.setHttp();
        $scope.getDealType();
        $("#myModals11").modal("hide");
    };
    //获取合同详情
    $scope.contractDetail = function (id) {
        $http.get('/contract/get-deal-one?id='+id).then(function (result) {
            $scope.dealData = result.data.data;
            $('#contractContentDetail').html($scope.dealData.intro);
        })
    };
});
