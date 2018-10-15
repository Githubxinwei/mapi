/**
 * Created by 杨大侠 on 2017/8/11.
 */

var appModule = angular.module('App');
appModule.controller('pictureManagementCtrl', function ($scope, $http,Upload,$location, $rootScope,$interval,$window) {

    $(document).ready(function() {
        $("#id_label_single").select2();
        $("#id_label_single1").select2();
        $("#Cancel").addClass('displayBlock')
        $("#selectCancel").addClass("displayNone")
    });

    //初始化场馆
    $scope.initVenue = function () {
        $http({url: '/rechargeable-card-ctrl/get-venue', method: 'get'}
        ).then(function (data) {
            $scope.venueAll = data.data.venue;
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.initVenue()
    $scope.initTypeData = function () {
        $http({ method: 'get',url: '/picture-management/get-pic-type'}).then(function (data) {
            $scope.initTypeData = data.data.type;
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.initTypeData();
    $scope.initUrl = function () {
        var startTime = $("#reservation").val().substr(0, 10);
        if(startTime == ''){startTime = startTime}else{startTime = startTime+"00:00:00"}
        var endTime = $("#reservation").val().substr(-10, 10);
        if(endTime == ''){endTime = endTime}else{endTime = endTime+"23:59:59"}
        var data = {
            sortType:$scope.sortType == undefined ? '': $scope.sortType,
            sortName:$scope.sort == undefined ? '': $scope.sort,
            startTime:startTime,
            endTime:endTime,
            venueId:$scope.venueId == undefined ? '': $scope.venueId, // 场馆
            type:$scope.selectType == undefined ? '': $scope.selectType, //类别
            keywords:$scope.searchValue == undefined ? '': $scope.searchValue, //输入框的值
        }
        $scope.pageInitUrl = '/picture-management/pic-info?'+$.param(data);
    }
    $scope.initUrl();

    //初始化列表数据
    $scope.initListData = function () {
        $http({method:'get',url:$scope.pageInitUrl}).then(function (data) {
            if (data.data.data != "" && data.data.data != undefined && data.data.data.length != undefined) {
                $scope.listDataItem = data.data.data
                $scope.pages = data.data.pages;
                $scope.shopShow = false;
            }else {
                $scope.listDataItem = data.data.data
                $scope.pages = data.data.pages;
                $scope.shopShow = true
            }
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})
    }
    $scope.initListData()
    //回车
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchClick();
        }
    }
    //点击按钮搜索
    $scope.searchClick = function () {
        $scope.initUrl();
        $scope.initListData();
    }

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pageInitUrl = '/picture-management/pic-info?page='+value;
            $scope.initListData();
        }
    }
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.initListData();
    };
    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.initUrl();
        $scope.initListData();
    };
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    
    $scope.checkboxLength = 0;
    $scope.otherCheckMemberId = [];
    //全部选中
    $scope.CheckAll = function (num) {
        $("#Cancel").removeClass('displayBlock')
        $("#Cancel").addClass('displayNone')
        $("#selectCancel").removeClass("displayNone")
        $("#selectCancel").addClass("displayBlock")
        var a = document.getElementsByClassName('checkboxs');
        for(i=0;i<a.length;i++){
            a[i].checked=true;
        };
        var len = $('.gradeA').find('.checkList').length
        for(var j=0;j<len;j++){
            $scope.otherCheckMemberId.push($('.gradeA').find('.checkList').eq(j).attr('data-value'));
        }
        $scope.checkboxLength = a.length
    }
    //全部不选
    $scope.UnCheck = function () {
        $("#Cancel").removeClass('displayNone')
        $("#Cancel").addClass('displayBlock')
        $("#selectCancel").removeClass("displayBlock")
        $("#selectCancel").addClass("displayNone")
        $scope.otherCheckMemberId.splice(0,$scope.otherCheckMemberId.length)
        $scope.checkboxLength = 0;
        var a = document.getElementsByClassName('checkboxs');
        for(i=0;i< a.length;i++){
            a[i].checked=false;
        };
    }
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
    }
    $scope.img = {
        imagePic:''
    }
    //关闭
    $scope.closeModel = function () {
        $scope.imagePic = '';
        $scope.addImageName = '';
        $scope.addType = '';
        $scope.img = '';
    }

    $scope.upload = function (file, text) {
        Upload.upload({
            url: '/class/upload',
            method: 'POST',
            data: {UploadForm: {imageFile: file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            $scope.fileSize = parseInt(file.size / 1024)
            if (result.data.status == 'success') {
                    $scope.img.imagePic = result.data.imgLink;
            } else if (result.data.data == '"图片格式出现错误"') {
                    $scope.img.imagePic = result.data.imgLink;
            }
        }, function (error) {
            console.log(error)
            Message.warning("系统错误请联系工作人员");
        });
    }
    //上传图片
    $scope.addImages = function () {
        if($scope.img.imagePic == null || $scope.img.imagePic == undefined || $scope.img.imagePic == ''){Message.warning("请选择图片");return;}
        if($scope.addImageName == null || $scope.addImageName == undefined || $scope.addImageName == ''){Message.warning("请输入图片名称");return}
        if($scope.addType == null || $scope.addType == undefined || $scope.addType == ''){Message.warning("请选择类别");return}
        $scope.potentialMemberButtonFlag1 = true;
        var img = new Image();
        img.src = $scope.img.imagePic;
        img.onload = function(){
            var data ={
                name:$scope.addImageName,
                type:$scope.addType,
                wide: img.width,
                height:img.height,
                size:$scope.fileSize,
                url:$scope.img.imagePic
            }

            $http({method:'post',url:'/picture-management/add-pic',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
                if (success.data.status == 'success'){
                    window.location.replace('/picture-management');
                    $("#myModal").hide()
                    $('.modal-backdrop').hide()
                    $scope.initListData()
                    $scope.img = '';
                    $scope.addType = '';
                    $scope.addImageName = '';

                }
            },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})

        };
    }
    $scope.removeImage = function () {
        if($scope.otherCheckMemberId == ''){Message.warning("请选择需要删除的图片");return;}
        if($scope.otherCheckMemberId != ''){
            var data = {picId:$scope.otherCheckMemberId}
            Sweety.remove({
                url              : "",
                http             : $http,
                title            : '确定要删除吗?',
                text             : '删除后信息无法恢复',
                confirmButtonText: '确定',
                data             : {
                    action: 'unbind'
                }
            }, function () {
                $http({method:'post',url:'/picture-management/del-pic-all',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
                    $scope.otherCheckMemberId.splice(0,$scope.otherCheckMemberId.length)
                    $scope.checkboxLength = 0;
                    var a = document.getElementsByClassName('checkboxs');
                    for(i=0;i< a.length;i++){
                        a[i].checked=false;
                    };
                    $scope.initListData();
                },function (error) {
                    console.log(error);
                    Message.error("系统错误请留联系管理人员")
                })
            });

        }
    }


    //查看详情
    $scope.viewPictureDetails = function (id) {
        $http({method:'get',url:'/picture-management/pic-details?picId='+id}).then(function (data) {
            $scope.viewPictureDetailsData = data.data;
        },function (error) {console.log(error);Message.error("系统错误请联系工作人员");})
        $("#myModal1").show();
    }
    //删除一条数据
    $scope.deleteOne = function (id) {
        Sweety.remove({
            url: "/picture-management/pic-data?picId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '删除图片后无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.initListData();
            $("#myModal1").hide();
            $("#myModal2").hide();
            $('.modal-backdrop').hide()
        });
    }
    //修改保存
    $scope.modifySave = function (id,name,url) {
        var data = {
            name:name,
            id:id,
            type:$scope.viewPictureDetailsData.type,
            url:url,
            _csrf_backend: $('#_csrf').val()
        }
        $http({method:'post',url:'/picture-management/update-pic',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
            $scope.initListData();
            $("#myModal1").hide();
            $("#myModal2").hide();
            $('.modal-backdrop').hide()
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})
    }
    //图片类别
    $scope.pictureCategory = function () {
        $("#myModal3").show();
        $scope.pictureCategoryData();
    }
    $scope.pictureCategoryData =  function () {
        $http({method:'get',url:'/picture-management/get-pic-type-data'}).then(function (data) {
            $scope.pictureCategoryListData = data.data.data;
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})
    }
    //图片类别 修改
    $scope.deletePictureCategory = function (id) {
        $("#myModal5").show();
        $http({method:'get',url:'/picture-management/get-pic-type-one?id='+id}).then(function (data) {
            $scope.deletePictureCategoryData = data.data.data;
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})
    }
    //图片类别 删除
    $scope.pictureCategoryModification = function (id) {

        Sweety.remove({
            url: "/picture-management/del-pic-type?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '删除后无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.pictureCategoryData();
        },function(){

        },true,true);
    }


    $scope.okTypeClose = function () {
        $("#myModal4").hide();
        $scope.newType = ''
    }
    //新增 图片类型
    $scope.okType = function (newType) {

        $scope.potentialMemberButtonFlag = true;

        $http({method:'get',url:'/picture-management/pic-type-name?name='+newType}).then(function (data) {
            if(data.data.message == '图片类型名称不存在'){
                var data1 = {
                    _csrf_backend: $('#_csrf').val(),
                    typeName :newType,
                }
                $http({method:'post',url:'/picture-management/pic-type-insert-post',data:$.param(data1),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
                    if (success.data.status == "success"){
                        $scope.pictureCategoryData()
                        $(".modal-backdrop").hide()
                        $("#myModal4").hide();
                        Message.success(success.data.data)
                        $scope.newType = ''
                    }
                },function (error) {
                    console.log(error);Message.error("系统错误请联系管理人员")})
            }
            if(data.data.message == '图片类型名称已经存在'){
                Message.warning(data.data.message)
                return
            }
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})
    }
    
    
    //修改图片详情
    $scope.okTypeUpdata = function () {

        $http({method:'get',url:'/picture-management/pic-type-name?name='+$scope.deletePictureCategoryData.type_name}).then(function (data) {
            if(data.data.message == '图片类型名称不存在'){
                var data1 = {
                    _csrf_backend: $('#_csrf').val(),
                    typeName  :$scope.deletePictureCategoryData.type_name,
                    picTypeId:$scope.deletePictureCategoryData.id,
                }
                $http({method:'post',url:'/picture-management/pic-type-update-post',data:$.param(data1),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
                    $scope.pictureCategoryData()
                    $("#myModal5").hide();
                    $(".modal-backdrop").hide()
                },function (error) {console.log(error);Message.error("系统错误请联系管理人员")})
            }
            if(data.data.message == '图片类型名称已经存在'){
                Message.warning(data.data.message)
                return
            }
        },function (error) {console.log(error);Message.error("系统错误请联系管理人员");})
    }
})