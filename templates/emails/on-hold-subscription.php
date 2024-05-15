<?php
/**
 * Cancelled Subscription email
 *
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 2.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'woocommerce_email_header', $email_heading, $email );

// @hooked Wooflow_Email_Customizer::email_main_text_area

do_action( 'woocommerce_email_designer_details', $subscription, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_subscriptions_email_order_details', $subscription, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $subscription, $sent_to_admin, $plain_text, $email );

//Show user-defined additonal content - this is set in each email's settings.
/* translators: #%s: url */
$additional_content = ( isset( $additional_content ) && ! empty( $additional_content ) ) ? $additional_content : sprintf( esc_html__( 'Thanks for using %s', 'wooflow-email-customizer' ), get_bloginfo( 'name' ) );
?>
<p class="additional_content_h6"><?php esc_html_e( $additional_content ); ?></p>

<?php
do_action( 'woocommerce_email_footer', $email );
