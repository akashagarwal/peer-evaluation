<?php
/**
 * PeerEvaluation extension.
 *
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 *
 * @file
 * @ingroup Extensions
 * @author Akash Agarwal, 2014
 * @license GNU General Public Licence 2.0 or later
 */

$wgExtensionCredits['other'][] = array(
        'path' => __FILE__,
        'name' => 'PeerEvaluation',
        'author' => array(
                '[http://wikieducator.org/User:Akash_Agarwal Akash Agarwal]',
        ),  
        'version'  => '0.1 Alpha',
        'url' => 'http://wikieducator.org/Extension:PeerEvaluation',
        'description' => 'Prototype extension for PeerEvaluation',

);

/* Setup */

// Register files
$wgAutoloadClasses['PeerEvaluationHooks'] = dirname(__FILE__). '/PeerEvaluation.hooks.php';

$wgAutoloadClasses['SpecialHelloWorld'] = dirname(__FILE__). '/specials/SpecialHelloWorld.php';
$wgAutoloadClasses['SpecialSubmitActivity'] = dirname(__FILE__). '/specials/SpecialSubmitActivity.php';
$wgAutoloadClasses['SpecialEvaluate'] = dirname(__FILE__). '/specials/SpecialEvaluate.php';
$wgAutoloadClasses['SpecialViewActivities'] = dirname(__FILE__). '/specials/SpecialViewActivities.php';
$wgAutoloadClasses['SpecialViewAllEvaluations'] = dirname(__FILE__). '/specials/SpecialViewAllEvaluations.php';


$wgMessagesDirs['PeerEvaluation'] = dirname(__FILE__). '/i18n';
$wgExtensionMessagesFiles['PeerEvaluationAlias'] = dirname(__FILE__). '/PeerEvaluation.i18n.alias.php';

// Register hooks
#$wgHooks['NameOfHook'][] = 'PeerEvaluationHooks::onNameOfHook';

// Register special pages
$wgSpecialPages['HelloWorld'] = 'SpecialHelloWorld';
$wgSpecialPageGroups['HelloWorld'] = 'other';

$wgSpecialPages['SubmitActivity'] ='SpecialSubmitActivity' ;
$wgSpecialPageGroups['SubmitActivity'] = 'OCL4Ed-PeerEvaluation';

$wgSpecialPages['ViewActivities'] ='SpecialViewActivities' ;
$wgSpecialPageGroups['ViewActivities'] = 'OCL4Ed-PeerEvaluation';

$wgSpecialPages['Evaluate'] ='SpecialEvaluate' ;
$wgSpecialPageGroups['Evaluate'] = 'OCL4Ed-PeerEvaluation';

$wgSpecialPages['ViewAllEvaluations'] ='SpecialViewAllEvaluations' ;
$wgSpecialPageGroups['ViewAllEvaluations'] = 'OCL4Ed-PeerEvaluation';


// Register modules
$wgResourceModules['ext.PeerEvaluation.foo'] = array(
	'scripts' => array(
		'modules/ext.PeerEvaluation.foo.js',
	),
	'styles' => array(
		'modules/ext.PeerEvaluation.foo.css',
	),
	'messages' => array(
	),
	'dependencies' => array(
	),     

	'localBasePath' => dirname(__FILE__),
);

// Sample tag extension
$wgAutoloadClasses['Poll'] = dirname(__FILE__). '/poll_body.php';
$wgHooks['ParserFirstCallInit'][] = 'Poll::onParserInit';

$wgAutoloadClasses['TagSubmitActivity'] = dirname(__FILE__). '/tags/SubmitActivity_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagSubmitActivity::onParserInit';


//API's 

$wgAutoloadClasses['apiSubmitActivity'] = dirname(__FILE__). '/apis/SubmitActivity.php';
$wgAPIModules['apiSubmitActivity'] = 'apiSubmitActivity';


class ApiSample extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {

        global $wgUser;
        $user = $wgUser->getId();
        $params = $this->extractRequestParams();

	$face=$params['face'];
	$emoticon = ';)';
        $result = $this->getResult();
        // Other responses depend on the value of the face parameter
        switch ( $face ) {
            case 'O_o':
                $emoticon = 'o_O';
                break;
            case 'o_O':
                $emoticon = 'O_o';
                break;
        }
        // Top level
        $this->getResult()->addValue( null, $this->getModuleName(), array ( 'apiresponses' => 'are as follows' ) );

        // Deliver a facial expression in reply
        $this->getResult()->addValue( null, $this->getModuleName()
            , array ( 'nonverbalresponse' => array ( 'emoticon' => $emoticon ) ) );
        // Offer a few predictions about the user's future
        $this->getResult()->addValue( null, $this->getModuleName()
            , array ( 'predictions' => array (
                'yourweek' => 'You will learn something interesting and useful about API extensions '
                    .'this week' ,
                'yourlife' => 'You will become a successful MediaWiki hacker, which will serve you well '
                    .'in your life' ,
                'eternity' => 'Eventually all life will be destroyed in the heat death' ) ) );

        return true;
    }
 
    protected function getDB() {
        return wfGetDB( DB_MASTER );
    }
 
    public function getAllowedParams() {
        return array (
            'll' => null,
            'vote' => null
        );
    }
 
    public function getParamDescription() {
        return array (
            'll' => 'SHA1 hash of poll question',
            'vote' => "pairs of question:vote separated by '|'"
        );
    }
 
    public function getDescription() {
        return 'Poll voting and statistics defined by WEPoll';
    }
 
    protected function getExamples() {
        return array (
            'api.php?action=wepoll&poll=1234ab&povote=0:1|1:2',
            'api.php?action=wepoll&poll=1234ab'
        );
    }
 
    public function getVersion() {
        return __CLASS__ . ': 0';
    }
}



/* Configuration */

// Enable Foo
#$wgPeerEvaluationEnableFoo = true;
