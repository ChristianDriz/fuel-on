// $(function () {

    // const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    // const data = {
    //     labels: labels,
    //     datasets: [{
    //         label: 'Monthly Sales',
    //         data: [65, 59, 10, 80, 81, 56, 55, 40],
    //         backgroundColor: [
    //         'rgba(255, 99, 132, 0.2)',
    //         'rgba(255, 159, 64, 0.2)',
    //         'rgba(255, 205, 86, 0.2)',
    //         'rgba(75, 192, 192, 0.2)',
    //         'rgba(54, 162, 235, 0.2)',
    //         'rgba(153, 102, 255, 0.2)',
    //         'rgba(201, 203, 207, 0.2)'
    //         ],
    //         borderColor: [
    //             'rgb(255, 99, 132)',
    //             'rgb(255, 159, 64)',
    //             'rgb(255, 205, 86)',
    //             'rgb(75, 192, 192)',
    //             'rgb(54, 162, 235)',
    //             'rgb(153, 102, 255)',
    //             'rgb(201, 203, 207)'
    //         ],
    //         borderWidth: 1
    //     }]
    // };

    // const config = {
    //     type: 'bar',
    //     data: data,
    //     options: {
    //       scales: {
    //         y: {
    //           beginAtZero: true
    //         }
    //       }
    //     },
    // };

    // var myChart = new Chart(
    //     document.getElementById('chart'), 
    //     config
    // );

		// var earning = document.getElementById('chart');
		// var monthlysaleschart = new Chart(earning, {
		// type: 'bar',
		// data: {
		// 	labels: [],
		// 	datasets: [{
		// 		label: 'Monthly Earning',
		// 		data: [],
		// 		backgroundColor: [
		// 			'rgba(255, 99, 132, 0.5)',
		// 			'rgba(54, 162, 235, 0.5)',
		// 			'rgba(255, 206, 86, 0.5)',
		// 			'rgba(75, 192, 192, 0.5)',
		// 			'rgba(153, 102, 255, 0.5)',
		// 			'rgba(255, 159, 64, 0.5)',
		// 			'rgba(244, 159, 164, 0.5)',
		// 		],
		// 		borderColor: [
		// 			'rgba(255, 99, 132, 1)',
		// 			'rgba(54, 162, 235, 1)',
		// 			'rgba(255, 206, 86, 1)',
		// 			'rgba(75, 192, 192, 1)',
		// 			'rgba(153, 102, 255, 1)',
		// 			'rgba(255, 159, 64, 1)',
		// 			'rgba(244, 159, 164, 1)',
		// 		],
		// 		borderWidth: 1
		// 		}]
		// 	},
		// 	options: {
		// 		responsive: true
		// 	}
		// });

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
						'rgba(255, 99, 132, 0.5)',
						'rgba(54, 162, 235, 0.5)',
						'rgba(255, 206, 86, 0.5)',
						'rgba(75, 192, 192, 0.5)',
						'rgba(153, 102, 255, 0.5)',
						'rgba(255, 159, 64, 0.5)',
						'rgba(244, 159, 164, 0.5)',
					],
					borderColor: [
						'rgba(255, 99, 132, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)',
						'rgba(244, 159, 164, 1)',
					],
					borderWidth: 1
					}]
				},
				options: {
					responsive: true
				}
			});
			
        }
    });
    

// });