<?php
/**
 * WEC_Email_Admin
 *
 * @class   WEC_Email_Admin
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WEC_Email_Admin class.
 */
class WEC_Email_Admin {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return WEC_Email_Admin
	*/
	public static function get_email_instance() {

		if ( null === self::$instance ) {
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
	 * Initialize the main plugin function
	 * 
	 * @since  1.0
	*/
	public function __construct() {
		$this->init();
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init() {
		
		//Add CSS to WooCommerce Emails
		add_filter( 'woocommerce_email_styles', array( $this, 'email_add_css_to_woocommerce_email_styles' ), 9999, 2 );
						
	}

	public function email_add_css_to_woocommerce_email_styles( $styles, $email = '' ) { 

		// Add responsive style
		$styles .= '@media only screen and (max-width: 700px) {
			#template_container, #template_header_image, #template_header, #template_body, #template_footer, #template_footer_container {
				width:100% !important;
				min-width:320px !important;
			}
			td#td_content {
				padding-left: 10px !important;
				padding-right: 10px !important;
			}
			#wrapper {
				margin: 0 auto !important;
			}
			div#footerlink {
				width: 100% !important;
				text-align: center !important;
				float: none !important;
			}
			div#footersocial {
				width: 100% !important;
				text-align: center !important;
				float: none !important;
			}
			.wclp_location_box2{
				border-top: 0 !important;
			}
			.wclp_location_box{
				display: block !important;
				width: 100% !important;
			}	
		}';
		
		// Add custom styles.
		$styles .= $this->get_styles_string();
	
		return $styles;
	}
	
	// @snippet get option value 
	// @return value

	public function get_option_value( $key, $option ) {
		
		$options = get_option($option, array());
		
		$setting = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
		$selectors = isset($setting[$key]['selectors']) ? $setting[$key]['selectors'] : '';
		$defualt = isset($setting[$key]['default']) ? $setting[$key]['default'] : '';
		
		return isset($options[$key]) ? stripslashes($options[$key]) : $defualt;		
	}
	
	/**
	 * Get styles string
	 * 
	 * @param bool $add_custom_css
	 * @return string
	 */
	public function get_styles_string( $add_custom_css = true ) {
		$styles_array     = array();
		$styles           = '';

		// Iterate over settings.
		foreach ( Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func() as $setting_key => $setting ) {

			// Only add CSS properties.
			if ( isset( $setting['style'] ) && 'css' === $setting['style'] ) {
				
				$key = substr($setting_key, strpos($setting_key, '[')+1, -1);
				$option = substr($setting_key, 0, strpos($setting_key, '['));
				
				$property_value = $this->get_option_value($setting_key, 'email_customizer_settings_option');
				
				if ( 'range' === $setting['type'] ) {
					$unit = $setting['unit'];
					$property_value = $this->get_option_value($setting_key, 'email_customizer_settings_option') . $unit;
				}

				// Iterate over selectors.
				if ( isset( $setting['selectors_template'] ) ) {
					$trackship_and_woocommerce = $setting['selectors_template'];
				} elseif ( isset( $setting['selectors'] ) ) {
					$trackship_and_woocommerce = $setting['selectors'];
				}

				foreach ( $trackship_and_woocommerce as $selector => $properties ) {
					if ( is_array($properties) ) {
						// Iterate over properties.
						foreach ( $properties as $property ) {
							if ( $property_value ) {

								$styles_array[ $selector ][ $property ] = $property_value;

								if ( 'border-bottom-width' === $property && $property_value > '0px' ) {
									$styles_array[ $selector ][ 'border-bottom-style' ] = 'solid';	
								}

								if ( 'background-color' === $property || 'color' === $property || 'font-weight' === $property || 'border-color' === $property || 'border-top-color' === $property || 'border-right-color' === $property || 'border-right-width' === $property || 'border-top-width' === $property || 'border-width' === $property ||'border-style' === $property ||'padding' === $property || 'padding-left' === $property || 'padding-right' === $property || 'padding-top' === $property || 'padding-bottom' === $property || 'text-align' === $property || 'font-family' === $property || 'border-radius' === $property || 'font-style' === $property || 'font-size' === $property || 'border-top-left-radius' === $property || 'border-top-right-radius' === $property || 'border-bottom-left-radius' === $property || 'border-bottom-right-radius'=== $property || 'border-top-style'=== $property ) {
									$styles_array[ $selector ][ $property ] = $property_value . ' !important';
								}
							}
						}
					}
				}
			}
		}
		
		// Join property names with values.
		foreach ( $styles_array as $selector => $properties ) {

			// Open selector.
			$styles .= $selector . '{';

			foreach ( $properties as $property_key => $property_value ) {
				
				// Add property.
				$styles .= $property_key . ':' . $property_value . ';';
			}

			// Close selector.
			$styles .= '}';
		}
		// Add custom CSS
		if ( $add_custom_css ) {
			$setting = Wooflow_Email_Customizer()->wooflow_email_option->email_customize_setting_options_func();
			$defualt = $setting['custom_style_textarea'];
			$styles .= $defualt['default'];
		}

		// Return styles string
		return $styles;
	
	}
	
	/*
	* get email content data for store owner
	*/
	public function email_content() {
		
		ob_start();
		
		$WEC_Email = new Woocommerce_Email_Customizer_Preview();
		$WEC_Email->get_preview_email();
		
		$message = ob_get_clean();
		return $message;
		
	}
	
	/**
	 * Get blog name formatted for emails.
	 *
	 * @return string
	 */
	private function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	/**
	 * Get the from name for outgoing emails.
	 *
	 * @return string
	 */
	public function get_from_name() {
		$from_name = apply_filters( 'woocommerce_email_from_name', get_option( 'woocommerce_email_from_name' ), $this );
		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	/**
	 * Get the from address for outgoing emails.
	 *
	 * @return string
	 */
	public function get_from_address() {
		$from_address = apply_filters( 'woocommerce_email_from_address', get_option( 'woocommerce_email_from_address' ), $this );
		return sanitize_email( $from_address );
	}
	
}
