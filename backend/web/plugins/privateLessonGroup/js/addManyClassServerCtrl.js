/**
 * Created by Administrator on 2017/12/29 0029.
 * 私教管理 - 私教小团体课 - 增加私教小团体服务js
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

angular.module('App').controller('addManyClassServerCtrl',function($scope,$http,Upload){
    //初始化
    $scope.init = function () {
        $scope.getClassOptions();
        $scope.getVenueOptions();
        $scope.getDonationOptions();
        $scope.num = 0;
        $scope.validTimeType = 1;
        $scope.addCourseHtml();
        $scope.addLimitHtml();
        $scope.addSaleVenueHtml();
        $scope.addDonationHtml();
        $scope.getDeal();
    }

    //获取课程下拉框信息
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
    //获取售卖场馆下拉框信息
    $scope.getVenueOptions = function () {
        $http.get('/site/get-auth-venue').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data;
                $scope.venueStatus = true;
            }else{
                $scope.optionVenue = '暂无数据';
                $scope.venueStatus = false;
            }
        });
    };
    //获取赠品名称下拉框信息
    $scope.getDonationOptions = function () {
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
        });
    };
    //获取合同下拉框信息
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
    }
    //获取合同内容
    $scope.dealIdChange = function(id){
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
    }
    //添加课种选择模版
    $scope.addCourseHtml = function () {
        $scope.htmlAttr = 'courseSelect123';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.courseSelect123 = result.data.html;
        })
    };
    //添加多人阶梯价格区间
    $scope.addLimitHtml = function () {
        $scope.htmlAttr = 'limitHtml123';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.limitHtml123 = result.data.html;
        })
    }
    //添加售卖场馆模版
    $scope.addSaleVenueHtml = function () {
        $scope.htmlAttr = 'svenueHtml123';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.svenueHtml123 = result.data.html;
        })
    }
    //添加赠品模版
    $scope.addDonationHtml = function () {
        $scope.htmlAttr = 'giftHtml123';
        $scope.num = $scope.num + 1;
        $http.get('/private-lesson-group/add-template?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.giftHtml123 = result.data.html;
        })
    }
    //删除元素
    $scope.removeHtmlId = function (id, attr) {
        if (attr == 'svenueHtml123') {
            //删除售卖场馆模版
            $scope.svenueHtmlHttp = $scope.commonRemoveId(id,$scope.svenueHtmlHttp);
        }else if(attr == 'limitHtml123'){
            $scope.serverLimitHttp = $scope.commonRemoveId(id,$scope.serverLimitHttp);
        }else if(attr == 'courseSelect123') {
            $scope.courseSelectHttp = $scope.commonRemoveId(id,$scope.courseSelectHttp);
        }else if(attr == 'giftHtml123'){
            $scope.giftHtmlHttp = $scope.commonRemoveId(id,$scope.giftHtmlHttp);
        }

    }
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
    $scope.init();
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
    //定义课程选择数组
    $scope.course = function () {
        $scope.classId   = [];
        $scope.classTime = [];
        $scope.classNum  = [];
        $scope.onePrice  = [];
    }
    //定义多人阶梯价格区间
    $scope.people = function () {
        $scope.classPersonLeft    = [];
        $scope.classPersonRight   = [];
        $scope.unitPrice          = [];
        $scope.posPrice           = [];
        $scope.lowNum             = [];
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
    //获取表单值
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
    //获取课种选择表单信息
    $scope.getCourse = function () {
        var div = $('#courseSelectForm').children('.row');
        $scope.course();
        if ($scope.commonProof(div)){
            div.each(function (i) {
                var $classId = $(this).find('option:selected').val();
                var $classTime = $(this).find("input[name='classTime']").val();
                var $classNum = $(this).find("input[name='classNum']").val();
                var $onePrice = $(this).find("input[name='onePrice']").val();
                if ($classId) {
                    $scope.classId.push($classId);
                }
                if ($classTime) {
                    $scope.classTime.push($classTime);
                }
                if ($classNum) {
                    $scope.classNum.push($classNum);
                }
                if ($onePrice) {
                    $scope.onePrice.push($onePrice);
                }
            })
        } else {
            $scope.course();
        }

    }
    //多人阶梯价格课程总原价的计算
    $scope.countPrice = 0;
    $scope.countOnePrice = function () {
        var div = $('#courseSelectForm').children('.row');
        var $price = 0;
        div.each(function (i) {
            var $classNum = $(this).find("input[name='classNum']").val();
            var $onePrice = $(this).find('input[name="onePrice"]').val();
            if ($classNum != '' && $onePrice != '') {
                $price = $price + ($classNum * $onePrice);
            }
        })
        $scope.countPrice = $price;
    }
    //获取阶梯人数区间表单信息
    $scope.getLimit = function () {
        var form = $('#limitForm').children('.row');
        $scope.people();
        if ($scope.commonProof(form)) {
            form.each(function (i) {
                var $classPersonLeft = $(this).find('input[name="classPersonLeft"]').val();
                var $classPersonRight = $(this).find('input[name="classPersonRight"]').val();
                var $unitPrice = $(this).find('input[name="unitPrice"]').val();
                var $posPrice = $(this).find('input[name="posPrice"]').val();
                var $lowNum = $(this).find('input[name="lowNum"]').val();
                if ($classPersonLeft) {
                    $scope.classPersonLeft.push($classPersonLeft);
                }
                if ($classPersonRight) {
                    $scope.classPersonRight.push($classPersonRight);
                }
                if ($unitPrice) {
                    $scope.unitPrice.push($unitPrice);
                }
                if ($posPrice) {
                    $scope.posPrice.push($posPrice);
                }
                if ($lowNum) {
                    $scope.lowNum.push($lowNum);
                }
            });
        } else {
            $scope.people();
        }
    }
    //获取场馆表单信息
    $scope.getVenue = function () {
        var div = $('#privateVenue').children('.row');
        $scope.saleVenue();
        if ($scope.commonProof(div)) {
            div.each(function (i) {
                var $venueId = $(this).find('option:selected').val();
                var $venueSaleNum = $(this).find('input[name="venueSaleNum"]').val();
                if ($venueId) {
                    $scope.venueId.push($venueId);
                }
                if ($venueSaleNum) {
                    $scope.venueSaleNum.push($venueSaleNum);
                }
            })
        } else {
            $scope.saleVenue();
        }
    }
    //获取赠品表单信息
    $scope.getGift = function () {
        var div = $('#giftForm').children('.row');
        $scope.gift();
        if ($scope.commonProof(div)) {
            div.each(function (i) {
                var $giftId = $(this).find('option:selected').val();
                var $giftNum = $(this).find('input[name="giftNum"]').val();
                if ($giftId) {
                    $scope.giftId.push($giftId);
                }
                if ($giftNum) {
                    $scope.giftNum.push($giftNum);
                }
            })
        } else {
            $scope.gift();
        }
    }

    /***************************获取所有数据****************************************/
    $scope.getDatas = function () {
        $scope.getTotalNum();
        $scope.getCourse();
        $scope.getLimit();
        $scope.getVenue();
        $scope.getGift();
        return {
            name                : $scope.name,
            validTime           : $scope.validTime,
            validTimeType       : $scope.validTimeType,
            courseType          : $scope.courseType,
            sellNum             : $scope.sellNum,
            saleStart           : $scope.saleStart,
            saleEnd             : $scope.saleEnd,
            classId             : $scope.classId,
            classTime           : $scope.classTime,
            classNum            : $scope.classNum,
            onePrice            : $scope.onePrice,
            classPersonLeft     : $scope.classPersonLeft,
            classPersonRight    : $scope.classPersonRight,
            unitPrice           : $scope.unitPrice,
            posPrice            : $scope.posPrice,
            lowNum              : $scope.lowNum,
            venueId             : $scope.venueId,
            venueSaleNum        : $scope.venueSaleNum,
            giftId              : $scope.giftId,
            giftNum             : $scope.giftNum,
            transferNum         : $scope.transferNum,
            transferPrice       : $scope.transferPrice,
            desc                : $scope.desc,
            pic                 : $scope.pic,
            dealId              : $scope.dealId,
            _csrf_backend       : $('#_csrf').val(),                //验证
        }
    }
    //对数据进行验证
    //定义公共验证方法
    $scope.commonRule = function (attr,text) {
        if(!attr){Message.warning(text);return false;}return true;
    };
    //验证售卖场馆
    $scope.venueRule = function (arr1,arr2) {
        if (arr1.length == 0 || arr2.length == 0 || arr1.length != arr2.length) {
            Message.warning('请选择售卖场馆和售卖数量!');
            return false;
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
    //验证课种选择
    $scope.courseRule = function (classId,classTime,classNum,onePrice,text) {
        if (classId.length == 0 || classTime.length == 0 || classNum.length == 0 || onePrice.length == 0) {
            Message.warning(text);return false;
        }
        if (classId.length != classTime.length || classId.length != classNum.length || classId.length != onePrice.length) {
            Message.warning(text);return false;
        }
        return true;
    }
    //验证多人阶梯价格
    $scope.peopleRule = function (classPersonLeft,classPersonRight,unitPrice,posPrice,lowNum) {
        if (classPersonLeft.length == 0 || classPersonRight.length == 0 || unitPrice.length == 0 || posPrice.length == 0 || lowNum.length == 0) {
            Message.warning('请将多人阶梯价格填写完整!');return false;
        }
        if (classPersonLeft.length != classPersonRight.length || classPersonLeft.length != classPersonRight.length|| classPersonLeft.length != unitPrice.length || classPersonLeft.length != posPrice.length || classPersonLeft.length != lowNum.length) {
            Message.warning('请将多人阶梯价格填写完整!');return false;
        }
        return true;
    }
    //验证区间开始结束
    $scope.limitArrRule = function (arr1,arr2,text) {
        for (var i=0; i<arr1.length; i++) {
            if (parseFloat(arr1[i]) >= parseFloat(arr2[i])) {
                Message.warning(text);
                return false;
            }
        }
        return true;
    }
    //验证规则提示
    $scope.rules = function () {
        if (!$scope.commonRule($scope.name,'请填写产品名称!')) { return false; }
        if (!$scope.commonRule($scope.validTime,'请填写产品有效期!')) { return false; }
        if (!$scope.commonRule($scope.courseType,'请选择课程类型!')) { return false; }
        if (!$scope.commonRule($scope.sellNum,'请输入售卖总数量!')) { return false; }
        if (!$scope.commonRule($scope.saleStart,'请选择售卖起始日期!')) { return false; }
        if (!$scope.commonRule($scope.saleEnd,'请选择售卖结束日期!')) { return false; }
        if (!$scope.courseRule($scope.classId,$scope.classTime,$scope.classNum,$scope.onePrice,'请将课种选择填写完整!')) { return false; }
        if (!$scope.peopleRule($scope.classPersonLeft,$scope.classPersonRight,$scope.unitPrice,$scope.posPrice,$scope.lowNum)) { return false; }
        if (!$scope.limitArrRule($scope.classPersonLeft,$scope.classPersonRight,'人数区间开始区间大于结束区间!')) { return false; }
        if (!$scope.venueRule($scope.venueId,$scope.venueSaleNum)) { return false; }
        if (!$scope.descRule($scope.desc,'请填写课程介绍!'))  { return false; }
        if (!$scope.descRule($scope.pic,'请添加照片!'))  { return false; }
        return true;
    }


    //添加私教小团体服务 - 与后台交互(post传递)
    $scope.setHttp = function () {
        $scope.params = $scope.getDatas();
        //console.log('$scope.params',$scope.params);
        if ($scope.rules()) {
            $http({
                url : '/private-lesson-group/add-group-server',
                method : 'POST',
                data : $.param($scope.params),
                headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(result){
                //console.log('result.data.status',result.data);
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
