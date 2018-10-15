//次卡添加按时效扣次时
 $(function(){
     //调用inputCheck插件
     function check(){
         $('.i-checks').iCheck({
             checkboxClass: 'icheckbox_square-green',
             radioClass: 'iradio_square-green'
         });
     }
     check();
     //调用开始日期
     $(".datetimeStart").datetimepicker({
         format: 'yyyy-mm-dd',
         minView:'month',
         language: 'zh-CN',
         autoclose:true,
         startDate:'2008-08-08'
     }).on("click",function(){
     $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").val());
 });
//调用结束日期
     $(".datetimeEnd").datetimepicker({
         format: 'yyyy-mm-dd',
         minView:'month',
         language: 'zh-CN',
         autoclose:true,
         startDate:new Date()
     }).on("click",function(){
         $(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").val());
     });
     //判断是否被选择
     if($('#timeAging').hasClass('hover')){
         $('#timeNum').css('display','block');
     }else{
         $('#timeNum').css('display','none');
     }
     // 单选选择按时效添加时，显示时间框
     $('#timeAging').on('ifChecked',function(){
         $('#timeNum').css('display','block');
     });
     //按时添加没有被选择时隐藏时间插件
     $('#timeAging').on('ifUnchecked',function(){
         $('#timeNum').css('display','none');
     });
     //checkBox选中时按钮弹出
     // $('.checkBoxClick').click(function(){
     //
     // });
     var index;
     $('.checkBoxClick').on('ifChecked',function(){
          index = $(this).index('.checkBoxClick');
         $('.btnCheck').css("display","none");
         $('.btnCheck').eq(index).css("display","block");
     });
     $('.checkBoxClick').on('ifUnchecked',function(){
         $('.btnCheck').css("display","none");
         $('.timeSpan').eq(index).text("");
     });

     //选择固定日，特定星期不可选
     // js table选中时js
     $('table td').on('click',function(){
         var $week = angular.element(document.getElementById('week'));
         if(!$(this).parents('table').hasClass('noneClick')){
             var check = $(this).toggleClass('bColor');
             $week.find('input').iCheck('disable');
             $week.find('input').css('left','23px').css('z-index','1000');
         }

         var $table = $('#day');
         var $dayCheck = $table.find('.bColor');
         var $sum = $dayCheck.length - 1;
         if($sum == -1){
             $week.find("input[name='weeksTime']").removeAttr('disabled').css('left', '0').css('z-index', '0');
             $week.find('div.icheckbox_square-green').removeAttr('disabled');
         }
     });

     //选择特定星期，固定日不可选
     var $input = angular.element(document.getElementById('week'));
     var $table = $('#day');
     var input = $input.find('input:checked');
     $input.on('ifChecked',function () {
         $('.clockpicker').find("input[name='dayStart']").attr('disabled', 'disabled');
         $('.clockpicker').find("input[name='dayStart']").attr('');
         $('.clockpicker').find("input[name='dayEnd']").attr('disabled', 'disabled');
         $('.clockpicker').find("input[name='dayEnd']").attr('');
         $table.find('td').css('cursor','not-allowed');
         $table.addClass('noneClick');
     });
     $input.on('ifUnchecked',function () {
         var  $weekChecked = $input.find("div.checked");
         var  $num         = $weekChecked.length - 1;
         if($num == undefined || $num == ""){
             $('.clockpicker').find("input[name='dayStart']").removeAttr('disabled');
             $('.clockpicker').find("input[name='dayEnd']").removeAttr('disabled');
             $table.find('td').css('cursor','pointer');
             $table.removeClass('noneClick');
         }
     });

     //特定星期选择时间插件显示时间 js
     $('.successBtn').click(function(){
         $('.timeSpan').eq(index).css("display","inline-block");
         $('.timeSpan').eq(index).text($('#timeValueStart').val() + '-' + $('#timeValueEnd').val());
         $('.modal').css("display","none");
     });
     inputUnlimited();
     //添加input的按钮js

     $('.addBtn1').click(function(){
         setHeight(0);
         // var $sellVenue = $('#sellVenue');
         // var $box1 =  $('<div/>').addClass('inputBox1');
         // $box1.append('<div class="form-group" style="margin-top: 20px;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;"><span style="color: red;">*</span>选择场馆</label><select class="form-control" style="margin-left: 24px;padding-top: 4px; width: 180px;"><option>请选择售卖场馆</option><option value="1">大上海馆</option><option value="2">大卫城馆</option><option value="3">大学路馆</option></select></div><div class="form-group inputUnlimited" style="margin-top: 20px;margin-left: 64px;"><label for="exampleInputName3" style="font-size: 16px;font-weight: normal;color: #333;display: inline-block"><span style="color: red;">*</span>售卖张数</label><input type="number" class="form-control" id="exampleInputName3" name="sheetsNum" style="margin-left: 23px;padding-top: 4px; width: 180px;display: inline-block;" placeholder="0张"></div><div class="form-group" style="margin-top: 20px;"><label for="exampleInputName3"style="font-size: 16px;font-weight: normal;color: #333;display: inline-block;margin-left: 66px;"><span style="color: red;">*</span>售卖日期</label><div class="input-daterange input-group cp"style="display: inline-block;"><input type="text" id="" class="input-sm form-control datetimeStart" name="sellStartTime" style="width: 82px;text-align: left;font-size: 14px;cursor: pointer;margin-left: 6px;" placeholder="起始日期"  style="width: 100px;text-align:left;font-size: 13px;cursor: pointer;" ng-model="startTime"><input type="text"id=""class="input-sm form-control datetimeEnd"name="sellEndTime"placeholder="结束日期"style="width: 82px;text-align: left;font-size: 14px;cursor: pointer;margin-left: 4px;"ng-model="endTime"></div></div>');
         // $sellVenue.append($box1);
         check();
         inputUnlimited();
         var i ;
         $(".datetimeStart").datetimepicker({
             format: 'yyyy-mm-dd',
             minView:'month',
             language: 'zh-CN',
             autoclose:true,
             startDate:'2008-08-08'
         }).on("click",function(){
             i = $(this).index('.datetimeStart');
             $(".datetimeStart").datetimepicker("setEndDate",$(".datetimeEnd").eq(i).val());
         });
         $(".datetimeEnd").datetimepicker({
             format: 'yyyy-mm-dd',
             minView:'month',
             language: 'zh-CN',
             autoclose:true,
             startDate:new Date()
         }).on("click",function(){
             $(".datetimeEnd").datetimepicker("setStartDate",$(".datetimeStart").eq(i).val());
         });
     });
     $('#addDiscount123').on('click',function(){
         check();
         setHeight(0);
         inputUnlimited();
     });
     $('#addCardValidityBtn').on('click',function(){
         check();
         setHeight(0);
         inputUnlimited();
     })

     //第二个按钮
     $('.addBtn2').click(function(){
         setHeight(0);
         // var $currencyVenue = $('#venue');
         // var $box2 =  $('<div/>').addClass('inputBox2');
         // $box2.append('<div><div class="form-group" style="margin-top: 20px;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;"><span style="color: red;">*</span>适用场馆</label><select class="form-control" style="margin-left: 24px;padding-top: 4px; width: 180px;"><option>请选择场馆</option><option value="1">大上海馆</option><option value="2">大卫城馆</option><option value="3">大学路馆</option></select></div><div class="form-group inputUnlimited" style="margin-top: 20px;margin-left: 64px;position: relative;"><label for="exampleInputName3" style="font-size: 16px;font-weight: normal;color: #333;display: inline-block"><span style="color: red;">*</span>每月通店限制</label><input type="number" class="form-control" id="exampleInputName3" name="currencyTimes" style="padding-top: 4px; width: 166px;display: inline-block;margin-left: 4px;" placeholder="通店次数"><div class="checkbox i-checks checkbox-inline" style="margin: 7px 5px 0 -58px"><label><input type="checkbox" value="-1" ng-model="applyTimes"> <i></i> 不限</label></div></div></div></div>');
         // $currencyVenue.append($box2);
         //动态添加需要调用下checkBox插件
         check();
         inputUnlimited();
     });
     // 第三个按钮
     $('.addBtn3').click(function(){
         setHeight(1);
         // var $class = $('#course');
         // var $box3 =  $('<div/>').addClass('inputBox3');
         // $box3.append('<div><div class="form-group"style="margin-top: 10px;position: relative;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;">课程名称</label><select class="form-control" style="margin-left: 24px;padding-top: 4px; width: 180px;"><option>请选择课程</option><option value="1">瑜伽</option><option value="2">舞蹈</option></select></div><div class="form-group inputUnlimited" style="margin-top: 10px;margin-left: 64px;position: relative;"><label for="exampleInputName3" style="font-size: 16px;font-weight: normal;color: #333;display: inline-block">每日节数</label><input type="number" name="leagueTimes" class="form-control" id="exampleInputName3" style="margin-left: 24px;padding-top: 4px;padding-right: 80px; width: 180px;display: inline-block;" placeholder="0节"><div class="checkbox i-checks checkbox-inline" style="position: absolute; top: 4px;left: 214px;"><label><input type="checkbox" value="-1" ng-model="applyTimes"><i></i> 不限</div></div></div>');
         // $class.append($box3);
         check();
         inputUnlimited();
     });
     // 第四个按钮
     $('.addBtn4').click(function(){
         setHeight(1);
         // var $server = $('#server');
         // var $box4 =  $('<div/>').addClass('inputBox4');
         // $box4.append('<div><div class="form-group"style="margin-top: 10px;position: relative;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;">课程名称</label><select class="form-control" style="margin-left: 24px;padding-top: 4px; width: 180px;"><option>请选择服务</option><option value="1">A服务</option><option value="2">B服务</option></select></div><div class="form-group inputUnlimited" style="margin-top: 10px;margin-left: 64px;position: relative;"><label for="exampleInputName3" style="font-size: 16px;font-weight: normal;color: #333;display: inline-block">每日数量</label><input type="number" name="leagueTimes" class="form-control" id="exampleInputName3" style="margin-left: 24px;padding-top: 4px;padding-right: 80px; width: 180px;display: inline-block;" placeholder="0"><div class="checkbox i-checks checkbox-inline" style="position: absolute; top: 4px;left: 214px;"><label><input type="checkbox" value="-1" ng-model="applyTimes"><i></i> 不限</div></div></div>');
         // $server.append($box4);
         check();
         inputUnlimited();
     });
     // 第五个按钮
     $('.addBtn5').click(function(){
         setHeight(1);
         // var $goods = $('#shopping');
         // var $box5 =  $('<div/>').addClass('inputBox5');
         // $box5.append('<div><div class="form-group" style="margin-top: 10px;display: inline-block;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;">商品名称</label><select class="form-control" style="margin-left: 24px;padding-top: 4px; width: 180px;display: inline-block;"><option>请选择商品</option><option value="1">A商品</option><option value="2">B商品</option></select></div><div class="form-group inputUnlimited" style="margin-top: 10px;margin-left: 64px;position: relative;display: inline-block;"><label for="exampleInputName3" style="font-size: 16px;font-weight: normal;color: #333;display: inline-block">扣次数量</label><input type="number" name="goodsNum" class="form-control" id="exampleInputName3" style="margin-left: 24px;padding-top: 4px; width: 180px;display: inline-block;" placeholder="0个"></div></div>');
         // $goods.append($box5);
         inputUnlimited();
     });
     // 第六个按钮
     $('.addBtn6').click(function(){
         setHeight(1);
         // var $gift = $('#gift');
         // var $box6 =  $('<div/>').addClass('inputBox6');
         // $box6.append('<div><div class="form-group" style="margin-top: 10px;margin-left:0;display: inline-block;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;">赠品名称</label><select class="form-control" style="margin-left: 24px;padding-top: 4px; width: 180px;display: inline-block;"><option>请选择赠品</option><option value="1">A赠品</option><option value="2">B赠品</option></select></div><div class="form-group inputUnlimited" style="margin-top: 10px;margin-left: 64px;position: relative;display: inline-block;"><label for="exampleInputName3" style="font-size: 16px;font-weight: normal;color: #333;display: inline-block">赠品数量</label><input type="number" name="giftNum" class="form-control" id="exampleInputName3" style="margin-left: 24px;padding-top: 4px; width: 180px;display: inline-block;" placeholder="0个"></div></div>');
         // $gift.append($box6);
         inputUnlimited();
     });
     // 第七个按钮
     $('.addBtn7').click(function(){
         setHeight(2);
         // var $leave = $('#leaveDay');
         // var $box7 =  $('<div/>').addClass('inputBox7');
         // $box7.append('<div><div class="form-group" style="margin-top: 10px;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;">请假次数</label><input type="text" name="leaveTimes" class="form-control" id="exampleInputName2" style="margin-left: 24px;padding-top: 4px; width: 180px;display: inline-block;"placeholder="几次"></div><div class="form-group" style="margin-top: 10px;margin-left: 64px;"><label for="exampleInputName2" style="font-size: 16px;font-weight: normal;color: #333;">每次请假天数</label><input type="number" name="leaveDays" class="form-control" id="exampleInputName2" style="margin-left: 4px;padding-top: 4px; width: 170px;display: inline-block;" placeholder="多少天"></div></div>');
         // $leave.append($box7);
         inputUnlimited();
     });
     function inputUnlimited(){
         //（数量不限）点击单选框，输入框添加限制
         $('.inputUnlimited').on('ifChecked',function(){
             $(this).children('input').attr('disabled','disabled');
             $(this).children('input').val('');
         });
         //（数量不限）点击单选框，输入框解除限制
         $('.inputUnlimited').on('ifUnchecked',function(){
             $(this).children('input').removeAttr('disabled');
         })
     }

     // 设置每一步向导内容的高度，参数是第几步（索引从0开始）
     function setHeight(step){
         var $boxHeight =  $('.formBox1').eq(step).outerHeight();
         $('.wizard > .content').height($boxHeight + 120);
     }
     //表单向导加载完毕后计算第一步内容的高度
     setHeight(0);
     //合同的切换事件
     //初始显示通用合同
     $('#bargainContent>li').eq(0).css('display','block').siblings('#bargainContent>li').css('display','none');
     //点击出来对应相应的合同
     $('#bargain4 ').on('change',function(){

         var i = $('#bargain4 option').index($('#bargain4 option:selected'))
         $('#bargainContent>li').eq(i).css('display','block').siblings('#bargainContent>li').css('display','none');
     })
 });








