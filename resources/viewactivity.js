$ ( document ).ready ( function() {


	$(".getActivities").click ( function()  {
		$id=$(this).attr('id');
		$.get("/core/api.php?action=apiGetActivities&id="+$id+"&format=json",function(data,status){
			$text=data['apiGetActivities']['success']

			$('#t1content').html($text);

			$(".title").click ( function()  {
				$id=$(this).attr('id');
				$.get("/core/api.php?action=apiGetEvaluationForm&id="+$id+"&format=json",function(data,status){
					$text2=data['apiGetEvaluationForm']['success'];

					if ( !$text2 ) {
						$('#t1content').html("Activity Not Currently avalable for Evaluation");
						return;
					};
					$('#mcontent').html($text2);
					$('#formcontent').hide();

					$('.Related').focus(function(){
						if ( $(this).val() == 1 )
							$('#formcontent').show();
						if ( $(this).val() == 0 )
							$('#formcontent').hide();
					} );

					$("#submitform").click ( function()  {
						var inp={};
						inp.related = $('input[name=Related]:checked').val();
						inp.related_comment = $('textarea#Related_comment').val();
						inp.id = $('#actid').val();

						formcontent=$ ('#formcontent').children('#ques');

						formcontent.each(function() {
							name=$(this).attr('name');	
							inp[name]=$('input[name='+name+']:checked').val();
							inp['c'+name]=$('textarea#c'+name).val();
						} );

						$.get("/core/api.php?action=apiSubmitEvaluationForm&format=json",inp,function(data,status){
							$('#mcontent').html('<h6>'+ data['apiSubmitEvaluationForm']['success'] +'</h6>');
						} );
						$('#mcontent').html('Submitting your Evaluation...');

					} );

				} );

			} )
  		} );

		$( '#t1content' ).html( "Getting the latest activities for you..." );
	} )	




} )
