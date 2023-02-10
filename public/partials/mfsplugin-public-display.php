<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/jamainrex
 * @since      1.0.0
 *
 * @package    Mfsplugin
 * @subpackage Mfsplugin/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
    <input type="hidden" id="userid" value="<?php echo $userId; ?>">
    <input type="hidden" id="devid" value="<?php echo $devId; ?>">
	<h5 style="color: white;"><?php echo $uname; ?></h5>
    <h5 style="color: white;">Device ID:</h5>
    <select id="transferdevid">
        <?php 
            foreach($_clientInfos as $uid => $_clientInfo){?>
                <option <?php if($_clientInfo == $devId) echo 'selected'; ?> value="<?php echo $uid.'-'.$_clientInfo;?>">
                    <?php echo $_clientInfo; ?>
                </option>
           <?php }
        ?>
    </select>
    <button id="updateDevIdbtn" class="btn btn-primary">Update</button>
</div>
<script>
	function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
    }
	var updateDevId = function(currentDev, newDev) {
        var data = new FormData();
        data.append('action', 'update_user_device');
        data.append('currentUserDevice', currentDev);
        data.append('newUserDevice', newDev);
        jQuery.ajax({
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
					var data = response.responseJSON;
                    if( data.error ) {
                        Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: data.message
                            });
                    }
                    else{
                        for (let i = 1; i <= 5; i++) {
                            var ctrname = 'chart_' + i;
                            if( devid = getCookie(ctrname) === data.prev ){
                                setCookie(ctrname, data.new, 356);
                            }else if( devid = getCookie(ctrname) === data.new ){
                                setCookie(ctrname, data.prev, 356);
                            }
                          }

                        Swal.fire({
                            title: 'Success',
                            html: data.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                if(data.redirect) {
                                window.location.href = data.redirect;
                                }
                            }
                            });
                    }
                },
                success: function (response) {
                    console.log("Success: ", response);
                },
            });
    }
    jQuery('#updateDevIdbtn').hide();
    jQuery('#transferdevid').on('change',function(e) {
        var userDevId = jQuery('#userid').val() + '-' + jQuery('#devid').val();
		console.log(userDevId);
        var updateDevIdbtn = jQuery('#updateDevIdbtn');
        if( jQuery(this).val() != userDevId )
            updateDevIdbtn.trigger('click');
    });
    jQuery('#transferdevid').trigger('change');
	
	jQuery('#updateDevIdbtn').on('click', function(e) {
        var currentDev = jQuery('#userid').val() + '-' + jQuery('#devid').val();
        var newDev = jQuery('#transferdevid').val();
        updateDevId(currentDev, newDev);
    })
</script>