var map;
// Balanga longlat
var lng = 120.54321231608183;
var lat = 14.647694482753147;
var infoWindow = new google.maps.InfoWindow({ map: map });
const image = "https://www.flaticon.com/download/icon/2933939?icon_id=2933939&author=294&team=294&keyword=Petrol&pack=2933800&style=Lineal+Color&style_id=693&format=png&color=%23000000&colored=2&size=24&selection=1&premium=&type=standard&search=gas+station&search=gas+station";


function initMap(mapLong, mapLat) {
  console.log(mapLong + " " + mapLat);
  map = new google.maps.Map(document.getElementById('maps'), {
    center: {
      lat: mapLat,
      lng: mapLong
    },
    zoom: 13
  });


  var request = {
    location: map.getCenter(),
    radius: 8047,
    types: ['gas_station']
  }

  var service = new google.maps.places.PlacesService(map);

  service.nearbySearch(request, callback);
}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    console.log(results.length);
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i]);
    }
  }
}

function createMarker(place) {
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    title: place.name,
    icon: image
  })

  google.maps.event.addListener(marker, 'click', function () {
    infoWindow.setContent(place.name);
    infoWindow.open(map, this);
  });
}

function getlocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  var myLat = position.coords.latitude;
  var myLong = position.coords.longitude;
  initMap(myLong, myLat);
}

initMap(lng, lat);