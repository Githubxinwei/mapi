// 权限管理angularJs
angular.module('App').controller('authorityCtrl', function($scope,$http){
    $scope.items = [
        {ID:'1098',name:'大黄',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'杰尼'},
 {ID:'1098',name:'嘻嘻嘻',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'杰尼'},
        {ID:'109821251',name:'大黄',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'东尼大木'},
        {ID:'109854566',name:'垃圾垃圾',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'杰尼'},
        {ID:'10980000',name:'东尼大木',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'杰尼'},
        {ID:'18',name:'大黄',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'东尼大木'},
        {ID:'1',name:'三个字',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'东尼大木'},
        {ID:'8',name:'周杰伦',authority:'私教',type:'一般权限',create_at:'2017-4-1',user:'杰尼'},
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
})