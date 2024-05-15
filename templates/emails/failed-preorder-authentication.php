<?php
/**
 * Failed-preorder-authentication email
 * 
 * This template can be overridden by copying it to yourtheme/woocommerce-gateway-stripe/templates/failed-preorder-authentication.php.
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

do_action( 'woocommerce_email_header', $email_heading, $email );


$billing_email = $order->get_billing_email();
$billing_phone = $order->get_billing_phone();

// @hooked Wooflow_Email_Customizer::email_main_text_area
do_action( 'woocommerce_email_designer_details', $order, $sent_to_admin, $plain_text, $email );

?>
<?php if ( $email->get_custom_message() ) : ?>
	<blockquote><?php echo wpautop( wptexturize( $email->get_custom_message() ) ); ?></blockquote>
<?php endif; ?>

<?php
do_action( 'woocommerce_email_before_order_table', $order, false, $plain_text, $email );

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_after_order_table', $order, false, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

?>
<p>
<?php esc_html_e( 'Thanks for shopping with us.', 'woocommerce-gateway-stripe' ); ?>
</p>
<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
