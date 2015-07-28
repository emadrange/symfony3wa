/**
 * Created by wap75 on 28/07/15.
 */

$(document).ready(function(){

    /*******************************************/
    /* suppression d'un élément dans le caddie */
    /*******************************************/

    $('#detailCart').on('click', '.btn-danger', function(event){

        event.preventDefault();

        var $product = $(this).closest('.item-product');

        if (confirm('Voulez-vous enlever ce produit'))
        {    
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'json'
            }).done(function(data, textStatus){

                var productTotal = $product.find('.product-total').text();
                var subTotal = $('.sub-total').text() - productTotal;
                var total = subTotal + 4.25;

                $('.sub-total').text(parseFloat(subTotal).toFixed(2));
                $('.total').text(parseFloat(total).toFixed(2));

                $product.fadeOut(700, function(){
                    $(this).remove();

                    $('.alert-info').fadeOut("slow");
                    $('.alert-danger h4').text(data);
                    $('.alert-danger').fadeIn("slow");
                });

                if (subTotal === 0)
                {
                    $('#detailCart').fadeOut(700, function(){
                        $(this).remove();

                        $('.alert-danger h4').text('Votre caddie est vide');
                        $('.alert-danger').fadeIn("slow");
                    });
                }
            });
        }
    });

    /***************************************/
    /* Décrémente la quantité d'un élément */
    /***************************************/

    $('#detailCart').on('click', '.btn-warning', function(event){

        event.preventDefault();

        var $product = $(this).closest('.item-product');
        var quantity = parseFloat($product.find('.quantity').val()) - 1;

        if (quantity === 0)
        {
            $product.find('.btn-danger').trigger('click');
        }
        else
        {
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'json'
            }).done(function(data, textStatus){

                $product.find('.quantity').val(quantity);

                var productPrice = $product.find('.product-price').text();
                $product.find('.product-total').text(parseFloat(productPrice * quantity).toFixed(2));

                var subTotal = parseFloat($('.sub-total').text()) - productPrice;
                $('.sub-total').text(parseFloat(subTotal).toFixed(2));
                $('.total').text(parseFloat(subTotal + 4.25).toFixed(2));

                $('.alert-danger').fadeOut("slow");
                $('.alert-info h4').text(data);
                $('.alert-info').fadeIn("slow");
            });
        }
    });

    /***************************************/
    /* Incrémente la quantité d'un élément */
    /***************************************/

    $('#detailCart').on('click', '.btn-success', function(event){

        event.preventDefault();

        var $product = $(this).closest('.item-product');

        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json'
        }).done(function(data, textStatus){

            var quantity = parseFloat($product.find('.quantity').val()) + 1;
            $product.find('.quantity').val(quantity);

            var productPrice = parseFloat($product.find('.product-price').text());
            $product.find('.product-total').text(parseFloat(productPrice * quantity).toFixed(2));

            var subTotal = parseFloat($('.sub-total').text()) + productPrice;
            $('.sub-total').text(parseFloat(subTotal).toFixed(2));
            $('.total').text(parseFloat(subTotal + 4.25).toFixed(2));

            $('.alert-danger').fadeOut("slow");
            $('.alert-info h4').text(data);
            $('.alert-info').fadeIn("slow");
        });
    });
});
