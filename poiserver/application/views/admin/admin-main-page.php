<?php
?>
<!DOCTYPE html>
 <html  dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <title>Admin page</title>

 <!-- Bootstrap -->
 <link href="<?=$this->config->base_url();?>css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="<?=$this->config->base_url();?>css/bootstrap-responsive.css" rel="stylesheet">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <!-- <script src="<?= $this->config->base_url();?>js/bootstrap.min.js"></script>-->
 <script src="<?= $this->config->base_url();?>js/jquery-1.6.2.min.js"></script>
 <script src="<?= $this->config->base_url();?>js/admin/admin.js"></script>
 </head>
 <body>
 <div class="container">
 	 <h2>POI Server Admin</h2>
	 <div class="row-fluid">
		 <div class="span1">
			 <button class="btn btn-large btn-primary" type="button">Capas</button>
		 </div>
		 <div class="span11">
		 	<button class="btn btn-large btn-success" type="button">Usuarios</button>
		 </div>
	 </div>
	 <div class="row-fluid"/>
	  <div class="row-fluid">
		 <div class="span1">
			 <button class="btn btn-large btn-primary" type="button">Checkins</button>
		 </div>
		 <div class="span11">
		 	<button class="btn btn-large btn-success" type="button">Tablas maestras</button>
		 </div>
	 </div>
</div>
 </body>