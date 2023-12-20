<?php

/**
 * @link              https://github.com/alvindcaesar/breakdance-global-styles-json-export
 * @since             1.0.0
 *
 * @wordpress-plugin
 * 
 * Plugin Name:       Breakdance Global Styles JSON Export
 * Plugin URI:        https://github.com/alvindcaesar/breakdance-global-styles-json-export
 * Description:       Export and Import your Breakdance global styles to JSON file
 * Version:           1.0.0
 * Author:            Alvind Caesar
 * Author URI:        https://github.com/alvindcaesar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       breakdance-global-styles-json-export
 * Domain Path:       /languages
 */


defined( 'ABSPATH' ) or die;

if ( ! function_exists( 'is_plugin_active' ) ) {

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( ! is_plugin_active( 'breakdance/plugin.php' ) ) {
      return;
    }
}

define( 'BD_IMPORT_SETTINGS_NONCE', 'bd_import_settings' );

if ( ! class_exists( 'BD_Global_Styles_Export' ) ) {

    class BD_Global_Styles_Export {

        private static $instance;

        public static function instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof BD_Global_Styles_Export ) ) {
                self::$instance = new BD_Global_Styles_Export();
                self::$instance->register_hooks();
            }
            return self::$instance;
        }

        public function register_hooks() {
            add_action( 'admin_post_bd_export_settings', [ $this, 'export_settings'] );
            add_action( 'admin_post_bd_import_settings', [ $this, 'import_settings'] );
            add_action(
                'breakdance_register_admin_settings_page_register_tabs',
                [ $this, 'add_tab' ]
            );
        }

        public function add_tab() {
            \Breakdance\Admin\SettingsPage\addTab(
                'Export Global Styles',
                'export_global_styles',
                [ $this, 'tab_page' ],
                99
            );
        }

        public function tab_page() {
            require_once plugin_dir_path(__FILE__) . 'settings-page.php';
        }

        public function export_settings() {
            if ( null == get_option( 'breakdance_global_settings_json_string' ) ) return;

            $export_setting = get_option( 'breakdance_global_settings_json_string' );

            $timestamp = date( 'Y-m-d-H_i_s' );

            header("Content-disposition: attachment; filename=breakdance_global_styles_{$timestamp}.json");

            header('Content-Type: application/json');

            echo $export_setting;
            
            exit;
        }

        public function import_settings() {
            if ( ! wp_verify_nonce( $_POST['_wpnonce'], BD_IMPORT_SETTINGS_NONCE ) || ! current_user_can( 'manage_options' ) ) {
                wp_die( 'Access denied.' );
            }
        
            if ( ! isset($_FILES['json_file']) || ! isset( $_FILES['json_file']['tmp_name'] ) ) {
                wp_die('No valid JSON file provided.');
            }
        
            $json_data = file_get_contents( $_FILES['json_file']['tmp_name'] );
        
            if ( $json_data === false ) {
                wp_die('Error reading JSON file.');
            }
        
            $existing_value = get_option( 'breakdance_global_settings_json_string' );
        
            if ( false !== $existing_value ) {
                update_option( 'breakdance_global_settings_json_string', $json_data );
            } else {
                add_option( 'breakdance_global_settings_json_string', $json_data );
            }
        
            wp_redirect( site_url( '?breakdance=builder&mode=browse&returnUrl=' ) . admin_url( 'admin.php?page=breakdance_settings&&tab=global_styles/' ) );
        
            exit;
        }
    }
}

add_action( 'breakdance_loaded', [ 'BD_Global_Styles_Export', 'instance' ] );

