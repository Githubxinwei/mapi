angular.module('App').controller('adminCtrl', function ($scope, $http) {

    $scope.signOut = function () {
        location.href = "/login/index";
        localStorage.clear();
        /*
        $http({method:'get',url:'/cache/cache-flush'}).then(function (data) {
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        })
        */
        return false;
    };
    // 公司联盟侧边栏的信息提醒js
    // $scope.getCorprateAllianceInfo = function () {
    //     $http.get('/corporate-alliance/pending').success(function (data) {
    //         $scope.infoNumber = data.data;
    //     });
    // };
    // $scope.getCorprateAllianceInfo();

    //右边覆盖头像
    $http({method: 'get', url: "/personnel/employee-center"}).then(function (data) {
        $scope.listData = data.data;
    }, function (error) {
        console.log(error);
        Message.error("系统错误请联系工作人员")
    });

    var $data = '';
    //获取标题菜单
    $scope.listItemsTitle = function () {
        $http({method: 'get', url: '/jurisdiction/get-auth-module-all'}).then(function (data) {
            if(data.data != '' && data.data != 'null'){
                $scope.listItemsTitleData = data.data;
                angular.forEach($scope.listItemsTitleData, function (item, index, array) {
                    $data += "<li class='hidden-folded padder m-t m-b-sm text-muted text-xs marginLeft0 christmas' ng-if='item.id == idItems.itemId' >";
                    $data += "<a>";
                    $data += "<i class='"+ item.icon +" '></i>";
                    $data += "<span class='nav-label fontSize12 templetName'>" + item.name + "</span>";
                    // $data += "<span class='fa arrow'></span>";
                    $data += "</a>";
                    $data += "<ul class='nav nav-second-level '>";
                    angular.forEach(item.module, function (items, indexs, arrays) {
                        $data += "<li>"
                        $data += "<a href="+ items.url +">";
                        $data += "<i class='"+ items.icon +"'></i>";
                        $data += "<span class='nav-label templetName2'>" + items.name + "</span>";
                        $data += "</a>";
                        $data += "</li>";
                    })
                    $data += "</ul>";
                    $data += "</li>";
                })
                $(".sideMenuAll").append($data);
                $(".nav-second-level").css("display", 'none')
                $scope.loadFunction();
                //遍历导航列表，选中某项
                function getNavTarget () {
                    //获取链接url中对应的模块名
                    var urlPage1 = String(window.location.href.split('/')[3]);
                    var urlPage2 = String(window.location.href.split('/')[4]);
                    //大小模块组合
                    var urlPageHref = urlPage1 + '/' + urlPage2;
                    //获取导航列表的dom，遍历查找对应选项并选中
                    //找到所有子列表
                    $scope.navLi = $('.sideMenuAll').children().find('.nav-second-level').children('li').children('a');
                    for(var i = 0;i<$scope.navLi.length;i++) {
                        //拿到子列表a标签的href值，大小模块一起进行匹配
                        $scope.navLiHref = $scope.navLi.eq(i).attr('href').split('/')[1] + '/' + $scope.navLi.eq(i).attr('href').split('/')[2];
                        if($scope.navLiHref == urlPageHref) {
                            //设置样式
                            $scope.navLi.eq(i)
                                .css('color', '#fff')
                                .parent().css('background', '#131e26')
                                .parent().css('display', 'block');
                        }
                    }
                }
                getNavTarget();
            }
        }, function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }

    $scope.listItemsTitle();
    $scope.loadFunction = function () {
        angular.element(document).ready(function () {
            var BOOL = true;
            $(".marginLeft0").click(function () {
                $('body').removeClass('mini-navbar');
                $(this).children("ul").fadeOut(800).css("display", "block")
                $(this).siblings(".marginLeft0 ").children("ul").css("display", "none");
                $('.arrow').removeClass("down")
                $(this).find('.arrow').fadeIn(1000).addClass('down')
            })

        });
    }
});
$(function(){
    // 菜单切换
    $('.navbar-minimalize').click(function () {
        if ($('body').hasClass('mini-navbar')) {
            $('.arrow').removeClass("down");
            $(".marginLeft0 ").children("ul").css("display", "none");
            $('#side-menu').hide();
            setTimeout(
                function () {
                    $('#side-menu').fadeIn(500);
                }, 100);
        }
    });
    $(window).resize(function(){
        var $width = document.body.clientWidth;
        // console.log($width)
        if($width >= 769){
            $('body').removeClass('mini-navbar');
            // $('.navbar-static-side').fadeIn(500);
        }else{
            $('.arrow').removeClass("down");
            $(".marginLeft0 ").children("ul").css("display", "none");
        }
    });
});