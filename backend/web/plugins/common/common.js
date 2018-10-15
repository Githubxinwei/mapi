angular.module('DirectiveApp',[]).directive('compileview', function ($compile) {
    return {
        restrict: 'A',
        replace: true,
        link: function (scope, ele, attrs) {
            scope.$watch(function () {return scope.$eval(attrs.compileval);},
                function(html) {
                    ele.html(html);
                    $compile(ele.contents())(scope);
                });
        }
    };
}).directive('venuehtml', function ($compile) {
    return {
        restrict: 'A',
        replace: true,
        scope: '=',
        link: function (scope, elem, attrs) {
            $(elem).click(function() {
                if(attrs.id == 'addSellVenue'){
                    $(this).prev().append(scope.saleHtml);
                }else if(attrs.id == 'addVenue'){
                    $(this).prev().append(scope.applyHtml);
                }else if(attrs.id == 'addNewDiscount'){
                    $(this).parents('.addNewDiscountBtn').prev('.addNewDiscount').append(scope.newDiscountHtml);
                }else if(attrs.id == 'addCardValidityBtn'){
                    $(this).parents('div').prev('.cardValidity123').append(scope.addNewRenewHtml);
                }else if(attrs.id == 'addDiscount'){
                    $(this).parent('.discountBtnBox').prev('.discountLists').append(scope.discountHtml);
                } if(attrs.id == 'addDiscount123'){
                    $(this).parent('.discountBtnBox').prev('.discountLists').append(scope.discountHtml123);
                }if(attrs.id == 'addSetRole'){
                    $(this).parents('.addSetRoleDiv').prev('.setRoleBox').append(scope.setRoleHtml);
                    //服务器过滤，解决动态添加下拉没数据问题
                    $compile(elem.parents('.addSetRoleDiv').prev('.setRoleBox').children(':last').contents())(scope);
                }else if(attrs.id == 'addCardValidityEdit'){
                    $(this).parents('.removeBoxBtn').prev('#validityRenewBoxLists').append(scope.addCardValidityEditHtml);
                }else if(attrs.id == 'addDiscountEdit'){
                    $(this).parents('.discountBtnBox').prev('.discountList').append(scope.discountEditHtml);
                }else if(attrs.id == 'addSellVenueEdit'){
                    $(this).parents('.addSellVenueEditBox').prev('#sellVenue').append(scope.saleEditHtml);
                    //服务器过滤，解决动态添加下拉没数据问题
                    $compile(elem.parents('.addSellVenueEditBox').prev('#sellVenue').children(':last').contents())(scope);
                }else if(attrs.id == 'addApplyVenueEdit'){
                    $(this).parents('.addApplyVenueEditEditBox').prev('#applyVenue').append(scope.applyEditHtml);
                    $compile(elem.parents('.addApplyVenueEditEditBox').prev('#applyVenue').children(':last').contents())(scope);
                }else if(attrs.id == 'addLeagueCourseEdit'){
                    $(this).parents('.addLeagueCourseEditBox').prev('#course').append(scope.leagueClassEditHtml);
                    $compile(elem.parents('.addLeagueCourseEditBox').prev('.course').children(':last').contents())(scope);
                }else if(attrs.id == 'addDonationEdit'){
                    $(this).parents('.addDonationBox').prev('#giveShopBox').append(scope.donationEditHtml);
                    $compile(elem.parents('.addDonationBox ').prev('#giveShopBox').children(':last').contents())(scope);
                }else if(attrs.id == 'addLeaveEdit'){
                    $(this).parents('.leaveBtnBox').prev('.leaveNumBoxLists').append(scope.leaveEditHtml);
                }else if(attrs.id == 'addServeClassEdit'){
                    $(this).parents('#addServeClassEditBox').prev('#ptServeClassBox').append(scope.serveClass123Html);
                    $compile(elem.parents('#addServeClassEditBox ').prev('#ptServeClassBox').children(':last').contents())(scope);
                }else if(attrs.id == 'addPTSellVenueBtn'){
                    $(this).parents('#addPTSellVenueBtnBox').prev('.ptServeSellVenueBox').append(scope.serveSellVenue123Html);
                    $compile(elem.parents('#addPTSellVenueBtnBox ').prev('.ptServeSellVenueBox').children(':last').contents())(scope);
                }else if(attrs.id == 'addShopGiftEdit'){
                    $(this).parents('#addShopGiftEditBox').prev('#giftShopEdit123123Box').append(scope.giftShopEditHtml123);
                    $compile(elem.parents('#addShopGiftEditBox ').prev('#giftShopEdit123123Box').children(':last').contents())(scope);
                }else if(attrs.id == 'addCourseSectionBtn'){
                    $(this).parents('#addCourseSectionBtnBox').prev('#courseSectionNumBox').append(scope.courseSectionHtml);
                    $compile(elem.parents('#addCourseSectionBtnBox').prev('#courseSectionNumBox').children(':last').contents())(scope);
                }else if(attrs.id == 'addCourse'){
                    $(this).prev().append(scope.classHtml);
                }else if(attrs.id == 'addServer'){
                    $(this).prev().append(scope.serverHtml);
                }else if(attrs.id == 'addShop'){
                    $(this).prev().append(scope.shopHtml);
                }else if(attrs.id == 'addDonation'){
                    $(this).prev().append(scope.donationHtml);
                }else if(attrs.id == 'addLeave'){
                    $(this).prev().append(scope.leaveHtml);
                }else if(attrs.id == 'addPrivateVenue'){
                    $(this).parents('.addPrivateVenue').prev().children().append(scope.saleHtml);
                    $compile(elem.parents('.addPrivateVenue').prev().children().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addPrivateCourse'){
                    $(this).parents('.addPrivateCourse').prev().children().append(scope.classPrivateHtml);
                    $compile(elem.parents('.addPrivateCourse').prev().children().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addPrivateGift'){
                    $(this).parents('.addPrivateGift').prev().children().append(scope.donationHtml);
                    $compile(elem.parents('.addPrivateGift').prev().children().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addPrivateServe'){
                    $(this).parents('.addPrivateServe').prev().children().append(scope.serverHtml);
                    $compile(elem.parents('.addPrivateServe').prev().children().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addPrivatePrice'){
                    $(this).parents('.addPrivatePrice').prev().append(scope.pricePrivateHtml);
                    $compile(elem.parents('.addPrivatePrice').prev().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addPayWay') {
                    $(this).parents('.addPayWayBtnBox').prev('.addPayWayBox').append(scope.addPayWayHtml);
                    $compile(elem.parents('.addPayWayBtnBox').prev('.addPayWayBox').children(':last').contents())(scope);
                }else if(attrs.id == 'buyCardMethod') {
                    $(this).parents('.buyCardMethodBtnBox').prev('.buyCardMethodBox').append(scope.addBuyCardHtml);
                    $compile(elem.parents('.buyCardMethodBtnBox').prev('.buyCardMethodBox').children(':last').contents())(scope);
                }else if(attrs.id == 'buyClassMethod') {
                    $(this).parents('.buyClassMethodBtnBox').prev('.buyClassMethodBox').append(scope.addBuyClassHtml);
                    $compile(elem.parents('.buyClassMethodBtnBox').prev('.buyClassMethodBox').children(':last').contents())(scope);
                }else if(attrs.id == 'addPersonLimit') {
                    $(this).parents('.addPersonLimit1').prev('#numLimit').children(':last').append(scope.personLimitHtml123);
                    $compile(elem.parents('.addPersonLimit1').prev('#numLimit').children(':last').children(':last').contents())(scope);
                }else if(attrs.id == 'addNumLimit') {
                    $(this).parents('.addNumLimit').prev().prev('.addPersonLimit1').prev('#numLimit').append(scope.NumLimitHtml);
                }else if(attrs.id == 'addGroupPrivateVenue') {
                    $(this).parents('.addGroupPrivateVenue').prev('#privateVenue').after().append(scope.groupSaleVenueHtml);
                    //服务器过滤，解决动态添加下拉没数据问题
                    $compile(elem.parents('.addGroupPrivateVenue').prev('#privateVenue').children(':last').contents())(scope);
                }else if(attrs.id == 'addGroupPrivateGift') {
                    $(this).parents('.addGroupPrivateGiftBox').prev('#productGift').after().append(scope.groupGroupDonationHtml);
                    //服务器过滤，解决动态添加下拉没数据问题
                    $compile(elem.parents('.addGroupPrivateGiftBox').prev('#productGift').children(':last').contents())(scope);
                }else if(attrs.id == 'addCabinetMonth') {
                    $(this).parents('.help-block').prev('.addCabinetMonth').append(scope.addCabinetMonthHtml);
                    $compile(elem.parents('.help-block').prev('.addCabinetMonth').children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addCabMonth') {
                    $(this).parents('.help-blocks').prev('.addCabinetMonth').append(scope.addMoresMothHtml);
                    $compile(elem.parents('.help-blocks').prev('.addCabinetMonth').children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'modifyPlugins'){
                    $(this).parents('.huiLeiAdd').prev('.modify').append(scope.modifyPluginsHtml);
                    $compile(elem.parents('.huiLeiAdd').prev('.modify').children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addCourse123') {
                    $(this).parents('.addCourseBox123').prev('.courseForm').append(scope.courseSelect123);
                    $compile(elem.parents('.addCourseBox123').prev('.courseForm').children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addLimit123') {
                    $(this).parents('.addLimitBox123').prev('.limitForm').append(scope.limitHtml123);
                    $compile(elem.parents('.addLimitBox123').prev('.limitForm').children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addVenue123'){
                    $(this).parents('.addVenueBox123').prev().append(scope.svenueHtml123);
                    $compile(elem.parents('.addVenueBox123').prev().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addGift123') {
                    $(this).parents('.addGiftBox123').prev('.addGift').append(scope.giftHtml123);
                    $compile(elem.parents('.addGiftBox123').prev().children(':last').contents())(scope);
                    return false;
                }else if(attrs.id == 'addPayWays') {
                    $(this).parents('.addPayWaysBox').prev().append(scope.payWaysHtml);
                    $compile(elem.parents('.addPayWaysBox').prev().children(':last').contents())(scope);
                    return false;
                }
                $compile(elem.prev().children(':last').contents())(scope);
            });
        }
    };
}).directive('memberlist', function ($compile) {
    return {
        restrict: 'A',
        replace: true,
        scope: '=',
        link: function (scope, elem, attrs) {
            // $(elem).click(function() {
            //     var elem = $('#memberTbody');
            //     $compile(elem.contents())(scope);
            // });
        }
    };
}).directive('inputnum', function () {
    return {
        restrict: 'A',
        replace: true,
        link: function (scope, elem, attrs) {
            elem.keyup(function () {
                var $val = $(this).val();
                // $val = $val.replace(/^0/g, "");
                $val = $val.replace(/^[^1-9][^0-9]*$/g,'');
                $(this).val($val);
            });
        }
    };
}).directive('checknum',function () {
        return {
            restrict: 'A',
            replace: true,
            link: function (scope, elem, attrs) {
                $(elem).on('input change',function () {
                    var $val = $(this).val();
                    $val = $val.replace(/[^0-9]*/g,'');
                    $(this).val($val);
                });
            }
        };
}).directive('inputtext', function () {
    return {
        restrict: 'A',
        replace: true,
        link: function (scope, elem, attrs) {
            elem.keyup(function () {
                var $val = $(this).val();
                // $val = $val.replace(/^0/g, "");
                $val = $val.replace(/^[^1-9][^0-9]*$/g,'');
                $(this).val($val);
            });
        }
    };
}).directive('autoFocus', function(){
    return function(scope, element){
        element[0].focus();
    };
}) .directive('switchauth', function(){
    return {
        restrict: 'A',
        replace : true,
        scope   : '=',
        link: function (scope, elem, attrs) {
            var $id = attrs.value;
           if($.inArray($id,scope.arrIdModule) != -1){
               elem.removeClass('ng-empty');
               elem.addClass('ng-not-empty checked');
               elem.children('input').removeClass('ng-empty');
               elem.children('input').addClass('ng-not-empty checked');
           }
        }
    };
});
angular.module('FilterApp',[]).filter('cut', function () {
    return function (value, wordwise, max, tail) {
        if (!value) return '';
        max = parseInt(max, 10);
        if (!max) return value;
        if (value.length <= max) return value;
        value = value.substr(0, max);
        if (wordwise) {
            var lastspace = value.lastIndexOf(' ');
            if (lastspace != -1) {
                value = value.substr(0, lastspace);
            }
        }
        return value + (tail || ' …');
    };
}).filter('birth',function () {
    return function (value,status) {
        if (!value) return '';
        var date = new Date(Date.parse(value));
        var myDate = new Date();//当前时间
        var currentDate = myDate.getFullYear();//当前年份
        var age = currentDate - date.getFullYear();

        return age;
    }
}).filter('checkPrint',function () {
    return function (value,printSetting) {
            var myDate = new  Date();
            var $nowTime = Date.parse(myDate);
            if (value*1000 + printSetting*60*1000 < $nowTime) {
                return true;
            } else {
                return false;
            }
    }
}).filter('noData',function () {
    return function (value,tail,attr) {
        if(value){
            if(attr && value == '0'){
                return 0;
            }
            return value+tail;
        }else{
            if(attr){
                return 0;
            }
            return '暂无数据';
        }
    }
}).filter('attrOption',function () {
    return function (value,attr) {
        if(value && attr){
            if(value.id == attr.id){
                return true;
            }
        }
    }
}).filter('attrVenue',function () {
    return function (value,attr) {
        if(value && attr){
            if($.inArray(value,attr) != -1){
                return true;
            }
        }
    }
}).filter('stringToArr',function () {
    return function (value) {
        if(value){
            var $value = $.parseJSON(value);
            return $value.day+'天';
        }else{
            return '暂无数据';
        }
    }
}).filter('jsonParse', function() {
    return function (str) {
        try{
            return angular.fromJson(str);
        }catch (e){
            return str;
        }
    }
}).filter('attrSeatClass',function () {
    return function (value,attr) {
        // console.log('seat',value,'about',attr)
       if(attr == undefined || !attr){
           return 'btn-white';
       }else{
           if(value.id == attr.seat_id && value.seat_type == 3){
               return 'btn-warning';
           }else if (value.id == attr.seat_id && value.seat_type == 2){
               return 'btn-success';
           }else if(value.id == attr.seat_id && value.seat_type == 1){
               return 'btn-info';
           }
       }
    }
}).filter('attrSeatClass123',function ($http) {
    return function (value,attr) {
        // console.log('seat',value,'about',attr)
        if(attr == undefined || !attr){
            return 'btn-white';
        }else{
            var len = attr.length;
            for(var i=0;i < len;i++){
                if(value.id == attr[i].seat_id && value.seat_type == 3){
                    return 'btn-warning';
                }else if (value.id == attr[i].seat_id && value.seat_type == 2){
                    return 'btn-success';
                }else if(value.id == attr[i].seat_id && value.seat_type == 1){
                    if(attr[i].member.member_type == '2' ) {
                        return 'bg-danger';
                    }else if(attr[i].member.member_type == '1' ) {
                        return 'btn-info';
                    }
                }
            }

        }
    }
}).filter('classDate',function ($sce) {
    return function (value) {
        // console.log(value)
        if(!value) return null;
        var $time = parseInt(value.start)*1000;
        var $end  = parseInt(value.end)*1000;
         var $nowTime = Date.parse(new Date());
         if($end < $nowTime){
             return $sce.trustAsHtml('<small class="label label-danger">已结束</small>');
         }else if($time <= $nowTime && $nowTime <= $end){
             return $sce.trustAsHtml('<small class="label label-success">进行中</small>');
         }else if($end <= $nowTime){
             return $sce.trustAsHtml('<small class="label label-danger">已结束</small>');
         }
         else{
             return $sce.trustAsHtml('<small class="label label-primary">未开始</small>');
         }
    };
}).filter('cardPrice',function () {
    return function (value) {
        if(value){
            if(value.original_price && value.sell_price){
                return '￥'+value.sell_price;
            }else if(value.sell_price){
                return '￥'+value.sell_price;
            }else if(value.original_price){
                return '￥'+value.original_price;
            }
            if(value.max_price && value.min_price){
                // console.log(value);
                return '￥'+value.min_price+' - '+' ￥'+value.max_price ;
            }
            if(value.max_price){
                return '￥'+value.max_price ;
            }
            if(value.min_price){
                return '￥'+value.min_price;
            }
            return '暂无价格';
        }
    };
}).filter('stringToJson',function () {
    return function (value,attr) {

        if(value){
            var valueArr = angular.fromJson(value);
            if(valueArr[attr] != undefined || valueArr[attr] != '' ){
                return valueArr[attr];
            }else{
                return '暂无数据';
            }

        }
    }

}).filter('totalNum',function () {
    return function (value) {
        if(value && value == 'NAN'){
            return 0;
        }else{
            if(value){
                return value;
            }
            return 0;
        }
    }
}).filter('to_Html', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        }
    }]
).filter('week',function () {
    return function (value) {
        value = parseInt(value);
        switch(value){
            case 1:
               value ="一";
            break;
            case 2:
                value ="二";
            break;
            case 3:
                value ="三";
            break;
            case 4:
                value ="四";
            break;
            case 5:
                value ="五";
                break;
            break;
            case 6:
                value ="六";
            break;
            case 7:
                value = "日";
            break;
            default:
                value ="";
        }
       return value;
    }
}).filter('totalLength',function () {
    return function (value,tail) {
        if(!value) return '';
        var $times = new Date(value);
        var $nowTimes = new Date();
        if($times.getDate() < 10){
            var  $day = '0'+$times.getDate();
        }else{
            $day = $times.getDate()
        }
        var $time    = $times.getFullYear() + "-" +($times.getMonth() + 1) + "-" + $day;
        if($nowTimes.getDate() < 10){
            var  $nowDay = '0'+$nowTimes.getDate();
        }else{
            $nowDay = $nowTimes.getDate();
        }
        var $nowTime = $nowTimes.getFullYear() + "-" +($nowTimes.getMonth() + 1) + "-" + $nowDay;
        $time    = $time.toLocaleString();
        $nowTime = $nowTime.toLocaleString();
        if($time == $nowTime){
            return parseInt(($nowTimes - $times)/(1000*60*60))+tail;
        }else{
            return parseInt((new Date($time+' 23:59:59') - $times)/(1000*60*60))+tail;
        }

    }
}).filter('memberAbout',function () {
    return function (value,tail) {
        var dataId   = [];
        var dataName = [];
        if(tail.length){
           for(var i = 0; i < tail.length ; i++){
               dataId.push(tail[i].seat_id);
               dataName.push(tail[i].member.name);
           }
            // console.log('dataId',dataId);
            // console.log('dataName',dataName);
            // console.log('value',value);
           if($.inArray(value,dataId) >= 0){
               var attr = $.inArray(value,dataId);
               return dataName[attr];
           } else{
               return '无人';
           }
        }else{
            return '无人';
        }
    }
});
angular.module('App',[
    'ngFileUpload',
    // 'ui.bootstrap',
    'angular-ladda',
    'DirectiveApp',
    'uiSwitch',
    'FilterApp',
    'ngImgCrop',
]);

Message = {
    success: function (msg) {
        return toastr.success(msg, '操作成功', {
            progressBar  : true,
            positionClass: 'toast-top-center',
            closeButton  : true,
            timeOut      : 800
        });
    },
    warning: function (msg) {
        return toastr.warning(msg, '提示', {
            progressBar  : true,
            positionClass: 'toast-top-center',
            closeButton  : true,
            timeOut      : 800
        });
    },
    error  : function (error) {
        var msg = "● "+error;
        return toastr.error(msg,'操作失败' , {
            progressBar  : true,
            positionClass: 'toast-top-center',
            closeButton  : true,
            timeOut      : 800
        });
    }
};

$.loading = {
    show : function () {
        $('#cloud_loader').css('display','block');
    },
    hide : function () {
        $('#cloud_loader').css('display','none');
    }
};

Sweety = {
    remove : function (options, successCallback, FailCallback,boolean,tail) {
        swal({
            title              : options.title || '确定要删除吗?',
            text               : options.text || '删除后,关联数据将一并删除,请谨慎操作',
            type               : "warning",
            confirmButtonColor : options.buttonColor || "#DD6B55",
            confirmButtonText  : options.confirmButton || "确定!",
            cancelButtonText   : "取消",
            showCancelButton   : true,
            closeOnConfirm     : false,
            showLoaderOnConfirm: true
        }, function () {
            options.http.get(options.url).then(function (result) {
                // console.log('result',result);
                if ($.isFunction(successCallback)) {
                    successCallback(result)
                }
                if(boolean){
                    if(result.data.status != undefined && result.data.status == 'success'){
                        if(tail){
                            var message = result.data.data;
                        }else{
                            message = '成功';
                        }
                        var type    = 'success';
                    }else{
                        if(result.data.status != undefined && result.data.status == 'error'){
                            message = result.data.data;
                            type    = 'error';
                        }else{
                            message = '失败';
                            type    = 'error';
                        }
                    }
                }else{
                    message = '成功';
                    type    = 'success';
                }

                swal(
                    {
                        type             : type,
                        title            : message,
                        timer            : 2000,
                        showConfirmButton: false
                    });
                // swal('删除成功', '', "success");
            }, function (error) {
                if ($.isFunction(FailCallback)) {
                    FailCallback(error)
                }
                swal(
                    {
                        type             : "error",
                        title            : "删除失败",
                        text             : error.message,
                        timer            : 2000,
                        showConfirmButton: false
                    });
            });
        });
    }
};

Swal   = {
    success   : function (msg,$title,$times) {
        swal({
            type              : "success",
            title             : "操作成功",
            text              :  msg,
            showConfirmButton : true,
            timer             :  $times == undefined || $times ? $times : 200000,
            title             : $title == undefined || $title ? $title : '验卡成功',
        });
    },
    error   : function (msg,$times) {
        swal({
                type             : "error",
                title            : "操作失败",
                text             :  msg,
                showConfirmButton: true,
                timer            :  $times == undefined || $times ? $times : 200000
            });
    }
};
function removeItem(params, cbForSuccess, cbForError) {
    params.title = !params.title ? '确定要删除吗?' : params.title;
    params.text  = !params.text ? '确定要删除吗?' : params.text;
    swal({
        title              : params.title,
        text               : params.text,
        type               : "warning",
        confirmButtonColor : "#DD6B55",
        confirmButtonText  : "删除!",
        cancelButtonText   : "取消",
        showCancelButton   : true,
        closeOnConfirm     : false,
        showLoaderOnConfirm: true
    }, function () {
        params.http.delete(params.url + params.id).then(function (result) {
            if ($.isFunction(cbForSuccess)) {
                cbForSuccess()
            }
            swal('删除成功', '', "success");
        }, function (error) {
            if ($.isFunction(cbForError)) {
                cbForError()
            }
            swal('删除失败', error.data.message, "error");
        });
    });
}

$.config =  {
    highlight : function (self) {
        self.parent().children().css('background-color','#f0f0f0');
        self.css('background-color','#FFB90F');
     },
    closeLight :function () {
        $('tr').css('background-color','#f0f0f0');
    }
};

$.array = {
    arrayIntersection : function ( a, b ) {
        var result = [];
        var alen = a.length;
        var blen = b.length;
        for(var i=0;i<alen;i++){
            for(var j=0;j<blen;j++){
                if(a[i] == b[j]){
                    result.push(a[i]);
                }
            }
         }
        return result;
    }
};

$httpBehaviorTrajectory = {
    http: function (behavior,moduleId,behaviorIntro) {
        if (moduleId == null || moduleId == undefined || moduleId == ''){
            return
        }
        $.post("/user/insert-employee-data",{behavior:behavior,moduleId:moduleId,behaviorIntro:behaviorIntro},
            function(data){}
        )
    }
}

$(document).on('click','button.removeHtml',function () {
    var $attr = $(this).data('remove');
    $(this).parents('div.'+$attr).remove();
});

