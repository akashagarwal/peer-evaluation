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
		global $wgOut;
//		$out = $this->getOutput();
	
//		$out->setPageTitle("PeerEvaluation test page");

		$wgOut->addHTML("PeerEvaluation test page");
	}
}
