$(document).ready(function () {	
    $('.increment-btn').click(function () { 
		//purpose of closest and find is para di mag kapareho yung mga value ng quantity and stocks 
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var stocks = parseInt($(this).closest('.product-data').find('.with-stocks').attr('id'));

        var quant = isNaN(quantity) ? 0 : quantity;

        if (quant < stocks){
            quant++;  
            $(this).closest('.product-data').find('.quantity').val(quant);
        }
    });

    $('.decrement-btn').click(function () { 
		//purpose of closest and find is para di mag kapareho yung mga value ng quantity and stocks 
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var quant = isNaN(quantity) ? 0 : quantity;

        if (quant > 1){
            quant--;
            $(this).closest('.product-data').find('.quantity').val(quant);
        }
    });

    //update quantity using the buttons
    $('.update-quantity').click(function () { 
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var productID = $(this).closest('.product-data').find('.product-id').val();
        var stocks = parseInt($(this).closest('.product-data').find('.with-stocks').attr('id'));
        var price = $(this).closest('.product-data').find('.price').attr('data-price');

        total = price * quantity;
        formatted = currencyconverter(total);

        //if the quantity that the user inputs is greater than the stocks available, it will show alert
        if(quantity > stocks){
            Swal.fire({
                title: 'Oops...',
                text: 'There are only ' +  stocks + ' item(s) and you have reach the maximum quantity.',
                icon: 'info',
                button: true
            }).then(() => {
                $(this).closest('.product-data').find('.quantity').val(stocks);
            });
        }
        else{
            $.ajax({
                type: "POST",
                url: "assets/includes/updateCart-inc.php",
                data: {
                    prodID: productID, quantity: quantity
                },
                success: function (response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log('not success');
                }
            });
            if(formatted == "₱NaN"){
                $(this).closest('.product-data').find('.subtotal').html('₱' + price);
            }
            else{
                $(this).closest('.product-data').find('.subtotal').html(formatted);
            }
        }
        calculateCart();
    });

    //update quantity using inputs
	$('.quantity').on('input', function () {
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var productID = $(this).closest('.product-data').find('.product-id').val();
        var stocks = parseInt($(this).closest('.product-data').find('.with-stocks').attr('id'));
        var price = $(this).closest('.product-data').find('.price').attr('data-price');

        //if the quantity that the user inputs is greater than the stocks available, it will show alert
        if(quantity > stocks){

            total = price * stocks;
            formatted = currencyconverter(total);

            Swal.fire({
                title: 'Oops...',
                text: 'There are only ' +  stocks + ' item(s) and you have reach the maximum quantity.',
                icon: 'info',
                button: true
            }).then(() => {
                //it will use the price * stocks formula if the user inputs more than the stocks available
                $.ajax({
                    type: "POST",
                    url: "assets/includes/updateCart-inc.php",
                    data: {
                        prodID: productID, quantity: stocks
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (e) {
                        console.log('not success');
                    }
                });
                if(formatted == "₱NaN"){
                    $(this).closest('.product-data').find('.subtotal').html('₱' + price);
                }
                else{
                    $(this).closest('.product-data').find('.subtotal').html(formatted);
                }

                //return the max stocks available 
                $(this).closest('.product-data').find('.quantity').val(stocks);
                calculateCartStock();
            });
            
        }
        //if the quantity that the user inputs is 0, it will show alert
        else if(quantity <= 0){

            total = price * 1;
            formatted = currencyconverter(total);

            Swal.fire({
                title: 'Oops...',
                text: 'Minimum quantity is 1',
                icon: 'info',
                button: true
            }).then(() => {
                //it will use the price * 1 formula if the user inputs less than the stocks available
                $.ajax({
                    type: "POST",
                    url: "assets/includes/updateCart-inc.php",
                    data: {
                        prodID: productID, quantity: stocks
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (e) {
                        console.log('not success');
                    }
                });
                if(formatted == "₱NaN"){
                    $(this).closest('.product-data').find('.subtotal').html('₱' + price);
                }
                else{
                    $(this).closest('.product-data').find('.subtotal').html(formatted);
                }
                $(this).closest('.product-data').find('.quantity').val(1);

                calculateCartOne();
            });
            
        }
        else{
            $.ajax({
                type: "POST",
                url: "assets/includes/updateCart-inc.php",
                data: {
                    prodID: productID, quantity: quantity
                },
                success: function (response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log('not success');
                }
            });
            //will use the price * quantity formula if the user inputs not greater than the stocks available
            total = price * quantity;
            formatted = currencyconverter(total);

            if(formatted == "₱NaN"){
                $(this).closest('.product-data').find('.subtotal').html('₱' + price);
            }
            else{
                $(this).closest('.product-data').find('.subtotal').html(formatted);
            }

            calculateCart();
        }       
        
	});

    //prevent the user to use enter in input type
    $('.quantity').keypress(function(e) {
        e = e || window.event;
        var key = e.keyCode || e.charCode;
        return key !== 13; 
    });


//for checkboxes

    //to select all products in shop
    $('.selectall-in-shop').change(function () { 
        var shopID = $(this).closest('.prodak').find('.shopID').val();
        var check = this.checked == true ? 1 : 0;

        $(this).closest('.prodak').find('.select-one').each(function() {
            this.checked = check;
        });
        
        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-in-shop-inc.php",
            data: {
                checked: $(this).is(":checked"), shopID: shopID
            },
            success: function (data) {
                console.log('success');
            }
        });

        calculateCart();
    });
    
    //to select all products
    $('#select-all').change(function () { 

        var check = this.checked == true ? 1 : 0;
        $('.tsekbox').each(function() {
            this.checked = check;
        });

        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
            data: {
                checked: $(this).is(":checked")
            },
            success: function (data) {
                console.log(data);
            }
        });

        calculateCart();
    });
    
    //to select one product
    $('.select-one').change(function () {   
        var productID = $(this).closest('.product-data').find('.product-id').val();

        $.ajax({
            type: "POST",
            url: "assets/includes/selectPerProduct-inc.php",
            data: {
                checkbox: $(this).is(":checked"), prodID: productID
            },
            success: function () {
                console.log('success');
            },
            error: function() {
                console.log('not success');
            }
        });

        calculateCart();
    });


    //to calculate the grand total of cart
    function calculateCart() {
        var grandtotal = 0;
        var items = $(".product-data");
        
        $.each(items, function() {
            var checkbox = $(this).find(".tsekbox");
            var itemQuantity = $(this).find('.quantity').val();
            var itemPrice = $(this).find('.price').attr('data-price');

            if (checkbox.prop("checked")) {
                grandtotal += itemPrice * itemQuantity;  
            }     
        });
        const formatted = currencyconverter(grandtotal);
        $(".grand-total").html(formatted);
    }

    //to calculate the grand total of cart using the price * stock, if the user input more than stocks 
    function calculateCartStock() {
        var grandtotal = 0;
        var items = $(".product-data");
        
        $.each(items, function() {
            var checkbox = $(this).find(".tsekbox");
            var itemPrice = $(this).find('.price').attr('data-price');
            var stocks = $(this).find('.with-stocks').attr('id');

            if (checkbox.prop("checked")) {
                grandtotal += itemPrice * stocks;  
            }     
        });
        const formatted = currencyconverter(grandtotal);
        $(".grand-total").html(formatted);
    }

    //to calculate the grand total of cart using the price * 1, if the user input less than stocks 
    function calculateCartOne() {
        var grandtotal = 0;
        var items = $(".product-data");
        
        $.each(items, function() {
            var checkbox = $(this).find(".tsekbox");
            var itemPrice = $(this).find('.price').attr('data-price');

            if (checkbox.prop("checked")) {
                grandtotal += itemPrice * 1;  
            }     
        });
        const formatted = currencyconverter(grandtotal);
        $(".grand-total").html(formatted);
    }

    function currencyconverter(number){

        let formattedNumber = new Intl.NumberFormat("en-US", {
            style: 'currency', 
            currency: 'PHP'
        }).format(number);

        return formattedNumber;
    }
});