<?php
/**
 * Submit Activity SpecialPage for PeerEvaluation extension
 *
 * @file
 * @ingroup Extensions
 */

class SpecialSubmitActivity extends SpecialPage {
	public function __construct() {
		parent::__construct( 'SubmitActivity' );
	}

	/**
	 * Shows the page to the user.
	 * @param string $sub: The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	
	public function execute( $sub ) {
		global $wgOut,$wgUser,$wgRequest;
//		$out = $this->getOutput();
	
//		$out->setPageTitle("PeerEvaluation test page");

//		$wgOut->addHTML("PeerEvaluation test page");

		if (!$wgUser->isLoggedIn())
		{
			$wgOut->addHTML("Please Login and return back to this page.");
			return;
		}

		$wgOut->setPageTitle("Register Your Blog");
		$username=$wgUser->getName();
		

		$userCoursePage="User:".$username."/OCL4Ed 14.02";
		$title=Title::newFromText($userCoursePage);

		if (!$title->exists())
		{
			$wgOut->addHTML("You are not registered for OCL4eD. Please register and come back to this page.");
			return;
		}
		
			
//		$wgOut->addHTML("Success: Your course page is it ".$title->getFullURL());


		if (!$wgRequest->wasPosted())
		{
		$form =' <form action="./Special:SubmitActivity" method="post">
			Activity : <select name="Activity_id">
			<option value="1">Copyright_MCQ_e-learning_activity</option>
			<option value="2">Learning_reflection</option> 
			</select>
			<br>
			URL of the blog : <input type="text" name="URL"><br>
			Title : <input type="text" name="Title"><br>
			Comment : <input type="text" name="Comment"><br>
			<input type="checkbox" name="OptedIn" value="true"> Opt in for Evaluation <br>
			<input type="submit" value="Submit">
			</form>';
		$wgOut->addHTML($form);
		return;
		}
 

		$dbw = wfGetDB( DB_MASTER );

		$date = date('Y-m-d H:i:s');

		$dbw->insert(
			'pe_Activities',
			array('userid' => $wgUser->getId(), 'URL' => $wgRequest->getText('URL') , 'Title' => $wgRequest->getText('Title') , 'Comment' => $wgRequest->getText('Comment')  , 'OptedIn' => $wgRequest->getBool('OptedIn',$default = false), 'Activity_id' => $wgRequest->getInt('Activity_id'), 'Timestamp' => $date),
			$fname = 'Database::insert', $options = array()
		);

		$wgOut->addHTML("Activity Successfully Registereed<br/>");
		$wgOut->addHTML(" <h1><a href='./Special:ViewActivities'> Click Here to view all submitted Activities </a></h1><br/>");
		
	}
}
