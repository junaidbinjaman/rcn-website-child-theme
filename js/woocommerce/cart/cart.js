jQuery(document).ready(function ($) {
    wcQtyPlusMinusBtn($);
});

function wcQtyPlusMinusBtn($) {
    var plusBtn = $('.rch-child-cart-qty-plus-btn');
    var minusBtn = $('.rch-child-cart-qty-minus-btn');

    minusBtn.on('click', function () {
        var input = $(this).siblings('input');
        
        incrementDecrementValue('minus', input)
    });

    plusBtn.on('click', function () {
        var input = $(this).siblings('input');
        
        incrementDecrementValue('plus', input)
    });

    function incrementDecrementValue(action, input) {
        var value = parseInt(input.val());
        
        if ('minus' === action && value > 1) {
            input.val(value - 1);
        }

        if ('plus' === action) {
            input.val(value + 1);
        }
    }
}
