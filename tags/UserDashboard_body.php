<?php
class TagUserDashboard {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'userdashboard', array( __CLASS__, 'userdashboardRender' ) );

		return true;
	}



	static function userdashboardRender( $input, array $args, Parser $parser ) {
		$ret='<script src="/extensions/PeerEvaluation/resources/userdashboard.js"></script>';

		$ret .='<span id="mcontent">
			<span id="t1content">
			</span>
			</span>
			<span id="bcontent"></span>';
		return $ret;
	}

}
?>