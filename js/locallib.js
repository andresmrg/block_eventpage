$('document').ready(function() {

    // Use default settings.
    try {
        $("#id_starttime, #id_endtime").timePicker({
            timeFormat: 'hh.mm tt',
        });
    } catch(e) {
        // Statements.
        console.log(e);
    }

    // JQuery.
    $(location).attr('href');

    // Pure javascript.
    var pathname = window.location.pathname;
    var param = decodeURIComponent((new RegExp('[?|&]id=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;

    if (pathname == '/blocks/eventpage/view.php') {
        $('.nav-item').appendTo("#languageselector");
    }

});