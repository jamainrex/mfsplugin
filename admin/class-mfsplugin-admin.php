<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/jamainrex
 * @since      1.0.0
 *
 * @package    Mfsplugin
 * @subpackage Mfsplugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mfsplugin
 * @subpackage Mfsplugin/admin
 * @author     Jerex Lennon <skyguyverph@gmail.com>
 */
class Mfsplugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mfsplugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mfsplugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mfsplugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mfsplugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mfsplugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mfsplugin-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function is_device_on( $device_id ) {
		global $wpdb;
		$device_status =  $wpdb->get_results( 
			$wpdb->prepare( "SELECT `status` FROM device_config WHERE devid = %s", $device_id )
		);

		return ( !isset( $device_status[0] ) || $device_status[0]->status == 0 ) ? false : true;
	}

	public function activate_ota() {
		global $current_user; 
		get_currentuserinfo();

		$device_id = get_user_meta( $current_user->ID, 'DeviceID' , true );
		
		if( !$device_id ) {
			$response = array(
				'status' => 'error',
				'error' => true,
				'message' => "No User Device Found!"
			);
			echo wp_send_json($response);
			wp_die();
		}
		
		$device_status = $this->is_device_on( $device_id );
		if( !$device_status ) {
			$resp = array(
				'status' => 'offline',
				'error' => true,
				'data' => ['device_id' => $device_id, 'status' => $device_status ],
				'message' => "Device Offline"
			);
			echo wp_send_json($resp);
			wp_die();
		}

		$message = '{ "message" : '. stripslashes( $_REQUEST['message'] ) .' }';
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://staging.mindfullsolutions.site:8080/v1/devices/'. $device_id .'/commands',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $message,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		if( $response == 'Success' || is_null( $response ) || !$response ) {
			$resp = array(
				'status' => 'success',
				'success' => true,
				'data' => $response,
				'message' => "Device has been activated!"
			);
			echo wp_send_json($resp);
			wp_die();
		}


		$respJson = json_decode( $response );
		if( $respJson->status != 200 ) {
			$resp = array(
				'status' => 'error',
				'error' => true,
				'data' => $response,
				'message' => "Error activating the Device!"
			);
			echo wp_send_json($resp);
			wp_die();
		}


		$resp = array(
			'status' => 'success',
			'success' => true,
			'data' => $respJson,
			'message' => "Device has been activated!"
		);
		echo wp_send_json($resp);
		wp_die();
	}

	public function activate_ota_bydevid() {
		
		$device_id = $_REQUEST['devid'];

		if( !$device_id ) {
			$response = array(
				'status' => 'error',
				'error' => true,
				'message' => "No User Device Found!"
			);
			echo wp_send_json($response);
			wp_die();
		}
		
		$device_status = $this->is_device_on( $device_id );
		if( !$device_status ) {
			$resp = array(
				'status' => 'offline',
				'error' => true,
				'data' => ['device_id' => $device_id, 'status' => $device_status ],
				'message' => "Device Offline"
			);
			echo wp_send_json($resp);
			wp_die();
		}

		$message = '{ "message" : '. stripslashes( $_REQUEST['message'] ) .' }';
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://staging.mindfullsolutions.site:8080/v1/devices/'. $device_id .'/commands',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $message,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		if( $response == 'Success' || is_null( $response ) || !$response ) {
			$resp = array(
				'status' => 'success',
				'success' => true,
				'data' => $response,
				'message' => "Device has been activated!"
			);
			echo wp_send_json($resp);
			wp_die();
		}


		$respJson = json_decode( $response );
		if( $respJson->status != 200 ) {
			$resp = array(
				'status' => 'error',
				'error' => true,
				'data' => $response,
				'message' => "Error activating the Device!"
			);
			echo wp_send_json($resp);
			wp_die();
		}


		$resp = array(
			'status' => 'success',
			'success' => true,
			'data' => $respJson,
			'message' => "Device has been activated!"
		);
		echo wp_send_json($resp);
		wp_die();
	}
	
	public function update_user_device() {
		if( !isset($_REQUEST['currentUserDevice']) || !isset($_REQUEST['newUserDevice']) )
			{
				$resp = array(
					'status' => 'error',
					'error' => true,
					'message' => "Error updating User Device ID!"
				);
				echo wp_send_json($_REQUEST);
				wp_die();
			}

		$currentUserDevice = explode("-", $_REQUEST['currentUserDevice']);
		$newUserDevice = explode("-", $_REQUEST['newUserDevice']);
		// Update user Device ID
		update_user_meta( $currentUserDevice[0], 'DeviceID', $newUserDevice[1] );
		update_user_meta( $newUserDevice[0], 'DeviceID', $currentUserDevice[1] );
		
		$url = site_url( '/device-1-settings/?devid='. $newUserDevice[1] );
		$resp = array(
			'status' => 'success',
			'success' => true,
			'message' => "Success!",
			'prev' => $currentUserDevice[1],
			'new' => $newUserDevice[1],
			'redirect' => $url
		);
		echo wp_send_json($resp);
		wp_die();
	}
}