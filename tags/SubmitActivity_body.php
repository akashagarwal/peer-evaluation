<?php
class TagSubmitActivity {
		static function onParserInit( Parser $parser ) {
				$parser->setHook( 'submitactivity', array( __CLASS__, 'submitactivityRender' ) );
				return true;
		}
		static function submitactivityRender( $input, array $args, Parser $parser ) {
			$ret='<script src="/extensions/PeerEvaluation/resources/submitactivity.js"></script>';

			$activityPage=$args['activity'];

			$ret.="<span id='errors'></span><div id='t1content'>";
/*
			$title = Title::newFromText( ':' . $activityPage );
			$revision = Revision::newFromTitle ( $title );

			if ( $revision == null )
			  return "Page does not exist";
			$text = $revision->getText( Revision::FOR_PUBLIC );            

			$ret.=$text;
*/
			$ret .=' <div id="form">';

			$ret.='
				</select>
				URL of the blog post : <input type="text" name="URL" id="url" onblur="urlFunction()">
				<b id="urlerror" > </b>
				Title : <input type="text" name="Title" id="title">
				Comment : <input type="text" name="Comment" id="comment">
				<input type="checkbox" name="OptedIn" value="true" id="optin"> Opt in for Peer Evaluation
				<input type="hidden" id="activityPage" name="activityPage" value="'.$activityPage.'">
				<input type="submit" value="Submit" onclick="submit()">
				</div>
				</div>';
			return $ret;
		}


}
?>

