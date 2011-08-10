<?php
?>
<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
<link rel="stylesheet" href="http://leaflet.cloudmade.com/dist/leaflet.css" />
<script src="http://leaflet.cloudmade.com/dist/leaflet.js"></script>


<script type="text/javascript">
			function addFrame(domParent, sourceUrl, serviceTitle, height, width, xmin, ymin, xmax, ymax, layerNames, layerTitles, crs, isqueryable, wmsVersion){
				//layers separator is ;, we replace with , (openlayers separator)
				var separator = ";";
				var re = new RegExp(separator, "g"); 
				var wmsLayers = layerNames.replace(re, ","); 	

				var lat = ymin + ymax / 2;
				var lon = xmin + xmax / 2;

				
				var map = new L.Map(domParent);
				
				
				var wmsLayersArray = wmsLayers.split(",");	
				var wmsTitlesArray = layerTitles.split(";");
				for(var i = 0; i < wmsLayersArray.length; i++){
					var layerName = wmsLayersArray[i];
					var layerTitle = wmsTitlesArray[i];

					var wmsLyr = new L.TileLayer.WMS(sourceUrl, 
					{
					    layers: layerName,
					    format: 'image/png',
					    transparent: true,
					    srs: "EPSG:4326",
					    version: wmsVersion
					});

					wmsLyr.wmsParams["srs"] = "EPSG:4326";

					map.addLayer(wmsLyr).setView(new L.LatLng(lat, lon), 9);
						
				}

				

				var marker = new L.Marker(new L.LatLng(lat, lon));
				map.addLayer(marker);
				marker.bindPopup('A pretty CSS3 popup.<br />Easily customizable.').openPopup();
					
			}
</script>	