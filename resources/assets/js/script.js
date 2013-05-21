$(document).ready(function() {

    /* CLI Interface Form */
    $('#cli-cmd').submit(function(e) {
        e.preventDefault();

        $form = $(this);

        $.ajax({
            type: "POST",
            url: $form.attr('action'),
            data: $form.serialize()
        }).done(function( data, status) {

                response = jQuery.parseJSON(data)

                if (response.code > 0) {
                    for (i=0; i < response.fields.length; i++) {
                        $('#'+response.fields[i]+"_error").html(response.message[i]);
                    }
                } else {
                    document.location.reload(true);
                }

            });
    });
});
