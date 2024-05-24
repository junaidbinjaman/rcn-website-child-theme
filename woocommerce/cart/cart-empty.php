<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

$shop_page_id        = get_option( 'woocommerce_shop_page_id' );
$shop_page_permalink = get_the_permalink( $shop_page_id );
?>

<div>
	<div class="rcn-child-cart-continue-shopping-btn">
		<a href="<?php echo esc_html( $shop_page_permalink ); ?>" class="rcn-child-cart-continue-shopping-btn">
			<i class="eicon-angle-left"></i>
			<span>Continue Shopping</span>
		</a>
	</div>
	<hr class="rcn-child-cart-header-divider" />
	<h3 class="rcn-child-cart-secondary-header">Shopping cart</h3>
	<p class="rcn-child-cart-paragraph">You have <span><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span> items in your cart</p>
</div>

<div class="rcn-child-cart-empty-screen">
	<img src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/img/empty-cart-icon.png' ); ?>" width="68" height="68" alt="df">
	<p>No item in your cart</p>
</div>
