/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

/*global $:false */
/*jshint -W043 */
/*global wgScriptPath */


$ ( document ).ready ( function() {

	var activity=$("#peVEactivityPage").attr("activity");

	$.get(wgScriptPath+"/api.php?action=evaluations&evactivities="+activity+"&evprop=evaluations&format=json",function(data) {

		var $table="<h6> Click on a title to get the details </h6>";
		$table+=" \
			<table border='1' class='prettytable sortable ''  > \
			<tr> \
			  <td>Title</td> \
			  <td>Submitted by</td> \
			  <td>URL</td> \
			  <td>Comment</td> \
			  <td>Evaluated by</td> \
			</tr> \
		";

		var evalDetails={};
		for (var i in data.evaluations) {
			if ( !data.evaluations[i].evaluation)
				continue;
			$table += "<tr> \
			  <td>  <a class='peVEtitle' href='#dialog' key='"+i+"' name="+data.evaluations[i].id+">"+ data.evaluations[i].evaluation.activity.title +" </a> </td> \
			  <td> <a href='/User:"+ data.evaluations[i].evaluation.activity.userName +"'>"+ data.evaluations[i].evaluation.activity.userName +" </a></td> \
			  <td><a href='"+ data.evaluations[i].evaluation.activity.url +"' target='_blank'> "+data.evaluations[i].evaluation.activity.url+"</a> </td> \
			  <td>"+ data.evaluations[i].evaluation.activity.comment +"</td> \
			  <td> <a href='/User:"+data.evaluations[i].evaluaterUName+"'>"+ data.evaluations[i].evaluaterUName +" </a></td> \
			  </tr> \
			";

			var j;
			if ( data.evaluations[i].evaluation.related === 0 ) {
				evalDetails[i]="The evaluator said that the post is not related.<br>";
				if (data.evaluations[i].evaluation.comment) {
					evalDetails[i]+="<b>Comment :"+data.evaluations[i].evaluation.comment;
				}
				else {
					evalDetails[i]+="<b>No additional comment </b>";
				}
			}
			else if ( data.evaluations[i].evaluation.type === "1" ) {
				var typea =  data.evaluations[i].evaluation.submission.a;
				var typeb =  data.evaluations[i].evaluation.submission.b;

				evalDetails[i] = "<b> Level 1 </b> <br><br>";
				for (j = 0; j < typea.length ; j++) { 
					evalDetails[i] += "<b> Question : </b>" + typea[j].Question + "<br>";
					evalDetails[i] += "<b> Answer : </b>" + ( typea[j].Answer === "1" ? "Yes" : "No" ) + "<br><br>";
				}

				evalDetails[i] += "<br><b> Level 2 </b> <br><br>";
				for (j = 0; j < typeb.length ; j++) { 
					evalDetails[i] += "<b> Question : </b>" + typeb[j].Question + "<br>";
					evalDetails[i] += "<b> Answer : </b>" + ( typeb[j].Answer === "1" ? "Yes" : "No" ) + "<br><br>";
				}
				if (data.evaluations[i].evaluation.comment) {
					evalDetails[i]+="<b>Comment :"+data.evaluations[i].evaluation.comment;
				}
				else {
					evalDetails[i]+="<b>No additional comment </b>";
				}

			}

			else if ( data.evaluations[i].evaluation.type === "2" ) {
				var answers =  data.evaluations[i].evaluation.submission;

				evalDetails[i] = "<b> Details: </b> <br><br>";
				for (j = 0; j < answers.length ; j++) { 
					evalDetails[i] += "<b> Question : </b>" + answers[j].Question + "<br>";
					evalDetails[i] += "<b> Grade : </b>" + answers[j].Answer + "<br><br>";
				}
				if (data.evaluations[i].evaluation.comment) {
					evalDetails[i]+="<b>Comment :"+data.evaluations[i].evaluation.comment;
				}
				else {
					evalDetails[i]+="<b>No additional comment </b>";
				}
			}

			else if ( data.evaluations[i].evaluation.type === "3" ) {
				var answers =  data.evaluations[i].evaluation.submission;

				evalDetails[i] = "<b> Details: </b> <br><br>";
				for (j = 0; j < answers.length ; j++) { 
					evalDetails[i] += "<b> Question : </b>" + answers[j].Question + "<br>";
					evalDetails[i] += "<b> Rating : </b>" + answers[j].Answer + "<br><br>";
				}
				if (data.evaluations[i].evaluation.comment) {
					evalDetails[i]+="<b>Comment :"+data.evaluations[i].evaluation.comment;
				}
				else {
					evalDetails[i]+="<b>No additional comment </b>";
				}
			}

			else {
				evalDetails[i]="<b> Error </b> : This is an old evaluation for which details cannot be parsed <br>";
				evalDetails[i] += "<br>Shown below is the raw data<br><br>";
				evalDetails[i] += JSON.stringify(data.evaluations[i].evaluation.submission);
			}
		}


		$("#peVEt1content").html($table);

		$(".peVEtitle").click( function() {
			$("#peVEbcontent").after("<div id='peVEdialog' title='Evaluation Details'> " + evalDetails[$(this).attr("key")] +" </div>");
			$( "#peVEdialog" ).dialog({width:500});	
		});

	});

});
