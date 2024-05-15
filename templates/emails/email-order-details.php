<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/email-order-details.php.
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

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );
$subscription_order_details_border = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'subscription_order_details_border', $wooflow_customizer['subscription_order_details_border'] );
$secondary_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'secondary_color', $wooflow_customizer['secondary_color'] );
$header_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_font_size', $wooflow_customizer['header_font_size'] );
$contant_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'contant_font_size', $wooflow_customizer['contant_font_size'] );
$all_border_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'all_border_color', $wooflow_customizer['all_border_color'] );
$show_product_image = $wec->get_customizer_option_value_from_array( 'email_customizer_settings_option', 'show_product_image', $wooflow_customizer['show_product_image'] );
if ( 'trackship_SaaS' == $email_template ) {
	remove_action( 'woocommerce_email_after_order_table', array( 'WC_Subscriptions_Order' , 'add_sub_info_email' ), 15, 3 );
	?>
<style>
	#body_content_inner table .woocommerce_table_style *{
		font-size:<?php echo esc_html( $contant_font_size ); ?>px !important;
	}
	h1{
		font-size:<?php echo esc_html( $header_font_size ); ?>px !important;
	}
	div.heading_with_content{
		margin:20px !important;
	}
	table.td.woocommerce_table_style{
		line-height:1.5 !important;
	}
	#body_content_inner table.td th, th.td.tlabel-subtotal, td.td.tvalue-subtotal, td.td.tvalue-total, th.td.tlabel-shipping, th.td.tlabel-paymentmethod,td.td.tvalue-shipping, td.td.tvalue-paymentmethod, th.td.tlabel-total, td.td.tvalue-vat, td.td.tlabel-vat, td.td.note-add, td.td.note-two, th.td.copify-td, #body_content_inner table.td td, td.td.note-add, td.td.note-two{
		padding-left:0px !important;
	}
	.subscription-table-background-color{
		background:#fff;
		border:<?php echo esc_html( $subscription_order_details_border ); ?>px solid <?php echo esc_html( $all_border_color ); ?> !important;
	}
	span.margin_set{
		display: table;
	}
	<?php
	$email_type = isset( $_GET['email_type'] ) ? sanitize_text_field($_GET['email_type']) : get_option( 'orderStatus', 'new_order' );
	if ( 'customer_processing_order' == $email_type || 'customer_pickup_order' == $email_type || 'customer_ready_pickup_order' == $email_type || 'customer_refunded_order' == $email_type || 'customer_invoice' == $email_type || 'customer_on_hold_order' == $email_type || 'customer_note' == $email_type ) {
		?>
		.subscription-table-background-color{
			margin:10px 0 10px;
		}
	<?php } ?>
</style>
<?php
	wc_get_template(  'emails/trackship-template/trackship-saas-email-order-details.php', 
		array( 
			'order'			=> $order,
			'sent_to_admin' => $sent_to_admin,
			'plain_text'	=> $plain_text,
			'email'			=> $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'zorem' == $email_template ) {
	wc_get_template(  'emails/zorem-template/zorem-email-order-details.php', 
		array( 
			'order'			=> $order,
			'sent_to_admin' => $sent_to_admin,
			'plain_text'	=> $plain_text,
			'email'			=> $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'woocommerce' == $email_template ) {
	remove_action( 'woocommerce_email_after_order_table', array( 'WC_Subscriptions_Order' , 'add_sub_info_email' ), 15, 3 );
	wc_get_template(  'emails/woocommerce-template/woocommerce-email-order-details.php', 
		array( 
			'order'			=> $order,
			'sent_to_admin' => $sent_to_admin,
			'plain_text'	=> $plain_text,
			'email'			=> $email,
			'show_image'	=> $show_product_image,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
}
?>
