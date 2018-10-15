/**
 * Created by 杨大侠 on 2017/6/17.
 */
angular.module('App').controller('roleCtrl', function($scope,$http) {

    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();

    });
    $scope.pageInitUrl = '/role/role-info';

    //添加搜索页数
    $scope.skipPage = function(value){
        if($scope.getCompanyIdToJumpPage == '' || $scope.getCompanyIdToJumpPage == null || $scope.getCompanyIdToJumpPage == undefined){
            $scope.getCompanyIdToJumpPage = '';
        }
        if(value != undefined){
            $scope.pageInitUrl = '/role/role-info?companyId='+$scope.getCompanyIdToJumpPage+'&per-page=8&page='+value+'&per-page=8';
            $scope.getDataPage();
        }
    };

    //初始化列表数据
    $scope.initListData = function () {
        $.loading.show();
        $http({method:'get',url:$scope.pageInitUrl}).then(function (data) {
            if(data.data.data == "" || data.data.data == undefined || data.data.data.length == undefined){
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }
            else{
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
            $.loading.hide();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        })
    };
    $scope.initListData();
    //选择公司
    $scope.selectCompany = function () {
        $http({method:'get',url:'/site/get-auth-company' }).then(function (data) {
            $scope.selectCompanyDatas = data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };
    $scope.selectCompany();
    //触发主页下拉框公司搜索
    $scope.selectCompanyChange = function (id) {
        $scope.getCompanyIdToJumpPage = id;
        $.loading.show();
        $http({method:'get',url:'/role/role-info?companyId='+id}).then(function (data) {
            if(data.data.data == "" || data.data.data == undefined || data.data.data.length == undefined){
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = true;
            }
            else{
                $scope.listDatas = data.data.data;
                $scope.pages = data.data.pages;
                $scope.dataInfo = false;
            }
            $.loading.hide();
            $('#PageHomeNumFlag').val('');
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        })
    }
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.initListData();
    };
    $scope.getDataPage = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.listDatas = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.listDatas = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide()
        });
    };
    $scope.newEmployeesData = {
        roleName:'',
        corporateName:''
    }
    //选择公司
    $scope.newSelectCompany = function () {
        $http({method:'get',url:'/site/get-auth-company' }).then(function (data) {
            $scope.newSelectCompanyData = data.data;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.newSelectCompany();
    //搜索关键字
    $scope.searchWords = function () {
      var roleKeywords = $scope.roleKeywords;
      var id = $scope.selectCompanyDataId;
      if (id == undefined || id==null || id==''){
          id='';
      }
      if (roleKeywords == undefined || roleKeywords==null || roleKeywords==''){
          roleKeywords='';
      }
          $.loading.show();
          $http({method:'get',url:'/role/role-info?companyId='+id+'&keywords='+roleKeywords}).then(function (data) {
              if(data.data.data == "" || data.data.data == undefined || data.data.data.length == undefined){
                  $scope.listDatas = data.data.data;
                  $scope.pages = data.data.pages;
                  $scope.dataInfo = true;
              }
              else{
                  $scope.listDatas = data.data.data;
                  $scope.pages = data.data.pages;
                  $scope.dataInfo = false;
              }
              $('#PageHomeNumFlag').val('');
              $.loading.hide();
          },function (error) {
              console.log(error);
              Message.error("系统错误请联系工作人员");
              $.loading.hide();
          })
    };
    //enter搜索角色名
    $scope.enterSearchRoles = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchWords();
        }
    };
    //清空搜索
    $scope.clearSearch = function () {
        $scope.roleKeywords = '';
        $scope.selectCompanyDataId = '';
        $('.select2-selection__rendered').text('不限').attr('title', '');
        //初始化数据
        $scope.initListData();
    }
    
    //添加员工模态框
    $scope.addShowModel = function () {
        $("#newEmployees").modal("show");
    }
    
    //新增员工添加
    $scope.newEmployeesAdd = function () {
        if ($scope.newEmployeesData.roleName == ''){
            Message.warning("请您输入角色姓名")
            return
        }
        if ($scope.newEmployeesData.corporateName == ''){
            Message.warning("请您输入公司名称")
            return
        }
        var data = {
            name:$scope.newEmployeesData.roleName,
            companyId:$scope.newEmployeesData.corporateName,
            _csrf_backend:$("#_csrf").val()
        }
        $http({method:'post',url:"/role/add-role",data:$.param(data),headers:{'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            $scope.initListData();
            $("#newEmployees").modal("hide")
            $scope.newEmployeesData.roleName = '';
            $scope.newEmployeesData.corporateName = '';
            $scope.selectCompanyDataId = '';
            $scope.selectCompany();
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系管理人员")
        })
    }

    //分配员工
    $scope.assignEmployees = function (id,companyId) {
        $scope.roleId = id;
        $scope.companyId = companyId;
        $scope.departmenturl = '/rechargeable-card-ctrl/get-department-date?companyId='+companyId;
        $scope.getPeopleData();
        $scope.assignEmployeeSelectionDepartment();
    };
    //获取角色数据
    $scope.getPeopleData = function(){
        $.loading.show();
        $http({method:'get',url:'/role/role-date?companyId='+ $scope.companyId}).then(function (data) {
            if (data.data.data <= 0){
                $scope.assignEmployeesData = data.data.data;
                $scope.peoplesNum = data.data.count;
                $scope.payNoMoneyDataShow1 = true;
            }else {
                $scope.assignEmployeesData = data.data.data;
                $scope.peoplesNum = data.data.count;
                $scope.payNoMoneyDataShow1 = false;
            }
            $.loading.hide();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        })
    };

    //索要分配的角色id
    $scope.adminsId = [];
    var updateSelected = function(action,id,name){
        if(action == 'add' && $scope.adminsId.indexOf(id) == -1){
            $scope.adminsId.push(id);
        }
        if(action == 'remove' && $scope.adminsId.indexOf(id)!=-1){
            var idx = $scope.adminsId.indexOf(id);
            $scope.adminsId.splice(idx,1);
        }
    };
    $scope.updateSelection = function($event, id){
        var checkbox = $event.target;
        var action = (checkbox.checked?'add':'remove');
        updateSelected(action,id);
    };
    $scope.isSelected = function(id){
        return $scope.adminsId.indexOf(id)>=0;
    };
    //分配员工完成
    $scope.assignEmployeesAdd = function () {
        $scope.getAssignEmployeesData = function(){
           return{
               _csrf_backend: $('#_csrf').val(),
               roleId        : parseInt($scope.roleId),//角色id
               adminId       : $scope.adminsId //分配员工id Arr
            }
        };
        if($scope.adminsId == '' || $scope.adminsId == undefined || $scope.adminsId == null){
            Message.warning("暂无员工数据");
            return;
        }
        $http({
            url: "/role/update-employee",
            method: 'POST',
            data: $.param($scope.getAssignEmployeesData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result){
            if(result.data.status == "success"){
                Message.success(result.data.data);
                $('#assignEmployees').modal('hide');
                $scope.adminsId = [];
            }
            if(result.data.status == "error"){
                Message.warning('分配失败');
            }
        });
    };
    //模态框关闭初始化数据
    $('#assignEmployees').on('hidden.bs.modal', function (e) {
       $scope.getSearchData = '';
    });
    //查看详情取消
    $scope.newEmployeesAddCancel = function () {
        $("#newEmployees").hide();
        $(".modal-backdrop").hide();
    };

    //分配员工搜索
    $scope.allocationSearch = function () {
        if ($scope.getSearchData == '' || $scope.getSearchData == undefined){
            Message.warning("请输入需要搜索的内容");
            return;
        }else {
            $.loading.show();
            $http({method:'get',url:'/role/role-date?key='+$scope.getSearchData}).then(function (data) {
                if(data.data.data.length != 0){
                    $scope.assignEmployeesData =  data.data.data;
                    $scope.payNoMoneyDataShow1 = false;
                }else{
                    $scope.assignEmployeesData =  data.data.data;
                    $scope.payNoMoneyDataShow1 = true;
                }
                $.loading.hide();
            },function (error) {
                console.log(error);
                Message.error("系统错误请联系工作人员");
                $.loading.hide();
            })
        }
    };
    //分配员工清空按钮
    $scope.clearInfo = function(){
        $scope.selectionDepartmentId = '';
        $scope.getSearchData = '';
        $scope.getPeopleData();
    };
    //分配员工选择部门
    $scope.assignEmployeeSelectionDepartment = function () {
        $http({method:'get',url:$scope.departmenturl}).then(function (data) {
            $scope.selectionDepartment = data.data.venue;
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    };
    $scope.assignEmployeeSelectionDepartmentData = function (id) {
        $.loading.show();
        $http({method:'get',url:'/role/role-date?departmentId='+id}).then(function (data) {
            if(data.data.data.length != 0){
                $scope.assignEmployeesData =  data.data.data;
                $scope.payNoMoneyDataShow1 = false;
            }else{
                $scope.assignEmployeesData =  data.data.data;
                $scope.payNoMoneyDataShow1 = true;
            }
            $.loading.hide();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        })
    };
    //分配员工取消
    $scope.assignEmployeesCancel = function () {
        $('#assignEmployees').modal('hide');
    };

    //删除
    $scope.delete = function (id,name) {
        Sweety.remove({
            url : '/role/delete?roleId='+id,
            http : $http,
            title : '确定要删除吗?',
            text : '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data : {
                action: 'unbind'
            }
        },function () {
            $scope.initListData();
            $scope.selectCompany();
            $scope.selectCompanyDataId = '';
        })
    };


    //查看详情
    $scope.viewDetails = function (id,name) {
        $scope.keywords = '';
        $scope.viewDetailsId = id;
        $('#viewDetails').modal('show');
        $http({method:'get',url:'/role/get-employee-member?roleId='+id}).then(function (data) {
            if(data.data.data.length <= 0){
                $scope.viewDetailsData = data.data.data;
                $scope.payNoMoneyDataShow = true;
            }else {
                $scope.viewDetailsData = data.data.data;
                $scope.payNoMoneyDataShow = false;
            }
            $scope.viewDetailsName = data.data.pages;
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系管理人员")
        })
    }
    //搜索角色
    $scope.searchRole = function () {
        var keywords = $scope.keywords;
        if (keywords == '' || keywords == null || keywords == undefined) {
            keywords = '';
        }
        $.loading.show();
        $http({method:'get',url:'/role/get-employee-member?roleId='+$scope.viewDetailsId+'&keywords='+keywords}).then(function (data) {
            if(data.data.data.length <= 0){
                $scope.viewDetailsData = data.data.data;
                $scope.payNoMoneyDataShow = true;
            }else {
                $scope.viewDetailsData = data.data.data;
                $scope.payNoMoneyDataShow = false;
            }
            $scope.viewDetailsName = data.data.pages;
            $.loading.hide();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员");
            $.loading.hide();
        })
    };
    //enter搜索角色
    $scope.enterSearch = function (e) {
        //console.log(e);
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchRole();
        }
    };
    //查看详情清空按钮
    $scope.clearDetailKeywords = function(){
        $scope.keywords = '';
        $scope.searchRole();
    };
    //查看详情 移除角色
    $scope.removeRoles = function (id,name) {
        $http({method:'get',url:'/role/update-admin?id='+id}).then(function (data) {
            $scope.viewDetails($scope.viewDetailsId);
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    }
})