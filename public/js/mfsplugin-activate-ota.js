(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    //console.log(mfsplugin_params);

    $(function() {
        $('a#activate-ota-btn').on('click', function() {
            var el = $(this);

            console.log(mfsplugin_params);
            var data = new FormData();
            data.append('action', 'activate_ota');
            data.append('message', mfsplugin_params.message);

            $.ajax({
                type: 'post',
                url: mfsplugin_ajax.url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function (response) {
                    console.log("BeforeSend: ", response);
                },
                complete: function (response) {
                    console.log("Complete: ", response);
                    console.log("Status", response.status);
                    var data = response.responseJSON;
                    console.log("Msg: ", data.message);

                    if( data.error ) {
                        alert(mfsplugin_params.error_msg);
                    } 
                    else {
                        alert(mfsplugin_params.success_msg);
                        el.attr('disabled','disabled');
                    }
                },
                success: function (response) {
                    console.log("Success: ", response);
                },
            });
        })
     });

})( jQuery );
