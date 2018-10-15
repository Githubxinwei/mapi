/**
 * Created by Administrator on 2017/4/8.
 */
// 点击效果的js
$('.dateBtn1').click(function(){
    $('.dateBtn1').removeClass("btn-white");
    $('.dateBtn1').addClass("btn-primary");
    $('.dateBtn2').removeClass("btn-primary");
    $('.dateBtn2').addClass("btn-white");
    $('.dateBtn3').removeClass("btn-primary");
    $('.dateBtn3').addClass("btn-white");
});
$('.dateBtn2').click(function(){
    $('.dateBtn2').removeClass("btn-white");
    $('.dateBtn2').addClass("btn-primary");
    $('.dateBtn1').removeClass("btn-primary");
    $('.dateBtn1').addClass("btn-white");
    $('.dateBtn3').removeClass("btn-primary");
    $('.dateBtn3').addClass("btn-white");
});
$('.dateBtn3').click(function(){
    $('.dateBtn3').removeClass("btn-white");
    $('.dateBtn3').addClass("btn-primary");
    $('.dateBtn1').removeClass("btn-primary");
    $('.dateBtn1').addClass("btn-white");
    $('.dateBtn2').removeClass("btn-primary");
    $('.dateBtn2').addClass("btn-white");
});
