<?php

/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

class TagViewEvaluations {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'viewevaluations', array( __CLASS__, 'viewevaluationsRender' ) );

		return true;
	}



	static function viewevaluationsRender( $input, array $args, Parser $parser ) {

		global $wgPeerEvaluationHomedirPath;
		$ret = '<script src="' . $wgPeerEvaluationHomedirPath . '/resources/viewevaluations.js"></script>';
		$ret .= '<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>';
		$ret .= '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">';
        $activity = $args['activity'];

        $ret .= "<span id='peVEactivityPage' activity='" . $activity . "' ></span>";

		$ret .= '<span id="peVEmcontent">
			<span id="peVEt1content">
			</span>
			</span>
			<span id="peVEbcontent"></span>';
		return $ret;
	}

}
?>