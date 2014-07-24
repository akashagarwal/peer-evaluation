<?php

class apiSubmitActivity extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {
        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;

        if (!$wgUser->isLoggedIn()) {
            $this->dieUsage('must be logged in',
                'notloggedin');
        };
	
	$result=$this->getResult();
	$this->editArticle('yo hoho mo !!! it works' , 'auto edit by API','User:Akashagarwal/sample-Activity3');
        $result->addValue(null, $this->getModuleName(),array('success' => "
            Activity Successfully Registered<br/>"));

        return true;
    }
    
    protected function getDB() {
        return wfGetDB( DB_MASTER );
    }

    protected function editArticle ($text, $summary,$title ) {

	    $wgTitle = Title::newFromText( $title );
	    $wgArticle = new Article( $wgTitle );
	    $wgArticle->doEdit( $text, $summary, ( 0 ) | ( 0 ) | ( 0 ) | ( 0 ) );
    }

    public function getAllowedParams() {
        return array (
            'url' => null,
            'title' => null,
            'comment' => null,
            'activityid' => null,
            'optin' => null,
            'logincheck' => null
        );
    }
 
    public function getParamDescription() {
        return array (
            'url' => 'url',
            'title' => 'title',
            'comment' => 'comment',
            'activityid' => 'activityid',
            'optin' => 'optin',
            'logincheck' => 'logincheck'

        );
    }
 
    public function getDescription() {
        return 'API to submit the Activities';
    }
 
    protected function getExamples() {
        return array (
            'api.php?action=apiSubmitActivity&url=www.myreflections.blogspot.com&title=Myrefole&comment=yesitworks&activityid=1&optin=true&format=xml',
        );
    }
        public function getVersion() {
		        return __CLASS__ . ': 0';
			    }

}

?>
