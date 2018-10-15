// 柜子管理angularJs文件
angular.module('App').controller('cabinetCtrl', function($scope,$http){
    $scope.items = [
        {number:'001',status:'已租用',type:'男柜',name:'王宝强',phone:'13838383838',money:'20000元',date:'2016-1-1',dateStatus:'快到期',user:'尼古拉斯·赵four'},
        {number:'002',status:'已租用',type:'女柜',name:'anglelababy',phone:'13838383838',money:'20001元',date:'2016-1-1',dateStatus:'快到期',user:'尼古拉斯·赵four'},
        {number:'002',status:'已租用',type:'女柜',name:'anglelababy',phone:'13838383838',money:'20001元',date:'2016-1-1',dateStatus:'',user:'尼古拉斯·赵four'},
        {number:'002',status:'已租用',type:'女柜',name:'anglelababy',phone:'13838383838',money:'20001元',date:'2016-1-1',dateStatus:'',user:'尼古拉斯·赵four'},

    ]
    $scope.search =  function () {
    }
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
        }
    };



    //实时搜索 searchs 
    $scope.searchs = function () {
            if ($scope.userName == ""){
                return
            }
            $http.get("/user/member-keywords?keywords="+ $scope.userName).then(function (data) {
                $scope.searchsData = data.data.data;
            },function (error) {
                console.log(error);
                Message.error("系统错误请联系工作人员")
            });
        $('#searchsBox').css('display','block')
    }
    $scope.sectleClick = function (text) {
        $scope.userName = text;
        $('#searchsBox').css('display','none')
    }
    $scope.searchsBlur = function () {
        $('#searchsBox').css('display','none')
    }
    /**
     * 后台 - 柜子管理 - 分页（组织架构主界面数据遍历）
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
     */

    //初始化页面加载数据
    $scope.init = function () {
        $scope.pageInitUrl = '/cabinet/home-data';
        $scope.getClassModel();
        $scope.venue();       //初始场馆信息
    };
    //分页样式
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };
    // 获取后台组织架构数据
    $scope.getClassModel = function () {
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
        })
    };
    //初始化加载右上角 我爱运动 所有场馆信息
    $scope.venue =function(){
        $http.get("/cabinet/venue-cabinet").then(function (result) {
               $scope.venueS     = result.data;
               $scope.myVenueS   = result.data;
        })
    };

    //初始化主页数据
    $scope.init();
    /**
     * 后台 - 组织架构管理 - 获取柜子状态数据
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/4/24
     */
     $scope.cabinetStatus = function(){
         var status =[];
         $("#status").find('input:checked').each(function(key){
                var val = $(this).val();
                 if(val!=7){
                     status.push(val);
                     $scope.endRent = "";
                 }else{
                     $scope.endRent = 7;
                 }
         });
         $scope.status = status;
     };


    //接受搜索参数
    $scope.searchModel = function(){
       var startTime = $("#reservation").val().split(' - ')[0];
       var endTime   = $("#reservation").val().split(' - ')[1];
        return {
            venueId        : $scope.venueId      != undefined&&$scope.venueId  !="" ? $scope.venueId     : null,
            topSearch      : $scope.topSearch    != undefined&&$scope.topSearch!="" ? $scope.topSearch   : null,    //综合字段搜索
            endRent        : $scope.endRent      != undefined&&$scope.endRent!="" ? $scope.endRent   : null,    //综合字段搜索
            sex             : $scope.sex          != undefined&&$scope.sex!="" ? $scope.sex   : null,
            cabinetStatus  : $scope.status       != undefined&&$scope.status!=""?$scope.status :[],
            startTime      : startTime            != undefined ? startTime           : null,    // 创建时间开始
            endTime        : endTime              != undefined ? endTime             : null,     // 上课时间结束
            sortType       : $scope.sortType      != undefined ? $scope.sortType     : null,    //需要排序的字段名字
            sortName       : $scope.sort          != undefined ? $scope.sort         : null    //排序的类型
        }
    };
    /**整理搜索参数以及搜索url**/
    $scope.initPath = function (){
        $scope.searchParams   =  $scope.searchModel();
        $scope.pageInitUrl       =  '/cabinet/home-data?' + $.param($scope.searchParams);
    };
    //执行最终搜索
    $scope.search = function() {
        $scope.cabinetStatus();
        $scope.initPath();
        $scope.getClassModel();
    };

    /**
     * 后台 - 柜子表 - 主界面各个字段排序
     *author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
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

    //更改大柜小柜对应搜索所需数据
    $scope.findCabinet = function(typeId){
        $http.get("/cabinet/cabinet-data?id="+typeId).then(function (result) {
            $scope.cabinetId ="";
            $scope.numberS   = result.data;
        })
    };
    /**
     * 后台 - 柜子管理 - 调柜数据加载
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
     */
    $scope.initData = function(consumerName,mobile,typeName,cabinetNumber,start,end){
        $scope.memName        = consumerName; //会员名字
        $scope.tel            = mobile;
        $scope.theTypeName   = typeName;
        $scope.num            = cabinetNumber;
        $scope.start          = start;
        $scope.end            = end;
    };
    $scope.change = function(typeId,id,memCabinetId,consumerName,mobile,typeName,cabinetNumber,start,end,venueId){
         $scope.cabinetTypeId         = typeId;       //类型id
         $scope.cabinetId             = id;           //柜子id
         $scope.originalCabinetId    = id;           //原始柜子id
         $scope.memCabinetId          = memCabinetId; //会员柜子id
         $scope.venueId               = venueId;       //场馆id
         $scope.initData(consumerName,mobile,typeName,cabinetNumber,start,end);
        //获取select框的数据select列表遍历数据
        $http.get("/cabinet/cabinet-type?venueId="+venueId+"&&typeId="+typeId).then(function (result) {
            $scope.cabinetId    = "";
            $scope.typeS        = result.data[0];
            $scope.numberS      = result.data[1];
        });
    };
    
    /**
     * 后台 - 柜子管理 - 点击退柜
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
     */
    //点击时加载起始数据
    $scope.quitRent = function(consumerName,mobile,typeName,cabinetNumber,start,end,memCabinetId){
        $scope.initData(consumerName,mobile,typeName,cabinetNumber,start,end);
        $scope.memCabinetId   = memCabinetId; //会员柜子id
    };
    /**
     * 后台 - 柜子管理 - 退柜子接受前台数据
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
     */
    $scope.quiteCabinet = function(){
        //整理发送过来的数据
        $scope.quitData = function () {
            return {
                _csrf_backend     : $('#_csrf').val(),
                memCabinetId      : $scope.memCabinetId,
                quiteDate          : $scope.quiteDate!=undefined||$scope.quiteDate!=""?$scope.quiteDate:null
            }
        };
        if($scope.quiteDate==null){
            Message.warning("退租日期不能为空");
            return false;
        }

        //发送客户端数据
        $http({
            url: "/cabinet/quite-cabinet",
            method: 'POST',
            data: $.param($scope.quitData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
            //关闭模态框
            $('#myModals6').css("display","none");
            //刷新主界面数据
            $scope.getClassModel();
            //添加成功提示
            Message.success("操作成功");
        });
    };
    /**
     * 后台 - 柜子管理 - 接受前台调柜数据
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
     */
   $scope.changeCabinet = function(){
       //整理发送后的数据
       $scope.addData = function () {
           return {
               _csrf_backend      : $('#_csrf').val(),
               originalCabinetId  : $("#originalCabinetId").val(),
               cabinetId          : $scope.cabinetId,
               memCabinetId       : $scope.memCabinetId
           }
       };
       if($scope.cabinetId==''){
           Message.warning("柜子编号不能为空");
           return false;
       }
       //发送客户端数据
       $http({
           url: "/cabinet/change-cabinet",
           method: 'POST',
           data: $.param($scope.addData()),
           headers: {'Content-Type': 'application/x-www-form-urlencoded'}
       }).success(function () {
           //关闭模态框
           $('#myModals5').css("display","none");
           //刷新主界面数据
           $scope.getClassModel();
           //添加成功提示
           Message.success("调柜成功");
       });
   };

    /**
     * 后台 - 柜子管理- 单条数据删除
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/3
     */
    $scope.del=function(id,cabinetId){
        Sweety.remove({
            url: "/cabinet/del-mem-cabinet?id="+id+"&&cabinetId="+cabinetId,
            http: $http,
            title: '确定要解绑吗?',
            text: '若解绑成功，此会员将不再租此柜子',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getClassModel();
        },function () {

        },true);
    };
    /**
     * 后台 - 柜子管理 -  根据不同的场馆遍历柜子类型数据
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/4
     */
    $scope.searchCabinetType = function(venueId){
        $http.get("/cabinet/cabinet-type-data?id="+venueId).then(function (result) {
             $scope.myCabinetType ="";
             $scope.cabinetType   = result.data;
        })
    };
    /**
     * 后台 - 柜子管理 -  接受新增柜子传过来的数据并发送给后台
     *@create author Hou kaiXin houkaixin@itsport.club
     *@create 2017/5/4
     */
     $scope.addCabinet = function(){
         $scope.organizationId = "";
         $scope.cabinetTypeId         = "";
     };
    //接受新增柜子数据并发送给后台
    $scope.addMyCabinet = function(){
        $scope.addData = function(){
            return {
                _csrf_backend : $('#_csrf').val(),
                organizationId : $scope.organizationId != undefined&&$scope.organizationId     !="" ? $scope.organizationId    : null,    //场馆id
                cabinetTypeId  : $scope.myCabinetType  != undefined&&$scope.myCabinetType      !="" ? $scope.myCabinetType     : null,    //柜子类型id
                cabinetNum     : $scope.cabinetNum     != undefined&&$scope.cabinetNum         !="" ? $scope.cabinetNum         : null,    //柜子数量
                dayRentPrice   : $scope.dayRentPrice   != undefined&&$scope.dayRentPrice       !=""  ? $scope.dayRentPrice     : null,    //日租金
                monthRentPrice : $scope.monthRentPrice != undefined&&$scope.monthRentPrice     !=""  ? $scope.monthRentPrice   : null,    //月租金
                yearRentPrice  : $scope.yearRentPrice  != undefined&&$scope.yearRentPrice      !=""  ? $scope.yearRentPrice    : null     //年租金
            };
        };
        //发送客户端数据
        $http({
            url: "/cabinet/add-venue-cabinet",
            method: 'POST',
            data: $.param($scope.addData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
            //关闭模态框
            $('#myModals3').css("display","none");
            //刷新主界面数据
            $scope.getClassModel();
            //添加成功提示
            Message.success("柜子添加成功");
        });
    };
    //新增衣柜类型(指定场馆)
   //点击新增衣柜类型初始化数据
    $scope.initCabinetType = function () {
        $scope.theSex ="请选择";
    };
    $scope.submitCabinetType = function(){
        $scope.addData = function(){
            return {
                _csrf_backend     : $('#_csrf').val(),
                sex                : $scope.theSex                != undefined&&$scope.theSex           !=""  ? $scope.theSex             : null,    //性别
                cabinetTypeName   : $scope.cabinetTypeName      != undefined&&$scope.cabinetTypeName  !=""  ? $scope.cabinetTypeName   : null,     //柜子类型名字
                organizationId    :$scope.organizationId        != undefined&&$scope.organizationId   !=""  ? $scope.organizationId     : null     //柜子类型名字
            };
        };
        if($scope.organizationId==""||$scope.organizationId==undefined){
            Message.warning("请选择对应场馆");
            return false;
        }
        if($scope.cabinetTypeName==null){
            Message.warning("柜子类型名称不能为空");
            return false;
        }
        if($scope.theSex=="请选择"){
            Message.warning("性别不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/cabinet/add-cabinet-type",
            method: 'POST',
            data: $.param($scope.addData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
              //关闭模态框
              $('#myModals7').css("display","none");
              //刷新场馆对应的 柜子类型数据
              $scope.searchCabinetType($scope.organizationId);
            //添加成功提示
             Message.success("柜子类型添加成功");
        });
    };
    //点击每一行信息加载(初始化数据)
    $scope.dataLoad = function(cabinetNumber,typeName,consumerName,mobile,dayRentPrice,monthRentPrice,yearRentPrice,endRent,adminOperator,status,memCabinetId,cabinetId){
        $scope.num             = cabinetNumber;
        $scope.theTypeName    = typeName;
        $scope.consumerName   = consumerName;
        $scope.tel             = mobile;
        $scope.dayRentPrice   = dayRentPrice;
        $scope.monthRentPrice = monthRentPrice;
        $scope.yearRentPrice  = yearRentPrice;
        $scope.endRent        =  endRent;
        $scope.adminOperator  = adminOperator;
        $scope.status          = status;
        $scope.memCabinetId   = memCabinetId;
        $scope.cabinetId      = cabinetId;

    };
    // 绑定用户日期插件的js
    $("#dateIndex").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true,//今日按钮
        setStartDate: "2017-5-25"
    }).on('changeDate', function (ev) {
        $scope.date = $("#dateSpan").val();
    });
    //点击绑定用户（绑定信息）
    $scope.bindUser = function(cabinetNumber,typeName,cabinetId){
        $scope.cabinetNumber = cabinetNumber;
        $scope.typeName      = typeName;
        $scope.myCompanyId   = cabinetId;
    };
    //绑定用户
    $scope.TheBindUser = function(){
        $scope.bindData = function(){
            return {
                _csrf_backend      : $('#_csrf').val(),
                userName           : $scope.userName             != undefined&&$scope.userName       !=""  ? $scope.userName      : null,    //性别
                theMobile          : $scope.theMobile            != undefined&&$scope.theMobile      !=""  ? $scope.theMobile     : null,     //柜子类型名字
                cardNum            :$scope.cardNum               != undefined&&$scope.cardNum         !=""  ? $scope.cardNum       : null,     //会员卡号
                adminOperator      :$scope.adminOperator         != undefined&&$scope.adminOperator  !=""  ? $scope.adminOperator: null,     //经办人
                date                :$scope.date                  != undefined&&$scope.date            !=""  ? $scope.date          : null,     //经办人
                cabinetId          : $scope.myCompanyId           != undefined&&$scope.myCompanyId     !=""  ? $scope.myCompanyId    : null     //柜子ID、
            };
        };
        if($scope.userName==null || $scope.theMobile==null ||$scope.cardNum==null){
            Message.warning("用户名，电话或卡号不能为空");
            return false;
        }
        if($scope.adminOperator==null||$scope.date==null){
            Message.warning("经办人或办理日期不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/cabinet/bind-user",
            method: 'POST',
            data: $.param($scope.bindData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
             if(result.status=="error"){
                 Message.warning(result.data);
                 return false;
             }else{
                 Message.success(result.data);
                 //关闭模态框
                 $('#myModals4').css("display","none");
                 //刷新柜子数据
                 $scope.getClassModel();
             }
        });
    };
    /**
     * 后台 - 柜子管理 -  修改
     *@create author Hou kaiXin <<houkaixin@itsport.club>>
     *@create 2017/5/9
     */
    $scope.cabinetUpdate = function(){
        $scope.updateData = function(){
            return {
                _csrf_backend      : $('#_csrf').val(),
                dayRentPrice   : $scope.dayRentPrice   != undefined&&$scope.dayRentPrice       !=""  ? $scope.dayRentPrice     : null,    //日租金
                monthRentPrice : $scope.monthRentPrice != undefined&&$scope.monthRentPrice     !=""  ? $scope.monthRentPrice   : null,    //月租金
                yearRentPrice  : $scope.yearRentPrice  != undefined&&$scope.yearRentPrice      !=""  ? $scope.yearRentPrice    : null,     //年租金
                adminOperator  : $scope.adminOperator  != undefined&&$scope.adminOperator      !=""  ? $scope.adminOperator    : null,     //经办人
                memCabinetId   : $scope.memCabinetId   != undefined&&$scope.memCabinetId       !=""  ? $scope.memCabinetId     : null,     //会员柜子Id
                cabinetId      : $scope.cabinetId      != undefined&&$scope.cabinetId          !=""  ? $scope.cabinetId         : null,     //会员柜子Id
                date           : $("#exampleInputName7").val()!= undefined&&$("#exampleInputName7").val()!=""  ?$("#exampleInputName7").val(): null     //结束日期
            };
        };
         var  $data =$scope.updateData();
        if($data.dayRentPrice==null||$data.monthRentPrice==null||$data.yearRentPrice==null){
            Message.warning("请填写日月年支付金额");
            return false;
        }else{
            if (isNaN($data.dayRentPrice)||isNaN($data.monthRentPrice)||isNaN($data.yearRentPrice)){
                Message.warning("日月年支付金额必须为数字类型");
                return false;
            }
        }


        if($data.adminOperator==null){
            Message.warning("经办人不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/cabinet/cabinet-update",
            method: 'POST',
            data: $.param($scope.updateData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            if(result.status=="error"){
                Message.warning(result.data);
                return false;
            }else{
                Message.success("修改成功");
                //关闭模态框
                $('#myModals2').css("display","none");
                //刷新柜子数据
                $scope.getClassModel();
            }
        });
    }
    // 关闭绑定会员数据初始化的方法
    $scope.closeUser = function () {
        $scope.userName = ""
        $scope.theMobile = ""
        $scope.cardNum = ""
        $scope.date = ""
        $scope.adminOperator = ""
        $('#searchsBox').css('display','none')
    }
    // 关闭会员退租的数据初始化方法
    $scope.closeCabint = function () {
        $scope.quiteDate = "";
    }
});