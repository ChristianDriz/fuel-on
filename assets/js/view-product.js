$(document).ready(function () {
    $('.increment-btn').click(function () { 
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var stocks = parseInt($('.stocks').attr('id'));

        var quant = isNaN(quantity) ? 0 : quantity;

        if (quant < stocks){
            quant++;
            $(this).closest('.product-data').find('.quantity').val(quant);
        }
    });

    $('.decrement-btn').click(function () { 
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());

        var quant = isNaN(quantity) ? 0 : quantity;

        if (quant > 1){
            quant--;
            $(this).closest('.product-data').find('.quantity').val(quant);
        }
    });


    //only accepts number in input type text
    $('.quantity').keypress(function(e) {
        var x  = e .which || e.keycode;
        if(x >= 48 && x <= 57){
            return true;
        }
        else{
            return false;
        }
    });
});