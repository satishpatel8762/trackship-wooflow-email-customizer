<?php

defined( 'ABSPATH' ) || exit;

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );
$layout = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_layout', $wooflow_customizer['footer_layout'] );
$footer_position = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_position', $wooflow_customizer['footer_position'] );
$container_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_width', $wooflow_customizer['container_width'] );
$footer_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_width', $wooflow_customizer['footer_width'] );
$container_padding_left_right = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_padding_left_right', $wooflow_customizer['container_padding_left_right'] );
$footer_background_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_background_color', $wooflow_customizer['footer_background_color'] );
$footer_border_bottom_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_border_bottom_width', $wooflow_customizer['footer_border_bottom_width'] );
$footer_border_bottom = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_border_bottom', $wooflow_customizer['footer_border_bottom'] );

if ( 'woocommerce' == $email_template ) {
	if ( 'Inside' == $footer_position ) { 
		?>
		<style>
			table#template_footer{
				width:100% !important;
				border-top-width:<?php echo esc_html( $footer_border_bottom_width ); ?>px !important;
				border-top-style:<?php echo esc_html( $footer_border_bottom ); ?> !important;
				background-color:<?php echo esc_html( $footer_background_color ); ?> !important;
			}
			</style>
	<?php } else { ?>
		<style>
			table#template_footer{
				width:<?php echo esc_html( $container_width ); ?>px !important;
				border-top-width:none !important;
				border-top-style:none!important;
			}
		</style>
	<?php } ?>
<?php } ?>

<style>
	table#footersocial p:last-child{
		margin:0 30px 0 !important;
	}
	#footersocial ul li a img{
		width:30px !important;
	}
	div#footersocial ul, table#template_footer p, div.footer_links{
		text-align:<?php echo esc_html( $layout ); ?> !important;
		padding-left:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
		padding-right:<?php echo esc_html( $container_padding_left_right ); ?>px !important;
	}
</style>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
							<?php if ( 'Inside' == $footer_position ) {	?>
							<tr>
								<td align="center" valign="top">
									<!-- Footer -->
									<table border="0" cellpadding="10" cellspacing="0" id="template_footer">
										<tr>
											<td valign="top">
												<table border="0" cellpadding="10" cellspacing="0" width="100%">
													<tr>
														<td colspan="2" valign="middle" id="credit">
															<?php do_action( 'wec_layout_email_footer_design' ); ?>
															<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- End Footer -->
								</td>
							</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
				<?php if ( 'Outside' == $footer_position ) { ?>
			
						<!-- Footer -->
						<tr>
							<td class="outside_footer_bg" align="center" valign="top">
								<table border="0" cellpadding="10" cellspacing="0" id="template_footer">
									<tr>
										<td valign="top">
											<table border="0" cellpadding="10" cellspacing="0" width="100%">
												<tr>
													<td colspan="2" valign="middle" id="credit">
														<?php do_action( 'wec_layout_email_footer_design' ); ?>	
														<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php } ?>
						<!-- End Footer -->
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
