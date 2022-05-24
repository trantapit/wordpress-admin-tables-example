<?php
/**
 *
 * Plugin Name: Sales postings plugin
 * Description: This is add-on to create sales postings
 * Version:     1.0
 * Author:      Bearsthemes - Tap
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: sales-postings
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

// Loading table class
if (!class_exists('WP_List_Table')) {
      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// Define
define( 'SP_DIR', plugin_dir_path( __FILE__ ) );
define( 'SP_URI', plugin_dir_url( __FILE__ ) );

// Include
require( SP_DIR . '/install.php' );
require( SP_DIR . '/uninstall.php' );
require( SP_DIR . '/functions.php' );
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
}

// Starts the plugin.
add_action( 'plugins_loaded', 'sp_settings_submenu_page' );
function sp_settings_submenu_page() {
    $plugin = new SP_Setting_Submenu();
    $plugin->init();
}
