// function toggle(source) {
//   checkboxes = document.getElementsByName('check');
//   for(var i=0, n=checkboxes.length;i<n;i++) {
//     checkboxes[i].checked = source.checked;
//   }
// }

$(document).ready(function () {
	$('.increment-btn').on('click', function() {
		var stocks = $('.stocks').val();
		// alert (stocks);
		
		var quantity = $('.quant-input').val();
		// alert (value);

		if(quantity < stocks)
		{
			quantity++;
			$('.quant-input').val(quantity);
		}		
	});  

	$('.decrement-btn').on('click', function() {
		var quantity = $('.quant-input').val();
		// alert (value);
		if(quantity > 1)
		{
			quantity--;
			$('.quant-input').val(quantity);
		}		
	});  
	
	// $('.addcart-btn').on('click', function() {

	// 	var qty = $('.quant-input').val();
	// 	var prodID = $(this).val();

	// 	$.ajax({
	// 		method: "POST",
	// 		url: "assets/includes/addToCart-inc.php",
	// 		data: {
	// 			"prodID": prodID,
	// 			"quantity": qty,
	// 			"scope": "add"
	// 		},
	// 		dataType: "dataType",
	// 		success: function (response) {
				
	// 			if(response == 201)
	// 			{
	// 				alert.success("Added to cart successfully");
	// 			}
	// 		}
	// 	});
		
	// });
}); 


