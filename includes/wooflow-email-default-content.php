<?php

if (!defined('ABSPATH')) {
	exit;
}

class Default_Content_Templates {

	public $plugin_path;

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return Default_Content_Templates
	 */
	public static function get_instance() {

		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;

	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @return string plugin path
	 */
	public function get_plugin_path() {
		if (isset($this->plugin_path)) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
		return $this->plugin_path;
	}

	public static function get_plugin_domain() {
		return __FILE__;
	}

	/*
	* plugin file directory function
	*/
	public function plugin_dir_url() {
		return plugin_dir_url(__FILE__);
	}

	/**
	 * Hook in email header with access to the email object
	 *
	 * @param string $email_heading.
	 * @param object $email the email object.
	 * @return void
	 */
	public function add_custom_header_to_woocommerce_email($email_heading, $email) {
		wc_get_template(
			'emails/email-header.php',
			array(
				'email_heading' => $email_heading,
				'email'		=> $email,
			),
			'wooflow-email-customizer/',
			$this->get_plugin_path() . '/templates/'
		);
	}

	public function wec_customer_new_account_and_reset_password_str_replace($user_id, $new_account_and_reset_password_str_replace) {
		$user_name = $user_id->user_login;
		$key = get_password_reset_key($user_id);

		// Get user data
		$user_data = get_userdata($user_id->ID);

		$first_name = $user_data->first_name;
		$last_name = $user_data->last_name;
		$billing_company = $user_data->billing_company;

		$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
		$wec = Wooflow_Email_Customizer()->default_content_customizer;
		$fluid_button_background_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_background_color', $wooflow_customizer['fluid_button_background_color']);
		$wooflow_email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template']);
		$fluid_button_font_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_font_color', $wooflow_customizer['fluid_button_font_color']);
		$connect_your_store = '<p class="customer_account_btn" style="text-align:center;border-style: solid;border-radius: 5px;padding: 5px;margin:0 !important;cursor: pointer !important;border-color:' . esc_html($fluid_button_background_color) . ';background:' . esc_html($fluid_button_background_color) . '"><a style="text-decoration: none;color:' . esc_html($fluid_button_font_color) . '" href="' . esc_url(wc_get_page_permalink('myaccount')) . '"> ' . __('Connect your store', 'wooflow-email-customizer') . '</a></p>';
		$account_button = '<p class="customer_account_btn" style="text-align:center;border-style: solid;border-radius: 5px;padding: 5px;margin:0 !important;cursor: pointer !important;border-color:' . esc_html($fluid_button_background_color) . ';background:' . esc_html($fluid_button_background_color) . '"><a style="text-decoration: none;color:' . esc_html($fluid_button_font_color) . '" href="' . esc_url(wc_get_page_permalink('myaccount')) . '"> ' . __('My account', 'wooflow-email-customizer') . '</a></p>';
		$reset_password_link = esc_url_raw(wp_lostpassword_url() . '?action=rp&key=' . $key . '&login=' . rawurlencode($user_name));
		$reset_password_button = '<p class="customer_account_btn" style="text-align:center;border-style: solid;border-radius: 5px;padding: 5px;margin:0 !important;cursor: pointer !important;border-color:' . esc_html($fluid_button_background_color) . ';background:' . esc_html($fluid_button_background_color) . '"><a style="text-decoration: none;color:' . esc_html($fluid_button_font_color) . '" href="' . $reset_password_link . '">' . __('Reset Password', 'wooflow-email-customizer') . '</a></p>';

		$company_name = $billing_company ? $billing_company : 'Detectives Ltd.';
		$email = $user_data->user_email;
		$customer_first_name = $first_name ? $first_name : '';
		$customer_last_name  = $last_name ? $last_name : '';
		$customer_full_name = $customer_first_name . ' ' . $customer_last_name;
		$new_account_and_reset_password_str_replace = str_replace('{user_first_name}',  $first_name, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{user_last_name}',  $last_name, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{user_company_name}', $company_name, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{username}', $user_name, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{user_email}', $email, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{user_full_name}', $customer_full_name, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{site_title}', get_bloginfo('name'), $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{account_button}', $account_button, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{connect_your_store}', $connect_your_store, $new_account_and_reset_password_str_replace);
		$new_account_and_reset_password_str_replace = str_replace('{reset_password_button}', $reset_password_button, $new_account_and_reset_password_str_replace);

		return $new_account_and_reset_password_str_replace;
	}

	/**
	 * Hook in main text areas for customized emails
	 *
	 * @param  object  $order   the order object.
	 * @param  boolean $sent_to_admin if sent to admin.
	 * @param  boolean $plain_text if plan text.
	 * @param  object  $email the Email object.
	 * @return void
	 */
	public function email_main_text_area($order, $sent_to_admin, $plain_text, $email) {

		$email_status = $email->id;

		$email_settings = get_option('woocommerce_' . $email_status . '_settings', array());

		$admin_emails = array('new_order', 'cancelled_order', 'failed_order');

		if (in_array($email_status, $admin_emails)) {
			$email_additional_content = isset($email_settings[$email_status . '_email_additional_content_admin']) ? $email_settings[$email_status . '_email_additional_content_admin'] : '';
		}

		$customer_emails = array('customer_processing_order', 'customer_completed_order', 'customer_refunded_order', 'customer_on_hold_order', 'customer_invoice', 'customer_note', 'customer_new_account', 'customer_reset_password');

		if (in_array($email_status, $customer_emails)) {
			$email_additional_content = isset($email_settings[$email_status . '_email_additional_content_customer']) ? $email_settings[$email_status . '_email_additional_content_customer'] : '';
		}

		$subscription_emails = array('new_renewal_order', 'new_switch_order', 'customer_processing_renewal_order', 'customer_completed_renewal_order', 'customer_completed_switch_order', 'customer_on_hold_renewal_order', 'cancelled_subscription', 'customer_renewal_invoice', 'expired_subscription', 'suspended_subscription');

		if (in_array($email_status, $subscription_emails)) {
			$email_additional_content = isset($email_settings[$email_status . '_email_additional_content_subscription']) ? $email_settings[$email_status . '_email_additional_content_subscription'] : '';
		}

		//woocommerce-gateway-stripe - emails
		$woocommerce_gateway_stripe_emails = array('failed_renewal_authentication', 'failed_authentication_requested', 'failed_preorder_sca_authentication');

		if (in_array($email_status, $woocommerce_gateway_stripe_emails)) {
			$email_additional_content = isset($email_settings[$email_status . '_email_additional_content_other']) ? $email_settings[$email_status . '_email_additional_content_other'] : '';
		}

		$user =  wp_get_current_user();
		if ($user) {
			$username = $user->user_login;
		}

		$preview_id = get_option('email_selected_order_id', 'mockup');
		if ('mockup' !== $preview_id) {
			$order = new WC_Order($order);
		}

		$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
		$wec = Wooflow_Email_Customizer()->default_content_customizer;
		$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template']);
		if (isset($email_additional_content) && '' == $email_additional_content && 'trackship_saas' == $email_template) {
?>
			<style>
				h1 {
					margin: 0px 0 15px !important;
				}

				td a.user_id {
					display: block;
					margin: 0px 0 15px !important;
				}
			</style>
		<?php
			return;
		}

		$user_email   = $order->get_billing_email();
		$company_name = $order->get_shipping_company() ? $order->get_shipping_company() : 'Detectives Ltd.';
		$customer_first_name = $order->get_billing_first_name() ? $order->get_billing_first_name() : '';
		$customer_last_name  = $order->get_billing_last_name() ? $order->get_billing_last_name() : '';
		$customer_full_name = $customer_first_name . ' ' . $customer_last_name;
		$email_additional_content = str_replace(array('{order_number}'), array($order->get_order_number()), $email_additional_content);
		$email_additional_content = str_replace(array('{customer_first_name}'), array($order->get_billing_first_name()), $email_additional_content);
		$email_additional_content = str_replace(array('{customer_last_name}'), array($order->get_billing_last_name()), $email_additional_content);
		$email_additional_content = str_replace('{customer_company_name}', $company_name, $email_additional_content);
		$email_additional_content = str_replace('{customer_username}', $username, $email_additional_content);
		$email_additional_content = str_replace('{customer_email}', $user_email, $email_additional_content);
		$email_additional_content = str_replace('{customer_full_name}', $customer_full_name, $email_additional_content);
		$email_additional_content = str_replace(array('{site_title}'), array(get_bloginfo('name')), $email_additional_content);
		$order_date = $order->get_date_created();

		$order_date = $order->get_date_created();
		if ($order_date !== null) {
			$order_date = 'Date: ' . gmdate('d/m/Y', strtotime($order_date));
		} else {
			$order_date = '';
		}

		$email_additional_content = str_replace('{order_date}', $order_date, $email_additional_content);
		$order_id = get_option('email_selected_order_id', 'mockup');
		$order = wc_get_order($order_id);
		$pay_now_url = $order ? $order->get_checkout_payment_url() : '#';

		if ('woocommerce' == $email_template) {
		?>
			<style>
				p.first_span_text {
					margin: 10px 0 10px !important;
					display: contents;
				}
			</style>
		<?php
		} else {
		?>
			<style>
				p.first_span_text {
					margin: 0 0 16px !important;
				}
			</style>
			<?php
		}
		if ('new_order' == $email_status || 'cancelled_order' == $email_status || 'failed_order' == $email_status || 'new_renewal_order' == $email_status || 'new_switch_order' == $email_status || 'cancelled_subscription' == $email_status) {
			if ('' !== $email_additional_content) {
			?>
				<p class="first_span_text" style="font-weight: 100;">
					<?php esc_html_e($email_additional_content); ?>
				</p>
			<?php } else { ?>
				<p class="first_span_text" style="font-weight: 100;"></p>
			<?php } ?>
			<?php
		} else {
			if ('' !== $email_additional_content && 'customer_renewal_invoice' !== $email_status) {
			?>
				<p class="first_span_text" style="font-weight: 100;float: left;">
					<?php esc_html_e($email_additional_content); ?>
				</p>
			<?php }	else { ?>
				<p class="first_span_text" style="font-weight: 100;float: left;"></p>
			<?php } ?>
		<?php
		}
		$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
		$wec = Wooflow_Email_Customizer()->default_content_customizer;
		$fluid_button_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_size', $wooflow_customizer['fluid_button_size']);
		$fluid_button_radius = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_radius', $wooflow_customizer['fluid_button_radius']);
		$fluid_button_font_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_font_color', $wooflow_customizer['fluid_button_font_color']);
		$fluid_button_background_color = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'fluid_button_background_color', $wooflow_customizer['fluid_button_background_color']);
		$customer_renewal_invoice_button_and_link = isset($email_settings['customer_renewal_invoice_button_and_link']) ? $email_settings['customer_renewal_invoice_button_and_link'] : 0;
		if ('customer_renewal_invoice' == $email_status) {
		?>
			<style>
				button.invoice_btn {
					border: none;
					background-color: <?php echo esc_html($fluid_button_background_color); ?> !important;
				}

				button.invoice_btn a {
					color: <?php echo esc_html($fluid_button_font_color); ?> !important;
				}
			</style>
			<?php
			if (15 == $fluid_button_size) {
			?>
				<style>
					button.invoice_btn {
						width: 120px !important;
						padding: 12px 0;
						border-radius: <?php echo esc_html($fluid_button_radius); ?>px !important;
					}
				</style>
			<?php } else { ?>
				<style>
					button.invoice_btn {
						width: 160px !important;
						padding: 12px 0;
						border-radius: <?php echo esc_html($fluid_button_radius); ?>px !important;
					}
				</style>
			<?php
			}
			if (0 == $customer_renewal_invoice_button_and_link) {
			?>
				<p class="first_span_text" style="font-weight: 100;float: left;">
					<?php esc_html_e($email_additional_content); ?> <a class="customer_invoice_link" href="https://my.trackship.com/settings/#billing">Pay Now</a>
				</p>
			<?php
			} else {
			?>
				<p class="first_span_text" style="font-weight: 100;float: left;">
					<?php esc_html_e($email_additional_content); ?>
				</p>
				<p><button style="padding:10px;border-width: 1px;" class="invoice_btn"> <a style="text-decoration: auto;" class="customer_invoice_link" href="https://my.trackship.com/settings/#billing">Pay Now</a></button></p>
		<?php
			}
		}
	}

