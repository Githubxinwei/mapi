/**
 * Created by suyu on 2017/6/26.
 */

$('.navbar-minimalize').click(function (){
        // 改变侧边栏宽度的点击事件
        $navWidth = $('.navbar-static-side').width();
        // console.log($navWidth);
        if ($navWidth > 100){
            $(".titleBigxingxing").hide();
            $(".nav-header").css("padding-left","0");
        }else{
            $(".titleBigxingxing").show();
        }
});
