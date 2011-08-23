<?php
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <title>Full Screen Example</title>   
             
        <link rel="stylesheet" href="../theme/default/style.css" type="text/css">
        <link rel="stylesheet" href="style.css" type="text/css">
        
        <style type="text/css">
            html, body, #map {
                margin: 0;
                width: 100%;
                height: 100%;
            }

            #text {
                position: absolute;
                bottom: 1em;
                left: 1em;
                width: 512px;
                z-index: 20000;
                background-color: white;
                padding: 0 0.5em 0.5em 0.5em;
            }
        </style>
        

        <script src="../OpenLayers.js"></script>
        <script type="text/javascript">
			var map;
			function init(){
			    map = new OpenLayers.Map('map');
			
			    var ol_wms = new OpenLayers.Layer.WMS( "OpenLayers WMS",
			        "http://vmap0.tiles.osgeo.org/wms/vmap0",
			        {layers: 'basic'} );
			        var ol_wms_nobuffer = new OpenLayers.Layer.WMS( "OpenLayers WMS (no tile buffer)",
			        "http://vmap0.tiles.osgeo.org/wms/vmap0",
			        {layers: 'basic'}, {buffer: 0});
			
			    map.addLayers([ol_wms, ol_wms_nobuffer]);
			    map.addControl(new OpenLayers.Control.LayerSwitcher());
			    map.setCenter(new OpenLayers.LonLat(0, 0), 6);
			}
		</script>
    </head>
    
    <body onload="init()">
        <div id="map"></div>

          <div id="text">
              <h1 id="title">Full Screen Example</h1>

              <div id="tags">
                css, style, fullscreen, window, margin, padding, scrollbar
              </div>

              <p id="shortdesc">
                Demonstrate a map that fill the entire browser window.
            </p>

            <div id="docs">
                <p>This example uses CSS to define the dimensions of the map element in order to fill the screen.
                When the user resizes the window, the map size changes correspondingly. No scroll bars!</p>

               </p>
            </div>
        </div>
    </body>
</html>
    


