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
			    if (xmlhttp.readyState==4 && xmlhttp.status==200)
				        {
						    document.getElementById("form").innerHTML=xmlhttp.responseText;
				        }
			      }
	url=document.getElementById("url").value;
	title=document.getElementById("title").value;
	comment=document.getElementById("comment").value;
	activityid=document.getElementById("activityid").value;
	optin=document.getElementById("optin").checked;
	console.log(url+"Yes");
	xmlhttp.open("GET","/core/extensions/PeerEvaluation/resources/submitactivity.php?url="+url+"&title="+title+"comment="+comment+"activityid="+activityid+"optin="+optin,true);
	xmlhttp.send();

}

window.onload=function(){
	if (!wgUserName)
	{
		content=document.getElementById("t1content").innerHTML="You are not logged in";
		console.log("Not logged in");
	}
};
