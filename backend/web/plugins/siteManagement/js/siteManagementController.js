
angular.module('App').controller('siteManagementCtrl', function ($scope, $http, Upload) {
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
    });
    $scope.venueId = '';
    /**
     * author:李广杨
     * create:2017-09-08
     * 函数描述:数据准备
     * param: venueId:场馆id
     *        sortType:要排序的类型
     *        sortName:排序状态
     *        yardName:场地名称
     * */
    $scope.searchDatas = function () {
        var data = {
            venueId : $scope.venueId   != undefined ? $scope.venueId : '',
            sortType: $scope.sortType  != undefined ? $scope.sortType : '',
            sortName: $scope.sort      != undefined ? $scope.sort : '',
            yardName: $scope.className != undefined ? $scope.className : '',
        };
        $scope.pageInitUrl = '/site-management/get-venue-yard-page?'+$.param(data);
    };
    $scope.searchDatas();
    /**
     * author:李广杨
     * create:2017-09-08
     * 函数描述:初始化获取场馆的数据
     * */
    //初始化场馆
    $scope.initVenue = function () {
        $http({
            url: '/site/get-auth-venue',
            method: 'get'
        }).then(function (data) {
            $scope.venueId = data.data[0].id;
            $scope.venueAll = data.data;
            $scope.VenueStauts = true;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };
    $scope.initVenue();
    /**
     * author:李广杨
     * create:2017-09-08
     * 函数描述:初始化获取列表的数据
     * */
    $scope.listData = function () {
        $.loading.show();
        $http({method:'get',url:$scope.pageInitUrl}).then(function (data) {
            if (data.data.data != "" && data.data.data != undefined && data.data.data.length != undefined) {
                $scope.listDataItem = data.data.data;
                $scope.pages        = data.data.pages;
                $scope.dataInfo     = false;
            }else {
                $scope.listDataItem = data.data.data;
                $scope.pages =   data.data.pages;
                $scope.dataInfo = true
            };
            $.loading.hide();
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
            $.loading.hide();
        });
    };
    $scope.listData();
    /**
     * author:李广杨
     * create:2017-09-08
     * 函数描述:点击回车的时候，也调用点击搜索按钮一样的方法，达到点击回车搜索的功能
     * */
    //回车搜索
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchDatas();
            $scope.listData();
        };
    };
    //点击按钮搜索
    $scope.searchClass = function () {
        $scope.searchDatas();
        $scope.listData();
    };
    $scope.replacementPage = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $scope.listData();
    };
    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchDatas();
        $scope.listData();
    };
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES';
        };
        $scope.sort = sort;
    };
    //跳转指定页面
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pageInitUrl = '/site-management/get-venue-yard-page?page='+value;
            $scope.listData();
        };
    };
    /**
     * author:李广杨
     * create:2017-09-06
     * 函数描述:删除场地信息的数据
     * param: id:要删除的场地的id
     * */
    //删除单条数据
    $scope.removeItem = function (id) {
        Sweety.remove({
            url              : "/site-management/deal-venue-yard?id="+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '删除后场地信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.listData();
        },function(){

        },true);
    };
    //新增场地 初始化数据
    $scope.addField = function () {
        $http({method:'get',url:'/site-management/venue-card-category?venueId='+$scope.venueId}).then(function (data) {
            $scope.venueCardCategory = data.data.data;
            $("#userHeader1").select2({
                placeholder:'请选择会员卡',
                dropdownParent:$("#siteManagementAdd")
            });
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员")
        });
        $("#siteManagementAdd").show();
    };
    /**
     * author:李广杨
     * create:2017-09-07
     * 函数描述:完成新增场地
     * param: venueId:场馆id
     *        yardName:场地的名字
     *        peopleLimit:人数限制
     *        businessTime:营业时间
     *        everyTimeLong:每次时长
     * */
    //新增场地完成
    $scope.addFieldOk = function () {
        var dayStart = '2017-09-13 '+$("input[name='dayStart']").val()
        var datEnd   = '2017-09-13 '+$("input[name='dayEnd']").val()
        var data = {
            venueId:$scope.venueId,
            yardName:$scope.siteName,
            peopleLimit:$scope.numberBox,
            businessTime: $("input[name='dayStart']").val()+"-"+$("input[name='dayEnd']").val(), // 营业时间
            everyTimeLong:$scope.timeLength ,
            cardCategoryId:$("#userHeader1").val(),
            _csrf_backend:$("#_csrf").val()
        }
        if(dayStart < datEnd ){
            if(!$scope.messageWarning(data.yardName,"请输入名称")){ return}
            if(!$scope.messageWarning(data.peopleLimit,"请输入人数")){ return ;}
            if(data.businessTime == '' && data.businessTime == undefined && data.businessTime == null){
                Message.warning("请选择时间");return;
            }
            if(!$scope.messageWarning(data.everyTimeLong,"请输入每次时长")){ return ;}
            $http({method:'post',url:'/site-management/add-venue-yard',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
                if(success.data.status == "success"){
                    $scope.siteName   = '';
                    $("input[name='dayStart']").val('')
                    $("input[name='dayEnd']").val('')
                    $scope.numberBox  = '';
                    $scope.timeLength = '';
                    Message.success(success.data.data);
                    $scope.listData()
                    $("#siteManagementAdd").hide();
                    $(".modal-backdrop").hide();
                }
                else if (success.data.status == "error" ){
                    Message.warning(success.data.data);
                    return;
                }
            },function (error) {
                console.log(error);Message.error("系统错误请联系工作人员")
            })
        }else {
            if(!$scope.messageWarning(data.yardName,"请输入名称")){ return}
            if(!$scope.messageWarning(data.peopleLimit,"请输入人数")){ return ;}
            if(data.businessTime == '' && data.everyTimeLong == undefined && data.everyTimeLong == null){
                Message.warning("请选择时间");return;
            }
            if(!$scope.messageWarning(data.everyTimeLong,"请输入每次时长")){ return ;}
            Message.warning("请认真填写时间");return;
        }
    }
    $scope.upDateArray = [];
    //更改场地 初始化数据
    $scope.upDate = function (id,yard_name,people_limit,business_time,active_duration) {
        $scope.upDateArray = [];
        $("#id_label_single1").val('')
        $http({method:'get',url:'/site-management/card-category?yardId='+id}).then(function (data) {
            $scope.upDateArray = [];
            for (var index = 0; index < data.data.data.length; index++) {
                $scope.upDateArray.push(data.data.data[index].card_category_id);
            }
            $http({method:'get',url:'/site-management/venue-card-category?venueId='+$scope.venueId}).then(function (success) {
                $scope.venueCardCategory = success.data.data;
                $("#id_label_single1").select2({
                    placeholder:'不限会员卡',
                    dropdownParent:$("#siteManagementUpdate")
                });
            },function (error) {
                console.log(error);Message.error("系统错误请联系工作人员")
            });
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员")
        });
        $scope.upDateId           = id;
        $scope.upDateYardName    = yard_name;
        $scope.upDatePeopleLimit = parseInt(people_limit);
        $("input[name='dayStart1']").val(business_time.substr(0,5))
        $("input[name='dayEnd1']").val(business_time.substr(6,11))
        $scope.upDateActiveAuration = parseInt(active_duration);
    }
    /**
     * author:李广杨
     * create:2017-09-08
     * 函数描述:修改更新场地信息
     * param: id:修改的id
     *        businessTime:营业时间
     *        everyTimeLong:每次时长
     * */
    //修改场地 完成
    $scope.upDateAdd= function () {
        var data = {
            id:$scope.upDateId,
            peopleLimit:$scope.upDatePeopleLimit,
            businessTime:$("input[name='dayStart1']").val()+"-"+$("input[name='dayEnd1']").val(),
            everyTimeLong:$scope.upDateActiveAuration,
            cardCategoryId:$("#id_label_single1").val(),
            // card_category_id:$scope.upDateArray,
        }
        $http({method:'post',url:"/site-management/update-venue-yard",data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if(success.data.status == "success"){
                Message.success(success.data.data);
                $scope.listData();
                $("#siteManagementUpdate").modal("hide");
                $(".modal-backdrop").hide();
            }else if (success.data.status == "error"){
                Message.success(success.data.data);
                $scope.listData();
                $("#siteManagementUpdate").modal("hide");
                $(".modal-backdrop").hide();
            }
            $scope.listData()
        },function (error) {
            console.log(error);Message.error("系统错误请留下工作人员")
        });
    }
    /**
     * author:李广杨
     * create:2017-09-07
     * 函数描述:获取场地预约的详情信息
     * param: id:需要获取的场地的id
     * */
    //预约详情
    $scope.siteManagementDetailsClick = function (id) {
        // $("#siteManagementDetails").modal("show");
        $scope.classStatus = '';
        var display =$('#searchMember').css('display');
        if(display == 'none'){
            $scope.keywords = '';
        }
        $scope.siteManagementDetailsId = id;
        $scope.dateInput = $scope.getNowFormatDate();
        $scope.getDetailsLeft($scope.siteManagementDetailsId);
        $scope.selectionTime($scope.aboutIntervalSection)
    }
    /**
     * author:李广杨
     * create:2017-09-09
     * 函数描述:取消预约场地,取消预约场地需要保证场地预约时间还没到(即场地未过期)
     * param: id:需要取消的场地的id
     *        date:发起取消预约事件的时间
     * */
    //取消预约
    $scope.cancelReservation = function (id,date,dates) {
        var d = date+' '+dates.substr(0,5)
        if (d < $scope.getNowFormatDates()){
            $scope.selectionTime($scope.aboutIntervalSection);
            Message.warning("当前时间已经过期，不能取消预约!");return;
        }else {
            Sweety.remove({
                url              : "/site-management/cancel-member-about?id="+id,
                http             : $http,
                title            : '确定要取消预约吗?',
                text             : '取消后所有信息无法恢复',
                confirmButtonText: '确定',
                data             : {
                    action: 'unbind'
                }
            }, function () {
              $scope.getDetailsLeft($scope.siteManagementDetailsId);

            },function(){

            },true);
        }
    }
    // 日期插件的调用
    $("#dateIndex").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true //今日按钮
    }).on('changeDate', function (ev) {
        $scope.getDetailsLeft($scope.siteManagementDetailsId);
        $scope.selectionTime($scope.aboutIntervalSection)
    });
    $scope.memberAboutDetail = function (urlPages) {
        $http({method:'get',url:urlPages}).then(function (data) {
                if (data.data.data != "" || data.data.data != undefined ) {
                    $scope.selectionTimeList = data.data.data;
                    $scope.detailPages = data.data.pages;
                    $scope.detailInfo = false;
                    $scope.nowPage      = data.data.nowPage;
                } else {
                    $scope.selectionTimeList = data.data.data;
                    $scope.detailPages = data.data.pages;
                    $scope.detailInfo = true;
                }
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }
    $scope.getDetailsLeft = function (id) {
        $scope.url = '/site-management/yard-detail?yardId='+id+"&memberAboutDate="+$scope.dateInput
        $http({method:'get',url:$scope.url}).then(function (data) {
            if (data.data.data.params != ''){
                $scope.siteDetailsLeft       = data.data.data;
                $scope.aboutIntervalSection = data.data.data.params;
                $scope.detailPages           = data.data.pages;
                $scope.dataParams = false;
                for(var i in $scope.siteDetailsLeft.orderNumList){
                    if ($scope.siteDetailsLeft.orderNumList.hasOwnProperty(i)) {
                        if(i == $scope.aboutIntervalSection){
                            var d = $scope.dateInput +" "+i.substr(0,5);
                            if(d < $scope.getNowFormatDates()){
                                $scope.selectionTime(data.data.data.params,1)
                            }
                            if(d > $scope.getNowFormatDates()) {
                                $scope.selectionTime(data.data.data.params,2)
                            }
                        }
                    };
                }
            }else {
                Message.warning("暂时没有数据");
                $scope.siteDetailsLeft       = data.data.data;
                $scope.selectionTime(data.data.data.params,1)
                $scope.dataParams = true;
            }
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }
    $scope.selectionTime = function (key,timeStatus) {
        $scope.aboutIntervalSection = key;
        $http({method:'get',url:'/site-management/get-about-data-detail?memberAboutDate='+$scope.dateInput+"&aboutIntervalSection="+key+"&yardId="+$scope.siteManagementDetailsId}).then(function (data) {
            if (data.data.data != "" || data.data.data != undefined) {
                $scope.selectionTimeList   = data.data.data;
                $scope.detailPages          = data.data.pages;
                $scope.detailInfo           = false;
                $scope.nowPage      = data.data.nowPage;
            } else {
                $scope.selectionTimeList   = data.data.data;
                $scope.detailPages          = data.data.pages;
                $scope.detailInfo           = true;
            }
            if(timeStatus == 1){
                $scope.timeStatus = 1;
                $scope.dataParams = true;
            }
            if(timeStatus == 2) {
                $scope.timeStatus = 2;
                $scope.dataParams = false;
            }
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }
    //预约会员
    $scope.reservationMember = function () {
        $("#siteManagementDetails").modal("hide");
        $("#searchMember").modal("show");
    }
    /**
     * author:李广杨
     * create:2017-09-09
     * 函数描述:根据输入的数据，进行会员的搜索
     * param: data:输入的数据
     * */
    //预约会员搜索
    $scope.reservationMemberSearch  = function (data) {
        var date = $scope.dateInput +' '+ $scope.aboutIntervalSection.substr(0,5)
        if(date < $scope.getNowFormatDates()){
            Message.warning("请选择没有过期的时间!");return;
        }
        if(!$scope.messageWarning(data,"请输入搜索的内容")){ return}
        $http({method:'get',url:'/site-management/search-member?mobile='+data+"&yardId="+  $scope.siteManagementDetailsId }).then(function (success) {
            if(success.data.status == "error"){
                Message.warning(success.data.data);
                return;
            }else if(success.data.status =="success") {
                if(success.data.data.memberCard.length != 0){
                    console.log(success);
                    $scope.memberDetails = success.data.data;
                    $scope.memberCardId  = success.data.data.memberCard[0].memberCardId;
                    $scope.memberBool    = false;
                }else {
                    $scope.memberDetails = success.data.data;
                    $scope.memberBool    = true;
                    console.log(success);
                }
                $scope.keywords = '';
                $("#searchMember").modal("hide");
                $("#membershipDetails").modal("show");
            }
        },function (error) {
         console.log(error);Message.error("系统错误请联系工作人员")
        })
    }
    /**
     * author:李广杨
     * create:2017-09-09
     * 函数描述:会员搜索成功后点击预约触发事件
     * param: yardId:场地的id
     *        memberId:会员的id
     *        memberCardId:会员卡的id
     *        aboutDate:选择的时间
     * */
    //会员点击预约
    $scope.memberReservationIsSuccessful = function () {
        var data = {
            yardId:$scope.siteManagementDetailsId,
            memberId:$scope.memberDetails.memberMessage.id,
            memberCardId:$(".cardIdYuyue").val(),
            aboutIntervalSection:$scope.aboutIntervalSection,
            aboutDate:$scope.dateInput
        };
        $http({method:'post',url:'/site-management/member-yard-about',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (success) {
            if (success.data.status == "success"){
                Message.success(success.data.data)
            }
            if (success.data.status == "error"){
                Message.warning(success.data.data);
                return;
            }
            $("#membershipDetails").modal("hide");
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }

    //定义公共验证方法
    $scope.messageWarning = function (data,success) {
        if(data == null || data == undefined || data == ''){
            Message.warning(success);
            return false;
        }
        else {
            return true;
        };
    };
    $scope.getNowFormatDate = function () {
        var date = new Date();
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var hour=date.getHours();
        var minute=date.getMinutes();
        var second=date.getSeconds();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var hour=date.getHours();
        if (hour >= 1 && hour <= 9) {
            hour = "0" + hour;
        }
        var minute=date.getMinutes();
        if (minute >= 1 && minute <= 9) {
            minute = "0" + minute;
        }
        var second=date.getSeconds();
        if (second >= 1 && second <= 9) {
            second = "0" + second;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate
        return currentdate;
    }


    $scope.getNowFormatDates = function () {
        var date = new Date();
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var hour=date.getHours();
        var minute=date.getMinutes();
        var second=date.getSeconds();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var hour=date.getHours();
        if (hour >= 1 && hour <= 9) {
            hour = "0" + hour;
        }
        var minute=date.getMinutes();
        if (minute >= 1 && minute <= 9) {
            minute = "0" + minute;
        }
        var second=date.getSeconds();
        if (second >= 1 && second <= 9) {
            second = "0" + second;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate+" "+hour+":"+minute;
        return currentdate;
    }
    //取消关闭
    $scope.cancelClose = function (number) {
        if(number ==1){
            $scope.siteName   = '';
            $("input[name='dayStart']").val('')
            $("input[name='dayEnd']").val('')
            $scope.numberBox  = '';
            $scope.timeLength = '';
            $('#siteManagementAdd').hide();
            $(".modal-backdrop").hide();
            return;
        }
        if(number ==2){
            $('#siteManagementUpdate').hide();
            $(".modal-backdrop").hide();
            return;
        }
    }
})