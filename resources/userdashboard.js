$ ( document ).ready ( function() {
	$(window).focus(function() {
		location.reload(true);
	});

	$.get("/api.php?action=apiUserDashboard&format=json",function(data,status){
		if (data['error'] && data['error']['code']=='notloggedin') {
			$('#errors').html('<b>You need to be logged in to view your Dashboard. Click <a href="/?title=Special:UserLogin" target="_blank">here </a> to login</b>');	
			return;
		};
		$text=data['apiUserDashboard']['success'];
		$('#t1content').html($text);
		sortables_init();
		
		$.getScript( "http://code.jquery.com/ui/1.11.0/jquery-ui.js", function() {

			$('.evaltitle').click ( function()  {
				$id = $(this).attr('name');
				$('#t1content').after('<div id="dialog" title="Evaluation Details">'+$('#'+$id).html()+'</div>');
				$( "#dialog" ).dialog({width:500});
				return false;
			});

		});

			$(".title").click ( function()  {
				$login=1;
				$id=$(this).attr('id');

				$.get("/api.php?action=apiSubmitActivity&logincheck=1&format=json",function(data,status){ 
					if (data['error']) {
						$('#t1content').html('<b> Not logged in : You need to be logged in to submit evaluations </b><br>');
						$login=0;
					};


					$.get("/api.php?action=apiGetEvaluationForm&id="+$id+"&format=json",function(data,status){
						if (!$login)
							return;
						$text2=data['apiGetEvaluationForm']['success'];

						if (data['apiGetEvaluationForm']['type'] == 1) {

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
			 						$.post("/api.php?action=apiSubmitEvaluationForm&format=json",inp,function(data,status){
			 							if (!data['apiSubmitEvaluationForm']) {
											code=data['error']['code'];
											if (code == 'notloggedin') {
												alert('Error : Looks like you have logged out from another tab or your session has expired. Please login before you continue.');
											}
											else {
												alert('Error : Cound not submit the activity. Please report this.');
											}
											return;
										}
										$('#mcontent').html('<h6>'+ data['apiSubmitEvaluationForm']['success'] +'</h6>');
									} );
									$('#mcontent').html('Submitting your Evaluation...');
								};
							} );
						};

						if (data['apiGetEvaluationForm']['type'] == 2) {

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

							$('.answerl1').focus (function () {
								$rowid=$(this).attr('name');
								$content=$('#'+$(this).val()+$rowid).html();
								$('#rcontent'+$rowid).html( $content );
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
									inp[name]=$('input[name=l2'+name+']:checked').val();
									inp['c'+name]=$('textarea#c'+name).val();
									if (inp.related==1 && flag==0 & inp[name] == null) {
										alert('Please select an option for all questions before submitting');
										flag=1;
									}
								} );

								if (flag == 0) {
			 						$.post("/api.php?action=apiSubmitEvaluationForm&format=json",inp,function(data,status){
			 							if (!data['apiSubmitEvaluationForm']) {
											code=data['error']['code'];
											if (code == 'notloggedin') {
												alert('Error : Looks like you have logged out from another tab or your session has expired. Please login before you continue.');
											}
											else {
												alert('Error : Cound not submit the activity. Please report this.');
											}
											return;
										}
										$('#mcontent').html('<h6>'+ data['apiSubmitEvaluationForm']['success'] +'</h6>');
									} );
									$('#mcontent').html('Submitting your Evaluation...');
								};
							} );
						};

					} );
				});

			} );
	});
} )
