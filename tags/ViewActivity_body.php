<?php
class TagViewActivity {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'viewactivities', array( __CLASS__, 'viewactivitiesRender' ) );

		return true;
	}



	static function viewactivitiesRender( $input, array $args, Parser $parser ) {
		$ret='<script src="/core/extensions/PeerEvaluation/resources/viewactivity.js"></script>';

		$ret .='<span id="mcontent"><button type="button" id="1" class="getActivities">1st Learning reflection</button> <button type="button" id="2" class="getActivities">2nd Learning reflection</button> <button type="button" id="3" class="getActivities">Activity 3.1</button> <button type="button" id="4" class="getActivities">Activity 4.1</button> <button type="button" id="5" class="getActivities" >3rd Learning reflection</button> <br>
			<span id="t1content">
			</span>
			</span>';
		return $ret;
	}

}
?>

