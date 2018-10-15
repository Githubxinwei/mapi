var app = angular.module('App').controller('personalCtrl', function($scope,$http,Upload){
    $scope.personal = function(){
        // $('#myModals2').hide();
        $("#myModals2").modal('hide');
        // $("#myModals2").modal("toggle");//去掉阴影层
        $(".userBasic").addClass('active');
        $('.userMessage').removeClass('active');
        $('.userBasic1').show();
        $('.userMessage1').hide();
    };
    $scope.closeDetails = function () {
        $("#myModals2").modal('hide');
        // $('#myModals2').hide();
        // $("#myModals2").modal("toggle");//去掉阴影层
        $(".userBasic").addClass('active');
        $('.userMessage').removeClass('active');
        $('.userBasic1').show();
        $('.userMessage1').hide();
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single1").select2();
        $(".js-example-basic-single2").select2();
        $(".js-example-basic-single3").select2();
    });
    //点击跳转约课页面
    $scope.employeeCourse = function(reservationCourseId){
        localStorage.setItem('reservationCourse',JSON.stringify({
            id:reservationCourseId
        }));
        location.href = '/personnel/course'
    };
    //取消潜在会员约课
    $scope.potentialMemberCancelAppointment = function(id){
        Sweety.remove({
            url              : "/check-card/cancel-about-class?id="+id,
            http             : $http,
            title            : '确定要取消约课吗?',
            text             : '取消约课后无法恢复',
            confirmButtonText: '确定',
            confirmButton     : '取消约课',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.getData();
        },function(){

        },true,true);
    };
    // $scope.potentialMemberCancelAppointment = function (id) {
    //         $http({method:'get', url:'/check-card/cancel-about-class?id='+id}).then(function (data) {
    //             if (data.data.status == "success"){
    //                 Message.success(data.data.data);
    //                 $scope.getData();
    //             }
    //             if (data.data.status == "error"){
    //                 Message.warning("现在不能取消约课");
    //                 $scope.getData();
    //             }
    //         },function (error) {
    //             console.log(error);
    //             Message.error('系统错误请联系工作人员')
    //         })
    // }
    /**
     *后台员工管理 - 员工信息查询 - 访问Personnel控制器的actionEmployeeInfo()
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/24
     * @return bool|string
     */
    $scope.init = function () {
        $scope.pageInitUrl = '/personnel/employee-info';
        $scope.getCompanyOptions(); //公司
        // $scope.getVenue();          //场馆
        // $scope.getDepartment();     //部门
        $scope.getData();
        $scope.initPath();
        $scope.class   = [];
        $scope.sexArr = [
            {'id':1,'name':'男'},
            {'id':2,'name':'女'}
        ];
        $scope.statusArr = [
            {'id':1,'status':'在职'},
            {'id':2,'status':'离职'},
            {'id':3,'status':'调岗'},
            {'id':4,'status':'全职'},
            {'id':5,'status':'兼职'}
        ];
    };
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getData();

    };
    /**获取员工表数据***/
    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).success(function (response) {
            if(response.data != "" && response.data != undefined && response.data.length != undefined){
                $scope.datas    = response.data;
                $scope.pages    = response.pages;
                $scope.dataInfo = false;
            }else{
                $scope.datas    = response.data;
                $scope.pages    = response.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide();
        });
    };
    /**删除员工表数据***/
    $scope.Employee = function(id,name) {
        Sweety.remove({
            url              : "/personnel/employee-del?employeeId="+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.getData();
        }, function () {
        },true);
    };
    //点击未审核按钮
    $scope.updateEmployee = function (id,$index) {
        $scope.updateEmployeeId = id;
        $scope.updateEmployeeIndex = $index;
        $http.get("/personnel/update-status?id="+$scope.updateEmployeeId).then(function (result){
            $scope.result = result.data;
            if($scope.result.status == 'success'){
                Message.success($scope.result.data);
                $('#auditModal').modal('hide');
                $scope.datas[$scope.updateEmployeeIndex].is_pass = $scope.datas[$scope.updateEmployeeIndex].is_pass == 0 ? 1 : 0;
            }else{
                Message.warning($scope.result.data);
            }
        });
    };
    /**处理搜索数据***/
    $scope.searchEmployeeData = function () {
        return {
            keywords       : $scope.keywords    != undefined  ? $scope.keywords   : null,//关键字
            sortType       : $scope.sortType    != undefined  ? $scope.sortType   : null,
            sortName       : $scope.sort        != undefined  ? $scope.sort       : null,
            venueId        : $scope.venueId     != undefined  ? $scope.venueId    : null,//公司id
            depId          : $scope.depId       != undefined  ? $scope.depId      : null,//场馆id
            department     : $scope.department  != undefined  ? $scope.department : null//部门id
        }
    };
    $scope.initPath = function () {
        $scope.searchParams =  $scope.searchEmployeeData();
        $scope.pageInitUrl =  '/personnel/employee-info?' + $.param($scope.searchParams);
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            // $scope.pageInitUrl = '/user/member-info?page='+value;
            $scope.pageInitUrl =  '/personnel/employee-info?' + $.param($scope.searchParams) + '&page=' + value;
            $scope.getData();
        }
    };

    /**搜索方法***/
    $scope.searchEmployee = function () {
        $scope.initPath();
        $scope.getData();
    };
    /******Enter键搜索*******/
    $scope.enterSearchs = function(e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.searchEmployee(); }
    };
    /******排序*******/
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchEmployee();
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

    /******添加员工*******/
    $scope.myModalAdd = function () {
        // 日期插件的调用
        $("#dateIndexAdd").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true //今日按钮
        }).on('changeDate', function (ev) {});
        // 日期插件的调用
        $(".birthDate").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true //今日按钮
        }).on('changeDate', function (ev) {});
        $scope.picInit();
        //初始化清空
        $scope.name = '';
        $scope.venueId = '';
        $scope.depId = '';
        $scope.department = '';
        $scope.position = '';
        $scope.status = '';
        $scope.alias = '';
        $scope.salary = '';
        $scope.sex = '';
        $scope.mobile = '';
        $scope.classHour = '';
        $scope.classTime = '';
        $scope.classAmount = '';
        $scope.work_time = '';
        $scope.intro = '';
        $scope.pic = '';
        $('#salary').val('');

        $scope.department ='';
        $scope.customAddBool = true;
    };
    $scope.addStaffBlur = function (name) {
        $http({method:'get',url:'/personnel/get-employee-name?name='+ name}).then(function (data) {
            if(data.data.status == 'error'){
                Message.warning("已经有该姓名")
            }
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    };

    //通过改变input的type类型来解决浏览器记住密码问题
    $scope.passwordChange = function(val){
        if(val == ''){
            $('#setPassword').attr({
                type:'text'
            });
        }else{
            $('#setPassword').attr({
                type:'password'
            });
        }
    };
    //通过改变input的type类型来解决浏览器记住密码问题
    $scope.passwordFocus  = function(val){
        if(val == ''){
            $('#setPassword').attr({
                type:'text'
            });
        }else{
            $('#setPassword').attr({
                type:'password'
            });
        }
    };
    
    // 部门变动 员工职位变动
    $scope.departmentChange = function (id) {
        $scope.customAddBool = false;
        $http({method:'get',url:'/personnel/get-employee-position?branchId='+id}).then(function (data) {
            $scope.getEmployeePositionData = data.data.attributes
            $scope.position = ''
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员")})
    };
    //获取公司信息
    $scope.getCompanyOptions = function () {
            $http.get('/site/get-auth-company').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data;
            }
        });
    };
    //自定义添加 员工职位
    $scope.customAdd = function () {
        $("#sellSource1").show();
    };
    // 回车搜索自定义销售渠道事件
    $scope.enterSearch = function (e){
        var keyCode = window.event ? e.keyCode : e.which;
        // var event = window.event || arguments.callee.caller.arguments[0];
        if (keyCode == 13)
        {
            $scope.customAddOk($scope.customSalesChannels);
        }
    };
    $scope.customAddOk = function (data) {
        if(data == '' || data == undefined || data == null){
            Message.warning("请输入职位名称");return;
        }
        var datas ={
            name:data,
            branchId:$scope.department,
            _csrf_backend: $('#_csrf').val(),
        };
        $http({method:'post',url:'/personnel/set-employee-position',data:$.param(datas),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if(success.data.status == 'success'){
                $scope.customAddId = success.data.data;
            }
            $http({method:'get',url:'/personnel/get-employee-position?branchId='+$scope.department}).then(function (data) {
                $scope.getEmployeePositionData = data.data.attributes;
            },function (error) {console.log(error);Message.error("系统错误请联系管理人员")})
            $("#sellSource1").hide();
            $(".modal-backdrop").eq(1).hide();
        },function (error) {
            console.log(error);Message.error("系统错误请联系管理人员")
        })
    };
    //删除员工职位
    $scope.removeCustom = function () {
        var id = $('#selectedCustomAddId').find('option:selected').data('ids');
        if (id != undefined) {
            Sweety.remove({
                url: "/personnel/delete-position-by-id?id=" + id,
                http: $http,
                title: '确定要删除吗?',
                text: '删除后信息无法恢复',
                confirmButtonText: '确定',
                data: {
                    action: 'unbind'
                }
            }, function () {
                $http({method:'get',url:'/personnel/get-employee-position?branchId='+$scope.department}).then(function (data) {
                    $scope.getEmployeePositionData = data.data.attributes;
                },function (error) {console.log(error);Message.error("系统错误请联系管理人员")})
                $scope.position = '';
            },function(){

            },true);
        } else {
            Message.warning('请选择需要删除的销售来源!');
            return;
        }
        $scope.position = '';

    };
    // 初始化公司数据
    // $scope.getCompanyOptions();
    //获取场馆信息
    $scope.getVenue   = function (venueId) {
        if(venueId){
                $http.get('/personnel/get-venue-data?companyId='+venueId).then(function (result) {
                if(result.data.venue != undefined && result.data.venue != ""){
                    $scope.Department = result.data.venue;
                    $scope.depId = ''
                }else{
                    $scope.Department = result.data.venue;
                    $scope.department = [];
                    $scope.depId = ''
                }
            });
        }else{
            $http.get('/rechargeable-card-ctrl/get-venue-all').then(function (result) {
                if(result.data.venues != undefined && result.data.venues != ""){
                    $scope.Department = result.data.venues;
                    $scope.depId = ''
                }else{
                }
            });
        }
    };
    // 初始化场馆数据
    // $scope.getVenue();

    //上传图片大小判断
    $scope.setCover              = function (file,text) {
        if(file){
            if(file.size > 2000000){
                Message.warning('图片太大');
            }else{
                $scope.setPic(file,text);
            }
        }
    };
    //图片上传方法
    $scope.setPic                = function (file,text) {
        Upload.upload({
            url    : '/class/upload',
            method : 'POST',
            data   : {UploadForm:{imageFile:file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result){
            if(result.data.status == 'success'){
                if (text == 'update') {
                    $scope.EmployeeData.pic = result.data.imgLink;
                    $("#cutPic").modal("hide");
                }else {
                    $scope.pic  = result.data.imgLink;
                    $("#addCutPic").modal("hide");
                }
                $scope.picInit();
            }else{
                Message.warning(result.data.data);
            }
        })
    };
    //展示剪裁图片模态框
    $(function(){
        $("#btn2").click(function () {
            $("#cutPic").modal("show");
        })
    });
    $(function(){
        $("#btn1").click(function () {
            $("#addCutPic").modal("show");
        })
    });
    //图片剪裁
    //初始化图片数据
    $scope.picInit = function () {
        $('#fileInput').val('');
        $scope.myImage='';
        $scope.myCroppedImage='';
    };
    $scope.picInit();

    var handleFileSelect = function(evt) {
        //console.log('evt',evt);
        var file = evt.currentTarget.files[0];
        if (!/image\/\w+/.test(file.type)) {
            Message.warning("文件必须为图片");
            $scope.picInit();
            return false;
        }
        //console.log(file);
        var reader = new FileReader();
        reader.onload = function (evt) {
            $scope.$apply(function($scope){
                $scope.myImage = evt.target.result;
            });
        };
        reader.readAsDataURL(file);
    };
    $(document).on('change','#fileInput',handleFileSelect);

    //剪裁后图片的保存
    $scope.uploadPic = function () {
        if ($scope.myCroppedImage=='') {
            Message.warning("请上传图片");
            return false;
        }
        $scope.file = $scope.getUploadPic($scope.myCroppedImage, "image/png");
        //console.log($scope.file);
        $scope.setPic($scope.file,'update');
    };

    $scope.addPic = function () {
        if ($scope.myCroppedImage=='') {
            Message.warning("请上传图片");
            return false;
        }
        $scope.file = $scope.getUploadPic($scope.myCroppedImage, "image/png");
        //console.log($scope.file);
        $scope.setPic($scope.file,'add');
    };
    //解析base64的格式图片
    $scope.getUploadPic = function (dataUrl,type) {
        var binary = atob(dataUrl.split(',')[1]);
        var array = [];
        for(var i = 0; i < binary.length; i++) {
            array.push(binary.charCodeAt(i));
        }
        return new Blob([new Uint8Array(array)], {type:type });
    };

    //获取部门信息
    $scope.getDepartment   = function (depId) {
        if ($scope.department == null){
            $scope.department = '';
        }else {
            $scope.department = '';
        }
        if(depId) {
            $http.get('/personnel/get-department?depId=' + depId).then(function (result) {
                if (result.data.venue != undefined && result.data.venue != "") {
                    $scope.departments = result.data.venue;
                }else{
                    $scope.departments = [];
                }
            });
        }else{
            $http.get('/personnel/get-department-all-data').then(function (result) {
                if(result.data.department != undefined && result.data.department != ""){
                    $scope.departments = result.data.department;
                }else{
                }
            });
        }
    };
    // 初始化部门数据
    // $scope.getDepartment();
    //验证手机号是否存在
    $scope.getMobile            = function (mobile) {
        if(mobile != undefined){
            $http.get('/league/get-mobile-info?mobile='+mobile).then(function (result) {
                $scope.mobileStatus = result.data.status;
            });
        }
    };

    //初始化身份证号和出生日期
    $scope.identityCard = '';
    $scope.birthDate = '';
    //输入身份证时获取出生日期
    $scope.identityCardChange = function () {
        if($scope.identityCard.length === 18) {
            $scope.birthDate = $scope.identityCard.toString().substr(6, 4) +
                '-'
                + $scope.identityCard.toString().substr(10, 2) +
                '-'
                + $scope.identityCard.toString().substr(12, 2);
        }
    };
    //修改身份证时获取出生日期
    $scope.employeeIdentityCardChange = function () {
        if($scope.EmployeeData.identityCard.length === 18) {
            $scope.EmployeeData.birth_time = $scope.EmployeeData.identityCard.toString().substr(6, 4) +
                '-'
                + $scope.EmployeeData.identityCard.toString().substr(10, 2) +
                '-'
                + $scope.EmployeeData.identityCard.toString().substr(12, 2);
        }
    };
    //获取前台数据并提交给后台
    $scope.add = function() {
        $scope.addData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                name         : $scope.name        != undefined && $scope.name        != "" ? $scope.name       : null,//姓名
                position     : $('#selectedCustomAddId').val()    != undefined && $('#selectedCustomAddId').val()    != "" ? $('#selectedCustomAddId').val()   : null,//员工职位
                salary       : $scope.salary      != undefined && $scope.salary      != "" ? $scope.salary     : null,//薪资
                classTime    : $scope.classTime   != undefined && $scope.classTime   != "" ? $scope.classTime  : null,//基础课量
                classAmount  : $scope.classAmount != undefined && $scope.classAmount != "" ? $scope.classAmount: null,//免费课量
                classHour    : $scope.classHour   != undefined && $scope.classHour   != "" ? $scope.classHour  : null,//课时范围
                department   : $scope.department  != undefined && $scope.department  != "" ? $scope.department : null,//部门
                mobile       : $scope.mobile      != undefined && $scope.mobile      != "" ? $scope.mobile     : null,//手机号
                company:$scope.venueId,//公司
                venue:$scope.depId,//场馆
                birth_time   : $scope.birthDate,//出生日期
                identityCard : $scope.identityCard,//身份证号
                // fingerprint:templateDataArray,
                sex          : $scope.sex         != undefined && $scope.sex         != "" ?$scope.sex         : null,//性别
                status       : $scope.status      != undefined && $scope.status      != "" ?$scope.status      : null,//员工职位
                alias        : $scope.alias       != undefined && $scope.alias       != "" ?$scope.alias       : null,//别名
                intro        : $scope.intro       != undefined && $scope.intro       != "" ?$scope.intro       : null,//简介
                work_time: $scope.work_time       != undefined && $scope.work_time       != "" ?$scope.work_time       : null,//从业时间
                pic: $scope.pic       != undefined && $scope.pic       != "" ?$scope.pic       : null//图片
            }
        };
        //整理发送后的数据
        //保存数据之前数据验证
        if($scope.name==null  || $scope.name==""){
            Message.warning("员工名字不能为空");
            return false;
        }
        if($scope.venueId==null  || $scope.venueId==""){
            Message.warning("所属公司不能为空");
            return false;
        }
        if($scope.depId==null  || $scope.depId==""){
            Message.warning("所属场馆不能为空");
            return false;
        }
        if($('#selectedCustomAddId').val() == '' || $('#selectedCustomAddId').val() ==null){Message.warning("职位不能为空");return;}
        if($scope.status==null  || $scope.status==""){
            Message.warning("员工状态不能为空");
            return false;
        }
        if($scope.department==null  || $scope.department==""){
            Message.warning("所属部门 不能为空");
            return false;
        }
        var $timeFormat = /^\d{4}\-\d{2}\-\d{2}$/;
        if($scope.work_time != '' && $scope.work_time != null && $scope.work_time != undefined){
            if (!$timeFormat.test($scope.work_time)){
                Message.warning("从业时间格式不正确，正确格式如：2008-08-08");
                return false;
            }
        }
        var $pattern = /^1[1234567890]\d{9}$/;
        if($scope.mobile != '' && $scope.mobile != null && $scope.mobile != undefined){
            if (!$pattern.test($scope.mobile)){
                Message.warning("手机号格式不正确");
                return false;
            }
        }
       /* if(($scope.mobile != null && $scope.mobile != undefined) && !($pattern.test($scope.mobile))){
            Message.warning("手机号格式不正确");
            return false;
        }*/
        if($scope.mobileStatus == 'error'){
            Message.warning("手机号已经存在");
            return false;
        }
        // if(templateDataArray.length == 0 || templateDataArray.length == ''){Message.warning("请录入指纹");return;}
        //发送客户端数据
        $http({
            url: "/personnel/add-data",
            method: 'POST',
            data: $.param($scope.addData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function () {
            Message.success('添加成功');
            //执行关闭模态框
            $('#myModal').hide(); //关闭模态框
            $('.modal-backdrop').hide(); //关闭模态框遮罩层
            $scope.getData();  //刷新列表
        });
    };
    $scope.init();
    //授权
    $scope.allotAccount = function(id,allotStatue){
        $scope.isAllotNull = allotStatue;
        $scope.allotNumber = '';
        $scope.allotPassword ='';
        // $http.get('/personnel/get-department-all-data').then(function (result) {
        //     $scope.allSection = result.data.department;
        // });
        $http.get('/personnel/get-employee-admin-level-one?id='+ allotStatue).then(function (result) {
            $scope.isAllot = result.data.data;
            if(result.data.data != null){
                $scope.allotNumber = result.data.data.username;
                $scope.allotPassword = result.data.data.password_hash;
            }
        });
        $('#myAllotModal1').modal('show');
        $http.get('/personnel/employee-details?EmployeeId='+id).then(function (result) {
            //部门id
            $scope.allotVenueId = result.data.organization_id;
            $scope.allotEmployeeId = id;
            // $scope.allotBranch = result.data.organization_name;
            $scope.mobile = result.data.mobile;
            $scope.mobiles = result.data.mobile;
        });
    };
    //权限管理数据
    $http.get('/personnel/get-assign-admin').then(function (result) {
        $scope.jurisdictionLists = result.data.data;
    });
    //完成分配
    $scope.allotComplete = function(){
        if($scope.isAllotNull == null){
            $scope.allotAccountData = {
                employeeId:$scope.allotEmployeeId,//员工id
                username:$scope.allotNumber,//账号
                password:$scope.allotPassword,//密码
                venueId:$scope.allotVenueId,//部门
                mobile:$('#allotPhone').val(),//手机号
                level:null,
                scenario:'insert',
                _csrf_backend:$('#_csrf').val()
            };
            var $patternNum = /^\w{3,18}$/;
            if($scope.allotAccountData.username == undefined || !($patternNum.test($scope.allotAccountData.username))){
                Message.warning("账号请输入3到18位的数字字母下划线！");
                return false;
            }
            var $patternNumPassword = /^[0-9a-zA-Z]{6,18}$/;
            if($scope.allotAccountData.password == undefined ||!($patternNumPassword.test($scope.allotAccountData.password))){
                Message.warning("密码请输入6到18位的数字或字母！");
                return false;
            }
        }
        if($scope.isAllotNull != null){
            $scope.allotAccountData = {
                employeeId:$scope.allotEmployeeId,//员工id
                venueId:$scope.allotVenueId,//部门
                mobile:$('#allotPhone').val(),//手机号
                level:$('#selectJurisdiction').val(),
                adminId:$scope.isAllotNull,
                scenario:'update',
                _csrf_backend:$('#_csrf').val()
            }
        }
        //验证手机号是否存在
        $scope.getAllotMobile = function (mobile) {
            if(mobile != undefined){
                $http.get('/league/get-mobile-info?mobile='+mobile).then(function (result) {
                    $scope.allotMobileStatus = result.data.status;
                });
            }
        };
        var $pattern = /^1[1234567890]\d{9}$/;
        if(($('#allotPhone').val() == null || $('#allotPhone').val() == undefined) || !($pattern.test($('#allotPhone').val()))){
            Message.warning("手机号格式不正确");
            return false;
        }
        if($('#allotPhone').val() != $scope.mobile && $('#allotPhone').val() != null){
            $scope.getAllotMobile($scope.allotAccountData.mobile);
        }
        if($scope.allotMobileStatus == 'error'){
            Message.warning("手机号已经存在");
            return false;
        }
        if($('#selectJurisdiction').val() == ''){
            Message.warning("请选择权限类型");
            return;
        }
        //发送客户端数据
        $http({
            url: "/personnel/assign-admin",
            method: 'POST',
            data: $.param($scope.allotAccountData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            if(result.status=="success"){
                Message.success('分配成功');
                $('#myAllotModal1').modal('hide');
                $scope.getData();
            }else{
                if(result.data.username == "用户名已注册！"){
                    Message.warning(result.data.username);
                }
            }
        });
    };
    /******修改员工信息*******/
    //获取员工单条信息(向表单添加默认信息)
    $scope.getEmployee = function(id){
        // 日期插件的调用
        $("#dateIndex").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true //今日按钮
        }).on('changeDate', function (ev) {});
        $("#myModals").modal("show");
        $scope.picInit();
        $scope.EmployeeData = '';
        var EmployeeId = id;
        if(id != undefined){
            $http.get('/personnel/employee-details?EmployeeId='+id).then(function (result) {
                $scope.getVenue(result.data.companyId);
                $scope.getDepartment(result.data.venueId);

                $scope.EmployeeData = result.data;
                localStorage.setItem("EmployeeDataOrganization",JSON.stringify({id:$scope.EmployeeData.organization_id}))
                $scope.EmployeeDataOrganization($scope.EmployeeData.organization_id)
                //解决修改时修改成功课时课量再修改其他重复赋值的问题
                var $params = angular.fromJson($scope.EmployeeData.params);
                if($params != null){
                    $scope.updateClassTime = $params.classTime;
                    $scope.updateClassAmount = $params.classAmount;
                }
                $http({method:'get',url:'/personnel/get-employee-position?branchId='+$scope.EmployeeData.organization_id}).then(function (data) {
                    $scope.getEmployeePositionData = data.data.attributes;
                },function (error) {console.log(error);Message.error("系统错误请联系管理人员")})
            })
        }else{
            $scope.EmployeeData = '暂无数据';
        }
    };
    $scope.EmployeeDataOrganization  = function (id) {
        var getModuleId = localStorage.getItem("EmployeeDataOrganization");
        var ids = JSON.parse(getModuleId).id;
        if(id != ids){
            $scope.EmployeeData.position = '';
            $http({method:'get',url:'/personnel/get-employee-position?branchId='+id}).then(function (data) {
                $scope.getEmployeePositionData = data.data.attributes;
            },function (error) {console.log(error);Message.error("系统错误请联系管理人员")})
            $scope.EmployeeData.position = ''
        }
    };
    //验证手机号是否存在
    $scope.getMobiles = function (mobile) {
        if(mobile != undefined){
            $http.get('/personnel/get-mobile?mobile='+mobile).then(function (result) {
                $scope.phoneStatus = result.data.status;
            });
        }
    };
    //发送数据请求指令
    $scope.update = function(){
        $scope.employeeData = function(){
            return {
                _csrf_backend: $('#_csrf').val(),
                employeeName            :$scope.EmployeeData.name,              //员工名字
                employeePosition        :$scope.EmployeeData.position,          //职业
                employeeSex             :$scope.EmployeeData.sex,                //性别
                // employeeAge           :$scope.EmployeeData.age,                //年龄
                employeeIdentityCard    : $scope.EmployeeData.identityCard,     //身份证
                employeeBirthDate       : $scope.EmployeeData.birthDate,        //出生日期
                employeeSalary          : parseInt($scope.EmployeeData.salary), //薪资
                classHour               :$scope.EmployeeData.class_hour,        //课时
                classTime               :$("#classTime").val(),                 //基础课量
                classAmount             :$("#classAmount").val(),               //免费课量
                EmployeeId              :$("#EmployeeId").val(),                //需要修改的课程id
                fingerprint:templateDataArray,
                organization            :$scope.EmployeeData.organization_id,   //修改组织架构id
                employeeMobile          :$scope.EmployeeData.mobile,//手机号
                employeeStatus          :$scope.EmployeeData.status,//员工状态
                employeeAlias           :$scope.EmployeeData.alias,//别名
                employeeIntro           :$scope.EmployeeData.intro,//简介
                pic                     :$scope.EmployeeData.pic,//图片
                work_time               :$scope.EmployeeData.work_date,//工作年限

            }
        };
        if($scope.EmployeeData.name==null  || $scope.EmployeeData.name==""){
            Message.warning("员工名字不能为空");
            return false;
        }
        /*if(isNaN(parseInt($scope.EmployeeData.age))) {
            Message.warning('年龄必须输入数字!')
            return ;
        }*/
        var $timeFormat = /^\d{4}\-\d{2}\-\d{2}$/;
        if($scope.EmployeeData.work_date != '' && $scope.EmployeeData.work_date != null && $scope.EmployeeData.work_date != undefined){
            if (!$timeFormat.test($scope.EmployeeData.work_date)){
                Message.warning("从业时间格式不正确，正确格式如：2008-08-08");
                return false;
            }
        }
        var $pattern = /^1[1234567890]\d{9}$/;
        if($scope.EmployeeData.mobile != '' && $scope.EmployeeData.mobile != null && $scope.EmployeeData.mobile != undefined){
            if (!$pattern.test($scope.EmployeeData.mobile)){
                Message.warning("手机号格式不正确");
                return false;
            }
        }
        /*if(($scope.EmployeeData.mobile != null && $scope.EmployeeData.mobile != undefined) && !($pattern.test($scope.EmployeeData.mobile))){
            Message.warning("手机号格式不正确");
            return false;
        }*/
        if($scope.phoneStatus == 'error'){
            Message.warning("手机号已经存在");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/personnel/update-data",
            method: 'POST',
            data: $.param($scope.employeeData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == 'success') {
                Message.success('修改成功');
            }else {
                Message.warning('修改失败');
            }
            $("#myModals").modal("hide")
            // $('.modal-backdrop').hide(); //关闭模态框遮罩层
            $scope.getData();            //调用员工

        });
    };
    //点击批量转移
    $scope.massTransfer1 = function(){
        $('#myModals2').modal('hide');
        $scope.checkboxLength = 0;
        // $("#myModals3").show();
    };
    //点击员工列表弹出详情
    $scope.employeeDetails = function (id,type,name) {
        var display =$('#myModals2').css('display');
        if(display == 'none'){
            $(".userMessage1").css("display","none");
            $(".userBasic1").css("display","block");
            $('.userBasic ').addClass('active');
            $('.userMessage').removeClass('active');
            $('#myModals2').modal('show');
        }
        $scope.privateLessonPurchase = '';
        $scope.allMember = '';
        $("#keywordMember").val('');
        //老id
        $scope.employeeDetailsId = id;
        //员工部门类型
        $scope.organizationType = type;
        //老教练名字
        $scope.organizationName = name;

        $http({method:'get',url:'/personnel/employee-details?EmployeeId='+id}).then(function (data) {
           $scope.employeeDetailsListData = data.data;
            $scope.employeeMemberInformation(id,type)
            // $("#myModals2").show();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };
    //员工详情列表数据
    $scope.employeeMemberInformation = function (id,name) {
        $scope.employeeMemberInfoId = id;
        $scope.employeeMemberInformationName = name;
        if (name == '私教部'){
            $http({method:'get',url:'/personnel/sell-employee-member?employeeId='+id}).then(function (data) {
                if(data.data.data.length <= 0){
                    $scope.personnelNoDataShow = true;
                    $scope.personnelNoDataShows = true;
                    $scope.employeeMemberInformationList = data.data.data
                }else {
                    $scope.personnelNoDataShow = false;
                    $scope.personnelNoDataShows = false;
                    $scope.employeeMemberInformationList = data.data.data
                }
            },function (error) {
                console.log(error)
                Message.error("系统错误请联系管理人员")
            })
        }
        if(name == '销售部'){
            $http({method:'get',url:'/personnel/get-employee-member?employeeId='+id}).then(function (data) {
                if(data.data.data.length <= 0){
                    $scope.personnelNoDataShow = true;
                    $scope.personnelNoDataShows = true;
                    $scope.employeeMemberInformationList = data.data.data
                }else {
                    $scope.personnelNoDataShow = false;
                    $scope.personnelNoDataShows = false;
                    $scope.employeeMemberInformationList = data.data.data
                }
            },function (error) {
                console.log(error)
                Message.error("系统错误请联系管理人员")
            })
        }
        $scope.allMember = '';
        $scope.privateLessonPurchase = '';
        $scope.keywordsMember ='';
    };

    //回车搜索
    $scope.enterSearchMember = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13)
        {
            $scope.searchEmployeeMember();
        }
    };
    //点击按钮搜索
    $scope.searchEmployeeMember = function () {
        var datas = {
            memberType  :   $scope.allMember != undefined? $scope.allMember : null, //会员类型（正式、潜在）
            keywords    :   $("#keywordMember").val() != undefined ? $("#keywordMember").val() : '', //关键字
            isCourse    :   $scope.privateLessonPurchase != undefined  ? $scope.privateLessonPurchase : '', //收费私课购买(状态)
            employeeId  :   $scope.employeeMemberInfoId != undefined ? $scope.employeeMemberInfoId : '',//员工id
        };
        if($scope.employeeMemberInformationName =='私教部') {
            $http({method:'get',url:'/personnel/sell-employee-member?'+$.param(datas)}).then(function (data) {
                if(data.data.data.length <= 0){
                    $scope.personnelNoDataShow = true;
                    $scope.personnelNoDataShows = true;
                    $scope.employeeMemberInformationList = data.data.data
                }else {
                    $scope.personnelNoDataShow = false;
                    $scope.personnelNoDataShows = false;
                    $scope.employeeMemberInformationList = data.data.data
                }
            },function (error) {
                console.log(error)
                Message.error("系统错误请联系管理人员")
            })
        }
        if($scope.employeeMemberInformationName =='销售部') {
            $http({method:'get',url:'/personnel/get-employee-member?'+$.param(datas)}).then(function (data) {
                if(data.data.data.length <= 0){
                    $scope.personnelNoDataShow = true;
                    $scope.personnelNoDataShows = true;
                    $scope.employeeMemberInformationList = data.data.data
                }else {
                    $scope.personnelNoDataShow = false;
                    $scope.personnelNoDataShows = false;
                    $scope.employeeMemberInformationList = data.data.data
                }
            },function (error) {
                console.log(error)
                Message.error("系统错误请联系管理人员")
            })
        }
    };
    $scope.closeBtnModalPrivate = function(){
        $('#myModals4').modal('hide');
    };

    //员工详情 切换
    $scope.userBasic = function () {
        $(".userBasic").addClass('active');
        $('.userMessage').removeClass('active');
        $('.userBasic1').show();
        $('.userMessage1').hide();
    };
    $scope.userMessage = function () {
        $(".userMessage").addClass('active');
        $('.userBasic').removeClass('active');
        $('.userMessage1').show();
        $('.userBasic1').hide();
    };
    $scope.checkboxLength = 0;
    $scope.otherCheckMemberId = [];
    //全部选中
    $scope.CheckAll = function () {
        var a = document.getElementsByClassName('checkboxs');
        for(i=0;i<a.length;i++){
            a[i].checked=true;
        }
        var len = $('.gradeA').find('.checkList').length
        for(var j=0;j<len;j++){
            $scope.otherCheckMemberId.push($('.gradeA').find('.checkList').eq(j).attr('data-value'));
        }
        $scope.checkboxLength = a.length
    };
    //全部不选
    $scope.UnCheck = function () {
        $scope.otherCheckMemberId.splice(0,$scope.otherCheckMemberId.length)
        $scope.checkboxLength = 0;
        var a = document.getElementsByClassName('checkboxs');
        for(i=0;i< a.length;i++){
            a[i].checked=false;
        }
    };
    $scope.otherCheck = function (id) {
        var $a = $('input.check'+id);
        if($a.is(":checked")){
            $scope.checkboxLength = $scope.checkboxLength + 1;
            $scope.otherCheckMemberId.push(id);
        }else{
            $scope.otherCheckMemberId.splice($.inArray(id,$scope.otherCheckMemberId),1);
            $scope.checkboxLength = $scope.checkboxLength - 1;
        }
       return false;
    };
    //进入私教列表
    $scope.privateEducation = function () {
        if($scope.otherCheckMemberId ==''){
            Message.warning("请选择会员")
            return
        }else {
            if ($scope.employeeMemberInformationName == '私教部'){
                $http({method:'get',url:'/private-teach/employee-info'}).then(function (data) {
                    $scope.employeeMemberPrivateDducation = data.data
                    // $("#myModals4").show();
                    $("#myModals4").modal('show');
                },function (error) {
                    console.log(error)
                    Message.error("系统错误请联系管理人员")
                })
            }
            if($scope.employeeMemberInformationName == '销售部'){
                $http({method:'get',url:'/user/get-adviser'}).then(function (data) {
                    $scope.employeeMemberPrivateDducation = data.data
                    // $("#myModals4").show();
                    $("#myModals4").modal('show');
                },function (error) {
                    console.log(error)
                    Message.error("系统错误请联系管理人员")
                })
            }
        }
    };
    //选择转入的名字
    $scope.privateEducationOk = function (id,name) {
        //选择新的id
        $scope.privateEducationOkId = id;
        $scope.privateEducationOkName = name;
    };
    //完成 转会员
    $scope.confirmationTransfer = function () {
        $.loading.show();
        if ($scope.organizationName == $scope.privateEducationOkName){
            // $("#myModals5").hide();
            $("#myModals5").modal('hide');
            // $("#myModals4").hide();
            $("#myModals4").modal('hide');
            // $("#myModals3").hide();
            $("#myModals3").modal('hide');
            // $("#myModals2").hide();
            $("#myModals2").modal('hide');
            // $(".modal-backdrop").hide();
            $.loading.hide();
            Message.warning("自己不能给自己转")
            return
        }
        var  data = {
            memberId:$scope.otherCheckMemberId, //会员
            counselorId:$scope.privateEducationOkId, //老id
            type:$scope.employeeMemberInformationName, // 是私教 还是 销售
            employeeId:$scope.employeeDetailsId, // 新id
        };
        $http({method:'post',url:'/personnel/update-member-status',data:$.param(data),headers: { 'Content-Type': 'application/x-www-form-urlencoded' }}).then(function (data) {
            if (data.data.status == "success"){
                // $("#myModals5").hide();
                $("#myModals5").modal('hide');
                // $("#myModals4").hide();
                $("#myModals4").modal('hide');
                // $("#myModals3").hide();
                // $("#myModals2").hide();
                $("#myModals3").modal('hide');
                $("#myModals2").modal('hide');
                // $(".modal-backdrop").hide();
                $(".userBasic").addClass('active');
                $('.userMessage').removeClass('active');
                $('.userBasic1').show();
                $('.userMessage1').hide();
                $.loading.hide();
                Message.success(data.data.data)
            }
            if (data.data.data == "修改失败"){
                // $("#myModals5").hide();
                $("#myModals5").modal('hide');
                // $("#myModals4").hide();
                $("#myModals4").modal('hide');
                // $("#myModals3").hide();
                $("#myModals3").modal('hide');
                // $("#myModals2").hide();
                $("#myModals2").modal('hide');
                // $(".modal-backdrop").hide();
                $(".userBasic").addClass('active');
                $('.userMessage').removeClass('active');
                $('.userBasic1').show();
                $('.userMessage1').hide();
                $.loading.hide();
                Message.success(data.data.data)
            }
        },function (error) {
            console.log(error);
            $.loading.hide();
            Message.error("系统错误请联系工作人员")
        })
    }

}).controller('employeeCourseCtrl',function($http,$scope,$rootScope){
    //员工约课
    var memberids = localStorage.getItem('reservationCourse');
    //转化成json对象
    var $memberIdArr = angular.fromJson(memberids);
    $scope.memberId  = $memberIdArr.id;
    $scope.memberType  = $memberIdArr.MemberType;

    //触发esc 返回上一级
    $("body").keydown(function (event) {
        if ((event.keyCode || event.which) == 27) {
            location.href='/personnel/index';
        }
    });
    //获取数据主页列表数据
    $scope.getData = function () {
        var url = '/check-card/group-class-data?memberId='+$scope.memberId
        $http({
            url: url,
            method: 'GET'
        }).then(function (data) {
            $scope.data = data.data;
        },function (error) {
            consoel.log(error)
            Message.warning('系统错误请联系工作人员')
        })
    };
    $scope.getData();
    $scope.dateCurrentw = myDate();
    //点击约课
    $scope.selectCourseSeat = function(id) {
        $scope.classId = id;
        $http({
            method: 'get',
            url: '/check-card/group-class-member-rule-data?memberId='+$scope.memberId+'&classId='+$scope.classId+"&memberCardId="+''+'&isEmployee=1'
        }).then(function (data) {
            $scope.isCanClass = data.data.isCanClass;
            $scope.isAboutClass = data.data.isAboutClass;
            $scope.isDance = data.data.isDance;
            if ($scope.isCanClass == false){
                Message.warning('您不能预约这节课！');
                $('#myModal').modal('hide');
                return
            }
            $scope.getAboutSeatRule();
        },function (err) {
            console.log(err);
            Message.error("系统错误请联系工作人员")
        });
    };
    $scope.totalRows = [];
    $scope.getAboutSeatRule = function () {
        if ($scope.isAboutClass == true){
            Message.warning('已经预约过了');
            $('#myModal').modal('hide');
            return
        }
        if ($scope.isDance === true) {
            //渲染不是舞蹈的课程座位
            var url = '/check-card/get-seat-detail?id=' + $scope.classId + '&memberId='+$scope.memberId
            $http({
                url: url,
                method: 'GET'
            }).then(function (data) {
                if (data.data.status == 'success') {
                    $scope.items = data.data.message;
                    for(var i = 1;i<=$scope.items.total_rows;i++){
                        $scope.totalRows.push(i);
                    }
                    angular.element(document).ready(function () {
                        $('#myModal').modal('show');
                        var seatLength = $scope.items.length;
                    })
                }
            }, function (error) {
                console.log(error);
                Message.warning('系统错误请联系工作人员')
                $('#myModal').modal('hide');
            })
        }
    };
    /**
     * 选择座位
     */
    //选择座位
    var seatNum;
    $scope.seatSelect = function (id, num,i) {
        if($('.courseSeates').eq(i).hasClass('vip')){
            Message.warning('您好，员工不能选择VIP座位!');
            return;
        }
        if ($('.courseSeates').eq(i).hasClass('notSelect')) {
            return;
        } else {
            seatNum = id;
            var number = parseInt(seatNum);
            $scope.seatID = number;
            $('.courseSeates').removeClass('selected');

            $('.courseSeates').eq(i).addClass('selected');
        }
    };
    $scope.modalClose = function () {
        $("#myModal").hide();
        $scope.totalRows.splice(0,$scope.totalRows.length);
    };
    //跳转预约成功
    $scope.appointment = function(){
        var url = '/check-card/set-about-class-record';
        var orderData = {
            classId: $scope.classId, //课程ID
            _csrf_backend:$('#_csrf').val(),
            seatId:$scope.seatID, //选择座位
            memberId: $scope.memberId,//会员ID
            classType: 'group', //课程类型
            is_employee:true,
            aboutType:'mobile'
        };
        if (orderData.seatId == '' || orderData.seatId == undefined){
            Message.warning('请选择座位')
            // $('#myModal').modal('hide');
            return;
        }
        $http({
            method: 'POST',
            url: url,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(orderData)
        }).then(function (success) {
            if(orderData.seatId != ''){
                if(success.data.status == 'repeat'){
                    $('#myModal').modal('hide');
                    Message.warning('请不要点击多次立即预约');
                    return
                }
                if (success.data.message == '预约成功'){
                    $scope.totalRows.splice(0,$scope.totalRows.length);
                    var classsids = success.data.data;
                    $('#myModal').modal('hide');
                    $('#myModalComplete').modal('show');
                    $scope.getData();
                    var  urls = '/check-card/get-about-class-detail?id='+ classsids
                    $http({
                        method: 'GET',
                        url:urls,
                    }).then(function (success) {
                        $scope.datase = success.data.data;
                        $scope.printers = success.data.data;
                        $scope.getData();
                    })
                    $('#myModal').modal('hide');
                    $('#myModalComplete').modal('show');
                }
                //开课前多少分钟不能预约
                else if(success.data.data.status == "endAboutLimit"){
                    Message.warning("开课"+ success.data.data.endClassLimit +"分钟前不能预约");
                    return
                }
                else if(success.data.status == 'error'){
                    Message.warning(success.data.data);
                    $('#myModal').modal('hide');
                    $scope.getData();
                }
            }
        }, function (error) {
            console.log(error);
            Message.warning('系统错误请联系工作人员');
            $('#myModal').modal('hide');
        })
    };
    $scope.backPre = function(){
        history.go(-1);
    };
    /****** 打印机start *****/
    //打印单条
    $scope.aboutPrints = function (item) {
        $scope.printers = item;
        $scope.printTicket();
    };
    //end
    $scope.printTicket = function () {
        if($scope.printers == undefined || !$scope.printers){
            Message.warning('没有预约的课程无法打印');
            return false;
        }
        $scope.aboutId = $scope.printers.id;
        $scope.getPrintHtml();
    };
    $scope.getPrintHtml = function () {
        var open = 1;
        if (open < 10) {
            var $prints = $('#coursePrints');
            var bodyHtml  = $('body').html();
            $scope.bdhtml = $prints.html();//获取当前页的html代码
            window.document.body.innerHTML= $scope.bdhtml;
            $scope.updateAboutStatus();
            window.print();
            window.document.body.innerHTML= bodyHtml;
            location.replace(location.href);
        } else {
            window.print();
        }
    };
    $scope.updateAboutStatus = function () {
        $http.get('/check-card/update-about-print?id='+$scope.aboutId).then(function (result) {

        });
    };
    /****** 打印机end ******/
    //判断是否点击回车按钮
    angular.element(document).ready(function () {
        //点击回车立即预约
        $("#myModal").keydown(function (event) {
            if ((event.keyCode || event.which)  == 13) {
                $scope.appointment();
            }
        });
        //点击回车关闭模态框
        $('#myModalComplete').keydown(function(event){
            if ((event.keyCode || event.which)  == 13) {
                $('#myModalComplete').modal('hide');
            }
        });
    });
});

function myDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate

    return currentdate;
};

app.filter('employee_status_func', function() { //可以注入依赖
    return function(text) {
        if(text == '1'){
            return '在职';
        }else if(text =='2'){
            return '离职';
        }else if(text =='3'){
            return '调岗';
        }else if(text =='4'){
            return '全职';
        }else if(text =='5'){
            return '兼职';
        }
    }
});