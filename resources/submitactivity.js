/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

/*global $:false */
/*jshint -W043 */


$ ( document ).ready ( function() {

	$.get("/api.php?action=query&meta=userinfo&format=json",function(data){ 
		if (data.query.userinfo.id === 0) {
			$("#errors").html("You need to be logged in to submit an activity. Click <a href='/?title=Special:UserLogin' target='_blank'>here </a> to login");
			$("#form").hide();
		}
	});

	$("#url").blur( function() {
		var url=$(this).val();
		var n = url.indexOf("www");
		if (n===0 || url.indexOf("http")!==0 )
		{
			url="http://" + url;
			$(this).val(url);
		}
		var content="<p>Please ensure that the URL contains the blog post specified and not the home page of the blog or the edit page. \
				<br>Click <a href="+url+" target='_blank'> here </a> to check if you reach the correct post. </p>";
		$("#urlerror").html(content);
	});

	$("#sa_submit").click( function() {

		$("#urlerror2").html("");
		$("#titleerror").html("");

		var submitData={};

		var url=$("#url").val();
		if (url === null || url === "") {
			$("#urlerror2").html("<br><b style='color:red'>URL must be filled out</b>");
			return false;
		}

		var title=$("#title").val();
		if (title === null || title === "") {
			$("#titleerror").html("<b style='color:red' >Title must be filled out</b></br>");
			return false;
		}
		var comment=$("#comment").val();
		var activityPage=$("#activityPage").val();

		var optin=document.getElementById("optin").checked;

		submitData.action="pesubmit";
		submitData.peurl=url;
		submitData.petitle=title;
		submitData.pecomment=comment;
		submitData.peoptin=optin;
		submitData.peactivity=activityPage;
		submitData.format="json";

		$("#form").html("Processing your submission...");			

		$.post("/api.php", submitData, function( data ) {
				if (!data.pesubmit) {
					if ( data.error.code === "notloggedin") {
						$("#form").html("Looks like you have logged out from another tab or your session has expired. Please login before you continue.");
					}
					else {
						$("#form").html("Error : "+data.error.code);
					}
					return;
				}
				$("#form").html(data.pesubmit.success);
			});
	});
});