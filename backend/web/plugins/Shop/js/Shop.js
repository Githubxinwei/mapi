/**
 * Created by 杨大侠 on 2017/6/5.
 */

angular.module('App').controller('indexCtrl', function($scope,$http) {
    
    $scope.pageInitUrl = '/shop/shop-list?keywords=' + ''
    // 主页列表搜素
    $scope.listDatas = function () {
        $.loading.show();
        $http({method:'get',url:$scope.pageInitUrl}).then(function (data) {
            if(data.data.data == "" || data.data.data == undefined || data.data.data.length == undefined){
                $scope.listOfGoodsDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }else{
                $scope.listOfGoodsDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            };
            $.loading.hide();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
            $.loading.hide();
        });
    };
    $scope.listDatas();
    //按下回车搜索
    $scope.enterSearch = function(e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){
            $http({method:'get',url:'/shop/shop-list?topSearch=' + $scope.keywords}).then(function (data) {
                if (data.data.data.length != 0) {
                    $scope.listOfGoodsDatas = data.data.data
                    $scope.pages = data.data.pages;
                    $scope.dataInfo = false;
                    $scope.shopShow   = false;
                }else{
                    $scope.listOfGoodsDatas = data.data.data
                    $scope.pages = data.data.pages;
                    $scope.shopShow   = true;
                };
            },function (error) {
                console.log(error);
                Message.error("系统错误请联系管理人员");
            });
        };
    };
    //按下按钮搜索
    $scope.searchButton = function () {
        $http({method:'get',url:'/shop/shop-list?topSearch='+$scope.keywords}).then(function (data) {
            if (data.data.data.length != 0) {
                $scope.listOfGoodsDatas = data.data.data
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
                $scope.shopShow   = false;
            }else{
                $scope.pages = data.data.pages;
                $scope.shopShow   = true;
            }
            $scope.listOfGoodsDatas = data.data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };
    //排序
    $scope.changeSort = function (type,name) {
        $scope.sortType = type;             //排
        // 序字段
        $scope.switchSort(name);            //准备排序状态
        $http({method:'get',url:'/shop/shop-list?sortType='+$scope.sortType+'&sortName='+$scope.sort}).then(function (data) {
            $scope.listOfGoodsDatas = data.data.data;
            $scope.pages = data.data.pages;
            $scope.dataInfo = false;
            $scope.shopShow   = false;

        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };
    //处理正序、倒序
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        };
        $scope.sort = sort;//排序状态
    };
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.listDatas();
    };
    $scope.getDataPage = function () {
        $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.listOfGoodsDatas = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.listOfGoodsDatas = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            };
            $.loading.hide();
        });
    };
    //初始化新增页面数据
    $scope.increaseAddData = {
        commodityCategory:'',
        commodityName:'',
        commodityBrand:'',
        commodityModel:'',
        itemPricing:'',
        manufacturer:'',
        supplier:'',
        id:'',
        unit:'',
    };
    //自定义商品
    $scope.confirmAdd = function (datas) {
        if(datas == undefined){
            Message.warning("请输入自定义类别");return;
        };
        var data = {
            typeName:datas,
            _csrf_backend: $('#_csrf').val()
        };
        $http({method:"post",url:'/shop/set-goods-type',data: $.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status == 'error'){
                Message.warning(data.data.data)
                return;
            }else if (data.data.status == 'success'){
                $scope.categoryAddData();
                $scope.increaseAddData.id  = data.data.id;
                $scope.increaseAddData.commodityCategory  = data.data.id;
                $scope.goodsTypeId = data.data.id;
                $("#exampleModal").hide();
                $(".modal-backdrop").hide();
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        });
    };
    //获取商品类型 赋值下拉框
    $scope.categoryAddData = function (id) {
        $scope.increaseAddData.id = id;
        $http({method:'get',url:'/shop/get-goods-type-data'}).then(function (data) {
            $scope.commodityData = data.data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    $scope.categoryAddData();
    //删除类别
    $scope.deleteCategory = function (id) {
        Sweety.remove({
            url              : "/shop/del-goods?goodsId="+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.categoryAddData()
        },function(){

        },true);
    };
    //商品增加
    $scope.commodityIncreaseAdd = function () {
        var  data = {
            goodsTypeId:$scope.increaseAddData.id,//类别ID
            goodsName:$scope.increaseAddData.commodityName, //商品名称
            goodsBrand:$scope.increaseAddData.commodityBrand, //商品品牌
            unitPrice:$scope.increaseAddData.itemPricing, //商品单价
            goodsModel:$scope.increaseAddData.commodityModel, //商品型号
            goodsProducer:$scope.increaseAddData.manufacturer, //生产商
            goodsSupplier:$scope.increaseAddData.supplier, //供应商
            unit:$scope.increaseAddData.unit, //单位
            scenario:'insert', // 场景
            goodsAttribute:$('#shopType').val() != undefined ? $('#shopType').val() : null ,
            _csrf_backend:$('#_csrf').val(),
        };
        if (data.goodsTypeId == ""||data.goodsTypeId == null|| data.goodsTypeId == undefined){
            Message.warning("请选择商品类别");return;
        };
        if (data.unit == ""||data.unit == null|| data.unit == undefined){
            Message.warning("请选择商品单位");return;
        };
        if (data.goodsName == ""||data.goodsName == null|| data.goodsName == undefined){
            Message.warning("请输入商品名称");return;
        };
        if (data.goodsBrand == ""||data.goodsBrand == null|| data.goodsBrand == undefined){
            Message.warning("请输入商品品牌");return;
        };
        if (data.goodsModel == "" ||data.goodsModel == null||data.goodsModel == undefined){
            Message.warning("请输入商品型号");return;
        };
        if (data.unitPrice == "" ||data.unitPrice == null||data.unitPrice == undefined){
            Message.warning("请输入商品单价");return;
        };
        $http({method:'post',url:'/shop/set-goods-data',data: $.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status == "success"){
                $scope.listDatas();
                $("#commodityIncrease").hide();
                $(".modal-backdrop").hide();
                $scope.increaseAddData = '';
                $('#editShopType').val("")
                Message.success(data.data.data);
            };
            if(data.data.status == "error"){
                $scope.listDatas();
                $("#commodityIncrease").hide();
                $(".modal-backdrop").hide();
                $scope.increaseAddData = '';
                $('#editShopType').val("")
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //商品详情页面数据渲染
    $scope.commodityDetailsIdlist = function () {
        $http({method:'get',url:'/shop/get-goods-history?goodsId='+$scope.commodityDetailsId}).then(function (data) {
            if (data.data.length != 0) {
                $scope.commodityDetailsDataList = data.data
                $scope.shopDetailShow   = false;
            }else{
                $scope.shopDetailShow   = true;
            };
            $scope.commodityDetailsDataList = data.data;
            $("#myModals2").show();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
        $http({meodth:'get',url:'/shop/get-goods-the-detail?goodsId='+$scope.commodityDetailsId}).then(function (data) {
            $scope.commodityDetailsDatessssss = data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //商品详情
    $scope.commodityDetails = function (id,unit,type,shopNumber) {
        $scope.commodityDetailsId = id;
        $scope.commodityDetailsUnit = unit;
        $scope.goodsTypeId = type;
        $scope.shopNumber = shopNumber;
        $scope.commodityDetailsIdlist();
    }
    //商品入库
    $scope.goodsWarehousing = function (id,unit) {
        $scope.goodsWarehousingId = id;
        $scope.commodityDetailsUnit = unit;
        $("#goodsWarehousingModel").show();
    }
    // 入库取消按钮
    $scope.quxiao = function(){
        $('#goodsWarehousingModel').hide();
        $("#goodsWarehousingModel").modal("toggle");//去掉阴影层
    }
    // 出库取消按钮
    $scope.ckqx = function(){
        $('#goodsOutOfStock').hide();
        $('#goodsOutOfStock').modal("toggle");
    }
    // 详情入库
    $scope.rkqx = function(){
        $('#goodsWarehousingModel1').hide();
        $('#goodsWarehousingModel1').modal("toggle");
    }
    // 详情出库
    $scope.detailck = function(){
        $('#goodsOutOfStock1').hide();
        $('#goodsOutOfStock1').modal('toggle');
    }
    //商品入库完成
    $scope.goodsWarehousingAdd = function (number,Warehouse) {
        if (number == undefined ){
            Message.warning("请填写数量");return;
        };
        if (Warehouse == undefined ){
            Message.warning("请填写单号");return;
        };
        var data = {
            scenario:'storage',
            goodsId:$scope.goodsWarehousingId ,
            number:number,
            listNum:Warehouse,
            unit:$scope.commodityDetailsUnit,
            _csrf_backend:$('#_csrf').val(),
        };
        $http({method:'post',url:'/shop/out-in-storage',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $scope.listDatas();
                $scope.warehouseReceiptNo = '';
                $scope.warehousingType = '';
                $scope.warehousingQuantity = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.listDatas();
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
        $("#goodsWarehousingModel").hide();
        $(".modal-backdrop").hide();
    };
    //详情页入库
    $scope.goodsWarehousing1 = function () {
        $("#goodsWarehousing1").show();
    };
    //详情页入库add
    $scope.goodsWarehousing1Add = function (number,Warehouse) {
        if (number == undefined ){
            Message.warning("请填写数量");return;
        };
        if (Warehouse == undefined ){
            Message.warning("请填写单号");return;
        };
        var data = {
            scenario:'storage',
            goodsId:$scope.commodityDetailsId,
            number:number,
            listNum:Warehouse,
            unit:$scope.commodityDetailsUnit,
            _csrf_backend:$('#_csrf').val(),
        };
        $http({method:'post',url:'/shop/out-in-storage',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $("#goodsWarehousingModel1").hide();
                $(".modal-backdrop").hide();
                $scope.listDatas();
                $scope.commodityDetailsIdlist();
                $scope.warehouseReceiptNo1 = '';
                $scope.warehousingType1 = '';
                $scope.warehousingQuantity1 = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.listDatas();
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    // 商品出库
    $scope.goodsOutOfStock = function (id,unit) {
        $scope.goodsOutOfStockId = id;
        $scope.commodityDetailsUnit = unit;
        $("#goodsOutOfStock").show();
    };
    $scope.goodsOutOfStockAdd = function (number,listNumber) {
        if (number == undefined ){
            Message.warning("请填写商品入库");return;
        };
        if(listNumber == undefined){
            Message.warning("请输入单号");return;
        };
        var data = {
            scenario:'library',
            goodsId:$scope.goodsOutOfStockId,
            number:number,
            unit:$scope.commodityDetailsUnit,
            listNum:listNumber,
        };
        $http({method:'post',url:'/shop/out-in-storage',data:$.param(data),headers:{'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $("#goodsOutOfStock").hide();
                $(".modal-backdrop").hide();
                $scope.listDatas();
                $scope.goodsOutOfStockListNum = '';
                $scope.goodsOutOfStockNumber = '';
                $scope.goodsOutOfStockListUnit = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.listDatas();
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        });
    };
    //商品详情 出库
    $scope.goodsOutOfStock1 = function () {$("#goodsOutOfStock1").show();};
    //商品详情页 出库完成
    $scope.goodsOutOfStock1Add = function (number,listNumbers) {
        if (number == undefined){
            Message.warning("请填写出库");return;
        };
        if(listNumbers == undefined){
            Message.warning('请填写商品单号');return ;
        };
        var data = {
            scenario:'library',
            goodsId:$scope.commodityDetailsId,
            number:number,
            unit:$scope.commodityDetailsUnit,
            listNum:listNumbers,
        };
        $http({method:'post',url:'/shop/out-in-storage',data:$.param(data),headers:{'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $("#goodsOutOfStock1").hide();
                $(".modal-backdrop").hide();
                $scope.listDatas();
                $scope.commodityDetailsIdlist()
                $scope.goodsOutOfStockListNums = '';
                $scope.goodsOutOfStockNumber1 = '';
                $scope.goodsOutOfStockListUnit1 = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.listDatas();
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
        $("#goodsOutOfStock1").hide();
        $(".modal-backdrop").hide();
    };
    //获取商品类型 赋值下拉框
    $scope.categoryAddDatas = function (id) {
        $scope.increaseAddData.id = id;
        $http({method:'get',url:'/shop/get-goods-type-data'}).then(function (data) {
            $scope.commodityCategorySelectData = data.data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    $scope.commodityCategoryAddDataSelect = function (id) {
        $scope.goodsTypeId = id;
    };
    //商品详情 修改
    $scope.commodityModification = function () {
        $scope.categoryAddDatas();
        $http({method:'get',url:'/shop/get-goods-detail?id='+$scope.commodityDetailsId}).then(function (data) {
            $scope.commodityModificationUpdata = data.data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
        $("#commodityModification").show();
    };
    //商品详情 修改完成 
    $scope.completeModificationAdd = function () {
        var data = {
            goodsTypeId:$scope.goodsTypeId,//类型ID
            goodsId:$scope.commodityDetailsId,
            goodsName:$scope.commodityModificationUpdata.goods_name, //商品名称
            goodsBrand:$scope.commodityModificationUpdata.goods_brand, //商品品牌
            unitPrice:$scope.commodityModificationUpdata.unit_price, //商品单价
            goodsModel:$scope.commodityModificationUpdata.goods_model, //商品型号
            goodsProducer:$scope.commodityModificationUpdata.goods_producer, //生产商
            goodsSupplier:$scope.commodityModificationUpdata.goods_supplier, //供应商
            unit:$scope.commodityModificationUpdata.unit,
            scenario:'update', // 场景
            goodsAttribute:null,//商品类型ID
            _csrf_backend:$('#_csrf').val(),
        }
        // if($('#editShopType').val() ==''){
        //     Message.warning('请选择商品类型！');
        //     return;
        // }
        $http({method:'post',url:'/shop/set-goods-data',data: $.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $scope.listDatas();
                $scope.commodityDetailsIdlist();
                $("#commodityModification").hide();
                $(".modal-backdrop").hide();
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.listDatas();
                $scope.commodityDetailsIdlist();
                $("#commodityModification").hide();
                $(".modal-backdrop").hide();
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //商品详情 商品报损
    $scope.commodityLoss = function () {
        $("#commodityLoss").show();
    }
    $scope.commodityLossAdd = function (number,text) {
        if ($scope.shopNumber == null){
            Message.warning("您现在还没有入库商品");return;
        };
        if (number == undefined ){
            Message.warning("请填写数量");return;
        };
        if (text == undefined){
            Message.warning("请填写备注");return;
        };
        var data = {
            scenario:'damage',
            goodsId:$scope.commodityDetailsId,
            number:number,
            describe:text,
            unit:$scope.commodityDetailsUnit,
            _csrf_backend:$('#_csrf').val(),
        };
        $http({method:'post',url:'/shop/damage-overflow',data: $.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $("#commodityLoss").hide();
                $(".modal-backdrop").hide();
                $scope.listDatas();
                $scope.commodityDetailsIdlist()
                $scope.commodityLossNumber = '';
                $scope.commodityLossText = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.listDatas();
                $scope.commodityDetailsIdlist();
                $scope.commodityLossNumber = '';
                $scope.commodityLossText = '';
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //商品详情 退货
    $scope.merchandiseReturns = function () {
        $("#merchandiseReturns").show();
    };
    $scope.merchandiseReturnsAdd = function (number,text) {
        if ($scope.shopNumber == null){
            Message.warning("您现在还没有入库商品");return;
        };
        if (number == undefined ){
            Message.warning("请填写数量");return;
        };
        if (text == undefined){
            Message.warning("请填写备注");return;
        };
        var data ={
            scenario:'quiteGoods',
            goodsId:$scope.commodityDetailsId,
            describe:text,
            number:number,
            unit:$scope.commodityDetailsUnit,
            _csrf_backend:$('#_csrf').val(),
        }
        $http({method:'post',url:'/shop/out-in-storage',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $scope.commodityDetailsIdlist();
                $("#merchandiseReturns").hide();
                $(".modal-backdrop").hide();
                $scope.listDatas();
                $scope.merchandiseReturnsText = '';
                $scope.merchandiseReturnsNumber = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.commodityDetailsIdlist();
                $scope.listDatas();
                $scope.merchandiseReturnsText = '';
                $scope.merchandiseReturnsNumber = '';
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };
    //商品详情 报溢
    $scope.commodityOverflow = function () {
        $("#commodityOverflow").show();
    };
    $scope.commodityOverflowAdd = function (number,text) {
        if ($scope.shopNumber == null){
            Message.warning("您现在还没有入库商品");return;
        };
        if (number == undefined ){
            Message.warning("请填写数量");return;
        };
        if (text == undefined){
            Message.warning("请填写备注");return;
        };
        var data ={
            scenario:'overflow',
            goodsId:$scope.commodityDetailsId,
            describe:text,
            number:number,
            unit:$scope.commodityDetailsUnit,
            _csrf_backend:$('#_csrf').val(),
        };
        $http({
            method:'post',
            url:'/shop/damage-overflow',
            data:$.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            if(data.data.status =="success"){
                $("#commodityOverflow").hide();
                $(".modal-backdrop").hide();
                $scope.commodityDetailsIdlist()
                $scope.listDatas();
                $scope.commodityOverflowText = '';
                $scope.commodityOverflowNumber = '';
                Message.success(data.data.data);
            };
            if (data.data.status == 'error'){
                $scope.commodityDetailsIdlist()
                $scope.listDatas();
                Message.error(data.data.data);
            };
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
        });
    };
});