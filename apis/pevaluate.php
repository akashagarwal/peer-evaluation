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


		$activityPage = filter_var( $params['peactivity'], FILTER_SANITIZE_STRING );
		$id = filter_var( $params['peid'], FILTER_SANITIZE_NUMBER_INT );
		$evaluation = filter_var($params['pevaluation'],FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

		$dbw = $this->getDB();

		$dbw->insert(
			'pe_evaluations',
			array( 'activityId' => $id, 'Activity' => $activityPage, 'evaluaterUName' => $wgUser->getName() , 'evaluation' => $evaluation ),
			$fname = 'Database::insert',
			$options = array()
		);

		$result->addValue( null, $this->getModuleName(), array( 'success' => "
			Evaluation Successfully Processed" ) );

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
