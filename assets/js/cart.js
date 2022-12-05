
$(document).ready(function () {
	$('.quant-input-update').on('change', function () {
		$(this).closest("form").submit();
	});

	$('.cbox-store-selectall').on('change', function () {
		const storeCheckBox = $(this);
		const productContainer = storeCheckBox.closest(".prodak");
		var checked = this.checked
		
		productContainer.find('.cbox-md').each(function () {
			// alert(1);
			if (checked)
				$(this).attr('checked', 'checked');
			else
				$(this).removeAttr('checked');

			// $(this).change();

			// $(this).not(this).prop('checked', storeCheckBox.checked);
			// $(this).not(this).prop('checked', this.checked);
		});
	});
});


