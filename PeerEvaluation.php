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





/* Configuration */

// Enable Foo
#$wgPeerEvaluationEnableFoo = true;
