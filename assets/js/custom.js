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
    $( 'button[type=submit]' ).ladda( 'bind' );
});

function FillAddress(f) {
    if (f.sameAddress.checked == true) {
        f.childAddress.value = f.guardianAddress.value;
        f.childCity.value = f.guardianCity.value;
        f.childZIP.value = f.guardianZIP.value;
    }
}

//<a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreDates" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další období</span><span class="rmv-date">Odebrat toto období</span></a>
