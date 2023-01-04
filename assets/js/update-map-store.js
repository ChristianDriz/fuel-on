var map;
var infoWindow = new google.maps.InfoWindow({ map: map });
const image = "assets/img/marker-station.png";

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
        title: "My Station",
        icon: image,
        draggable: true
    });

    $.ajax({
        type: "POST",
        url: "assets/includes/myStoreLocation-inc.php",
        dataType: 'json',
        success: function (data) {
            $.each(data, function (i, val) {
                createMarkerAjax(val);
            });
            },
        });

    function createMarkerAjax(location) {
        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
            if (marker) {
                marker.setPosition(mapsMouseEvent.latLng);
            } else {
                marker = new google.maps.Marker({
                    position: mapsMouseEvent.latLng,
                    title: 'My location',
                    map: map,
                    icon: image,
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

        const content = '<h6 style="text-align: center;">My Station: ' + location.name 
        + '</h6>'
        + '<p style="font-weight: 500; margin: 0;">Address: ' + location.address + '</p>' 
        + '<p style="font-weight: 500; margin: 0;">Schedule: ' + location.sched + '</p>'
        + '<br>'

        //to show the station details
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(content);
            infoWindow.open(map, this);
        });
    }
}






	