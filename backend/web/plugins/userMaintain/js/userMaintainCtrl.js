
/**
 * 会员信息 - 会员维护的angularJs加载
 */
// 日期插件的按钮不显示的js
$('.icon-arrow-left').addClass('glyphicon,glyphicon-arrow-left');
$('.icon-arrow-right').addClass('glyphicon,glyphicon-arrow-right');

//窗口的自适应缩放
// $(function() {
//     var r = document.body.offsetWidth / window.screen.availWidth;
//     $(document.body).css("-webkit-transform","scale(" + r + ")");
// });
// $(window).resize(function() {
//     var r = document.body.offsetWidth / window.screen.availWidth;
//     $(document.body).css("-webkit-transform","scale(" + r + ")");
// });

/**
 *后台会员管理 - 会员信息查询 - 访问user控制器的actionMember()
 * @author Wang kang <huanghua@itsports.club>
 * @create 2017/11/27
 */
angular.module('App').controller('maintainCtrl', function ($scope, $http, Upload, $timeout,$interval,$compile) {
    $.loading.show();
    /**
     * maintain数据的获取
     */
    $scope.getMaintainData=function (){
        $http.get('/site/get-auth-venue').then(function (result) {
            // console.log(11,result);
            if(result.data != undefined && result.data != ""){
                $scope.maintainVenue = result.data;
                $scope.VenueStauts = true;
            }else{
                $scope.VenueStauts = false;
                $scope.VenueStautsLength = true;
                $scope.maintainVenue = '暂无数据';
            }
      });
    }
    $scope.getMaintainData();

//    获取上过私教课的会员列表
    $scope.getMaintainUserList = function () {
        // console.log(222222)
        $.loading.show();
        $http.get('/user-maintain/fitness-member-list').then(function(results){
            // console.log(results);
            if(results.data != undefined && results.data != ""){
                $scope.maintainVenueMemberList = results.data.data;
                $scope.maintainVenueFlag = false;
                // console.log(results.data);
                $scope.navList = results.data.pages;


            }else{
                $scope.maintainVenueFlag = true;
                $scope.maintainVenueMemberList = '暂无数据';
                $scope.navList = results.data.pages
            }
            $.loading.hide();
            // console.log(1111,results);
        });
    }
    $scope.getMaintainUserList();

    //分页
    $scope.replacementPages = function (urlPages) {
        $scope.pageInitUrl = urlPages;
        $.loading.show();
        $http.get(urlPages).then(function(results){
            if(results.data != undefined && results.data != ""){
                $scope.maintainVenueMemberList = results.data.data;
                $scope.maintainVenueFlag = false;
                // console.log(results.data.data)
                $scope.navList = results.data.pages;
            }else{
                $scope.maintainVenueFlag = true;
                $scope.maintainVenueMemberList = results.data.data;
                $scope.navList = results.data.pages;
            }
            $.loading.hide();
        });         //再次调用后台查询
    };

    // 搜素按钮事件
    $scope.search = function () {
        $scope.searchData123 = function () {
            return {
                venueId : $scope.venueIdData == undefined ? null : $scope.venueIdData,
                plan    : $scope.planGoal == undefined ? null : $scope.planGoal,
                send    : $scope.sendMessageType == undefined ? null : $scope.sendMessageType,
                keyword : $scope.inputSearch == undefined ? null : $scope.inputSearch
            }
        }
        $http({
            url : '/user-maintain/fitness-member-list?' + $.param($scope.searchData123()),
            method : 'GET'
        }).then(function (results) {
            console.log(results);
            // $scope.maintainVenueMemberList =results.data.data;
            if(results.data.data.length != 0){
                $scope.maintainVenueMemberList = results.data.data;
                $scope.maintainVenueFlag = false;
                // console.log(results.data.data)
                $scope.navList = results.data.pages;
            }else{
                $scope.maintainVenueFlag = true;
                $scope.maintainVenueMemberList = results.data.data;
                $scope.navList = results.data.pages;
            }
            console.log($scope.maintainVenueFlag)
        })

    }
    //回车搜索触发
    $scope.enterSearch = function (){
        var event = window.event || arguments.callee.caller.arguments[0];
        if (event.keyCode == 13) {
            $scope.search();
        }

    };




    $scope.addFitness = function () {
        $scope.potentialMemberButtonFlag = false;
        $('#maintainEditSport').modal('show');
        $scope.strs = "新增健身目标";
        $('#maintainEdit').modal('hide');
    }
    $scope.addDiet = function () {
        $scope.potentialMemberButtonFlag = false;
        $('#maintainEditSportFood').modal('show');
        $scope.strs = "新增饮食计划";
        $('#maintainEdit').modal('hide');
    }


//  新增健身目标
    $scope.sendMessage = function(){

        $scope.sendMessageData = function () {
            return {
                scenarios:'addFitness',
                fitnessGoal:$scope.fitnessGoal,
                content:$scope.fitnessContent,
                _csrf_backend: $('#_csrf').val()
            }
        }
        //判断内容不能为空
        var fitnessGoal = $('#fitnessGoal').val().replace(/(^\s*)|(\s*$)/g, "");
        if( fitnessGoal == ''){
            Message.warning('健身目标不能为空');
            return;
        }

        var fitnessContent = $('#fitnessContent').val().replace(/(^\s*)|(\s*$)/g, "");
        if( fitnessContent == ''){
            Message.warning('内容不能为空');
            return;
        }
        $scope.potentialMemberButtonFlag = true;
        $http({
            method  : 'POST',
            url     : '/user-maintain/edit-fitness-diet',
            data    : $.param($scope.sendMessageData()),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(res){

            if(res.data == '保存成功'){
                $scope.potentialMemberButtonFlag = false;
                $scope.getTemplate();                               //重新渲染
                $('#maintainEditSport').modal('hide');              //关闭模态框
                $scope.fitnessGoal='';                              //清空健身目标
                $scope.fitnessContent='';                           //清空内容

            }else{
                $scope.potentialMemberButtonFlag = false;
                Message.warning('添加失败！');
            }
        })
        $scope.fitnessGoal='';                              //清空健身目标
        $scope.fitnessContent='';                           //清空内容

    }

//   新增饮食计划
    $scope.sendFood = function(){
        
        $scope.sendMessageData = function () {
            return {
                scenarios:'addDiet',
                dietPlan:$scope.dietPlan,
                content:$scope.dietContent,
                _csrf_backend: $('#_csrf').val()
            }
        }

        //判断内容不能为空
        var dietPlan = $('#dietPlan').val().replace(/(^\s*)|(\s*$)/g, "");
        if( dietPlan == ''){
            Message.warning('饮食计划不能为空');
            return;
        }

        var dietContent = $('#dietContent').val().replace(/(^\s*)|(\s*$)/g, "");
        if( dietContent == ''){
            Message.warning('内容不能为空');
            return;
        }

        $scope.potentialMemberButtonFlag = true;
        $http({
            method  : 'POST',
            url     : '/user-maintain/edit-fitness-diet',
            data    : $.param($scope.sendMessageData()),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(res){
            // console.log(res.data)
            if(res.data == '保存成功'){
                $scope.potentialMemberButtonFlag = false;
                $scope.getTemplate();            //重新渲染列表
                $scope.dietPlan='';              //清空饮食计划
                $scope.dietContent='';           //清空内容
                $('#maintainEditSportFood').modal('hide'); //关闭模态框
            }else{
                $scope.potentialMemberButtonFlag = false;
                Message.warning('添加失败！');
            }
        })

    }

//   获取模板列表
    $scope.getTemplate = function(){
        $http.get('/user-maintain/get-fitness-data').then(function(results){
            // console.log(results.data)
            $scope.templates = results.data;
        })

    }
    $scope.getTemplate();
//   删除模板
    $scope.removeTem = function (id) {
        Sweety.remove({
            url              : "/user-maintain/del-fitness-diet?fitDietId="+id,
            http             : $http,
            title            : '确定要删除吗?',
            text             : '删除后模板信息无法恢复',
            confirmButtonText: '确定',
            method:'get',
            data             : {
                fitDietId: 'id'
            }
        }, function () {
            $scope.getTemplate();
        },function(){

        },true);
    };
//   修改健身目标
    $scope.tap = function(type,id,name,content){
        if(type == "1"){
            $('#maintainEditSport').modal('show');
            $scope.strs = "修改健身目标";
            $scope.fitnessGoal = name;
            $scope.fitnessContent = content;
            $scope.sendMessage = function() {
                $scope.amendMessageData = function () {
                    return {
                        scenarios: 'updateFitness',
                        fitnessId:id,
                        fitnessGoal: $scope.fitnessGoal,
                        content: $scope.fitnessContent
                        // _csrf_backend: $('#_csrf').val()
                    }
                }
                //判断内容不能为空
                var fitnessGoal = $('#fitnessGoal').val().replace(/(^\s*)|(\s*$)/g, "");
                if( fitnessGoal == ''){
                    Message.warning('健身目标不能为空');
                    return;
                }

                var fitnessContent = $('#fitnessContent').val().replace(/(^\s*)|(\s*$)/g, "");
                if( fitnessContent == '') {
                    Message.warning('内容不能为空');
                    return;
                }

                $http({
                    method: 'POST',
                    url: '/user-maintain/edit-fitness-diet',
                    data: $.param($scope.amendMessageData()),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (res) {
                    console.log(res)
                    if (res.data == '保存成功') {
                        $scope.getTemplate();            //重新渲染
                        $('#maintainEdit').modal('show');
                        $scope.fitnessGoal='';           //清空健身目标
                        $scope.fitnessContent='';        //清空内容

                        $('#maintainEditSport').modal('hide');

                    } else {
                        Message.warning('添加失败！');
                    }
                })
            }
        }
//   修改饮食计划
        if(type == "2"){
            $('#maintainEditSportFood').modal('show');
            $scope.strs = "修改饮食计划";
            $scope.dietPlan = name;
            $scope.dietContent = content;

            $scope.sendFood = function(){
                $scope.amendMessageData = function () {
                    return {
                        scenarios:'updateDiet',
                        dietId:id,
                        dietPlan:$scope.dietPlan,
                        content:$scope.dietContent,
                        _csrf_backend: $('#_csrf').val()
                    }
                }

                //判断内容不能为空
                var dietPlan = $('#dietPlan').val().replace(/(^\s*)|(\s*$)/g, "");
                if( dietPlan == ''){
                    Message.warning('饮食计划不能为空');
                    return;
                }

                var dietContent = $('#dietContent').val().replace(/(^\s*)|(\s*$)/g, "");
                if( dietContent == ''){
                    Message.warning('内容不能为空');
                    return;
                }

                $http({
                    method  : 'POST',
                    url     : '/user-maintain/edit-fitness-diet',
                    data    : $.param($scope.amendMessageData()),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function(res){
                    if(res.data == '保存成功'){
                        $scope.getTemplate();            //重新渲染
                        $scope.dietPlan='';              //清空饮食计划
                        $scope.dietContent='';           //清空内容
                        $('#maintainEdit').modal('show');
                        $('#maintainEditSportFood').modal('hide');

                    }else{
                        Message.warning('添加失败！');
                    }
                })
            }
        }
        $('#maintainEdit').modal("hide");


    }
// 点击查看详情
    $scope.infoList=function(id){
        $('#maintainDetails').modal('show');
        $scope.memberIdData = id;
        $scope.targetWeight = '';
        $scope.fitnessGoalDataName = '';
        $scope.fitnessGoalDataContent = '';
        $scope.foodPlanDataName = '';
        $scope.foodPlanDataContent = '';
        $http.get('/user-maintain/member-fitness-detail?memberId='+id).then(function(result){
            console.log(result)
            $scope.targetWeight = result.data.target_weight;
            $scope.fitnessGoalDataName =result.data.fitness_id;
            $scope.fitnessGoalDataContent = result.data.fitnessContent;
            $scope.foodPlanDataName =result.data.diet_id;
            $scope.foodPlanDataContent =result.data.dietContent;
        })
        $scope.getGoals();
        $scope.getPlans();
        //获取给某个会员发送的短信
        getMessageData();
        // $scope.getMessages = function(){
        //
        //     $http.get('/user-maintain/get-fitness-message?memberId='+$scope.memberIdData).then(function(res){
        //         console.log(res)
        //         $scope.showMessages = res.data;
        //
        //     })
        // }
        // $scope.getMessages();

    }


    //获取模板里的健身目标
    $scope.getGoals = function () {
        $http.get('/user-maintain/get-fitness-data?type=1&memberId=').then(function (data) {
            console.log(data.data.length);
            $scope.fitnessGoalData1 = data.data;
             // if(data.data.length = '0' ){
             //     console.log(111)
             //     $scope.choseGoal = '请选择目标';
             // }

        })
    }
    //获取模板里的饮食计划
    $scope.getPlans = function () {
        $http.get('/user-maintain/get-fitness-data?type=2&memberId=').then(function (data) {
            // console.log(data);
            $scope.fitnessGoalData2 = data.data;
        })
    }
    //获取选择的健身目标模板展示对应的模板内容
    $scope.showData = function (id) {
        $http.get('/user-maintain/get-fitness-one?fitDietId=' + id).then(function (data) {
            // console.log(data.data.content);
            $scope.fitnessGoalDataContent = data.data.content;
        })
    }
    //获取选择的饮食计划模板展示对应的模板内容
    $scope.showData1 = function (id) {
        $http.get('/user-maintain/get-fitness-one?fitDietId=' + id).then(function (data) {
            // console.log(data.data.content);
            $scope.foodPlanDataContent = data.data.content;
        })
    }

    //保存按钮
    $scope.storage = function () {
        if($scope.fitnessGoalDataName == '' ||$scope.fitnessGoalDataContent == '' || $scope.foodPlanDataName == '' || $scope.foodPlanDataContent == ''){
            Message.warning('内容不能为空')
        }else{
            $scope.maintainDetaildata = function () {
                return {
                    memberId:$scope.memberIdData,
                    weight:$scope.targetWeight,
                    fitnessId: $scope.fitnessGoalDataName,
                    dietId:$scope.foodPlanDataName,
                    fitnessContent:$scope.fitnessGoalDataContent,
                    dietContent:$scope.foodPlanDataContent
                }
            };
            $http({
                url:'/user-maintain/add-member-fitness',
                method:'POST',
                data:$.param($scope.maintainDetaildata()),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (results) {
                console.log(results);
                if(results.data.status == 'success'){
                    Message.success('保存成功');
                    $('#maintainDetails').modal('hide');
                    $scope.getMaintainUserList();
                }else{
                    Message.warning(results.data.data)
                }
            })
        }


    }



   // 给某个会员发送健身目标短信
    $scope.goalMessage = function(){
        // console.log($scope.memberIdData)
        if($scope.fitnessGoalDataName == undefined || $scope.fitnessGoalDataContent == undefined){
            Message.warning('内容不能为空')
        }else{
            $http.get('/user-maintain/send-fitness-diet-one?type=1&memberId='+$scope.memberIdData).then(function(results){
                // console.log(results)

                if(results.data.status == 'success'){
                    Message.success('发送成功');
                    // $scope.getMessages();
                    getMessageData();
                }else{
                    Message.warning(results.data.data)
                }
            })
        }
        //获取给某个会员发送的短信
        getMessageData();

        // $scope.getMessages = function(){
        //
        //     $http.get('/user-maintain/get-fitness-message?memberId='+$scope.memberIdData).then(function(res){
        //         console.log(res)
        //         $scope.showMessages = res.data;
        //     })
        // }
        // $scope.getMessages();


    }

    // 给某个会员发送饮食计划短信
    $scope.planMessage = function(){
        // console.log($scope.memberIdData)
        if($scope.foodPlanDataName == undefined || $scope.foodPlanDataContent == undefined){

            Message.warning('内容不能为空')
        }else{
            $http.get('/user-maintain/send-fitness-diet-one?type=2&memberId='+$scope.memberIdData).then(function(results){
                console.log(results)
                    // Message.success('发送成功');
                    // // $scope.getMessages();
                    //
                    // getMessageData();

                if(results.data.status == 'success'){
                    Message.success('发送成功');
                    // $scope.getMessages();
                    getMessageData();
                }else{
                    Message.warning(results.data.data)
                }

            })
        }
        //获取给某个会员发送的短信
        getMessageData();
        // $scope.getMessages = function(){
        //
        //     $http.get('/user-maintain/get-fitness-message?memberId='+$scope.memberIdData).then(function(res){
        //         console.log(res)
        //         $scope.showMessages = res.data;
        //     })
        // }
        // $scope.getMessages();
    }

 //   发送健身目标短信

    $scope.sendGoal = function(){
        if($scope.fitnessGoalDataName == '' || $scope.fitnessGoalDataContent == ''){
            Message.warning('内容不能为空')
        }else{
            $scope.sendGoalsData = function(){
                return{
                    type    : '1',
                    venueId : $scope.venueIdData == undefined ? null : $scope.venueIdData,
                    plan    : $scope.planGoal == undefined ? null : $scope.planGoal,
                    send    : $scope.sendMessageType == undefined ? null : $scope.sendMessageType,
                    keyword : $scope.inputSearch == undefined ? null : $scope.inputSearch
                }
            }

            $http({
                url:'/user-maintain/send-fitness-diet',
                method:'POST',
                data:$.param($scope.sendGoalsData()),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function(res){
                console.log(res);
                // Message.success('发送成功');
                if(res.data.status == 'success'){
                    Message.success('发送成功');
                   
                }else{
                    Message.warning(res.data.data)
                }
                getMessageData ();
                // location.href('/uesr-maintain');

            })
        }


    }
//   发送饮食计划短信
    $scope.sendPlan = function(){
        if($scope.foodPlanDataName == '' || $scope.foodPlanDataContent == ''){
            Message.warning('内容不能为空')
        }else{
            $scope.sendPlansData = function(){
                return{
                    type    : '2',
                    venueId : $scope.venueIdData == undefined ? null : $scope.venueIdData,
                    plan    : $scope.planGoal == undefined ? null : $scope.planGoal,
                    send    : $scope.sendMessageType == undefined ? null : $scope.sendMessageType,
                    keyword : $scope.inputSearch == undefined ? null : $scope.inputSearch
                }
            }

            $http({
                url : '/user-maintain/send-fitness-diet',
                method:'POST',
                data:$.param($scope.sendPlansData()),
                headers:{ 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function(res){
                // console.log(res);
                if(res.data.status == 'success'){
                    Message.success('发送成功');

                }else{
                    Message.warning(res.data.data)
                }
                getMessageData ();

                // location.href('/uesr-maintain');



            })

        }

    }

//  获取给某个会员发送的短信
    function getMessageData (){
        $scope.getMessages = function(){

            $http.get('/user-maintain/get-fitness-message?memberId='+$scope.memberIdData).then(function(res){
                // console.log(res)
                $scope.showMessages = res.data;
            })
        }
        $scope.getMessages();
    }

});//最外层



