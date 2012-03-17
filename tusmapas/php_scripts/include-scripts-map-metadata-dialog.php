<?php
?>
//service metadata jquery dialog
				
var options2 = {
		autoOpen: false,
		width: 600,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}
		},
		zIndex:99999
};
$("#dialog").dialog(options);


// Dialog Link
$('#dialog_link').click(function(){
	$('#dialog').dialog('open');
	return false;
});

//hover states on the static widgets
$('#dialog_link, ul#icons li').hover(
	function() { $(this).addClass('menu-header.a'); }, 
	function() { $(this).removeClass('menu-header.a'); }
);