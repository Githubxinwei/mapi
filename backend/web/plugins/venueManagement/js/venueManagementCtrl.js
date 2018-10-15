$(function () {
    var tdHtml = '<td  class="seatSize  p4 cp seatColumnLists"> <div class="addSeatNum"></div> <div class="addSeatType" data-value=""></div></td>';

    var trHtml = '<tr class="seatRowLists"> <td class="seatSize preAddTdBut tdBut"><span>前</span></td> <div class="addSeatListBox">';
    var trEndHtml = ' </div> <td class="seatSize nextAddTdBut tdBut"><span>后</span></td> </tr>';
   $(document).on('click','td.nextAddTdBut',function () {
        $(this).before(tdHtml);
        return false;
    });
    $(document).on('click','td.preAddTdBut',function () {
        $(this).after(tdHtml);
        return false;
    });
    $(document).on('click','td.bottomAddTdBut',function () {
        var $index = $(this).parent("tr").children("td").index(this);
         $(this).parent('tr').before(trHtml+tdHtml+trEndHtml);
        // $(this).before(tdHtml);
        return false;
    });
    $('#AboutClassCourse').select2();
    $('#AboutClassVenue123').select2();
});

/**
 * Created by DELL on 2017/7/26.
 * 场地管理座位排次和教室场地控制器
 *
 */
