<?php
/**
 * Email mobile messaging
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-mobile-messaging.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Internal\Orders\MobileMessagingHandler;
$email_template = Wooflow_Email_Customizer()->default_content_customizer->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', 'trackship_SaaS');
if ( 'trackship_SaaS' == $email_template ) {
	return;
}
echo '<div class="last_message">';
echo wp_kses_post( MobileMessagingHandler::prepare_mobile_message( $order, $blog_id, $now, $domain ) );
echo '</div>';
