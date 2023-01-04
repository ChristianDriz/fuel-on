 $(document).ready(function () {
	$.ajax({
        type: 'POST',
        url: 'assets/includes/getChartData-inc.php',
        data: {
            shopId: $('#input-shop-id').val(), status: $('#input-ord-status').val()
        },
        dataType: 'json',
        success: function (data) {
			console.log(data);
            
			var month = [];
			var amount = [];

			for (var i in data){
				month.push(data[i].month);
				amount.push(data[i].amount);
			}

			var earning = document.getElementById('chart');
			var monthlysaleschart = new Chart(earning, {
			type: 'bar',
			data: {
				labels: month,
				datasets: [{
					label: 'Monthly Earning',
					data: amount,
					backgroundColor: [
						// 'rgba(255, 99, 132, 0.5)',
						// 'rgba(54, 162, 235, 0.5)',
						// 'rgba(255, 206, 86, 0.5)',
						// 'rgba(75, 192, 192, 0.5)',
						// 'rgba(153, 102, 255, 0.5)',
						// 'rgba(255, 159, 64, 0.5)',
						// 'rgba(244, 159, 164, 0.5)',

						'#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'
					],
					}]
				},
				options: {
					responsive: true
				}
			});
        }
    });
});
