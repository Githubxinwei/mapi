$(document).ready(function() {

    $('#reservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });

    $('#reservationLeave').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });
    $('#contract').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });

    $('#noSignReservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });
    $('#addUserReservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });
    $('#birthdayReservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });
    $('#saleMoneyReservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });
    $('#peopleNumberReservation').daterangepicker(null, function(start, end, label) {

        console.log(start.toISOString(), end.toISOString(), label);

    });
});