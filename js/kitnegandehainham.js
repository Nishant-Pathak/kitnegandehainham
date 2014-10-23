var map;
var emsg = "Something went wrong";
function initialize() {
  var mapOptions = {
    zoom: 17
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
    google.maps.event.addListener(map, 'dblclick', function(e) {
    placeMarker(e.latLng, map);
  });
  plotExistingMarkers();
}

function plotExistingMarkers() {
    $.post("nitro/nitro.php?action=getMarker").done(function(data) {
    data = jQuery.parseJSON(data);
    if((data.errorcode != 0) || (data.message != "Done")) {
       console.log(typeof data);
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
   } ,"json");
}
//new google.maps.LatLng(21.1289956, 82.7792201)

function placeMarker(position, map) {
  Recaptcha.create("6LeRZvwSAAAAAGjP7P9C-FAsrA9TQz3FnjRcqYE1",
    "recaptcha_div",
    {
      theme: "red",
      callback: Recaptcha.focus_response_field
    }
  );
var formDiv = '<div class="row">  ' +
              '<div class="col-md-12"> ' +
              '<form class="form-horizontal" id="markerForm" action="nitro/nitro.php?action=saveMarker" method="POST"> ' +
              '<div class="form-inline">' +
              '<label class="col-sm-4">Location Cordinates</label>' +
              '<div class="form-group">' +
              '<input type="text" class="form-control" id="Lat" value='+position.k+' readonly>' +
              '</div>' +
              '<div class="form-group">' +
              '<input type="text" class="form-control" id="Lng" value='+position.B+' readonly>' +
              '</div>' +
              '</div>' +
              '<div id="recaptcha_div"></div>' +
              '</form>' +
              '</div>' +
              '</div>' +

              '<script>' +
              '$("#markerForm").submit(function(e) { ' +
                  'var postData = {};/* $(this).serializeArray(); */'+
                  'postData.Lat =  $("#Lat").val();' +
                  'postData.Lng =  $("#Lng").val();' +
                  'postData.recaptcha_challenge_field = $("#recaptcha_challenge_field").val();' +
                  'postData.recaptcha_response_field = $("#recaptcha_response_field").val();' +
                  '$.post('+
                    '"nitro/nitro.php?action=saveMarker",'+
                    'postData).done(function(data) {'+
                       'if(data.errorcode != 0 || data.message != "Done") {'+
                       'bootbox.dialog({message:data.message,'+
                          'title:"Server Error",'+
                          'buttons:{danger: { label: "Re-try",className: "btn-danger", callback: function() {'+
                       '}}}}); '+
                       '} else {'+
                          'var marker = new google.maps.Marker({ '+
                          'position: new google.maps.LatLng(postData.Lat, postData.Lng), '+
                          'map: map }); '+
                    '} });    '+
              'e.preventDefault();'+
              '});</script>' +
              '</div>' +
              '</div>';
  bootbox.dialog({
     title: "Do you want to declare this place dirty ?",
     message: formDiv,
              
     buttons: {
       success : {
         label: "Save",
         className: "btn-success",
         callback: function (data) {
            $("#markerForm").submit();

         }
       }
    }});
  //map.panTo(position);*/
}

function handleNoGeolocation(errorFlag) {

  var options = {
    map: map,
    position: new google.maps.LatLng(21.1289956, 82.7792201)
  };

  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);