<?php
/**
 * PeerEvaluation extension - the thing that needs you.
 *
 * For more info see http://mediawiki.org/wiki/Extension:PeerEvaluation
 *
 * @file
 * @ingroup Extensions
 * @author John Doe, 2014
 * @license GNU General Public Licence 2.0 or later
 */

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'PeerEvaluation',
	'author' => array(
		'Akash Agarwal',
	),
	'version'  => 'Alpha',
	'url' => 'https://www.wikieducator.org/Extension:PeerEvaluation',
	'descriptionmsg' => 'Prototype extension for PeerEvaluation',
);

/* Setup */

// Register files
$wgAutoloadClasses['PeerEvaluationHooks'] = dirname(__FILE__). '/PeerEvaluation.hooks.php';
$wgAutoloadClasses['SpecialHelloWorld'] = dirname(__FILE__). '/specials/SpecialHelloWorld.php';
$wgAutoloadClasses['SpecialSubmitActivity'] = dirname(__FILE__). '/specials/SpecialSubmitActivity.php';

$wgAutoloadClasses['SpecialViewActivities'] = dirname(__FILE__). '/specials/SpecialViewActivities.php';
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


/* Configuration */

// Enable Foo
#$wgPeerEvaluationEnableFoo = true;