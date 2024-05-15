<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;

$container_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_width', $wooflow_customizer['container_width'] );
$logo_position = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'logo_position', $wooflow_customizer['logo_position'] );
$logo_alignment = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'logo_alignment', $wooflow_customizer['logo_alignment'] );
$heading_text_alignment = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'heading_text_alignment', $wooflow_customizer['heading_text_alignment'] );
$header_image_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'logo_width', $wooflow_customizer['logo_width'] );

?>
<style>
	#wrapper {max-width:100%}
</style>
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
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="margin:0 !important;">
	<style type="text/css" id="wooflow_email_design_custom_css">
		<?php
			$setting = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
			$defualt = isset( $setting['custom_style_textarea'] ) ? $setting['custom_style_textarea'] : '';
			echo esc_html( $defualt['default'] );
		?>
	</style>
	<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="padding-top:20px;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="<?php echo esc_html( $container_width ); ?>px" id="template_container" style="border-radius:20px !important;box-shadow:none !important;">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="border:0;">
										<tr>
											<td id="header_wrapper" style="padding-top:0 !important">
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
											</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>	
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" id="template_body" width="100%">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table  border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top" style="padding:0">
															<div id="body_content_inner">
																<?php if ( !empty( $email_heading ) ) { ?>
																	<table class="trackship_order_table" width="100%" style="margin-bottom:16px;">
																		<tr>
																			<?php
																			$preview_id = get_option( 'email_selected_order_id', 'mockup' );
																			$admin_emails = array('new_order', 'cancelled_order', 'failed_order', 'new_renewal_order', 'new_switch_order', 'cancelled_subscription');
																			
																			if ( $email && is_object($email->object) && isset($email->object) && in_array( $email->id, $admin_emails ) && 'mockup' !== $preview_id && $preview_id ) {
																				?>
																				<td valign="top" style="padding:0;">
																					<h1 style="text-align: left;text-shadow:none;" >
																						<?php echo esc_html( $email_heading ); ?>
																					</h1>
																				</td>
																				<?php
																				$user_id = $email->object->get_user_id();
																				if (isset($user_id)) {
																					?>
																					<td style="padding:0;text-align: right;" width="120px">
																						<a class="user_id" style="font-size: 14px;margin: 0;" target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=trackship-api&/user-info?&user_id=' . $user_id)); ?>">Go to Store Info</a>
																					</td>
																					<?php
																				}
																			} else { ?>
																				<td valign="top" style="padding:0;">	
																					<h1 style="text-shadow:none;text-align:<?php esc_html_e( $heading_text_alignment ); ?>" >
																						<?php echo esc_html( $email_heading ); ?>
																					</h1>
																				</td>	
																			<?php } ?>
																		</tr>
																	</table>
																<?php } ?>
