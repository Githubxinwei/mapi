/**
 * 会员信息 - 私教购买的angularJs加载
 */
// 日期插件的按钮不显示的js
$('.icon-arrow-left').addClass('glyphicon,glyphicon-arrow-left');
$('.icon-arrow-right').addClass('glyphicon,glyphicon-arrow-right');

/**
 *后台会员管理 - 会员信息查询 - 访问user控制器的actionMember()
 * @author Huang hua <huanghua@itsports.club>
 * @create 2017/3/30
 * 后台会员管理 - 会员信息删除 - 删除指定会员信息按钮
 * @update author Hou kaiXin houkaixin@itsport.club
 * @update 2017/3/31
 * @return bool|string
 */
angular.module('App').controller('indexCtrl', function ($scope, $http, Upload, $timeout,$interval) {
    // angular.element(document).ready(function () {
    //     var spanBig = $('.spanBig').text();
    //     var spanSmall = $('.spanSmall').text();
    //     var getData = localStorage.getItem("initBehaviorTrajectoryId");
    //     var getItemData = JSON.parse(getData)
    //     for (var key in getItemData) {
    //         if (getItemData.hasOwnProperty(key)) {
    //             var element = getItemData[key];
    //             for(var i in element){
    //                 if (element.hasOwnProperty(i)){
    //                     var elementModule = element[i];
    //                     if(elementModule.name == spanBig){
    //                         for(var a in elementModule.module){
    //                             if (elementModule.module.hasOwnProperty(a)){
    //                                 var elementSmallModule = elementModule.module[a];
    //                                 if (elementSmallModule.name == spanSmall){
    //                                     var id  =  elementSmallModule.id
    //                                     localStorage.setItem("moduleId",JSON.stringify({
    //                                         id:id
    //                                     }))
    //                                     return
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // })

    // var getModuleId = localStorage.getItem("moduleId");
    // $scope.moduleId = JSON.parse(getModuleId).id
    // $httpBehaviorTrajectory.http(1, $scope.moduleId, "会员管理模块")
    //续费的缴费日期
    $("#moneyDate").datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language: 'zh-CN',
        autoclose: true
    })

    $("#expireDate").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true,//今日按钮
        setStartDate: "2017-5-25"
    }).on('changeDate', function (ev) {
        $scope.expireDate = $("#expireDate").val();
    });

    $("#postponeDate").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true,//今日按钮
        setStartDate: "2017-5-25"
    }).on('changeDate', function (ev) {
        $scope.postponeDate = $("#postponeDate").val();
    });

    //订金日历插件调用
    $('#subscriptionDate').daterangepicker(null, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    $('#validityDateTime123').daterangepicker(null, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    $scope.path = function () {
        $scope.MAIN = {
            'API': {
                'memberCardPath': "/user/member-card-info?MemberId=" + $scope.id,
                'memberCardInfo': "/member/get-member-card?MemberCardId=" + $scope.memberCardId,
                'groupPath': '/user/group-class-info?' + "MemberId=" + $scope.id,
                'giftPAth': '/user/gift-record-info?' + "MemberId=" + $scope.id,
                'cabinetPath': '/user/cabinet-info?' + "MemberId=" + $scope.id,
                'leavePath': "/user/leave-record-info?MemberId=" + $scope.id,
                'consumptionPath': "/user/consumption-info?MemberId=" + $scope.id,
                'entryRecordPath': "/user/entry-record-info?MemberId=" + $scope.id,
                'entryPath': "/user/entry-info?MemberId=" + $scope.id,
                'memberPath': "/user/member-detail?MemberId=" + $scope.id,
                'chargePath': "/user/charge-class-info?MemberId=" + $scope.id
            }
        };
    };
    // 卡种筛选的selcet多选事件
    $(document).ready(function () {
        $(".cardStatus").select2({
            placeholder: "选择会员状态" //设置placeholder的内容
        });
        $(".cardStatus1").select2({
            placeholder: "性别不限" //设置placeholder的内容
        })
    });
    $.loading.show();
    $scope.init = function () {
        $scope.entryTime = "";
        $scope.pageInitUrl = '/user/member-info';
        $scope.initPath();
        // $scope.getCardTypeOptions();           //获取卡种类别
        $scope.getVenueOptions();              //获取用户有权限的场馆
        $scope.searchEntryData();
        $scope.getData();
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchClearData();
        $scope.init();
        $scope.getData();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.sex = null;
        $scope.type = null;
        $scope.dateTime = null;
        $scope.keywords = null;
        $scope.allStates = null;
        $scope.venueId = null;
        $scope.trialClass = null;
        $scope.privatesData = null;
        $('#idLabelSingle').select2("val", "");
        $('#select2-idLabelSingle-container').text('选择销售');
    };
    //分隔搜索时间
    $scope.formatDate = function () {
        $scope.startTime = $("#reservation").val().substr(0, 10);
        $scope.endTime = $("#reservation").val().substr(-10, 10);
    };

    $scope.formatDate();
    $scope.replacementPages = function (urlPages) {
        $.loading.show();
        $scope.pageInitUrl = urlPages;
        $scope.getData();
    };

    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchCard();
    };
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        }
        $scope.sort = sort;
    };

    $scope.getData = function () {
            $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.datas = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.datas = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide();
        });
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            // console.log('页数',value)
            $scope.pageInitUrl = '/user/member-info?page='+value;
            $scope.getData();
        }
    };
    //获取用户有权限的场馆
    $scope.getVenueOptions = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if (result.data != undefined && result.data != "") {
                $scope.optionVenue = result.data;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.VenueStautsLength = true;
                $scope.optionVenue = '暂无数据';
            }
        });
    };
    //获取卡种类型信息
    // $scope.getCardTypeOptions = function () {
    //     $http.get('/member-card/get-type').then(function (result) {
    //         if (result.data.type != undefined && result.data.type != "") {
    //             $scope.optionType = result.data.type;
    //         }
    //     });
    // };
    //冻结按钮
    $scope.updateMember = function (id,statusId) {
        $scope.freezeMemberId = id;
        $scope.statusId  = statusId;
        $('#freezeContent').val('')
        $('#freezeRemark').modal('show');
        // if(parseInt(statusId) == 2){
        //     $http.get("/user/update-status?id=" + id).then(function (result) {
        //         Message.success('操作成功');
        //         $('.frozen').show();
        //         $scope.getData();
        //     });


    };
    //
    $scope.confirmFreeze = function(){

        var data = {
            _csrf_backend: $('#_csrf').val(),
            memberId :$scope.freezeMemberId,
            note      :$('#freezeContent').val(),
            behaviorId :$scope.statusId,
            memberCardId:null
        }
        // console.log('冻结原因',data);
        if($('#freezeContent').val().replace(/ /g,'') == ''){
            Message.warning('冻结原因不能为空!');
            return;
        }
        $http({
            url:'/user/add-note',
            method: 'POST',
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            // console.log('返回的数据',response)
            if(response.data.status== 'success'){
                Message.success(response.data.data);
                $http.get("/user/update-status?id=" + $scope.freezeMemberId).then(function (result) {
                    $('#freezeRemark').modal('hide');
                    // console.log(result)
                    $('.frozen').show();
                    $scope.getData();
                });
            }
        });
    }

    $scope.delMem = function (id, name) {
        Sweety.remove({
            url: "/user/mem-data?memberId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getData();
        });
    };

    $scope.delMemberCard = function (id) {
        Sweety.remove({
            url: "/user/member-card-del?memberId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getCardData();
        });
    };
    $scope.delMemberCabinet = function (id) {
        Sweety.remove({
            url: "/user/member-cabinet-del?memberId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getCabinetData();
        });
    };
    $scope.delChargeClass = function (id) {
        Sweety.remove({
            url: "/user/course-package-del?memberId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getChargeClassData();
        });
    };

    $scope.getMemberDataCard = function (id,status) {
        $scope.selectEntryRecord = ''
        $scope.MemberId123 = id;
        $scope.MemberStatusFlag = status;
        $('div.tab-content .tab-pane').removeClass('active');
        var $nav = $('.nav-tabs li');
        $nav.removeClass('active');
        $('#tab-1').addClass('active');
        $('.nav-tabs li:first').addClass('active');
        $scope.id = id;
        $scope.getMemberData(id);
        $scope.path();
    };
    $scope.chooseSelectSaleAll = function () {
        $http({method: "get", url: '/user/get-adviser'}).then(function (data) {
            $scope.optionSale = data.data
        }, function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员")
        })
    }
    $scope.chooseSelectSaleAll()

    /******点击资料选项触发事件*******/
    $scope.getMemberDetail = function (id) {
        $scope.getDetailData(id);
    };

    /******获取会员详细信息*******/
    $scope.getDetailData = function () {
        $http.get($scope.MAIN.API.memberPath).success(function (result) {
            // $scope.MemberData = result.data;
        });
    };
    // 点击会员卡详情触发事件
    $scope.cardDetail = function (id) {

        // $scope.getDetailData(id);
    }
    $scope.getMemberData = function (id) {
        $scope.renewUserId = id;
        $http.get("/user/member-details-card?MemberId=" + id).then(function (result) {
            $scope.MemberData = result.data;
            $scope.userTel = result.data.mobile;
        })
    };
    /**
     *后台会员管理 - 会员信息（版本2） -  会员卡详情列表
     * @author 苏雨
     * @create 2017/5/26
     * @update 2017/5/26
     *
     */

    // 获取续费过期时长
    $scope.getRenewTimesFun = function (){
        $http.get('/member-card/get-config?type=' + "card" + '&key=renew').success(function (data){
            // console.log(data);
            if(data.attributes != null){
                $scope.renewValue = (((data.attributes.value)*24)*60)*60;
                $scope.allTimesRenewCard = parseInt($scope.cardInfoTime) + parseInt($scope.renewValue);
            }
            // console.log("card:",$scope.allTimesRenewCard)
            // console.log("time:",$scope.timestamp);
        });
    }

    // 获取销售人员信息
    $scope.getSaleMansInfo = function () {
        $http.get("/user/get-adviser").success(function (response) {
            $scope.saleInfo = response;
        });
    };

    $scope.getCardInfoSelect = function (){
        $http.get($scope.MAIN.API.memberCardPath).success(function (response) {
            if (response.item == "" || response.item == undefined || response.item.length <= 0) {
                $scope.cardInfoItems = response.item;
            } else {
                $scope.cardInfoItems = response.item;
            }
        })
    };

    $scope.getCardRewenRecondList = function (){
        $http.get('/member/history?memberCardId=' + $scope.infoId).then(function (data) {
            if (data.item == "" || data.data == undefined || data.data.length <= 0) {
                $scope.paymentItems = data.data;
                $scope.payNoMoneyDataShow = true;
            } else {
                $scope.paymentItems = data.data;
                $scope.payNoMoneyDataShow = false;
            }
        });
    };

    // 点击会员卡详情触发事件
    $scope.getMemberInfo = function () {
        $("#showDetails").css("display","none");
        $http.get($scope.MAIN.API.memberCardPath).success(function (response) {
            if (response.item == "" || response.item == undefined || response.item.length <= 0) {
                $scope.cardInfoItems = response.item;
            } else {
                $scope.cardInfoItems = response.item;
            }
            // 获取今日日期
            var todayDate = function () {
                var newToday = new Date();
                var y = newToday.getFullYear();
                var m = newToday.getMonth() + 1;//获取当前月份的日期
                var d = newToday.getDate();
                $scope.todaySale = y + "-" + m + "-" + d;
            };
            todayDate();
            $scope.renewDate = $scope.todaySale;
            $scope.endTime = $scope.todaySale;
            if ($scope.cardInfoItems.length == 0||$scope.cardInfoItems[0].card_name == null||$scope.cardInfoItems[0].card_name == undefined||$scope.cardInfoItems[0].card_name == '') {
                $scope.infoId = 'xixi';
            } else {
                $scope.infoId = $scope.cardInfoItems[0].id;
            }
            $scope.getCardRewenRecondList();
            $scope.infoChange = function (id) {
                $http.get('/member/get-member-card?memberCardId=' + $scope.infoId).success(function (data) {
                    $scope.cardInfo  = data;
                    $scope.usageMode = data.usage_mode;
                    $scope.cardNameNot    = data.card_name;
                    $scope.transferPrice = $scope.cardInfo.transfer_price;
                    $scope.cardActiveTime = data.active_time;
                    // 到期时间的时间戳
                    $scope.cardInfoTime = data.invalid_time;
                    // 计算剩余时间的js
                    // 获取今日时间
                    $scope.timestamp = Date.parse(new Date()) / 1000;
                    $scope.remainingDate = Math.round((((parseInt($scope.cardInfoTime) - parseInt($scope.timestamp)) / 24) / 60) / 60);
                    $scope.getRenewTimesFun();
                    if ($scope.remainingDate < 0) {
                        $('#daysSpan').text("卡已过期")
                    }
                    // 列表页时间转换的js
                    $scope.cardTime = $scope.getMyDate($scope.cardInfo.invalid_time * 1000);
                    $http.get('/member/history?memberCardId=' + $scope.infoId).then(function (data) {
                        $scope.paymentItems = data.data;
                        // $scope.consumptionDate = $scope.getMyDate($scope.paymentItemsS.invalid_time * 1000);
                        $scope.getCardRewenRecondList();
                    })
                    if($scope.cardInfo.status == 3){
                        $(".freezeButton").html("解冻");
                    }
                    else {
                        $(".freezeButton").html("冻结");
                    }
                });
            };
            $scope.infoChange();
            // 卡详情续费的点击方法
            $scope.renrewCard = function (id) {
                $scope.rememberCardId = id;
                //获取续费所有卡种
                $http.get("/sell-card/card-category").success(function (data){
                    $scope.renrewCardTypeList = data;
                    // console.log(data);
                });
            };

            // 获取续费卡种的有效期
            $(".cardTimeWords").hide();
            // 设置默认金额选择
            $scope.renewPrice       = '';
            $scope.getRenewCardInfo = function (data){
                // console.log("info:",data);
                if(data != "" && data != null && data != undefined){
                    var $data = angular.fromJson(data);
                    $scope.renewCardNumber   = $data.id; //续费后的卡id
                    if($data.duration != null){
                        $scope.renewCardDay = $data.duration.replace(/[^0-9]/ig,""); //续费后的卡有效天数
                    }
                    else{
                        $scope.renewCardDay = "0";
                    };
                    $scope.renewStartDat     = $scope.cardInfo.invalid_time;
                    $scope.renewTermEnd      = (parseInt($scope.cardInfo.invalid_time) + parseInt($scope.renewCardDay*24*60*60)).toString();  // 计算续费使用期限
                    $(".cardTimeWords").show();
                    // 判断续费卡价格
                    if($data.renew_price == null && $data.sell_price != null){
                        $scope.renewPrice         = $data.sell_price;
                        $scope.renewCardAllMoney  = $data.sell_price;
                        $scope.renCardRecondMoney = $data.sell_price;
                    }
                    else if($data.renew_price != null && $data.sell_price != null){
                        $scope.renewPrice         = $data.renew_price;
                        $scope.renCardRecondMoney = $data.sell_price;
                        $scope.renewCardAllMoney  = $data.renew_price;
                    }
                    else{
                        $scope.renewPrice         = "";
                        $scope.renCardRecondMoney = "";
                        $scope.renewCardAllMoney  = "";
                    }
                }
                else {
                    $(".cardTimeWords").hide();
                    $scope.renewPrice          = '';
                    $scope.renCardRecondMoney  = "";
                    $scope.renewCardAllMoney   = "";
                }
            };

            $scope.priceChange = function (){
                $scope.renCardRecondMoney = $scope.renewPrice;
            };

            $scope.getSaleMansInfo();
            // 续费完成提交数据
            $scope.renrewSuccess = function () {
                $scope.memberCardId = $scope.rememberCardId;
                console.log($scope.renewTermEnd);
                $scope.renrewSuccessData = function () {
                    return {
                        memberCardId  : $scope.memberCardId    != undefined && $scope.memberCardId    != "" ? $scope.memberCardId    : null, //续费的老卡的卡id
                        cardCategoryId: $scope.renewCardNumber != undefined && $scope.renewCardNumber != "" ? $scope.renewCardNumber : null, //续费卡id
                        renewDate     : $scope.renewStartDat   != undefined && $scope.renewStartDat   != "" ? $scope.renewStartDat   : null, //续费起始日期
                        renewPrice    : $scope.renewPrice      != undefined && $scope.renewPrice      != "" ? $scope.renewPrice      : null, //续费价格
                        endTime       : $scope.renewTermEnd    != undefined && $scope.renewTermEnd    != "" ? $scope.renewTermEnd    : null, //续费结束日期
                        seller        : $scope.seller          != undefined && $scope.seller          != "" ? $scope.seller                   : null, //销售
                        cardNumber    : $scope.cardNumber      != undefined && $scope.cardNumber      != "" ? $scope.cardNumber      : null, //自定义的卡号
                        usage_mode    : $scope.usageMode       != undefined && $scope.usageMode       != "" ? $scope.usageMode       : null, //卡使用类型 1、自用 2、送人
                        _csrf_backend: $('#_csrf').val()
                    }
                };
                //保存数据之前数据验证
                if ($scope.renewCardNumber == null || $scope.renewCardNumber == "" || $scope.renewCardNumber == undefined) {
                    Message.warning("请选择卡种");
                    return false;
                }
                if ($scope.renewStartDat == null || $scope.renewStartDat == "") {
                    Message.warning("日期有误，请重新选择卡种");
                    return false;
                }
                if ($scope.renewPrice == null || $scope.renewPrice == "") {
                    Message.warning("请输入金额");
                    return false;
                }
                if ($scope.seller == null || $scope.seller == "") {
                    Message.warning("请选择销售");
                    return false;
                }
                $scope.checkButton = true;
                // 发送数据
                $http({
                    url: "/member/card-renew",
                    method: 'POST',
                    data: $.param($scope.renrewSuccessData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (data) {
                    if (data.data.status == "success") {
                        Message.success("续费成功");
                        // 数据初始化
                        $scope.renewTermEnd  = "";
                        $scope.renewPrice    = "";
                        $scope.seller        = "";
                        $scope.renewStartDat = "";
                        $scope.cardNumber    = "";
                        $scope.renewCardType = "";
                        $scope.checkButton   = false;
                        $("#myModals15").modal("hide");
                        $scope.getCardInfoSelect();
                        $scope.getCardRewenRecondList();
                    } else {
                        $scope.checkButton = false;
                        Message.warning(data.data);
                    }
                })
            };

            // 查询转卡会员手机号
            $scope.searchUserClick = function (){
                var $pattern = /^1((3[0-9]|4[57]|5[0-35-9]|7[0678]|8[0-9])\d{8}$)/;
                if($scope.mobile == null || $scope.mobile == ""){
                    Message.warning("请输入转卡人手机号");
                    return false;
                }else if(!($pattern.test($scope.mobile))) {
                    Message.warning("手机号输入错误，请重新输入");
                    return;
                }
                $http.get("/cabinet/search-member?phone=" + $scope.mobile).success(function (data){
                    // console.log('data:',data);
                    if(data == "null") {
                        Message.warning("未找到会员，请重新输入");
                        return;
                    }
                    else {
                        $("#nameInputCheck").val(data.name);
                        // $scope.userDataName = data.name;
                    }
                })
            };

            // 动态改变
            $scope.changeNameNumber = function (){
                $("#nameInputCheck").val("");
            };

            // 转卡的点击方法
            $scope.zhuanCard = function (id) {
                $scope.transferCardButtonFlag = false;
                $scope.zhuanCardId = id;
                // 转卡的点击提交信息的方法
                $scope.giveCard = function () {
                    $scope.memberCardId = $scope.zhuanCardId;
                    $scope.cardAdd = function () {
                        // 整理提交数据
                        return {
                            memberCardId: $scope.memberCardId != undefined && $scope.memberCardId != "" ? $scope.memberCardId : null,
                            mobile: $scope.mobile != undefined && $scope.mobile != "" ? $scope.mobile : null,
                            transferPrice: $scope.transferPrice != undefined && $scope.transferPrice != "" ? $scope.transferPrice : null,
                            _csrf_backend: $('#_csrf').val()
                        }
                    };
                    if(!$scope.transferCardButtonFlag){

                    //保存数据之前数据验证
                    var $pattern = /^1((3[0-9]|4[57]|5[0-35-9]|7[0678]|8[0-9])\d{8}$)/;
                    if ($scope.mobile == null || $scope.mobile == "") {
                        Message.warning("请输入转卡人手机号");
                        return false;
                    } else if (!($pattern.test($scope.mobile))) {
                        Message.warning("请输入正确的手机号");
                        return false;
                    }
                    if ($scope.transferPrice == null || $scope.transferPrice == "") {
                        Message.warning("请输入转让手续费");
                        return false;
                    }
                    $scope.transferCardButtonFlag = true;
                    // 发送数据
                    $http({
                        url: "/member/transfer-card",
                        method: 'POST',
                        data: $.param($scope.cardAdd()),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (data) {
                        if (data.data.status == "success") {
                            Message.success("转卡成功");
                            // 数据初始化
                            $scope.name = "";
                            $scope.mobile = "";
                            $scope.transferPrice = "";
                            $("#myModals1").modal("hide");
                            $("#myModals2").modal("hide");
                            $("#myModals17").modal("hide");
                            $scope.getData();
                            $("#nameInputCheck").val("");
                        } else {
                            $scope.transferCardButtonFlag = false;
                            Message.warning(data.data.data);
                        }
                    })
                    }
                }
            };
        });
    };

    // 获取销售人员信息
    $scope.getAllSaleMan = function (){
        $http.get("/user/get-adviser").success(function (response) {
            $scope.saleInfoList = response;
        });
    };

    // 升级卡的点击方法
    $scope.updateCard = function (id,cardId,time) {
        $scope.getAllSaleMan();
        $scope.updateCardButtonFlag = false;
        // 获取比旧卡费用更高的卡
        $scope.memberCardId = id;
        $http.get('/sell-card/new-card-category?oldCardCategory=' + id).success(function (data) {
            if($scope.cardList == []||$scope.cardList == null||$scope.cardList == ''){
                $scope.cardList = data;
            }
            else{
                $scope.cardList = data;
            }
        });
        // 卡种选择事件
        $scope.getUpsateCardType = function (){
            $scope.upsateCardRecondMoney = 0;
            $scope.upsateCardTimeStart   = $scope.cardInfo.create_at; //升级卡的开始时间
            $scope.upsateType            = angular.fromJson($scope.upsateCardType); //转换格式
            $scope.upsateTypeId          = $scope.upsateType.id; // 新卡的卡种id
            $scope.oldCardSellPrice      = parseInt($scope.cardInfo.amount_money); // 老卡价格
            if($scope.upsateType.sell_price == 'null' || $scope.upsateType.sell_price == null){
                $(".upsateCardMoneySelect").removeAttr("readonly");
                $(".upsateCardMoneySelect").val("");
                $scope.upsateCardMoneyMax = parseInt($scope.upsateType.max_price); //新卡最高售价
                $scope.upsateCardMoneyMin = parseInt($scope.upsateType.min_price); //新卡最低售价
                $scope.newCardSellPrice   = parseInt($scope.upsateType.min_price) + "-" + parseInt($scope.upsateType.max_price); // 新卡价格
            }
            else if($scope.upsateType.sell_price != 'null' && $scope.upsateType.sell_price != null){
                $(".upsateCardMoneySelect").attr("readonly",true);
                $scope.upsateCardMoney       = $scope.upsateType.sell_price; //新卡售价
                $scope.newCardSellPrice      = parseInt($scope.upsateCardMoney);  // 新卡价格
                $scope.upsateCardRecondMoney = $scope.newCardSellPrice - $scope.oldCardSellPrice; //升级价格
                $(".upsateCardMoneySelect").val($scope.newCardSellPrice);
                $('.newCardPricePay').text($scope.upsateCardRecondMoney.toFixed(2));
            }
            $scope.upsateDuration        = parseInt($scope.upsateType.duration.replace(/[^0-9]/ig,""))*24*60*60; //计算卡种有效期
            $scope.upsateCardEnd         = parseInt($scope.cardInfo.create_at) + $scope.upsateDuration; //升级卡的结束时间
            var timetemps = new Date();
            timetemps.setHours(0);
            timetemps.setMinutes(0);
            timetemps.setSeconds(0);
            timetemps.setMilliseconds(0);
            $scope.todaytimetemps = Date.parse(timetemps)/1000; //获取当前时间戳
            $scope.getDiscount($scope.upsateTypeId);
            if($scope.upsateCardRecondMoney < 0){
                Message.warning("新卡价格小于当前卡，无法升级");
            }
            else if($scope.upsateCardEnd < $scope.todaytimetemps || $scope.upsateCardEnd == $scope.todaytimetemps){
                Message.warning("新卡升级时间有误，无法升级");
            }
            $(".cardTimeUpsateWords").show();
            $scope.getDiscountListValue = '';
        };
        // 提交升级
        $scope.successUpdate = function () {
            $scope.updateCardButtonFlag = true;
            $scope.addUpdate = function () {
                $scope.cardId = $scope.memberCardId;
                // 整理提交的数据
                return {
                    newCardId   : $scope.upsateTypeId          != undefined && $scope.upsateTypeId          != "" ? $scope.upsateTypeId            : null, //新卡卡种id
                    cardId      : $scope.cardId                != undefined && $scope.cardId                != "" ? $scope.cardId                  : null, //老卡卡id
                    payAmount   : $scope.upsateCardRecondMoney != undefined && $scope.upsateCardRecondMoney != "" ? $scope.upsateCardRecondMoney   : null, //售价
                    seller      : $scope.seller                != undefined && $scope.seller                != "" ? $scope.seller                  : null, //销售
                    card_number : $scope.upcardNumber          != undefined && $scope.upcardNumber          != "" ? $scope.upcardNumber            : null, //卡号
                    amountMoney : $scope.upsateCardMoney       != undefined && $scope.upsateCardMoney       != "" ? $scope.upsateCardMoney         : null, //价格
                    discount  :  $scope.discountId             != undefined && $scope.discountId            != "" ? $scope.discountId              : null, //折扣
                    dueTime   :  $scope.upsateCardEnd          != undefined && $scope.upsateCardEnd         != "" ? $scope.upsateCardEnd           : null,//卡续费的到期时间戳
                    _csrf_backend: $('#_csrf').val()
                }
            };
                // 保存数据前的验证
                if($scope.upsateCardRecondMoney < 0){
                    Message.warning("新卡价格小于当前卡，不能升级");
                    $scope.updateCardButtonFlag = false;
                    return false;
                }
                if($scope.upsateCardRecondMoney == 0 || $scope.upsateCardRecondMoney == ''){
                    Message.warning("新卡价格有误，无法升级");
                    $scope.updateCardButtonFlag = false;
                    return false;
                }
                if($scope.upsateCardEnd < $scope.todaytimetemps || $scope.upsateCardEnd == $scope.todaytimetemps){
                    Message.warning("新卡升级时间有误，无法升级");
                    $scope.updateCardButtonFlag = false;
                    return false;
                }
                if ($scope.seller == null || $scope.seller == "") {
                    Message.warning("请选择销售");
                    $scope.updateCardButtonFlag = false;
                    return false;
                }
                if ($scope.upsateTypeId == null || $scope.upsateTypeId == "") {
                    Message.warning("请选择新的会员卡");
                    $scope.updateCardButtonFlag = false;
                    return false;
                }
                if ($scope.upsateCardMoney == null || $scope.upsateCardMoney == "") {
                    Message.warning("卡价格有误，请重新选择");
                    $scope.updateCardButtonFlag = false;
                    return false;
                }
                if ($scope.upsateMoneyVal > $scope.upsateCardMoneyMax || $scope.upsateMoneyVal < $scope.upsateCardMoneyMin) {
                    var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
                    Message.warning(upsateCardMoneyWords);
                    return false;
                }
                // 发送数据
                $http({
                    url: '/member/new-card-update',
                    method: 'POST',
                    data: $.param($scope.addUpdate()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (data) {
                    if (data.data.status == "success") {
                        Message.success("升级成功");
                        // 数据初始化
                        $scope.upsateCardType        = "";
                        $scope.discount              = "";
                        $scope.upcardNumber          = "";
                        $scope.seller                = "";
                        $scope.upsateTypePrice       = "";
                        $scope.upsateCardRecondMoney = "";
                        $(".cardTimeUpsateWords").hide();
                        $("#memberCardUpgradeModal").modal("hide");
                        $("#myModals2").modal("hide");
                        $scope.getCardRewenRecondList();
                    } else {
                        $scope.updateCardButtonFlag = false;
                        Message.warning("升级失败");
                    }
                });
            }
        };

    // 判断升级的区间价格
    $(".upsateCardMoneySelect").blur(
        function (){
            $scope.upsateMoneyVal = $(".upsateCardMoneySelect").val();
            $scope.upsateCardRecondMoney = parseInt($scope.upsateCardMoney) - $scope.oldCardSellPrice;
            $('.newCardPricePay').text($scope.upsateCardRecondMoney.toFixed(2));
            if($(".discountSelect").val() == '' || $(".discountSelect").val() == undefined){
                if ($scope.upsateMoneyVal > $scope.upsateCardMoneyMax || $scope.upsateMoneyVal < $scope.upsateCardMoneyMin) {
                    var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
                    Message.warning(upsateCardMoneyWords);
                    $(".upsateCardMoneySelect").val("");
                    $('.newCardPricePay').text("");
                    $scope.upsateCardRecondMoney = '';
                }
            }
            else{
                $scope.discountMath();
                if ($scope.upsateMoneyVal > $scope.upsateCardMoneyMax || $scope.upsateMoneyVal < $scope.upsateCardMoneyMin) {
                    var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
                    Message.warning(upsateCardMoneyWords);
                    $(".upsateCardMoneySelect").val("");
                    $('.newCardPricePay').text("");
                    $scope.upsateCardRecondMoney = '';
                }
            }
        }
    );

    // 获取卡折扣
    $scope.getDiscount = function (id){
        $http.get('/member-card/new-card-category?newCardCategory=' + id).success(function (data){
                $scope.getDiscountList = data;
        });
    };

    $scope.residueCard = '不限';

    // 卡折扣剩余判断
    $scope.discountMath = function (){
        if($scope.getDiscountListValue != undefined && $scope.getDiscountListValue != ''){
            $scope.discountCardList = angular.fromJson($scope.getDiscountListValue);
            $scope.discountCardNum  = $scope.discountCardList.surplus;
            $scope.discountId       = $scope.discountCardList.id;
            if($(".upsateCardMoneySelect").val() != ''&& $scope.discountCardList.discount != '' && $scope.discountCardList.discount != undefined  && $scope.discountCardList.discount != null){
                if($scope.discountCardNum == ''|| $scope.discountCardNum == undefined || $scope.discountCardNum == '-1'){
                    $scope.residueCard               = '不限';
                }
                else{
                    $scope.residueCard = $scope.discountCardNum;
                }
                $scope.upsateCardRecondMoney = parseInt($(".upsateCardMoneySelect").val())*parseFloat($scope.discountCardList.discount)/10 - $scope.oldCardSellPrice;
                $('.newCardPricePay').text($scope.upsateCardRecondMoney.toFixed(2));
            }
        }
        else{
                $scope.discountId  = '';
                $scope.residueCard = '不限';
                $scope.upsateCardRecondMoney = parseInt($(".upsateCardMoneySelect").val()) - $scope.oldCardSellPrice;
                $('.newCardPricePay').text($scope.upsateCardRecondMoney);
        }
    };
    // 模态框关闭事件
    // 续费
    $('#myModals15').on('hide.bs.modal',function (){
        $scope.renewCardType = "";
        $scope.cardNumber    = "";
        $scope.cardNumber    = "";
        $scope.seller        = "";
    });
    // 升级使用期限默认关闭
    $(".cardTimeUpsateWords").hide();
    // 升级
    $('#memberCardUpgradeModal').on('hide.bs.modal',function (){
        $scope.upsateCardType        = "";
        $scope.upcardNumber          = "";
        $scope.seller                = "";
        $scope.upsateTypePrice       = "";
        $scope.upsateCardMoney       = "";
        $scope.upsateCardRecondMoney = '';
        $scope.upsateCardEnd         = '';
        $('.newCardPricePay').text("");
        $(".cardTimeUpsateWords").hide();
        $(".discountSelect").val("");
    });
    /**** 升级结束 ****/



    $scope.showDetails = function () {
        $("#showDetails").css("display","block")
    };
    $scope.noneDetails = function () {
        $("#showDetails").css("display","none")
    };
    $scope.membershipCardDetails = function (id) {
        $("#membershipCardDetails").show();
        if(id != 'xixi'){
            $http({method:'get',url:'/member/member-card-details?memberCardId='+id}).then(function (data) {
                    $scope.membershipCardDetailsData = data.data.data[0];
                    if($scope.membershipCardDetailsData.cardCategory.cardTime.day){
                        var qqqq = $scope.membershipCardDetailsData.cardCategory.cardTime.day
                        $scope.cardTimeDay = JSON.parse(qqqq);
                        console.log($scope.cardTimeDay)
                        // for(var key in $scope.cardTimeDay.day){
                        //     if ($scope.cardTimeDay.day.hasOwnProperty(key)) {
                        //         var element = $scope.cardTimeDay.day[key];
                        //         console.log($("#table td").eq(element+1));
                        //         $("#table td").eq(element+1).addClass("backgroundColorRed")
                        //     }
                        // }
                    }

                    if($scope.membershipCardDetailsData.cardCategory.cardTime.week){
                        var qqqq = $scope.membershipCardDetailsData.cardCategory.cardTime.week
                        $scope.cardTimeDay1 = JSON.parse(qqqq);
                    }

                    if ($scope.membershipCardDetailsData.leave_total_days == null){
                        var awww = $scope.membershipCardDetailsData.leave_long_limit
                        $scope.leaveLongIimit = JSON.parse(awww);
                    }
                    console.log(data.data.data);
                },function (error) {
                console.log(error)
                Message.error("系统错误请联系管理人员")
            });
        }
    };
    $scope.closeAndBreak = function () {
        // 数据初始化
        $scope.clearBina = '';
        $scope.clearMoney = '';
        $scope.clearDays = '';
        $scope.clearTime = '';
        $scope.paymentItems = [];
        $scope.cardInfo = [];
    };


    $scope.setMember = function () {
        $scope.loadMember = true;
        $http.get('/site/set-member').then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
            } else {
                Message.warning(result.data.data)
            }
            $scope.loadMember = false;
            $scope.init();
        });
    };


    /**处理搜索数据***/

    // 会员状态select选择时不同情况的判断
    $scope.cardStatusPost = function (){
        if($scope.allStates == "freeze"){
            $scope.statusFreeze = "2";
            $scope.cardStatusEd = "";
            $scope.statusVacate = "";
        }
        if($scope.allStates == "vacate"){
            $scope.statusVacate = "1";
            $scope.cardStatusEd = "";
            $scope.statusFreeze = "";
        }
        if($scope.allStates != "freeze"&&$scope.allStates != "vacate") {
            $scope.statusVacate = "";
            $scope.statusFreeze = "";
            $scope.cardStatusEd = $scope.allStates;
        }
    };

    // 数据整理
    $scope.searchCardData = function () {
        $scope.formatDate();
        return {
            keywords: $scope.keywords != undefined ? $scope.keywords : null,
            sex: $scope.sex != undefined ? $scope.sex : null,
            venueId: $scope.venueId != undefined ? $scope.venueId : null,
            type: $scope.type != undefined ? $scope.type : null,
            startTime: $scope.startTime != undefined ? $scope.startTime : null,
            endTime: $scope.endTime != undefined ? $scope.endTime : null,
            sortType: $scope.sortType != undefined ? $scope.sortType : null,
            sortName: $scope.sort != undefined ? $scope.sort : null,
            freeze: $scope.statusFreeze,
            vacate: $scope.statusVacate,
            free  :$scope.trialClass != undefined && $scope.trialClass != undefined ? $scope.trialClass : null,//体验课
            sellId: $scope.selectSale != undefined && $scope.selectSale != undefined ? $scope.selectSale : null,
            privates: $scope.privatesData != undefined && $scope.privatesData != undefined ? $scope.privatesData : null,
            status: $scope.cardStatusEd
        }
    };
    // 生日提醒
    $scope.birthdayRemind = function(){
        $http({
            url:'/user/birthday-reminder',
            method:'GET',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
               if(result.data.status == "success"){
                   Message.success(result.data.message);
               }else{
                   Message.warning(result.data.message);
               }
        })
    }
    $scope.initPath = function () {
        $scope.searchParams = $scope.searchCardData();
        $scope.pageInitUrl = '/user/member-info?' + $.param($scope.searchParams);
    };
    /**搜索方法***/
    $scope.searchCard = function () {
        // $scope.searchCarding = true;
        $scope.initPath();
        $scope.getData();
    };
    /******Enter键搜索*******/
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $scope.searchCard();
        }
    };
    /******点击会员卡选项触发事件*******/
    $scope.getMemCard = function (id) {
        $scope.getCardData(id);
    };


    /******获取会员卡信息*******/
    $scope.getCardData = function () {
        $http.get($scope.MAIN.API.memberCardPath).success(function (response) {
            if (response.item == "" || response.item == undefined || response.item.length <= 0) {
                $scope.items = response.item;
                $scope.memberCardPages = response.pages;
                $scope.memberCardNoDataShow = true;
                $scope.getData()
            } else {
                $scope.items = response.item;
                $scope.memberCardPages = response.pages;
                $scope.memberCardNoDataShow = false;
            }
        });
    };
    $scope.replaceMemberCard = function (urlPages) {
        $scope.MAIN.API.memberPath = urlPages;
        $scope.getCardData();
    };
    /**
     *后台会员管理 - 会员卡 - 将时间戳转化成固定时间
     * @author houKaiXin <houKaiXin@itsports.club>
     * @create 2017/5/12
     */
    $scope.getMyDate = function (str) {
        str = parseInt(str);
        if (str != "" || str != null) {
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth() + 1;
            oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
            var oDay = oDate.getDate();
            oDay = oDay >= 10 ? oDay : '0' + oDay;
            var theDate = oYear + "-" + oMonth + "-" + oDay;
        } else {
            theDate = "";
        }
        return theDate
    };


    //获取当前日期时间到分
    $scope.getDateTime = function () {
        var oDate = new Date();
        var oYear = oDate.getFullYear();
        var oMonth = oDate.getMonth() + 1;
        oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
        var oDay = oDate.getDate();
        oDay = oDay >= 10 ? oDay : '0' + oDay;
        var oHours = oDate.getHours();
        oHours = oHours >= 10 ? oHours : '0' + oHours;
        var oMinutes = oDate.getMinutes();
        oMinutes = oMinutes >= 10 ? oMinutes : '0' + oMinutes;
        var oSeconds = oDate.getSeconds();
        oSeconds = oSeconds >= 10 ? oSeconds : '0' + oSeconds;
        var theDate = oYear + "-" + oMonth + "-" + oDay + " " + oHours + ':' + oMinutes + ":" + oSeconds;

        return theDate;
    }

    /*订金*/
    $scope.deposit = function(id){
        $scope.depositButtonFlag = false;
        $("#subscriptionDate").val('')
        $scope.depositMoney   = "";
        $scope.depositPayMode = "";
        $scope.depositToRoll  = '';
        $scope.depositMemberId = id;
        $('#deposit').modal('show');
    }
    //提交订金
    $scope.depositSubmit = function () {
        var leaveDateRange = $("#subscriptionDate").val()
        var startTime = leaveDateRange.slice(0,10)
        var endTime = leaveDateRange.slice(13);
        var data = {
            memberId:$scope.depositMemberId,
            price:$scope.depositMoney,
            voucher:$scope.depositToRoll,
            startTime:startTime,
            endTime:endTime,
            payMode:$scope.depositPayMode,
            _csrf_backend: $('#_csrf').val(),
        }
        // console.log(data)
        if(data.price == '' || data.price == undefined){
            Message.warning("请输入金额")
            return
        }
        if($scope.depositToRoll != null && $scope.depositToRoll != ''){
            if($scope.depositToRoll < $scope.depositMoney){
                Message.warning("抵券金额不能小于定金金额")
                return
            }
        }
        if(data.startTime == '' || data.endTime == undefined){
            Message.warning("请选择时间")
            return
        }
        if(parseInt($scope.depositMoney) < parseInt($scope.depositToRoll)){
            Sweety.remove({
                url : "/sell-card/confirm-deposit",
                http : $http,
                title : '确定要提交吗?',
                text : '定金金额小于抵劵金额',
                buttonColor : '#27c24c',
                confirmButtonText : '确定',
                confirmButton : '确定',
                data : {
                    action: 'unbind'
                }
            }, function () {
                $http({method:'post',url:'/potential-members/set-member-deposit-form',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
                    if(data.data.status == "success"){
                        $("#deposit").modal("hide");
                        $scope.getData();
                    }
                },function (error) {
                    console.log(error)
                    Message.error("系统错误请联系工作人员")
                })
            },function(){

            },true,true);

        }else{
            Sweety.remove({
                url : "/sell-card/confirm-deposit",
                http : $http,
                title : '确定要提交吗?',
                text : '请确认输入的金额哦',
                buttonColor : '#27c24c',
                confirmButtonText : '确定',
                confirmButton : '确定',
                data : {
                    action: 'unbind'
                }
            }, function () {
                $http({method:'post',url:'/potential-members/set-member-deposit-form',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
                    // console.log(data)
                    if(data.data.status == "success"){
                        $("#deposit").modal("hide");
                        $scope.getData();
                     }
                },function (error) {
                    console.log(error);
                    Message.error("系统错误请联系工作人员")
                })
            },function(){

            },true,true);
        }
        if(parseInt($scope.depositMoney) == parseInt($scope.depositToRoll)){
            Sweety.remove({
                url : "/sell-card/confirm-deposit",
                http : $http,
                title : '确定要提交吗?',
                text : '定金金额与抵劵金额相等吗?',
                buttonColor : '#27c24c',
                confirmButtonText : '确定',
                confirmButton : '确定',
                data : {
                    action: 'unbind'
                }
            }, function () {
                $http({method:'post',url:'/potential-members/set-member-deposit-form',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
                    // console.log(data)
                    if(data.data.status == "success"){
                        $("#deposit").modal("hide");
                        $scope.getData();
                     }
                },function (error) {
                    console.log(error)
                    Message.error("系统错误请联系工作人员")
                })
            },function(){

            },true,true);
        }
    }

    // console.log('获取当前时间',$scope.getDateTime())
    /**
     *后台会员管理 - 会员卡 - 查询所有顾问
     * @author houKaiXin <houKaiXin@itsports.club>
     * @create 2017/4/21
     */
    $scope.updateMemCard = function (id, invalidTime, memCardId, memId) {
        //初始化数据
        $scope.adviser = id;
        $scope.memId = memId;
        $scope.memCardId = memCardId;
        var MyDate = $scope.getMyDate(invalidTime * 1000);
        $scope.myDate = MyDate;

        //默认值赋值
        $("#datetimeEnd").val(MyDate);
        $http.get("/user/get-adviser").success(function (response) {
            $scope.allAdviser = response;
        });
    };
    //接受数据表单数据 执行修改
    $scope.refer = function () {
        //发送数据请求指令
        $scope.cardData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                memCardId: $scope.memCardId,
                memId: $scope.memId,
                invalidDate: $("#datetimeEnd").val(),
                adviserId: $scope.adviser
            }
        };
        var sendData = $scope.cardData();
        if (sendData.invalidDate == null || sendData.invalidDate == "") {
            Message.warning("失效日期不能为空");
            return false;
        }
        if (sendData.adviserId == "" || sendData.adviserId == null) {
            Message.warning("销售顾问不能为空");
            return false;
        }
        //发送客户端数据
        $http({
            url: "/user/update-card",
            method: 'POST',
            data: $.param($scope.cardData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            if (result.status == "success") {
                Message.success(result.data);
                $("#myModals6").css("display", "none");
                $scope.getData();       //更新主页信息
                $scope.getCardData();   //更新分页信息
            } else {
                Message.warning(result.data);
            }
        });

    };

    //点击请假按钮，传送数据
    $scope.leaveBut = function (id) {
        $scope.leaveTypeChecked = '';
        $scope.card_id          = '';
        $scope.startLeaveDay    = '';
        $scope.endLeaveDay    = '';
        $('#leaveCause').val('');
        $scope.endLeaveFlag = false;
        $scope.leaveData1 = false;
        $scope.leaveData2 = false;
        $scope.card_id = '';
        $scope.leaveMemberId = parseInt(id);

        $http.get("/user/member-details-card?MemberId=" + $scope.leaveMemberId).then(function (result) {
            $scope.MemberData = result.data;
            // console.log('22222',$scope.MemberData);
            if ($scope.MemberData.leaveRecord == null) {
                $('#myModalsLeave').modal('show');
                //会员卡失效时间
                $scope.invalidTime = parseInt($scope.MemberData.memberCard[0].invalid_time);
                //会员卡生效时间
                $scope.activeTime = parseInt($scope.MemberData.memberCard[0].active_time);
                var now = Math.ceil(new Date().getTime() / 1000);
                $scope.allDays = Math.ceil(($scope.invalidTime - $scope.activeTime) / (60 * 60 * 24));
                $scope.limitedDays = Math.ceil(($scope.invalidTime - now) / (60 * 60 * 24));

                //根据返回的数字对应不同的状态
                var len = $scope.MemberData.memberCard.length;
                for (var i = 0; i < len; i++) {
                    var memberState = parseInt($scope.MemberData.memberCard[i].status)
                    switch (memberState) {
                        case 1:
                            $scope.memberFlag = '正常';
                            break;
                        case 2:
                            $scope.memberFlag = '禁用';
                            break;
                        case 3:
                            $scope.memberFlag = '冻结';
                            break;
                        case 4:
                            $scope.memberFlag = '未激活';
                            break;
                    }
                }
            } else {
                Message.warning('此卡处于异常状态或请假状态，不能请假！');
            }
        });
        //获取所有会员卡
        $http.get('/user/get-member-card?memberId=' + $scope.leaveMemberId).then(function (result) {
            $scope.cards = result.data;
            // console.log('所有的会员卡',$scope.cards);

        });

    }
    $scope.selectLeaveDaysOne = function (ind) {
        // console.log('请假的数据',ind)
        $scope.leaveIndex = ind;
        $scope.leave1 = ind;
        // console.log($scope.leave1)
        if (ind == '') {
            $scope.startLeaveDay = "";
            $scope.endLeaveDay = "";
            return;
        }
        if ($scope.leaveStyle.leave_days_times != null) {
            var leave = $scope.leaveStyle.leave_days_times[ind];
            $scope.selectLeaveDays = leave[0];
            // console.log(leave[0])
            // console.log('11111',$scope.leaveStyle.leave_days_times[ind])
            if ($scope.startLeaveDate11 != null && $scope.startLeaveDate11 != '' && $scope.startLeaveDate11 != undefined && $scope.leaveTypeChecked == '1') {
                var endTime = parseInt($scope.startLeaveDate11) + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 -2000;
                $scope.endLeaveDay = $scope.getMyDate(endTime);
                $scope.endLeaveFlag = true;
            }

        }

        if($scope.leaveStyle.leave_least_days != null && $scope.leaveStyle.leave_least_days != ' ' && $scope.leaveTypeChecked == '1'){
            var days = $scope.leaveStyle.leave_least_days;
            var allDays = $scope.leaveStyle.leave_total_days;
            if(parseInt(days) <= parseInt(allDays)){
                $scope.selectLeaveDays  = parseInt(days);
                if($scope.startLeaveDate11 != null && $scope.startLeaveDate11 != '' && $scope.startLeaveDate11 != undefined ){
                    var endTime =  parseInt($scope.startLeaveDate11) + $scope.selectLeaveDays*24*60*60*1000-1000;
                    $scope.endLeaveDay =  $scope.getMyDate(endTime);
                    $scope.endLeaveFlag = true;
                }
            }else{
                Message.warning('请假天数小于可请假天数，请走特殊请假功能');
                return;
            }
        }
    }

    //根据不同的时间算出天数
    $scope.getDateDays = function(start,end){
        if(start != '' &&  end !='' && $scope.leaveTypeChecked =='2'){
            var startTimes = new Date(start + ' '+'00:00:00').getTime();
            var endTimes   = new Date(end + ' '+'23:59:59').getTime();
            // console.log(startTimes+'startTimes',endTimes+'endTimes');
            var daysTime = parseInt(endTimes) - parseInt(startTimes);
            var days = Math.ceil(daysTime/(24*60*60*1000));
            $scope.leaveDays123 = days;
            // console.log(days)
        }
    }

    //选择请假类型
    $scope.selectLeaveType = function(val){
        if(val == '1'){
            $scope.endLeaveFlag = true;
        }else{
            $scope.endLeaveFlag = false;
            $scope.getDateDays($scope.startLeaveDay,$scope.endLeaveDay);
        }
    }

    $scope.selectOneMemberCard = function (cardId) {
        // console.log('所选会员卡的id',cardId);
        $scope.leave1 = "";
        $scope.LeaveDays = '';
        $scope.leaveTotalDays = '';
        $scope.leaveLeastDays = '';
        $scope.endLeaveFlag = false;
        $scope.startLeaveDay = '';
        $scope.endLeaveDay = '';
        $scope.selectLeaveDays = '';
        $scope.endLeaveDateTime = '';
        $scope.leaveTotalDays = '';
        $scope.LeaveDays = '';
        $scope.leaveData1 = false;
        if (cardId != '') {
            $scope.morenFlag = true;
            $http.get('/user/get-leave-limit?memberCardId=' + cardId).then(function (response) {
                // console.log('获取请假限制',response.data)
                $scope.leaveStyle = response.data;
                // console.log(response.data.invalid_time)
                if (response.data.invalid_time != undefined) {
                    $scope.memberCardEndTime = response.data.invalid_time;
                }

                if ($scope.leaveStyle.leave_days_times != null) {
                    $scope.LeaveDays = response.data.leave_days_times;
                    $scope.leaveLimitStatus = 2;
                    $scope.leaveData1 = true;
                    $scope.leaveData2 = false;

                }
                if ($scope.leaveStyle.leave_least_days != null && $scope.leaveStyle.leave_total_days != null) {
                    $scope.leaveLeastDays = response.data.leave_least_days;
                    $scope.leaveTotalDays = response.data.leave_total_days
                    $scope.leaveLimitStatus = 1;
                    $scope.leaveData1 = true;
                    $scope.leaveData2 = true;
                }
                if ($scope.leaveStyle.leave_days_times == null && $scope.leaveStyle.leave_least_days == null && response.data.leave_total_days == null) {
                    $scope.leaveLimitStatus = 3;
                    $scope.leaveData2 = true;
                    $scope.leaveData1 = false;
                }
            })
        } else {
            $scope.leaveData1 = false;
            $scope.leaveData2 = false;
        }

    }

    //请假结束时间变化
    $scope.endLeaveDate = function (endLeaveDay) {
        $scope.getDateDays($scope.startLeaveDay,$scope.endLeaveDay);
        // console.log($scope.leaveStyle)
        if ($scope.leaveStyle == undefined) {
            Message.warning('请先选择会员卡！');
            return;
        } else {
            // console.log('请假结束日期',endLeaveDay)
            var endTime = new Date(endLeaveDay).getTime() / 1000;
            // console.log('12121',endTime)
            // console.log('5445',$scope.memberCardEndTime)
            if (endTime > parseInt($scope.memberCardEndTime)) {
                Message.warning('请假的结束日期不能大于会员卡的到期日期！');
                return;
            }
        }

    }


    //根据请假开始时间获取请假到期时间
    $scope.startLeaveDate = function (starDate) {
        $scope.getDateDays($scope.startLeaveDay,$scope.endLeaveDay);
        // console.log('请假开始时间',starDate);
        var starLeaveTime = new Date(starDate + ' ' + '00:00:01').getTime();
        $scope.startLeaveDate11 = parseInt(starLeaveTime);
        $scope.startLeaveDateTime = starDate + ' ' + '00:00:01';
        if ($scope.selectLeaveDays != undefined && $scope.selectLeaveDays != '' && $scope.selectLeaveDays != null) {
            // console.log( starDate + '---'+$scope.selectLeaveDays);
            var endTime = starLeaveTime + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 -2000;
            // console.log(starLeaveTime)
            // console.log(endTime/1000)
            // console.log(parseInt($scope.memberCardEndTime))
            if ((endTime / 1000) > parseInt($scope.memberCardEndTime)) {
                Message.warning('请假的结束日期不能大于会员卡的到期日期！');
                return;
            }
            $scope.endLeaveDay = $scope.getMyDate(endTime);

            $scope.endLeaveFlag = true;
        }

    }

    //实现请假功能
    //    会员卡状态：1、正常 2、冻结 3、过期 4、未激活
    $scope.submitLeave = function () {
        // console.log($scope.startLeaveDateTime)
        // console.log($scope.endLeaveDateTime)
        // var leaveDateRange = $('#reservationLeave').val();
        // var startTime = leaveDateRange.slice(0,10)
        // var endTime = leaveDateRange.slice(13);
        // //请假开始时间为秒
        // var startTimeS = startTime.replace(/-/g,'/');
        // var time = new Date(startTimeS).getTime();
        // time = Math.ceil(parseInt(time+21*24*3600)/1000);
        // var now = Math.ceil(new Date().getTime()/1000);
        // startTime = startTime +' '+'00:00';
        // endTime = endTime + ' '+ '23:59';
        $scope.endLeaveDateTime = $scope.endLeaveDay + ' ' + '23:59:59';
        var leaveAllDay = Math.ceil((new Date($scope.endLeaveDateTime).getTime() - new Date($scope.startLeaveDateTime).getTime()) / (24 * 60 * 60 * 1000))
        var leaveData = {
            leaveType    :$scope.leaveTypeChecked != undefined && $scope.leaveTypeChecked != '' ? $scope.leaveTypeChecked : null,
            leavePersonId: parseInt($scope.leaveMemberId),
            leaveReason: $('#leaveCause').val(),
            _csrf_backend: $('#_csrf').val(),
            leaveStartTime: $scope.startLeaveDateTime,
            leaveEndTime: $scope.endLeaveDateTime,
            leaveTotalDays: leaveAllDay,
            leaveArrayIndex: $scope.leaveIndex != undefined && $scope.leaveIndex != '' ? $scope.leaveIndex : null,
            leaveLimitStatus: $scope.leaveLimitStatus,
            memberCardId: $('#selectCard').val()
        }
        // console.log(leaveData)
        if ($scope.leaveTypeChecked == '' || $scope.leaveTypeChecked == null || $scope.leaveTypeChecked == undefined) {
            Message.warning('请选择请假类型');
            return;
        }

        if (leaveData.memberCardId == '' || leaveData.memberCardId == null || leaveData.memberCardId == undefined) {
            Message.warning('请选择您的会员卡');
            return;
        }
        if ($scope.startLeaveDay == '' || $scope.startLeaveDay == undefined || $scope.startLeaveDay == '') {
            Message.warning('请选择请假开始时间');
            return;
        }
        if ($scope.endLeaveDay == '' || $scope.endLeaveDay == undefined || $scope.endLeaveDay == '') {
            Message.warning('请选择请假结束时间');
            return;
        }
        $scope.leavePost = function (){
            $scope.laddaButton = true;
            $http({
                url: '/check-card/leave-record',
                method: 'POST',
                data: $.param(leaveData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (result) {
                // console.log('请假返回数据',result)
                if (result.data.status == "success") {
                    $('#myModalsLeave').modal('hide');
                    Message.success(result.data);
                    $scope.getData();
                    $scope.getLeaveData();
                    $scope.laddaButton = false;
                } else {
                    Message.warning(result.data.message);
                    $scope.laddaButton = false;
                }
            });
        };
        $scope.leavePost();
    };
    $scope.privateCoachs = function (id) {
        $scope.coachId = id;
    }
    //私课主页
    $scope.privateLessonBuy = function (id,object) {
        $scope.privateLessonBuyButtonFlag = false;
        // $scope.dataCompleteBuy.renewalDate = $scope.getDateTime();
        var now = new Date().getTime();
        $scope.currentTime = now/1000;
        $scope.paymentTerm = '';
        $scope.giftStatus = '';
        $scope.PayMoney    = '';
        $scope.blendMoney = 0;
        $scope.trues = false;
        $scope.privateLessonBuyMemberID = id
        $scope.addSellSourceId = "";
        $scope.dataCompleteBuy.paymentMethod = '';
        $scope.dataCompleteBuy.distributionChannel = '';
        $scope.dataCompleteBuy.sellingPrivateEducation = '';
        $scope.dataCompleteBuy.renewalDate = '';
        $scope.dataCompleteBuy.numberOfCourses = '';
        $scope.buyMemberDetail = object;
        $scope.voucher123      = object.voucher;//抵劵金额
        $scope.subscription123  = object.price;//订金

        $http.get('/potential-members/get-member-deposit-one?memberId=' +id).then(function(response){
            if(response.data != null){
                $scope.orderid123 = response.data.id;
            }
        })
        // console.log(object);
        $('#registerDate').val('');
        $http({
            method: "get",
            url: "/user/member-details-card?MemberId=" + id,
        }).then(function (data) {
            $scope.aboutClassData = data.data;
        }, function (error) {
            Message.error("系统错误请联系工作人员")
        })

        $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
            $scope.privateCoach = data.data
        }, function (error) {
            // console.log(error)
            Message.error("系统错误请联系工作人员")
        })

        $('#privateBuyModal').modal('show');
        $scope.monthUpNums = '';
    }

    //收款方式下拉
    $scope.gatheringWay = function(value){
        $scope.voucher123    = $scope.buyMemberDetail.voucher;//抵劵金额
        $scope.subscription123  = $scope.buyMemberDetail.price;//订金
        $scope.AllMoney123 = $scope.blendMoney;//总金额
        // console.log(value);
        if( parseInt(value) == 2){
            if($scope.buyMemberDetail.voucher != null && $scope.buyMemberDetail.start_time  <= $scope.currentTime && $scope.currentTime <= $scope.buyMemberDetail.end_time ){
                $scope.PayMoney = $scope.AllMoney123 - $scope.voucher123;
                // console.log('有效',$scope.PayMoney);
            }else {
                $scope.PayMoney = $scope.AllMoney123 - $scope.subscription123;
                // console.log('wu效',$scope.PayMoney);
                $scope.voucher123 = 0;
            }
        }else{
            $scope.PayMoney = $scope.AllMoney123;
            $scope.voucher123 = 0 ;
            $scope.subscription123 = 0;
            // console.log('全款',$scope.PayMoney);
        }
    }

    //总金额发生变化时
    $scope.allMoneyChange = function(money){
        // console.log('money',money)
        $scope.gatheringWay($scope.paymentTerm);
    }


    //购买私教课
    $scope.buyPrivateCourse = function () {

        $scope.paymentTerm = '';
        $scope.dataCompleteBuy.numberOfCourses = '';
        $scope.blendMoney = '';
        $http({
            method: "GET",
            url: '/private-teach/get-private-teach-all?memberId=' + $scope.privateLessonBuyMemberID
        }).then(function (data) {
            // $scope.dataCompleteBuy = '';
            // $scope.blendMoney = 0;
            if (data.data.data.alone != 0) {
                $scope.aloneData = data.data.data.alone;
                $scope.manyData = data.data.data.many;
            } else {
                $scope.shopDetailShow = true;
            }
        }, function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员")
        })

        // $('#privateBuyModal').modal('hide');
        $('#selectPrivateCourseModal').modal('show');
    }
    //改变select的颜色
    function selectColor() {
        if ($('#selectColor').val() == 0) {
            $('#selectColor').css('color', '#999')
        } else {
            $('#selectColor').css('color', '#000')
        }
        if ($('#marketingChannel').val() == 0) {
            $('#marketingChannel').css('color', '#999')
        } else {
            $('#marketingChannel').css('color', '#000')
        }
    }

    selectColor();
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".typeSelect").select2({
            width: "88%",
            placeholder: "请选择"
        });
        $(".sellerSelect").select2({
            width: "88%",
            placeholder: "请选择"
        });
        $(".telPhoneNumber").select2({
            width: 250
        });

    })

    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $scope.blendMoney = 0;
    //选择课程 单节
    $scope.selectPrivateCourseSingle = function (id, pic, name, chargeType, array, unitPrice, newMember,month_up_num) {
        $scope.trues = true;
        $scope.blendId = id;
        if(pic == ''){
            $scope.blendPic = '/plugins/user/images/noPic.png';
        }else {
            $scope.blendPic = pic;
        }
        $scope.blendName = name;
        $scope.chargeType = chargeType;
        //如果数组为空 单节原价
        if (array == '') {
            $scope.arrayUnitPrice = array;
            $scope.blendSingle = unitPrice;
        }
        if (array != '') {
            $scope.arrayUnitPrice = array;
            $scope.newMember = newMember;
        }
        if(month_up_num != '' && month_up_num != null && month_up_num != undefined){
            $scope.monthUpNum = month_up_num
        }
        $('#privateBuyModal').modal('show');
        $('#selectPrivateCourseModal').modal('hide');
        $('#registerDate').val($scope.getDateTime());
    }
    //选择课程套餐
    $scope.selectPrivateCourseServe = function (id, pic, name, chargeType, total) {
        $scope.trues = true;
        $scope.blendId = id;
        if(pic == ''){
            $scope.blendPic = '/plugins/user/images/noPic.png';
        }else {
            $scope.blendPic = pic;
        }
        $scope.blendName = name;
        $scope.chargeType = chargeType;
        if (chargeType == 'many') {
            $scope.blendServe = total;
        }
        $('#privateBuyModal').modal('show');
        $('#selectPrivateCourseModal').modal('hide');
        $('#registerDate').val($scope.getDateTime());
    }

    //新增销售来源
    $scope.addSellSource = function () {
        $scope.customSalesChannels = '';
        $('#sellSource1').modal('show');
    }
    //新增销售来源提交
    $scope.confirmAdd = function (data) {
        if ($scope.customSalesChannels == "" || $scope.customSalesChannels == null || $scope.customSalesChannels == undefined) {
            Message.warning("请输入自定义销售渠道");
        } else {
            $("#sellSource1").modal('hide');
            var dataJson = {
                source: data,
                scenario: "source",
                configType:'charge',
                _csrf_backend: $('#_csrf').val(),
            }
            $http({method: 'post', url: '/potential-members/set-source', data: dataJson}).then(function (data) {
                if (data.data.status == "error") {
                    Message.error("系统错误请联系工作人员");
                } else {
                    Message.success("添加完成");
                    // console.log(data.data.id)
                    $scope.addSellSourceId = data.data.id;
                    $scope.getSellSources();
                    $scope.selectSalesSources();
                    // dataCompleteBuy.distributionChannel
                    // $scope.dataCompleteBuy.distributionChannel = data.data.value;
                }
            });
        }
    }

    $scope.selectSalesSources = function (value) {
        $scope.SalesSources = value
        $scope.sellSourceId = value;

    }

    //获取销售来源下拉
    $scope.getSellSources = function(){
        $http({
            url: '/potential-members/get-source?configType=charge',
            method: 'get'
        }).then(function (data) {
            $scope.memberSearchData = data.data;
            // console.log('销售来源',$scope.memberSearchData);
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.getSellSources();

    //删除销售来源
    $scope.deleteTheSource = function () {
        var id = $('#marketingChannel').find('option:selected').data('module');
        // console.log('删除的id',id);
        if (id != undefined) {
            Sweety.remove({
                url: "/potential-members/delete-config?configId=" + id,
                http: $http,
                title: '确定要删除吗?',
                text: '删除后信息无法恢复',
                confirmButtonText: '确定',
                data: {
                    action: 'unbind'
                }
            }, function () {
                $scope.getSellSources();
                $scope.dataCompleteBuy.distributionChannel = "";
            });
        } else {
            Message.warning('请选择需要删除的销售来源!');
            return;
        }
    };

    //计算课程总价
    $scope.courseQuantity = function (value) {

        if($scope.monthUpNum != '' && $scope.monthUpNum != undefined && $scope.monthUpNum != null){
            $scope.monthUpNums = Math.ceil(value / $scope.monthUpNum)
        }

        if (value == 0) {
            Message.warning("不能输入0节")
            $scope.dataCompleteBuy.numberOfCourses = '';
            return;
        }
        if (value == '' || value == undefined || value == null) {
            $scope.blendMoney = 0;
        }
        if ($scope.chargeType == 'many') {
            $scope.blendMoney = value * $scope.blendServe;
        }
        if ($scope.chargeType == 'alone') {
            if ($scope.arrayUnitPrice == '') {
                $scope.blendMoney = $scope.blendSingle * value;
            } else {
                var lengthArray = $scope.arrayUnitPrice.length
                for (var key in $scope.arrayUnitPrice) {
                    // console.log('key',key);
                    if ($scope.arrayUnitPrice.hasOwnProperty(key)) {
                        var element = $scope.arrayUnitPrice[key];
                        // element.intervalEnd  /结束节数
                        //开始节数 element.intervalStart
                        //pos机价钱 element.posPrice
                        //优惠单价 element.unitPrice
                        // console.log(element.intervalEnd)
                        // console.log(element.intervalStart)
                        // console.log(element.posPrice)
                        // console.log(element.unitPrice)

                        if (element.intervalEnd >= value && value >= element.intervalStart && $scope.newMember == true) {
                            $scope.blendMoney = value * element.posPrice
                            // console.log($scope.blendMoney)
                            // console.log(1)
                            continue
                        }
                        if (element.intervalEnd >= value && value >= element.intervalStart && $scope.newMember == false) {
                            $scope.blendMoney = value * element.unitPrice
                            // console.log($scope.blendMoney)
                            // console.log(2)
                            continue
                        }
                        if (element.intervalEnd <= value && value >= element.intervalStart && $scope.newMember == true) {
                            $scope.blendMoney = value * element.posPrice
                            // console.log($scope.blendMoney)
                            // console.log(3)
                            continue
                        }
                        if (element.intervalEnd < value && value > element.intervalStart && $scope.newMember == false && (lengthArray - 1) == key) {
                            $scope.blendMoney = value * element.unitPrice
                            // console.log($scope.blendMoney)
                            // console.log(4)
                            continue
                        }
                    }
                }
            }
        }

        $scope.paymentTerm = '';

    }
    $('#marketingChannel').on('change', function () {
        selectColor();
    })
    $('#selectColor').on('change', function () {
        selectColor();
    })
    //点击返回按钮跳转购买私课
    $scope.backBuyPrivate = function () {
        $('#privateBuyModal').modal('show');
        $('#selectPrivateCourseModal').modal('hide');
        $scope.dataCompleteBuy = '';
    }
    //获取选择信息
    $scope.dataCompleteBuy = {
        numberOfCourses: '',
        renewalDate: '',
        id: '',
        distributionChannel: '',
        paymentMethod: ''
    }
    //完成购买
    $scope.completeBuy = function () {
        $scope.privateLessonBuyButtonFlag = false;
        if(!$scope.privateLessonBuyButtonFlag){
            if($scope.paymentTerm == ''){
                Message.warning("请选择付款方式")
                return;
            }
            if($scope.PayMoney == ''){
                $scope.PayMoney = $scope.blendMoney;
                $scope.voucher123 = 0;
                $scope.subscription123 = 0;
            }
            if($scope.paymentTerm == '1'){
                $scope.PayMoney = $scope.blendMoney;
            }
            var data123 = {
                memberId: $scope.id, //会员id
                chargeId: $scope.blendId, //私教课程id
                chargeType: $scope.chargeType, //课程区别
                nodeNumber: $scope.dataCompleteBuy.numberOfCourses,//课程节数
                coachId: $scope.dataCompleteBuy.sellingPrivateEducation,//销售私教id
                saleType: $scope.dataCompleteBuy.distributionChannel,//销售渠道
                renewTime: $('#registerDate').val(),//缴费日期
                payMethod: $scope.dataCompleteBuy.paymentMethod, //支付方式
                giftStatus    : $scope.giftStatus, //领取赠品
                totalPrice: $scope.blendMoney, //总价钱
                netPrice   :$scope.PayMoney,//实收金额
                cashCoupon :$scope.voucher123,//抵劵
                deposit    :$scope.subscription123,//订金
                endTime:$scope.monthUpNums,
                _csrf_backend: $('#_csrf').val()
            }
            if ($scope.blendId == '' || $scope.blendId == undefined || $scope.blendId == null) {
                Message.warning("请选择私教产品")
                return
            }
            if ($scope.dataCompleteBuy.numberOfCourses == '' || $scope.dataCompleteBuy.numberOfCourses == undefined || $scope.dataCompleteBuy.numberOfCourses == null) {
                Message.warning("请选择课程节数")
                return
            }
            if ($scope.dataCompleteBuy.distributionChannel == '' || $scope.dataCompleteBuy.distributionChannel == null || $scope.dataCompleteBuy.distributionChannel == undefined) {
                Message.warning("请选择私教渠道");
                return
            }
            if ($('#registerDate').val() == '' || $('#registerDate').val() == undefined) {
                Message.warning("请选择缴费日期");
                return;
            }
            if ($scope.giftStatus == '' || $scope.giftStatus == undefined) {
                Message.warning("请选择是否领取过赠品")
                return
            }
            if ($scope.dataCompleteBuy.paymentMethod == '' || $scope.dataCompleteBuy.paymentMethod == undefined) {
                Message.warning("请选择支付方式")
                return
            }
            if ($scope.dataCompleteBuy.sellingPrivateEducation == '' || $scope.dataCompleteBuy.sellingPrivateEducation == undefined) {
                Message.warning("请选择销售教练")
                return
            }
            $scope.privateLessonBuyButtonFlag = true;
            var url = '/user/save-member-charge'
            $http({
                method: 'POST',
                url: url,
                data: $.param(data123),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).then(function (data) {
                if (data.data.status === "success") {
                    Message.success(data.data.data)
                    $('#privateBuyModal').modal('hide');
                    /*if(parseInt($scope.paymentTerm) == 2){
                        $http.get('/sell-card/del-deposit?id='+$scope.orderid123).then(function(reponse){
                            $scope.getData();
                        });
                    }*/
                    $scope.getData();
                    $scope.blendMoney = '';
                }
                else {
                    $scope.privateLessonBuyButtonFlag = false;
                    Message.warning(data.data.data)
                }
                $scope.blendId = '';
                $scope.dataCompleteBuy.numberOfCourses = ''
                $scope.dataCompleteBuy.renewalDate = ''
                $scope.dataCompleteBuy.sellingPrivateEducation = ''
                $scope.dataCompleteBuy.distributionChannel = ''

                $scope.dataCompleteBuy.paymentMethod = ''
                $scope.blendPic = null
                $scope.trues = false;
                $scope.monthUpNums = '';
            }, function (error) {
                console.log(error)
                Message.error("系统错误请联系工作人员")
            });
        }
    }


    $(function () {
        //登记日期
        $("#registerDate").datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language: 'zh-CN',
            autoclose: true
        })
        //续费日期
        $("#renewDate").datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language: 'zh-CN',
            autoclose: true
        })
        //缴费日期
        $("#registerDate").datetimepicker({
            minView: "month",
            format: 'yyyy-mm-dd hh:ii',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: false//今日按钮
        });

        $("#bothDate").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true//今日按钮
        });
        var date = $scope.newDate;
        // 触发日期选择器的js
        $("#dateStart").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true,//今日按钮
            setStartDate: date
        }).on('changeDate', function (ev) {
            $scope.calendar = $("#dateSpan").val();
        });
        // 触发日期选择器的js2
        $("#dateEnd").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true,//今日按钮
            setStartDate: date
        }).on('changeDate', function (ev) {
            $scope.calendar2 = $("#dateSpan2").val();
        });
        // 请假开始日期插件的js
        $("#dataLeave1").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true,//今日按钮
        });
        // 请假结束日期插件的js
        $("#dataLeaveEnd").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true,//今日按钮
        });
        $("#giveCourseDate").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true,//今日按钮
        });
    })

    //选择新的会员卡种
    $scope.selectNewMemberCard = function () {
        $('#selectCardList').modal('show');
    }
    //返回升级会员卡模态框
    $scope.backUpgrade = function () {
        $('#memberCardUpgradeModal').modal('show')
        $('#selectCardList').modal('hide');
    }

    // $('#selectPrivateCourseModal').modal('show');
    /******点击请假选项触发事件*******/
    $scope.getLeaveRecord = function (id) {
        $scope.getLeaveData(id);
    };
    /******获取请假表信息*******/
    $scope.getLeaveData = function () {
        $http.get($scope.MAIN.API.leavePath).success(function (response) {
            if (response.vacate == undefined || response.vacate == '') {
                $scope.leaveNoDataShow = true;
            } else {
                $scope.leaveNoDataShow = false;
            }
            $scope.vacates = response.vacate;
            $scope.leavePages = response.pages;
        });
    };
    $scope.replaceLeavePage = function (urlPages) {
        $scope.MAIN.API.leavePath = urlPages;
        $scope.getLeaveData();
    };
    /*****点击消除请假*****/
    $scope.removeLeave = function (id, status) {
        if (status == 1) {
            Sweety.remove({
                url: "/check-card/del-leave-record?id=" + id,
                http: $http,
                title: '确定要消除请假吗?',
                text: '消除请假后信息无法恢复',
                confirmButtonText: '确定',
                confirmButton: '消假',
                data: {
                    action: 'unbind'
                }
            }, function () {
                $scope.getData();
                $scope.getLeaveData();
            }, function () {

            }, true, true);
        } else {
            return;
        }

    };
    /******点击团课选项触发事件*******/
    $scope.getGroupClass = function (id) {
        $scope.getGroupClassData(id);
    };
    /******获取团课表信息*******/
    $scope.getGroupClassData = function () {
        $http.get($scope.MAIN.API.groupPath).success(function (response) {
            // console.log('dasjdoi',response);
            if (response.group == undefined || response.group == '') {
                $scope.groupNoDataShow = true;
            } else {
                $scope.groupNoDataShow = false;
            }
            $scope.groups = response.group;
            $scope.groupPages = response.pages;
        });
    };
    $scope.replaceGroupPage = function (urlPages) {
        $scope.MAIN.API.groupPath = urlPages;
        $scope.getGroupClassData();
    };
    /******点击消费选项触发事件*******/
    $scope.getHistory = function (id) {
        $scope.getHistoryData(id);
    };
    /************ 私课信息**********/
    $scope.privateEducationPurchaseInit = function (id) {
        $scope.privateLessonInformation();
        $scope.getChargeClassData(id);

        $scope.iboxCcontentModel = ''
    }

    //消费记录排序
    $scope.recordsOfConsumption = function ( type,sort) {
        $scope.recordsOfConsumptionType = type;
        $scope.switchSort(sort);
        $scope.getHistoryData()
    }

    $scope.recordsOfConsumptionData = function () {
        return {
            memberId: $scope.id,
            sortType:$scope.recordsOfConsumptionType,
            sortName:$scope.sort
        }
    }
    /******获取消费记录表信息*******/
    $scope.getHistoryData = function () {
        var data = $scope.recordsOfConsumptionData();
        $http.get('/user/consumption-info?'+ $.param(data) ).success(function (response) {
            if (response.expense == undefined || response.expense == '') {
                $scope.payNoDataShow = true;
            } else {
                $scope.payNoDataShow = false;
            }
            $scope.expenses = response.expense;
            $scope.payPages = response.pages;
        });
    };
    $scope.replacePayPage = function (urlPages) {
        $scope.MAIN.API.consumptionPath = urlPages;
        $scope.getHistoryData();
    };
    /******点击柜子选项触发事件*******/
    $scope.getCabinet = function (id) {
        $scope.getCabinetData(id);
    };
    /******获取柜子表信息*******/
    $scope.getCabinetData = function () {
        $http.get($scope.MAIN.API.cabinetPath).success(function (response) {
            if (response.cabinet == undefined || response.cabinet == '') {
                $scope.cabinetNoDataShow = true;
            } else {
                $scope.cabinetNoDataShow = false;
            }
            $scope.cabinets = response.cabinet;
            // console.log('柜子',$scope.cabinets);
            $scope.cabinetPages = response.pages;
        });
    };
    $scope.replaceCabinetPages = function (urlPages) {
        $scope.MAIN.API.cabinetPath = urlPages;
        $scope.getCabinetData();
    };
    /******点击赠品选项触发事件*******/
    // $scope.getGift = function (id) {
    //     $scope.getGiftRecordData(id);
    // };
    /******获取赠品表信息*******/
    $scope.getGiftRecordData = function () {
        $scope.getGiftUrl = '/user/gift-record-info?memberId=' + $scope.id;
        $http.get($scope.getGiftUrl).success(function (response) {
            console.log(response)
            if(response.gift == undefined || response.gift == null || response.gift == ""){
                $scope.giftNoDataShow = true;
                $scope.giftList = response.gift;
                $scope.pages    = response.pages;
            }
            else{
                $scope.giftNoDataShow = false;
                $scope.giftList = response.gift;
                $scope.pages    = response.pages;
            }
        });
    };
    $scope.replaceGiftPage = function (urlPages) {
        $scope.getGiftUrl = urlPages;
        $scope.getGiftRecordData();
    };

    // 点击领取赠品
    $scope.receiveGift = function (id){
        $scope.giftIdReceive = id;
        $http.get("/user/update-gift-status?id=" + $scope.giftIdReceive).success(function (data){
            if(data.status == "success"){
                Message.success(data.data);
                $scope.getGiftRecordData();
            }else {
                Message.warning(data.data);
            }

        })
    };

    /******点击私课触发事件*******/
    $scope.getChargeClass = function (id) {
        $scope.getChargeClassData(id);
    };
    /******获取私课表信息*******/
    $scope.getChargeClassData = function () {
        $http.get($scope.MAIN.API.chargePath).success(function (response) {
            if (response.charge == undefined || response.charge == '') {
                $scope.privateNoDataShow = true;
            } else {
                $scope.privateNoDataShow = false;
            }
            $scope.charges = response.charge;
            $scope.privatePages = response.pages;
        });
    };
    $scope.replaceChargePage = function (urlPages) {
        $scope.MAIN.API.chargePath = urlPages;
        $scope.getChargeClassData();
    };
    /******点击私课触发详细事件*******/
    $scope.getChargeClassDetail = function (id, charge_id) {
        $scope.getChargeData(id, charge_id);
    };
    /******获取私课上课记录表数据*******/
    $scope.getChargeData = function (id, charge_id) {
        $http.get("/user/class-record-info?MemberId=" + $scope.id + '&charge_id=' + charge_id).success(function (response) {
            $scope.records = response.record;
        });
    };
    /******点击进场选项触发事件*******/
    $scope.getEntryRecord = function (id) {

        //调用开始日期
        $("#datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        }).on("click",function(){
            $("#datetimeStart").datetimepicker("setEndDate",$("#datetimeEnd").val());
        });

        $scope.searchParams = '';
        $scope.InitUrl = '&entryTime=';
        $scope.getGiftRecordData();
        $scope.getEntryRecordData(id);
        $scope.getMemberSendCardRecord();
    };
    /******获取进场表信息*******/
    $scope.getEntryRecordData = function () {
        $http.get($scope.MAIN.API.entryRecordPath + $scope.InitUrl).success(function (response) {
            if (response.entry == undefined || response.entry == '') {
                $scope.entryNoDataShow = true;
            } else {
                $scope.entryNoDataShow = false;
            }
            $scope.entrys = response.entry;
            $scope.entryPages = response.pages;
            $scope.count = response.count;

        });
    };
    $scope.replaceEntryPages = function (urlPages) {
        $scope.MAIN.API.entryRecordPath = urlPages + $scope.InitUrl;
        $scope.getEntryRecordData();
    };


    $scope.getBehaviorRecord = function(){
        $http.get($scope.behaviorRecordUrl).then(function(response){
            // console.log('xingwei信息',response);
            if(response.data.data.length != 0){
                $scope.behaviorRecordLists = response.data.data;
                $scope.behaviorRecordPages = response.data.page;
                $scope.behaviorRecordFlag   = false;
            }else{
                $scope.behaviorRecordLists = response.data.data;
                $scope.behaviorRecordPages = response.data.page;
                $scope.behaviorRecordFlag   = true;
            }
        });
    }
    //获取行为记录
    $scope.SelectMessage = function(value){
        // console.log(value)
        $scope.behaviorRecordUrl = '/user/information-records?memberId='+ $scope.MemberId123;
        $scope.getBehaviorRecord();
    }

    $scope.replaceInformationRecords = function(urlPages){
        $scope.behaviorRecordUrl = urlPages;
        $scope.getBehaviorRecord();
    }


    /**处理搜索到场数据***/
    $scope.searchEntryData = function () {
        return {
            entryTime: $scope.entryTime != undefined ? $scope.entryTime : null
        }
    };
    $scope.initPaths = function () {

        $scope.searchParams = $scope.searchEntryData();
        if ($scope.searchParams != '' && $scope.searchParams != undefined) {
            // var startTimes  = Date.parse(new Date($scope.searchParams['entryTime']));         //开始日期格式化时间戳
            // $scope.searchParams = startTimes / 1000;
            $scope.InitUrl = '&entryTime=' + $scope.searchParams.entryTime;
        }
    };
    /**搜索到场方法***/
    $scope.searchEntry = function () {
        $scope.initPaths();
        $scope.getEntryRecordData();
    };

    $scope.init();

    /**
     *后台会员管理 - 会员信息（版本2） -  修改信息（1、先查询出信息 2、修改查询出的信息）
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/4/5
     * @update 2017/4/10
     *
     */
    $scope.getMemberIdUpdate = function (id) {
        $scope.memberPic = undefined;


        $("#imgBoolFalse").removeClass("imgBoolTrue")
        $("#imgBoolUndefined").removeClass("imgBoolFalse")

        $("#imgBoolFalse").addClass("imgBoolFalse")
        $("#imgBoolUndefined").addClass("imgBoolTrue")
        // console.log($scope.memberPic);
        $('#myModals').modal('show');
        $scope.updateMemCard(id);
        var MemberId = id;
        if (id != undefined) {
            $http.get('/user/member-details-card?MemberId=' + id).then(function (result) {
                $scope.MemberDetailsUpdate = result.data;
                $scope.MemberDetailsUpdate.pic = result.data.pic;
                // $scope.memberPic             = result.data.pic;
                // console.log('修改',result.data)
                $scope.coachId = $scope.MemberDetailsUpdate.counselor_id;
            })
        } else {
            $scope.MemberDetailsUpdate = '暂无数据';
        }
    };
    //查询身份证是否存在
    $scope.getMemberIdCard = function (id, idCard) {
        var MemberId = id;
        var MemberIdCard = idCard;
        if (MemberIdCard == undefined) {
            $scope.IdCardStatus = false;
            return false;
        }
        var lengths = MemberIdCard.length;
        if (MemberIdCard != undefined && lengths == 18) {
            $http.get('/user/member-details?MemberIdCard=' + MemberIdCard + "&MemberId=" + MemberId).then(function (result) {
                if (result.data) {
                    $scope.IdCard = '该身份证号已经存在';
                    $scope.IdCardStatus = true;
                } else {
                    $scope.IdCardStatus = false;
                }
            })
        }
    };
    //上传头像
    $scope.uploadCover = function (file, text) {
        if (file) {
            if (file.size > 2000000) {
                Message.warning('文件太大不能上传')
            } else {
                $scope.upload(file, text);
            }

        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
        }
    };

    $scope.upload = function (file, text) {
        Upload.upload({
            url: '/class/upload',
            method: 'POST',
            data: {UploadForm: {imageFile: file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                if (text == 'update') {
                    $scope.MemberDetailsUpdate.pic = result.data.imgLink;
                } else {
                    $scope.MemberDetailsUpdate.pic = result.data.imgLink;
                }
            } else {
                Message.warning(result.data.data);
            }
        });
    };
    //选择框触发事件
    $scope.updateCardNumber = function (id, num, date) {
        $scope.cardId = id;
        $scope.number = num;
        // console.log(date);
        var $date = new Date(date * 1000);
        var $year = $date.getFullYear();
        var $month = $date.getMonth() + 1;
        // console.log($date.getMonth());
        // console.log($month);
        var $day = $date.getDate();
        $scope.expireDate = $year + '-' + $month + '-' + $day;
    }
    $scope.updateCardInfo = function () {
        $scope.data =
        {
            _csrf_backend: $('#_csrf').val(),
            number: $scope.number,            //姓名
            expireDate: $scope.expireDate,            //到期时间
            id: $scope.cardId,
            postponeDate:$scope.postponeDate//延期开卡日期
        };

        if($scope.postponeDate != '' && $scope.postponeDate != undefined && $scope.postponeDate != null){
            if($('#postponeCause1').val() == ''){
                Message.warning('延期开卡原因不能为空!');
                return;
            }
        }
        // console.log($scope.data);
        $http({
            url: '/user/member-card-edit',
            method: 'POST',
            data: $.param($scope.data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            Message.success(data.data);
            if($('#postponeCause1').val() != ''&& $scope.postponeDate != '' && $scope.postponeDate != undefined && $scope.postponeDate != null){
                var data = {
                    _csrf_backend: $('#_csrf').val(),
                    memberId :$scope.MemberId123,
                    note      :$('#postponeCause1').val(),
                    behaviorId :3,
                    memberCardId:$scope.cardId
                }
                // console.log('延期原因',data);
                $http({
                    url:'/user/add-note',
                    method: 'POST',
                    data: $.param(data),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(response){
                    // console.log('延期记录',response);
                });
            }
            $('#myModals18').modal("hide"); //关闭模态框
            $scope.getMemberInfo();
        })
    }
    //接收值
    $scope.MemberInfo = function () {
        $scope.data =
        {
            _csrf_backend: $('#_csrf').val(),
            name: $scope.MemberDetailsUpdate.name,            //姓名
            sex: $scope.MemberDetailsUpdate.sex,             //性别
            birth_date: $scope.MemberDetailsUpdate.birth_date,      //出生年月
            mobile: $scope.MemberDetailsUpdate.mobile,          //手机号
            id_card: $scope.MemberDetailsUpdate.id_card,         //身份证号
            profession: $scope.MemberDetailsUpdate.profession,      //工作
            // counselor     : $scope.MemberDetailsUpdate.counselor,    //销售顾问
            // counselor     : $("#counselor").val(),                   //销售顾问

            fingerprint:templateDataArray,
            family_address: $scope.MemberDetailsUpdate.family_address,  //家庭住址
            id: $scope.MemberDetailsUpdate.id,
            adviserId: $scope.MemberDetailsUpdate.counselor_id,
            pic: $scope.MemberDetailsUpdate.pic

        };
        $http({
            url: '/user/member-info-edit',
            method: 'POST',
            data: $.param($scope.data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            Message.success(data.data);
            $('#myModals').modal('hide');
            $scope.getData();            //调用员工
        })
    }
    //获取 会员详情信息  私课信息
    //获取课程下拉框
    $scope.privateLessonInformation = function () {
        $http({method: 'get', url: '/user/get-charge-array-info?memberId=' + $scope.id}).then(function (data) {
            if (data.data == '') {
                $scope.privateLessonInformations = data.data;
                $scope.privateLessonSelect = 'xixi';
                $scope.privateLessonTemplet = '';
                $scope.renewTheRecordDate = '';
                $('.optionNoDataClassXixi').show();
                $('.wSelectAddHiH').val('xixi');
            }
            else {
                $('.optionNoDataClassXixi').hide();
                $scope.privateLessonInformations = data.data;
                $scope.privateLessonSelect = $scope.privateLessonInformations[0].orderId;
                $scope.privateLessonSelectClick($scope.privateLessonSelect);
            }
        }, function (error) {
            // console.log(error);
            Message.error('系统错误请联系管理人员')
        })
    }

    $scope.replacePersonal = function (urlPages) {
        $scope.replacePersonalUrl= urlPages;
        $scope.appointmentRecordListData();
    }
    //选择框触发事件
    $scope.privateLessonSelectClick = function (chargeIds) {
        $scope.chargeIds = chargeIds;
        $http({
            method: 'get',
            url: '/user/get-charge-info?memberId=' + $scope.id + '&chargeId=' + chargeIds
        }).then(function (data) {
            $scope.privateLessonTemplet = data.data;
            $scope.positionId123 = data.data.id;
            $scope.renewTheRecord($scope.privateLessonTemplet.orderId)
        }, function (error) {
            Message.error('系统错误请联系管理人员')
        })
        $scope.replacePersonalUrl = '/user/class-record-info?MemberId='+$scope.id+'&charge_id='+chargeIds;
        $scope.appointmentRecordListData();
    }
    $scope.appointmentRecordListData = function () {
        $http({method:'get',url:$scope.replacePersonalUrl}).then(function (data) {
            if(data.data.record == '' || data.data.record.length == 0){
                $scope.currentTimeS = new Date().getTime()/1000;
                $scope.appointmentRecord = data.data.record;
                $scope.appointmentRecordPages = data.data.pages;
                $scope.appointmentRecordShow = true
            }else {
                $scope.currentTimeS = new Date().getTime()/1000;
                $scope.appointmentRecord = data.data.record;
                $scope.appointmentRecordPages = data.data.pages;
                $scope.appointmentRecordShow = false
            }
           $scope.appointmentRecordDate  = new Date().getTime()
            // console.log($scope.appointmentRecordDate )
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }
    //续费记录
    $scope.renewTheRecord = function (orderId) {
        $http({
            method: 'get',
            url: '/user/get-charge-history?memberId=' + $scope.id + '&orderId=' + orderId
        }).then(function (data) {
            if (data.data.data == '' || data.data.data == undefined || data.data.data == null) {
                $scope.renewTheRecordDate = data.data.data;
            } else {
                $scope.renewTheRecordDate = data.data.data;
            }
        }, function (error) {
            // console.log(error)
            Message.error('系统错误请联系工作人员')
        })
    }
    // 私课信息点击续费按钮，传递数据
    $scope.privateBuy = function () {
        $scope.RenewPTButtonFlag = false;
        $scope.privateBuyDatas.total = '';
        $scope.privateBuyDatas.discount = '';
        $scope.privateBuyDatas.data = '';
        $scope.privateBuyDatas.education = '';
        $scope.privateBuyDatas.remarks = '';
        //页面数据
        $http({
            method: 'get',
            url: '/user/get-charge-info?memberId=' + $scope.id + '&chargeId=' + $scope.chargeIds
        }).then(function (data) {
            $scope.privateBuyData = data.data;
        }, function (error) {
            // console.log(error);
            Message.error('系统错误请联系管理人员')
        })

        ///获取私教销售下拉框
        $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
            $scope.privateBuyDataSectle = data.data
        }, function (error) {
            // console.log(error)
            Message.error("请联系工作人员")
        })

        $('#privateBuy').modal('show');
    }


    //续费 选择销售教练
    $scope.selectAboutClassData = function (id) {
        $scope.selectAboutClassDataID = id
    }
    //初始化续费数据
    $scope.privateBuyDatas = {
        total: '', // 课程节数
        discount: '',//折扣
        sellingPrivateEducation: '',//销售教练
        remarks: '',//备注
        data: ""
    }
    //失去节数焦点 算出总钱数
    $scope.nodeNumberBlur = function (data) {
        $http({method:'get',url:'/user/compute-price?chargeId='+$scope.privateBuyData.chargeId+"&number="+data+"&memberId="+$scope.id}).then(function (success) {
            $scope.nodeNumberBlurData = success.data;
            console.log($scope.nodeNumberBlurData)
        },function (error) {
            console.log(error);Message.error("系统错误请联系工作人员");
        })
    }
    //完成续费
    $scope.OkRenewal = function () {
        //折扣
        var data = {
            memberId: $scope.id, //会员id
            chargeId: $scope.privateLessonSelect, //私教课程id
            nodeNumber: $scope.privateBuyDatas.total,//课程节数
            coachId: $scope.selectAboutClassDataID, //销售私教id
            overTime: $scope.privateBuyDatas.data,//延长结束日期
            note: $scope.privateBuyDatas.remarks, // 备注
            offer: $scope.privateBuyDatas.discount,//折扣
            scenario: 'carry',//续费标识
            _csrf_backend: $('#_csrf').val()
        }
        if(!$scope.RenewPTButtonFlag){
        if (data.nodeNumber == undefined || data.nodeNumber == null || data.nodeNumber == '') {
            Message.warning('请输入课程节数')
            return
        }
        if (data.overTime == undefined || data.overTime == null || data.overTime == '') {
            Message.warning("请选择延长时间")
            return
        }
        if (data.coachId == undefined || data.coachId == null || data.coachId == '') {
            Message.warning('请输入销售私教')
            return
        }
        $scope.RenewPTButtonFlag = true;
        var url = '/user/save-member-charge'
        $http({
            method: 'POST',
            url: url,
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function (data) {
            if (data.data.status === "success") {
                Message.success(data.data.data)
                $scope.privateLessonSelectClick($scope.chargeIds);
                $scope.renewTheRecord($scope.privateLessonTemplet.orderId)
                $('#privateBuy').modal('hide');
            }
            else {
                $scope.RenewPTButtonFlag = false;
                Message.warning(data.data.data)
            }
        }, function (error) {
            $scope.RenewPTButtonFlag = false;
            // console.log(error)
            Message.error("系统错误请联系工作人员")
        })
        }
    }
    $scope.getMyDateCh = function(str){
        str = parseInt(str);
        if(str!=""||str!=null){
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth()+1;
            oMonth = oMonth>=10? oMonth:'0'+oMonth;
            var oDay = oDate.getDate();
            oDay = oDay>=10? oDay:'0'+oDay;
            var theDate = oYear+"年"+oMonth+"月"+oDay+"日";
        }else{
            theDate = "";
        }
        return theDate
    };

    //延期功能
    $scope.postpone = function(){
        $scope.postponeBtnFlag = false;
        $scope.postponeDays123 = '';
        var oldTime = parseInt($scope.privateLessonTemplet.deadline_time);
        $scope.postponeEndTime123 = $scope.getMyDateCh(oldTime*1000)
        $('#postponeModal').modal('show');
    }

    //延期课程天数
    $scope.postponeDaysBlur = function(val){
        if(val != ''&& val != 0 && val != null){
            // console.log(val)
            var oldTime = parseInt($scope.privateLessonTemplet.deadline_time);
            var time = parseInt(val*60*24*60);
            // console.log(oldTime+'-------'+time)

            $scope.postponeEndTime123 = $scope.getMyDateCh((oldTime+time)*1000)
            // console.log( $scope.postponeEndTime123)
        }else{
            var oldTime = parseInt($scope.privateLessonTemplet.deadline_time);
            $scope.postponeEndTime123 = $scope.getMyDateCh(oldTime*1000)
        }

    }

    //延期完成按钮
    $scope.postponeBtnSubmit = function(){
        $scope.getPostponeDate = function(){
            return{
                id:$scope.positionId123,
                days:$scope.postponeDays123 != undefined && $scope.postponeDays123 !=''? $scope.postponeDays123 : null,
                _csrf_backend: $('#_csrf').val()
            }
        }
        if($scope.postponeDays123 == undefined || $scope.postponeDays123 ==''|| $scope.postponeDays123 ==null){
            Message.warning('请输入延期天数');
            return;
        }
        $scope.postponeBtnFlag = true;
        // console.log($scope.getPostponeDate())
        $http({
            method: 'POST',
            url: '/user/delay',
            data: $.param($scope.getPostponeDate()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function(response){
            if(response.data.status == "success"){
                $scope.privateLessonSelectClick($scope.privateLessonSelect);
                $('#postponeModal').modal('hide');
                Message.success(response.data.data);
            }else{
                $scope.postponeBtnFlag = false;
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
            // console.log('延期返回数据',response);
        })
    }

    //初始化转让数据
    $scope.turn = {
        memberNumber: '',
        transferAmount: '',
        transferNode: ''
    }
    // 私课信息点击转课按钮，传递数据
    $scope.transfer = function () {
        $scope.transferButtonFlag = false;
        $('#transfer').modal('show');
    }
    $scope.transferOk = function () {

        if(!$scope.transferButtonFlag){

        if ($scope.turn.memberNumber == '' || $scope.turn.memberNumber == null) {
            Message.warning('请输入会员编号')
            return
        }
        if ($scope.turn.transferAmount == '' || $scope.turn.transferAmount == null) {
            Message.warning('请输入转让金额')
            return
        }
        if ($scope.turn.transferNode == '' || $scope.turn.transferNode == null) {
            Message.warning('请输入转让节数')
            return
        }
        var data = {
            memberId: $scope.id, //会员id
            chargeId: $scope.privateLessonSelect, //私教课程id
            memberNumber: $scope.turn.memberNumber,//会员编号
            transferPrice: $scope.turn.transferAmount,//转让金额
            transferNum: $scope.turn.transferNode, //转让节数
            _csrf_backend: $('#_csrf').val()
        }
        $scope.transferButtonFlag = true;
        $http({
            method: 'post',
            url: '/member/set-member-charge-transfer',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(data),
        }).then(function (data) {
            if (data.data.status == 'success') {
                $('#transfer').modal('hide');
                $scope.getDetailData()
                $scope.privateLessonInformation();
                Message.success(data.data.data)
            }
            if (data.data.status == 'error') {
                $scope.transferButtonFlag = false;
                Message.warning(data.data.data)
                return
            }
        }, function (error) {
            $scope.transferButtonFlag = false;
            // console.log(error)
            Message.error('系统错误请联系管理员')
        })
        }
    }

    $scope.newDate = getNowFormatDate1();

    //赠送模态框
    $scope.presenterClick = function(id){
        $scope.givePtButtonFlag = false;
        $scope.privateEducationSelectCardType(id);
        $scope.giveType123 = '';
        $scope.giveCard123         = '';
        $scope.privateEducationSelectListId = '';
        $scope.giveCourseNum123      = '';
        $scope.giveCourseName123     = '';
        $scope.giveCourseValidity     = '';

        $('#presenterModal').modal('show');
    }

    //赠送类型筛选
    $scope.giveTypeSelect = function(id){
        $http.get('/user/get-course?courseType='+id).then(function(response){
            // console.log('根据类型获取课程',response);
            $scope.giveCourseLists123 = response.data.data;
        })
    }

    //获取有效期时间
    $scope.getValidityDate = function(){
        var date = $('#validityDateTime123').val();
        var startTime = date.substr(0, 10);
        $scope.startDate123 = startTime;
        var endTime = date.substr(-10, 10);
        $scope.giveCourseValidity = endTime;
        // console.log($scope.startDate123+'*****'+$scope.giveCourseValidity)
    }

    //赠送完成按钮
    $scope.presenterSubmit = function(){
        $scope.getValidityDate();
        $scope.getPresenterData = function(){
            return {
                courseType:$scope.giveType123 != undefined && $scope.giveType123 != ''? $scope.giveType123 : null,//赠送类型
                memberCardId:$scope.giveCard123 != undefined && $scope.giveCard123 != ''? $scope.giveCard123 : null,//会员卡id
                coachId:$scope.privateEducationSelectListId != undefined && $scope.privateEducationSelectListId != ''? $scope.privateEducationSelectListId : null,//私教id
                courseNum:$scope.giveCourseNum123 != undefined && $scope.giveCourseNum123 != ''? $scope.giveCourseNum123 : null,//课程节数
                courseId:$scope.giveCourseName123 != undefined && $scope.giveCourseName123 != ''? $scope.giveCourseName123 : null,//课程id
                validity:$scope.giveCourseValidity != undefined && $scope.giveCourseValidity != ''? $scope.giveCourseValidity : null,//有效期
                validityStart :$scope.startDate123 != undefined && $scope.startDate123 != ''? $scope.startDate123 : null,//有效期开始时间
                _csrf_backend: $('#_csrf').val()
            }
        }
        // console.log($scope.getPresenterData())
        if($scope.giveType123 == undefined || $scope.giveType123 == ''||$scope.giveType123 == null){
            Message.warning('请选择赠送类别');
            return;
        }
        if($scope.giveCard123 == undefined || $scope.giveCard123 == ''||$scope.giveCard123 == null){
            Message.warning('请选择会员卡');
            return;
        }
        if($scope.privateEducationSelectListId == undefined || $scope.privateEducationSelectListId == ''||$scope.privateEducationSelectListId == null){
            Message.warning('请选择私教');
            return;
        }
        if($scope.giveCourseNum123 == undefined || $scope.giveCourseNum123 == ''||$scope.giveCourseNum123 == null){
            Message.warning('请选择赠送课程节数');
            return;
        }
        if($scope.giveCourseName123 == undefined || $scope.giveCourseName123 == ''||$scope.giveCourseName123 == null){
            Message.warning('请选择赠送课程');
            return;
        }
        if($scope.giveCourseValidity == undefined || $scope.giveCourseValidity == ''||$scope.giveCourseValidity == null){
            Message.warning('请选择赠送课程有效期');
            return;
        }
        $scope.givePtButtonFlag = true;
        $http({
            method: 'post',
            url: '/user/give-class',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param($scope.getPresenterData()),
        }).then(function(response){
            // console.log('赠送返回的数据',response);
            if(response.data.status == "success"){
                Message.success(response.data.data);
                $scope.getData();
                $('#presenterModal').modal('hide');
            }else{
                $scope.givePtButtonFlag = false;
                angular.forEach(response.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        })

    }



    // 分配私教
    $scope.distriButionClick = function (id) {
        $scope.distributionPtButtonFlag = false;
        $scope.distriButionMemberId = id;
        $scope.privateEducationSelectCardType(id)
    }
    //进入选择私教的模态框
    $scope.distributionTeacher =function () {
        $("#selectTeacherModal").modal('show');
        $scope.privateEducationData();
    }
    $scope.privateEducationModal = function () {
        $("#selectTeacherModal").modal('hide');
    }
    //获取分配教练的会员卡
    $scope.privateEducationSelectCardType = function (id) {
        $http({method:'get',url:'/user/member-card-info?MemberId='+ id}).then(function (data) {
            $scope.privateEducationSelectCardData = data.data.item;
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    //分配私教模态框 会员卡id
    $scope.privateEducationSelectCardChange = function (id) {
        $scope.privateEducationSelectTypeId = id;
    }
    $scope.privateEducationData = function () {
        $http({method:'get',url:'/private-teach/private-coach'}).then(function (data) {
            $scope.privateEducationDataList = data.data
            // console.log(data.data)
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.privateEducationSelectList = function (id,pic,name) {
        $("#selectTeacherModal").modal('hide');
        $scope.privateEducationSelectListId = id;
        $scope.privateEducationSelectListPic = pic;
        $scope.privateEducationSelectListName = name;
    }
    $scope.courseTypeSelect = function (id) {
        $scope.couresType = id
        $http({method:'get',url:"/user/get-course?courseType="+id}).then(function (data) {
            // console.log(data);
            $scope.courseTypeSelectData = data.data.data;
            $scope.couresName = '';
        },function (error) {
            console.log(error)
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.courseTypeSelectChange = function (id) {
        $scope.couresName = id;
    }
    $scope.privateEducationSelectOk = function () {
        var data = {
            memberCardId: $scope.privateEducationSelectTypeId,
            coachId:$scope.privateEducationSelectListId,
            courseNum:$scope.courseNum,
            courseId:$scope.couresName,
            courseType:$scope.couresType
        }
        if(!$scope.distributionPtButtonFlag){
            if(data.memberCardId == null || data.memberCardId == '' || data.memberCardId == undefined){
                Message.warning("请选择卡种")
                return
            }
            if(data.coachId == null || data.coachId == '' || data.coachId == undefined){
                Message.warning("请选择教练")
                return
            }
            if(data.courseNum == null || data.courseNum == '' || data.courseNum == undefined){
                Message.warning("请输入节数")
                return
            }
            if($scope.couresType == null || $scope.couresType == "" || $scope.couresType == undefined){
                Message.warning("请选择类型")
                return
            }
            if(data.courseId == null || data.courseId == '' || data.courseId == undefined){
                Message.warning("请选择课程名称")
                return;
            }
            $scope.distributionPtButtonFlag = true;
            $http({method:'post',url:'/user/assign-private',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
                if(data.data.status == "success"){
                    Message.success(data.data.data)
                    $("#distribution").modal("hide");
                    $scope.privateEducationSelectTypeId = ''
                    $scope.privateEducationSelectListId = ''
                    $scope.courseNum = ''
                    $scope.couresType = ''
                    $scope.couresName = ''
                    $scope.privateEducationSelectListPic = null
                    $scope.privateEducationSelectListName = null
                    return
                }
                if(data.data.status == "error"){
                    $scope.distributionPtButtonFlag = false;
                    Message.warning(data.data.data)
                    $("#distribution").modal("hide");
                    $scope.couresType = ''
                    $scope.privateEducationSelectTypeId = ''
                    $scope.privateEducationSelectListId = ''
                    $scope.courseNum = ''
                    $scope.couresType = ''
                    $scope.couresName = ''
                    $scope.privateEducationSelectListPic = null
                    $scope.privateEducationSelectListName = null
                    return
                }
            },function (error) {
                console.log(error);
                $scope.distributionPtButtonFlag = false;
                Message.error("系统错误请联系工作人员")
            });
        }

    };
    $scope.privateEducationClose = function () {
        $scope.privateEducationSelectTypeId = '';
        $scope.privateEducationSelectListId = '';
        $scope.courseNum = '';
        $scope.privateEducationSelectListPic = null;
        $scope.privateEducationSelectListName = null;
    };

    // 获取潜在会员送人卡信息记录
    $scope.getMemberSendCardRecord = function (){
        $http.get("/user/get-member-send-record?memberId=" + $scope.id).success(function (data){
            // console.log('getMemberSendCardRecord',data);
            $scope.memberSendCardList = data.data;
            if($scope.memberSendCardList.length == 0){
                $scope.payNoSendCardRecordDataShow = true; //暂无数据图像显示
            }
            else{
                $scope.payNoSendCardRecordDataShow = false; //暂无数据图像关闭
            }
        });
    };

    /** 会员卡冻结 **/
    $scope.freezeMemberCardBtn = function (id,status){
        $scope.freezeCardId     = id;
        $scope.freezeCardStatus = status;
       $scope.getFreezeCard();
    };
    // 会员卡冻结请求
    $scope.getFreezeCard = function () {
        if ($scope.freezeCardStatus == 3) {
            Sweety.remove({
                    url: '/member/update-status?memberCardId=' + $scope.freezeCardId,
                    http: $http,
                    title: '确定要解冻吗?',
                    text: '解冻后恢复正常',
                    confirmButtonText: '确定',
                    data: {
                        action: 'unbind'
                    }
                }
                , function (data) {
                    if (data.data.status == "success") {
                        Message.success("解冻成功");
                        $scope.infoChange();
                    }
                    else {
                        Message.success("冻结失败，请重试或者联系管理员");
                    }
                }
            );
        }
        else{
            Sweety.remove({
                    url: '/member/update-status?memberCardId=' + $scope.freezeCardId,
                    http: $http,
                    title: '确定要冻结吗?',
                    text: '冻结后可解冻',
                    confirmButtonText: '确定',
                    data: {
                        action: 'unbind'
                    }
                }
                , function (data) {
                    if (data.data.status == "success") {
                        Message.success("冻结成功");
                        $scope.infoChange();
                    }
                    else {
                        Message.success("冻结失败，请重试或者联系管理员");
                    }
                }
            );
        }
    };



    /**二次购卡**/
    // 获取所有售卖卡种
    $scope.getAllCardCategory = function (){
        $http.get("/sell-card/card-category").success(function (data){
            // console.log(data);
            $scope.getCardList = data;
        });
    };

    // 点击购卡按钮
    $scope.userBuyCard = function (id){
        $scope.userId = id;
        $("#buyCardModal").modal("show"); //购卡模态框显示
        $scope.getSaleMansInfo(); //获取销售人员信息
        $scope.getAllCardCategory(); //获取所有售卖卡种
        $scope.use               = "1"; //设置默认使用方式
    };

    // 整理二次购卡提交的数据
    $scope.buyTwoCardData = function (){
        $scope.newUserContactUrl = 'http://qa.aixingfu.net/purchase-card/contact-send?cardId='+$scope.cardType; //设置合同链接地址
        return{
            cardCateGoryId: $scope.cardType       != undefined && $scope.cardType       != "" ? $scope.cardType                 : null, //选择卡种
            paymentType   : $scope.pay            != undefined && $scope.pay            != "" ? $scope.pay                      : null, //付款方式
            saleMan       : $scope.saleMan        != undefined && $scope.saleMan        != "" ? $scope.saleMan                  : null, //选择销售
            amountMoney   : $scope.buyMoney       != undefined && $scope.buyMoney       != "" ? $scope.buyMoney                 : null, //总金额
            payMethod     : $scope.paymentMethod  != undefined && $scope.paymentMethod  != "" ? $scope.paymentMethod            : null,  //收款方式
            usageMode     : $scope.use            != undefined && $scope.use            != "" ? $scope.use                      : null,  //使用方式
            mobile        : $scope.userTel        != undefined && $scope.userTel        != "" ? $scope.userTel                  : null,  //手机号
            cardNumber    : $scope.userCardNumber != undefined && $scope.userCardNumber != "" ? $scope.userCardNumber           : null,  //会员卡号
            url           : $scope.newUserContactUrl, //合同链接
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 二次购卡完成提交数据
    $scope.buyTwoCardSuccess = function (){
        // 提交前验证
        // 选择卡种
        if($scope.cardType == "" || $scope.cardType == undefined || $scope.cardType == null){
            Message.warning("请选择卡种");
            return;
        }
        //付款方式
        if($scope.pay == "" || $scope.pay == undefined || $scope.pay == null){
            Message.warning("请选择付款方式");
            return;
        }
        //选择销售
        if($scope.saleMan == "" || $scope.saleMan == undefined || $scope.saleMan == null){
            Message.warning("请选择销售");
            return;
        }
        //总金额
        if($scope.buyMoney == "" || $scope.buyMoney == undefined || $scope.buyMoney == null){
            Message.warning("请填写总金额");
            return;
        }
        //收款方式
        if($scope.paymentMethod == "" || $scope.paymentMethod == undefined || $scope.paymentMethod == null){
            Message.warning("请选择收款方式");
            return;
        }
        else{
            $scope.checkButton = true;
            // 发送数据
            $http({
                url: "/sell-card/update-sell-card",
                method: 'POST',
                data: $.param($scope.buyTwoCardData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data){
                    if(data.status == "success"){
                        $scope.checkButton = false;
                        Message.success(data.data);
                        $("#buyCardModal").modal("hide");
                    }
                    else{
                        $scope.checkButton = false;
                        Message.success(data.data);
                    }
            })
        }

    };

    // 二次购卡关闭事件
    $('#buyCardModal').on('hide.bs.modal', function(){
        $scope.cardType      = "";
        $scope.pay           = "";
        $scope.saleMan       = "";
        $scope.buyMoney      = "";
        $scope.paymentMethod = "";
        $scope.use           = "1";
    });

    // 绑定会员
    $scope.bindingUser = function (id,card){
        $("#bindingUserSelectModal").modal("show"); //搜索会员模态框显示
        $scope.sendGiftUserId = id;
        // $scope.sendGiftCardId = card;
        // console.log(card);
    };

    /*** 绑定老会员 ***/
    // 绑定老会员
    $scope.oldUserBinding = function (){
        $("#bindingUserModal").modal("show"); //老会员绑定模态框显示
    };

    // 回车搜索事件
    $scope.myKeyup = function (e){
        var keycode = window.event?e.keyCode:e.which;
        if(keycode==13){
            $scope.searchBindingUser();
        }
    };

    // 点击搜索事件
    $scope.searchBindingUser = function (){
        $http.get("/private-teach/member?keyword=" + $scope.keywordsTel + "&type=send").success(function(data){
            // console.log(data);
            if(data.id == null || data.id == "" || data.id == undefined){
                Message.warning("会员不存在");
            }
            else{
                $scope.userInfoNews = data;
                // console.log(data);
                $("#bindingUserDetailsModal").modal("show");  //会员详情模态框显示
            }
        })
    };

    // 绑定老会员的数据整理
    $scope.oldUserBindingData = function (){
        return{
            oldMemberId : $scope.id              != undefined && $scope.id              != "" ? $scope.id                        : null, //会员id
            memberId    : $scope.userInfoNews.id != undefined && $scope.userInfoNews.id != "" ? $scope.userInfoNews.id : null, //被转卡会员id
            cardId      : $scope.sendGiftUserId  != undefined && $scope.sendGiftUserId  != "" ? $scope.sendGiftUserId  : null, //卡的id
            scenario: "old", //会员类型new新old老
            status  : 2, //会员状态新1、老2
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 绑定老会员的数据提交
    $scope.bindingOldUserSendCard = function (){
        // 发送数据
        $scope.checkButton = true;
        $http({
            url: "/user/save-delivery-form",
            method: 'POST',
            data: $.param($scope.oldUserBindingData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data){
            if(data.status == "error"){
                $scope.checkButton = false;
                Message.warning("提交错误！请重新填写或者联系管理员");
            }
            else{
                $scope.checkButton = false;
                Message.success("添加成功!");
                $scope.keywordsTel = "";
                $("#bindingUserModal").modal("hide"); //老会员绑定搜索模态框关闭
                $("#bindingUserDetailsModal").modal("hide");  //会员详情模态框关闭
                $("#bindingUserSelectModal").modal("hide"); //模态框关闭
                $("#myModals2").modal("hide"); //模态框关闭
                $scope.infoChange();
            }
        })
    };

    // 模态框关闭事件
    $('#bindingUserModal').on('hide.bs.modal', function (){
        $scope.keywordsTel = "";
    });

    /*** 绑定老会员结束 ***/

    /*** 绑定新会员 ***/
    // 绑定新会员
    $scope.newUserBinding = function (){
        $("#bindingNewUserModal").modal("show"); //新会员绑定模态框显示
    };

    // 绑定新会员获取验证码
    $scope.paracont = "获取验证码";
    $scope.disabled = false;

    // 获取验证码
    $scope.getCode = function (){
        var $pattern = /^1[0-9]{10}$/;
        if ($scope.newBindingMobile == null || $scope.newBindingMobile == undefined || $scope.newBindingMobile == "" || !($pattern.test($scope.newBindingMobile))) {
            Message.warning('请填写正确的手机号！');
            return false;
        }
        if($scope.newBindingName == null || $scope.newBindingName == ""){
            Message.warning('请填写姓名！');
            return false;
        }
        var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        if ($scope.newBindingIdCard == null || $scope.newBindingIdCard == "" || !(idCardP.test($scope.newBindingIdCard))) {
            Message.warning("请输入18位有效身份证号");
            return false;
        }
        else {
            // 验重
            $http.get("/sell-card/set-data?mobile="+$scope.newBindingMobile+"&idCard="+$scope.newBindingIdCard+"&name="+$scope.newBindingName).then(function (id){
                // console.log(id);
                if(id.data.status == "error"){
                    Message.warning(id.data.data);
                    return false;
                }
                else{
                    var second = 15,
                        timePromise = undefined;
                    timePromise = $interval(function () {
                        if (second <= 0) {
                            $interval.cancel(timePromise);
                            timePromise = undefined;
                            second = 15;
                            $scope.paracont = "获取验证码";
                            $scope.disabled = false;
                        } else {
                            $scope.paracont = second + "S后获取";
                            $scope.disabled = true;
                            second--;
                        }
                    }, 1000, 100);
                    // 发送验证码
                    $http({
                        url: '/sell-card/create-code',
                        method: 'POST',
                        data: $.param({'mobile': $scope.newBindingMobile, '_csrf_backend': $('#_csrf').val()}),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (result) {
                        $scope.code = result.data.data;
                        // console.log(result);
                    });
                }
            });
        }
    };

    // 整理绑定新会员的数据
    $scope.newUserBindingData = function (){
        return{
            name        : $scope.newBindingName   != undefined && $scope.newBindingName   != "" ? $scope.newBindingName  : null, //名称
            idCard      : $scope.newBindingIdCard != undefined && $scope.newBindingIdCard != "" ? $scope.newBindingIdCard: null, //身份证号
            cardId      : $scope.sendGiftUserId   != undefined && $scope.sendGiftUserId   != "" ? $scope.sendGiftUserId  : null, //卡的id
            sex         : $scope.newBindingSex    != undefined && $scope.newBindingSex    != "" ? $scope.newBindingSex   : null, //性别
            mobile      : $scope.newBindingMobile != undefined && $scope.newBindingMobile != "" ? $scope.newBindingMobile: null, //手机号
            code        : $scope.newBindingCode   != undefined && $scope.newBindingCode   != "" ? $scope.newBindingCode  : null, //验证码
            oldMemberId : $scope.id               != undefined && $scope.id               != "" ? $scope.id              : null, //老会员ID
            scenario: "new", //会员类型new新old老
            status  : 1, //会员状态新1、老2
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 绑定新会员的数据提交及验证
    $scope.newBindingSuccess = function (){
        // 验证
        // 姓名
        if($scope.newBindingName == null || $scope.newBindingName == ""){
            Message.warning('请填写姓名！');
            return false;
        }
        // 身份证号
        var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        if ($scope.newBindingIdCard == null || $scope.newBindingIdCard == "" || !(idCardP.test($scope.newBindingIdCard))) {
            Message.warning("请输入18位有效身份证号");
            return false;
        }
        // 手机号
        var $pattern = /^1[0-9]{10}$/;
        if ($scope.newBindingMobile == null || $scope.newBindingMobile == undefined || $scope.newBindingMobile == "" || !($pattern.test($scope.newBindingMobile))) {
            Message.warning('请填写正确的手机号！');
            return false;
        }
        // 性别
        if($scope.newBindingSex == null || $scope.newBindingSex == ""){
            Message.warning('请选择性别！');
            return false;
        }
        // 验证码
        if($scope.newBindingCode == null || $scope.newBindingCode == "" || $scope.newBindingCode != $scope.code){
            Message.warning('请输入正确的验证码！');
            return false;
        }
        else{
            $scope.checkButton = true;
            // 发送数据
            $http({
                url: "/user/save-delivery-form",
                method: 'POST',
                data: $.param($scope.newUserBindingData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data){
                if(data.status == "error"){
                    Message.warning("提交错误！请重新填写或者联系管理员");
                    $scope.checkButton = false;
                }
                else{
                    Message.success("添加成功!");
                    $("#bindingNewUserModal").modal("hide"); //模态框关闭
                    $("#bindingUserSelectModal").modal("hide"); //模态框关闭
                    $scope.checkButton = false;
                    $("#myModals2").modal("hide"); //模态框关闭
                    $scope.infoChange();
                }
            })
        }
    };

    // 新会员模态框关闭事件
    $('#bindingNewUserModal').on('hide.bs.modal', function (){
        $scope.newBindingName   = "";
        $scope.newBindingIdCard = "";
        $scope.newBindingSex    = "";
        $scope.newBindingMobile = "";
        $scope.newBindingCode   = "";
    });

    /*** 绑定新会员结束 ***/

});
// 购卡协议模块
angular.module('App').controller('sellingContactCtrl',function ($scope,$http){
    $scope.getUrlNum = function (){
        var url = window.location.search;
        var loc = url.substring(url.lastIndexOf('=')+1, url.length);
        // console.log('loc',loc);
        $http.get('/purchase-card/deal?cardId=' + loc).success(function (data){
            // console.log('卡种id2',$scope.cardCateGoryId);
            $scope.getNewContractData = data.intro;
            $scope.newContractName = data.name;
        });
    };
    $scope.getUrlNum();
});


function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        + " " + date.getHours() + seperator2 + date.getMinutes()
        + seperator2 + date.getSeconds();
    return currentdate;
}
function getNowFormatDate1() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        + " ";
    return currentdate;
}
