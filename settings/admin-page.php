<?php
/**
 * Webpagetest slack initiate
 *
 * @package webpagetest-slack
 * @since 1.0.0
 */

if ( ! class_exists( 'WPT_Slack' ) ) :

	/**
	 * Create class WPT_Slack
	 * admin page
	 */
	class WPT_Slack {

		/**
		 * Declare a static variable instance.
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Initiate class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new WPT_Slack();
				self::$instance->includes();
				self::$instance->hooks();
			}
			return self::$instance;
		}

		/**
		 * Include files required to plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function includes() {

			require_once( WSN_PLUGIN_DIR . 'settings/update-settings.php' );
		}

		/**
		 * Call hooks
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function hooks() {

			add_action( 'admin_menu', array( $this, 'wsn_register_submenu' ) );
		}

		/**
		 * Register submenu
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wsn_register_submenu() {
			$func = __CLASS__ . '::webpagetest_slack_page';
			add_options_page( 'Webpagetest Slack', 'Webpagetest Slack', 'manage_options', 'webpagetest-slack-notification', $func );
		}

		/**
		 * Webpagetest slack admin page
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function webpagetest_slack_page() {

			$test_id = get_option( 'wpt_test_id' );
			$webpage_apikey = $wpttest_tests = $slack_url = $slack_channel = $wpttest_url = '';

			$webpage_apikey	= WPT_Slack_Update::get_config( 'webpage_apikey' );
			$wpttest_url = WPT_Slack_Update::get_config( 'wpttest_url' );
			$wpttest_tests = WPT_Slack_Update::get_config( 'wpttest_tests' );
			$slack_url = WPT_Slack_Update::get_config( 'slack_url' );
			$slack_channel = WPT_Slack_Update::get_config( 'slack_channel' );
		?>
			<div class="wrap">
						<div class='wpt-wrap'>
						<h1 class="wp-heading-inline"><?php _e( 'Webpagetest Slack', 'webpagetest-slack' ); ?></h1>
						<form id="wsn-runnow-form" action="#" method="post" style="display: inline;">
							<input type="submit" id="doaction" class="button action" value='Run Now' style="margin: 10px;" />
							<?php wp_nonce_field( 'wptslackrun', 'webpagetest-slack-run' ); ?>
						</form>
					</div>
				<?php

				if ( empty( $wpttest_url ) ) {
					$wpttest_url = get_site_url();
				}

				if ( isset( $_POST['webpagetest-slack-run'] ) && wp_verify_nonce( $_POST['webpagetest-slack-run'], 'wptslackrun' ) ) {

					if ( isset( $test_id ) && ! empty( $test_id ) ) {	?>
					
					<div id="message" class="updated notice notice-success is-dismissible" style="position: absolute;">
						<p><?php _e( 'Test is running. You will get results on Slack after test complete.', 'webpagetest-slack' ); ?></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'webpagetest-slack' ); ?></span>
						</button>
					</div>
			
				<?php }
				} ?>

			    <form id="wsn-option-form" action="#" method="post" >
			     	
		    		<div class="apmw-config-fields">
						<h4><?php _e( 'Webpagetest API Key', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e( 'Enter your Webpagetest API Key here', 'webpagetest-slack' ); ?></i>
						</p>
					
						<input type="text" class="regular-text" name="webpage-apikey" value="<?php echo $webpage_apikey; ?>" class="regular-text" />
						<p class="admin-help">
							<i><?php _e( 'Need API key? Get one ', 'webpagetest-slack' ); ?><a target='_blank' href="https://www.webpagetest.org/getkey.php"><?php _e( ' here.', 'webpagetest-slack' ); ?></a></i>
						</p>
					</div>

					<div class="apmw-config-fields">
						<h4><?php _e( 'URL to Run Test', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e( 'Enter URL to Run Webpagetest', 'webpagetest-slack' ); ?></i>
						</p>
						<input type="text" class="regular-text" name="wpttest-url" value="<?php echo $wpttest_url; ?>" class="regular-text" />
					</div>

					<div class="apmw-config-fields">
						<h4><?php _e( 'Number of test to Run', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e( 'Enter number of tests to run', 'webpagetest-slack' ); ?></i>
						</p>
						<input type="number" class="regular-text" name="wpttest-tests" value="<?php echo $wpttest_tests; ?>" class="regular-text" />
					</div>

			     	<div class="apmw-config-fields">
						<h4><?php _e( 'Slack Service URL', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e( 'Enter Slack Webhook URL.', 'webpagetest-slack' ); ?></i>
						</p>
						<input type="text" class="regular-text" name="slack-url" value="<?php echo $slack_url; ?>" class="regular-text" />
						<p class="admin-help">
							<i><?php _e( 'Need help to get webhook url? ', 'webpagetest-slack' ); ?><a target='_blank' href="https://slack.com/apps/A0F7XDUAZ-incoming-webhooks"><?php _e( ' click here.', 'webpagetest-slack' ); ?></a></i>
						</p>
					</div>

					<div class="apmw-config-fields">
						<h4><?php _e( 'Channel', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e( 'Enter Slack Channel name. For example : #general ', 'webpagetest-slack' ); ?></i>
						</p>
						<input type="text" class="regular-text" name="slack-channel" value="<?php echo $slack_channel; ?>" class="regular-text" />
					</div>

					<p class="submit">
						<input type="submit" name="wsn-save" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'webpagetest-slack' ); ?>" />
					</p>
					<?php wp_nonce_field( 'wptslack', 'webpagetest-slack' ); ?>
			    </form>
			</div>
		<?php
		}
	}
endif;
