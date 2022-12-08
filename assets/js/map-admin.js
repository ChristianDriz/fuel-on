var map;

// Balanga longlat
// var lng = 120.54321231608183;
// var lat = 14.647694482753147;
var infoWindow = new google.maps.InfoWindow({ map: map });
const image = "assets/img/marker-station.png";

function initMap() {
    $('.view-map').click(function () {

        const button = $(this);
        var lat_value = button.data('lat');
        var long_value  = button.data('lng');
        var name = button.data('name');

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
            title: name,
            icon: image
        })

        const content = '<h6 style="text-align: center;">' + name + '</h6>'

        //to show the station details
        google.maps.event.addListener(usermarker, 'click', function () {
            infoWindow.setContent(content);
            infoWindow.open(map, this);
        });
    });
}

$(function(){
    initMap();
});




