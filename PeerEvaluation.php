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

// Register files
$wgAutoloadClasses['PeerEvaluationHooks'] = dirname(__FILE__). '/PeerEvaluation.hooks.php';

$wgMessagesDirs['PeerEvaluation'] = dirname(__FILE__). '/i18n';
$wgExtensionMessagesFiles['PeerEvaluationAlias'] = dirname(__FILE__). '/PeerEvaluation.i18n.alias.php';

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

//Tags

$wgAutoloadClasses['TagSubmitActivity'] = dirname(__FILE__). '/tags/SubmitActivity_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagSubmitActivity::onParserInit';

$wgAutoloadClasses['TagViewActivity'] = dirname(__FILE__). '/tags/ViewActivity_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagViewActivity::onParserInit';

$wgAutoloadClasses['TagViewEvaluations'] = dirname(__FILE__). '/tags/ViewEvaluations_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagViewEvaluations::onParserInit';

$wgAutoloadClasses['TagUserDashboard'] = dirname(__FILE__). '/tags/UserDashboard_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagUserDashboard::onParserInit';

$wgAutoloadClasses['GenForm'] = dirname(__FILE__). '/tags/GenForm.php';
$wgHooks['ParserFirstCallInit'][] = 'GenForm::onParserInit';

//API's 

$wgAutoloadClasses['apiSubmitActivity'] = dirname(__FILE__). '/apis/SubmitActivity.php';
$wgAPIModules['apiSubmitActivity'] = 'apiSubmitActivity';

$wgAutoloadClasses['apiGetActivities'] = dirname(__FILE__). '/apis/GetActivities.php';
$wgAPIModules['apiGetActivities'] = 'apiGetActivities';

$wgAutoloadClasses['apiGetEvaluationForm'] = dirname(__FILE__). '/apis/GetEvaluationForm.php';
$wgAPIModules['apiGetEvaluationForm'] = 'apiGetEvaluationForm';

$wgAutoloadClasses['apiSubmitEvaluationForm'] = dirname(__FILE__). '/apis/SubmitEvaluationForm.php';
$wgAPIModules['apiSubmitEvaluationForm'] = 'apiSubmitEvaluationForm';

$wgAutoloadClasses['apiViewEvaluations'] = dirname(__FILE__). '/apis/ViewEvaluations.php';
$wgAPIModules['apiViewEvaluations'] = 'apiViewEvaluations';

$wgAutoloadClasses['apiUserDashboard'] = dirname(__FILE__). '/apis/UserDashboard.php';
$wgAPIModules['apiUserDashboard'] = 'apiUserDashboard';


$wgAutoloadClasses['pesubmit'] = dirname(__FILE__). '/apis/pesubmit.php';
$wgAPIModules['pesubmit'] = 'pesubmit';

/* Configuration */

// Enable Foo
#$wgPeerEvaluationEnableFoo = true;
