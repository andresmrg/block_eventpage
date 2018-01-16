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