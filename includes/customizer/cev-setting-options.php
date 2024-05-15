<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cev_Options {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return Cev_Options
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
		add_filter( 'wooflow_customizer_setting_options', array( $this, 'cev_options_array' ) );
	}

	public function cev_options_array( $settings ) {

		if ( class_exists( 'Customer_Email_Verification_Pro' ) ) {
			$settings[ 'customer_email' ] = array(
				'title'	=> esc_html__( 'Email Type and Text', 'wooflow-email-customizer' ),
				'type'     => 'section',
				'parent'	=> 'email_settings_two',
				'show'     => true,	
				'class' => 'sub_second_options_panel',
			);
	
			$cev_emails = array(
				'email_registration' => esc_html__( 'Registration', 'wooflow-email-customizer' ),
				'email_checkout'     => esc_html__( 'Checkout', 'wooflow-email-customizer' ),
				'email_login_otp'    => esc_html__( 'New Login OTP', 'wooflow-email-customizer' ),
				'email_login_auth'   => esc_html__( 'New Login Authentication', 'wooflow-email-customizer' ),
			);
	
			foreach ( $cev_emails as $key => $value ) {
				if ( 'email_registration' == $key ) {
					$settings['cev_verification_email_subject'] = array(
						'title'    => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_email_subject' ),
						'placeholder' => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_email_subject',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
					);
					$settings['cev_verification_email_heading'] = array(
						'title'    => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_email_heading' ),
						'placeholder' => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_email_heading',
						'option_type' => 'key',
						'refresh'   => true,
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
					);
				
					//Email content reg
					$settings['cev_verification_email_body'] = array(
						'title'    => esc_html__( 'Verification Message', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_email_body' ),
						'placeholder' => esc_html__( 'Verification Message', 'customer-email-verification' ),
						'type'     => 'textarea',
						'show'     => true,				
						'option_name' => 'cev_verification_email_body',
						'option_key'=> 'cev_verification_email_body',
						'option_type' => 'key',
						'refresh'   => true,
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu body',
					);
				} else if ( 'email_checkout' == $key ) {
					//Email Subject che
					$settings['cev_verification_email_subject_che'] = array(
						'title'    => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_email_subject_che' ),
						'placeholder' => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_email_subject_che',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject_che',
					);
				
					//Email Heading che
					$settings['cev_verification_email_heading_che'] = array(
						'title'    => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_email_heading_che' ),
						'placeholder' => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_email_heading_che',
						'option_type' => 'key',
						'refresh'   => true,
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading_che',
					);
				
					//Email content reg
					$settings['cev_verification_email_body_che'] = array(
						'title'    => esc_html__( 'Verification Message', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_email_body_che' ),
						'placeholder' => esc_html__( 'Verification Message', 'customer-email-verification' ),
						'type'     => 'textarea',
						'show'     => true,				
						'option_name' => 'cev_verification_email_body_che',
						'option_key'=> 'cev_verification_email_body_che',
						'option_type' => 'key',
						'refresh'   => true,
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu body_che',
					);
				} else if ( 'email_login_otp' == $key ) {
					//Email Subject che
					$settings['cev_verification_login_otp_email_subject'] = array(
						'title'    => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_login_otp_email_subject' ),
						'placeholder' => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_login_otp_email_subject',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
					);
				
					//Email Heading che
					$settings['cev_verification_login_otp_email_heading'] = array(
						'title'    => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_login_otp_email_heading' ),
						'placeholder' => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_login_otp_email_heading',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
						'refresh'   => true,
					);
				
					//Email content reg
					$settings['cev_verification_login_otp_email_content'] = array(
						'title'    => esc_html__( 'Email Content', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_login_otp_email_content' ),
						'placeholder' => esc_html__( 'Email Content', 'customer-email-verification' ),
						'type'     => 'textarea',
						'show'     => true,				
						'option_name' => 'cev_verification_login_otp_email_content',
						'option_key'=> 'cev_verification_login_otp_email_content',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu content',
						'refresh'   => true,
					);
				} else if ( 'email_login_auth' == $key ) {
					//Email Subject che
					$settings['cev_verification_login_auth_email_subject'] = array(
						'title'    => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_login_auth_email_subject' ),
						'placeholder' => esc_html__( 'Email Subject', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_login_auth_email_subject',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu subject',
					);

					//Email Heading che
					$settings['cev_verification_login_auth_email_heading'] = array(
						'title'    => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_login_auth_email_heading' ),
						'placeholder' => esc_html__( 'Email Heading', 'customer-email-verification' ),
						'type'     => 'text',
						'show'     => true,				
						'option_name' => 'cev_verification_login_auth_email_heading',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu heading',
						'refresh'   => true,
					);

					//Email content reg
					$settings['cev_verification_login_auth_email_content'] = array(
						'title'    => esc_html__( 'Email Content', 'customer-email-verification' ),
						'default'  => cev_pro()->customizer_options->get_option_val( 'cev_verification_login_auth_email_content' ),
						'placeholder' => esc_html__( 'Email Content', 'customer-email-verification' ),
						'type'     => 'textarea',
						'show'     => true,				
						'option_name' => 'cev_verification_login_auth_email_content',
						'option_key'=> 'cev_verification_login_auth_email_content',
						'option_type' => 'key',
						'class'		=> $key . '_sub_menu all_cusomer_status_submenu content',
						'refresh'   => true,
					);
				}
				//Available variables
				$settings[ $key . 'cev_email_code_block'] = array(
					'title'    => esc_html__( 'Available Variables', 'customer-email-verification' ),
					'default'  => '<code>You can use HTML tags : &lt;a&gt;, &lt;strong&gt;, &lt;i&gt;	 and placeholders:{site_title}<br>{cev_user_verification_pin}<br>{cev_user_verification_link}<br>{cev_display_name}<br>{cev_change_pw_btn}<br>{login_browser}<br>{login_device}<br>{login_time}<br>{login_ip}</code>','You can use HTML tag : <strong>, <i>',				
					'type'     => 'codeinfo',
					'show'     => true,				
					'class'		=> $key . '_sub_menu all_cusomer_status_submenu content',
				);
			}
		}
		return $settings;
	}	
}
