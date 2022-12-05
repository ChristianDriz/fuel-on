$(document).ready(function () {
	$('.update-quanty').on('change', function () { 
		var quantity = $(this).val();
		var prodID = $(this).attr('id');

		$.ajax({
			type: "POST",
			url: "assets/includes/updateCart-inc.php",
			data: {prodID: prodID, quantity: quantity},
			// success: function (data) {
			// 	$(".kartt").html(data);
			// }
		});
	});
});