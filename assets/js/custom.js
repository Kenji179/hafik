jQuery(document).ready(function ($) {
    $('.fancybox').fancybox();
    $('[data-toggle=mobile]').click(function () {
        $('.nav').toggleClass('active');
    });
    $('.date-time-from').datetimepicker({
        language: 'cs',
        weekStart: 1,
        format: 'dd.mm.yyyy hh:ii',
        autoclose: 1,
        fontAwesome: 1,
        daysOfWeekDisabled: [0, 6],
        todayHighlight: 1
    });

    $('.date-time-to').datetimepicker({
        language: 'cs',
        weekStart: 1,
        format: 'dd.mm.yyyy hh:ii',
        autoclose: 1,
        fontAwesome: 1,
        daysOfWeekDisabled: [0],
        todayHighlight: 1
    });

    $('.date-of-birth').datetimepicker({
        language: 'cs',
        weekStart: 1,
        format: 'dd.mm.yyyy',
        minView: 2,
        startView: 4,
        autoclose: 1,
        fontAwesome: 1,
        todayHighlight: 1
    });
    $('[data-toggle="popover"]').popover();
});

function FillAddress(f) {
    if (f.sameAddress.checked == true) {
        f.addressChild.value = f.addressGuardian.value;
        f.cityChild.value = f.cityGuardian.value;
        f.ZIPChild.value = f.ZIPGuardian.value;
    }
}
