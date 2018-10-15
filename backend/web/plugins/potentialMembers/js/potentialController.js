angular.module('App').controller('potentialCtrl', function ($scope, $http, $location, $rootScope,$interval,$timeout) {
    /**
     * 潜在会员 首页
     * @type {string} 2017-5-22 苏雨
     */
    // 关闭模态框
    $scope.closeMotalFun = function (){
        $scope.paymentMethod = "";
        // $scope.saleMan = "";
        $scope.paymentType = "";
        $scope.card = "";
        $('#inputMoney').val("");
    };
    $scope.getMyDate = function(str){
        str = parseInt(str);
        if(str!=""||str!=null){
            var oDate = new Date(str);
            var oYear = oDate.getFullYear();
            var oMonth = oDate.getMonth()+1;
            oMonth = oMonth>=10? oMonth:'0'+oMonth;
            var oDay = oDate.getDate();
            oDay = oDay>=10? oDay:'0'+oDay;
            var theDate = oYear+"-"+oMonth+"-"+oDay;
        }else{
            theDate = "";
        }
        return theDate
    };
    angular.element(document).ready(function () {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        // $(".js-example-basic-single").select2();
        $(".selectSale").select2({
            width: '180px',
            placeholder: "请选择销售"
        });
        $(".cardChange").select2();
        $(".saleSelect").select2({
            placeholder: "请选择"
        });
        $(".typeSelect").select2({
            width: "100%",
            placeholder: "请选择"
        });
        $(".sellerSelect").select2({
            width: "100%",
            placeholder: "请选择"
        });
        $('.buyCardDepositSelect1').select2({
            width     : '100%',
            multiple  : true
        });
    });
    $("#dataLeave12").datetimepicker({
        format: 'yyyy-mm-dd',
        minView: "month",//设置只显示到月份
        language: 'zh-CN',
        autoClose: true,
        todayBtn: true//今日按钮
    });
    $("#bothDate").datetimepicker({
        minView: "month",//设置只显示到月份
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayBtn: true//今日按钮
    });
    //潜在会员购卡日历
    $("#buyCardBirthdays").datetimepicker({
        format: 'yyyy-mm-dd',
        minView: "month",//设置只显示到月份
        language: 'zh-CN',
        autoClose: true
    });
    // 鼠标点击后座位效果的js
    $('.seat').click(function () {
        $(this).toggleClass("activeSeat");
    });
    $('.close1').click(function () {
        $("#myModal").modal("hide");
    });
    // 新增潜在会员的模态框显示和关闭的js
    $scope.addUser = function () {
        $scope.potentialMemberButtonFlag = false;
        $scope.selectCredentials = '';
        $scope.idCard = '';
        $scope.memberSex = '';
        $scope.counselorId = '';
        $scope.wselectSalesSources = '';
        $("#myModal2").modal("show");
        //生日选择的时间选择器
        $("#birthdays").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: "month",//设置只显示到月份
            language: 'zh-CN',
            autoClose: true
        });
        $scope.counselor();
    };
    $('.close2').click(function () {
        $("#myModal2").modal("hide");
    });
    $('.close4').click(function () {
        $("#myModal4").modal("hide");
    });
    $scope.successBtn4 = function () {
        $("#myModal4").modal("hide");
        // 预约完成显示
        $("#myModal3").modal("show");
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pageInitUrl = '/member/get-member-info?page='+value;
            $scope.listsDates();
        }
    };

    $scope.pageInitUrl = '/member/get-member-info?keywords=' + '' + '&wayToShop=' + '';
    $scope.listsDates = function () {
        $.loading.show();
        $http({
            method: 'get', url: $scope.pageInitUrl
        }).then(function (data) {
            if(data.data.data.length <= 0 ){
                $scope.listDate = data.data.data;
                $scope.payNoMoneyDataShow = true;
                $scope.pages = data.data.pages;
            }else {
                $scope.listDate = data.data.data;
                $scope.payNoMoneyDataShow = false;
                $scope.pages = data.data.pages;
            }
            $.loading.hide()
        }, function (error) {
            Message.error('系统错误请联系管理员');
            $.loading.hide();
        })
    }
    $scope.listsDates();
    $scope.input = {
        searchValue: ''
    };
    $scope.searchClick = function () {
        var array = {
            venueId  : $scope.changeVenueId,
            wayToShop: $scope.changeSectleValue,
            keywords : $scope.input.searchValue,
            sortType : $scope.sortType != undefined ? $scope.sortType : null,
            sort     : $scope.sort != undefined ? $scope.sort : null,
            potMember: $scope.memberType != undefined ? $scope.memberType : null,
            newMember: $scope.memberType != undefined ? $scope.memberType : null,
        };
        $http({
            url: '/member/get-member-info?' + $.param(array),
            method: 'get'
        }).then(function (data) {
            if(data.data.data.length <= 0 ){
                $scope.listDate = data.data.data;
                $scope.payNoMoneyDataShow = true;
                $scope.pages = data.data.pages;
            }else {
                $scope.listDate = data.data.data;
                $scope.payNoMoneyDataShow = false;
                $scope.pages = data.data.pages;
            }
        }, function (error) {
            console.log(error)
            Message.error("系统错误请联系管理人员")
        })
    }
    
    //清空按钮的操作
    $scope.clearSearch = function(){
        $scope.changeVenueId = '';
        $scope.changeSectleValue = '';
        $scope.input.searchValue = '';
        $scope.listsDates();
    }
    //取消预约场地
    $scope.cancelReservationYard = function (id) {
        $http.get('/site-management/cancel-yard-about-class?id=' + id).then(function (result) {
            if(result.data.status == 'success') {
                Message.success(result.data.message);
                $scope.listsDates();
                $scope.getMemberAboutYardRecord();
            }else {
                Message.warning(result.data.message);
            }
        })
    }
    // 潜在会员的详情的模态框显示和关闭的js
    $scope.potentialDetails = function (id) {
        $scope.printsFlag = false;
        $scope.potentialUserId = id;
        $http({
            url: '/user/member-information?memberId=' + id,
            method: 'get'
        }).then(function (data) {
            $scope.potentialDetail = data.data
            if(data.data.aboutClassId == false){
                $scope.printsFlag = false;
            }else{
                $scope.printsFlag = true;
                var urls = '/check-card/get-about-class-detail?id=' + data.data.aboutClassId;
                $http({
                    method: 'GET',
                    url: urls,
                }).then(function (success) {
                    //打印机需要的信息
                    $scope.printers = success.data.data;
                })
            }

        }, function (error) {
            Message.error('系统错误请联系工作人员')
        })
        $("#myModal").modal("show");
    };
    // 获取会籍顾问
    $scope.counselor = function () {
        $http({
            url: "/user/get-adviser",
            method: 'GET'
        }).then(function (date) {
            $scope.counselorItems = date.data;
        })
    };
    //删除潜在会员
    $scope.removeMember = function(id){
        Sweety.remove({
            url: "/member/del-member-info?memberId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '潜在会员删除后信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.listsDates();
        });
    }
    // 预约完成关闭
    $scope.closeSuccess = function () {
        $("#myModal3").modal("hide");
    };
    $scope.potentialPurchaseClose = function () {
        localStorage.clear();
        $("#potentialPurchaseModal").hide();
    };
    //购卡
    $scope.sellingData = function (name,mobile,sex,w,wid) {
        $scope.card             = '';
        var object = w;
        // $.loading.show();
        $("#potentialPurchaseModal").modal("show");
        $scope.potentialMemberBuyCardButtonFlag = false;
        // $scope.cardInfoIt();
        $scope.selectedCard      = '';
        $scope.amountMoney       = '';
        $scope.amountMoneyMax    = '';
        $scope.amountMoneyMin    = '';
        $scope.cardCateGoryId    = '';
        $scope.saleMan           = '';
        $scope.paymentType       = '';
        $scope.paymentMethod     = '';
        $scope.cardNumber        = '';
        $scope.giftStatus        = '';
        $scope.usageMode         = '';
        $scope.allMathMoney      = '';
        $scope.giftsDaysSelect   = "";
        $scope.num               = 0;
        $scope.payMethodArr      = '';
        $scope.payPriceArr       = '';
        $scope.allPayArr         = '';
        $scope.cardCheckNumber  = '';
        $('.addPayMethodSelect').val('');
        $('.addPayPrice ').val('');
        $scope.allPayWayPrice   = 0;
        $scope.repeatDom = function() {
            for(var i = 0;i<$('.addPayWayBox').children().length;i++) {
                if($('.newSelectDom').length != 0) {
                    $('.newSelectDom').remove();
                }
            }
        }
        $scope.repeatDom();
        $scope.addPaySelectElement();
        $scope.getGiftGiveDaysListValue      = [];
        $scope.getGiftGiveDaysListValue.days = "";
        $scope.getGiftGiveDaysListValue.id   = "";
        $scope.defaultCardId= '';
        $scope.defaultVenueId= '';
        $scope.buyCardMaxPrice   = "";
        $scope.cardVenueId    = '';
        $scope.amount = '';
        $scope.venue = '';
        $scope.memberId_buyCardDeposit = wid;
        $scope.buyCardDepositSelectChange();
        $(".discountWords").hide();
        $('#select2-saleman-container').text('请选择 ');
        $('#select2-selectedCards-container').text('');
        $http({method:'get',url:'/potential-members/get-member-deposit-one?memberId='+wid}).then(function (data) {
            // $scope.sellingDataPrice    = data.data.price;
            $scope.voucherStartSell    = parseInt(data.data.start_time);
            $scope.voucherEndSell      = parseInt(data.data.end_time) + 12*60*60*60;
            $scope.todayTimesTemps     = Date.parse(new Date())/1000;
            // if(data.data.voucher != null && $scope.voucherStartSell < $scope.todayTimesTemps && $scope.voucherEndSell > $scope.todayTimesTemps){
            //     $scope.sellingDataVoucher  = data.data.voucher;
            // }
            // else if($scope.voucherStartSell > $scope.todayTimesTemps || $scope.voucherEndSell < $scope.todayTimesTemps){
            //     $scope.sellingDataVoucher  = 0;
            // }
            // else{
            //     $scope.sellingDataVoucher  = 0;
            // }
            if(data.data != "null"){
                $scope.orderid123 = data.data.id;
            }
            var endTime1   = $scope.getMyDate(data.data.end_time*1000)+" "+"23:59:59";
            $scope.endTime = parseInt(new Date(endTime1).getTime());
            $scope.Time    = parseInt(Date.parse(new Date()));
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系管理人员")
        });
        $scope.name   = name;
        $scope.sex    = sex;
        $scope.mobile = mobile;
        /**
         *  create at 2017*12*27
         *  author    杨慧磊
         *  描述       优化显示生日
         **/
        //判断生日字段是否有值
        if(object.birth_date != null && object.birth_date != '' && object.birth_date != undefined){
            //如果有
            $scope.buyCardBirthdays123 = object.birth_date;

        }else{
            //如果没有
            //判断身份证是否有值
            if(object.id_card != null && object.id_card != '' && object.id_card.length == 18 && parseInt(object.id_card.slice(10,12)) <= 12 && parseInt(object.id_card.slice(12,14)) <= 31){
                //如果有
                var birthColumn = object.id_card.slice(6,14);
                //年
                var year  = birthColumn.slice(0,4)+'-';
                //月
                var month = birthColumn.slice(4,6)+'-';
                //日
                var day   = birthColumn.slice(6,8);
                $scope.buyCardBirthdays123 = year + month + day;

            }else{
                //如果没有
                $scope.buyCardBirthdays123 = '';
            }
        }
        if(object.document_type != null){
            $scope.buyCardSelectCredentials = object.document_type;
        }else{
            $scope.buyCardSelectCredentials = '';
        }
        $http({
            url: '/user/member-information?memberId=' + w.id,
            method: 'get'
        }).then(function (result) {
            $scope.buyCardMemberDetail = result.data;
            $scope.idCard = $scope.buyCardMemberDetail.id_card;
            // $scope.getDefaultVenueAndCard($scope.idCard);
        });

    };
    //输入身份证获取生日
    $scope.getBirthDay = function(){
        if($scope.buyCardSelectCredentials == '1'){
            if($scope.idCard.length > 10){
                if(parseInt($scope.idCard.slice(10,12)) > 12){
                    Message.warning('身份证格式错误');
                    return false;
                }
            }
            if($scope.idCard.length>12){
                if(parseInt($scope.idCard.slice(12,14)) > 31){
                    Message.warning('身份证格式错误');
                    return false;
                }
            }
            if($scope.idCard.length == 18){
                var birthField = $scope.idCard.slice(6,14);
                var yearField  = birthField.slice(0,4)+'-';
                var monthField = birthField.slice(4,6)+'-';
                var dayField   = birthField.slice(6,8);
                $scope.buyCardBirthdays123 = yearField + monthField + dayField;
            }else if($scope.idCard.length > 18){
                Message.warning('身份证格式错误');
                return false;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //选择默认场馆和卡种
    $scope.getDefaultVenueAndCard = function(idCard){
        $scope.defaultPrice123 = '';
        if(idCard != null &&idCard != ''){

            $http.get('/sell-card/selected-card?idCard='+ idCard).then(function(response){
                if(response.data.data != null){
                    $scope.selectedCard = response.data.data;
                    $scope.defaultCardId = response.data.data.card_category_id;
                    $scope.defaultVenueId = response.data.data.venue_id;
                    $scope.cardVenueId    = response.data.data.venue_id;
                    $scope.cardCateGoryId  = response.data.data.card_category_id;
                    $('#selectedCards').val(response.data.data.cardCategory);
                    $scope.defaultPrice123 = response.data.data.cardCategory;
                    $scope.getVenueCard(response.data.data.venue_id);
                    $('#id').val(response.data.data.card_category_id).select2()
                    $('#select2-selectedCards-container').text(response.data.data.card_name);
                    $scope.cardNameDefault = response.data.data.card_name;
                    $timeout(function(){
                        var selectOptions =$('.optionsCards');
                        // selectOptions.each(function(i,item){
                        //     var option = $(this).val();
                        //     var item = angular.fromJson(option);
                        //     console.log(item)
                        //     // console.log()
                        //     if(item.id == $scope.defaultCardId){
                        //         $scope.amountMoney    =  item.sell_price;
                        //         $scope.amountMoneyMax = item.max_price;
                        //         $scope.amountMoneyMin = item.min_price;
                        //         $scope.cardCateGoryId = item.id;
                        //         console.log(item)
                        //     }
                        // });
                        $scope.cardCateGoryArray($scope.defaultPrice123);
                        $('#select2-selectedCards-container').text($scope.cardNameDefault);
                        $.loading.hide();
                    },500)
                }else{
                    $.loading.hide();
                    $scope.card = '';
                }
            });
        }else{
            $.loading.hide();
        }
    }
    
    $scope.selectSalesSources = function (value) {
        $scope.SalesSources = value;
        $scope.sellSourceId = value;
    };

    //完后提交
    $scope.addPotentialMember = function () {
        $http({
            url: '/user/get-mobile-info?mobile=' + $scope.memberMobile,
            method: 'GET'
        }).then(function (data) {
            if (data.data.status == 'success') {
                $scope.potentialMemberButtonFlag = true;
                // 发送客户端数据
                $http({
                    url: "/member/set-member-info",
                    method: 'POST',
                    data: $.param($scope.addData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (data) {
                    if (data.data.status == "success") {
                        Message.success('添加成功');
                        $("#myModal2").modal("hide");
                        // $("#myModal2").modal("toggle");//去掉阴影层
                        $scope.listsDates();
                        $scope.memberSearch();
                        $('#exampleInputName2').val("");
                        $('#exampleInputName3').val("");
                        $scope.memberSex = "";
                        $scope.wselectSalesSources= "请选择来源渠道";
                        $("#exampleInputName3").val("");
                        $scope.counselorId = "请选择顾问";
                        $('#birthdays').val("");
                        $scope.potentialMemberButtonFlag = false;
                    }else{
                        $scope.potentialMemberButtonFlag = false;
                    }
                }, function (error) {
                    Message.error("系统错误前请联系工作人员");
                    $scope.potentialMemberButtonFlag = false;
                });
            } else if (data.data.status == 'error') {
                Message.warning('手机号存在');
                $scope.potentialMemberButtonFlag = false;
                return;
            }
        });
    }

    // 新增潜在会员的提交
    $scope.potentialMemberAdd = function () {
        $scope.addData = function () {
            return {
                memberName: $scope.memberName != undefined && $scope.memberName != "" ? $scope.memberName : null,
                memberMobile: $scope.memberMobile != undefined && $scope.memberMobile != "" ? $scope.memberMobile : null,
                memberSex: $scope.memberSex != undefined && $scope.memberSex != "" ? $scope.memberSex : null,
                memberAge: $scope.memberAge != undefined && $scope.memberAge != "" ? $scope.memberAge : null,
                idCard: $scope.idCard != undefined && $scope.idCard != "" ? $scope.idCard : null,
                birthDate: $scope.birthDate != undefined && $scope.birthDate != "" ? $scope.birthDate : null,
                counselorId: $scope.counselorId != undefined && $scope.counselorId != "" ? $scope.counselorId : null,
                note: $scope.note != undefined && $scope.note != "" ? $scope.note : null,
                wayToShop: $scope.wselectSalesSources != undefined && $scope.wselectSalesSources != "" ? $scope.wselectSalesSources : null,
                documentType  :$scope.selectCredentials     != undefined &&  $scope.selectCredentials !=''?$scope.selectCredentials :null,
                _csrf_backend: $('#_csrf').val()
            };
        };
        //保存数据之前数据验证
        if ($scope.memberName == null || $scope.memberName == "") {
            Message.warning("名字不能为空");
            $scope.potentialMemberButtonFlag = false;
            return ;
        }
        var phone = /^1\d{10}$/;
        if ($scope.memberMobile == null || $scope.memberMobile == "") {
            Message.warning("请输入手机号");
            $scope.potentialMemberButtonFlag = false;
            return;
        } else if (!(phone.test($scope.memberMobile))) {
            Message.warning("手机号输入有误");
            $scope.potentialMemberButtonFlag = false;
            return;
        }
        //当证件类型不为空的时候判断证件号
        if($scope.selectCredentials != undefined && $scope.selectCredentials !='' && $scope.selectCredentials !=null){
            if($scope.selectCredentials == '1'){
                var inputValue = $scope.idCard;
                var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
                if(!idCardP.test(inputValue)){
                    Message.warning('您的身份证输入有误，请重新输入');
                    return;
                }
            }
            if($scope.idCard =='' || $scope.idCard ==undefined|| $scope.idCard ==null){
                Message.warning('请输入证件号');
                return;
            }
        }
        if ($scope.wselectSalesSources == null || $scope.wselectSalesSources == '') {
            Message.warning('请选择来源渠道');
            $scope.potentialMemberButtonFlag = false;
            return
        }
        // if ($scope.counselor == null || $scope.counselor == "") {
        //     Message.warning("请选择会籍");
        //     $scope.potentialMemberButtonFlag = false;
        //     return;
        // }
        if ($scope.counselorId == null || $scope.counselorId == '') {
            Message.warning('请选择顾问');
            $scope.potentialMemberButtonFlag = false;
            return
        }
        $scope.potentialMemberButtonFlag = true;
        //身份证号码去重
        if ($scope.addData().idCard != null) {
            $http.get('/user/member-details?MemberIdCard=' + ($scope.addData().idCard) + "&MemberId=" + null).then(function (result) {
                if (result.data != '') {
                    Message.warning('该证件号已经存在');
                    $scope.potentialMemberButtonFlag = false;
                    return;
                } else {
                    $scope.addPotentialMember();
                }
            });
        } else {
            $scope.addPotentialMember();
        }
    }
    //处理sectle 选择搜索事件
    $scope.changeSectle = function (value) {
        var array = {
            venueId  : $scope.changeVenueId,
            wayToShop: value,
            keywords : $scope.input.searchValue,
            sortType : $scope.sortType != undefined ? $scope.sortType : null,
            sort     : $scope.sort != undefined ? $scope.sort : null,
            potMember: $scope.memberType != undefined ? $scope.memberType : null,
            newMember: $scope.memberType != undefined ? $scope.memberType : null,
        };
        $http({method: 'get', url: "/member/get-member-info?" + $.param(array)}).then(function (data) {
            if(data.data.data.length <= 0 ){
                $scope.listDate = data.data.data;
                $scope.payNoMoneyDataShow = true;
                $scope.pages = data.data.pages;
            }else {
                $scope.listDate = data.data.data;
                $scope.payNoMoneyDataShow = false;
                $scope.pages = data.data.pages;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    }

    // 关闭的方法
    $scope.closeModalQZuser = function (){
        $scope.wselectSalesSources = "";
    }

    //处理sectle 自定义会员类型
    $scope.memberSearch = function () {
        $http({
            url: '/potential-members/get-source',
            method: 'get'
        }).then(function (data) {
            $scope.memberSearchData = data.data;
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员")
        })
    }
    $scope.memberSearch();
    $scope.customAdd = function (data) {
        $("#exampleModal").modal("show");
        $scope.customSalesChannels = "";
    }

    // 模态框关闭事件
    $("#exampleModal").on('hide.bs.modal',function (){

    });

    $scope.confirmAdd = function (data) {
        if($scope.customSalesChannels == "" ||$scope.customSalesChannels == null ||$scope.customSalesChannels == undefined){
            Message.warning("请输入自定义销售渠道");
        }else{
            $("#exampleModal").modal("hide");
            var dataJson = {
                source: data,
                scenario: "source",
                _csrf_backend: $('#_csrf').val()
            }
            $http({method: 'post', url: '/potential-members/set-source', data: dataJson}).then(function (data) {
                $scope.seclectId = data.data.id;
                if(data.data.status == "error"){
                    Message.error("系统错误请联系工作人员");
                }else {
                    Message.success("添加完成");
                    $scope.memberSearch();
                    $scope.selectSalesSources();
                    // $scope.wselectSalesSources = $scope.seclectId;
                    $scope.wselectSalesSourcesNs = data.data.id;
                }
            });
        }
        }

    //回车搜索触发
    $scope.enterSearch = function () {
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13) {
            $scope.changeSectle();
        }
    };
    //删除销售来源
    $scope.deleteTheSource = function(id) {
        if( $scope.sellSourceId != '' && $scope.sellSourceId != null && $scope.sellSourceId != undefined ){
            Sweety.remove({
                url              : "/potential-members/delete-config?configId="+ $scope.sellSourceId,
                http             : $http,
                title            : '确定要删除吗?',
                text             : '删除后信息无法恢复',
                confirmButtonText: '确定',
                data             : {
                    action: 'unbind'
                }
            }, function () {
                $scope.memberSearch();
                $scope.selectSalesSources();
                $scope.wselectSalesSources = "";
            });
        }else{
            Message.warning('请选择需要删除的销售来源!');
            return;
        }
    };

    //列表排序
    $scope.changeSort = function (attr, sort) {
        $scope.sortType = attr;             //排序字段
        $scope.switchSort(sort);            //准备排序状态
        $scope.changeSectle();              //调用搜索方法
    };
    //处理正序、倒序
    $scope.switchSort = function (sort) {
        if (!sort) {
            sort = 'DES';
        } else if (sort == 'DES') {
            sort = 'ASC';
        } else {
            sort = 'DES'
        }
        $scope.sort = sort;             //排序状态
    };
    $scope.replacementPages = function (urlPages) {
        $.loading.show();
        $scope.pageInitUrl = urlPages;
        $scope.listsDates();
    };
    $scope.getDataPage = function () {
        $http.get($scope.pageInitUrl).success(function (response) {
            if (response.data != "" && response.data != undefined && response.data.length != undefined) {
                $scope.listDate = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = false;
            } else {
                $scope.listDate = response.data;
                $scope.pages = response.pages;
                $scope.dataInfo = true;
            }
            $.loading.hide()
        });
    };
    //取消潜在会员约课
    $scope.potentialMemberCancelAppointment = function (id) {

        Sweety.remove({
            url: "/check-card/cancel-about-class?id=" + id,
            http: $http,
            title: '确定取消约课吗?',
            text: '取消约课后无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.listsDates();
        });
    }
    // $scope.getDataPage()
    //点击约课跳转约课页面
    $scope.reservationCourse = function (reservationCourseId, reservationCourseMemberType) {
        localStorage.setItem('reservationCourse', JSON.stringify({
            id: reservationCourseId, MemberType: reservationCourseMemberType
        }))
        location.href = '/potential-members/course-list';
    }
/** 获取场馆**/
    $scope.getVenue=function () {
        $http({
            url:"/rechargeable-card-ctrl/get-venue?status=card",
            method:"GET"
        })
            .then(function(data){
                $scope.venueItems=data.data.venue
                // console.log(data.data.venue)
            })

    }
    //获取不同场馆下卡种
    $scope.venueSelectChange=function (id) {
        // console.log(222,id)
      if(id!=""&&id!=[]){
          var items = angular.fromJson(id);
      }else{
          var items ="无数据"
      }
        $scope.cardVenueId    = items.id;
        // $scope.cardVenueId    = items.id;
        $scope.getVenueCard(items.id)
    }
    $scope.getVenueCard = function (id){
        $http.get('/sell-card/card-category?venueId=' +id).success(function (data){
            $scope.getVenueCardItems = data;
        });
    };
    $scope.getVenue();
    /*** 购卡 ***/
    // 获取卡信息
    $scope.cardInfoIt = function () {
        $http({
            url: "/sell-card/card-category",
            method: 'GET'
        }).then(function (data) {
            $scope.cardItems = data.data;
        })
    };
    $scope.cardCateGoryArray = function (array) {
        $scope.paymentType = '';
        if(array != '' && array != []){
            var item = angular.fromJson(array);
        }else{
            var item        = '';
            item.sell_price = null;
            item.max_price  = null;
        }
        if(item.sell_price != null){
            $scope.amount            = item.sell_price;
            $scope.amountMoney       = item.sell_price;
            $scope.buyCardMaxPrice   = "";
            $scope.buyCardMinPrice   = "";
        }
        else if(item.sell_price == null && item.max_price != null && item.min_price != null){
            $scope.amount            = "";
            $scope.amountMoney       = "";
            $scope.buyCardMaxPrice = item.max_price;
            $scope.buyCardMinPrice = item.min_price;
        }else{
            $scope.amount            = "";
            $scope.amountMoney       = "";
            $scope.buyCardMaxPrice   = "";
            $scope.buyCardMinPrice   = "";
        }
        $scope.amountMoneyMax    = item.max_price;
        $scope.amountMoneyMin    = item.min_price;
        $scope.cardCateGoryId    = item.id;
        $scope.defaultCardId     = item.id
        $('#select2-selectedCards-container').text(item.card_name);
        $scope.getAllGiftDays(item.id);
        $scope.newUserContactUrl = 'http://product.aixingfu.net/purchase-card/contact-send?cardId='+$scope.cardCateGoryId;
        $(".amountMoneyInput ").val("");
        $scope.getBuyCardDiscount($scope.cardCateGoryId);
        $scope.discountSelect    = "";
        $(".discountWords").hide();
        $scope.mathPrice();
    };

    /*** 获取折扣 ***/
    // 获取卡折扣
    $scope.getBuyCardDiscount = function (id){
        $http.get('/member-card/new-card-category?newCardCategory=' + id).success(function (data){
            $scope.getBuyDiscountList = data;
        });
    };
    // 折扣的change事件
    $scope.discountBuyChange = function (data){
        $scope.allMathMoney = "";
        $scope.paymentType  = "";
        if(data != null && data != ''){
            $scope.discountValueList      = angular.fromJson(data);
            $scope.discountAllValue       = $scope.discountValueList.discount; //折扣值
            $scope.discountBuyCardSurplus = $scope.discountValueList.surplus;  //卡折扣剩余数量 -1为不限
            $(".discountWords").show();
            $scope.mathPrice();
        }
        else{
            $scope.discountValueList      = '';
            $scope.discountBuyCardSurplus = ''; //卡折扣剩余数量 -1为不限
            $(".discountWords").hide();
            $scope.mathPrice();
        }
        if($scope.amountMoney == '' || $scope.amountMoney == undefined){
            $scope.allMathMoney   = "";
            $scope.paymentType    = "";
            $scope.discountSelect = "";
            Message.warning("请先输入总金额");
            $(".discountWords").hide();
        }
    };
    /*** 折扣结束 ***/

    /*** 计算价格 ***/
    // 计算价格
    $scope.mathPrice = function (){
        if($scope.discountAllValue == null || $scope.discountAllValue == '' || $scope.discountAllValue == undefined){
            $scope.allMathMoney       = $scope.amountMoney;
            $scope.allMathMoneyBefore = $scope.amountMoney;
        }
        else if($scope.discountSelect == ''){
            $scope.allMathMoney       = $scope.amountMoney;
            $scope.allMathMoneyBefore = $scope.amountMoney;
        }
        else{
            $scope.allMathMoney       = parseInt(parseInt($scope.amountMoney) * (parseInt($scope.discountAllValue)/10));
            $scope.allMathMoneyBefore = parseInt(parseInt($scope.amountMoney) * (parseInt($scope.discountAllValue)/10));
        }
    };
    // 区间价格失去焦点时的判断
    $("#amountMoneyInputVal").blur(function (){
        if($scope.amount != null && $scope.buyCardMaxPrice != undefined && $scope.buyCardMaxPrice != '' &&$scope.buyCardMinPrice != undefined && $scope.buyCardMinPrice != ''){
            var buyMax = parseInt($scope.buyCardMaxPrice);
            var buyMin = parseInt($scope.buyCardMinPrice);
            if($("#amountMoneyInputVal").val() > buyMax || $("#amountMoneyInputVal").val() < buyMin){
                $("#amountMoneyInputVal").val("");
                $scope.amountMoney    = '';
                $scope.allMathMoney   = "";
                $scope.discountSelect = "";
                $scope.paymentType    = "";
                Message.warning("价格有误，请重新输入");
            }
        }
    });
    // 计算区间价格
    $scope.mathAllAmountMoneyLast = function (){
        $scope.paymentType = '';
        if($scope.discountAllValue == null || $scope.discountAllValue == '' || $scope.discountAllValue == undefined){
            $scope.allMathMoney       = $scope.amountMoney;
            $scope.allMathMoneyBefore = $scope.amountMoney;
        }
        else if($scope.discountSelect == ''){
            $scope.allMathMoney       = $scope.amountMoney;
            $scope.allMathMoneyBefore = $scope.amountMoney;
        }
        else if(isNaN($scope.allMathMoney)){
            $scope.allMathMoney = '';
        }
        else{
            $scope.allMathMoney       = parseInt(parseInt($scope.amountMoney) * (parseInt($scope.discountAllValue)/10));
            $scope.allMathMoneyBefore = parseInt(parseInt($scope.amountMoney) * (parseInt($scope.discountAllValue)/10));
        }
    };
    // 定金和抵券的计算
    $scope.paymentTypeChange = function (value){
        if($scope.allMathMoneyBefore == undefined){
          $scope.allMathMoneyBefore = 0;
        }
        if(value == "2"){
            $('.buyCardDepositSelect1 ').attr('disabled',false);
            $scope.getDepositAdd();
            // if($scope.sellingDataVoucher != 0 && $scope.voucherStartSell < $scope.todayTimesTemps && $scope.voucherEndSell > $scope.todayTimesTemps){
            //     $scope.allMathMoney = $scope.allMathMoneyBefore - parseInt($scope.sellingDataVoucher);
            // }
            // if($scope.sellingDataPrice != null && ($scope.sellingDataVoucher == 0 || $scope.sellingDataVoucher == null)){
            if($scope.allDepositMoneyAmount != null){
                // $scope.allMathMoney = $scope.allMathMoneyBefore - parseInt($scope.sellingDataPrice);
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
            $('.buyCardDepositSelect1').attr('disabled',true)
        }
    };

    //定金金额遍历
    $scope.buyCardDepositSelectChange = function () {
        $http.get('/member/deposit-data-type?memberId='+$scope.memberId_buyCardDeposit+'&type=1').then(function (data) {
            $scope.buyCardDepositSelectData = angular.fromJson(data.data.type);
            // console.log('1111',$scope.buyCardDepositSelectData);
            // var objs = $scope.buyCardDepositSelectData;
            // console.log('2222',objs);
            // angular.forEach(objs, function(data){
            //     console.log('33333',data.price);;
            // });
        })
        $scope.getDepositFunc();
        $scope.surplusPrice = parseFloat($scope.isCompare($scope.depositMoneySurplusArr));
        $scope.paymentTypeChange($scope.paymentType);
    };
    /*** 计算结束 ***/
    //点击新增付款途径 新增一行
    $scope.num = 0;
    $scope.attr = '';
    $scope.addPayWayHtml = '';
    $scope.addPaySelectElement = function () {
        $scope.attr = 'addPayWay';
        $scope.num = $scope.num + 1;
        $http({
            url: "/potential-members/add-venue?attr=" + $scope.attr + '&num=' + $scope.num,
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (result) {
            $scope.addPayWayHtml = result.data.html;
        })
    }
    $scope.addPaySelectElement();
    /*** 获取赠送天数 ***/
    $scope.getAllGiftDays = function (id){
        $http.get('/sell-card/given-day?categoryId='+id).success(function (data){
            $scope.getGiftGiveDaysList = data.data;
        });
    };
    $scope.giftsDaysSelectChange = function (data){
        if(data != '' && data != undefined){
            $scope.getGiftGiveDaysListValue   = angular.fromJson(data);
            // if($scope.getGiftGiveDaysListValue.days == null || $scope.getGiftGiveDaysListValue.days != undefined ) {
            //     $scope.getGiftGiveDaysListValue.days        = "";
            // }
        }
        else{
            $scope.getGiftGiveDaysListValue.days        = "";
            $scope.getGiftGiveDaysListValue.id          = "";
            $scope.getGiftGiveDaysListValue             = [];
            $scope.giftsDaysSelect                      = "";
        }
    };
    /*** 赠送天数结束 ***/

    /**
     * 获取验证码
     */
    $scope.getPhone = function () {
        return {
            name: $scope.name,
            idCard: $scope.idCard,
            sex: $scope.sex,
            mobile: $scope.mobile,
            code: $scope.code
        }
    };
    // 获取销售人员信息
    $http.get("/user/get-adviser").success(function (response) {
        $scope.saleInfo = response;
    });
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
    $('#cardList').change(function () {
        $("#fenqi").val("");
        if ($("#fenqi").value == undefined) {
            return false;
        }
    });
    $(document).ready(function () {
        $(".cardSelect").select2();
        $("#selectedCardsGift").select2({
            width: "100%",
            placeholder: "请选择"
        });
        $("#selectedCards").select2({
            width: "100%",
            height: "30px",
        });
        $(".saleSelect").select2({
            width: "100%",
            height: "30px",
            placeholder: "请选择"
        });
        $(".typeSelect").select2({
            width: "100%",
            height: "30px",
            placeholder: "请选择"
        });
        $("#sellSelectUpdateBi").select2({
            width: "100%",
            theme: "classic",
            placeholder: "请选择"
        });
        $("#sellSelectUpdateWays").select2({
            width: "100%",
            theme: "classic",
            placeholder: "请选择"
        });
        $('#venueId').select2({
            width: '80%',
            height: '35px'
        });
        $('#id_label_single').select2({
            width: '80%',
            height: '35px'
        })
    });
    //证件选择
    $scope.selectCredentialsChange = function(id){
        if(id=='1'){
            $scope.inputIdCard();
        }
    }

    $scope.inputIdCard = function(){
        if($scope.selectCredentials == '1'){
            if($scope.idCard != undefined && $scope.idCard !=''){
                var year = $scope.idCard.substring(6,10);
                var month = $scope.idCard.substring(10,12);
                var day  = $scope.idCard.substring(12,14);
                if(day == ''){
                    $scope.birthDate ="";
                }
                if(month != '' && day != ''&& parseInt(month)<=12 && parseInt(day)<=31){
                    $scope.birthDate =year+"-"+month+"-"+day;
                }
                if( parseInt(month)>12 || parseInt(day) > 31){
                    Message.warning("您的身份证号输入有误，请重新输入");
                    return;
                }
            }
        }

    };
    /**** 潜在会员购卡实时验证 ****/
    // 姓名
    $(".nameInputCheck").blur(function (){
        var inputValue = $(".nameInputCheck ").val();
        if(inputValue != ''){
            $(".nameInputCheck").css("border-color","#00cc00");
        }
        else{
            $(".nameInputCheck").css("border-color","red");
            Message.warning("请输入姓名");
        }
    });
    // 身份证
    // $(".idCardInputCheck").blur(function (){
    //     var inputValue = $(".idCardInputCheck").val();
    //     var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
    //     if(inputValue != ''&& idCardP.test(inputValue)){
    //         $(".idCardInputCheck").css("border-color","#00cc00");
    //     }
    //     else{
    //         $(".idCardInputCheck").css("border-color","red");
    //         Message.warning("身份证号输入有误，请重新输入");
    //     }
    // });
    // 编辑潜在会员的身份证
    $(".idCardNewsInput").blur(function (){
        if($scope.selectCredentials == '1'){
            var inputValue = $(".idCardNewsInput").val();
            var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
            if(inputValue != ''&& idCardP.test(inputValue)){
                $(".idCardNewsInput").css("border-color","#00cc00");
            }
            else{
                $(".idCardNewsInput").css("border-color","red");
                Message.warning("身份证号输入有误，请重新输入");
            }
        }
    });
    // 手机号
    $(".mobileInputCheck").blur(function (){
        var inputValue = $(".mobileInputCheck").val();
        var $patternMobile = /^1\d{10}$/;
        if($patternMobile.test(inputValue)){
            $(".mobileInputCheck").css("border-color","#00cc00");
        }
        else{
            $(".mobileInputCheck").css("border-color","red");
            Message.warning("手机号输入有误，请重新输入");
        }
    });
    //判断数组是否有重复
    $scope.isRepeat = function (arr){
        var hash = {};
        for(var i in arr) {
            if(hash[arr[i]]) {
                return true;
            }
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
    //完成时获取新的付款途径
    $scope.getPayArr = function(){
        $scope.allPayArr= [];//支付方式与支付价格的数组对象
        $scope.payMethodArr = [];
        $scope.payPriceArr = [];
        var $payDiv = $('.addSelectElement').children('div');
        $payDiv.each(function (index,item) {
            var _payMethod = $(this).find('select').val();
            var _payPrice = $(this).find('input').val();
            if(_payPrice == '') {
                _payPrice = 0;
            }
            var data = {
                type  : _payMethod,
                price : _payPrice
            }
            $scope.allPayArr.push(data);
            $scope.payMethodArr.push(_payMethod);
            $scope.payPriceArr.push(_payPrice);
        })
    }
    $scope.addPayPriceChange = function () {
        $scope.getPayArr();
        $scope.allPayWayPrice = parseFloat($scope.isCompare($scope.payPriceArr));
    }
    $scope.addPayPriceChange123 = function(){
        $timeout(function(){
            $scope.addPayPriceChange();
        },100)
    }
    $scope.addPayPriceChange321 = function(){
        $timeout(function(){
            $scope.getPayArr();
            if($scope.isRepeat($scope.payMethodArr)) {
                Message.warning('付款途径不能有重复！');
                return;
            }
        },100)
    };

    /**
     * 遍历定金的数据 存到数组 准备向后台发送
     */
    $scope.getDepositFunc = function(){
        $scope.depositIdArr= [];
        $scope.depositMoneyArr = [];
        $scope.depositMoneySurplusArr = [];
        var $depositDiv = $('.buyCardDepositSelect1').children('option');
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
        $scope.getDepositFunc();
        //计算选中的用于抵扣的定金总额
        $scope.allDepositMoneyAmount = parseFloat($scope.isCompare($scope.depositMoneyArr)).toFixed(2);
        // $scope.surplusPrice = parseFloat($scope.allMathMoney) - parseFloat($scope.allDepositMoneyAmount);
    }
    //整理发送后的数据
    $scope.addBuyCardData = function () {
        return {
            name          : $scope.name                         != undefined && $scope.name                         != "" ? $scope.name                         : null,
            idCard        : $scope.idCard                       != undefined && $scope.idCard                       != "" ? $scope.idCard                       : null,
            sex           : $scope.sex                          != undefined && $scope.sex                          != "" ? $scope.sex                          : null,
            mobile        : $scope.mobile                       != undefined && $scope.mobile                       != "" ? $scope.mobile                       : null,
            cardCateGoryId: $scope.cardCateGoryId               != undefined && $scope.cardCateGoryId               != "" ? $scope.cardCateGoryId               : null,
            paymentType   : $scope.paymentType                  != undefined && $scope.paymentType                  != "" ? $scope.paymentType                  : null,
            payTimes      : $scope.payTimes                     != undefined && $scope.payTimes                     != "" ? $scope.payTimes                     : null, //分期次数
            saleMan       : $scope.saleMan                      != undefined && $scope.saleMan                      != "" ? $scope.saleMan                      : null,
            amountMoney   : $scope.amountMoney                  != undefined && $scope.amountMoney                  != "" ? $scope.amountMoney                  : null,
            // payMethod  :$scope.paymentMethod                 != undefined && $scope.paymentMethod                != "" ? $scope.paymentMethod                : null,
            payMethod     : $scope.allPayArr                    != undefined && $scope.allPayArr                    != "" ? $scope.allPayArr                    : null,
            cardNumber    : $scope.cardCheckNumber              != undefined && $scope.cardCheckNumber              != "" ? $scope.cardCheckNumber              : null,
            giftStatus    : $scope.giftStatus                   != undefined && $scope.giftStatus                   != "" ? $scope.giftStatus                   : null, // 领取赠品
            usageMode     : $scope.usageMode                    != undefined && $scope.usageMode                    != "" ? $scope.usageMode                    : null, // 领取赠品
            depositArrId  : $scope.depositIdArr                 != undefined && $scope.depositIdArr                 != "" ? $scope.depositIdArr                 : null, //包含所有定金Id的数组
            deposit       : $scope.allDepositMoneyAmount        != undefined && $scope.allDepositMoneyAmount        != "" ? $scope.allDepositMoneyAmount        : 0  ,  //定金
            // cashCoupon : $scope.sellingDataVoucher           != undefined && $scope.sellingDataVoucher           != "" ? $scope.sellingDataVoucher           : 0 ,   //代金券
            netPrice      : $scope.allMathMoney, // 实收价格(应付金额)
            giveDay       : $scope.getGiftGiveDaysListValue.days!= undefined && $scope.getGiftGiveDaysListValue.days!= "" ? $scope.getGiftGiveDaysListValue.days: null,
            giftDayId     : $scope.getGiftGiveDaysListValue.id  != undefined && $scope.getGiftGiveDaysListValue.id  != "" ? $scope.getGiftGiveDaysListValue.id  : null,
            note          : $scope.potentialBuyCardNote         != undefined && $scope.potentialBuyCardNote         != "" ? $scope.potentialBuyCardNote         : null, //备注
            belongVenue   : $scope.cardVenueId                  != undefined && $scope.cardVenueId                  != "" ? $scope.cardVenueId                  : null, //场馆ID
            url           : $scope.newUserContactUrl,
            documentType  : $scope.buyCardSelectCredentials     != undefined &&  $scope.buyCardSelectCredentials    != "" ? $scope.buyCardSelectCredentials     : null,
            birthDay      : $scope.buyCardBirthdays123          != undefined &&  $scope.buyCardBirthdays123         != "" ? $scope.buyCardBirthdays123          : null,
            _csrf_backend : $('#_csrf').val()
        };
    };
    //获取前台数据并提交给后台
    $scope.addBuyCard = function () {
        $scope.getPayArr();
        if($scope.isRepeat($scope.payMethodArr)) {
            Message.warning('付款途径不能有重复！');
            return;
        }
        var cardCheckNumber = $(".cardCheckNumber").val();
        var currentMobile   = $('#mobile').val();
        $scope.potentialBuyCardNote = $('#potentialMemberBuyCardNote').val();
        // if($scope.sellingDataPrice == '' || $scope.sellingDataPrice == undefined || $scope.sellingDataPrice == null){
        //     $scope.paidInPrice = $scope.amountMoney
        // }
        // if($scope.sellingDataPrice != '' || $scope.sellingDataPrice != undefined || $scope.sellingDataPrice != null){
        //     $scope.paidInPrice = $(".amountPaid").text();
        // }
        //保存数据之前数据验证
        if ($scope.name == null || $scope.name == "") {
            Message.warning("名字不能为空");
            return false;
        }
        // var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        // if ($scope.idCard == null || $scope.idCard == "" || !(idCardP.test($scope.idCard))) {
        //     Message.warning("请输入18位有效身份证号");
        //     return false;
        // }
        if ($scope.sex == null || $scope.sex == "") {
            Message.warning("请选择性别");
            return false;
        }
        var $patternMobile = /^1\d{10}$/;
        if ($scope.mobile == null || $scope.mobile == "" || !($patternMobile.test($scope.mobile))) {
            Message.warning("手机号输入有误");
            return false;
        }
        if($scope.buyCardSelectCredentials == null || $scope.buyCardSelectCredentials == "" || $scope.buyCardSelectCredentials == undefined ){
            Message.warning("请选择证件类型");
            return false;
        }
        //当证件类型不为空的时候判断证件号
        if($scope.buyCardSelectCredentials != undefined && $scope.buyCardSelectCredentials !='' && $scope.buyCardSelectCredentials !=null){
            if($scope.buyCardSelectCredentials == '1'){
                var inputValue = $scope.idCard;
                var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
                if(!idCardP.test(inputValue)){
                    Message.warning('您的身份证号输入有误，请重新输入');
                    return;
                }
            }
        }
        if($scope.idCard == null || $scope.idCard == "" || $scope.idCard == undefined ){
            Message.warning("请输入证件号");
            return false;
        }
        // if ($scope.cardVenueId == null || $scope.cardVenueId == "" || $scope.cardVenueId == undefined) {
        //     Message.warning("请选择场馆!");
        //     return;
        // }
        if ($scope.cardCateGoryId == null || $scope.cardCateGoryId == "") {
            Message.warning("请选择卡种");
            return false;
        }
        if ($scope.saleMan == null || $scope.saleMan == "") {
            Message.warning("请选择销售");
            return false;
        }
        if ($scope.amountMoney == null || $scope.amountMoney == "") {
            Message.warning("请输入金额");
            return false;
        }
        if ($scope.paymentType == null || $scope.paymentType == "") {
            Message.warning("请选择收款方式");
            return false;
        }
        if ($scope.usageMode == null || $scope.usageMode == "") {
            Message.warning("请选择使用方式");
            return false;
        }
        if ($scope.giftStatus == null || $scope.giftStatus == "") {
            Message.warning("请选择是否领取过赠品");
            return false;
        }
        if($scope.amountMoneyMax != null && $scope.amountMoneyMin != null){
            var  money = parseInt($scope.amountMoney);
            var max = parseInt($scope.amountMoneyMax);
            var min = parseInt($scope.amountMoneyMin);
            if(money > max + 1){
                Message.warning("输入金额不能大于"+$scope.amountMoneyMax);
                return false;
            }
            if( money < min - 1){
                Message.warning("输入金额不能小于"+$scope.amountMoneyMin);
                return;
            }
        }
        // if ($scope.paymentMethod == null || $scope.paymentMethod == ''){
        //     Message.warning("请选择付款方式");
        //     return false;
        // }
        for(var i = 0;i<$scope.payMethodArr.length;i++) {
            if($scope.payMethodArr[i] == null || $scope.payMethodArr[i] == undefined || $scope.payMethodArr[i] == '') {
                Message.warning('付款途径或价格不能为空，请重新输入！');
                return;
            }
        }
        for(var i = 0;i<$scope.payPriceArr.length;i++) {
            var regTest = /^\d+(\.\d{1,2})?$/;
            if(!regTest.test($scope.payPriceArr[i])) {
                Message.warning('金额只能输入整数或两位以内小数！');
                return;
            }
        }
        $scope.isRepeat($scope.payMethodArr);
        if($scope.isRepeat($scope.payMethodArr)) {
            Message.warning('付款途径不能有重复，请重新输入！');
            return;
        }
        if (parseFloat($scope.isCompare($scope.payPriceArr)) != parseFloat($scope.addBuyCardData().netPrice)){
            Message.warning("所有付款途径价格之和必须等于应付金额！");
            return;
        }
        if($scope.getGiftGiveDaysListValue.surplus == '0' || $scope.getGiftGiveDaysListValue.surplus == 0){
            Message.warning("赠送次数用完");
            return false;
        }
        $scope.getDepositAdd();
        Sweety.remove({
            url : "/sell-card/confirm-sell-card",
            http : $http,
            title : '确定是否已收款?',
            text : '未确定请点击取消',
            buttonColor : '#27c24c',
            confirmButtonText : '确定',
            confirmButton : '确定',
            data : {
                    action: 'unbind'
            }
        }, function () {
            $http.get('/purchase-card/deal?cardId=' + $scope.cardCateGoryId).success(function (data){
                $scope.getNewContractData = data.intro;
                $scope.newContractName = data.name;
            });
            $.loading.show();
            $http({
                url: "/sell-card/sell-card",
                method: 'POST',
                data: $.param($scope.addBuyCardData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (data) {
                $scope.dataUp = data;
                if ($scope.dataUp.status == "success") {
                    Message.success('购卡成功');
                    $('#potentialPurchaseModal').modal('hide');
                    /*if(parseInt($scope.paymentType) == 2){
                        $http.get('/sell-card/del-deposit?id='+$scope.orderid123).then(function(reponse){
                            $scope.listsDates();
                        });
                    }*/
                    $scope.listsDates();
                    $scope.card             = '';
                    $scope.paymentType      = '';
                    $scope.saleMan          = '';
                    $scope.paymentMethod    = '';
                    $scope.cardCheckNumber  = '';
                    $scope.giftStatus       = '';
                    $scope.usageMode        = '';
                    $.loading.hide();
                }
                else {
                    Message.warning($scope.dataUp.data);
                    $.loading.hide();
                }
            });
        },function(){

        },true,true);
    };
    // 潜在会员购卡模态框关闭事件
    // $('#potentialPurchaseModal').on('hide.bs.modal',function (){
    //     $scope.card             = '';
    //     $scope.paymentType      = '';
    //     // $scope.saleMan          = '';
    //     $scope.paymentMethod    = '';
    //     $scope.cardCheckNumber  = '';
    //     $scope.giftStatus       = '';
    //     $scope.usageMode        = '';
    // });
    // 获取公司
    $http.get('/potential-members/get-identify').success(function (data){
            $scope.companyList = data;
            $scope.identifyCompany = data.identify;
    });
    // 获取场馆信息
    $http.get('/site/get-auth-venue').success(function (data){
        $scope.venueListInfo = data;
    });

    // 选择公司
    $scope.companyChange = function (id){
        $scope.companyId = id;
        // 获取场馆信息
        $http.get('/site/get-auth-venue?companyId=' + $scope.companyId + '&status=' + $scope.identifyCompany).success(function (data){
            $scope.venueListInfo = data;
            $scope.postCompanySearch();
        })
    };
    // 发送公司参数搜索
    $scope.postCompanySearch = function (){
        $http.get("/member/get-member-info?nowCompanyId=" + $scope.companyId).success(function (data){
            if(data.data.length <= 0 ){
                $scope.listDate = data.data;
                $scope.payNoMoneyDataShow = true;
                $scope.pages = data.pages;
            }else {
                $scope.listDate = data.data;
                $scope.payNoMoneyDataShow = false;
                $scope.pages = data.pages;
            }
        });
    }
    // 发送场馆参数搜索
    $scope.changeVenue = function (id){
        var array = {
            venueId  : id,
            wayToShop: $scope.changeSectleValue,
            keywords : $scope.input.searchValue,
            sortType : $scope.sortType != undefined ? $scope.sortType : null,
            sort     : $scope.sort != undefined ? $scope.sort : null,
            potMember: $scope.memberType != undefined ? $scope.memberType : null,
            newMember: $scope.memberType != undefined ? $scope.memberType : null,
        };
        $http.get("/member/get-member-info?" + $.param(array)).success(function (data){
            $scope.listDate = data.data;
            if(data.data.length <= 0 ){
                $scope.listDate = data.data;
                $scope.payNoMoneyDataShow = true;
                $scope.pages = data.pages;
            }else {
                $scope.listDate = data.data;
                $scope.payNoMoneyDataShow = false;
                $scope.pages = data.pages;
            }
        });
    };
    // 编辑潜在会员
    $scope.updateUser = function (id,userName,mobile,object){
        // 获取选中的会员信息
        $scope.updateUserId = id;
        $scope.updateUserName = userName;
        $scope.updateUserMobile = mobile;
        if(object.document_type != null){
            $scope.EditSelectCredentials = object.document_type;
        }else{
            $scope.EditSelectCredentials = '';
        }

        //生日选择的时间选择器
        $("#dateTimeBirthday").datetimepicker({
            format: 'yyyy-mm-dd',
            minView: "month",//设置只显示到月份
            language: 'zh-CN',
            autoClose: true
        });
        $scope.counselor();
        // 获取会员详情信息
        $http({
            url: '/user/member-information?memberId=' + $scope.updateUserId,
            method: 'get'
        }).then(function (data) {
            $scope.potentialDetailS = data.data;
            $scope.updateIdCard = $scope.potentialDetailS.id_card;
            $scope.updateSex = $scope.potentialDetailS.sex;
            $scope.updateBirthDay = $scope.potentialDetailS.birth_date;
            $scope.updateSell = $scope.potentialDetailS.counselor_id;
            $scope.updateWayToShop = $scope.potentialDetailS.toShopId;
            // $scope.idCardChangeBirth();
            if($scope.potentialDetailS.params == null||$scope.potentialDetailS.params == ''){
                $scope.updateNote = '';
            }
            else {
                $scope.updateNote = $.parseJSON($scope.potentialDetailS.params).note;
            }
        }, function (error) {
            Message.error('系统错误请联系工作人员')
        });
        // 模态框触发
        $("#updateUserModal").modal("show");
    };
    //修改证件类型选择
    $scope.EditSelectCredentialsChange = function(){
        $scope.idCardChangeBirth();
    };

    // 身份证转换成生日
    $scope.idCardChangeBirth = function (){
        if($scope.EditSelectCredentials == '1'){
            if($scope.updateIdCard != undefined && $scope.updateIdCard !=''){
                var year = $scope.updateIdCard.substring(6,10);
                var month = $scope.updateIdCard.substring(10,12);
                var day  = $scope.updateIdCard.substring(12,14);
                if(day == ''){
                    $scope.updateBirthDay ="";
                }
                if(month != '' && day != ''&& parseInt(month)<=12 && parseInt(day)<=31){
                    $scope.updateBirthDay =year+"-"+month+"-"+day;
                }
                if( parseInt(month)>12 || parseInt(day) > 31){
                    Message.warning("您的身份证号输入有误，请重新输入");
                    return;
                }
            }
        }
    };
    // 潜在会员编辑数据整理
    $scope.updateUserData = function () {
        return{
            memberId   :   $scope.updateUserId    != undefined && $scope.updateUserId    != "" ? $scope.updateUserId    :    null,
            sex        :   $scope.updateSex       != undefined && $scope.updateSex       != "" ? $scope.updateSex       :    null,
            idCard     :   $scope.updateIdCard    != undefined && $scope.updateIdCard    != "" ? $scope.updateIdCard    :    null,
            birthDate  :   $scope.updateBirthDay  != undefined && $scope.updateBirthDay  != "" ? $scope.updateBirthDay  :    null,
            wayToShop  :   $scope.updateWayToShop != undefined && $scope.updateWayToShop != "" ? $scope.updateWayToShop :    null,
            counselorId:   $scope.updateSell      != undefined && $scope.updateSell      != "" ? $scope.updateSell      :    null,
            note       :   $scope.updateNote      != undefined && $scope.updateNote      != "" ? $scope.updateNote      :    null,
            documentType :   $scope.EditSelectCredentials!= undefined && $scope.EditSelectCredentials != "" ? $scope.EditSelectCredentials      :    null,
            _csrf_backend: $('#_csrf').val()
        };
    };
    // 潜在会员编辑数据提交
    $scope.updateUserSuccess = function () {
        if ($scope.updateSex == null || $scope.updateSex == "") {
            Message.warning("请选择性别");
            return false;
        }
        if ($scope.updateIdCard == null || $scope.updateIdCard == "") {
            Message.warning("请输入证件号");
            return false;
        }
        if ($scope.EditSelectCredentials == null || $scope.EditSelectCredentials == "" || $scope.EditSelectCredentials == undefined) {
            Message.warning("请输证件类型");
            return false;
        }
        if($scope.EditSelectCredentials == '1'){
            var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
            if ($scope.updateIdCard == null || $scope.updateIdCard == "" || !(idCardP.test($scope.updateIdCard))) {
                Message.warning("请输入18位有效身份证号");
                return false;
            }
        }
        // if ($scope.updateIdCard.length < 18 || $scope.updateIdCard.length > 18) {
        //     Message.warning("请输入正确的18位身份证号");
        //     return false;
        // }
        if ($scope.updateBirthDay == null || $scope.updateBirthDay == "") {
            Message.warning("请选择生日日期");
            return false;
        }
        if ($scope.updateWayToShop == null || $scope.updateWayToShop == "") {
            Message.warning("请选择来源渠道");
            return false;
        }
        if ($scope.updateSell == null || $scope.updateSell == "") {
            Message.warning("请选择销售");
            return false;
        }
        else {
            $http({
                url: "/member/edit-member",
                method: "POST",
                data: $.param($scope.updateUserData()),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (status) {
                if (status.data.status == 'success') {
                    $scope.listsDates();
                    Message.success('提交完成');
                    $("#updateUserModal").modal("hide")
                } else {
                    Message.warning(status.data.data);
                    return false;
                }
            });
        }
    };
    //押金
    $scope.deposit = function (id,name) {
        $("#deposit").modal("show");
        $scope.depositOkMemberId = id;
        $scope.depositOkMemberName = name;
        $scope.depositPayType = '';
        $("#reservation").val('');
        // 获取销售人员信息
        $http.get("/user/get-adviser").success(function (response) {
            $scope.saleInfo = response;
        });
    };

    /** 跟进 开始 **/
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
    };
    //跟进 模态框
    $scope.followUp = function (memberId) {
        $scope.potentialMemberId = memberId;
        $("#follow-up").modal("show");
        $scope.followOkLoad = false;
        $('#entryNote').val('');
        //默认获取当前时间
        $('#registerDate').val($scope.getDateTime());
        //选择入场日期
        $("#registerDate").datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            language: 'zh-CN',
            autoclose: true
        });
    };
    //跟进 完成
    $scope.followOk = function () {
        var data = {
            memberId      : $scope.potentialMemberId,
            entryTime     : $('#registerDate').val(),
            note          : $scope.entryNote,
            _csrf_backend : $('#_csrf').val()
        };
        if($('#registerDate').val() == undefined || $('#registerDate').val() == ''){
            Message.warning('请选择入场时间');
            return;
        }
        $scope.followOkLoad = true;
        $http({
            method : 'post',
            url    : '/potential-members/follow-up',
            data   : $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success(data.data.data);
                $("#follow-up").modal("hide");
                $scope.listsDates();
            }else{
                Message.warning(data.data.data);
                $scope.followOkLoad = false;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    /** 跟进 结束 **/

    //完成
    $scope.depositOk = function () {
        var leaveDateRange = $("#reservation").val();
        var startTime = leaveDateRange.slice(0,10)+' 00:00:01';
        var endTime = leaveDateRange.slice(13)+' 23:59:59';
        console.log(startTime+'---'+endTime);
        var data = {
            memberId:$scope.depositOkMemberId,
            price:$scope.depositMoney,
            // voucher:$scope.depositToRoll,
            startTime:startTime,
            endTime:endTime,
            payMode:$scope.depositPayMode,
            payType:$scope.depositPayType,
            seller : $scope.sellName,         //销售
            _csrf_backend: $('#_csrf').val()
        };
        if(data.price == ''|| data.price == undefined){
            Message.warning("请输入金额");
            return
        }
        if(data.payMode == ''|| data.payMode == undefined){
            Message.warning("请选择付款方式");
            return
        }
        if(data.payType == ''|| data.payType == undefined){
            Message.warning("请选择定金类型");
            return
        }
        // if(data.voucher != null){
        //     if(data.voucher < data.price){
        //         Message.warning("抵券金额不能小于定金金额");
        //         return
        //     }
        // }
        if(data.startTime == '' || data.startTime == undefined || data.startTime==" 00:00:01"){
            Message.warning("请选择时间");
            return
        }
        if ($scope.sellName == '' || $scope.sellName == undefined||$scope.sellName == null) {
            Message.warning("请选择销售")
            return
        }
        // if(parseInt($scope.depositMoney) < parseInt($scope.depositToRoll)){
        //     Sweety.remove({
        //         url : "/sell-card/confirm-deposit",
        //         http : $http,
        //         title : '确定要提交吗?',
        //         text : '定金金额小于抵劵金额',
        //         buttonColor : '#27c24c',
        //         confirmButtonText : '确定',
        //         confirmButton : '确定',
        //         data : {
        //             action: 'unbind'
        //         }
        //     }, function () {
        //         $.loading.show();
        //         $http({method:'post',url:'/potential-members/set-member-deposit-form',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
        //             if(data.data.status == "success"){
        //                 $("#deposit").modal("hide");
        //                 $scope.listsDates()
        //                 $scope.depositOkMemberId = '';
        //                 $scope.depositMoney = "";
        //                 $scope.depositPayMode = "";
        //             }
        //             $.loading.hide();
        //         },function (error) {
        //             $.loading.hide();
        //             console.log(error)
        //             Message.error("系统错误请联系工作人员")
        //         })
        //     },function(){
        //
        //     },true,true);
        //
        // }else{
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
                    if(data.data.status == "success"){
                        $("#deposit").modal("hide");
                        $scope.listsDates()
                        $scope.depositOkMemberId = '';
                        $scope.depositMoney = "";
                        $scope.depositPayMode = "";
                    }
                },function (error) {
                    console.log(error)
                    Message.error("系统错误请联系工作人员")
                })
            },function(){

            },true,true);
        // }
        // if(parseInt($scope.depositMoney) == parseInt($scope.depositToRoll)){
        //     Sweety.remove({
        //         url : "/sell-card/confirm-deposit",
        //         http : $http,
        //         title : '确定要提交吗?',
        //         text : '定金金额与抵劵金额相等吗?',
        //         buttonColor : '#27c24c',
        //         confirmButtonText : '确定',
        //         confirmButton : '确定',
        //         data : {
        //             action: 'unbind'
        //         }
        //     }, function () {
        //         $http({method:'post',url:'/potential-members/set-member-deposit-form',data:$.param(data),headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (data) {
        //             if(data.data.status == "success"){
        //                 $("#deposit").modal("hide");
        //                 $scope.listsDates()
        //                 $scope.depositOkMemberId = '';
        //                 $scope.depositMoney = "";
        //                 $scope.depositPayMode = "";
        //             }
        //         },function (error) {
        //             console.log(error)
        //             Message.error("系统错误请联系工作人员")
        //         })
        //     },function(){
        //
        //     },true,true);
        // }
    };
    /**** 送人卡潜在会员 ****/

    // tab详情切换
    $(".myModalDiv2").hide();
    $(".myModalDiv3").hide();
    // 基本信息
    $('.tabT1').click(function (){
        $(".tabT1").addClass("activeBox");
        $(".tabT2").removeClass("activeBox");
        $(".tabT3").removeClass("activeBox");
        $(".myModalDiv1").show();
        $(".myModalDiv2").hide();
        $(".myModalDiv3").hide();
    });
    // 赠卡详情
    $('.tabT2').click(function (){
        $(".tabT2").addClass("activeBox");
        $(".tabT1").removeClass("activeBox");
        $(".tabT3").removeClass("activeBox");
        $(".myModalDiv1").hide();
        $(".myModalDiv3").hide();
        $(".myModalDiv2").show();
    });
    // 送卡记录
    $('.tabT3').click(function (){
        $(".tabT3").addClass("activeBox");
        $(".tabT1").removeClass("activeBox");
        $(".tabT2").removeClass("activeBox");
        $(".myModalDiv1").hide();
        $(".myModalDiv2").hide();
        $(".myModalDiv3").show();
    });

/*** 潜在会员卡信息 ***/
    // 查询潜在会员名下所有卡
    $scope.sendCardTal = function () {
        $.loading.show();
        $http.get('/user/member-card-info?MemberId=' +  $scope.potentialUserId+ "&type=1").success(function (data){
            $scope.cardList = data.item;
            if($scope.cardList.length > 0){
                var $postData = angular.toJson($scope.cardList[0]);
                $scope.getPotentialUserCardInfo($postData);
                $.loading.hide();
            }
            else{
                $scope.getPotentialUserCardInfo();
                $.loading.hide();
            }
        });
    };

    // 查询潜在会员名下卡详情
    $scope.getPotentialUserCardInfo = function(id){
        $scope.allDte    = id ;
        var $idData      = angular.fromJson(id);
        if($idData != undefined && $idData != null && $idData != ""){
            $scope.infoId    = $idData.id;
            $scope.usageMode = $idData.usage_mode;
        }
        else {
            $scope.infoId    = '';
        }
        $scope.getUrlPotentialInfo($scope.infoId);
        $scope.getPotentialUserPayment($scope.infoId);
    };
    $scope.selectEntryRecord = "";
    //初始化送人卡的到期时间和激活时间
    $scope.expiryTimeChange = '';
    $scope.delayActiveCardChange = '';
    $scope.getUrlPotentialInfo =function (id){
        $http.get("/member/get-member-card?memberCardId=" + id).success(function (data){
            $scope.cardInfo = data;
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
            $(".cardSelectName").val($scope.allDte);
            var $option = $(".cardSelectName").find('option').eq(0).html();
            if($option == '' || $option == null ){
                $(".cardSelectName").find('option').eq(0).remove();
            }
            $scope.transferPrice  = $scope.cardInfo.transfer_price;
            $scope.cardActiveTime = data.active_time;
            // 到期时间的时间戳
            $scope.cardInfoTime   = data.invalid_time;
            // 计算剩余时间的js
            // 获取今日时间
            $scope.timestamp      = Date.parse(new Date()) / 1000;
            $scope.remainingDate  = Math.round((((parseInt($scope.cardInfoTime) - parseInt($scope.timestamp)) / 24) / 60) / 60);
            // $(".cardSelectName").val($scope.cardInfo.id);
        });
    };

    // 获取潜在会员缴费记录
    $scope.getPotentialUserPayment = function(id){
        $http.get("/member/history?memberCardId=" + id).success(function (data){
            if (data == "" || data == undefined || data.length <= 0) {
                $scope.paymentItems        = data;
                $scope.payNoMoneyDataShowS = true;
            }
            else {
                $scope.paymentItems        = data;
                $scope.payNoMoneyDataShowS = false;
            }
        });
    };

    // 获取潜在会员送人卡信息记录
    $scope.getMemberSendCardRecord = function (){
        $http.get("/user/get-member-send-record?memberId=" + $scope.potentialUserId).success(function (data){
            $scope.memberSendCardList = data.data;
            if($scope.memberSendCardList.length == 0){
                $scope.payNoSendCardRecordDataShow = true; //暂无数据图像显示
            }
            else{
                $scope.payNoSendCardRecordDataShow = false; //暂无数据图像关闭
            }
            console.log($scope.payNoSendCardRecordDataShow);
        });
    };
    // 获取潜在会员约课信息记录
    $scope.getMemberAboutClassRecord = function (){
        $http.get("/user/group-class-info?MemberId=" + $scope.potentialUserId+"&type=pot").success(function (data){
            $scope.memberAboutList = data.group;
            console.log(data);
            if(data.group == null && data.group == undefined || data.group.length == 0){
                $scope.memberAboutDataList = true; //暂无数据图像显示
            }
            else{
                $scope.memberAboutDataList = false; //暂无数据图像关闭
            }
        });
    };
    // 获取潜在会员预约场地约课信息记录
    $scope.getMemberAboutYardRecord = function (){
        $http.get("/member/get-member-about-yard-record?memberId=" + $scope.potentialUserId).success(function (data){
            $scope.memberYardList = data.data;
            if($scope.memberYardList.length == 0){
                $scope.memberYardDataList = true; //暂无数据图像显示
            }
            else{
                $scope.memberYardDataList = false; //暂无数据图像关闭
            }
        });
    };
    // 获取最近入场记录
    $scope.getFollowRecord = function (){
        $http.get("/potential-members/get-follow-record?memberId=" + $scope.potentialUserId).success(function (data){
            $scope.followRecord      = data.data;
            $scope.followRecordPages = data.pages;
            if($scope.followRecord.length == 0){
                $scope.followRecordList = true;  //暂无数据图像显示
            }else{
                $scope.followRecordList = false; //暂无数据图像关闭
            }
        });
    };

    //获取送人卡修改时间记录
    $scope.getSendCardTimeRecord = function () {
        $http.get('/potential-members/information-records?memberId=' + $scope.potentialUserId).then(function (response) {
            console.log(response);
            $scope.changeTimeLists = response.data.data;
        })
    };
    
    //获取行为记录
    $scope.SelectMessage = function (value) {
        $scope.behaviorRecordUrl = '/user/information-records?memberId=' + $scope.MemberId123;
        //送人记录
        if(value == ''){
            $scope.selectEntryRecord = "";
            $scope.getMemberSendCardRecord();
        }
        //约课记录
        if(value == '1'){
            $scope.getMemberAboutClassRecord();
        }
        //场地记录
        if(value == '2'){
            $scope.getMemberAboutYardRecord();
        }
        //定金信息
        if(value == '3'){
            $('.Deposit').removeClass('DN');
            $scope.getDepositAllMoney();
        }else {
            $('.Deposit').addClass('DN');
        }
        //最近入场记录
        if(value == '4'){
            $scope.getFollowRecord();
        }
        //赠卡记录
        if(value == '5') {
            $scope.getSendCardTimeRecord();
        }
    };
    /****** 打印机start *****/
    //潜在会员卡打印单条
    $scope.aboutPrints = function (item) {
        $scope.printers = item;
        $scope.printTicket();
    };
    $scope.printTicket = function () {
        if ($scope.printers == undefined || !$scope.printers) {
            Message.warning('没有预约的课程无法打印');
            return false;
        }
        $scope.aboutId = $scope.printers.id;
        $scope.getPrintHtml();
    };
    $scope.getPrintHtml = function () {
        var open = 1;
        if (open < 10) {
            var $prints = $('#coursePrints');
            var bodyHtml = $('body').html();
            $scope.bdhtml = $prints.html();//获取当前页的html代码
            window.document.body.innerHTML = $scope.bdhtml;
            $scope.updateAboutStatus();
            window.print();
            window.document.body.innerHTML = bodyHtml;
            location.replace(location.href);
        } else {
            window.print();
        }
    };
    $scope.updateAboutStatus = function () {
        $http.get('/check-card/update-about-print?id=' + $scope.aboutId).then(function (result) {

        });
    };
    /****** 打印机end ******/
    //打印场地单条
    $scope.aboutPrintsField = function (printer) {
        $scope.printerField = printer;
        // console.log($scope.printerField);
        $timeout(function () {
            var open = 1;
            if (open < 10) {
                var $prints = $('#field');
                var bodyHtml = $('body').html();
                var bdhtml = $prints.html();//获取当前页的html代码
                window.document.body.innerHTML = bdhtml;
                window.print();
                window.document.body.innerHTML = bodyHtml;
                location.replace(location.href);
            } else {
                window.print();
            }
        }, 100);
        // }
    }

    /****定金信息****/
    $scope.getDepositAllMoney = function() {
        if($scope.depositSelect == undefined || $scope.depositSelect == null) {
            $scope.depositSelect = '';
        }
        $http.get('/member/member-deposit-list?memberId='+$scope.potentialUserId+'&type='+$scope.depositSelect).then(function (data) {
            if(data != null && data !=undefined && data != '') {
                $scope.getDepositInfoData = data.data.deposit;
                if($scope.getDepositInfoData.length == 0) {
                    $scope.depositAllMoney = 0;
                }else {
                    $scope.depositAllMoney = data.data.allPrice;
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
    //潜在会员场地
    $scope.field = function(id,type){
        $scope.venue = '';
        $scope.aboutFieldId = id;
        var $time = new Date().getTime();
        $scope.currentDate123 = $scope.getMyDate($time);
        $scope.startDate = $scope.getMyDate($time);
        $('#fieldModals').modal('show');
        $scope.fieldModals();
        //获取登陆者信息
        $http.get('/personnel/employee-center').success(function (data){
            $scope.venue = data.venue_id;
            $scope.fieldModals();
        });
        // 获取场馆信息
        $http.get('/site/get-auth-venue').success(function (data){
            $scope.venueList = data;
            $scope.fieldModals();
        });
    };
    //
    $scope.fieldDateChange = function(date){
        var $date = $('#dataLeave12>input').val();
        $scope.startDate = $date;
        $scope.selectSiteManagement($scope.initYardPageId, $scope.initYardPageNumber);
    };

    //选择场馆
    $scope.choseVenue = function(id){
        $scope.venue = id;
        $scope.fieldModals();
    };

    //获取所有的场地
    $scope.fieldModals = function () {
        $scope.venue = $scope.venue == undefined ? '' : $scope.venue;
        $http({method: 'get', url: '/site-management/get-potential-yard-page?venueId=' + $scope.venue}).then(function (response) {
            var $len = response.data.data.length;
            if($len != 0){
                $scope.listDataItems = response.data.data;
                $scope.nowPage = response.data.nowPage;
                $scope.totalPage = response.data.totalPage;
                $scope.fieldPages =response.data.pages;
                $scope.initYardPageId = response.data.data[0].id;
                $scope.selectSiteManagement($scope.initYardPageId, 0);
                $timeout(function(){
                    $('#fieldModals .SiteManagement').eq(0).addClass("colorf0f3f4");
                },1)
            }else{
                $scope.listDataItems = response.data.data;
                $scope.nowPage = response.data.nowPage;;
                $scope.totalPage = response.data.totalPage;
                $scope.fieldPages =response.data.pages;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };

    //场地分页
    $scope.potentialPage = function(urlPages){
        $http({method: 'get', url: urlPages}).then(function (response) {
            var $len = response.data.data.length;
            if($len != 0){
                $scope.listDataItems = response.data.data;
                $scope.nowPage = response.data.nowPage;
                $scope.totalPage = response.data.totalPage;
                $scope.fieldPages =response.data.pages;
                $scope.initYardPageId = response.data.data[0].id;
                $scope.selectSiteManagement($scope.initYardPageId, 0);
                $timeout(function(){
                    $('#fieldModals .SiteManagement').eq(0).addClass("colorf0f3f4");
                },1)
            }else{
                $scope.listDataItems = response.data.data;
                $scope.nowPage = response.data.nowPage;;
                $scope.totalPage = response.data.totalPage;
                $scope.fieldPages =response.data.pages;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    }


    //get条件
    $scope.fieldSelectData = function () {
        return {
            yardId :$scope.initYardPageId,   // 场地id    （备注：潜在会员预约所需参数：yardId,memberAboutDate,memberId,aboutObject）
            memberAboutDate:$scope.startDate, //预约日期
            memberId: $scope.aboutFieldId,
            aboutObject:'member'   //会员
        }
    };
    //选择场地
    $scope.selectSiteManagement = function (id, i) {
        $('#fieldModals .SiteManagement').eq(i).addClass("colorf0f3f4").siblings('.SiteManagement').removeClass("colorf0f3f4");
        if (id == '' || id == undefined || id == null) {
            return false;
        }
        $scope.initYardPageId = id;
        $scope.initYardPageNumber = i;
        $http({
            method: 'get',
            url: '/site-management/yard-detail?' + $.param($scope.fieldSelectData())
        }).then(function (data) {
            if (data.data.data.params != '') {
                $scope.siteDetailsLeft = data.data.data;
                $scope.dataParams = false;
            }
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };
    //详情分页
    $scope.memberAboutDetail = function (urlPages) {
        $http({method:'get',url:urlPages}).then(function (data) {
            if (data.data.data != "" || data.data.data != undefined ) {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = false;
                $scope.nowPage      = data.data.nowPage;
            } else {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = true;
            }
        },function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        })
    }
    //获取详情数据
    $scope.fieldDetailsData = function(){
        return{
            memberAboutDate : $scope.startDate,   // 会员预约日期
            aboutIntervalSection:$scope.aboutIntervalSection, // 区段预约日期
            yardId: $scope.initYardPageId//场地id
        }
    }


    //详情数据
    $scope.siteManagementDetails = function (key, start) {
        $('#siteManagementDetails').modal('show');
        $scope.aboutIntervalSection = key;
        $scope.aboutTimeStatus = start;
        $http({
            method: 'get',
            url: '/site-management/get-about-data-detail?'+$.param($scope.fieldDetailsData())
        }).then(function (data) {
            if (data.data.data != "" || data.data.data != undefined) {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = false;
                $scope.nowPage = data.data.nowPage;
            };
            if (data.data.data == "") {
                $scope.selectionTimeList = data.data.data;
                $scope.detailPages = data.data.pages;
                $scope.detailInfos = true;
            };
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };

    //预约场地
    $scope.siteReservation = function (key,num) {
        $scope.aboutIntervalSection = key;
        var data = {
            yardId: $scope.initYardPageId,// 电话
            memberId: $scope.aboutFieldId, //会员ID
            memberCardId: null,//会员卡id
            aboutIntervalSection: $scope.aboutIntervalSection,// 会员预约区间段
            aboutDate: $scope.startDate,// 会员预约日期
            aboutObject:"potentialMember"  // 预约对象(潜在会员)
        };
        $http({
            method: 'post',
            url: '/site-management/member-yard-about',
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (success) {
            if (success.data.status == "success") {
                Message.success(success.data.data);
                $scope.listsDates();
                // $scope.siteManagementDetails();
            }
            if (success.data.status == "error") {
                Message.warning(success.data.data);
                return;
            }
            // $scope.siteReservationList();
            $scope.selectSiteManagement($scope.initYardPageId, $scope.initYardPageNumber);
        }, function (error) {
            console.log(error);
            Message.error("系统错误请联系工作人员");
        });
    };

    //取消预约
    $scope.cancelReservation = function (id, num, key) {
        $scope.aboutIntervalSection = key;
        var d = $scope.startDate + ' ' + $scope.aboutIntervalSection.substr(0, 5)
        if (d < $scope.startDate) {
            Message.warning("当前时间已经过期，不能取消预约!");
            return;
        } else {
            if(num == 1){
                Sweety.remove({
                    url: "/site-management/deal-yard-about-class?yardId="+$scope.initYardPageId+"&memberAboutDate="+$scope.startDate+"&memberId ="+$scope.aboutFieldId +"&intervalSection="+key,
                    http: $http,
                    title: '确定要取消预约吗?',
                    text: '取消后所有信息无法恢复',
                    confirmButtonText: '确定',
                    data: {
                        action: 'unbind'
                    }
                }, function () {
                    $scope.selectSiteManagement($scope.initYardPageId, $scope.initYardPageNumber);
                    $scope.getMemberAboutClassRecord();
                }, function () {
                }, true);
            }
            if(num == 2){
                Sweety.remove({
                    url: "/site-management/cancel-yard-about-class?id="+id,
                    http: $http,
                    title: '确定要取消预约吗?',
                    text: '取消后所有信息无法恢复',
                    confirmButtonText: '确定',
                    data: {
                        action: 'unbind'
                    }
                }, function () {
                    $scope.siteManagementDetails($scope.aboutIntervalSection);
                    $scope.getMemberAboutClassRecord();
                }, function () {
                }, true);
            }

        }
    }
    $scope.getNowFormatDate = function () {
        var date = new Date();
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var hour = date.getHours();
        var minute = date.getMinutes();
        var second = date.getSeconds();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate
        return currentdate;
    }




/*** 潜在会员卡信息结束 ***/

    // 获取今日日期
    $scope.getToday = function (){
        var today = new Date();
        var y = today.getFullYear();
        var m = today.getMonth() + 1;//获取当前月份的日期
        var d = today.getDate();
        $scope.dateInput = y+"-"+m+"-"+d;
    };
    $scope.getToday();

    // 设置续费默认日期
    $scope.renewDate = $scope.dateInput;

    //潜在会员修改送人卡到期时间
    $scope.changeMemberCardTime = function (cardId) {
        $scope.giftCardId = cardId;
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
        $('#changeCardTimeModal').modal('show');
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
        $http({
            method  : 'post',
            url     : '/potential-members/update-gift-card',
            data    : $.param(request),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.status === 'success') {
                Message.success('修改成功！');
                $('#changeCardTimeModal').modal('hide');
                $scope.delayActiveCardChange = '';
                $scope.expiryTimeChange = '';
                $.loading.hide();
                $scope.sendCardTal();
            }else {
                Message.warning('操作失败，请刷新后重试！');
                $scope.delayActiveCardChange = $scope.getMyDate($scope.delayActiveCardChange11);
                $scope.expiryTimeChange = $scope.getMyDate($scope.expiryTimeChange);
                $.loading.hide();
                return false;
            }
        });
    };

/*** 续费模块 ***/
    // 续费按钮点击
    $scope.renewCard = function (id){
        $scope.renewId = id;
        $scope.getAllRenewCard();
    };

    //获取续费所有卡种
    $scope.getAllRenewCard = function () {
        $http.get("/member/get-renewal-card").success(function (data){
            $scope.renrewCardTypeList = data.type;
        });
    };

    // 设置默认金额选择
    $scope.renewPrice       = '';
    $(".cardTimeWords").hide();
    $scope.getRenewCardInfo = function (data){
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

    // 续费提交数据整理
    $scope.renewData = function (){
        $scope.memberCardId = $scope.renewId;
        return{
            memberCardId  : $scope.memberCardId    != undefined && $scope.memberCardId     != "" ? $scope.memberCardId    : null, //续费的老卡的卡id
            cardCategoryId: $scope.renewCardNumber != undefined && $scope.renewCardNumber  != "" ? $scope.renewCardNumber : null, //续费卡id
            renewDate     : $scope.renewStartDat   != undefined && $scope.renewStartDat    != "" ? $scope.renewStartDat   : null, //续费起始日期
            renewPrice    : $scope.renewPrice      != undefined && $scope.renewPrice       != "" ? $scope.renewPrice      : null, //续费价格
            endTime       : $scope.renewTermEnd    != undefined && $scope.renewTermEnd     != "" ? $scope.renewTermEnd    : null, //续费结束日期
            seller        : $scope.sellerSelect    != undefined && $scope.sellerSelect     != "" ? $scope.sellerSelect    : null, //销售
            cardNumber    : $scope.cardNumber      != undefined && $scope.cardNumber       != "" ? $scope.cardNumber      : null, //自定义的卡号
            usageMode    :  $scope.usageMode       != undefined && $scope.usageMode        != "" ? $scope.usageMode       : null, //使用类型
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 续费提交数据及验证
    $scope.renewCardPost = function (){
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
        if ($scope.sellerSelect == null || $scope.sellerSelect == "" || $('.select2-selection__rendered').html() == '请选择') {
            Message.warning("请选择销售");
            return false;
        }
        $scope.checkButton = true;
        // 发送数据
        $http({
            url: "/member/card-renew",
            method: 'POST',
            data: $.param($scope.renewData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (data) {
            if (data.data.status == "success") {
                Message.success("续费成功");
                // 数据初始化
                $scope.renewTermEnd    = "";
                $scope.renewPrice      = "";
                $scope.renewStartDat   = "";
                $scope.cardCheckNumber = "";
                $scope.renewCardType   = "";
                $scope.sellerSelect    = "";
                $scope.checkButton     = false;
                $('.select2-selection__rendered').html('请选择');
                $scope.sendCardTal();
                $scope.getPotentialUserCardInfo();
                $scope.getMemberSendCardRecord();
                $("#renewCardModal").modal("hide");
                $("#myModal").modal("hide");
                $(".cardTimeWords").hide();
            }
            else {
                $scope.checkButton = false;
                Message.warning(data.data);
            }
        })
    };
/*** 续费模块结束 ***/

/***送人卡升级模块***/
// 升级卡的点击方法
    $scope.updateCard = function (id,cardId,time) {
        $scope.updateCardButtonFlag = false;
        // 获取比旧卡费用更高的卡
        $scope.memberCardId = id;
        $http.get('/sell-card/all-card-category?oldCardCategory=' + id).success(function (data) {
            if($scope.cardList == []||$scope.cardList == null||$scope.cardList == ''){
                $scope.upcardList = data;
            }
            else{
                $scope.upcardList = data;
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
                    discount  :  $scope.discountId             != undefined && $scope.discountId            != "" ? $scope.discountId             : null, //折扣
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
    
    // 升级使用期限默认关闭
    $(".cardTimeUpsateWords").hide();
    // 升级
    $('#memberCardUpgradeModal').on('hide.bs.modal',function (){
        $scope.upsateCardType        = "";
        $scope.upcardNumber          = "";
        $scope.seller                = "";
        $scope.upsateTypePrice       = "";
        $scope.upsateCardMoney       = "";
        $('.newCardPricePay').text("");
        $scope.upsateCardRecondMoney = '';
        $(".cardTimeUpsateWords").hide();
        $(".discountSelect").val("");
    });

    // 续费模态框关闭时
    $('#renewCardModal').on('hide.bs.modal', function (){
         $scope.renewTermEnd  = "";
         $scope.renewPrice    = "";
         $scope.renewStartDat = "";
         $scope.cardNumber    = "";
         $scope.renewCardType = "";
         $scope.sellerSelect  = "";
       $('.select2-selection__rendered').html('请选择');
         $(".cardTimeWords").hide();
    });

/***送人卡升级模块结束***/

    // 绑定会员
    $scope.bindingUser = function (id,card){
        $("#bindingUserSelectModal").modal("show"); //搜索会员模态框显示
        $scope.sendGiftUserId = id;
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
            if(data.id == null || data.id == "" || data.id == undefined){
                Message.warning("会员不存在");
            }
            else{
                $scope.userInfoNews = data;
                $("#bindingUserDetailsModal").modal("show");  //会员详情模态框显示
            }
        })
    };

    // 绑定老会员的数据整理
    $scope.oldUserBindingData = function (){
        return{
            oldMemberId : $scope.potentialUserId != undefined && $scope.potentialUserId != "" ? $scope.potentialUserId : null, //被转卡会员id
            memberId: $scope.userInfoNews.id != undefined && $scope.userInfoNews.id != "" ? $scope.userInfoNews.id : null, //被转卡会员id
            cardId     : $scope.sendGiftUserId  != undefined && $scope.sendGiftUserId  != "" ? $scope.sendGiftUserId  : null, //卡的id
            scenario: "old", //会员类型new新old老
            status  : 2, //会员状态新1、老2
            _csrf_backend: $('#_csrf').val()
        }
    };

    // 绑定老会员的数据提交
    $scope.bindingOldUserSendCard = function (){
        $scope.checkButton = true;
        // 发送数据
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
                $scope.getUrlPotentialInfo();
            }
        })
    };
    // 模态框关闭事件
    $('#bindingUserModal').on('hide.bs.modal', function (){
        $scope.keywordsTel = "";
    });
    // 模态框显示事件
    $('#myModal').on('show.bs.modal', function (){
        $(".tabT1").addClass("activeBox");
        $(".tabT2").removeClass("activeBox");
        $(".tabT3").removeClass("activeBox");
        $(".myModalDiv1").show();
        $(".myModalDiv2").hide();
        $(".myModalDiv3").hide();
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
        // var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
        // if ($scope.newBindingIdCard == null || $scope.newBindingIdCard == "" || !(idCardP.test($scope.newBindingIdCard))) {
        //     Message.warning("请输入18位有效身份证号");
        //     return false;
        // }
        else {
            // 验重
            $http.get("/sell-card/set-data?mobile="+$scope.newBindingMobile+"&idCard="+$scope.newBindingIdCard+"&name="+$scope.newBindingName).then(function (id){
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
            oldMemberId : $scope.potentialUserId  != undefined && $scope.potentialUserId  != "" ? $scope.potentialUserId : null, //老会员ID
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
                    $scope.checkButton = false;
                    //Message.warning("提交错误！请重新填写或者联系管理员")
                    Message.warning(data.data);
                }
                else{
                    $scope.checkButton = false;
                    Message.success("添加成功!");
                    $("#bindingNewUserModal").modal("hide"); //模态框关闭
                    $("#bindingUserSelectModal").modal("hide"); //模态框关闭
                    $scope.getUrlPotentialInfo();
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

    /**** 结束 ****/

}).controller('potentialMemberCourseCtrl', function ($http, $scope, $rootScope, $timeout) {

    var memberids = localStorage.getItem('reservationCourse');
    //转化成json对象
    var $memberIdArr = angular.fromJson(memberids);
    $scope.memberId = $memberIdArr.id;
    $scope.memberType = $memberIdArr.MemberType;
    //触发esc 返回上一级
    $("body").keydown(function (event) {
        if ((event.keyCode || event.which) == 27) {
            location.href = '/potential-members/index?c=83';
        }
    });

    //获取登陆者信息
    $http.get('/personnel/employee-center').success(function (data){
        $scope.venueId = data.venue_id;
        $scope.getData();
    });

    //选择场馆
    $scope.PackVenue = function(id){
        $scope.venueId = id;
        $scope.getData();
    };

    // 获取场馆信息
    $http.get('/site/get-auth-venue').success(function (data){
        $scope.venueListInfo = data;
        $scope.getData();
    });

    //获取数据主页列表数据
    $scope.getData = function () {
        console.log($scope.venueId+'222');
        $scope.venueId = $scope.venueId == undefined ? '' : $scope.venueId;
        var url = '/check-card/group-class-data?memberId=' + $scope.memberId + '&venueId='+ $scope.venueId;
        $http({
            url: url,
            method: 'GET'
        }).then(function (data) {
            $scope.data = data.data;
        }, function (error) {
            Message.warning('系统错误请联系工作人员')
        })
    };
    $scope.dateCurrentw = myDate();
    //点击约课
    $scope.selectCourseSeat = function (id) {
        $scope.classId = id
        $http({
            method: 'get',
            url: '/check-card/group-class-member-rule-data?memberId=' + $scope.memberId + '&classId=' + $scope.classId + "&memberCardId=" + ""
        }).then(function (data) {
            $scope.isCanClass = data.data.isCanClass;
            $scope.isAboutClass = data.data.isAboutClass;
            $scope.isDance = data.data.isDance;
            if(data.data.potential == true){
                Message.warning('不好意思潜在会员只能预约一节课');
                return;
            }
            if ($scope.isCanClass == false) {
                Message.warning('您不能预约这节课！')
                $('#myModal').modal('hide');
                return
            }
            $scope.getAboutSeatRule();
        }, function (err) {
            console.log(err)
            Message.error("系统错误前请联系工作人员")
        });
    };
    $scope.totalRows = [];
    $scope.getAboutSeatRule = function () {
        // if ($scope.isCanClass == false){
        //     Message.warning('您还没有办卡！')
        //     $('#myModal').modal('hide');
        //     return
        // }
        if ($scope.isAboutClass == true) {
            Message.warning('已经预约过了')
            $('#myModal').modal('hide');
            return
        }
        if ($scope.isDance === true) {
            //渲染不是舞蹈的课程座位
            var url = '/check-card/get-seat-detail?id=' + $scope.classId + '&memberId=' + $scope.memberId
            $http({
                url: url,
                method: 'GET'
            }).then(function (data) {
                if (data.data.status == 'success') {
                    $scope.items = data.data.message;
                    // for(var a in $scope.items.total_rows){
                    //
                    // }
                    for(var i = 1;i<=$scope.items.total_rows;i++){
                        $scope.totalRows.push(i);
                    }
                    angular.element(document).ready(function () {
                        $('#myModal').modal('show');
                        var seatLength = $scope.items.length;
                        // for (var i = 0; i < seatLength; i++) {
                        //     var list = $scope.items[i];
                        //     if (list.is_anyone == 1) {
                        //         //对应的座位号从1开始
                        //         //索引是从0开始
                        //         $('.courseSeates').eq(i).addClass('notSelect');
                        //     }
                        // }
                    })
                }
            }, function (error) {
                Message.warning('系统错误请联系工作人员')
                $('#myModal').modal('hide');
            })
        }
    };
    /**
     * 选择座位
     */
    //选择座位
    var seatNum;
    $scope.seatSelect = function (id, num, type,i) {
        $scope.memberSeatType = type;
        if ($('.courseSeates').eq(i).hasClass('notSelect') || $('.selectedVip').eq(i).hasClass('notSelect')) {
            return;
        }
        else {
            seatNum = id;
            var number = parseInt(seatNum);
            $scope.seatID = number;
            var $vip = $('.vip');
            if ($scope.memberSeatType == 1) {
                $('.courseSeates').removeClass('selected');
                $('.courseSeates').eq(i).addClass('selected');
                if (!$vip.hasClass('selected')) {
                    $vip.addClass('selectedVip');
                    $vip.removeClass('vip');
                }
            }
            if ($scope.memberSeatType == 2) {
                if ($scope.memberSeatType != $scope.identify) {
                    Message.warning("不好意思请升级，您的身份")
                    return
                }
                $('.courseSeates').removeClass('selected');
                $('.courseSeates').eq(i).removeClass('selectedVip');
                $('.courseSeates').eq(i).addClass('selected');
                $('.courseSeates').eq(i).addClass('vip');
                if (!$vip.hasClass('selected')) {
                    $vip.addClass('selectedVip');
                    $vip.removeClass('vip');
                }
            }
        }
    }
    $scope.modalClose = function () {
        $("#myModal").modal("hide");
        $scope.totalRows.splice(0,$scope.totalRows.length);
    }
    //跳转预约成功
    $scope.appointment = function () {
        var url = '/check-card/set-about-class-record';
        var orderData = {
            classId: $scope.classId, //课程ID
            _csrf_backend: $('#_csrf').val(),
            seatId: $scope.seatID, //选择座位
            memberId: $scope.memberId,//会员ID
            classType: 'group', //课程类型
            aboutT: 'mobile'
        }
        if (orderData.seatId == '' || orderData.seatId == undefined) {
            Message.warning('请选择座位')
            return;
        }
        $http({
            method: 'POST',
            url: url,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(orderData)
        }).then(function (success) {
            if (orderData.seatId != '') {
                if (success.data.status == "repeat") {
                    Message.warning(success.data.message);
                    $("#myModalComplete").hide();
                    $(".modal-backdrop").hide();
                    return;
                }else if (success.data.status == "limitOne") {
                    Message.warning(success.data.message);
                    $("#myModalComplete").hide();
                    $(".modal-backdrop").hide();
                    return;
                }
                //开课前多少分钟不能预约
                else if(success.data.data.status == "endAboutLimit"){
                    Message.warning("开课"+ success.data.data.endClassLimit +"分钟前不能预约");
                    return
                }
                if (success.data.message == '预约成功') {
                    $scope.totalRows.splice(0,$scope.totalRows.length);
                    var classsids = success.data.data
                    $('#myModal').modal('hide');
                    $('#myModalComplete').modal('show');
                    var urls = '/check-card/get-about-class-detail?id=' + classsids
                    $http({
                        method: 'GET',
                        url: urls,
                    }).then(function (success) {
                        $scope.datase = success.data.data
                        //打印机需要的信息
                        $scope.printers = success.data.data;
                        $scope.getData();
                    })
                } else if (success.data.status == 'error') {
                    Message.warning(success.data.data)
                    $('#myModal').modal('hide');
                }
            }
        }, function (error) {
            console.log(error);
            Message.warning('系统错误请联系工作人员')
            $('#myModal').modal('hide');
        })
    }
    $scope.backPre = function () {
        history.go(-1);
    }
    //判断是否点击回车按钮
    angular.element(document).ready(function () {
        //点击回车立即预约
        $("#myModal").keydown(function (event) {
            if ((event.keyCode || event.which) == 13) {
                $scope.appointment();
            }
        });
        //点击回车关闭模态框
        $('#myModalComplete').keydown(function (event) {
            if ((event.keyCode || event.which) == 13) {
                $('#myModalComplete').modal('hide');
            }
        });
    })
        /****** 打印机start *****/
        //打印单条
        $scope.aboutPrints = function (item) {
            $scope.printers = item;
            $scope.printTicket();
        };
        $scope.printTicket = function () {
            if ($scope.printers == undefined || !$scope.printers) {
                Message.warning('没有预约的课程无法打印');
                return false;
            }
            $scope.aboutId = $scope.printers.id;
            $scope.getPrintHtml();
        };
        $scope.getPrintHtml = function () {
            var open = 1;
            if (open < 10) {
                var $prints = $('#coursePrints');
                var bodyHtml = $('body').html();
                $scope.bdhtml = $prints.html();//获取当前页的html代码
                window.document.body.innerHTML = $scope.bdhtml;
                $scope.updateAboutStatus();
                window.print();
                window.document.body.innerHTML = bodyHtml;
                location.replace(location.href);
            } else {
                window.print();
            }
        };
        $scope.updateAboutStatus = function () {
            $http.get('/check-card/update-about-print?id=' + $scope.aboutId).then(function (result) {

            });
        };
        /****** 打印机end ******/

}).controller('sellingContactCtrl',function ($scope,$http){
    $scope.getUrlNum = function (){
        var url = window.location.search;
        var loc = url.substring(url.lastIndexOf('=')+1, url.length);
        $http.get('/purchase-card/deal?cardId=' + loc).success(function (data){
            $scope.getNewContractData = data.intro;
            $scope.newContractName = data.name;
        });
    };
    $scope.getUrlNum();
})
// 控制input输入的指令
.directive('inputnums', function () {
    return {
        restrict: 'A',
        replace: true,
        link: function (scope, elem, attrs) {
            elem.keyup(function () {
                var $val = $(this).val();
                // $val = $val.replace(/^0/g, "");
                $val = $val.replace(/^[^1-9][^0-9]*$/g, '');
                $(this).val($val);
            });
        }
    };
});


function myDate() {
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

    return currentdate;
}
//新增显示
$(".Deposits").click(function () {
    $(".Deposit").show()
})