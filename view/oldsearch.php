<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
<script>
$(function() {
	var availableTags = [
		<?php
		$getShows=getShows();
		foreach($getShows as $show){
			echo '{ label: "'.$show['showname'].'", value: "'.$show['showname'].'" },';
		}
		?>
	];
function split( val ) {
		return val.split( /,\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}

	$( "#q" )
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
			if ( event.keyCode === $.ui.keyCode.ENTER &&
					$( this ).data( "autocomplete" ).menu.active ) {
				//event.preventDefault();
				window.open('?q='+document.getElementById('q').value,'_self');
			}
		})
		.autocomplete({
			autoFocus: false,
			minLength: 0,
			select: function( event, ui ){
				$(event.target).val(ui.item.value);
                $('#searchform').submit();
			},
			source: function( request, response ) {	
				response( $.ui.autocomplete.filter(	
					availableTags, extractLast( request.term ) ) );	
			}
		});
});
</script>