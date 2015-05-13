var geocoder,
    map,
    marker,
    markers = [],
    markerCount = 0,
    lat,
    lng,
    circle;
    
function initialize( jsmap ) {
    geocoder = new google.maps.Geocoder();
    map = jsmap;
}

function updateRadius(circle, rad){
  circle.setRadius(rad);
}

function placeMarker(location) {
    geocoder = new google.maps.Geocoder();
    if (marker) {
        marker.setPosition(location); //on change sa position
    } else {
        marker = new google.maps.Marker({
            position: location,
            map: map,
        });
    }
    $('#casper_event_latitude').val(location.lat());
    $('#casper_event_longitude').val(location.lng());
    getAddress(location);
}

function getAddress(latLng) {

    geocoder.geocode({'latLng': latLng},
    function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                $('#casper_event_location').val(results[0].formatted_address);
            } else {
                $('#casper_event_location').val('No results');
            }
        }
        else {
            $('#casper_event_location').val(status);
        }
    });
}

function findAddress(location) {
    var geo;
    
    geocoder.geocode({'address': location}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            lat = results[0].geometry.location.lat();
            lng = results[0].geometry.location.lng();
            
            geo = new google.maps.LatLng(lat, lng);
            
            
            map.setCenter( geo );
            map.setZoom(12);
            $('input[name=address]').val( results[0].formatted_address );
            
            var circleOptions = {
              strokeColor: '#FF0000',
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: '#FF0000',
              fillOpacity: 0.15,
              map: map,
              //editable: true,
              center: geo,
              radius: 5 * 1000 /* for 5 kilometers */
            };
            
            circle = new google.maps.Circle(circleOptions);
            
            google.maps.event.addListener(circle, 'center_changed', function()   
            {
                //setEventDistrict( circle.getCenter(), circle.getRadius() );
            });  

            google.maps.event.addListener(circle, 'radius_changed', function()   
            {  
                //setEventDistrict( circle.getCenter(), circle.getRadius() );
            });  
            
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}
// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
  circle.setMap(null);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}

function addMarkerToMap(lat, long, htmlMarkupForInfoWindow) {
    var infowindow = new google.maps.InfoWindow();
    var myLatLng = new google.maps.LatLng(lat, long);
    
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        animation: google.maps.Animation.DROP,
    });
     markers.push(marker);
     
    //Gives each marker an Id for the on click
    markerCount++;
    //Creates the event listener for clicking the marker
    //and places the marker on the map
    google.maps.event.addListener(marker, 'click', (function(marker, markerCount) {
        return function() {
            infowindow.setContent(htmlMarkupForInfoWindow);
            infowindow.open(map, marker);
        }
    })(marker, markerCount)); 
     
}
