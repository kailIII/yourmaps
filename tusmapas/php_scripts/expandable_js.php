<?php
?>
   <script src='../resources/js/jquery-1.6.1.js' type='text/javascript'></script>
   <script src='../resources/js/jquery.expander.js' type='text/javascript'></script>
   <script src='../resources/js/scripts.js' type='text/javascript'></script>
	<script type="text/javascript">
		$(document).ready(function() {
				 $('div.expandable p').expander({
					    slicePoint:    220,  
					    expandText:  'Leer más...', 
					    collapseTimer:   0, 
					    userCollapseText: '[^]'  // default is '[collapse expanded text]'
				  });
		  });
	</script>