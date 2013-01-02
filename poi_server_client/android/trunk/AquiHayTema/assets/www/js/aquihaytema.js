(function($){
	// Variables de la aplicacion
	var id_layer=5;
	var urlServer='http://www.lookingformaps.com/poiserver/index.php/poiserver';
	var peticionGetPOIs='getpoisbyposition';
	var peticionGetSecurityQuestions='listsecurityquestions';
	var peticionUserExist = 'userexist';
	var peticionInsertarUsuario = 'insertuser';
	var peticiongetChekinsByPoi = 'getcheckinsbypoi';
	// Variables de configuracion
	var distancia=5;
	var aliasUsuario='';
	var idioma='';
	var password = '';
	//var question = '';
	var idQuestion=1;
	var respuesta='';
	var existeUsuario=false;
	var nombreBD = 'AquiHayTemaDb';
	
	var self = $.mobile.AquiHayTema = {
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
					
					/*$('#crear_alias').off('click').on('click', function(){
						self.crearAlias();
					});*/
					
					$('#ir_configuracion').off('click').on('click', function(){
						$.mobile.changePage('#configuracion');
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
				$('#configuracion').live('pageshow', function(){
					// Actualizamos los valores de los campos de textos si hemos leido valores de la BD
					if(aliasUsuario != ''){
						//alert('Actualizamos valores del formulario de config...');
						$('#radio_busqueda').val(distancia);
						$('#radio_busqueda').slider('refresh');
						$('#alias').val(aliasUsuario);
						$('#alias').attr("placeholder",aliasUsuario);
						$('#password').val(password);
						$('#password').attr("placeholder",password);
						$('#respuesta').val(respuesta);
						$('#respuesta').attr("placeholder",respuesta);
						// La opcion de pregunta secreta que se ha usado
						$('#preguntas option[value="' +  idQuestion + '"]').attr('selected', 'selected');

					}
					
					//alert('Comportamiento del boton');
					
					// Definimos el comportamiento del boton "atras"
					$('#guardar_config').off('click').on('click', function(){
						// Hay que asegurarse de que todos los campos están rellenos
						var camposOk = self.compruebaCampos();
						
						if(camposOk){
							// Solo si los campos estan rellenos, procedemos al alta de usuario
							// Lo primero que tenemos que hacer es ver si el usuario existe ya en el servidor
							// Como el servidor puede tardar unos segundos en responder, activamos el icono de loading
							// Activcamos mensaje de loading, ya que vamos a comunicarnos con el servidor
							$.mobile.loading( 'show', {
								text: 'Guardando usuario...',
								textVisible: true,
								theme: 'b',
								html: ''
								});
							
							var nombre = encodeURIComponent($('#alias').val());
							// Lanzamos el proceso de alta del usuario
							self.altaUsuario(nombre);
							
						}
					});
				});
				
				// Usamos el evento de salir de la pagina de configuracion para actualizar valores globales de configuracion
				// TODO: SALVAMOS TAMBIEN EN LA BD?
				$('#configuracion').live('pagehide', function(){
					distancia = $('#radio_busqueda').val();
				});
			},
			// Funcion que consulta con el Servidor de puntos
			// VERSION POISERVER
			consultarPuntosCercanos: function(lat, lon){
				//alert('Consultando al servidor en un radio de ' + $('#radio_busqueda').attr('value') + ' Km');
				// Lanzamos la peticion con el radio de busqueda correspondiente
				var distanciaKm = distancia * 1000;
				$.getJSON(urlServer + "/"
						+ peticionGetPOIs + "/"
						+ id_layer + "/"
						+ lon +"/"
						+ lat+ "/" 
						+ distanciaKm,
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
			        	 		 lista.append($('<li/>')
			        	 				 .data('poi',data.pois[i]).bind('vclick',function(){self.verPOI($(this).data('poi'));})
			        	 				 .attr('href','javascript:void(0)')
			        	 				 .append($('<h2/>')
										 .text(data.pois[i].name).append($('<BR/>'))
										 .append($('<h3/>').text('descripción: ' + data.pois[i].description)
										 .append($('<h3/>').text('nº checkins: ' + data.pois[i].num_checkins)))));
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
				
				// TODO: Aqui debemos pedir los checkinbypois, y actualizar el collapsable de la lista
				// Comprobamos si hay chekins disponibles
				if(poi.num_checkins > 0)
				{
					// Aqui hacemos la peticion de los checkins del poi
					// poiserver/getcheckinsbypoi/layer_id/poi_id
					// alert('Pidiendo el getcheckinsbypoi...');
					$.getJSON(urlServer + "/"
							+ peticiongetChekinsByPoi + '/'
							+ id_layer + '/'
							+ poi.id,
				             // Si obtenemos la respuesta del Servidor correctamente
							 function(data){
				        	 	//alert('Obtenida respuesta del getcheckinsbypoi!');

								// Generamos la lista con los checkins
				        	 	// TODO: ORDENAR LOS CHEKINS POR TIEMPO?
				        	 	var lista = $('<ul>');
				        	 	for (var i = 0; i < data.checkins.length; i++){
				        	 		//alert('Procesamos un checkpoi...');
				        	 		lista.append($('<li/>')
				        	 				 .attr('href','javascript:void(0)')
				        	 				 .append($('<h2/>')
											 .text(data.checkins[i].user_alias).append($('<BR/>'))
											 .append($('<font style="white-space:normal; font-size: small" />').text(data.checkins[i].description)
											 .append($('<BR/>'))
											 .append($('<font style="white-space:normal; font-size: small" />').text('(' + data.checkins[i].check_time + ')')))));
				        	 		
				        	 	};
							
				        	 	$('#checkins_poi').show();
				        	 	$('#lista_checinks_poi').empty().append(lista.children()).listview('refresh');
				        	 	
				        	 	
				          
				       		});
					
					
				}
				else
				{
					$('#checkins_poi').hide();
				}
				
				
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
						text: 'Buscando en ' + distancia + 'km a la redonda...',
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
				//alert('Abriendo conexion...');
				self.connection = window.openDatabase(nombreBD, "1.0", "Datos", 200000);
				//alert('conexion establecida');
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
					//tx.executeSql('DROP TABLE IF EXISTS Config');
					//alert('Borrada');
				    tx.executeSql('CREATE TABLE IF NOT EXISTS Config (id INTEGER PRIMARY KEY ASC, alias VARCHAR(50), password VARCHAR(30), id_question NUMBER, fecha VARCHAR(30), radio REAL, idioma VARCHAR(30), respuesta VARCHAR(50))');
				    //alert('Tabla creada. Consultando datos...');
				    tx.executeSql('SELECT * FROM Config', [],function(tx,rs){
						if(rs.rows.length > 0){
							//alert('Hay datos...' + rs.rows.item(0).alias + ',' + rs.rows.item(0).radio + ',' + rs.rows.item(0).idioma + ',' +  rs.rows.item(0).id_question );
							// Modificamos las variables globales internas
							aliasUsuario = rs.rows.item(0).alias;
							distancia = rs.rows.item(0).radio;
							idioma = rs.rows.item(0).idioma;
							idQuestion = rs.rows.item(0).id_question;
							password = rs.rows.item(0).password;
							respuesta = rs.rows.item(0).respuesta;
							// Modifcamos el texto de la pagina de bienvenida
							self.daBienvenida(aliasUsuario);
							
							// La propia pagina de configuracion se encargara de rellenar el alias y el
							// radio de busqueda cada vez que se pinte. Eso evita que el usuario haga cambios en los campos,
							// no los salve...
							
						}
						else
						{
							// alert('No hay usuario registrado');
							// Activamos en la pantalla de configuracion el mensaje correspondiente
							$('#div_bienvenida_alta').show();
							
						}
						
					});
				})
				
			},
			// Funcion que guarda el alias en la BD
			crearAlias: function(){
				
				// TODO: Lo primero que hay que hacer es comprobar si ya existe ese usuario, y solo
				// si no existe, darlo de alta en el servidor y actualizar los valores en la aplicacion y BD
				
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
			actualizarAlias: function(){
	
				var f = (new Date()).toUTCString();
				var datos = [
						$('#alias').val(),
						$('#radio_busqueda').val(),
						f
				];
				
				// Guardamos en la BD los datos de configuracion
				self.transaction(function(tx){
				   tx.executeSql('INSERT OR REPLACE INTO Config (id, alias, radio, fecha) VALUES (1,?,?,?)', datos,function(tx){
				    	// Al actualizar no hace falta hacer nada				    	
				    },
				    function (tx,err){
				    	self.error(tx,err);
				    });
				})
			},
			// Funcion que modifica el mensaje de bienvenida
			/*daBienvenida: function(nombre){
				$('#div_bienvenida_alta').hide();
				$('#div_bienvenida_info').html('<h2>Hola, ' + nombre + '!</h2>' + 
						'<h3>Te recordamos cómo funciona la aplicación:</h3>');
				$('#div_bienvenida_info').show();
			},*/
			// Funcion que consulta con el Servidor la lista de preguntas de seguridad disponibles
			consultarPreguntas: function(){
				//alert('Consultando al servidor preguntas...');
				$.getJSON(urlServer + "/"
						+ peticionGetSecurityQuestions,
			             // Si obtenemos la respuesta del Servidor correctamente
						 function(data){
			        	 	//alert(data.questions.length);
							
							// Desactivamos el icono de carga
							//self.muestraLoading(false);
			        	 	
							// Generamos la lista de cuestiones
			        	 	var lista = '';
			        	 	for (var i = 0; i < data.questions.length; i++){
			        	 		// Construimos la lista
			        	 		// TODO: POR AHORA VEMOS EN ESPAÑOL
			        	 		 //lista = lista + '<option id="opcion_pregunta_' +  i+1 + '" value="' + data.questions[i].id + '">' + data.questions[i].es_question + '</option>';
			        	 		lista = lista + '<option value="' + data.questions[i].id + '">' + data.questions[i].es_question + '</option>';
			        	 	};
						
			        	 	$('#preguntas').html(lista).selectmenu('refresh', true);
			          
			       		});
				
			},
			// Funcion que modifica el mensaje de bienvenida
			daBienvenida: function(nombre){
				//alert('Bienvenido, ' + nombre);
				$('#div_bienvenida_alta').hide();
				$('#saludo_personalizado').text('Hola, ' + nombre + '!');
				$('#div_bienvenida_info').show();
			},
			// Funcion que comprueba si los campos del formulario de alta estan rellenos
			// TODO: MAS QUE EL ALERT, O ADEMAS DEL MISMO, DEBERIAN PINTARSE EN ROJO LOS CAMPOS ERRONEOS, CAMBIANDO SU CLASS
			compruebaCampos: function(){
				//alert('Comprobando campos...');
				// Primero comprobamos si los campos usuario y contraseña estan rellenos
				if($.trim($('#alias').val()).length == 0 || $.trim($('#password').val()).length == 0 )
				{
					alert('Los campos usuario y contraseña no pueden estar vacíos');
					return false;
				}
				else // usuario y contraseña estan rellenos
				{
					//alert('La preguntaescogida es id=' + $('#preguntas').val() + ',' + $('#preguntas').find(":selected").text());
					if($.trim($('#respuesta').val()).length == 0){
						alert('La respuesta a la pregunta de seguridad debe tener al menos una letra');
						return false;
					}
					
				}

				// Llegado aqui, todo es correcto
				return true;
			},
			// Funcion que comprueba si el usuario que se quiere dar de alta existe ya o no en el servidor
			altaUsuario: function(nombre){
				
				var existe = false;
				
				//alert('Consultando al servidor si existe el usuario ' + nombre + '...');
						
				$.getJSON(urlServer + '/'
						+ peticionUserExist + '/' + nombre,
			             // Si obtenemos la respuesta del Servidor correctamente
						 function(data){
						
						// Desactivamos el icono de carga
						//self.muestraLoading(false);
			        	// Damos por sentado que siempre hay respuesta del servidor
			        	 existe = data.message;
						 if(existe){
							 	$.mobile.loading( 'hide' );
								alert('Lo sentimos, ese usuario ya existe, por favor elija uno nuevo');
						 }
						 // Si no existe, procedemos a darlo de alta
						 else {
							 	// poiserver/insertuser/securityQuestionCode/alias/password/securityAnswer
							 	/* alert('Haciendo la petición de alta: ' + urlServer + '/'
										+ peticionInsertarUsuario + '/' + $('#preguntas').val() + '/'
										+ encodeURIComponent($('#alias').val()) + '/'
										+ encodeURIComponent($('#password').val()) + '/'
										+ encodeURIComponent($('#respuesta').val()));*/
							 	$.getJSON(urlServer + '/'
								+ peticionInsertarUsuario + '/' + $('#preguntas').val() + '/'
								+ encodeURIComponent($('#alias').val()) + '/'
								+ encodeURIComponent($('#password').val()) + '/'
								+ encodeURIComponent($('#respuesta').val()),
							    // Si obtenemos la respuesta del Servidor correctamente
								// TODO: CONTROLAR SI SE PRODUCE ERROR: ESTARIA DADO DE ALTA EN EL SERVER PERO NO EN LA BD
								function(data){
									// TODO: PONER LOS ESTILOS DE LOS INPUTS EN VERDE?
							 		// Una vez dado de alta en el servidor, lo guardamos en nuestra BD
							 		//alert('Usuario dado de alta en Servidor, procediendo a guardarlo...');
							 		var f = (new Date()).toUTCString();
									var datos = [
											$('#alias').val(),
											$('#password').val(),
											$('#preguntas').val(),
											f,
											$('#radio_busqueda').val(),
											'ES',
											$('#respuesta').val()
									];
									
									// Guardamos en la BD los datos de configuracion
									self.transaction(function(tx){
									   tx.executeSql('INSERT OR REPLACE INTO Config (id, alias, password, id_question, fecha, radio, idioma, respuesta) VALUES (1,?,?,?,?,?,?,?)', datos,function(tx){
									    	// Al actualizar no hace falta hacer nada	
										   //alert('Datos de usuario guardados en la BD local');
									    },
									    function (tx,err){
									    	self.error(tx,err);
									    });
									});
							 		
							 		// Finalizado el proceso, informamos al usuario del exito del alta
								 	$.mobile.loading('hide');
									alert('Enhorabuena, el usuario ha sido creado!');
									// Actualizamos las variables globales
									distancia=$('#radio_busqueda').val();
									aliasUsuario=$('#alias').val();
									idioma='ES';
									password = $('#password').val();
									idQuestion=$('#radio_busqueda').val();
									respuesta = $('#respuesta').val();
									// Cambiamos el mensaje de bienvenida para que refleje el nuevo alias
									self.daBienvenida($('#alias').val());
									$.mobile.changePage('#ayuda');
							   });
							 
							 	
							}
			        	 	
			       		});

				
				
				//return existe;
			}

	};
	
	self.init();
	
	
})(jQuery)

// TIPS
// Cambiar de pagina:
// $.mobile.changePage('#listado');