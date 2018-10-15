/**
 * Created by DELL on 2017/6/7.
 */
$(function(){
    $('.backBtn').on('click',function(){
        console.log('返回');
        history.go(-1);
        location.reload();
    });
})