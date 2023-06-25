<!DOCTYPE html>
<html>
<head>
    <title>Map App</title>
    @vite('resources/js/app.js')
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
<div id="map"></div>

<form method="POST" action="{{ route('map.addMarker') }}">
    @csrf
    <input type="text" name="latitude" placeholder="Latitude" required>
    <input type="text" name="longitude" placeholder="Longitude" required>
    <button type="submit">Add Marker</button>
</form>
<script async
        src="https://maps.googleapis.com/maps/api/js?key=<SECRET_API_KEY>&callback=initMap"></script>
<script>
    function initMap() {
        const centralPoint = {
            lat: 50.46358,
            lng: 30.43267
        };

        const inputLatitude = document.querySelector('[name="latitude"]');
        const inputLongitude = document.querySelector('[name="longitude"]');


        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: centralPoint,
        });

        google.maps.event.addListener(map, 'click', function (event) {
            inputLatitude.value = event.latLng.lat();
            inputLongitude.value = event.latLng.lng();
        });

        const mapPoints = @json($mapPoints);

        mapPoints.forEach(mapPoint => {
            const marker = new google.maps.Marker({
                position: {lat: parseFloat(mapPoint.latitude), lng: parseFloat(mapPoint.longitude)},
                map: map,
            });

            setTimeout(() => {
                marker.setMap(null);
            }, 60000);
        });

        Echo.channel('marker.added')
            .listen('MarkerAdded', (e) => {
                const marker = new google.maps.Marker({
                    position: {lat: parseFloat(e.mapPoint.latitude), lng: parseFloat(e.mapPoint.longitude)},
                    map: map,
                });

                setTimeout(() => {
                    marker.setMap(null);
                }, 60000);

            });
    }
</script>
</body>
</html>

