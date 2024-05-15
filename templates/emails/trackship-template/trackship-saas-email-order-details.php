<?php

if (!defined('ABSPATH')) {
	exit;
}

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$preview_id = get_option('email_selected_order_id', 'mockup');
$text_align = is_rtl() ? 'right' : 'left';

?>
<table class="td woocommerce_table_style" style="width: 100%;line-height: 1.5;border:0;padding:10px 0px;border-spacing: inherit;">
	<thead>
		<tr>
			<?php
			if (is_object($order)) {
			?>
				<th class="woo_product_th td" style="padding:0;border:0;border-bottom:1px solid #e0e0e0;text-align:<?php echo esc_attr($text_align); ?>;">
					<?php
					if ($sent_to_admin) {
						esc_html_e('Order Number ', 'wooflow-email-customizer');
					?>
						<a style="text-decoration: none;" href="<?php echo esc_url($order->get_edit_order_url()); ?>" target="_blank"> (#<?php echo esc_html($order->get_order_number()); ?>)</a>
					<?php
					} else {
						esc_html_e('Order Number ', 'wooflow-email-customizer');
						echo '(#' . esc_html($order->get_order_number()) . ')';
					}
					?>
				</th>
				<th class="woo_price_th td" width="100px" style="min-width:100px;padding:0;border:0;border-bottom:1px solid #e0e0e0;text-align:right;">
					<?php echo esc_html(date_i18n('M, j Y', wc_format_datetime(($order->get_date_created())))); ?>
				</th>
			<?php } ?>
		</tr>
	</thead>

	<?php

	$total_tax = $order->get_total_tax();
	$total_product = count($order->get_items());

	foreach ($order->get_items() as $item_id => $item) {
		$product       = $item->get_product();
		$sku           = '';
		$purchase_note = '';
		$product_id = $item->get_product_id();

		if (is_object($product)) {
			$sku           = $product->get_sku();
			$purchase_note = $product->get_purchase_note();
		}

		if ('mockup' == $preview_id) {
	?>
			<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
				<td class="woo_qty_id td qty-value copify-td" style="padding:5px 0;border:0;vertical-align: middle;text-align:left;width:75%">
					<span>A Study in Scarlet</span>
				</td>
				<td class="woo_price_id td qty-value copify-td" style="padding:5px 0;border:0;text-align:right; vertical-align: middle;">
					<span>24.90</span>
				</td>
			</tr>
		<?php
		} else {
		?>
			<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
				<td class="woo_qty_id td qty-value copify-td" style="padding:5px 0;border:0;vertical-align: middle;text-align:left;width:75%">
					<?php $plan_balance = $product->get_meta('_plan_balance'); ?>
					<span class="margin_set">
						<?php echo $plan_balance; ?> Shipment Trackers
					</span>
				</td>
				<td class="woo_price_id td qty-value copify-td" style="padding:5px 0;border:0;text-align:right; vertical-align: middle;">
					<?php echo wp_kses_post(str_replace('(ex. tax)', '', $order->get_formatted_line_subtotal($item))); ?>
				</td>
			</tr>
		<?php
		}
	}
	$item_totals = $order->get_order_item_totals();
	if ($item_totals) {

		if ($total_tax <= 0 && $total_product <= 1 && !isset($item_totals['refund_0'])) {
			unset($item_totals['order_total']);
		}
		unset($item_totals['cart_subtotal']);
		unset($item_totals['payment_method']);
		unset($item_totals['shipping']);
		$i = 0;
		foreach ($item_totals as $total) {
			$i++;
		?>
			<tr class="woo_label_tr">
				<th class="woo_label_th td" scope="row" style="padding:5px 0;border:0;text-align:<?php echo esc_attr($text_align); ?>;"><?php echo wp_kses_post(str_replace(':', '', $total['label'])); ?></th>
				<td class="woo_value_th td" style="padding:5px 0;border:0;text-align:right;"><?php echo wp_kses_post(str_replace(':', '', $total['value'])); ?></td>
			</tr>
	<?php
		}
	}
	?>
	<?php
	$subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'any'));
	if (!empty($subscriptions)) {
		foreach ($subscriptions as $subscription) {
	?>
			<tr class="woo_label_tr">
				<th class="woo_label_th td" scope="row" colspan="2" style="padding:5px 0;border:0;text-align:<?php echo esc_attr($text_align); ?>;">
					<?php if ('mockup' !== $preview_id) { ?>
						<span><?php esc_html_e('Payment Method:', 'wooflow-email-customizer'); ?></span>
						<strong><?php echo wp_kses_post($subscription->get_payment_method_to_display()); ?></strong>
					<?php } ?>
				</th>
			</tr>
		<?php
		}
	} else {
		?>
		<tr class="woo_label_tr">
			<th class="woo_label_th td" scope="row" colspan="2" style="padding:5px 0;border:0;text-align:<?php echo esc_attr($text_align); ?>;">
				<?php if ('mockup' !== $preview_id) { ?>
					<span><?php esc_html_e('Payment Method:', 'wooflow-email-customizer'); ?></span>
					<strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
				<?php } ?>
			</th>
		</tr>
	<?php
	} ?>
