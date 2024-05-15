<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Customer_Options {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return Customer_Options
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

		$email_type = isset( $_GET['email_type'] ) ? sanitize_text_field($_GET['email_type']) : get_option( 'orderStatus', 'new_order' );
		$email_settings = get_option('woocommerce_' . $email_type . '_settings', array());

		$settings[ 'customer_email' ] = array(
			'title'	=> esc_html__( 'Email Type and Text', 'wooflow-email-customizer' ),
			'type'     => 'section',
			'parent'	=> 'email_settings_two',
			'show'     => true,	
			'class' => 'sub_second_options_panel',
		);
	
		$all_customer_emails = array(
			'customer_processing_order'   => esc_html__( 'Customer Processing Order', 'wooflow-email-customizer' ),
			'customer_completed_order'    => esc_html__( 'Customer Completed Order', 'wooflow-email-customizer' ),
			'customer_refunded_order'     => esc_html__( 'Customer Refunded Order', 'wooflow-email-customizer' ),
			'customer_on_hold_order'      => esc_html__( 'Customer On Hold Order', 'wooflow-email-customizer' ),
			'customer_invoice'        	  => esc_html__( 'Customer Invoice', 'wooflow-email-customizer' ),
			'customer_note'     		  => esc_html__( 'Customer Note', 'wooflow-email-customizer' ),
			'customer_new_account'        => esc_html__( 'Customer New Account', 'wooflow-email-customizer' ),
			'customer_reset_password'     => esc_html__( 'Customer Reset Password', 'wooflow-email-customizer' ),		
		);	
		
		foreach ( $all_customer_emails as $key => $value ) {		
			$customer_default_value = Wooflow_Email_Customizer()->default_content_customizer->get_value();
			$email_settings = get_option('woocommerce_' . $key . '_settings', array());
	
			$email_additional_content = '<p> Hi {user_first_name},</p><p> Thanks for creating an account on {user_first_name}. Your username is {user_first_name}. You can access your account area to view orders, change your password, and more at:</p> {account_button}';
			$email_additional_content_reset_pass = '<p> Hi  {user_first_name},</p><p> Someone has requested a new password for the following account on {user_first_name}<p> Username: {user_first_name}</p>
			<p>If you didn\'t make this request, just ignore this email. If you\'d like to proceed:</p> {reset_password_button}';
				
			$settings[ 'customer_email_status' ] = array(
				'title'    => esc_html__( 'Email Type', 'wooflow-email-customizer' ),
				'type'     => 'opt_select',
				'default'  => isset($email_type) ? $email_type : 'new_order',
				'show'     => true,
				'options'  => Wooflow_Email_Customizer()->wec_admin->customer_email_types_list(),
			);
			// $settings[ $key . '_enabled' ] = array(
			// 	'default'  => !empty($email_settings['enabled']) && 'no' == $email_settings['enabled'] ? 0 : 1,
			// 	'type'     => 'tgl-btn',
			// 	'show'     => false,
			// 	'option_name' => 'woocommerce_' . $key . '_settings',
			// 	'option_key'=> 'enabled',
			// 	'option_type'=> 'array',
			// 	'class'		=> $key . '_sub_menu all_cusomer_status_submenu enabled',
			// );
				
			if ( 'customer_refunded_order' == $key ) {
				$settings[ $key . '_subject_full' ] = array(
					'title'    => esc_html__( 'Full Refund Subject', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject_full']) ? stripslashes($email_settings['subject_full']) : $customer_default_value[ $key . '_subject'],
					'placeholder' => $customer_default_value[ $key . '_subject'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject_full',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject_paid',
				);
				$settings[ $key . '_subject_partial' ] = array(
					'title'    => esc_html__( 'Partial Refund Subject', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject_partial']) ? stripslashes($email_settings['subject_partial']) : $customer_default_value[ $key . '_subject'],
					'placeholder' => $customer_default_value[ $key . '_subject'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject_partial',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
				);
				$settings[ $key . '_heading_full' ] = array(
					'title'    => esc_html__( 'Full Refund Email Heading', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading_full']) ? stripslashes($email_settings['heading_full']) : $customer_default_value[ $key . '_heading'],
					'placeholder' => $customer_default_value[ $key . '_heading'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading_full',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading_paid',
				);
				$settings[ $key . '_heading_partial' ] = array(
					'title'    => esc_html__( 'Partial Refund Email Heading', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading_partial']) ? stripslashes($email_settings['heading_partial']) : $customer_default_value[ $key . '_heading'],
					'placeholder' => $customer_default_value[ $key . '_heading'],
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'heading_partial',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading_partial',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
				);
				$settings[ $key . '_email_additional_content_customer' ] = array(
					'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings[ $key . '_email_additional_content_customer']) ? stripslashes($email_settings[ $key . '_email_additional_content_customer']) :  '',
					'placeholder' => '',
					'type'     => 'textarea',
					'show'     => true,
					'option_key'=> $key . '_email_additional_content_customer',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
				);
				$settings[ $key . '_additional_content_customer'] = array(
					'title'    => esc_html__( 'Email Additional Content', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['additional_content']) ? stripslashes($email_settings['additional_content']) : '',
					'placeholder' => '',
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'additional_content',
					'option_key'=> 'additional_content',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu additional_content',
				);
			} elseif ( 'customer_invoice' == $key ) {
				$settings[ $key . '_subject' ] = array(
					'title'    => esc_html__( 'Subject', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject']) ? stripslashes($email_settings['subject']) : $customer_default_value[ $key . '_subject'],
					'placeholder' => $customer_default_value[ $key . '_subject'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject_paid',
				);
				$settings[ $key . '_subject_paid' ] = array(
					'title'    => esc_html__( 'Subject(paid)', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject_paid']) ? stripslashes($email_settings['subject_paid']) : $customer_default_value[ $key . '_subject'],
					'placeholder' => $customer_default_value[ $key . '_subject'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject_paid',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
				);
				$settings[ $key . '_heading' ] = array(
					'title'    => esc_html__( 'Email heading', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading']) ? stripslashes($email_settings['heading']) : $customer_default_value[ $key . '_heading'],
					'placeholder' => $customer_default_value[ $key . '_heading'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading_paid',
				);
				$settings[ $key . '_heading_paid' ] = array(
					'title'    => esc_html__( 'Email heading (paid)', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading_paid']) ? stripslashes($email_settings['heading_paid']) : $customer_default_value[ $key . '_heading'],
					'placeholder' => $customer_default_value[ $key . '_heading'],
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'heading_paid',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading_paid',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
				);
				$settings[ $key . '_email_additional_content_customer' ] = array(
					'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings[ $key . '_email_additional_content_customer']) ? stripslashes($email_settings[ $key . '_email_additional_content_customer']) :  '',
					'placeholder' => '',
					'type'     => 'textarea',
					'show'     => true,
					'option_key'=> $key . '_email_additional_content_customer',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
				);
				$settings[ $key . '_additional_content_customer'] = array(
					'title'    => esc_html__( 'Email Additional Content', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['additional_content']) ? stripslashes($email_settings['additional_content']) : '',
					'placeholder' => '',
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'additional_content',
					'option_key'=> 'additional_content',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu additional_content',
				);
			} elseif ( 'customer_processing_order' == $key || 'customer_completed_order' == $key || 'customer_on_hold_order' == $key || 'customer_new_account' == $key || 'customer_reset_password' == $key || 'customer_note' == $key ) {
				$settings[ $key . '_subject_customer' ] = array(
					'title'    => esc_html__( 'Email Subject', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject']) ? stripslashes($email_settings['subject']) : $customer_default_value[ $key . '_subject'],
					'placeholder' => $customer_default_value[ $key . '_subject'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
				);
				$settings[ $key . '_heading_customer' ] = array(
					'title'    => esc_html__( 'Email Heading', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading']) ? stripslashes($email_settings['heading']) : $customer_default_value[ $key . '_heading'],
					'placeholder' => $customer_default_value[ $key . '_heading'],
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'heading',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
				);
				if ( 'customer_new_account' == $key ) {
					$settings[ $key . '_email_additional_content_customer' ] = array(
						'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
						'default'  =>!empty( $email_settings[ $key . '_email_additional_content_customer'] ) ? $email_settings[ $key . '_email_additional_content_customer'] : $email_additional_content,
						'placeholder' => $email_additional_content,
						'type'     => 'textarea',
						'show'     => true,
						'option_key'=> $key . '_email_additional_content_customer',
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
					);
				}
				if ( 'customer_reset_password' == $key ) {
					$settings[ $key . '_email_additional_content_customer' ] = array(
						'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
						'default'  => !empty( $email_settings[ $key . '_email_additional_content_customer'] ) ? $email_settings[ $key . '_email_additional_content_customer'] : $email_additional_content_reset_pass,
						'placeholder' => $email_additional_content_reset_pass,
						'type'     => 'textarea',
						'show'     => true,
						'option_key'=> $key . '_email_additional_content_customer',
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
					);
				}
				if ( 'customer_on_hold_order' == $key ) {
					$settings[ $key . '_email_additional_content_customer' ] = array(
						'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
						'default'  => isset($email_settings[ $key . '_email_additional_content_customer']) ? stripslashes($email_settings[ $key . '_email_additional_content_customer']) :  '',
						'placeholder' => '',
						'type'     => 'textarea',
						'show'     => true,
						'option_key'=> $key . '_email_additional_content_customer',
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
					);
				}
				if ( 'customer_completed_order' == $key ) {
					$settings[ $key . '_email_additional_content_customer' ] = array(
						'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
						'default'  => isset($email_settings[ $key . '_email_additional_content_customer' ]) ? stripslashes($email_settings[ $key . '_email_additional_content_customer']) :  '',
						'placeholder' => 'We have finished processing your order.',
						'type'     => 'textarea',
						'show'     => true,
						'option_key'=> $key . '_email_additional_content_customer',
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
					);
				}
				if ( 'customer_processing_order' == $key ) {
					$settings[ $key . '_email_additional_content_customer' ] = array(
						'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
						'default'  => isset($email_settings[ $key . '_email_additional_content_customer' ]) ? stripslashes($email_settings[ $key . '_email_additional_content_customer']) :  '',
						'placeholder' => 'We have finished processing your order.',
						'type'     => 'textarea',
						'show'     => true,
						'option_key'=> $key . '_email_additional_content_customer',
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
					);
				}
				if ( 'customer_note' == $key ) {
					$settings[ $key . '_email_additional_content_customer' ] = array(
						'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
						'default'  => isset($email_settings[ $key . '_email_additional_content_customer' ]) ? stripslashes($email_settings[ $key . '_email_additional_content_customer']) :  '',
						'placeholder' => '',
						'type'     => 'textarea',
						'show'     => true,
						'option_key'=> $key . '_email_additional_content_customer',
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type' => 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
					);
				}
					if ( 'customer_reset_password' !== $key && 'customer_new_account' !== $key ) {
						$settings[ $key . '_additional_content_customer'] = array(
							'title'    => esc_html__( 'Email Additional Content', 'wooflow-email-customizer' ),
							'default'  => isset($email_settings['additional_content']) ? stripslashes($email_settings['additional_content']) : '',
							'placeholder' => '',
							'type'     => 'text',
							'show'     => true,
							'option_key'=> 'additional_content',
							'option_name' => 'woocommerce_' . $key . '_settings',
							'option_type' => 'array',
							'class'		=> $key . '_sub_menu all_cusomer_status_submenu additional_content',
						);
					}
				
			} 
				if ( 'customer_reset_password' == $key || 'customer_new_account' == $key ) {
					$customer_code = '{site_title} <br> {username} <br> {user_full_name} <br> {user_first_name} <br> {user_last_name} <br> {user_email} <br> {account_button} <br> {reset_password_button}';
				} else {
					$customer_code = '{site_title} <br> {customer_first_name} <br> {customer_last_name} <br> {order_date} <br> {order_number} <br> {customer_email} <br> {customer_full_name} <br> {customer_username}';
				}

				$settings[ $key . '_codeinfoblock_customer' ] = array(
					'title'    => esc_html__( 'Available Placeholders:', 'wooflow-email-customizer' ),
					'default'  => '<code> ' . $customer_code . ' </code>',
					'type'     => 'codeinfo',
					'show'     => true,
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu border_bottom_none',
				);
				
		}
		return $settings;
	}
}
