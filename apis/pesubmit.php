<?php

class pesubmit extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, '' );
	}

	public function execute() {
		global $wgUser, $wgServer;
		global $wgDefaultUserOptions;

		$result = $this->getResult();
		$params = $this->extractRequestParams();

		$data = '';

		$activityPage = filter_var( $params['peactivity'], FILTER_SANITIZE_STRING );

		if ( !$activityPage ) {
			$this->dieUsage( 'nopeactivity' , 'activity page cannot be null' );
		}

		$title = Title::newFromText( ':' . $activityPage );
		$revision = Revision::newFromTitle ( $title );

		if ( $revision == null )
		  return "Page does not exist";
		$text = $revision->getText( Revision::FOR_PUBLIC );

		$end = strpos( $text, '|}' );

		$entry = "|-\n";
		$entry .= "|[" . filter_var( $params['peurl'], FILTER_SANITIZE_STRING ) . " " . filter_var( $params['petitle'], FILTER_SANITIZE_STRING ) . "]\n";
		$entry .= "|[[User:" . $wgUser->getName() . "|" . $wgUser->getRealName() . "]]\n";
		$entry .= "|" . filter_var( $params['pecomment'], FILTER_SANITIZE_STRING ) . "\n";
		$entry .= "|" . ( filter_var( $params['peoptin'], FILTER_SANITIZE_STRING ) == 'true' ? "Yes" : "No" ) . "\n";
		$entry .= "|" . date( 'Y-m-d H:i:s' ) . "\n";
		$entry .= "|0\n";


		$pre = substr( $text, 0 , $end );
		$post = substr( $text, $end );
		$this->editArticle( $pre . $entry . $post, "Test edit of article", $activityPage );


		$result->addValue( null, $this->getModuleName(), array( 'success' => "
			Activity successfully registered" . $data ) );

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
			'pecourse' => null,
			'peactivity' => null,
			'peurl' => null,
			'pecomment' => null,
			'peoptin' => null,
			'petitle' => null
		);
	}

	public function getParamDescription() {
		return array (
			'pecourse' => 'course identifier',
			'peactivity' => 'activity identifier',
			'peurl' => 'URL to submission',
			'pecomment' => 'Comment by author',
			'peoptin' => 'Peer Evaluation opt in flag',
			'petitle' => 'Title of the submission'
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
