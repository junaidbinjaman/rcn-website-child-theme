<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<?php
$shop_page_id        = get_option( 'woocommerce_shop_page_id' );
$shop_page_permalink = get_the_permalink( $shop_page_id );
?>

<div>
	<div class="rcn-child-cart-continue-shopping-btn">
		<a href="<?php echo esc_html( $shop_page_permalink ); ?>" class="rcn-child-cart-continue-shopping-btn">
			<i class="eicon-angle-left"></i>
			<span>Shopping Continue</span>
		</a>
	</div>
	<hr class="rcn-child-cart-header-divider" />
	<h3 class="rcn-child-cart-secondary-header">Shopping cart</h3>
	<p class="rcn-child-cart-paragraph">You have <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?> items in your cart</p>
</div>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<div class="rcn-child-cart-container">
	<?php
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		/**
		 * Filter the product name.
		 *
		 * @since 2.1.0
		 * @param string $product_name Name of the product in the cart.
		 * @param array $cart_item The product in the cart.
		 * @param string $cart_item_key Key for the product in the cart.
		 */
		$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>
			<div class="rcn-child-cart-item">
				<div class="rcn-child-cart-item-thumbnail">
				<?php
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

				if ( ! $product_permalink ) {
					echo $thumbnail; // PHPCS:ignore.
				} else {
					printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS:ignore.
				}
				?>
				</div>
				<div class="rcn-child-cart-item-data">
					<div class="rcn-child-cart-item-name">
					<?php
					if ( ! $product_permalink ) {
						echo wp_kses_post( $product_name . '&nbsp;' );
					} else {
						/**
						 * This filter is documented above.
						 *
						 * @since 2.1.0
						 */
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<h2>%s</g2>', $_product->get_name() ), $cart_item, $cart_item_key ) );
					}

					do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

					echo wp_kses_post( sprintf( '<p>%s</p>', 'Extra cheese and toping' ) );

					// Meta data.
					echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS:ignore.

					// Backorder notification.
					if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
					}
					?>
					</div>
					<div class="rcn-child-cart-item-action">
						<div class="rcn-child-cart-price-qty">

						<!-- Product price -->
						<?php
							printf( '<p><small>Price:</small><br />%s</p>',apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) ); // phpcs:ignore
						?>

						<!-- Qty input -->
						
						<div class="rcn-child-cart-qty">
							<label for="rcn-child-cart-qty-<?php echo esc_attr( $product_id ); ?>" data-action="minus">
								<span class="dashicons dashicons-minus"></span>
							</label>
							<input 
							type="number" 
							name="qty"
							value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
							id="rcn-child-cart-qty-<?php echo esc_attr( $product_id ); ?>"
							data-product-id="<?php echo esc_attr( $product_id ); ?>"
							/>
							<label for="rcn-child-cart-qty-<?php echo esc_attr( $product_id ); ?>" data-action="plus">
								<span class="dashicons dashicons-plus"></span>
							</label>
						</div>

						<!-- Product subtotal -->
						<?php
							printf( '<p><small>Subtotal:</small><br />%s</p>',apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) ); // phpcs:ignore
						?>
						</div>
						<div class="rcn-child-cart-product-remove">
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove rcn-child-cart-product-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								/* translators: %s is the product name */
								esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() ),
								wp_kses_post( '<span class="dashicons dashicons-trash"></span>' )
							),
							$cart_item_key
						);
						?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>


<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
