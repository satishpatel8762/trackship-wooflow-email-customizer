<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates/Emails
 * @version 4.0.0
 */

/** 
 * 1. add hook 'woocommerce_email_designer_details' to pull in main text
 * 2. Remove static main text area.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$email_settings = get_option('woocommerce_customer_reset_password_settings', array());
$wooflow_customizer_default_content_customizer = Wooflow_Email_Customizer()->default_content_customizer->get_value();
$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$wooflow_email_template = $wec->get_customizer_option_value_from_array( 'email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );
$fluid_button_size = $wec->get_customizer_option_value_from_array( 'email_customizer_settings_option', 'fluid_button_size', $wooflow_customizer['fluid_button_size'] );
$fluid_button_radius = $wec->get_customizer_option_value_from_array( 'email_customizer_settings_option', 'fluid_button_radius', $wooflow_customizer['fluid_button_radius'] );
$fluid_button_font_color = $wec->get_customizer_option_value_from_array( 'email_customizer_settings_option', 'fluid_button_font_color', $wooflow_customizer['fluid_button_font_color'] );
$fluid_button_background_color = $wec->get_customizer_option_value_from_array( 'email_customizer_settings_option', 'fluid_button_background_color', $wooflow_customizer['fluid_button_background_color'] );

$email_additional_content = $wec->get_customizer_option_value_from_array( 'woocommerce_customer_reset_password_settings', 'customer_reset_password_email_additional_content_customer', $wooflow_customizer_default_content_customizer['customer_reset_password_email_additional_content_customer'] );

$email_additional_content = html_entity_decode( $email_additional_content );
$user_data = get_userdata( $user_id );
$email_additional_content = Wooflow_Email_Customizer()->default_content_customizer->wec_customer_new_account_and_reset_password_str_replace( $user_data, $email_additional_content );

if ( 'trackship_SaaS' !== $wooflow_email_template ) {
	do_action( 'woocommerce_email_header', $email_heading, $email );
} else {
	$email_heading_h1 = isset($email_settings['heading']) ? $email_settings['heading'] : '';
	$padding = '10px';
	if ( '' == $email_heading_h1 ) {
		$email_heading = '';
		$padding = '0px';
	}
	do_action( 'woocommerce_email_header', $email_heading, $email );
}

echo wp_kses_post( wpautop( wptexturize( $email_additional_content ) ) );

if ( 15 == $fluid_button_size ) { ?>
	<style>
		p.customer_account_btn{
			width:160px !important;
			padding:12px 0;
			border-radius:<?php echo esc_html( $fluid_button_radius ); ?>px !important;
		}
	</style>
<?php } else { ?>
	<style>
		p.customer_account_btn{
			width:200px !important;
			padding:12px 0;
			border-radius:<?php echo esc_html( $fluid_button_radius ); ?>px !important;
		}
	</style>
<?php } ?>
	
<?php do_action( 'woocommerce_email_footer', $email ); ?>
