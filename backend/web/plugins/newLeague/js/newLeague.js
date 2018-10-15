// box hover js
$(function () {
    $.loading.show();
    $('.titleBox').click(function(){
        $.loading.hide();
        $(this).addClass('active');
        $('.titleBox2').removeClass('active');
        $('.titleBox3').removeClass('active');
        $('#titleBox').show();
        $('#titleBox2').hide();
        $('#titleBox3').hide();
    });
    $('.titleBox2').click(function(){
        $(this).addClass('active');
        $('.titleBox').removeClass('active');
        $('.titleBox3').removeClass('active');
        $('#titleBox2').show();
        $('#titleBox').hide();
        $('#titleBox3').hide();
    })
    $('.titleBox3').click(function(){
        $(this).addClass('active');
        $('.titleBox').removeClass('active');
        $('.titleBox2').removeClass('active');
        $('#titleBox2').hide();
        $('#titleBox').hide();
        $('#titleBox3').show();
    })

});

