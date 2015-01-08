var map,
    marker_title  = 'Je suis Charlie.',
    marker_img    = '/img/charlie.jpg';

function initializeMap() {
  map = new google.maps.Map(document.getElementById('charlie'), {
      center    : {
        lat: 46.71109,
        lng: 1.7191036
      },
      zoom      : 6,
      mapTypeId : google.maps.MapTypeId.SATELLITE,
      disableDefaultUI: true
  })

  //Add clustered markers
  $.get('/getCharlies',function(charlies) {
      var markers = [];
      for (i in charlies) {
        var marker = new google.maps.Marker({
                position: new google.maps.LatLng(charlies[i].latitude, charlies[i].longitude),
                map     : map,
                title   : marker_title + ' (' + charlies[i].created_at + ')',
                icon    : { url: marker_img }
            });
          markers.push(marker);
      }
      //Temporary disable clustering to have more visual impact.
      //var markerCluster = new MarkerClusterer(map, markers);
  });
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            $('.alert-danger').show();
            break;
        case error.POSITION_UNAVAILABLE:
        case error.TIMEOUT:
            $('.alert-warning').show();
            break;
        case error.UNKNOWN_ERROR:
            error_message = "An unknown error occurred."
            break;
    }
    $('.desc').hide();
}

function addPosition(position) {
    var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        map.setCenter(latLng);
        map.setZoom(11);

    $.post('addPosition', {lat: position.coords.latitude, lon: position.coords.longitude}, function(data) {
        var latLng = new google.maps.LatLng(data.latitude, data.longitude);
        if (data.success == true) {
            var marker = new google.maps.Marker({
                position: latLng,
                map     : map,
                title   : marker_title,
                icon    : { url: marker_img }
            });
            $('.main').slideUp();
        }
    });
}

$('.addACharlie').on('click', function(){
    navigator.geolocation.getCurrentPosition(addPosition, showError);
});

//If geolocation not available, no add button
if (navigator.geolocation) {
    $('.main').show();
}

google.maps.event.addDomListener(window, 'load', initializeMap);