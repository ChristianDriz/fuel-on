var map;
var infoWindow = new google.maps.InfoWindow({ map: map });
const image = "assets/img/marker-station.png";

function initMap() {
    var lat_value = $('#mapLat').val();
    var long_value = $('#mapLng').val();
    
    var coords = new google.maps.LatLng(lat_value, long_value);
    
    var myOptions = {
        zoom: 14,
        center: coords,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        clickableIcons: false,
        disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
		scrollWheel: false, // If set to false disables the scrolling on the map.
		draggable: false // If set to false , you cannot move the map around.
    }

    map = new google.maps.Map(document.getElementById("maps"), myOptions);
    var usermarker = new google.maps.Marker({
        map: map,
        position: coords,
        icon: image
    })

    const content = '<h6 style="text-align: center;">' + $('#station').val()
    + '</h6>'
    + '<p style="font-weight: 500; margin: 0;">' + $('#address').val() +'</p>' 

    //to show the station details
    google.maps.event.addListener(usermarker, 'click', function () {
        infoWindow.setContent(content);
        infoWindow.open(map, this);
    });
    
}

$(function(){
    initMap();
});




	