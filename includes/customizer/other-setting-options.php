<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Other_Options {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return Other_Options
	*/
	public static function get_instance() {

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
	}

	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init() {
		add_filter( 'wooflow_customizer_setting_options', array( $this, 'customer_options_array' ) );
	}

	public function customer_options_array( $settings ) {

		if ( Wooflow_Email_Customizer()->is_subscription_active() ) {
	
			$settings[ 'customer_email' ] = array(
				'title'	=> esc_html__( 'Email Type and Text', 'wooflow-email-customizer' ),
				'type'     => 'section',
				'parent'	=> 'email_settings_two',
				'show'     => true,	
				'class' => 'sub_second_options_panel',
			);
	
			$other_emails = array(
				'failed_authentication_requested'		=> esc_html__( 'Payment Authentication Requested Email', 'wooflow-email-customizer' ),
				'failed_renewal_authentication'			=> esc_html__( 'Failed Subscription Renewal SCA Authentication', 'wooflow-email-customizer' ),
				'failed_preorder_sca_authentication'	=> esc_html__( 'Pre-order Payment Action Needed', 'wooflow-email-customizer' ),
			);
	
			foreach ( $other_emails as $key => $value ) {
				$default_value = Wooflow_Email_Customizer()->default_content_customizer->get_value();
				$admin_order = array( 'failed_authentication_requested' );
				$email_settings = get_option('woocommerce_' . $key . '_settings', array());
				$settings[ 'customer_email_status' ] = array(
					'title'    => esc_html__( 'Email Type', 'wooflow-email-customizer' ),
					'type'     => 'opt_select',
					'default'  => isset($email_type) ? $email_type : 'new_order',
					'show'     => true,
					'options'  => $other_emails,
				);
				if ( in_array( $key, $admin_order ) ) {
					$settings[ $key . '_recipient' ] = array(
						'title'    => esc_html__( 'Email Recipient', 'wooflow-email-customizer' ),
						'desc'  => esc_html__( 'add comma-seperated emails, defaults to placeholder {admin_email} ', 'wooflow-email-customizer' ),
						'default'  => isset($email_settings[ 'recipient' ]) ? $email_settings[ 'recipient' ] : get_option('admin_email'),
						'placeholder' => get_option('admin_email'),
						'type'     => 'text',
						'show'     => true,
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_key'=> 'recipient',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu recipient',
					);
				}
				$settings[ $key . '_subject_other' ] = array(
					'title'    => esc_html__( 'Email Subject', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject']) ? stripslashes($email_settings['subject']) : $default_value[ $key . '_subject_other'],
					'placeholder' => $default_value[ $key . '_subject_other'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
				);
				$settings[ $key . '_heading_other' ] = array(
					'title'    => esc_html__( 'Email Heading', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading']) ? stripslashes($email_settings['heading']) : $default_value[ $key . '_heading_other'],
					'placeholder' => $default_value[ $key . '_heading_other'],
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'heading',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
				);
				$settings[ $key . '_email_additional_content_other' ] = array(
					'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings[ $key . '_email_additional_content_other']) ? stripslashes($email_settings[ $key . '_email_additional_content_other']) :  $default_value[ $key . '_email_additional_content_other'] ,
					'placeholder' => $default_value[ $key . '_email_additional_content_other'],
					'type'     => 'textarea',
					'show'     => true,
					'option_key'=> $key . '_email_additional_content_other',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
				);
				$settings[ $key . '_codeinfoblock_other' ] = array(
					'title'    => esc_html__( 'Available Placeholders:', 'wooflow-email-customizer' ),
					'default'  => '<code> {order_date} <br> {order_number}	<br> {transaction_id} <br> </code>',
					'type'     => 'codeinfo',
					'show'     => true,
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu border_bottom_none',
				);
			}
		}
		return $settings;
	}	
}
