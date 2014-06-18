<?php
class TagViewActivity {
        static function onParserInit( Parser $parser ) {
                $parser->setHook( 'viewactivities', array( __CLASS__, 'viewactivitiesRender' ) );
                global $wgOut   ;
//		$wgOut->addStyle('/core/extensions/PeerEvaluation/resources/poll.css');

                return true;
        }

        static function viewactivitiesRender( $input, array $args, Parser $parser ) {
            $ret='<script src="/extensions/PeerEvaluation/resources/viewactivity.js"></script>';

            $ret .='<button type="button" id="1b">1st Learning reflection</button> <button type="button" id="2b">2nd Learning reflection</button> <button type="button" id="3b">Activity 3.1</button> <button type="button" id="4b">Activity 4.1</button> <button type="button" id="5b">3rd Learning reflection</button>
<div id="t1content">
</div>';
            return $ret;
        }


}
?>

