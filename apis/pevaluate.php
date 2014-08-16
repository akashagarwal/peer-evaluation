<?php

/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

class pevaluate extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, '' );
	}

	public function execute() {
		global $wgUser, $wgServer;
		global $wgDefaultUserOptions;

		$result = $this->getResult();
		$params = $this->extractRequestParams();
		
        if (!$wgUser->isLoggedIn()) {
            $this->dieUsage('must be logged in',
                'notloggedin');
        };

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

		$title = Title::newFromText( ':' . $activityPage );
		$revision = Revision::newFromTitle ( $title );

		if ( $revision == null ) {
			$this->dieUsage( 'pagedoesnotexest' , 'activity page does not exist' );
		}

		$text = $revision->getText( Revision::FOR_PUBLIC );

		$idPos = strpos( $text, "id=".$id );
		$idPos -= 15;

		$ntext = substr( $text, $idPos );		
		$noPosSt = strpos( $ntext, "|" );
		$ntext = substr( $ntext, $noPosSt + 1 );
		$noPosEnd = strpos( $ntext, "\n" );

		$numEval = substr( $ntext, 0, $noPosEnd);

		$this->editArticle( substr( $text, 0, $idPos + $noPosSt +1 ) . strval( intval( $numEval ) + 1 ) . substr( $text, $idPos + $noPosSt + 1 +  $noPosEnd ) , "Update of number of evaluation through pevaluate API", $activityPage );


		$result->addValue( null, $this->getModuleName(), array( 'success' => "
			Evaluation successfully processed" ) );

		return true;
	}

	protected function getDB() {
		return wfGetDB( DB_MASTER );
	}

	protected function editArticle ( $text, $summary, $title ) {

		$wgTitle = Title::newFromText( $title );
		$wgArticle = new Article( $wgTitle );
		$wgArticle->doEdit( $text, $summary, ( 0 ) | ( 0 ) | ( 0 ) | ( 0 ) );
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
		return 'API to submit evaluations';
	}

	protected function getExamples() {
		return array ();
	}
	public function getVersion() {
		return __CLASS__ . ': 0';
	}
}

?>
