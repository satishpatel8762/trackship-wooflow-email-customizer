<?php

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

// do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email );

$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;

$header_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'header_font_size', $wooflow_customizer['header_font_size'] );
$contant_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'contant_font_size', $wooflow_customizer['contant_font_size'] );

?>
<style>
	.heading_with_content h1{
		font-size:<?php echo esc_html( $header_font_size ); ?>px !important;
	}
	#body_content_inner table *{
		font-size:<?php echo esc_html( $contant_font_size ); ?>px !important;
	}
	.img-padding.copify-td img{
		width:50px !important;
	}
</style>
		<h2 style="margin:10px 0 18px">
			<?php
			if ( $sent_to_admin ) {
				$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
				$after  = '</a>';
			} else {
				$before = '';
				$after  = '';
			}
			/* translators: %s: Order ID. */
			echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'wooflow-email-customizer' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
			?>
		</h2>
		<table class="td woocommerce_table_style" cellspacing="0" cellpadding="6" style="width: 100%;line-height: 2.5;">
			<thead>
				<tr>
					<th class="woo_product_th td" scope="col" style="border:0;border-right:1px solid #e0e0e0;width:65%;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'wooflow-email-customizer' ); ?></th>
					<th class="woo_qty_th td" scope="col" style="border:0;border-right:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Qty', 'wooflow-email-customizer' ); ?></th>
					<th class="woo_price_th td" scope="col" style="border:0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'wooflow-email-customizer' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $order->get_items() as $item_id => $item ) :
					$product       = $item->get_product();
					$sku           = '';
					$purchase_note = '';
					$image         = $show_image;
					// $image_size = $image_size;
					$product_id = $item->get_product_id();
					
					if ( is_object( $product ) ) {
						$sku           = $product->get_sku();
						$purchase_note = $product->get_purchase_note();
						$image         = $product->get_image();
					} else {
						$image         = '<img src=' . esc_url( Wooflow_Email_Customizer()->plugin_dir_url() ) . 'assets/images/dummy-product-image.jpg>';
					}
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
						<?php if ( true == $show_image ) { ?>
							<td class="woo_image_id td img-padding copify-td" style="border:0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle;">
								<?php
									echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) ); 
									echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );
								?>
							</td>
						<?php } ?>
						<?php if ( false == $show_image ) { ?>
						<td class="woo_image_id td content-color copify-td"
						<?php
							if ( false == $show_image ) {
								echo 'colspan="1"';
							}
							?>
							style="border:0;border-top:1px solid #e0e0e0;border-right:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle;">
							<?php
							// Product name.
							echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );
							
							if ( !empty( $downloads ) ) {
								echo '<br>';
								foreach ( $downloads as $download ) {
									if ( $product_id == $download['product_id'] && isset($download['url']) ) {
										echo '<a href="' . esc_url( $download['url'] ) . '">';
										esc_html_e( $download['download_name'] );
										echo '</a><br>';
									}
								}
							}
							?>
						</td>
						<?php } ?>
						<td class="woo_qty_id td qty-value copify-td" style="border:0;border-top:1px solid #e0e0e0;border-right:1px solid #e0e0e0;vertical-align: middle;text-align:left">
						<?php
						$qty = $item->get_quantity();
						esc_html_e( $qty );
						?>
						</td>
						<td class="woo_price_id td qty-value copify-td" style="border:0;border-top:1px solid #e0e0e0;text-align:left; vertical-align: middle;">
							<?php echo wp_kses_post( str_replace( '(ex. tax)', '', $order->get_formatted_line_subtotal( $item ) ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<?php
				$item_totals = $order->get_order_item_totals();

				if ( $item_totals ) {
					$i = 0;
					foreach ( $item_totals as $total ) {
						$i++;
						?>
						<tr>
							<th class="woo_label_th td" scope="row" colspan="2" style="border:0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['label'] ); ?></th>
							<td class="woo_value_th td" style="border:0;border-top:1px solid #e0e0e0;text-align:left;"><?php echo wp_kses_post( str_replace( '(ex. tax)', '', $total['value'] ) ); ?></td>
						</tr>
						<?php
					}
				}
				if ( $order->get_customer_note() ) {
					?>
					<tr>
						<th class="woo_note_th td" scope="row" colspan="2" style="border:0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'wooflow-email-customizer' ); ?></th>
						<td class="woo_note_value_th td" style="border:0;border-top:1px solid #e0e0e0;text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
					</tr>
					<?php
				}
				?>
			</tfoot>
		</table>
	<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
<?php
if ( Wooflow_Email_Customizer()->is_subscription_active() ) {
	
	$preview_id = get_option( 'email_selected_order_id', 'mockup' ); 
	if ( 'mockup' == $preview_id ) {
		return;
	}
	$subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'any' ) );

	if ( !empty( $subscriptions ) ) {
		foreach ( (array) $subscriptions as $key => $subscription ) {
			break;
		}
		?>
		<div style="clear:both; height:1px;"></div>
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
	<?php } ?>
<?php } ?>
