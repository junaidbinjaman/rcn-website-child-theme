jQuery(document).ready(function ($) {});

function quantityHandler($, product_id, quantity) {
    $.ajax({
        type: 'POST',
        url: wp_cart_ajax.url,
        data: {
            action: 'foobar',
            nonce: wp_cart_ajax.nonce,
            product_id: product_id,
            quantity: quantity,
        },
        success: function (response) {
            response = JSON.parse(response);

            if (response.status) {
                $(`#rcn-child-cart-qty-${response.product_id}`).val(response.quantity);
            }
        },
        error: function (xhr, status, error) {
            console.log('Error');
        },
    });
}
