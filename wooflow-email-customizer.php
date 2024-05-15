<?php

/**
 * Plugin Name: WooFlow Email Customizer
 * Plugin URI: https://www.zorem.com/shop/
 * Description: Customize the default woocommerce email templates design and text through the native WordPress customizer. Preview emails.
 * Version: 1.0
 * Author: zorem
 * Author URI:  http://www.zorem.com/
 * License:     GPL-2.0+
 * License URI: http://www.zorem.com/
 * Text Domain: wooflow-email-customizer
 * Domain Path: /lang/
 * WC requires at least: 5.0
 * WC tested up to: 6.6.1
 **/

if (!defined('ABSPATH')) {
	exit;
}

class Wooflow_Email_Customizer {

	/**
	 * Wooflow_Email_Customizer
	 *
	 * @var string
	 */

	public $version = '1.0';
	public $plugin_path;
	public $wec_admin;
	public $email_admin;
	public $default_content_customizer;
	public $wooflow_email_option;
	public $subscritpion_options;
	public $other_options;
	public $admin_options;
	public $customer_options;
	public $cev_options;

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Check if Wocoomerce is activated
		if ($this->is_wc_active()) {
			$this->includes();
			$this->email_init();
		}
	}

	/**
	 * Check if WooCommerce is active
	 *	 
	 * @since  1.0.0
	 * @return bool
	 */
	private function is_wc_active() {

		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}
		if (is_plugin_active('woocommerce/woocommerce.php')) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		// Do the WC active check
		if (false === $is_active) {
			add_action('admin_notices', array($this, 'notice_activate_wc'));
		}
		return $is_active;
	}

	/**
	 * Display WC active notice
	 *
	 * @since  1.0.0
	 */
	public function notice_activate_wc() {
?>
		<div class="error">
			<p>
				<?php
				/* translators: %s: search WooCommerce plugin link */
				printf(esc_html__('Please install and activate %1$sWooCommerce%2$s for Wooflow Email Customizer!', 'wooflow-email-customizer'), '<a href="' . esc_url(admin_url('plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins')) . '">', '</a>');
				?>
			</p>
		</div>
<?php
	}

	/**
	 * Include plugin file.
	 *
	 * @since 1.0.0
	 *
	 */
	public function includes() {

		require_once $this->get_plugin_path() . '/includes/customizer/wooflow-customizer.php';
		$this->wec_admin = WC_EC_CUSTOMIZER::get_email_instance();

		require_once $this->get_plugin_path() . '/includes/wc-wooflow-email-customizer-admin.php';
		$this->email_admin = WEC_Email_Admin::get_email_instance();

		require_once $this->get_plugin_path() . '/includes/wooflow-email-default-content.php';
		$this->default_content_customizer = Default_Content_Templates::get_instance();

		require_once $this->get_plugin_path() . '/includes/customizer/wooflow-email-setting-option.php';
		$this->wooflow_email_option = Wooflow_Email_Option::get_instance();

		require_once $this->get_plugin_path() . '/includes/customizer/subscription-setting-options.php';
		$this->subscritpion_options = Subscritpion_Options::get_instance();

		require_once $this->get_plugin_path() . '/includes/customizer/other-setting-options.php';
		$this->other_options = Other_Options::get_instance();

		require_once $this->get_plugin_path() . '/includes/customizer/admin-setting-options.php';
		$this->admin_options = Admin_Options::get_instance();

		require_once $this->get_plugin_path() . '/includes/customizer/customer-setting-option.php';
		$this->customer_options = Customer_Options::get_instance();

		require_once $this->get_plugin_path() . '/includes/customizer/cev-setting-options.php';
		$this->cev_options = Cev_Options::get_instance();
	}

	/*
	* email_init when class loaded
	*/
	public function email_init() {

		// Load plugin textdomain
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		add_action('plugins_loaded', array($this, 'load_includes_file'));
		add_action('plugins_loaded', array($this, 'email_on_plugins_loaded'), 80);

		//callback for add action link for plugin page	
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'my_plugin_action_links'));

		add_filter('woocommerce_locate_template', array($this->default_content_customizer, 'woo_email_template_path'), 1, 3);
	}

	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */

	public function my_plugin_action_links($links) {
		$links = array_merge(array(
			'<a href="' . esc_url(admin_url('/admin.php?page=email_customizer')) . '">' . __('Settings', 'woocommerce') . '</a>'
		), $links);
		return $links;
	}

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

	/*
	* load text domain
	*/
	public function load_textdomain() {
		load_plugin_textdomain('wooflow-email-customizer', false, plugin_dir_path(plugin_basename(__FILE__)) . 'lang/');
	}

	/*
	* include file on plugin load
	*/
	public function load_includes_file() {
		require_once $this->get_plugin_path() . '/includes/customizer/wooflow-customizer.php';
		require_once $this->get_plugin_path() . '/includes/customizer/wooflow-email-setting-option.php';
	}

	/**
	 * Trigger Load on plugins loaded.
	 */

	public function email_on_plugins_loaded() {

		// Remove the woocommerce call for email header.
		if (function_exists('WC')) {

			// Add our custom call for email header.
			remove_action('woocommerce_email_header', array(WC()->mailer(), 'email_header'));
			add_action('woocommerce_email_header', array($this->default_content_customizer, 'add_custom_header_to_woocommerce_email'), 20, 2);
			// Hook in main text areas for customized emails.
			add_action('woocommerce_email_designer_details', array($this->default_content_customizer, 'email_main_text_area'), 10, 4);

			// Use our templates instead of woocommerce.
			add_filter('woocommerce_locate_template', array($this->default_content_customizer, 'filter_locate_template'), 10, 3);

			// Add our custom call for email footer.
			remove_action('woocommerce_email_footer', array(WC()->mailer(), 'email_footer'));
			add_action('woocommerce_email_footer', array($this->default_content_customizer, 'add_custom_footer_to_woocommerce_email'), 10, 1);

			remove_action('woocommerce_email_order_details', array(WC()->mailer(), 'order_downloads'), 10, 4);

			// Add our custom call for email order details.
			remove_action('woocommerce_email_order_details', array(WC()->mailer(), 'order_details'), 10, 4);
			add_action('woocommerce_email_order_details', array($this->default_content_customizer, 'add_woocommerce_email_order_details'), 10, 4);

			// Add our custom call for email customer details.
			remove_action('woocommerce_email_customer_details', array($this->default_content_customizer, 'customer_details'), 10, 3);
			add_action('woocommerce_email_customer_details', array($this->default_content_customizer, 'add_woocommerce_customer_details'), 10, 3);

			// Add our custom call for email addresses.
			remove_action('woocommerce_email_customer_details', array(WC()->mailer(), 'email_addresses'), 20, 4);
			add_action('woocommerce_email_customer_details', array($this->default_content_customizer, 'add_woocommerce_email_addresses'), 20, 4);
		}

		add_filter('woocommerce_locate_template', array($this->default_content_customizer, 'woo_email_template_path'), 1, 3);

		// Hook for replacing {year} in email-footer.
		add_filter('woocommerce_email_footer_text', array($this->default_content_customizer, 'email_absolute_footer_text_replace'));

		// Hook in footer custom content hook.
		add_action('wec_layout_email_footer_design', array($this->default_content_customizer, 'email_footer_layout_content'), 10);

		// Hook in footer custom content hook.
		add_action('woocommerce_email_order_items_args', array($this->default_content_customizer, 'add_wc_order_email_args_images'), 10);
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

	public function is_subscription_active() {
		if (class_exists('WC_Subscriptions')) {
			return true;
		}
		return false;
	}
}

/**
 * Returns an instance of Wooflow_Email_Customizer.
 *
 * @since 1.0
 * @version 1.0
 *
 * @return Wooflow_Email_Customizer
 */
function Wooflow_Email_Customizer() {
	static $instance;

	if (!isset($instance)) {
		$instance = new Wooflow_Email_Customizer();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
 */
Wooflow_Email_Customizer();
