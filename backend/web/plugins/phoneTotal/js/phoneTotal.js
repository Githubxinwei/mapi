/**
 * 财务管理 - 手机统计 - 手机数据统计
 * @author zhujunzhe@itsports.club
 * @create 2018/1/6 pm
 */
angular.module('App',[]).controller('phoneTotalCtrl',function($http,$scope){
    /**
     *  @日期插件
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $(function(){
        $('#sellDate1').daterangepicker(null, function(start, end, label) {
        });
    });
    /**
     *  @日期字符串处理函数
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.getTimestamp = function(string){
        return string.split(' - ');
    };
    /**
     *  @日期转化时间戳
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.getTimeDate = function(string) {
        return new Date(string).getTime()/1000;
    };
    /**
     *  @获取INPUT值
     *  @author yanghuilei
     *  @create 2018/1/8
     */
    $scope.getInput    = function(){
        var $tempArr  = $scope.getTimestamp($("#sellDate1").val());
        var t1 = $tempArr[0];
        var t2 = $tempArr[1];
        if(t1 == t2){
            t1 = t1 + ' 0:0:0';
            t2 = t2 + ' 23:59:59';
        }
        return [$scope.getTimeDate(t1), $scope.getTimeDate(t2)];
    };
    /**
     *  @封装初始化日期
     *  @author yanghuilei
     *  @create 2018/1/6
     */
     $scope.dateInit = function(){
         var dd = new Date();
         var y = dd.getFullYear();
         var yy;
         var m  = dd.getMonth()+1;
         var n  = dd.getMonth() == 0 ? 12 : dd.getMonth();
         var d  = dd.getDate();
         if(dd.getMonth() == 0){
              yy= y - 1;
         }else{
              yy= y;
         }
         if(m.toString().length == 1){
             m = '0' + m.toString();
         }
         if(n.toString().length == 1){
             n = '0' + n.toString();
         }
         if(d.toString().length == 1){
             d = '0' + d.toString();
         }
         $scope.curDate = y+"-"+m+"-"+d;
         $scope.prevDate = yy+'-'+n+'-'+d;
         $("#sellDate1").val($scope.prevDate + ' - ' + $scope.curDate);
    };
    /**
     *  @获取序列化参数
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.param = function(){
      return $.param({
          startTime : $scope.getInput()[0],
          endTime   : $scope.getInput()[1],
      });
    };
    /**
     *  @初始化数据
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.dataInit = function(param){
        $.loading.show();
        $http.get('/v1/api-member/venue-num-statics?' + param).success(function(data){
           $scope.activeNum = JSON.parse(data).data.activeNum;
           $scope.downNum   = JSON.parse(data).data.downloadNum;
           $scope.visitNum   = JSON.parse(data).data.visitNum;
            $scope.countTotalActiveNum();
            $.loading.hide();
        });

    };
    /**
     *  @清空日期事件获取全部数据
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.emptyDate = function(){
        $("#sellDate1").val("");
        $scope.dataInit('');
    };
    /**
     *  @点击确定进行搜索
     *  @author yanghuilei
     *  @create 2018/1/8
     */
    $('#sellDate1').on('apply.daterangepicker', function(ev, picker){
        if($('#sellDate1').val() != ""){
            $scope.dataInit($scope.param());
        }
    });
    /**
     *  @调用初始化日期
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.dateInit();
    /**
     *  @调用初始化数据
     *  @author yanghuilei
     *  @create 2018/1/6
     */
    $scope.dataInit($scope.param());
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
    $scope.a = 2;
    $scope.clickA = function (num) {
        $.loading.show();
        if (num == undefined) {
            $scope.a = 2;
            $.loading.hide();
            $('#show-matrix-id').addClass('active');
            $('#show-list-id').removeClass('active');
        }
        if (num == 1) {
            $scope.a = 1;
            $.loading.hide();
            $('#show-list-id').addClass('active');
            $('#show-matrix-id').removeClass('active');
        }
        if (num == 2) {
            $scope.a = 2;
            $.loading.hide();
            $('#show-matrix-id').addClass('active');
            $('#show-list-id').removeClass('active');
        }
    }
    /*********************************列表****************************************/
    $scope.tableInit = function(param){
        $.loading.show();
        $http.get('/v1/api-member/table-num-statics?' + param).success(function(data){
            $scope.tableData = JSON.parse(data).data;
            if($scope.tableData == undefined || $scope.tableData == null || $scope.tableData.length == 0){
                 $scope.dataInfo = true;
            }else{
                 $scope.dataInfo  = false;
            }
            $scope.countTotalActiveNum();
            $.loading.hide();
        });
    };
    $scope.tableInit('');
    $(function () {
        $('#sellDate2').daterangepicker(null, function (start, end, label) {
        });
    })
    //点击时间搜索
    $scope.startTime = '';
    $scope.endTime = '';
    $('#sellDate2').on('apply.daterangepicker', function(ev, picker){
        if($('#sellDate2').val() != ""){
            $scope.startTime = $scope.getInput2()[0];
            $scope.endTime   = $scope.getInput2()[1];
            $scope.tableInit($.param({
                startTime   : $scope.getInput2()[0],
                endTime     : $scope.getInput2()[1],
                companyId   : $scope.companyId,
                client      : $scope.client,
            }));
        }
    });
    $scope.getInput2    = function(){
        var $tempArr  = $scope.getTimestamp($("#sellDate2").val());
        var t1 = $tempArr[0];
        var t2 = $tempArr[1];
        if(t1 == t2){
            t1 = t1 + ' 0:0:0';
            t2 = t2 + ' 23:59:59';
        }
        return [$scope.getTimeDate(t1), $scope.getTimeDate(t2)];
    };
    $scope.searchCompany = function () {
        $scope.tableInit($.param({
            startTime : $scope.startTime != '' ? $scope.startTime : null,
            endTime : $scope.endTime != '' ? $scope.endTime : null,
            companyId : $scope.companyId,
            client : $scope.client,
        }));

    }
    $scope.searchClient = function () {
        $scope.tableInit($.param({
            startTime : $scope.startTime != '' ? $scope.startTime : null,
            endTime : $scope.endTime != '' ? $scope.endTime : null,
            companyId : $scope.companyId,
            client : $scope.client,
        }));
    }
    //计算总点击量
    $scope.countTotalActiveNum = function () {
        var totalActiveNum = 0;
        var totalVisitNum = 0;
        if ($scope.tableData && ($scope.tableData).length != 0) {
            for (var i = 0; i<($scope.tableData).length; i++) {
                totalActiveNum = totalActiveNum + parseInt($scope.tableData[i].activeNum);
                totalVisitNum = totalVisitNum + parseInt($scope.tableData[i].visitNum);
            }
        }
        $scope.totalActiveNum = totalActiveNum;
        $scope.totalVisitNum = totalVisitNum;
    }
    //清空日期按钮
    $scope.emptyDate2 = function(){
        $("#sellDate2").val("");
        $scope.tableInit($.param({
            companyId : $scope.companyId,
            client : $scope.client,
        }));
    };
});
