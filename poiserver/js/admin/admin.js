function sendKmls(urlBase){
		var servers = $("#service_to_index").val();
		var idPoiService = $("#poi_layer").val();
		
		
		
		
		var url = urlBase + "index.php/poiserver/loadManyKmls/"+idPoiService;
		
		alert("url="+url);

		  //first we sent the request with maps
		 $.ajax({
			  type: "POST",
			  url: url,
			  data: "kmls="+encodeURIComponent(servers),
			  success: function( data ) {
						alert("kmls enviados con exito"+data.toString());
						     
					},//function data
					error:function(data, textStatus, errorThrown){
						alert("Error:"+errorThrown.toString());
					}
	     });//ajax
	}
