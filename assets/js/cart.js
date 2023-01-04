$(document).ready(function () {
    //increment quantity
    $('.increment-btn').click(function () { 
		//purpose of closest and find is para di mag kapareho yung mga value ng quantity and stocks 
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var stocks = parseInt($(this).closest('.product-data').find('.with-stocks').attr('data-stocks'));

        var quant = isNaN(quantity) ? 0 : quantity;

        if (quant < stocks){
            quant++;  
            $(this).closest('.product-data').find('.quantity').val(quant);
        }
    });
    //decrement quantity
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
        var price = $(this).closest('.product-data').find('.price').attr('data-price');

        total = price * quantity;
        formatted = currencyconverter(total);

        $.ajax({
            type: "POST",
            url: "assets/includes/updateCart-inc.php",
            data: {
                prodID: productID, quantity: quantity
            },
            success: function (response) {
                console.log(response);
            },
            error: function() {
                console.log('not success');
            }
        });
        $(this).closest('.product-data').find('.subtotal').html(formatted); 
        calculateCart();
    });

    //update quantity using inputs
	$('.quantity').focusout(function () {
        var quantity = $(this).closest('.product-data').find('.quantity').val();
        var productID = $(this).closest('.product-data').find('.product-id').val();
        var stocks = parseInt($(this).closest('.product-data').find('.with-stocks').attr('data-stocks'));
        var price = parseInt($(this).closest('.product-data').find('.price').attr('data-price'));

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
                    error: function () {
                        console.log('not success');
                    }
                });
                $(this).closest('.product-data').find('.subtotal').html(formatted);
                $(this).closest('.product-data').find('.quantity').val(stocks);
                calculateCart();  
            });
        }
        //if the quantity that the user inputs is 0, it will show alert
        else if(quantity <= 0 || quantity === ''){
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
                        prodID: productID, quantity: 1
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function () {
                        console.log('not success');
                    }
                });
                $(this).closest('.product-data').find('.subtotal').html(formatted);
                $(this).closest('.product-data').find('.quantity').val(1);
                calculateCart();  
            });
        }
        else{
            //will use the price * quantity formula if the user inputs not greater than the stocks available
            total = price * quantity;
            formatted = currencyconverter(total);
            $.ajax({
                type: "POST",
                url: "assets/includes/updateCart-inc.php",
                data: {
                    prodID: productID, quantity: quantity
                },
                success: function (response) {
                    console.log(response);
                },
                error: function() {
                    console.log('not success');
                }
            });
            $(this).closest('.product-data').find('.subtotal').html(formatted);
            calculateCart();  
        }     
	});

//CHECKBOXES
    //selecting one checkbox 
    $('.select-one').click(function () { 
        var childCheckboxes = $(this).closest('.prodak-with-stock').find('.select-one');
        var parentCheckbox = $(this).closest('.prodak-with-stock').find('.selectall-in-shop');
        var grandParentCheckbox = $('#select-all');
        var grandchild = $('.select-one');
        var productID = $(this).closest('.product-data').find('.product-id').val();

        if (childCheckboxes.filter(":checked").length === childCheckboxes.length) {
            parentCheckbox.prop("checked", true); 
        } else {
            parentCheckbox.prop("checked", false);
        }

        if (grandchild.filter(":checked").length === grandchild.length){
            grandParentCheckbox.prop("checked", true);
        } 
        else{
            grandParentCheckbox.prop("checked", false);
        }
        //for child checkbox
        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
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

    //selecting all product per shop
    $('.selectall-in-shop').click(function () { 
        var childCheckboxes = $(this).closest('.prodak-with-stock').find('.select-one');
        var parentCheckbox = $(this).closest('.prodak-with-stock').find('.selectall-in-shop');
        var shopID = $(this).closest('.prodak-with-stock').find('.shopID').val();
        var grandParentCheckbox = $('#select-all');
        var parent = $('.selectall-in-shop');

        if (parent.filter(":checked").length === parent.length) {
            grandParentCheckbox.prop("checked", true);
        } else {
            grandParentCheckbox.prop("checked", false);
        }
        childCheckboxes.prop("checked", parentCheckbox.prop("checked")); 

        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
            data: {
                checked: $(this).is(":checked"), shopID: shopID
            },
            success: function () {
                console.log('success');
            }
        });
        calculateCart();
    });

    //selecting all checkbox
    $('#select-all').click(function () { 
        $('.tsekbox').prop("checked", $(this).prop("checked"));

        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
            data: {
                checked: $(this).is(":checked")
            },
            success: function () {
                console.log('success');
            }
        });
        calculateCart();
    });

    //to calculate the grand total of cart
    function calculateCart() {
        var grandtotal = 0;
        var items = $(".product-data");
        var total = 0;
        var checked = $('.tsekbox').filter(':checked');        
        $.each(items, function() {
            var checkbox = $(this).find(".tsekbox");
            var itemQuantity = $(this).find('.quantity').val();
            var itemPrice = parseInt($(this).find('.price').attr('data-price'));
            var stocks = parseInt($(this).find('.with-stocks').attr('data-stocks'));

            if (checkbox.prop("checked")){
                // total += checkbox.length;
                if (itemQuantity <= 0 && stocks != 0){
                    grandtotal += itemPrice * 1;  
                    total += checkbox.length;
                }
                else if (itemQuantity > stocks && stocks != 0){
                    grandtotal += itemPrice * stocks;   
                    total += checkbox.length; 
                }
                else if (stocks != 0){   
                    grandtotal += itemPrice * itemQuantity;   
                    total += checkbox.length;
                }
            }
        });

        if (checked.length > 0 ) {
            //enable the check out if one checkbox is checked
            $('.place-order').removeClass("disabled");
            //enable the delete button 
            $('.del').click(function () { 
                Swal.fire({
                    title: 'Confirmation',
                    text: "Do you want to remove " + total + " product/s in your cart?",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location = 'assets/includes/deleteAllinCart-inc.php?type=checked';
                    }
                })
            });
        } else {
            //disable checkout if all checkbox is unchecked
            $('.place-order').attr("class", "btn btn-primary place-order disabled"); 
            //disable the delete button
            $('.del').click(function () { 
                Swal.fire({
                    text: 'Please select a product(s) to be deleted',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000
                });
            });      
        }
        const formatted = currencyconverter(grandtotal);
        $(".grand-total").html(formatted);
        $(".item-count").html('Total (' + total + ' item):&nbsp');
    }

    function currencyconverter(number){
        var formattedNumber = new Intl.NumberFormat("en-US", {
            style: 'currency', 
            currency: 'PHP'
        }).format(number);
        return formattedNumber;
    }

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

    var data = $('.select-all').attr('data-count');
    if(data == 0){
        $(".tsekbox").prop("disabled", true); 
        $("#select-all").prop("checked", false);
        $('.del').attr("class", "btn del disabled"); 
    }
    else{
        $(".tsekbox").prop ("disabled", false); 
    } 
});



