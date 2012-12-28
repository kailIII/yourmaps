(function($){
	// Variables de la aplicacion
	var id_layer=5;
	var urlServer='http://www.lookingformaps.com/poiserver/index.php/poiserver';
	var peticionGetPOIs='getpoisbyposition';
	// Valor por defecto.La distancia puede modificarse en la opciones de la aplicacion
	var distancia=5000;
	var aliasUsuario='Anónimo';
	
	var self = $.mobile.GapNote = {
			transition : 'none',
			/*checkTransition :  function(){
				$.mobile.defaultPageTransition = self.transition;
			},*/
			init : function(){
				
				// Eliminamos efectos de transicion
				$.mobile.defaultPageTransition = 'none';
				
				// Comprobamos si el usuario tiene ya guardado en la BD un alias y un radio de busqueda,
				// para actualizar los valores correspondientes
				self.consultaBD();
				
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
					// Activamos el icono de loading
					self.muestraLoading(true);
					// Obtenemos las coordenadas del dispositivo
					navigator.geolocation.getCurrentPosition(
						// Si se obtienen sin problemas, solicitamos informacion
						function success(position) {
							//alert('Latitud: ' + position.coords.latitude     + '\n' +
					        //        'Longitud: '           + position.coords.longitude             + '\n' +
					        //        'Altitud: '            + position.coords.altitude              + '\n' +
					        //        'Precisión: '            + position.coords.accuracy              + '\n' +
					        //        'Precisión altura: '   + position.coords.altitudeAccuracy      + '\n' +
					        //        'Heading: '             + position.coords.heading               + '\n' +
					        //        'Velocidad: '               + position.coords.speed                 + '\n' +
					        //        'Timestamp: '           + new Date(position.timestamp)          + '\n');
							//
							self.consultarPuntosCercanos(position.coords.latitude,position.coords.longitude);
						},
						// Si hay algun problema al obtener las coordenadas
						function error(error) {
							//alert(error.message);
							self.muestraLoading(false);
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
						//alert('Refrescando POIs...');
						self.muestraLoading(true);
						$('#listado-notas').empty();
						navigator.geolocation.getCurrentPosition(
							// Si se obtienen sin problemas, solicitamos informacion
							function success(position) {
								self.consultarPuntosCercanos(position.coords.latitude,position.coords.longitude);
							},
							// Si hay algun problema al obtener las coordenadas
							function error(error) {
								self.muestraLoading(false);
								self.error(tx,error);
							});
					})
					
				});
				
				// Cada vez que se accede a "ayuda"
				$('#ayuda').live('pageshow', function(){
					$('#crear_alias').off('click').on('click', function(){
						self.crearAlias();
					})
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
				
				// Cada vez que se accede a "config"
				$('#aqui_hay').live('pageshow', function(){
					// Leemos de la BD si el usuario tiene alias y radio definifos
					
				});
			},
			// Funcion que consulta con el Servidor de puntos
			// VERSION POISERVER
			consultarPuntosCercanos: function(lat, lon){
				//alert('Consultando al servidor en un radio de ' + $('#radio_busqueda').attr('value') + ' Km');
				// Lanzamos la peticion con el radio de busqueda correspondiente
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
							// Desactivamos el icono de carga
							self.muestraLoading(false);
							// Generamos la lista de puntos obtenidos
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
			          
			       		});
				
			},
			// Funcion generica de error
			error : function(tx,err){
				// Desactivamos el icono de carga
				self.muestraLoading(false);
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
				// TODO: Esto seguramente haya que hacerlo en la funcion que se encargue del chekin, asi
				// que se le pasa la lat y long a dihca funcion y que ella actualice el href
				$('#ir_a_poi').attr('href', 'geo:'+ poi.lat + ','+ poi.long +'?z=20&q='+ poi.lat + ','+ poi.long + '(' + 'aqui!' + ')');
				
				},
			muestraLoading : function(activar){
					// Si la entrada es true, mostramos el icono de loading
					if(activar)
					{
						$.mobile.loading( 'show', {
						text: 'Buscando...',
						textVisible: true,
						theme: 'b',
						html: ''
						});
					}
					// Si es false, ocultamos el icono de carga
					else
					{
						$.mobile.loading( 'hide' );
					}
			
			},
			// Objeto que contendra el acceso a nuestra BD
			connection : null,
			// Funcion para abrir la BD
			openDatabase : function(){
				alert('Abriendo conexion...');
				self.connection = window.openDatabase("PoiClientDb", "1.0", "Datos_Poi_Client", 200000);
			},
			// Funcion para ejecutar sentencias en nuestra BD
			transaction: function(fn,err,suc){
				// Si nuestra BD no existe, la creamos
				if(self.connection == null){
					//alert('La conexión valia null...');
					self.openDatabase();
				}
				self.connection.transaction(fn, err, suc);
			},
			// Consulta inicial para ver si el usuario tiene alias y radio definido de un uso anterior
			consultaBD: function(){
				//alert('Consulta inicial de la BD...');
				self.transaction(function(tx){
				    tx.executeSql('CREATE TABLE IF NOT EXISTS Config (id INTEGER PRIMARY KEY ASC, alias VARCHAR(50), fecha VARCHAR(30), radio REAL)');
				    //alert('Consultando datos...');
				    tx.executeSql('SELECT * FROM Config', [],function(tx,rs){
						if(rs.rows.length > 0){
							var aliasdb = rs.rows.item(0).alias;
							var radiodb = rs.rows.item(0).radio;
							// Modifcamos el texto de la pagina de bienvenida
							self.daBienvenida(aliasdb);
							// Modificamos las variables globales internas
							aliasUsuario=aliasdb;
							distancia=radiodb;
							// Modificamos el valor del alias en la pagina de configuracion 
							$('#alias').val(aliasdb);
							$('#alias').attr("placeholder",aliasdb);
							// Modificamos el valor del radio de busqueda en la pagina de configuracion
							$('#radio_busqueda').val(radiodb);
						}
						else
						{
							alert('No hay datos aún en la BD');
						}
						
					});
				})
				
			},
			// Funcion que guarda el alias en la BD
			crearAlias: function(){
				
				var f = (new Date()).toUTCString();
				var datos = [
						$('#alias_inicial').val(),
						$('#radio_busqueda').val(),
						f
				];
				
				// Guardamos en la BD el alias y la fecha
				self.transaction(function(tx){
				   tx.executeSql('INSERT OR REPLACE INTO Config (id, alias, radio, fecha) VALUES (1,?,?,?)', datos,function(tx){
				    	// Actualizamos la pagina de bienvenida con el mensaje personalizado
				    	self.daBienvenida($('#alias_inicial').val());
				    },
				    function (tx,err){
				    	self.error(tx,err);
				    });
					/*tx.executeSql('DELETE FROM Config', [],function(tx){
				    	// Actualizamos la pagina de bienvenida con el mensaje personalizado
				    	alert('Datos borrados de la BD');
				    },
				    function (tx,err){
				    	self.error(tx,err);
				    })*/
				})
			},
			// Funcion que modifica el mensaje de bienvenida
			daBienvenida: function(nombre){
				$('#div_bienvenida_alias').html('<h2>Hola, ' + nombre + '!</h2>' + 
						'<h3>Te recordamos cómo funciona la aplicación:</h3>');
			}
	};
	
	self.init();
	
	
})(jQuery)

// TIPS
// Cambiar de pagina:
// $.mobile.changePage('#listado');