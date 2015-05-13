var geocoder;
var map;

function initialize( jsmap ) {
    geocoder = new google.maps.Geocoder();
    
    if( jsmap = typeof jsmap !== 'undefined' ) {
        map = jsmap;
    }
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
            map.setCenter(results[0].geometry.location);
            map.setZoom(10);
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}