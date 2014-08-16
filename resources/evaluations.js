/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

/*global $:false */
/*jshint -W043 */
/*global wgScriptPath */

$ ( document ).ready ( function() {

	$.get(wgScriptPath+"/api.php?action=query&meta=userinfo&format=json",function(data){ 
		if (data.query.userinfo.id === 0) {
			$("#peEvalform").hide();
			$("#peEvaltable").html("You need to be logged in to view submissions available for evaluation. Click <a href='/?title=Special:UserLogin' target='_blank'>here </a> to login");
		}
		else {
			var activity=$("#peEvaltype").attr("activity");
			$.get(wgScriptPath+"/api.php?action=evaluations&evactivities="+activity+"&evprop=submissions&format=json",function(data) {
				$("#peEvalform").hide();

				var table = "<h2>Click on a title to evaluate submission</h2>";

				table+=" \
					<table border='1' class='prettytable sortable' > \
					<tr> \
					<td>Title</td> \
					<td>Submitted by</td> \
					<td>URL</td>      \
					<td>Comment</td> \
					<td>Opted in for Evaluation</td> \
					<td>Number of Evaluations</td> \
					<td>Submission Time</td> \
					</tr> \
					";

				for (var i in data.evaluations) {

					var $t=" \
						<tr> \
						<td> <a id='"+i+"' class='peEvaltitle' > "+data.evaluations[i].title+" </a> </td> \
						<td> <a href='/User:"+data.evaluations[i].userName+"'>"+ data.evaluations[i].userName +" </a></td> \
						<td> <a href='"+data.evaluations[i].url+"' target='_blank'> "+data.evaluations[i].url+"</a></td>   \
						<td>"+data.evaluations[i].comment+"</td> \
						<td>"+( (data.evaluations[i].optedIn === "Yes" ) ? "Yes" :"No")+"</td> \
						<td>"+data.evaluations[i].numEval+"</td> \
						<td>"+data.evaluations[i].timeStamp+"</td> \
						</tr> \
						";
					table+=$t;
				}
				$("#peEvaltable").html(table);

				$(".peEvaltitle").click ( function()  {	
					$("#peEvaltable").hide();
					var type=$("#peEvaltype").attr("value");
					$("#peEvalform").show();
					$("#peEvalform").css("visibility","visible");


					var id=$(this).attr("id");
					var evaluation={};
					evaluation.activity={ title: data.evaluations[id].title, userName: data.evaluations[id].userName, url:data.evaluations[id].url , comment:data.evaluations[id].comment , optedIn:data.evaluations[id].optedIn , timeStamp:data.evaluations[id].timeStamp };

					var $actdata = "<h3> Activity Details </h3>";
					$actdata += "<b> Title : </b>" + data.evaluations[id].title + "<br>";    
					$actdata += "<b> Comment : </b>" + data.evaluations[id].comment + "<br>";
					$actdata += "<b> URL : </b> <a href='" + data.evaluations[id].url +"' target='_blank'>" + data.evaluations[id].url +"</a><br>";

					$("#peEvalactDetails").html($actdata);

					$("#peEvalformcontent").hide();

					evaluation.related = -1;

					$(".peEvalRelated").focus(function(){
						$("#peEvalrelatedError").html("");

						if ( $(this).val() === "1" ) {
							$("#peEvalformcontent").show();
							evaluation.related=1;
							return;
						}
						else if ( $(this).val() === "0" )
							$("#peEvalformcontent").hide();
							evaluation.related=0;
					});

					evaluation.type = type;
					if ( type === "1" ) {

						$("#peEvalsubmit").click ( function () {

							$("#peEvalsubmitError").html("");
							if ( evaluation.related === -1 ) {
								$("#peEvalrelatedError").html("<b style='color:red'> Please select Yes or No</b></br>");
								return;
							}

							var submitData={};

							var overallComment = $("#peEvaloverallComment").val();
							if ( overallComment )
								evaluation.comment = overallComment;

							if ( evaluation.related === 0 ) {
								submitData.peid=data.evaluations[id].id;
								submitData.peactivity=activity;
								submitData.pevaluation=JSON.stringify(evaluation);

								$("#peEvalform").html("Processing Evaluation");
								$.post(wgScriptPath+"/api.php?action=pevaluate&format=json",submitData,function(data){
									$("#peEvalform").html(data.pevaluate.success);
								});								
							}

							else {
								var errorFlag=0;
								evaluation.submission={ a:[] , b:[] };

								$("span[type=q]").each( function() {
									var entry=$(this);
									var ans=$("input[name="+entry.attr("qid")+"]:checked").val();
									$("#peEvalerror"+entry.attr("qid")).html("");

									if (!ans) {
										$("#peEvalerror"+entry.attr("qid")).html("<b style='color:red'> Please select an option for this question</b></br>");
										errorFlag=1;
									}

									var qa={};

									qa.Question=entry.html();
									qa.Answer=ans;

									evaluation.submission[entry.attr("name")].push(qa);

								});
								if ( errorFlag === 1) {
									$("#peEvalsubmitError").html("Please compelete the form (errors are specifies above in red) <br/>");
									return;
								}
								submitData.peid=data.evaluations[id].id;
								submitData.peactivity=activity;
								submitData.pevaluation=JSON.stringify(evaluation);

								$("#peEvalform").html("Processing Evaluation");
								$.post(wgScriptPath+"/api.php?action=pevaluate&format=json",submitData,function(data){
									$("#peEvalform").html(data.pevaluate.success);
								});
							}
						});
					}

					if ( type === "2" ) {

						$("#peEvalsubmit").click ( function () {

							$("#peEvalsubmitError").html("");
							if ( evaluation.related === -1 ) {
								$("#peEvalrelatedError").html("<b style='color:red'> Please select Yes or No</b></br>");
								return;
							}
							var submitData={};

							var overallComment = $("#peEvaloverallComment").val();
							if ( overallComment )
								evaluation.comment = overallComment;
							if ( evaluation.related === 0 ) {
								submitData.peid=data.evaluations[id].id;
								submitData.peactivity=activity;
								submitData.pevaluation=JSON.stringify(evaluation);
								$("#peEvalform").html("Processing Evaluation");
								$.post(wgScriptPath+"/api.php?action=pevaluate&format=json",submitData,function(data){
									$("#peEvalform").html(data.pevaluate.success);
								});								
							}

							else {
								var errorFlag=0;
								evaluation.submission=[];

								$("span[type=q]").each( function() {
									var entry=$(this);
									var ans=$("input[name="+entry.attr("qid")+"]:checked").val();
									$("#peEvalerror"+entry.attr("qid")).html("");
									if (!ans) {
										$("#peEvalerror"+entry.attr("qid")).html("<b style='color:red'> Please select an option for this question</b></br>");
										errorFlag=1;
									}

									var qa={};

									qa.Question=entry.attr("q");
									qa.Answer=ans;

									evaluation.submission.push(qa);

								});
								if ( errorFlag === 1) {
									$("#peEvalsubmitError").html("Please compelete the form (errors are specifies above in red) <br/>");
									return;
								}

								submitData.peid=data.evaluations[id].id;
								submitData.peactivity=activity;
								submitData.pevaluation=JSON.stringify(evaluation);
								$("#peEvalform").html("Processing Evaluation");
								$.post(wgScriptPath+"/api.php?action=pevaluate&format=json",submitData,function(data){
									$("#peEvalform").html(data.pevaluate.success);
								});
							}
						});
					}       
					if ( type === "3" ) {

						$("#peEvalsubmit").click ( function () {

							$("#peEvalsubmitError").html("");
							if ( evaluation.related === -1 ) {
								$("#peEvalrelatedError").html("<b style='color:red'> Please select Yes or No</b></br>");
								return;
							}
							var submitData={};

							var overallComment = $("#peEvaloverallComment").val();
							if ( overallComment )
								evaluation.comment = overallComment;

							if ( evaluation.related === 0 ) {
								submitData.peid=data.evaluations[id].id;
								submitData.peactivity=activity;
								submitData.pevaluation=JSON.stringify(evaluation);
								$("#peEvalform").html("Processing Evaluation");
								$.post(wgScriptPath+"/api.php?action=pevaluate&format=json",submitData,function(data){
									$("#peEvalform").html(data.pevaluate.success);
								});								
							}

							else {
								var errorFlag=0;
								evaluation.submission=[];

								$("input[type=text]").each( function() {
									var entry=$(this);
									var ans=entry.val();
									$("#peEvalerror"+entry.attr("name")).html("");
									if (ans === "" ) {
										$("#peEvalerror"+entry.attr("name")).html("<b style='color:red'> Please rate this question</b></br></br>");
										errorFlag=1;
									}

									if ( parseInt(ans,10) < 1 || parseInt(ans,10) > 5  ) {
										$("#peEvalerror"+entry.attr("name")).html("<b style='color:red'> Please enter a value between 1 and 5</b></br></br>");
										errorFlag=1;
									}

									var qa={};

									qa.Question=entry.attr("q");
									qa.Answer=ans;

									evaluation.submission.push(qa);

								});
								if ( errorFlag === 1) {
									$("#peEvalsubmitError").html("Please compelete the form (errors are specified above in red) <br/>");
									return;
								}
								submitData.peid=data.evaluations[id].id;
								submitData.peactivity=activity;
								submitData.pevaluation=JSON.stringify(evaluation);
								$("#peEvalform").html("Processing Evaluation");
								$.post(wgScriptPath+"/api.php?action=pevaluate&format=json",submitData,function(data){
									$("#peEvalform").html(data.pevaluate.success);
								});
							}
						});
					}
				} );
			});
		}			
	});
});