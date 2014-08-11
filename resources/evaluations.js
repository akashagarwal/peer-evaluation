/*global $:false */
/*jshint -W043 */

$ ( document ).ready ( function() {

	$.get("/api.php?action=query&meta=userinfo&format=json",function(data){ 
		if (data.query.userinfo.id === 0) {
			$("#table").html("You need to be logged in to submit an activity. Click <a href='/?title=Special:UserLogin' target='_blank'>here </a> to login");
			return;
		}
	});

	var activity=$("#type").attr("activity");
	$.get("/api.php?action=evaluations&evactivities="+activity+"&evprop=submissions&format=json",function(data) {
		$("#form").hide();

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
				<td> <a id='"+i+"' class='title' > "+data.evaluations[i].title+" </a> </td> \
				<td> <a href='/User:"+data.evaluations[i].userName+"'>"+ data.evaluations[i].userName +" </a></td> \
				<td> <a href='"+data.evaluations[i].url+"' target='_blank'> "+data.evaluations[i].url+"</a></td>   \
				<td>"+data.evaluations[i].comment+"</td> \
				<td>"+(data.evaluations[i].optedIn ? "Yes" :"No")+"</td> \
				<td>"+data.evaluations[i].numEval+"</td> \
				<td>"+data.evaluations[i].timeStamp+"</td> \
				</tr> \
				";
			table+=$t;
		}
		$("#table").html(table);

		$(".title").click ( function()  {	
			$("#table").hide();
			var type=$("#type").attr("value");
			$("#form").show();
			$("#form").css("visibility","visible");

			var id=$(this).attr("id");
			var evaluation={};
			evaluation.activity={ title: data.evaluations[id].title, userName: data.evaluations[id].userName, url:data.evaluations[id].url , comment:data.evaluations[id].comment , optedIn:data.evaluations[id].optedIn , timeStamp:data.evaluations[id].timeStamp };
			if ( type === "1" ) {

				$("#submit").click ( function () {
					evaluation.submission={ a:[] , b:[] };

					$("span[type=q]").each( function() {
						var entry=$(this);
						var ans=$("input[name="+entry.attr("qid")+"]:checked").val();

						var qa={};

						qa.Question=entry.html();
						qa.Answer=ans;

						evaluation.submission[entry.attr("name")].push(qa);

					});
					var submitData={};
					submitData.peid=data.evaluations[id].id;
					submitData.peactivity="User:Test/sampleactivity";
					submitData.pevaluation=JSON.stringify(evaluation);

					$.post("/api.php?action=pevaluate&format=json",submitData,function(data){
						$("#form").html(data.pevaluate.success);
					});
				});
			}
			if ( type === "2" ) {

				$("#submit").click ( function () {
					evaluation.submission=[];

					$("span[type=q]").each( function() {
						var entry=$(this);
						var ans=$("input[name="+entry.attr("qid")+"]:checked").val();

						var qa={};

						qa.Question=entry.attr("q");
						qa.Answer=ans;

						evaluation.submission.push(qa);

					});
					var submitData={};
					submitData.peid=data.evaluations[id].id;
					submitData.peactivity="User:Test/sampleactivity";
					submitData.pevaluation=JSON.stringify(evaluation);
					$.post("/api.php?action=pevaluate&format=json",submitData,function(data){
						$("#form").html(data.pevaluate.success);
					});
				});
			}       
			if ( type === "3" ) {

				$("#submit").click ( function () {
					evaluation.submission=[];

					$("input[type=text]").each( function() {
						var entry=$(this);
						var ans=entry.val();

						var qa={};

						qa.Question=entry.attr("q");
						qa.Answer=ans;

						evaluation.submission.push(qa);

					});
					var submitData={};
					submitData.peid=data.evaluations[id].id;
					submitData.peactivity="User:Test/sampleactivity";
					submitData.pevaluation=JSON.stringify(evaluation);

					$.post("/api.php?action=pevaluate&format=json",submitData,function(data){
						$("#form").html(data.pevaluate.success);
					});
				});
			}
		} );
	});
});