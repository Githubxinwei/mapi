var thI = $('.thCheck i');
$('.thCheck').click(function(){
    thI.toggleClass("glyphicon glyphicon-chevron-up");
    thI.toggleClass("glyphicon glyphicon-chevron-down");
});
$('.backBtn').click(function(){
    $('.modal').css("display","none");
});

