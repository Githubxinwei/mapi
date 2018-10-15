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
angular.module('App').controller('indexCtrl', function ($scope, $http, Upload, $timeout,$interval,$compile) {
    //续费的缴费日期
    $("#moneyDate").datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language: 'zh-CN',
        autoclose: true
    });

    //修改办卡日期
    $("#buyCardDate").datetimepicker({
        format         : 'yyyy-mm-dd hh:ii',
        language       : 'zh-CN',
        autoclose      : true,
        todayBtn       : true,
        todayHighlight : true
    }).on("changeDate",function(ev){
        var date = $scope.buyCardDate;
        date = new Date(Date.parse(date.replace(/-/g, "/")));
        date = date.getTime()/1000;
        $scope.buyCardDateTimetamps = date;
    });

    //修改激活日期
    $("#useCardDate").datetimepicker({
        format         : 'yyyy-mm-dd hh:ii',
        language       : 'zh-CN',
        autoclose      : true,
        todayBtn       : true,
        todayHighlight : true
    }).on("changeDate",function(ev){
        var date = $scope.useCardDate;
        date = new Date(Date.parse(date.replace(/-/g, "/")));
        date = date.getTime()/1000;
        $scope.useCardDateTimetamps = date;
    });

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
    $('#subscriptionDate').daterangepicker(null, function (start, end, label) {
        // console.log(start.toISOString(), end.toISOString(), label);
    });
    $('#cardReservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });

    $('#validityDateTime123').daterangepicker(null, function (start, end, label) {
        // console.log(start.toISOString(), end.toISOString(), label);
    });
    //添加模板时调用icheck
    $scope.getICheck = function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    }
    $scope.path = function () {
        $scope.MAIN = {
            'API': {
                'memberCardPath': "/user/member-card-info?MemberId=" + $scope.id +'&type=1',
                'memberCardInfo': "/member/get-member-card?MemberCardId=" + $scope.memberCardId,
                'groupPath': '/user/group-class-info?' + "MemberId=" + $scope.id,
                'giftPAth': '/user/gift-record-info?' + "MemberId=" + $scope.id,
                'cabinetPath': '/cabinet/member-consum-list?cabinetId=&type=&memberId='+$scope.id,
                'leavePath': "/user/leave-record-info?MemberId=" + $scope.id,
                'consumptionPath': "/user/consumption-info?MemberId=" + $scope.id,
                'entryRecordPath': "/member/entry-record-info?MemberId=" + $scope.id,
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
        });
        $('.buyCardDepositSelect2').select2({
            width:'230px',
            multiple:true
        })
    });
    $.loading.show();
    $scope.init = function () {
        $scope.entryTime = "";
        $scope.bindUsername = "";
        $scope.bindSex      = "";
        $scope.bindMobile   = "";
        $scope.bindIdCard   = "";
        $scope.bindCode     = "";
        $scope.pageInitUrl = '/member/list';
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
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
        $scope.sex = null;
        $scope.type = null;
        $scope.keywords = null;
        $scope.allStates = null;
        $scope.venueId = null;
        $scope.trialClass = null;
        $scope.privatesData = null;
        $('#reservation').val('');
        $('#cardReservation').val('');
        $('#idLabelSingle').select2("val", "");
        $('#select2-idLabelSingle-container').text('选择销售');
        // $scope.getData();
    };
    //分隔搜索激活时间
    $scope.formatDate = function () {
        $scope.startTime = $("#reservation").val().substr(0, 10);
        $scope.endTime = $("#reservation").val().substr(-10, 10);
    };

    $scope.formatDate();
    //分隔搜索办卡时间
    $scope.cardFormatDate = function () {
        $scope.cardTimeStart = $("#cardReservation").val().substr(0, 10);
        $scope.cardTimeEnd = $("#cardReservation").val().substr(-10, 10);
    };

    $scope.cardFormatDate();
    //分页
    $scope.replacementPages = function (urlPages) {
        $.loading.show();
        $scope.pageInitUrl = urlPages;
        $http.get(urlPages).success(function (response) {
            var elem = $('#memberTbody');
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                elem.html(response.data);
                $scope.pages = response.pages;
                $scope.nowPage = response.nowPage;
                $scope.dataInfo = false;
            } else {
                $scope.dataInfo = true;
            }
            $compile(elem.contents())($scope);
            $.loading.hide();
        });
    };
    //升降序
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
    //获取列表
    $scope.getData = function () {
        $.loading.show();
        $http.get($scope.pageInitUrl).success(function (response) {
            var elem = $('#memberTbody');
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                elem.html(response.data);
                // console.log('response.data',response.data);
                // console.log('response.pages',response.pages);
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                elem.html(response.data);
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            }
            $compile(elem.contents())($scope);
            $.loading.hide();
        });
    };
    $.loading.hide();
    // //添加搜索页数
    // $scope.skipPage = function (value) {
    //     if (value != undefined) {
    //         // console.log('页数',value)
    //         $scope.pageInitUrl = '/member/list?page=' + value;
    //     }
    // };
    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $.loading.show();
            $scope.nowPage = value;
            $scope.searchParams = $scope.searchCardData();
            $scope.pageInitUrl = '/member/list?' + $.param($scope.searchParams);
            $http.get($scope.pageInitUrl).then(function (response) {
                var elem = $('#memberTbody');
                if (response.data.data != "" && response.data.data != undefined && response.data.data.length != undefined) {
                    elem.html(response.data.data);
                    $scope.pages = response.data.pages;
                    $scope.nowPage = response.data.nowPage;
                }
                $compile(elem.contents())($scope);
                $.loading.hide();
            });
        }
    };
    //获取用户有权限的场馆
    $scope.getVenueOptions = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if (result.data != undefined && result.data != "") {
                $scope.optionVenue = result.data;
                $scope.VenueStauts = true;
            } else {
                $scope.VenueStauts = false;
                $scope.VenueStautsLength = true;
                $scope.optionVenue = '暂无数据';
            }
        });
    };
    //冻结按钮
    $scope.updateMember = function (id, statusId) {
        // console.log(id);
        // console.log(statusId);
        $scope.freezeMemberId = id;
        $scope.statusId = statusId;
        $('#freezeContent').val('');
        $('#freezeRemark').modal('show');
        // if(parseInt(statusId) == 2){
        //     $http.get("/user/update-status?id=" + id).then(function (result) {
        //         Message.success('操作成功');
        //         $('.frozen').show();
        //         $scope.getData();
        //     });


    };
    //冻结
    $scope.confirmFreeze = function () {
        $scope.freezeBtnSuccessPost = true;
        $timeout(function (){
            var data = {
                _csrf_backend: $('#_csrf').val(),
                memberId: $scope.freezeMemberId, //会员id（string）
                note: $('#freezeContent').val(),//会员冻结原因（string）
                behaviorId: $scope.statusId,//会员状态id（string）
                memberCardId: null
            };
            if ($('#freezeContent').val().replace(/ /g, '') == '') {
                Message.warning('冻结原因不能为空!');
                $scope.freezeBtnSuccessPost = false;
                return;
            }
            $http({
                url: '/user/add-note',
                method: 'POST',
                data: $.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (response) {
                if (response.data.status == 'success') {
                    $http.get("/user/update-status?id=" + $scope.freezeMemberId).then(function (result) {
                        if($scope.statusId == 1){
                            Message.success('冻结成功');
                        }else{
                            Message.success('解冻成功');
                        }

                        $('#freezeRemark').modal('hide');
                        // console.log(result)
                        $('.frozen').show();
                        $scope.initPath();
                        $scope.getData();
                    });
                }
            });
        },1000);
    };

    $('#freezeRemark').on('hide.bs.modal',function(){
        $scope.freezeBtnSuccessPost = false;
    });
    //删除会员
    $scope.delMem = function (id, $event) {
        var $del = $event.target;
        // var $id  = $($del).parents('td').data('id');
        Sweety.remove({
            url: "/user/mem-data?memberId=" + $scope.id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.initPath();
            $scope.getData();
        });
    };
    //删除会员卡
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
    //删除会员柜子
    $scope.delMemberCabinet = function (id, cabId) {
        Sweety.remove({
            url: "/user/member-cabinet-del?memberId=" + cabId,
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

    //批量删除私教课程
    $scope.batchDeletingCourseMess = function(){
        $scope.allChargeDelArr = [];
        var $inputList = $('#allchargeBox123').find('.chargeList123');
        $inputList.each(function(){
            if($(this).find('.icheckbox_square-green').hasClass('checked')){
                var _input = $(this).find('input[name="selectCourse123"]').val();
                $scope.allChargeDelArr.push(_input);
            }
        });
        $scope.delAllchargeData = {
            courseIdArr :$scope.allChargeDelArr,
            _csrf_backend      :$('#_csrf').val()
        }
        Sweety.remove({
            url: "/sell-card/batch-del-window",
            http: $http,
            title: '确定?',
            text: '批量删除后无法恢复!',
            confirmButtonText: '确定',
            confirmButton: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $http({
                method: 'post',
                url: '/user/batch-del-course',
                data: $.param($scope.delAllchargeData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data) {
                if (data.data.status == "success") {
                    Message.success(data.data.data);
                    $scope.getChargeClassData();
                }
                if(data.data.status == "error"){
                    Message.warning(data.data.data);
                }
            }, function (error) {
                Message.error("系统错误请联系工作人员")
            })
        }, function () {

        }, true, true);
    }

    //删除私教课程
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

    // $scope.getMemberDataCard = function (id, status) {
    //     $scope.selectEntryRecord = '';
    //     $scope.MemberId123 = id;
    //     $scope.MemberStatusFlag = status;
    //     $('div.tab-content .tab-pane').removeClass('active');
    //     var $nav = $('.nav-tabs li');
    //     $nav.removeClass('active');
    //     $('#tab-1').addClass('active');
    //     $('.nav-tabs li:first').addClass('active');
    //     $scope.id = id;
    //     console.log('member',$scope.id)
    //     $scope.getMemberData(id);
    //     $scope.path();
    // };
    $(document).on('click', '.memberList', function () {
        $scope.id ='';
        var id = $(this).data('id');
        $scope.selectEntryRecord = '';
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
        return false;
    });
    $scope.chooseSelectSaleAll = function () {
        $http({method: "get", url: '/user/get-adviser'}).then(function (data) {
            $scope.optionSale = data.data
        }, function (error) {
            // console.log(error)
            Message.error("系统错误请联系管理人员")
        })
    };
    $scope.chooseSelectSaleAll();

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
    };
    $scope.getMemberData = function (id) {
        $.loading.show();
        $scope.renewUserId = id;
        $http.get("/user/member-details-card?MemberId=" + id).then(function (result) {
            $scope.MemberData = result.data;
            $scope.userTel = result.data.mobile;
            $scope.ICCardNumber = parseInt(result.data.ic_number);
            $.loading.hide();
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
    $scope.getRenewEndTimesFun = function () {
        $http.get('/member-card/get-config?type=' + "card" + '&key=renew' + '&beforeRenew=beforeRenew').success(function (data) {
            if (data.attributes != null) {
                $scope.renewValue = (((data.attributes.value) * 24) * 60) * 60;
                $scope.allEndTimesRenewCard = parseInt($scope.cardInfoTime) + parseInt($scope.renewValue);
            }
        });
    };
    $scope.getRenewStartTimesFun = function () {
        $http.get('/member-card/get-config-data?type=' + "card" + '&beforeRenew=beforeRenew').success(function (data) {
            // console.log('start:',data);
            if (data.attributes != null) {
                $scope.renewStartValue = (((data.attributes.value) * 24) * 60) * 60;
                $scope.allStartTimesRenewCard = parseInt($scope.cardInfoTime) - parseInt($scope.renewStartValue);
            }
        });
    };

    // 获取销售人员信息
    $scope.getSaleMansInfo = function () {
        $http.get("/user/get-adviser").success(function(response){
            $scope.saleInfo = response;
        });
    };
    //续费的时候选择卡种
    $scope.getCardInfoSelect = function () {
        $http.get($scope.MAIN.API.memberCardPath).success(function (response) {
            if (response.item == "" || response.item == undefined || response.item.length <= 0) {
                $scope.cardInfoItems = response.item;
            } else {
                $scope.cardInfoItems = response.item;
            }
        })
    };
    //会员卡详情下面列表
    $scope.BatchDeletingRenewSelect = function(val){
        if(val== '1'){
            $timeout(function(){
                $scope.getICheck();
            },100);
        }
        if(val == '3') {
            // 获取会员的所有会员卡变更记录
            $scope.getChangeRecords();
        }
    }
    //批量删除续费记录
    $scope.BatchDeletingRenewTheRecord = function(){
        $scope.allBatchDeletingRenewArr = [];
        var allBatchLists = $('#allRenewBox123').find('.RenewTheRecordList123').find('.icheckbox_square-green');
        allBatchLists.each(function(){
            if($(this).hasClass('checked')){
                var _input = $(this).find('input[name="RenewTheRecord123"]').val();
                $scope.allBatchDeletingRenewArr.push(_input);
            }
        });

        $scope.allBatchDeletingRenewData = {
            conHistoryId       :$scope.allBatchDeletingRenewArr,
            _csrf_backend      :$('#_csrf').val()
        }
        Sweety.remove({
            url: "/sell-card/batch-del-window",
            http: $http,
            title: '确定删除吗?',
            text: '批量删除后无法恢复!',
            confirmButtonText: '确定',
            confirmButton: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
        $http({
            method: 'post',
            url: '/member/batch-del-renew-history',
            data: $.param($scope.allBatchDeletingRenewData),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success(data.data.data);
                $scope.getMemberInfo();
            }
            if(data.data.status == "error"){
                Message.warning(data.data.data);
            }
        }, function (error) {
            Message.error("系统错误请联系工作人员")
        })
        }, function () {

        }, true, true);
    }

    //缴费记录
    $scope.getCardRewenRecondList = function () {
        $http.get('/member/history?memberCardId=' + $scope.infoId).then(function (data){
            if (data.item == "" || data.data == undefined || data.data.length <= 0) {
                $scope.paymentItems = data.data;
                $scope.payNoMoneyDataShow = true;
                $timeout(function(){
                    $scope.getICheck();
                },100)
            } else {
                $scope.paymentItems = data.data;
                $scope.payNoMoneyDataShow = false;
                $timeout(function(){
                    $scope.getICheck();
                },100)
            }
        });
    };

    // select2方法
    $scope.typeSelect2Fun = function () {
        $(".typeSelectS").select2({
            width: "88%",
            placeholder: "请选择",
            dropdownParent: $("#myModals15")
        });
    };

    // 点击会员卡详情触发事件
    $scope.getMemberInfo = function () {
        $scope.bindModel = '1';
        $scope.getLeagueAutoNoFreeze();
        $("#showDetails").css("display","none");
        $http.get($scope.MAIN.API.memberCardPath).success(function (response) {
            if(response.item[0] != null){
                $scope.memberStatus = response.item[0].member.status;
            }else{
                $scope.memberStatus = '2';
            }
            $scope.cardInfoItems = response.item;
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
            if ($scope.cardInfoItems.length == 0 || $scope.cardInfoItems[0].card_name == null || $scope.cardInfoItems[0].card_name == undefined || $scope.cardInfoItems[0].card_name == ''){
                $scope.infoId = 'xixi';
                $scope.infoBring = false;
            } else {
                $scope.infoId = $scope.cardInfoItems[0].id;
                $scope.infoBring = $scope.cardInfoItems[0]['bring'];
                if($scope.cardInfoItems[0]['pid'] != null && $scope.cardInfoItems[0]['pid'] != ''){
                    $scope.infoBring = false;
                }
            }
            $scope.getCardRewenRecondList();
            $scope.renewTypeSelect = '2';
            $scope.typeSelect2Fun();
            $scope.getMemberTogherCard($scope.infoId);
            $scope.infoChange();
            // 获取续费卡种的有效期
            $(".cardTimeWords").hide();
            // 设置默认金额选择
            $scope.renewPrice = '';
            $scope.getSaleMansInfo();
        });
    };
    //会员卡变更记录all
    $scope.getChangeRecords = function () {
        $.loading.show();
        $http.get("/member/member-change-records?memberId=" + $scope.id).then(function (data) {
            if (data.data == ''|| data.data.length<=0){
                $scope.changeRecords = data.data;
                $scope.NoChangeRecords = true;
                $.loading.hide();
            }else {
                $scope.changeRecords = data.data;
                $scope.NoChangeRecords = false;
                $.loading.hide();
            }
        });
    }
    // 会员卡团课爽约自动解冻
    $scope.getLeagueAutoNoFreeze = function (){
        $http.get("/new-league/card-automatic-thaw?memberId=" + $scope.id + "&isRequestMember=isMember").success(function (data){
        });
    };
    /*** 选择会员卡 ***/
    // 下拉选择后的切换显示
    $scope.infoChange = function (){
        //console.log($scope.infoId)
        $scope.bindModel = '1';
        $http.get('/member/get-member-card?memberCardId=' + $scope.infoId).success(function (data){
            // 计算剩余时间的js
            // 获取今日时间
            //console.log(data)
            $scope.cardInfo = data;
            $scope.moneyListCardId = $scope.cardInfo.id;
            $scope.cardNameNot = data.card_name;
            $scope.usageMode = data.usage_mode;
            $scope.transferPrice = $scope.cardInfo.transfer_price;
            // 到期时间的时间戳
            $scope.cardInfoTime = data.invalid_time;
            $scope.timestamp = Date.parse(new Date()) / 1000;
            $scope.remainingDate = Math.round((((parseInt($scope.cardInfoTime) - parseInt($scope.timestamp)) / 24) / 60) / 60);
            //卡最后的激活时间
            $scope.cardFinalActiveTime = parseInt(data.create_at) + parseInt(data.active_limit_time * 24 * 60 * 60);
            //自动激活后卡的到期时间   到期时间为激活期限最后一天  加上  有效期
            $scope.cardFinalActiveTime_auto = $scope.cardFinalActiveTime + parseInt(data.duration * 24 * 60 * 60);
            //如果到期还未激活,将自动激活,激活时间为最后一天
            $scope.getRenewEndTimesFun();
            $scope.getRenewStartTimesFun();
            if ($scope.remainingDate < 0) {
                $('#daysSpan').text("卡已过期");
                $scope.pastDue = true; //卡状态标注为到期
            }else{
                $scope.pastDue = false;
            }
            if(data.invalid_time !== null && data.invalid_time !== undefined && data.invalid_time !== '') {
                $scope.expiryTimeChange = $scope.getMyDate(data.invalid_time*1000);
                $scope.expiryTimeChange1 = $scope.getMyDate(data.invalid_time*1000);
                $scope.expiryTimeChange11 = data.invalid_time;
            }else {
                $scope.expiryTimeChange = '';
                $scope.expiryTimeChange1 = '';
                $scope.expiryTimeChange11 = '';
            }
            if(data.active_time !== null && data.active_time !== undefined && data.active_time !== '') {
                $scope.delayActiveCardChange = $scope.getMyDate(data.active_time*1000);
                $scope.delayActiveCardChange1 = $scope.getMyDate(data.active_time*1000);
                $scope.delayActiveCardChange11 = data.active_time;
            }else {
                $scope.delayActiveCardChange = '';
                $scope.delayActiveCardChange1 = '';
                $scope.delayActiveCardChange11 = '';
            }
            // 列表页时间转换的js
            $scope.cardTime = $scope.getMyDate($scope.cardInfo.invalid_time * 1000);
            /*$http.get('/member/history?memberCardId=' + $scope.infoId).then(function (data) {
                $scope.paymentItems = data.data;
                $timeout(function(){
                    $scope.getICheck();
                },100);
                // $scope.consumptionDate = $scope.getMyDate($scope.paymentItemsS.invalid_time * 1000);

            });*/
            $scope.getCardRewenRecondList();
            if ($scope.cardInfo.status == 3) {
                $(".freezeButton").html("解冻");
            }
            else {
                $(".freezeButton").html("冻结");
            }
        });
        $http.get('/member/judge-leave?memberCardId=' + $scope.infoId).success(function(result){
            //console.log(result)
            if(result.status == 1){
                $scope.leaveStatus = true;
            }else{
                $scope.leaveStatus = false;
            }
        })
    };
    /*** 选择会员卡结束 ***/

    /*** 续费 ***/
    // 设置续费方式初始化
    $(document).on('change', '.renewTypeSelect', function () {
        if ($scope.renewTypeSelect == 1) {
            $scope.typeSelect2Fun();
        }
        else {

        }
    });
    // 卡详情续费的点击方法
    $scope.renrewCard = function (id, categoryId,object) {
        $scope.memberId = object.member_id;      //获取会员Id
        $('.payMoneyType').attr('disabled',true);//初始化定金下拉框不能点击
        // 到期时间的时间戳
        $scope.cardInfoTime = object.invalid_time;
        $scope.timestamp = Date.parse(new Date()) / 1000;
        $scope.remainingDate = Math.round((((parseInt($scope.cardInfoTime) - parseInt($scope.timestamp)) / 24) / 60) / 60);
        if ($scope.remainingDate < 0) {
            // $('#daysSpan').text("卡已过期");
            $scope.remainingDate = '卡已到期';
        }
        $scope.infoChange(id);
        $scope.renewCardNumber = $scope.cardInfo.card_category_id;
        $scope.renewTypeSelect = '2';

        $(".sellerSelect").val("").select2({
            width: '88%'
        });
        $scope.rememberCardId     = id;
        $scope.rememberCategoryId = categoryId;
        //获取续费所有卡种
        $http.get("/sell-card/card-category").success(function (data) {
            $scope.renrewCardTypeList = data;
            // console.log(data);
        });
        $scope.getRenewEffectiveTime();
        $('#myModals15').modal('show');
        //获取定金金额
        $http.get("/member/deposit-data-type?memberId="+$scope.memberId + "&type=3").success(function (results) {
             $scope.payMoneyType = results.type;
             $('.select2-container').css('width','100%')
        });
        $timeout(function(){
            $scope.renewEffectiveTimeValue = "";
            $('#renewEffectiveTimeValue').val('');
        },800);
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".payMoneyType").select2({
            multiple: true,
            dropdownParent: $("#myModals15"),
            width: "100%"
        });
    });
    // 设置续费金额
    $scope.priceChange = function () {
        $scope.renCardRecondMoney = $scope.renewPrice;
    };
    //续费收款方式下拉
    $scope.getPayMethod = function (value) {
        if(parseInt(value) == '2'){
            $('.payMoneyType').attr('disabled',false);
        }else{
            $('.payMoneyType').attr('disabled',true);
        }
    };
    //定金下拉框改变事件
    $scope.getEarnest = function(arr){
        $scope.depositArrId = arr;//获取定金金额的id数组
        $scope.dingJinArr = [];   //获取定金金额的数组
        $scope.priceArr = [];     //定义剩余购课定金数组
        var $depositDiv = $('.payMoneyType').children('option');
        $depositDiv.each(function (index,item) {
            if($(this).is(':checked')) {
                var _id = $(this).val();
                var _price = $(this).data('price');
                $scope.dingJinArr.push(_price);
            }else {
                var _price = $(this).data('price');
                $scope.priceArr.push(_price);
            }
        }) ;
        //选中定金金额
        $scope.dingJin = parseFloat($scope.isCompare($scope.dingJinArr)).toFixed(2);
        //剩余定金金额
        $scope.classSurplusPrice = parseFloat($scope.isCompare($scope.priceArr));
        //console.log($scope.priceArr);
        //console.log($scope.dingJin);
        //应缴费金额
        if($scope.renewPrice != undefined && $scope.renewPrice != '' ){
            if(Number($scope.renewPrice) >=  Number($scope.dingJin)){
                // 应缴续费金额
                $scope.renCardRecondMoney = $scope.renewPrice -  $scope.dingJin;
                // console.log(Number($scope.renewPrice) ==  Number($scope.dingJin))
            }else{
                Message.warning('定金金额不能大于续费金额');
                $('.payMoneyType').attr('disabled',true);
                $scope.renCardRecondMoney = $scope.renewPrice;
                $scope.chosePayType = '';
                $scope.payMoneyTypes='';
                $scope.classSurplusPrice = '';
                return
            }

        }else{
            Message.warning('续费价格不能为空');
            $('.payMoneyType').attr('disabled',true);
            $scope.chosePayType = '';
            $scope.payMoneyTypes='';
            $scope.classSurplusPrice = '';
        }

    };
    // 续费卡种选择
    $scope.getRenewCardInfo = function (data) {
        $scope.renewPrice         = '';
        $scope.renCardRecondMoney = '';
        $scope.maxPrice = angular.fromJson(data).max_price; //卡的最大金额
        $scope.minPrice = angular.fromJson(data).min_price;//卡的最小金额
        $scope.ordinaryRenewal =angular.fromJson(data).ordinary_renewal;//卡的普通续费价
        console.log( $scope.minPrice)
        console.log( $scope.maxPrice)
        console.log(  $scope.ordinaryRenewal)
        if (data != "" && data != null && data != undefined) {
            var $data              = angular.fromJson(data);
            $scope.renewPutonItem  = $data;
            $scope.renewCardNumber = $data.id; //续费后的卡id
            if ($data.duration != null) {
                $scope.renewCardDay = $data.duration.replace(/[^0-9]/ig, ""); //续费后的卡有效天数
            }
            else {
                $scope.renewCardDay = "0";
            }
            $scope.renewStartDat = $scope.cardInfo.invalid_time;
            $scope.renewTermEnd = (parseInt($scope.cardInfo.invalid_time) + parseInt($scope.renewCardDay * 24 * 60 * 60)).toString();  // 计算续费使用期限
            $(".cardTimeWords").show();
            // 判断续费卡价格
            if($data.ordinary_renewal != null){
                $scope.renewCardAllMoney = $data.ordinary_renewal;
            }else{
                if ($data.sell_price == null && $data.max_price != null) {
                    $scope.renewCardAllMoney = $data.min_price + '-' + $data.max_price;

                }
                else if ($data.sell_price != null) {
                    $scope.renewCardAllMoney = $data.sell_price;
                    $scope.renCardRecondMoney = $scope.renewPrice - number(100);
                }
                else {
                    $scope.renCardRecondMoney = "";
                    $scope.renewCardAllMoney = "";
                }
            }

        }
        else {
            $(".cardTimeWords").hide();
            $scope.renCardRecondMoney = "";
            $scope.renewCardAllMoney = "";
        }
    };
    // 获取续费增加有效期
    $scope.getRenewEffectiveTime = function () {
        $http.get("/member-card/card-category-data?memberCardId=" + $scope.rememberCardId).success(function (data) {
            $scope.renewEffectiveTimeList = angular.fromJson(data.validity_renewal);
        })
    };

    //续费有效期点击
    $scope.renewEffectiveTimeChangeClick = function(){
        $scope.renewEffectiveTimeChange('');
    }
    // 续费有效期选择
    $scope.renewEffectiveTimeChange = function (item){
        var item = $('#renewEffectiveTimeValue').val();
        $scope.renewEffectiveTimeItem = item;
        $(".renewPriceSelect").val("");
        $scope.renCardRecondMoney = '';
        if (item != "" && item != null && item != undefined) {
            $scope.effectiveTimeItem  = angular.fromJson(item);          //有效期列表
            $scope.effectiveTimeType  = $scope.effectiveTimeItem.type;   //有效期类型
            $scope.effectiveTimeDay   = $scope.effectiveTimeItem.day;    //有效期时长
            $scope.effectiveTimePirce = parseInt($scope.effectiveTimeItem.price);  //有效期价格
            $scope.renewStartDat      = $scope.cardInfo.create_at;
            // 根据不同的续费类型判断续费到期时间
            switch ($scope.effectiveTimeType) {
                // 天
                case 'd':
                    $scope.renewTermEnd = parseInt($scope.cardInfo.invalid_time) + (parseInt($scope.effectiveTimeDay) * 24 * 60 * 60);
                    break;
                // 月
                case 'm':
                    $scope.renewTermEnd = parseInt($scope.cardInfo.invalid_time) + (parseInt($scope.effectiveTimeDay) * 30 * 24 * 60 * 60);
                    break;
                // 季
                case 'q':
                    $scope.renewTermEnd = parseInt($scope.cardInfo.invalid_time) + (parseInt($scope.effectiveTimeDay) * 3 * 30 * 24 * 60 * 60);
                    break;
                // 年
                case 'y':
                    $scope.renewTermEnd = parseInt($scope.cardInfo.invalid_time) + (parseInt($scope.effectiveTimeDay) * 365 * 24 * 60 * 60);
                    break;
            }
            $(".cardTimeWords").show();
        }
        else {
            $(".cardTimeWords").hide();
            $scope.renewEffectiveTimeItem                  = '';
            $scope.renewEffectiveTimeItem.ordinary_renewal = '';
        }
    };
    // 续费价格失去焦点判断
    $(".renewPriceSelect").blur(function (){
        //判断续费价格区间
        if($scope.ordinaryRenewal != null && Number($scope.ordinaryRenewal) !=  Number($scope.renewPrice)){
            Message.warning('续费金额必须等于新卡金额');
            $('.payMoneyType').attr('disabled',true);
            $('.renewPriceSelect').val('');
            $scope.chosePayType = '';
            $scope.payMoneyTypes='';
            $scope.classSurplusPrice = '';
            return
        }else if($scope.ordinaryRenewal == null && Number($scope.renewPrice) > Number($scope.maxPrice) ){
            Message.warning("价格不得大于"+ $scope.maxPrice + "元");
            $('.renewPriceSelect').val('');
            return
        }else if($scope.ordinaryRenewal == null && Number($scope.renewPrice) < Number($scope.minPrice)){
            Message.warning("价格不得小于"+ $scope.minPrice + "元");
            $('.renewPriceSelect').val('');
            return
        }

        if($scope.renewEffectiveTimeItem != '' && $scope.renewEffectiveTimeItem != undefined && $scope.renewEffectiveTimeItem != null && $scope.renewTypeSelect == '2'){
            if($scope.effectiveTimePirce != null && $scope.effectiveTimePirce != ''){
                if($scope.renewPrice < $scope.effectiveTimePirce){
                    Message.warning("价格不得小于"+ $scope.effectiveTimePirce + "元");
                    $(".renewPriceSelect").val("");
                    $scope.renCardRecondMoney = "";
                }
            }
            else if($scope.effectiveTimePirce == 0){

            }
            else{
                $scope.renewPrice = $(".renewPriceSelect").val("");
            }
        }
        else if($scope.renewPutonItem.ordinary_renewal != null && $scope.renewPutonItem.ordinary_renewal != undefined && $scope.renewPutonItem.ordinary_renewal != '' && $scope.renewTypeSelect == '1'){
            if($scope.renewPrice < parseInt($scope.renewPutonItem.ordinary_renewal)){
                Message.warning("价格不得小于"+ $scope.renewPutonItem.ordinary_renewal + "元");
                $(".renewPriceSelect").val("");
                $scope.renCardRecondMoney = "";
            }
            else {

            }
        }
        else{

        }
    });
    // 续费价格区间判断
    // if($scope.ordinaryRenewal != null && Number($scope.ordinaryRenewal) !=  Number($scope.renewPrice)){
    //     Message.warning('续费金额必须等于新卡金额');
    //     $('.payMoneyType').attr('disabled',true);
    //     // $('.renewPriceSelect').val('');
    //     $scope.chosePayType = '';
    //     $scope.payMoneyTypes='';
    //     $scope.classSurplusPrice = '';
    //     return
    // }
   /* if($scope.renewPrice > $scope.maxPrice){
        Message.warning("价格不得大于"+ $scope.maxPrice + "元");
        // $scope.renewPrice = '';
        $('.renewPriceSelect').val('')
        return
    }else if($scope.renewPrice < $scope.minPrice){
        Message.warning("价格不得小于"+ $scope.minPrice + "元");
        // $scope.renewPrice = '';
        $('.renewPriceSelect').val('')
        return
    }*/
    // 续费完成提交数据
    $scope.renrewSuccess = function () {
        $scope.memberCardId = $scope.rememberCardId;
        $scope.renrewSuccessData = function () {
            return {
                renewalWay     : $scope.renewTypeSelect       != undefined && $scope.renewTypeSelect       != "" ? $scope.renewTypeSelect       : null, //续费方式 1.普通续费  2.延长有效期
                memberCardId   : $scope.memberCardId          != undefined && $scope.memberCardId          != "" ? $scope.memberCardId          : null, //续费的老卡的卡id
                cardCategoryId : $scope.renewCardNumber       != undefined && $scope.renewCardNumber       != "" ? $scope.renewCardNumber       : null, //续费卡id
                renewDate      : $scope.renewStartDat         != undefined && $scope.renewStartDat         != "" ? $scope.renewStartDat         : null, //续费起始日期
                renewPrice     : $scope.renCardRecondMoney    != undefined  ? $scope.renCardRecondMoney    : null, //续费定金后价格
                totalAmount    : $scope.renewPrice            != undefined  ? $scope.renewPrice    : null, //续费定金前金额
                endTime        : $scope.renewTermEnd          != undefined && $scope.renewTermEnd          != "" ? $scope.renewTermEnd          : null, //续费结束日期
                seller         : $scope.seller                != undefined && $scope.seller                != "" ? $scope.seller                : null, //销售
                cardNumber     : $(".cardNumInput").val()     != undefined && $(".cardNumInput").val()     != "" ? $(".cardNumInput").val()     : null, //自定义的卡号
                usage_mode     : $scope.usageMode             != undefined && $scope.usageMode             != "" ? $scope.usageMode             : null, //卡使用类型 1、自用 2、送人
                note           : $scope.renewCardNote         != undefined && $scope.renewCardNote         != "" ? $scope.renewCardNote         : null, //续费备注
                payType        : $scope.chosePayType          != undefined && $scope.chosePayType          != "" ? $scope.chosePayType          : null, //付款方式
                depositArrId   : $scope.depositArrId          != undefined && $scope.depositArrId          != "" ? $scope.depositArrId          : null, //定金金额
                _csrf_backend  : $('#_csrf').val()
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
        if ($scope.renewPrice == null || $scope.renewPrice == "" || $scope.renewPrice < 0) {
            Message.warning("请输入金额");
            return false;
        }
        if($scope.effectiveTimePirce != null && $scope.effectiveTimePirce != ''){
            if($scope.renewPrice < $scope.effectiveTimePirce){
                Message.warning("价格不得小于"+ $scope.effectiveTimePirce + "元");
                $(".renewPriceSelect").val("");
                $scope.renCardRecondMoney = "";
                return false;
            }
        }
        if($scope.renewPutonItem != '' && $scope.renewPutonItem != null && $scope.renewPutonItem != undefined){
            if($scope.renewPutonItem.ordinary_renewal != null && $scope.renewPutonItem.ordinary_renewal != undefined && $scope.renewPutonItem.ordinary_renewal != '' && $scope.renewTypeSelect == '1') {
                if ($scope.renewPrice < parseInt($scope.renewPutonItem.ordinary_renewal)) {
                    Message.warning("价格不得小于" + $scope.renewPutonItem.ordinary_renewal + "元");
                    $(".renewPriceSelect").val("");
                    $scope.renCardRecondMoney = "";
                    return false;
                }
            }
        }
        if($scope.renewTypeSelect == "2" && $scope.effectiveTimeDay == undefined){
            Message.warning("请选择有效期");
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
                $scope.renewTermEnd = "";
                $scope.renewPrice = "";
                $scope.seller = "";
                $scope.renewStartDat = "";
                $scope.cardNumber = "";
                $scope.renewCardType = "";
                $scope.renewEffectiveTimeValue = "";
                $scope.renewCardNote = "";
                $scope.checkButton = false;
                $scope.chosePayType = '';
                $scope.payMoneyTypes='';
                $scope.classSurplusPrice='';
                $("#myModals15").modal("hide");
                $scope.getCardRewenRecondList();
                $scope.infoChange();
                $scope.getCardInfoSelect();
            } else {
                $scope.checkButton = false;
                Message.warning(data.data.data);
            }
        })
    };
    // 卡种续费方式选择
    $scope.renewTypeSelectChange = function (value){
        $(".renewPriceSelect").val("");
        $(".typeSelect").select2("val","");
        $(".sellerSelect").select2("val","");
        $(".cardTimeWords ").hide();
        $scope.renewCardType           = "";
        $scope.cardNumber              = "";
        $scope.renewPrice              = "";
        $scope.seller                  = "";
        $scope.renewEffectiveTimeValue = "";
        $scope.renCardRecondMoney      = "";
        $scope.renewPriceSelect        = "";
        $scope.renewCardAllMoney       = "";
        if(value == 2){
            $scope.renewCardNumber = $scope.cardInfo.card_category_id;
        }
    };
    // 续费模态框关闭事件
    $('#myModals15').on('hide.bs.modal', function (){
        $(".renewPriceSelect").val("");
        // $(".renewTimeSelect").val("");
        $(".sellerSelect").val("");
        $(".cardTimeWords ").hide();
        $scope.renewCardType           = "";
        $scope.cardNumber              = "";
        $scope.renewPrice              = "";
        $scope.seller                  = "";
        $scope.renewEffectiveTimeValue = "";
        $scope.renCardRecondMoney      = "";
        $scope.renewCardAllMoney       = "";
        $scope.chosePayType = '';
        $scope.payMoneyTypes='';
        $scope.classSurplusPrice='';
    });
    /*** 续费结束 ***/

    /*** 转卡 ***/
    // 查询转卡会员手机号
    $scope.searchUserClick = function () {
        var $pattern = /^[1][3587][0-9]{9}$/;
        if ($scope.mobile == null || $scope.mobile == "") {
            Message.warning("请输入转卡人手机号");
            return false;
        } else if (!($pattern.test($scope.mobile))) {
            Message.warning("手机号输入错误，请重新输入");
            return;
        }
        $.loading.show();
        $http.get("/member/search-member?mobile=" + $scope.mobile).success(function (data) {
            if (data == "null") {
                Message.warning("未找到会员，请重新输入");
                return;
            }else {
                $scope.turnCardData = data;
                if(data.length > 0) {
                    $scope.name = data[0].id;
                }
                // $("#nameInputCheck").val(data.name);
                // $scope.userDataName = data.name;
            }
            $.loading.hide();
        })
    };
    // 动态改变
    $scope.changeNameNumber = function () {
        $("#nameInputCheck").val("");
    };
    /*// 转卡的点击方法
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
            if (!$scope.transferCardButtonFlag) {

                //保存数据之前数据验证
                var $pattern = /^1((3[0-9]|4[57]|5[0-35-9]|7[0-9]|8[0-9])\d{8}$)/;
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
            }
        };
    };*/

    // 动态改变
    $scope.changeNameNumber = function () {
        $("#nameInputCheck").val("");
    };

    //转卡的输入框初始化
    $scope.name = '';
    $scope.mobile = '';
    // 转卡的点击方法
    $scope.zhuanCard = function (id) {
        $scope.transferCardButtonFlag = false;
        $scope.zhuanCardId = id;
        $scope.mobile = '';
        $scope.name = '';
        $scope.turnCardData = '';
        // 转卡的点击提交信息的方法
        $scope.giveCard = function () {
            $scope.memberCardId = $scope.zhuanCardId;
            $scope.cardAdd = function () {
                // 整理提交数据
                return {
                    memberCardId: $scope.memberCardId != undefined && $scope.memberCardId != "" ? $scope.memberCardId : null,//会员卡id（string）
                    mobile: $scope.mobile != undefined && $scope.mobile != "" ? $scope.mobile : null,//手机号
                    transferPrice: $scope.transferPrice != undefined && $scope.transferPrice != "" ? $scope.transferPrice : null,//金额
                    newMemberId : $scope.name != undefined && $scope.name != "" ? $scope.name : null,//新的会员id
                    _csrf_backend: $('#_csrf').val()
                }
            };
            if (!$scope.transferCardButtonFlag) {

                //保存数据之前数据验证
                var $pattern = /^1((3[0-9]|4[57]|5[0-35-9]|7[0-9]|8[0-9])\d{8}$)/;
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
                        $scope.initPath();
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
    /*** 转卡结束 ***/
    /*** 升级 ***/
    // 获取销售人员信息
    $scope.getAllSaleMan = function () {
        $http.get("/user/get-adviser").success(function (response) {
            $scope.saleInfoList = response;
        });
    };

    // 升级方式的change事件
    $scope.upSateCardTypeChange = function(){
        $scope.getMoreMoneyUpsateCard($scope.upSateCardTypeValue);
        if($scope.upSateCardTypeValue == ''){
            $scope.upsateCardTimeStart   = $scope.cardInfo.create_at; //升级卡的开始时间
        }
        else{
            $scope.upsateCardTimeStart   = $scope.todaytimetemps; //升级卡的开始时间
        }
        if($scope.todaytimetemps != '' && $scope.todaytimetemps != null && $scope.todaytimetemps !=undefined ) {
            if($scope.upSateCardTypeValue == ''){
                $scope.upsateCardTimeStart = $scope.cardInfo.create_at; //升级卡的开始时间
                $scope.upsateCardEnd = parseInt($scope.cardInfo.create_at) + $scope.upsateDuration; //升级卡的结束时间
            }
            else{
                $scope.upsateCardTimeStart = $scope.todaytimetemps; //升级卡的开始时间
                $scope.upsateCardEnd = parseInt($scope.todaytimetemps) + $scope.upsateDuration; //升级卡的结束时间
            }
        }
        if($scope.upsateType != null || $scope.upsateType != undefined) {
            $scope.getMoreMoneyUpsateCard(angular.fromJson($scope.upsateType));
        }
    };
    // 获取升级价格更高的卡
    $scope.getMoreMoneyUpsateCard = function (value){
        if(value == ''|| value == undefined){
            $http.get('/sell-card/all-card-category?oldCardCategory=' + $scope.memberCardId + '&cardTypeId=' + $scope.updateCardType).success(function (data) {
                if ($scope.cardList == [] || $scope.cardList == null || $scope.cardList == '') {
                    $scope.cardList = data;
                }
                else {
                    $scope.cardList = data;
                }
            });
        }
        else{
            $http.get('/sell-card/all-card-category?oldCardCategory=' + $scope.memberCardId).success(function (data) {
                if ($scope.cardList == [] || $scope.cardList == null || $scope.cardList == '') {
                    $scope.cardList = data;
                }
                else {
                    $scope.cardList = data;
                }
            });
        }
    };

    // 升级卡的点击方法
    $scope.updateCard = function (id,categoryId,createTime,object) {
        $scope.memberId = object.member_id; //获取会员ID
        $scope.choseUpPayType = '';
        $scope.payUpMoneyTypes='';
        $('.payUpMoneyType').attr('disabled',true);
        $scope.updateCardType = $scope.cardInfo.card_type; // 卡类别：1.时间卡、2.次卡、3.充值卡、4.混合卡
        $scope.getAllSaleMan();
        $scope.updateCardButtonFlag = false;
        $scope.memberCardId = id;
        if($scope.upsateCardType == undefined) {
            $scope.upsateCardType == '';
        }
        $scope.upSateCardTypeChange();
        $("#memberCardUpgradeModal").modal("show");
        //获取定金金额
        $http.get("/member/deposit-data-type?memberId="+$scope.memberId + "&type=4").success(function (results) {
            $scope.payUpMoneyType = results.type;
            $('.select2-container').css('width','100%')
        });
    };
    angular.element(document).ready(function () {
        $(".js-example-basic-single").select2();
        $(".payUpMoneyType").select2({
            multiple: true,
            dropdownParent: $("#memberCardUpgradeModal"),
            width: "100%"
        });
    });
    //续费收款方式下拉
    $scope.getUpPayMethod = function (value) {
        if(parseInt(value) == '2'){
            $('.payUpMoneyType').attr('disabled',false);
        }else{
            $('.payUpMoneyType').attr('disabled',true);
        }
    };
    //定金下拉框改变事件
    $scope.getUpEarnest = function(arr){
        // console.log(JSON.parse($scope.getDiscountListValue).discount);

        $scope.depositArrId = arr;//获取定金金额的id数组
        $scope.dingJinArr = [];   //获取定金金额的数组
        $scope.priceArr = [];     //定义剩余购课定金数组
        var $depositDiv = $('.payUpMoneyType').children('option');
        $depositDiv.each(function (index,item) {
            if($(this).is(':checked')) {
                var _id = $(this).val();
                var _price = $(this).data('price');
                $scope.dingJinArr.push(_price);
            }else {
                var _price = $(this).data('price');
                $scope.priceArr.push(_price);
            }
        }) ;
        //选中定金金额
        $scope.dingJin = parseFloat($scope.isCompare($scope.dingJinArr)).toFixed(2);
        //剩余定金金额
        $scope.classSurplusPrice = parseFloat($scope.isCompare($scope.priceArr));
        // 判断折算价格是否为空
        if($scope.getDiscountListValue == undefined){
            Message.warning('金额不能为空');
            $scope.choseUpPayType = '';
            $scope.payUpMoneyTypes='';
            $scope.classSurplusPrice = '';
            $('.payUpMoneyType').attr('disabled',true);
            return
        }
        if($scope.getDiscountListValue != ''){
            var discountPrice =  JSON.parse($scope.getDiscountListValue).discount;
            if(Number($scope.upsateCardMoney * (discountPrice / 10) - $scope.upcardConversionMoney) >= Number($scope.dingJin)){
                $('.newCardPricePay').text($scope.upsateCardMoney * (discountPrice / 10) - $scope.upcardConversionMoney - $scope.dingJin);
            }else{
                Message.warning('定金金额不能大于补交金额');
                $scope.choseUpPayType = '';
                $scope.payUpMoneyTypes='';
                $scope.classSurplusPrice = '';
                $('.payUpMoneyType').attr('disabled',true);
                $('.newCardPricePay').text($scope.upsateCardMoney - $scope.upcardConversionMoney);
                return
            }
        }else{
            if(Number($scope.upsateCardMoney - $scope.upcardConversionMoney) >= Number($scope.dingJin)){
                $('.newCardPricePay').text($scope.upsateCardMoney - $scope.upcardConversionMoney - $scope.dingJin);
            }else{
                Message.warning('定金金额不能大于补交金额');
                $scope.choseUpPayType = '';
                $scope.payUpMoneyTypes='';
                $scope.classSurplusPrice = '';
                $('.payUpMoneyType').attr('disabled',true);
                $('.newCardPricePay').text($scope.upsateCardMoney - $scope.upcardConversionMoney);
                return
            }

        }
    };
    // 卡种选择事件
    $scope.getUpsateCardType = function (data) {
        $('.newCardPricePay').text("");
        $("#upcardConversionMoneyInput").val("");
        $scope.upsateCardRecondMoney = 0;
        $scope.upsateType            = angular.fromJson(data); //转换格式
        $scope.upsateTypeId          = $scope.upsateType.id; // 新卡的卡种id
        $scope.oldCardSellPrice      = parseFloat($scope.cardInfo.amount_money).toFixed(2); // 老卡价格
        if ($scope.upsateType.sell_price == undefined || $scope.upsateType.sell_price == null) {
            $(".upsateCardMoneySelect").removeAttr("disabled");
            $(".upsateCardMoneySelect").val("");
            $scope.upsateCardMoneyMax = parseFloat($scope.upsateType.max_price).toFixed(2); //新卡最高售价
            $scope.upsateCardMoneyMin = parseFloat($scope.upsateType.min_price).toFixed(2); //新卡最低售价
            $scope.newCardSellPrice   = parseFloat($scope.upsateType.min_price).toFixed(2) + "-" + parseFloat($scope.upsateType.max_price).toFixed(2); // 新卡价格
        }
        else if ($scope.upsateType.sell_price != 'null' && $scope.upsateType.sell_price != null) {
            $(".upsateCardMoneySelect").attr("disabled", true);
            $scope.upsateCardMoney = parseFloat($scope.upsateType.sell_price).toFixed(2);        //新卡售价
            $scope.newCardSellPrice = parseFloat($scope.upsateCardMoney).toFixed(2);   //新卡价格
            $(".upsateCardMoneySelect").val($scope.newCardSellPrice);
            $(".newCardPricePay").text($scope.newCardSellPrice);
        }
        $scope.upsateDuration = parseInt($scope.upsateType.duration.replace(/[^0-9]/ig, "")) * 24 * 60 * 60; //计算卡种有效期
        // $scope.upsateCardEnd = parseInt($scope.cardInfo.create_at) + $scope.upsateDuration; //升级卡的结束时间
        var timetemps = new Date();
        timetemps.setHours(0);
        timetemps.setMinutes(0);
        timetemps.setSeconds(0);
        timetemps.setMilliseconds(0);
        $scope.todaytimetemps = Date.parse(timetemps) / 1000; //获取当前时间戳
        if($scope.upSateCardTypeValue == ''){
            $scope.upsateCardTimeStart = $scope.cardInfo.create_at; //升级卡的开始时间
            $scope.upsateCardEnd = parseInt($scope.cardInfo.create_at) + $scope.upsateDuration; //升级卡的结束时间
        }
        else if($scope.upSateCardTypeValue == '1'){
            $scope.upsateCardTimeStart = $scope.todaytimetemps; //升级卡的开始时间
            $scope.upsateCardEnd = parseInt($scope.todaytimetemps) + $scope.upsateDuration; //升级卡的结束时间
        }else if($scope.upSateCardTypeValue == undefined){
            $scope.upSateCardTypeValue = '';
            $scope.upsateCardTimeStart = $scope.cardInfo.create_at; //升级卡的开始时间
            $scope.upsateCardEnd = parseInt($scope.cardInfo.create_at) + $scope.upsateDuration; //升级卡的结束时间
        }
        $scope.getDiscount($scope.upsateTypeId);
        if ($scope.upsateCardRecondMoney < 0) {
            Message.warning("新卡价格小于当前卡，无法升级");
        }
        else if ($scope.upsateCardEnd < $scope.todaytimetemps || $scope.upsateCardEnd == $scope.todaytimetemps) {
            Message.warning("新卡升级时间有误，无法升级");
        }
        $(".cardTimeUpsateWords").show();
        $scope.getDiscountListValue = '';
    };
    // 提交升级
    $scope.successUpdate = function () {
        $scope.upsateCardRecondMoney = $(".newCardPricePay").text();
        $scope.updateCardButtonFlag = true;
        $scope.addUpdate = function () {
            $scope.cardId = $scope.memberCardId;
            // 整理提交的数据
            return {
                newCardId     : $scope.upsateTypeId          != undefined && $scope.upsateTypeId          != "" ? $scope.upsateTypeId          : null, //新卡卡种id
                cardId        : $scope.cardId                != undefined && $scope.cardId                != "" ? $scope.cardId                : null, //老卡卡id
                payAmount     : $scope.upsateCardRecondMoney != undefined && $scope.upsateCardRecondMoney != "" ? $scope.upsateCardRecondMoney : null, //售价
                seller        : $scope.seller                != undefined && $scope.seller                != "" ? $scope.seller                : null, //销售
                card_number   : $scope.upcardNumber          != undefined && $scope.upcardNumber          != "" ? $scope.upcardNumber          : null, //卡号
                amountMoney   : $scope.upsateCardMoney       != undefined && $scope.upsateCardMoney       != "" ? $scope.upsateCardMoney       : null, //价格
                discount      : $scope.discountId            != undefined && $scope.discountId            != "" ? $scope.discountId            : null, //折扣
                dueTime       : $scope.upsateCardEnd         != undefined && $scope.upsateCardEnd         != "" ? $scope.upsateCardEnd         : null, //卡升级的到期时间戳
                upStartTime   : $scope.upsateCardTimeStart   != undefined && $scope.upsateCardTimeStart   != "" ? $scope.upsateCardTimeStart   : null, //卡升级的开始时间戳
                payType       : $scope.choseUpPayType        != undefined && $scope.choseUpPayType        != "" ? $scope.choseUpPayType        : null, //付款方式
                depositArrId  : $scope.depositArrId          != undefined && $scope.depositArrId          != "" ? $scope.depositArrId          : null, //定金金额
                note          : $scope.upSateNote            != undefined && $scope.upSateNote            != "" ? $scope.upSateNote            : null, //升级备注
                _csrf_backend : $('#_csrf').val()
            }
        };
        // 保存数据前的验证
        if ($scope.upsateCardRecondMoney < 0) {
            Message.warning("新卡价格小于当前卡，不能升级");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.upsateCardRecondMoney == undefined || $scope.upsateCardRecondMoney == '') {
            Message.warning("新卡价格有误，无法升级");
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if ($scope.upsateCardEnd < $scope.todaytimetemps || $scope.upsateCardEnd == $scope.todaytimetemps) {
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
        if (parseInt($scope.upsateMoneyVal) > parseInt($scope.upsateCardMoneyMax) || parseInt($scope.upsateMoneyVal) < parseInt($scope.upsateCardMoneyMin)) {
            var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
            Message.warning(upsateCardMoneyWords);
            $scope.updateCardButtonFlag = false;
            return false;
        }
        if($scope.upcardConversionMoney == "" || $scope.upcardConversionMoney < 0){
            Message.warning("请输入折算金额");
            $scope.updateCardButtonFlag = false;
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
                $scope.upcardConversionMoney = "";
                $(".cardTimeUpsateWords").hide();
                $("#memberCardUpgradeModal").modal("hide");
                $("#myModals2").modal("hide");
                $scope.getCardRewenRecondList();
            } else {
                $scope.updateCardButtonFlag = false;
                Message.warning(data.data.data.card_number[0]);
            }
        });
    }
    // 判断升级的区间价格
    $scope.upsateCardMoneySelectBlur = function(){
        $scope.upsateMoneyVal = parseFloat($(".upsateCardMoneySelect").val()).toFixed(2);
        $scope.upsateCardRecondMoney = parseFloat($scope.upsateCardMoney).toFixed(2);
        $('.newCardPricePay').text($scope.upsateCardRecondMoney);
        if ($(".discountSelect").val() == '' || $(".discountSelect").val() == undefined) {
            if (parseInt($scope.upsateMoneyVal) > parseInt($scope.upsateCardMoneyMax) || parseInt($scope.upsateMoneyVal) < parseInt($scope.upsateCardMoneyMin)) {
                var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin +'元';
                Message.warning(upsateCardMoneyWords);
                $(".upsateCardMoneySelect").val("");
                $('.newCardPricePay').text("");
                $scope.upsateCardRecondMoney = '';
            }
        }
        else {
            $scope.discountMath();
            if (parseInt($scope.upsateMoneyVal) > parseInt($scope.upsateCardMoneyMax) || parseInt($scope.upsateMoneyVal) < parseInt($scope.upsateCardMoneyMin)) {
                var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
                Message.warning(upsateCardMoneyWords);
                $(".upsateCardMoneySelect").val("");
                $('.newCardPricePay').text("");
                $scope.upsateCardRecondMoney = '';
            }
        }
    }

    // // 判断升级的区间价格
    // $(".upsateCardMoneySelect").blur(
    //     function () {
    //         $scope.upsateMoneyVal = parseFloat($(".upsateCardMoneySelect").val()).toFixed(2);
    //         $scope.upsateCardRecondMoney = parseFloat($scope.upsateCardMoney).toFixed(2);
    //         $('.newCardPricePay').text($scope.upsateCardRecondMoney);
    //         if ($(".discountSelect").val() == '' || $(".discountSelect").val() == undefined) {
    //             if ($scope.upsateMoneyVal > parseInt($scope.upsateCardMoneyMax) || $scope.upsateMoneyVal < parseInt($scope.upsateCardMoneyMin)) {
    //                 var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin +'元';
    //                 Message.warning(upsateCardMoneyWords);
    //                 $(".upsateCardMoneySelect").val("");
    //                 $('.newCardPricePay').text("");
    //                 $scope.upsateCardRecondMoney = '';
    //             }
    //         }
    //         else {
    //             $scope.discountMath();
    //             if ($scope.upsateMoneyVal > parseInt($scope.upsateCardMoneyMax) || $scope.upsateMoneyVal < parseInt($scope.upsateCardMoneyMin)) {
    //                 var upsateCardMoneyWords = "价格不能大于" + $scope.upsateCardMoneyMax + "," + "价格不能小于" + $scope.upsateCardMoneyMin;
    //                 Message.warning(upsateCardMoneyWords);
    //                 $(".upsateCardMoneySelect").val("");
    //                 $('.newCardPricePay').text("");
    //                 $scope.upsateCardRecondMoney = '';
    //             }
    //         }
    //     }
    // );

    // 获取卡折扣
    $scope.getDiscount = function (id){
        $http.get('/member-card/new-card-category?newCardCategory=' + id).success(function (data) {
            $scope.getDiscountList = data;
        });
    };

    $scope.residueCard = '不限';

    // 卡折扣剩余判断
    $scope.discountMath = function () {
        $("#upcardConversionMoneyInput").val("");
        $('.newCardPricePay').text("");
        $scope.upcardConversionMoney = "";
        if ($scope.getDiscountListValue != undefined && $scope.getDiscountListValue != '') {
            $scope.discountCardList = angular.fromJson($scope.getDiscountListValue);
            $scope.discountCardNum  = $scope.discountCardList.surplus;
            $scope.discountId       = $scope.discountCardList.id;
            if ($(".upsateCardMoneySelect").val() != '' && $scope.discountCardList.discount != '' && $scope.discountCardList.discount != undefined && $scope.discountCardList.discount != null) {
                if ($scope.discountCardNum == '' || $scope.discountCardNum == undefined || $scope.discountCardNum == '-1') {
                    $scope.residueCard = '不限';
                }
                else {
                    $scope.residueCard = $scope.discountCardNum;
                }
                $scope.upsateCardRecondMoney = parseFloat($(".upsateCardMoneySelect").val()).toFixed(2) * parseFloat($scope.discountCardList.discount) / 10;
                $('.newCardPricePay').text($scope.upsateCardRecondMoney);
            }
        }
        else {
            $scope.discountId = '';
            $scope.residueCard = '不限';
            $scope.upsateCardRecondMoney = parseInt($(".upsateCardMoneySelect").val());
            $('.newCardPricePay').text($scope.upsateCardRecondMoney);
        }
    };
    // 模态框关闭事件
    // 升级使用期限默认关闭
    $(".cardTimeUpsateWords").hide();
    // 折算金额的计算
    $("#upcardConversionMoneyInput").blur(function (){
        if($scope.upsateCardMoney != '' && $scope.upcardConversionMoney >= 0 && $scope.upcardConversionMoney != undefined){
            var $conversionMoney = parseFloat($scope.upsateCardMoney).toFixed(2) - parseFloat($scope.upcardConversionMoney).toFixed(2);
            if($conversionMoney > 0 && $conversionMoney != undefined){
                $(".newCardPricePay").text($conversionMoney);
            }
            else{
                Message.warning("补交金额小于等于0，无法升级");
                $scope.upcardConversionMoney = "";
                $("#upcardConversionMoneyInput").val("");
                $(".newCardPricePay").text("");
            }
        }
        else if($("#upcardConversionMoneyInput").val() == '0'){
            $(".newCardPricePay").text($scope.newCardSellPrice);
        }
        else{
            Message.warning("请输入折算金额");
            $scope.upcardConversionMoney = "";
            $("#upcardConversionMoneyInput").val("");
        }
    });

    // 升级
    $('#memberCardUpgradeModal').on('hide.bs.modal', function () {
        $scope.upsateCardType        = "";
        $scope.upcardNumber          = "";
        $scope.seller                = "";
        $scope.upsateTypePrice       = "";
        $scope.upsateCardMoney       = "";
        $scope.upsateCardRecondMoney = "";
        $scope.upsateCardEnd         = "";
        $scope.upcardConversionMoney = "";
        $('.newCardPricePay').text("");
        $(".cardTimeUpsateWords").hide();
        $(".discountSelect").val("");
        $scope.choseUpPayType = '';
        $scope.payUpMoneyTypes='';
        $scope.classSurplusPrice = '';
    });
    /**** 升级结束 ****/

    /**** 赠送天数 ****/
    // 赠送天数按钮点击事件
    $scope.giftDaysClick = function (id){
        $scope.getGiftGiveDaysListValue   = [];
        $scope.giftsDaysSelect            = "";
        $scope.getGiftGiveDaysListAllDays = "";
        $scope.giftTextarea               = "";
        $scope.giftGiveAllMathDays        = parseInt((parseInt($scope.cardInfo.invalid_time) - parseInt($scope.cardInfo.create_at))/60/60/24);
        $("#giftDaysModal").modal("show");
        $scope.getAllGiftDays(id);
    };
    $scope.getAllGiftDays = function (id){
        $http.get('/sell-card/given-member-day').success(function (data){
            $scope.getGiftGiveDaysList = data.data;
        });
    };
    $scope.giftsDaysSelectChange = function (data){
        if(data != '' && data != undefined){
            $scope.getGiftGiveDaysListValue   = angular.fromJson(data);
            $scope.getGiftGiveDaysListAllDays = $scope.getGiftGiveDaysListValue.days;
            $scope.getGiftGiveDaysListAllMath = parseInt($scope.cardInfo.invalid_time) + (parseInt($scope.getGiftGiveDaysListValue.days)*24*60*60);
            $scope.giftGiveAllMathDays        = parseInt((parseInt($scope.cardInfo.invalid_time) - parseInt($scope.cardInfo.create_at))/60/60/24) + parseInt($scope.getGiftGiveDaysListValue.days);
        }
        else{
            $scope.getGiftGiveDaysListValue.id = "";
            $scope.getGiftGiveDaysListValue    = [];
            $scope.giftsDaysSelect             = "";
            $scope.getGiftGiveDaysListAllDays  = "";
            $scope.giftGiveAllMathDays         = parseInt((parseInt($scope.cardInfo.invalid_time) - parseInt($scope.cardInfo.create_at))/60/60/24);

        }
    };
    // 赠送数据整理
    $scope.giftDaysData = function (){
        return {
            memberId     : $scope.id                            != undefined && $scope.id                            != undefined ? $scope.id                            : null, //会员id
            memberCardId : $scope.cardInfo.id                   != undefined && $scope.cardInfo.id                   != undefined ? $scope.cardInfo.id                   : null, //会员卡id
            giveCardId   : $scope.getGiftGiveDaysListValue.id   != undefined && $scope.getGiftGiveDaysListValue.id   != undefined ? $scope.getGiftGiveDaysListValue.id   : null, //赠送id
            days         : $scope.getGiftGiveDaysListValue.days != undefined && $scope.getGiftGiveDaysListValue.days != undefined ? $scope.getGiftGiveDaysListValue.days : null, //赠送天数
            note         : $scope.giftTextarea                  != undefined && $scope.giftTextarea                  != undefined ? $scope.giftTextarea                  : null, //赠送备注
            _csrf_backend: $('#_csrf').val()
        }
    };
    // 赠送提交
    $scope.postGiftDaysInfo = function (){
        if($scope.giftTextarea == ""){
            Message.warning("请填写备注");
            return;
        }
        if($scope.getGiftGiveDaysListValue.days == ""){
            Message.warning("请选择赠送天数");
            return;
        }
        if($scope.getGiftGiveDaysListValue.surplus == 0 || $scope.getGiftGiveDaysListValue.surplus == "0"){
            Message.warning("赠送次数用完");
            return;
        }
        $http({
            url: '/member-card/give-day',
            method: 'POST',
            data: $.param($scope.giftDaysData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data){
            if(data.data.status == "success"){
                Message.success(data.data.data);
                $("#giftDaysModal").modal("hide");
                $scope.getGiftGiveDaysListValue   = [];
                $scope.giftsDaysSelect            = "";
                $scope.getGiftGiveDaysListAllDays = "";
                $scope.giftTextarea               = "";
                $scope.getMemberInfo();
            }
            else{
                Message.warning(data.data.data);
            }
        });
    };
    /**** 赠送结束 ****/



    $scope.showDetails = function () {
        $(".cardImg").css("cursor", "pointer");
        $("#showDetails").css("display", "block");
    };
    $scope.noneDetails = function () {
        $("#showDetails").css("display", "none");
    };
    //会员卡卡详情
    $scope.membershipCardDetails = function (id) {
        $("#membershipCardDetails").show();
        if (id != 'xixi') {
            $http({method: 'get', url: '/member/member-card-details?memberCardId=' + id}).then(function (data) {
                console.log(data.data.data)
                $scope.memberCardDetails   = data.data.data;
                $scope.memberClassNameList = [];
                $scope.memberClassNumList  = [];
                if($scope.memberCardDetails.cardCategory.class != null){
                    var $classArr = $scope.memberCardDetails.cardCategory.class;
                }
                if($scope.memberCardDetails.cardCategory.bindPack != null){
                    var $classNumArr = $scope.memberCardDetails.cardCategory.bindPack;
                }
                if($classArr != undefined && $classArr.length > 0) {
                    angular.forEach($classArr,function (data){
                        $scope.memberClassNameList.push(data.name);
                    });
                    $scope.memberClassNameList = $scope.memberClassNameList.join("/");
                }

                if($classNumArr != undefined && $classNumArr.length > 0){
                    angular.forEach($classNumArr,function (data){
                        var p = data.number;
                        if(data.number == '-1'&& data.polymorphic_type == "class"){
                            p = "不限"
                        }
                        else if(data.number == null && data.polymorphic_type == "class"){
                            p = ""
                        }
                        else if(data.number > '0'&& data.polymorphic_type == "class"){
                            p = data.number;
                        }
                        $scope.memberClassNumList.push(p);
                    });
                    $scope.memberClassNumList = $scope.memberClassNumList.join("");
                }
                if ($scope.memberCardDetails.memberCardTime != null && $scope.memberCardDetails.memberCardTime.day) {
                    var qqqq = $scope.memberCardDetails.memberCardTime.day;
                    $scope.cardTimeDay = JSON.parse(qqqq);
                }

                if ($scope.memberCardDetails.memberCardTime != null && $scope.memberCardDetails.memberCardTime.week) {
                    var qqqq = $scope.memberCardDetails.memberCardTime.week;
                    $scope.cardTimeDay1 = JSON.parse(qqqq);
                }

                if ($scope.memberCardDetails.leave_total_days == null) {
                    var awww = $scope.memberCardDetails.leave_days_times;
                    $scope.leaveLongIimit = JSON.parse(awww);
                }
            }, function (error) {
                // Message.error("系统错误请联系管理人员")
            });
        }
    };
    $scope.closeAndBreak = function () {
        // 数据初始化
        $scope.clearBina    = '';
        $scope.clearMoney   = '';
        $scope.clearDays    = '';
        $scope.clearTime    = '';
        $scope.paymentItems = [];
        $scope.cardInfo     = [];
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
    $scope.cardStatusPost = function () {
        if ($scope.allStates == "freeze") {
            $scope.statusFreeze = "2";
            $scope.cardStatusEd = "";
            $scope.statusVacate = "";
        }
        if ($scope.allStates == "vacate") {
            $scope.statusVacate = "1";
            $scope.cardStatusEd = "";
            $scope.statusFreeze = "";
        }
        if ($scope.allStates != "freeze" && $scope.allStates != "vacate") {
            $scope.statusVacate = "";
            $scope.statusFreeze = "";
            $scope.cardStatusEd = $scope.allStates;
        }
    };

    // 数据整理
    $scope.searchCardData = function () {
        $scope.formatDate();
        $scope.cardFormatDate();
        return {
            keywords: $scope.keywords != undefined ? $scope.keywords : null,
            sex: $scope.sex != undefined ? $scope.sex : null,
            venueId: $scope.venueId != undefined ? $scope.venueId : null,
            // type: $scope.type != undefined ? $scope.type : null,
            startTime: $scope.startTime != undefined ? $scope.startTime : null,
            endTime: $scope.endTime != undefined ? $scope.endTime : null,
            cardTimeStart: $scope.cardTimeStart != undefined ? $scope.cardTimeStart : null,
            cardTimeEnd: $scope.cardTimeEnd != undefined ? $scope.cardTimeEnd : null,
            sortType: $scope.sortType != undefined ? $scope.sortType : null,
            sortName: $scope.sort != undefined ? $scope.sort : null,
            freeze: $scope.statusFreeze,
            vacate: $scope.statusVacate,
            page: $scope.nowPage != undefined ? $scope.nowPage : 1,
            free: $scope.trialClass != undefined && $scope.trialClass != undefined ? $scope.trialClass : null,//体验课
            sellId: $scope.selectSale != undefined && $scope.selectSale != undefined ? $scope.selectSale : null,
            privates: $scope.privatesData != undefined && $scope.privatesData != undefined ? $scope.privatesData : null,
            status: $scope.cardStatusEd
        }
    };
    // 生日提醒
    $scope.birthdayRemind = function () {
        swal({
            title: "确定发送吗？",
            text: "此功能会批量给会员发送祝福短信，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm) {
                $http({
                    url: '/user/birthday-reminder',
                    method: 'GET',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (result) {
                    if (result.data.status == "success") {
                        //console.log('发送成功');
                        //Message.success(result.data.message);
                        swal(result.data.message,"","success");
                    } else {
                        //Message.warning(result.data.message);
                        //swal("发送！", result.data.message, "success");
                        swal({
                            title:result.data.message ,
                            text: "2秒后自动关闭。",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
            } else {
                swal.close();
            }
        });
    };
    $scope.initPath = function () {
        $scope.searchParams = $scope.searchCardData();
        $scope.pageInitUrl = '/member/list?' + $.param($scope.searchParams);
    };
    /**搜索方法***/
    $scope.searchCard = function () {
        // $scope.searchCarding = true;
        $.loading.show();
        $scope.initPath();
        $scope.getData();
    };
    /******Enter键搜索*******/
    $scope.enterSearch = function (e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if (keyCode == 13) {
            $.loading.show();
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
                // $scope.getData()
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
    $scope.deposit = function (id) {
        $scope.depositButtonFlag = false;
        $("#subscriptionDate").val('')
        $scope.depositMoney = "";
        $scope.depositPayMode = "";
        $scope.depositPayType="";
        $scope.depositToRoll = '';
        $scope.depositMemberId = id;
        $('#deposit').modal('show');
    }
    //选择定金类型触发change事件
    $scope.depositChange = function (value) {
        if(value == 2){
            //获取私教人员信息
            $http.get("/private-teach/private-coach").success(function (response) {
                $scope.saleInfo = response;
            });
        }else{
            // 获取销售人员信息
            $http.get("/user/get-adviser").success(function (response) {
                $scope.saleInfo = response;
            });
        }
    }
    //提交订金
    $scope.depositSubmit = function () {
        var leaveDateRange = $("#subscriptionDate").val()
        var startTime = leaveDateRange.slice(0, 10)+' 00:00:01';
        var endTime = leaveDateRange.slice(13)+' 23:59:59';
        var data = {
            memberId: $scope.depositMemberId,//会员id
            price: $scope.depositMoney,//金额
            // voucher: $scope.depositToRoll,//抵劵
            startTime: startTime,//开始时间
            endTime: endTime,//结束时间
            payMode: $scope.depositPayMode,//付款方式
            payType: $scope.depositPayType,//定金类型
            seller : $scope.sellName,         //销售
            _csrf_backend: $('#_csrf').val(),
        }
        if (data.price == '' || data.price == undefined|| data.price == null) {
            Message.warning("请输入金额")
            return
        }
        // if ($scope.depositToRoll != null && $scope.depositToRoll != '') {
        //     if ($scope.depositToRoll < $scope.depositMoney) {
        //         Message.warning("抵券金额不能小于定金金额")
        //         return
        //     }
        // }
        if (data.startTime == '' || data.startTime == undefined|| data.startTime == null ||data.startTime==" 00:00:01") {
            Message.warning("请选择时间")
            return
        }
        if ($scope.depositPayType == '' || $scope.depositPayType == undefined|| $scope.depositPayType == null) {
            Message.warning("请选择定金类型")
            return
        }
        if ($scope.depositPayMode == '' || $scope.depositPayMode == undefined||$scope.depositPayMode == null) {
            Message.warning("请选择付款方式")
            return
        }
        if ($scope.sellName == '' || $scope.sellName == undefined||$scope.sellName == null) {
            Message.warning("请选择销售")
            return
        }
        // if () {
        //     Sweety.remove({
        //         url: "/sell-card/confirm-deposit",
        //         http: $http,
        //         title: '确定要提交吗?',
        //         // text: '定金金额小于抵劵金额',
        //         buttonColor: '#27c24c',
        //         confirmButtonText: '确定',
        //         confirmButton: '确定',
        //         data: {
        //             action: 'unbind'
        //         }
        //     }, function () {
        //         $http({
        //             method: 'post',
        //             url: '/potential-members/set-member-deposit-form',
        //             data: $.param(data),
        //             headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        //         }).then(function (data) {
        //             if (data.data.status == "success") {
        //                 Message.success(data.data.data)
        //                 $("#deposit").modal("hide");
        //                 $scope.initPath();
        //                 $scope.getData();
        //             }
        //             if(data.data.status == "error"){
        //                 Message.warning(data.data.data)
        //             }
        //         }, function (error) {
        //             Message.error("系统错误请联系工作人员")
        //         })
        //     }, function () {
        //
        //     }, true, true);
        //
        // } else {
        Sweety.remove({
            url: "/sell-card/confirm-deposit",
            http: $http,
            title: '确定要提交吗?',
            text: '请确认输入的金额哦',
            buttonColor: '#27c24c',
            confirmButtonText: '确定',
            confirmButton: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $http({
                method: 'post',
                url: '/potential-members/set-member-deposit-form',
                data: $.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data) {
                if (data.data.status == "success") {
                    $("#deposit").modal("hide");
                    $scope.initPath();
                    $scope.getData();
                }
            }, function (error) {
                Message.error("系统错误请联系工作人员")
            })
        }, function () {

        }, true, true);
        // if (parseInt($scope.depositMoney) == parseInt($scope.depositToRoll)) {
        //     Sweety.remove({
        //         url: "/sell-card/confirm-deposit",
        //         http: $http,
        //         title: '确定要提交吗?',
        //         text: '定金金额与抵劵金额相等吗?',
        //         buttonColor: '#27c24c',
        //         confirmButtonText: '确定',
        //         confirmButton: '确定',
        //         data: {
        //             action: 'unbind'
        //         }
        //     }, function () {
        //         $http({
        //             method: 'post',
        //             url: '/potential-members/set-member-deposit-form',
        //             data: $.param(data),
        //             headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        //         }).then(function (data) {
        //             if (data.data.status == "success") {
        //                 $("#deposit").modal("hide");
        //                 $scope.initPath();
        //                 $scope.getData();
        //             }
        //         }, function (error) {
        //             Message.error("系统错误请联系工作人员")
        //         })
        //     }, function () {
        //
        //     }, true, true);
        // }
    };

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
    //带人卡绑定
    $scope.memberTogherCard = function (id) {
        $scope.memberBindId = id;
        $scope.addBindShow  = true;
        $http.get("/member/is-vice-cards?id="+id).success(function (response) {
            if(response.data != null && response.data.length > 0){
                $('#memberTogetherModal').modal('show');
                $scope.memberBindCard = response.data;
                $scope.bindShowDetail = true;
                $scope.bindShow       = false;
                $scope.memberBindCardId = response.data[0]['id'];
                $scope.getMemberTogherCard(response.data[0]['id'],response.data[0]['bring']);
                $('#bind').find('option:first').remove();
            }else{
                Message.warning('对不起,您还未购买带人卡');
            }
        });
    };
    $scope.getMemberBingInfo = function (id) {
        $scope.getMemberTogherCard(id);
    };
    //获取卡绑定信息
    $scope.getMemberTogherCard = function (id,num) {
        $scope.bindCardId = id;
        $http.get("/member/get-vice-cards?id="+id).success(function (response) {
            $scope.viceCards = response.data;
            if($scope.viceCards != null && $scope.viceCards.length > 0){
                $scope.getMemberById($scope.viceCards[0]['member_id'],$scope.viceCards[0]['create_at'],$scope.viceCards[0]['id']);
                if($scope.viceCards.length == num){
                    $scope.addBindShow = false;
                }
            }else{
                $scope.bindShowDetail = false;
                $scope.bindShow       = true;
            }
        });
    };
    // 绑定切换卡的样式改变
    $(document).on('click','.memberTogetherCardBox',function (){
        $(".memberTogetherCardBox").removeClass("memberTogetherCardBoxBgc");
        $(this).addClass("memberTogetherCardBoxBgc");
    });
    //获取绑定人详情
    $scope.getMemberById = function (id,time,cardId) {
        $scope.bindShowDetail = true;
        $scope.bindShow       = false;
        $scope.delBindId      = cardId;
        $http.get("/user/member-details-card?MemberId=" + id).then(function (result) {
            $scope.bindData = result.data;
            $scope.bindTime = time;
        })
    };
    //获取绑定人详情
    $scope.delBindMemberById = function () {
        $scope.bindShowDetail = true;
        $scope.bindShow       = false;
        $http.get("/member/del-vice-cards?id=" + $scope.delBindId).then(function (result) {
            Message.success('解绑成功');
            $scope.memberTogherCard($scope.memberBindId);
        })
    };
    $scope.setShowBind = function () {
        $scope.bindShowDetail = false;
        $scope.bindShow       = true;
    };
    // IC卡绑定 模态框  显示IC卡原号码
    $scope.icCardBinding = function (id) {
        $('#ICCardBindingModal').modal('show');
        $scope.memberId_bindICCard = id;

    }
    // IC卡绑定 修改后 点击完成
    $scope.ICCardBindingChange = function () {
        $scope.ICCardBindingInfo = function() {
            return {
                id : $scope.memberId_bindICCard,//ic卡id
                icNumber: $scope.ICCardNumber,//ic卡number
                amount: $scope.ICCardCharge != undefined ? parseFloat($scope.ICCardCharge) : null,//金额
            }
        };
        var regTest = /^\d+(\.\d{1,2})?$/;

        if(!regTest.test($scope.ICCardCharge)) {
            Message.warning('金额只能输入整数或小数点后两位以内小数！');
            return;
        }
        if($scope.ICCardNumber == '' || $scope.ICCardNumber == null || $scope.ICCardNumber == undefined) {
            Message.warning('号码不能为空！');
        }else {
            $http({
                url: "/member/ic-binding",
                method: 'POST',
                data: $.param($scope.ICCardBindingInfo()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data) {
                if (data.data.status == "success") {
                    Message.success("绑定成功");
                    $('#ICCardBindingModal').modal('hide');
                    $scope.ICCardNumber = '';
                    $scope.ICCardCharge = 0;
                }else {
                    Message.warning("绑定失败，请重新输入后重试！");
                    return;
                }
            })
        }
    }
    // 续费完成提交数据
    $scope.bindMember = function (name,sex,mobile,idCard,code) {
        $scope.bindData = function () {
            return {
                memberCardId  : $scope.bindCardId               != undefined && $scope.bindCardId     != "" ? $scope.bindCardId    : null, //续费的老卡的卡id
                username      : name  != undefined && name      != "" ? name  : null, //续费结束日期
                idCard        : idCard   != undefined && idCard != "" ? idCard    : null, //销售
                mobile        : mobile   != undefined && mobile != "" ? mobile   : null, //自定义的卡号
                sex           : sex       != undefined && sex   != "" ? sex     : null, //卡使用类型 1、自用 2、送人
                _csrf_backend: $('#_csrf').val()
            }
        };
        //保存数据之前数据验证
        if ($scope.bindCardId == null || $scope.bindCardId == "" || $scope.bindCardId == undefined) {
            Message.warning("请选择卡种");
            return false;
        }
        var $pattern = /^1[0-9]{10}$/;
        if (mobile == null || mobile == undefined || mobile == "" || !($pattern.test(mobile))) {
            Message.warning('请填写正确的手机号！');
            return false;
        }
        if(name == null || name == ""){
            Message.warning('请填写姓名！');
            return false;
        }
        // var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        // if (idCard == null || idCard == "" || !(idCardP.test(idCard))) {
        //     Message.warning("请输入18位有效身份证号");
        //     return false;
        // }
        if( $scope.code != code){
            Message.warning("验证码不正确");
            return false;
        }
        $scope.checkButton = true;
        // 发送数据
        $http({
            url: "/member/save-main-card",
            method: 'POST',
            data: $.param($scope.bindData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success("绑定成功");
                $scope.memberTogherCard($scope.memberBindId);
                $scope.getData();
            } else {
                $scope.checkButton = false;
                Message.warning(data.data.data);
            }
        })
    };

    //获取验证码
    $scope.getBindMemberCode = function (mobile) {
        $scope.NewMobile = mobile;
        var $pattern = /^1[345678]\d{9}$/;
        if(mobile == null || mobile == '' || !($pattern.test(mobile))){
            Message.warning('请填写正确的手机号');
            return false;
        }
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
            data: $.param({'mobile': $scope.NewMobile, '_csrf_backend': $('#_csrf').val()}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            $scope.code = result.data.data;
        });
    };
    //接受数据表单数据 执行修改
    $scope.refer = function () {
        //发送数据请求指令
        $scope.cardData = function () {
            return {
                _csrf_backend: $('#_csrf').val(),
                memCardId: $scope.memCardId,//会员卡id
                memId: $scope.memId,//会员id
                invalidDate: $("#datetimeEnd").val(),//失效日期
                adviserId: $scope.adviser//销售顾问
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
                $scope.initPath();
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
        $scope.card_id = '';
        $scope.startLeaveDay = '';
        $scope.endLeaveDay = '';
        $('#leaveCause').val('');
        $scope.endLeaveFlag = false;
        $scope.leaveData1 = false;
        $scope.leaveData2 = false;
        $scope.card_id = '';
        $scope.leaveMemberId = parseInt(id);

        $http.get("/user/member-details-card?MemberId=" + $scope.leaveMemberId).then(function (result) {
            $scope.MemberData = result.data;
            // if ($scope.MemberData.leaveRecord == null) {
                $('#myModalsLeave').modal('show');
                //会员卡失效时间
                $scope.invalidTime = parseInt($scope.MemberData.memberCard[0].invalid_time);
                //会员卡生效时间
                $scope.activeTime = parseInt($scope.MemberData.memberCard[0].active_time);
                var now = Math.ceil(new Date().getTime() / 1000);
                $scope.mathAllDays = function (){
                    var mathDay = Math.ceil(($scope.invalidTime - $scope.activeTime) / (60 * 60 * 24));
                    if(!isNaN(mathDay)){
                        return mathDay.toFixed(0);
                    }
                    else {
                        return 0;
                    }
                };
                $scope.allDays = $scope.mathAllDays();
                $scope.limitedDays = Math.ceil(($scope.invalidTime - now) / (60 * 60 * 24));

                //根据返回的数字对应不同的状态
                var len = $scope.MemberData.memberCard.length;
                for (var i = 0; i < len; i++) {
                    var memberState = parseInt($scope.MemberData.memberCard[i].status);
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
            // } else {
            //     Message.warning('此卡处于异常状态或请假状态，不能请假！');
            // }
        });
        $scope.getMemberCardName();

    };

    //获取所有会员卡
    $scope.getMemberCardName = function () {
        $http.get('/user/get-member-card?memberId=' + $scope.leaveMemberId).then(function (result) {
            $scope.cards = result.data;
        });
    };
    //选择请假天数
    $scope.selectLeaveDaysOne = function (ind) {
        $scope.leaveIndex = ind;
        $scope.leave1 = ind;
        if (ind == '') {
            $scope.startLeaveDay = "";
            $scope.endLeaveDay = "";
            return;
        }
        // if ($scope.leaveStyle.leave_days_times != null) {
        //     var leave = $scope.leaveStyle.leave_days_times[ind];
        //     $scope.selectLeaveDays = leave[0];
        //     if ($scope.startLeaveDate11 != null && $scope.startLeaveDate11 != '' && $scope.startLeaveDate11 != undefined && $scope.leaveTypeChecked == '1') {
        //         var endTime = parseInt($scope.startLeaveDate11) + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 -2000;
        //         $scope.endLeaveDay = $scope.getMyDate(endTime);
        //         $scope.endLeaveFlag = true;
        //     }
        //
        // }
        if($scope.leaveStyle.leave_least_days != null && $scope.leaveStyle.leave_least_days != ' ' && $scope.leaveTypeChecked == '1'){
            var days = $scope.leaveStyle.leave_least_days;
            var allDays = $scope.leaveStyle.leave_total_days;
            if (parseInt(days) <= parseInt(allDays)) {
                $scope.selectLeaveDays = parseInt(days);
                if ($scope.startLeaveDate11 != null && $scope.startLeaveDate11 != '' && $scope.startLeaveDate11 != undefined) {
                    var endTime = parseInt($scope.startLeaveDate11) + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 - 1000;
                    $scope.endLeaveDay = $scope.getMyDate(endTime);
                    $scope.endLeaveFlag = true;
                }
            } else {
                Message.warning('请假天数小于可请假天数，请走特殊请假功能');
                return;
            }
        }
    }

    //根据不同的时间算出天数
    $scope.getDateDays = function (start, end) {
        if (start != '' && end != '' && $scope.leaveTypeChecked == '2') {
            var startTimes = new Date(start + ' ' + '00:00:00').getTime();
            var endTimes = new Date(end + ' ' + '23:59:59').getTime();
            var daysTime = parseInt(endTimes) - parseInt(startTimes);
            var days = Math.ceil(daysTime / (24 * 60 * 60 * 1000));
            $scope.leaveDays123 = days;
        }
    };

    //选择请假类型
    $scope.selectLeaveType = function (val) {
        if (val == '1') {
            $scope.endLeaveFlag = true;
            $scope.leaveTypeCheckedA = '1';
            if(!$scope.card_id) {
                Message.warning('请先选择卡种');
            }else {
                $http.get('/member/leave-record-data?cardId=' + $scope.card_id).then(function (response) {
                    if(response.data.card.length > 0) {
                        $scope.startTimeMin = parseInt(response.data.card[0].leave_end_time);
                    }
                })
            }
        } else if (val == '2'){
            $scope.endLeaveFlag = false;
            $scope.leaveTypeCheckedA = '2';
            $scope.getDateDays($scope.startLeaveDay, $scope.endLeaveDay);
        }else if(val == '3'){
            $scope.endLeaveFlag = true;
            $scope.leaveTypeCheckedA = '1';
        }
    };

    $scope.startLeaveDate = function () {
        $scope.getTodayTimesTamp();
        if(!$scope.card_id) {
            Message.warning('请先选择卡种');
        }else {
            if(!$scope.leaveTypeChecked){
                Message.warning("请先选择请假类型");
                $(".leaveDateStartInput").val("");
                $(".leaveDateEndInput").val("");
            }
        }
    };

    //请假中选择卡种
    $scope.selectOneMemberCard = function (cardId) {
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
        $scope.leaveData1 = false;
        // $scope.cardAttribute='';
        $scope.studentLeaveType='';
        $scope.leaveTypeChecked = '';
        $scope.card_id = cardId;
        if (cardId != '') {
            $scope.morenFlag = true;
            $http.get('/user/get-leave-limit?memberCardId=' + cardId).then(function (response){
                $scope.leaveStyle = response.data;
                // console.log('leave',$scope.leaveStyle);
                if (response.data.invalid_time != undefined) {
                    $scope.memberCardEndTime = response.data.invalid_time;
                }
                // if (response.data.attributes != undefined) {
                //     $scope.cardAttribute = response.data.attributes;
                // }
                if (response.data.leave_type == null || response.data.leave_type == '' || response.data.leave_type == 1) {
                    $scope.studentLeaveType = response.data.leave_type;
                    // console.log('status',$scope.studentLeaveType);
                }
                if ($scope.leaveStyle.leave_days_times != null && $scope.studentLeaveType == null) {
                    $scope.LeaveDays = response.data.leave_days_times;
                    $scope.leaveLimitStatus = 2;
                    $scope.leaveData1 = true;
                    $scope.leaveData2 = false;

                }
                if ($scope.leaveStyle.leave_least_days != null && $scope.leaveStyle.leave_total_days != null && $scope.studentLeaveType == null) {
                    $scope.leaveLeastDays = parseInt(response.data.leave_least_days);
                    $scope.leaveTotalDays = parseInt(response.data.leave_total_days);
                    $scope.leaveLimitStatus = 1;
                    $scope.leaveData1 = true;
                    $scope.leaveData2 = true;
                }
                // if ($scope.leaveStyle.leave_days_times == null && $scope.leaveStyle.leave_least_days == null && response.data.leave_total_days == null) {
                if ($scope.studentLeaveType == 1 && $scope.leaveStyle.student_leave_limit != null) {
                    $scope.LeaveDays = response.data.student_leave_limit;
                    $scope.leaveLimitStatus = 3;
                    $scope.leaveData2 = true;
                    $scope.leaveData1 = false;
                }
                if ($scope.studentLeaveType == 1 && $scope.leaveStyle.cardStudentLeaveType != null) {
                    $scope.LeaveDays = response.data.cardStudentLeaveType;
                    $scope.leaveLimitStatus = 3;
                    $scope.leaveData2 = true;
                    $scope.leaveData1 = false;
                }
                if ($scope.studentLeaveType == 1 && $scope.leaveStyle.student_leave_limit == null && $scope.leaveStyle.cardStudentLeaveType == null) {
                    Message.warning('请设置学生请假天数!');
                    return;
                }
            })
        } else {
            $scope.leaveData1 = false;
            $scope.leaveData2 = false;
        }

    };
    //请假结束时间变化
    $scope.endLeaveDate = function (endLeaveDay){
        $scope.getDateDays($scope.startLeaveDay, $scope.endLeaveDay);
        if ($scope.leaveStyle == undefined) {
            Message.warning('请先选择会员卡！');
            return;
        }
        else{
            // var endTime = new Date(endLeaveDay).getTime() / 1000;
            // if (endTime > parseInt($scope.memberCardEndTime)) {
            //     Message.warning('请假的结束日期不能大于会员卡的到期日期！');
            //     return;
            // }
        }

    };

    // $scope.startLeaveDate = function (starDate) {
    //     $scope.getDateDays($scope.startLeaveDay,$scope.endLeaveDay);
    //     // console.log('请假开始时间',starDate);
    //     var starLeaveTime = new Date(starDate + ' ' + '00:00:01').getTime();
    //     $scope.startLeaveDate11 = parseInt(starLeaveTime);
    //     $scope.startLeaveDateTime = starDate + ' ' + '00:00:01';
    //     if ($scope.selectLeaveDays != undefined && $scope.selectLeaveDays != '' && $scope.selectLeaveDays != null) {
    //         // console.log( starDate + '---'+$scope.selectLeaveDays);
    //         var endTime = starLeaveTime + $scope.selectLeaveDays * 24 * 60 * 60 * 1000 -2000;
    //         // console.log(starLeaveTime)
    //         // console.log(endTime/1000)
    //         // console.log(parseInt($scope.memberCardEndTime))
    //         if ((endTime / 1000) > parseInt($scope.memberCardEndTime)) {
    //             Message.warning('请假的结束日期不能大于会员卡的到期日期！');
    //             return;
    //         }
    //     }
    //
    // }

    //实现请假功能
    //    会员卡状态：1、正常 2、冻结 3、过期 4、未激活
    $scope.submitLeave = function () {
        var leaveData = {
            leaveType: $scope.leaveTypeChecked != undefined && $scope.leaveTypeChecked != '' ? $scope.leaveTypeChecked : null,//请假类型
            leavePersonId: parseInt($scope.leaveMemberId),//会员id
            leaveReason: $('#leaveCause').val(),//请假原因
            _csrf_backend: $('#_csrf').val(),
            leaveStartTime: $scope.leaveStartTimesTamp,//开始时间
            leaveEndTime: $scope.leaveEndTimesTamp,//结束时间
            leaveTotalDays: $scope.leaveAllDays,//请假总天数
            leaveArrayIndex: $scope.leaveIndex != undefined && $scope.leaveIndex != '' ? $scope.leaveIndex : null,//请假天数
            leaveLimitStatus: $scope.leaveLimitStatus,
            memberCardId: $('#selectCard').val()
        };
        if ($scope.leaveTypeChecked == '' || $scope.leaveTypeChecked == null || $scope.leaveTypeChecked == undefined) {
            Message.warning('请选择请假类型');
            return;
        }

        if (leaveData.memberCardId == '' || leaveData.memberCardId == null || leaveData.memberCardId == undefined) {
            Message.warning('请选择您的会员卡');
            return;
        }
        if ($scope.leaveStartTimesTamp == '' || $scope.leaveStartTimesTamp == undefined || $scope.leaveStartTimesTamp == '') {
            Message.warning('请选择请假开始时间');
            return;
        }
        if($scope.startTimeMin > $scope.leaveStartTimesTamp) {
            $scope.showLastLeaveTime = $scope.getMyDate($scope.startTimeMin * 1000);
            toastr.warning('请假开始时间不能早于上次请假结束时间（' + $scope.showLastLeaveTime + '）！', '提示', {
                progressBar  : true,
                positionClass: 'toast-top-center',
                closeButton  : true,
                timeOut      : 2500
            });
            $('.leaveAllDaysSpans').text('');
            $('.leaveDateStartInput').val('');
            $('.leaveDateEndInput').val('');
            return;
        }
        // if($scope.leaveTypeChecked == '1'){
        //     if($scope.leaveAllDays > $scope.leaveTotalDays){
        //         Message.warning('请假时间过长，请重新选择');
        //         return;
        //     }
        //     else if($scope.leaveAllDays < $scope.leaveLeastDays){
        //         Message.warning('请假时间过短，请重新选择');
        //         return;
        //     }
        // }
        if ($scope.leaveEndTimesTamp == '' || $scope.leaveEndTimesTamp == undefined || $scope.leaveEndTimesTamp == '') {
            Message.warning('请选择请假结束时间');
            return;
        }
        $scope.leavePost = function () {
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
                    $scope.initPath();
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
    };
    //私课主页
    $(document).on('click', '.privateLessonBuy', function () {
        $scope.buyClassDepositSelectChange();
        $("#privateBuyModal").modal("show");
        $scope.getSellSources();
        $scope.privateLessonBuyButtonFlag = false;
        // $scope.dataCompleteBuy.renewalDate = $scope.getDateTime();
        var now = new Date().getTime();
        $scope.currentTime = now / 1000;
        $scope.paymentTerm = '';
        $scope.giftStatus = '';
        $scope.PayMoney = '';
        $scope.blendMoney = 0;
        $scope.trues = false;
        $scope.privateLessonBuyMemberID = $scope.id;
        $scope.addSellSourceId = "";
        $scope.dataCompleteBuy.paymentMethod = '';
        $scope.dataCompleteBuy.distributionChannel = '';
        $scope.dataCompleteBuy.sellingPrivateEducation = '';
        $scope.dataCompleteBuy.renewalDate = '';
        $scope.dataCompleteBuy.numberOfCourses = '';
        $scope.num = 0;
        $scope.attr = '';
        $scope.addBuyClassHtml = '';
        $scope.buyClassMethodArr      = [];
        $scope.buyClassPriceArr      = [];
        $scope.buyClassArr = [];
        $scope.allBuyClassPrice='';
        $scope.classSurplusPrice='';
        $scope.allClassDepositMoneyAmount='';
        $scope.buyClassInput='';
        $scope.AllMoney123='';
        $('.buyClassMethodSelect').val('');
        $('.buyClassPriceSelect').val('');
        $scope.repeatDom1 = function() {
            for(var i = 0;i<$('.buyClassMethodBox').children().length;i++) {
                if($('.newSelectDom1').length != 0) {
                    $('.newSelectDom1').remove();
                }
            }
        }
        $scope.repeatDom1();
        $scope.addBuyClassMethod();
        $scope.voucher123 = parseInt($(this).find('.voucherOpcitySpan1').html());  //抵劵金额
        $scope.subscription123 = parseInt($(this).find('.priceOpcitySpan').html());     //订金
        $scope.voucherStartTime = parseInt($(this).find('.startTimeOpcitySpan1').html());//开始时间
        $scope.voucherEndTime = parseInt($(this).find('.endTimeOpcitySpan1').html());  //结束时间
        // if ($scope.voucher123 != null && $scope.voucherStartTime <= $scope.currentTime && $scope.currentTime <= $scope.voucherEndTime) {
        //     $(".voucherB").html($scope.voucher123);
        //     $(".subcriptionB").text($scope.subscription123);
        // } else {
        //     $(".voucherB").html("0");
        //     $(".payCouserMoney").text($scope.PayMoney);
        //     $(".subcriptionB").text($scope.subscription123);
        // }
        $http.get('/potential-members/get-member-deposit-one?memberId=' + $scope.id).then(function (response) {
            if (response.data != null) {
                $scope.orderid123 = response.data.id;
            }
        });
        // console.log(object);
        $('#registerDate').val('');
        $http({
            method: "get",
            url: "/user/member-details-card?MemberId=" + $scope.id
        }).then(function (data) {
            $scope.aboutClassData = data.data;
        }, function (error) {
            Message.error("系统错误请联系工作人员");
        });

        $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
            $scope.privateCoach = data.data
        }, function (error) {
            // console.log(error)
            Message.error("系统错误请联系工作人员")
        });
        $scope.monthUpNums = '';
    });

    //收款方式下拉
    $scope.gatheringWay = function (value) {
        if($scope.blendMoney == undefined){
            $scope.blendMoney = 0;
        }
        $scope.AllMoney123 = $scope.blendMoney;//总金额
        if (parseInt(value) == 2) {
            $scope.getClassDepositAdd();
            $('.moneySelect').attr('disabled',false);
            if ($scope.allClassDepositMoneyAmount != null) {
                if($scope.AllMoney123 < parseFloat($scope.allClassDepositMoneyAmount)) {
                    Message.warning('选择定金总金额不得大于购课的总金额！');
                    $scope.allClassDepositMoneyAmount = 0.00;
                    $scope.buyClassDepositSelect = 0;
                    // $('.select2-selection__rendered').children().remove();
                    $scope.PayMoney = $scope.AllMoney123 - parseFloat($scope.allClassDepositMoneyAmount);
                }else {
                    // $(".subcriptionB").text($scope.allClassDepositMoneyAmount);
                    $scope.PayMoney = $scope.AllMoney123 - parseFloat($scope.allClassDepositMoneyAmount);
                    // $(".payCouserMoney").text($scope.PayMoney);
                }
            }  else if(isNaN($scope.PayMoney)){
                $scope.PayMoney = '';
            }
            else {
                $scope.PayMoney = $scope.AllMoney123;
            }
        } else {
            // $(".subcriptionB").text($scope.allClassDepositMoneyAmount);
            $scope.PayMoney = $scope.AllMoney123;
            // $(".payCouserMoney").text($scope.PayMoney);
            $scope.allClassDepositMoneyAmount = 0.00;
            $scope.buyClassDepositSelect = 0;
            $('.select2-selection__rendered').children().remove();
            $('.moneySelect').attr('disabled',true)
        }
    };

    //总金额发生变化时
    $scope.allMoneyChange = function (money) {
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
        $(".moneySelect").select2({
            multiple: true,
            dropdownParent: $("#privateBuyModal"),
            width: "100%"
        });
        $(".typeSelect").select2({
            width: "88%",
            placeholder: "请选择"
        });
        $(".sellerSelect").select2({
            width: "88%"
        });
        $(".telPhoneNumber").select2({
            width: 250
        });
        $(".typeSelectS").select2({
            width: "88%",
            placeholder: "请选择",
            dropdownParent: $("#myModals15")
        });
    });

    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };

    $scope.blendMoney = 0;
    //选择课程 单节
    $scope.selectPrivateCourseSingle = function (id, pic, name, chargeType, array, unitPrice, newMember, month_up_num) {
        $scope.trues = true;
        $scope.blendId = id;
        if (pic == '') {
            $scope.blendPic = '/plugins/user/images/noPic.png';
        } else {
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
        if (month_up_num != '' && month_up_num != null && month_up_num != undefined) {
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
        if (pic == '') {
            $scope.blendPic = '/plugins/user/images/noPic.png';
        } else {
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
    };
    $scope.getClassDepositArr = function(){
        $scope.classDepositIdArr= [];
        $scope.classDepositMoneyArr = [];
        $scope.classDepositMoneySurplusArr = [];
        var $depositDiv = $('.moneySelect').children('option');
        $depositDiv.each(function (index,item) {
            if($(this).is(':checked')) {
                var _id = $(this).val();
                var _price = $(this).data('price');
                $scope.classDepositIdArr.push(_id);
                $scope.classDepositMoneyArr.push(_price);
            }else {
                var _price = $(this).data('price');
                $scope.classDepositMoneySurplusArr.push(_price);
            }
        })
    }
    $scope.getClassDepositAdd = function () {
        $scope.getClassDepositArr();
        //计算选中的用于抵扣的定金总额
        $scope.allClassDepositMoneyAmount = parseFloat($scope.isCompare($scope.classDepositMoneyArr)).toFixed(2);
    }

    //定金金额遍历
    $scope.buyClassDepositSelectChange = function () {
        $http.get('/member/deposit-data-type?memberId='+$scope.id+'&type=2').then(function (data) {
            $scope.buyClassDepositSelectData = angular.fromJson(data.data.type);
        })
        $scope.getClassDepositArr();
        $scope.classSurplusPrice = parseFloat($scope.isCompare($scope.classDepositMoneySurplusArr));
        $scope.gatheringWay($scope.paymentTerm);
    };

    //新增销售来源
    $scope.addSellSource = function () {
        $scope.customSalesChannels = '';
        $('#sellSource1').modal('show');
    };
    //新增销售来源提交
    $scope.confirmAdd = function (data) {
        if ($scope.customSalesChannels == "" || $scope.customSalesChannels == null || $scope.customSalesChannels == undefined) {
            Message.warning("请输入自定义销售渠道");
        } else {
            $("#sellSource1").modal('hide');
            var dataJson = {
                source: data,
                scenario: "source",
                configType: 'charge',
                _csrf_backend: $('#_csrf').val()
            };
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
    };

    $scope.selectSalesSources = function (value) {
        $scope.SalesSources = value;
        $scope.sellSourceId = value;

    };

    //获取销售来源下拉
    $scope.getSellSources = function () {
        $http({
            url: '/potential-members/get-source?configType=charge',
            method: 'get'
        }).then(function (data) {
            $scope.memberSearchData = data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };


    //删除销售来源
    $scope.deleteTheSource = function () {
        var id = $('#marketingChannel').find('option:selected').data('module');
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

        if ($scope.monthUpNum != '' && $scope.monthUpNum != undefined && $scope.monthUpNum != null) {
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
                    if ($scope.arrayUnitPrice.hasOwnProperty(key)) {
                        var element = $scope.arrayUnitPrice[key];

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
                            $scope.blendMoney = value * element.posPrice;
                            continue
                        }
                        if (element.intervalEnd < value && value > element.intervalStart && $scope.newMember == false && (lengthArray - 1) == key) {
                            $scope.blendMoney = value * element.unitPrice;
                            continue
                        }
                    }
                }
            }
        }
        ;

        $scope.paymentTerm = '';

    };
    $('#marketingChannel').on('change', function () {
        selectColor();
    });
    $('#selectColor').on('change', function () {
        selectColor();
    });
    //点击返回按钮跳转购买私课
    $scope.backBuyPrivate = function () {
        $('#privateBuyModal').modal('show');
        $('#selectPrivateCourseModal').modal('hide');
        $scope.dataCompleteBuy = '';
    };
    //获取选择信息
    $scope.dataCompleteBuy = {
        numberOfCourses: '',
        renewalDate: '',
        id: '',
        distributionChannel: '',
        paymentMethod: ''
    };
    //获取支付方式和支付价格
    $scope.getBuyClassArr = function(){
        $scope.buyClassArr= [];//支付方式与支付价格的数组对象
        $scope.buyClassMethodArr = [];
        $scope.buyClassPriceArr = [];
        var $buyClassDiv = $('.addBuyClassElement').children('div');
        $buyClassDiv.each(function (index,item) {
            var _buyClassMethod = $(this).find('select').val();
            var _buyClassPrice = $(this).find('input').val();
            if( _buyClassPrice == ''){
                _buyClassPrice = 0;
            }
            var data = {
                type  : _buyClassMethod,
                price : _buyClassPrice
            }
            $scope.buyClassArr.push(data);
            $scope.buyClassMethodArr.push(_buyClassMethod);
            $scope.buyClassPriceArr.push(_buyClassPrice);
        })
    }
    $scope.buyClassInputChange = function () {
        $scope.getBuyClassArr();
        $scope.allBuyClassPrice = parseFloat($scope.isCompare($scope.buyClassPriceArr));
    }
    $scope.buyClassInputChange123 = function () {
        $timeout(function () {
            $scope.buyClassInputChange();
        },100)
    }
    $scope.buyClassInputChange321 = function(){
        $timeout(function(){
            $scope.getBuyClassArr();
            if($scope.isRepeat($scope.buyClassMethodArr)) {
                Message.warning('付款途径不能有重复！');
                return;
            }
        },100)
    }
    //完成购买
    $("#completeBuy").click(function () {
        $scope.privateLessonBuyButtonFlag = false;
        $scope.getBuyClassArr();
        if (!$scope.privateLessonBuyButtonFlag) {
            if ($scope.paymentTerm == '') {
                Message.warning("请选择付款方式");
                return;
            }
            if ($scope.PayMoney == null || $scope.PayMoney == undefined) {
                $scope.PayMoney = $scope.blendMoney;
            }
            if ($scope.paymentTerm == '1') {
                $scope.PayMoney = $scope.blendMoney;
            }
            var data123 = {
                memberId   : $scope.privateLessonBuyMemberID,                //会员id
                chargeId   : $scope.blendId,                                 //私教课程id
                chargeType : $scope.chargeType,                              //课程区别
                nodeNumber : $scope.dataCompleteBuy.numberOfCourses,         //课程节数
                coachId    : $scope.dataCompleteBuy.sellingPrivateEducation, //销售私教id
                saleType   : $scope.dataCompleteBuy.distributionChannel,     //销售渠道
                renewTime  : $('#registerDate').val(),                       //缴费日期
                payType  : $scope.paymentTerm,           //支付方式 （全款、押金）
                payMethod  : $scope.buyClassArr,                           //支付途径
                depositArrId  : $scope.classDepositIdArr          != undefined && $scope.classDepositIdArr          != "" ? $scope.classDepositIdArr                 : null, //包含所有定金Id的数组
                giftStatus : $scope.giftStatus,                              //领取赠品
                totalPrice : $scope.blendMoney,                              //总价钱
                netPrice   : $scope.PayMoney,                                //实收金额
                cashCoupon : $scope.voucher123,                              //抵劵
                deposit    : $scope.allClassDepositMoneyAmount,                         //订金
                endTime    : $scope.monthUpNums,                             //结束时间
                buyNote    : $scope.buyProNote,                              //购买备注
                _csrf_backend: $('#_csrf').val()
            };
            if ($scope.blendId == '' || $scope.blendId == undefined || $scope.blendId == null) {
                Message.warning("请选择私教产品");
                return
            }
            if ($scope.paymentTerm == 2 && $scope.voucherStartTime < $scope.currentTime && $scope.currentTime > $scope.voucherEndTime){
                // Message.warning("暂无定金或定金已过有效期");
                // return
            }
            if ($scope.dataCompleteBuy.numberOfCourses == '' || $scope.dataCompleteBuy.numberOfCourses == undefined || $scope.dataCompleteBuy.numberOfCourses == null) {
                Message.warning("请选择课程节数");
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
                Message.warning("请选择是否领取过赠品");
                return
            }

            for(var i = 0;i<$scope.buyClassMethodArr.length;i++) {
                if($scope.buyClassMethodArr[i] == null || $scope.buyClassMethodArr[i] == undefined || $scope.buyClassMethodArr[i] == '') {
                    Message.warning('付款途径或价格不能为空，请重新输入！');
                    return
                }
            }
            for(var i = 0;i<$scope.buyClassPriceArr.length;i++) {
                var regTest = /^\d+(\.\d{1,2})?$/;
                if(!regTest.test($scope.buyClassPriceArr[i])) {
                    Message.warning('金额只能输入整数或两位以内小数！');
                    return;
                }
            }
            $scope.isRepeat($scope.buyClassMethodArr);
            if($scope.isRepeat($scope.buyClassMethodArr)) {
                Message.warning('付款途径不能有重复，请重新输入！');
                return;
            }
            if ($scope.paymentTerm == 1 ) {
                if (parseFloat($scope.isCompare($scope.buyClassPriceArr)) != parseFloat($scope.PayMoney)){
                    Message.warning("所有付款途径价格之和必须等于应付金额！");
                    return;
                }
            }else{
                if ($scope.allBuyClassPrice != parseFloat($scope.PayMoney)){
                    Message.warning("所有付款途径价格之和必须等于应付金额！");
                    return;
                }
            }
            // if ($scope.dataCompleteBuy.paymentMethod == '' || $scope.dataCompleteBuy.paymentMethod == undefined) {
            //     Message.warning("请选择支付方式");
            //     return
            // }
            if ($scope.dataCompleteBuy.sellingPrivateEducation == '' || $scope.dataCompleteBuy.sellingPrivateEducation == undefined) {
                Message.warning("请选择销售教练");
                return
            }
            $scope.privateLessonBuyButtonFlag = true;
            var url = '/user/save-member-charge';
            $http({
                method: 'POST',
                url: url,
                data: $.param(data123),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data) {
                if (data.data.status === "success") {
                    Message.success(data.data.data);
                    $('#privateBuyModal').modal('hide');
                    /*if (parseInt($scope.paymentTerm) == 2) {
                        $http.get('/sell-card/del-deposit?id=' + $scope.orderid123).then(function (reponse) {
                        });
                    }*/
                    $scope.blendMoney = '';
                }
                else {
                    $scope.privateLessonBuyButtonFlag = false;
                    Message.warning(data.data.data)
                }
                $scope.blendId = '';
                $scope.dataCompleteBuy.numberOfCourses = '';
                $scope.dataCompleteBuy.renewalDate = '';
                $scope.dataCompleteBuy.sellingPrivateEducation = '';
                $scope.dataCompleteBuy.distributionChannel = '';
                $scope.paymentTerm = '';
                $scope.dataCompleteBuy.paymentMethod = '';
                $scope.blendPic = null;
                $scope.trues = false;
                $scope.monthUpNums = '';
            }, function (error) {
                console.log(error)
                Message.error("系统错误请联系工作人员")
            });
        }
    });


    $(function () {
        // 获取今日时间戳
        $scope.getTodayTimesTamp = function (){
            var timetemps = new Date();
            timetemps.setHours(0);
            timetemps.setMinutes(0);
            timetemps.setSeconds(0);
            timetemps.setMilliseconds(0);
            $scope.todaytimetemps = Date.parse(timetemps)/1000; //获取当前时间戳
        };
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
            todayBtn: true//今日按钮
        }).on('changeDate',function (ev) {
            if($(".leaveDateStartInput").val() != ''){
                var leaveStart = $(".leaveDateStartInput").val() + " " + "00:00:00";
                $scope.leaveStartTimesTamp = Date.parse(leaveStart)/1000;
            }else {
                $scope.leaveStartTimesTamp = '';
            }
        });
        // 请假结束日期插件的js
        $("#dataLeaveEnd").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true//今日按钮
        }).on('changeDate',function (ev){
            if($scope.leaveTypeChecked !=  ''){
                if($(".leaveDateEndInput").val() != '' && $(".leaveDateStartInput").val() != ''){
                    var leaveEnd = $(".leaveDateEndInput").val() + " " + "23:59:59";
                    $scope.leaveEndTimesTamp = Date.parse(leaveEnd)/1000;
                    if($scope.leaveStartTimesTamp > $scope.leaveEndTimesTamp){
                        Message.warning("结束日期应在开始日期之后");
                        $scope.leaveStartTimesTamp = '';
                        $scope.leaveEndTimesTamp   = '';
                        $(".leaveDateStartInput").val("");
                        $(".leaveDateEndInput").val("");
                        $('.leaveAllDaysSpans').text('');
                    }
                    // else if($scope.leaveEndTimesTamp < $scope.todaytimetemps){
                    //     Message.warning("请假结束日期不能小于今天");
                    //     $(".leaveDateStartInput").val("");
                    //     $(".leaveDateEndInput").val("");
                    // }
                    else{
                        $scope.mathAroundLeaveDate();
                    }
                }
                else if($(".leaveDateStartInput").val() == ''){
                    Message.warning("请先选择开始日期");
                    $scope.leaveStartTimesTamp = '';
                    $scope.leaveEndTimesTamp   = '';
                    $(".leaveDateEndInput").val("");
                }
                else{
                    Message.warning("请选择结束日期");
                    $scope.leaveEndTimesTamp = '';
                }
            }
            else{
                Message.warning("请先选择请假类型");
                $(".leaveDateStartInput").val("");
                $(".leaveDateEndInput").val("");
            }
        });
        $("#giveCourseDate").datetimepicker({
            minView: "month",//设置只显示到月份
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true//今日按钮
        });
    });

    // 计算正常请假时长
    $scope.mathAroundLeaveDate = function (){
        $scope.leaveAllDays = (($scope.leaveEndTimesTamp - $scope.leaveStartTimesTamp)/24/60/60).toFixed(0);
        if(!isNaN($scope.leaveAllDays)){
            $(".leaveAllDaysSpans").text($scope.leaveAllDays);
        }
        else{
            $scope.leaveAllDays = 0;
        }
    };

    //选择新的会员卡种
    $scope.selectNewMemberCard = function () {
        $('#selectCardList').modal('show');
    };
    //返回升级会员卡模态框
    $scope.backUpgrade = function () {
        $('#memberCardUpgradeModal').modal('show');
        $('#selectCardList').modal('hide');
    };

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
                $scope.initPath();
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
        $scope.iboxCcontentModel = '';
        $scope.courseDelayRecordData = '';
    };
    //消费记录排序
    $scope.recordsOfConsumption = function (type, sort) {
        $scope.recordsOfConsumptionType = type;
        $scope.switchSort(sort);
        $scope.getHistoryData()
    };

    $scope.recordsOfConsumptionData = function () {
        return {
            memberId: $scope.id,
            sortType: $scope.recordsOfConsumptionType,
            sortName: $scope.sort
        }
    };
    /******获取消费记录表信息*******/
    $scope.getHistoryData = function () {
        var data = $scope.recordsOfConsumptionData();
        $http.get('/user/consumption-info?' + $.param(data)).success(function (response) {
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
        $scope.delCabinetMemberId = id;
        $scope.getCabinetData(id);
    };
    /******获取柜子表信息*******/
    $scope.getCabinetData = function () {
        $http.get($scope.MAIN.API.cabinetPath).then(function (response) {
            $scope.cabinets = response.data.data;
            $scope.cabinetPages = response.data.page;
            if ($scope.cabinets.length > 0) {
                $scope.cabinetNoDataShow = false;
            } else {
                $scope.cabinetNoDataShow = true;
            }
        });
    };
    $scope.memberConsumList = function (urlPages) {
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
            if (response.gift == undefined || response.gift == null || response.gift == "") {
                $scope.giftNoDataShow = true;
                $scope.giftList = response.gift;
                $scope.giftPages = response.pages;
            }
            else {
                $scope.giftNoDataShow = false;
                $scope.giftList = response.gift;
                $scope.giftPages = response.pages;
            }
        });
    };
    $scope.replaceGiftPage = function (urlPages) {
        $scope.getGiftUrl = urlPages;
        $scope.getGiftRecordData();
    };

    // 点击领取赠品
    $scope.receiveGift = function (id) {
        $scope.giftIdReceive = id;
        $http.get("/user/update-gift-status?id=" + $scope.giftIdReceive).success(function (data) {
            if (data.status == "success") {
                Message.success(data.data);
                $scope.getGiftRecordData();
            } else {
                Message.warning(data.data);
            }

        })
    };

    /******点击私课触发事件*******/
    $scope.getChargeClass = function (id) {
        $scope.getChargeClassData(id);
    };
    //选择列表下拉
    $scope.selectPTRecord  =function(val){
        if(val == '2'){
            $timeout(function(){
                $scope.getICheck();
            },100);
        }else if(val == '3'){
            if ($scope.privateLessonTemplet != null && $scope.privateLessonTemplet != '' && $scope.privateLessonTemplet != undefined) {
                //获取私教课程延期记录
                $http.get("/user/course-delay-record?memberId="+$scope.privateLessonTemplet.member_id+"&course_order_id="+$scope.privateLessonTemplet.id).then(function (response) {
                    if (response.data.length > 0) {
                        $scope.courseDelayRecordFlag = false;
                    }else{
                        $scope.courseDelayRecordFlag = true;
                    }
                    $scope.courseDelayRecordData = response.data;
                })
            }else {
                $scope.courseDelayRecordFlag = true;
            }

        }
    }

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
            $timeout(function(){
                $scope.getICheck();
            },100);
        });
    };
    $scope.chargeClass = function (urlPages) {
        $scope.MAIN.API.chargePath = urlPages;
        $scope.getChargeClassData();
    };
    /******点击私课触发详细事件*******/
    $scope.getChargeClassDetail = function (id, idd) {
        $scope.getChargeData(id, idd);
        $scope.chargeIdMemberBuy = idd;
    };
    /******获取私课上课记录表数据*******/
    $scope.getChargeData = function (id, idd) {
        $http.get("/user/class-record-info?MemberId=" + $scope.id + '&charge_id=' + idd).success(function (response) {
            $scope.records = response.record;
        });
    };

    /******点击进场选项触发事件*******/
    $scope.getEntryRecord = function (id) {
        //调用开始日期
        $("#datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            startDate: '2008-08-08'
        }).on("click", function () {
            $("#datetimeStart").datetimepicker("setEndDate", $("#datetimeEnd").val());
        });
        if(!$('.depositSelect').hasClass('DN')) {
            $('.depositSelect').addClass('DN')
        }
        $scope.searchParams = '';
        $scope.InitUrl = '&entryTime=';
        //$scope.getGiftRecordData();
        $scope.getEntryRecordData(id);
        $scope.getMemberSendCardRecord();
        // 到场离场记录日期插件调用
        $("#backDateTimeInfo").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        }).on('changeDate', function(ev){
            $scope.backListTime = $("#backDateTimeInfo").val();
            $scope.InitUrl      = '&entryTime=' + $scope.backListTime;
            $scope.getEntryRecordData();
        });
        // 清空到场离场记录
       $scope.initBackDateTimeInfo = function (){
            $("#backDateTimeInfo").val("");
            $scope.backListTime = "";
            $scope.InitUrl      = '&entryTime=' + $scope.backListTime;
            $scope.getEntryRecordData();
        };
        $scope.selectEntryRecord = '';
    };

    /******获取进场表信息*******/
    $scope.getEntryRecordData = function () {
        $http.get($scope.MAIN.API.entryRecordPath + $scope.InitUrl).success(function (response) {
            if (response.entry == undefined || response.entry == '') {
                $scope.entryNoDataShow = true;
                $scope.entrys     = response.entry;
                $scope.count      = response.count;
                $scope.entryPages = response.pages;
            } else {
                $scope.entryNoDataShow = false;
                $scope.entrys     = response.entry;
                $scope.count      = response.count;
                $scope.entryPages = response.pages;
            }
            // $scope.entrys     = response.entry;
            // $scope.count      = response.count;
            // $scope.entryPages = response.pages;

        });
    };
    $scope.replaceEntryPages = function (urlPages) {
        $scope.MAIN.API.entryRecordPath = urlPages + $scope.InitUrl;
        $scope.getEntryRecordData();
    };

    //获取会籍变更记录
    $scope.getSellChangeRecords = function () {
        $.loading.show();
        $http.get('/member/consultant-change?memberId=' + $scope.MemberId123).then(function (response) {
            if(response.data.data.length === 0) {
                $scope.consultantChangeRecordNoData = true;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }else {
                $scope.consultantChangeRecordNoData = false;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }
            $.loading.hide();
        })
    };
    //会籍变更记录的分页
    $scope.consultantPages = function (url) {
        $.loading.show();
        $http.get(url).then(function (response) {
            if(response.data.data.length === 0) {
                $scope.consultantChangeRecordNoData = true;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }else {
                $scope.consultantChangeRecordNoData = false;
                $scope.consultantChangeRecord = response.data.data;
                $scope.consultantChangePage = response.data.page;
            }
            $.loading.hide();
        })
    };


    $scope.getBehaviorRecord = function () {
        $http.get($scope.behaviorRecordUrl).then(function (response) {
            if (response.data.data.length != 0) {
                $scope.behaviorRecordLists = response.data.data;
                $scope.behaviorRecordPages = response.data.page;
                $scope.behaviorRecordFlag = false;
            } else {
                $scope.behaviorRecordLists = response.data.data;
                $scope.behaviorRecordPages = response.data.page;
                $scope.behaviorRecordFlag = true;
            }
        });
    };

    //转卡记录获取
    $scope.turnCardRecord = function () {
        $http.get('/member/turn-card-info?memberId=' + $scope.MemberId123).then(function (response) {
            if(response.data.data =='' || response.data.data ==null || response.data.data ==undefined){
                $scope.noTransferData = true;
                $scope.turnCardRecordList = response.data.data;
            }else{
                $scope.noTransferData = false;
                $scope.turnCardRecordList = response.data.data;
            }
        })
    };


    //获取行为记录
    $scope.SelectMessage = function (value) {
        $scope.behaviorRecordUrl = '/user/information-records?memberId=' + $scope.MemberId123;
        $scope.getBehaviorRecord();
        $('.depositGetSelect').val('');
        $scope.depositSelect = '';
        // 私课延期的记录
        if(value == '1'){
            $scope.getGiftRecordData();
        }
        if(value == '4'){
            $scope.getDelayPrivateRecord();
        }
        if (value == '5')
            $scope.getGiftDaysInfoRecond();

        if(value == '6') {
            $('.depositSelect').removeClass('DN');
            $scope.getDepositAllMoney();
        }else {
            $('.depositSelect').addClass('DN');
        }
        if(value == '7') {
            $scope.getSellChangeRecords();
        }
        // if(value == '7') {
        //     $scope.getMateTypeRecord();
        // }
        if(value == '8') {
            $scope.turnCardRecord();
        }
    };

    // //获取用户的匹配属性记录数据
    // $scope.getMateTypeRecord = function () {
    //     $scope.httpUrl = '/card-type/member-matching-record?memberId='+$scope.renewUserId;
    //     $scope.getRecordList($scope.httpUrl);
    // };
    //
    // //获取记录
    // $scope.getRecordList = function (urlPages) {
    //     $http.get(urlPages).then(function (data) {
    //         if(data.data.data != undefined && data.data.data != null && data.data.data.length > 0) {
    //             $scope.mateRecordNoData = false;
    //             $scope.recordList = data.data.data;
    //             $scope.replaceMatchingPage = data.data.pages;
    //         }else {
    //             $scope.mateRecordNoData = true;
    //             $scope.recordList = data.data.data;
    //             $scope.replaceMatchingPage = data.data.pages;
    //         }
    //     })
    // };
    //
    // //分页函数
    // $scope.replaceMatchPages = function (urlPages) {
    //     $scope.getRecordList(urlPages);
    // };

    // 获取私课延期记录的方法
    $scope.getDelayPrivateRecord = function (){
        $http.get("/member/extension-record-info?memberId=" + $scope.MemberId123).success(function (data){
            console.log()
            if(data.extension.length != 0){
                $scope.delayPrivateRecordList = data.extension;
                $scope.priDelayNoDataShow = false;
            }else{
                $scope.delayPrivateRecordList = data.extension;
                $scope.priDelayNoDataShow = true;
            }

        });
    };

    // 获取赠送天数记录
    $scope.getGiftDaysInfoRecond = function (){
        $http.get("/member/give-day-info?memberId=" + $scope.id).success(function (data){
            if(data.data != '' && data.data != null && data.data != [] && data.data != undefined){
                $scope.giftDaysInfoRecondData = data.data;
                $scope.giftNoDataInfoHaShow   = false;
            }
            else{
                $scope.giftDaysInfoRecondData = data.data;
                $scope.giftNoDataInfoHaShow   = true;
            }
        });
    };
    //撤销赠送天数
    $scope.cancelGiftDay = function(giftId,memberCardId){
        $http.get("/member/cancel-gift-day?giftId=" + giftId + '&memberCardId=' + memberCardId).success(function (data){
            if(data.status == 'success'){
                Message.success(data.data);
                $scope.getGiftDaysInfoRecond();
            }else{
                Message.warning(data.data);
            }
        });
    };

    $scope.replaceInformationRecords = function (urlPages) {
        $scope.behaviorRecordUrl = urlPages;
        $scope.getBehaviorRecord();
    };

    $scope.getDepositAllMoney = function() {
        if($scope.depositSelect == undefined || $scope.depositSelect == null) {
            $scope.depositSelect = '';
        }
        $http.get('/member/member-deposit-list?memberId='+$scope.id+'&type='+$scope.depositSelect).then(function (data) {
            if(data != null && data !=undefined && data != '') {
                $scope.getDepositInfoData = data.data.deposit;
                if($scope.getDepositInfoData.length == 0) {
                    $scope.depositAllMoney = 0;
                    // $scope.depositAllVoucher = 0;
                    $scope.priDelayNoDataShow = true;
                }else {
                    $scope.depositAllMoney = data.data.allPrice;
                    // $scope.depositAllVoucher = data.data.allVoucher;
                    $scope.priDelayNoDataShow = false;
                }
            }
        })
    };
    //订金的信息记录
    $scope.depositSelectChange = function (val) {
        $scope.depositSelect = val;
        $scope.getDepositAllMoney();

    };
    //删除订金记录的按钮
    $scope.depositInfoDelete = function (id) {
        Sweety.remove({
            url: '/member/del-deposit-data?depositId=' + id,
            http: $http,
            title: '确定要删除吗?',
            text: '会员删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.depositSelect = '';
            $scope.getDepositAllMoney();
        })
    };

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
        // console.log($scope.memberPic);
        $('#myModals').modal('show');
        $scope.updateMemCard(id);
        var MemberId = id;
        if (id != undefined) {
            $http.get('/user/member-details-card?MemberId=' + id).then(function (result) {
                $scope.MemberDetailsUpdate = result.data;
                $scope.MemberDetailsUpdate.pic = result.data.pic;
                $scope.isFingerPrint = result.data.memberDetails.fingerprint;
                $scope.MemberDetailsUpdate.note = result.data.memberDetails.note;
                if($scope.isFingerPrint != null) {
                    $("#imgBoolTrue").addClass("imgBoolFalse");
                    $("#imgBoolTrue").removeClass("imgBoolTrue");
                    $('#imgBoolFalse').addClass("imgBoolTrue");
                    $("#imgBoolFalse").removeClass("imgBoolFalse");
                }else {
                    $("#imgBoolTrue").addClass("imgBoolTrue");
                    $("#imgBoolTrue").removeClass("imgBoolFalse");
                    $('#imgBoolFalse').addClass("imgBoolFalse");
                    $("#imgBoolFalse").removeClass("imgBoolTrue");
                }
                // $scope.memberPic             = result.data.pic;
                // console.log('修改',result)
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
                    $scope.IdCard = '该证件号已经存在';
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
    $scope.getMyDateTime = function(str){
        str = parseInt(str);
        if(str!=""||str!=null){
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth()+1;
            oMonth = oMonth>=10? oMonth:'0'+oMonth;
            var oDay = oDate.getDate();
            oDay = oDay>=10? oDay:'0'+oDay;
            var H = oDate.getHours();
            H = H>=10? H:'0'+H;
            var M = oDate.getMinutes();
            M = M>=10? M:'0'+M;
            var S = oDate.getSeconds();
            S = S>=10? S:'0'+S;
            var theDate = oYear+"-"+oMonth+"-"+oDay +' '+ H +':'+M+':'+S;
        }else{
            theDate = "";
        }
        return theDate
    };
    function add0(m){
        return m<10?'0'+m:m;
    }
    //选择框触发事件
    $scope.updateCardNumber = function (id, num,money,date,create,active,name,transfer,mcAttributes,status,leaveType,cardTypeId) {
        $.loading.show();
        $scope.cardTypeId = cardTypeId;
        // $('.cardTypeId').select2({width:'100%'});
        $('.select2-selection__rendered').css({'margin-top':'0'});
        // console.log($scope.cardTypeId);
        $scope.getCardTypeList(cardTypeId);
        $('#myModals18').modal('show');
        $scope.status = status;
        $scope.cardName = '';
        $scope.useCardDateTimetamps = null;
        $('#useCardDate').val('');
        $('#buyCardDate').val('');
        $('#buyCardDate').val($scope.getMyDateTime(create*1000));
        $('#postponeDate').val('');
        $('#postponeCause1').val('');
        $scope.buyCardDateTimetamps = create;
        if(active != null && active != '' && active != 'null'){
            $('#useCardDate').val($scope.getMyDateTime(active*1000));
            $scope.useCardDateTimetamps = active;
        }else{
            $scope.useCardDateTimetamps = null;
        }
        $scope.cardId      = id;
        $scope.number      = num;
        $scope.money       = money;
        $scope.cardName  =  name;
        $scope.assignment  =  transfer;
        $scope.cardAttributeEdit  = mcAttributes;
        $scope.cardLeaveType  = leaveType;
        $scope.assignmentNumber   = function(){
            $scope.transferNumber = $scope.assignment;
        }
        $scope.memberAttribute = function(){
            $scope.attributes = $scope.cardAttributeEdit;
        }
        $scope.cardTypeCharge = function(){
            $scope.leaveType = $scope.cardLeaveType;
        }
        var $date = new Date(date * 1000);
        var $year = $date.getFullYear();
        var $month = $date.getMonth() + 1;
        // console.log($date.getMonth());
        // console.log($month);
        var $day = $date.getDate();
        $scope.expireDate = $year + '-' + $month + '-' + $day;
    };
    //点击删除指纹
    $scope.memberDeleteFingerprint = function(id) {
        $scope.memberId_fingerprint = id;
        $http({
            method: 'get',
            url: '/member/update-fingerprint?memberId='+$scope.memberId_fingerprint
        }).then(function (data) {
            if(data.data.status == 'success') {
                Message.success(data.data.data);
                $("#imgBoolTrue").addClass("imgBoolTrue");
                $("#imgBoolTrue").removeClass("imgBoolFalse");
                $('#imgBoolFalse').addClass("imgBoolFalse");
                $("#imgBoolFalse").removeClass("imgBoolTrue");
            }else if(data.data.status == 'error'){
                Message.error(data.data.data);
            }
        })
    };
    //点击删除头像
    $scope.memberDeletePhoto = function(id) {
        $scope.MemberDetailsUpdate.pic = '';
        $scope.memberId_photo = id;
        $http({
            method: 'get',
            url: '/member/del-member-photo?memberId='+$scope.memberId_photo
        }).then(function (data) {
            if(data.data.status == 'success') {
                Message.success(data.data.data);
                $("#imgTrue").addClass("imgTrue");
                $("#imgTrue").removeClass("imgFalse");
                $('#imgFalse').addClass("imgFalse");
                $("#imgFalse").removeClass("imgTrue");
            }else if(data.data.status == 'error'){
                Message.warning(data.data.data);
            }
        })
    };

    //卡种获取
    $scope.getCardTypeList = function (id) {
        $http.get('/card-type/card-category-all?cardCategoryId=' + id).then(function (response) {
            // console.log(response);
            $scope.cardTypeList = response.data.data;
            $.loading.hide();
        })
    };

    //会员卡匹配
    $scope.memberCardMatch = function () {
        $scope.chooseVenue          = '';
        $scope.chooseNewCardType    = '';
        $scope.matchCardType1       = '';
        $scope.matchCardType2       = '';
        $scope.matchWithPeople      = '';
        $scope.matchVenueLimit      = '';
        $scope.matchTimeLimit       = '';
        $scope.matchGroupClass      = '';
        $scope.matchLeave           = '';
        $scope.matchGift            = '';
        $scope.matchTransfer        = '';
        $scope.matchContract        = '';
        $scope.matchValidityRenew   = '';
        $scope.matchCardTypeNote    = '';
        $('#matchingModal').modal('show');
    };
    //选择场馆change事件
    $scope.chooseVenueChange = function (venueId) {
        if(!venueId) {
            Message.warning('请选择场馆！');
            return false;
        }else {
            $http.get('/sell-card/card-category?venueId=' + venueId).success(function (data){
                $scope.getVenueCardItems = data;
            });
        }
    };
    //卡种点击事件
    $scope.isChooseVenue = function () {
        if(!$scope.chooseVenue) {
            Message.warning('请先选择场馆，再选择卡种！');
            $scope.getVenueCardItems = '';
            return false;
        }
    };
    //点击属性匹配模态框完成按钮
    $scope.matchCardTypeComplete = function(){
        $scope.mateChooseArr = [];
        for(var i = 0; i < $(".chooseCardType>div>label>input").length ; i++) {
            if($(".chooseCardType>div>label>input").eq(i).is(':checked')) {
                $scope.mateChooseArr.push(i+1);
            }
        }
        var request =  {
            oldCardId      : $scope.infoId,             //会员卡id
            cardCategoryId : $scope.chooseNewCardType,  //选择卡种id
            note           : $scope.note,               //备注
            checkArrId     : $scope.mateChooseArr,      //匹配的属性的id(选中的复选框的id)的数组
            _csrf_backend: $('#_csrf').val()
        };
        // 保存数据之前数据验证
        if(!$scope.chooseVenue) {
            Message.warning('请先选择所属场馆！');
            return ;
        }
        if(!$scope.chooseNewCardType) {
            Message.warning("请选择卡种!");
            return false;
        }
        if(!$scope.mateChooseArr || (Object.prototype.toString.call($scope.mateChooseArr)==='[object Array]'&&$scope.mateChooseArr.length===0)) {
            Message.warning("请选择需要匹配的属性!");
            return false;
        }
        $scope.checkButton = true;
        // 发送数据
        $http({
            url: "/card-type/matching-record-member-card",
            method: 'POST',
            data: $.param(request),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success("保存成功");
                $scope.getData();
                $('#matchingModal').modal('hide');
            }else {
                Message.warning(data.data.message);
            }
            $scope.checkButton = false;
        })
    };

    $scope.updateCardInfo = function () {
        $scope.data =
        {
            _csrf_backend: $('#_csrf').val(),
            cardName        : $scope.cardName,             //卡种名称
            number          : $scope.number,               //姓名
            money           : $scope.money,                //卡种金额
            expireDate      : $scope.expireDate,           //到期时间
            id              : $scope.cardId,               //会员id
            createTime      : $scope.buyCardDateTimetamps, //创建日期
            activeTime      : $scope.useCardDateTimetamps, //激活日期
            postponeDate    : $scope.postponeDate,          //延期开卡日期
            transferNumber  : $scope.transferNumber,        //会员卡转让次数
            attributes      : $scope.attributes,            //会员卡属性修改
            leaveType       : $scope.leaveType,              //请假类型 null正常 1学生请假
            cardCategoryId  : $scope.cardTypeId
        };
        // if ($scope.postponeDate != '' && $scope.postponeDate != undefined && $scope.postponeDate != null) {
        //     if ($('#postponeCause1').val() == '') {
        //         Message.warning('延期开卡原因不能为空!');
        //         return;
        //     }
        // }
        // console.log($scope.data);
        $http({
            url: '/user/member-card-edit',
            method: 'POST',
            data: $.param($scope.data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            if (data.status == "success") {
                Message.success(data.data);
            }else{
                Message.warning(data.data);
            }
            if ($('#postponeCause1').val() != '' && $scope.postponeDate != '' && $scope.postponeDate != undefined && $scope.postponeDate != null) {
                var data = {
                    _csrf_backend: $('#_csrf').val(),
                    memberId: $scope.MemberId123,//会员id
                    note: $('#postponeCause1').val(),
                    behaviorId: 3,
                    memberCardId: $scope.cardId//会员卡id
                }
                // console.log('延期原因',data);
                $http({
                    url: '/user/add-note',
                    method: 'POST',
                    data: $.param(data),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    // console.log('延期记录',response);
                });
            }
            $('#myModals18').modal("hide"); //关闭模态框
            $scope.infoChange();
            $scope.getCardInfoSelect();
        })
    };
    //接收值
    $scope.MemberInfo = function () {
        $http.get('/member/judge-mobile?mobile=' + $scope.MemberDetailsUpdate.mobile+'&memberId='+$scope.MemberDetailsUpdate.id).then(function (response) {
            if(response.data.status == 'error') {
                Message.warning('手机号已存在！');
                return false;
            }else {
                $scope.data =
                    {
                        _csrf_backend: $('#_csrf').val(),
                        name: $scope.MemberDetailsUpdate.name,            //姓名
                        sex: $scope.MemberDetailsUpdate.sex,             //性别
                        birth_date: $scope.MemberDetailsUpdate.birth_date,      //出生年月
                        // mobile: $scope.MemberDetailsUpdate.mobile,          //手机号
                        mobile   : $scope.MemberDetailsUpdate.mobile  != undefined && $scope.MemberDetailsUpdate.mobile != "" ? $scope.MemberDetailsUpdate.mobile  : 0, //手机号
                        id_card: $scope.MemberDetailsUpdate.id_card,         //身份证号
                        profession: $scope.MemberDetailsUpdate.profession,      //工作
                        note:$scope.MemberDetailsUpdate.note,
                        documentType:$scope.MemberDetailsUpdate.document_type,//证件类型
                        // counselor     : $scope.MemberDetailsUpdate.counselor,    //销售顾问
                        // counselor     : $("#counselor").val(),                   //销售顾问

                        fingerprint: templateDataArray,
                        family_address: $scope.MemberDetailsUpdate.family_address,  //家庭住址
                        id: $scope.MemberDetailsUpdate.id,
                        adviserId: $scope.MemberDetailsUpdate.counselor_id,
                        pic: $scope.MemberDetailsUpdate.pic
                        // icNumber: $scope.MemberDetailsUpdate.memberDetails.ic_number //ic绑定号码
                    };
                // if ($scope.MemberDetailsUpdate.mobile == undefined || $scope.MemberDetailsUpdate.mobile == '' || $scope.MemberDetailsUpdate.mobile == null) {
                //     Message.warning('请输入手机号');
                //     return;
                // }
                if ($scope.MemberDetailsUpdate.id_card == undefined || $scope.MemberDetailsUpdate.id_card == '' || $scope.MemberDetailsUpdate.id_card == null) {
                    Message.warning('请输入证件号');
                    return;
                }
                $http({
                    url: '/user/member-info-edit',
                    method: 'POST',
                    data: $.param($scope.data),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
                    Message.success(data.data);
                    $('#myModals').modal('hide');
                    $scope.initPath();
                    $scope.getData();            //调用员工
                })
            }
        });
    };
    //获取 会员详情信息  私课信息
    //获取课程下拉框
    $scope.privateLessonInformation = function () {
        $scope.appointmentRecord = [];
        $scope.appointmentRecordShow = true;
        $.loading.show();
        $http({method: 'get', url: '/user/get-charge-array-info?memberId=' + $scope.id}).then(function (data) {
            var $len = data.data.length;
            if ($len != 0) {
                $scope.privateLessonInformations = data.data;
                $scope.privateLessonSelect = $scope.privateLessonInformations[0].orderId;
                $scope.privateLessonSelectClick($scope.privateLessonSelect);
                $.loading.hide();
            }
            else{
                $scope.privateLessonSelect       = '';
                $scope.privateLessonInformations = data.data.length;
                $scope.privateLessonTemplet      = '';
                $scope.renewTheRecordDate        = '';
                $('.wSelectAddHiH').val('');
                $.loading.hide();
            }
        }, function (error) {
            // console.log(error);
            Message.error('系统错误请联系管理人员')
        })
    };
    //选择框触发事件
    $scope.privateLessonSelectClick = function (chargeIds) {
        $scope.iboxCcontentModel = '';
        $scope.chargeIds = chargeIds;
        $http({
            method: 'get',
            url: '/user/get-charge-info?memberId=' + $scope.id + '&chargeId=' + chargeIds
        }).then(function (data) {
            //console.log(data)
            $scope.privateLessonTemplet = data.data;
            $scope.restClassNum = data.data.overage_section;
            $scope.AllClassNum = data.data.course_amount;
            $scope.positionId123 = data.data.id;
            $scope.renewTheRecord($scope.privateLessonTemplet.orderId)
        }, function (error) {
            Message.error('系统错误请联系管理人员')
        });
        $scope.replacePersonalUrl = '/user/class-record-info?MemberId=' + $scope.id + '&charge_id=' + $scope.chargeIds;
        $scope.appointmentRecordListData();
         //console.log( $scope.replacePersonalUrl)
    };
    $scope.appointmentRecordListData = function () {
        $http({method: 'get', url: $scope.replacePersonalUrl}).then(function (data) {
            //console.log(data.data)
            if (data.data.record == null) {
                $scope.currentTimeS = new Date().getTime() / 1000;
                $scope.appointmentRecord = data.data.record;
                $scope.appointmentRecordShow = true
            } else {
                $scope.currentTimeS = new Date().getTime() / 1000;
                $scope.appointmentRecord = data.data.record;
                $scope.appointmentRecordShow = false
            }
            $scope.appointmentRecordDate = new Date().getTime();
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    };
    //续费记录
    $scope.renewTheRecord = function (orderId) {
        $http({
            method: 'get',
            url: '/user/get-charge-history?memberId=' + $scope.id + '&orderId=' + orderId
        }).then(function (data) {
            if (data.data.data == '' || data.data.data == undefined || data.data.data == null) {
                $scope.renewTheRecordDate     = data.data.data;
                $scope.payNoMoneyCouClassShow = true;
            } else {
                $scope.renewTheRecordDate     = data.data.data;
                $scope.payNoMoneyCouClassShow = false;
            }
        }, function (error) {
            // console.log(error)
            Message.error('系统错误请联系工作人员')
        })
    };
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
            $scope.privateLessonTemplet = data.data;
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
        });

        $('#privateBuy').modal('show');
    };


    //续费 选择销售教练
    $scope.selectAboutClassData = function (id) {
        $scope.selectAboutClassDataID = id
    };
    //初始化续费数据
    $scope.privateBuyDatas = {
        total: '', // 课程节数
        discount: '',//折扣
        sellingPrivateEducation: '',//销售教练
        remarks: '',//备注
        data: ""
    };
    //失去节数焦点 算出总钱数
    $scope.nodeNumberBlur = function (data) {
        $http({
            method: 'get',
            url: '/user/compute-price?chargeId=' + $scope.privateBuyData.chargeId + "&number=" + data + "&memberId=" + $scope.id
        }).then(function (success) {
            $scope.nodeNumberBlurData = success.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    };
    //完成续费
    $scope.OkRenewal = function () {
        //折扣
        var data = {
            memberId: $scope.memberId, //会员id
            chargeId: $scope.privateLessonSelect, //私教课程id
            nodeNumber: $scope.privateBuyDatas.total,//课程节数
            coachId: $scope.selectAboutClassDataID, //销售私教id
            overTime: $scope.privateBuyDatas.data,//延长结束日期
            note: $scope.privateBuyDatas.remarks, // 备注
            offer: $scope.privateBuyDatas.discount,//折扣
            scenario: 'carry',//续费标识
            _csrf_backend: $('#_csrf').val()
        }
        if (!$scope.RenewPTButtonFlag) {
            if (data.nodeNumber == undefined || data.nodeNumber == null || data.nodeNumber == '') {
                Message.warning('请输入课程节数')
                return
            }
            if (data.overTime == undefined || data.overTime == null || data.overTime == '') {
                Message.warning("请选择延长时间");
                return
            }
            if (data.coachId == undefined || data.coachId == null || data.coachId == '') {
                Message.warning('请输入销售私教');
                return
            }
            $scope.RenewPTButtonFlag = true;
            var url = '/user/save-member-charge';
            $http({
                method: 'POST',
                url: url,
                data: $.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data) {
                if (data.data.status === "success") {
                    Message.success(data.data.data);
                    $scope.privateLessonSelectClick($scope.chargeIds);
                    $scope.renewTheRecord($scope.privateLessonTemplet.orderId);
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
    };
    $scope.getMyDateCh = function (str) {
        str = parseInt(str);
        if (str != "" || str != null) {
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth() + 1;
            oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
            var oDay = oDate.getDate();
            oDay = oDay >= 10 ? oDay : '0' + oDay;
            var theDate = oYear + "年" + oMonth + "月" + oDay + "日";
        } else {
            theDate = "";
        }
        return theDate
    };
    $scope.getDateCh = function (str) {
        str = parseInt(str);
        if (str != "" && str != null && str != 0) {
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
    //修改剩余节数
    $scope.modifyCourseNum = function() {
        $scope.restClassNumNew = $('#modifyRestClassNumber').val();
        if(parseInt($scope.restClassNum) > parseInt($scope.AllClassNum)) {
            Message.warning('修改课程节数不能大于总课程节数!');
            return false;
        }
        $scope.getRestClassNumber = function() {
            return {
                chargeId: $scope.positionId123,//课程id
                classNumber: $scope.restClassNumNew,//数量
                _csrf_backend: $('#_csrf').val()
            }
        };
        $http({
            url: '/member/edit-personal',
            method:'POST',
            data: $scope.getRestClassNumber(),
            header:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function (response) {
            if(response.data.status == 'success') {
                Message.success('修改剩余节数成功！');
                $scope.privateLessonSelectClick($scope.positionId123);
                $('#ModifyClassNumModal').modal('hide');
            }else {
                Message.error('修改失败，请刷新后重试！');
            }
        })
    };
    //私课修改
    $scope.chargeEdit = function(){
        // $scope.defaultCourseId  = $scope.privateLessonTemplet.course_id;
        // $scope.courseId         = $scope.privateLessonTemplet.course_id;
        $scope.defaultPrivateId = $scope.privateLessonTemplet.private_id;
        $scope.privateId        = $scope.privateLessonTemplet.private_id;
        $scope.className        = $scope.privateLessonTemplet.name;
        $scope.totalMoney       = $scope.privateLessonTemplet.money_amount;
        $scope.totalNum         = $scope.privateLessonTemplet.course_amount;
        $scope.overageNum       = $scope.privateLessonTemplet.overage_section;
        $scope.createTime       = $scope.getDateCh($scope.privateLessonTemplet.create_at*1000);
        $scope.deadlineTime     = $scope.getDateCh($scope.privateLessonTemplet.deadline_time*1000);
        $scope.privateLessonTemplet.activeTime == null  ?  0 : $scope.privateLessonTemplet.activeTime;
        $scope.privateStartTime = $scope.getDateCh($scope.privateLessonTemplet.activeTime*1000);
        $('#ChargeUpdateModal').modal('show');
        $("#startTime111").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true
        })
        //获取课种下拉框
        $http({method: "get", url: "/rechargeable-card-ctrl/get-private-data"}).then(function (data) {
            $scope.courseDataSectle = data.data.venue;
        }, function (error) {
            Message.error("请联系工作人员");
        });
        //获取私教下拉框
        $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
            $scope.privateDataSectle = data.data;
        }, function (error) {
            Message.error("请联系工作人员");
        });
    };
    //获取课种下拉框
    /*$http({method: "get", url: "/rechargeable-card-ctrl/get-private-data"}).then(function (data) {
        $scope.courseDataSectle = data.data.venue;
    }, function (error) {
        Message.error("请联系工作人员");
    });
    //获取私教下拉框
    $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
        $scope.privateDataSectle = data.data;
    }, function (error) {
        Message.error("请联系工作人员");
    });*/
    //私课修改
    $scope.chargeClassUpdate = function () {
        $scope.getChargeClassUpdate = function() {
            return {
                chargeId     : $scope.positionId123,
                className    : $scope.className != undefined && $scope.className != '' ? $scope.className : null,//课程名称
                // courseId     : $scope.courseId != undefined && $scope.courseId != '' ? $scope.courseId : null,
                privateId    : $scope.privateId != undefined && $scope.privateId != '' ? $scope.privateId : null,//教练id
                totalMoney   : $scope.totalMoney != undefined && $scope.totalMoney != '' ? $scope.totalMoney : null,//总金额
                totalNum     : $scope.totalNum != undefined && $scope.totalNum != '' ? $scope.totalNum : null,//总节数
                overageNum   : $scope.overageNum != undefined && $scope.overageNum != '' ? $scope.overageNum : null,//剩余节数
                createTime   : $scope.createTime != undefined && $scope.createTime != '' ? $scope.createTime : null,//办理日期
                deadlineTime : $scope.deadlineTime != undefined && $scope.deadlineTime != '' ? $scope.deadlineTime : null,//到期时间
                privateStartTime : $scope.privateStartTime != undefined && $scope.privateStartTime != '' ? $scope.privateStartTime : null,//开课时间
                _csrf_backend: $('#_csrf').val()
            }
        };
        if(parseInt($scope.overageNum) > parseInt($scope.totalNum)){
            Message.warning('修改剩余节数不能大于总节数!');
            return false;
        }
        if ($scope.className == undefined || $scope.className == '' || $scope.className == null) {
            Message.warning('请输入课程名称');
            return;
        }
        if ($scope.privateId == undefined || $scope.privateId == '' || $scope.privateId == null) {
            Message.warning('请选择教练');
            return;
        }
        if ($scope.totalMoney == undefined || $scope.totalMoney == '' || $scope.totalMoney == null) {
            Message.warning('请输入总金额');
            return;
        }
        if ($scope.totalNum == undefined || $scope.totalNum == '' || $scope.totalNum == null) {
            Message.warning('请输入总节数');
            return;
        }
        if ($scope.overageNum == undefined || $scope.overageNum == '' || $scope.overageNum == null) {
            Message.warning('请输入剩余节数');
            return;
        }
        if ($scope.createTime == undefined || $scope.createTime == '' || $scope.createTime == null) {
            Message.warning('请选择办理日期');
            return;
        }
        if ($scope.deadlineTime == undefined || $scope.deadlineTime == '' || $scope.deadlineTime == null) {
            Message.warning('请选择到期日期');
            return;
        }
        if ($scope.privateStartTime == undefined || $scope.privateStartTime == '' || $scope.privateStartTime == null) {
            Message.warning('请选择开课时间');
            return;
        }
        $http({
            url: '/member/charge-update',
            method:'POST',
            data: $scope.getChargeClassUpdate(),
            header:{'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
            if(response.data.status == 'success'){
                Message.success('修改成功！');
                $scope.privateLessonSelectClick($scope.positionId123);
                $scope.getChargeClassData();
                $('#ChargeUpdateModal').modal('hide');
            }else{
                Message.warning('修改失败！');
            }
        })
    };
    //私课记录添加
    $scope.addPersonalList = function() {
        $scope.addNewPrivateLessonRecordFinishFlag = false;
        $scope.addNewPrivateLessonClassChoose = "";
        $scope.addNewPrivateLessonLastNum = "";
        $scope.addNewPrivateLessonAllNum = "";
        $scope.addNewPrivateLessonClassType = "";
        $scope.addNewPrivateLessonDo = "";
        $scope.addNewPrivateLessonStart = "";
        $scope.addNewPrivateLessonEnd = "";
        $scope.addNewPrivateLessonAmount = "";
        $scope.addNewPrivateLessonAdviser = "";
        $('#addNewPrivateLessonRecordModal').modal('show');
        //办理时间
        $("#startClassTime").datetimepicker({
            minView: "month",
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true
        });
        //开课时间
        $("#startClassDate").datetimepicker({
            // minView: "month",
            format: 'yyyy-mm-dd hh:ii',
            language: 'zh-CN',
            autoclose: true
        });
        //到期时间
        $("#endClassDate").datetimepicker({
            minView: "month",
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            autoclose: true
        });
        //获取课程下拉框
        $http.get('/rechargeable-card-ctrl/get-private-class-data').then(function (data) {
            $scope.allClassName = data.data.venue;
        });
        //获取私教下拉框
        $http({method: "get", url: "/private-teach/private-coach"}).then(function (data) {
            $scope.addPrivateListData = data.data;
        }, function (error) {
            Message.error("请联系工作人员");
        });
    };
    $scope.addNewPrivateLessonRecordData = function() {
        return {
            memberId            : $scope.id, //会员id
            classId             : $scope.addNewPrivateLessonClassChoose != undefined && $scope.addNewPrivateLessonClassChoose != ''  ? $scope.addNewPrivateLessonClassChoose : null, //课程名称
            totalNum            : $scope.addNewPrivateLessonAllNum != undefined && $scope.addNewPrivateLessonAllNum      != ''  ? $scope.addNewPrivateLessonAllNum      : null, //总节数
            overageNum          : $scope.addNewPrivateLessonLastNum != undefined && $scope.addNewPrivateLessonLastNum     != ''  ? $scope.addNewPrivateLessonLastNum     : null, //剩余节数
            totalMoney          : $scope.addNewPrivateLessonAmount != undefined && $scope.addNewPrivateLessonAmount      != ''  ? $scope.addNewPrivateLessonAmount      : null, //总金额
            privateId           : $scope.addNewPrivateLessonAdviser != undefined && $scope.addNewPrivateLessonAdviser     != ''  ? $scope.addNewPrivateLessonAdviser     : null, //教练id
            createTime          : $scope.addNewPrivateLessonDo != undefined && $scope.addNewPrivateLessonDo          != ''  ? $scope.addNewPrivateLessonDo          : null, //办理日期
            deadlineTime        : $scope.addNewPrivateLessonEnd != undefined && $scope.addNewPrivateLessonEnd         != ''  ? $scope.addNewPrivateLessonEnd         : null, //课程截止时间
            privateStartTime    : $scope.addNewPrivateLessonStart != undefined && $scope.addNewPrivateLessonStart       != ''  ? $scope.addNewPrivateLessonStart       : null, //开课时间
            // sellId              : $scope.addNewPrivateLessonAdviser,//会籍顾问id
            courseType          : $scope.addNewPrivateLessonClassType != undefined && $scope.addNewPrivateLessonClassType   != ''  ? $scope.addNewPrivateLessonClassType   : null, //1.PT2.HS3.生日课  public $sellId; 销售人员id
            _csrf_backend       : $('#_csrf').val()
        }
    }
    $scope.addNewPrivateLessonRecordFinish = function() {
        if($scope.addNewPrivateLessonClassChoose == '' || $scope.addNewPrivateLessonClassChoose == undefined) {
            Message.warning('请选择课程名称！');
            return false;
        }
        if($scope.addNewPrivateLessonLastNum == '' || $scope.addNewPrivateLessonLastNum == undefined) {
            Message.warning('请输入剩余节数！');
            return false;
        }
        if($scope.addNewPrivateLessonAllNum == '' || $scope.addNewPrivateLessonAllNum == undefined) {
            Message.warning('请输入总节数！');
            return false;
        }
        if($scope.addNewPrivateLessonLastNum == '' || $scope.addNewPrivateLessonLastNum == undefined) {
            Message.warning('请输入剩余节数！');
            return false;
        }
        if(parseInt($scope.addNewPrivateLessonLastNum) > parseInt($scope.addNewPrivateLessonAllNum)) {
            Message.warning('剩余节数不能大于总节数！');
            return false;
        }
        if($scope.addNewPrivateLessonClassType == '' || $scope.addNewPrivateLessonClassType == undefined) {
            Message.warning('请选择课程类型！');
            return false;
        }
        if($scope.addNewPrivateLessonDo == '' || $scope.addNewPrivateLessonDo == undefined) {
            Message.warning('请选择办理日期！');
            return false;
        }
        if($scope.addNewPrivateLessonStart == '' || $scope.addNewPrivateLessonStart == undefined) {
            Message.warning('请选择开课时间！');
            return false;
        }
        if($scope.addNewPrivateLessonEnd == '' || $scope.addNewPrivateLessonEnd == undefined) {
            Message.warning('请选择到期时间！');
            return false;
        }
        if($scope.addNewPrivateLessonAmount == '' || $scope.addNewPrivateLessonAmount == undefined) {
            Message.warning('请输入办理金额！');
            return false;
        }
        if($scope.addNewPrivateLessonAdviser == '' || $scope.addNewPrivateLessonAdviser == undefined) {
            Message.warning('请选择办理私教！');
            return false;
        }
        $scope.addNewPrivateLessonRecordFinishFlag = true;
        $http({
            method: 'POST',
            url: '/member/add-course-order',
            data: $.param($scope.addNewPrivateLessonRecordData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function (result) {
            console.log(result)
            if(result.data.status == 'success') {
                Message.success('新增成功！');
                $('#addNewPrivateLessonRecordModal').modal('hide');
                $scope.getChargeClassData();
                $scope.addNewPrivateLessonRecordFinishFlag = false;
            }else {
                $scope.addNewPrivateLessonRecordFinishFlag = false;
                Message.warning('操作失败，请重试！');
                return
            }
        })
    }
    //延期功能
    $scope.postpone = function () {
        $scope.postponeBtnFlag     = false;
        $scope.postponeDays123     = "";
        $scope.delayPrivateRemarks = "";
        var timeStamp =new Date().getTime();
        if($scope.privateLessonTemplet.deadline_time > timeStamp/1000){
            var oldTime = parseInt($scope.privateLessonTemplet.deadline_time);
        }else{
            var oldTime = parseInt(timeStamp/1000);
        }

        $scope.postponeEndTime123 = $scope.getMyDateCh(oldTime * 1000);
        $('#postponeModal').modal('show');
    };

    //延期课程天数
    $scope.postponeDaysBlur = function (val) {
        var timeStamp =new Date().getTime();
        if ($scope.privateLessonTemplet.deadline_time < timeStamp/1000) {
            var oldTime = parseInt(timeStamp/1000);
            var time = parseInt(val * 60 * 24 * 60);
            $scope.postponeEndTime123 = $scope.getMyDateCh((oldTime + time) * 1000);
        } else {
            var time = parseInt(val * 60 * 24 * 60);
            var oldTime = parseInt($scope.privateLessonTemplet.deadline_time);
            $scope.postponeEndTime123 = $scope.getMyDateCh((oldTime + time) * 1000)
        }
        // if (val != '' && val != 0 && val != null && $scope.privateLessonTemplet.deadline_time < timeStamp/1000) {
        //     var oldTime = parseInt(timeStamp/1000);
        //     var time = parseInt(val * 60 * 24 * 60);
        //     $scope.postponeEndTime123 = $scope.getMyDateCh((oldTime + time) * 1000);
        // } else {
        //     var oldTime = parseInt($scope.privateLessonTemplet.deadline_time);
        //     $scope.postponeEndTime123 = $scope.getMyDateCh(oldTime * 1000)
        // }

    };

    //延期完成按钮
    $scope.postponeBtnSubmit = function () {
        $scope.getPostponeDate = function () {
            return {
                id     : $scope.positionId123,
                days   : $scope.postponeDays123     != undefined && $scope.postponeDays123     != '' ? $scope.postponeDays123     : null,
                remarks: $scope.delayPrivateRemarks != undefined && $scope.delayPrivateRemarks != '' ? $scope.delayPrivateRemarks : null,
                _csrf_backend: $('#_csrf').val()
            }
        };
        if ($scope.postponeDays123 == undefined || $scope.postponeDays123 == '' || $scope.postponeDays123 == null) {
            Message.warning('请输入延期天数');
            return;
        }
        if ($scope.delayPrivateRemarks == undefined || $scope.delayPrivateRemarks == '' || $scope.delayPrivateRemarks == null) {
            Message.warning('请输入备注');
            return;
        }
        $scope.postponeBtnFlag = true;
        // console.log($scope.getPostponeDate())
        $http({
            method: 'POST',
            url: '/member/delay',
            data: $.param($scope.getPostponeDate()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function (response) {
            if (response.data.status == "success") {
                $scope.privateLessonSelectClick($scope.privateLessonSelect);
                $('#postponeModal').modal('hide');
                Message.success(response.data.data);
            } else {
                $scope.postponeBtnFlag = false;
                angular.forEach(response.data.data, function (value, key) {
                    Message.warning(value);
                });
            }
        })
    };

    //初始化转让数据
    $scope.turn = {
        memberNumber: '',
        transferAmount: '',
        transferNode: ''
    };
    // 私课信息点击转课按钮，传递数据
    $scope.transfer = function () {
        $scope.turn = {
            memberNumber: '',
            transferAmount: '',
            transferNode: ''
        };
        $scope.verificationCode = '';
        $scope.memberTransferNumber = $scope.MemberData.mobile
        $scope.transferButtonFlag = false;
        $('#transfer').modal('show');
    }
    //获取转课手机验证码
    $scope.getTransferBindMemberCode = function (mobile) {
        $scope.transferMobile = mobile;
        var $pattern = /^1[345678]\d{9}$/;
        if(mobile == null || mobile == '' || !($pattern.test(mobile))){
            Message.warning('请填写正确的手机号');
            return false;
        }
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
            data: $.param({'mobile': $scope.transferMobile, '_csrf_backend': $('#_csrf').val()}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            $scope.TransferClassCode = result.data.data;
        });
    };
    $scope.transferOk = function () {
        if (!$scope.transferButtonFlag) {

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
            if($scope.verificationCode =='' || $scope.verificationCode == null || $scope.verificationCode == undefined){
                Message.warning('请输入验证码')
                return
            }else{
                if($scope.TransferClassCode != $scope.verificationCode){
                    Message.warning('验证码输入错误')
                    return
                }
            }
            var data = {
                memberId: $scope.id, //会员id
                chargeId: $scope.privateLessonSelect, //私教课程id
                memberNumber: $scope.turn.memberNumber,//会员编号
                transferPrice: $scope.turn.transferAmount,//转让金额
                transferNum: $scope.turn.transferNode, //转让节数
                code:$scope.verificationCode,
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
    $scope.presenterClick = function (id) {
        $scope.givePtButtonFlag = false;
        $scope.privateEducationSelectCardType(id);
        $scope.giveType123 = '';
        $scope.giveCard123 = '';
        $scope.privateEducationSelectListId = '';
        $scope.privateEducationSelectListName = '';
        $scope.giveCourseNum123 = '';
        $scope.giveCourseName123 = '';
        $scope.giveCourseValidity = '';
        $scope.memberId = id;
        $('#presenterModal').modal('show');
        $scope.giveCourseCoachIdFunction();
    }

    //赠送类型筛选
    $scope.giveTypeSelect = function (id) {
        $http.get('/user/get-course?courseType=' + id).then(function (response) {
            // console.log('根据类型获取课程',response);
            $scope.giveCourseLists123 = response.data.data;
        })
    };

    //获取有效期时间
    $scope.getValidityDate = function () {
        var date = $('#validityDateTime123').val();
        var startTime = date.substr(0, 10);
        $scope.startDate123 = startTime;
        var endTime = date.substr(-10, 10);
        $scope.giveCourseValidity = endTime;
        // console.log($scope.startDate123+'*****'+$scope.giveCourseValidity)
    }

    //获取所有教练
    $scope.giveCourseCoachIdFunction = function () {
        $http.get('/private-teach/private-coach').then(function (response) {
            // console.log(response);
            $scope.giveCourseCoachIdList = response.data;
        })
    };

    //赠送完成按钮
    $scope.presenterSubmit = function () {
        if($scope.giveType123 != null && $scope.giveType123 != 4){
            $scope.getValidityDate();
        }
        $scope.giveCourseCoach = $('.giveCourseCoach').val();
        $scope.getPresenterData = function () {
            return {
                courseType: $scope.giveType123 != undefined && $scope.giveType123 != '' ? $scope.giveType123 : null,//赠送类型
                memberCardId: $scope.giveCard123 != undefined && $scope.giveCard123 != '' ? $scope.giveCard123 : null,//会员卡id
                // coachId: $scope.privateEducationSelectListId != undefined && $scope.privateEducationSelectListId != '' ? $scope.privateEducationSelectListId : null,//私教id
                coachId: $scope.giveCourseCoach != undefined && $scope.giveCourseCoach != '' ? $scope.giveCourseCoach : null,//私教id
                // chargeId: $scope.buyMemberSelectListId != undefined && $scope.buyMemberSelectListId != '' ? $scope.buyMemberSelectListId : null,//私教id
                className:$scope.privateEducationSelectListName != undefined && $scope.privateEducationSelectListName != ''? $scope.privateEducationSelectListName : null,
                courseNum: $scope.giveCourseNum123 != undefined && $scope.giveCourseNum123 != '' ? $scope.giveCourseNum123 : null,//课程节数
                // courseId: $scope.giveCourseName123 != undefined && $scope.giveCourseName123 != '' ? $scope.giveCourseName123 : null,//课程id
                courseId: $scope.giveClassInfoCourseId != undefined && $scope.giveClassInfoCourseId != '' ? $scope.giveClassInfoCourseId : null,//课程id
                validity: $scope.giveCourseValidity != undefined && $scope.giveCourseValidity != '' ? $scope.giveCourseValidity : null,//有效期
                validityStart: $scope.startDate123 != undefined && $scope.startDate123 != '' ? $scope.startDate123 : null,//有效期开始时间
                type: $scope.giveClassInfoType != undefined && $scope.giveClassInfoType != '' ? $scope.giveClassInfoType : null,//有效期开始时间
                memberId: $scope.giveCourseMemberId != undefined && $scope.giveCourseMemberId != '' ? $scope.giveCourseMemberId : null,//有效期开始时间
                _csrf_backend: $('#_csrf').val()
            }
        }
        // console.log($scope.getPresenterData())
        if ($scope.giveType123 == undefined || $scope.giveType123 == '' || $scope.giveType123 == null) {
            Message.warning('请选择赠送类别');
            return;
        }
        if (($('#giveCard123').val() == undefined || $('#giveCard123').val() == '' || $('#giveCard123').val() == null) && $scope.giveType123 != 4) {
            Message.warning('请选择会员卡');
            return;
        }
        if (($scope.privateEducationSelectListId == undefined || $scope.privateEducationSelectListId == '' || $scope.privateEducationSelectListId == null) && $scope.giveType123 != 4) {
            Message.warning('请选择私教');
            return;
        }
        // console.log($scope.buyMemberSelectListId);
        // if (($scope.buyMemberSelectListId == undefined || $scope.buyMemberSelectListId == '' || $scope.buyMemberSelectListId == null) && $scope.giveType123 == 4) {
        //     Message.warning('请选择私教课程');
        //     return;
        // }
        if ($scope.giveCourseNum123 == undefined || $scope.giveCourseNum123 == '' || $scope.giveCourseNum123 == null) {
            Message.warning('请选择赠送课程节数');
            return;
        }
        if (($scope.giveCourseCoach == undefined || $scope.giveCourseCoach == '' || $scope.giveCourseCoach == null) && $scope.giveType123 == 4) {
            Message.warning('请选择教练');
            return;
        }
        if (($scope.giveCourseName123 == undefined || $scope.giveCourseName123 == '' || $scope.giveCourseName123 == null) && $scope.giveType123 != 4) {
            Message.warning('请选择赠送课程');
            return;
        }
        if (($scope.giveCourseValidity == undefined || $scope.giveCourseValidity == '' || $scope.giveCourseValidity == null) && $scope.giveType123 != 4) {
            Message.warning('请选择赠送课程有效期');
            return;
        }
        $scope.givePtButtonFlag = true;
        $http({
            method: 'post',
            url: '/user/give-class',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param($scope.getPresenterData()),
        }).then(function (response) {
            console.log('赠送返回的数据',response);
            if (response.data.status == "success") {
                Message.success(response.data.data);
                $scope.initPath();
                $scope.getData();
                $('#presenterModal').modal('hide');
            } else {
                $scope.givePtButtonFlag = false;
                Message.warning(response.data.data)
                // angular.forEach(response.data.data, function (value, key) {
                //     Message.warning(value);
                // });
            }
        })

    }


    // 分配私教
    $scope.distriButionClick = function (id){
        $scope.distributionPtButtonFlag = false;
        $scope.distriButionMemberId = id;
        $scope.privateEducationSelectCardType(id);
        $http.get("/user/get-member-id?memberId=" + id).success(function (data){
            if(data != null && data != undefined && data != ''){
                $scope.memberBirthday = data.data.id_card.substring(10,12);
            }
            else{
                $scope.memberBirthday = "";
            }
        });
    };
    //进入选择私教的模态框
    $scope.distributionTeacher = function () {
        $("#selectTeacherModal").modal('show');
        $scope.privateEducationData();
    };
    //进入选择购买私课的模态框
    $scope.distributionChargeClass = function () {
        $("#selectBuyClassModal").modal('show');
        $scope.getBuyPrivateClassData();
    };
    $scope.privateEducationModal = function () {
        $("#selectTeacherModal").modal('hide');
        $("#selectBuyClassModal").modal('hide');
    };
    // 获取赠送类型的课程名称
    $scope.courseTypeSelect = function (id){
        $http.get("/user/get-course-data?courseType=" + $scope.couresType + "&cardCategoryId=" + $scope.privateEducationSelectCardTypeId).success(function(data){
            $scope.couresNameId           = "";
            $scope.couserClassNameId      = "";
            $scope.couserClassNameItemVal = "";
            $("#couresNameId").val("");
            if(data != null && data.data != undefined && data != ""){
                $scope.couserClassNameItem    = data.data;                //课程信息
                $scope.couserClassNameItemVal = data.data.name;                //课程名称
                $scope.couserClassNameNum     = data.data.number;         //课程数量
                $scope.couserClassNameId      = data.data.polymorphic_id; //课程id
            }
            else{
                $scope.couresNameId = "";
                $("#couresNameId").val("");
            }
        });
    };
    //获取分配教练的会员卡
    $scope.privateEducationSelectCardType = function (id) {
        $scope.giveCourseMemberId = id;
        $http({method: 'get', url: '/user/member-card-info?MemberId=' + id}).then(function (data) {
            $scope.privateEducationSelectCardData = data.data.item;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };
    //分配私教模态框 会员卡id
    $scope.privateEducationSelectCardChange = function (data) {
        if(data != null && data != undefined && data != ""){
            $scope.privateEducationSelectTypeItem   = angular.fromJson(data);                                 //卡信息
            $scope.privateEducationSelectTypeId     = $scope.privateEducationSelectTypeItem.id;               //卡id
            $scope.privateEducationSelectCardTypeId = $scope.privateEducationSelectTypeItem.card_category_id; //卡种id
        }
        else{
            $scope.privateEducationSelectTypeItem   = ""; //卡信息
            $scope.privateEducationSelectTypeId     = ""; //卡id
            $scope.privateEducationSelectCardTypeId = ""; //卡种id
            $scope.couserClassNameId                = "";
        }
        $scope.courseTypeSelect();
    };
    $scope.privateEducationData = function () {
        $http({method: 'get', url: '/private-teach/private-coach'}).then(function (data) {
            $scope.privateEducationDataList = data.data;
            // console.log(data.data)
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };
    //获取会员已购买私教课
    $scope.getBuyPrivateClassData = function () {
        $http({
            method: 'get',
            url: '/private-teach/get-private-teach-all'
        }).then(function (data) {
            $scope.buyClassList = data.data;
            $scope.allClassListAlone = data.data.data.alone;
            $scope.allClassListMany = data.data.data.many;
            // console.log(data.data)
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    };
    $scope.privateEducationSelectList = function (id, pic, name) {
        $("#selectTeacherModal").modal('hide');
        $scope.privateEducationSelectListId = id;
        $scope.privateEducationSelectListPic = pic;
        $scope.privateEducationSelectListName = name;
    };
    //选择会员已购买私教课
    $scope.buyMemberPrivateSelectList = function (name, type, id) {
        $("#selectBuyClassModal").modal('hide');
        // $scope.buyMemberSelectListId = id;
        $scope.privateEducationSelectListName = name;
        $scope.giveClassInfoType = type;
        $scope.giveClassInfoCourseId = id;
    };
    $scope.courseTypeSelectChange = function () {

    };
    $scope.privateEducationSelectOk = function () {
        var data = {
            memberCardId   : $scope.privateEducationSelectTypeId,
            memberId       : $scope.distriButionMemberId,
            coachId        : $scope.privateEducationSelectListId,
            memberBirthday : $scope.memberBirthday,
            courseId       : $scope.couserClassNameId,
            courseNum      : $scope.couserClassNameNum,
            cardCategoryId : $scope.privateEducationSelectCardTypeId,
            courseType     : $scope.couresType
        };
        if (!$scope.distributionPtButtonFlag) {
            if (data.memberCardId == null || data.memberCardId == '' || data.memberCardId == undefined) {
                Message.warning("请选择卡种");
                return
            }
            if (data.coachId == null || data.coachId == '' || data.coachId == undefined) {
                Message.warning("请选择教练");
                return
            }
            if ($scope.couresType == null || $scope.couresType == "" || $scope.couresType == undefined) {
                Message.warning("请选择类型");
                return
            }
            if (data.courseId == null || data.courseId == '' || data.courseId == undefined) {
                Message.warning("请选择课程名称");
                return;
            }
            if (data.courseNum == null || data.courseNum == '' || data.courseNum == undefined) {
                Message.warning("请选择课程名称");
                return;
            }
            if(data.courseNum == '0'){
                Message.warning("课程已用完");
                return;
            }
            $scope.distributionPtButtonFlag = true;
            $http({
                method: 'post',
                url: '/user/allot-private',
                data: $.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (data) {
                if (data.data.status == "success") {
                    Message.success(data.data.data);
                    $("#distribution").modal("hide");
                    $scope.privateEducationSelectTypeId   = '';
                    $scope.privateEducationSelectListId   = '';
                    $scope.privateEducationSelectListPic  = null;
                    $scope.privateEducationSelectListName = null;
                    $scope.courseNum  = '';
                    $scope.couresType = '';
                    $scope.couresName = '';
                    $scope.couresType   = "";
                    $scope.couresNameId = "";
                    $("#couresNameId").val("");
                    $("#privateEducationSelectValue").val("");
                    return
                }
                if (data.data.status == "error") {
                    $scope.distributionPtButtonFlag = false;
                    Message.warning(data.data.data);
                    $("#distribution").modal("hide");
                    $scope.couresType = '';
                    $scope.privateEducationSelectTypeId = '';
                    $scope.privateEducationSelectListId = '';
                    $scope.courseNum = '';
                    $scope.couresType = '';
                    $scope.couresName = '';
                    $scope.privateEducationSelectListPic = null;
                    $scope.privateEducationSelectListName = null;
                    $scope.couresType   = "";
                    $scope.couresNameId = "";
                    $("#couresNameId").val("");
                    $("#privateEducationSelectValue").val("");
                    return;
                }
            }, function (error) {
                console.log(error);
                $scope.distributionPtButtonFlag = false;
                Message.error("系统错误请联系工作人员");
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
    // 分配私教模态框关闭事件
    $("#distribution").on('hide.bs.modal',function (){
        $scope.couresType = '';
        $scope.privateEducationSelectTypeId = '';
        $scope.privateEducationSelectListId = '';
        $scope.courseNum = '';
        $scope.couresType = '';
        $scope.couresName = '';
        $scope.privateEducationSelectListPic = null;
        $scope.privateEducationSelectListName = null;
        $scope.couresType   = "";
        $scope.couresNameId = "";
        $("#couresNameId").val("");
        $scope.privateEducationSelectValue = "";
    });

    // 获取潜在会员送人卡信息记录
    $scope.getMemberSendCardRecord = function () {
        $http.get("/user/get-member-send-record?memberId=" + $scope.id).success(function (data) {
            // console.log('getMemberSendCardRecord',data);
            $scope.memberSendCardList = data.data;
            if ($scope.memberSendCardList.length == 0) {
                $scope.payNoSendCardRecordDataShow = true; //暂无数据图像显示
            }
            else {
                $scope.payNoSendCardRecordDataShow = false; //暂无数据图像关闭
            }
        });
    };

    /** 会员卡冻结 **/
    $scope.freezeMemberCardBtn = function (id,status) {
        $scope.freezeCardId = id;
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
        else {
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
    $scope.getAllCardCategory = function () {
        $http.get("/sell-card/card-category").success(function (data) {
            // console.log(data);
            $scope.getCardList = data;
        });
    };
    //
    $scope.cardChange = function (value) {
      $http.get("/sell-card/card-category-money?cardCategory="+value).success(function (data) {
          $scope.pay = '';
          if(data != '' && data != []){
              var item = angular.fromJson(data);
          }else{
              var item        = '';
              item.data.sell_price = null;
              item.data.max_price  = null;
          }
          if(item.data.sell_price != null){
              $scope.amount            = item.data.sell_price;
              $scope.buyMoney       = item.data.sell_price;
              $scope.buyCardMaxPrice   = "";
              $scope.buyCardMinPrice   = "";
          }
          else if(item.data.sell_price == null && item.data.max_price != null && item.data.min_price != null){
              $scope.amount            = "";
              $scope.buyMoney       = "";
              $scope.buyCardMaxPrice = item.data.max_price;
              $scope.buyCardMinPrice = item.data.min_price;
          }else{
              $scope.amount            = "";
              $scope.buyMoney       = "";
              $scope.buyCardMaxPrice   = "";
              $scope.buyCardMinPrice   = "";
          }
          $scope.amountMoneyMax    = item.data.max_price;
          $scope.amountMoneyMin    = item.data.min_price;
          $scope.cardCateGoryId    = item.data.id;
          $scope.defaultCardId     = item.data.id;
          $(".amountMoneyInput1").val("")
          $scope.mathPrice();
          });
    };
    /*** 计算价格 ***/
    // 计算价格
    $scope.mathPrice = function (){
            $scope.allMathMoney       = parseInt($scope.buyMoney);
            $scope.allMathMoneyBefore = parseInt($scope.buyMoney);
    };
    // 区间价格失去焦点时的判断
    $("#amountMoneyInputVal2").blur(function (){
        if($scope.amount != null && $scope.buyCardMaxPrice != undefined && $scope.buyCardMaxPrice != '' &&$scope.buyCardMinPrice != undefined && $scope.buyCardMinPrice != ''){
            var buyMax = parseInt($scope.buyCardMaxPrice);
            var buyMin = parseInt($scope.buyCardMinPrice);
            if($("#amountMoneyInputVal2").val() > buyMax || $("#amountMoneyInputVal2").val() < buyMin){
                $("#amountMoneyInputVal2").val("");
                $scope.buyMoney    = '';
                $scope.allMathMoney   = "";
                $scope.pay    = "";
                Message.warning("输入的价格不在区间价内，请重新输入");
            }
        }
    });
    // 计算区间价格
    $scope.mathAllAmountMoneyLast = function (){
        $scope.pay = '';
        // console.log('111',$scope.buyMoney)
        if($scope.buyMoney != null || $scope.buyMoney != '' || $scope.buyMoney != undefined){
            $scope.allMathMoney       = $scope.buyMoney;
            $scope.allMathMoneyBefore = $scope.buyMoney;
        }
        else if(isNaN($scope.allMathMoney)){
            $scope.allMathMoney = '';
        }
        else{
            $scope.allMathMoney       = parseInt($scope.buyMoney);
            $scope.allMathMoneyBefore = parseInt($scope.buyMoney);
        }
    };
    // 点击购卡按钮
    $scope.userBuyCard = function (id) {
        $scope.userId = id;
        $scope.buyCardDepositSelectChange();
        $("#buyCardModal").modal("show"); //购卡模态框显示
        $scope.getSaleMansInfo();         //获取销售人员信息
        $scope.getAllCardCategory();      //获取所有售卖卡种
        $scope.use = "1";                 //设置默认使用方式
        $scope.num = 0;
        $scope.attr = '';
        $scope.addBuyCardHtml = '';
        $scope.buyCardMethodArr = [];
        $scope.buyCardPriceArr = [];
        $scope.buyCardArr = [];
        $('.buyCardMethodSelect').val('');
        $('.buyCardPriceSelect').val('');
        $scope.cardType = "";
        $scope.pay = "";
        $scope.saleMan = "";
        $scope.buyMoney = "";
        $scope.paymentMethod = "";
        $scope.buyCardMaxPrice="";
        $scope.buyCardMinPrice="";
        $scope.allMathMoney="";
        $scope.buyCardInput = "";
        $scope.allBuyCardPrice = "";
        $scope.repeatDom2 = function() {
            for(var i = 0;i<$('.buyCardMethodBox').children().length;i++) {
                if($('.newSelectDom2').length != 0) {
                    $('.newSelectDom2').remove();
                }
            }
        }
        $scope.repeatDom2();
        $scope.addBuyCardMethod();
    };
    //判断数组是否有重复
    $scope.isRepeat = function(arr){
        var hash = {};
        for(var i in arr) {
            if(hash[arr[i]])
                return true;
            hash[arr[i]] = true;
        }
        return false;
    }
    //判断价格数组里的值是否少于或多余应付金额
    $scope.isCompare = function(arr) {
        var result = 0;
        for(var i = 0; i < arr.length; i++) {
            result += parseFloat(arr[i]);
        }
        return result.toFixed(2);
    }
    $scope.num = 0;
    $scope.attr = '';
    $scope.addBuyCardHtml = '';
    $scope.addBuyClassHtml = '';
    $scope.addBuyCardMethod = function() {
        $scope.attr = 'addBuyCard';
        $scope.num = $scope.num + 1;
        $http({
            url: "/member/add-venue?attr=" + $scope.attr + '&num=' + $scope.num,
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            $scope.addBuyCardHtml = result.data.html;
        })
    }
    $scope.addBuyClassMethod = function() {
        $scope.attr = 'addBuyClass';
        $scope.num = $scope.num + 1;
        $http({
            url: "/member/add-venue?attr=" + $scope.attr + '&num=' + $scope.num,
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            $scope.addBuyClassHtml = result.data.html;
        })
    }
    $scope.addBuyCardMethod();
    $scope.addBuyClassMethod();
    //完成时获取新的付款途径
    $scope.getBuyCardArr = function(){
        $scope.buyCardArr= [];//支付方式与支付价格的数组对象
        $scope.buyCardMethodArr = [];
        $scope.buyCardPriceArr = [];
        var $buyCardDiv = $('.addBuyCardElement').children('div');
        $buyCardDiv.each(function (index,item) {
            var _buyCardMethod = $(this).find('select').val();
            var _buyCardPrice = $(this).find('input').val();
            if( _buyCardPrice == ''){
                _buyCardPrice = 0;
            }
            var data = {
                type  : _buyCardMethod,
                price : _buyCardPrice
            }
            $scope.buyCardArr.push(data);
            $scope.buyCardMethodArr.push(_buyCardMethod);
            $scope.buyCardPriceArr.push(_buyCardPrice);
        })
    }
    $scope.buyCardInputChange = function () {
        $scope.getBuyCardArr();
        $scope.allBuyCardPrice = parseFloat($scope.isCompare($scope.buyCardPriceArr));
    }
    $scope.buyCardInputChange123 = function () {
        $timeout(function () {
            $scope.buyCardInputChange();
        },100)
    }
    $scope.buyCardInputChange321 = function(){
        $timeout(function(){
            $scope.getBuyCardArr();
            if($scope.isRepeat($scope.buyCardMethodArr)) {
                Message.warning('付款途径不能有重复！');
                return;
            }
        },100)
    }
    // 定金的计算
    $scope.paymentTypeChange = function (value){
        if($scope.allMathMoneyBefore == undefined){
            $scope.allMathMoneyBefore = 0;
        }
        if(value == "2"){
            $('.buyCardDepositSelect2').attr('disabled',false);
            $scope.getDepositAdd();
            if($scope.allDepositMoneyAmount != null){
                if($scope.allMathMoneyBefore < parseFloat($scope.allDepositMoneyAmount)) {
                    Message.warning('选择定金总金额不得大于购卡的总金额！');
                    $scope.allDepositMoneyAmount = 0.00;
                    $scope.buyCardDepositSelect = 0;
                    $('.select2-selection__rendered').children().remove();
                    $scope.allMathMoney = $scope.allMathMoneyBefore - parseFloat($scope.allDepositMoneyAmount);
                }else {
                    $scope.allMathMoney = $scope.allMathMoneyBefore - parseFloat($scope.allDepositMoneyAmount);
                }

            }
            else if(isNaN($scope.allMathMoney)){
                $scope.allMathMoney = '';
            }
            else {
                $scope.allMathMoney = $scope.allMathMoneyBefore;
            }
        }
        else {
            $scope.allMathMoney = $scope.allMathMoneyBefore;
            $scope.allDepositMoneyAmount = 0.00;
            $scope.buyCardDepositSelect = 0;
            $('.select2-selection__rendered').children().remove();
            $('.buyCardDepositSelect2').attr('disabled',true)
        }
    };
    $scope.getDepositArr = function(){
        $scope.depositIdArr= [];
        $scope.depositMoneyArr = [];
        $scope.depositMoneySurplusArr = [];
        var $depositDiv = $('.buyCardDepositSelect2').children('option');
        $depositDiv.each(function (index,item) {
            if($(this).is(':checked')) {
                var _id = $(this).val();
                var _price = $(this).data('price');
                $scope.depositIdArr.push(_id);
                $scope.depositMoneyArr.push(_price);
            }else {
                var _price = $(this).data('price');
                $scope.depositMoneySurplusArr.push(_price);
            }
        })
    }
    $scope.getDepositAdd = function () {
        $scope.getDepositArr();
        //计算选中的用于抵扣的定金总额
        $scope.allDepositMoneyAmount = parseFloat($scope.isCompare($scope.depositMoneyArr)).toFixed(2);
    }

    //定金金额遍历
    $scope.buyCardDepositSelectChange = function () {
        $http.get('/member/deposit-data-type?memberId='+$scope.userId+'&type=1').then(function (data) {
            $scope.buyCardDepositSelectData = angular.fromJson(data.data.type);
        })
        $scope.getDepositArr();
        $scope.surplusPrice = parseFloat($scope.isCompare($scope.depositMoneySurplusArr));
        $scope.paymentTypeChange($scope.pay);
    };

    // 整理二次购卡提交的数据
    $scope.buyTwoCardData = function () {
        $scope.newUserContactUrl = 'http://product.aixingfu.net/purchase-card/contact-send?cardId=' + $scope.cardType; //设置合同链接地址
        return {
            cardCateGoryId: $scope.cardType       != undefined && $scope.cardType       != "" ? $scope.cardType       : null, //选择卡种
            paymentType   : $scope.pay            != undefined && $scope.pay            != "" ? $scope.pay            : null, //付款方式
            saleMan       : $scope.saleMan        != undefined && $scope.saleMan        != "" ? $scope.saleMan        : null, //选择销售
            amountMoney   : $scope.buyMoney       != undefined && $scope.buyMoney       != "" ? $scope.buyMoney       : null, //总金额
            // payMethod     : $scope.paymentMethod  != undefined && $scope.paymentMethod  != "" ? $scope.paymentMethod  : null, //收款方式
            payMethod     : $scope.buyCardArr     != undefined && $scope.buyCardArr     != "" ? $scope.buyCardArr     : null,//付款途径
            usageMode     : $scope.use            != undefined && $scope.use            != "" ? $scope.use            : null, //使用方式
            depositArrId  : $scope.depositIdArr          != undefined && $scope.depositIdArr          != "" ? $scope.depositIdArr                 : null, //包含所有定金Id的数组
            deposit       : $scope.allDepositMoneyAmount != undefined && $scope.allDepositMoneyAmount != "" ? $scope.allDepositMoneyAmount        : 0  ,  //定金
            netPrice      : $scope.allMathMoney          != undefined && $scope.allMathMoney          != "" ? $scope.allMathMoney                 : null, // 实收价格
            mobile        : $scope.userTel        != undefined && $scope.userTel        != "" ? $scope.userTel        : null, //手机号
            cardNumber    : $scope.userCardNumber != undefined && $scope.userCardNumber != "" ? $scope.userCardNumber : null, //会员卡号
            note          : $scope.buyCardNote    != undefined && $scope.buyCardNote    != "" ? $scope.buyCardNote    : null, //购卡备注
            memberId      : $scope.id             != undefined && $scope.id             != "" ? $scope.id             : null, //会员id
            url           : $scope.newUserContactUrl, //合同链接
            _csrf_backend : $('#_csrf').val()
        }
    };
    // 二次购卡完成提交数据
    $scope.buyTwoCardSuccess = function () {
        $scope.getBuyCardArr();
        $scope.isRepeat($scope.buyCardMethodArr);
        if($scope.isRepeat($scope.buyCardMethodArr)) {
            Message.warning('付款途径不能有重复，请重新输入！');
            return;
        }
        // 提交前验证
        // 选择卡种
        if ($scope.cardType == "" || $scope.cardType == undefined || $scope.cardType == null) {
            Message.warning("请选择卡种");
            return;
        }
        //付款方式
        if ($scope.pay == "" || $scope.pay == undefined || $scope.pay == null) {
            Message.warning("请选择付款方式");
            return;
        }
        //选择销售
        if ($scope.saleMan == "" || $scope.saleMan == undefined || $scope.saleMan == null) {
            Message.warning("请选择销售");
            return;
        }
        //总金额
        if ($scope.buyMoney == "" || $scope.buyMoney == undefined || $scope.buyMoney == null) {
            Message.warning("请填写总金额");
            return;
        }
        for(var i = 0;i<$scope.buyCardMethodArr.length;i++) {
            if($scope.buyCardMethodArr[i] == null || $scope.buyCardMethodArr[i] == undefined || $scope.buyCardMethodArr[i] == '') {
                Message.warning('付款途径或价格不能为空，请重新输入！');
                return
            }
        }
        for(var i = 0;i<$scope.buyCardPriceArr.length;i++) {
            var regTest = /^\d+(\.\d{1,2})?$/;
            if(!regTest.test($scope.buyCardPriceArr[i])) {
                Message.warning('金额只能输入整数或两位以内小数！');
                return;
            }
        }
        $scope.isRepeat($scope.buyCardMethodArr);
        if($scope.isRepeat($scope.buyCardMethodArr)) {
            Message.warning('付款途径不能有重复，请重新输入！');
            return;
        }
        if (parseFloat($scope.isCompare($scope.buyCardPriceArr)) != parseFloat($scope.allMathMoney)){
            Message.warning("所有付款途径价格之和必须等于应付金额！");
            return;
        }
        //收款方式
        // if ($scope.paymentMethod == "" || $scope.paymentMethod == undefined || $scope.paymentMethod == null) {
        //     Message.warning("请选择收款方式");
        //     return;
        // }
        // else {
        $scope.checkButton = true;
        // 发送数据
        $http({
            url: "/sell-card/update-sell-card",
            method: 'POST',
            data: $.param($scope.buyTwoCardData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            if (data.status == "success") {
                $scope.checkButton = false;
                Message.success(data.data);
                $("#buyCardModal").modal("hide");
            }
            else if(data.data.card_number != null && data.data.card_number != '' && data.data.card_number != undefined && data.data.card_number.length > 0 && data.status == 'error'){
                Message.warning("卡号不能重复！");
                $scope.checkButton = false;
            }
            else {
                $scope.checkButton = false;
                Message.warning(data.data);
                return;
            }
        })
        // }

    };

    // 二次购卡关闭事件
    $('#buyCardModal').on('hide.bs.modal', function () {
        $scope.cardType = "";
        $scope.pay = "";
        $scope.saleMan = "";
        $scope.buyMoney = "";
        $scope.paymentMethod = "";
        $scope.buyCardMaxPrice="";
        $scope.buyCardMinPrice="";
        $scope.buyCardPriceArr=[];
        $scope.allMathMoney="";
        $scope.buyCardInput = "";
        $scope.allBuyCardPrice = "";
        $scope.use = "1";
    });

    //修改送人卡到期时间
    $scope.changeMemberTime = function (id) {
        $scope.giftCardId = id;
        $('.delayActiveCard').datetimepicker({
            format: 'yyyy-mm-dd',
            minView: "month",//设置只显示到月份
            language: 'zh-CN',
            autoclose: true
        });
        $('.expiryTime').datetimepicker({
            format: 'yyyy-mm-dd',
            minView: "month",//设置只显示到月份
            language: 'zh-CN',
            autoclose: true
        });
        $('#changeMemberTimeModal').modal('show');
    };

    //确定修改按钮
    $scope.changePresentCardTime = function () {
        if(!$scope.delayActiveCardChange){Message.warning('请输入激活时间！');return;}
        if(!$scope.expiryTimeChange){Message.warning('请输入到期时间！');return;}
        $.loading.show();
        if($scope.delayActiveCardChange.toString() === $scope.delayActiveCardChange1.toString()) {
            $scope.delayActiveCardChange = $scope.delayActiveCardChange11;
        }else {
            $scope.delayActiveCardChange = Date.parse($scope.delayActiveCardChange)/1000;
        }
        if($scope.expiryTimeChange.toString() === $scope.expiryTimeChange1.toString()) {
            $scope.expiryTimeChange = $scope.expiryTimeChange11;
        }else {
            $scope.expiryTimeChange = Date.parse($scope.expiryTimeChange)/1000;
        }
        var request = {
            memberCardId: $scope.giftCardId,            //会员卡ID
            activeTime  : $scope.delayActiveCardChange, //激活时间
            invalidTime : $scope.expiryTimeChange       //到期时间
        };
        console.log(request);
        $http({
            method  : 'post',
            url     : '/potential-members/update-gift-card',
            data    : $.param(request),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.status === 'success') {
                Message.success('修改成功！');
                $('#changeMemberTimeModal').modal('hide');
                $.loading.hide();
                $scope.delayActiveCardChange = '';
                $scope.expiryTimeChange = '';
                $scope.infoChange();
            }else {
                Message.warning('操作失败，请刷新后重试！');
                $scope.delayActiveCardChange = $scope.delayActiveCardChange1;
                $scope.expiryTimeChange = $scope.expiryTimeChange1;
                $.loading.hide();
                return false;
            }
        });
    };

    // 绑定会员
    $scope.bindingUser = function (id, card) {
        $("#bindingUserSelectModal").modal("show"); //搜索会员模态框显示
        $scope.sendGiftUserId = id;
        // $scope.sendGiftCardId = card;
        // console.log(card);
    };

    /*** 绑定老会员 ***/
    // 绑定老会员
    $scope.oldUserBinding = function () {
        $("#bindingUserModal").modal("show"); //老会员绑定模态框显示
    };

    // 回车搜索事件
    $scope.myKeyup = function (e) {
        var keycode = window.event ? e.keyCode : e.which;
        if (keycode == 13) {
            $scope.searchBindingUser();
        }
    };

    // 点击搜索事件
    $scope.searchBindingUser = function () {
        $http.get("/private-teach/member?keyword=" + $scope.keywordsTel + "&type=bind").success(function (data) {
            // console.log(data);
            if (data.id == null || data.id == "" || data.id == undefined) {
                Message.warning("会员不存在");
            }
            else {
                $scope.userInfoNews = data;
                // console.log(data);
                $("#bindingUserDetailsModal").modal("show");  //会员详情模态框显示
            }
        })
    };

    // 绑定老会员的数据整理
    $scope.oldUserBindingData = function () {
        return {
            oldMemberId: $scope.id != undefined && $scope.id != "" ? $scope.id : null, //会员id
            memberId: $scope.userInfoNews.id != undefined && $scope.userInfoNews.id != "" ? $scope.userInfoNews.id : null, //被转卡会员id
            cardId: $scope.sendGiftUserId != undefined && $scope.sendGiftUserId != "" ? $scope.sendGiftUserId : null, //卡的id
            scenario: "old", //会员类型new新old老
            status: 2, //会员状态新1、老2
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 绑定老会员的数据提交
    $scope.bindingOldUserSendCard = function () {
        // 发送数据
        $scope.checkButton = true;
        $http({
            url: "/user/save-delivery-form",
            method: 'POST',
            data: $.param($scope.oldUserBindingData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            if (data.status == "error") {
                $scope.checkButton = false;
                Message.warning(data.data);
            }
            else {
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
    $('#bindingUserModal').on('hide.bs.modal', function () {
        $scope.keywordsTel = "";
    });

    /*** 绑定老会员结束 ***/

    /*** 绑定新会员 ***/
    // 绑定新会员
    $scope.newUserBinding = function () {
        $("#bindingNewUserModal").modal("show"); //新会员绑定模态框显示
    };

    // 绑定新会员获取验证码
    $scope.paracont = "获取验证码";
    $scope.disabled = false;

    // 获取验证码
    $scope.getCode = function () {
        var $pattern = /^1[0-9]{10}$/;
        if ($scope.newBindingMobile == null || $scope.newBindingMobile == undefined || $scope.newBindingMobile == "" || !($pattern.test($scope.newBindingMobile))) {
            Message.warning('请填写正确的手机号！');
            return false;
        }
        if ($scope.newBindingName == null || $scope.newBindingName == "") {
            Message.warning('请填写姓名！');
            return false;
        }
        // var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        // if ($scope.newBindingIdCard == null || $scope.newBindingIdCard == "" || !(idCardP.test($scope.newBindingIdCard))) {
        //     Message.warning("请输入18位有效身份证号");
        //     return false;
        // }
        else {
            // 验重
            $http.get("/sell-card/set-data?mobile=" + $scope.newBindingMobile + "&idCard=" + $scope.newBindingIdCard + "&name=" + $scope.newBindingName).then(function (id) {
                // console.log(id);
                if (id.data.status == "error") {
                    Message.warning(id.data.data);
                    return false;
                }
                else {
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
    $scope.newUserBindingData = function () {
        return {
            name: $scope.newBindingName != undefined && $scope.newBindingName != "" ? $scope.newBindingName : null, //名称
            idCard: $scope.newBindingIdCard != undefined && $scope.newBindingIdCard != "" ? $scope.newBindingIdCard : null, //身份证号
            cardId: $scope.sendGiftUserId != undefined && $scope.sendGiftUserId != "" ? $scope.sendGiftUserId : null, //卡的id
            sex: $scope.newBindingSex != undefined && $scope.newBindingSex != "" ? $scope.newBindingSex : null, //性别
            mobile: $scope.newBindingMobile != undefined && $scope.newBindingMobile != "" ? $scope.newBindingMobile : null, //手机号
            code: $scope.newBindingCode != undefined && $scope.newBindingCode != "" ? $scope.newBindingCode : null, //验证码
            oldMemberId: $scope.id != undefined && $scope.id != "" ? $scope.id : null, //老会员ID
            scenario: "new", //会员类型new新old老
            status: 1, //会员状态新1、老2
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 绑定新会员的数据提交及验证
    $scope.newBindingSuccess = function () {
        // 验证
        // 姓名
        if ($scope.newBindingName == null || $scope.newBindingName == "") {
            Message.warning('请填写姓名！');
            return false;
        }
        // // 身份证号
        // var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        // if ($scope.newBindingIdCard == null || $scope.newBindingIdCard == "" || !(idCardP.test($scope.newBindingIdCard))) {
        //     Message.warning("请输入18位有效身份证号");
        //     return false;
        // }
        // 手机号
        var $pattern = /^1[0-9]{10}$/;
        if ($scope.newBindingMobile == null || $scope.newBindingMobile == undefined || $scope.newBindingMobile == "" || !($pattern.test($scope.newBindingMobile))) {
            Message.warning('请填写正确的手机号！');
            return false;
        }
        // 性别
        if ($scope.newBindingSex == null || $scope.newBindingSex == "") {
            Message.warning('请选择性别！');
            return false;
        }
        // 验证码
        if ($scope.newBindingCode == null || $scope.newBindingCode == "" || $scope.newBindingCode != $scope.code) {
            Message.warning('请输入正确的验证码！');
            return false;
        }
        else {
            $scope.checkButton = true;
            // 发送数据
            $http({
                url: "/user/save-delivery-form",
                method: 'POST',
                data: $.param($scope.newUserBindingData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (data) {
                if (data.status == "error") {
                    Message.warning("提交错误！请重新填写或者联系管理员");
                    $scope.checkButton = false;
                }
                else {
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
    $('#bindingNewUserModal').on('hide.bs.modal', function () {
        $scope.newBindingName = "";
        $scope.newBindingIdCard = "";
        $scope.newBindingSex = "";
        $scope.newBindingMobile = "";
        $scope.newBindingCode = "";
    });

    /*** 绑定新会员结束 ***/
    /**
     *新增
     *
     * */
    $scope.addNewRenewRecordMoneyList = function() {
        $scope.addNewCardRenewRecordMoney       = '';
        $scope.addNewCardRenewRecordTime        = '';
        $scope.addNewCardRenewRecordActiveTime  = '';
        $scope.addNewCardRenewRecordName        = '';
        $scope.addNewCardRenewRecordSeller      = '';
        $scope.addNewCardRenewRecordEnd         = '';
        $scope.addNewCardRenewRecordActive      = '';
        $('#addNewCardRenewRecordModal').modal('show');
        $scope.getSellerAllAdviseData();
        //缴费日期调用时间插件
        $("#addNewCardRenewRecordTimeBox").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        })
        //到期日期调用时间插件
        $("#addNewCardRenewRecordEndBox").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        })
        //激活日期调用时间插件
        $("#addNewCardRenewRecordActiveTimeBox").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        })
    }
    $scope.addNewCardRenewRecordData = function () {
        $scope.addNewCardRenewRecordDate = Date.parse($scope.addNewCardRenewRecordTime) /1000;
        $scope.addNewCardRenewRecordDueDate = Date.parse($scope.addNewCardRenewRecordEnd) /1000;
        $scope.addNewCardRenewRecordDateActiveTime = Date.parse($scope.addNewCardRenewRecordActiveTime) /1000;
        return {
            memberCardId : $scope.moneyListCardId,
            paymentAmount: $scope.addNewCardRenewRecordMoney,
            activateTime : $scope.addNewCardRenewRecordDateActiveTime,
            paymentTime  : $scope.addNewCardRenewRecordDate,
            paymentName  : $scope.addNewCardRenewRecordName,
            sellId       : $scope.addNewCardRenewRecordSeller,
            dueDate      : $scope.addNewCardRenewRecordDueDate,
            behavior     : $scope.addNewCardRenewRecordActive,
            _csrf_backend: $('#_csrf').val()
        }
    }
    $scope.addNewCardRenewRecord = function() {
        if($scope.addNewCardRenewRecordTime == null || $scope.addNewCardRenewRecordTime == undefined || $scope.addNewCardRenewRecordTime == '') {
            Message.warning('请输入缴费时间！');
            return;
        }
        if($scope.addNewCardRenewRecordActiveTime == null || $scope.addNewCardRenewRecordActiveTime == undefined || $scope.addNewCardRenewRecordActiveTime == '') {
            Message.warning('请输入激活时间！');
            return;
        }
        if($scope.addNewCardRenewRecordName == null || $scope.addNewCardRenewRecordName == undefined || $scope.addNewCardRenewRecordName == '') {
            Message.warning('请输入缴费名称！');
            return;
        }
        if($scope.addNewCardRenewRecordMoney == null || $scope.addNewCardRenewRecordMoney == undefined || $scope.addNewCardRenewRecordMoney == '') {
            Message.warning('请输入缴费金额！');
            return;
        }
        if($scope.addNewCardRenewRecordSeller  == null || $scope.addNewCardRenewRecordSeller == undefined || $scope.addNewCardRenewRecordSeller == '') {
            Message.warning('请选择会籍顾问！');
            return;
        }
        if($scope.addNewCardRenewRecordEnd == null || $scope.addNewCardRenewRecordEnd == undefined || $scope.addNewCardRenewRecordEnd == '') {
            Message.warning('请输入到期日期！');
            return;
        }
        if($scope.addNewCardRenewRecordActive == null || $scope.addNewCardRenewRecordActive == undefined || $scope.addNewCardRenewRecordActive == '') {
            Message.warning('请输入行为！');
            return;
        }
        $http({
            url: "/member/add-consumption-history",
            method: 'POST',
            data: $.param($scope.addNewCardRenewRecordData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            if(result.data.status == 'success') {
                Message.success('新增成功！');
                $scope.getCardRewenRecondList();
                $('#addNewCardRenewRecordModal').modal('hide');
            }else {
                Message.warning('新增失败！');
                return;
            }
        })
    }
    /*** 会员卡金额操作 ***/
    /**
     * author:张亚鑫
     * date:2017-12-6
     * 函数描述：点击修改，打开修改模态框，显示数据，如果进行修改并点击完成，就发送数据到后台
     * param:    id                : 缴费记录id
     *           consumptionAmount : 缴费的金额
     *           memberCardId      : 会员卡id
     *           sellerId          : 会籍id
     *           time              : 缴费日期
     *           name              : 缴费名称
     *           dueDate           : 到期日期
     *           behavior          : 行为
     * */
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
    $scope.getSellerAllAdviseData = function() {
        $http.get("/user/get-adviser").success(function (response) {
            $scope.allAdviser = response;
        });
    }
    // 金额修改
    $scope.upsateMoneyList = function (id,sellId,amount,activeTimeA,activeTimeB,dueDate,date,category,paymentName,cardName){
        $("#moneyUpsateModals").modal("show");
        $scope.moneyListTimeDate = parseInt(date)*1000;
        $scope.moneyUpsateInputVal = amount;//缴费的金额
        $scope.upsateMoneyListId   = id;//缴费记录id
        $scope.sellName            = sellId;//会籍id
        $scope.sellId              = sellId;
        $scope.moneyListDate       = $scope.getMyDate($scope.moneyListTimeDate);//缴费日期
        if(paymentName) {
            $scope.moneyListCardName   = paymentName;//缴费名称
        }else {
            $scope.moneyListCardName   = cardName;//缴费名称
        }
        if(dueDate) {
            $scope.moneyListTimeDueDate = parseInt(dueDate)*1000;
            $scope.moneyListDueDate    = $scope.getMyDate($scope.moneyListTimeDueDate);//到期日期
        }else {
            $scope.moneyListDueDate = '';
        }
        if(activeTimeA) {
            $scope.moneyListActiveTime = $scope.getMyDate(parseInt(activeTimeA)*1000);//激活时间
        }else if(activeTimeB) {
            $scope.moneyListActiveTime = $scope.getMyDate(parseInt(activeTimeB)*1000);//激活时间
        }else {
            $scope.moneyListActiveTime = '';
        }
        $scope.moneyListCategory   = category;//行为
        if( $scope.upsateMoneyListId == null || $scope.upsateMoneyListId == undefined || $scope.upsateMoneyListId == '') {
            Message.warning('未获取到缴费记录，请重新打开！')
        }
        $scope.getSellerAllAdviseData();
        //缴费日期调用时间插件
        $("#moneyListDateBox").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        })
        //到期日期调用时间插件
        $("#moneyListDueDateBox").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        })
        //激活时间调用时间插件
        $("#moneyListActiveTimeBox").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: 'month',
            language: 'zh-CN',
            autoclose: true,
            todayBtn: true
        })
    };
    $scope.chargeSellId = function(){
        $scope.sellId = $scope.sellName;
    }
    $scope.chargeMoney = function(){
        $scope.consumptionAmount = $scope.moneyUpsateInputVal;
    }
    // 金额修改提交
    // 整理数据
    $scope.getMoneyUpsateModalData = function (){
        $scope.moneyListTimeDate = Date.parse($scope.moneyListDate) /1000;
        $scope.moneyListTimeDueDate = Date.parse($scope.moneyListDueDate) /1000;
        $scope.moneyListTimeActiveTime = Date.parse($scope.moneyListActiveTime) /1000;
        return{
            id                : $scope.upsateMoneyListId        != undefined && $scope.upsateMoneyListId        != "" ? $scope.upsateMoneyListId         : null,
            consumptionAmount : $scope.moneyUpsateInputVal      != undefined && $scope.moneyUpsateInputVal      != "" ? $scope.moneyUpsateInputVal       : null,
            activateTime      : $scope.moneyListTimeActiveTime  != undefined && $scope.moneyListTimeActiveTime  != "" ? $scope.moneyListTimeActiveTime   : null,
            memberCardId      : $scope.cardInfo.id              != undefined && $scope.cardInfo.id              != "" ? $scope.cardInfo.id               : null,
            sellerId          : $scope.sellId                   != undefined && $scope.sellId                   != "" ? $scope.sellId                    : null,
            time              : $scope.moneyListTimeDate        != undefined && $scope.moneyListTimeDate        != "" ? $scope.moneyListTimeDate         : null,
            name              : $scope.moneyListCardName        != undefined && $scope.moneyListCardName        != "" ? $scope.moneyListCardName         : null,
            dueDate           : $scope.moneyListTimeDueDate     != undefined && $scope.moneyListTimeDueDate     != "" ? $scope.moneyListTimeDueDate      : null,
            behavior          : $scope.moneyListCategory        != undefined && $scope.moneyListCategory        != "" ? $scope.moneyListCategory         : null,
            _csrf_backend: $('#_csrf').val()
        }
    };
    $scope.moneyUpsateModalSuccess = function (){
        if($scope.moneyListDate == null || $scope.moneyListDate == undefined || $scope.moneyListDate == '') {
            Message.warning('请输入缴费日期！');
            return;
        }
        if($scope.moneyListActiveTime == null || $scope.moneyListActiveTime == undefined || $scope.moneyListActiveTime == '') {
            Message.warning('请输入激活时间！');
            return;
        }
        if($scope.moneyListCardName == null || $scope.moneyListCardName == undefined || $scope.moneyListCardName == '') {
            Message.warning('请输入缴费名称！');
            return;
        }
        if($scope.moneyUpsateInputVal == null || $scope.moneyUpsateInputVal == undefined || $scope.moneyUpsateInputVal == '') {
            Message.warning('请输入缴费金额！');
            return;
        }
        if($scope.sellId  == null || $scope.sellId == undefined || $scope.sellId == '') {
            Message.warning('请选择会籍顾问！');
            return;
        }
        if($scope.moneyListDueDate == null || $scope.moneyListDueDate == undefined || $scope.moneyListDueDate == '') {
            Message.warning('请输入到期日期！');
            return;
        }
        if($scope.moneyListCategory == null || $scope.moneyListCategory == undefined || $scope.moneyListCategory == '') {
            Message.warning('请输入行为！');
            return;
        }
        $http({
            url: "/member/update-consumption",
            method: 'POST',
            data: $.param($scope.getMoneyUpsateModalData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data){
            if(data.data.status == "success"){
                Message.success("修改成功！");
                // $scope.getMemberInfo();
                $scope.getCardRewenRecondList();
                $scope.infoChange();
                $("#moneyUpsateModals").modal("hide");
            }
            else{
                Message.warning("修改失败！");
            }
        });
    };

    // 删除当前会员卡
    $scope.memberCardClick = function (memberCardId){
        Sweety.remove({
            url: '/member-card/member-card-del?memberCardId=' + memberCardId,
            http: $http,
            title: '确定要删除吗?',
            text: '删除后信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function (data){
            if(data.data.status == "success"){
                Message.success("删除成功");
                $scope.getMemberInfo();
            }
            else{
                Message.success(data.data.data);
            }
        });
    };
    // 删除信息
    $scope.delMoneyList = function (id){
        Sweety.remove({
            url: '/member/del-consumption-data?consumptionId=' + id,
            http: $http,
            title: '确定要删除吗?',
            text: '删除后信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function (data){
            if(data.data.status == "success"){
                Message.success("删除成功");
                $scope.getCardRewenRecondList();
            }
            else{
                Message.success(data.data.data);
            }
        });
    };
    /*** 会员卡金额操作结束 ***/
    //会员卡转移功能
    $scope.transferPeople = function(object){
        //console.log(object)
        $('#transferPeopleModal').modal('show');
        $scope.transferCardName     = object.card_name;//卡名称
        $scope.transferMemberCardId = object.id;       //会员卡id
        $scope.transferMemberId     = object.member_id;//会员id
    };
    //会员卡转移编号查找按钮
    $scope.searchTransferBtn = function(){
        if($scope.serialNumInput == '' || $scope.serialNumInput == undefined){
            Message.warning('请输入会员编号');
            return
        }else{
            $.loading.show();
            $http.get('/member/member-venue-name?memberId=' + $scope.serialNumInput).success(function(result){
                //console.log(result)
                if(result.card != null){
                    //会员姓名
                    if(result.card.name != null){
                        $('#transferMemberName').val(result.card.name);
                    }else{
                        $('#transferMemberName').val('暂无数据');
                    }
                    //所属场馆
                    if(result.card.venueName != null){
                        $('#transferVenueName').val(result.card.venueName);
                    }else{
                        $('#transferVenueName').val('暂无数据');
                    }
                    $scope.successBtnStatus = true;
                }else{
                    Message.warning('查找不到该会员编号,请重新输入');
                    $('#transferMemberName').val('');
                    $('#transferVenueName').val('');
                }
                $.loading.hide();
            })
        }
    };
    //会员编号change事件
    $scope.serialNumChange = function(){
        //清空会员姓名和场馆
        $('#transferMemberName').val('');
        $('#transferVenueName').val('');
        $scope.successBtnStatus = false;
    };
    //会员卡转移功能参数
    $scope.transferParamData = function(){
        return{
            memberCardId  : $scope.transferMemberCardId, //会员卡id
            newMemberId   : $scope.serialNumInput,       //新的会员编号
            _csrf_backend : $('#_csrf').val()
        }
    };
    //会员卡转移模态框确定按钮
    $scope.transferSuccessBtn = function(){
        //console.log( $scope.transferParamData());

        swal({
            title: "确定转移吗？",
            text: "此操作会把当前会员卡转移到指定会员下，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: false
        },function(isConfirm){
            if (isConfirm) {
                $http({
                    url: "/member/new-transfer-card",
                    method: 'POST',
                    data: $.param($scope.transferParamData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (data){
                    //console.log(data)
                    if(data.data.status == "success"){
                        //swal.close();
                        //Message.success("转移成功！");
                        swal("转移成功！", "","success");
                        $("#transferPeopleModal").modal("hide");
                        $scope.getMemberInfo();
                    }
                    else{
                        //Message.warning(data.data.data);
                        swal("转移失败！", data.data.data,"error");
                    }
                    $scope.successBtnStatus = false;
                });
            } else {
                swal.close();
            }
        });
    };
    //会员卡转移模态框关闭事件
    $('#transferPeopleModal').on('hidden.bs.modal', function (e) {
        $scope.serialNumInput = '';
        $('#transferMemberName').val('');
        $('#transferVenueName').val('');
        $scope.successBtnStatus = false;
    })
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
        + " " + date.getHours() + seperator2 + date.getMinutes() + seperator2 + date.getSeconds();
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
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate + " ";
    return currentdate;
}
//新增显示
$(".Deposits").click(function () {
    $(".Deposit").show()
})