<?php
class Poll {
        static function onParserInit( Parser $parser ) {
                $parser->setHook( 'poll', array( __CLASS__, 'pollRender' ) );
                global $wgOut;
//                $wgOut->addModules( 'ext.poll' );
//		$wgOut->addScriptFile('/extensions/PeerEvaluation/resources/jquery.min.js');
		$wgOut->addScriptFile('/core/extensions/PeerEvaluation/resources/poll.js');
		$wgOut->addStyle('/core/extensions/PeerEvaluation/resources/poll.css');
                return true;
        }
        static function pollRender( $input, array $args, Parser $parser ) {
                global $wgUser;
//		$ret = '<script type="text/javascript" src="/extensions/PeerEvaluation/resources/jquery.min.js"></script>';
//		$ret .= '<script type="text/javascript" src="/extensions/PeerEvaluation/resources/poll.js"></script>';
                $ret = $wgUser->getName ().'<br>';
                $ret .= '<div id="thetable">';
                $ret .= '<table class="wtable">';
                $ret .= '<tr>';
                $ret .= '<td>Feedback</td>';
                $ret .= '<td><input id="inp001" type="text" /></td>';
                $ret .= '</tr>';
                $ret .= '<tr>';
                $ret .= '<td>Previous feedback given on:</td>';
                $ret .= '<td>(never)</td>';
                $ret .= '</tr>';
                $ret .= '<tr>';
                $ret .= '<td>Clear history</td>';
                $ret .= '<td><input id="chk001" type="checkbox" /></td>';
                $ret .= '</tr>';
                $ret .= '<tr>';
             //   $ret .= '<td align="center" colspan=2><input id="btn001" type="button"  value="Submit"></td>';
                $ret .= '<td align="center" colspan=2><input id="btn001" type="button" onclick="change()"  value="Submit"></td>';
                $ret .= '</tr>';

                $ret .= '</table>';
                $ret .= '</div>';
        //      $ret .= '<input type="submit"/>';
                return $ret;
        }
        public static function submitVote( $feedback, $clear ) {
                wfErrorLog( "submitVote() called text=" . $feedback . " clear=" . $clear . "\n",
                            '/tmp/poll001.log' );
                return true;
}

}
?>

