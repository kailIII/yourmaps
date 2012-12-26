(function($){
	var self = $.mobile.GapNote = {
			transition : 'none',
			/*checkTransition :  function(){
				$.mobile.defaultPageTransition = self.transition;
			},*/
			init : function(){
				
				// Eliminamos efectos de transicion
				$.mobile.defaultPageTransition = 'none';		
				
				// Esta funcion se ejecuta la primera vez que se carga cada pagina
				$(document).bind('pageinit', function(){  
					//console.log('page init');
					
				});
				
				// Estas funciones se ejecutan cada vez que se visualiza la
				// pagina correspondiente, DESPUES del pageinit en caso de que
				// se visualice por primera vez
				
				// Cada vez que se accede a "donde hay  pois"
				$('#donde_hay').live('pageshow', function(){
					// No queremos que se active la busqueda de POIs si estos ya estan calculados. Si el usuario
					// quiere obtenerlos de nuevo, solo tiene que pulsar el boton de "refrescar pois"
					if($('#listado-notas li').length == 0)
					{
					// Obtenemos las coordenadas del dispositivo
					navigator.geolocation.getCurrentPosition(
						// Si se obtienen sin problemas, solicitamos informacion
						function success(position) {
							alert('Latitud: ' + position.coords.latitude     + '\n' +
					                'Longitud: '           + position.coords.longitude             + '\n' +
					                'Altitud: '            + position.coords.altitude              + '\n' +
					                'Precisión: '            + position.coords.accuracy              + '\n' +
					                'Precisión altura: '   + position.coords.altitudeAccuracy      + '\n' +
					                'Heading: '             + position.coords.heading               + '\n' +
					                'Velocidad: '               + position.coords.speed                 + '\n' +
					                'Timestamp: '           + new Date(position.timestamp)          + '\n');
							
							self.consultarPuntosCercanos(position.coords.latitude,position.coords.longitude);
						},
						// Si hay algun problema al obtener las coordenadas
						function error(error) {
							//alert(error.message);
							alert('Error GPS. Se tomarán coordenadas por defecto');
							self.consultarPuntosCercanos('37.38264','-5.9962951');
						});
					
					}
					else // La lista de POIs ya estaba rellena
					{
						// No hacemos nada
					}
					
				});
				
				// Cada vez que se accede a "ayuda"
				$('#ayuda').live('pageshow', function(){
					
				});
				
				// Cada vez que se accede a "aqui"
				$('#aqui_hay').live('pageshow', function(){
					
				});
			},
			// Funcion que consulta con el Servidor de puntos
			// VERSION POISERVER
			consultarPuntosCercanos: function(lat, lon){
				alert('Consultando al servidor puntos cercanos a:\n' + lat + ', ' + lon);
				$.getJSON("http://www.lookingformaps.com/poiserver/index.php/poiserver/getpoisbyposition/5/"
						+ lon 
						+"/"
						+ lat
						+ "/90000",
			             // Si obtenemos la respuesta del Servidor correctamente
						 function(data){
			        	 	//alert(data.hotspots.length);
							/*var obj = eval ("(" + data.pois + ")");
							alert(obj);*/
			        	 	var lista = $('<ul>');
			        	 	for (var i = 0; i < data.pois.length; i++){
			        		 //
			        	 		var latDestino = data.pois[i].lat;
			        	 		var lonDestino = data.pois[i].long;
			        	 		// Construimos la lista de POIs
			        	 		// Para cada elemento, asignamos el POI como dato asociado al list item,
			        	 		// y definimos la funcion que debe ejecutarse al hacer clic sobre el elemento
			        	 		// Igualmente, el enlace "ver" lanza la apertura del visor de mapas.
			        	 		 lista.append($('<li/>').data('poi',data.pois[i]).bind('vclick',function(){
			        	 			self.verPOI($(this).data('poi'));})
			        	 				 .attr('href','javascript:void(0)')
			        	 				 .append($('<h2/>')
										 .text(data.pois[i].name).append($('<BR/>')).
										 append($('<h3/>').text('descripción: ' + data.pois[i].description))
										 .append('<a href="geo:'+ latDestino + ','+ lonDestino +'?z=20" target="_blank">Ver</a>')));
			        	 	};
						
			        	 	$('#listado-notas').empty().append(lista.children()).listview('refresh');
			        	 	
			        	 	//self.abreMapa();
			          
			       		});
				
			},
			// Funcion generica de error
			error : function(tx,err){
				console.error('Error!',err);
				alert('Se ha producido un error: ' + err.message);
			},
			// Esta funcion se activa cuando seleccionamos un POI de la lista de POIs
			// Actualiza los atributos de la pagina "info_poi" y la abre.
			verPOI : function(poi){
				$('#nombre_poi_val').text(poi.name);
				// En la descripcion puede venir codigo html
				$('#descripcion_poi_val').html(poi.description);
				$.mobile.changePage('#info_poi');
				},
			abreMapa: function(){
				//alert('intent');
				
				/*alert('Abriendo mapa...');
				window.plugins.webintent.startActivity({
		            action: WebIntent.ACTION_VIEW,
		            url: 'geo:0,0?q=' + 'new york'}, 
		            function() {}, 
		            function() {alert('Failed to open URL via Android Intent');}
		        );*/
				
				// OPCION 1: CAMBIANDO LA VENTANA A GOOGLE MAPS
				// Se llama a la aplicación nativa de mapas
				/*var url = 'http://maps.google.com/maps?';
			    url += 'q=[Sevilla]';
			    url += '&near=37.38264';
			    url += ',-5.9962951';
			    url += '&z=15';
			    window.location = url;*/
			    
			    
				/*Intent intent = new Intent(android.content.Intent.ACTION_VIEW,
						Uri.parse("http://maps.google.com/maps?saddr=20.344,34.34&daddr=20.5666,45.345"));
						startActivity(intent);*/
			}
	};
	
	self.init();
	
	
})(jQuery)

// TIPS
// Cambiar de pagina:
// $.mobile.changePage('#listado');