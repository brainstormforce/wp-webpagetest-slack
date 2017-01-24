<?php
/**
 * Plugin Name: WebPageTest Slack
 * Plugin URI: http://www.brainstormforce.com/
 * Description: Get your website automatically tested for performance / speed and get results on Slack.
 * Author: Brainstorm Force
 * Version: 1.0.0
 * Author URI: http://www.brainstormforce.com/
 * Text Domain: webpagetest-slack
 *
 * @package webpagetest-slack
 * @author Brainstorm Force
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define( 'WSN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WSN_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/**
 * Initiate plugin
 */
require_once( WSN_PLUGIN_DIR . 'settings/admin-page.php' );
WPT_Slack::instance();
