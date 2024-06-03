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

$jd_rcn_order_date = date_create( $order->get_date_created() );

// Check if discount and shipping are applicable.
$jd_rcn_discount          = $order->get_discount_total();
$jd_rcn_hide_discount_row = '';

if ( $jd_rcn_discount < 1 ) {
	$jd_rcn_hide_discount_row = 'display: none';
}

$jd_rcn_shipping          = $order->get_shipping_total();
$jd_rcn_hide_shipping_row = '';

if ( $jd_rcn_shipping < 1 ) {
	$jd_rcn_hide_shipping_row = 'display: none';
}

?>

<!-- Email container -->
<div style="
	background-color:#f5f5f8; 
	padding-top: 130px;
	padding-bottom: 130px;
	padding-left: 30px;
	padding-right: 30px
">
	<center> <!-- old day tag to center contents -->
		<div style="
			background-color: white; 
			width: 600px; 
			padding-top: 20px; 
			padding-left: 25px; 
			padding-right: 25px; 
			padding-bottom: 20px; 
			border-radius: 12px; 
			-webkit-box-shadow: 7px 7px 41px 0px rgba(214,214,214,1);
			-moz-box-shadow: 7px 7px 41px 0px rgba(214,214,214,1); 
			box-shadow: 7px 7px 41px 0px rgba(214,214,214,1);
		">
			<!-- Email template header -->
			<div style="
				text-align: center;
				margin-top: 25px;
			">
				<!-- RCN Logo -->
				<a href="https://realitycapturenetwork.com" target="_BLANK">
					<img 
						src="https://realitycapturenetwork.com/wp-content/uploads/2023/01/RCN-Blue-Rectangle.png" 
						alt="RCN Logo" 
						width="200px" 
						height="auto" 
						style="border-radius:6px">
				</a>
				<!-- Header text -->
				<h3 style="
					text-align: center;
					font-size: 28px;
					color: #000;
				">PURCHASE NOTE</h3>
				<!-- Order ID -->
				<p style="
					font-size: 15px;
					font-weight: 800;
					color: #000;
					margin-top: -8px;
				">
					Receipt #<?php echo esc_html( $order->get_id() ); ?>
				</p>
			</div>

			<!-- Body upper -->
			<div style="margin-bottom: 15px">
				<table cellspacing="0" cellpadding="0" width="100%" style="text-align: center">
					<tbody>
						<tr>
							<td style="vertical-align:top; width:200px" valign="top">
								<p style="
									font-size:12px;
									font-weight:500;
									line-height:17px;
									margin-bottom:0px;
									margin-top:0;
									margin-right:0;
									margin-left:0;
									color: #000;
								">Amount Paid</p>
								<p style="
									font-size:14px;
									font-weight:900;
									line-height:17px;
									margin-bottom:4px;
									margin-top:0;
									margin-right:0;
									margin-left:0;
									color: #000;
								">
									<?php echo $order->get_formatted_order_total(); //phpcs:ignore ?>
								</p>
							</td>
							<td style="vertical-align:top; width:200px" valign="top">
								<p style="
									font-size:12px;
									font-weight:500;
									line-height:17px;
									margin-bottom:0px;
									margin-top:0;
									margin-right:0;
									margin-left:0;
									color: #000;
								">Receipt Date</p>
								<p style="
									font-size:14px;
									font-weight:900;
									line-height:17px;
									margin-bottom:4px;
									margin-top:0;
									margin-right:0;
									margin-left:0;
									color: #000;
								">
									<?php echo esc_html( date_format( $jd_rcn_order_date, 'm/d/Y' ) ); ?>
								</p>
							</td>
							<td style="vertical-align:top; width:200px" valign="top">
								<p style="
									font-size:12px;
									font-weight:500;
									line-height:17px;
									margin-bottom:0px;
									margin-top:0;
									margin-right:0;
									margin-left:0;
									color: #000;
								">Payment Method</p>
								<p style="
									font-size:14px;
									font-weight:900;
									line-height:17px;
									margin-bottom:4px;
									margin-top:0;
									margin-right:0;
									margin-left:0;
									color: #000;
								">
									<?php echo esc_html( $order->get_payment_method_title() ); ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Order items list table -->
			<div style="text-align:center">
				<table style="
						width: 100%;
						border: 1px solid #d2d4de;
						margin-bottom: 20px;
						text-align: left;
						border-radius: 4px;
						border-spacing: 0;
				">
					<thead>
						<tr>
							<th style="
								border-bottom: 1px solid #d2d4de;
								line-height: 29px;
								padding: 10px 28px 10px 28px;
								text-align: left;
								font-size: 14px;
								border-top: 0;
								width: 58%;
							">Product Name</th>
							<th style="border-bottom: 1px solid #d2d4de; width: 14%">Quantity</th>
							<th style="border-bottom: 1px solid #d2d4de; width: 14%">Price</th>
							<th style="border-bottom: 1px solid #d2d4de; width: 14%">Line Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Get order items.
						$order_items = $order->get_items();

						// Loop through order items.
						foreach ( $order_items as $item_id => $item ) :
							?>
							<tr>
								<td style="
										color: #000;
									line-height: 17px;
									padding: 06px 28px 06px 28px;
									text-align: left;
									font-weight: 500;
									font-size: 14px;
									border-top: 0;
								">
									<?php echo esc_html( $item->get_name() ); ?>
								</td>
								<td style="
									line-height: 17px;
									padding: 0px 28px 0px 0px;
									text-align: left;
									font-weight: 500;
									font-size: 14px;
										border-top: 0;
								">
									<?php echo esc_html( $item->get_quantity() ); ?>
								</td>
								<td style="
									line-height: 17px;
									padding: 0px 28px 0px 0px;
									text-align: left;
									font-weight: 500;
									font-size: 14px;
									border-top: 0;
								">
									<?php
									$product = $item->get_product();
									echo wc_price( $product->get_price() ) //phpcs:ignore
									?>
								</td>
								<td style="
									line-height: 17px;
									padding: 0px 28px 0px 0px;
									text-align: left;
									font-weight: 500;
									font-size: 14px;
									border-top: 0
								">
									$<?php echo esc_html( $item->get_subtotal() ); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<!-- Body lower / order summary -->
			<div style="text-align: center">
				<table style="
						border:1px solid #d2d4de;
						border-top-right-radius:4px;
						border-top-left-radius:4px;
						border-bottom-right-radius:0;
						border-bottom-left-radius:0;
						border-collapse:separate;" cellspacing="0" cellpadding="0" width="100%">
					<tbody>
						<tr>
							<td style="
								color:#000;
								line-height:20px;
								padding:20px 28px 16px 28px;
								text-align:left;
								font-weight:900;
								font-size:14px;
								border-top:0;" align="left">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">Subtotal</p>
							</td>
							<td style="
								color:#000;
								line-height:20px;
								padding:20px 28px 16px 28px;
								text-align:right;
								font-weight:900;
								font-size:14px;
								border-top:0;" align="right">
								<p style="margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">
									$<?php echo esc_html( $order->get_subtotal() ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<td style="
								color:#000;
								line-height:20px;
								padding:20px 28px 0 28px;
								text-align:left;
								font-weight:200;
								font-size:11px;
								border-top:1px solid #d2d4de;" align="left">
							</td>
							<td style="
								color:#000;
								line-height:20px;
								padding:20px 28px 0 28px;
								text-align:left;
								font-weight:200;
								font-size:11px;
								border-top:1px solid #d2d4de;" align="left">
							</td>
						</tr>
						<tr>
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 12px 28px;
								text-align:left;
								font-weight:500;
								font-size:14px;
								border-top:0;" align="left
							">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">Sales Tax</p>
							</td>
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 12px 28px;
								text-align:right;
								font-weight:500;
								font-size:14px;
								border-top:0; " align="right">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">
									$<?php echo esc_html( $order->get_total_tax() ); ?>
								</p>
							</td>
						</tr>
						<tr style="<?php echo esc_attr( $jd_rcn_hide_shipping_row ); ?>"> <!-- Hide if shipping is not applicable -->
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 12px 28px;
								text-align:left;
								font-weight:500;
								font-size:14px;
								border-top:0; " align="left">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">
									Shipping Cost
								</p>
							</td>
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 12px 28px;
								text-align:right;
								font-weight:500;
								font-size:14px;
								border-top:0; " align="right">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">
									$<?php echo esc_html( $order->get_shipping_total() ); ?>.00
								</p>
							</td>
						</tr>
						<tr style="<?php echo esc_attr( $jd_rcn_hide_discount_row ); ?>"> <!-- Hide is discount is not applicable -->
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 12px 28px;
								text-align:left;
								font-weight:500;
								font-size:14px;
								border-top:0; " align="left">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">Total Discount</p>
							</td>
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 12px 28px;
								text-align:right;
								font-weight:500;
								font-size:14px;
								border-top:0; " align="right">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:500;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">
									-<?php echo esc_html( $order->get_discount_to_display() ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 20px 28px;
								text-align:left;
								font-weight:500;
								font-size:14px;
								border-top:0; " align="left">
								<p style="
									margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:900;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">Amount Paid</p>
							</td>
							<td style="
								color:#000;
								line-height:20px;
								padding:0 28px 20px 28px;
								text-align:right;
								font-weight:900;
								font-size:14px;
								border-top:0; " align="right">
								<p style="margin-top:0;
									margin-bottom:0;
									font-size:14px;
									font-weight:900;
									line-height:20px;
									margin-right:0;
									margin-left:0;
									color:#000;
									font-family:Lato,Helvetica,Roboto,sans-serif
								">
									<?php echo $order->get_formatted_order_total(); //phpcs:ignore ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<!-- Action button -->
				<button style="
						margin-top: 25px;
						margin-bottom: 25px;
						text-align: center;
						border: none;
						border-radius: 8px;
						padding-top: 15px;
						padding-bottom: 15px;
						padding-right: 40px;
						padding-left: 40px;
						font-weight: 600;
						font-size: 14px;
						color: #fff;
						background-color: #0040e0;
				">
					<a href="#" style="text-decoration: none; color: #fff; text-transform: uppercase"> Visit your account <a>
				</button>
			</div>
		</div>
	</center>
</div>
