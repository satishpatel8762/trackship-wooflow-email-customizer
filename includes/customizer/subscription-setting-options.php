<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Subscritpion_Options {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return Subscritpion_Options
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
	
			$Subscription_emails = array(
				'new_renewal_order'                 => esc_html__( 'New Renewal Order', 'wooflow-email-customizer' ),
				'new_switch_order'					=> esc_html__( 'Subscription Switched', 'wooflow-email-customizer' ),
				'customer_processing_renewal_order' => esc_html__( 'Processing Renewal order', 'wooflow-email-customizer' ),
				'customer_completed_renewal_order'  => esc_html__( 'Completed Renewal Order', 'wooflow-email-customizer' ),
				'customer_on_hold_renewal_order'    => esc_html__( 'On-hold Renewal Order', 'wooflow-email-customizer' ),
				'customer_completed_switch_order'   => esc_html__( 'Subscription Switch Complete', 'wooflow-email-customizer' ),
				'customer_renewal_invoice'      	=> esc_html__( 'Customer Renewal Invoice', 'wooflow-email-customizer' ),
				'cancelled_subscription'      		=> esc_html__( 'Cancelled Subscription', 'wooflow-email-customizer' ),
				'expired_subscription'				=> esc_html__( 'Expired Subscription', 'wooflow-email-customizer' ),
				'suspended_subscription'			=> esc_html__( 'Suspended Subscription', 'wooflow-email-customizer' ),
			);
	
			foreach ( $Subscription_emails as $key => $value ) {
				$default_value = Wooflow_Email_Customizer()->default_content_customizer->get_value();
				$email_settings = get_option('woocommerce_' . $key . '_settings', array());
				$admin_order = array( 'new_renewal_order', 'new_switch_order', 'cancelled_subscription', 'expired_subscription', 'suspended_subscription' );
				$settings[ 'customer_email_status' ] = array(
					'title'    => esc_html__( 'Email Type', 'wooflow-email-customizer' ),
					'type'     => 'opt_select',
					'default'  => isset($email_type) ? $email_type : 'new_order',
					'show'     => true,
					'options'  => $Subscription_emails,
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
				$settings[ $key . '_subject_subscription' ] = array(
					'title'    => esc_html__( 'Email Subject', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['subject']) ? stripslashes($email_settings['subject']) : $default_value[ $key . '_subject'],
					'placeholder' => $default_value[ $key . '_subject'],
					'type'     => 'text',
					'show'     => true,
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'subject',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
				);
				$settings[ $key . '_heading_subscription' ] = array(
					'title'    => esc_html__( 'Email Heading', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings['heading']) ? stripslashes($email_settings['heading']) : $default_value[ $key . '_heading'],
					'placeholder' => $default_value[ $key . '_heading'],
					'type'     => 'text',
					'show'     => true,
					'class'	=> 'heading',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_key'=> 'heading',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
				);
				$settings[ $key . '_email_additional_content_subscription' ] = array(
					'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
					'default'  => isset($email_settings[ $key . '_email_additional_content_subscription']) ? stripslashes($email_settings[ $key . '_email_additional_content_subscription']) :  '' ,
					'placeholder' => '',
					'type'     => 'textarea',
					'show'     => true,
					'option_key'=> $key . '_email_additional_content_subscription',
					'option_name' => 'woocommerce_' . $key . '_settings',
					'option_type' => 'array',
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
				);
				$settings[ $key . '_additional_content_subscription'] = array(
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
				if ( 'customer_renewal_invoice' == $key ) {
					$settings[ 'customer_renewal_invoice_button_and_link' ] = array(
						'title'    => esc_html__( 'Make "Pay For This Order" A Button', 'wooflow-email-customizer' ),
						'default'  => isset($email_settings['customer_renewal_invoice_button_and_link']) ? $email_settings['customer_renewal_invoice_button_and_link'] : 0,
						'type'     => 'btn-link-tgl-btn',
						'show'     => true,
						'option_name' => 'woocommerce_' . $key . '_settings',
						'option_type'=> 'array',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu additional_content',
					);
				}
				$settings[ $key . '_codeinfoblock_subscription' ] = array(
					'title'    => esc_html__( 'Available Placeholders:', 'wooflow-email-customizer' ),
					'default'  => '<code> {site_title} <br> {order_date} <br> {order_number} <br> {customer_first_name} <br> {customer_last_name} <br> {customer_full_name} <br> {customer_email} <br>  		{customer_username} <br> </code>',
					'type'     => 'codeinfo',
					'show'     => true,
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu border_bottom_none',
				);
			}
		}
		return $settings;
	}	
}
