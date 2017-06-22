<?php
/*
Plugin Name: Xwild Framework
Plugin URI: https://xwild-dev.ru
Description: Плагин для расширения функциональности темы ASTM.
Version: 0.1.0
Author: XWILD Team
Author URI: https://xwild-dev.ru
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'XWILD_FW_VERSION', '0.1.0' );
define( 'XWILD_FW_MINIMUM_WP_VERSION', '4.7' );
define( 'XWILD_FW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( XWILD_FW_PLUGIN_DIR . '/classes/xwild_core.php' );
register_activation_hook( __FILE__, array( 'Xwild_core', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Xwild_core', 'plugin_deactivation' ) );


add_action( 'init', array( 'Xwild_core', 'init' ) );
