<?php
/**
 * Mautic for WordPress initiate
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'WPT_Slack' ) ) :

	class WPT_Slack {

		private static $instance;

		public static function instance() {
			
			if ( ! isset( self::$instance ) ) {
				self::$instance = new WPT_Slack();
				self::$instance->includes();
				self::$instance->hooks();
			}
			return self::$instance;
		}

		public function includes() {

			require_once( WSN_PLUGIN_DIR . 'settings/update-settings.php' );
		}

		public function hooks() {

			add_action( 'admin_menu', array( $this, 'wsn_register_submenu' ) );
		}

		public function wsn_register_submenu() {
			$func = __CLASS__ . '::webpagetest_slack_page';
			add_options_page( 'Webpagetest Slack', 'Webpagetest Slack', 'manage_options', 'webpagetest-slack-notification', $func );
		}

		public static function webpagetest_slack_page() {
		?>
			<div class="wrap">
				<h1><?php _e( 'Webpagetest Slack Notify', 'webpagetest-slack'); ?></h1>
			<?php
			
				$webpage_apikey = $wpttest_url = $wpttest_tests = $slack_url = $slack_channel = '';
				
				$webpage_apikey	= WPT_Slack_Update::get_config('webpage_apikey');
				$wpttest_url = WPT_Slack_Update::get_config('wpttest_url');
				$wpttest_tests = WPT_Slack_Update::get_config('wpttest_tests');
				$slack_url = WPT_Slack_Update::get_config('slack_url');
				$slack_channel = WPT_Slack_Update::get_config('slack_channel');
			?>
			    <form id="wsn-option-form" action="#" method="post">
			     	
		    		<div class="apmw-config-fields">
						<h4><?php _e( 'Webpagetest API Key', 'webpagetest-slack' ); ?></h4>
							<p class="admin-help">
								<i><?php _e('Enter your Webpagetest API Key here', 'webpagetest-slack'); ?></i>
							</p>
						<input type="text" class="regular-text" name="webpage-apikey" value="<?php echo $webpage_apikey; ?>" class="regular-text" />
							<p class="admin-help">
								<i><?php _e('Need API key? Get one ', 'webpagetest-slack'); ?><a target='_blank' href="https://www.webpagetest.org/getkey.php"><?php _e(' here.', 'webpagetest-slack'); ?></a></i>
							</p>
					</div>

					<div class="apmw-config-fields">
						<h4><?php _e( 'URL to Run Test', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e('Enter URL to Run Webpagetest', 'webpagetest-slack'); ?></i>
						</p>
						<input type="text" class="regular-text" name="wpttest-url" value="<?php echo $wpttest_url; ?>" class="regular-text" />
					</div>

					<div class="apmw-config-fields">
						<h4><?php _e( 'Number of test to Run', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e('Enter number of tests to run', 'webpagetest-slack'); ?></i>
						</p>
						<input type="number" class="regular-text" name="wpttest-tests" value="<?php echo $wpttest_tests; ?>" class="regular-text" />
					</div>

			     	<div class="apmw-config-fields">
						<h4><?php _e( 'Slack Service URL', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e('Enter Slack Webhook URL.', 'webpagetest-slack'); ?></i>
						</p>
						<input type="text" class="regular-text" name="slack-url" value="<?php echo $slack_url; ?>" class="regular-text" />
					</div>

					<div class="apmw-config-fields">
						<h4><?php _e( 'Channel', 'webpagetest-slack' ); ?></h4>
						<p class="admin-help">
							<i><?php _e('Enter Slack Channel name. For example : #general ', 'webpagetest-slack'); ?></i>
						</p>
						<input type="text" class="regular-text" name="slack-channel" value="<?php echo $slack_channel; ?>" class="regular-text" />
					</div>

					<p class="submit">
						<input type="submit" name="wsn-save" class="button-primary" value="<?php esc_attr_e( 'Save', 'webpagetest-slack' ); ?>" />
					</p>
					<?php wp_nonce_field( 'wptslack', 'webpagetest-slack' ); ?>
			    </form>
			</div>
		<?php
		}
	}
endif;