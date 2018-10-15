$(function(){
    // $('.tab-1').click(function(){
    //     $(this).addClass('active');
    //     $('.tab-2').removeClass('active');
    //     $('.tab-3').removeClass('active');
    //     $('.tab-4').removeClass('active');
    //     $('.tab-tb-1').show();
    //     $('.tab-tb-2').hide();
    //     $('.tab-tb-3').hide();
    //     $('.tab-tb-4').hide();
    // });
    // $('.tab-2').click(function(){
    //     $(this).addClass('active');
    //     $('.tab-1').removeClass('active');
    //     $('.tab-3').removeClass('active');
    //     $('.tab-4').removeClass('active');
    //     $('.tab-tb-1').hide();
    //     $('.tab-tb-2').show();
    //     $('.tab-tb-3').hide();
    //     $('.tab-tb-4').hide();
    // });
    // $('.tab-3').click(function(){
    //     $(this).addClass('active');
    //     $('.tab-1').removeClass('active');
    //     $('.tab-2').removeClass('active');
    //     $('.tab-4').removeClass('active');
    //     $('.tab-tb-1').hide();
    //     $('.tab-tb-2').hide();
    //     $('.tab-tb-3').show();
    //     $('.tab-tb-4').hide();
    // });
    // $('.tab-4').click(function(){
    //     $(this).addClass('active');
    //     $('.tab-1').removeClass('active');
    //     $('.tab-2').removeClass('active');
    //     $('.tab-3').removeClass('active');
    //     $('.tab-tb-1').hide();
    //     $('.tab-tb-2').hide();
    //     $('.tab-tb-3').hide();
    //     $('.tab-tb-4').show();
    // });
    $(document).on('click','tr.aboutClassList',function () {
         var $table =  $('#DataTables_Table_0');
         $table.find('tr.aboutClassList').removeClass('backColorBlue');
         $(this).addClass('backColorBlue');
    });

    $('#classVenueChange').select2({
        width:'100%',
    });

    $('#CourseVenueChange').select2({
        width:'100%',
    });

    $('#missVenueChange').select2({
        width:'100%',
    });
});