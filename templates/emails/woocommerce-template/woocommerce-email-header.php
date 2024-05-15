<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;

$container_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_width', $wooflow_customizer['container_width'] );
$logo_position = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'logo_position', $wooflow_customizer['logo_position'] );
$header_bg_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_bg_color', $wooflow_customizer['header_bg_color'] );
$heading_text_alignment = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'heading_text_alignment', $wooflow_customizer['heading_text_alignment'] );
$header_image_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'logo_width', $wooflow_customizer['logo_width'] );
$heading_padding_top = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'heading_padding_top', $wooflow_customizer['heading_padding_top'] );
$heading_padding_bottom = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'heading_padding_bottom', $wooflow_customizer['heading_padding_bottom'] );
$heading_padding_left_right = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'heading_padding_left_right', $wooflow_customizer['heading_padding_left_right'] );
$container_padding_top = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_top', $wooflow_customizer['container_padding_top'] );
$container_padding_bottom = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_bottom', $wooflow_customizer['container_padding_bottom'] );
$container_padding_left_right = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_left_right', $wooflow_customizer['container_padding_left_right'] );
$logo_alignment = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'logo_alignment', $wooflow_customizer['logo_alignment'] );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php esc_html_e( get_bloginfo( 'name', 'display' ) ); ?></title>
		<style type="text/css" id="wooflow_email_design_custom_css">
			<?php  
				$setting = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
				$defualt = isset($setting['custom_style_textarea']) ? $setting['custom_style_textarea'] : '';
				echo esc_html( $defualt['default'] );
			?>
		</style>
	</head>
	<style>
		h1{
			text-align:left !important;
		}
		#header_wrapper {
			padding-top:<?php echo esc_html( $heading_padding_top ); ?>px !important;
			padding-bottom:<?php echo esc_html( $heading_padding_bottom ); ?>px !important;
			padding-left:<?php echo esc_html( $heading_padding_left_right ); ?>px !important;
			padding-right:<?php echo esc_html( $heading_padding_left_right ); ?>px !important;
		}
		#body_content_inner {
			padding-top:<?php echo esc_html( $container_padding_top ); ?>px !important;
			padding-bottom:<?php echo esc_html( $container_padding_bottom ); ?>px !important;
			padding-left:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
			padding-right:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
		}
		#template_header{
			background-color:<?php echo esc_html( $header_bg_color ); ?> !important;
		}
		div#template_header_image p {
			padding-left:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
			padding-right:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
		}
	</style>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="margin:0 !important;">
	<style type="text/css" id="wooflow_email_design_custom_css">
		<?php
			$setting = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
			$defualt = isset($setting['custom_style_textarea']) ? $setting['custom_style_textarea'] : '';
			echo esc_html( $defualt['default'] );
		?>
		
	</style>
	<div style="min-height:100vh !important;" id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<div id="template_header_image" style="width:<?php esc_html_e( $container_width ); ?>px">
							<?php
								$img = get_option( 'woocommerce_email_header_image' );
							if ( empty( $img ) ) {
								$img =  isset($img) ? $img : '';
							}
							if ( $img && 'outside' == $logo_position ) {
								echo '<p style="padding:0 30px 0;text-align:' . esc_html( $logo_alignment ) . ';"><img src="' . esc_url( $img ) . '" alt="' . esc_html( get_bloginfo( 'name', 'display' ) ) . '" style="width:' . esc_html( $header_image_width ) . 'px;" /></p>';
							}
							?>
						</div>
						<table border="0" cellpadding="0" cellspacing="0" width="700" id="template_container" style="overflow:hidden;box-shadow:none !important">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0"  cellpadding="0" cellspacing="0" id="template_header" style="border-radius:0 !important;border:0;width:100%">
										<tr>
											<div>
												<td id="header_wrapper">
													<?php
													$img = get_option( 'woocommerce_email_header_image' );
													if (empty($img)) {
														$img =  isset($img) ? $img : '';
													}
													if ( $img && 'inside' == $logo_position ) {
														echo '<p style="text-align:' . esc_html( $logo_alignment ) . ';"><img src="' . esc_url( $img ) . '" alt="' . esc_html( get_bloginfo( 'name', 'display' ) ) . '" width="' . esc_html( $header_image_width ) . 'px;"/>'; 
														echo '</p>';
													}
													?>
													<h1 style="text-shadow:none; text-align:<?php esc_html_e( $heading_text_alignment ); ?>" >
														<?php echo esc_html( $email_heading ); ?>
													</h1>
												</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>	
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" id="template_body" style="width:100%">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table  border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top" style="padding:0">
															<div id="body_content_inner">
