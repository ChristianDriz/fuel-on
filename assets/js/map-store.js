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
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById("maps"), myOptions);
    var usermarker = new google.maps.Marker({
        map: map,
        position: coords,
        title: "My Station",
        icon: image
    })

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
        const content = '<h6 style="text-align: center;">My Station: ' + location.name 
        + '</h6>'
        + '<p style="font-weight: 500; margin: 0;">Address: ' + location.address + '</p>' 
        + '<p style="font-weight: 500; margin: 0;">Schedule: ' + location.sched + '</p>'
        + '<br>'
        + '<div style="text-align: center;">'
        + '<a class="btn" style="background-color:#fea600; border: none; color: #ffffff; padding:3px 10px;" href="store-mytimeline.php">View</a>';
        + '</div>'

        //to show the station details
        google.maps.event.addListener(usermarker, 'click', function () {
            infoWindow.setContent(content);
            infoWindow.open(map, this);
        });
    }
    
}

$(function(){
    initMap();
});




	