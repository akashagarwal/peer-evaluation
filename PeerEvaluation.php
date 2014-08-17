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
        'version'  => '0.2',
        'url' => 'http://wikieducator.org/Extension:PeerEvaluation',
        'description' => 'Prototype extension for PeerEvaluation',
);

// Tags

$wgAutoloadClasses['TagSubmitActivity'] = dirname( __FILE__ ) . '/tags/SubmitActivity_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagSubmitActivity::onParserInit';

$wgAutoloadClasses['TagViewEvaluations'] = dirname( __FILE__ ) . '/tags/ViewEvaluations_body.php';
$wgHooks['ParserFirstCallInit'][] = 'TagViewEvaluations::onParserInit';

$wgAutoloadClasses['Evaluation'] = dirname( __FILE__ ) . '/tags/Evaluation.php';
$wgHooks['ParserFirstCallInit'][] = 'Evaluation::onParserInit';

// API's


$wgAutoloadClasses['pesubmit'] = dirname( __FILE__ ) . '/apis/pesubmit.php';
$wgAPIModules['pesubmit'] = 'pesubmit';

$wgAutoloadClasses['evaluations'] = dirname( __FILE__ ) . '/apis/evaluations.php';
$wgAPIModules['evaluations'] = 'evaluations';

$wgAutoloadClasses['pevaluate'] = dirname( __FILE__ ) . '/apis/pevaluate.php';
$wgAPIModules['pevaluate'] = 'pevaluate';


/* Configuration */

// Enable Foo
# $wgPeerEvaluationEnableFoo = true;

$wgPeerEvaluationHomedirPath = "/extensions/PeerEvaluation";
