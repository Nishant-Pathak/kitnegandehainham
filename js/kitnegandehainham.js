var map;

function initialize() {
  var mapOptions = {
    zoom: 5
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

  // Try HTML5 geolocation
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

      map.setCenter(pos);
    }, function() {
      handleNoGeolocation(true);
    });
  } else {
    // Browser doesn't support Geolocation
    handleNoGeolocation(false);
  }
}

function handleNoGeolocation(errorFlag) {

  var options = {
    map: map,
    position: new google.maps.LatLng(21.1289956, 82.7792201)
  };

  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);