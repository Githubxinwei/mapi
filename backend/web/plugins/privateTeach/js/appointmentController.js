/**
 * @云运动 - 后台 - 预约设置
 * @author huanghua <huanghua@itsports.club>
 * @create 2017/4/13
 */

var app = angular.module('App');
app.controller('appointmentController',function($scope, $http){

    $scope.init = function () {
        $scope.getData();
    };

    $scope.getIsCheck = function () {
        var form =  $('form').find('input:checked');
        form.each(function (i) {
            if($(this).attr('name') == 'is_coach_class'){
                $scope.is_coach_class = $(this).val();
            }else if($(this).attr('name') == 'is_coach_vacation'){
                $scope.is_coach_vacation= $(this).val();
            }else if($(this).attr('name') == 'is_member_class'){
                $scope.is_member_class= $(this).val();
            }else{
                $scope.is_member_class  = $(this).val();
            }
        });
    };

    $scope.setHttp = function () {
        $http({
            url        : '/private-teach/subscribe-data',
            method     : 'POST',
            data       :  $.param($scope.params),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success'){
                Message.success(result.data.data);
            }else{
                Message.warning(result.data.data);

            }
        });
    };
    $scope.getFormData = function () {
        $scope.getIsCheck();

        return  {
            _csrf_backend        :  $('#_csrf').val(),
            subscribe            :  $scope.subscribe,
            venue                :  $scope.venue,
            cancel               :  $scope.cancel,
            class                : $scope.class,
            member_show          :  $scope.member_show,
            is_coach_class       :  $scope.is_coach_class,
            is_coach_vacation    :  $scope.is_coach_vacation,
            is_member_class      :  $scope.is_member_class

        }
    };

    $scope.present = function () {
        $scope.params =   $scope.getFormData();
        $scope.setHttp();
    };

    $scope.getData = function () {
        $http.get("/private-teach/config-data").success(function (response)
        {
            $scope.datas = response.data;

            if($scope.datas != undefined && $scope.datas != ''){
                $scope.subscribe          = response.data[0].value;
                $scope.venue              = response.data[1].value;
                $scope.cancel             = response.data[2].value;
                $scope.class              = response.data[3].value;
                $scope.member_show        = response.data[4].value;
                $scope.is_coach_class     = response.data[5].value;
                $scope.is_coach_vacation  = response.data[6].value;
                $scope.is_member_class    = response.data[7].value;
                $scope.setIsCheck();
            }
        });
    };
    $scope.setIsCheck = function () {
        var form =  $('form').find('input:radio');
        form.each(function (i) {
            var $name = $(this).attr('name');
            var $val = $(this).val();
            if($name == 'is_coach_class'  && $val == $scope.is_coach_class){
                $(this).parent().addClass('checked');
            }else if($name == 'is_coach_vacation' && $val == $scope.is_coach_vacation){
                $(this).parent().addClass('checked');
            }else if($name == 'is_member_class' && $val == $scope.is_member_class){
                $(this).parent().addClass('checked');
            }
        });
    };

    $scope.init();
});
























