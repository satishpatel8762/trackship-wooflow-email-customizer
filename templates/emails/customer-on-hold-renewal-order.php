<?php
/**
 * Customer on-hold renewal order email.
 *
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

/** 
 * 1. add hook 'woocommerce_email_designer_details' to pull in main text
 * 2. Remove static main text area.
 */

do_action( 'woocommerce_email_header', $email_heading, $email ); 
// @hooked Wooflow_Email_Customizer::email_main_text_area

do_action( 'woocommerce_email_designer_details', $order, $sent_to_admin, $plain_text, $email );

// @hooked WC_Subscriptions_Email::order_details() Shows the order details table.
// @since 2.1.0

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

//Show user-defined additonal content - this is set in each email's settings.
if ( 'We look forward to fulfilling your order soon.' == $additional_content ) {
	$default_content = Wooflow_Email_Customizer()->default_content_customizer->defalut_contant();
	echo esc_html( $default_content );
} else {
	?>
	<p class="additional_content_h6"><?php echo esc_html( $additional_content ); ?></p>
	<?php
}

do_action( 'woocommerce_email_footer', $email );
