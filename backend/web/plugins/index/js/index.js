$(function(){
    //菜单点击
    // J_iframe
    $(".J_menuItem").on('click',function(){
        var url = $(this).attr('href');
        $("#J_iframe").attr('src',url);
        return false;
    });
    $('.nameImgBox,.userNameDetail').hover(function(){
        $('.userNameDetail').css({'display':'block'});
    },function(){
        $('.userNameDetail').css({'display':'none'});
    });
    var $side = $('#side-menu').children('li').find('ul');
    if($side.length){
        // console.log('$side.length',$side.length);
        $side.each(function () {
            if($(this).children('li').length <= 0){
                $(this).parent().hide();
            }
        });
    }
});