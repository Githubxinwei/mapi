// 下拉选择框不同条件的触发
$(document).ready(function(){
    $('#fenqi').on('change',function () {
        var select =  $('#fenqi option:selected').val();
        // console.log(select);
        if(select == "1" || select == ""|| select == "no"){
            $('.shoufu').hide();
            $('.yueshu').hide();
            $('.monthBox').hide();
            $('.money').hide();
            $('.month').hide();
        }else{
            $('.monthBox').show();
            $('.shoufu').show();
            $('.yueshu').show();
            $('.money').show();
            $('.month').show();
        }
    })
});