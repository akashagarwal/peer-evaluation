<?php
class TagSubmitActivity {
        static function onParserInit( Parser $parser ) {
                $parser->setHook( 'submitactivity', array( __CLASS__, 'submitactivityRender' ) );
                return true;
        }
        static function submitactivityRender( $input, array $args, Parser $parser ) {
            $ret='<script src="/extensions/PeerEvaluation/resources/submitactivity.js"></script>';

            $ret.="<div id='t1content'>";

//             <option value="1">Copyright_MCQ_e-learning_activity</option>
//             <option value="2">Learning_reflection</option> 


            $ret .=' <div id="form">
                Activity : <select name="Activity_id" id="activityid">
                <option value="1">1st Learning reflection</option>
                <option value="2">2nd Learning reflection</option>   
                <option value="3">Activity 3.1</option>   
                <option value="4">Activity 4.1</option>   
                <option value="5">3rd Learning reflection</option>   
                </select>
                URL of the blog post : <input type="text" name="URL" id="url" onblur="urlFunction()">
                <b id="urlerror" > </b>
                Title : <input type="text" name="Title" id="title">
                Comment : <input type="text" name="Comment" id="comment">
                <input type="checkbox" name="OptedIn" value="true" id="optin"> Opt in for Peer Evaluation
                <input type="submit" value="Submit" onclick="submit()">
                </div>
                </div>';
            return $ret;
        }


}
?>

