/**
 * Created by Administrator on 2017/11/1 0001.
 */

$(function(){
    $(".test h3").click(function(){
        var Test = $(this).next("ul");
        if(Test.css("display")=="none"){
            Test.css("display","block");
        }
        else{
            Test.css("display","none");
        }
    });
});