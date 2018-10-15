//评价管理页面控制器
var app = angular.module('App',[]);
app.controller('evaluateCtrl',function($scope,$http){
    $scope.items = [
        {evaluateTime:'2017.3.6',course:'健身',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'瑜伽',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'单车',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'舞蹈',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'健身',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'瑜伽',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'单车',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'舞蹈',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'健身',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
        {evaluateTime:'2017.3.6',course:'瑜伽',coachName:'阿波',grade:'课程5.0分/教练4.6分/服务4分',valuer:'Mzd',date:'2017.3.6'},
    ];

    $scope.search =  function () {
        // console.log('点击了 1111')

    }
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
            // console.log('点击了 2222')
        }
    }
});
