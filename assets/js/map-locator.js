var marker = null;
// Balanga longlat
var lng = 120.45639026706307;
var lat = 14.65473282485934;

function initMap() {

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 10,
        center: { lat, lng },
        clickableIcons: false,
    });


    // Configure the click listener.
    map.addListener("click", (mapsMouseEvent) => {
        if (marker) {
            marker.setPosition(mapsMouseEvent.latLng);
        } else {
            marker = new google.maps.Marker({
                position: mapsMouseEvent.latLng,
                map: map
            });
            map.panTo(mapsMouseEvent.latLng);
        }

        const coordinates = mapsMouseEvent.latLng;
        $('#mapLat').val(coordinates.lat());
        $('#mapLng').val(coordinates.lng());

        map.panTo(mapsMouseEvent.latLng);
    });
}

function placeMarkerAndPanTo(latLng, map) {
    new google.maps.Marker({
        position: latLng,
        map: map,
    });
    map.panTo(latLng);
}

$(function () {
    initMap();
});
