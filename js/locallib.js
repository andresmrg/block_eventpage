$('document').ready(function() {

    // Use default settings.
    try {
        $("#id_starttime, #id_endtime").timePicker({
            timeFormat: 'hh.mm tt',
        });
    } catch(e) {
        // statements
        console.log(e);
    }

    $('.nav-item').appendTo("#languageselector");
    $('.nav-item').css({
        width: '170px',
        position: 'absolute',
        right: '0',
        border: '1px solid #F4F4F4',
        padding: '10px 20px',
    });

    $('#page-header').hide();
    $('#page-footer').hide();
    $('.navbar-nav').hide();
    $('#page').css('margin-top', '0px !important');
    $('#page.container-fluid').css('padding', '0px !important');
    // $('header').css('visibility', 'hidden');

    // function check_confirm() {
    //     var c = confirm('Are you sure? Once deleted, users won\'t be able to access to this event!');
    //     if (c) {
    //         return true;
    //     }
    //     else {
    //         return false;
    //     }
    // }

    // $('.deleteEventPage').bind('click', function(e) {
    //     e.stopPropagation();
    // });

    // Alert to delete an event page.
    // var x = false;
    // $('.deleteEventPage').click(function(event) {

    // });

    //     console.log(x);
    //     if (x) {
    //         x = false;
    //         return;
    //     }

    //     event.preventDefault();
    //     // This controls the alert messages box.
    //     swal({
    //         title: "Are you sure?",
    //         text: "Once deleted, users won't be able to access to this event!",
    //         type: "warning",
    //         showCancelButton: true,
    //         dangerMode: true,
    //         confirmButtonColor: "red",
    //         confirmButtonText: "Yes, delete it!",
    //         closeOnConfirm: false
    //     },
    //     function(){
    //         x = true;
    //         $(this).trigger('click');
    //         // Message after clicking yes.
    //         swal("Ok!", "The event page has been deleted.", "success");
    //     });

    // });

    // $('#id_themecolor').colorpicker();

});