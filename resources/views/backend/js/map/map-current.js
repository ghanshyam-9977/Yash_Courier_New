// ================= GLOBAL VARIABLES =================
var map;
var marker;
var geocoder;

// If editing, these may already exist (avoid reference errors)
var mapLat = window.mapLat || null;
var mapLong = window.mapLong || null;

// ================= INITIALIZE MAP =================
function initMap() {
    const defaultLocation = { lat: 23.2599, lng: 77.4126 }; // Bhopal

    geocoder = new google.maps.Geocoder();

    map = new google.maps.Map(document.getElementById('googleMap'), {
        zoom: 12,
        center: defaultLocation,
        mapTypeId: 'roadmap',
        streetViewControl: false,
        fullscreenControl: true
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
        title: 'Hub Location',
        animation: google.maps.Animation.DROP
    });

    // ================= AUTOCOMPLETE =================
    const input = document.getElementById('autocomplete-input');
    const autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'in' },
        fields: ['formatted_address', 'geometry']
    });

    autocomplete.bindTo('bounds', map);

    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();

        if (!place.geometry || !place.geometry.location) {
            alert('No details available for selected location');
            return;
        }

        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();

        updateLocation(lat, lng);
        marker.setPosition({ lat, lng });
        map.setCenter({ lat, lng });
        map.setZoom(15);

        input.value = place.formatted_address;
    });

    // ================= MARKER DRAG =================
    marker.addListener('dragend', function (e) {
        const lat = e.latLng.lat();
        const lng = e.latLng.lng();

        updateLocation(lat, lng);
        reverseGeocode(lat, lng);
    });

    // ================= MAP CLICK =================
    map.addListener('click', function (e) {
        const lat = e.latLng.lat();
        const lng = e.latLng.lng();

        marker.setPosition(e.latLng);
        updateLocation(lat, lng);
        reverseGeocode(lat, lng);
    });

    // ================= EDIT MODE =================
    if (mapLat && mapLong) {
        const lat = parseFloat(mapLat);
        const lng = parseFloat(mapLong);

        marker.setPosition({ lat, lng });
        map.setCenter({ lat, lng });
        map.setZoom(15);
        updateLocation(lat, lng);
    } else {
        // ================= AUTO FETCH CURRENT LOCATION =================
        getLocation();
    }
}

// ================= GET CURRENT LOCATION =================
function getLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation not supported by this browser');
        return;
    }

    const icon = document.getElementById('locationIcon');
    if (icon) icon.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

    navigator.geolocation.getCurrentPosition(
        function (position) {
            showPosition(position);
            if (icon) icon.innerHTML = '<i class="fa fa-crosshairs"></i>';
        },
        function (error) {
            showError(error);
            if (icon) icon.innerHTML = '<i class="fa fa-crosshairs"></i>';
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

// ================= PROCESS LOCATION =================
function showPosition(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    updateLocation(lat, lng);

    const pos = { lat, lng };
    marker.setPosition(pos);
    map.setCenter(pos);
    map.setZoom(16);

    marker.setAnimation(google.maps.Animation.BOUNCE);
    setTimeout(() => marker.setAnimation(null), 1500);

    reverseGeocode(lat, lng);
}

// ================= GEOLOCATION ERROR =================
function showError(error) {
    let message = 'Location error';

    switch (error.code) {
        case error.PERMISSION_DENIED:
            message = 'Location permission denied';
            break;
        case error.POSITION_UNAVAILABLE:
            message = 'Location unavailable';
            break;
        case error.TIMEOUT:
            message = 'Location request timed out';
            break;
    }

    alert(message);
    console.error(error);
}

// ================= REVERSE GEOCODE =================
function reverseGeocode(lat, lng) {
    geocoder.geocode({ location: { lat, lng } }, function (results, status) {
        const input = document.getElementById('autocomplete-input');

        if (status === 'OK' && results[0]) {
            input.value = results[0].formatted_address;
        } else {
            input.value = lat + ', ' + lng;
        }
    });
}

// ================= UPDATE LAT/LNG =================
function updateLocation(lat, lng) {
    mapLat = lat;
    mapLong = lng;

    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('long');

    if (latInput) latInput.value = lat;
    if (lngInput) lngInput.value = lng;

    console.log('Updated Location:', lat, lng);
}
