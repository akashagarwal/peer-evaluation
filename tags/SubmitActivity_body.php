<?php
class TagSubmitActivity {
        static function onParserInit( Parser $parser ) {
                $parser->setHook( 'submitactivity', array( __CLASS__, 'submitactivityRender' ) );
//		$wgOut->addStyle('/core/extensions/PeerEvaluation/resources/poll.css');

                return true;
        }
        static function submitactivityRender( $input, array $args, Parser $parser ) {
            $ret='<script src="/core/extensions/PeerEvaluation/resources/submitactivity.js"></script>';

            $ret.="<div id='t1content'>";
 //           global $wgOut,$wgRequest;
//      $out = $this->getOutput();
    
//      $out->setPageTitle("PeerEvaluation test page");

//      $wgOut->addHTML("PeerEvaluation test page");
/*
            if (!$wgUser->isLoggedIn())
            {
                $ret.="Please Login and return back to this page.";
                return $ret;
            }
            $username=$wgUser->getName();
            

            $userCoursePage="User:".$username."/OCL4Ed 14.02";
            $title=Title::newFromText($userCoursePage);

            if (!$title->exists())
            {
                $ret+="You are not registered for OCL4eD. Please register and come back to this page.";
                return $ret;
            }
  */          
                
    //      $wgOut->addHTML("Success: Your course page is it ".$title->getFullURL());
 //               <option value="1">Copyright_MCQ_e-learning_activity</option>
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

