<?php
?>
<!DOCTYPE html>
 <html  dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <title>Load pois</title>
 <!-- Bootstrap -->
 <link href="<?=$this->config->base_url();?>css/bootstrap.min.css" rel="stylesheet" media="screen">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<!-- <script src="<?= $this->config->base_url();?>js/bootstrap.min.js"></script>-->
 <script src="<?= $this->config->base_url();?>js/jquery-1.6.2.min.js"></script>
 <script src="<?= $this->config->base_url();?>js/admin/admin.js"></script>
 </head>
 <body>
 <h1>Administración</h1>
 <div class="span12">
	 <form src="<?=$this->config->base_url();?>index.php/poiserver/">
	 
		<label for="service_to_index">URL de archivos KML a importar</label>
		<textarea id="service_to_index" rows="15" cols="60" class="field span12" >
		</textarea>
		
<!--		AÑADIR UN OPTION Y UN INPUT TYPE = FILE-->
<!--		PARA AÑADIR KMLS SUBIENDO EL FICHERO EN VEZ DE DESCARGANDOLO-->
<!--		DE UNA URL-->
		
		
		
		<div class="control-group">  
		 	<label class="control-label" for="poi_layer">Capa de POIs</label>  
			<div class="controls">  
               <select class="span10" id="poi_layer">  
<?
				$poiServices = $poi_layers;
				$numPoiServices = sizeof($poiServices);
				for($i = 0; $i < $numPoiServices; $i++ ){
?>
				<option value="<?=$poiServices[$i]->id?>"><?=$poiServices[$i]->service_name?></option>
<?					
				}
?>  
               </select>  
            </div>  
            <button  type="button" class="btn btn-primary" onClick="javascript:sendKmls('<?=$this->config->base_url();?>')">Cargar POIs</button>
	 </form>
  </div> 
 </body>
 </html>