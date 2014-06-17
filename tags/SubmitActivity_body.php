<?php
class TagSubmitActivity {
        static function onParserInit( Parser $parser ) {
                $parser->setHook( 'submitactivity', array( __CLASS__, 'submitactivityRender' ) );
                global $wgOut   ;
        		$wgOut->addScriptFile('/extensions/PeerEvaluation/resources/submitactivity.js');
//		$wgOut->addStyle('/core/extensions/PeerEvaluation/resources/poll.css');

                return true;
        }
        static function submitactivityRender( $input, array $args, Parser $parser ) {
            $ret="<div id='t1content'>";
            global $wgOut,$wgRequest;
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


            $ret .=' <div id="form">
                Activity : <select name="Activity_id" id="activityid">
                <option value="1">Copyright_MCQ_e-learning_activity</option>
                <option value="2">Learning_reflection</option> 
                </select>
                URL of the blog : <input type="text" name="URL" id="url" onblur="urlFunction()">
                <b id="urlerror" > </b>
                Title : <input type="text" name="Title" id="title">
                Comment : <input type="text" name="Comment" id="comment">
                <input type="checkbox" name="OptedIn" value="true" id="optin"> Opt in for Evaluation
                <input type="submit" value="Submit" onclick="submit()">
                </div>
                </div>';
            return $ret;
        }


}
?>

