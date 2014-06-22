$ ( document ).ready ( function() {


	$(".getActivities").click ( function()  {
		$id=$(this).attr('id');
		$.get("/api.php?action=apiGetActivities&id="+$id+"&format=json",function(data,status){
			$text=data['apiGetActivities']['success']

			$('#t1content').html($text);

			$(".title").click ( function()  {
				if (!wgUserName) {
					$('#t1content').html('You need to be logged in to submit an evaluation');
					return;
				}
				$id=$(this).attr('id');
				$.get("/api.php?action=apiGetEvaluationForm&id="+$id+"&format=json",function(data,status){
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

						flag=0;

						if (inp.related == null){
							alert('You cannot submit an empty form');
							flag=1;
						}
						formcontent=$ ('#formcontent').children('#ques');
						formcontent.each(function() {
							name=$(this).attr('name');	
							inp[name]=$('input[name='+name+']:checked').val();
							inp['c'+name]=$('textarea#c'+name).val();
							if (inp.related==1 && flag==0 & inp[name] == null) {
								alert('Please select an option for all questions before submitting');
								flag=1;
							}
						} );

						if (flag == 0) {
	 						$.get("/api.php?action=apiSubmitEvaluationForm&format=json",inp,function(data,status){
								$('#mcontent').html('<h6>'+ data['apiSubmitEvaluationForm']['success'] +'</h6>');
							} );
							$('#mcontent').html('Submitting your Evaluation...');
						};
					} );

				} );

			} )
  		} );

		$( '#t1content' ).html( "Getting the latest activities for you..." );
	} )	




} )
