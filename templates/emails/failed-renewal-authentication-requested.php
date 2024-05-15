<?php
/**
 * Admin email about payment retry failed due to authentication
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

/**
 * Output the email header.
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

// @hooked Wooflow_Email_Customizer::email_main_text_area
do_action( 'woocommerce_email_designer_details', $order, $sent_to_admin, $plain_text, $email );

?>
<p><?php esc_html_e( 'The renewal order is as follows:', 'woocommerce-gateway-stripe' ); ?></p>

<?php

/**
 * Shows the order details table.
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
* Shows order meta data.
*/
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
* Shows customer details, and email address.
*/
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
* Output the email footer.
*/
do_action( 'woocommerce_email_footer', $email );
