<?php
/**
 * The main functions file of the child theme
 *
 * This file is part of a child theme called RCN Child.
 * The RCN Child is a custom astra child theme developed explicitly for RCN
 * Functions in this file will be loaded before the parent theme's functions.
 * For more information, please read
 * https://developer.wordpress.org/themes/advanced-topics/child-themes/
 *
 * @package rcn-child
 */

use ElementorPro\Modules\Forms\Fields\Number;

use function PHPSTORM_META\type;

/**
 * Enqueue styles & scripts
 *
 * @return void
 */
function your_theme_enqueue_styles() {
	$parent_style = 'parent-style';
	wp_enqueue_style(
		$parent_style,
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get( 'Version' )
	);

	wp_register_style(
		'rcn-child-cart',
		get_stylesheet_directory_uri() . '/src/css/woocommerce/cart/cart.css',
		array( 'elementor-frontend', 'elementor-post-31' ),
		fileatime(
			get_stylesheet_directory() . '/src/css/woocommerce/cart/cart.css'
		),
		'all'
	);

	wp_register_script(
		'rcn-child-cart-scripts',
		get_stylesheet_directory_uri() . '/src/js/woocommerce/cart/cart.js',
		array( 'jquery' ),
		fileatime( get_stylesheet_directory() . '/src/js/woocommerce/cart/cart.js' ),
		true
	);

	wp_localize_script(
		'rcn-child-cart-scripts',
		'wp_cart_ajax',
		array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'wc-cart-nonce' ),
		)
	);

	/**
	 * Fixes the elementor mini cart popup issue
	 *
	 * The popup doesn't show anything is the wc cart fragment is not enqueued properly
	 */
	wp_enqueue_script( 'wc-cart-fragments' );

	// Load WC cart scripts and css on cart page only.
	if ( is_page( wc_get_page_id( 'cart' ) ) || is_page( 31446 ) ) { // 31446 is the development cart page id.
		wp_enqueue_style( 'rcn-child-cart' );
		wp_enqueue_script( 'rcn-child-cart-scripts' );
	}
}

add_action( 'wp_enqueue_scripts', 'your_theme_enqueue_styles' );

/*
========================================
	Add your own functions below this line.
========================================
*/

/**
 * Redirect logged out users to the home page
 *
 * @return void
 */
function ik_redirect_after_logout() {
	wp_safe_redirect( home_url() );
	exit;
}

add_action( 'wp_logout', 'ik_redirect_after_logout' );

/**
 * Used coupon column on order listing page
 *
 * The function add a new column on order listing that that
 * shows the used coupon.
 *
 * @param array $columns An associative array that contains the columns data.
 * @return array
 */
function add_coupon_column_to_orders( $columns ) {
	$new_columns = array();

	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;

		if ( 'order_date' === $key ) {
			$new_columns['order_coupon'] = 'Coupon Used';
		}
	}

	return $new_columns;
}

add_filter( 'manage_edit-shop_order_columns', 'add_coupon_column_to_orders' );

/**
 * The function display used coupon.
 *
 * @param array $column An associative array that contains the columns data.
 * @return void
 */
function populate_coupon_column( $column ) {
	global $post;

	if ( 'order_coupon' === $column ) {
		$order = wc_get_order( $post->ID );

		$coupons = $order->get_coupon_codes();

		if ( ! empty( $coupons ) ) {
			echo esc_html( implode( ', ', $coupons ) );
		} else {
			echo '';
		}
	}
}

add_action( 'manage_shop_order_posts_custom_column', 'populate_coupon_column' );

/**
 * Teh function changes the stock input type into text
 *
 * On single product edit page, the quantity input type by default is number
 * This function changes it to a text box to avoid changing the number by mistake
 *
 * @return void
 */
function jd_rcn_admin_enqueue_scripts_for_product_edit_page() {
	$current_screen = get_current_screen();
    $action         = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : ''; // phpcs:ignore

	if ( 'product' === $current_screen->post_type && 'edit' === $action ) {
		?>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				// Stock input box
				var jdRCNStockInputBox = document.querySelector("#_stock")
				jdRCNStockInputBox.type = "text";

				jdRCNStockInputBox.addEventListener('click', function (e) {
					var result = window.confirm("Are you sure you want to change stock amount?");
					if (result == false) {
						jdRCNStockInputBox.disabled = true;
					}
				});

				// Low stock input box
				var jdRCNLowStockInputBox = document.querySelector("#_low_stock_amount");
				jdRCNLowStockInputBox.type = "text";

				jdRCNLowStockInputBox.addEventListener('click', function (e) {
					var result = window.confirm("Are you sure you want to change low stock amount?");
					if (result == false) {
						jdRCNLowStockInputBox.disabled = true;
					}
				});
			});
		</script>
		<?php
	}
}

add_action( 'admin_enqueue_scripts', 'jd_rcn_admin_enqueue_scripts_for_product_edit_page' );

/**
 * The function modifies the failed login error_clear_last
 *
 * @param string $error The error message.
 * @return string.
 */
function jd_rcn_login_error_message( $error ) {
	$error = 'Invalid email or password';
	return $error;
}

add_filter( 'login_errors', 'jd_rcn_login_error_message' );

/**
 * The function handles the user redirection on
 * - account page
 * - login
 * - Password reset page
 * - password reset form page
 *
 * @return void
 */
function redirect_non_logged_in_users_to_login() {

	$current_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

	if ( '/my-account/lost-password/?show-reset-form=true&action' === $current_uri ) {
		return;
	}

	if ( is_user_logged_in() && is_page( 'account-password' ) ) {
		wp_safe_redirect( '/my-account' );
		exit;
	}

	if ( ! is_user_logged_in() && is_page( 'my-account' ) ) {
		wp_safe_redirect( '/login' );
		exit;
	}

	if ( is_user_logged_in() && is_page( 'login' ) ) {
		wp_safe_redirect( '/' );
		exit;
	}
}

