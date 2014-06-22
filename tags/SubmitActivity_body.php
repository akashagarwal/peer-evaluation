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
                Activity : <select name="Activity_id" id="activityid">';
            $dbr = wfGetDB( DB_SLAVE );
            $activity_cd= $dbr->select(
                'pe_cd_Activities',
                array( '*'),
                $conds = '',
                $fname = __METHOD__,
                $options = array( '' )
            );

            foreach ($activity_cd as $row) {
                $ret.= '<option value="'.$row->id.'">'.$row->title.'</option>';
            }

            $ret.='
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

