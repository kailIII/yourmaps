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
				
				// Cada vez que se accede a "donde"
				$('#donde_hay').live('pageshow', function(){
				
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
					
					 
					
				});
				
				// Cada vez que se accede a "ayuda"
				$('#ayuda').live('pageshow', function(){
					
				});
				
				// Cada vez que se accede a "aqui"
				$('#aqui_hay').live('pageshow', function(){
					
				});
			},
			// Funcion que consulta con el Servidor de puntos
			consultarPuntosCercanos: function(lat, lon){
				alert('Consultando al servidor puntos cercanos a:\n' + lat + ', ' + lon);
				$.getJSON("http://www.juntadeandalucia.es/servicios/mapas/jara.php?lang=en&countryCode=ES&lon="
						+ lon 
						+"&userId=6f85d06929d160a7c8a3cc1ab4b54b87db99f74b&version=6.2&radius=1582&lat="
						+ lat
						+ "&layerName=sedesjaandalucia&accuracy=100",
			             // Si obtenemos la respuesta del Servidor correctamente
						 function(data){
			        	 	//alert(data.hotspots.length);
			        	 	var lista = $('<ul>');
			        	 	for (var i = 0; i < data.hotspots.length; i++){
			        		 //alert(data.hotspots[i]);
			        	 		var latDestino = data.hotspots[i].lat;
			        	 		var lonDestino = data.hotspots[i].lon;
			        	 		var latNumero = latDestino * 1 / 1000000;
			        	 		var lonNumero = lonDestino * 1 / 1000000;
							 lista.append($('<li/>').append($('<h2/>')
									 //.text('Lat:' + latNumero + ', Lon: ' + lonNumero).append($('<BR/>')).
									 .text(data.hotspots[i].title).append($('<BR/>')).
									 append($('<h3/>').text('distancia: ' + data.hotspots[i].distance + ' metros'))
									 .append('<a href="geo:'+ latNumero + ','+ lonNumero +'?z=17" target="_blank">Ver</a>')));
			        	 	};
						
			        	 	$('#listado-notas').empty().append(lista.children()).listview('refresh');
			        	 	
			        	 	self.abreMapa();
			          
			       		});
				
			},
			// Funcion generica de error
			error : function(tx,err){
				console.error('Error!',err);
				alert('Se ha producido un error: ' + err.message);
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