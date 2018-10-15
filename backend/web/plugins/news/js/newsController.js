/**
 * Created by suyu on 2017/9/7.
 */
angular.module('App').controller('newsCtrl', function($scope,$http,$timeout){

    /**** 页面加载后执行 ****/
    $(document).ready(function (){
        $scope.initTime();           //初始化时间
        $scope.getVenueData();       //获取场馆列表
        $scope.getAllList();         //获取数据列表
        $scope.searchData  = false; //暂无数据不显示
        $scope.venueSelect = '';     //暂无数据不显示
    });

    /**** 头部条件筛选 ****/
    // 启动日期插件
    $("#doubleTime").daterangepicker();
    // 设置时间
    $scope.initTime = function (){
        var today = new Date();             //获取今日时间戳
        var y = today.getFullYear();         //获取当前年份
        var m = today.getMonth() + 1;        //获取当前月份
        var d = today.getDate();             //获取当前日期
        var time  = $("#doubleTime").val();  //时间插件的值
        $scope.todayTime = y+"-"+m+"-"+d;    //今日日期格式：yyyy-MM-dd
        // 判断日期插件的值是否为空
        if(time == '' || time == undefined){
            $scope.startTime = $scope.todayTime + " " + "00:00:00";                      //今日开始时间格式：yyyy-MM-dd HH-MM-SS
            $scope.endTime   = $scope.todayTime + " " + "23:59:59";                      //今日结束时间格式：yyyy-MM-dd HH-MM-SS
            $("#doubleTime").val($scope.todayTime); //设置插件格式：yyyy-MM-dd - yyyy-MM-dd
        }
        else{
            var startTime = time.substr(0, 10);              //获取时间插件的开始日期
            var endTime   = time.substr(-10, 10);            //获取时间插件的结束日期
            $scope.startTime = startTime + " " + "00:00:00"; //时间插件的开始时间格式：yyyy-MM-dd HH-MM-SS
            $scope.endTime   = endTime   + " " + "23:59:59"; //时间插件的结束时间格式：yyyy-MM-dd HH-MM-SS
        }
    };
    // 获取场馆信息
    $scope.getVenueData = function (){
        $http.get('/site/get-auth-venue').success(function (data){
            $scope.venueList = data; //场馆列表渲染
        });
    };
    // 整理搜索数据
    $scope.searchListData = function () {
        return {
            startTime : $scope.startTime   != undefined && $scope.startTime   != "" ? $scope.startTime   : null, //开始时间
            endTime   : $scope.endTime     != undefined && $scope.endTime     != "" ? $scope.endTime     : null, //结束时间
            venueId   : $scope.venueSelect != undefined && $scope.venueSelect != "" ? $scope.venueSelect : null, //选择场馆
            keywords  : $scope.search      != undefined && $scope.search      != "" ? $scope.search      : null, //输入搜索
            sortType  : $scope.sortType    != undefined && $scope.sortType    != "" ? $scope.sortType    : null, //排序类型
            sortName  : $scope.sort        != undefined && $scope.sort        != "" ? $scope.sort        : null  //排序名称
        }
    };
    // 设置初始化url
    $scope.initPath = function (){
        $scope.searchParams = $scope.searchListData();                              //整理的数据
        $scope.pageInitUrl  = "/news/sms-info?" + $.param($scope.searchParams); //初始化url
    };
    // 搜索获取数据事件
    $scope.searchNews = function (){
        $.loading.show();
        $http.get($scope.pageInitUrl).success(function (data){
            // 判断列表渲染数据是否为空
            if(data.data.length != 0 && data.data != undefined && data.data != ""){
                $scope.noNewsListData = false; //暂无数据图像
            }
            else{
                $scope.noNewsListData  = true; //暂无数据图像
            }
            $scope.newsItem    = data.data;  //列表数据
            $scope.pages       = data.pages; //列表分页
            $scope.nowPage     = data.now  ; //当前页数
            $.loading.hide();
        });
    };
    // 设置搜索分页
    $scope.replacementPages = function (urlPages){
        $scope.pageInitUrl = urlPages; //分页连接
        $scope.searchNews();
    };
    // 搜索的点击事件
    $scope.getSearchData = function (){
        $scope.initTime();
        $scope.initPath();
        $scope.searchNews();
    };
    // 搜索的回车事件
    $scope.searchName = function (e){
        var $keywords = window.event?e.keyCode:e.which;
        if($keywords == 13){
            $scope.getSearchData();
        }
    };
    //场馆下拉框事件
    $scope.venueChange = function(id){
        $scope.initPath();
        $scope.searchNews();
    };
    //清除按钮事件
    $scope.clearBtn = function(){
        $('#doubleTime').val('');
        $scope.initTime();
        $scope.venueSelect = '';
        $scope.search = '';
        $scope.initPath();
        $scope.searchNews();
    };
    //日期选中事件
    $('#doubleTime').on('apply.daterangepicker', function(ev, picker){
        $scope.initTime();
        $scope.initPath();
        $scope.searchNews();
    });
    /***** 头部条件筛选结束 ****/
    
    /**** 总列表渲染 ***/
    // 初始化加载总列表
    $scope.getAllList = function (){
        $scope.getSearchData();
    };
    // 排序
    $scope.changeSort = function (attr,sort){
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.getSearchData();
    };
    $scope.switchSort = function (sort){
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    // 删除列表所有信息
    $scope.delItem = function (){
        /*Sweety.remove({
            url: "/news/del-sms-all?venueId=" + $scope.venueSelect,
            http: $http,
            title: '确定要删除吗?',
            text: '短信删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function (data){
            if(data.data.status == "success"){
                Message.success("删除成功");
                $scope.getAllList();
            }
            else{
                Message.warning("删除失败");
            }
        });*/
        swal({
            title: "确定删除吗？",
            text: "此操作会删除当前场馆全部短信，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: false
        },function(isConfirm){
            if (isConfirm) {
                $http.get("/news/del-sms-all?venueId=" + $scope.venueSelect).then(function(data){
                    console.log(data);
                    if(data.data.status == "success"){
                        //Message.success("删除成功");
                        swal("删除成功！", "","success");
                        $scope.getAllList();
                    }else{
                        swal("删除失败！", "","error");
                    }
                })
            } else {
                swal.close();
            }
        });
    };
    /**** 总列表渲染结束 ***/

    /**** 点击查看列表详情 ****/
    // 渲染详情
    $scope.lookDetails = function (name,time,content){
        $scope.contentName = name;    //会员名称
        $scope.contentTime = time;    //发送时间
        $scope.contentText = content; //短息内容
    };

    // 重新发送
    $scope.sendNow = function (id){
      $http.get("/news/sms-resend?smsId=" + id).success(function (data){
          if(data.status == "success"){
              Message.success("发送成功");
          }
          else{
              Message.success("发送失败");
          }
      })
    };
    /**** 点击查看列表详情结束 ****/

});