	/**
	 * Filter in custom email templates with priority to child themes
	 *
	 * @param string $template the email template file.
	 * @param string $template_name name of email template.
	 * @param string $template_path path to email template.	 
	 * @return string
	 */
	public function filter_locate_template($template, $template_name, $template_path) {

		// Make sure we are working with an email template.
		if (!in_array('emails', explode('/', $template_name))) {
			return $template;
		}

		// clone template.
		$_template = $template;

		// Get the woocommerce template path if empty.
		if (!$template_path) {
			global $woocommerce;
			$template_path = $woocommerce->template_url;
		}

		// Get our template path.
		$plugin_path = Wooflow_Email_Customizer()->get_plugin_path() . '/templates/';

		// Look within passed path within the theme - this is priority.
		$template = locate_template(array($template_path . $template_name, $template_name));

		// If theme isn't trying to override get the template from this plugin, if it exists.
		if (!$template && file_exists($plugin_path . $template_name)) {
			$template = $plugin_path . $template_name;
		}

		// else if we still don't have a template use default.
		if (!$template) {
			$template = $_template;
		}

		// Return template.
		return $template;
	}

	/**
	 * Set up the footer content
	 */
	public function email_footer_layout_content() {


		$login_settings = get_option('email_customizer_settings_option', array());
		// print_r($login_settings);exit;
		$footer_position = isset($login_settings['footer_position']) ? $login_settings['footer_position'] : 'Inside';
		$layout = isset($login_settings['footer_layout']) ? $login_settings['footer_layout'] : 'center';

		$facebook_link = isset($login_settings['facebook_link']) ? $login_settings['facebook_link'] : 'https://www.facebook.com/';
		$twitter_link = isset($login_settings['twitter_link']) ? $login_settings['twitter_link'] : 'https://twitter.com/';
		$linkedin_link = isset($login_settings['linkedin_link']) ? $login_settings['linkedin_link'] : 'https://www.linkedin.com/';
		$border_color = isset($login_settings['border_color']) ? stripslashes($login_settings['border_color']) : '#dedede';

		if ('left' == $layout) {
			$text_align = 'left';
			$margin_left = '48px';
		} else if ('center' == $layout) {
			$text_align = 'center';
		} else if ('right' == $layout) {
			$text_align = 'right';
			$margin_right = '48px';
		}
		?>

		<?php
		$footer_text = get_option('email_customizer_settings_option');
		$footer_text_add = isset($footer_text['footer_text_add']) ? $footer_text['footer_text_add'] : [];
		?>
		<style>
			@media only screen and (max-width:500px) {

				/* For mobile phones: */
				.footer_links {
					padding: 10px !important;
					margin: 0px !important;
				}

				div#footersocial ul,
				table#template_footer p {
					padding: 10px !important;
					margin: 0px !important;
				}

				.mobile_css {
					margin-bottom: 0px !important;
				}
			}
		</style>
		<?php
		if (!empty($footer_text_add)) {
			foreach ($footer_text_add as $key => $val) {
		?>
				<style>
					.footer_links span:last-child a {
						padding-right: 0 !important;
					}
				</style>
				<div class="footer_links" style="text-align:<?php echo esc_html_e($text_align); ?>; margin-left:<?php echo isset($margin_left) ? esc_html_e($margin_left) : ''; ?>; margin-right:<?php echo isset($margin_right) ? esc_html_e($margin_right) : ''; ?>;margin:10px 48px 10px 48px; ">
					<span><a style="text-decoration:auto;padding:5px;padding-left:0;" href="<?php echo esc_url($val); ?>"><?php esc_html_e($key); ?></a></span>
				</div>
		<?php
			}
		}
		?>

