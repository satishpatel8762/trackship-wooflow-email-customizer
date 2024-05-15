<?php
/**
 * Cancelled Subscription email
 *
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( 'subscription' == $email->object ) {
	echo 'This canceled subscription requires that an order containing a subscription, please select subscription order.';
	return;
}

if ( !$subscription ) {
	return;
}

do_action( 'woocommerce_email_header', $email_heading, $email );

// @hooked Wooflow_Email_Customizer::email_main_text_area

do_action( 'woocommerce_email_designer_details', $subscription, $sent_to_admin, $plain_text, $email );

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$email_template = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'wooflow_email_template', $wooflow_customizer['wooflow_email_template'] );

$text_align = is_rtl() ? 'right' : 'left';

if ( 'woocommerce' == $email_template ) {
	?>
	<h2 style="margin:10px 0 18px 0"><?php esc_html_e( 'Subscription Information:', 'wooflow-email-customizer' ); ?></h2>
	<div class="subscription-table-background-color">
		<table class="subscription-table" style="border:1px solid #e0e0e0;width: 100%; margin-bottom: 10px;overflow: hidden;" cellspacing="0" cellpadding="6" >
			<thead>
				<tr>
					<th class="subscription_id_th td" scope="col" style="border:0;border-right:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'ID', 'wooflow-email-customizer' ); ?></th>
					<th class="subscription_price_th td" scope="col" style="border:0;border-right:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'wooflow-email-customizer' ); ?></th>
					<th class="subscription_start_date_th td" scope="col" style="border:0;border-right:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Start Date', 'wooflow-email-customizer' ); ?></th>
					<th class="subscription_end_date_th td" scope="col" style="border:0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'End Date', 'wooflow-email-customizer' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="subscription_id td" style="border:0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( isset( $text_align ) ); ?>">
						<a href="<?php echo esc_url( ( isset( $is_admin_email ) ) ? wcs_get_edit_post_link( $subscription->get_id() ) : $subscription->get_view_order_url() ); ?>">
							<?php
								/* translators: #%s: customer's get_order_number */
								echo sprintf( esc_html_x( '#%s', 'subscription number in email table. (eg: #106)', 'wooflow-email-customizer' ), esc_html( $subscription->get_order_number() ) );
							?>
						</a>
					</td>
					<td class="subscription_price td" style="border:0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( isset( $text_align ) ); ?>">
						<?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?>
					</td>
					<td class="subscription_start_date td" style="border:0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( isset( $text_align ) ); ?>">
						<?php echo esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'date_created', 'site' ) ) ); ?>
					</td>
					<td class="subscription_end_date td" style="border:0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( isset( $text_align ) ); ?>">
						<?php echo esc_html( ( 0 < $subscription->get_time( 'end' ) ) ? date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) : _x( 'When Cancelled', 'Used as end date for an indefinite subscription', 'wooflow-email-customizer' ) ); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php do_action( 'woocommerce_subscriptions_email_order_details', $subscription, $sent_to_admin, $plain_text, $email ); ?>
<?php } elseif ( 'trackship_SaaS' == $email_template ) { ?>
	<div class="subscription-table-background-color" style="padding:0;border:0 !important;">
		<table class="subscription-table" width="100%" style="overflow: hidden;" cellspacing="0" cellpadding="6" >
			<thead>
				<tr>
					<th class="subscription_id_th td" scope="col" style="border:0;padding:0;border-bottom:1px solid #e0e0e0;text-align:left;padding-bottom: 8px;" colspan="2">
						<?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?>
						<a style="text-decoration: auto;" href="<?php echo esc_url( wcs_get_edit_post_link( $subscription->get_id() ) ); ?>">(#<?php echo esc_html( $subscription->get_order_number() ); ?>)</a>	
					</th>		
				</tr>
			</thead>
			<tbody>
				<?php
				$product_name = '';
				foreach ( $subscription->get_items() as $item_id => $item ) {
					$_product  = apply_filters( 'woocommerce_subscriptions_order_item_product', $item->get_product(), $item );
					$product_name = substr(strstr( $_product->get_name(), ','), strlen(',') );
				}
				?>
				<tr>
					<th class="subscription_price_th td" scope="col" style="padding:0;padding-top:5px;border:0;text-align:left;"><?php esc_html_e( $product_name, 'wooflow-email-customizer' ); ?> / <?php echo esc_attr( $subscription->get_billing_period() ); ?></th>
					<td class="subscription_price td" style="border:0;text-align:right;padding:0;padding-top:5px;text-align:right;">
						<?php echo esc_html( get_woocommerce_currency_symbol() . esc_html( $subscription->get_total() ) ); ?>
					</td>
				</tr>
				<tr>
					<?php if ( 0 < $subscription->get_time( 'next_payment', 'site' ) ) { ?>
						<th class="subscription_end_date_th td" scope="col" style="padding:5px 0;border:0;text-align:left;"><?php esc_html_e( 'Next Payment', 'wooflow-email-customizer' ); ?></th>
						<td class="subscription_end_date td" style="border:0;text-align:right;padding:0;padding-top:5px; padding:5px 0;border:0;">
							<?php echo esc_html( date_i18n( 'M j Y', $subscription->get_time( 'next_payment', 'site' ) ) ); ?>
						</td>
					<?php } else { ?>
						<th class="subscription_end_date_th td" scope="col" style="border:0;text-align:left;padding:0;padding-top:5px;"><?php esc_html_e( 'End Date', 'wooflow-email-customizer' ); ?></th>
						<td class="subscription_end_date td" style="border:0;text-align:right;padding:0;padding-top:5px;">
							<?php echo esc_html( date_i18n( 'M j Y', $subscription->get_time( 'end', 'site' ) ) ); ?>
						</td>
					<?php } ?>
				</tr>
				<tr class="woo_label_tr">
					<th class="woo_label_th td" scope="row" colspan="2" style="padding:5px 0;border:0;border-top:1px solid #e0e0e0;text-align:left;">
						<span><?php esc_html_e( 'Payment Method:', 'wooflow-email-customizer' ); ?></span> <strong><?php echo wp_kses_post( $subscription->get_payment_method_to_display() ); ?></strong>
					</th>
				</tr>
			</tbody>
		</table>
	</div>
<?php } ?>
<?php
// do_action( 'woocommerce_subscriptions_email_order_details', $subscription, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $subscription, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_footer', $email );
