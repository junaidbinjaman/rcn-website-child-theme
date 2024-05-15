<?php
/*
This file is part of a child theme called Astra Child - RCN.
Functions in this file will be loaded before the parent theme's functions.
For more information, please read
https://developer.wordpress.org/themes/advanced-topics/child-themes/
// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)
function your_theme_enqueue_styles() {
$parent_style = 'parent-style';
wp_enqueue_style( $parent_style,
get_template_directory_uri() . '/style.css');
wp_enqueue_style( 'child-style',
get_stylesheet_directory_uri() . '/style.css',
array($parent_style),
wp_get_theme()->get('Version')
);
}
add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');
/*
	Add your own functions below this line.
======================================== */

// * Redirect WordPress Logout to Home Page
add_action( 'wp_logout', 'ik_redirect_after_logout' );
function ik_redirect_after_logout() {
	wp_safe_redirect( home_url() );
	exit;
}


// add rcn logo on login page
function ik_rcn_login_logo() {
	?>
	<style type="text/css">
	body.login div#login h1 a {
		background-image: url(https://staging3.rcndevelopment.com/wp-content/uploads/2022/03/RCN-White-_-Transparent-e1648642862748.png);
		background-size: 220px ! important;
		width: 265px ! important;
	}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'ik_rcn_login_logo' );


// Creates new Woocommerce Dashboard with menu items
add_filter( 'woocommerce_account_menu_items', 'ik_rcn_woocommerce_panel_nav' );
function ik_rcn_woocommerce_panel_nav() {
	$items = array(
		'dashboard'       => __( 'Dashboard', 'woocommerce' ),
		'orders'          => __( 'Orders', 'woocommerce' ),
		'payment-methods' => __( 'Payment Methods', 'woocommerce' ),

		'edit-account'    => __( 'Your Profile', 'woocommerce' ),
		'customer-logout' => __( 'Logout', 'woocommerce' ),
	);

	return $items;
}

// Add custom column to WooCommerce orders page
add_filter( 'manage_edit-shop_order_columns', 'add_coupon_column_to_orders' );
function add_coupon_column_to_orders( $columns ) {
	$new_columns = array();

	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;

		// Insert the custom column after the "Date" column
		if ( $key === 'order_date' ) {
			$new_columns['order_coupon'] = 'Coupon Used';
		}
	}

	return $new_columns;
}

// Populate custom column with coupon data
add_action( 'manage_shop_order_posts_custom_column', 'populate_coupon_column' );
function populate_coupon_column( $column ) {
	global $post;

	if ( $column == 'order_coupon' ) {
		$order = wc_get_order( $post->ID );

		$coupons = $order->get_used_coupons();

		if ( ! empty( $coupons ) ) {
			echo implode( ', ', $coupons );
		} else {
			echo '';
		}
	}
}

// Making product stock input type as text box
function jd_rcn_admin_enqueue_scripts_for_product_edit_page() {
	$current_screen = get_current_screen();

	if ( $current_screen->post_type === 'product' && $_GET['action'] === 'edit' ) {
		?>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				// Stock input box
				var jdRCNStockInputBox = document.querySelector("#_stock")
				jdRCNStockInputBox.type = "text";
				// get confirmation for stock change
				jdRCNStockInputBox.addEventListener('click', function (e) {
					var result = window.confirm("Are you sure you want to change stock amount?");
					if (result == false) {
						jdRCNStockInputBox.disabled = true;
					}
				});

				// Low stock input box
				var jdRCNLowStockInputBox = document.querySelector("#_low_stock_amount");
				jdRCNLowStockInputBox.type = "text";
				// get confirmation for low stock change
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

function jd_rcn_login_error_message( $error ) {
	return 'Invalid email or password';
}


add_filter( 'login_errors', 'jd_rcn_login_error_message' );

function redirect_non_logged_in_users_to_login() {

	$current_uri = $_SERVER['REQUEST_URI'];

	// if ($current_uri === '/account-password/lost-password/') {
	// echo $current_uri;
	// exit;
	// }

	if ( $current_uri == '/my-account/lost-password/?show-reset-form=true&action' ) {
		return;
	}

	// Check if the user is not logged in
	if ( is_user_logged_in() && is_page( 'account-password' ) ) {
		// Redirect to the login page
		wp_safe_redirect( '/my-account' );
		exit;
	}

	// Check if the user is not logged in
	if ( ! is_user_logged_in() && is_page( 'my-account' ) ) {
		// Redirect to the login page
		wp_safe_redirect( '/login' );
		exit;
	}

	if ( is_user_logged_in() && is_page( 'login' ) ) {
		wp_safe_redirect( '/' );
		exit;
	}
}
add_action( 'template_redirect', 'redirect_non_logged_in_users_to_login' );

// Add this code to your custom plugin or theme's functions.php file

function modify_product_price_based_on_quantity( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	// Iterate through each cart item
	foreach ( $cart->get_cart() as $cart_item ) {
		$quantity = $cart_item['quantity'];

		// Modify the price based on your rules
		if ( $quantity <= 3 ) {
			$cart_item['data']->set_price( 0 );
		} elseif ( $quantity == 4 ) {
			$cart_item['data']->set_price( 100 );
		} else {
			// Assuming the price doubles for each additional quantity
			$cart_item['data']->set_price( 100 * ( 2 ** ( $quantity - 4 ) ) );
		}
	}
}
// add_action( 'woocommerce_before_calculate_totals', 'modify_product_price_based_on_quantity' );

?>