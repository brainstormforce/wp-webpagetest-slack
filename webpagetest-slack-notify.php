<?php
/*
 * Plugin Name: WebPageTest Slack
 * Plugin URI: http://www.brainstormforce.com/
 * Description: Get your website automatically tested for performance / speed and get results on Slack.
 * Author: Brainstorm Force
 * Version: 1.0.0
 * Author URI: http://www.brainstormforce.com/
 * Text Domain: webpagetest-slack
/**
 * WebPageTest Slack
 * Copyright (C) 2017
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

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
$WPT_Slack = WPT_Slack::instance();