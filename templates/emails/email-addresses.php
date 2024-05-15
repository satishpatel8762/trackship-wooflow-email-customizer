<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );

if ( 'trackship_SaaS' == $email_template ) {
	wc_get_template(  'emails/trackship-template/trackship-saas-email-addresses.php', 
		array(
			'order' => $order,
			'sent_to_admin' => $sent_to_admin,
			'plain_text' => $plain_text,
			'email' => $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'zorem' == $email_template ) {
	wc_get_template(  'emails/zorem-template/zorem-email-addresses.php', 
		array(
			'order' => $order,
			'sent_to_admin' => $sent_to_admin,
			'plain_text' => $plain_text,
			'email' => $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'woocommerce' == $email_template ) {
	wc_get_template(  'emails/woocommerce-template/woocommerce-email-addresses.php', 
		array(
			'order' => $order,
			'sent_to_admin' => $sent_to_admin,
			'plain_text' => $plain_text,
			'email' => $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
}
