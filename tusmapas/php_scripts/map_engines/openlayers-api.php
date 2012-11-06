<?php
/*
 *  to simplify the crs management, we follow these criteria:
 *  	1. 4326
 *      2. 900913
 *      3. 25830 - 25831
 *      4. 23030 - 23031
 *      
 *      select * from WMS_SERVICES where crs not like '%4326%' 
 *      	and crs not like '%25830%'
 *      	and crs not like '%23030%' 
 *           and crs not like '%23031%'
 *           
 *      Only 3 wms services dont have any of these projections: 4269 and CRS:84
 */
?>
<!--<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />-->

<link rel="stylesheet"
	href="../resources/js/openlayers/theme/looking4maps/style.css"
	type="text/css" />
	
<!--<script-->
<!--	src="http://www.openlayers.org/api/OpenLayers.js">-->
<!--</script>-->

<script src="../resources/js/openlayers/OpenLayers.js">
</script>

<script
	src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAwHyNgGRTfiB-gz3pHckZ2IIdF-UZGx9I'>
</script>

<script src="../resources/js/proj4js/proj4js-compressed.js" 
        type="text/javascript">
</script>

<script src="../resources/js/proj4js/projCode/merc.js" type="text/javascript"></script>  
<script src="../resources/js/proj4js/projCode/tmerc.js" type="text/javascript"></script>  
<script src="../resources/js/proj4js/projCode/utm.js" type="text/javascript"></script>  

<!--<script src="../resources/js/proj4js/proj4js.js" -->
<!--        type="text/javascript">-->
<!--</script>-->


<script src="../resources/js/proj4js/defs/EPSG4326.js" type="text/javascript"></script> 
<script src="../resources/js/proj4js/defs/4326.js" type="text/javascript"></script> 
<?	
	if(strripos($crs, "900913")){
?>
	<script src="../resources/js/proj4js/defs/EPSG900913.js" type="text/javascript"></script>
<?	
	}else if(strripos($crs, "25830")){
?>		
<script src="../resources/js/proj4js/defs/EPSG25830.js" type="text/javascript"></script>
<?	   
	}else if(strripos($crs, "25831")){
?>		
<script src="../resources/js/proj4js/defs/EPSG25831.js" type="text/javascript"></script>
<?	 
		
	}else if(strripos($crs, "23030")){
?>		
<script src="../resources/js/proj4js/defs/EPSG23030.js" type="text/javascript"></script>
<?	 	
	}else if(strripos($crs, "23031")){
?>		
<script src="../resources/js/proj4js/defs/EPSG23031.js" type="text/javascript"></script>
<?	 		
	}else{
		if($crs != null && crs != ""){
			$crsList = split(";", $crs);
			$newCrs = $crsList[0];
			$newCrs = str_replace(":", "", $newCrs);
		
		//FIXME Tendriamos que ver si es CRS:84 pq OpenLayers no se lo está tragando
?>
<script src="../resources/js/proj4js/defs/<?=$newCrs?>.js" type="text/javascript"></script>
<?
		}
	}
?>


<!--<script src="../resources/js/proj4js/defs/EPSG23030.js" type="text/javascript"></script>   -->
<!--<script src="../resources/js/proj4js/defs/EPSG900913.js" type="text/javascript"></script>-->




<script type="text/javascript">
//todo esto se enviara según el tipo de mapa sea kml o wms. ahorramos memoria y red
			var map;
