var map;
var emsg = "Something went wrong";
function initialize() {
  var mapOptions = {
    zoom: 13
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
  google.maps.event.addListener(map, 'rightclick', function(e) {
    placeMarker(e.latLng, map);
  });
  plotExistingMarkers();
}

function plotExistingMarkers() {
    $.post("nitro/nitro.php?action=getMarker").done(function(data) {
    if(data.errorcode != 0 || data.message != "Done") {
       bootbox.alert(emsg);
       return;
    }
 //   console.log(data);
    for(var i = 0; i< data.markers.length; i++) {
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(data.markers[i].Lat, data.markers[i].Lng),
        map: map
      });
    }
    });
}
//new google.maps.LatLng(21.1289956, 82.7792201)

function placeMarker(position, map) {
  bootbox.confirm("Do you want to declare this place dirty?", function(result) {
  if(result === true) {
    var postdata = {};
    postdata.Lat = position.k;
    postdata.Lng = position.B;
    $.post("nitro/nitro.php?action=saveMarker", postdata).done(function(data) {
    if(data.errorcode != 0 || data.message != "Done") {
       bootbox.alert(emsg);
       return;
    }
    var marker = new google.maps.Marker({
      position: position,
      map: map
    });
    });
  }
  });
  //map.panTo(position);
}

function handleNoGeolocation(errorFlag) {

  var options = {
    map: map,
    position: new google.maps.LatLng(21.1289956, 82.7792201)
  };

  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);