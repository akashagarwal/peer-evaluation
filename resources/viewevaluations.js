$ ( document ).ready ( function() {

	$.get("/api.php?action=apiViewEvaluations&format=json",function(data,status){
		$text=data['apiViewEvaluations']['success'];
		$('#t1content').html($text);
		sortables_init();
		$('.title').click ( function()  {
			$id = $(this).attr('name');
			$('#bcontent').html($('#'+$id).html());
		});
	});
} )
