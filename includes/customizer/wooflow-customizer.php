<?php 
/**
 * WC_EC_CUSTOMIZER
 *
 * @class   WC_EC_CUSTOMIZER
 * @package WooCommerce/Classes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_EC_CUSTOMIZER class.
 */
class WC_EC_CUSTOMIZER {

	public static $email_types_class_names  = array(
		// WooCommerce email classes.
		'new_order'                         => 'WC_Email_New_Order',
		'cancelled_order'                   => 'WC_Email_Cancelled_Order',
		'customer_processing_order'         => 'WC_Email_Customer_Processing_Order',
		'customer_completed_order'          => 'WC_Email_Customer_Completed_Order',
		'customer_refunded_order'           => 'WC_Email_Customer_Refunded_Order',
		'customer_on_hold_order'            => 'WC_Email_Customer_On_Hold_Order',
		'failed_order'                      => 'WC_Email_Failed_Order',
		'customer_invoice'                  => 'WC_Email_Customer_Invoice',
		'customer_note'                     => 'WC_Email_Customer_Note',
		'customer_new_account'              => 'WC_Email_Customer_New_Account',
		'customer_reset_password'           => 'WC_Email_Customer_Reset_Password',

		//WooCommerce Subscriptions Plugin classes.
		'new_renewal_order'                 => 'WCS_Email_New_Renewal_Order',
		'new_switch_order'					=> 'WCS_Email_New_Switch_Order',
		'customer_processing_renewal_order' => 'WCS_Email_Processing_Renewal_Order',
		'customer_completed_renewal_order'  => 'WCS_Email_Completed_Renewal_Order',
		'customer_completed_switch_order'   => 'WCS_Email_Completed_Switch_Order',
		'customer_renewal_invoice'          => 'WCS_Email_Customer_Renewal_Invoice',
		'cancelled_subscription'      		=> 'WCS_Email_Cancelled_Subscription',
		'expired_subscription'				=> 'WCS_Email_Expired_Subscription',
		'customer_on_hold_renewal_order'	=> 'WCS_Email_Customer_On_Hold_Renewal_Order',
		'suspended_subscription'			=> 'WCS_Email_On_Hold_Subscription',

		//Stripe Payment Gateway for WooCommerce
		'failed_authentication_requested'    =>	'WC_Stripe_Email_Failed_Authentication_Retry',
		'failed_renewal_authentication'      =>	'WC_Stripe_Email_Failed_Renewal_Authentication',
		'failed_preorder_sca_authentication' =>	'WC_Stripe_Email_Failed_Preorder_Authentication',

		//CEV Emails
		'email_registration'				=> 'WC_Email_registration',
		'email_checkout'					=> 'WC_Email_checkout',
		'email_login_otp'					=> 'WC_Email_Login_Otp',
		'email_login_auth'					=> 'WC_Email_Login_Auth',

	);
	
	public static $email_types_order_status = array(
		'new_order'                         => 'processing',
		'cancelled_order'                   => 'cancelled',
		'customer_completed_order'          => 'completed',
		'customer_refunded_order'           => 'refunded',
		'customer_on_hold_order'            => 'on-hold',
		'failed_order'                      => 'failed',
		'customer_new_account'              => null,
		'customer_reset_password'           => null,

		//WooCommerce Subscriptions Plugin classes.
		'new_renewal_order'                 => 'processing',
		'new_switch_order'					=> 'Switch',
		'customer_processing_renewal_order' => 'processing',
		'customer_completed_renewal_order'  => 'completed',
		'customer_completed_switch_order'   => 'completed',
		'customer_renewal_invoice'          => 'failed',
		'cancelled_subscription_order'     	=> 'cancelled',
		'expired_subscription'				=> 'expired',
		'customer_on_hold_renewal_order'	=> 'on-hold-renewal',
		'suspended_subscription'			=> 'on-hold',

	);
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return WC_EC_CUSTOMIZER
	*/
	public static function get_email_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	 * 
	 * @since  1.0
	*/
	
	public function __construct() {

		$this->init();

		if ( ! self::email_own_preview_request() ) {
			return;
		}

		// Set up preview.
		add_action( 'parse_request', array( $this, 'email_set_up_preview' ) );
	}

	/**
	 * Set up preview
	 *
	 * @return void
	 */
	public function email_set_up_preview() {
		include Wooflow_Email_Customizer()->get_plugin_path() . '/includes/customizer/preview/wooflow-customizer-email-preview.php';			
	}

