/**
 * Created by Administrator on 2017/12/29 0029.
 * Content:私教管理 - 私教小团体课 - 增加私教小团体课程js
 */
$(function () {
    //定义开始时间
    function startDate() {
        $('.datetimeStart').datetimepicker({
            format      : 'yyyy-mm-dd',
            minView     : 'month',
            language    : 'zh-CN',
            autoclose   : true,
            startDate   : '2008-08-08',
        });
    }
    //调用开始时间
    startDate();
    $('.datetimeStart').on('click',function () {
        $('.datetimeStart').datetimepicker('setEndDate',$('.datetimeEnd').val());
    });

    //定义结束时间
    function endDate() {
        $('.datetimeEnd').datetimepicker({
            format          : 'yyyy-mm-dd',
            minView         : 'month',
            language        : 'zh-CN',
            autoclose       : true,
            startDate       : new Date(),
        });
    }
    //调用结束时间
    endDate();
    $('.datetimeEnd').on('click',function () {
        $('.datetimeEnd').datetimepicker('setStartDate',$('.datetimeStart').val());
    })

    var $input = $('.inputSellNum');
    //（数量不限）点击单选框，输入框添加限制
    $('#unLimit').on('ifChecked', $input, function () {
        $(this).find("input[name='sellNum']").attr('disabled', 'disabled');
        $(this).find("input[name='sellNum']").val('');
    });
    $('#venueSaleNum').on('ifChecked', $input, function () {
        $(this).find("input[name='venueSaleNum']").attr('disabled', 'disabled');
        $(this).find("input[name='venueSaleNum']").val('');
    });
    //（数量不限）点击单选框，输入框解除限制
    $('#unLimit').on('ifUnchecked', $input, function () {
        $(this).find("input[name='sellNum']").removeAttr('disabled');
    });

    $('#venueSaleNum').on('ifUnchecked', $input, function () {
        $(this).find("input[name='venueSaleNum']").removeAttr('disabled');
    })

});
angular.module('App').controller('addManyClassLessonCtrl',function($scope,$http,Upload){
    //初始化数据
    $scope.init = function () {
        $scope.num = 0;
        $scope.venueHttp          = [];
        $scope.getClassOptions();                   //获取课种下拉框信息
        $scope.getVenueOptions();                   //获取售卖场馆下拉框信息
        $scope.getShopLists();                      //获取赠品下拉框信息
        $scope.getDeal();                           //获取合同下拉框信息
        $scope.addPersonLimitHtml();
        $scope.addNumLimitHtml();
        $scope.addSaleVenueHtml();
        $scope.addDonationHtml();
    }


    //添加人数区间模版
    $scope.addPersonLimitHtml = function () {
        $scope.htmlAttr = 'personHtml123';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.personLimitHtml123 = result.data.html;
        })
    }
    //添加节数区间模版
    $scope.addNumLimitHtml = function () {
        $scope.htmlAttr = 'numHtml';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.NumLimitHtml = result.data.html;
        })
    }
    //添加售卖场馆模版
    $scope.addSaleVenueHtml = function () {
        $scope.htmlAttr = 'venueHtml';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.groupSaleVenueHtml = result.data.html;
        })
    }
    //添加赠送商品模版
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'donationHtml';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.groupGroupDonationHtml = result.data.html;
        })
    }
    //删除元素
    $scope.removeHtmlId = function (id, attr) {
        if (attr == 'venueHtml') {
            //删除售卖场馆模版
            $scope.venueHttp = $scope.commonRemoveId(id,$scope.venueHttp);
        }else if(attr == 'personHtml123'){
            $scope.personLimitHttp = $scope.commonRemoveId(id,$scope.venueHttp);
        }else if(attr == 'numHtml') {
            $scope.groupNumHttp = $scope.commonRemoveId(id,$scope.groupNumHttp);
        }else if(attr == 'donationHtml'){
            $scope.groupDonationHttp = $scope.commonRemoveId(id,$scope.groupDonationHttp);
        }

    }

    //获取课种信息
    $scope.getClassOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-private-data').then(function (result) {
            if(result.data.venue != undefined && result.data.venue != ""){
                $scope.optionClass = result.data.venue;
                $scope.classStatus = true;
            }else{
                $scope.optionClass = '暂无数据';
                $scope.classStatus = false;
            }
        })
    }

    //获取售卖场馆信息
    $scope.getVenueOptions = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue = '暂无数据';
                $scope.venueStatus = false;
            }
        })
    }

    //获取赠品信息
    $scope.getShopLists = function () {
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            if(result.data.goods != undefined && result.data.goods != ""){
                $scope.optionDonation = result.data.goods;
                $scope.donationStatus = true;
                // $scope.donationShow   = true;
            }else{
                $scope.optionDonation = '暂无数据';
                $scope.donationStatus = false;
                // $scope.donationShow = false;
            }
        })
    }

    //获取合同信息
    $scope.getDeal = function () {
        $http.get('/contract/get-deal?type=2').then(function (result) {
            if (result.data != undefined && result.data != '') {
                $scope.contactList = result.data;
                $scope.contactStatus = true;
            }else{
                $scope.contactList = '暂无数据';
                $scope.contactStatus = false;
            }
        })
    };
    //获取合同内容
    $scope.contactAddChange = function(id){
        //获取合同详情
        $http.get('/contract/get-deal-one?id='+id).then(function (result) {
            //console.log(result.data)
            if(result.data.data != null){
                $scope.contentTeach = result.data.data.intro;
            }else{
                Message.warning('请选择合同');
                $scope.contentTeach = '';
            }
        })
    };
    $scope.init();

    //图片上传的方法
    $scope.setPic = function (file) {
        Upload.upload({
            url         : '/class/upload',
            method      : 'POST',
            data        : {UploadForm:{imageFile:file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                $scope.pic = result.data.imgLink;
            }else{
                Message.warning(result.data.data);
            }
        })
    }
    //上传图片大小的判断
    $scope.setCover = function (file) {
        if(file) {
            if(file.size >=2000000){
                Message.warning('图片太大');
            }else{
                $scope.setPic(file);
            }
        }
    }

    //下拉选择框改变事件
    //课程改变触发事件
    $scope.selectClass = function (id) {
        $scope.classHttp.push(id);
        $scope.getClassId();
        if($scope.classId != undefined && $scope.classId.length){
            $scope.classHttp = $.array.arrayIntersection($scope.classHttp,$scope.classId);
        }
    }
    //场馆改变触发事件
    $scope.selectVenue = function (id) {
        $scope.classHttp.push(id);
        $scope.getVenueId();
        if($scope.venueId != undefined && $scope.venueId.length){
            $scope.venueHttp = $.array.arrayIntersection($scope.venueHttp,$scope.venueId);
        }
    }
    //赠品改变触发事件
    $scope.selectGift = function (id) {
        $scope.classHttp.push(id);
        $scope.getGiftId();
        if($scope.giftId != undefined && $scope.giftId.length){
            $scope.donationHttp = $.array.arrayIntersection($scope.donationHttp,$scope.giftId);
        }
    }

    //定义数组
    //定义人数/节数区间数组
    $scope.price = function () {
        $scope.intervalStart        = [];       //课程节数开始区间
        $scope.intervalEnd          = [];       //课程节数节数区间
        $scope.classPrice = [];
        $scope.classPersonLeft      = [];       //课程人数左区间
        $scope.classPersonRight     = [];       //课程人数右区间
        $scope.unitPrice             = [];       //优惠价格
        $scope.posPrice              = [];       //pos价格
        $scope.lowNum                = [];       //最低开课人数
    }
    //定义场馆数组
    $scope.saleVenue = function () {
        $scope.venueId          = [];
        $scope.venueSaleNum     = [];
    }
    //定义赠品数组
    $scope.gift = function () {
        $scope.giftId  = [];
        $scope.giftNum = [];
    }

    //公共属性判断
    $scope.commonProof = function (data) {
        if (data == undefined || data == null) {
            return false;
        } else {
            return true;
        }
    };
    //获取销售总数量
    $scope.getTotalNum = function () {
        var div = $('div#unLimit');
        var $checked = div.find('div.icheckbox_square-green').hasClass('checked');
        var $totalNum = div.find("input[name='sellNum']").val();
        if ($checked || $totalNum) {
            if ($checked) {
                $scope.sellNum = -1;
            } else {
                $scope.sellNum = $totalNum;
            }
        }
    }

    //获取多人阶梯价格
    $scope.getPrice = function () {
        var $div = $('#numLimit').children('.row');
        $scope.price();
        $div.each(function (i) {
            $scope.limitDatas = [];
            var $intervalStart = $(this).find("input[name='intervalStart']").val();
            var $intervalEnd = $(this).find("input[name='intervalEnd']").val();
            var $limitPrice = $(this).children('.personLimit').children('.clearfix');
            if ($intervalStart) {
                $scope.intervalStart.push($intervalStart);
            }
            if ($intervalEnd) {
                $scope.intervalEnd.push($intervalEnd);
            }
            $limitPrice.each(function (i) {
                var $classPersonLeft  = $(this).find("input[name='classPersonLeft']").val();
                var $classPersonRight = $(this).find("input[name='classPersonRight']").val();
                var $unitPrice        = $(this).find("input[name='unitPrice']").val();
                var $posPrice         = $(this).find("input[name='posPrice']").val();
                var $lowNum           = $(this).find("input[name='lowNum']").val();
                if ($classPersonLeft) {
                    var $data = {
                        classPersonLeft     : $classPersonLeft,
                        classPersonRight    : $classPersonRight,
                        unitPrice            : $unitPrice,
                        posPrice             : $posPrice,
                        lowNum               : $lowNum
                    }
                    $scope.limitDatas.push($data);
                }
            });
               $scope.classPrice.push($scope.limitDatas);
        })
    }
    //获取售卖场馆
    $scope.getVenueId = function () {
        var div = $('#privateVenue').children('.row');
        $scope.saleVenue();
        if ($scope.commonProof(div)) {
            div.each(function (i) {
                var $venueId = $(this).find('option:selected').val();
                var $venueSaleNum = $(this).find('input[name="venueSaleNum"]').val();
                if ($venueId) {
                    $scope.venueId.push($venueId);
                    if ($venueSaleNum) {
                        $scope.venueSaleNum.push($venueSaleNum);
                    }
                }
            })
        } else {
            $scope.saleVenue();
        }
    }

    //获取赠品
    $scope.getGiftId = function () {
        var div = $('#productGift').children('.row');
        $scope.gift();
        if ($scope.commonProof(div)) {
            div.each(function (i) {
                var $giftId = $(this).find("option:selected").val();
                var $giftNum = $(this).find("input[name='giftNum']").val();
                if ($giftId) {
                    $scope.giftId.push($giftId);
                    if ($giftNum) {
                        $scope.giftNum.push($giftNum);
                    }
                }
            })
        } else {
            $scope.getGiftId();
        }
    }

    //获取表单数据
    $scope.getFormData = function () {
        $scope.getTotalNum();
        $scope.getPrice();
        $scope.getVenueId();
        $scope.getGiftId();
        return {
            name                                    : $scope.name,             //产品名称
            monthUpNum                              : $scope.monthUpNum,      //每月上课数量
            courseType                              : $scope.courseType,       //课程类型
            sellNum                                 : $scope.sellNum,          //售卖总数量
            saleStart                               : $scope.saleStart,        //售卖起始日期
            saleEnd                                 : $scope.saleEnd,           //售卖节数日期
            classId                                 : $scope.classId,          //课种id
            classTime                               : $scope.classTime,        //课程时长
            onePrice                                : $scope.onePrice,         //单节原价
            intervalStart                           : $scope.intervalStart,     //课程节数左区间(数组)
            intervalEnd                             : $scope.intervalEnd,       //课程节数右区间(数组)
            classPrice                              : $scope.classPrice,            //课程价格区间
            venueId                                 : $scope.venueId,               //售卖场馆id(数组)
            venueSaleNum                            : $scope.venueSaleNum,          //单馆售卖数量(数组)
            giftId                                  : $scope.giftId,                  //赠品id(数组)
            giftNum                                 : $scope.giftNum,                //赠品数量(数组)
            transferNum                             : $scope.transferNum,            //转让次数
            transferPrice                           : $scope.transferPrice,           //转让金额
            desc                                    : $scope.desc,                     //课程介绍
            pic                                     : $scope.pic,                       //照片
            dealId                                  : $scope.contactAdd,               //合同ID
            _csrf_backend                           : $('#_csrf').val(),                //验证
        }
    }

    //对数据进行验证
    //定义公共验证方法
    $scope.commonRule = function (attr,text) {
        if(!attr){Message.warning(text);return false;} return true;
    };
    //定义公共数组验证
    $scope.arrayRule = function (attr,num,text) {
        if (attr.length ==0 || num.length ==0){
            Message.warning(text);return false;
        }
        if (attr.length != num.length) {
            Message.warning(text);return false;
        }
        return true;
    }
    //课程判断
    $scope.classRule = function (classId,classTime,classNum,text) {
        if (classId.length==0 || classTime.length==0 || classNum.length==0) {
            Message.warning(text);return false;
        }
        return true;
    }
    //课程描述介绍
    $scope.descRule = function (desc,text) {
        if (desc === undefined) {
            Message.warning(text);return false;
        }
        return true;
    }
    //验证售卖场馆
    $scope.venueRule = function (arr1,arr2) {
        if (arr1.length == 0 || arr2.length == 0 || arr1.length != arr2.length) {
            Message.warning('请选择售卖场馆和售卖数量!');
            return false;
        }
        return true;
    }
    //验证人数数组
    $scope.peopleRule = function (arr) {
        for (var i=0; i<arr.length; i++){
            if (arr[i].length == 0) {
                Message.warning('请填写人数区间!');
                return false;
            }
            for (var j=0; j<arr[i].length; j++) {
                if (arr[i][j].classPersonLeft==''){
                    Message.warning('人数区间填写不完整!');
                    return false;
                }
                if (arr[i][j].classPersonRight==''){
                    Message.warning('人数区间填写不完整!');
                    return false;
                }
                if (parseFloat(arr[i][j].classPersonLeft) >= parseFloat(arr[i][j].classPersonRight)) {
                    Message.warning('人数开始区间大于结束区间!');
                    return false;
                }
                if (arr[i][j].unitPrice == '') {
                    Message.warning('请填写优惠价格!');
                    return false;
                }
                if (arr[i][j].posPrice == '') {
                    Message.warning('请填写pos单价!');
                    return false;
                }
                if (arr[i][j].lowNum == '') {
                    Message.warning('最低开课人数未填写!');
                    return false;
                }

            }
        }
        return true;
    }
    //验证课程节数
    $scope.numberRule = function (arr1,arr2) {
        if (arr1.length == 0 || arr2.length == 0) {
            Message.warning('请填写课程节数区间!');
            return false;
        }
        for (var i=0;i<arr1.length;i++) {
            if (parseFloat(arr1[i]) >= parseFloat(arr2[i])) {
                console.log('arr1[i]',arr1[i]);
                console.log('arr2[i]',arr2[i]);
                Message.warning('课程节数开始区间不能大于节数区间!');return false;
            }
        }
        return true;
    }
    //验证规则提示
    $scope.rules = function () {
        if (!$scope.commonRule($scope.name,'请填写产品名称!')) { return false; }
        if (!$scope.commonRule($scope.monthUpNum,'请输入每月上课数量!')) { return false; }
        if (!$scope.commonRule($scope.courseType,'请选择课程类型!')) { return false; }
        if (!$scope.commonRule($scope.sellNum,'请输入售卖总数量!')) { return false; }
        if (!$scope.commonRule($scope.saleStart,'请选择售卖起始日期!')) { return false; }
        if (!$scope.commonRule($scope.saleEnd,'请选择售卖结束日期!')) { return false; }
        if (!$scope.commonRule($scope.classId,'请选择课种!')) { return false; }
        if (!$scope.commonRule($scope.classTime,'请输入课程时长!')) { return false; }
        if (!$scope.commonRule($scope.onePrice,'请输入单节原价!')) { return false; }
        if (!$scope.numberRule($scope.intervalStart,$scope.intervalEnd)) { return false; }
        if (!$scope.peopleRule($scope.classPrice)) { return false; }
        if (!$scope.venueRule($scope.venueId,$scope.venueSaleNum)) { return false; }
        if (!$scope.descRule($scope.desc,'请填写课程介绍!'))  { return false; }
        if (!$scope.descRule($scope.pic,'请添加照片!'))  { return false; }
        return true;
    }

    //添加私教小团体课程 - 与后台交互(post传递)
    $scope.setHttp = function () {
        $scope.params = $scope.getFormData();
        console.log('$scope.params',$scope.params);
        if ($scope.rules()){
            $http({
                url : '/private-lesson-group/add-group-lesson',
                method : 'POST',
                data : $.param($scope.params),
                headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function (result) {
                if (result.data.status == 'success') {
                    Message.success('添加成功');
                    window.history.go(-1);
                } else {
                    Message.warning('添加失败');
                }
            })
        }

    }
});






















