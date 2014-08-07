/*global $:false */
/*jshint -W043 */

$ ( document ).ready ( function() {

	var activity=$("#activityPage").attr("activity");

	$.get("/api.php?action=evaluations&evactivities="+activity+"&evprop=evaluations&format=json",function(data) {

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

		for (var i in data.evaluations) {
			console.log(data.evaluations[i]);
			if ( !data.evaluations[i].evaluation)
				continue;
			$table += "<tr> \
			  <td>  <a class='title' name="+data.evaluations[i].id+">"+ data.evaluations[i].evaluation.activity.title +" </a> </td> \
			  <td> <a href='/User:"+ data.evaluations[i].evaluation.activity.userName +"'>"+ data.evaluations[i].evaluation.activity.userName +" </a></td> \
			  <td><a href='"+ data.evaluations[i].evaluation.activity.url +"' target='_blank'> "+data.evaluations[i].evaluation.activity.url+"</a> </td> \
			  <td>"+ data.evaluations[i].evaluation.activity.comment +"</td> \
			  <td> <a href='/User:"+data.evaluations[i].evaluaterUName+"'>"+ data.evaluations[i].evaluaterUName +" </a></td> \
			  </tr> \
			";
		}

		$("#t1content").html($table);
	});

});
