//组织管理中部门页面控制器
angular.module('App',[]).controller('departmentCtrl', function($scope,$http){
    $scope.items = [
        {name:'销售',codeId:'xiaoshou'},
        {name:'私教',codeId:'sijiao'},
        {name:'销售',codeId:'xiaoshou'},
        {name:'销售',codeId:'xiaoshou'},
        {name:'私教',codeId:'sijiao'},
        {name:'销售',codeId:'xiaoshou'},
    ]
});