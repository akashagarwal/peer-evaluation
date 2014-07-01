$ ( document ).ready ( function() {

	$.get("/api.php?action=apiViewEvaluations&format=json",function(data,status){
		$text=data['apiViewEvaluations']['success'];
		$('#t1content').html($text);
		sortables_init();
		$.getScript( "http://code.jquery.com/ui/1.11.0/jquery-ui.js", function() {
			$('.title').click ( function()  {
				$id = $(this).attr('name');
				$('#t1content').after('<div id="dialog" title="Evaluation Details">'+$('#'+$id).html()+'</div>');
				$( "#dialog" ).dialog({width:500});
				return false;
			});
		});
	});

} )
