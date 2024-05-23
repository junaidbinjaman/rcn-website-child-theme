jQuery(document).ready(function ($) {
    $('.rcn-child-cart-qty label').on('click', function () {
        var action = $(this).data('action');
        var product_id = $(this).data('product-id');
        var input = $(`#rcn-child-cart-qty-${product_id}`);
        var quantity = parseInt(input.val());
        var availableStock = parseInt(input.data('available-stock'));

        if (quantity === 1 && 'minus' === action) {
            return;
        }

        if (availableStock === quantity && 'plus' === action) {
            return;
        }

        $('.rcn-child-cart-loading-screen').fadeIn();

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

            if (response.status) {
                $(`#rcn-child-cart-qty-${response.product_id}`).val(
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

                $('.rcn-child-cart-loading-screen').fadeOut();
            }
        },
        error: function (xhr, status, error) {
            console.log('Error');
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
