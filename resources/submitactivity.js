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
			$("#pesaerrors").html("You need to be logged in to submit an activity. Click <a href='/?title=Special:UserLogin' target='_blank'>here </a> to login");
			$("#pesaform").hide();
		}
	});

	$("#pesaurl").blur( function() {
		var url=$(this).val();
		var n = url.indexOf("www");
		if (n===0 || url.indexOf("http")!==0 )
		{
			url="http://" + url;
			$(this).val(url);
		}
		var content="<p>Please ensure that the URL contains the blog post specified and not the home page of the blog or the edit page. \
				<br>Click <a href="+url+" target='_blank'> here </a> to check if you reach the correct post. </p>";
		$("#pesaurlerror").html(content);
	});

	$("#pesa_submit").click( function() {

		$("#pesaurlerror2").html("");
		$("#pesatitleerror").html("");

		var submitData={};

		var url=$("#pesaurl").val();
		if (url === null || url === "") {
			$("#pesaurlerror2").html("<br><b style='color:red'>URL must be filled out</b><br/>");
			return false;
		}

		var title=$("#pesatitle").val();
		if (title === null || title === "") {
			$("#pesatitleerror").html("<b style='color:red' >Title must be filled out</b><br/>");
			return false;
		}
		var comment=$("#pesacomment").val();
		var activityPage=$("#pesaactivityPage").val();

		var optin=document.getElementById("pesaoptin").checked;

		submitData.action="pesubmit";
		submitData.peurl=url;
		submitData.petitle=title;
		submitData.pecomment=comment;
		submitData.peoptin=optin;
		submitData.peactivity=activityPage;
		submitData.format="json";

		$("#pesaform").html("Processing your submission...");			

		$.post("/api.php", submitData, function( data ) {
				if (!data.pesubmit) {
					if ( data.error.code === "notloggedin") {
						$("#pesaform").html("Looks like you have logged out from another tab or your session has expired. Please login before you continue.");
					}
					else {
						$("#pesaform").html("Error : "+data.error.code);
					}
					return;
				}
				$("#pesaform").html(data.pesubmit.success);
			});
	});
});