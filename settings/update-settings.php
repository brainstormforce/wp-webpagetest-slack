<?php
/**
 * Mautic for WordPress initiate
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'WS_Notify_Update' ) ) {

	class WS_Notify_Update {

		private static $instance;

		public static function instance() {
			
			if ( ! isset( self::$instance ) ) {
				self::$instance = new WS_Notify_Update();
				self::$instance->hooks();
			}
			return self::$instance;
		}

		public function hooks() {
			
			// Register submenu
			
			// Runs when the plugin is upgraded.
			add_action( 'upgrader_process_complete', array( $this, 'wpt_upgrader_process_complete' ), 100 );
			add_action( 'save_post', array( $this, 'wpt_upgrader_process_complete' ), 100 );

 			// Fires once an attachment has been added.
			add_action( 'add_attachment', array( $this, 'wpt_upgrader_process_complete' ), 100 );

			//pre upgrade

			$test_id = get_option( 'wpt_test_id' );
			if ( isset( $test_id ) && ! empty( $test_id ) ) {

				self::get_test_results( $test_id );
			}	
			self::update_settings();
		}

		public function wpt_upgrader_process_complete() {
			
			$test_id = self::fetch_testId();
			update_option( 'wpt_test_id', $test_id );		
		}

		public static function update_settings() {

			if ( isset( $_POST['webpagetest-slack-notify'] ) && wp_verify_nonce( $_POST['webpagetest-slack-notify'], 'wptslack' ) ) {

				$option = array();
				$option['slack_url'] = isset( $_POST['slack-url'] ) ? esc_url( $_POST['slack-url'] ) : '';
				$option['slack_channel'] = isset( $_POST['slack-channel'] ) ? sanitize_text_field( $_POST['slack-channel'] ) : '';
				$option['wpttest_tests'] = isset( $_POST['wpttest-tests'] ) ? sanitize_text_field( $_POST['wpttest-tests'] ) : '';
				$option['webpage_apikey'] = isset( $_POST['webpage-apikey'] ) ? esc_attr( $_POST['webpage-apikey'] ) : '';
				$option['wpttest_url'] = isset( $_POST['wpttest-url'] ) ? esc_url( $_POST['wpttest-url'] ) : '';

				update_option( 'webpagetest-slack-notify', $option );
			}
		}

		public static function wsn_send_slack_message( $message ) {
			$apiEndpoint = self::get_config('slack_url');
			$apiResponse = wp_remote_post( $apiEndpoint, 
				array(
				'method'      => 'POST',
				'timeout'     => 30,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => array(
					'payload'   => wp_json_encode( array(
						'text'     => $message,
						'channel'  => self::get_config('slack_channel'),
						'username' => get_bloginfo( 'name' )
					) ),
				),
				) ); 
		}

		public function fetch_testId() {
			
			$key = self::get_config('webpage_apikey');
			$runs = self::get_config('wpttest_tests');
			$url = self::get_config('wpttest_url');
			
			$request = 'http://www.webpagetest.org/runtest.php?url=' . $url .'&runs=' . $runs .'&f=json&k=' . $key;

			$response = wp_remote_get( $request );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200) {
				
				return;
			}
			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body );

			return $body->data->testId;

		}

		public function get_test_results( $test_id ) {

			if ( isset( $test_id ) ) {

			$request = 'http://www.webpagetest.org/testStatus.php?f=json&test=' . $test_id ;

			$response = wp_remote_get( $request );

			$body = wp_remote_retrieve_body( $response );

			$body = json_decode( $body );

				if ( $body->statusCode === 200 ) {
					
					$request = 'http://www.webpagetest.org/jsonResult.php?test=' . $test_id ;

					$response = wp_remote_get( $request );

					$body = wp_remote_retrieve_body( $response );

					$code = wp_remote_retrieve_response_code( $response );

						$body = json_decode( $body );
						$test = '```';
						foreach ( $body->data->runs as $key => $value) {
	
							$test .= 'URL: ' . $value->firstView->URL  . '
';
							$test .= 'Time: ' . $value->firstView->fullyLoaded / 1000 . ' Seconds
';
							$test .= 'Requests: ' . $value->firstView->requestsFull . '
';
							$test .= 'Bytes In: ' . $value->firstView->bytesIn / 1000 . ' KB

';
						}
						$test .= 'View Full Summary: ' . $body->data->summary . '```';

						delete_option( 'wpt_test_id' );
						self::wsn_send_slack_message( $test );
				}
			} // check test id ends
		}
			
		public static function get_config( $setting = '', $default = '' ) {
			
			$options = get_option( 'webpagetest-slack-notify' );
			
			if ( isset( $options[ $setting ] ) ) {
				return $options[ $setting ];
			}
			return $default;
		}
	} // class end
} // class condition end

function wsn_notify_update() {

	$WS_Notify_Update = WS_Notify_Update::instance();
}
add_action( 'plugins_loaded', 'wsn_notify_update' );