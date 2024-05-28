<?php
/**
 * The cart qty input html code.
 *
 * @package rcn-child
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $args ) ) {
	return;
}

$product_id = isset( $args['product_id'] ) ? $args['product_id'] : null;
$cart_item  = isset( $args['cart_item'] ) ? $args['cart_item'] : null;
$_product   = wc_get_product( $product_id );

?>

<div class="rcn-child-cart-qty">
	<label
	for="rcn-child-cart-qty-<?php echo esc_attr( $product_id ); ?>"
	data-action="minus"
	data-product-id="<?php echo esc_attr( $product_id ); ?>"
	>
		<span>
			<i aria-hidden="true" class="fas fa-minus"></i>
		</span>
	</label>
		<input 
		type="number" 
		name="qty"
		value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
		class="rcn-child-cart-qty-<?php echo esc_attr( $product_id ); ?>"
		disabled
		data-available-stock="<?php echo esc_attr( $_product->get_stock_quantity() ); ?>"
		/>
	<label
	for="rcn-child-cart-qty-<?php echo esc_attr( $product_id ); ?>"
	data-action="plus"
	data-product-id="<?php echo esc_attr( $product_id ); ?>"
	>
		<span>
			<i aria-hidden="true" class="fas fa-plus"></i>
		</span>
	</label>
</div>