add_action( 'template_redirect', 'redirect_non_logged_in_users_to_login' );

/**
 * The function removes the marketing menu of woocommerce
 * on WordPress navbar.
 *
 * @param array $features The wc feature array.
 * @return array
 */
function remove_wc_marketing_menu( $features ) {

	return array_filter(
		$features,
		function ( $value ) {
			return 'marketing' !== $value;
		}
	);
}

add_filter( 'woocommerce_admin_features', 'remove_wc_marketing_menu' );

/**
 * Remove tag support from wp blog
 *
 * @return void
 */
function remove_tags_support() {
	unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}

/**
 * Cart coupon handler
 *
 * @return void
 */
function rcn_child_coupon_handler() {
	$coupon_code = isset( $_POST['rcn-child-cart-coupon'] ) ? sanitize_text_field( wp_unslash( $_POST['rcn-child-cart-coupon'] ) ) : '';

	if ( ! isset( $_POST['action'] ) || 'rcn-child-cart-coupon' !== $_POST['action'] ) {
		return;
	}

	if (
		! isset( $_POST['rcn-child-cart-coupon-nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rcn-child-cart-coupon-nonce'] ) ), 'rcn-child-cart-coupon-nonce' )
	) {
		return;
	}

	if ( WC()->cart->has_discount( $coupon_code ) ) {
		return;
	}

	WC()->cart->apply_coupon( $coupon_code );
}

/**
 * This is foobar.
 *
 * @return void
 */
function update_product_quantity() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wc-cart-nonce' ) ) {
		$result = array(
			'status'  => false,
			'message' => 'The nonce verification failed',
		);

		echo wp_json_encode( $result );
		wp_die();
	}

	$product_id     = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
	$quantity       = isset( $_POST['quantity'] ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : 0;
	$stock_quantity = wc_get_product( $product_id )->get_stock_quantity();
	$stock_status   = wc_get_product( $product_id )->get_stock_status();
	$quantity       = intval( $quantity );
	$cart           = WC()->cart;

	if ( 0 === $quantity ) {
		wc_add_notice( 'Please click on the trash icon to remove the product', 'error' );
		ob_start();
		wc_print_notices();
		$notices = ob_get_clean();

		$result = array(
			'status'      => false,
			'status_code' => 110, // 110 means, the customer is trying to go beyond 1.
			'notice'      => $notices,
		);

		echo wp_json_encode( $result );
		wp_die();
	}

	if ( 'outofstock' === $stock_status ) {
		$product_name = wc_get_product( $product_id )->get_title();
		$message      = "Sorry! {$product_name} cannot be purchased. Because , it's out of stock.";

		wc_add_notice( $message, 'error' );
		ob_start();
		wc_print_notices();
		$notice = ob_get_clean();

		$result = array(
			'status'      => false,
			'status_code' => 112,
			'notice'      => $notice,
		);

		echo wp_json_encode( $result );
		wp_die();
	}

	if ( null !== $stock_quantity && $quantity > $stock_quantity ) {
		wc_add_notice( 'Sorry! you\'r running out of stock. Please contact support', 'error' );
		ob_start();
		wc_print_notices();
		$notice = ob_get_clean();

		$result = array(
			'status'         => false,
			'status_code'    => 111, // 111 means, the customer is running out of stock.
			'notice'         => $notice,
			'product_id'     => $product_id,
			'quantity'       => $quantity,
			'stock_quantity' => $stock_quantity,
		);

		echo wp_json_encode( $result );
		wp_die();
	}

	foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( intval( $product_id ) === $cart_item['product_id'] ) {
			$cart->set_quantity( $cart_item_key, $quantity );

			wc_add_notice( 'The cart is updated successfully' );
			ob_start();
			wc_print_notices();
			$notices = ob_get_clean();

			$result = array(
				'status'              => true,
				'product_id'          => $product_id,
				'quantity'            => $quantity,
				'item_subtotal'       => wc_price( intval( wc_get_product( $product_id )->get_price() ) * $quantity ),
				'cart_subtotal'       => wc_price( $cart->get_subtotal() ),
				'cart_tax_total'      => wc_price( $cart->get_taxes_total() ),
				'cart_total'          => wc_price( $cart->total ),
				'cart_content_count'  => $cart->get_cart_contents_count(),
				'cart_total_discount' => $cart->get_total_discount(),
				'notice'              => $notices,
				'message'             => 'The quantity is updated successfully',
			);

			echo wp_json_encode( $result );
			wp_die();
		}
	}

		$result = array(
			'status'     => false,
			'product_id' => $product_id,
			'quantity'   => $quantity,
			'message'    => 'The quantity is not updated.',
		);

		echo wp_json_encode( $result );
		wp_die();
}

add_action( 'wp_ajax_update_product_quantity', 'update_product_quantity' );
add_action( 'wp_ajax_nopiv_update_product_quantity', 'update_product_quantity' );

/**
 * All the functions needs to be assign init hook will go in here
 *
 * @return void
 */
function init_hook_callback() {
	remove_tags_support();
	rcn_child_coupon_handler();
}

add_action( 'init', 'init_hook_callback' );

//phpcs:disabled

function foobar() {
	if ( is_admin() ) return;

	echo '<pre style="color: black">';
	var_dump( wc_get_product( 14900 )->get_stock_quantity() );
	echo '</pre>';
}

// add_action( 'woocommerce_cart_loaded_from_session', 'foobar' );
