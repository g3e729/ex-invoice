
function copyElement(item, container) {
    let type = container == '#cart' ? '.product' : '.payment';
    let type2 = container == '#cart' ? 'products' : 'payments';
    let items = $(type).length - 1;
    let productItem = $($(item).clone()[0]);

    productItem.removeClass('d-none');

    if (items > 0) {
        productItem.find(".remove").removeClass('d-none');
    }

    productItem.removeAttr('id');
    let inputs = productItem.find('.field');

    inputs.each(function (index, item) {
        let inpName = $(item).data('name');
        $(item).attr('name', type2 + '[' + items + '][' + inpName + ']');
    });

    $(container).append(productItem);
}

function compute() {
    let total = 0;
    let items = $(".product");

    items.each(function (index, item) {
        if (! $(item).hasClass('d-none')) {  
            let price = $($(item).find("[data-price]")[0]).val();
            let quantity = $($(item).find("[data-quantity]")[0]).val();
            let tax = $($(item).find("[data-tax]")[0]).val();
            let amount = price * quantity;
            amount = (amount * (tax / 100)) + amount;

            total = total + amount;
        }
    });

    $("#total span").text(total.toFixed(2));
    $("input[name='amount']").val(total.toFixed(2));
}

$(document).ready(function() {
    if ($('.product').length < 2) {
        copyElement("#product-holder", "#cart");
    } else {
        $('.product-select').each(function (index, item) {
            if (! $(item).data('name')) {
                getPrice(item);
            }
        });
    }

    if ($('.payment').length < 2) {
        copyElement("#payment-holder", "#payment");
    }

    compute();
});

$("#add-product").on('click', function () {
    copyElement("#product-holder", "#cart");
});

$("#add-payment").on('click', function () {
    copyElement("#payment-holder", "#payment");
});

function removeParent(e) {
    $(e).parent("div").remove();
    compute();
}

function getPrice(e) {
    let productId = $(e).val();
    let price = $('#product-' + productId).val();
    $(e).next('input').val(price);
    compute();
}