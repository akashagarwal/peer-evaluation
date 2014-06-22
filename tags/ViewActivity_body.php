<?php
class TagViewActivity {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'viewactivities', array( __CLASS__, 'viewactivitiesRender' ) );

		return true;
	}



	static function viewactivitiesRender( $input, array $args, Parser $parser ) {
		$ret='<script src="/extensions/PeerEvaluation/resources/viewactivity.js"></script>';

		$dbr = wfGetDB( DB_SLAVE );

		$ret .='<span id="mcontent">';

        $activity_cd= $dbr->select(
            'pe_cd_Activities',
            array( '*'),
            $conds = 'pe_flag=1',
            $fname = __METHOD__,
            $options = array( '' )
        );

        $ret.="<b> Activities available for Evaluation </b> <br>";

        foreach ($activity_cd as $row) {
			$ret.='<button type="button" id="'.$row->id.'" class="getActivities">'.$row->title.'</button> ';
	    }
		$activity_cd= $dbr->select(
            'pe_cd_Activities',
            array( '*'),
            $conds = 'pe_flag=0',
            $fname = __METHOD__,
            $options = array( '' )
        );

        $ret.="<br><b> Activities currently not available for Evaluation </b> <br>";

        foreach ($activity_cd as $row) {
			$ret.='<button type="button" id="'.$row->id.'" class="getActivities">'.$row->title.'</button> ';
	    }
	
		$ret .=	'<br><span id="t1content">
			</span>
			</span>';
		return $ret;
	}

}
?>

