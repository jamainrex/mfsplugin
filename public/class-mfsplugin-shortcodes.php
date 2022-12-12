<?php

class Mfsplugin_Shortcodes
{
    public static function register_shortcodes()
    {
        add_shortcode( 'mfs-activate-ota', [ __CLASS__, 'activate_ota_sc' ] );
    }
    
    public static function activate_ota_sc( $atts )
    {
        global $current_user;

        extract(shortcode_atts(array(
            'btn_text' => 'version 5.0',
            'message' => '',
            'success_msg' => 'Success Message',
            'error_msg' => 'Error Message'
        ), $atts));

        wp_enqueue_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', plugin_dir_url( __FILE__ ) . 'js/mfsplugin-activate-ota.js', array( 'jquery' ), MFSPLUGIN_VERSION, false );
        wp_localize_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', 'mfsplugin_params', array(
			'message' => $message,
            'success_msg' => $success_msg,
            'error_msg' => $error_msg
		) );
        ob_start();

        //echo '<pre>'.print_r($message, true).'</pre>';
      
        require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcode-activate-ota-button.php';
        return ob_get_clean();
    }
}