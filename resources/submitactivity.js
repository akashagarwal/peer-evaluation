/*global $:false */
/*global alert:false */
/* exported submit */
/* exported urlFunction */
/*jshint -W043 */


function submit()
{

	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
	    if (xmlhttp.readyState===4 && xmlhttp.status===200)
		{
			var xmlDoc=xmlhttp.responseXML;
			var x=xmlDoc.getElementsByTagName("pesubmit")[0];
			if (!x) {
				var code=xmlDoc.getElementsByTagName("error")[0].attributes.code.Value;
				if (code === "notloggedin") {
					alert("Looks like you have logged out from another tab or your session has expired. Please login before you continue.");
				}
				else {
					alert("Error : "+code);
				}
				return;
			}
			var y=x.attributes.success.nodeValue;
		    document.getElementById("form").innerHTML=y;
		}
	};
	var url=document.getElementById("url").value;
    if (url === null || url === "") {
        alert("URL must be filled out");
        return false;
    }

	var title=document.getElementById("title").value;
    if (title === null || title === "") {
        alert("Title must be filled out");
        return false;
    }
	var comment=document.getElementById("comment").value;
	var activityPage=document.getElementById("activityPage").value;

	var optin=document.getElementById("optin").checked;
	xmlhttp.open("POST","/api.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("action=pesubmit&peurl="+url+"&petitle="+title+"&pecomment="+comment+"&peoptin="+optin+"&peactivity="+activityPage+"&format=xml");
	document.getElementById("form").innerHTML="Your submission is being processed...";

}

function urlFunction()
{
	var url=document.getElementById("url").value;
	var n = url.indexOf("www");
	if (n===0 || url.indexOf("http")!==0 )
	{
		url="http://" + url;
		document.getElementById("url").value=url;
	}
	var content="<p>Please ensure that the URL contains the blog post specified and not the home page of the blog or the edit page. \
			<br>Click <a href="+url+" target='_blank'> here </a> to check if you reach the correct post. </p>";
	document.getElementById("urlerror").innerHTML=content;
}

$ ( document ).ready ( function() {

	$.get("/api.php?action=query&meta=userinfo&format=json",function(data){ 
		if (data.query.userinfo.id === 0) {
			$("#errors").html("You need to be logged in to submit an activity. Click <a href='/?title=Special:UserLogin' target='_blank'>here </a> to login");
			$("#form").hide();
		}
	});


});