<?php

class evaluations extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, '' );
	}
 
	public function execute() {
		
		global $wgUser, $wgServer;
		global $wgDefaultUserOptions;

		$result = $this->getResult();
		$params = $this->extractRequestParams();

		$data='';

		$activityPage=$params['evactivities'];
		$evprop=$params['evprop'];

		if ( !$activityPage ) {
			$this->dieUsage( 'nopeactivity' , 'activity page cannot be null' );
		}


		if ( $evprop == 'submissions' ) {	

			$title = Title::newFromText( ':' . $activityPage );
			$revision = Revision::newFromTitle ( $title );

			if ( $revision == null )
			  return "Page does not exist";
			$text = $revision->getText( Revision::FOR_PUBLIC );            
				
			$entries=array();

			$startpos=strpos($text, '|-');

			$num=1;
			while ( $startpos ) {


				$text=substr($text, $startpos+2);
				$entry=array();

				$pos=strpos($text, " ");
				$url=substr($text, 3,$pos-3);
				$text=substr($text, $pos+1);

				$entry['url'] = $url;

				$pos=strpos($text, "]");
				$title=substr($text, 0,$pos);
				$text=substr($text, $pos+1);
				$entry['title'] = $title;

				$pos=strpos($text, "User:");
				$text=substr($text, $pos+5);


				$pos=strpos($text, "|");
				$userName=substr($text, 0,$pos);
				$text=substr($text, $pos+1);
				$entry['userName'] = $userName;

				$pos=strpos($text, "|");
				$text=substr($text, $pos+1);

				$pos=strpos($text, "|");
				$comment=substr($text, 0,$pos);
				$text=substr($text, $pos+1);
				$entry['comment'] = $comment;

				$pos=strpos($text, "|");
				$optedIn=substr($text, 0,$pos);
				$text=substr($text, $pos+1);
				$entry['optedIn'] = $optedIn;

				$pos=strpos($text, "|");
				$timeStamp=substr($text, 0,$pos);
				$text=substr($text, $pos+1);
				$entry['timeStamp'] = $timeStamp;

				$pos=strpos($text, "<");
				$numEval=substr($text, 0,$pos);
				$text=substr($text, $pos+1);
				$entry['numEval'] = $numEval;

				$pos=strpos($text, "-->");
				$id=substr($text, 6,$pos-6);
				$entry['id'] = $id;

				$entries['entry'.$num]=$entry;
				
				$num++;
				$startpos=strpos($text, '|-');
			}

			$result->addValue(null, $this->getModuleName(),$entries);
		}

		if ( $evprop == 'evaluations' ) {

			$dbr=$this->getDB();

				$res = $dbr->select(
				'pe_evaluations',
				array( '*'),
				$conds = 'Activity="'.$activityPage.'"',
				$fname = __METHOD__,
				$options = array('')
			);

			$num=1;
			$entries=array();

			foreach ( $res as $row ) {
				$entry=array();
				$evaluation=json_decode($row->evaluation,true);
				$entry['id']=$row->activityId;
				$entry['evaluaterUName']=$row->evaluaterUName;
				$entry['evaluation']=$evaluation;
				$entries['entry'.$num]=$entry;
				$num++;
			}

			$result->addValue(null, $this->getModuleName(),$entries);
		}

		return true;
	}
	
	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}


	public function getAllowedParams() {
		return array (
			'evprop' => null,
			'evcourse' => null,
			'evactivities' => null,
			'evauthor' => null,
			'evalutor' => null,
			'evstartid' => null,
			'evlimit' => null,		
			'evcontinue' => null
		);
	}
 
	public function getParamDescription() {
		return array (
			'evprop' => 'properties to get',
			'evcourse' => 'limit to course when retrieving activities or evaluations',
			'evactivities' => 'list of activities',
			'evauthor' => 'user submitting activity',
			'evalutor' => 'user submitting evaluation',
			'evstartid' => 'from which evaluation property to start',
			'evlimit' => 'maximum number of items to return',		
			'evcontinue' => 'when more results are available, use this to continue'
			);
	}
 
	public function getDescription() {
		return 'API to submit the Activities';
	}
 
	protected function getExamples() {
		return array (
		);
	}
	public function getVersion() {
		return __CLASS__ . ': 0';
	}
}

?>
