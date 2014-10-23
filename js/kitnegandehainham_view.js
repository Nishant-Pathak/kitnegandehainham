var map;
var emsg = "Something went wrong";
function initialize() {
  var mapOptions = {
    zoom: 4
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

    handleNoGeolocation(false);
/*  google.maps.event.addListener(map, 'rightclick', function(e) {
    placeMarker(e.latLng, map);
  });
    google.maps.event.addListener(map, 'dblclick', function(e) {
    placeMarker(e.latLng, map);
  }); */
  plotExistingMarkers();
}

function plotExistingMarkers() {
    $.post("nitro/nitro.php?action=getMarker").done(function(data) {
    data = jQuery.parseJSON(data);
    if(data.errorcode != 0 || data.message != "Done") {
       bootbox.alert(emsg);
       return;
    }
    if(data.markers == null) {
        console.log("Nothing to show");
        return;
    }
    for(var i = 0; i< data.markers.length; i++) {
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(data.markers[i].Lat, data.markers[i].Lng),
        map: map
      });
    }
    }, "json");
}

function handleNoGeolocation(errorFlag) {

  var options = {
    map: map,
    position: new google.maps.LatLng(21.1289956, 82.7792201)
  };

  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);