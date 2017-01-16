<?php
/*
 * Plugin Name: Webpagetest Slack Notify
 * Plugin URI: http://www.brainstormforce.com/
 * Description: Notify webpagetest report to your slack channel on published post or page and upgrade theme or plugin.
 * Author: Brainstorm Force
 * Version: 1.1.0
 * Author URI: http://www.brainstormforce.com/
 * Text Domain: webpagetest-slack-notify
/**
 * Webpagetest Slack Notify
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

if ( ! function_exists( 'add_action' ) ) {
	echo 'Plugin can not do much when called directly.';
	exit;
}

define( 'WSN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WSN_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/**
 * Initiate plugin
 */
require_once( WSN_PLUGIN_DIR . 'settings/admin-page.php' );
$WS_Notify = WS_Notify::instance();