</table>
<?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?>
<?php
if (Wooflow_Email_Customizer()->is_subscription_active()) {
?>
	<style>
		table.subscription-table tbody tr td {
			text-align: right !important;
		}

		table.subscription-table tbody tr .subscription_end_date,
		table.subscription-table tbody tr .subscription_end_date_th {
			border-bottom: 0 !important;
		}

		table.subscription-table tbody tr th {
			text-align: left !important;
			padding-left: 0 !important;

		}
	</style>
	<?php
	$email_type = isset($_GET['email_type']) ? sanitize_text_field($_GET['email_type']) : get_option('orderStatus', 'new_order');
	$preview_id = get_option('email_selected_order_id', 'mockup');
	$item_totals = $order->get_order_item_totals();
	if ('mockup' == $preview_id || 'customer_invoice' == $email_type || 'customer_note' == $email_type || isset($item_totals['refund_0'])) {
		return;
	}

	$subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'any'));
	if (!empty($subscriptions) && $sent_to_admin) {
		foreach ($subscriptions as $key => $subscription) {
			$sub_id = $key;
			$subscription = $subscription->get_edit_order_url();
			break;
		}
	?>
		<table class="subscription-table" width="100%" style="overflow: hidden;" cellspacing="0" cellpadding="6">
			<thead>
				<tr>
					<th class="subscription_id_th td" scope="col" style="border:0;padding:0;border-bottom:1px solid #e0e0e0;text-align:<?php echo esc_attr($text_align); ?>;" colspan="2">
						<?php
						if ($sent_to_admin) {
							esc_html_e('Subscription ', 'wooflow-email-customizer');
						?>
							<a style="text-decoration: none;" href="<?php echo esc_url($subscription); ?>" target="_blank"> (#<?php echo esc_html($sub_id); ?>)</a>
						<?php
						} else {
							esc_html_e('Subscription ', 'wooflow-email-customizer');
						}
						?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php

				foreach ($subscriptions as $subscription) {
					foreach ($subscription->get_items() as $item_id => $product_subscription) {
						// Get the name
						$product       = $product_subscription->get_product();
						$plan_balance = $product->get_meta('_plan_balance');
				?>
						<tr class="woo_label_tr">
							<th class="subscription_price_th td" scope="col" style="padding-bottom:5px;padding:0;border:0;text-align:<?php echo esc_attr($text_align); ?>;"><?php esc_html_e($plan_balance, 'wooflow-email-customizer'); ?> Shipment Trackers / <?php echo esc_attr($subscription->get_billing_period()); ?></th>
							<td class="subscription_price td" style="padding:5px 0;min-width: 100px !important;border:0;text-align:<?php echo esc_attr(isset($text_align)); ?>">
								<?php echo esc_html(get_woocommerce_currency_symbol() . esc_html($subscription->get_total())); ?>
							</td>
						</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
<?php
	}
}
