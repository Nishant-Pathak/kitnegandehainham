var map;
var markerToPlot;
var positionToPlot;
var alreadyClicked;
var emsg = "Something went wrong";
var alreadyMarked = {
    fillColor:"#FF0000",
    center: null,
    map:null,
    radius:2,
    fillOpacity:1,
    strokeColor: '#FF0000',
    strokeOpacity: 1,
};

function reinitlize() {
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      positionToPlot = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

      map.setCenter(positionToPlot);
      markerToPlot.setPosition(positionToPlot);
      markerToPlot.setAnimation(google.maps.Animation.BOUNCE);
    }, function() {
    showAlert("Give access to your device location.", true);
    positionToPlot = new google.maps.LatLng(21.1289956, 82.7792201);
    map.setCenter(positionToPlot);
    markerToPlot.setPosition(positionToPlot);
    markerToPlot.setAnimation(google.maps.Animation.BOUNCE);
    });
  } else {
    showAlert("Your browser does not support Geolocation.",true);
  }
}

function initialize() {
  var mapOptions = {
    zoom: 17
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
  alreadyMarked.map = map;
  markerToPlot = new google.maps.Marker({
    position: positionToPlot,
    map: map,
    draggable:true,
    animation: google.maps.Animation.BOUNCE,
    title: 'Drag me to adjust. Click me to mark.'
  });
  reinitlize();
  google.maps.event.addListener(markerToPlot, 'click', function(e) {
    if(alreadyClicked) return;
    markerToPlot.setAnimation(null);
    placeMarker(e.latLng, map);
  });
  plotExistingMarkers();
  $('#closebtn,#closebtn1').click(function() {
    resetForm();
    reinitlize();
  });
  $('#savebtn').click(function() {
    saveMarker();
  });
  Recaptcha.create("6LeRZvwSAAAAAGjP7P9C-FAsrA9TQz3FnjRcqYE1",
    "recaptcha_div",
    {
        theme: "white",
        callback: Recaptcha.focus_response_field
    });
    $('#myModal').on('hidden.bs.modal', function(e) {
        alreadyClicked = false;
    });
}

function plotExistingMarkers() {
    $.post("nitro/nitro.php?action=getMarkers").done(function(data) {
    if(typeof data == "string") data = jQuery.parseJSON(data);
    if((data.errorcode != 0) || (data.message != "Done")) {
       bootbox.alert(emsg);
       return;
    }
    if(data.markers == null) {
        console.log("Nothing to show");
        return;
    }
    for(var i = 0; i< data.markers.length; i++) {
        var c = new google.maps.LatLng(data.markers[i].Lat, data.markers[i].Lng); // circles are already marked
        alreadyMarked.center = c;
        new google.maps.Circle(alreadyMarked);
    }
   } ,"json");
}

function saveMarker() {
    $("#markerForm").submit();
}

function resetForm() {
    $("#markerForm")[0].reset();
}

function placeMarker(position, map) {
    alreadyClicked = true;
    $('.modal-footer').css("display", "block");
    $('#markerForm')[0].reset();
    $('#formdiv').css("display","block");
    $('.progress').css("display","none");
    $('.modal-header').removeClass("alert-warning");
    $('#myModalLabel').text("Do you want to declare this place dirty ?");
    $('#Lat').val(position.k);
    $('#Lng').val(position.B);
    $('#savebtn').addClass("btn-success");
    $('#savebtn').text("Save");
    $('#myModal').modal('show');

    $("#SelectM input[name='SelectMode']").click(function() {
        if($('input:radio[name=SelectMode]:checked').val() == 1) {
            $('#dirtyPic').attr("capture", "camera");
        } else {
            $('#dirtyPic').removeAttr("capture");
        }
    });

    $("#dirtyPic").fileinput({
        showUpload: false,
        previewFileType: "image",
        allowedFileTypes: ["image"],
        browseClass: "btn btn-primary",
        browseLabel: "Select",
        browseIcon:  '<i class="glyphicon glyphicon-picture"></i>',
        removeClass: "btn btn-danger",
        removeLabel: "Delete",
        removeIcon:  '<i class="glyphicon glyphicon-trash"></i>',
    });
    
    $("form#markerForm").submit(function(e) {
        e.preventDefault();
        $('#myModalLabel').text("Please be patient");
        $('.progress').css("display","block");
        $('#formdiv').css("display","none");
        $('.modal-footer').css("display", "none");
        var postData = new FormData($(this)[0]);
        $.ajax({
            url:         "nitro/nitro.php?action=saveMarker",
            type:        "POST",
            data:        postData,
            cache:       false,
            contentType: false,
            processData: false,
            beforeSend: function(xhrr, optn) {
                if(!alreadyClicked) {
                    console.log("aborting as already posted");
                    xhrr.abort();
                } else {
                  alreadyClicked = false;
                }
            },
            xhr: function() {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        var uploaded = Math.round((e.loaded / e.total) * 10000)/100 + '%';
                        updateProgressBar(uploaded);
                    }
                };
                xhr.upload.onload = function() {
                        $('.progress-bar').text("Done!");
                }
                return xhr;
            },
            success:  function(data) {
                if(typeof data == "string") data = jQuery.parseJSON(data);
                    if(data.errorcode != 0 || data.message != "Done") {
                        $('#myModalLabel').text("Error in Upload: " + data.message);
                        $('.modal-footer').css("display", "block");
                        $('#formdiv').css("display","block");
                        $('#savebtn').text("Re-try");
                        $('#savebtn').addClass("btn-danger").removeClass("btn-success");
                        $('.progress').css("display","none");
                        Recaptcha.reload();
                        $('.modal-header').addClass("alert-warning");
                } else {
                    showAlert("You are done!! Have a look in view tab or mark another location.", false);
                    $('#myModal').modal('hide');
                    $('.modal-header').removeClass("alert-warning");
                    reinitlize();
                    var c = new google.maps.LatLng(data.marker.Lat, data.marker.Lng);
                    alreadyMarked.center = c;
                    new google.maps.Circle(alreadyMarked);
                }
                updateProgressBar("0%");
                alreadyClicked = true;
            },
            failure: function(data) {
                showAlert("Something went wrong, Please try again later", true);
                updateProgressBar("0%");
                reinitlize();
            }});
    });

  //map.panTo(position);*/
}

function updateProgressBar(uploaded) {
    $('.progress-bar').text(uploaded);
    $('.progress-bar').css("width", uploaded);
}

function showAlert(msg, asError) {
    if(asError) {
        $('.alert').addClass("alert-danger").removeClass("alert-success");
    } else {
        $('.alert').addClass("alert-success").removeClass("alert-danger");
    }
    $('.alert').css("display","block");
    $('#alerttext').text(msg);
    setTimeout(function() {
                        $('#alertclose').alert('close');
    }, 5000);
}
google.maps.event.addDomListener(window, 'load', initialize);