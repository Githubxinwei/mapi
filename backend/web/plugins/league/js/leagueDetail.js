var app = angular.module('App');
app.controller('leagueDetailController',function($scope,$http) {
    $scope.init = function () {
        $scope.aboutDetail();
    };
    $scope.aboutDetail = function () {
        $http.get("/league/get-course-detail").then(function (result) {
            // console.log(result.data);
            // console.log('aaaa',result.data.venue_id.organization.name);
            $scope.detailObj = result.data;
        });
    };
    $scope.init();
});


























