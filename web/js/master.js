$(function () {
    $('[data-toggle="tooltip"]').tooltip()
    getLocation();
});

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(centerMapPosition);

    }
}
function centerMapPosition(position) {
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        
        geo = new google.maps.LatLng(lat, lng);
        map.setCenter( geo );
        map.setZoom(6);
}