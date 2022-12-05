var map;
// Balanga longlat
var lng = 120.54321231608183;
var lat = 14.647694482753147;
var infoWindow = new google.maps.InfoWindow({ map: map });
const image = "assets/img/marker-station.png";

function initMap(mapLong, mapLat) {
	console.log(mapLong + " " + mapLat);
	map = new google.maps.Map(document.getElementById('maps'), {
		center: {
		lat: mapLat,
		lng: mapLong
		},
		zoom: 12
	});

	// Ajax here
  	$.ajax({
    	type: "POST",
    	url: "assets/includes/getStoreLocations-inc.php",
    	dataType: 'json',
    	success: function (data) {
			$.each(data, function (i, val) {
				createMarkerAjax(val);
			});
    	},
  	});
}

function createMarkerAjax(location) {
	const latLng = new google.maps.LatLng(location.lat, location.lng);
  	var marker = new google.maps.Marker({
    	map: map,
    	position: latLng,
    	title: location.name,
		icon: image
  	})

	const content = '<h6 style="text-align: center;">' + location.name 
	+ '</h6>'
	+ '<p style="font-weight: 500; margin: 0;">Address: ' + location.address + '</p>' 
	+ '<p style="font-weight: 500; margin: 0;">Schedule: ' + location.sched + '</p>'
	+ '<br>'
	+ '<div style="text-align: center;">'
	+ '<a class="btn" style="background-color:#fea600; border: none; color: #ffffff; padding:3px 10px;" href="customer-viewstore-timeline.php?stationID=' + location.id + '">View Station</a>';
	+ '</div>'

  	google.maps.event.addListener(marker, 'click', function () {
    	infoWindow.setContent(content);
    	infoWindow.open(map, this);
  	});
}

initMap(lng, lat);


//TO GET THE NEAREST STATION

const user = "assets/img/marker-user.png";

function showNearest(){

	function initialize_map() {
		//user lat and lang
		var lat_value = $('#mapLat').val();
		var long_value = $('#mapLng').val();

		var coords = new google.maps.LatLng(lat_value, long_value);
	
		var myOptions = {
			zoom: 14,
			center: coords,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	
		map = new google.maps.Map(document.getElementById("maps"), myOptions);
		var usermarker = new google.maps.Marker({
			map: map,
			position: coords,
			title: "My Location",
			icon: user
		})

		const content = '<h6 style="text-align: center;">My Location</h6>'

		google.maps.event.addListener(usermarker, 'click', function () {
			infoWindow.setContent(content);
			infoWindow.open(map, this);
		});
	

		//nearest stores

		// Ajax here
		$.ajax({
			type: "POST",
			url: "assets/includes/getNearestStation-inc.php",
			dataType: 'json',
			success: function (data) {
				$.each(data, function (i, val) {
					createMarkerAjax(val);
				});
			},
		});

		//MARKER OF THE STATION
		function createMarkerAjax(location) {
			const latLng = new google.maps.LatLng(location.lat, location.lng);
			var marker = new google.maps.Marker({
				map: map,
				position: latLng,
				title: location.name,
				icon: image
			})
		
			const content = '<h6 style="text-align: center;">' + location.name 
			+ '</h6>'
			+ '<p style="font-weight: 500; margin: 0;">Address: ' + location.address + '</p>' 
			+ '<p style="font-weight: 500;">Schedule: ' + location.sched + '</p>'
			+ '<p style="font-weight: 500; margin: 0;">Distance to my location: ' + location.distance + 'KM</p>'
			+ '<br>'
			+ '<div style="text-align: center;">'
			+ '<a class="btn" style="background-color:#fea600; border: none; color: #ffffff; padding:3px 10px;" href="customer-viewstore-timeline.php?stationID=' + location.id + '">View Station</a>';
			+ '</div>'
		
			google.maps.event.addListener(marker, 'click', function () {
				infoWindow.setContent(content);
				infoWindow.open(map, this);
			});
		}
	}

	$(function(){
		initialize_map();
	});
}