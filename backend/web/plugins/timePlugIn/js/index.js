$(function(){
    //时间插件启动
    $('.clockpicker').clockpicker()
        .find('input').on('change',function() {
        console.log(this.value);
    });
});
