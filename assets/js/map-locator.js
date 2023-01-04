var marker = null;
var map;
var infoWindow = new google.maps.InfoWindow({ map: map });
var lat = 14.65473282485934;
var lng = 120.45639026706307;
const user = "assets/img/marker-station.png";

$(function(){
    initialize_map();
});

//to update the lat and lng position (hidden input)
function updateMarkerPosition(latlng) {
    $('#mapLat').val(latlng.lat());
    $('#mapLng').val(latlng.lng());
}

function initialize_map() {
    var myOptions = {
        zoom: 10,
        center: {lat, lng},
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        clickableIcons: false
    }
    map = new google.maps.Map(document.getElementById("maps"), myOptions);

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

        //marker can be dragged
        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerPosition(marker.getPosition());
            map.panTo(marker.getPosition());
        });

        const content = '<h6 style="text-align: center;">My location</h6>' 

        //clicking the marker will show content
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(content);
            infoWindow.open(map, this);
        });

    });
}