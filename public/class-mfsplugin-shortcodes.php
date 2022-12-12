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
        ), $atts));

        wp_enqueue_script( MFSPLUGIN_PLUGIN_NAME . '-activate-ota-js', plugin_dir_url( __FILE__ ) . 'js/mfsplugin-activate-ota.js', array( 'jquery' ), MFSPLUGIN_VERSION, false );

        ob_start();
      
        require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcode-activate-ota-button.php';
        return ob_get_clean();
    }
}