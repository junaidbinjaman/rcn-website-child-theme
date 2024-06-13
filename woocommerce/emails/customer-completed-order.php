<?php
/**
 * Customer completed order email
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version (woocommerce) 8.5.2
 *
 * @author Junaid Bin Jaman
 * @version (file) 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rcn_child_order_date         = date_create( $order->get_date_created() );
$rcn_child_myaccount_page_uri = get_permalink( wc_get_page_id( 'myaccount' ) );

$rcn_child_discount          = $order->get_discount_total();
$rcn_child_hide_discount_row = $rcn_child_discount < 1 ? 'display: none;' : '';

$rcn_child_shipping          = $order->get_shipping_total();
$rcn_child_hide_shipping_row = $rcn_child_shipping < 1 ? 'display: none;' : '';
?>

<div style="background-color:#f5f5f8;">
	<center>
		<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; padding: 30px 0px">
			<tr>
				<td align="center" style="background-color: white; padding: 5px; border-radius: 12px; box-shadow: 7px 7px 41px rgba(214,214,214,1);">
					<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="padding: 20px; padding-bottom: 0px">
								<a href="https://realitycapturenetwork.com" target="_blank">
									<img src="https://realitycapturenetwork.com/wp-content/uploads/2024/06/RCN_no-wordmark_blue.png" alt="RCN Logo" width="200" style="border-radius:6px; display:block;">
								</a>
								<p style="font-size: 15px; font-weight: 800; color: #000; font-family: 'Montserrat', sans-serif;">Receipt #<?php echo esc_html( $order->get_id() ); ?></p>
							</td>
						</tr>
						<tr>
							<td>
								<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
									<tr>
										<td style="width: 33.33%; padding: 10px;">
											<p style="font-size: 12px; font-weight: bold; color: #999999; font-family: 'Montserrat', sans-serif; margin: 0;">Amount Paid</p>
											<p style="font-size: 14px; font-weight: 900; color: #1a1a1a; font-family: 'Montserrat', sans-serif; margin: 4px 0 0;"><?php echo $order->get_formatted_order_total(); //phpcs:ignored ?></p>
										</td>
										<td style="width: 33.33%; padding: 10px;">
											<p style="font-size: 12px; font-weight: bold; color: #999999; font-family: 'Montserrat', sans-serif; margin: 0;">Receipt Date</p>
											<p style="font-size: 14px; font-weight: 900; color: #1a1a1a; font-family: 'Montserrat', sans-serif; margin: 4px 0 0;"><?php echo esc_html( date_format( $rcn_child_order_date, 'm/d/Y' ) ); ?></p>
										</td>
										<td style="width: 33.33%; padding: 10px;">
											<p style="font-size: 12px; font-weight: bold; color: #999999; font-family: 'Montserrat', sans-serif; margin: 0;">Payment Method</p>
											<p style="font-size: 14px; font-weight: 900; color: #1a1a1a; font-family: 'Montserrat', sans-serif; margin: 4px 0 0;"><?php echo esc_html( $order->get_payment_method_title() ); ?></p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px;">
								<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #d2d4de; text-align: left; border-radius: 4px; border-spacing: 0;">
									<tbody>
										<?php
										$order_items = $order->get_items();
										foreach ( $order_items as $item_id => $item ) :
											$product = $item->get_product();
											?>
										<tr>
											<td style="padding: 10px 10px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-size: 14px">
												<?php echo esc_html( sprintf( '%s', $item->get_name() ) ); ?><br />
												<small style="color: #999999"><?php echo sprintf( 'Unit price: %s', wc_price( $product->get_price() ) ); // phpcs:ignore. ?></small><br />
												<small style="color: #999999"><?php echo esc_html( sprintf( 'Quantity: %s', $item->get_quantity() ) ); ?></small>
											</td>
											<td valign="top" style="padding: 10px 10px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-size: 14px; text-align: right"><?php echo wc_price( $item->get_subtotal() ); //phpcs:ignored ?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px;">
								<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #d2d4de; border-radius: 4px;">
									<tbody>
										<tr>
											<td style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;">Subtotal</td>
											<td align="right" style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo wc_price( $order->get_subtotal() ); //phpcs:ignored ?></td>
										</tr>
										<tr>
											<td style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;">Sales Tax</td>
											<td align="right" style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo wc_price( $order->get_total_tax() ); //phpcs:ignored ?></td>
										</tr>
										<tr style="<?php echo esc_attr( $rcn_child_hide_shipping_row ); ?>">
											<td style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;">Shipping Cost</td>
											<td align="right" style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo wc_price( $order->get_shipping_total() ); //phpcs:ignored ?></td>
										</tr>
										<tr style="<?php echo esc_attr( $rcn_child_hide_discount_row ); ?>">
											<td style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;">Discount</td>
											<td align="right" style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 500;"><?php echo wc_price( $order->get_discount_total() ); //phpcs:ignored ?></td>
										</tr>
										<tr>
											<td style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 800;">Total Amount Paid</td>
											<td align="right" style="padding: 10px 10px; font-size: 14px; color: #1a1a1a; font-family: 'Montserrat', sans-serif; font-weight: 800;"><?php echo wc_price( $order->get_total() ); //phpcs:ignored ?></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" style="padding: 20px;">
								<a href="<?php echo esc_url( $rcn_child_myaccount_page_uri ); ?>" style="background-color: #006CFA; color: white; font-family: 'Montserrat', sans-serif; font-weight: 600; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-size: 14px;">View Your Account</a>
							</td>
						</tr>
						<tr>
							<td align="center" style="padding: 20px; color: #98a0a6; font-family: 'Montserrat', sans-serif; font-size: 11px;">
								Reality Capture Network, LLC
								<br>3405 E Overland Rd #375, Meridian, ID 83642
								<br>If you have any questions, feel free to contact us at <a href="mailto:info@realitycapturenetwork.com" style="color: #98a0a6; text-decoration: underline;">team@realitycapturenetwork.com</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</center>
</div>