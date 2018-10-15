/**
 * 后台 - 课种管理 - 课种管理模块
 *@create author Hou kaiXin houkaixin@itsport.club
 *@create 2017/3/30
 */

angular.module('App').controller('classCtrl', function ($scope, $http, Upload) {
    //初始化
    $scope.init = function () {
        $scope.pageInitUrl = '/class/course';
        $scope.getClassModel();
        $scope.getClassData();
        $scope.type = true;
        $scope.num  = 0;
    };
    //分页
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.getClassModel();
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pageInitUrl = '/class/course?page='+value;
            $scope.getClassModel();
        }
    };

    //  分页数据信息
    $scope.getClassModel = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).then(function (result) {
            if (result.data.data.length!=0) {
                $scope.items        = result.data.data;
                $scope.pages        = result.data.pages;
                $scope.dataInfo     = false;
                $scope.searchData   = false;
            } else {
                $scope.items = result.data.data;
                $scope.pages = result.data.pages;
                if($scope.searchParams!=null){
                 $scope.searchData = true;
                 $scope.dataInfo  = false;
                }else{
                $scope.dataInfo = true;

                }
            }
            $.loading.hide();
        })
    };

    // 获取所有数据信息
    $scope.getClassData = function () {
        $http.get('/class/class').then(function (results) {
            $scope.Myitems = results.data;
        })
    };


   //初始化 课种表单数据
    $scope.myInit = function(){
        $scope.course.pid="choose";
        $scope.Myitems=[];
    };
    $scope.init();

    //初始化url
    $scope.initPath = function () {
        $scope.searchParams = $scope.search();
        $scope.pageInitUrl = '/class/course?' + $.param($scope.searchParams);
    };

    /**搜索方法（搜索栏）***/
    $scope.searchClass = function () {
        $scope.initPath();
        $scope.getClassModel();
    };
    /**
     * 按回车键进行搜索
     */
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.searchClass();
        }
    };
   /**主界面(字段)搜索**/
   $scope.changeSort = function (attr,sort) {
       $scope.sortType = attr;
       $scope.switchSort(sort);
       $scope.searchClass();
   };
    $scope.switchSort = function (sort) {
        if(!sort){
            sort = 'DESC';
        }else if(sort == 'DESC'){
            sort = 'ASC';
        }else{
            sort = 'DESC'
        }
        $scope.sort = sort;
    };

    /**页面搜索（搜索栏）***/
    $scope.search = function () {
        return {
            name        : $scope.className  != undefined ? $scope.className  : null,//名称
            pic         : $scope.pic        != undefined ? $scope.pic        : null,//图片
            class_type  : $scope.class_type != undefined ? $scope.class_type : null,//课程类型
            category    : $scope.category   != undefined ? $scope.category    : null,//分类
            create_id   : $scope.create_id  != undefined ? $scope.create_id  : null,//创建人
            sortType    : $scope.sortType   != undefined ? $scope.sortType   : null,
            sortName    : $scope.sort       != undefined ? $scope.sort       : null,
            created_at  : $scope.created_at != undefined ? $scope.created_at  : null//创建时间
        }
    };

    /**删除弹框提示***/
    $scope.courseDel = function (id,name) {
        Sweety.remove({
            url: "/class/course-del?id=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '课种删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getClassModel();
            $scope.getClassData();
        },function () {

        },true);
    };
    $scope.course={
        name        : '',
        class_type  :''
    };
    /**课种增加***/
    $scope.addCourse = function () {
        if ($scope.course.name != ""&&$scope.course.class_type !="") {
            $scope.course = {
                _csrf_backend: $('#_csrf').val(),
                name          : $scope.course.name,
                class_type   :  $scope.course.class_type,
                pid           : $scope.course.pid,
                course_desrc : $scope.course.desrc,
                pic           : $scope.course.pic
            };
            if($scope.course.pid=="choose"){
                Message.warning("请选择上级分类");
                return false;
            }
            //数据过滤
            $.each($scope.course,function(name,value){
                 if(value==undefined||value==""){
                     delete $scope.course[name];
                 }
            });
            //数据请求
            $http({
                url: "/class/course-add",
                method: 'POST',
                data: $.param($scope.course),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function () {
                window.location.replace('/class/index');
            });
        } else {
            Message.warning("课种名或课种不能为空");
        }
    };

    /**获取单条课种信息***/
    $scope.courseType = function (id) {
        $http.get("/class/course-check?id=" + id).then(function (result) {
            $scope.datas = result.data;
            $scope.datas.class_type = result.data.class_type;
            $scope.myClassType($scope.datas.class_type);
        });
    };

   //获取连带的课种信息
    $scope.myClassType = function(type){
        if(type!=""){
            $http.get('/class/class-type?type='+type).then(function (results) {
                $scope.Myitems = results.data;
            })
        }
    };


    /**获取修改表单提交过来的信息***/
    $scope.update = function () {
        var $desrc = $scope.datas.course_desrc;
        //接收数据
        $scope.courseData = {
            _csrf_backend: $('#_csrf').val(),
            id          : $scope.datas.id,//课种id
            name        : $scope.datas.name,//课种名称
            pid         : $scope.datas.pid,//选择分类
            pic         : $scope.datas.pic,//图片
            class_type  : $scope.datas.class_type//课程类型
        };
        if($scope.datas.pid=="choose"){
             Message.warning("上级分类不能为空");
             return false;
        }
        // 判断desrc数据是否发送
        if ($desrc != null && $desrc != "" && $desrc!=undefined){
            $scope.courseData.course_desrc = $scope.datas.course_desrc;
        }else{
            $scope.courseData.course_desrc ="";
        }
        //数据请求
        if($scope.datas.name!=""&&$scope.datas.name!=undefined&&$scope.datas.class_type!="") {
            $http({
                url: "/class/course-update",
                method: 'POST',
                data: $.param($scope.courseData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function () {
                window.location.replace('/class/index');
            });
        }else{
            Message.warning("课种名或课程类型不能为空");
        }

    };
    //上传图片
    $scope.uploadCover = function (file, text) {
        if (file) {
            if (file.size > 2000000) {
                Message.warning('文件太大不能上传')
            } else {
                $scope.upload(file, text);
            }

        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
        }
    };

    $scope.upload = function (file, text) {
        Upload.upload({
            url    : '/class/upload',
            method :'POST',
            data   : {UploadForm: {imageFile: file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                if (text == 'update') {
                    $scope.datas.pic = result.data.imgLink;
                } else {
                    $scope.course.pic = result.data.imgLink;
                }
            } else {
                Message.warning(result.data.data);
            }
        });
    };

    // 下拉筛选
    $scope.classChangeType = function (){
        $http.get("/class/course?class_type=" + $scope.classTypeSelect).success(function (result){
            if (result.data.length!=0) {
                $scope.items        = result.data;
                $scope.pages        = result.pages;
                $scope.dataInfo     = false;
                $scope.searchData   = false;
            } else {
                $scope.items = result.data;
                $scope.pages = result.pages;
                if($scope.searchParams!=null){
                    $scope.searchData = true;
                    $scope.dataInfo  = false;
                }else{
                    $scope.dataInfo = true;
                }
            }
        })
    };
    //团课和私教课的信息遍历(针对修改课种功能)
    $scope.searchType = function(type){
        if(type!=""){
            $http.get('/class/class-type?type='+type).then(function (results) {
                $scope.Myitems = results.data;
            })
        }
        $scope.datas.pid = "choose";
    };
    //团课和私教课的信息遍历(针对修改课种功能)
    $scope.theSearchType = function(type){
        if(type!=""){
            $http.get('/class/class-type?type='+type).then(function (results) {
                $scope.Myitems = results.data;
            })
        }
    };
    //添加新增上级分类模板
    $scope.addApplyHtml = function (){
        $scope.htmlAttr = 'addNextTemplate';
        $scope.num  = $scope.num + 1;
        $http.get('/course/add-type?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.applyType = result.data.html;
        });
    };
});