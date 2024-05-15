<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( !function_exists( 'cev_pro') ) {
	return;
}
$cev_verification_image_content = get_option('cev_verification_image_content', cev_pro()->plugin_dir_url() . 'assets/css/images/email-verification.png');
$cev_new_email_image_width  =  get_option( 'cev_email_content_widget_header_image_width', cev_pro()->customizer_options->defaults['cev_email_content_widget_header_image_width'] );
$cev_content_align_style = get_option( 'cev_content_align_style', cev_pro()->customizer_options->defaults['cev_content_align_style'] );
$cev_new_acoount_button_text = get_option( 'cev_new_acoount_button_text', cev_pro()->customizer_options->defaults['cev_new_acoount_button_text'] );
$cev_verification_content_font_color = get_option( 'cev_verification_content_font_color', cev_pro()->customizer_options->defaults['cev_verification_content_font_color'] );
$cev_header_content_font_size = get_option( 'cev_header_content_font_size', cev_pro()->customizer_options->defaults['cev_header_content_font_size'] );

$cev_widget_content_width_style = get_option( 'cev_widget_content_width_style', cev_pro()->customizer_options->defaults['cev_widget_content_width_style'] );
$cev_verification_content_background_color = get_option( 'cev_verification_content_background_color', cev_pro()->customizer_options->defaults['cev_verification_content_background_color'] );
$cev_verification_content_border_color = get_option( 'cev_verification_content_border_color', cev_pro()->customizer_options->defaults['cev_verification_content_border_color'] );
$cev_widget_content_padding_style = get_option( 'cev_widget_content_padding_style', cev_pro()->customizer_options->defaults['cev_widget_content_padding_style'] );

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$secondary_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'secondary_color', $wooflow_customizer['secondary_color'] );
$header_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_font_size', $wooflow_customizer['header_font_size'] );
$contant_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'contant_font_size', $wooflow_customizer['contant_font_size'] );
$fluid_button_font_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_font_color', $wooflow_customizer['fluid_button_font_color'] );

?>
<style>
	#wrapper {max-width:100%}
	<?php if ( !isset( $_GET['wooflow-email-customizer-preview'] ) ) { ?>
		@media only screen and (max-width:500px) {
		/* For mobile phones: */
			#template_container, #template_footer{
				width: 100% !important;
				max-width: 100% !important;
			}
		}
	@media screen and (max-width: 600px) {
		#header_wrapper {
			padding: 0 !important;
			font-size: 24px;
		}
		#body_content table > tbody > tr > td {
			padding-left: 0 !important;
			padding-right: 0 !important;
		}
		.woocommerce_table_style tr, .subscription-table tr.woo_label_tr {
			line-height:15px !important;
		}
		#body_content table .subscription-table > tbody > tr > td {
			line-height:10px !important;
		}
		#body_content_inner {
			font-size: 15px !important;
			padding:20px 20px !important;
		}
	}
	<?php } ?>
	p a{
		color:<?php echo esc_html( $fluid_button_font_color ); ?> !important;
	}
</style>
<div style="width:100%; margin-bottom: 0;margin-top: 10px;text-align:<?php esc_html_e( $cev_content_align_style ); ?>;" >
	<div style="text-align:<?php esc_html_e( $cev_content_align_style ); ?>;">
		<?php echo wp_kses_post( wpautop( $content ) );	?>
	</div>
</div>
<div style="text-align:<?php esc_html_e( $cev_content_align_style ); ?>;"><?php echo wp_kses_post( $footer_content ); ?></div>
