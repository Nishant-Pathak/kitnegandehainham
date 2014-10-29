var map;
var geocoder;
var markers = [];
var markercount = 0;
var circles = [];
var switching = true;
var emsg = "Something went wrong: ";
function initialize() {
    geocoder = new google.maps.Geocoder();
    var mapOptions = {
        zoom: 4
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    handleNoGeolocation(false);
    google.maps.event.addListener(map, 'zoom_changed', function(e) {
        ReplaceMarker();
    });
  plotExistingMarkers();
}

function setAllMap(map) {
  markercount = 0;
  for (var i = 0; i < markers.length; i++) {
    setTimeout(function() {
        if(map != null) markers[markercount].setAnimation(google.maps.Animation.DROP);
        markers[markercount++].setMap(map);
    }, i*100);
  }
}

function setAllCircleMap(map) {
  for (var i = 0; i < circles.length; i++) {
    circles[i].setMap(map);
  }
}

function ReplaceMarker() {
    if(map.getZoom() < 3) map.setZoom(4);
    if(map.getZoom() > 8 && switching == true) {
        setAllCircleMap(null);
        setAllMap(map);
        switching = false;
    } else if(map.getZoom() < 8 && switching == false) {
        setAllCircleMap(map);
        setAllMap(null);
        switching = true;
    }
   // alert(map.getZoom());
}

function plotExistingMarkers() {
    $.post("nitro/nitro.php?action=getMarkers").done(function(data) {
    if(typeof data == "string") data = jQuery.parseJSON(data);
    if(data.errorcode != 0 || data.message != "Done") {
       bootbox.alert(emsg + data.message);
       return;
    }
    if(data.markers == null) {
        console.log("Nothing to show");
        return;
    }
    for(var i = 0; i< data.markers.length; i++) {
        var pos = new google.maps.LatLng(data.markers[i].Lat, data.markers[i].Lng);
        var dirtyOptions = {
            strokeColor: '#FF0000',
            strokeOpacity: 0.2 * data.markers[i].Severe,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.2 * data.markers[i].Severe,
            map: map,
            center: pos,
            radius: 1000
        };
        dirtyCircle = new google.maps.Circle(dirtyOptions);
        circles.push(dirtyCircle);
        /*
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(data.markers[i].Lat, data.markers[i].Lng),
            map: map
        }); 
        */
        var marker = new google.maps.Marker({
            position: pos,
            map: null,
            draggable: false,
            animation: google.maps.Animation.DROP
        });
        markers.push(marker);
        google.maps.event.addListener(marker, 'click', function() {
            var p = this.getPosition();
            $.ajax({
                url:         "nitro/nitro.php?action=getImage&Lat="+p.k+"&Lng="+p.B,
                type:        "POST",
                success:     function(data) {
                    if(typeof data == "string") data = jQuery.parseJSON(data);
                    var str = bootbox.alert(data.imgdiv).toString();
                    infowindow.setContent(str);
                    geocoder.geocode({'latLng': p}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                $('#cord').text(results[1].formatted_address);
                            }
                        }
                    });
                }
            });
        });
    }
});
}

var infowindow = new google.maps.InfoWindow({
      content: ""
});
function handleNoGeolocation(errorFlag) {

  var options = {
    map: map,
    position: new google.maps.LatLng(21.1289956, 82.7792201)
  };

  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);