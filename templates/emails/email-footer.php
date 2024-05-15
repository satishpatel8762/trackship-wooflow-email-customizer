<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
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

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );
$container_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_width', $wooflow_customizer['container_width'] );
$container_background_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_background_color', $wooflow_customizer['container_background_color'] );
$layout = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_layout', $wooflow_customizer['footer_layout'] );
$container_padding_left_right = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_left_right', $wooflow_customizer['container_padding_left_right'] );
$footer_text_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_text_color', $wooflow_customizer['footer_text_color'] );
$woocommerce_email_background_color = get_option( 'woocommerce_email_background_color' );

if ( 'trackship_SaaS' == $email_template ) {
	?>
	<style>
		td#credit p{
			color:<?php echo esc_html( $footer_text_color ); ?> !important;
		}
		table#template_footer{
			width:<?php echo esc_html( $container_width ); ?>px !important;
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
			border:0 !important;
		}
		table#template_footer p, #footersocial{
			text-align:<?php echo esc_html( $layout ); ?> !important;
			padding-left:15px !important;
			padding-right:15px !important;
		}
		td#credit{
			font-weight: 300;
		}
		@media only screen and (max-width:500px) {
		/* For mobile phones: */
			a.social-link-url > img{
				width: 40px !important;
			}
		}
	</style>
<?php
	wc_get_template(  'emails/trackship-template/trackship-saas-email-footer.php',
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'zorem' == $email_template ) {
	wc_get_template(  'emails/zorem-template/zorem-email-footer.php',
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
} elseif ( 'woocommerce' == $email_template ) {

	?>
	<style>
		table#template_footer{
			width:<?php echo esc_html( $container_width ); ?>px !important;
			background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;
			border:0 !important;
		}
	</style>
	<?php
	wc_get_template(  'emails/woocommerce-template/woocommerce-email-footer.php',
		'wooflow-email-customizer/', Wooflow_Email_Customizer()->get_plugin_path() . '/templates/'
	);
}
?>
