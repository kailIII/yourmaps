(function($){
	// Variables de la aplicacion
	var id_layer=5;
	var urlServer='http://www.lookingformaps.com/poiserver/index.php/poiserver';
	var peticionGetPOIs='getpoisbyposition';
	// Valor por defecto.La distancia puede modificarse en la opciones de la aplicacion
	var distancia=9000;
	
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
						// No hacemos nada?
					}
					
					// Definimos el comportamiento del boton "refrescar"
					$('#refrescar_pois').off('click').on('click', function(){
						// Obtenemos las coordenadas del dispositivo
						alert('Refrescando POIs...');
						$('#listado-notas').empty();
						navigator.geolocation.getCurrentPosition(
							// Si se obtienen sin problemas, solicitamos informacion
							function success(position) {
								self.consultarPuntosCercanos(position.coords.latitude,position.coords.longitude);
							},
							// Si hay algun problema al obtener las coordenadas
							function error(error) {
								self.error(tx,error);
							});
					})
					
				});
				
				// Cada vez que se accede a "ayuda"
				$('#ayuda').live('pageshow', function(){
					
				});
				
				// Cada vez que se accede a "aqui"
				$('#aqui_hay').live('pageshow', function(){
					
				});
				
				// Cada vez que se accede a "info poi"
				$('#info_poi').live('pageshow', function(){
					
					// Definimos el comportamiento del boton "atras"
					$('#atras_info_poi').off('click').on('click', function(){
						// Vuelve a la lista de POIs
						//$.mobile.changePage('#donde_hay');
						navigator.app.backHistory();
					});
					
					// Definimos el comportamiento del boton "ver"
					$('#ir_a_poi').off('click').on('click', function(){
						alert('Hacer check in??');
					});
					
				});
			},
			// Funcion que consulta con el Servidor de puntos
			// VERSION POISERVER
			consultarPuntosCercanos: function(lat, lon){
				alert('Consultando al servidor en un radio de ' + $('#radio_busqueda').attr('value') + ' Km');
				distancia = $('#radio_busqueda').attr('value') * 1000;
				$.getJSON(urlServer + "/"
						+ peticionGetPOIs + "/"
						+ id_layer + "/"
						+ lon +"/"
						+ lat+ "/" 
						+ distancia,
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
			        	 		 lista.append($('<li/>')
			        	 				 .data('poi',data.pois[i]).bind('vclick',function(){self.verPOI($(this).data('poi'));})
			        	 				 .attr('href','javascript:void(0)')
			        	 				 .append($('<h2/>')
										 .text(data.pois[i].name).append($('<BR/>'))
										 .append($('<h3/>').text('descripción: ' + data.pois[i].description))));
										 //.append('<a href="geo:'+ latDestino + ','+ lonDestino +'?z=20" target="_blank">Ver</a>')));
										 //.append('<a href="http://maps.google.com/maps?saddr='+ lat + ','+ lon +'&daddr=' + latDestino + ','+ lonDestino + '" target="_blank">Ver</a>')));
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
				// En la descripcion puede venir codigo html, asi que lo procesamos
				$('#descripcion_poi_val').html(poi.description);
				$.mobile.changePage('#info_poi');
				
				// Actualizamos el destino del boton del "Ir"
				$('#ir_a_poi').attr('href', 'geo:'+ poi.lat + ','+ poi.long +'?z=18&q='+ poi.lat + ','+ poi.long + '(' + 'aqui!' + ')');
				
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