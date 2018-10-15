/**
 * Created by DELL on 2017/8/28.
 * content: 仓库管理页面js、控制器
 * author: 程丽明
 */
$(function(){
    //场馆
    $('#allVenues').select2();
    $('#applyForVenues').select2();
    //商品类型
    $('#shopTypes').select2();
    $('#applyForTypes').select2();
    //商品品牌
    $('#shopBrands').select2();
    $('#storeIsVenue').select2();
    $('#allotShopVenue').select2();
    // $('#addShopCategory').select2();
    // $('#addShopCompany').select2();
    //时间
    $('#storeDate').daterangepicker(null, function(start, end, label) {
    });
    $('#applyForDate').daterangepicker(null, function(start, end, label) {
    });
})

angular.module('App').controller('storeCtrl',function($scope,$http,$timeout){
    $.loading.show();
    $scope.listsNumbers = 8;
    //时间戳转换为年月日
    $scope.getMyDate = function(str){
        str = parseInt(str);
        if(str!=""||str!=null){
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth()+1;
            oMonth = oMonth>=10? oMonth:'0'+oMonth;
            var oDay = oDate.getDate();
            oDay = oDay>=10? oDay:'0'+oDay;
            var theDate = oYear+"-"+oMonth+"-"+oDay;
        }else{
            theDate = "";
        }
        return theDate
    };

    //初始化获取当月的第一天和最后一天
    $scope.getMonthOneAndMonthLast = function(){
        var date = new Date();
        var startDate =$scope.getMyDate(date.setDate(1));

        var currentMonth=date.getMonth();
        var nextMonth=++currentMonth;
        var nextMonthFirstDay=new Date(date.getFullYear(),nextMonth,1);
        var oneDay=1000*60*60*24;
        var endDate = $scope.getMyDate(nextMonthFirstDay-oneDay);
        $('#storeDate').val(startDate+' - '+ endDate);
        $('#applyForDate').val(startDate+' - '+ endDate);
    }

    //新增仓库场馆
    $("#addWarehouseModal").on("shown.bs.modal", function(){
        $("#storeIsVenue").select2({
            language: "zh-CN",
            dropdownParent:$("#addWarehouseModal")
        });
    });
    //新增商品品类
    $("#addStoreShopModal").on("shown.bs.modal", function(){
        $("#addShopCategory").select2({
            language: "zh-CN",
            dropdownParent:$("#addStoreShopModal")
        });
    });
    //新增商品单位
    $("#addStoreShopModal").on("shown.bs.modal", function(){
        $("#addShopCompany").select2({
            language: "zh-CN",
            dropdownParent:$("#addStoreShopModal")
        });
    });

    //所有仓库
    $("#addStoreShopModal").on("shown.bs.modal", function(){
        $("#addShopStoreName").select2({
            language: "zh-CN",
            dropdownParent:$("#addStoreShopModal")
        });
    });

    //初始化调用
    $scope.getInitData = function(){
        $scope.getMonthOneAndMonthLast();
        $scope.getAllVenue();
        $scope.getAllShopCategoryLists();
        $scope.WarehouseKeywordsSearch();
        $scope.getCurrentEmployeeVenueId();
    }
    /**
     * author:程丽明
     * create:2017-8-30
     * 函数描述：根据/get-venue接口获取所有的场馆
     * */
    //获取所有的场馆
    $scope.getAllVenue = function(){
        $http.get('/main/get-venue').then(function(response){
            $scope.allVenueLists = response.data;
        });
    }
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：根据/get-goods-type-data接口获取所有的商品品类
     * */
    //获取所有的商品品类
    $scope.getAllShopCategoryLists = function(){
        $http.get('/shop/get-goods-type-data').then(function(response){
            $scope.shopCategoryLists = response.data.data;
        });
    }
    /**
     * author:程丽明
     * create:2017-9-19
     * 函数描述：根据/employee-center接口获取登陆的账号所在的场馆id
     * */
    //获取登录账号场馆id
    $scope.getCurrentEmployeeVenueId =function(){
        $http.get('/personnel/employee-center').then(function(response){
            $scope.CurrentLogonId =  response.data.venue_id;
        });
    }
    /**
     * author:程丽明
     * create:2017-10-18
     * 函数描述：根据/get-store-all接口获取新增商品中的仓库
     * */
    //获取新增商品中的仓库
    $scope.getAddShopWarehouse = function(){
        $http.get('/store-management/get-store-all').then(function(response){
            // console.log('response',response)
            $scope.getAllShopWarehouseLists = response.data.data;
        });
    }
    /**
     * author:程丽明
     * create:2017-9-19
     * 函数描述：根据/get-store-data-all接口，传场馆的id，来获取该场馆下的仓库
     * param: id:场馆的id
     * */
    //获取所有仓库
    $scope.getAllWarehouseLists = function(id){
        $scope.Warehouse = '';
        $http.get('/store-management/get-store-data-all?venueId='+id).then(function(response){
            $scope.allWarehouseLists = response.data.data;
        });
    }

    //新增仓库按钮
    $scope.addStoreButton = function(){
        $scope.CompleteAddClassroomButtonFlag = false;
        $scope.addWarehouseName  = '';
        $scope.addWarehouseVenue = '';
        $('#addWarehouseModal').modal('show');
    }

   /*------- 1、新增仓库功能Start--------*/
    $scope.getAddWarehouseData = function(){
        return {
            _csrf_backend :$('#_csrf').val(),
            venueId        :$scope.addWarehouseVenue != undefined && $scope.addWarehouseVenue != ''? $scope.addWarehouseVenue :null,
            name           :$scope.addWarehouseName != undefined && $scope.addWarehouseName != ''? $scope.addWarehouseName :null,
        }
    }
    /**
     * author:程丽明
     * create:2017-8-30
     * 函数描述：新增一个仓库到场馆里
     * param: venueId:场馆的id
     *        name:仓库的名称
     * */
    //完成新增仓库
    $scope.addClassroomComplete = function(){
        if($scope.addWarehouseName == undefined ||  $scope.addWarehouseName == '' ||  $scope.addWarehouseName == null ){
            Message.warning('请输入仓库名称');
            return;
        }
        $scope.CompleteAddClassroomButtonFlag = true;
        $http({
            url:'/store-management/add-store',
            method:'POST',
            data: $.param($scope.getAddWarehouseData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#addWarehouseModal').modal('hide');
                $scope.getAllWarehouseOrderLists();
            }else{
                $scope.CompleteAddClassroomButtonFlag = false;
                Message.warning(response.data.data);
            }
        })
    }
    /*------- 新增仓库功能End--------*/
    //升降序
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

    //仓库升降序
    $scope.changeGoodSort = function (attr,sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);
        $scope.WarehouseKeywordsSearch();
    };


    /*-------2、获取仓库列表start-----*/
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：新增一个仓库到场馆里
     * param: keywords:搜索框输入的关键词
     *        venueId:场馆id
     *        startTime:开始时间
     *        endTime:结束时间
     *        type:仓库的类型
     *        category:商品品类
     *        sortType:排序的类型
     *        sortName:排序的状态
     * */
    $scope.getWarehouseSearchData = function(){
       var homeDate = $('#storeDate').val();
        if(homeDate == ''){
            $scope.WarehouseStartDate = '';
            $scope.WarehouseEndDate   = '';
        }else{
            var startTime = homeDate.substr(0, 10)+' '+ '00:00:01';
            var endTime = homeDate.substr(-10, 10)+' '+ '23:59:59';
            $scope.WarehouseStartDate = startTime;
            $scope.WarehouseEndDate   = endTime;
        }
        return {
            keywords        :$scope.WarehouseKeywords != undefined && $scope.WarehouseKeywords != ''? $scope.WarehouseKeywords :null,
            venueId         :$scope.homePageWarehouseVenue != undefined && $scope.homePageWarehouseVenue != ''? $scope.homePageWarehouseVenue :null,
            startTime       :$scope.WarehouseStartDate != undefined && $scope.WarehouseStartDate != ''? $scope.WarehouseStartDate :null,
            endTime         :$scope.WarehouseEndDate != undefined && $scope.WarehouseEndDate != ''? $scope.WarehouseEndDate :null,
            type             :$scope.WarehouseType != undefined && $scope.WarehouseType != ''? $scope.WarehouseType :null,
            category        :$scope.WarehouseCategory != undefined && $scope.WarehouseCategory != ''? $scope.WarehouseCategory :null,
            // goodsBrands     :$scope.WarehouseBrands != undefined && $scope.WarehouseBrands != ''? $scope.WarehouseBrands :null,
            // goodsModel      :$scope.WarehouseModel != undefined && $scope.WarehouseModel != ''? $scope.WarehouseModel :null,
            sortType       : $scope.sortType           != undefined && $scope.sortType                   != ''  ? $scope.sortType   : null,
            sortName       : $scope.sort                != undefined && $scope.sort                       != ''  ? $scope.sort       : null
        }
    }
    //初始化仓库列表urL
    $scope.getWarehouseOrderUrlInit =function(){
        $scope.WarehouseOrderUrlInit = '/store-management/storehouse-info?' +$.param($scope.getWarehouseSearchData());
    }
    /******Enter键搜索*******/
    $scope.WarehouseSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.WarehouseKeywordsSearch();
        }
    };

    //关键字搜索
    $scope.WarehouseKeywordsSearch = function(){
        $scope.getWarehouseOrderUrlInit()
        $scope.getAllWarehouseOrderLists();
    }

    $scope.ClearSearchSelect = function(){
        $scope.WarehouseKeywords      = '';
        $scope.homePageWarehouseVenue = '';
        $('#storeDate').val('');
        $scope.WarehouseType           = '';
        $scope.WarehouseCategory       = '';
        $('#select2-allVenues-container').text('请选择场馆');
        $('#select2-shopTypes-container').text('商品品类');
        $scope.WarehouseKeywordsSearch();
    }
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：获取仓库的列表
     * param: keywords:搜索框输入的关键词
     *        venueId:场馆id
     *        startTime:开始时间
     *        endTime:结束时间
     *        type:仓库的类型
     *        category:商品品类
     *        sortType:排序的类型
     *        sortName:排序的状态
     * */
    //获取仓库列表
    $scope.getAllWarehouseOrderLists = function(){
        $http.get($scope.WarehouseOrderUrlInit).then(function(response){
            if(response.data.data.length != 0){
                $scope.AllWarehouseOrderLists    = response.data.data;
                $scope.AllWarehouseOrderFlag     = false;
                $scope.AllWarehouseOrderNowPage  = response.data.now;
                $scope.AllWarehouseOrderPages    = response.data.pages;
            }else{
                $scope.AllWarehouseOrderLists    = response.data.data;
                $scope.AllWarehouseOrderFlag     = true;
                $scope.AllWarehouseOrderNowPage  = response.data.now;
                $scope.AllWarehouseOrderPages    = response.data.pages;
            }
            $.loading.hide();
        });
    }

    $scope.replacementPages = function(urlPages){
        $scope.WarehouseOrderUrlInit= urlPages;
        $scope.getAllWarehouseOrderLists();
    }

    /*-------获取仓库列表End-----*/

    //新增商品
    $scope.addShopButton = function(){
        $scope.addShopCategorySelect  = '';//商品品类
        $scope.addShopStoreSelect     = '';//仓库名称
        $scope.addShopTypeSelect      = '';//商品类型
        $scope.addShopCompanySelect   = '';//商品单位
        $scope.addShopGoodName         = '';//商品名称
        $scope.addShopGoodBrandName    = '';//商品品牌
        $scope.addShopGoodModel        = '';//商品型号
        $scope.addShopGoodSupplier     = '';//供应商
        $scope.addShopGoodManufacturer = '';//生产商
        $scope.getAllWarehouseLists();
        $scope.getAddShopWarehouse();
        $scope.CompleteAddShopButtonFlag = false;
        $('#addStoreShopModal').modal('show');
    }

    /*----------3、新增商品Start------------*/
    //自定义商品品类
    $scope.addCustomShopCategory = function(){
        $scope.customShopCategoryCompleteFlag = false;
        $scope.customShopCategoryName = '';
        $('#customShopCategoryModal').modal('show');
    }

    //获取自定义添加商品类型数据
    $scope.getAddCustomShopCategoryData = function(){
        return {
            _csrf_backend :$('#_csrf').val(),
            typeName       :$scope.customShopCategoryName!= undefined && $scope.customShopCategoryName != ''? $scope.customShopCategoryName :null,
        }
    }
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：自定义商品品类添加
     * param: typeName:新增的自定义的商品品类的名称
     * */
    //完成自定义商品品类
    $scope.customShopCategoryComplete = function(){
        if($scope.customShopCategoryName== undefined || $scope.customShopCategoryName == ''|| $scope.customShopCategoryName ==null){
            Message.warning('请输入自定义商品品类');
            return;
        }
        $scope.customShopCategoryCompleteFlag = true;
        // $scope.ClearSearchSelect();
        $http({
            url:'/shop/set-goods-type',
            method:'POST',
            data: $.param($scope.getAddCustomShopCategoryData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#customShopCategoryModal').modal('hide');
                $scope.getAllShopCategoryLists();
                $("#addShopCategory").select2();
                $('#addShopCategory').val(response.data.id);
                $scope.addShopCategorySelect = response.data.id;;
                $scope.addShopCategorySelect123  = response.data.id;
                $('#select2-addShopCategory-container').text($scope.customShopCategoryName);
                $scope.WarehouseKeywordsSearch();
            }else{
                $scope.customShopCategoryCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：删除自定义商品品类
     * param: id:要删除的商品品类的id
     * */
    //删除自定义商品品类
    $scope.delCustomShopCategory = function(id){
        if(id == undefined || id == '' || id == null){
            Message.warning('请选择需要删除的商品品类');
            return;
        }else{
            Sweety.remove({
                url              : "/shop/del-goods?goodsId="+id,
                http             : $http,
                title            : '确定要删除商品品类吗?',
                text             : '删除商品品类后无法恢复',
                confirmButtonText: '确定',
                confirmButton     : '删除',
                data             : {
                    action: 'unbind'
                }
            }, function () {
                $scope.getAllShopCategoryLists();
                $scope.addShopCategorySelect = '';
                $('#addShopCategory').val('');
                $scope.addShopCategorySelect123  = '';
                $('#select2-addShopCategory-container').text('请选择品类');
            },function(){

            },true,true);
        }

    }
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：新增商品，向后台发送数据
     * param: storeId:仓库名称
     *        goodsAttribute:商品类型
     *        goodsTypeId:商品品类
     *        unit:商品单位
     *        goodsName:商品名称
     *        goodsBrand:商品品牌
     *        goodsModel:商品型号
     *        goodsProducer:生产商
     *        goodsSupplier:供应商
     **/
    //新增商品提交数据
    $scope.getAllAddShopData = function(){
        return {
            _csrf_backend    :$('#_csrf').val(),
            storeId          :$scope.addShopStoreSelect!= undefined && $scope.addShopStoreSelect != ''? $scope.addShopStoreSelect :null,//仓库名称
            goodsAttribute   :$scope.addShopTypeSelect!= undefined && $scope.addShopTypeSelect != ''? $scope.addShopTypeSelect :null,//商品类型
            goodsTypeId      :$scope.addShopCategorySelect!= undefined && $scope.addShopCategorySelect != ''? $scope.addShopCategorySelect :null,//商品品类
            unit              :$scope.addShopCompanySelect!= undefined && $scope.addShopCompanySelect != ''? $scope.addShopCompanySelect :null,//商品单位
            goodsName        :$scope.addShopGoodName!= undefined && $scope.addShopGoodName != ''? $scope.addShopGoodName :null,//商品名称
            goodsBrand       :$scope.addShopGoodBrandName!= undefined && $scope.addShopGoodBrandName != ''? $scope.addShopGoodBrandName :null,//商品品牌
            goodsModel       :$scope.addShopGoodModel!= undefined && $scope.addShopGoodModel != ''? $scope.addShopGoodModel :null,//商品型号
            goodsProducer    :$scope.addShopGoodManufacturer!= undefined && $scope.addShopGoodManufacturer != ''? $scope.addShopGoodManufacturer :null,//生产商
            goodsSupplier    :$scope.addShopGoodSupplier!= undefined && $scope.addShopGoodSupplier != ''? $scope.addShopGoodSupplier :null,//供应商
        }
    }

    //完成新增商品
    $scope.addShopComplete = function(){
        if($scope.addShopStoreSelect== undefined || $scope.addShopStoreSelect == ''|| $scope.addShopStoreSelect ==null){
            Message.warning('请选择所要存入的仓库');
            return;
        }

        if($scope.addShopTypeSelect== undefined || $scope.addShopTypeSelect == ''|| $scope.addShopTypeSelect ==null){
            Message.warning('请选择商品类型');
            return;
        }
        if($scope.addShopCategorySelect== undefined || $scope.addShopCategorySelect == ''|| $scope.addShopCategorySelect ==null){
            Message.warning('请选择商品品类');
            return;
        }
        if($scope.addShopCompanySelect== undefined || $scope.addShopCompanySelect == ''|| $scope.addShopCompanySelect ==null){
            Message.warning('请选择商品单位');
            return;
        }
        if($scope.addShopGoodName== undefined || $scope.addShopGoodName == ''|| $scope.addShopGoodName ==null){
            Message.warning('请输入商品名称');
            return;
        }
        $scope.CompleteAddShopButtonFlag = true;
        $http({
            url:'/store-management/add-shop',
            method:'POST',
            data: $.param($scope.getAllAddShopData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#addStoreShopModal').modal('hide');
                $scope.WarehouseKeywordsSearch();
            }else{
                $scope.CompleteAddShopButtonFlag = false;
                Message.warning(response.data.data);
            }
        });
    }

    /*----------新增商品End------------*/



    /*----------4、入库Start------------*/
    //入库
    $scope.enterStorageBtn = function(id){
        $scope. warehouseShopId = id;
        $scope.enterStorageCompleteFlag = false;
        $scope.enterStorageNum   = '';
        $scope.enterStorageMoney = '';
        $scope.enterStorageNote  = '';
        $scope.enterStorageType123 = '';
        $('#enterStorageModal').modal('show');
    }
    /**
     * author:程丽明
     * create:2017-8-31
     * 函数描述：商品入库，发送数据到后台
     * param: goodsId:仓库id
     *        type:入库类型
     *        number:入库数量
     *        note:入库备注
     *        unitPrice:入库单价
     **/
    //完成入库
    $scope.enterStorageComplete = function(){
        var enterStorageData  ={
            _csrf_backend    :$('#_csrf').val(),
            goodsId          :$scope. warehouseShopId,
            type             :$scope.enterStorageType123!= undefined && $scope.enterStorageType123 != ''? $scope.enterStorageType123 :null,//入库类型
            number           :$scope.enterStorageNum!= undefined && $scope.enterStorageNum != ''? $scope.enterStorageNum :null,//入库数量
            note             :$scope.enterStorageNote!= undefined && $scope.enterStorageNote != ''? $scope.enterStorageNote :null,//入库备注
            unitPrice        :$scope.enterStorageMoney!= undefined && $scope.enterStorageMoney != ''? $scope.enterStorageMoney :null,//入库单价
        }
        if($scope.enterStorageType123== undefined || $scope.enterStorageType123 == ''|| $scope.enterStorageType123 ==null){
            Message.warning('请选择入库类型');
            return;
        }
        if($scope.enterStorageNum== undefined || $scope.enterStorageNum == ''|| $scope.enterStorageNum ==null){
            Message.warning('请输入入库数量');
            return;
        }

        $scope.enterStorageCompleteFlag = true;
        $http({
            url:'/store-management/add-access',
            method:'POST',
            data: $.param(enterStorageData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#enterStorageModal').modal('hide');
                $scope.WarehouseKeywordsSearch();
                $scope.getShopDetailLists($scope. warehouseShopId);
                if($scope.shopGoodMOdalId != undefined && $scope.shopGoodMOdalId != '' && $scope.shopGoodMOdalId != null ){
                    $scope.getWarehouseStockAndMoney($scope.shopGoodMOdalId);
                }
            }else{
                $scope.enterStorageCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }
    /*----------入库End------------*/


    /*----------出库Start------------*/
    //出库
    $scope.outerStorageBtn = function(id,object){
        $scope.shopNumMoney = 0;
        $scope.outerStorageRepertoryNum = object.storage_num;
        $scope. warehouseShopId = id;
        $scope.outerStorageCompleteFlag = false;
        $scope.outerStorageNum = '';
        $('#outerStorageModal').modal('show');
    }
    //显示商品详情
    $scope.changeGoodsInfo = function(id,venueId){
        $http.get('/store-management/goods-info?goodsId='+id).then(function(response){
            $scope.goodsInfo = response.data;
        });
        $http.get('/store-management/get-all-stores?venueId='+venueId).then(function(response){
            $scope.stores = response.data;
        });
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.venues = response.data;
        });
        $http.get('/store-management/get-goods-types?venueId='+venueId).then(function(response){
            $scope.types = response.data;
        });
        $('#modifyModal').modal('show');
    };
    //修改
    $scope.done = function(id){
        var goodsInfo = {
            goodsName      : $scope.goodsInfo.goods_name,
            goodsNumber    : $scope.goodsInfo.storage_num,
            storeName      : $scope.goodsInfo.store_name,
            venueId        : $scope.goodsInfo.venue_id,
            goodsAttribute : $scope.goodsInfo.goods_attribute,
            goodsTypeId    : $scope.goodsInfo.goods_type_id,
            brand          : $scope.goodsInfo.goods_brand,
            model          : $scope.goodsInfo.goods_model,
            producer       : $scope.goodsInfo.goods_producer,
            supplier       : $scope.goodsInfo.goods_supplier
        };
        $http({
            url    :'/store-management/update-goods-info?goodsId='+id,
            method :'POST',
            data   :$.param(goodsInfo),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                $scope.WarehouseKeywordsSearch();
                Message.success(response.data);
                $('#modifyModal').modal('hide');
            }else{
                Message.warning(response.data);
            }
        });
    };
    //自定义商品品类弹窗
    $scope.addGoodsType = function(){
        $scope.goodsType = '';
        $('#addGoodsType').modal('show');
    };
   //关闭商品品类自定义模态框
    $(".goodsClose").click(function (){
        $("#addGoodsType").modal("hide");
    });
   //删除商品类型
    $scope.delGoodsType = function(id,venueId){
        $http.get('/store-management/del-goods-type?goodsTypeId='+id).then(function(response){
            if(response.data.status == "success"){
                $http.get('/store-management/get-goods-types?venueId='+venueId).then(function(response){
                    $scope.types = response.data;
                });
                Message.success(response.data);
            }else{
                console.log(response);
                Message.warning(response.data.data);
            }
        });
    };
    //添加商品品类
    $scope.addType = function(venueId,companyId){
        var info = {
            goodsType : $scope.goodsType,
            venueId   : venueId,
            companyId : companyId
        };
        $http({
            url    :'/store-management/add-goods-type',
            method :'POST',
            data   :$.param(info),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                $http.get('/store-management/get-goods-types?venueId='+venueId).then(function(response){
                    $scope.types = response.data;
                });
                Message.success(response.data);
                $('#addGoodsType').modal('hide');
            }else{
                Message.warning(response.data);
            }
        });
    };
    //出库数量限制
    $scope.outerStorageNumChange = function(num){
        $scope.getOuterStorageMoney($scope.warehouseShopId,num);
        if(num != ''){
            var newNum = parseInt(num);
            var allNum = parseInt($scope.outerStorageRepertoryNum);
            if(newNum >= allNum){
                $scope.outerStorageNum  = allNum;
            }
        }
    }
    //根据出库的数量计算金额
    $scope.getOuterStorageMoney = function(ShopId,num){
        if(num != '' && num != undefined && num != null){
            $http.get('/store-management/out-goods?goodsId='+ShopId+'&number=' +num).then(function(response){
                // console.log('出库金额',response);
                $scope.shopNumMoney = response.data;
            })
        }else{
            $scope.shopNumMoney = 0;
        }
    }
    /**
     * author:程丽明
     * create:2017-9-19
     * 函数描述：商品出库，发送数据到后台
     * param: goodsId:仓库id
     *        number:出库的数量
     **/
    //完成出库
    $scope.outerStorageComplete = function(){
        var outerStorageData  ={
            _csrf_backend    :$('#_csrf').val(),
            goodsId          :$scope. warehouseShopId,
            number           :$scope.outerStorageNum!= undefined && $scope.outerStorageNum != ''? $scope.outerStorageNum :null,//入库数量
         }
        if($scope.outerStorageNum == undefined || $scope.outerStorageNum == ''|| $scope.outerStorageNum ==null){
            Message.warning('请输入出库数量');
            return;
        }
        $scope.outerStorageCompleteFlag = true;
        $http({
            url:'/store-management/add-out',
            method:'POST',
            data: $.param(outerStorageData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            // console.log('出库返回数据',response);
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#outerStorageModal').modal('hide');
                $scope.WarehouseKeywordsSearch();
                $scope.getShopDetailLists($scope. warehouseShopId);
                if($scope.shopGoodMOdalId != undefined && $scope.shopGoodMOdalId != '' && $scope.shopGoodMOdalId != null ){
                    $scope.getWarehouseStockAndMoney($scope.shopGoodMOdalId);
                }
            }else{
                $scope.outerStorageCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }

    /*----------出库End------------*/

    /*********调拨Start********/

    //调拨
    $scope.allotShopBtn = function(id,object){
        $scope.allotShopCompleteFlag = false;
        $scope.getAllWarehouseLists();
        $scope.allotShopNum123      = '';
        $scope.allotShopNote        = '';
        $scope.allotShopWarehouseId = '';
        $scope.Warehouse  = '';
        $scope.allotCompany123 = '';
        $scope.getCompanyIdFlag = false;
        //商品库存数量
        $scope.shopRepertoryNum = object.storage_num;
        $scope.warehouseStoreId = object.store_id;
        $scope. warehouseShopId = id;
        $scope.allotShopStoreId = object.store_id;
        $scope.allotShopStoreVenueId = object.venue_id;
        $('#allotShopModal').modal('show');
    }
    /**
     * author:程丽明
     * create:2017-10-17
     * 函数描述：商品调拨
     * param: text:公司名称
     **/
    //输入公司名称时触发
    $scope.allotCompanyInput = function(text){
        if(text != ''){
            $http.get('/store-management/company-name?companyName='+text).then(function(response){
                if(response.data.status == 'success' ){
                    $scope.getCompanyId213 = response.data.data.id;
                    $scope.getAllotVenueLists(response.data.data.id);
                    $scope.getCompanyIdFlag = true;
                    $('#beVenueId').val('');
                }else{
                    Message.warning(response.data.data);
                    $scope.getCompanyIdFlag = false;
                }
            });
        }
    }

    //获取该公司下的场馆
    $scope.getAllotVenueLists = function(id){
        $http.get('/main/get-venue-data?companyId='+id).then(function(response){
            $scope.AllotVenueLists123 = response.data.venue;
        });
    }
    //通过场馆获取改场馆中的仓库
    $scope.allotShopVenueSelect123 = function(id){
        $scope.getAllWarehouseLists(id);
    }

    //选择仓库时获取场馆id
    $scope.allotShopWarehouseIdSelect = function(object){
        if(object != ''){
            var Warehouse = angular.fromJson(object)
            $scope.allotShopWarehouseId = Warehouse.id;
            $scope. allotShopWarehouseVenueId = Warehouse.venue_id
        }else{
            $scope.allotShopWarehouseId = '';
            $scope. allotShopWarehouseVenueId = '';
        }
    }

    //调拨数量输入不能大于库存数量
    // $scope.allotShopNumInput = function(num){
    //     if(num != ''){
    //         var newNum = parseInt(num);
    //         var allNum = parseInt($scope.shopRepertoryNum);
    //         if(newNum >= allNum){
    //             $scope.allotShopNum123 = allNum;
    //         }
    //     }
    // }
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：商品调拨
     * param: beVenueId:场馆id
     *        goodsId:仓库id
     *        number:入库数量
     *        note:入库备注
     *        beStoreId:仓库
     *        storeId：入库单价
     **/
    //完成调拨按钮
    $scope.allotShopComplete1 = function(){
        var allotShopData = {
            _csrf_backend    :$('#_csrf').val(),
            beVenueId         :$('#beVenueId').val(),
            // beVenueId         :$scope. allotVenue123,
            goodsId          :$scope. warehouseShopId,
            number           :$scope.allotShopNum123!= undefined && $scope.allotShopNum123 != ''? $scope.allotShopNum123 :null,//入库数量
            note             :$scope.allotShopNote!= undefined && $scope.allotShopNote != ''? $scope.allotShopNote :null,//入库备注
            // beStoreId        :$scope.Warehouse!= undefined && $scope.Warehouse != ''? $scope.Warehouse :null,//入库单价
            beStoreId        :$('#Warehouse123').val(),
            storeId         :$scope.allotShopStoreId!= undefined && $scope.allotShopStoreId != ''? $scope.allotShopStoreId :null,//入库单价
        }
        if($scope.allotShopNum123 == undefined || $scope.allotShopNum123 == ''|| $scope.allotShopNum123 == null){
            Message.warning('请输入调拨数量');
            return;
        }
        if($scope.allotCompany123 == ''|| $scope.allotShopNum123 == undefined || $scope.allotShopNum123 == null){
            Message.warning('请输入需要搜索的公司');
            return;
        }

        if($('#beVenueId').val() == undefined || $('#beVenueId').val() == ''|| $('#beVenueId').val() ==null){
            Message.warning('请选择场馆');
            return;
        }
        if($('#Warehouse123').val() == undefined || $('#Warehouse123').val() == ''|| $('#Warehouse123').val() ==null){
            Message.warning('请选择仓库');
            return;
        }
        $scope.allotShopCompleteFlag = true;
        $http({
            url:'/store-management/add-mobilise',
            method:'POST',
            data: $.param(allotShopData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#allotShopModal').modal('hide');
                $scope.WarehouseKeywordsSearch();
                $scope.getShopDetailLists($scope. warehouseShopId);
            }else{
                $scope.allotShopCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }


    /*********调拨End********/
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：获取商品详情
     * param: keywords:搜索关键词
     *        type:商品类型
     **/
    //获取商品详情
    $scope.getShopDetailListsData = function(){
        return{
            keywords :$scope.shopOrderKeywords!= undefined && $scope.shopOrderKeywords != ''? $scope.shopOrderKeywords :null,
            type     :$scope.shopStockType123!= undefined && $scope.shopStockType123 != ''? $scope.shopStockType123 :null,
        }
    }

    //获取商品详情列表公共
    $scope.getPublicShopLists = function(){
        $http.get($scope.ShopDetailListsUrlInit).then(function(response){
            if(response.data.details.length != 0){
                $scope.ShopDetailModalListsSum = response.data.chengliming;
                $scope.ShopDetailModalLists = response.data.details;
                $scope.ShopDetailModalListsFlag     = false;
                $scope.ShopDetailModalListsPages    = response.data.pages;
            }else{
                $scope.ShopDetailModalLists = response.data.details;
                $scope.ShopDetailModalListsFlag     = true;
                $scope.ShopDetailModalListsPages    = response.data.pages;
            }
            $.loading.hide();
        });

    }

    //获取商品详情列表数据
    $scope.getShopDetailLists  =function(id){
        $scope.ShopDetailListsUrlInit = '/store-management/get-goods-history?goodsId='+id +'&'+$.param($scope.getShopDetailListsData());
        $scope.getPublicShopLists();
    }

    //商品详情列表分页
    $scope.detailsPages = function(urlPages){
        $scope.ShopDetailListsUrlInit = urlPages;
        $scope.getPublicShopLists();
    }

    //获取商品详情
    $scope.getShopMessage = function(id){
        $http.get('/shop/get-goods-the-detail?goodsId='+ id).then(function(response){
            $scope.shopDetail123 = response.data;
        });
    };

    //商品状态筛选
    $scope.shopStockTypeSelect = function(id){
        var typeId = parseInt(id);
        switch(typeId)
        {
            case 1:
                $scope.shopTypeName = '入库';
                break;
            case 2:
                $scope.shopTypeName = '出库';
                break;
            case 3:
                $scope.shopTypeName = '报损';
                break;
            case 4:
                $scope.shopTypeName = '退货';
                break;
            case 5:
                $scope.shopTypeName = '报溢';
                break;
            case 6:
                $scope.shopTypeName = '入库（调拨）';
                break;
            case 7:
                $scope.shopTypeName = '出库（调拨）';
                break;

        }
        $scope.getShopDetailLists($scope.shopGoodMOdalId);
    }

    //关键字搜索
    $scope.shopOrderBtnClick = function(){
        $scope.getShopDetailLists($scope.shopGoodMOdalId);
    }

    //Enter款借鉴
    $scope.shopOrderSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.shopOrderBtnClick();
        }
    };


    //商品详情
    $scope.shopDetailClick = function(object){
        $.loading.show();
        $scope.shopOrderKeywords = '';
        $scope.shopStockType123  = '';
        $scope.shopDetialObject = object;
        $scope.allotShopStoreId = object.store_id;
        $scope.shopDetailStorageNum = object.storage_num;
        var goodId = object.id;
        if(goodId != null && goodId != '' && goodId != undefined){
            $scope.shopGoodMOdalId = goodId;
            $scope.getWarehouseStockAndMoney($scope.shopGoodMOdalId);
            $scope.getShopMessage($scope.shopGoodMOdalId);
            $scope.getShopDetailLists($scope.shopGoodMOdalId);
            $('#shopDetailModal').modal('show');
        }
    }

    //初始化调用获取剩余库存和金额
    $scope.getWarehouseStockAndMoney = function(id){
        $http.get('/store-management/get-select-goods?goodsId='+$scope.shopGoodMOdalId).then(function(response){
            $scope.WarehouseStockTotalMoney = response.data.totalAmount.totalMoney;
            $scope.WarehouseStockTotalSum   = response.data.totalAmount.totalSum;
            $scope.WarehouseStockUnit       = response.data.totalAmount.unit[0];
        }) ;
    }

    //商品报损、退货、报溢列表
    $scope.shopHandleTypeClick = function(typeNum){
        $scope.shopLossOrderKeywords = '';
        $scope.getShopCurrentInventoryLists();
        // console.log(typeNum);
        if(typeNum == 3){
            $scope.shopHandleType = typeNum;
            $scope.shopHandleTypeName = '报损';
        }
        if(typeNum == 4){
            $scope.shopHandleType = typeNum;
            $scope.shopHandleTypeName = '退货'
        }
        if(typeNum == 5){
            $scope.shopHandleType = typeNum;
            $scope.shopHandleTypeName = '报溢'
        }
        $('#shopLossModal').modal('show');
    }

    //商品报损、退货、报溢数量模态框
    $scope.shopSelect123 = function(goodId,object){
        $scope.shopIdType = goodId;
        $scope.shopWarehousingRecordId = object.id;
        $scope.slectShopRepertoryNum = object.surplus_amount;
        $scope.slectShopRepertoryPrice     = object.gcUnitPrice;
        if($scope.shopHandleType  == 3){
            $scope.shopLossCompleteFlag = false;
            $scope.shopLossNum = '';
            $scope.shopLossRemarks = '';
            $('#shopLossNumModal').modal('show');
        }
        if($scope.shopHandleType  == 4){
            $scope.shopReturnCompleteFlag = false;
            $scope.shopReturnNum = '';
            $scope.shopReturnRemarks = '';
            $('#shopReturnNumModal').modal('show');
        }
        if($scope.shopHandleType  == 5){
            $scope.shopOverflowNum  = '';
            $scope.shopOverflowRemarks = '';
            $scope.shopOverflowCompleteFlag = false;
            $('#shopOverflowNumModal').modal('show');
        }
    }
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：商品退货报损和报溢的列表
     * param: goodsId:仓库id
     *        keywords:商品报损关键词
     **/
    //商品退货、报损、报溢选择列表
    $scope.getShopCurrentInventoryLists = function(){
        var data = {
            goodsId   :$scope.shopGoodMOdalId,
            keywords :$scope.shopLossOrderKeywords!= undefined && $scope.shopLossOrderKeywords != ''? $scope.shopLossOrderKeywords :null,
        }
        $http.get('/store-management/get-select-goods?' + $.param(data)).then(function(response){
            if(response.data.details.length != 0){
                $scope.ShopCurrentInventoryLists        = response.data.details;
                $scope.ShopCurrentInventoryListsFlag     = false;
                $scope.ShopCurrentInventoryListsPages    = response.data.pages;
                $scope.ShopCurrentInventoryListsNow    = response.data.now;
            }else{
                $scope.ShopCurrentInventoryLists         = response.data.details;
                $scope.ShopCurrentInventoryListsFlag     = true;
                $scope.ShopCurrentInventoryListsPages    = response.data.pages;
                $scope.ShopCurrentInventoryListsNow    = response.data.now;
            }
        })
    }

    //订单搜索
    $scope.shopLossOrderBtnClick = function(){
        $scope.getShopCurrentInventoryLists();
    }

    $scope.shopLossOrderSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.shopLossOrderBtnClick();
        }
    }


    //商品退货、报损、报溢 分页
    $scope.selectPages = function(urlPage){
        $http.get(urlPage).then(function(response){
            if(response.data.details.length != 0){
                $scope.ShopCurrentInventoryLists        = response.data.details;
                $scope.ShopCurrentInventoryListsFlag     = false;
                $scope.ShopCurrentInventoryListsPages    = response.data.pages;
                $scope.ShopCurrentInventoryListsNow    = response.data.now;
            }else{
                $scope.ShopCurrentInventoryLists         = response.data.details;
                $scope.ShopCurrentInventoryListsFlag     = true;
                $scope.ShopCurrentInventoryListsPages    = response.data.pages;
                $scope.ShopCurrentInventoryListsNow    = response.data.now;
            }
        })
    }
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：商品报损
     * param: goodsId:仓库id
     *        number:报损数量
     *        note:报损原因备注
     *        status:3
     *        goodChangeId:商品id
     *        unitPrice:入库单价
     **/
    //商品报损完成
    $scope.shopLossComplete = function(){
        var shopLossData = {
            _csrf_backend   :$('#_csrf').val(),
            goodsId          :$scope. shopIdType,
            number           :$scope.shopLossNum!= undefined && $scope.shopLossNum != ''? $scope.shopLossNum :null,//入库数量
            note             :$scope.shopLossRemarks!= undefined && $scope.shopLossRemarks != ''? $scope.shopLossRemarks :null,//入库备注
            status           :3,
            goodsChangeId   :$scope.shopWarehousingRecordId,
            unitPrice        :$scope.slectShopRepertoryPrice
        }
        if($scope.shopLossNum == undefined || $scope.shopLossNum == ''|| $scope.shopLossNum == null){
            Message.warning('请输入报损数量');
            return;
        }
        if($scope.shopLossRemarks == undefined || $scope.shopLossRemarks == ''|| $scope.shopLossRemarks == null){
            Message.warning('请输入报损原因');
            return;
        }
        $scope.shopLossCompleteFlag = true;
        $http({
            url:'/store-management/add-breakage',
            method:'POST',
            data: $.param(shopLossData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#shopLossNumModal').modal('hide');
                $scope.getShopMessage($scope.shopGoodMOdalId);
                $scope.getShopDetailLists($scope.shopGoodMOdalId);
                $scope.getWarehouseStockAndMoney($scope.shopGoodMOdalId);
                $scope.getShopCurrentInventoryLists();
                $scope.WarehouseKeywordsSearch();

            }else{
                $scope.shopLossCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });

    }

    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：商品退货
     * param: goodsId:仓库id
     *        number:退货数量
     *        note:退货原因备注
     *        goodChangeId:商品id
     *        unitPrice:入库单价
     **/
    //商品退货完成
    $scope.shopReturnComplete = function(){
        var shopReturnData = {
            _csrf_backend   :$('#_csrf').val(),
            goodsId          :$scope. shopIdType,
            number           :$scope.shopReturnNum!= undefined && $scope.shopReturnNum != ''? $scope.shopReturnNum :null,//入库数量
            note             :$scope.shopReturnRemarks!= undefined && $scope.shopReturnRemarks != ''? $scope.shopReturnRemarks :null,//入库备注
            goodsChangeId   :$scope.shopWarehousingRecordId,
            unitPrice        :$scope.slectShopRepertoryPrice
        }
        if($scope.shopReturnNum == undefined || $scope.shopReturnNum == ''|| $scope.shopReturnNum == null){
            Message.warning('请输入退货数量');
            return;
        }
        if($scope.shopReturnRemarks == undefined || $scope.shopReturnRemarks == ''|| $scope.shopReturnRemarks == null){
            Message.warning('请输入退货原因');
            return;
        }
        $scope.shopReturnCompleteFlag = true;
        $http({
            url:'/store-management/add-return',
            method:'POST',
            data: $.param(shopReturnData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#shopReturnNumModal').modal('hide');
                $scope.getShopMessage($scope.shopGoodMOdalId);
                $scope.getShopDetailLists($scope.shopGoodMOdalId);
                $scope.getWarehouseStockAndMoney($scope.shopGoodMOdalId);
                $scope.getShopCurrentInventoryLists();
                $scope.WarehouseKeywordsSearch();

            }else{
                $scope.shopReturnCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }

    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：商品报溢
     * param: goodsId:仓库id
     *        number:报溢数量
     *        note:报溢原因备注
     *        status:5
     *        goodChangeId:商品id
     *        unitPrice:入库单价
     **/
    //商品报溢完成
    $scope.shopOverflowComplete = function(){
        var shopOverflowData = {
            _csrf_backend   :$('#_csrf').val(),
            goodsId          :$scope. shopIdType,
            number           :$scope.shopOverflowNum!= undefined && $scope.shopOverflowNum != ''? $scope.shopOverflowNum :null,//入库数量
            note             :$scope.shopOverflowRemarks!= undefined && $scope.shopOverflowRemarks != ''? $scope.shopOverflowRemarks :null,//入库备注
            status           :5,
            goodsChangeId   :$scope.shopWarehousingRecordId,
            unitPrice        :$scope.slectShopRepertoryPrice
        }
        if($scope.shopOverflowNum == undefined || $scope.shopOverflowNum == ''|| $scope.shopOverflowNum == null){
            Message.warning('请输入报溢数量');
            return;
        }
        if($scope.shopOverflowRemarks == undefined || $scope.shopOverflowRemarks == ''|| $scope.shopOverflowRemarks == null){
            Message.warning('请输入报溢原因');
            return;
        }
        $scope.shopLossCompleteFlag = true;
        $http({
            url:'/store-management/add-breakage',
            method:'POST',
            data: $.param(shopOverflowData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#shopOverflowNumModal').modal('hide');
                $scope.getShopMessage($scope.shopGoodMOdalId);
                $scope.getShopDetailLists($scope.shopGoodMOdalId);
                $scope.getWarehouseStockAndMoney($scope.shopGoodMOdalId);
                $scope.getShopCurrentInventoryLists();
                $scope.WarehouseKeywordsSearch();

            }else{
                $scope.shopLossCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }
    //商品报损，退货、报溢数量输入
    $scope.inputShopNum123 = function(inputNum){
        var nums = parseInt($scope.slectShopRepertoryNum);
        var inputNum = parseInt(inputNum);
        if($scope.shopHandleType  == 3){
            if(inputNum >= nums){
                $scope.shopLossNum = nums;
            }
        }
        if($scope.shopHandleType  == 4){
            if(inputNum >= nums){
                $scope.shopReturnNum = nums;
            }
            
        }
        if($scope.shopHandleType  == 5){
            if(inputNum >= nums){
                $scope.shopOverflowNum = nums;
            }
        }
    }
    //点击仓库管理
    $scope.warehouseClick = function(){
        $scope.WarehouseKeywordsSearch();
    }
    /***********************调拨申请****************************/
    //点击调拨按钮
    $scope.allocationClick = function(){
        $.loading.show();
        $scope.applyForVenue    = '';
        $scope.applyForType     = '';
        $scope.applyForKeywords = '';
        $scope.sortType          = 'ht_time';
        $scope.sort              = 'DES';
        $scope.getApplyForListsInit();
    }
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：商品报损
     * param: keywords:申请输入的关键词
     *        type:类型
     *        startTime:开始时间
     *        endTime:结束时间
     *        venueId:场馆的did
     *        sortType:排序的类型
     *        sortName:排序的状态
     **/
    //调拨申请页面筛选数据
    $scope.getAllocationFiltrateData = function(){
        var homeDate = $('#applyForDate').val()
        if(homeDate == '' || homeDate == undefined || homeDate == null){
            $scope.applyForStartDate = '';
            $scope.applyForEndDate   = '';
        }else{
            var startTime = homeDate.substr(0, 10)+' '+ '00:00:01';
            var endTime = homeDate.substr(-10, 10)+' '+ '23:59:59';
            $scope.applyForStartDate = startTime;
            $scope.applyForEndDate   = endTime;
        }
        return{
            keywords  :$scope.applyForKeywords!= undefined && $scope.applyForKeywords != ''? $scope.applyForKeywords :null,
            type      :$scope.applyForType!= undefined && $scope.applyForType != ''? $scope.applyForType :null,
            startTime :$scope.applyForStartDate!= undefined && $scope.applyForStartDate != ''? $scope.applyForStartDate :null,
            endTime   :$scope.applyForEndDate!= undefined && $scope.applyForEndDate != ''? $scope.applyForEndDate :null,
            venueId   :$scope.applyForVenue != undefined && $scope.applyForVenue != ''? $scope.applyForVenue :null,
            sortType  : $scope.sortType           != undefined && $scope.sortType                   != ''  ? $scope.sortType   : null,
            sortName  : $scope.sort                != undefined && $scope.sort                       != ''  ? $scope.sort       : null
        }
    }
    //初始url
    $scope.getApplyForUrlInit = function(){
        $scope.ApplyForUrlInit = '/store-management/mobilise-info?'+ $.param($scope.getAllocationFiltrateData());
    }
    //获取调拨申请列表
    $scope.getApplyForListsData = function(){
        $http.get($scope.ApplyForUrlInit).then(function(response){
            if(response.data.data != 0){
                $scope.ShopApplyForLists         = response.data.data;
                $scope.ShopApplyForListsFlag     = false;
                $scope.ShopApplyForListsPages    = response.data.pages;
                $scope.ShopApplyForListsNow      = response.data.now;
            }else{
                $scope.ShopApplyForLists         = response.data.data;
                $scope.ShopApplyForListsNow      = response.data.now;
                $scope.ShopApplyForListsFlag     = true;
                $scope.ShopApplyForListsPages    = response.data.pages;
            }
            $.loading.hide();
        });
    }
    //分页
    $scope.transfersPages = function(urlPages){
        $scope.ApplyForUrlInit = urlPages;
        $scope.getApplyForListsData();
    }
    //初始调拨申请列表
    $scope.getApplyForListsInit = function(){
        $scope.getApplyForUrlInit();
        $scope.getApplyForListsData();
    }
    //清空调拨筛选
    $scope.applyForClearSearch = function(){
        $scope.applyForKeywords = '';
        $scope.applyForType     = '';
        $('#applyForDate').val('');
        $scope.applyForVenue    = '';
        $('#select2-applyForVenues-container').text('请选择场馆');
        $('#select2-applyForTypes-container').text('商品类型');
        $scope.applyForSearch();
    }
    //调拨申请列表筛选
    $scope.applyForSearch = function(){
        $scope.getApplyForListsInit();
    }
    $scope.applyForEnterSearch = function(e){
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.getApplyForListsInit();
        }
    }
    //升降序
    $scope.switchApplyForSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    //仓库升降序
    $scope.changeApplyForSort = function (attr,sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchApplyForSort(sort);
        $scope.getApplyForListsInit();
    };
    //确定通过调拨按钮
    $scope.allocationOrderAdopt = function(id){
        $http.get('/store-management/update-two?mobiliseTypeId=' +id).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $scope.getApplyForListsInit();
            }else{
                Message.warning(response.data.data);
            }
        });
    }
    //拒绝通过按钮
    $scope.allocationOrderRefuse = function(object){
        $scope.RefuseAllocationContent  = '';
        $scope.RefuseMobiliseId = object.mobilise_id;
        $scope.RefuseMobiliseTypeId = object.mobiliseTypeId;
        $scope.RefuseAllocationCompleteFlag = false;
        $('#RefuseAllocationOrderModal').modal('show');
    }
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：拒绝调拨
     * param: mobiliseId:拒绝的id
     *        mobiliseTypeId:拒绝的类型
     *        rejectNote:拒绝的原因
     **/
    //拒绝完成按钮
    $scope.RefuseAllocationComplete = function(){
        var RefuseAllocationData = {
            _csrf_backend    :$('#_csrf').val(),
            mobiliseId       :$scope. RefuseMobiliseId,
            mobiliseTypeId   :$scope.RefuseMobiliseTypeId!= undefined && $scope.RefuseMobiliseTypeId != ''? $scope.RefuseMobiliseTypeId :null,//入库数量
            rejectNote        :$scope.RefuseAllocationContent!= undefined && $scope.RefuseAllocationContent != ''? $scope.RefuseAllocationContent :null,//入库备注
        }
        if($scope.RefuseAllocationContent == undefined || $scope.RefuseAllocationContent == ''|| $scope.RefuseAllocationContent == null){
            Message.warning('请输入拒绝原因');
            return;
        }
        $scope.RefuseAllocationCompleteFlag = true;
        $http({
            url:'/store-management/update-four',
            method:'POST',
            data: $.param(RefuseAllocationData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#RefuseAllocationOrderModal').modal('hide');
                $scope.getApplyForListsInit();
            }else{
                $scope.RefuseAllocationCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }
    //调拨申请详情模态框
    $scope.AllocationDetailClick = function(object){
        $scope.allocationObject = object;
        $scope.getApplyForShopDetail(object.goodsId);
        $scope.getApplyForStatusLists(object.mobiliseTypeId);
        $('#AllocationOrderDetailModal').modal('show');
    }
    //获取调拨详情商品信息
    $scope.getApplyForShopDetail =function(id){
        $http.get('/store-management/get-goods-detail?goodsId=' + id).then(function(response){
            $scope.ApplyForShopDetailMess = response.data;
        });
    }
    //调拨申请中的确定调拨按钮
    $scope.allocationOrderClick = function(object){
        $scope.CompleteAllocationOrderButtonFlag = false;
        $scope.allocationOrderDetailMess = object;
        var data = {
            beStoreId: object.be_store_id,
            goodsAttribute:object.goods_attribute,
            unit   :object.unit,
            goodsName:object.goods_name,
            goodsTypeId:object.goodsTypeId,
            goodsTypeName:object.goods_type,
        }
        $http.get('/store-management/get-new-goods-id?'+$.param(data)).then(function(response){
            $scope.allocationOrderNewGoodsId = response.data.id;
            $scope.getAllocationOrderMoney(response.data.id,object.num);
            $('#allocationOrderModal').modal('show');
        });
        // $scope.getAllocationOrderMoney(object.newGoodsId,object.num);
        // $('#allocationOrderModal').modal('show');
    }
    //根据出库的数量计算金额
    $scope.getAllocationOrderMoney = function(ShopId,num){
        if(num != '' && num != undefined && num != null){
            $http.get('/store-management/out-goods?goodsId='+ShopId+'&number=' +num).then(function(response){
                $scope.AllocationOrderMoney = response.data;
            })
        }else{
            $scope.AllocationOrderMoney = 0;
        }
    }
    /**
     * author:程丽明
     * create:2017-9-1
     * 函数描述：拒绝调拨
     * param: oldGoodsId:旧商品id
     *        newGoodsId:新商品id
     *        number:数量
     *        oldStoreId:旧仓库id
     *        newStoreId:新仓库id
     **/
    //调拨完成
    $scope.allocationOrderComplete = function(){
        var allocationOrderData = {
            _csrf_backend   :$('#_csrf').val(),
            oldGoodsId       :$scope.allocationOrderDetailMess.goodsId,
            newGoodsId       :$scope.allocationOrderNewGoodsId,
            number           :$scope.allocationOrderDetailMess.num,
            oldStoreId       :$scope.allocationOrderDetailMess.store_id,
            newStoreId       :$scope.allocationOrderDetailMess.be_store_id,
            mobiliseId       :$scope.allocationOrderDetailMess.mobilise_id,
        }
        $scope.CompleteAllocationOrderButtonFlag = true;
        $http({
            url:'/store-management/mobilise-complete-form',
            method:'POST',
            data: $.param(allocationOrderData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $('#allocationOrderModal').modal('hide');
                $scope.getApplyForListsInit();
            }else{
                $scope.CompleteAllocationOrderButtonFlag = false;
                Message.warning(response.data.data);
            }
        });
    }
    //获取调拨详情列表
    $scope.getApplyForStatusLists = function(id){
        $http.get('/store-management/get-goods-info?mobiliseTypeId=' + id).then(function(response){
            $scope.ApplyForStatusListsMess = response.data;
        });
    }
    $scope.getInitData();
});