<?
			if($type == "KML"){
?>



			function addFrameKml(domParent, friendlyUrl, serviceTitle, height, width, xmin, ymin, xmax, ymax){

				//compute a bounding box
		        var width = xmax - xmin;
		        var height = ymax - ymin;
				
				if(width == 0)
					width = 0.02;

				if(height == 0)
					height = 0.02;

		        
				var newxmin = xmin - width;
				var newymin = ymin - height;
				var newxmax = xmax + width;
				var newymax = ymax + height;

				googleCrs1 = "EPSG:900913";
				sphericalProjection = new OpenLayers.Projection(googleCrs1);
				
				universalCrs = "EPSG:4326";
				
				OpenLayers.Projection.addTransform("EPSG:4326", "EPSG:900913",
						OpenLayers.Layer.SphericalMercator.projectForward);
				
				OpenLayers.Projection.addTransform("EPSG:900913", "EPSG:4326",
						OpenLayers.Layer.SphericalMercator.projectInverse);

						
						
				
				var options = {
								theme: null, 
								maxResolution: "auto",
		                      	maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34), 
		                       	projection: sphericalProjection,
	                   			minResolution: 0.00439453125,
	                   			numZoomLevels: 22,
		                       	displayProjection: new OpenLayers.Projection("EPSG:4326"),
		                       	units: "degrees" 
				};

				map = new OpenLayers.Map(domParent, options);

						
				
				
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


				ghyb = new OpenLayers.Layer.Google("Google Hybrid",
						{type:G_HYBRID_MAP,sphericalMercator:true});
				ghyb.projection = sphericalProjection;
				map.addLayer(ghyb);
				map.setBaseLayer(ghyb);

				gmap=new OpenLayers.Layer.Google("Google Streets",{sphericalMercator:true});
				gmap.projection = sphericalProjection;
				map.addLayer(gmap);
				map.setBaseLayer(gmap);

			
				projection = new OpenLayers.Projection("EPSG:900913");


//////////////////////////////////////////////////////////////////REVISAR					
				var layerBounds = new OpenLayers.Bounds(newxmin, newymin, newxmax, newymax);
				layerBounds.transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
//////////////////////////////////////////////////////////////////REVISAR

				var style = new OpenLayers.Style(
						{
		                	pointRadius: "${radius}",
		                	fillColor: "red",
		                	fillOpacity: 0.8,
		                	strokeColor: "#ff5555",
		                	strokeWidth: 2,
		                	strokeOpacity: 0.8
	            		}, 
	            		{
	                		context: {
	                    		radius: function(feature) {
											return Math.min(feature.attributes.count, 7) + 3;
	                    				}
	                		}
	            		}	
	            );
					



			    var kml = new OpenLayers.Layer.Vector(serviceTitle, {
						projection: new OpenLayers.Projection("EPSG:4326"),
						strategies: [
										new OpenLayers.Strategy.Fixed()
//										new OpenLayers.Strategy.BBOX(),
//										new OpenLayers.Strategy.Cluster()
									],
						protocol: new OpenLayers.Protocol.HTTP({url: ("get-kml.php?mapa="+friendlyUrl),  
																format: new OpenLayers.Format.KML({
			                                							extractStyles: true, 
			                                							extractAttributes: true,
			                                							maxDepth: 10
			                                					})})
				,   styleMap: new OpenLayers.StyleMap({
			                        //"default": style,
			                        "select": {
			                            fillColor: "#8aeeef",
			                            strokeColor: "#32a8a9"
			                        }
			                })
				    });
			 
		
			 
			 map.addLayer(kml);
			    
		    select = new OpenLayers.Control.SelectFeature(kml);
            
            kml.events.on({
                "featureselected": onFeatureSelect,
                "featureunselected": onFeatureUnselect
            });
	  
            map.addControl(select);
            select.activate();   
					
										
									

            map.addControl(new OpenLayers.Control.PanZoomBar());
            map.addControl(new OpenLayers.Control.Navigation());


			var layerSwitcher = new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher'),overflow:scroll});
            layerSwitcher.ascending = false;
            layerSwitcher.useLegendGraphics = true;


			map.addControl(layerSwitcher);
		    map.addControl(new OpenLayers.Control.MousePosition()); 

		    map.addControl(new OpenLayers.Control.Permalink("permalink"));
			        
//			map.zoomToExtent(layerBounds);
//			var boundsData = kml.getDataExtent();
//			map.zoomToExtent(boundsData);
//			map.zoomToMaxExtent();
		    map.zoomToExtent(layerBounds);
		}


		function onFeatureSelect(event) {
            var feature = event.feature;
            var selectedFeature = feature;
            var layer = selectedFeature.layer;
            var map = layer.map;

			//var str = selectedFeature.attributes.name + " " + selectedFeature.attributes.description;
//			var str = selectedFeature.attributes.name;  
			var str;
			for(var index in selectedFeature.attributes){
				str += (index+":"+selectedFeature.attributes[index])+"<br/>";
			}

			          
            var popup = new OpenLayers.Popup.FramedCloud("Looking for maps", 
			                			feature.geometry.getBounds().getCenterLonLat(),
			                			new OpenLayers.Size(100,100),
			                			"<h5>"+ str + "</h5>",
			                			null, true, onPopupClose);
            feature.popup = popup;
            map.addPopup(popup);
	    }
	      
        function onFeatureUnselect(event) {
            var feature = event.feature;
            var layer = feature.layer;
            var map = layer.map;
            if(feature.popup) {
                map.removePopup(feature.popup);
                feature.popup.destroy();
                delete feature.popup;
            }
        }

        function onPopupClose(evt) {
            select.unselectAll();
        }
        	
