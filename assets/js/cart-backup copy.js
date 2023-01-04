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
    });

    //update quantity using inputs
	$('.quantity').on('input', function () {
        var quantity = parseInt($(this).closest('.product-data').find('.quantity').val());
        var productID = $(this).closest('.product-data').find('.product-id').val();
        var stocks = parseInt($(this).closest('.product-data').find('.with-stocks').attr('id'));

        //if the quantity that the user inputs is greater than the stocks available, it will show alert
        if(quantity > stocks){

            Swal.fire({
                title: 'Oops...',
                text: 'There are only ' +  stocks + ' item(s) and you have reach the maximum quantity.',
                icon: 'info',
                button: true
            }).then(() => {
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
            });
        }
        //if the quantity that the user inputs is 0, it will show alert
        else if(quantity <= 0){
            Swal.fire({
                title: 'Oops...',
                text: 'Minimum quantity is 1',
                icon: 'info',
                button: true
            }).then(() => {
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
                error: function() {
                    console.log('not success');
                }
            });
        }       
	});

//for checkboxes

    //to select all products in shop
    $('.selectall-in-shop').change(function () { 
        var shopID = $(this).closest('.prodak').find('.shopID').val();
        // var check = this.checked == true ? 1 : 0;

        // $(this).closest('.prodak').find('.select-one').each(function() {
        //     this.checked = check;
        // });
        
        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
            data: {
                checked: $(this).is(":checked"), shopID: shopID
            },
            success: function (data) {
                console.log('success');
            }
        });
    });
    
    //to select all products
    $('#select-all').change(function () { 		
        // var check = this.checked == true ? 1 : 0;
        // $('.tsekbox').each(function() {
        //     this.checked = check;
        // });

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
    });
    
    //to select one product
    $('.select-one').change(function () {   
        var productID = $(this).closest('.product-data').find('.product-id').val();
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
    });
});