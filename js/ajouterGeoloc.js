"use strict";

var marker;
var map;

function setMarker(pos) {
	map.setCenter(pos);
	marker.setPosition(pos);
}

function ajouterMap() {

	if (navigator.geolocation) {
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 15
		});

		navigator.geolocation.getCurrentPosition(function (position) {
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

			marker = new google.maps.Marker({draggable: true});
			marker.setMap(map);
			setMarker(pos);
			document.getElementById("lat").value = position.coords.latitude;
			document.getElementById("lng").value = position.coords.longitude;
		}, function Erreur(error) {
			switch (error.code) {
				case (error.POSITION_UNAVAILABLE):
					{
						alert('Impossible de determiner la position !');
						document.getElementById("lat").value = 0;
						document.getElementById("lng").value = 0;
					}
					break;
				case (error.PERMISSION_DENIED):
					{
						alert('Geolocalisation non autorisée ou non activée !');
						document.getElementById("lat").value = 0;
						document.getElementById("lng").value = 0;
					}
					break;
				case (error.TIMEOUT):
					{
						alert('Temps de chargement écoulé !');
						document.getElementById("lat").value = 0;
						document.getElementById("lng").value = 0;
					}
					break;
				case (error.UNKNOWN_ERROR):
					{
						alert('Erreur inconnue !');
						document.getElementById("lat").value = 0;
						document.getElementById("lng").value = 0;
					}
					break;
			}
		}, {

			enableHighAccuracy: true,
			maximumAge: 0
		});


	}

}




