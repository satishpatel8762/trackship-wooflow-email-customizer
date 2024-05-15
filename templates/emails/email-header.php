<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );
$header_font_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_font_color', $wooflow_customizer['header_font_color'] );
$header_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_font_size', $wooflow_customizer['header_font_size'] );
$header_bg_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_bg_color', $wooflow_customizer['header_bg_color'] );
$container_padding_left_right = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_left_right', $wooflow_customizer['container_padding_left_right'] );
$container_background_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_background_color', $wooflow_customizer['container_background_color'] );
$woocommerce_email_background_color = get_option( 'woocommerce_email_background_color' );
$container_padding_top = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_top', $wooflow_customizer['container_padding_top'] );
$container_padding_bottom = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_bottom', $wooflow_customizer['container_padding_bottom'] );

if ( empty( $email ) ) {
	$email = array();
}
// echo '<pre>';print_r($_GET);
if ( 'trackship_SaaS' == $email_template ) {
	?>
		<style>
			h4, ol li{
				font-size: 15px;line-height: 1.3;
			}
			#body_content_inner p {
				margin: 0 0 10px !important;
			}
		#wrapper {max-width:100%}
		<?php if ( !isset( $_GET['wooflow-email-customizer-preview'] ) && !isset( $_GET['action'] ) ) { ?>
			@media screen and (max-width: 600px) {
			#template_container, #template_footer{
				/* width: 500px !important; */
				max-width: 100% !important;
			}
			div #header_wrapper {
				padding: 0 !important;
				font-size: 24px;
			}
			#body_content table > tbody > tr > td {
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			.woocommerce_table_style tr, .subscription-table tr.woo_label_tr {
				line-height:1.3 !important;
			}
			#body_content table .subscription-table > tbody > tr > td {
				line-height:1.3px !important;
			}
			#body_content_inner {
				padding:20px 20px !important;
			}
			#body_content_inner *, #template_footer *{
				font-size:13px !important;
			}
			#body_content_inner a.user_id{
				font-size:13px !important;
			}
			#body_content_inner h1{
				font-size:18px !important;
			}
			#body_content_inner table.trackship_order_table{
				margin-bottom:10px !important;
			}
		}
		<?php } ?>
		@media only screen and (max-width:500px) {
		/* For mobile phones: */
			table#template_container{
				padding: 0 10px !important;
			}
			#header_wrapper {
				padding: 0 !important;
				font-size: 24px;
			}
		}
		@media only screen and (max-width:768px) {
		/* For mobile phones: */
			table#template_container{
				padding: 0 10px !important;
			}
		}
		td#credit{
			padding:0;
		}
		h1{
			text-align:left !important;
			color:<?php echo esc_html( $header_font_color ); ?> !important;
			font-size:<?php echo esc_html( $header_font_size ); ?>px !important;
		}
		#template_footer{
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
		}
		#body_content_inner {
			padding-top:<?php echo esc_html( $container_padding_top ); ?>px !important;
			padding-bottom:<?php echo esc_html( $container_padding_bottom ); ?>px !important;
			padding-left:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
			padding-right:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
			background-color: #ffffff !important;
		}
		#body_content{
			background-color: #ffffff !important;
		}
		#template_body{
			border-radius:20px;
			overflow: hidden;
		}
		#template_header{
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
		}
		#header_wrapper{
			padding:0 !important;
			padding-top:25px !important;
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
		}
		#template_header p img {
			margin-bottom:20px !important;
		}
		#template_header p{
			margin:0;
		}
		table#template_container{
			border:0 !important;
			border-width:0 !important;
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
			padding: 0 0px;
		}
		.additional_content_h6, div.last_message{
			margin-bottom:0 !important;
			background:#fff;
		}
		button{
			cursor: pointer !important;
		}
		body {
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
		}
	</style>
<?php
	wc_get_template(  'emails/trackship-template/trackship-saas-email-header.php', 
		array( 
			'email_heading' => $email_heading,
			'email'		=> $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'zorem' == $email_template ) {
	wc_get_template(  'emails/zorem-template/zorem-email-header.php', 
		array( 
			'email_heading' => $email_heading,
			'email'		=> $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'woocommerce' == $email_template ) {
	?>
	<style>
		h4, ol li{
			font-size: 15px;line-height: 1.3;
		}
		#body_content_inner p {
			margin: 0 0 10px !important;
		}
		#wrapper {max-width:100%}
		@media screen and (max-width: 600px) {
			#header_wrapper {
				padding-left: 20px !important;
				padding-right: 20px !important;
				font-size: 24px;
			}
			.woocommerce_table_style tr, .subscription-table tr.woo_label_tr {
				line-height:1.3 !important;
			}
			#body_content table .subscription-table > tbody > tr > td {
				line-height:1.3 !important;
			}
			#body_content_inner {
				padding: 20px !important;
			}
			#body_content_inner *, #template_footer *{
				font-size:13px !important;
			}
			#header_wrapper h1{
				font-size:18px !important;
			}
			#body_content_inner h2{
				margin:0 !important;
			}
			#body_content_inner table#addresses tr td {
				padding-left:0 !important;
				padding-right:0 !important;
			}
			#wrapper {
				padding-top:0 !important;
			}
		}
		@media only screen and (max-width:500px) {
		/* For mobile phones: */
			div#template_header_image p img{
				width:200px !important;
			}
			.img_completed_order img {
				height:35px !important;
			}
		}
		body {
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
		}
	</style>
	<?php
	wc_get_template(  'emails/woocommerce-template/woocommerce-email-header.php', 
		array( 
			'email_heading' => $email_heading,
			'email'		=> $email,
		),
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
}
?>
