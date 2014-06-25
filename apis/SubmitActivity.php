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

        $params = $this->extractRequestParams();

        $result = $this->getResult();

        $logincheck=$params['logincheck'];
        if ($logincheck){
            return;
        };

        $id = NULL;
        $user = $wgUser->getId();

        $dbw=$this->getDB();
//       $result->addValue( null, $this->getModuleName(), array ( 'apiresponses' => $params['face'] ));
//     $result->addValue( null, $this->getModuleName(), array ( 'user' => $user ));

        $date = date('Y-m-d H:i:s');

        $dbw->insert(
            'pe_Activities',
            array('userid' => $wgUser->getId(), 'URL' => trim(filter_var($params['url'],FILTER_SANITIZE_STRING)) , 'Title' => trim(filter_var($params['title'],FILTER_SANITIZE_STRING)) , 'Comment' => trim(filter_var($params['comment'],FILTER_SANITIZE_STRING))  , 'OptedIn' => ($params['optin']=='true'?1:0), 'Activity_id' => filter_var($params['activityid'],FILTER_SANITIZE_NUMBER_INT), 'Timestamp' => $date),
            $fname = 'Database::insert', $options = array()
        );

        $result->addValue(null, $this->getModuleName(),array('success' => "
            Activity Successfully Registered<br/>
            <h1><a> Click Here to view all submitted Activities </a></h1><br/>"));


        return true;
    }
    
    protected function getDB() {
        return wfGetDB( DB_MASTER );
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
