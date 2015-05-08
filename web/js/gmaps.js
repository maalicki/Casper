var map;
var geocoder;
var myOptions;
function initialize() {

    geocoder = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(event.latLng);
    });

    var marker;
    function placeMarker(location) {
        if (marker) { //on vérifie si le marqueur existe
            marker.setPosition(location); //on change sa position
        } else {
            marker = new google.maps.Marker({//on créé le marqueur
                position: location,
                map: map
            });
        }
        $('#casper_event_latitude').val( location.lat() );
        $('#casper_event_longitude').val( location.lng() );
        getAddress(location);
    }

    function getAddress(latLng) {
        geocoder.geocode({'latLng': latLng},
        function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#casper_event_location').val( results[0].formatted_address );
                } else {
                    $('#casper_event_location').val( 'No results' );
                }
            }
            else {
                $('#casper_event_location').val( status );
            }
        });
    }
}
google.maps.event.addDomListener(window, 'load', initialize);