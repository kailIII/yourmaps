<?php
?>
<link
	rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />

<link rel="stylesheet"
	href="http://openlayers.org/api/theme/default/style.css"
	type="text/css" />

<script
	src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script
	src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAE-YX8EJi6DbrGsUuYstTURQPvX9dPatAPssV01vsiHNP8i7lhxQQUvf8MKvpfau-8jVsw4hWqL_67g'></script>


<script type="text/javascript">
			function addFrame(domParent, sourceUrl, 
					serviceTitle, height, width, 
					xmin, ymin, xmax, ymax, layerNames, 
					layerTitles, crs, isqueryable, wmsVersion){

			//compute a bounding box
	        var width = xmax - xmin;
	        var height = ymax - ymin;
			

			var newxmin = xmin - (width/100);
			var newymin = ymin - (height/100);
			var newxmax = xmax + (width/100);
			var newymax = ymax + (height/100);
			

			googleCrs1 = "EPSG:900913";
			googleCrs2 = "EPSG:102113";
			googleCrs3 = "EPSG:3857";

			universalCrs = "EPSG:4326";

			var sphericalMercator = false;
			var sphericalProjection;
			
			if(crs.indexOf(googleCrs1) != -1){
				sphericalMercator = true;
				sphericalProjection = new OpenLayers.Projection(googleCrs1);	

				
			}else if(crs.indexOf(googleCrs2) != -1){
				sphericalMercator = true;
				sphericalProjection = new OpenLayers.Projection(googleCrs2);	
			}else if(crs.indexOf(googleCrs3) != -1){
				sphericalMercator = true;
				sphericalProjection = new OpenLayers.Projection(googleCrs3);
			}

			if(sphericalMercator){
					
					OpenLayers.Projection.addTransform("EPSG:4326", "EPSG:102113",
						OpenLayers.Layer.SphericalMercator.projectForward);
					
					OpenLayers.Projection.addTransform("EPSG:102113", "EPSG:4326",
							OpenLayers.Layer.SphericalMercator.projectInverse);

					OpenLayers.Projection.addTransform("EPSG:4326", "EPSG:3857",
							OpenLayers.Layer.SphericalMercator.projectForward);
						
					OpenLayers.Projection.addTransform("EPSG:3857", "EPSG:4326",
								OpenLayers.Layer.SphericalMercator.projectInverse);
											
					OpenLayers.Projection.addTransform("EPSG:4326", "EPSG:900913",
							OpenLayers.Layer.SphericalMercator.projectForward);
					
					OpenLayers.Projection.addTransform("EPSG:900913", "EPSG:4326",
							OpenLayers.Layer.SphericalMercator.projectInverse);

					
					
					//it is a wms service compatible with commercial tile services
					var options = {theme: null, 
					 	   		   maxResolution: "auto",
	                       		   maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34), 
	                       		   projection: sphericalProjection,
                   				   minResolution: 0.00439453125,
                   				   numZoomLevels: 22,
	                       		   displayProjection: new OpenLayers.Projection("EPSG:4326"),
	                       		   units: "degrees" 
					};

					var map = new OpenLayers.Map(domParent, options);

					
					gmap=new OpenLayers.Layer.Google("Google Streets",{sphericalMercator:true});
					gmap.projection = sphericalProjection;
					map.addLayer(gmap);
					map.setBaseLayer(gmap);
					
					gsat=new OpenLayers.Layer.Google("Google Satellite",
							{type:G_SATELLITE_MAP,
						sphericalMercator:true,
						numZoomLevels:22});
					gsat.projection = sphericalProjection;
					map.addLayer(gsat);
					map.setBaseLayer(gsat);

					

					gphy=new OpenLayers.Layer.Google("Google Physical",
							{type:G_PHYSICAL_MAP,sphericalMercator:true});
					gphy.projection = sphericalProjection;
					map.addLayer(gphy);
					map.setBaseLayer(gphy);
					

					omap=new OpenLayers.Layer.OSM("Simple OSM Map",{sphericalMercator:true});
					omap.projection = sphericalProjection;
					map.addLayer(omap);
					map.setBaseLayer(omap);

					oosm=new OpenLayers.Layer.OSM("t@h","http://tah.openstreetmap.org/Tiles/tile/${z}/${x}/${y}.png",
								{transitionEffect:"resize"});
					oosm.projection = sphericalProjection;
					map.addLayer(oosm);
					map.setBaseLayer(oosm);
					
					ocyc=new OpenLayers.Layer.OSM("CycleMap","http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png");
					ocyc.projection = sphericalProjection;
					map.addLayer(ocyc);
					map.setBaseLayer(ocyc);
	
	
					cmap=new OpenLayers.Layer.OSM("CloudMade 1",
								"http://a.tile.cloudmade.com/bdee0890581544d9999e29abf71023cb/1/256/${z}/${x}/${y}.png");
					cmap.projection = sphericalProjection;
					map.addLayer(cmap);
					map.setBaseLayer(cmap);

					
					cnig=new OpenLayers.Layer.OSM("CloudMade 2",
							"http://a.tile.cloudmade.com/bdee0890581544d9999e29abf71023cb/999/256/${z}/${x}/${y}.png");
					cnig.projection = sphericalProjection;
					map.addLayer(cnig);
					map.setBaseLayer(cnig);
					
					cred=new OpenLayers.Layer.OSM("CloudMade 3","http://a.tile.cloudmade.com/bdee0890581544d9999e29abf71023cb/8/256/${z}/${x}/${y}.png");
					cred.projection = sphericalProjection;
					map.addLayer(cred);
					map.setBaseLayer(cred);


					ghyb=new OpenLayers.Layer.Google("Google Hybrid",
							{type:G_HYBRID_MAP,sphericalMercator:true});
					ghyb.projection = sphericalProjection;
					map.addLayer(ghyb);
					map.setBaseLayer(ghyb);

		
				}else if(crs.indexOf(universalCrs) != -1){

					var options = {theme: null, 
						 	   maxResolution: "auto",
		                       maxExtent: new OpenLayers.Bounds(-180.0,-90.0,180.0,90.0),
		                       projection: new OpenLayers.Projection("EPSG:4326"),
		                       resolutions: [0.703125, 0.3515625, 0.17578125, 0.087890625, 
		      		                       0.0439453125, 0.02197265625, 0.010986328125, 
		      		                       0.0054931640625, 0.00274658203125, 0.001373291015625,
		      		                        6.866455078125E-4, 3.4332275390625E-4, 1.71661376953125E-4, 
		      		                        8.58306884765625E-5, 4.291534423828125E-5,
		      		                         2.1457672119140625E-5, 1.0728836059570312E-5, 
		      		                         1.341104507446289E-6, 6.705522537231445E-7,
		      		                           8.381903171539307E-8, 4.190951585769653E-8, 
		      		                            5.238689482212067E-9, 2.6193447411060333E-9],
		                       displayProjection: new OpenLayers.Projection("EPSG:4326"),
		                       units: "degrees" 
						};

						var map = new OpenLayers.Map(domParent, options);

						
						
					osgeo = new OpenLayers.Layer.WMS( "OSGEo WMS",
							  "http://vmap0.tiles.osgeo.org/wms/vmap0",
							  {layers: 'Vmap0', isBaseLayer:false});

					
					
					osm = new OpenLayers.Layer.WMS('OSM_Basic', 
							'http://osm.wheregroup.com/cgi-bin/osm_basic.xml?', 
							{layers: 'OSM_Basic', isBaseLayer:false},{singleTile:true});

					mcar = new OpenLayers.Layer.WMS("Metacarta",
							"http://labs.metacarta.com/wms/vmap0?",
							{layers:"basic",
							projection:new OpenLayers.Projection("4326")
							});

					landsatNasa = new OpenLayers.Layer.WMS("Satélite Landsat",
							"http://irs.gis-lab.info/?",
							{layers:"landsat",
							projection:new OpenLayers.Projection("4326")
							},{singleTile:false, tileSize: new OpenLayers.Size(256,256)});



					mapnik = new OpenLayers.Layer.WMS("Open Street Map Mapnik",
							"http://irs.gis-lab.info/?",
							{layers:"osm",
							projection:new OpenLayers.Projection("4326")
							},{singleTile:false, tileSize: new OpenLayers.Size(256,256)});

					
					//WMS Services from maps.opengeo.org have availability problems.
					//They are almost always fallen.

					gm_layer = new OpenLayers.Layer.WMS("Blue Marble",
							"http://maps.opengeo.org/geowebcache/service/wms?TILED=true&",
							{layers : "bluemarble"}
					);
							
					osmWms = new OpenLayers.Layer.WMS(
							"openstreetmap","http://maps.opengeo.org/geowebcache/service/wms",
							{layers: 'openstreetmap', format: 'image/png', isBaseLayer:false },
							{ tileSize: new OpenLayers.Size(256,256)});

					map.addLayer(osgeo);
					map.setBaseLayer(osgeo);

					map.addLayer(osm);
					map.setBaseLayer(osm);

					map.addLayer(gm_layer);
					map.setBaseLayer(gm_layer);

					map.addLayer(mcar);
					map.setBaseLayer(mcar);

					map.addLayer(osmWms);
					map.setBaseLayer(osmWms);

					map.addLayer(landsatNasa);
					map.setBaseLayer(landsatNasa);

					map.addLayer(mapnik);
					map.setBaseLayer(mapnik);

				}
				

				//layers separator is ;, we replace with , (openlayers separator)
				var separator = ";";
				var re = new RegExp(separator, "g"); 
				var wmsLayers = layerNames.replace(re, ","); 	

			
				var wmsLayersArray = wmsLayers.split(",");	
				var wmsTitlesArray = layerTitles.split(";");

				var projection;
				if(sphericalMercator){
					projection = new OpenLayers.Projection("EPSG:900913");
				}else{
					projection = sphericalProjection;
				}//fixme if crs not contains 4326, projection must be one of the supported crs	

				var layerBounds = new OpenLayers.Bounds(xmin, ymin, xmax, ymax);
				layerBounds.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
				
				for(var i = 0; i < wmsLayersArray.length; i++){

					var layerName = wmsLayersArray[i];
					var layerTitle = wmsTitlesArray[i];

					layer = new OpenLayers.Layer.WMS( layerTitle,
		                    sourceUrl,
		                    {layers: layerName,
	                    	 transparent:true
	                    	 },
	                    	{format:"image/png",
	                    	 version: wmsVersion,
	                    	 transparent:true,
                             maxExtent: layerBounds, 
	                    	 opacity: 0.7,
	                    	 styles:"default",
	                    	 singleTile: false
		                    } 
                    );
                    layer.projection = projection;
                    

		            map.addLayer(layer);


		            //FIXME Create a new vectorial layer with the bounding box, to ensure it is 
		            //crearly visible
				}//for

                map.addControl(new OpenLayers.Control.PanZoomBar());
                map.addControl(new OpenLayers.Control.Navigation());
				map.addControl(new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher'),overflow:scroll}));
		        map.addControl(new OpenLayers.Control.MousePosition()); 

		        map.addControl(new OpenLayers.Control.Permalink("permalink"));
		        

		        

				var bounds = new OpenLayers.Bounds(newxmin, newymin, newxmax, newymax);
				bounds.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());

				//TODO FIXME Crear un algoritmo que diga si dibujar el rectangulo del bbox o no
				//en funcion del nivel de zoom.
				
	drawBoundingBox(map, bounds);

		        map.zoomToExtent(bounds);

			}

			/**
				Its useful draw bounding box over base map to ensure visibility for certain
				levels of zoom
			*/
			function drawBoundingBox(map, boundingBox){

			  var poly = boundingBox.toGeometry();

			  var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
	          renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

		       
              var layer_style = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
              layer_style.fillOpacity = 0.2;
              layer_style.graphicOpacity = 1;


              var vectorLayer = new OpenLayers.Layer.Vector("Simple Geometry", {
                style: layer_style,
                renderers: renderer
               });

		           
	            // create a polygon feature from a linear ring of points
	           
	            var polygonFeature = new OpenLayers.Feature.Vector(poly);
		        map.addLayer(vectorLayer); 
		        vectorLayer.addFeatures([polygonFeature]);
						
			}
</script>
