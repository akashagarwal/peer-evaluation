$ ( document ).ready ( function() {

	$.get("/core/api.php?action=apiViewEvaluations&format=json",function(data,status){
		$text=data['apiViewEvaluations']['success'];
		$('#t1content').html($text);

		$('.title').click ( function()  {
			$id = $(this).attr('name');
			$('#bcontent').html($('#'+$id).html());
		});
	});
} )
