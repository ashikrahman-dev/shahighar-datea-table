function increaseValue() {
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    document.getElementById('number').value = value;
}

function decreaseValue() {
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 0 : value;
    value < 1 ? value = 1 : '';
    value--;
    document.getElementById('number').value = value;
}

function increaseValue1() {
    var value = parseInt(document.getElementById('number1').value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    document.getElementById('number1').value = value;
}

function decreaseValue1() {
    var value = parseInt(document.getElementById('number1').value, 10);
    value = isNaN(value) ? 0 : value;
    value < 1 ? value = 1 : '';
    value--;
    document.getElementById('number1').value = value;
}

(function($) {
    $(document).ready(function() {
        function changeTotalSubtotal(select_delevery_price=null) {
            var select_delevery_price;
            if(select_delevery_price != 0) {
                $('.delivery-in-dhaka-price').text(80);
                select_delevery_price = parseInt($('input[name="select_delevery_location"]:checked').val());
            } else {
                $('.delivery-in-dhaka-price').text(0);
                select_delevery_price = 0;
            }
            var selectedProduct = $('input[name="selected_product"]:checked').val();
            var quantity = parseInt($('input[name="selected_product"]:checked').closest('.product-talbina-wrap').find('input.product-quantity').val());
            var price = parseInt($('input[name="selected_product"]:checked').data('price'));
            var username = $('.form-section-inner-wrap input[name="name"]').val();
            var phone = $('.form-section-inner-wrap input[name="mobile-number"]').val();
            var address = $('.form-section-inner-wrap input[name="address"]').val();
            var subtotal = parseInt(price*quantity);
            var total = parseInt(subtotal + select_delevery_price);
            var subtotalHtmlDom = $('p.subtotal-price span.subtotal-price');
            var totalHtmlDom = $('p.subtotal-price span.total-price');
            subtotalHtmlDom.text(subtotal);
            totalHtmlDom.text(total);
            var product_info = {
                selectedProduct: selectedProduct,
                select_delevery_price: select_delevery_price,
                quantity: quantity,
                price: price,
                username: username,
                phone: phone,
                address: address,
                subtotal: subtotal,
                total: total
            }
            return product_info;
        }
        changeTotalSubtotal();

        function comboProductCheckFilter() {
            /**
             * if check combo product then shipping charge will not add for dhaka city
            * */
            var selectedProductComboProductCheck = $('input[name="selected_product"]:checked').data('combo_product');
            if(true == selectedProductComboProductCheck) {
                select_delevery_price = parseInt($('input[name="select_delevery_location"]:checked').val());
                if(select_delevery_price == 120) {
                    delivery_price = 120;
                } else {
                    delivery_price = 0;
                }
                changeTotalSubtotal(delivery_price);
            } else {
                changeTotalSubtotal();
            }
        }

        $('input[name="selected_product"]').on('change', function() {
            comboProductCheckFilter();
        })
        $('input[name="select_delevery_location"]').on('change', function() {
            comboProductCheckFilter();
        })
        $('.value-button').on('click', function() {
            comboProductCheckFilter();
        })
        $('.form-submit-btn').on('click', function() {
            comboProductCheckFilter();
        });
        $('.product-quantity').on('change', function() {
            $(this).closest('.product-talbina-wrap').find('input[name="selected_product"]').prop('checked', true);
            comboProductCheckFilter();
        });
        $('.form-submit-btn').on('click', function() {
            var data = changeTotalSubtotal();
            var dataForAjax = {
                data: data,
                action: 'product_delevary_ajax_func'
            };
            $.ajax({
                type: 'POST',
                action: dataForAjax, // This should be the correct action name
                url: ajaxurl.ajaxurl, // WordPress AJAX handler URL
                data: dataForAjax,
                success: function(response) {
                    console.log(response); // Handle the response from the plugin
                }
            });
        });
    });
})(jQuery)