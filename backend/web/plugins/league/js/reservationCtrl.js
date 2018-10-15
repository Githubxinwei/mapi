/**
 * @云运动 - 后台 - 团课管理 - 预约
 * @author 朱梦珂 <zhumengke@itsports.club>
 * @create 2017/4/13
 */
//预约js文件
var app = angular.module('App');
app.controller('reservationCtrl',function($scope,$http){
    $scope.init = function () {
        $scope.setScopeData();
    };

    $scope.getIsCheck = function () {
        var form =  $('form').find('input:checked');
        form.each(function (i) {
            if($(this).attr('name') == 'is_deduction_times'){
                $scope.is_deduction_times = $(this).val();
            }else if($(this).attr('name') == 'is_check_leave'){
                $scope.is_check_leave= $(this).val();
            }else{
                $scope.is_check_replace  = $(this).val();
            }
        });
    };
    
    $scope.setHttp = function () {
        $http({
            url        : '/league/league-reservation',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            $scope.datas = result.data;
            if($scope.datas.status == 'success'){
                Message.success($scope.datas.data);
                setTimeout(function () {
                    // location.href = '/league/index?&c=7u5'
                    history.go(-1)
                },1000)
            }else{
                angular.forEach($scope.datas.data,function (value,key) {
                    Message.warning(value);
                })
            }
        });
    };

    $scope.getFormData = function () {
        $scope.getIsCheck();
        return {
            _csrf_backend       : $("#_csrf").val(),
            before_class        : $scope.before_class,
            venue_id            : $scope.venue_id,
            reservation_number : $scope.reservation_number,
            class_number        : $scope.class_number,
            cancel_time         : $scope.cancel_time,
            how_minutes         : $scope.how_minutes,
            how_days             : $scope.how_days,
            allow_look           : $scope.allow_look,
            is_deduction_times  : $scope.is_deduction_times,
            is_check_replace     : $scope.is_check_replace,
            is_check_leave       : $scope.is_check_leave
        }
    };

    $scope.present = function () {
        $scope.params =   $scope.getFormData();
        $scope.setHttp();
    };
    $scope.setScopeData = function () {
        $http.get("/league/config-info").success(function (response){
            $scope.datas = response;

            $scope.before_class         = $scope.datas.config[0].value;
            $scope.venue_id             = $scope.datas.config[1].value;
            $scope.reservation_number  = $scope.datas.config[2].value;
            $scope.class_number        = $scope.datas.config[3].value;
            $scope.cancel_time         = $scope.datas.config[4].value;
            $scope.how_minutes         = $scope.datas.config[5].value;
            $scope.how_days            = $scope.datas.config[6].value;
            $scope.allow_look          = $scope.datas.config[7].value;
            $scope.is_deduction_times = $scope.datas.config[8].value;
            $scope.is_check_replace   = $scope.datas.config[9].value;
            $scope.is_check_leave     = $scope.datas.config[10].value;
            $scope.setIsCheck();
        });
    };

    $scope.setIsCheck = function () {
        var form =  $('form').find('input:radio');

        form.each(function (i) {
            var $name = $(this).attr('name');
            var $val  = $(this).val();
            if($name == 'is_deduction_times' && $val == $scope.is_deduction_times){
                $(this).parent().addClass('checked');
            }else if($name == 'is_check_leave' && $val == $scope.is_check_leave){
                $(this).parent().addClass('checked');
            }else if($name == 'is_check_replace' && $val == $scope.is_check_replace){
                $(this).parent().addClass('checked');
            }
        });
    };
    $scope.Return = function () {
        history.go(-1)
    }
    $scope.init();
});
