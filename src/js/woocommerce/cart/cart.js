jQuery(document).ready(function ($) {
    couponVisibilityHandler($);

    $('.rcn-child-cart-qty label').on('click', function () {
        var action = $(this).data('action');
        var product_id = $(this).data('product-id');
        var input = $(`.rcn-child-cart-qty-${product_id}`);
        var quantity = parseInt(input.val());

        $('.rcn-child-cart-loading-screen').fadeIn();

        if (quantity === 1 && 'minus' === action) {
            quantityHandler($, product_id, 0);
            return;
        }

        if ('minus' === action) {
            quantity = quantity - 1;
            quantityHandler($, product_id, quantity);
        }

        if ('plus' === action) {
            quantity = quantity + 1;
            quantityHandler($, product_id, quantity);
        }
    });
});

function quantityHandler($, product_id, quantity) {
    $.ajax({
        type: 'POST',
        url: wp_cart_ajax.url,
        data: {
            action: 'update_product_quantity',
            nonce: wp_cart_ajax.nonce,
            product_id: product_id,
            quantity: quantity,
        },
        success: function (response) {
            response = JSON.parse(response);

            // The customer is running out of stock.
            if (!response.status && response.status_code === 111) {
                noticeHandler($, response.notice);
                $('.rcn-child-cart-loading-screen').fadeOut();
                return;
            }

            // The customer is trying to go beyond 1
            if (!response.status && response.status_code === 110) {
                noticeHandler($, response.notice);
                $('.rcn-child-cart-loading-screen').fadeOut();
                return;
            }

            // The product is completely out of stock.
            if (!response.status && response.status_code === 112) {
                noticeHandler($, response.notice);
                $('.rcn-child-cart-loading-screen').fadeOut();
                return;
            }

            if (response.status) {
                $(`.rcn-child-cart-qty-${response.product_id}`).val(
                    response.quantity
                );

                itemSubtotalHandle(
                    $,
                    response.product_id,
                    response.item_subtotal
                );
                cartSubtotalHandler($, response.cart_subtotal);
                cartTotalHandle($, response.cart_total);
                handleCartContentCounts($, response.cart_content_count);
                cartTotalTaxHandler($, response.cart_tax_total);
                noticeHandler($, response.notice);
                discountUpdateHandler($, response.cart_total_discount);

                $('.rcn-child-cart-loading-screen').fadeOut();
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
    });
}

function itemSubtotalHandle($, product_id, subtotal) {
    $(`.rcn-child-cart-item-${product_id}`)
        .find('.rcn-child-cart-item-subtotal .woocommerce-Price-amount')
        .each(function () {
            $(this).replaceWith(subtotal);
        });
}

function cartSubtotalHandler($, subtotal) {
    $('.rcn-child-cart-subtotal td').html(subtotal);
}

function cartTotalHandle($, total) {
    $('.rcn-child-order-total td').html(total);
}

function handleCartContentCounts($, count) {
    $('.rcn-child-cart-paragraph> span').text(count);
}

function cartTotalTaxHandler($, tax) {
    $('.rcn-child-tax-total td').html(tax);
}

function noticeHandler($, notice) {
    $('.woocommerce-notices-wrapper').html(notice);
}

function discountUpdateHandler($, discountHTML) {
    $('.rcn-child-cart-discount td').html(discountHTML);
}

function couponVisibilityHandler($) {
    $('.rcn-child-cart-coupon-wrapper').on('click', 'p', function () {
        $(this).hide();
        $(this).siblings().css('display', 'flex');
    });
}