	/**
	 * Checks to see if we are opening our custom customizer preview
	 *
	 * @return bool
	 */
	public static function email_own_preview_request() {

		$email = isset( $_GET['email_type'] ) ? sanitize_text_field( $_GET['email_type'] ) : get_option( 'orderStatus', 'new_order' );
		return isset( $_REQUEST['wooflow-email-customizer-preview'] ) ? sanitize_text_field( $_REQUEST['wooflow-email-customizer-preview'] ) : '';
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init() {

		//adding hooks
		add_action( 'admin_menu', array( $this, 'register_woocommerce_menu' ), 99 );
		
		//save of settings hook
		add_action( 'wp_ajax_wec_save_email_settings', array( $this, 'email_customizer_save_email_settings' ) );
		
		add_action( 'wp_ajax_save_email_templete', array( $this, 'save_email_templete_callback' ) );

		add_action( 'wp_ajax_woocommerce_customizer_email_preview', array( $this, 'get_preview_email' ) );
		
		//load javascript in admin
		add_action('admin_enqueue_scripts', array( $this, 'customizer_enqueue_scripts' ) );
		
		//load CSS/javascript in admin
		add_action('admin_footer', array( $this, 'admin_footer_enqueue_scripts' ) );
		add_action( 'wp_ajax_email_header_setting', array( $this, 'woflow_email_customizer_header_section_settings' ) );
		
		add_action( 'wp_ajax_woflow_send_to_email_setting', array( $this, 'woflow_send_test_email_func' ) );
		add_action( 'wp_ajax_woflow_export_import_setting', array( $this, 'woflow_export_import_setting' ) );
		add_action( 'wp_ajax_woflow_import_email_setting', array( $this, 'woflow_import_email_setting' ) );
		
	}

	/*
	 * Admin Menu add function
	 *
	 * @since  2.4
	 * WC sub menu 
	*/
	public function register_woocommerce_menu() {
		add_submenu_page( 'woocommerce', 'Email Customizer', 'Email Customizer', 'manage_options', 'email_customizer', array( $this, 'settingsPage' ) );
	
	}
	
	/*
	 * Add admin javascript
	 *
	 * @since  2.4
	 * WC sub menu 
	*/
	public function admin_footer_enqueue_scripts() {
		?>
		<style type="text/css">
			#toplevel_page_email_customizer { display: none !important; }
		</style>
		<?php
	}
	
	public function customer_email_types_list() {

		$wc_emails      = WC_Emails::instance();
		$emails         = $wc_emails->get_emails();
		
		$email_types = [];
		foreach ($emails as $key => $value) {
			$wc_admin_status = [];
			$wc_admin_order_key = array( 'WC_Email_New_Order', 'WC_Email_Cancelled_Order', 'WC_Email_Failed_Order' );
			if ( in_array( $key, $wc_admin_order_key ) ) {
				$wc_admin_status = array(
					'lable' 	=> esc_html__( 'Woocommerce Admin Emails', 'wooflow-email-customizer' ),
					'opt_group_option'	=> array(
						'new_order'          => 'New Order',
						'cancelled_order'    => 'Cancelled Order',
						'failed_order'       => 'Failed Order',
					),
				);
				$email_types['wc_admin_statuses'] = $wc_admin_status;
			}

			$wc_customer_status = [];
			$wc_customer_order_key = array( 'WC_Email_Customer_On_Hold_Order', 'WC_Email_Customer_Processing_Order', 'WC_Email_Customer_Completed_Order', 'WC_Email_Customer_Refunded_Order', 'WC_Email_Customer_Invoice', 'WC_Email_Customer_Note' );
			if ( in_array( $key, $wc_customer_order_key ) ) {
				$wc_customer_status = array(
					'lable' 	=> esc_html__( 'Woocommerce Customer Emails', 'wooflow-email-customizer' ),
					'opt_group_option'	=> array(
						'customer_on_hold_order'     => 'Customer On Hold Order',
						'customer_processing_order'  => 'Customer Processing Order',
						'customer_completed_order'   => 'Customer Completed Order',
						'customer_refunded_order'    => 'Customer Refunded Order',
						'customer_invoice'           => 'Customer Invoice',
						'customer_note'              => 'Customer Note',
					),
				);
				$email_types['wc_customer_statuses'] = $wc_customer_status;
			}

			$wc_account_status = [];
			$wc_account_order_key = array( 'WC_Email_Customer_New_Account', 'WC_Email_Customer_Reset_Password' );
			if ( in_array( $key, $wc_account_order_key ) ) {
				$wc_account_status = array(
					'lable' 	=> esc_html__( 'Woocommerce Account Emails', 'wooflow-email-customizer' ),
					'opt_group_option'	=> array(
						'customer_new_account'           => 'Customer New Account',
						'customer_reset_password'        => 'Customer Reset Password',
					),
				);
				$email_types['wc_account_statuses'] = $wc_account_status;
			}

			if ( Wooflow_Email_Customizer()->is_subscription_active() ) {
				$subscription_admin_status = [];
				$subscription_admin_key = array( 'WCS_Email_On_Hold_Subscription', 'WCS_Email_Expired_Subscription', 'WCS_Email_Cancelled_Subscription', 'WCS_Email_New_Switch_Order', 'WCS_Email_New_Renewal_Order' );
				if ( in_array( $key, $subscription_admin_key ) ) {
					$subscription_admin_status = array(
						'lable' 	=> esc_html__( 'Subscription Admin Emails', 'wooflow-email-customizer' ),
						'opt_group_option'	=> array(
							'new_renewal_order'				=> esc_html__( 'New Renewal Order', 'wooflow-email-customizer' ),
							'new_switch_order'				=> esc_html__( 'Subscription Switched', 'wooflow-email-customizer' ),
							'cancelled_subscription'		=> esc_html__( 'Cancelled Subscription', 'wooflow-email-customizer' ),
							'expired_subscription'			=> esc_html__( 'Expired Subscription', 'wooflow-email-customizer' ),
							'suspended_subscription'		=> esc_html__( 'Suspended Subscription', 'wooflow-email-customizer' ),
						),
					);
					$email_types['wcs_admin_statuses'] = $subscription_admin_status;
				}
			}

			if ( Wooflow_Email_Customizer()->is_subscription_active() ) {
				$subscription_customer_status = [];
				$subscription_customer_key = array( 'WCS_Email_Processing_Renewal_Order', 'WCS_Email_Completed_Renewal_Order', 'WCS_Email_Completed_Switch_Order', 'WCS_Email_Customer_Renewal_Invoice', 'WCS_Email_On_Hold_Subscription' );
				if ( in_array( $key, $subscription_customer_key ) ) {
					$subscription_customer_status = array(
						'lable' 	=> esc_html__( 'Subscription Customer Emails', 'wooflow-email-customizer' ),
						'opt_group_option'	=> array(
							'customer_processing_renewal_order' => 'Processing Renewal Order',
							'customer_completed_renewal_order'  => 'Completed Renewal Order',
							'customer_completed_switch_order'   => 'Completed Switch Order',
							'customer_renewal_invoice'          => 'Customer Renewal Invoice',
							'customer_on_hold_renewal_order' 	=> 'On Hold Subscription',
						),
					);
					$email_types['wcs_customer_statuses'] = $subscription_customer_status;
				}
			}

			if ( class_exists( 'WC_Stripe_Payment_Request' ) ) {
				$other_admin_status = [];
				$other_admin_order_key = array( 'WC_Stripe_Email_Failed_Authentication_Retry' );
				if ( in_array( $key, $other_admin_order_key ) ) {

					$other_admin_status = array(
						'lable' 	=> esc_html__( 'Other Admin Emails', 'wooflow-email-customizer' ),
						'opt_group_option'	=> array(
							'failed_authentication_requested'   => 'Payment Authentication Requested Email',
						),
					);
					if ( !class_exists( 'WC_Stripe_Payment_Request' ) ) {
						unset($other_admin_status['opt_group_option']['failed_authentication_requested']);
					}
					$email_types['other_admin_statuses'] = $other_admin_status;
				}
			}

			if ( class_exists( 'WC_Stripe_Payment_Request' ) ) {
				$other_customer_status = [];
				$other_customer_order_key = array( 'WC_Stripe_Email_Failed_Renewal_Authentication', 'WC_Stripe_Email_Failed_Preorder_Authentication' );
				if ( in_array( $key, $other_customer_order_key ) ) {
					$other_customer_status = array(
						'lable' 	=> esc_html__( 'Other Customer Emails', 'wooflow-email-customizer' ),
						'opt_group_option'	=> array(
							'failed_renewal_authentication' 		=> 'Failed Subscription Renewal SCA Authentication',
							'failed_preorder_sca_authentication'    => 'Pre-order Payment Action Needed',
						),
					);
					$email_types['other_customer_statuses'] = $other_customer_status;
				}
			}
		}
		if ( class_exists( 'Customer_Email_Verification_Pro' ) ) {
			$cev_emails_status = [];
			$cev_emails_order_key = array( 'WC_Email_registration', 'WC_Email_checkout', 'WC_Email_Login_Otp', 'WC_Email_Login_Auth' );
			if ( $cev_emails_order_key ) {
				$cev_emails_status = array(
					'lable' 	=> esc_html__( 'CEV Emails', 'wooflow-email-customizer' ),
					'opt_group_option'	=> array(
						'email_registration'				=> 'Registration',
						'email_checkout'					=> 'Checkout',
						'email_login_otp'					=> 'New Login OTP',
						'email_login_auth'					=> 'New Login Authentication',
					),
				);
				$email_types['cev_emails_statuses'] = $cev_emails_status;
			}
		}

		return $email_types;
	}

	/*
	 * callback for settingsPage
	 *
	 * @since  2.4
	*/
	public function settingsPage() {

		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '' ;
		
		// Add condition for css & js include for admin page  
		if ( 'email_customizer' != $page ) {
			return;
		}
	
		$email_type = isset( $_GET['email_type'] ) ? sanitize_text_field($_GET['email_type']) : 'new_order';

		$email_status = isset( $_GET['email_type'] ) ? sanitize_text_field($_GET['email_type']) : get_option( 'orderStatus' );

		$iframe_url = ( 'new_order' == $email_type ) ? $this->get_custom_preview_url( $email_type ) : Wooflow_Email_Customizer()->default_content_customizer->get_email_preview_url( $email_type ) ;

		$email_types = $this->customer_email_types_list();

		// When load this page will not show adminbar
		?>
		<style type="text/css" id="wooflow_email_design_custom_css">
			#wpcontent, #wpbody-content, .wp-toolbar {margin: 0 !important;padding: 0 !important;}
			#adminmenuback, #adminmenuwrap, #wpadminbar, #wpfooter, .notice, div.error, div.updated { display: none !important; }
			.sub_menu_style
			{
				padding:8px 25px;
				background: linear-gradient(to right, #05d6f2, #077ff1);
				color:#ffffff;
				font-size:15px;
				margin: 0px -20px 0px -20px !important;
				height:35px;
			}
			.sub_menu_style  > div.menu-sub-title {
				color:#ffffff;
				line-height: 2.4;
				font-size:15px;
			}
			#template_container {
				min-height: 100vh !important;
			}
		</style>
		<script type="text/javascript" id="zoremmail-onload">
			jQuery(document).ready( function() {
				jQuery('#adminmenuback, #adminmenuwrap, #wpadminbar, #wpfooter', 'div#query-monitor-main').remove();
			});
		</script>
		<section class="zoremmail-layout zoremmail-layout-has-sider">
			<form method="post" id="woocommerce_email_options" class="woocommerce_email_options" style="display: contents;">
				<section class="zoremmail-layout zoremmail-layout-has-content zoremmail-layout-sider">
					<aside class="zoremmail-layout-slider-header">
						<button type="button" class="wordpress-to-back" tabindex="0">
							<?php $back_link = admin_url(); ?>
							<a class="zoremmail-back-wordpress-link" href="<?php echo esc_html( $back_link ); ?>"><span class="zoremmail-back-wordpress-title dashicons dashicons-no-alt"></span></a>
						</button>
						<span class="wclp-save-content" style="float: right;">
								<button name="save" class="wclp-btn wclp-save button-primary woocommerce-save-button" type="submit" value="Save changes" disabled>Saved</button>
								<?php wp_nonce_field( 'email_customizer_options_actions', 'email_customizer_options_nonce_field' ); ?>
								<input type="hidden" name="action" value="wec_save_email_settings">	
						</span>
					</aside>
					<aside class="zoremmail-layout-slider-content">
						<div class="zoremmail-layout-sider-container">
							<?php $this->get_html( Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func($email_status) ); ?>
						</div>
					</aside>
					<aside class="zoremmail-layout-content-collapse">
						<button type="button" class="collapse-sidebar-hide" tabindex="0">Hide Controls
						</button>
						<div class="zoremmail-layout-content-media" style="float: right;">
							<a data-width="600px" data-iframe-width="100%" class="active"><span class="dashicons dashicons-desktop"></span></a>
							<a data-width="600px" data-iframe-width="610px"><span class="dashicons dashicons-tablet"></span></a>
							<a data-width="400px" data-iframe-width="410px"><span class="dashicons dashicons-smartphone"></span></a>
						</div>
					</aside>
				</section>
				<section class="zoremmail-layout zoremmail-layout-has-content">
					<div class="zoremmail-layout-content-container">
						<?php $get_email_type = isset( $_GET['email_type'] ) ? sanitize_text_field( $_GET['email_type'] ) : 'new_order'; ?>
						<input type="hidden" id="selected_orderStatus" value="<?php echo esc_html( $get_email_type ); ?>">
						<span class="header_orderStatus" style="display: none;">
							<select name="orderStatus" id="orderStatus" class="select">
								<?php foreach ( $email_types as $key => $val ) { ?>	
									<optgroup label="<?php echo esc_html($val['lable']); ?>">
										<?php foreach ( (array) $val['opt_group_option'] as $key1 => $val1 ) { ?>
											<option value="<?php echo esc_html($key1); ?>" <?php echo $email_type == $key1 ? 'selected' : ''; ?>><?php echo esc_html($val1); ?></option>
										<?php } ?>
									</optgroup>
								<?php } ?>
							</select>
						</span>
						<span class="header_order_id_Status" style="display: none;">
							<select name="email_selected_order_id" id="email_selected_order_id" class="select">
								<?php foreach ( $this->get_order_ids() as $order_id => $order ) { ?>
									<option value="<?php esc_attr_e($order_id); ?>" <?php echo get_option('email_selected_order_id') == $order_id ? 'selected' : ''; ?>><?php esc_html_e($order); ?></option> 
								<?php } ?>
							</select>
						</span>
					</div>
				</section>
				<section class="zoremmail-layout-content-preview customize-preview">
					<div id="overlay"></div>
						<iframe id="customizer_email_preview" src="<?php esc_attr_e($iframe_url); ?>" style="width: 100%;min-height: 100vh;display: block;margin: 0 auto;"></iframe>
					<span type="button" class="collapse-sidebar" tabindex="0" ></span>
				</section>
			</form>
		</section>
		<?php
	}
	
	/*
	* save settings function
	*/
	public function woflow_send_test_email_func() {
		// wp_send_json(array('success' => 'true'));
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( sanitize_text_field( $_POST['nonce'] ) ), 'send_test_email_nonce' ) ) { 
			return;
		}

