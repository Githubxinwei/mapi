//套餐管理页面控制器
var app = angular.module('App',[]);
app.controller('packageCtrl',function($scope,$http){
    $scope.items = [
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        {name:'尊享套餐',serveItem:'A服务 / B服务 / C服务 / D服务 / E服务 / F服务 / G服务 ',count:'100',timeImpose:'8:00-18:00'},
        
    ];
    $scope.search =  function () {
    }
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13)
        {
            $scope.search();
        }
    }
});
