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
        $('a.activate-ota-btn').on('click', function(e) {
            e.preventDefault();
            var el = $(this);
            var fid = el.data('fid');
            //console.log(el);
            //console.log(fid);
            var message = $("#"+fid+'_message').val();
            var success_msg = $("#"+fid+'_success').val();
            var error_msg = $("#"+fid+'_error').val();
			var offline_msg = $("#"+fid+'_offline').val();
            var data = new FormData();
            data.append('action', 'activate_ota');
            data.append('message', message);

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
                        // Device Offline
                        if( data.status == 'offline' ) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Device Offline',
                                html: offline_msg
                            });
                        }
                        else {
                            //alert(error_msg);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: error_msg
                            });
                        }
                    } 
                    else {
                        //alert(success_msg);
                        el.attr('disabled','disabled');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: success_msg
                          })
                    }
                },
                success: function (response) {
                    console.log("Success: ", response);
                },
            });
        })
		
		 $('a.activate-ota-btn2').on('click', function(e) {
            e.preventDefault();
            var el = $(this);
            var fid = el.data('fid');
            //console.log(el);
            //console.log(fid);
            var message = $("#"+fid+'_message').val();
            var success_msg = $("#"+fid+'_success').val();
            var error_msg = $("#"+fid+'_error').val();
			var offline_msg = $("#"+fid+'_offline').val();
            var devid = $("#"+fid+'_devid').val();
            var data = new FormData();
            data.append('action', 'activate_ota_bydevid');
            data.append('message', message);
            data.append('devid', devid);

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
                        // Device Offline
                        if( data.status == 'offline' ) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Device Offline',
                                html: offline_msg
                            });
                        }
                        else {
                            //alert(error_msg);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: error_msg
                            });
                        }
                    } 
                    else {
                        //alert(success_msg);
                        el.attr('disabled','disabled');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: success_msg
                          })
                    }
                },
                success: function (response) {
                    console.log("Success: ", response);
                },
            });
        })
     });

})( jQuery );
