<?php
/**
 * Failed-renewal-authentication email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-gateway-stripe/templates/failed-renewal-authentication.php.
 *
 * HOWEVER, on occasion WooCommerce_Stripe will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package WooCommerce_Stripe\Templates\Emails
 * @version 4.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @hooked WC_Emails::email_header() Output the email header
do_action( 'woocommerce_email_header', $email_heading, $email );


// @hooked Wooflow_Email_Customizer::email_main_text_area

do_action( 'woocommerce_email_designer_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_subscriptions_email_order_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_footer', $email );
