<?php
/**
 * HelloWorld SpecialPage for PeerEvaluation extension
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
		global $wgOut,$wgUser;
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

		$wgOut->addHTML("Success<br/>");
		$dbw = wfGetDB( DB_MASTER );


		$dbw->insert(
			'pe_Activities',
			array('userid' => $wgUser->getId(), 'URL' => 'dsfjkds', 'Title' => '1', 'Comment' => '1', 'OptedIn' => true, 'Activity_id' => '1'),
			$fname = 'Database::insert', $options = array()
			);




			

	}
}
