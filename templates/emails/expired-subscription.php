<?php
/**
 * Cancelled Subscription email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/expired-subscription.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); 

// @hooked Wooflow_Email_Customizer::email_main_text_area

do_action( 'woocommerce_email_designer_details', $subscription, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_details', $subscription, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $subscription, $sent_to_admin, $plain_text, $email );

//Show user-defined additonal content - this is set in each email's settings.
/* translators: #%s: url */
$additional_content = ( isset( $additional_content ) && ! empty( $additional_content ) ) ? $additional_content : sprintf( esc_html__( 'Thanks for using %s', 'wooflow-email-customizer' ), get_bloginfo( 'name' ) );
?>
<p class="additional_content_h6"><?php esc_html_e( $additional_content ); ?></p>

<?php
do_action( 'woocommerce_email_footer', $email );
