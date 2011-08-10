<?php
?>
<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
<script type="text/javascript">
			function addFrame(domParent, sourceUrl, height, width, xmin, ymin, xmax, ymax){
				 var frame = document.createElement("iframe");
    			 frame.id = "mapea";
    			 frame.class = "mapea";
    			//FIXME problema. Muchos no soportan este SRS. Almacenar en la bbdd los SRS soportados
    			 var projection = "EPSG:4230";
    			 var units = "d";
    			 var zoom = "14";
    			 var height = 550;
    			 var width = 600;		 
    			 var tools = "panzoombar,navtoolbar,layerswitcher,mouse,measurebar";
				 var srcFrame = "http://www.juntadeandalucia.es/servicios/mapas/mapea/Componente/templateMapeaOL.jsp?"+
				           "layers=WMS_FULL*" +
				           sourceUrl +
				           "*true&controls="+tools+"&getfeatureinfo&projection="
							 + projection +
							 "*"+ units + "&bbox=" + xmin + "," +
							  ymin + "," + xmax +"," + ymax;  

    			 
    			 frame.src = srcFrame;
    			 frame.height = height;
    			 frame.width = width;
    			 frame.border = 1;

    			 frame.align = "center";

	
document.getElementById(domParent).appendChild(frame);
			}
</script>	