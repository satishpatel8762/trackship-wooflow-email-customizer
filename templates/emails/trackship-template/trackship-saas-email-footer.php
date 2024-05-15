<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$container_width = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'container_width', $wooflow_customizer['container_width'] );
$woocommerce_email_background_color = get_option( 'woocommerce_email_background_color' );
$footer_position = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_position', $wooflow_customizer['footer_position'] );
$link_show = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'link_show', $wooflow_customizer['link_show'] );
$layout = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'footer_layout', $wooflow_customizer['footer_layout'] );
$facebook_link = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'facebook_link', $wooflow_customizer['facebook_link'] );
$twitter_link = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'twitter_link', $wooflow_customizer['twitter_link'] );
$linkedin_link = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'linkedin_link', $wooflow_customizer['linkedin_link'] );

?>
																</div>
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
								<tr>
									<table border="0" cellpadding="10" cellspacing="0" id="template_footer" width="100%" style="border:0 !important;width:<?php echo esc_html( $container_width ); ?>px !important;background-color:<?php echo esc_html( $woocommerce_email_background_color ); ?> !important;">
										<tr>
											<td valign="middle" id="credit" style="padding:0;">
												<table id="footersocial" border="0" cellpadding="10" cellspacing="0" width="100%">
													<tr>
														<?php if ( !empty( $facebook_link ) || !empty( $twitter_link ) || !empty( $linkedin_link ) ) { ?>
															<td valign="middle" id="credit" style="text-align: right;">
																<?php if ( !empty( $facebook_link ) ) { ?>
																	<a href="<?php echo esc_url( $facebook_link ); ?>" class="social-link-url" target="_blank" style=" text-decoration: none;line-height: 25px;">
																		<img src="<?php echo esc_url( Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/facebook.png' ); ?>" width="25px" style="width:40px !important;margin:0 5px;border-radius:50%;"/>
																	</a>
																<?php } ?>
															</td>
															<td valign="middle" id="credit" style="text-align: center;padding:5px !important; width:50px">
																<?php if ( !empty( $twitter_link ) ) { ?>
																	<a href="<?php echo esc_url( $twitter_link ); ?>" class="social-link-url" target="_blank" style="text-decoration: none;line-height: 25px;">
																		<img src="<?php echo esc_url( Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/twitter.png' ); ?>" width="25px" style="width:40px !important;margin:0 5px;border-radius:50%;"/>
																	</a>
																<?php } ?>
															</td>
															<td valign="middle" id="credit" style="text-align: left;">
																<?php if ( !empty( $linkedin_link ) ) { ?>
																	<a href="<?php echo esc_url( $linkedin_link ); ?>" class="social-link-url" target="_blank" style="text-decoration: none;line-height: 25px;">
																		<img src="<?php echo esc_url( Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/linkedin.png' ); ?>" width="25px" style="width:40px !important;margin:0 5px;border-radius:50%;"/>
																	</a>
																<?php } ?>
															</td>
														<?php } ?>
													</tr>
												</table>
												<?php 
													$settings = Wooflow_Email_Customizer()->email_admin;
													$login_settings = get_option( 'email_customizer_settings_option', array() );
													$mydate=getdate(gmdate('U'));
													$email_footer_text = isset ( $login_settings['email_footer_text'] ) ? $login_settings['email_footer_text'] : '';
													$email_footer_text = !empty( $email_footer_text ) ? $email_footer_text : 'Copyright ©' . $mydate['year'] . ' TrackShip, All rights reserved.';
												?>
												<p class="mobile_view_p"><?php echo esc_html( $email_footer_text ); ?></p>
											</td>
										</tr>	
									</table>
								</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