		//data to be saved
		$email_type = isset( $_POST['email_type'] ) ? sanitize_text_field($_POST['email_type']) : 'new_order';
		
		$wc_emails      = WC_Emails::instance();
		$emails         = $wc_emails->get_emails();	
	
		$mailer 		= WC()->mailer();
		$sent_to_admin 	= false;
		$plain_text 	= false;
		$message 		= $this->get_preview_email_add( $email_type );

		// $message 		= $this->get_preview_email_add( $email_type );

		$email_heading 	= 'Test email';

		$subject_email 	= 'email';
		
		$subject = str_replace('{site_title}', get_bloginfo( 'name' ), 'Test ' . $subject_email );

		// create a new email
		$email 		= new WC_Email();
		$headers 	= "Content-Type: text/html\r\n";
		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );

		$recipients = isset($_POST['email']) ? wc_clean($_POST['email']) : '';
		$recipients = explode( ',', $recipients );
		
		if ($recipients) {
			foreach ( $recipients as $recipient) {
				$test = wp_mail( $recipient, $subject, $message, $email->get_headers() );
			}
		}
		
		echo json_encode( array('success' => 'true') );
		die();
	}

	// export setting
	public function woflow_export_import_setting() {

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( sanitize_text_field( $_POST['nonce'] ) ), 'wooflow_email_export_import_nonce' ) ) { 
			return;
		}
		
		$charset	= get_option( 'blog_charset' );

		$options = array();
		$settings = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
		foreach ( $settings as $key => $setting ) {
			if ( isset( $setting['default'] ) && !in_array( $setting['type'], array( 'codeinfo', 'email_type_set', 'opt_select' ) ) ) {
				$options[$key]['type'] = $setting['type'];
				$options[$key]['default'] = $setting['default'];
				$options[$key]['option_name'] = $setting['option_name'];
				$options[$key]['option_type'] = $setting['option_type'];
			}
		}
		$options = base64_encode(base64_encode( serialize( $options ) ));
		wp_send_json($options);
	}
	
	//import setting
	public function woflow_import_email_setting() {
		// Make sure WordPress upload support is loaded.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( sanitize_text_field( $_POST['nonce'] ) ), 'wooflow_email_export_import_nonce' ) ) { 
			return;
		}
		
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$data = [];
		if ( isset( $_POST['file_data'] ) ) {
			$raw  = file_get_contents( wc_clean( $_POST['file_data'] ) );
			$data = unserialize( base64_decode( $raw ) );
		}

		foreach ( $data as $key => $val ) {

			if ( isset( $val['type'] ) && 'textarea' == $val['type'] ) {
				if ( 'key' == $val['option_type'] ) {
					update_option( $key, $val['default'] );
				} elseif ( 'array' == $val['option_type'] ) {
					$option_data = get_option( $val['option_name'], array() );
					$option_data[$key] = $val['default'];
					update_option( $val['option_name'], $option_data );
				}
			} elseif ( isset( $val['type'] ) && 'footer_link_text' == $val['type'] ) {
				$option_data = get_option( $val['option_name'], array() );
				$option_data['footer_text_add'] = $val['default'];
				update_option( $val['option_name'], $option_data );
			} elseif ( isset( $val['option_type'] ) && 'key' == $val['option_type'] ) {
				update_option( $key, $val['default'] );
			} elseif ( isset( $val['option_type'] ) && 'array' == $val['option_type'] ) {
				if ( isset( $val['option_key'] ) ) {
					$option_data = get_option( $val['option_name'], array() );
					$option_data[$val['option_key']] = $val['default'];
					update_option( $val['option_name'], $option_data );
				} else {
					$option_data = get_option( $val['option_name'], array() );
					$option_data[$key] = $val['default'];
					update_option( $val['option_name'], $option_data );
				}
			}
		}
	}
	
	/**
	 * Get the from name for outgoing emails.
	 *
	 * @return string
	 */
	public function get_from_name() {
		$from_name = apply_filters( 'woocommerce_email_from_name', get_option( 'woocommerce_email_from_name' ), $this );
		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	/**
	 * Get the from address for outgoing emails.
	 *
	 * @return string
	 */
	public function get_from_address() {
		$from_address = apply_filters( 'woocommerce_email_from_address', get_option( 'woocommerce_email_from_address' ), $this );
		return sanitize_email( $from_address );
	}	

	/*
	* Add admin javascript
	*
	* @since 1.0
	*/	
	public function customizer_enqueue_scripts() {
		
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '' ;
		
		// Add condition for css & js include for admin page  
		if ( 'email_customizer' != $page ) {
			return;
		}
		wp_enqueue_media();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_styles');
		wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
		wp_enqueue_script( 'select2');
		
		// Add tiptip js and css file	
		wp_enqueue_style( 'email-customizer', plugin_dir_url(__FILE__) . 'assets/css/customizer.css', array(), Wooflow_Email_Customizer()->version );
		wp_enqueue_script( 'email-customizer', plugin_dir_url(__FILE__) . 'assets/js/customizer.js', array( 'jquery', 'wp-util', 'wp-color-picker','jquery-tiptip' ), Wooflow_Email_Customizer()->version, true );
		$order_id = get_option( 'email_selected_order_id');
		$site_url = get_site_url();
		$source_link =  preg_replace('#(^https?:\/\/(w{3}\.)?)|(\/$)#', '', $site_url);

		wp_localize_script('email-customizer', 'email_customizer', array(
			'site_title'			=> get_bloginfo( 'name' ),
			'order_number'			=> $order_id ? $order_id : 1,
			'order_date'			=> '22-22-22',
			'site_url'				=> $source_link,
			'customer_first_name'	=> 'Sherlock',
			'customer_last_name'	=> 'Holmes',
			'customer_full_name'	=> 'Sherlock Holmes',
			'customer_company_name' => 'Detectives Ltd.',
			'customer_username'		=> 'sher_lock',
			'customer_email'		=> 'sherlock@holmes.co.uk',
			'email_iframe_url'		=> add_query_arg( array( 'action'	=> 'woocommerce_customizer_email_preview' ), admin_url( 'admin-ajax.php' ) ),
			'custom_iframe_url'		=> add_query_arg( array( 'wooflow-email-customizer-preview' => '1' ), home_url( '' ) ),
			'nonce' => wp_create_nonce('email-customizer-nonce'),
		));
		
	}
	
	/*
	* save settings function
	*/
	public function email_customizer_save_email_settings() {
		
		if ( !current_user_can( 'manage_options' ) ) {
			echo json_encode( array('permission' => 'false') );
			die();
		}

		if ( ! empty( $_POST ) && check_admin_referer( 'email_customizer_options_actions', 'email_customizer_options_nonce_field' ) ) {
			
			//data to be saved
			$settings = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
			
			foreach ( $settings as $key => $val ) {
				$value = isset( $_POST[$key] ) ? $_POST[$key] : '';
				if ( isset( $val['type'] ) && 'textarea' == $val['type'] ) {
                    if ( 'key' == $val['option_type'] ) {
                        update_option( $key, wp_kses_post( wp_unslash( $value ) ) );
                    } elseif ( 'array' == $val['option_type'] ) {
                        $option_data = get_option( $val['option_name'], array() );
                        $option_data[$key] = htmlentities( wp_unslash( $value ) );
                        update_option( $val['option_name'], $option_data );
                    }
                } elseif ( isset( $val['type'] ) && 'footer_link_text' == $val['type'] ) {
					$option_data = get_option( $val['option_name'], array() );
					$footer_data_input_text = array();
					if ( !empty( $_POST['footer_title'] ) ) {
						foreach ( wc_clean( $_POST['footer_title'] ) as $key => $provider ) {
							if ( isset( $_POST[ 'footer_title' ][ $key ] ) ) {
								$footer_data_input_text[$provider] = wc_clean( isset( $_POST['footer_url'][$key] ) );
							}			
						}		
					}
					$option_data['footer_text_add'] = $footer_data_input_text;
					update_option( $val['option_name'], $option_data );
				} elseif ( isset( $val['option_type'] ) && 'key' == $val['option_type'] ) {
					update_option( $key, wc_clean( $value ) );					
				} elseif ( isset( $val['option_type'] ) && 'array' == $val['option_type'] && 'select_template' !== $val['type'] ) {
					if ( isset( $val['option_key'] ) ) {
						$option_data = get_option( $val['option_name'], array() );
						$option_data[$val['option_key']] = wc_clean( wp_unslash( $value ) );
						update_option( $val['option_name'], $option_data );
					} else {
						$option_data = get_option( $val['option_name'], array() );
						$option_data[$key] = wc_clean( wp_unslash( $value ) );
						update_option( $val['option_name'], $option_data );
					}
				}
			}
			echo json_encode( array('success' => 'true') );
			die();
		}
	}
	
	public function save_email_templete_callback() {

		check_admin_referer( 'email-customizer-nonce', 'nonce' );
		$saved_value = get_option( 'email_customizer_settings_option', array() );
		$saved_value['wooflow_email_template'] = isset($_POST['last_clicked_templete']) ? sanitize_text_field( $_POST['last_clicked_templete'] ): '';
		update_option( 'email_customizer_settings_option', $saved_value );
		echo json_encode( array('success' => 'true') );
		exit;
	}

	/*
	* save settings function
	*/
	public function woflow_email_customizer_header_section_settings() {
		
		check_admin_referer( 'email-customizer-nonce', 'nonce' );

		if ( !current_user_can( 'manage_options' ) ) {
			echo json_encode( array('permission' => 'false') );
			die();
		}
		
		if ( ! empty( $_POST ) ) {
			
			//data to be saved
			$email_selected_order_id = isset( $_POST['email_selected_order_id'] ) ? sanitize_text_field( $_POST['email_selected_order_id'] ): 'mockup';
			
			update_option( 'email_selected_order_id', $email_selected_order_id );

			echo json_encode( array('success' => 'true') );
			die();
	
		}
	}
 
	/*
	* Get html of fields
	*/
	public function get_html( $arrays ) {
		
		echo '<ul class="zoremmail-panels">';
		?>
		<div class="customize-section-title">
			<h3>
				<span class="customize-action">
					<?php esc_html_e( 'You are customizing', 'wooflow-email-customizer' ); ?>
					<img title="prebuilt optimized email templates" class="question_icon" src="<?php echo esc_url( wooflow_email_customizer()->plugin_dir_url() ); ?>assets/images/question.png"></img>
				</span>
				<?php esc_html_e( 'Email Customizer', 'wooflow-email-customizer' ); ?>
			</h3>
			<div class="email_important_msg">
				<p class="email_msg_toggle">
				Template based email builder. We will offer prebuilt optimized email templates/skins.
				</p>
				<!-- <a href="https://www.zorem.com/products/" target="_blank">Wooflow Email Customizer plugin by Zorem.</a> -->
			</div>
		</div>
		<?php
		foreach ( (array) $arrays as $id => $array ) {
			if ( isset($array['show']) && true != $array['show'] ) {
				continue; 
			}

			if ( isset($array['type']) && 'panel' == $array['type'] ) {
				?>
				<li id="<?php isset($array['id']) ? esc_attr_e($array['id']) : ''; ?>" data-label="<?php isset($array['title']) ? esc_html_e($array['title']) : ''; ?>" data-iframe_url="<?php isset($array['iframe_url']) ? esc_attr_e($array['iframe_url']) : ''; ?>" class="zoremmail-panel-title <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>">
					<span><?php isset($array['title']) ? esc_html_e($array['title']) : ''; ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</li>
				<?php
			}
		}
		echo '</ul>';
		
		echo '<ul class="zoremmail-sub-panels" style="display:none;">';
		foreach ( (array) $arrays as $id => $array ) {
			
			if ( isset($array['show']) && true != $array['show'] ) {
				continue; 
			}
			
			if ( isset($array['type']) && 'sub-panel-heading' == $array['type'] ) {
				?>
				<li data-id="<?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>" class="zoremmail-sub-panel-heading <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?> <?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>">
					<?php
					/*<button type="button" class="customize-section-back" tabindex="0">
						<span class="dashicons dashicons-arrow-left-alt2"></span>
					</button>
					<span><?php esc_html_e( $array['title'] ); ?></span>*/
					?>
					<div class="customize-section-title">
						<button type="button" class="customize-section-back" tabindex="0">
							<span class="screen-reader-text">Back</span>
						</button>
						<h3>
							<span class="customize-action">
								<?php esc_html_e( 'You are customizing', 'wooflow-email-customizer' ); ?>
							</span>
							<?php esc_html_e( $array['title'] ); ?>
						</h3>
					</div>
				</li>
				<?php
			}

			if ( isset($array['type']) && 'sub-panel' == $array['type'] ) {
				?>
				<li id="<?php isset($array['id']) ? esc_attr_e($array['id']) : ''; ?>"  data-type="<?php isset($array['parent']) ? esc_html_e($array['parent']) : ''; ?>" data-label="<?php isset($array['title']) ? esc_html_e($array['title']) : ''; ?>" class="zoremmail-sub-panel-title <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?> <?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>">
					<span><?php isset($array['title']) ? esc_html_e($array['title']) : ''; ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</li>
				<?php
			}
		}
		echo '</ul>';
		echo '<ul class="zoremmail-sub-second-panels" style="display:none;">';
		foreach ( (array) $arrays as $id => $array ) {
			
			if ( isset($array['show']) && true != $array['show'] ) {
				continue; 
			}

			if ( isset($array['type']) && 'sub-second-panel-heading' == $array['type'] ) {
				?>
				<li data-id="<?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>" class="zoremmail-sub-second-panel-heading <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?> <?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>">
					<div class="customize-section-title">
						<button type="button" class="customize-section-back" tabindex="0">
							<span class="screen-reader-text">Back</span>
						</button>
						<h3>
							<span class="customize-action">
								<?php esc_html_e( 'You are customizing', 'wooflow-email-customizer' ); ?>
								<img class="email_customizer_logo_header" src="<?php echo esc_url( wooflow_email_customizer()->plugin_dir_url() ); ?>assets/images/angle-right-solid.png">
								<span class="customizer_Breadcrumb"></span>
							</span>
							<?php esc_html_e( $array['title'] ); ?>
						</h3>
					</div>
				</li>
				<?php
			}
			
			if ( isset($array['type']) && 'sub-second-panel' == $array['type'] ) {
				?>
				<li id="<?php isset($array['id']) ? esc_attr_e($array['id']) : ''; ?>"  data-type="<?php isset($array['parent']) ? esc_html_e($array['parent']) : ''; ?>" data-label="<?php isset($array['title']) ? esc_html_e($array['title']) : ''; ?>" class="zoremmail-sub-second-panel-title <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?> <?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>">
					<span><?php isset($array['title']) ? esc_html_e($array['title']) : ''; ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</li>
				<?php
			}
		}
		echo '</ul>';
		foreach ( (array) $arrays as $id => $array ) {

			if ( isset($array['show']) && true != $array['show'] ) {
				continue; 
			}

			if ( isset($array['type']) && 'panel' == $array['type'] ) {
				continue; 
			}

			if ( isset($array['type']) && 'sub-panel-heading' == $array['type'] ) {
				continue; 
			}
			
			if ( isset($array['type']) && 'sub-panel' == $array['type'] ) {
				continue; 
			}

			if ( isset($array['type']) && 'sub-second-panel-heading' == $array['type'] ) {
				continue; 
			}

			if ( isset($array['type']) && 'sub-second-panel' == $array['type'] ) {
				continue; 
			}

			if ( isset($array['type']) && ( 'section' == $array['type'] ) ) {
				echo 'heading' !== $id ? '</div>' : '';
				?>
				<div data-id="<?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>" class="zoremmail-menu-submenu-title <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>">
					<div class="customize-section-title">
						<button type="button" class="customize-section-back" tabindex="0">
							<span class="screen-reader-text">Back</span>
						</button>
						<h3>
							<span class="customize-action">
								<?php esc_html_e( 'Customizing', 'wooflow-email-customizer' ); ?>
								<img class="email_customizer_logo_header" src="<?php echo esc_url( wooflow_email_customizer()->plugin_dir_url() ); ?>assets/images/angle-right-solid.png">
								<span class="customizer_Breadcrumb"></span>
							</span>
							<?php esc_html_e( $array['title'] ); ?>
						</h3>
					</div>
				</div>
				<div class="zoremmail-menu-contain" data-parent="<?php isset($array['parent']) ? esc_attr_e($array['parent']) : ''; ?>">
				<?php
			} else {
				$array_default = isset( $array['default'] ) ? $array['default'] : '';
				$email_customizer_settings = get_option('email_customizer_settings_option', array());
				$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
				$wec = Wooflow_Email_Customizer()->default_content_customizer;
				$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );
				$link_show = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'link_show', $wooflow_customizer['link_show'] );
				$class = ( 0 == $link_show ) && isset( $array['hide'] ) ? ' layout_box_hide' : '';
				$header_image_option = get_option('woocommerce_email_header_image');
				?>
				<div class="zoremmail-menu zoremmail-menu-inline zoremmail-menu-sub <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?><?php echo esc_html ( $class ); ?>">
					<div class="zoremmail-menu-item">
						<div class="<?php esc_attr_e( $id ); ?>">
							<?php if ( isset($array['title']) /*&& 'checkbox' != $array['type']*/ ) { ?>
								<div class="menu-sub-title"><?php esc_html_e( $array['title'] ); ?></div>
							<?php } ?>
							<?php if ( isset($array['type']) && 'text' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<input type="text" id="<?php esc_attr_e( $id ); ?>" name="<?php esc_attr_e( $id ); ?>" placeholder="<?php isset($array['placeholder']) ? esc_attr_e($array['placeholder']) : ''; ?>" value="<?php echo esc_html( $array_default ); ?>" class="zoremmail-input <?php esc_html_e($array['type']); ?> <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>">
									<br>
									<span class="menu-sub-tooltip"><?php isset($array['desc']) ? esc_html_e($array['desc']) : ''; ?></span>
								</div>
							<?php } else if ( isset($array['type']) && 'textarea' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<textarea id="<?php esc_attr_e( $id ); ?>" rows="4" name="<?php esc_attr_e( $id ); ?>" placeholder="<?php isset($array['placeholder']) ? esc_attr_e($array['placeholder']) : ''; ?>" class="zoremmail-input <?php esc_html_e($array['type']); ?> <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>"><?php echo esc_html( $array_default ); ?></textarea>
									<br>
									<span class="menu-sub-tooltip"><?php isset($array['desc']) ? esc_html_e($array['desc']) : ''; ?></span>
								</div>
							<?php } else if ( isset($array['type']) && 'codeinfo' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<span class="menu-sub-codeinfo <?php esc_html_e($array['type']); ?>"><?php echo isset($array['default']) ? wp_kses_post($array['default']) : ''; ?></span>
								</div>
							<?php } else if ( isset($array['type']) && 'select' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<select name="<?php esc_attr_e( $id ); ?>" id="<?php esc_attr_e( $id ); ?>" class="zoremmail-input <?php esc_html_e($array['type']); ?> <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>">
										<?php foreach ( (array) $array['options'] as $key => $val ) { ?>
											<option value="<?php echo esc_html($key); ?>" <?php echo $array_default == $key ? 'selected' : ''; ?>><?php echo esc_html($val); ?></option>
										<?php } ?>
									</select>
									<br>
									<span class="menu-sub-tooltip"><?php isset($array['desc']) ? esc_html_e($array['desc']) : ''; ?></span>
								</div>
							<?php } else if ( isset($array['type']) && 'opt_select' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<select name="<?php esc_attr_e( $id ); ?>" id="<?php esc_attr_e( $id ); ?>" class="zoremmail-input <?php esc_html_e($array['type']); ?> <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>">
										<?php foreach ( (array) $array['options'] as $key => $val ) { ?>	
											<optgroup label="<?php echo esc_html($val['lable']); ?>" >
												<?php foreach ( (array) $val['opt_group_option'] as $key1 => $val1 ) { ?>
													<option value="<?php echo esc_html($key1); ?>" <?php echo $array_default == $key1 ? 'selected' : ''; ?>><?php echo esc_html($val1); ?></option>
												<?php } ?>
											</optgroup>
										<?php } ?>
									</select>
									<br>
									<span class="menu-sub-tooltip"><?php isset($array['desc']) ? esc_html_e($array['desc']) : ''; ?></span>
								</div>
							<?php } else if ( isset($array['type']) && 'radio_button_design' == $array['type'] ) { ?>
							<div class="menu-sub-field">
								<label class="menu-sub-title">
									<?php
									foreach ( $array['choices'] as $key => $value ) { 
										$rb_id = 'Normal' == $value ? 'normal' : 'full_width' ;
										$rb_id = in_array( $id, array('footer_width', 'header_width') ) ? $rb_id : '';
										$rb_second_id = 'Normal' == $value ? 'Normal' : 'Large' ;
										$rb_second_id = in_array( $id, array('fluid_button_size') ) ? $rb_second_id : '';
										?>
										<label class="radio-button-label">
											<input type="radio" class="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $rb_id ) . esc_attr( $rb_second_id ); ?>" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php echo $array_default == $key ? 'checked' : ''; ?>/>
											<span><?php echo esc_html( $value ); ?></span>
										</label>
									<?php } ?>
								</label>
							</div>
							<?php } else if ( isset($array['type']) && 'color' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<input type="text" name="<?php esc_attr_e( $id ); ?>" id="<?php esc_attr_e( $id ); ?>" class="input-text regular-input zoremmail-input <?php esc_html_e($array['type']); ?> <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>" value="<?php echo esc_html( $array_default ); ?>" placeholder="<?php isset($array['placeholder']) ? esc_attr_e($array['placeholder']) : ''; ?>" data-default-color="<?php isset($array['default_color']) ? esc_attr_e($array['default_color']) : ''; ?>">
									<br>
									<span class="menu-sub-tooltip"><?php isset($array['desc']) ? esc_html_e($array['desc']) : ''; ?></span>
								</div>
							<?php } else if ( isset($array['type']) && 'checkbox' == $array['type'] ) { ?>

								<div class="menu-sub-field">
								<label class="menu-sub-title">
									<input type="hidden" name="<?php esc_attr_e( $id ); ?>" value="0"/>
									<input type="checkbox" id="<?php esc_attr_e( $id ); ?>" name="<?php esc_attr_e( $id ); ?>" class="zoremmail-checkbox <?php isset($array['class']) ? esc_attr_e($array['class']) : ''; ?>" value="1" <?php echo $array_default ? 'checked' : ''; ?>/>
									<?php esc_html_e( $array['label'] ); ?>
								</label>
							</div>
							<?php } else if ( isset($array['type']) && 'radio_button' == $array['type'] ) { ?>
								<div class="menu-sub-field">
								<label class="menu-sub-title">
									<?php foreach ( $array['choices'] as $key => $radio_value ) { ?>
										<label class="radio-button-label">
											<input type="radio" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $key ); ?>"
											<?php echo $array_default == $key ? 'checked' : ''; ?>/>
											<span><?php echo esc_html( $radio_value ); ?></span>
										</label>
									<?php } ?>
								</label>
							</div>
							<?php } else if ( isset($array['type']) && 'inport_export_buttons' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<label class="menu-sub-title">
										<span class="customize-control-title">
											<?php esc_attr_e( 'Export', 'wooflow-email-customizer' ); ?>
										</span>
									</label>
										<span class="description customize-control-description">
											<?php esc_attr_e( 'Click the button below to export the customization settings for this plugin.', 'wooflow-email-customizer' ); ?>
										</span>
										<input type="button" class="button button-primary Wooflow-email-export Wooflow-email-button" name="wooflow-email-export-button" value="<?php esc_attr_e( 'Export', 'wooflow-email-customizer' ); ?>" />

									<hr class="wooflow-email-hr" />
									<label class="menu-sub-title">
										<span class="customize-control-title">
											<?php esc_attr_e( 'Import', 'wooflow-email-customizer' ); ?>
										</span>
									</label>
									<span class="description customize-control-description">
										<?php esc_attr_e( 'Upload a file to import customization settings for this plugin.', 'wooflow-email-customizer' ); ?>
									</span>
									<div class="Wooflow-email-import-controls">
										<input type="file" name="Wooflow-email-import-file" class="Wooflow-email-import-file" id="Wooflow-email-import-file" />
										<?php wp_nonce_field( 'wooflow_email_export_import_nonce', 'import_export_nonce_field' ); ?>
									</div>
									<div class="Wooflow-email-uploading"><?php esc_attr_e( 'Uploading...', 'wooflow-email-customizer' ); ?></div>
									<input type="button" class="button button-primary Wooflow-email-import Wooflow-email-button" name="wooflow-email-import-button" value="<?php esc_attr_e( 'Import', 'wooflow-email-customizer' ); ?>" />
								</div>
							<?php } else if ( isset($array['type']) && 'tgl-btn' == $array['type'] ) { ?>
								<div class="menu-sub-field">
									<label class="menu-sub-title">
										<span class="tgl-btn-parent">
											<input type="hidden" name="<?php esc_attr_e( $id ); ?>" value="no">
											<input type="checkbox" id="<?php esc_attr_e( $id ); ?>" name="<?php esc_attr_e( $id ); ?>" class="tgl tgl-flat" <?php echo $array_default ? 'checked' : ''; ?> value="yes">
											<label class="tgl-btn" for="<?php esc_attr_e( $id ); ?>"></label>
										</span>
										<label for="<?php esc_attr_e( $id ); ?>"><?php esc_html_e( 'Enable email', 'wooflow-email-customizer' ); ?></label>
									</label>
								</div>
							<?php
							} else if ( isset($array['type']) && 'btn-link-tgl-btn' == $array['type']  ) {
								if ( get_option( $id, $array['default'] ) ) {
									$checked = 'checked';
								} else {	
									$checked = '';
								} 							
								?>
								<div class="menu-sub-field">
									<label class="menu-sub-title" id="btn_link_font_size">
										<span class="tgl-btn-parent">
											<input type="hidden" name="<?php esc_attr_e( $id ); ?>" value="0">
											<input class="tgl tgl-flat <?php echo $array_default ? 'checked' : ''; ?>" id="<?php echo esc_html( $id ); ?>" name="<?php echo esc_html( $id ); ?>" type="checkbox" <?php echo esc_html( $checked ); ?> value="1"/>
											<label class="tgl-btn" for="<?php esc_attr_e( $id ); ?>"></label>
										</span>
									</label>
								</div>
							<?php } else if ( isset($array['type']) && 'range' == $array['type']  ) { ?>
								<div class="menu-sub-field">
									<label class="menu-sub-title">
										<input type="range" class="zoremmail-range" id="<?php esc_attr_e( $id ); ?>" name="<?php esc_attr_e( $id ); ?>" value="<?php echo esc_html( $array_default ); ?>" min="<?php esc_html_e( $array['min'] ); ?>" max="<?php esc_html_e( $array['max'] ); ?>" oninput="this.nextElementSibling.value = this.value">
										<input style="width:50px;" class="slider__value" type="number" min="<?php esc_attr_e( $array['min'] ); ?>" max="<?php esc_attr_e( $array['max'] ); ?>" value="<?php echo esc_html( $array_default ); ?>">
									</label>
								</div>
							
							<?php } else if ( isset($array['type']) && 'media' == $array['type'] ) { ?>	
								<fieldset>
									<input id="<?php echo esc_html( $id ); ?>" type="button" class="<?php echo !$header_image_option ? 'show' : 'hide'; ?> button upload-button" value="Select Image" >
									<input id="uploaded_image" name="<?php echo esc_html ( $id ); ?>" type="hidden" value="<?php echo esc_html ( $header_image_option ); ?>" />
									<img class="<?php echo $header_image_option ? 'show' : 'hide'; ?>" id="widget-image"  src="<?php echo esc_url( $header_image_option ); ?>">
									<button type="button" class="<?php echo $header_image_option ? 'show' : 'hide'; ?> button sma-replace-btn">Replace</button>
									<button type="button" class="<?php echo $header_image_option ? 'show' : 'hide'; ?> button sma-remove-btn" style="margin-left:5px;">Remove</button>
								</fieldset>
							<?php } else if ( isset($array['type']) && 'email_type_set' == $array['type'] ) { ?>	
								<div class="menu-sub-field">
									<label class="menu-sub-title">
										<?php wp_nonce_field( 'send_test_email_nonce', 'send_test_email_nonce_field' ); ?>
										<input type="hidden" id="email_type_set" name="email_type" value="<?php isset($_GET['email_type']) ? esc_attr_e( sanitize_text_field( $_GET['email_type'] ) ) : 'new_order'; ?>">
									</label>
										<input type="text" id="send_to_email" name="send_to_email" placeholder="admin@example.com" value="<?php esc_html_e( get_option('admin_email') ); ?>">
										<button name="save" class="efc-btn efc-save button-primary woflow-send-to-email" type="button" value=""><?php esc_html_e( 'Send Email', 'wooflow-email-customizer' ); ?></button>
								</div>
							<?php
							} else if ( isset($array['type']) && 'select_template' == $array['type'] ) {

									$wooflow_email_template = isset($email_customizer_settings['wooflow_email_template']) ? $email_customizer_settings['wooflow_email_template'] :  'trackship_SaaS';
									$templete_array = array(
										'woocommerce' => 'woocommerce_template.png',
										// 'zorem' => 'zorem_template.png',
										'trackship_SaaS' => 'trackship_saas_template.png',
									);
	
									?>
									<div class="email_templates_selects">
										<?php
										foreach ( $templete_array as $template_id => $template_value ) {
											$checked = $template_id == $wooflow_email_template ? 'checked' : '';
											?>
											<input id="<?php esc_html_e( $template_id ); ?>" type="radio" name="tabs" class="tab_input <?php esc_html_e( $checked ? 'checked_class' : ''); ?>" <?php esc_html_e( $checked ); ?> >
											<label for="<?php esc_html_e( $template_id ); ?>"><img class="" style="width:300px" src="<?php echo esc_url( Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/' . esc_html( $template_value ) ); ?>" ></img></label>
										<?php } ?>
									</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php
			}
		}
	}
	
	/**
	 * Get the email order status
	 *
	 * @param string $email_template the template string name.
	 */
	public function get_email_order_status( $email_template ) {
		
		$order_status = apply_filters( 'customizer_email_type_order_status_array', self::$email_types_order_status );
		$order_status = self::$email_types_order_status;
		
		if ( isset( $order_status[ $email_template ] ) ) {
			return $order_status[ $email_template ];
		} else {
			return 'processing';
		}
	}
	
	/**
	 * Get the email class name
	 *
	 * @param string $email_template the email template slug.
	 */
	public function get_email_class_name( $email_template ) {
		
		$class_names = apply_filters( 'customizer_email_type_class_name_array', self::$email_types_class_names );

		$class_names = self::$email_types_class_names;
		
		if ( isset( $class_names[ $email_template ] ) ) {
			return $class_names[ $email_template ];
		} else {
			return $class_names[ 'new_order' ];
		}
	}
	
	/**
	 * Get the email content
	 *
	 */
	public function get_preview_email_add( $email_type ) {

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( sanitize_text_field( $_POST['nonce'] ) ), 'send_test_email_nonce' ) ) { 
			return;
		}

		// Load WooCommerce emails.
		$wc_emails = WC_Emails::instance();
		$emails    = $wc_emails->get_emails();
		
		$email_template = isset( $_POST['email_type'] ) ? sanitize_text_field( $_POST['email_type'] ) : get_option( 'orderStatus', 'new_order' );
		$preview_id = get_option( 'email_selected_order_id', 'mockup' );
		
		$email_type = $this->get_email_class_name( $email_template );
		
		if ( class_exists( 'Customer_Email_Verification_Pro' ) ) {
			if ( 'WC_Email_registration' == $email_type ) {
				return cev_pro()->customizer->preview_account_email();
				die();
			} else if ( 'WC_Email_checkout' == $email_type ) {
				return cev_pro()->customizer->preview_checkout_email();
				die();
			} else if ( 'WC_Email_Login_Otp' == $email_type ) {
				return cev_pro()->customizer->preview_login_otp_email();
				die();
			} else if ( 'WC_Email_Login_Auth' == $email_type ) {
				return cev_pro()->customizer->preview_login_auth_email();
				die();
			}
		}
		
		if ( false === $email_type ) {
			return false;
		}		 				
		if ( 'Wc_Email_Custom_Order_Status' == $email_type ) {
			$slug = str_replace( '-', '_', substr( $email_template, 0 ) );
			$email = $emails[ 'wc_email_' . $slug ];
		} else if ( isset( $emails[ $email_type ] ) && is_object( $emails[ $email_type ] ) ) {
			$email = $emails[ $email_type ];
		}
		
		$order_status = self::get_email_order_status( $email_template );
		
		// Get an order
		$order = self::get_wc_order_for_preview( $order_status, $preview_id );
		
		if ( is_object( $order ) ) {
			// Get user ID from order, if guest get current user ID.
			$user_id = (int) get_post_meta( $order->get_id(), '_customer_user', true );
			if ( 0 === $user_id ) {
				$user_id = get_current_user_id();
			}
		} else {
			$user_id = get_current_user_id();
		}
		// Get user object
		$user = get_user_by( 'id', $user_id );
		
		if ( isset( $email ) ) {
			// Make sure gateways are running in case the email needs to input content from them.
			WC()->payment_gateways();
			// Make sure shipping is running in case the email needs to input content from it.
			WC()->shipping();
			switch ( $email_template ) {
				/**
				 * WooCommerce (default transactional mails).
				 */
				case 'customer_invoice':
					$email->object = $order;
					if ( is_object( $order ) ) {
						$email->invoice = ( function_exists( 'wc_gzdp_get_order_last_invoice' ) ? wc_gzdp_get_order_last_invoice( $order ) : null );
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'customer_refunded_order':
					$email->object               = $order;
					$email->partial_refund       = true;
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'customer_new_account':
					$email->object             = $user;
					$email->user_pass          = '{user_pass}';
					$email->user_login         = stripslashes( $email->object->user_login );
					$email->user_email         = stripslashes( $email->object->user_email );
					$email->recipient          = $email->user_email;
					$email->password_generated = true;
					break;
				case 'customer_note':
					$email->object                  = $order;
					$email->customer_note           = __( 'Hello! This is an example note', 'wooflow-email-customizer' );
					if ( is_object( $order ) ) {
						$email->find['order-date']      = '{order_date}';
						$email->find['order-number']    = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'customer_reset_password':
					$email->object     = $user;
					$email->user_id    = $user_id;
					$email->user_login = $user->user_login;
					$email->user_email = stripslashes( $email->object->user_email );
					$email->reset_key  = '{{reset-key}}';
					$email->recipient  = stripslashes( $email->object->user_email );
					break;
				/**
				 * WooCommerce Subscriptions Plugin (from WooCommerce).
				 */
				case 'cancelled_subscription':
					$subscription = false;
					if ( ! empty( $preview_id ) && 'mockup' != $preview_id ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $preview_id );
							// We get the related subscription for this order.
							if ( $subscriptions_ids ) {
								foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
									if ( $subscription_obj->get_parent()->get_id() == $preview_id ) {
										$subscription = $subscription_obj;
										break; // Stop the loop).
									}
								}
							}
						}
					}
					if ( $subscription ) {
						$email->object = $subscription;
					} else {
						$email->object = 'subscription';
					}
					break;
				case 'failed_authentication_requested':
					$email->object               = $order;
					$email->find['order-date']   = '{order_date}';
					$email->find['order-number'] = '{order_number}';
					if ( is_object( $order ) ) {
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					if ( ! empty( $preview_id ) && 'mockup' != $preview_id ) {
						if ( class_exists( 'WCS_Retry_Manager' ) && WCS_Retry_Manager::is_retry_enabled() ) {
							$retry = WCS_Retry_Manager::store()->get_last_retry_for_order( $preview_id );
							if ( ! empty( $retry ) && is_object( $retry ) ) {
								$email->retry                 = $retry;
								$email->find['retry_time']    = '{retry_time}';
								$email->replace['retry_time'] = wcs_get_human_time_diff( $email->retry->get_time() );
							} else {
								$email->object = 'retry';
							}
						} else {
							$email->object = 'retry';
						}
					} else {
						$email->object = 'retry';
					}
					break;
				case 'failed_renewal_authentication':
					$email->object     = $order;
					$email->user_id    = $user_id;
					$email->user_login = $user->user_login;
					$email->user_email = stripslashes( $email->object->user_email );
					$email->reset_key  = '{{reset-key}}';
					$email->recipient  = stripslashes( $email->object->user_email );
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'failed_preorder_sca_authentication':
					$email->object     = $order;
					$email->user_id    = $user_id;
					$email->user_login = $user->user_login;
					$email->user_email = stripslashes( $email->object->user_email );
					$email->reset_key  = '{{reset-key}}';
					$email->recipient  = stripslashes( $email->object->user_email );
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				/**
				 * Everything else.
				 */
				default:
					$email->object               = $order;
					$user_id = get_post_meta( $email->object->get_order_number(), '_customer_user', true );
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->find['customer-first-name'] = '{customer_first_name}';
						$email->find['customer-last-name'] = '{customer_last_name}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						$email->replace['customer-first-name'] = get_user_meta( $user_id, 'shipping_first_name', true );
						$email->replace['customer-last-name'] = get_user_meta( $user_id, 'shipping_last_name', true );
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
			}

			if ( ! empty( $email ) ) {

				$content = $email->get_content();
				$content = $email->style_inline( $content );
				$content = apply_filters( 'woocommerce_mail_content', $content );	
				
			} else {
				if ( false == $email->object ) {
					$content = '<div style="padding: 35px 40px; background-color: white;">' . __( 'This email type can not be previewed please try a different order or email type.', 'wooflow-email-customizer' ) . '</div>';
				}
			}
		} else {
			$content = false;
		}
		
		return $content;
		die();
	}

	/**
	 * Get the email content
	 *
	 */
	public function get_preview_email( $send_email = false, $email_addresses = null ) { 
		
		// Load WooCommerce emails.
		$wc_emails      = WC_Emails::instance();
		$emails         = $wc_emails->get_emails();	
		$email_template = isset( $_GET['email_type'] ) ? sanitize_text_field($_GET['email_type']) : get_option( 'orderStatus', 'new_order' );
		$preview_id = get_option( 'email_selected_order_id', 'mockup' );
		
		$email_type = $this->get_email_class_name( $email_template );

		if ( class_exists( 'Customer_Email_Verification_Pro' ) ) {
			if ( 'WC_Email_registration' == $email_type ) {
				echo cev_pro()->customizer->preview_account_email();
				die();
			} else if ( 'WC_Email_checkout' == $email_type ) {
				echo cev_pro()->customizer->preview_checkout_email();
				die();
			} else if ( 'WC_Email_Login_Otp' == $email_type ) {
				echo cev_pro()->customizer->preview_login_otp_email();
				die();
			} else if ( 'WC_Email_Login_Auth' == $email_type ) {
				echo cev_pro()->customizer->preview_login_auth_email();
				die();
			}
		}

		if ( false === $email_type ) {
			return false;
		}

		if ( 'Wc_Email_Custom_Order_Status' == $email_type ) {
			$slug = str_replace( '-', '_', substr( $email_template, 0 ) );
			$email = $emails[ 'wc_email_' . $slug ];
		} else if ( isset( $emails[ $email_type ] ) && is_object( $emails[ $email_type ] ) ) {
			$email = $emails[ $email_type ];
		}

		$order_status = self::get_email_order_status( $email_template );

		// Get an order
		$order = self::get_wc_order_for_preview( $order_status, $preview_id );
		if ( is_object( $order ) ) {
			// Get user ID from order, if guest get current user ID.
			$user_id = (int) get_post_meta( $order->get_id(), '_customer_user', true );
			if ( 0 === $user_id ) {
				$user_id = get_current_user_id();
			}
		} else {
			echo '<p style="font-size:18px !important;background: #f0f0f1;padding: 13px;text-align: center;">' . __( 'Please select any order to preview the email.', 'wooflow-email-customizer' ) . '</p>';
			die();
		}
		
		// Get user object
		$user = get_user_by( 'id', $user_id );
		
		if ( isset( $email ) ) {
			// Make sure gateways are running in case the email needs to input content from them.
			WC()->payment_gateways();
			// Make sure shipping is running in case the email needs to input content from it.
			WC()->shipping();
			switch ( $email_template ) {
				/**
				 * WooCommerce (default transactional mails).
				 */
				case 'customer_invoice':
					$email->object = $order;
					if ( is_object( $order ) ) {
						$email->invoice = ( function_exists( 'wc_gzdp_get_order_last_invoice' ) ? wc_gzdp_get_order_last_invoice( $order ) : null );
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'customer_refunded_order':
					$email->object               = $order;
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'customer_new_account':
					$email->object             = $user;
					$email->user_pass          = '{user_pass}';
					$email->user_login         = stripslashes( $email->object->user_login );
					$email->user_email         = stripslashes( $email->object->user_email );
					$email->recipient          = $email->user_email;
					$email->password_generated = true;
					break;
				case 'customer_note':
					$email->object                  = $order;
					$email->customer_note           = __( 'Hello! This is an example note', 'wooflow-email-customizer' );
					if ( is_object( $order ) ) {
						$email->find['order-date']      = '{order_date}';
						$email->find['order-number']    = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'customer_reset_password':
					$email->object     = $user;
					$email->user_id    = $user_id;
					$email->user_login = $user->user_login;
					$email->user_email = stripslashes( $email->object->user_email );
					$email->reset_key  = '{{reset-key}}';
					$email->recipient  = stripslashes( $email->object->user_email );
					break;
					/**
				 * WooCommerce Subscriptions Plugin (from WooCommerce).
				 */
				case 'cancelled_subscription':
					$subscription = false;
					if ( ! empty( $preview_id ) && 'mockup' != $preview_id ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $preview_id );
							// We get the related subscription for this order.
							if ( $subscriptions_ids ) {
								foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
									if ( $subscription_obj->get_parent()->get_id() == $preview_id ) {
										$subscription = $subscription_obj;
										break; // Stop the loop).
									}
								}
							}
						}
					}
					if ( $subscription ) {
						$email->object = $subscription;
					} else {
						$email->object = 'subscription';
					}
					break;
				case 'failed_authentication_requested':
					$email->object               = $order;
					$email->find['order-date']   = '{order_date}';
					$email->find['order-number'] = '{order_number}';
					if ( is_object( $order ) ) {
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					if ( ! empty( $preview_id ) && 'mockup' != $preview_id ) {
						if ( class_exists( 'WCS_Retry_Manager' ) && WCS_Retry_Manager::is_retry_enabled() ) {
							$retry = WCS_Retry_Manager::store()->get_last_retry_for_order( $preview_id );
							if ( ! empty( $retry ) && is_object( $retry ) ) {
								$email->retry                 = $retry;
								$email->find['retry_time']    = '{retry_time}';
								$email->replace['retry_time'] = wcs_get_human_time_diff( $email->retry->get_time() );
							} else {
								$email->object = 'retry';
							}
						} else {
							$email->object = 'retry';
						}
					} else {
						$email->object = 'retry';
					}
					break;
				case 'failed_renewal_authentication':
					$email->object     = $order;
					$email->user_id    = $user_id;
					$email->user_login = $user->user_login;
					$email->user_email = stripslashes( $email->object->user_email );
					$email->reset_key  = '{{reset-key}}';
					$email->recipient  = stripslashes( $email->object->user_email );
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				case 'failed_preorder_sca_authentication':
					$email->object     = $order;
					$email->user_id    = $user_id;
					$email->user_login = $user->user_login;
					$email->user_email = stripslashes( $email->object->user_email );
					$email->reset_key  = '{{reset-key}}';
					$email->recipient  = stripslashes( $email->object->user_email );
					if ( is_object( $order ) ) {
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
				/**
				 * Everything else.
				 */
				default:
					$email->object = $order;
					if ( is_object( $order ) ) {
						$user_id = get_post_meta( $email->object->get_order_number(), '_customer_user', true );
						$email->find['order-date']   = '{order_date}';
						$email->find['order-number'] = '{order_number}';
						$email->find['customer-first-name'] = '{customer_first_name}';
						$email->find['customer-last-name'] = '{customer_last_name}';
						$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
						$email->replace['order-number'] = $email->object->get_order_number();
						$email->replace['customer-first-name'] = get_user_meta( $user_id, 'shipping_first_name', true );
						$email->replace['customer-last-name'] = get_user_meta( $user_id, 'shipping_last_name', true );
						// Other properties
						$email->recipient = $email->object->get_billing_email();
					}
					break;
			}

			if ( ! empty( $email ) ) {
				if ( 'retry' == $email->object ) {
					$content = '<div style="padding: 35px 40px; background-color: white;">' . __( 'To generate a preview of this email type you must choose an order containing a subscription which has also failed to auto renew as the preview order in the settings.', 'wooflow-email-customizer' ) . '</div>';	
				} else {
					$content = $email->get_content();
					$content = $email->style_inline( $content );
					$content = apply_filters( 'woocommerce_mail_content', $content );
				}
			} else {
				if ( false == $email->object ) {
					$content = '<div style="padding: 35px 40px; background-color: white;">' . __( 'This email type can not be previewed please try a different order or email type.', 'wooflow-email-customizer' ) . '</div>';
				}
			}
		} else {
			$content = false;
		}
		
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_css_tags' ) );
		add_filter( 'safe_style_css', array( $this, 'safe_style_css' ), 10, 1 );

		echo wp_kses_post( $content );
		die();
	}
	
	public function allowed_css_tags( $tags ) {
		$tags['style'] = array( 'type' => true, );
		return $tags;
	}
	
	public function safe_style_css( $styles ) {
		 $styles[] = 'display';
		return $styles;
	}
	
	/**
	 * Get WooCommerce order for preview
	 *
	 * @param string $order_status
	 * @return object
	 */
	public static function get_wc_order_for_preview( $order_status = null, $order_id = null ) {
		if ( ! empty( $order_id ) && 'mockup' != $order_id ) { 
			return wc_get_order( $order_id );
		} else {
			// Use mockup order
			// Instantiate order object
			$order = new WC_Order();

			// Other order properties
			$order->set_props( array(
				'id'                 => 1,
				'status'             => ( null === $order_status ? 'processing' : $order_status ),
				'billing_first_name' => 'Sherlock',
				'billing_last_name'  => 'Holmes',
				'billing_company'    => 'Detectives Ltd.',
				'billing_address_1'  => '221B Baker Street',
				'billing_city'       => 'London',
				'billing_postcode'   => 'NW1 6XE',
				'billing_country'    => 'GB',
				'billing_email'      => 'sherlock@holmes.co.uk',
				'billing_phone'      => '02079304832',
				'date_created'       => gmdate( 'Y-m-d H:i:s' ),
				'total'              => 24.90,
			) );

			// Item #1
			$order_item = new WC_Order_Item_Product();
			$order_item->set_props( array(
				'name'     => 'A Study in Scarlet',
				'subtotal' => '9.95',
			) );
			$order->add_item( $order_item );

			// Return mockup order
			return $order;
		}

	}
	
	/**
	 * Get Order Ids
	 *
	 * @return array
	 */
	public static function get_order_ids() {
		$order_array = array();
		// $order_array['mockup'] = esc_html( 'Mockup Order', 'wooflow-email-customizer' );

		$query = new WC_Order_Query( array(
			'limit' => 10,
			'orderby' => 'date',
			'post_parent'	=> 'parent',
			'order' => 'DESC',
			'return' => 'ids',
			'type' => 'shop_order',

		) );
		$orders = $query->get_orders();
		
		foreach ( $orders as $order_id ) {	
			$order = new WC_Order( $order_id );
			$order_array[ $order_id ] = $order_id . ' - ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();	
		}

		return $order_array;
	}
	
	/**
	 * Get preview URL(front load url)
	 *
	 */
	public function get_custom_preview_url( $status ) {
		return add_query_arg( array(
			'wooflow-email-customizer-preview' => '1',
			'email_type'	=> $status
		), home_url( '' ) );
	}
}

/**
 * Returns an instance of WC_EC_CUSTOMIZER.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @return WC_EC_CUSTOMIZER
*/
function email_admin_customizer() {
	static $instance;

	if ( ! isset( $instance ) ) {
		$instance = new WC_EC_CUSTOMIZER();
	}
	return $instance;
}
