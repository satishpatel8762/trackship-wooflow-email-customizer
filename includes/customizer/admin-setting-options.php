<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Options {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return Admin_Options
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
		add_filter( 'wooflow_customizer_setting_options', array( $this, 'admin_options_array' ) );
	}

	public function admin_options_array( $settings ) {

		$settings[ 'customer_email' ] = array(
			'title'	=> esc_html__( 'Email Type and Text', 'wooflow-email-customizer' ),
			'type'     => 'section',
			'parent'	=> 'email_settings_two',
			'show'     => true,	
			'class' => 'sub_second_options_panel',
		);
	
		$admin_email = array(
			'new_order'                         => esc_html__( 'New Order', 'wooflow-email-customizer' ),
			'cancelled_order'                   => esc_html__( 'Cancelled Order', 'wooflow-email-customizer' ),
			'failed_order'                      => esc_html__( 'Failed Order', 'wooflow-email-customizer' ),
		);
	
		foreach ( $admin_email as $key => $value ) {
				
			$email_settings = get_option('woocommerce_' . $key . '_settings', array());
			$settings[ 'customer_email_status' ] = array(
				'title'    => esc_html__( 'Email Type', 'wooflow-email-customizer' ),
				'type'     => 'opt_select',
				'default'  => isset($email_type) ? $email_type : 'new_order',
				'show'     => true,
				'options'  => $admin_email,
			);
			$settings[ $key . '_enabled' ] = array(
				'default'  => !empty($email_settings['enabled']) && 'no' == $email_settings['enabled'] ? 0 : 1,
				'type'     => 'tgl-btn',
				'show'     => true,
				'option_name' => 'woocommerce_' . $key . '_settings',
				'option_key'=> 'enabled',
				'option_type'=> 'array',
				'class'		=> $key . '_sub_menu all_cusomer_status_submenu enabled',
			);
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
			$settings[ $key . '_subject_admin' ] = array(
				'title'    => esc_html__( 'Email Subject', 'wooflow-email-customizer' ),
				'default'  => isset($email_settings['subject']) ? stripslashes($email_settings['subject']) : Wooflow_Email_Customizer()->default_content_customizer->get_value()[ $key . '_subject_admin'],
				'placeholder' => Wooflow_Email_Customizer()->default_content_customizer->get_value()[ $key . '_subject_admin'],
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'woocommerce_' . $key . '_settings',
				'option_key'=> 'subject',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
			);
			$settings[ $key . '_heading_admin' ] = array(
				'title'    => esc_html__( 'Email Heading', 'wooflow-email-customizer' ),
				'default'  => isset($email_settings['heading']) ? stripslashes($email_settings['heading']) : Wooflow_Email_Customizer()->default_content_customizer->get_value()[ $key . '_heading_admin'],
				'placeholder' => isset(Wooflow_Email_Customizer()->default_content_customizer->get_value()[ $key . '_heading_admin']) ? Wooflow_Email_Customizer()->default_content_customizer->get_value()[ $key . '_heading_admin'] : '',
				'type'     => 'text',
				'show'     => true,
				'class'	=> 'heading',
				'option_name' => 'woocommerce_' . $key . '_settings',
				'option_key'=> 'heading',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
			);
			
			$settings[ $key . '_email_additional_content_admin' ] = array(
				'title'    => esc_html__( 'Email Content', 'wooflow-email-customizer' ),
				'default'  => isset($email_settings[ $key . '_email_additional_content_admin']) ? stripslashes($email_settings[ $key . '_email_additional_content_admin']) :  '',
				'placeholder' => '',
				'type'     => 'textarea',
				'show'     => true,
				'option_key'=> $key . '_email_additional_content_admin',
				'option_name' => 'woocommerce_' . $key . '_settings',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_cusomer_status_submenu email_additional_content',
			);

			$settings[ $key . '_codeinfoblock_admin' ] = array(
				'title'    => esc_html__( 'Available Placeholders:', 'wooflow-email-customizer' ),
				'default'  => '<code> {site_title} <br> {order_date} <br> {order_number} <br> {customer_first_name} <br> {customer_last_name} <br> {customer_full_name} <br> {customer_email} <br>  		{customer_username} <br> </code>',
				'type'     => 'codeinfo',
				'show'     => true,
				'class'		=> $key . '_sub_menu all_cusomer_status_submenu border_bottom_none',
			);
		}
		return $settings;
	}
}
