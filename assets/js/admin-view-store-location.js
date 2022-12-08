var map;
var infoWindow = new google.maps.InfoWindow({ map: map });
const image = "assets/img/marker-station.png";

function initMap() {
    var lat_value = $('#mapLat').val();
    var long_value = $('#mapLng').val();
    var name = $('#name').val();
    var address = $('#address').val();

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
        title: "My Station",
        icon: image
    })

    const content = '<h6 style="text-align: center;">' + name + '</h6>'
    + '<p style="font-weight: 500; margin: 0;">Address: ' + address + '</p>' 

    //to show the station details
    google.maps.event.addListener(usermarker, 'click', function () {
        infoWindow.setContent(content);
        infoWindow.open(map, this);
    });
    
}

$(function(){
    initMap();
});