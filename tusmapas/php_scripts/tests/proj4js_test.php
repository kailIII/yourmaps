<?php
?>
<html>
<head>

<script type="text/javascript" language="javascript" src="../../resources/js/jquery-1.6.1.js">
</script>
		
<script src="../../resources/js/proj4js/proj4js.js" 
        type="text/javascript">
</script>

<script src="../../resources/js/proj4js/defs/EPSG23030.js" type="text/javascript"></script>   

<script src="../../resources/js/proj4js/projCode/tmerc.js" type="text/javascript"></script>  
<script src="../../resources/js/proj4js/projCode/utm.js" type="text/javascript"></script>   


<script
	src="http://www.openlayers.org/api/OpenLayers.js">
</script>

<script>
function transformBounds(){
	var xmin = -5.0499887;
	var xmax = -5.0412713;
	var ymin =  38.4214946;
	var ymax =  38.4269054; 
    var layerBounds = new OpenLayers.Bounds(xmin, ymin, xmax, ymax);

    var src = new OpenLayers.Projection('EPSG:4326');
	var dest = new OpenLayers.Projection('EPSG:23030');
	
	layerBounds.transform(src, dest);
	
	var bounds = new OpenLayers.Bounds(xmin,ymin, xmax, ymax);
	
	OpenLayers.Projection.transform(bounds, src, dest);

	alert(layerBounds);
	alert(bounds);

}

</script>

<script>
$(document).ready(function() {
	  transformBounds();
})
</script>
</head>


<body>

</body>
</html>