//服务套餐页面控制器
var app = angular.module('App');
app.controller('serveCtrl',function($scope,$http){

    $.loading.show();                                       //打开页面
    $scope.init              = function () {
        // $scope.initPaths();                                         //公共路径
        // $scope.getAllServerCombo();                                 //查询所有服务套餐
        $scope.serverTypeArr = [];                                  //空数组 存放服务
        $scope.serverComboName = "";                                //初始化服务套餐名
        $scope.initServerPath();
        $scope.getAllServer();
        setTimeout("$.loading.hide()",1000);                        //隐藏页面
    };
    //接收服务种类
    $scope.getServerType     = function () {
        var serverLists = $("#serveLists");                         //元素对象
        var div         = serverLists.children("div.checkbox");     //元素对象
        div.each(function (i,ele) {
            if($(this).children('label').children('div').hasClass('checked')){
                $scope.serverTypeArr.push($.trim($(this).children('label').text()));        //把服务加到数组中
            }
        });
    };
    /**
     * author:黄鹏举
     * create:2017-05-04
     * 函数描述:添加服务的数据准备
     * param: serverComboName:服务套餐名称
     *        serverArr:服务名称的数组
     * */
    //接收添加数据
    $scope.getServerAllData  = function () {
        $scope.getServerType();
        return {
            serverComboName : $scope.serverComboName,                  //服务套餐名称
                  serverArr : $scope.serverTypeArr,                    //服务名称 数组
            _csrf_backend   : $('#_csrf').val()                        //防止csrf                                             //csrf
        }
    };
    //验证数据方法
    $scope.getServerDataRule = function (attr) {
        if(!attr['serverComboName']){
            Message.warning('请填写服务套餐名');
            return false;
        }else if(attr['serverArr'].length == 0){
            Message.warning('请选择服务类型');
            return false;
        }
        return true;
    };
    /**
     * author:黄鹏举
     * create:2017-05-04
     * 函数描述:将刚才准备的添加服务的数据,发动到数据库,并接受返回的状态(成功/失败)
     * */
    //向后台发送数据
    $scope.setServerComboData  = function () {
        $scope.params = $scope.getServerAllData();                                                     //获取所有数据
        if($scope.getServerDataRule($scope.params)){
            $http({
                url    　: '/serve-plan/set-server-combo',
                method 　: 'POST',
                data   　: $.param($scope.params),
                headers  : { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result) {
                if(result.data.status == 'success'){
                    Message.success('添加成功');
                    $('.modal').hide();                                                                 //关闭模态框
                    $('.modal-backdrop').hide();                                                        //关闭模态框遮罩层
                    $scope.init();                                                                      //初始化调用
                    var serverLists = $("#serveLists");                                                 //元素对象（用于清除之前添加选中的项目）
                    var div         = serverLists.children("div.checkbox");                             //元素对象
                    div.each(function (i,ele) {
                        if($(this).children('label').children('div').hasClass('checked')){
                            $(this).children('label').children('div.checked').removeClass('checked');    //移除之前选中的属性
                        }
                    });
                }else{
                    Message.warning('添加失败！');
                }
            })
        }
    };
    /**
     * author:黄鹏举
     * create:2017-05-08
     * 函数描述:将下方$scope.searchOrderData函数准备的数据，整理起来，定义成公共接口
     * param: keywords:搜索框数据
     *        sortType:排序的字段
     *        sortName:排序的状态
     * */
    //定义公共路径方法
    $scope.initPaths = function () {
        $scope.searchParams =  $scope.searchOrderData();                                          //接收搜索、排序数据
        $scope.pageInitUrl = '/serve-plan/get-all-server-combo?'+ $.param($scope.searchParams);   //后台路径
    };
    //接收搜索条件
    $scope.searchOrderData = function () {
        return {
            keywords      : $scope.keywords != undefined ? $scope.keywords : null,      //搜索框数据
            sortType      : $scope.sortType != undefined ? $scope.sortType : null,      //排序字段（$scope.changeSort（）中赋值）
            sortName      : $scope.sort     != undefined ? $scope.sort : null           //排序状态
        }
    };
    //服务信息分页
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getAllServerCombo();          //再次调用后台查询
    };
    /**
     * author:黄鹏举
     * create:2017-05-08
     * 函数描述:点击搜索时，根据上方函数的接口，进行数据获取及发送
     * */
    //点击搜索触发
    $scope.searchAbout = function () {
        $scope.initPaths();                 //路径和参数
        $scope.getAllServerCombo();
        //向后台发送数据
    };
    //回车搜索触发
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.searchAbout();           //主板发送数据
        }
    };
    //列表排序
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);            //排序处理
        // $scope.searchAbout();               //准备发送数据
        $scope.searchSortServer();
    };
    /**
     * author:黄鹏举
     * create:2017-05-08
     * 函数描述:排序(如果是正序，点击后倒序排列，反之亦然，如果没排序，以正序排序)
     * param: sort:顺序状态
     * */
    //处理正序、倒序
    $scope.switchSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;             //排序状态
    };
    /**
     * author:黄鹏举
     * create:2017-05-08
     * 函数描述:通过上方公共接口，获取所有的服务套餐
     * */
    //获取所有服务套餐
    $scope.getAllServerCombo = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            if(result.data.data != ""){
                $scope.items = result.data.data;     //服务信息
                $scope.pages = result.data.pages;    //分页
                $scope.dataInfo = false;            //关闭 - 没有数据的样式
            }else{
                $scope.items = result.data.data;     //服务信息
                $scope.pages = result.data.pages;    //分页
                $scope.dataInfo = true;              //开启 - 没有数据的样式
            }
        })
    };
    //服务删除数据（id是需要删除的数据id）
    $scope.delServerCombo = function (id) {
        $http.get('/serve-plan/del-server-info?id='+id).then(function (result) {
            if(result.data.status == "success" && result.data.status != ""){
                Message.success('删除成功!');
                $scope.init();
            }else{
                Message.warning('删除失败!');
            }
        })
    };
    /*****服务套餐结束*********/
    /*****服务开始*********/
    //接收添加数据
    $scope.getServer  = function () {
        return {
            name            : $scope.name,                  //服务套餐名称
            _csrf_backend   : $('#_csrf').val()                        //防止csrf                                             //csrf
        }
    };
    /**
     * author:李慧恩
     * create:2017-05-10
     * 函数描述:服务名称的添加，根据输入的服务名称，向后台数据库发送并保存
     * param: name:服务套餐的名称
     * */
    //添加服务
    $scope.addServer = function () {
        $scope.params = $scope.getServer();
        if($scope.params.name == undefined || $scope.params.name == ''){
            Message.warning('服务名称不能为空');
            return false;
        }
        $http({
            url    　: '/serve-plan/set-server',
            method 　: 'POST',
            data   　: $.param($scope.params),
            headers  : { 'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            if(result.data.status == 'success'){
                Message.success('添加成功');
                $('.modal').hide();                                                                 //关闭模态框
                $('.modal-backdrop').hide();                                                        //关闭模态框遮罩层
                $scope.init();                                                                      //初始化调用
            }else{
                Message.warning('添加失败！');
            }
        })
    };
    /**
     * author:李慧恩
     * create:2017-05-10
     * 函数描述:服务套餐的搜索
     * param: keywords:搜索框数据
     *        sortType:排序的字段
     *        sortName:排序的状态
     * */
    //接收搜索条件
    $scope.searchServer = function () {
        return {
            keywords      : $scope.keywords != undefined ? $scope.keywords : null,      //搜索框数据
            sortType      : $scope.sortType != undefined ? $scope.sortType : null,      //排序字段（$scope.changeSort（）中赋值）
            sortName      : $scope.sort     != undefined ? $scope.sort : null           //排序状态
        }
    };
    $scope.initServerPath = function () {
        $scope.searchParam = $scope.searchServer();
        $scope.initServerPaths =  '/serve-plan/get-all-server?'+ $.param($scope.searchParam);
    };
    /**
     * author:李慧恩
     * create:2017-05-10
     * 函数描述:所有服务套餐的获取
     * */
    //获取所有服务套餐
    $scope.getAllServer = function () {
        $http.get($scope.initServerPaths).then(function (result) {
            if(result.data.data != ""){
                $scope.items = result.data.data;     //服务信息
                $scope.pages = result.data.pages;    //分页
                $scope.dataInfo = false;            //关闭 - 没有数据的样式
            }else{
                $scope.items = result.data.data;     //服务信息
                $scope.pages = result.data.pages;    //分页
                $scope.dataInfo = true;              //开启 - 没有数据的样式
            }
        })
    };
    //点击搜索触发
    $scope.searchSortServer = function () {
        $scope.initServerPath();                 //路径和参数
        $scope.getAllServer();         //向后台发送数据
    };
    // $scope.delServer = function (id) {
    //     $http.get('/serve-plan/del-server?id='+id).then(function (result) {
    //         if(result.data.status == "success" && result.data.status != ""){
    //             alert('1111');
    //             Message.success('删除成功!');
    //             $scope.getAllServer();
    //         }else{
    //             Message.warning('删除失败!');
    //         }
    //     })
    // };
    /**
     * author:梁可可
     * create:2017-06-24
     * 函数描述:根据封装好的sweety.remove()方法，传入参数，执行服务的删除
     * param: id:要删除的服务的id
     *        name:要删除的服务的名称
     * */
    $scope.delServer = function(id,name) {
        var id = parseInt(id);
        var name = parseInt(name);
        Sweety.remove({
            url              :'/serve-plan/del-server?id='+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.getAllServer();
        });
    };
    //修改 - 获取单条数据
    $scope.getServerOne = function (id,name) {
        //初始化数据
        $scope.id            = id;
        $scope.serverName   = name;
    };
    /**
     * author:梁凯新
     * create:2017-06-24
     * 函数描述:发送服务的数据
     * param: serverId:服务的id
     *        name:服务的名称
     * */
    //发送数据到客户端
    $scope.updateServer = function(){
        //整理发送后的数据
        $scope.initData = function(){
            return {
                _csrf_backend: $('#_csrf').val(),
                serverId    : $scope.id,
                name         : $scope.serverName  != undefined && $scope.serverName!= "" ? $scope.serverName  : null
            };
        };
        if($scope.serverName==null||$scope.serverName==""){
            Message.warning("服务名不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/serve-plan/update-server",
            method: 'POST',
            data: $.param($scope.initData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
              if(result.status=="success"){
                  $("#myModal").css("display","none");
                  $(".modal-backdrop").css("display","none");
                  Message.success(result.data);
                 // $scope.initServerPath();
                  $scope.getAllServer();
              }else{
                  Message.warning(result.data);
                  return false;
              }
        });
    };

    $scope.newAdd = function(){
        $scope.name ="";
    };
    /*******服务结束***********/
    $scope.init();


});
