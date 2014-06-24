function submit()
{
//	alert("hello");
	var xmlhttp;
	if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
			    xmlhttp=new XMLHttpRequest();
			      }
	else
		  {// code for IE6, IE5
			    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			      }
	xmlhttp.onreadystatechange=function()
	{
		console.log("uYes");
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			xmlDoc=xmlhttp.responseXML;
			x=xmlDoc.getElementsByTagName("apiSubmitActivity")[0];
			if (!x) {
				code=xmlDoc.getElementsByTagName("error")[0].attributes['code'].nodeValue;
				if (code == 'notloggedin') {
					alert('Looks like you have logged out from another tab or your session has expired. Please login before you continue.');
				}
				else {
					alert('Error : Cound not submit the activity. Please report this.');
				}
				return;
			}
			y=x.attributes['success'].nodeValue;

		    document.getElementById("form").innerHTML=y;

		}
	}
	url=document.getElementById("url").value;
    if (url == null || url == "") {
        alert("URL must be filled out");
        return false;
    }

	title=document.getElementById("title").value;
    if (title == null || title == "") {
        alert("Title must be filled out");
        return false;
    }
	comment=document.getElementById("comment").value;

	activityid=document.getElementById("activityid").value;
    if (activityid == null || activityid == "") {
        alert("You must choose an activity.");
        return false;
    }
	optin=document.getElementById("optin").checked;
	console.log(url+"Yes");
	xmlhttp.open("GET","/api.php?action=apiSubmitActivity&url="+url+"&title="+title+"&comment="+comment+"&activityid="+activityid+"&optin="+optin+"&format=xml",true);
	xmlhttp.send();

}

function urlFunction()
{
	url=document.getElementById("url").value;
	var n = url.indexOf("www");
	if (n==0 || url.indexOf("http")!=0 )
	{
		url="http://" + url;
		document.getElementById("url").value=url;
	}
	content="<iframe  width='100%' height='300	' src="+url+"></iframe><br>";
	content+="<p>Please ensure that the URL contains the blog post specified and not the home page of the blog or the edit page.<br>If you can see your post above then the URL is correct otherwise click <a href="+url+" target='_blank'> here </a> to check if you reach the correct post. </p>";
	document.getElementById("urlerror").innerHTML=content;
}

$ ( document ).ready ( function() {

	$.get("/api.php?action=apiSubmitActivity&logincheck=1&format=json",function(data,status){ 
		if (data['error']) {
			$('#errors').html('<b> Not logged in : You need to be looged in to submit activities </b><br>');
		};
	});
});