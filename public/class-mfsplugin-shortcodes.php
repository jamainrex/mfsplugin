<?php

class Mfsplugin_Shortcodes
{
    public static function register_shortcodes()
    {
        add_shortcode( 'mfs-activate-ota', [ __CLASS__, 'activate_ota_sc' ] );
		add_shortcode( 'mfs-activate-ota-bydevid', [ __CLASS__, 'activate_ota_bydevid_sc' ] );
		add_shortcode( 'mfs-user-device', [ __CLASS__, 'user_device_info' ] );
		add_shortcode( 'mfs-user-filter', [ __CLASS__, 'user_filter' ] );
		add_shortcode( 'mfs-user-settings', [ __CLASS__, 'user_device_settings' ] );
    }
	
	public static function user_device_settings( $atts ) {
        global $current_user, $wpdb;
        get_currentuserinfo();
		$uname = $_REQUEST['uname'];
        $devId = $_REQUEST['devid'];
		
		$practID = get_user_meta( $current_user->ID, 'UserID' , true );
		$clientsIDs =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT user_id FROM wp_usermeta WHERE meta_key = 'PractitionerID' AND meta_value = %s", $practID ), ARRAY_A
		);

        $_clientIDs = [];
        foreach( $clientsIDs as $clientID ) {
            $_clientIDs[] = $clientID['user_id'];
        }

		$userIds =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT user_id FROM wp_usermeta WHERE meta_key = 'DeviceID' AND meta_value = %s", $devId ), ARRAY_A
		);
		$userId = 0;
		foreach($userIds as $userid) {
			if( in_array( $userid['user_id'], $_clientIDs) ){
				$userId = $userid['user_id'];
				break;
			}
		}
		
		$wordcamp_id_placeholders = implode( ', ', array_fill( 0, count( $_clientIDs ), '%d' ) );
		$clientInfos =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT u.ID, u.display_name AS `names`, um.meta_value AS `dev_id` FROM wp_users AS u LEFT JOIN wp_usermeta AS um ON u.ID = um.user_id WHERE u.ID IN ($wordcamp_id_placeholders) AND um.meta_key = 'DeviceID'", $_clientIDs ), ARRAY_A
		);
		$column = 'dev_id';
        $_clientInfos = [];
		$uname = '';
        foreach( $clientInfos as $clientInfo ) {
           $_clientInfos[$clientInfo['ID']] = $clientInfo[$column];
			if( $userId == $clientInfo['ID'] )
				$uname = $clientInfo['names'];
        }
		//echo $devId;
        //echo '<pre>'.print_r(['user_id'=>$userId, 'clients'=>$_clientInfos], true).'</pre>';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/mfsplugin-public-display.php';
        return ob_get_clean();
    }
	
	public static function activate_ota_bydevid_sc( $atts )
    {
        global $current_user;

        extract(shortcode_atts(array(
            'btn_text' => 'version 5.0',
            'message' => '',
            'success_msg' => 'Success Message',
            'error_msg' => 'Error Message',
			'offline_msg' => 'Error Device Offline',
        ), $atts));

        $fid = wp_unique_id('ota');

        wp_enqueue_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', plugin_dir_url( __FILE__ ) . 'js/mfsplugin-activate-ota.js', array( 'jquery' ), MFSPLUGIN_VERSION, false );
        wp_localize_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', 'mfsplugin_params' , array(
			'message' => $message,
            'success_msg' => $success_msg,
            'error_msg' => $error_msg,
			'offline_msg' => $offline_msg
		) );
        ob_start();

        //echo '<pre>'.print_r($message, true).'</pre>';
        $device_id = $_REQUEST['devid'];
        $btn = '2';
        if( $device_id ){
            echo '<input id="'.$fid .'_devid" value="'.$device_id.'" type="hidden">';
            require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcode-activate-ota-button.php';
        }
        return ob_get_clean();
    }
    
    public static function activate_ota_sc( $atts )
    {
        global $current_user;

        extract(shortcode_atts(array(
            'btn_text' => 'version 5.0',
            'message' => '',
            'success_msg' => 'Success Message',
            'error_msg' => 'Error Message',
			'offline_msg' => 'Error Device Offline',
        ), $atts));

        $fid = wp_unique_id('ota');

        wp_enqueue_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', plugin_dir_url( __FILE__ ) . 'js/mfsplugin-activate-ota.js', array( 'jquery' ), MFSPLUGIN_VERSION, false );
        wp_localize_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', 'mfsplugin_params' , array(
			'message' => $message,
            'success_msg' => $success_msg,
            'error_msg' => $error_msg,
			'offline_msg' => $offline_msg
		) );
        ob_start();

        //echo '<pre>'.print_r($message, true).'</pre>';
      $btn = '';
        require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcode-activate-ota-button.php';
        return ob_get_clean();
    }
	
	public static function user_device_info( $atts ) {
        global $current_user, $wpdb;
        get_currentuserinfo();

        extract(shortcode_atts(array(
            'column' => '',
        ), $atts));

		$device_id = get_user_meta( $current_user->ID, 'DeviceID' , true );

        ob_start();

        //$device_id = '7C87CE0A3570';
        $device_info =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT * FROM device_config WHERE devid = %s", $device_id ), ARRAY_A
		);

        if ( $wpdb->last_error ) {
            echo 'wpdb error: ' . $wpdb->last_error;
        }



        if( isset($device_info[0]) ) {
            if( !$column ) {
                echo implode( ", ", $device_info[0] );
            }
            else {
                $devInfo = $device_info[0];
                if( isset( $devInfo[ $column ] ) )
                    echo $devInfo[ $column ];
            }
        }

        return ob_get_clean();
    }
	
	public static function user_filter( $atts ) {
        global $current_user, $wpdb;
        get_currentuserinfo();

        extract(shortcode_atts(array(
            'column' => 'names',
        ), $atts));

		$practID = get_user_meta( $current_user->ID, 'UserID' , true );
        
        $clientsIDs =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT user_id FROM wp_usermeta WHERE meta_key = 'PractitionerID' AND meta_value = %s", $practID ), ARRAY_A
		);

        $_clientIDs = [];
        foreach( $clientsIDs as $clientID ) {
            $_clientIDs[] = $clientID['user_id'];
        }

        $wordcamp_id_placeholders = implode( ', ', array_fill( 0, count( $_clientIDs ), '%d' ) );

        $clientInfos =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT u.display_name AS `names`, um.meta_value AS `dev_id` FROM wp_users AS u LEFT JOIN wp_usermeta AS um ON u.ID = um.user_id WHERE u.ID IN ($wordcamp_id_placeholders) AND um.meta_key = 'DeviceID'", $_clientIDs ), ARRAY_A
		);

        $_clientInfos = [];
		$_clientInfos[] = ($column == 'names') ? "Select User" : " ";
        foreach( $clientInfos as $clientInfo ) {
            $_clientInfos[] = $clientInfo[$column];
        }

        ob_start();

        echo implode( ",", $_clientInfos );
        //echo  '<pre>'.print_r($clientInfos).'</pre>';
        //echo  '<pre>'.print_r($_clientInfos).'</pre>';

        return ob_get_clean();
    }
}