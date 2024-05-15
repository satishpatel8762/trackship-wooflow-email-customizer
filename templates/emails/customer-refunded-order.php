<?php
/**
 * Customer refunded order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-refunded-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @package  WooCommerce/Templates/Emails
 * @version 3.5.0
 */

/** 
 * 1. add hook 'woocommerce_email_designer_details' to pull in main text
 * 2. Remove static main text area.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @hooked WC_Emails::email_header() Output the email header

do_action( 'woocommerce_email_header', $email_heading, $email );

// @hooked Wooflow_Email_Customizer::email_main_text_area

do_action( 'woocommerce_email_designer_details', $order, $sent_to_admin, $plain_text, $email );

// @hooked WC_Emails::order_details() Shows the order details table.
// @hooked WC_Structured_Data::generate_order_data() Generates structured data.
// @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
// @since 2.5.0

 do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

// @hooked WC_Emails::order_meta() Shows order meta data.

 do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

// @hooked WC_Emails::customer_details() Shows customer details

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

//Show user-defined additonal content - this is set in each email's settings.

if ( 'We hope to see you again soon.' == $additional_content ) {
	$default_content = Wooflow_Email_Customizer()->default_content_customizer->defalut_contant();
	echo esc_html( $default_content );
} else {
	?>
	<p class="additional_content_h6"><?php echo esc_html( $additional_content ); ?></p>
	<?php
}

// @hooked WC_Emails::email_footer() Output the email footer
do_action( 'woocommerce_email_footer', $email );
