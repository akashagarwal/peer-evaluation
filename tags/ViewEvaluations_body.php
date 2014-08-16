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
		$ret='<script src="/extensions/PeerEvaluation/resources/viewevaluations.js"></script>';

        $activity=$args['activity'];

        $ret.="<span id='peVEactivityPage' activity='".$activity."' ></span>";
       
		$ret .='<span id="peVEmcontent">
			<span id="peVEt1content">
			</span>
			</span>
			<span id="peVEbcontent"></span>';
		return $ret;
	}

}
?>