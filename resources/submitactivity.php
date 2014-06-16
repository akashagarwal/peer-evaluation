<?php

/*echo "SUCCESS <br> ";
foreach ($_REQUEST as $key => $value) {
	
	echo $key." - ".$value." <br> ";

}
*/		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		echo "here <br>";
		global $wgUser;
		$dbw = wfGetDB( DB_MASTER );

		$date = date('Y-m-d H:i:s');
		echo "here <br>";

		$dbw->insert(
			'pe_Activities',
			array('userid' => $wgUser->getId(), 'URL' => $_REQUEST['url'] , 'Title' => $_REQUEST['title'] , 'Comment' => $_REQUEST['comment']  , 'OptedIn' => $_REQUEST['optin'], 'Activity_id' => $_REQUEST['activityid'], 'Timestamp' => $date),
			$fname = 'Database::insert', $options = array()
		);

		echo "Activity Successfully Registereed<br/>";
		echo " <h1><a href='./Special:ViewActivities'> Click Here to view all submitted Activities </a></h1><br/>";



?>
