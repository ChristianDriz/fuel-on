var map;
var infoWindow = new google.maps.InfoWindow({ map: map });
const user = "assets/img/marker-user.png";

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

    var coords = new google.maps.LatLng(lat_value, long_value);
    
    var myOptions = {
        zoom: 14,
        center: coords,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        clickableIcons: false
    }

    map = new google.maps.Map(document.getElementById("maps"), myOptions);

    var marker = new google.maps.Marker({
        map: map,
        position: coords,
        title: "My Location",
        icon: user,
        draggable: true
    });

    // Configure the click listener.
    map.addListener("click", (mapsMouseEvent) => {
        if (marker) {
            marker.setPosition(mapsMouseEvent.latLng);
        } else {
            marker = new google.maps.Marker({
                position: mapsMouseEvent.latLng,
                title: 'My location',
                map: map,
                icon: user,
                draggable: true
            });
            map.panTo(mapsMouseEvent.latLng);
        }
        updateMarkerPosition(marker.getPosition());
        map.panTo(mapsMouseEvent.latLng);
    });

    //marker can be dragged
    google.maps.event.addListener(marker, 'dragend', function() {
        updateMarkerPosition(marker.getPosition());
        map.panTo(marker.getPosition());
    });

    const content = '<h6 style="text-align: center;">My location</h6>' 

    //to show the station details
    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.setContent(content);
        infoWindow.open(map, this);
    });
}






	