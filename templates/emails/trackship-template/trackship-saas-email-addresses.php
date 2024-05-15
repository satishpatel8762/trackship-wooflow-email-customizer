<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?>
<table id="addresses" cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2 style="margin:0;"><?php esc_html_e( 'Billing Details', 'woocommerce' ); ?></h2>
			<address class="address" style="border:0;padding:0;line-height:1.5;border-top:1px solid #f5f5f5;">
				<div style="margin-top:5px">
					<p style="margin-bottom:0;font-style: normal;text-transform: capitalize;">
						<?php echo isset( $address ) ? $address : 'N/A'; ?>
					</p>
					<?php if ( $order->get_billing_email() ) : ?>
						<a><?php echo esc_html( $order->get_billing_email() ); ?></a>
					<?php endif; ?>
				</div>
			</address>
		</td>
	</tr>
</table>
