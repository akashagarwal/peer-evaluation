<?php

class pevaluate extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, '' );
	}
 
	public function execute() {
		global $wgUser, $wgServer;
		global $wgDefaultUserOptions;

		$result = $this->getResult();
		$params = $this->extractRequestParams();


		$activityPage=$params['peactivity'];
		$id=$params['peid'];
		$evaluation=$params['pevaluation'];

		$dbw=$this->getDB();

        $dbw->insert(
            'pe_evaluations',
            array('id' => $id, 'Activity' => $activityPage, 'evaluaterUName' => $wgUser , 'evaluation' => $evaluation ),
            $fname = 'Database::insert', 
            $options = array()
        );

		$result->addValue(null, $this->getModuleName(),array('success' => "
			Evaluation Successfully Processed<br/>"));
		
		return true;
	}
	
	protected function getDB() {
		return wfGetDB( DB_MASTER );
	}

	public function getAllowedParams() {
		return array (
			'peid' => null,
			'peactivity' => null,
			'pevaluation' => null,
		);
	}
 
	public function getParamDescription() {
		return array (
			'peid' => 'peer evaluation item',
			'peactivity' => 'activity identifier',
			'pevaluation' => 'evaluation of item',
		);
	}
 
	public function getDescription() {
		return 'API to submit the Activities';
	}
 
	protected function getExamples() {
		return array ();
	}
	public function getVersion() {
		return __CLASS__ . ': 0';
	}
}

?>