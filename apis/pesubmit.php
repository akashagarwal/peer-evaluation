<?php

class pesubmit extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {
        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;


        

        $result->addValue(null, $this->getModuleName(),array('success' => "
            Activity Successfully Registered<br/>"));
        
        return true;
    }
    
    protected function getDB() {
        return wfGetDB( DB_MASTER );
    }
 
    public function getAllowedParams() {
        return array (
            'pecourse' => null,
            'peactivity' => null,
            'peurl' => null,
            'pecomment' => null,
            'peoptin' => null
        );
    }
 
    public function getParamDescription() {
        return array (
            'pecourse' => 'course identifier',
            'peactivity' => 'activity identifier',
            'peurl' => 'URL to submission',
            'pecomment' => 'Comment by author',
            'peoptin' => 'Peer Evaluation opt in flag'
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
