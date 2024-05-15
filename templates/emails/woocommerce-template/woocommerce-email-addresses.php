<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();
$wooflow_customizer = Wooflow_Email_Customizer()->wooflow_email_option->wooflow_generate_defaults();
$wec = Wooflow_Email_Customizer()->default_content_customizer;
$address_table_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'address_table_font_size', $wooflow_customizer['address_table_font_size']);
$table_heading_font_size = $wec->get_customizer_option_value_from_array('email_customizer_settings_option', 'table_heading_font_size', $wooflow_customizer['table_heading_font_size']);

?>
<table id="addresses" cellspacing="0" cellpadding="0" width="100%" border="0" style="margin-bottom:20px;">
    <tr>
        <td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;padding:0;" valign="top" width="50%">
            <h2 style="<?php echo esc_html( $table_heading_font_size ); ?>px !important;margin:10px 0 18px 0;"><?php esc_html_e( 'Billing Details', 'woocommerce' ); ?></h2>
            <address class="address" style="line-height:1.5;background-color:#fff;">
                <div style="margin-top:5px">
                    <p style="margin-bottom:0;font-style: normal;text-transform: capitalize;">
                        <?php echo isset( $address ) ? $address : 'N/A'; ?>
                    </p>
                    <?php if ( $order->get_billing_email() ) : ?>
                        <a class="address_email" style="<?php echo esc_html( $address_table_font_size ); ?>px !important;"><?php echo esc_html( $order->get_billing_email() ); ?></a>
                    <?php endif; ?>
                </div>
            </address>
        </td>
    </tr>
</table>