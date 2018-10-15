/**
 * Created by Administrator on 2017/4/5.
 */
// 柜子状态修改按钮
$('.btnSpan').click(function(){
    $('.spanBox').toggle();
});
// 关闭模态框的按钮
// $('.successBtn').click(function(){
//     $('.modal').css("display","none");
// });
$('.backBtn').click(function(){
    $(this).parents('.modal').css("display","none");
});

