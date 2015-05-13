var geocoder,
    map,
    marker,
    markerCount = 0,
    lat,
    lng;
    
function initialize( jsmap ) {
    geocoder = new google.maps.Geocoder();
    map = jsmap;
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
    geocoder.geocode({'address': location}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            lat = results[0].geometry.location.k;
            lng = results[0].geometry.location.D;
            map.setCenter(results[0].geometry.location);
            map.setZoom(10);
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function addMarkerToMap(lat, long, htmlMarkupForInfoWindow){
    var infowindow = new google.maps.InfoWindow();
    var myLatLng = new google.maps.LatLng(lat, long);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        animation: google.maps.Animation.DROP,
    });
     
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
     
    //Pans map to the new location of the marker
    map.panTo(myLatLng)       
}
