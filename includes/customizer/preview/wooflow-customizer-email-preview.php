<?php 
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
?>
<html>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width" />
	<style type="text/css" id="wooflow_email_design_custom_css">
		<?php
		$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
		$wec = Wooflow_Email_Customizer()->default_content_customizer;
		$defualt = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'custom_style_textarea', $wooflow_customizer['custom_style_textarea'] );
		echo esc_html( !empty( $defualt ) );
		?>
		.woocommerce-store-notice.demo_store, .mfp-hide {display: none;}
		body{
			margin:0 !important;
		}
	</style>
</head>
	<body class="ec_preview_body" <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="margin:0 !important;"">
		<div id="overlay"></div>
		<div id="ec_preview_wrapper" style="display: block;">
			<?php Wooflow_Email_Customizer()->wec_admin->get_preview_email(); ?>
		</div>
		<?php
			do_action( 'woomail_footer' );
			wp_footer();
		?>
	</body>
</html>
