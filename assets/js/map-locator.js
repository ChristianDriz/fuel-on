
var map;
var infoWindow = new google.maps.InfoWindow({ map: map });
const user = "assets/img/marker-station.png";

$(function(){
    initialize_map();
});

function updateMarkerPosition(latlng) {
    $('#mapLat').val(latlng.lat());
    $('#mapLng').val(latlng.lng());
}

function initialize_map() {

    var lat_value = document.getElementById('mapLat').value; 
    var long_value = document.getElementById('mapLng').value;

    if(lat_value == '0' || lat_value == "" ){
        lat_value = 14.65473282485934;
    }
    if(long_value == '0' || long_value == ""){  
        long_value = 120.45639026706307;
    }
    var latlng = new google.maps.LatLng(lat_value, long_value);


    var myOptions = {
        zoom: 10,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById("maps"), myOptions);

    marker = new google.maps.Marker({
        position: latlng,
        title: 'My location',
        map: map,
        icon: user
    });

    google.maps.event.addListener(map, 'center_changed', function() {
        updateMarkerPosition(marker.getPosition());
        window.setTimeout(function() {
            var center = map.getCenter();
            marker.setPosition(center);
        }, 0);
    });

    const content = '<h6 style="text-align: center;">My location</h6>' 
    //to show the station details
    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.setContent(content);
        infoWindow.open(map, this);
    });

    google.maps.event.addDomListener(window, 'load', initialize);
}