		<div class="mobile_css">
			<?php if (!empty($facebook_link) || !empty($twitter_link) || !empty($linkedin_link)) { ?>
				<table id="footersocial" border="0" cellpadding="10" cellspacing="0" width="100%">
					<tr>
						<?php if (!empty($facebook_link) || !empty($twitter_link) || !empty($linkedin_link)) { ?>
							<td valign="middle" id="credit" style="text-align: right;">
								<?php if (!empty($facebook_link)) { ?>
									<a href="<?php echo esc_url($facebook_link); ?>" class="social-link-url" target="_blank" style=" text-decoration: none;line-height: 25px;">
										<img src="<?php echo esc_url(Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/facebook.png'); ?>" width="25px" style="width:40px !important;margin:0 5px;border-radius:50%;" />
									</a>
								<?php } ?>
							</td>
							<td valign="middle" id="credit" style="text-align: center;padding:5px !important; width:50px">
								<?php if (!empty($twitter_link)) { ?>
									<a href="<?php echo esc_url($twitter_link); ?>" class="social-link-url" target="_blank" style="text-decoration: none;line-height: 25px;">
										<img src="<?php echo esc_url(Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/twitter.png'); ?>" width="25px" style="width:40px !important;margin:0 5px;border-radius:50%;" />
									</a>
								<?php } ?>
							</td>
							<td valign="middle" id="credit" style="text-align: left;">
								<?php if (!empty($linkedin_link)) { ?>
									<a href="<?php echo esc_url($linkedin_link); ?>" class="social-link-url" target="_blank" style="text-decoration: none;line-height: 25px;">
										<img src="<?php echo esc_url(Wooflow_Email_Customizer()->plugin_dir_url() . 'assets/images/linkedin.png'); ?>" width="25px" style="width:40px !important;margin:0 5px;border-radius:50%;" />
									</a>
								<?php } ?>
							</td>
						<?php } ?>
					</tr>
				</table>
			<?php } ?>
		</div>
	<?php
	}

	/**
	 * Add a notice about woocommerce being needed.
	 *
	 * @param array $args the order detials args.
	 */
	public function add_wc_order_email_args_images($args) {
		$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
		$wec = Wooflow_Email_Customizer()->default_content_customizer;
		$show_product_image = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'show_product_image', $wooflow_customizer['show_product_image']);

		if ('show' === $show_product_image) {
			$args['show_image'] = true;
		}
		return $args;
	}

	/**
	 * Hook in email footer with access to the email object
	 *
	 * @param string $email email footer.
	 * @param object $email the email object.
	 * @return void
	 */
	public function add_custom_footer_to_woocommerce_email($email) {
		wc_get_template('emails/email-footer.php', array('email' => $email), $this->get_plugin_path() . '/templates/');
	}

	/**
	 * Hook in email order details with access to the email object
	 *
	 * @param string $order, $sent_to_admin, $plain_text.
	 * @param object $email the email object.
	 * @return void
	 */
	public function add_woocommerce_email_order_details($order, $sent_to_admin, $plain_text, $email) {
		wc_get_template(
			'emails/email-order-details.php',
			array(
				'order'			=> $order,
				'sent_to_admin' => $sent_to_admin,
				'plain_text'	=> $plain_text,
				'email'			=> $email,
			),
			'wooflow-email-customizer/',
			$this->get_plugin_path() . '/templates/'
		);
	}

	/**
	 * Hook in email order details with access to the email object
	 *
	 * @param string $order, $sent_to_admin, $plain_text.
	 * @param object $email the email object.
	 * @return void
	 */
	public function add_woocommerce_customer_details($order, $sent_to_admin, $plain_text) {

		if (!is_a($order, 'WC_Order')) {
			return;
		}

		$fields = array_filter(apply_filters('woocommerce_email_customer_details_fields', array(), $sent_to_admin, $order), array($this, 'customer_detail_field_is_valid'));

		if (!empty($fields)) {
			wc_get_template(
				'emails/email-customer-details.php',
				array('fields' => $fields),
				'wooflow-email-customizer/',
				$this->get_plugin_path() . '/templates/'
			);
		}
	}

	/**
	 * Is customer detail field valid?
	 *
	 * @param  array $field Field data to check if is valid.
	 * @return boolean
	 */
	public function customer_detail_field_is_valid($field) {
		return isset($field['label']) && !empty($field['value']);
	}

	/**
	 * Hook in email order details with access to the email object
	 *
	 * @param string $order, $sent_to_admin, $plain_text.
	 * @param object $email the email object.
	 * @return void
	 */

	public function add_woocommerce_email_addresses($order, $sent_to_admin, $plain_text, $email) {
		if (empty($order)) {
			return;
		}

		if (class_exists('WC_Subscriptions_Manager')) {
			$subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'any'));
		}

		if ('customer_invoice' == $email->id && !empty($subscriptions)) {
			return;
		}

		wc_get_template(
			'emails/email-addresses.php',
			array(
				'order' => $order,
				'sent_to_admin' => $sent_to_admin,
				'plain_text' => $plain_text,
				'email' => $email,
			),
			'wooflow-email-customizer/',
			$this->get_plugin_path() . '/templates/'
		);
	}

	//template
	public function woo_email_template_path($template, $template_name, $template_path) {

		// Make sure we are working with an email template.
		if (!in_array('emails', explode('/', $template_name))) {
			return $template;
		}

		global $woocommerce;
		$_template = $template;
		if (!$template_path) {
			$template_path = $woocommerce->template_url;

			$plugin_path  = untrailingslashit(plugin_dir_path(__FILE__)) . '/template/';

			// Look within passed path within the theme - this is priority
			$template = locate_template(array($template_path . $template_name, $template_name));
		}

		if (!$template && file_exists($plugin_path . $template_name)) {
			$template = $plugin_path . $template_name;
		}

		if (!$template) {
			$template = $_template;
		}

		return $template;
	}

	/**
	 * Filter callback to replace {year} in email footer
	 *
	 * @param  string $string Email footer text.
	 * @return string         Email footer text with any replacements done.
	 */
	public function email_absolute_footer_text_replace($string) {
		$settings = Wooflow_Email_Customizer()->email_admin;
		$login_settings = get_option('email_customizer_settings_option', array());
		$mydate = getdate(gmdate('U'));
		$email_footer_text = isset($login_settings['email_footer_text']) ? $login_settings['email_footer_text'] : '';
		return !empty($email_footer_text) ? $email_footer_text : 'Copyright ©' . $mydate['year'] . ' TrackShip, All rights reserved.';
	}

	/**
	 * Get preview URL(admin load url)
	 *
	 */
	public function get_email_preview_url($status) {
		return add_query_arg(array(
			'action'	=> 'woocommerce_customizer_email_preview',
			'email_type'	=> $status
		), admin_url('admin-ajax.php'));
	}

	//options array (key and default value get)
	public function get_customizer_option_value_from_array($array, $key, $default_value) {

		$array_data = get_option($array);
		$value = '';

		if (isset($array_data[$key])) {
			$value = $array_data[$key];
		}

		if ('' == $value) {
			$value = $default_value;
		}
		return $value;
	}
	public function defalut_contant() {
	?>
		<p class="additional_content_h6">Questions? contact us at <a href="mailto:billing@trackship.com">billing@trackship.com</a></p>
<?php
	}

	//options array default content
	public function get_value() {

		$defualt_array = array(
			'new_order_subject_admin' => '[{site_title}]: New order #{order_number}',
			'new_order_subject' => '[{site_title}]: New order #{order_number}',
			'new_order_heading' => 'New Order: #{order_number}',
			'new_order_heading_admin' => 'New Order: #{order_number}',
			'new_order_email_additional_content_admin' => '',
			'new_order_additional_content' => '',

			'cancelled_order_subject_admin' => '[{site_title}]: Order #{order_number} has been cancelled',
			'cancelled_order_subject' => '[{site_title}]: Order #{order_number} has been cancelled',
			'cancelled_order_heading' => 'Order Cancelled: #{order_number}',
			'cancelled_order_heading_admin' => 'Order Cancelled: #{order_number}',
			'cancelled_order_email_additional_content_admin' => '',
			'cancelled_order_email_additional_content_customer' => '',
			'cancelled_order_additional_content' => '',

			'customer_processing_order_subject_customer' => 'Your {site_title} order is now processing',
			'customer_processing_order_subject' => 'Your {site_title} order is now processing',
			'customer_processing_order_heading_customer' => 'Thanks for shopping with us',
			'customer_processing_order_heading' => 'Thanks for shopping with us',
			'customer_processing_order_email_additional_content_customer' => '',
			'customer_processing_order_additional_content' => '',

			'customer_completed_order_subject_customer' => 'Your {site_title} order is now complete',
			'customer_completed_order_subject' => 'Your {site_title} order is now complete',
			'customer_completed_order_heading_customer' => 'Thanks for shopping with us',
			'customer_completed_order_heading' => 'Thanks for shopping with us',
			'customer_completed_order_email_additional_content_customer' => '',
			'customer_completed_order_additional_content' => '',

			'wcast_completed_email_content' => '',

			'customer_refunded_order_subject' => 'Your {site_title} order #{order_number} has been refunded',
			'customer_refunded_order_heading' => 'Order Refunded: #{order_number}',
			'customer_refunded_order_heading_customer' => 'Order Refunded: #{order_number}',
			'customer_refunded_order_email_additional_content_customer' => '',
			'customer_refunded_order_additional_content' => '',

			'customer_on_hold_renewal_order_subject' => 'Your {site_title} order is now complete',
			'customer_on_hold_renewal_order_heading' => 'Thank you for your renewal order',
			'customer_on_hold_renewal_order_email_additional_content_customer' => '',
			'customer_on_hold_renewal_order_additional_content' => '',

			'customer_on_hold_order_subject' => 'Your {site_title} order has been received!',
			'customer_on_hold_order' => 'Thank you for your order',
			'customer_on_hold_order_heading_customer' => 'Thank you for your order',
			'customer_on_hold_order_heading' => 'Thank you for your order',
			'customer_on_hold_order_email_additional_content_customer' => '',
			'customer_on_hold_order_additional_content' => '',

			'failed_order_subject_admin' => '[{site_title}]: Order #{order_number} has failed',
			'failed_order_subject' => '[{site_title}]: Order #{order_number} has failed',
			'failed_order_heading_admin' => 'Order Failed: #{order_number}',
			'failed_order_email_additional_content_admin' => '',
			'failed_order_additional_content' => 'Hopefully they’ll be back. Read more about troubleshooting failed payments.',

			'customer_new_account_subject' => 'Your {site_title} account has been created!',
			'customer_new_account_order_subject' => 'Your {site_title} account has been created!',
			'customer_new_account_heading' => 'Welcome to ' . get_bloginfo(),
			'customer_new_account_order_heading' => 'Welcome to ' . get_bloginfo(),
			'customer_new_account_heading_customer' => '',
			'customer_new_account_email_additional_content_customer' => '<p> Hi {user_first_name},</p><p> Thanks for creating an account on {user_first_name}. Your username is {user_first_name}. You can access your account area to view orders, change your password, and more at:</p> {account_button}',
			'customer_new_account_additional_content' => '',

			'customer_note_subject' => 'Note added to your {site_title} order from {order_date}',
			'customer_note_heading' => 'A note has been added to your order',
			'customer_note_email_additional_content_customer' => '',
			'customer_note_additional_content' => 'N/A',

			'customer_invoice_subject' => 'Your latest {site_title} invoice',
			'customer_invoice_subject_paid' => 'Invoice for order #{order_number} on {site_title}',
			'customer_invoice_heading' => 'Your invoice for order #{order_number}',
			'customer_invoice_heading_paid' => 'Invoice for order #{order_number}',
			'customer_invoice_email_additional_content_customer' => '',
			'customer_invoice_additional_content' => 'N/A',

			'customer_reset_password_subject' => 'Password Reset Request for {site_title}',
			'customer_reset_password_order_subject' => 'Password Reset Request for {site_title}',
			'customer_reset_password_heading' => 'Password Reset Request',
			'customer_reset_password_order_heading' => 'Password Reset Request',
			'customer_reset_password_heading_customer' => '',
			'customer_reset_password_email_additional_content_customer' => '<p> Hi  {user_first_name},</p><p> Someone has requested a new password for the following account on {user_first_name}<p> Username: {user_first_name}</p>
			<p>If you didn\'t make this request, just ignore this email. If you\'d like to proceed:</p> {reset_password_button}',
			'customer_reset_password_additional_content' => '',

			'new_renewal_order_subject' => '[{blogname}] New subscription renewal order ({order_number}) - {order_date}',
			'new_renewal_order_heading' => 'New subscription renewal order',
			'new_renewal_order_email_additional_content_customer' => '',
			'new_renewal_order_additional_content' => '',

			'customer_processing_renewal_order_subject' => 'Your {blogname} renewal order receipt from {order_date}',
			'customer_processing_renewal_order_heading' => 'Thank you for your order',
			'customer_processing_renewal_order_email_additional_content_customer' => '',
			'customer_processing_renewal_order_additional_content' => '',

			'customer_completed_renewal_order_subject' => 'Your {blogname} renewal order from {order_date} is complete',
			'customer_completed_renewal_order_heading' => 'Your renewal order is complete',
			'customer_completed_renewal_order_email_additional_content_customer' => '',
			'customer_completed_renewal_order_additional_content' => '',

			'customer_completed_switch_order_subject' => 'Your {blogname} subscription change from {order_date} is complete',
			'customer_completed_switch_order_heading' => 'Your subscription change is complete',
			'customer_completed_switch_order_email_additional_content_customer' => '',
			'customer_completed_switch_order_additional_content' => '',

			'new_switch_order_subject' => '[{blogname}] Subscription Switched ({order_number}) - {order_date}',
			'new_switch_order_heading' => 'Subscription Switched',
			'new_switch_order_email_additional_content_customer' => '',
			'new_switch_order_additional_content' => '',

			'suspended_subscription_subject' => '[{blogname}] Subscription Suspended ({order_number}) - {order_date}',
			'suspended_subscription_heading' => 'Subscription Suspended',
			'suspended_subscription_email_additional_content_customer' => '',
			'suspended_subscription_additional_content' => '',

			'customer_renewal_invoice_subject' => 'Invoice for renewal order {order_number} from {order_date}',
			'customer_renewal_invoice_heading' => 'Invoice for renewal order {order_number}',
			'customer_renewal_invoice_heading_customer' => 'Invoice for renewal order {order_number}',
			'customer_renewal_invoice_email_additional_content_customer' => '',
			'customer_renewal_invoice_email_additional_content_subscription' => '',
			'customer_renewal_invoice_additional_content' => '',

			'cancelled_subscription_subject' => '[{blogname}] Subscription Cancelled',
			'cancelled_subscription_heading' => 'Subscription Cancelled',
			'cancelled_subscription_email_additional_content_customer' => '',
			'cancelled_subscription_additional_content' => '',

			'expired_subscription_subject' => '[{blogname}] Subscription Expired',
			'expired_subscription_heading' => 'Subscription Expired',
			'expired_subscription_email_additional_content_customer' => '',
			'expired_subscription_additional_content' => '',

			'failed_renewal_authentication_subject_other' => 'Payment authorization needed for renewal of {site_title} order {order_number}',
			'failed_renewal_authentication_heading_other' => 'Payment authorization needed for renewal of order {order_number}',
			'failed_renewal_authentication_email_additional_content_other' => '',

			'failed_preorder_sca_authentication_subject_other' => 'Payment authorization needed for pre-order {order_number}',
			'failed_preorder_sca_authentication_heading_other' => 'Payment authorization needed for pre-order {order_number}',
			'failed_preorder_sca_authentication_email_additional_content_other' => '',

			'failed_authentication_requested_subject_other' => '[{site_title}] Automatic payment failed for {order_number}. Customer asked to authenticate payment and will be notified again {retry_time}',
			'failed_authentication_requested_heading_other' => 'Automatic renewal payment failed due to authentication required',
			'failed_authentication_requested_email_additional_content_other' => '',

			'customer_shipped_order_email_additional_content_customer' => '',

			'completed_heading_customer' => '',

		);
		return $defualt_array;
	}
}