angular.module('App').controller('venueManagementCtrl',function($scope,$http,$timeout){
        $.loading.show();
    //座位类型
    $scope.seatAllType = [{id:1,name:'普通'},{id:2,name:'VIP'}]

    //获取所有的场馆
    $scope.getAllVenue = function(){
        $http.get('/rechargeable-card-ctrl/get-venue').then(function(response){
            $scope.allVenueLists = response.data.venue;
            $scope.selectVenueId = response.data.venue[0].id;
            $scope.venueInitId     = response.data.venue[0].id;
            $scope.venueInitName   = response.data.venue[0].name;
            $scope.getAllSeatClassroom();
            $scope.getAllVenueSite();
        });
    }
    $scope.getAllVenue();
    //选择场馆
    $scope.selectVenue = function(id,name){
        $scope.selectVenueId = id;
        $scope.getAllVenueSite();
        $scope.getAllSeatClassroom();
    }
    
    //点击座位排次执行方法
    $scope.clickSeatArrange = function(){
        $.loading.show();
        $scope.getAllSeatClassroom();
        $.loading.hide();
    }



    //获取所有座位排次
    $scope.getAllSeatClassroom = function(){
        $http.get('/venue-management/get-seat?venueId='+$scope.selectVenueId).then(function(response){
            $scope.allSeatDataLists = response.data.data;
            $.loading.hide();
        });
    }

    //输入列数后触发
    $scope.columnsBlur = function(col){
        $scope.addSeatCols = [];
        $scope.addSeatButCol = [];
        if(parseInt(col) > 20){
            Message.warning('最多只能输入20列,请重新输入!');
            $scope.addClassColumns = 20;
        }
        if($scope.addClassRows != ''&& $scope.addClassRows != undefined){
            $scope.allPeople = parseInt($scope.addClassColumns) * parseInt($scope.addClassRows);
            //判断是否为非数字
            if(isNaN($scope.allPeople)){
                $scope.allPeople = 0;
            }
        }
        for(var i =1;i<=parseInt($scope.addClassColumns);i++){
            $scope.addSeatCols.push(i);
        }
        // for(var i =1;i<=$scope.addClassColumns+2;i++){
        //     $scope.addSeatButCol.push(i);
        // }
    }
    //输入行时触发
    $scope.rowsBlur = function(row){
        $scope.addSeatRows = [];
        if($scope.addClassColumns != ''&& $scope.addClassColumns != undefined){
            $scope.allPeople = parseInt($scope.addClassColumns) * parseInt($scope.addClassRows);
            //判断是否为非数字
            if(isNaN($scope.allPeople)){
                $scope.allPeople = 0;
            }
        }
        for(var i =1;i<=parseInt($scope.addClassRows);i++){
            $scope.addSeatRows.push(i);
        }
    }


    //获取点击座位的索引
    $('.addSeatList').on('click',function(){
        $scope.seatIndex = $(this).index();
    })

    //编辑座位
    $scope.editSeat = function(row,col){
        $scope.selectSeatCol = col -1;
        $scope.selectSeatRow = row -1;
        $scope.editSeatNum = '';
        $scope.editSeatType = '';
        $('#editSeatModal').modal('show');
        $scope.getAllSeaTNumArr();
    }
    //获取所有的座位序号数组
    $scope.getAllSeaTNumArr = function(){
        $scope.AllSeatNumArr = [];
        var $seatLists = $('.addSeatNum123').find('.addSeatNum');
        $seatLists.each(function(index,item){
            var $seatNum = $(this).text();
            if($seatNum != ''){
                $scope.AllSeatNumArr.push(parseInt($seatNum));
            }
        })
    }

    
    //编辑座位完成
    $scope.editSeatComplete = function(num,type){
        $scope.getAllSeaTNumArr();
        if(parseInt($scope.AllSeatNumArr.indexOf(parseInt(num))) != -1){
            Message.warning('座位号不能重复输入');
            return;
        }
        if(num =='' || num == undefined){
            Message.warning('请输入座位号');
            return;
        }
        if(type == '' || type == undefined){
            Message.warning('请选择座位类型');
            return;
        }
        //找到被点击的seat
        var allSeat = $('.seatRowLists').find('.seatColumnLists').children('div');
        var clickSeat = $('.seatRowLists').eq($scope.selectSeatRow).find('.seatColumnLists').eq($scope.selectSeatCol).children('div');
        if(type != ''){
            var type= $('#selectEditSeatType').find("option:selected").text();
            clickSeat.children('.addSeatType').attr('data-value',$scope.editSeatType)
            clickSeat.children('.addSeatType').text(type);
        }
        if(num != undefined && num != ''){
            clickSeat.children('.addSeatNum').text(num);
        }
        $('#editSeatModal').modal('hide');
    }

    //删除座位
    $scope.delSeat1 =function(){
        //找到被点击的座位
        var clickSeat = $('.seatRowLists').eq($scope.selectSeatRow).find('.seatColumnLists').eq($scope.selectSeatCol).children('div');
        clickSeat.children('.addSeatType').text('');
        clickSeat.children('.addSeatNum').text('');
        $('#editSeatModal').modal('hide');
    }


    //添加新的座位排次
    $scope.addNewSeatLists = function(){
        $scope.classroomId  = '';
        $scope.addCourseName   = '';
        $scope.addClassRows    = '';
        $scope.addClassColumns   ='';
        $scope.numberArr  = [];
        $scope.typeArr    = [];
        $scope.addSeatRows =  [];
        $scope.addSeatCols = [];
        $scope.allPeople   = 0;
        $('#addNewSeatListsModal').modal('show');
    }

    $scope.preAddTdBut = function(value){
        $('.addSeatListBox').eq(value-1).prepend('1321321')
    }



    //完成座位排次提交数据
    $scope.completeAddSeatLists = function(){
        $scope.numberArr = [];
        $scope.typeArr   = [];
        $scope.rowsArrData   = [];
        $scope.colsArrData   = [];
        for(var i=1;i <=  $scope.addClassRows;i++){
            for(var j=1;j <=  $scope.addClassColumns;j++){
                $scope.rowsArrData.push(i);
                $scope.colsArrData.push(j)
            }
        }
        //获取所有的座位
        var seatLists = $('#addSeatLists').find('.seatSize').children('div')
        $scope.numSeatLength = false;
        seatLists.each(function(i,item){
            var seat = $(this);
            var seatNum = seat.children('.addSeatNum').text();
            var seatType = seat.children('.addSeatType').data('value');
            if(seatNum != ''){
                $scope.numSeatLength = true;
            }
            seatType = seatType !='' && seatType !=undefined?seatType : "",

            $scope.numberArr.push(seatNum);
            $scope.typeArr.push(seatType)
        });
        var getSeatListsData = function(){
            return{
                _csrf_backend     : $('#_csrf').val(),
                venueId            : $scope.selectVenueId,
                roomId             :$scope.classroomId != undefined && $scope.classroomId!= ''? parseInt($scope.classroomId) : null,//教室id
                name               :$scope.addCourseName!= undefined && $scope.addCourseName!= ''? $scope.addCourseName : null ,//座位排次名称
                rows               :$scope.addClassRows!= undefined && $scope.addClassRows!= ''? parseInt($scope.addClassRows) : null ,//排
                columns            :$scope.addClassColumns!= undefined && $scope.addClassColumns!= ''? parseInt($scope.addClassColumns) : null,//列
                rowsArr             :$scope.rowsArrData,//排数组
                columnsArr          :$scope.colsArrData,//列数组
                numberArr           :$scope.numberArr,//座位号数组
                typeArr              :$scope.typeArr//座位类型数组
            }
        }
        if($scope.numSeatLength == false){
            Message.warning('请添加座位');
            return;
        }

        if($scope.classroomId == '' || $scope.classroomId == undefined || $scope.classroomId == null){
            Message.warning('请选择教室');
            return;
        }
        if($scope.addCourseName == '' || $scope.addCourseName == undefined || $scope.addCourseName == null){
            Message.warning('请选输入座位名称');
            return;
        }
        if($scope.addClassRows == '' || $scope.addClassRows == undefined || $scope.addClassRows == null){
            Message.warning('请选择输入排数');
            return;
        }
        if($scope.addClassColumns == '' || $scope.addClassColumns == undefined || $scope.addClassColumns == null){
            Message.warning('请输入列数');
            return;
        }
        $timeout(function(){
            $http({
                url :'/venue-management/save-seat',
                method : 'POST',
                data:$.param(getSeatListsData()),
                headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                if(response.data.status == 'success'){
                    $scope.getAllSeatClassroom();
                    $('#addNewSeatListsModal').modal('hide');
                }else{
                    Message.warning(response.data.data);
                    return;
                }
            })
        },200)
    }
    

    //点击教室场地
    $scope.clickClassRoomSite = function(){
    }
    //点击显示座位排次
    $scope.seatDetailClick =function(object){
        $scope.seatTypeId = parseInt(object.id);
        $http({method:'get',url:'/venue-management/group-class?seatTypeId=' + $scope.seatTypeId}).then(function (data) {
            //未绑定
            if(data.data.status == "error"){
                $scope.editSeatListsBOOL = false;
            }
            //绑定了
            if(data.data.status == "success"){
                $scope.editSeatListsBOOL = true;
            }
        },function(error){
            console.log(error)
            Message.error("系统错误请联系管理人员")
        })
        $scope.clickSeatDetails = object;
        $scope.seatListsDetails();
        $('#seatDetailModal').modal('show');
    }

    //点击座位排次修改按钮
    $scope.editSeatListsClick = function(){
        $scope.CompleteButton = false;
        $('#seatDetailModal').modal('hide');
        $('#amendSeatModal').modal('show');
    }


    //获取座位排次详情
    $scope.seatListsDetails = function(){
        $scope.seatDetailArr = [];
        $scope.seatDetailRows = [];
        $http.get('/venue-management/seat-details?seatTypeId='+$scope.seatTypeId).then(function(response){
            $scope.seatAllDetail      = response.data.data;
            $scope.classroomEditId    = response.data.data.classroom_id;
            $scope.classroomEditName  = response.data.data.name;
            $scope.classroomEditCols  = response.data.data.total_columns;
            $scope.classroomEditRows  = response.data.data.total_rows;
            var rows = response.data.data.total_rows;
            for(var i=1;i<=rows;i++){
                $scope.seatDetailRows.push(i)
            }
            var cols = response.data.data.total_columns;
            var seatLists = response.data.data.seat;
            $scope.seatAllDetailSeat  = response.data.data.seat;
        });
    }
    //获取所有的座位排次数组
    $scope.getEditSeatNumArr = function(){
        $scope.AllEditSeatNumArr = [];
        var $seatLists = $('.editSeatNumber132').find('.addSeatNum');
        $seatLists.each(function(index,item){
            var $seatNum = $(this).text();
            if($seatNum != ''){
                $scope.AllEditSeatNumArr.push(parseInt($seatNum));
            }
        })
    }

    //修改模态框中的编辑座位
    $scope.editModalSeat = function(row,col){
        $scope.selectEditSeatCol = col -1;
        $scope.selectEditSeatRow = row -1;
        var clickSeat = $('.seatDetailModalLists').eq($scope.selectEditSeatRow).find('.seatModalEditBox').eq($scope.selectEditSeatCol).children('div');


        var type = clickSeat.children('.addSeatType').attr('data-value');
        if(parseInt(type) == 0){
            type= ''
            $scope.selectedSeatTypeId = '';
        }else{
            $scope.selectedSeatTypeId = type;
        }

        $scope.editEditSeatNum = '';
        $scope.editEditSeatType = '';
        $('#editModalSeatModal').modal('show');
        $timeout(function(){
            $scope.getEditSeatNumArr();
            var num  = clickSeat.children('.addSeatNum').text();
            $('#seatNum123').val(num);
            // $scope.AllEditSeatNumArr.remove(parseInt(num));
            $scope.AllEditSeatNumArr.splice($.inArray(parseInt(num), $scope.AllEditSeatNumArr), 1);
        },100);
    }
    //编辑修改座位完成
    $scope.editEditSeatComplete = function(num,type){
        var num = $('#seatNum123').val();
        var SeatType1231 = $('#editModalType123').val();
        if(parseInt($scope.AllEditSeatNumArr.indexOf(parseInt(num))) != -1){
            Message.warning('座位号不能重复输入');
            return;
        }
        if(SeatType1231 == '' || SeatType1231 == undefined || SeatType1231 == null){
            Message.warning('请选择座位类别');
            return;
        }
        if(num == '' || num == undefined || num == null){
            Message.warning('请输入座位号');
            return;
        }else{
            //找到被点击的seat
            var clickSeat = $('.seatDetailModalLists').eq($scope.selectEditSeatRow).find('.seatModalEditBox').eq($scope.selectEditSeatCol).children('div');
            clickSeat.parents('.seatModalEditBox').removeClass('tdBut');
            clickSeat.parents('.seatModalEditBox').removeClass('tdBut1');
            clickSeat.find('.addFlag123').addClass('disNone');
            clickSeat.find('.addFlag').addClass('disNone');
            var selectedType = $('#editModalType123').find("option:selected").val();
            if(selectedType != ''){
                var type1= $('#editModalType123').find("option:selected").text();
                clickSeat.children('.addSeatType').attr('data-value',$scope.editEditSeatType)
                clickSeat.children('.addSeatType').text(type1);
            }else{
                var type1= $('#editModalType123').find("option:selected").text();
                clickSeat.children('.addSeatType').attr('data-value','')
                clickSeat.children('.addSeatType').text('');
            }
            clickSeat.children('.addSeatNum').text(num);
            $('#editModalSeatModal').modal('hide');
        }
    }

    //删除编辑座位
    $scope.delModalSeatComplete = function(){
        var clickSeat = $('.seatDetailModalLists').eq($scope.selectEditSeatRow).find('.seatModalEditBox').eq($scope.selectEditSeatCol).children('div');
        clickSeat.parents('.seatModalEditBox').addClass('tdBut');
        clickSeat.parents('.seatModalEditBox').addClass('tdBut1');
        clickSeat.find('.addFlag123').removeClass('disNone');
        clickSeat.find('.addFlag').removeClass('disNone');
        clickSeat.children('.addSeatType').text('');
        clickSeat.children('.addSeatType').attr('data-value','0');
        clickSeat.children('.addSeatNum').text('');
        $('#editModalSeatModal').modal('hide');
    }


    //点击修改座位排次中删除按钮
    $scope.deleteCourseType = function(){
        Sweety.remove({
            url: "/venue-management/del-seat?seatTypeId=" + $scope.seatTypeId,
            http: $http,
            title: '确定要删除吗?',
            text: '删除后所有信息无法恢复',
            confirmButtonText: '确定',
            data: {
                action: 'unbind'
            }
        }, function () {
            $scope.getAllSeatClassroom();
            $('#amendSeatModal').modal('hide');
        },function () {

        },true);
    }
    //点击修改座位中的完成
    // /venue-management/update-seat
    $scope.completeEdit = function(){
        $scope.CompleteButton = true;
        $scope.numberEditArr = [];
        $scope.typeEditArr   = [];
        $scope.rowsEditArrData   = [];
        $scope.colsEditArrData   = [];
        for(var i=1;i <=  $scope.classroomEditRows;i++){
            for(var j=1;j <=  $scope.classroomEditCols;j++){
                $scope.rowsEditArrData.push(i);
                $scope.colsEditArrData.push(j)
            }
        }
        //获取所有的座位
        var seatLists = $('#editSeatLists123').find('.seatModalEditBox ').children('div');
        seatLists.each(function(i,item){
            var seat = $(this);
            var seatNum = seat.children('.addSeatNum').text();
            var seatType = seat.children('.addSeatType').attr('data-value');
            $scope.numberEditArr.push(seatNum);
            $scope.typeEditArr.push(seatType);
        });

        var editData = function(){
            return {
                _csrf_backend     : $('#_csrf').val(),
                venueId           :$scope.selectVenueId,
                seatTypeId        :$scope.seatTypeId,//座位排次id
                roomIdUp          :$scope.classroomEditId,//教室id
                nameUp            :$scope.classroomEditName,//座位排次名称
                rowsUp            :$scope.classroomEditRows,//排
                columnsUp         :$scope.classroomEditCols,//列
                rowsArrUp         :$scope.rowsEditArrData,//排数组
                columnsArrUp      :$scope.colsEditArrData,//列数组
                numberArrUp        :$scope.numberEditArr,//座位号数组
                typeArrUp          :$scope.typeEditArr//座位类型数组
            }
        };
        if($scope.classroomEditId == "" || $scope.classroomEditId == null || $scope.classroomEditId == undefined){
            Message.warning('请选择教室名称');
            return false;
        }
        if($scope.classroomEditName == "" || $scope.classroomEditName == null || $scope.classroomEditName == undefined){
            Message.warning('请输入座位名称');
            return false;
        }
        $http({
            url        :'/venue-management/update-seat',
            method     : 'POST',
            data       :  $.param(editData()),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                Message.success(response.data.data);
                $scope.getAllSeatClassroom();
                $('#amendSeatModal').modal('hide');
            }else{
                $scope.CompleteButton = false;
                Message.warning(response.data.data);
                return;
            }
        });
    }
    //点击添加场地
    $scope.addClassroomSite = function(){
        $scope.CompleteSiteButton = false;
        $scope.classroomSquare = '';
        $scope.classroomName   = '';
        $('#addClassroomSite').modal('show');
    }
    //获取所有的场地教室
    $scope.getAllVenueSite = function(){
        $http.get('/new-league/all-classroom?venueId='+$scope.selectVenueId).then(function(response){
            $scope.allVenueSiteLists = response.data;
        });
    }
    //删除场地
    $scope.removeVenueSite = function(id){
            Sweety.remove({
                url: "/venue-management/del-class-room?roomId=" + id,
                http: $http,
                title: '确定要删除吗?',
                text: '场地删除后所有信息无法恢复',
                confirmButtonText: '确定',
                data: {
                    action: 'unbind'
                }
            }, function () {
                $scope.getAllVenueSite();
            },function () {

            },true);
    }

    //新增教室场地完成
    $scope.addClassroomComplete = function(){
        $scope.CompleteSiteButton = true;
        $scope.addClassroomData = function(){
            return {
                _csrf_backend   : $('#_csrf').val(),
                venueId          : $scope.selectVenueId,
                areas            : $scope.classroomSquare   != ''&& $scope.classroomSquare   != undefined? $scope.classroomSquare : null,
                roomName         : $scope.classroomName     != '' && $scope.classroomName   != undefined ? $scope.classroomName    : null,
            }
        }
        if($scope.classroomName == ''){
            Message.warning('请输入教室名称');
            return;
        }
        $http({
            url        :'/venue-management/save-class-room',
            method     : 'POST',
            data       :  $.param($scope.addClassroomData()),
            headers    : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data.status == 'success'){
                Message.success(response.data.data);
                $scope.getAllVenueSite();
                $('#addClassroomSite').modal('hide');
            }else{
                $scope.CompleteSiteButton = false;
                angular.forEach(result.data.data,function (value,key) {
                    Message.warning(value);
                });
            }
        });
    }
});