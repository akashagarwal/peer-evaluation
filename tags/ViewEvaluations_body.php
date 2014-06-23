<?php
class TagViewEvaluations {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'viewevaluations', array( __CLASS__, 'viewevaluationsRender' ) );

		return true;
	}



	static function viewevaluationsRender( $input, array $args, Parser $parser ) {
		$ret='<script src="/extensions/PeerEvaluation/resources/viewevaluations.js"></script>';

		$ret .='<span id="mcontent">
			<span id="t1content">
			</span>
			</span>
			<span id="bcontent"></span>';
		return $ret;
	}

}
?>