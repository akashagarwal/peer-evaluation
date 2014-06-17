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
			x=xmlDoc.getElementsByTagName("apiSubmitActivity")[0].attributes['success'].nodeValue;

		    document.getElementById("form").innerHTML=x;

		}
	}
	url=document.getElementById("url").value;
	title=document.getElementById("title").value;
	comment=document.getElementById("comment").value;
	activityid=document.getElementById("activityid").value;
	optin=document.getElementById("optin").checked;
	console.log(url+"Yes");
	xmlhttp.open("GET","/core/api.php?action=apiSubmitActivity&url="+url+"&title="+title+"&comment="+comment+"&activityid="+activityid+"&optin="+optin+"&format=xml",true);
	xmlhttp.send();

}

window.onload=function(){
	if (!wgUserName)
	{
		content=document.getElementById("t1content").innerHTML="You are not logged in";
		console.log("Not logged in");
	}
};

function urlFunction()
{
	url=document.getElementById("url").value;
	var n = url.indexOf("www");
	if (n==0 || url.indexOf("http")!=0 )
	{
		url="http://" + url;
		document.getElementById("url").value=url;
	}
	content="<iframe  width='100%' src="+url+"></iframe><br>";
	content+="<p>Please ensure that the URL contains the blog post specified and not the home page of the blog or the edit page.<br>If you can see your post above then the URL is correct otherwise click <a href="+url+" target='_blank'> here </a> to check if you reach the correct post. </p>";
	document.getElementById("urlerror").innerHTML=content;

}