$(function () {
    $(document).on('mouseover','#tbody tr',function () {
         $(this).find('.displayBlock').css('opacity','1');
    });
    $(document).on('mouseleave','#tbody tr',function () {
        $(this).find('.displayBlock').css('opacity','0');
    });
    $("#selectedVenueSet").select2({
        width: "100%",
        height: "30px",
        placeholder: "请选择场馆"
    });
    $("#selectedVenueSetRe123").select2({
        width: "100%",
        height: "30px",
        placeholder: "请选择场馆"
    });
    //调用inputCheck插件
    function check(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    }
    check();

    $("#datetimeStart123").datetimepicker({
        format: 'yyyy-mm-dd',
        minView:'month',
        language: 'zh-CN',
        // autoclose:true,
        startDate:'2008-08-08'
    }).on("click",function(){
        $("#datetimeStart").datetimepicker("setEndDate",$("#datetimeEnd").val());
    });


    //调用结束日期
    $("#datetimeEnd123").datetimepicker({
        format: 'yyyy-mm-dd',
        minView:'month',
        language: 'zh-CN',
        // autoclose:true,
        startDate:new Date()
    }).on("click",function(){
        $("#datetimeEnd").datetimepicker("setStartDate",$("#datetimeStart").val());
    });
    $('#memberCardCompanyId').select2();
    $('#memberCardVenueId').select2();
    $('#memberCardType').select2();
    $('#memberCardStatus').select2();
    $('#type123').select2();
    //新增合同
    $('#deal123').select2();
    $('#setRole123').select2({
        width:'100%',
        // theme:'classic'
    });
    $('.selectRole123').select({
            width:'100%',
    });


    $('#reservationDate').daterangepicker(null, function(start, end, label) {
    });
    function starDate(){
        $(".datetimeStart").datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:'2008-08-08'
        });
    }
    starDate();
    $(".datetimeStart").on("click",function(){
        $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").val());
    });

