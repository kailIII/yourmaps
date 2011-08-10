<?php
?>
<script type="text/javascript" language="javascript" src="http://www.idee.es/IDEE_API/IDEE_API.js">
</script>


<script type="text/javascript">

function addFrame(domParent, sourceUrl, serviceTitle, height, width, xmin, ymin, xmax, ymax, layerNames, crs, isqueryable, wmsVersion){

	//layers separator is ;, we replace with , (openlayers separator)
	var separator = ";";
	var re = new RegExp(separator, "g"); 
	var wmsLayers = layerNames.replace(re, ","); 
	 var mapa = new IDEE_API.Map(domParent);	    
     var wms = new IDEE_API.WMS(serviceTitle,
             sourceUrl,
             {version: "1.1.0",
             layers: wmsLayers,
             queryables: [true],
             transparent: true,
             format: "image/png",
             tiled: false
          });
     mapa.addWMS(wms);

     var extent = new IDEE_API.Bounds(xmin, ymin, xmax, ymax, "EPSG:4230");
     mapa.setExtent(extent);
     
     var scaleBar = new IDEE_API.Tool.ScaleBar();
     var navBar = new IDEE_API.Tool.NavBarInfo();
     var mouse = new IDEE_API.Tool.MouseWheelZoom();
     var loading = new IDEE_API.Tool.LoadingMap();
     var toolbar = new IDEE_API.Tool.DefaultToolBar();
     var featureInfo = new IDEE_API.Tool.GetFeatureInfo();
     mapa.addTools([navBar, toolbar, featureInfo]);							

}

			
</script>	