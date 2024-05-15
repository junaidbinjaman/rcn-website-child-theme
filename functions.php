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
 * Remove tag support from wp blog
 *
 * @return void
 */
function remove_tags_support() {
	unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}
add_action( 'init', 'remove_tags_support' );

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