<?
	}else{
?>
		

			
	function addFrame(domParent, sourceUrl, serviceTitle, height, width, 
											xmin, ymin, xmax, ymax, layerNames, 
											layerTitles, crs, isqueryable, wmsVersion){

			//compute a bounding box
	        var width = xmax - xmin;
	        var height = ymax - ymin;
			

			var newxmin = xmin - (width/1000);
			var newymin = ymin - (height/1000);
			var newxmax = xmax + (width/1000);
			var newymax = ymax + (height/1000);


			var bounds = new OpenLayers.Bounds(newxmin, newymin, newxmax, newymax);
			
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


			var src = new OpenLayers.Projection("EPSG:4326");
			var dst;

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

					map = new OpenLayers.Map(domParent, options);

					
					
					
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

					gmap=new OpenLayers.Layer.Google("Google Streets",{sphericalMercator:true});
					gmap.projection = sphericalProjection;
					map.addLayer(gmap);
					map.setBaseLayer(gmap);


					dst = map.getProjectionObject();
					

		
				}else if(crs.indexOf(universalCrs) != -1 ){

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

				    map = new OpenLayers.Map(domParent, options);

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


					dst = map.getProjectionObject();
					

				}else{
					//if projection is not web mercator or 4326 we dont use base layers	
					//warning"!!!! map.getProjectionObject returns projection base layer
					
					
					var aProjection;
					if(crs.indexOf("25830") != -1){
						aProjection = new OpenLayers.Projection("EPSG:25830");
					}else if(crs.indexOf("25831") != -1){
						aProjection = new OpenLayers.Projection("EPSG:25831");
					}else if(crs.indexOf("23030") != -1){
						aProjection = new OpenLayers.Projection("EPSG:23030");
					}else if(crs.indexOf("23031") != -1){
						aProjection = new OpenLayers.Projection("EPSG:23031");
					}else{
						if(crs != "")
							aProjection = new OpenLayers.Projection(crs.split(";")[0]);
						else
							aProjection = new OpenLayers.Projection("EPSG:4326");
					}
					
					
//					var aProjection = new OpenLayers.Projection(crs.split(";")[0]);

					dst = aProjection;
					
					var options = {theme: null, 
						 	   maxResolution: "auto",
		                       maxExtent: new OpenLayers.Bounds(-180.0,-90.0,180.0,90.0),
                    		   projection: aProjection,
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

				    map = new OpenLayers.Map(domParent, options);
				}



			
				//convert lat long to the right crs 
				bounds.transform( src, dst );

				//layers separator is ;, we replace with , (openlayers separator)
				var separator = ";";
				var re = new RegExp(separator, "g"); 
				var wmsLayers = layerNames.replace(re, ","); 	

				var wmsLayersArray;
				if(wmsLayers != null)
					wmsLayersArray = wmsLayers.split(",");	
				var wmsTitlesArray;
				if(layerTitles != null)
					wmsTitlesArray = layerTitles.split(";");

				var projection;
				var withoutBaseLayers = false;
				if(sphericalMercator){
					projection = new OpenLayers.Projection("EPSG:900913");
				}else if(crs.indexOf(universalCrs) != -1 ){
					projection = sphericalProjection;
				}else{
//					projection = new OpenLayers.Projection(crs.split(";")[0]);
					projection = dst;
					withoutBaseLayers = true;
				}

				//Curiosly,  wmsLayersArray and wmsTitlesArray dont have the same length always
				//we'll iterate on the largest array
				
				var layerNamesLength = wmsLayersArray.length;
				var layerTitlesLength = wmsTitlesArray.length;

				var iterateArray = null;

				if(layerNamesLength > layerTitlesLength)
					iterateArray = wmsLayersArray;
				else
					iterateArray = wmsTitlesArray;
				
				for(var i = 0; i < iterateArray.length; i++){

					var layerName = wmsLayersArray[i];
					if (layerName === undefined)
						layerName = i + 1;
					var layerTitle = wmsTitlesArray[i];
					if (layerTitle === undefined)
						layerTitle = i + 1;

					

					
					layer = new OpenLayers.Layer.WMS( layerTitle,
		                    sourceUrl,
		                    {
	                    		layers: layerName,
	                    	 	transparent:true
	                    	 },
	                    	{
	 	                    	format:"image/png",
	                    	 	version: wmsVersion,
	                    	 	transparent:true,
                             	//maxExtent: layerBounds, 
	                    	 	maxExtent: bounds,
	                    	 	opacity: 0.7,
	                    	 	styles:"default",
	                    	 	singleTile: false
		                    } 
                    );
                    layer.projection = projection;
                    

		            map.addLayer(layer);
					if(i == 0 && withoutBaseLayers)
		            	map.setBaseLayer(layer);
				}//for
				
				/*
				for(var i = 0; i < wmsLayersArray.length; i++){

					var layerName = wmsLayersArray[i];
					var layerTitle;
					if(wmsTitlesArray != null && (wmsTitlesArray.length == wmsLayersArray.length)) 
						layerTitle = wmsTitlesArray[i];

					layer = new OpenLayers.Layer.WMS( layerTitle,
		                    sourceUrl,
		                    {
	                    		layers: layerName,
	                    	 	transparent:true
	                    	 },
	                    	{
	 	                    	format:"image/png",
	                    	 	version: wmsVersion,
	                    	 	transparent:true,
                             	//maxExtent: layerBounds, 
	                    	 	maxExtent: bounds,
	                    	 	opacity: 0.7,
	                    	 	styles:"default",
	                    	 	singleTile: false
		                    } 
                    );
                    layer.projection = projection;
                    

		            map.addLayer(layer);
					if(i == 0 && withoutBaseLayers)
		            	map.setBaseLayer(layer);
				}//for

				*/

				
                map.addControl(new OpenLayers.Control.PanZoomBar());
                map.addControl(new OpenLayers.Control.Navigation());
                var layerSwitcher = new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher'),overflow:scroll});
                layerSwitcher.ascending = false;
                layerSwitcher.useLegendGraphics = true;


    			map.addControl(layerSwitcher);
		        map.addControl(new OpenLayers.Control.MousePosition()); 

		        map.addControl(new OpenLayers.Control.Permalink("permalink"));
		        

		      
				
				
				//TODO FIXME Crear un algoritmo que diga si dibujar el rectangulo del bbox o no
				//en funcion del nivel de zoom.
				drawBoundingBox(map, bounds);
//		        map.zoomToExtent(bounds);
				map.zoomToMaxExtent();

			}

	<?
	}//else if $type
	?>

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


			
			function goMapsAroundMe(xmin, ymin, xmax, ymax){
				//window.location.href="maps-around.php?xmin="+xmin+"&xmax="+xmax+"&ymin="+ymin+"&ymax="+ymax;
				window.open("maps-around.php?xmin="+xmin+"&xmax="+xmax+"&ymin="+ymin+"&ymax="+ymax,'Maps around '+xmin+","+ymin+","+xmax+","+ymax);
			}

		
</script>