//新增课程中调用结束日期
    function endDate(){
        $(".datetimeEnd") .datetimepicker({
            format: 'yyyy-mm-dd',
            minView:'month',
            language: 'zh-CN',
            autoclose:true,
            startDate:new Date()
        })
    }
    endDate();
    $(".datetimeEnd").on("click",function(){
        $(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").val());
    });

    //时间插件启动
    $('.clockpicker').clockpicker()
        .find('input').on('change',function() {
    });

    //第一步点击td团中每月固定日
    $('table td').on('click',function(){
        var $week = $('.weekSelect');
        if(!$(this).parents('table').hasClass('noneClick')){
            var check = $(this).toggleClass('bColor');
            $week.find('input').iCheck('disable');
            $week.find('input').css('left','23px').css('z-index','1000');
        }
        var $table = $('#table');
        var $dayCheck = $table.find('.bColor');
        var $sum = $dayCheck.length - 1;
        if($sum == -1){
            $week.find("input[name='weeksTime']").removeAttr('disabled').css('left', '0').css('z-index', '0');
            $week.find('div.icheckbox_square-green').removeAttr('disabled');
        }
    });

    //选择特定星期，固定日不可选
    var $input = $('.weekSelect');
    var $table = $('#table');
    $('.weekSelect').on('ifChecked',function () {
        $('.clockpicker').find("input[name='dayStart']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayStart']").attr('');
        $('.clockpicker').find("input[name='dayEnd']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayEnd']").attr('');
        $table.find('td').css('cursor','not-allowed');
        $table.addClass('noneClick');
    });
    $('.weekSelect').on('ifUnchecked',function () {
        var  $weekChecked = $input.find("div.checked");
        var  $num         = $weekChecked.length - 1;
        if($num == undefined || $num == ""){
            $('.clockpicker').find("input[name='dayStart']").removeAttr('disabled');
            $('.clockpicker').find("input[name='dayEnd']").removeAttr('disabled');
            $table.find('td').css('cursor','pointer');
            $table.removeClass('noneClick');
        }
    });

    //第一步当week被选中时显示
    //记录点击的是星期几
    var ind ;
    var btnIndex;
    //获取被点击的索引
    $('.addTime').on('click',function(){
        btnIndex =  $(this).index('.addTime');
    });
    //被选中时触发的时间
    $('.week').on('ifChecked',function(){
        ind = $(this).index('.week');
        //按钮隐藏
        $('.addTime').addClass('disNone');
        $('.addTime').eq(ind).removeClass('disNone');
    });
    //取消选中时触发的事件
    $('.week').on('ifUnchecked',function(){
        //按钮隐藏
        var i = $(this).index('.week')
        $('.addTime').addClass('disNone');
        $('.weekTime').eq(i).text('');
    });

    //点击取消
    $('.cancelTime').on('click',function(){
        $('#addTime').addClass('disNone')
    });
    //点击确定
    $('.successBtn').on('click',function(){
        if($('#weekStart').val()!='' &&$('#weekEnd').val()!=''){
            $('.weekTime').eq(ind).text($('#weekStart').val()+'--'+$('#weekEnd').val());
            $('.addTime').eq(ind).addClass('disNone');
        }else{
            // alert('时间不能为空，请重新输入');
        }
    });
});
var app = angular.module('App');
app.controller('indexController',function($scope,$http,$timeout,Upload){
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
    
    $.loading.show();
    $scope.init = function () {
        $scope.pathApi ='/member-card/get-card-data?';
        $scope.initPath();
        $scope.getCardData('init');
        $scope.elementTr();
        // $scope.getCardVenueOptions();             //获取场馆
        $scope.getCardTypeOptions();                 //获取卡种类别
        setTimeout("$.loading.hide()",1000);         //隐藏页面
    };

    //添加搜索页数
    $scope.skipPage = function(value){
        if(value != undefined){
            $scope.pathApi = '/member-card/get-card-data?page='+value;
            $scope.getCardData();
        }
    };

    //获取公司信息
    $scope.getCompanyOptions = function () {
        $http.get('/site/get-auth-company').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionCompany = result.data;
            }
        });
    };
    // 初始化公司数据
    $scope.getCompanyOptions();

    //获取场馆信息
    $scope.getCardVenueOptions       = function (companyId) {
        $scope.optionVenue = [];
        if(companyId == ''){
            $scope.venue_id  = '';
        }else{
            $http.get('/rechargeable-card-ctrl/get-venue-data?companyId=' + companyId).then(function (result) {
                if(result.data.venue != undefined && result.data.venue != ""){
                    $scope.optionVenue = result.data.venue;
                }
            });
        }
    };
    //获取卡种类型信息
    $scope.getCardTypeOptions       = function () {
        $http.get('/member-card/get-type').then(function (result) {
            if(result.data.type != undefined && result.data.type != ""){
                $scope.optionType = result.data.type;
            }
        });
    };
    $scope.changeSort = function (attr,sort) {
        $scope.sortType = attr;
        $scope.switchSort(sort);
        $scope.searchCard();
    };
    $scope.switchSort = function (sort) {
        if(!sort){
            sort = 'DES';
        }else if(sort == 'DES'){
            sort = 'ASC';
        }else{
            sort = 'DES'
        }
        $scope.sort = sort;
    };
    $scope.getCardData = function (text) {
        $.loading.show();
        $http.get($scope.pathApi).then(function (result) {
            $scope.items = result.data.data;
            if($scope.items == undefined || $scope.items == ''){
                if(text == 'init'){
                    $scope.dataInfo = true;
                    $scope.searchData = false;
                }else{
                    $scope.dataInfo = false;
                    $scope.searchData = true;
                }
            }else{
                $scope.dataInfo = false;
                $scope.searchData = false;
            }
            $scope.pages = result.data.pages;
            $scope.searchCarding = false;
            $.loading.hide();
        });
    };
    //添加最大金额区间前面数字不能大于后面
    //当最大金额小于最小金额时
    $scope.maxPriceChange = function(num){
        var minNum = $scope.minPrice;
        if(minNum != null && minNum != '' && minNum != undefined){
            if(num <=minNum){
                $scope.maxPrice = minNum;
            }
        }
    }

    //当输入金额大约最大金额时
    $scope.minPriceChange = function(num){
        var maxNum = $scope.maxPrice;
        if(maxNum != null && maxNum != '' && maxNum != undefined){
            if(num >=maxNum){
                $scope.minPrice = maxNum;
            }
        }
    }


    /**处理搜索数据***/
    $scope.searchCardData = function () {
        var data = $('#reservationDate').val();
        if(data == ''){
            $scope.startTime = '';
            $scope.endTime   = '';
        }else{
            var startTime = data.substr(0, 10);
            var endTime = data.substr(-10, 10);
            $scope.startTime = startTime +' ' + '00:00:01';
            $scope.endTime = endTime +' ' + '23:59:59';
        }

        return {
            cardName      : $scope.cardName != undefined ? $scope.cardName : null,
            startTime     : $scope.startTime != undefined && $scope.startTime != '' ? $scope.startTime : null,
            endTime       : $scope.endTime != undefined && $scope.endTime != ''  ? $scope.endTime : null,
            venueId      :  $scope.venue_id != undefined && $scope.venue_id !='' ? $scope.venue_id : null,
            companyId     : $scope.company_id != undefined && $scope.company_id != '' ? $scope.company_id : null,
            cardType      : $scope.cardType != undefined ? $scope.cardType : null,
            type          : $scope.type123 != undefined ? $scope.type123 : null,
            minPrice      : $scope.minPrice != undefined ? $scope.minPrice : null,
            maxPrice      : $scope.maxPrice != undefined ? $scope.maxPrice : null,
            sortType      : $scope.sortType != undefined ? $scope.sortType : null,
            sortName      : $scope.sort     != undefined ? $scope.sort :null,
            status        : $scope.status   != undefined ? $scope.status : null,
            isCheck       : $scope.isCheck  != undefined && $scope.isCheck  != false ? $scope.isCheck : null,
            isBindDeal    : $scope.deal123  != undefined && $scope.deal123  != ''    ? $scope.deal123 : null,
        }
    };
    $scope.initPath = function () {
        $scope.searchParams =  $scope.searchCardData();
        $scope.pathApi =  '/member-card/get-card-data?' + $.param($scope.searchParams);
    };

    /**搜索方法***/
    $scope.searchCard = function () {
        // $scope.searchCarding = true;
        $scope.initPath();
        $scope.getCardData();
    };
    /*处理标签**/
    $scope.elementTr = function ($event) {

    };
    /**获取场馆**/
    $scope.setVenue = function (venue_id) {
         $scope.venue_id = venue_id;
    };
    $scope.setCardType = function () {
        var li    = angular.element(document.getElementsByClassName('checked'));
        var input = li.find('input:checked');
        if(input.length != undefined && input.length != 0){
            $scope.cardType = [];
            input.each(function (i) {
                $scope.cardType.push(parseInt(input[i].getAttribute('value')));
            })
        }else{
            $scope.cardType = [];
        }
    };
    /*初始化函数*/
    $scope.init();
    //添加下拉框的函数    
    angular.element(document).ready(function () {
        // $(".js-example-basic-single1").select2();
        // $(".js-example-basic-single2").select2();
        // $(".js-example-basic-single3").select2();
    });

    $scope.dateInit = function(){
        function starDate(){
            $(".datetimeStart").datetimepicker({
                format: 'yyyy-mm-dd',
                minView:'month',
                language: 'zh-CN',
                autoclose:true,
                startDate:'2008-08-08'
            });
        }
        starDate();
        $(".datetimeStart").on("click",function(){
            $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").val());
        });

//新增课程中调用结束日期
        function endDate(){
            $(".datetimeEnd") .datetimepicker({
                format: 'yyyy-mm-dd',
                minView:'month',
                language: 'zh-CN',
                autoclose:true,
                startDate:new Date()
            })
        }
        endDate();
        $(".datetimeEnd").on("click",function(){
            $(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").val());
        });
    }

    $scope.dateInit();

    //添加模板时调用icheck
    $scope.getICheck = function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    }

    $scope.setStyleCheckbox = function() {
        //（数量不限）点击单选框，输入框添加限制
        $('.inputUnlimited').on('ifChecked',function(){
            $(this).children('input').attr('disabled','disabled');
            $(this).children('input').val('');
        });
//（数量不限）点击单选框，输入框解除限制
        $('.inputUnlimited').on('ifUnchecked',function(){
            $(this).children('input').removeAttr('disabled');
        });
    }

    // 卡种修改
    $scope.updateCard = function(id){
        var div = $('#NormalSaleNum');
        var $divInput = div.children('input[name="discountNum"]');
        $divInput.val('');
        div.find('.i-checks').find('.icheckbox_square-green').iCheck('uncheck');
        $('.addNewDiscount').children('div').remove();
        $scope.num         = 0;
        $scope.editCardId123        = id;
        $scope.sellEndDate123        = '';
        $scope.sellStartDate123      = '';
        $scope.minPrice123            = '';
        $scope.maxPrice123            = '';
        $scope.originalPrice123       = '';
        $scope.sellPrice123           = '';
        $scope.sellVenueId123        = '';
        $scope.getOldDiscount($scope.sellVenueId123);
        $scope.oldDiscountArr         = [];
        $scope.mewDiscountArr         = [];
        $scope.addNewDiscountHtml();
        $("#myModals12").modal("show");
        $http.get("/member-card/get-card-detail?id="+id).then(function(response){
            $scope.sellVenueLists     = response.data.sellVenue;
            $scope.minPriceInit       = response.data.min_price;
            $scope.minPrice123        = response.data.min_price;
            $('.minPrice123').val(response.data.min_price);
            $scope.maxPriceInit       = response.data.max_price;
            $scope.maxPrice123        = response.data.max_price;
            $('.maxPrice123').val(response.data.max_price);
            $scope.originalPriceInit  = response.data.original_price;
            $scope.originalPrice123   = response.data.original_price;
            $('.originalPrice123').val(response.data.original_price);
            $scope.sellPriceInit      = response.data.sell_price;
            $scope.sellPrice123       = response.data.sell_price;
            $('.sellPrice123').val(response.data.sell_price);
        });
    }
    //获取老的折扣
    $scope.getOldDiscount = function(id){
        $http.get('/member-card/get-card-discount?id='+ id).then(function(response){
            $scope.oldDiscountLists = response.data.data;
            var length = $scope.oldDiscountLists.length;
            $timeout(function(){
                $scope.getICheck();
                $scope.setStyleCheckbox();
                $scope.getOldDiscountDom();
            },10)
        })
    }

    //默认显示老的折扣，不限时勾选
    $scope.getOldDiscountDom = function(){
        var oldLists =  $('.addNewDiscount').children('div');
        oldLists.each(function(index,item){
            var oldInput =  $(this).find('.inputUnlimited').children("input[name='addNewDiscountNum']");
            var oldInputData = oldInput.data("num");
            var oldCheck = $(this).find('.inputUnlimited').find('.i-checks').find('.icheckbox_square-green');
            if(oldInputData == -1){
                oldInput.attr('disabled','disabled');
                oldInput.val('');
                oldCheck.iCheck('check');
                $scope.setStyleCheckbox();
            }else{
                oldInput.val(oldInputData);
            }
        })
    }

    //完成时获取老的折扣数组
    $scope.getOldDiscountArr = function(){
        $scope.oldDiscountArr = [];
        $scope.oldDiscountFlag = true;
        var oldLists =  $('.oldDiscount').children('div');
        oldLists.each(function(index,item){
            var $oldInput =  $(this).find('.inputUnlimited').children("input[name='addOldDiscountNum']");
            var $oldNum = $oldInput.val();
            var $oldDiscount = $(this).find('input[name="oldDiscount"]').val();
            var $oldCheck = $(this).find('.inputUnlimited').find('.i-checks').find('.icheckbox_square-green');
            var $discountChecked = $oldCheck.hasClass('checked');//是否不限
            if($discountChecked && $oldDiscount != ''){
                var data ={
                    surplus :-1,
                    discount :$oldDiscount
                }
                $scope.oldDiscountArr.push(data);
            }
            if($oldNum != '' && $oldDiscount != ''){
                var data ={
                    surplus :$oldNum,
                    discount :$oldDiscount
                }
                $scope.oldDiscountArr.push(data);
            }
            if($oldDiscount == '' ){
                $scope.oldDiscountFlag = false;
            }
            if($oldDiscount != '' && $oldNum == '' && $discountChecked == false){
                $scope.oldDiscountFlag = false;
            }
        })
    }
    
    //完成时获取新的折扣
    $scope.getNewDiscountArr = function(){
        $scope.mewDiscountArr         = [];
        $scope.NewDiscountArr123      = [];//折扣数组
        $scope.newDiscountNumArr123   = [];//折扣张数数组
        var $newDiv = $('.addNewDiscount').children('div');
        $newDiv.each(function(index,item){
            var $newDiscount = $(this).find('input[name="addNewDiscount"]').val();
            var $newDiscountNumInput = $(this).find('input[name="addNewDiscountNum"]');
            var $newDiscountNum = $newDiscountNumInput.val();
            var $newDiscountChecked = $(this).find('.inputUnlimited').find('.i-checks').find('.icheckbox_square-green').hasClass('checked');
            if($newDiscountChecked && $newDiscount != ''){
                var data ={
                    surplus :-1,
                    discount :$newDiscount
                }
                $scope.mewDiscountArr.push(data);
                $scope.NewDiscountArr123.push($newDiscount)
                $scope.newDiscountNumArr123.push(-1)
            }
            if($newDiscountNum != '' && $newDiscount != ''){
                var data ={
                    surplus :$newDiscountNum,
                    discount :$newDiscount
                }
                $scope.mewDiscountArr.push(data);
                $scope.NewDiscountArr123.push($newDiscount)
                $scope.newDiscountNumArr123.push($newDiscountNum)
            }
        })
    }

    //完成时获取正常售卖张数
    $scope.getNormalSellNums = function(){
        $scope.NormalSellNumsFlag = '';
        var $div = $('#NormalSaleNum');
        var $divInput = $div.children('input[name="discountNum"]').val();
        var $inputChecked = $div.find('.i-checks').find('.icheckbox_square-green').hasClass('checked');
        if($inputChecked ){
            $scope.NormalSellNumsFlag = -1;
        }
        if($divInput != ''){
            $scope.NormalSellNumsFlag = $divInput;
        }
        if($divInput == '' && $inputChecked == false ){
            $scope.NormalSellNumsFlag = '';
        }
    }



    //选择售卖场馆
    $scope.selectedSellVenueId = function(id){
        $scope.getOldDiscount(id);
        if(id != ''){
            $scope.getICheck();
            $scope.setStyleCheckbox();
            var venueId = $('#sellVenueId123').find("option:selected").attr('data-valueId');
            var venueSellCard = angular.fromJson(venueId)
            $scope.sellectedSellVenueId = venueSellCard.venue_id;
            $scope.sellVenueSurplus  = venueSellCard.surplus;
            var div = $('#NormalSaleNum');
            var $divInput = div.children('input[name="discountNum"]');
            if(venueSellCard.surplus == -1){
                $divInput.children('input[name="discountNum"]').attr('disabled','disabled');
                $divInput.val('');
                div.find('.i-checks').find('.icheckbox_square-green').iCheck('check');
                $scope.setStyleCheckbox();
            }else{
                $divInput.val(venueSellCard.surplus);
            }
            $scope.sellEndDate123 = $scope.getMyDate(venueSellCard.sell_end_time*1000) ;
            $scope.sellStartDate123 = $scope.getMyDate(venueSellCard.sell_start_time*1000);
        }else{
            var div = $('#NormalSaleNum');
            var $divInput = div.children('input[name="discountNum"]');
            $divInput.val('');
            div.find('.i-checks').find('.icheckbox_square-green').iCheck('uncheck');
            $scope.sellEndDate123 = '';
            $scope.sellStartDate123 = '';

        }
    }

    //添加折扣模板
    $scope.addNewDiscountHtml  = function () {
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.htmlAttr = 'addNewDiscount';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.newDiscountHtml = result.data.html;
        });
    };

    //添加新增赠送
    $scope.addRoleGiveHtml  = function () {
        $scope.submitIsFlag = true;
        $scope.getICheck();
        $scope.selectCheckbox();
        $.loading.show();
        $scope.htmlAttr = 'setRole';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.setRoleHtml = result.data.html;
            $('.selectRole123').select2({
                width:'100%',
            });
            $('.selectMemberCard123').select2({
                width:'100%',
            });
            $.loading.hide();
            $scope.getICheck();
            $scope.selectCheckbox();
        });
    };
    $scope.isRepeat = function(arr){
            var hash = {};
            for(var i in arr) {
                if(hash[arr[i]])
                    return true;
                hash[arr[i]] = true;
            }
            return false;
    }

    $scope.editSubmit = function(){
        $scope.getNormalSellNums();
        $scope.getNewDiscountArr();
        $scope.isRepeat($scope.NewDiscountArr123);
        var editDate = function(){
            return{
                discount       :$scope.NewDiscountArr123,//折扣数组
                discountNum    :$scope.newDiscountNumArr123,//售卖张数数组
                limitId        :$scope.sellVenueId123,
                surplus         :$scope.NormalSellNumsFlag,//正常售卖张数
                venueId        :$scope.sellectedSellVenueId != undefined && $scope.sellectedSellVenueId != ''? $scope.sellectedSellVenueId:null,//售卖场馆id
                cardCategoryId :$scope.editCardId123 != undefined && $scope.editCardId123 != ''? $scope.editCardId123:null,//卡种id
                originalPrice   :$('.originalPrice123').val() != undefined && $('.originalPrice123').val() != ''? $('.originalPrice123').val():null,//一口价原价
                sellPrice        :$('.sellPrice123').val() != undefined && $('.sellPrice123').val() != ''? $('.sellPrice123').val():null,//一口价售价
                minPrice         :$('.minPrice123').val() != undefined && $('.minPrice123').val() != ''? $('.minPrice123').val():null, //区间价：最低价
                maxPrice          :$('.maxPrice123').val() != undefined && $('.maxPrice123').val() != ''? $('.maxPrice123').val():null, //区间价：最高价
                sellStartTime     :$scope.sellStartDate123 != undefined && $scope.sellStartDate123 != ''? $scope.sellStartDate123:null, //售卖开始时间
                sellEndTime        :$scope.sellEndDate123 != undefined && $scope.sellEndDate123 != ''? $scope.sellEndDate123:null,//售卖结束时间
                _csrf_backend      :$('#_csrf').val()
            }
        }
        if($scope.isRepeat($scope.NewDiscountArr123)){
            Message.warning('折扣不能重复,请重新输入');
            return;
        }
        if($scope.NormalSellNumsFlag == ''){
            Message.warning('正常售卖张数不能为空');
            return;
        }
        if($scope.originalPriceInit != null && $scope.originalPriceInit != '' && $scope.originalPriceInit != undefined){
            if($('.originalPrice123').val() =='' ||$('.originalPrice123').val() ==null ||$('.originalPrice123').val() ==undefined || $('.sellPrice123').val() =='' ||$('.sellPrice123').val() ==null ||$('.sellPrice123').val() ==undefined ){
                Message.warning('一口价不能为空!');
                return;
            }
        }
        if($scope.minPriceInit != null && $scope.minPriceInit != '' && $scope.minPriceInit != undefined){
            if($('.minPrice123').val() =='' ||$('.minPrice123').val() ==null ||$('.minPrice123').val() ==undefined || $('.minPrice123').val() =='' ||$('.minPrice123').val() ==null ||$('.minPrice123').val() ==undefined ){
                Message.warning('区间价不能为空!');
                return;
            }
        }
        if($scope.sellectedSellVenueId == undefined || $scope.sellectedSellVenueId == ''|| $scope.sellectedSellVenueId == undefined){
            Message.warning('请选择售卖场馆!');
            return;
        }
        if($scope.sellStartDate123 == undefined || $scope.sellStartDate123 == ''|| $scope.sellStartDate123 == null || $scope.sellEndDate123 == undefined || $scope.sellEndDate123 == ''|| $scope.sellEndDate123 == null ){
            Message.warning('请选择售卖时间!');
            return;
        }


        $http({
            method: 'POST',
            url: '/member-card/edit-card',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(editDate())
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.searchCard();
                $scope.sellVenueId123 = '';
                Message.success('修改成功');
                $('#myModals12').modal('hide');
            }else{
                angular.forEach(response.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        });
    }

    /****卡种分页***/
    $scope.replacementPages = function (urlPages) {
        $scope.pathApi = urlPages;
        $scope.getCardData();
    };
    /***卡种删除**/
    $scope.deleteCard = function (id) {
        Sweety.remove({
            url              : "/member-card/delete-card?id="+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '卡种删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data             : {
                action: 'unbind'
            }
        }, function () {
            $scope.getCardData('init');
        });
    };
    /**修改状态**/
    $scope.editStatus = function (id,$index,text) {
       $http.get("/member-card/edit-card-status?id="+id+"&text="+text).then(function (result) {
           if(result.data.status == 'success'){
               Message.success(result.data.data);
               $scope.items[$index].status = result.data.edit;
           }else{
               Message.warning(result.data.data[0])
           }
       });
    };
    /***清空搜索*****/
    $scope.searchClear = function () {
        $scope.searchClearData();
        $scope.init();
    };
    /*****clearSearchData********/
    $scope.searchClearData = function () {
            $scope.cardName   = null;
            // $scope.startTime  = '';
            // $scope.endTime    = '';
            // $scope.venue_id   = null;
            // $scope.cardType   = null;
            $scope.minPrice   = null;
            $scope.maxPrice   = null;
            $scope.sortType   = null;
            $scope.sort       = null;
            // $scope.status     = null;
            $scope.isCheck    = null;
            $scope.company_id = '';
            $scope.status     = '';
            $scope.venue_id   = '';
            $scope.cardType = '';
            $scope.type123 = '';
            $scope.deal123 = '';
            $('#reservationDate').val('');
            $('#memberCardCompanyId').select2("val", "");
            $('#select2-memberCardCompanyId-container').text('全部');
            $('#memberCardVenueId').select2("val", "");
            $('#select2-memberCardVenueId-container').text('全部');
            $('#memberCardType').select2("val", "");
            $('#select2-memberCardType-container').text('不限');
            $('#memberCardStatus').select2("val", "");
            $('#select2-memberCardStatus-container').text('不限');
            $('#type123').select2('');
            $('#deal123').select2('');
            $('#select2-deal123-container').text('合同绑定');
            $('#select2-type123-container').text('请选择类型');
    };
    /******Enter键搜索*******/
    $scope.enterSearch = function(e) {
        var keyCode = window.event ? e.keyCode : e.which;
        if(keyCode == 13){ $scope.searchCard(); }
    };
    /**
     * 云运动 - 卡种 - 获取卡种详情
     * @author HouKaiXin<HouKaiXin@itsports.club>
     * @create 2017/5/11
     * @return string
     */
    $scope.getCardDetail = function(id){
        $.loading.show();
        $scope.dealId = '';
        $scope.dataDeal = '';
        $scope.theData = '';
        $http.get("/member-card/get-card-detail?id="+id).then(function (result) {
            $scope.theData = result.data;
            // console.log($scope.theData);
            $scope.leaveLongIimit = angular.fromJson($scope.theData.leave_long_limit);
            $scope.studentLeaveLimit = angular.fromJson($scope.theData.student_leave_limit);
            if ($scope.theData.student_leave_limit != null && $scope.theData.student_leave_limit != '' && $scope.theData.student_leave_limit != undefined){
                $scope.summer     = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[0] != undefined ? $scope.studentLeaveLimit[0] : null ;
                $scope.winter     = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[1] != undefined ? $scope.studentLeaveLimit[1] : null ;
                $scope.summerEdit = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[0] != undefined ? $scope.studentLeaveLimit[0] : null ;
                $scope.winterEdit = $scope.studentLeaveLimit != null &&  $scope.studentLeaveLimit[1] != undefined ? $scope.studentLeaveLimit[1] : null ;
            }
            $scope.categoryTypeId = result.data.category_type_id;
            $scope.validLists = angular.fromJson(result.data.validity_renewal);
            if($scope.theData.cardTime != null){
                $scope.theData.cardTime.day  = angular.fromJson($scope.theData.cardTime.day);
                $scope.theData.cardTime.week = angular.fromJson($scope.theData.cardTime.week);
                $scope.weekStartTimeListsArr = [];
                if($scope.theData.cardTime.week.startTime != null && $scope.theData.cardTime.week.startTime != '' &&$scope.theData.cardTime.week.startTime != undefined){
                    var len = $scope.theData.cardTime.week.startTime.length;
                    for(var i=0;i<len;i++){
                        $scope.weekStartTimeListsArr.push(i);
                    }
                }
            }else{
                $scope.weekStartTimeListsArr = [];
            }
            $.loading.hide();
        });
        $scope.getGiveCourseData();//获取私课课程
        $scope.getCardTheVenue();//获取所属场馆
        $scope.groupCourse();//获取所有团课
        $scope.getDonationOptions();//获取所有赠品
    }
    // 获取卡信息
    $scope.cardInfoIt = function (id) {
        $scope.allCardIdArr = [];
        $http({
            url: "/sell-card/card-category?venueId=" +id,
            method: 'GET'
        }).then(function (response) {
            $scope.cardItems = response.data;
            $scope.allCardLength = response.data.length;
            var $item = response.data;
            $item.forEach(function(item,index){
                $scope.allCardIdArr.push(item.id);
            });
        })
    };

    //获取角色
    $scope.getSetRole = function(companyId){
        $http.get('/role/get-role?companyId='+companyId).then(function(response){
            $scope.setRoleLists123 = response.data.data;
        });
    }

    //获取场馆
    $scope.getAllVenueSet = function(){
        $http.get('/site/get-auth-venue').then(function(response){
            $scope.VenueItems = response.data;
        });
    }
    //点击续费
    $scope.selectRenewClick = function(){
        $scope.noOrderDay = '';
        $scope.rechargeCardSet123 = '';
        $scope.EarlyRenewals = '';
        $scope.renewMoneySuccessFlag = false;
    }

    //点击赠送
    $scope.selectDiveClick = function(){
        $scope.selectGiveType = '';
        $('input[name="giveDays"]').val('');
        $('input[name="giveNum"]').val('');
        $scope.setRoleCompleteFlag = false;
    }
    //获取默认的设置
    $scope.getDefaultSet123 = function(id){
        $http.get('/member-card/get-config-card-data?type=card'+'&venueId='+id).then(function(response){
            if(id != null){
                $scope.DefaultSetLists = response.data.data;
            }else{
                $scope.DefaultSetLists = [];
            }
            var data = $scope.DefaultSetLists;
            if(id != null &&id !='' && data.length != 0){
                for(var index in data){
                    if(data[index].key == 'renew'){
                        $('#siteInput').val(data[index].value);
                    }
                    if(data[index].key == 'recharge'){
                        $('#rechargeCardSet123').val(data[index].value);
                    }
                    if(data[index].key == "beforeRenew"){
                        $('#EarlyRenewals').val(data[index].value);
                    }
                }
            }else{
                $('#siteInput').val('');
                $('#rechargeCardSet123').val('');
                $('#EarlyRenewals').val('');
            }
        });
    }

    //设置模态框
    $scope.setClick123 = function(){
        $scope.submitIsFlag = true;
        $scope.defaultGiveSetLists =[];
        $scope.getAllVenueSet();
        $('.setRoleBox').children('.removeDiv').remove();
        $scope.getDefaultSet123('');
        $scope.getSetRole('');
        $scope.addRoleGiveHtml();
        $scope.venueSet = '';
        $scope.selectGiveType = '';
        $scope.noOrderDay = '';
        $scope.rechargeCardSet123 = '';
        $scope.EarlyRenewals = '';
        $scope.renewMoneySuccessFlag = false;
        $scope.setRoleCompleteFlag = false;
        $scope.selectCheckbox();
        $('.setRoleBox').find('.selectMemberCard123').removeAttr('disabled');
        $('.setRoleBox').find('.cardBox').find('select2').removeClass('select2-container--disabled');
        $('.setRoleBox').find('.cardBox').find('div.icheckbox_square-green').iCheck('uncheck');//移除 checked 状态
        $('.setRoleBox').find('.cardBox').find('input[name="giveDays"]').val('');
        $('.setRoleBox').find('.cardBox').find('input[name="giveNum"]').val('');
        $('#siteModal').modal('show');
    }

    //根据不同的场馆获取不同的卡
    $scope.selectedVenueChange = function(id){
        var pId = $('#selectedVenueSet').find('option:selected').data('company');
        $scope.getSetRole(pId);
        $scope.defaultGiveSetLists =[];
        $scope.submitIsFlag = true;
        $scope.cardInfoIt(id);
        $scope.getDefaultSetGive();
    };

    //获取默认之前设置的赠送
    $scope.getDefaultSetGive = function(){
        if($scope.selectGiveType != '' && $scope.selectGiveType != undefined && $scope.selectGiveType != ''&& $scope.venueSet != '' && $scope.venueSet != undefined ){
            $http.get('/member-card/get-gift-data?type='+ $scope.selectGiveType+'&venueId='+ $scope.venueSet).then(function(response){
                $scope.defaultGiveSetLists = response.data;
                if(response.data.length > 0){
                    $scope.submitIsFlag = false;
                }else{
                    $scope.submitIsFlag = true;
                }
                $.loading.show();
                $timeout(function(){
                    $('.selectRole123').select2({
                        width:'100%',
                    });
                    $('.selectMemberCard123').select2({
                        width:'100%',
                    });
                    $.loading.hide();
                    $scope.getICheck();
                    $scope.selectCheckbox();
                    $scope.getDefaultSetData()
                },300);
            });
        }
    }
    //默认渲染之前设置的赠送
    $scope.getDefaultSetData = function(){
        var oldLists =  $('.setRoleBox').children('.setRoleContent');
        oldLists.each(function(index,item){
            $scope.cardLength = 0;
            var $selectRole = $(this).find('select.selectRole123');
            var $giveDay = $(this).find('input[name="giveDays"]');
            var $giveNum = $(this).find('input[name="giveNum"]');
            var $memberCardLists = $(this).find('.selectMemberCard123');
            $selectRole.select2();
            $memberCardLists.select2();
            if($scope.defaultGiveSetLists.length !=0){
                $selectRole.attr('disabled','disabled');
                $giveDay.attr('disabled','disabled');
                $giveNum.attr('disabled','disabled');
                $memberCardLists.attr('disabled','disabled');
                if($scope.defaultGiveSetLists[index].role_id != 'null'&&$scope.defaultGiveSetLists[index].role_id != null && $scope.defaultGiveSetLists[index].role_id != undefined){
                    var $roleId = angular.fromJson($scope.defaultGiveSetLists[index].role_id);
                    $selectRole.val($roleId).trigger("change");
                }
                if($scope.defaultGiveSetLists[index].category_id != 'null'&&$scope.defaultGiveSetLists[index].category_id != null && $scope.defaultGiveSetLists[index].category_id != undefined){
                    var $cardData = angular.fromJson($scope.defaultGiveSetLists[index].category_id);
                    var $cardIdLength = $cardData.length;
                    // if($cardIdLength != $scope.allCardLength){

                        $memberCardLists.val($cardData).trigger("change");
                    // }
                    // if($cardIdLength == $scope.allCardLength){
                    //     $scope.cardLength = -1;
                    // }
                }
                var oldInput =  $(this).find('.cardBox').children("select.selectMemberCard123");
                var oldInputData = oldInput.data("num");
                var oldCheck = $(this).find('.cardBox').find('.i-checks').find('.icheckbox_square-green');
                oldCheck.iCheck('disable');
                oldCheck.css({'backgroundColor':'#e5e5e5',"cursor":'not-allowed'});
                oldInput.attr('disabled','disabled');
                // if(oldInputData == -1){
                //     oldInput.attr('disabled','disabled');
                //     oldInput.val('');
                //     oldCheck.iCheck('check');
                //     $scope.selectCheckbox();
                // }
                // else{
                //     oldInput.val(oldInputData);
                // }
            }
        });
    }

    //根据赠送类型
    $scope.selectGiveTypeChange = function(num){
        $scope.defaultGiveSetLists =[];
        $scope.submitIsFlag = true;
        $scope.getDefaultSetGive();
        if(num == '1'){
            $scope.addRoleGiveHtml();
            // $timeout(function(){
            //     $scope.getICheck();
            //     $scope.selectCheckbox();
            // },5)
        }
    }

    //删除单个赠送角色
    $scope.deleteRoleAndCard = function(id){
        Sweety.remove({
            url: "/member-card/del-gift-data?giftId=" + id,
            http: $http,
            title: '确定要删除吗?',
            text: '角色卡种赠送删除后无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getDefaultSetGive();
        });
    }

    //获取赠送角色数组
    $scope.getGiveRoleArr = function(){
        $scope.roleBoxLength = 0;
        $scope.roleIdArr  = [];
        $scope.giveDayArr = [];
        $scope.giveNumArr = [];
        $scope.memberCardArr = [];
        var $roleBox = $('.setRoleBox').children('.setRoleContent');
        $scope.roleBoxLength = $roleBox.length;
        $roleBox.each(function(index,item){
            var $checked    = $(this).find(".cardBox").find('div.icheckbox_square-green').hasClass('checked');
            var $roleId = $(this).find('.selectRole123').val();
            var $giveDay = $(this).find('input[name="giveDays"]').val();
            var $giveNum = $(this).find('input[name="giveNum"]').val();
            var $memberCardLists = $(this).find('.selectMemberCard123').val();


            if($roleId !=''&&$roleId != undefined && $roleId != null){
                $scope.roleIdArr.push($roleId);
                if($giveDay!=''&&$giveDay != undefined && $giveDay != null ){
                    $scope.giveDayArr.push($giveDay);
                    if($giveNum !=''&&$giveNum != undefined && $giveNum != null ){
                        $scope.giveNumArr.push($giveNum);
                    }else{
                        $scope.giveNumArr.push(-1);
                    }
                }
            }
            if($scope.selectGiveType == 1){
                if($checked || $memberCardLists){
                    if($memberCardLists){
                        $scope.memberCardArr.push($memberCardLists);
                    }else{
                        $scope.memberCardArr.push($scope.allCardIdArr);
                    }
                }
            }else if($scope.selectGiveType == 2){
                $scope.memberCardArr = [];
            }

            // if($memberCardLists !=''&&$memberCardLists != undefined && $memberCardLists != null){
            //     $scope.memberCardArr.push($memberCardLists);
            // }
        });
    }

    //赠送设置完成
    $scope.setRoleComplete = function(){
        var $roleBox = $('.setRoleBox').children('.setRoleContent');
        $scope.getGiveRoleArr();
        var giveData = {
            type :$scope.selectGiveType != undefined && $scope.selectGiveType != ''?$scope.selectGiveType : null,
            giftDay: $scope.giveDayArr,
            roleId :$scope.roleIdArr,
            giftTotal:$scope.giveNumArr,
            categoryId  :$scope.memberCardArr,
            venueId :$scope.venueSet,
            _csrf_backend      :$('#_csrf').val()
        };

        if($scope.venueSet == '' || $scope.venueSet == undefined || $scope.venueSet == null){
            Message.warning('请选择场馆');
            return;
        }
        if($scope.selectGiveType == undefined || $scope.selectGiveType == '' || $scope.selectGiveType == null ){
            Message.warning('请选择赠送类型');
            return;
        }
        if($scope.giveDayArr.length == 0){
            Message.warning('赠送天数或角色不能为空');
            return;
        }
        if($scope.roleBoxLength == 0 || $scope.roleIdArr.length  == 0){
            Message.warning('请添加新增赠送');
            return;
        }
        if($scope.selectGiveType == 1){
            if($scope.roleBoxLength !=$scope.roleIdArr.length || $scope.roleIdArr.length != $scope.giveDayArr.length ||$scope.roleIdArr.length != $scope.memberCardArr.length){
                Message.warning('角色、会员卡或赠送天数不能为空');
                return;
            }
        }

        if($scope.selectGiveType == 2){
            if($scope.roleBoxLength !=$scope.roleIdArr.length || $scope.roleIdArr.length != $scope.giveDayArr.length ){
                Message.warning('角色、赠送天数不能为空');
                return;
            }
        }

        $scope.setRoleCompleteFlag = true;

        $http({
            method: 'POST',
            url: '/member-card/add-gift-day',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(giveData)
        }).then(function(response){
            if(response.data.status == "success"){
                Message.success('设置成功');
                $scope.selectGiveType ='';
                $('input[name="giveDays"]').val('');
                $('input[name="giveNum"]').val('');
                $("#siteModal").modal("hide");
            }else{
                $scope.setRoleCompleteFlag = false;
                Message.warning(response.data.data);
                return;
            }
        });
    }

    // 续费时间数据
    $scope.renewTimeData = function (){
        return {
            type     : "card",
            venueId  : $scope.venueId,
            renew    : $('#siteInput').val(),
            recharge  : $('#rechargeCardSet123').val()   != undefined && $('#rechargeCardSet123').val()   != '' ? $('#rechargeCardSet123').val() : null,
            scenario : "card",
            beforeRenew :$('#EarlyRenewals').val(),
            _csrf_backend:$('#_csrf').val()
        }
    };

    // 续费时间设置
    $scope.renewMoneySuccess = function (){
        if ($scope.venueId == null || $scope.venueId == ""){
            Message.warning("请选择场馆");
            return false;
        }
        if ($('#siteInput').val() == null || $('#siteInput').val() == ""){
            Message.warning("请填写续费设置天数");
            return false;
        }
        if($('#EarlyRenewals').val()   == undefined || $('#EarlyRenewals').val()   == '' ){
            Message.warning("请填写提前续费天数");
            return false;
        }
        // if ($scope.rechargeCardSet123 == null || $scope.rechargeCardSet123 == ""){
        //     Message.warning("请填写充值卡消费金额");
        //     return false;
        // }
        $scope.renewMoneySuccessFlag = true;
        $http({
            method: 'POST',
            url: '/league/league-reservation',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param($scope.renewTimeData())
        }).then(function (data){
            if(data.data.status == "success"){
                Message.success(data.data.data);
                $("#siteModal").modal("hide");
            }else {
                Message.warning(data.data.data);
                $scope.renewMoneySuccessFlag = false;
            }
        });

    }
    /*卡种显示功能*/
    $scope.isShow = function(id){
        $http.get('/member-card/update-card-is-app-show?id='+ id).then(function(result){
            if(result.data.status == "success"){
                Message.success(result.data.message);
                // $scope.getClassModel();
                $scope.getCardData();
            //
            }
        });
    }

    $scope.num           = 0;
    $scope.unit          = true;
    $('.applySelectVenue').select2();
    //带人卡不带人卡
    $scope.withoutHuman = function(){
        $scope.bringEdit         = 0;
        $('#bring-false').attr('checked',true);
        $('#bring-true').attr('checked',false);
        $("#bring-num").attr("disabled",true).val(0);
    }
    //带人
    $scope.withPeople = function(){
        $('#bring-true').attr('checked',true);
        $('#bring-false').attr('checked',false);
        $scope.bringEdit = 1;
        $('#bring-num').val(1)
        $("#bring-num").attr("disabled",false);
    }
    //带人卡总人数不能超过5
    $scope.withPeopleNum = function(num){
        var $peopleNum = $('#bring-num').val();
        if($peopleNum != '' ){
            if(parseInt($peopleNum) >= 5 ){
                $scope.bringEdit = 5;
                $('#bring-num').val(5)
            }else{
                $scope.bringEdit = $peopleNum;
            }
        }

    }
    //获取卡种所属场馆
    $scope.getCardTheVenue = function(){
        $http.get('/member-card/get-venue-data-by-id').then(function(response){
            var len = response.data.data.length;
            if(len !=0 ){
                $scope.cardTheVenueListsFlag = true;
                $scope.cardTheVenueLists = response.data.data;
            }else{
                $scope.cardTheVenueListsFlag = false;
                $scope.cardTheVenueLists = response.data.data;
            }
        });
    }
    //获取所有团课课种信息
    $scope.groupCourse  = function(){
        $scope.leagueAllListsArr = [];
        $http.get('/new-league/top-course').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionClass = result.data;
                $scope.classStatus = true;
                var $allleague = result.data;
                $allleague.forEach(function(item,index){
                    var object = {
                        id:item.id,
                        text:item.name
                    }
                    $scope.leagueAllListsArr.push(object)
                });
            }else{
                $scope.optionClass = '暂无数据';
                $scope.classStatus = false;
            }
        });
    };

    //获取赠送的私课课程
    $scope.getGiveCourseData = function(){
        $http.get('/user/get-course?courseType=5').then(function(response){
            if(response.data.data.length != 0){
                $scope.GiveCourseDataLists = response.data.data;
                $scope.GiveCourseFlag    = true;
            }else{
                $scope.GiveCourseDataLists = response.data.data;
                $scope.GiveCourseFlag    = false;
            }
        });
    }
    //判断卡种名称是否存在
    $scope.setCardName = function () {
        $http.get('/member-card/set-card-name?name='+$scope.cardNameEdit +'&venueId=' + $scope.cardTheVenueIdEdit).success(function (result) {
            $scope.status = result.status;
        });
    };
    //根据选择场馆判断名称是否重复
    $scope.cardTheVenue = function(){
        $scope.setCardName();
    }
    //卡种属性修改
    $scope.basicAttributesEdit = function(){
        $scope.activeUnitEdit = '1';
        $scope.durationUnitEdit = '1';
        $scope.basicAttributesCompleteFlag = false;
        if($scope.categoryTypeId == '1'){
            $scope.cardTimesEdit = '';
            $scope.timesMethodEdit = '';
        }
        if($scope.categoryTypeId == '2'){
            $scope.timesMethodEdit = $scope.theData.count_method;
        }
        if($scope.theData.times != '' &&$scope.theData.times != null){
            $scope.cardTimesEdit = parseInt($scope.theData.times);
        }
        $scope.cardTheVenueIdEdit =$scope.theData.venue_id;//卡种所属场馆
        $scope.cardAttributeEdit  = $scope.theData.attributes;//属性
        $scope.cardTypeEdit       = $scope.theData.card_type;//类型
        $scope.cardNameEdit       = $scope.theData.card_name;//卡名称
        if($scope.theData.another_name != '' && $scope.theData.another_name != null){
            $scope.anotherNameEdit    = $scope.theData.another_name;//卡别名
        }else{
            $scope.anotherNameEdit    = '';//卡别名
        }
        if($scope.theData.active_time != '' && $scope.theData.active_time != null){
            $scope.activeTimeEdit    = parseInt($scope.theData.active_time);//激活时间
        }else{
            $scope.activeTimeEdit    = '';//激活时间
        }
        if($scope.theData.duration != '' && $scope.theData.duration != null){
            $scope.durationEdit    = angular.fromJson($scope.theData.duration).day;//有效天数
        }else{
            $scope.durationEdit    = '';//有效天数
        }
        if($scope.theData.single != '' && $scope.theData.single != null){
            $scope.SingularEdit       = parseInt($scope.theData.single);//单数
        }else{
            $scope.SingularEdit    = '';//单数
        }
        if(parseInt($scope.theData.bring) > 0 ){
            $("#bring-num").prop({"disabled":false});
            $('#bring-true').prop('checked',true);
            $('#bring-false').prop('checked',false);
            $scope.bringEdit          = parseInt($scope.theData.bring);
        }else{
            $('#bring-false').prop('checked',true);
            $('#bring-true').prop('checked',false);
            $("#bring-num").prop("disabled",true).val(0);
            $scope.bringEdit = 0;
        }
        if($scope.categoryTypeId == '1'){
            $('#timeAttributesModal').modal('show');
        }
        if($scope.categoryTypeId == '2'){
            $('#numAttributesModal').modal('show');
        }
    };

    //上传图片大小判断
    $scope.setCover = function (file) {
        if(file){
            if(file.size > 2000000){
                Message.warning('图片太大');
            }else{
                $scope.setPic(file);
            }
        }
    };
    //图片上传方法
    $scope.setPic = function (file) {
        Upload.upload({
            url    : '/private-teach/upload',
            method : 'POST',
            data   : {UploadForm:{imageFile:file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result){
            if(result.data.status == 'success'){
                $scope.theData.pic  = result.data.imgLink;
            }else{
                Message.warning(result.data.data);
            }
        })
    };

    //卡种基础信息修改完成
    $scope.basicAttributesComplete = function(){
        var basicAttributesEditData = {
            scenarios :'attributes',//场景
            cardId    :$scope.theData.id,//卡种id
            belongVenue :$scope.cardTheVenueIdEdit,//卡种所属场馆
            attributes :$scope.cardAttributeEdit,//卡的属性
            cardType :$scope.cardTypeEdit,//卡的类型
            cardName :$scope.cardNameEdit,//卡的名称
            anotherName :$scope.anotherNameEdit =='' || $scope.anotherNameEdit == undefined ? null:$scope.anotherNameEdit,//卡别名
            activeTime :$scope.activeTimeEdit =='' || $scope.activeTimeEdit == undefined ? null:$scope.activeTimeEdit,//激活期限
            activeUnit :$('#activeUnitEdit').val() =='' || $('#activeUnitEdit').val() == undefined ? null:$('#activeUnitEdit').val(),//激活期限单位
            duration :$scope.durationEdit,//有效天数
            durationUnit :$('#durationUnitEdit').val(),//有效天数单位
            singular :$scope.SingularEdit =='' || $scope.SingularEdit == undefined ? null:$scope.SingularEdit,//单数
            bring :$scope.bringEdit =='' || $scope.bringEdit == undefined ? null:$scope.bringEdit,//带人
            times :$scope.cardTimesEdit =='' || $scope.cardTimesEdit == undefined ? null:$scope.cardTimesEdit,//次数
            timesType :$scope.timesMethodEdit =='' || $scope.timesMethodEdit == undefined ? null:$scope.timesMethodEdit,//扣次方式
            pic : $scope.theData.pic,         //图片
            _csrf_backend:$('#_csrf').val()
        };
        if(typeof($("#bring-true").attr("checked"))!="undefined" &&($scope.bringEdit < 1 || $scope.bringEdit >5 || $scope.bringEdit == ''|| $scope.bringEdit == undefined) ){
            Message.warning('请输入带人人数（1-5位）');
            return;
        }
        // if($scope.status == 'error'){ Message.warning('卡名称已经存在'); return false; }
        if($scope.cardTheVenueIdEdit == '' || $scope.cardTheVenueIdEdit == undefined || $scope.cardTheVenueIdEdit == null){
            Message.warning('请选择卡种所属场馆');
            return;
        }
        if($scope.cardNameEdit == '' || $scope.cardNameEdit == undefined || $scope.cardNameEdit == null){
            Message.warning('请输入卡名称');
            return;
        }
        if($scope.cardTypeEdit == '' || $scope.cardTypeEdit == undefined || $scope.cardTypeEdit == null){
            Message.warning('请选择卡类型');
            return;
        }
        if($scope.durationEdit == '' || $scope.durationEdit == undefined || $scope.durationEdit == null){
            Message.warning('请输入有效天数');
            return;
        }
        $scope.basicAttributesCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(basicAttributesEditData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $scope.getCardData();
                if($scope.categoryTypeId == '1'){
                    $('#timeAttributesModal').modal('hide');
                }
                if($scope.categoryTypeId == '2'){
                    $('#numAttributesModal').modal('hide');
                }
            }else{
                $scope.basicAttributesCompleteFlag = false;
                // angular.forEach(response.data.data,function (value,key) {
                //     Message.warning(value);
                // });
                Message.warning(response.data.data)
            }
        });

    };
    //区域价不可填写
    $scope.setDisabled = function () {
        if($('#areaMinPrice1').val() == '' && $('#areaMinPrice2').val()==''){
            $scope.unable = false;
        }else{
            $scope.unable = true;
        }
    };
    //一口价不可填写
    $scope.setUnable = function () {
        if($('#areaMinPrice1s').val() == '' && $('#areaMinPrice2s').val() ==''){
            $scope.disabled = false;
        }else{
            $scope.disabled = true;
        }
    };

    //定价和售卖修改
    $scope.pricingAndSellingEdit = function(){
        var $sellLists = $('#validityRenewBoxLists').children('.validityRenewBox');
        $sellLists.each(function(){
            if($(this).hasClass('removeDiv')){
                $(this).remove();
            }
        });
        $scope.pricingAndSellingCompleteFlag = false;
        $scope.originalPriceEdit = '';//原价
        $scope.sellPriceEdit = '';//售价
        $scope.areaMinPriceEdit = '';//最小值
        $scope.areaMaxPriceEdit = '';//最大值
        $scope.offerPriceEdit = '';//优惠价
        $scope.appSellPrice = '';//移动端售价
        $scope.OrdinaryRenewalEdit = '';//普通续费
        if($scope.theData.original_price != null){
            $scope.unable = true;
            $scope.disabled = false;
            $scope.originalPriceEdit = parseInt($scope.theData.original_price);
        }
        if($scope.theData.sell_price != null){
            $scope.unable = true;
            $scope.disabled = false;
            $scope.sellPriceEdit = parseInt($scope.theData.sell_price);
        }
        if($scope.theData.min_price != null){
            $scope.disabled = true;
            $scope.unable = false;
            $scope.areaMinPriceEdit = parseInt($scope.theData.min_price);
        }
        if($scope.theData.max_price != null){
            $scope.disabled = true;
            $scope.unable = false;
            $scope.areaMaxPriceEdit = parseInt($scope.theData.max_price);
        }
        if($scope.theData.offer_price != null){
            $scope.offerPriceEdit = parseInt($scope.theData.offer_price);
        }
        if($scope.theData.app_sell_price != null){
            $scope.appSellPrice = parseInt($scope.theData.app_sell_price);
        }
        $scope.OrdinaryRenewalEdit = parseInt($scope.theData.ordinary_renewal);
        if($scope.theData.validity_renewal != 'null'){
            $scope.validityRenewalArr = angular.fromJson($scope.theData.validity_renewal);
        }else{
            $scope.validityRenewalArr = [];
            var object = {
                day:'',
                price:'',
                type :'d',
            };
            $scope.validityRenewalArr.push(object);
        }
        
        $('#pricingAndSellingModal').modal('show');
        $scope.addCardValidityEdit();
    };
    //获取所有的有效期续费
    $scope.getAllValidRenew = function(){
        $scope.AllValidRenewArr = [];
        var $AllValidRenewDiv = $('#validityRenewBoxLists').children('.validityRenewBox ');
        $AllValidRenewDiv.each(function(index,item){
            var $validNum = $(this).find('input[name="cardValidityNum"]').val();
            var $validCompany = $(this).find('.cardValidityCompany').val();
            var $validMoney  = $(this).find('input[name="cardValidityMoney"]').val();

            if($validNum != ''&& $validMoney != ''&& $validCompany != ''){
                var data ={
                    day :$validNum,
                    type:$validCompany,
                    price:$validMoney
                };
                $scope.AllValidRenewArr.push(data);
            }
        })
    };


    //定价和售卖修改完成
    $scope.pricingAndSellingComplete = function(){
        $scope.getAllValidRenew();
        var pricingAndSellingData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'price',//场景
            cardId    :$scope.theData.id,//卡种id
            originalPrice:$scope.originalPriceEdit,//一口原价
            sellPrice:$scope.sellPriceEdit,//一口售价
            minPrice:$scope.areaMinPriceEdit,//最低价
            maxPrice:$scope.areaMaxPriceEdit,//最高价
            offerPrice:$scope.offerPriceEdit,//优惠价
            appSellPrice:$scope.appSellPrice,//移动端售价
            ordinaryRenew:$scope.OrdinaryRenewalEdit,//普通续费价
            validityRenew:$scope.AllValidRenewArr//有效期续费
        };
        if($('#areaMinPrice1s').val() == '' && $('#areaMinPrice2s').val() == '' && $('#areaMinPrice1').val() == ''&& $('#areaMinPrice2').val() == '' ){
            Message.warning('请输入一口价或区域定价');
            return;
        }
        if($('#areaMinPrice1s').val() == '' && $('#areaMinPrice2s').val() == '' &&($('#areaMinPrice1').val() == ''||$('#areaMinPrice2').val() == '' )){
            Message.warning('请输入完整的原价和或售价');
            return;
        }
        if(($('#areaMinPrice1s').val() == '' || $('#areaMinPrice2s').val()) == '' &&$('#areaMinPrice1').val() == ''&& $('#areaMinPrice2').val() == '' ){
            Message.warning('请输入完整的区域定价');
            return;
        }
        if($scope.areaMinPriceEdit != '' && $scope.areaMaxPriceEdit != ''){
            if($scope.areaMaxPriceEdit < $scope.areaMinPriceEdit){
                Message.warning('最高价不能低于最低价');
                return;
            }
        }
        $scope.pricingAndSellingCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(pricingAndSellingData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#pricingAndSellingModal').modal('hide');
                $scope.getCardData();
            }else{
                $scope.pricingAndSellingCompleteFlag = false;
                angular.forEach(response.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        });

    }

    //获取所有售卖场馆数据加载
    $scope.venue     = function(){
        $http.get('/rechargeable-card-ctrl/get-venue?status=card').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.optionVenue = result.data.venue;
            }
        })
    };
    $scope.venue();
    //售卖场馆修改
    $scope.sellingVenueEdit = function(){
        //获取初始需要渲染的团课
        var $leaveLists = $('#sellVenue').children('.sellVenueBox ');
        $leaveLists.each(function(){
            if($(this).hasClass('removeDiv')){
                $(this).remove();
            }
        });
        $scope.sellingVenueEditCompleteFlag = false;
        $('#sellingVenueEditModal').modal('show');
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.dateInit();
        $timeout(function(){
            $scope.getOldSellVenueDom();
            $scope.getSellVenueIdArr();
        },100)
    }

    //默认显示之前设置的场馆，不限时勾选
    $scope.getOldSellVenueDom = function(){
        var oldLists =  $('#sellVenue').children('.sellVenueBox');
        oldLists.each(function(index,item){
            var $select = $(this).find('select[name="sellVenueSelect"]');//所有售卖场馆
            var $sellNum = $(this).find('input[name="sheets"]');//售卖张数
            var $sellStart = $(this).find('input[name="datetimeStart"]');//开始日期
            var $sellEnd = $(this).find('input[name="datetimeEnd"]');//结束日期

            var $discountLists = $(this).find('.discountList').find('.discountBox');//所有的折扣盒子
            // $select.select2();
            if($scope.theData.sellVenue.length !=0){
                $select.val($scope.theData.sellVenue[index].venue_id);
                // $(this).find('.select2-selection__rendered').text($scope.theData.sellVenue[index].name);
            }
            var oldInputData = $sellNum.data("num");
            var oldCheck = $(this).find('.sellNumBox').find('.i-checks').find('.icheckbox_square-green');
            if(oldInputData == -1){
                $sellNum.attr('disabled','disabled');
                $sellNum.val('');
                oldCheck.iCheck('check');
                $scope.setStyleCheckbox();
            }else{
                $sellNum.val(parseInt(oldInputData));
            }
            if($discountLists.length != 0){
                $discountLists.each(function(ind,ele){
                    if($scope.theData.sellVenue[index].cardDiscount.length > 0){
                        var $disNum = $(this).find('input[name="discountNum"]');//折扣次数
                        var $dis = $(this).find('input[name="discount"]');//折扣次数
                        if($scope.theData.sellVenue[index].cardDiscount[ind].discount != ''){
                            $dis.val(parseInt($scope.theData.sellVenue[index].cardDiscount[ind].discount))
                        }

                        var oldDisInput = $disNum.data("num");
                        var oldDisCheck = $(this).find('.i-checks').find('.icheckbox_square-green');
                        if(oldDisInput != ''){
                            if(oldDisInput == -1){
                                $disNum.attr('disabled','disabled');
                                $disNum.val('');
                                oldDisCheck.iCheck('check');
                                $scope.setStyleCheckbox();
                            }else{
                                $disNum.val(parseInt(oldDisInput));
                            }
                        }
                    }
                });
            }
        });
    }
    //选择场馆
    $scope.selectVenue = function(){
        $scope.getSellVenueIdArr();
    }
    //获取已经选择过得场馆
    $scope.getSellVenueIdArr = function () {
        var select = $('div#sellVenue');
        var div    = select.find('div.sellVenueBox');
        $scope.venueHttpIdArr = [];
        div.each(function (i) {
            var   $venueIds  =$(this).find('option:selected').val();
            if($venueIds != undefined && $venueIds != ""){
                $scope.venueHttpIdArr.push($venueIds);
            }
        });
    };


    // 获取所有售卖场馆
    $scope.getSellVenueArr = function () {
        var select = $('div#sellVenue');
        var div    = select.find('div.sellVenueBox');
        $scope.venueHttpIdArr = [];
        $scope.venueIds  = [];
        $scope.sheets    = [];
        $scope.saleStart = [];
        $scope.saleEnd   = [];
        $scope.discountArr = [];//售卖折扣数组
        $scope.sellDiscountArr = [];//售卖折扣数组
        $scope.sellDiscountNumArr = [];//售卖折扣数量
        div.each(function (i) {
            $scope.discountNumArr = [];
            $scope.sellDiscount  =[];
            $scope.sellDiscountNum =[];
            var   $venueIds  =$(this).find('option:selected').val();
            if($venueIds != undefined && $venueIds != ""){
                $scope.venueIds.push($venueIds);
                $scope.venueHttpIdArr.push($venueIds);
                var   $checked    = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var   $sheets     =  ($(this).find("input[name='sheets']").val());
                var $discountBox  = $(this).children('.discountList').children('.discountBox');//获取折扣box
                if($checked || $sheets ){
                    if($sheets){
                        $scope.sheets.push($sheets);
                    }else{
                        $scope.sheets.push(-1);
                    }
                }else{
                    $scope.sheets.push($sheets);
                }
                var   $saleStart =$(this).find("input[name='start']").val();
                var   $saleEnd   =$(this).find("input[name='end']").val();
                $scope.saleStart.push($saleStart);
                $scope.saleEnd.push($saleEnd);
                $discountBox.each(function(i){
                    var $discount = $(this).find('input[name="discount"]').val();//获取打折
                    var $discountCardNum = $(this).find('input[name="discountNum"]').val();//获取打card张数
                    var $discountChecked = $(this).find('div.icheckbox_square-green').hasClass('checked');//是否不限
                    if($discountChecked && $discount != ''){
                        $scope.sellDiscount.push($discount);
                        $scope.sellDiscountNum.push(-1);
                        var $discountData = {
                            discount:$discount,
                            surplus :-1
                        }
                        $scope.discountNumArr.push($discountData);
                    }else if($discount != '' && $discountCardNum != ''){
                        $scope.sellDiscount.push($discount);
                        $scope.sellDiscountNum.push($discountCardNum);
                        var $discountData = {
                            discount:$discount,
                            surplus :$discountCardNum
                        }
                        $scope.discountNumArr.push($discountData);
                    }else {
                        $scope.sellDiscount.push('');
                        $scope.sellDiscountNum.push('');
                    }

                });
                // if($scope.sellDiscount.length > 0 ){
                    $scope.sellDiscountArr.push($scope.sellDiscount);//折扣

                // }
                // if($scope.sellDiscountNum.length > 0){
                    $scope.sellDiscountNumArr.push($scope.sellDiscountNum);//售卖折扣数量
                // }
                $scope.discountArr.push($scope.discountNumArr);
            }
        });
    };

    //售卖场馆修改完成
    $scope.sellingVenueEditComplete = function(){
        $scope.getSellVenueArr();
        var sellingData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'sellVenue',//场景
            cardId    :$scope.theData.id,//卡种id
            sellVenueId:$scope.venueIds,//售卖场馆
            sellNum:$scope.sheets,//售卖张数
            sellStart      : $scope.saleStart,  //开始时间
            sellEnd     :$scope.saleEnd,          //售卖结束日期
            discount:$scope.sellDiscountArr,//折扣（几折）
            discountNum:$scope.sellDiscountNumArr,//折扣价售卖数
        }
        var $divLen = $('div#sellVenue').find('div.sellVenueBox').length;
        var $venLen = $scope.venueIds.length;
        var $sheetsLen = $scope.sheets.length;
        var $startLen = $scope.saleStart.length;
        var $endLen = $scope.saleEnd.length;
        var $dis = $scope.sellDiscountArr.length;
        var $disNum = $scope.sellDiscountNumArr.length;
        if($venLen != $divLen || $sheetsLen != $divLen|| $startLen != $divLen|| $endLen != $divLen){
            Message.warning('请填写完整的售卖场馆信息');
            return;
        }
        if($dis != $disNum){
            Message.warning('请填写完整的折扣信息');
            return;
        }
        $scope.sellingVenueEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(sellingData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#sellingVenueEditModal').modal('hide');
            }else{
                $scope.sellingVenueEditCompleteFlag = false;
                Message.warning(response.data.data)
            }
        });
    }

    //根据场馆等级获取场馆
    $scope.selectApplyVenueType = function(id){
        var typeId = id;
        if(id !=''){
            if(typeId == 1){
                $http.get('/rechargeable-card-ctrl/get-venue?status=card&type='+ id).then(function(response){
                    $scope.applyTypeVenueLists1 = response.data.venue;
                    $scope.applyVenueLists123 = response.data.venue;
                });
            }else if(typeId == 2){
                $http.get('/rechargeable-card-ctrl/get-venue?status=card&type='+ id).then(function(response){
                    $scope.applyTypeVenueLists2 = response.data.venue;
                    $scope.applyVenueLists123 = response.data.venue;
                });
            }
        }
    }
    $scope.applyVenue = function () {
        $scope.applyVenueId = [];
        $scope.applyStart   = [];
        $scope.applyEnd     = [];
        $scope.applyTimes   = [];
        $scope.applyGrade = [];
        $scope.applyVenueTypeArr = [];//场馆类型
        $scope.venueIsArr = [];//通店场馆类型1、单个2、多个
        $scope.venueListsArr = [];//所有通店场馆
        $scope.generalVenuesNoRepeat = [];//普通场馆选择数组，判断去重
        $scope.extrawellVenuesNoRepeat = [];//尊爵场馆选择数组，判断去重
        // $scope.openShopWeek = [];
        $scope.openShopWeekChecked = [];
        $scope.aboutLimit = [];
    };
    $scope.applyVenue();
    //通店场馆修改
    $scope.applyVenueEdit = function(){
        $.loading.show();
        //时间插件启动
        $('.clockpicker').clockpicker()
            .find('input').on('change',function() {
        });
        $scope.selectApplyVenueType(2)
        $scope.selectApplyVenueType(1)
        //获取初始需要渲染的团课
        var $leaveLists = $('#applyVenue').children('.applyVenueBox ');
        $leaveLists.each(function(){
            if($(this).hasClass('removeDiv')){
                $(this).remove();
            }
        });
        $('.applySelectVenue ').select2();
        $scope.applyVenueEditCompleteFlag = false;
        $('#applyVenueEditModal').modal('show');
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $timeout(function(){
            $.loading.hide();
            $scope.getOldApplyVenueDom();
        },500)
    };

    //获取所有适用场馆
    $scope.getVenueApply = function () {
        var div = $('div#applyVenue');
        var apply = div.children('div.applyVenueBox');
        $scope.applyVenue();
        if (apply.length>0) {
            apply.each(function (i) {
                // $scope.generalVenuesNoRepeat = [];//普通场馆选择数组，判断去重
                // $scope.extrawellVenuesNoRepeat = [];//尊爵场馆选择数组，判断去重
                // var $applyVenue = $(this).children(".pRelative").find('option:selected').val();
                var $checked    = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $applyTime  = $(this).find("input[name='times']").val();
                var $applyStart = $(this).find('div.clockpicker').children("input[name='applyStart']").val();
                var $applyEnd   = $(this).find('div.clockpicker').children("input[name='applyEnd']").val();
                var $applyGrade = $(this).find("select[name='times1']").find('option:selected').val();
                var $applyVenueType = $(this).find("select[name='applyVenueType']").val();
                var $applyVenue123 = $(this).find('.applySelectVenue').val();
                var $resList =    $(this).find('.applySelectVenue').select2("data");
                var $aboutLimit = $(this).children('.about').find('div.icheckbox_square-green').hasClass('checked');
                $scope.everyLen  = $resList.length;
                $scope.everClassArr  =[];
                $resList.forEach(function(item,index,arr){
                    $scope.everClassArr.push(item.id);
                    if($applyVenueType == 1){
                        $scope.generalVenuesNoRepeat.push(item.id);
                        if((index + 1) == $scope.everyLen){
                            $scope.venueListsArr.push($scope.everClassArr);
                            $scope.selectApplyVenueType($applyVenueType);
                        }
                    }
                    if($applyVenueType == 2){
                        $scope.extrawellVenuesNoRepeat.push(item.id);
                        if((index + 1) == $scope.everyLen){
                            $scope.venueListsArr.push($scope.everClassArr);
                            $scope.selectApplyVenueType($applyVenueType);
                        }
                    }
                });
                $scope.applyVenue123 = $applyVenue123;
                var week    = $(this).find('select[name="weeks"]').val();
                // if($scope.everyLen != ''&& $scope.everyLen>=1 && $scope.everyLen != null && $scope.everyLen != undefined && ($applyTime !=''||$checked)){
                    $scope.applyVenueTypeArr.push($applyVenueType);
                    $scope.applyGrade.push($applyGrade);
                    $scope.applyStart.push($applyStart);
                    $scope.applyEnd.push($applyEnd);
                    // $scope.applyVenueId.push($applyVenue);
                    $scope.openShopWeekChecked.push(week);
                    if($checked || $applyTime){
                        if($applyTime){
                            $scope.applyTimes.push($applyTime);
                        }else{
                            $scope.applyTimes.push(-1);
                        }
                    }
                    if($scope.everyLen ==1){
                        $scope.venueIsArr.push(1)
                    }else if($scope.everyLen >=1){
                        $scope.venueIsArr.push(2)
                    }
                    if($aboutLimit){
                        $scope.aboutLimit.push(-1);
                    }else{
                        $scope.aboutLimit.push(1);
                    }
                // }
            });
        }else{
            $scope.applyVenue();
        }
    };
    //判断开始时间点和结束时间点是否正确
    $scope.getTimeFindTime            = function (start,end) {
        var dateTime    = new Date().toLocaleDateString();         //获取今天日期2017/4/24
        var startTime   = dateTime +" "+ start;                    //拼接开始日期时间
        var endTime     = dateTime +" "+ end;                      //拼接结束日期时间
        var startTimes  = Date.parse(new Date(startTime));         //开始日期格式化时间戳
        startTimes = startTimes / 1000;
        var endTimes     = Date.parse(new Date(endTime));          //结束日期格式化时间戳
        endTimes    = endTimes / 1000;
        if(startTimes != "NaN" && endTimes != "NaN"){
            if(startTimes >= endTimes){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    };
    //定义公共验证方法
    $scope.commonRule = function (attr,text) {
        if(!attr||attr==""){Message.warning(text);return false;}return true;
    };
    //通店场馆修改完成
    $scope.applyVenueEditComplete = function(){
        var div = $('div#applyVenue');
        var apply = div.children('div.applyVenueBox');
        var $applyVenLen = apply.length;
        $scope.getVenueApply();
        var applyVenueData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'applyVenue',//场景
            cardId    :$scope.theData.id,//卡种id
            applyVenueId:$scope.venueListsArr,//通用场馆
            applyStart:$scope.applyStart,     //进馆开始时间
            applyEnd:$scope.applyEnd,         //进馆结束时间
            applyTimes:$scope.applyTimes,//通店次数
            venueType      : $scope.applyVenueTypeArr,  //场馆通店场馆类型
            applyLength     :$scope.venueIsArr,          //通店场馆选择类型1、单个 2、多个
            applyUnit:$scope.openShopWeekChecked,//通店次数单位（周、月）
            cardLevel:$scope.applyGrade,//卡的等级
            aboutLimit:$scope.aboutLimit//团课预约设置
        };
        var $typeLen = $scope.applyVenueTypeArr.length;
        var $venLen = $scope.venueListsArr.length;
        var $timesLen = $scope.applyTimes.length;
        if($typeLen < 1 || $venLen < 1 || $timesLen<1){
            Message.warning("请填写完成的通店信息");
            return;
        }
        if($typeLen != $venLen || $typeLen != $timesLen || $venLen != $applyVenLen || $timesLen != $applyVenLen){
            Message.warning("请填写完成的通店信息");
            return;
        }
        // if($scope.applyStart != '' && $scope.applyEnd != ''){
        //     if(!$scope.getTimeFindTime($scope.applyStart,$scope.applyEnd)){
        //         if(!$scope.commonRule('','进馆时间的结束时间点不正确')){ return false;}
        //     }
        // }
        $scope.applyVenueEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(applyVenueData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#applyVenueEditModal').modal('hide');
            }else{
                $scope.applyVenueEditCompleteFlag = false;
                Message.warning(response.data.data)
            }
        });
    };
    //时间戳转换成时间公共方法
    $scope.getDateCh = function (str) {
        str = parseInt(str);
        if (str != "" && str != null && str != 0) {
            var oDate = new Date(str);
            var hh = oDate.getHours();
            var mm = oDate.getMinutes();
            hh = hh >= 10 ? hh : '0' + hh;
            mm = mm >= 10 ? mm : '0' + mm;
            var theDate = hh + ":" + mm;
        } else {
            theDate = "";
        }
        return theDate;
    };
    //默认显示之前设置的通店场馆，不限时勾选
    $scope.getOldApplyVenueDom = function(){
        var oldLists =  $('#applyVenue').children('.applyVenueBox');
        oldLists.each(function(index,item){
            var $applyStart =  $(this).find('.clockpicker').children("input[name='applyStart']");
            var $applyEnd =  $(this).find('.clockpicker').children("input[name='applyEnd']");
            var $select = $(this).find('select[name="applySelectVenue"]');
            var $cardType = $(this).find('select[name="times1"]');
            var $venueType = $(this).find('select[name="applyVenueType"]');
            $select.select2();

            if($scope.theData.goVenue.length !=0){
                $applyStart.val($scope.getDateCh($scope.theData.goVenue[index].apply_start*1000));
                $applyEnd.val($scope.getDateCh($scope.theData.goVenue[index].apply_end*1000));
                $cardType.val($scope.theData.goVenue[index].level);
                if($scope.theData.goVenue[index].venue_ids != null && $scope.theData.goVenue[index].organization != undefined){
                    var $type = $scope.theData.goVenue[index].organization[0].identity;
                    $venueType.val($type);
                    var data = angular.fromJson($scope.theData.goVenue[index].venue_ids);
                    $select.val(data).trigger("change");
                }else{
                    var $type = $scope.theData.goVenue[index].identity;
                    $venueType.val($type);
                    var data = angular.fromJson($scope.theData.goVenue[index].venue_id);
                    $select.val(data).trigger("change");
                }
                var aboutInput = $scope.theData.goVenue[index].about_limit;
                var aboutCheck = $(this).find('.about').find('.i-checks').find('.icheckbox_square-green');
                if(aboutInput == -1){
                    aboutCheck.iCheck('check');
                }
            }
            var oldInput =  $(this).find('.inputUnlimited').children("input[name='times']");
            var oldInputData = oldInput.data("num");
            var oldCheck = $(this).find('.inputUnlimited').find('.i-checks').find('.icheckbox_square-green');
            if(oldInputData == -1){
                oldInput.attr('disabled','disabled');
                oldInput.val('');
                oldCheck.iCheck('check');
                $scope.setStyleCheckbox();
            }else{
                oldInput.val(oldInputData);
            }
        })
    };
    //场馆改变触发事件
    $scope.selectApply = function () {
        $scope.getVenueApply();
    };
    //打开周时间模态框
    $scope.openMyModals8 = function(){
        $('#myModals8').modal('show');
        $('#weekStart').val('');
        $('#weekEnd').val('');
    };

    //选择时间完成
    $scope.weekTimeSelect = function(){
        var nowTime = new Date().getTime();
        var currentDate = $scope.getMyDate(nowTime);
        var _start = $('#weekStart').val();
        var _end   = $('#weekEnd').val();
        if(_start =='' || _end == ''){
            Message.warning('请选择时间');
        }else if(_start != '' && _end != ''){
            var _startTime = currentDate + ' '+ _start;
            var _endTime = currentDate + ' '+ _end;
            var _startNow = new Date(_startTime).getTime();
            var _endNow = new Date(_endTime).getTime();
            if(_endNow <= _startNow){
                Message.warning('结束时间不能小于等于开始时间');
            }else{
                $('#myModals8').modal('hide');
            }
        }
    };

    //判断日历上的时间
    $scope.IsTimeTrue =function(){
        var nowTime = new Date().getTime();
        var currentDate = $scope.getMyDate(nowTime);
        var _start = $scope.dayStart;
        var _end   = $scope.dayEnd ;
         if(_start != '' && _end != ''){
            var _startTime = currentDate + ' '+ _start;
            var _endTime = currentDate + ' '+ _end;
            var _startNow = new Date(_startTime).getTime();
            var _endNow = new Date(_endTime).getTime();
            if(_endNow <= _startNow){
                Message.warning('结束时间不能小于等于开始时间');
                $scope.dayEnd ='';
            }
        }
    };

    //选择固定日或特定时间段，特定星期不可选
    $scope.setDayTime = function () {
        var $weekSelect = $('.weekSelect');
        var $table = $('#table');
        var $dayCheck = $table.find('.bColor');
        var $sum = $dayCheck.length - 1;
        if($scope.dayStart != undefined || $scope.dayEnd != undefined){
            $weekSelect.find('input').iCheck('disable',true);
            $weekSelect.find('input').css('left','23px').css('z-index','1000');
        }
        if(($scope.dayStart == "" || $scope.dayStart == undefined) && ($scope.dayEnd == "" || $scope.dayEnd == undefined) && $sum == -1){
            $weekSelect.find("input[name='weeksTime']").removeAttr('disabled').css('left','0').css('z-index','0');
            $weekSelect.find('div.icheckbox_square-green').removeAttr('disabled');
        }
        $scope.IsTimeTrue();
    };
    //选择特定星期，固定日和特定时间段不可选
    var $input = $('.weekSelect');
    var $table = $('#table');
    $('.weekSelect').on('ifChecked', function () {
        $('.clockpicker').find("input[name='dayStart']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayStart']").attr('');
        $('.clockpicker').find("input[name='dayEnd']").attr('disabled', 'disabled');
        $('.clockpicker').find("input[name='dayEnd']").attr('');
        $table.find('td').css('cursor','not-allowed');
        $table.addClass('noneClick');
    });
    $('.weekSelect').on('ifUnchecked', function () {
        var  $weekChecked = $input.find("div.checked");
        var  $num         = $weekChecked.length - 1;
        if($num == undefined || $num == ""){
            $('.clockpicker').find("input[name='dayStart']").removeAttr('disabled');
            $('.clockpicker').find("input[name='dayEnd']").removeAttr('disabled');
            $table.find('td').css('cursor','pointer');
            $table.removeClass('noneClick');
        }
    });

    //获取天（几号）
    $scope.getDays = function () {
        $scope.day = [];     //几号
        var table  = angular.element(document.getElementById('table'));
        var td     = table.find('td.bColor');
        if(td.length != undefined && td.length != 0){
            td.each(function (i){
                $scope.day.push(parseInt(td[i].innerHTML));
            })
        }else{
            $scope.day = [];
        }
    };
    //获取星期
    $scope.getWeek = function () {
        $scope.week      = [];     //星期几
        $scope.weekStart = [];     //星期的开始时间
        $scope.weekEnd   = [];     //星期的结束时间
        var li     = $('ul.weekSelect');
        var input  = li.find('.week').find('.checked').find('input');
        if(input.length != undefined && input.length != 0){
            input.each(function (i) {
                var $input = parseInt($(this).val())
                $scope.week.push($input);
                if($(this).parents('div.week').next().html() != ''){
                    $scope.weekStart.push($(this).parents('div.week').next().html().substring(0,5));
                    $scope.weekEnd.push($(this).parents('div.week').next().html().substring(7,12));
                }else{
                    $scope.weekStart.push(null);
                    $scope.weekEnd.push(null);
                }
            });
        }
    };
    //进馆时间限制修改
    $scope.entranceTimeSettingEdit = function(){
        $scope.allClearTimeAndWeek();
        $.loading.show();
        $scope.timeEditCompleteFlag = false;
        //获取初始需要渲染的进馆时间
        if($scope.theData.cardTime.day.day != null){
            $scope.dayStart = $scope.theData.cardTime.day.start;
            $scope.dayEnd   = $scope.theData.cardTime.day.end;
            $scope.getAllDay($scope.theData.cardTime.day.day);
        }else{
            $scope.getAllWeek($scope.theData.cardTime.week);
        }
        $('#entranceTimeEditModal').modal('show');
        $timeout(function(){
            $.loading.hide();
        },500)
    };
    //清空所有时间和星期
    $scope.allClearTimeAndWeek = function(){
        //清除星期
        var $allWeek = $('.weekSelect').find('.icheckbox_square-green');
        $allWeek.removeClass('checked');
        var $weekTme = $('.weekSelect').find('.weekTime');
        $weekTme.text('');
        $allWeek.removeAttr('disabled');
        //清除日历
        $scope.dayStart = '';
        $scope.dayEnd = '';
        var $td = $('#table').find('.timeTd');
        var $table = $('#table');
        $td.removeClass('bColor');
        $td.css('cursor','pointer');
        $table.removeClass('noneClick');
        $('.clockpicker').find("input[name='dayStart']").removeAttr('disabled');
        $('.clockpicker').find("input[name='dayEnd']").removeAttr('disabled');
    };

    //获取所有的日历时间
    $scope.getAllDay = function(allDay){
        if(allDay != null){
            var $allDayLen = allDay.length;
            if($allDayLen > 0 ){
                var $td = $('#table').find('.timeTd');
                $td.each(function(){
                    var $value = $(this).data('num').toString();
                    if($.inArray($value, allDay) != -1){
                        var $week = $('.weekSelect');
                        $(this).toggleClass('bColor');
                        $week.find('input').iCheck('disable');
                        $week.find('input').css('left','23px').css('z-index','1000');
                    }
                });
            }
        }
    };
    //获取所有的星期
    $scope.getAllWeek = function (allWeek) {
        if(allWeek != null){
            if(allWeek.weeks != null){
                var $allWeekLen = allWeek.weeks.length;
                if($allWeekLen > 0){
                    var $li = $('ul.weekSelect').find('input');
                    $li.each(function(index,item){
                        var $value = $(this).val();
                        for(var i=0;i<allWeek.weeks.length;i++){
                            if($value == allWeek.weeks[i]){
                                var $allWeek = $('.weekSelect').find('.icheckbox_square-green').eq(index);
                                $allWeek.addClass('checked');

                                var $weekTme = $('.weekSelect').find('.weekTime').eq(index);
                                if(allWeek.startTime[i] != null && allWeek.endTime[i] != null && allWeek.startTime[i] != '' && allWeek.endTime[i] != ''){
                                    var $time = allWeek.startTime[i]+'--'+ allWeek.endTime[i];
                                    $weekTme.text($time);
                                }
                                var $table = $('#table');
                                $('.clockpicker').find("input[name='dayStart']").attr('disabled', 'disabled');
                                $('.clockpicker').find("input[name='dayEnd']").attr('disabled', 'disabled');
                                $table.find('td').css('cursor','not-allowed');
                                $table.addClass('noneClick');
                            }
                        }
                    })
                }
            }
        }
    };
    //修改进馆时间，点击完成
    $scope.entranceTimeEditComplete = function () {
        $scope.getDays();
        $scope.getWeek();
        var timeEditData = {
            scenarios :'time',              //场景
            cardId    :$scope.theData.id,   //卡种id
            day       :$scope.day,          //几号（数组）
            dayStart  :$scope.dayStart,     //天的开始时间
            dayEnd    :$scope.dayEnd,       //天的节数时间
            week      :$scope.week,         //星期几（数组）
            weekStart :$scope.weekStart,    //星期的开始时间（数组）
            weekEnd   :$scope.weekEnd,      //星期的结束时间（数组）
            _csrf_backend:$('#_csrf').val()
        };
        // return;
        $scope.timeEditCompleteFlag = true;
        $http({
            method : 'POST',
            url    : '/member-card/card-edit',
            data   : $.param(timeEditData),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#entranceTimeEditModal').modal('hide');
            }else{
                $scope.timeEditCompleteFlag = false;
                Message.warning(response.data.data)
            }
        });
    };

    //定义课程数组
    $scope.class = function () {
        $scope.classId      = [];
        $scope.pitchNum     = [];
        $scope.binkClassIsArr = [];
        $scope.classArr1234  = [];
    };
    //js获取课程数组
    $scope.classServer = function () {
        var div = $('#course');
        var course = div.children('div.addLeagueCourseBox');
        $scope.class();
        if(course.length > 0){
            course.each(function (i) {
                var $select = $(this).find('option:selected').val();
                var $moreSelect = $(this).find('.leagueCourseList').val();
                var $reslist =$(this).find('.leagueCourseList').select2("data");
                var $check  = $(this).find('div.icheckbox_square-green').hasClass('checked');
                var $class  = $(this).find("input[name='times']").val();
                if($moreSelect){
                    $scope.everyLen  = $reslist.length;
                    $scope.everClassArr  =[];
                    $reslist.forEach(function(item,index,arr){
                        var $item = $(this);
                        $scope.classArr1234.push(item.id);
                        $scope.everClassArr.push(item.id);
                        if((index + 1) == $scope.everyLen){
                            $scope.classId.push($scope.everClassArr);
                        }
                    })

                    var $len =$reslist.length;
                    if($len == 1){
                        $scope.binkClassIsArr.push(1)
                    }
                    if($len > 1){
                        $scope.binkClassIsArr.push(2)
                    }
                    if($check || $class){
                        if($check){
                            $scope.pitchNum.push(-1)
                        }else{
                            $scope.pitchNum.push($class);
                        }
                    }
                }
            });
        }else{
            $scope.class();
        }
    };

    //课程改变触发事件
    $scope.selectClass     = function (id) {
        $scope.classServer();
        $scope.groupCourse();
    };

    //团课课程修改
    $scope.classPackageEdit = function(){
        $.loading.show();
        $scope.leagueClassEditCompleteFlag = false;
        //获取初始需要渲染的团课
        var $leaveLists = $('#course').children('.addLeagueCourseBox ');
        $leaveLists.each(function(){
            if($(this).hasClass('removeDiv')){
                $(this).remove();
            }
        });
        $('.leagueCourseList').select2();
        if($scope.theData.serverClass.length ==0){
            var $oldListsOne =  $('.course').children('.addLeagueCourseBox');
            var $selectOne = $oldListsOne.find('select[name="leagueCourseList"]');
            $selectOne.select2()
            $selectOne.val('');
            $scope.classKey = '';
            $oldListsOne.find('.select2-selection--multiple >.select2-selection__rendered').text('');
        }
        $timeout(function(){
            $scope.getICheck();
            $scope.setStyleCheckbox();
            $scope.getOldLeagueDom();
            $('#leagueClassEditModal').modal('show');
            $.loading.hide();
            $scope.classServer()
        },300)
    }

    //完成团课修改
    $scope.leagueClassEditComplete = function(){
        $scope.classServer();
        var $courseLen = $('#course').children('div.addLeagueCourseBox').length;
        var leagueEditData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'groupClass',//场景
            cardId    :$scope.theData.id,//卡种id
            groupClassId :$scope.classId,//团课id（数组）
            groupNum:$scope.pitchNum,//团课节数（数组）
            bindLength:$scope.binkClassIsArr//课程数组类型
        }
        if($courseLen > 1){
            if($scope.pitchNum.length != $scope.classId.length ||  $scope.classId.length != $courseLen){
                Message.warning('请填写完整的课程信息');
                return;
            }
        }
        if($courseLen == 1 ){
            if($scope.pitchNum.length != $scope.classId.length){
                Message.warning('请填写完整的课程信息');
                return;
            }
        }
        $scope.leagueClassEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(leagueEditData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#leagueClassEditModal').modal('hide');
            }else{
                $scope.leagueClassEditCompleteFlag = false;
                Message.warning(response.data.data)
            }
        });

    }

    //默认显示之前设置的团课，不限时勾选
    $scope.getOldLeagueDom = function(){
        var oldLists =  $('.course').children('.addLeagueCourseBox');
        oldLists.each(function(index,item){
            var $select = $(this).find('select[name="leagueCourseList"]');
            $select.select2();
            if($scope.theData.serverClass.length !=0){
                if($scope.theData.serverClass[index].polymorphic_ids != null){
                    var data = angular.fromJson($scope.theData.serverClass[index].polymorphic_ids);
                    $select.val(data).trigger("change");
                }else{
                    var data = angular.fromJson($scope.theData.serverClass[index].polymorphic_id);
                    $select.val(data).trigger("change");
                }
            }
            var oldInput =  $(this).find('.inputUnlimited').children("input[name='times']");
            var oldInputData = oldInput.data("num");
            var oldCheck = $(this).find('.inputUnlimited').find('.i-checks').find('.icheckbox_square-green');
            if(oldInputData == -1){
                oldInput.attr('disabled','disabled');
                oldInput.val('');
                oldCheck.iCheck('check');
                $scope.setStyleCheckbox();
            }else{
                oldInput.val(oldInputData);
            }
        })
    }

    //私课课程修改
    $scope.bindingPrivateLessonsEdit = function(){
        $scope.privateCourseEditCompleteFlag = false;
        $('#HSClass').select2();
        $('#HSClass').val('');
        $scope.HSClass = '';
        $scope.HSClassNum = '';
        $('#select2-HSClass-container').text('请选择课程');
        $('#PTClass').select2();
        $('#PTClass').val('');
        $scope.PTClass = '';
        $scope.PTClassNum = '';
        $('#select2-PTClass-container').text('请选择课程');
        $('#BirthdayClass').select2();
        $('#BirthdayClass').val('');
        $scope.BirthdayClass = '';
        $scope.birthClassNum = '';
        $('#select2-BirthdayClass-container').text('请选择课程');
        var $PTData = $scope.theData.serverCharge;
        $PTData.forEach(function(item,index){
            if(item.polymorphic_type == 'hs'){
                $('#HSClass').val(item.polymorphic_id);
                $scope.HSClass = item.polymorphic_id;
                $('#select2-HSClass-container').text(item.chargeClassName);
                $scope.HSClassNum = parseInt(item.number);
            }
            if(item.polymorphic_type == 'pt'){
                $('#PTClass').val(item.polymorphic_id);
                $scope.PTClass = item.polymorphic_id;
                $('#select2-PTClass-container').text(item.chargeClassName);
                $scope.PTClassNum = parseInt(item.number);
            }
            if(item.polymorphic_type == "birth"){
                $('#BirthdayClass').val(item.polymorphic_id);
                $scope.BirthdayClass = item.polymorphic_id;
                $('#select2-BirthdayClass-container').text(item.chargeClassName);
                $scope.birthClassNum = parseInt(item.number);
            }
        });
        $('#privateCourseEditModal').modal('show');
    }

    //私课修改完成
    $scope.privateCourseEditComplete = function(){
        var privateCourseData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'chargeClass',//场景
            cardId    :$scope.theData.id,//卡种id
            hsId :$('#HSClass').val() =='' || $('#HSClass').val() == undefined ? null:$('#HSClass').val(),//HS课程id
            hsNum :$scope.HSClassNum  != undefined && $scope.HSClassNum  != ''? $scope.HSClassNum :null,//HS课程节数
            ptId :$('#PTClass').val() =='' || $('#PTClass').val() == undefined ? null:$('#PTClass').val(),//PT课程id
            ptNum :$scope.PTClassNum != undefined && $scope.PTClassNum != '' ? $scope.PTClassNum : null,//PT课程节数
            birthId :$('#BirthdayClass').val() =='' || $('#BirthdayClass').val() == undefined ? null:$('#BirthdayClass').val(),//生日课id
            birthNum :$scope.birthClassNum  != undefined && $scope.birthClassNum != '' ? $scope.birthClassNum : null,//生日课节数
        }
        //hs课程
        if($('#HSClass').val() == ''&& ($scope.HSClassNum  != undefined && $scope.HSClassNum  != '' && $scope.HSClassNum  != null) ){
            Message.warning('HS课程或节数不能为空');
            return false;
        }
        if($('#HSClass').val() != ''&& ($scope.HSClassNum  == undefined || $scope.HSClassNum  == ''|| $scope.HSClassNum  == null) ){
            Message.warning('HS课程或节数不能为空');
            return false;
        }
        //pt课程
        if($('#PTClass').val() == ''&& ($scope.PTClassNum  != undefined && $scope.PTClassNum  != '' && $scope.PTClassNum  != null) ){
            Message.warning('PT课程或节数不能为空');
            return false;
        }
        if($('#PTClass').val() != ''&& ($scope.PTClassNum  == undefined || $scope.PTClassNum  == ''|| $scope.PTClassNum  == null) ){
            Message.warning('PT课程或节数不能为空');
            return false;
        }
        //生日课程
        if($('#BirthdayClass').val() == ''&& ($scope.birthClassNum  != undefined && $scope.birthClassNum  != '' && $scope.birthClassNum  != null) ){
            Message.warning('生日课程或节数不能为空');
            return false;
        }
        if($('#BirthdayClass').val() != ''&& ($scope.birthClassNum  == undefined || $scope.birthClassNum  == ''|| $scope.birthClassNum  == null) ){
            Message.warning('生日课程或节数不能为空');
            return false;
        }
        $scope.privateCourseEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(privateCourseData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#privateCourseEditModal').modal('hide');
            }else{
                $scope.assignmentEditCompleteFlag = false;
                Message.warning(response.data.data)
            }
        });
    }

    //选择商品时去重
    $scope.selectDonation = function(){
        $scope.giveShopArray = [];
        var allLists =  $('#giveShopBox').children('.donationBox');
        allLists.each(function(){
            var $select = $(this).find('option:selected').val();
            if($select != '' && $select != undefined){
                $scope.giveShopArray.push($select);
            }
        });
    }

    //赠品修改
    $scope.giftEditClick = function(){
        $.loading.show();
        $scope.giftEditCompleteFlag = false;
        var $giftEditLists = $('#giveShopBox').children('.donationBox');
        $giftEditLists.each(function(){
            if($(this).hasClass('removeDiv')){
                $(this).remove();
            }
        });
        $scope.addDonationEditHtml();
        $('#giftEditModal').modal('show');
        $timeout(function(){
            $.loading.hide();
            $scope.getOldGiftShop();
        },500);
    }

    //点击删除重新获取去重
    $scope.removeGIveClick = function(){
        $timeout(function(){
            $scope.selectDonation();
        },300)
    }
    //获取赠送商品信息
    $scope.getDonationOptions = function () {
        $http.get('/rechargeable-card-ctrl/get-donation-data').then(function (result) {
            if(result.data.goods.length != 0){
                $scope.optionDonation = result.data.goods;
                $scope.donationStatus       = true;
                $scope.myDonation         = true;
            }else{
                $scope.optionDonation       = result.data.goods;
                $scope.donationStatus       = false;
                $scope.myDonation         = false;
            }
        });
    };

    //默认显示之前赠送的商品，不限时勾选
    $scope.getOldGiftShop = function(){
        var oldLists =  $('#giveShopBox').children('.donationBox');
        oldLists.each(function(index,item){
            var $select = $(this).find('select.selectGive123');
            if($scope.theData.gift.length !=0){
                if($scope.theData.gift[index].goodsId != null){
                    // var data = angular.fromJson($scope.theData.serverClass[index].polymorphic_ids);
                    $select.val($scope.theData.gift[index].goodsId);
                }else{
                    // var data = angular.fromJson($scope.theData.serverClass[index].polymorphic_id);
                    $select.val('')
                }
            }
            var oldInput =  $(this).find('.inputUnlimited').children("input[name='times']");
            var oldInputData = oldInput.data("num");
            var oldCheck = $(this).find('.inputUnlimited').find('.i-checks').find('.icheckbox_square-green');
            if(oldInputData == -1){
                oldInput.attr('disabled','disabled');
                oldInput.val('');
                oldCheck.iCheck('check');
                $scope.setStyleCheckbox();
            }else{
                oldInput.val(oldInputData);
            }
        })
    }

    //js获取赠送数组
    $scope.donationShop = function () {
        $scope.donationId  = [];
        $scope.donationNum = [];
    };
    //js获取赠送数组
    $scope.donationGoods = function () {
        var div = $('#giveShopBox');
        var gift = div.children('div.donationBox');
        $scope.donationShop();
        gift.each(function (i) {
            var $selected  = $(this).find('option:selected').val();
            var $checked   = $(this).find('div.icheckbox_square-green').hasClass('checked');
            var $num       = $(this).find("input[name='times']").val();
            if($selected){
                $scope.donationId.push($selected);
                if($checked || $num){
                    if($checked){
                        $scope.donationNum.push(-1)
                    }else{
                        $scope.donationNum.push(parseInt($num));
                    }
                }
            }
        });
    };
    
    //赠品修改完成
    $scope.giftEditComplete = function(){
        var _div = $('div#giveShopBox');
        var _giftLength = _div.children('div.donationBox').length;
        $scope.donationGoods();
        var giftEditData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'gift',//场景
            cardId    :$scope.theData.id,//卡种id
            giftId :$scope.donationId,//赠品id Array
            giftNum :$scope.donationNum,//赠品数量 Array
        }
        var _giftIdLen = $scope.donationId.length;
        var _giftNumLen = $scope.donationNum.length;
        if(_giftIdLen != 0 || _giftNumLen !=0){
            if(_giftLength != _giftIdLen || _giftNumLen != _giftLength || _giftIdLen != _giftNumLen ){
                Message.warning('请填写完整的赠送信息');
                return;
            }
        }
        $scope.giftEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(giftEditData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#giftEditModal').modal('hide');
            }else{
                $scope.giftEditCompleteFlag = false;
                Message.warning(response.data.data);
            }
        });
    }

    //转让修改
    $scope.assignmentEdit = function(){
        $scope.transferNumberEdit = '';
        $scope.transferPriceEdit  = '';
        if($scope.theData.transfer_number != '' && $scope.theData.transfer_number != null){
            $scope.transferNumberEdit = parseInt($scope.theData.transfer_number);
        }
        if($scope.theData.transfer_price != '' && $scope.theData.transfer_price != null){
            $scope.transferPriceEdit = parseInt($scope.theData.transfer_price);
        }
        $scope.assignmentEditCompleteFlag = false;
        $('#assignmentEditModal').modal('show');
    }
    //转让修改完成
    $scope.assignmentEditComplete = function(){
        var assignmentEditData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'transfer',//场景
            cardId    :$scope.theData.id,//卡种id
            transferNum :$scope.transferNumberEdit =='' || $scope.transferNumberEdit == undefined ? null:$scope.transferNumberEdit,//转让次数
            transferPrice :$scope.transferPriceEdit =='' || $scope.transferPriceEdit == undefined ? null:$scope.transferPriceEdit,//转让价格
        }
        if(($scope.transferNumberEdit != ''&& $scope.transferNumberEdit !=null &&($scope.transferPriceEdit == ''|| $scope.transferPriceEdit == null))){
            Message.warning('请输入转让次数或转让金额');
            return;
        }
        if(($scope.transferPriceEdit != ''&& $scope.transferPriceEdit !=null &&($scope.transferNumberEdit == ''|| $scope.transferNumberEdit == null))){
            Message.warning('请输入转让次数或转让金额');
            return;
        }
        $scope.assignmentEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(assignmentEditData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#assignmentEditModal').modal('hide');
            }else{
                $scope.assignmentEditCompleteFlag = false;
                angular.forEach(response.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        });

    }

    //请假修改
    $scope.leaveEditClick = function(){
        $scope.leaveEditCompleteFlag = false;
        var $leaveLists = $('.leaveNumBoxLists').children('.leaveNumBox');
        $leaveLists.each(function(){
            if($(this).hasClass('removeDiv')){
                $(this).remove();
            }
        });
        $scope.leaveListsArr = [];
        $scope.leaveDaysTotalEdit = '';
        $scope.leaveTimesTotalEdit = '';
        $scope.leaveNumsEdit = '';
        $scope.everyLeaveDaysEdit = '';
        if($scope.theData.leave_total_days != '' && $scope.theData.leave_total_days != null){
            $scope.leaveDaysFlag1 = false;
            $scope.leaveNumsFlag = true;
            $scope.leaveDaysTotalEdit = parseInt($scope.theData.leave_total_days);
        }
        if($scope.theData.leave_least_Days != '' && $scope.theData.leave_least_Days != null){
            $scope.leaveDaysFlag1 = false;
            $scope.leaveNumsFlag = true;
            $scope.leaveTimesTotalEdit = parseInt($scope.theData.leave_least_Days);
        }
        if($scope.theData.leave_long_limit != '' && $scope.theData.leave_long_limit != 'null'){
            $scope.leaveNumsFlag = false;
            var data = angular.fromJson($scope.theData.leave_long_limit);
            if(data!= '' && data != null){
                $scope.leaveDaysFlag1 = true;
                data.forEach(function(item,index){
                    var object = {
                        a : parseInt(item[1]),
                        b : parseInt(item[0]),
                    };
                    $scope.leaveListsArr.push(object);
                });
            }else{
                $scope.leaveDaysFlag1 = false;
                $scope.leaveNumsFlag = true;
                var object = {
                    a : '',
                    b : '',
                };
                $scope.leaveListsArr.push(object);
            }

        }else{
            $scope.leaveDaysFlag1 = false;

            var object = {
                a : '',
                b : '',
            };
            $scope.leaveListsArr.push(object);
        }
        $('#leaveEditModal').modal('show');
        $scope.addLeaveEditHtml();
    }
    //请假次数不可填写
    $scope.setLeaveNumDisabled = function () {
        var $leaveDays =  $('#leaveDaysTotalEdit').val();
        var $leaveTimes =  $('#leaveTimesTotalEdit').val();
        if($leaveDays =='' && $leaveTimes == ''){
            $scope.leaveNumsFlag = false;
            var $leaveLists = $('.leaveNumBoxLists').find('input');
            $leaveLists.each(function(){
                var $value = $(this).val();
                $(this).attr('disabled',false);
            });
        }else{
            $scope.leaveNumsFlag = true;
            var $leaveLists = $('.leaveNumBoxLists').find('input');
            $leaveLists.each(function(){
                var $value = $(this).val();
                $(this).attr('disabled',true);
            });
        }
    };
    //请假天数不可填写
    $scope.seLeaveDaysDisabled = function () {
        var $leaveLists = $('.leaveNumBoxLists').find('input');
        $scope.leaveDaysFlag1 = false;
        $leaveLists.each(function(){
            var $value = $(this).val();
            if($value != ''){
                $scope.leaveDaysFlag1 = true;
            }
        });
    };
    //获取请假次数数组
    $scope.getLeaveEditArrList = function(){
        $scope.leaveDaysEditArr = [];
        var $leaveLists = $('.leaveNumBoxLists').children('.leaveNumBox');
        var $leaveDays =  $('#leaveDaysTotalEdit').val();
        var $leaveTimes =  $('#leaveTimesTotalEdit').val();
        if( $leaveDays =='' && $leaveTimes == ''){
            $leaveLists.each(function(index,item){
                var $arr = [];
                var $num = $(this).find('input[name="times"]').val();
                var $day = $(this).find('input[name="days"]').val();
                if($num != '' && $day != ''){
                    $arr.push($day);
                    $arr.push($num);
                    $scope.leaveDaysEditArr.push($arr);
                }
            });
        }
    }

    $scope.studentLeaveArr  = function () {
        $scope.studentLeaveDaysType    = [];
    };
    $scope.getStudentLeaveDays = function () {
        var div = $('div#studentLeaveDay');
        var $wDay = div.find("input[name='winterNum']").val();
        var $sDay = div.find("input[name='summerNum']").val();
        var $input =  div.find('input');
        $scope.studentLeaveArr();
        $scope.studentLearve = [];
        if($wDay != '' &&  $sDay != ''){
            $input.each(function(){
                var $day = $(this).val();
                var $time = 1;
                $scope.studentLearve.push($day);
                $scope.studentLearve.push($time);
                $scope.studentLeaveDaysType.push($scope.studentLearve);
                $scope.studentLearve = [];
            });
        }
    };
    //判断每次请假天数不能大于总天数
    /*$('#leaveTimesTotalEdit').on('blur',function(){
        console.log($('#leaveDaysTotalEdit').val(),$('#leaveTimesTotalEdit').val())
        if(parseInt($('#leaveDaysTotalEdit').val()) < parseInt($('#leaveTimesTotalEdit').val())){
            Message.warning('每次最低天数不能大于请假总天数');
            $('#leaveTimesTotalEdit').val('');
        }
    });*/

    //请假修改完成
    $scope.leaveEditComplete = function(){
        $scope.getLeaveEditArrList();
        $scope.getStudentLeaveDays();
        var assignmentEditData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'leave',//场景
            cardId    :$scope.theData.id,//卡种id
            leaveTotalDays:$scope.leaveDaysTotalEdit =='' || $scope.leaveDaysTotalEdit == undefined ? null:$scope.leaveDaysTotalEdit,//请假总天数
            onceLeastDays:$scope.leaveTimesTotalEdit =='' || $scope.leaveTimesTotalEdit == undefined ? null:$scope.leaveTimesTotalEdit,//每次最低天数
            leaveDaysArr  :$scope.leaveDaysEditArr =='' || $scope.leaveDaysEditArr == undefined ? null:$scope.leaveDaysEditArr,//请假次数，每次请假天数（数组）
            studentLeaveDaysType: $scope.studentLeaveDaysType =='' || $scope.studentLeaveDaysType == undefined ? null:$scope.studentLeaveDaysType//学生请假(暑假天数,次数,寒假天数,次数)
         };
        var $leaveDays =  $('#leaveDaysTotalEdit').val();
        var $leaveTimes =  $('#leaveTimesTotalEdit').val();
        if(($leaveDays =='' && $leaveTimes != '')||($leaveTimes =='' && $leaveDays != '')){
            Message.warning('请输入完整的请假天数');
            return;
        }else if($scope.leaveDaysTotalEdit < $scope.leaveTimesTotalEdit){
            //判断每次请假天数不能大于总天数
            Message.warning('每次最低天数不能大于请假总天数');
            $('#leaveTimesTotalEdit').val('');
            return;
        }
        $scope.leaveEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(assignmentEditData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#leaveEditModal').modal('hide');
            }else{
                $scope.leaveEditCompleteFlag = false;
                angular.forEach(response.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        });
    }

    //获取所有的合同
    $scope.getAllContract        = function () {
        $http.get('/contract/get-deal?type='+'1').then(function (result) {
            if(result.data != undefined && result.data != ""){
                $scope.dealData   = result.data;
                $scope.dealStauts = true;
                $.loading.hide();
            }else{
                $scope.dealData   = '暂无数据';
                $scope.dealStauts = false;
                $.loading.hide();
            }
        });
    };
    //获取合同内容
    $scope.getDealId = function (data) {
        $scope.dealIds = data;
        $http.get('/contract/get-deal-one?id='+$scope.dealIds).then(function (result) {
            $scope.dataDeal = result.data.data;
        });
    };

    //合同修改
    $scope.contractEdit = function(){
        $("#contractSelect").select2();
        $('#contractSelect').val("");
        $scope.dealId = '';
        $('#select2-contractSelect-container').text('请选择合同');
        $scope.dataDeal = '';
        $scope.contractEditCompleteFlag = false;
        $.loading.show();
        $scope.getAllContract();
        if($scope.theData.deal != null && $scope.theData.deal != ''){
            $('#contractEditModal').modal('show');
            // $("#CustomerType").select2("val", $scope.theData.id);
            $('#select2-contractSelect-container').text($scope.theData.deal.name);
            $scope.dealId = $scope.theData.deal.id;
            $scope.getDealId($scope.theData.deal.id);
        }else{
            $scope.dealId = '';
            $('#contractEditModal').modal('show');
        }

    }

    //合同修改完成
    $scope.contractEditComplete = function(){
        var contractEditData = {
            _csrf_backend:$('#_csrf').val(),
            scenarios :'deal',//场景
            cardId    :$scope.theData.id,//卡种id
            deal:$scope.dealId =='' || $scope.dealId == undefined ? null:$scope.dealId,//合同id
          }
        if($scope.dealId  == '' || $scope.dealId == null || $scope.dealId ==undefined){
            Message.warning('请选择合同');
            return;
        }

        $scope.contractEditCompleteFlag = true;
        $http({
            method: 'POST',
            url: '/member-card/card-edit',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param(contractEditData)
        }).then(function(response){
            if(response.data.status == 'success'){
                $scope.getCardDetail($scope.theData.id);
                Message.success(response.data.data);
                $('#contractEditModal').modal('hide');
            }else{
                $scope.contractEditCompleteFlag = false;
                Message.warning(response.data.data);
                // angular.forEach(response.data.data,function (value,key) {
                //     Message.warning(value);
                // });
            }
        });
    }

    // $('#contractEditModal').modal('show');

    //添加模板时调用icheck
    $scope.getICheck = function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    }

    $scope.setStyleCheckbox = function() {
        //（数量不限）点击单选框，输入框添加限制
        $('.inputUnlimited').on('ifChecked',function(){
            $(this).children('input').attr('disabled','disabled');
            $(this).children('input').val('');
        });
//（数量不限）点击单选框，输入框解除限制
        $('.inputUnlimited').on('ifUnchecked',function(){
            $(this).children('input').removeAttr('disabled');
        });
    }
    $scope.selectCheckbox = function() {
        //（数量不限）点击单选框，输入框添加限制
        $('.cardBox').on('ifChecked',function(){
            $(this).children('.selectMemberCard123').attr('disabled','disabled');
            $(this).children('.selectMemberCard123').val('');
            $(this).find('.select2-selection__rendered').text('')
        });
//（数量不限）点击单选框，输入框解除限制
        $('.cardBox').on('ifUnchecked',function(){
            $(this).children('.selectMemberCard123').removeAttr('disabled');
        });
    }

    $scope.getICheck();
    $scope.setStyleCheckbox();
    $scope.selectCheckbox();
    //添加有效期模板
    $scope.addCardValidityEdit = function(){
        $scope.htmlAttr = 'validRenewEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.addCardValidityEditHtml = result.data.html;
        });
    }

    //添加售卖场馆模板
    $scope.addVenueEditHtml  = function () {
        $scope.htmlAttr = 'saleVenueEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.saleEditHtml = result.data.html;
            $scope.addDiscountEditHtml();
            $scope.dateInit();
        });
    };

    //添加售卖折扣模板
    $scope.addDiscountEditHtml  = function (){
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.htmlAttr = 'discountEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.discountEditHtml = result.data.html;
        });
    };
    $scope.addVenueEditHtml();//售卖场馆模板初始调用

    //添加通店模板修改
    $scope.addApplyEditHtml = function () {
        //时间插件启动
        $('.clockpicker').clockpicker()
            .find('input').on('change',function() {
        });
        $('.applySelectVenue').select2();
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.htmlAttr = 'applyVenueEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.applyEditHtml = result.data.html;
        });
    };
    //初始调用通店场馆模板
    $scope.addApplyEditHtml();
    //添加课程模板
    $scope.addLeagueClassEditHtml = function () {
        $.loading.show();
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.htmlAttr = 'addLeagueClassEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.leagueClassEditHtml = result.data.html;
            $('.leagueCourseList').select2();
            $scope.groupCourse();
            $timeout(function(){
                $.loading.hide();
            },100)
        });
    };
    $scope.addLeagueClassEditHtml();

    //添加赠品模板
    $scope.addDonationEditHtml = function () {
        $scope.getICheck();
        $scope.setStyleCheckbox();
        $scope.selectDonation();
        $scope.htmlAttr = 'donationEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.donationEditHtml = result.data.html;
        });
    };

    //添加假期模板
    $scope.addLeaveEditHtml = function () {
        $scope.htmlAttr = 'leaveEdit';
        $scope.num  = $scope.num + 1;
        $http.get('/member-card/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.leaveEditHtml = result.data.html;
        });
    };

}).controller('timeCtrl',function ($scope,$http,$timeout) {
     // $.loading.show();
      $scope.init = function () {
          $timeout(function () {
              $.loading.hide();
          },1000);
      };
     $scope.cardSave = function () {
         $http.get('/member-card/card-save',$.param($scope.timeCard)).then(function (result) {
         });
     };
      $scope.init();
}).run(function () {

});


