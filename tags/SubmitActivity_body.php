<?php
/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

class TagSubmitActivity {
		static function onParserInit( Parser $parser ) {
				$parser->setHook( 'submitactivity', array( __CLASS__, 'submitactivityRender' ) );
				return true;
		}
		static function submitactivityRender( $input, array $args, Parser $parser ) {
			$ret='<script src="/extensions/PeerEvaluation/resources/submitactivity.js"></script>';

			$activityPage=$args['activity'];

			$ret.="<span id='pesaerrors'></span><div id='pesat1content'>";
/*
			$title = Title::newFromText( ':' . $activityPage );
			$revision = Revision::newFromTitle ( $title );

			if ( $revision == null )
			  return "Page does not exist";
			$text = $revision->getText( Revision::FOR_PUBLIC );            

			$ret.=$text;
*/
			$ret .=' <div id="pesaform">';

			$ret.='
</select>
<label for="pesaurl"><b>URL</b> of the submission ( Blog post/Wiki Page ) : </label><input type="text" name="pesaURL" id="pesaurl" size="50"><br/><br/>
<b id="pesaurlerror" > </b>
<b id="pesaurlerror2" ></b>
<label for="pesatitle"> <b>Title</b> : </label><input type="text" name="pesaTitle" id="pesatitle" size="50"><br/><br/>
<b id="pesatitleerror" ></b>
<label for="pesacomment">Comment : </label><textarea name="pesaComment" id="pesacomment"></textarea><br/>
<input type="checkbox" name="pesaOptedIn" value="true" id="pesaoptin"> <label for="pesaoptin"><b>Opt in</b> for Peer Evaluation</label><br/><br/>
<input type="hidden" id="pesaactivityPage" name="pesaactivityPage" value="'.$activityPage.'">
<input type="submit" value="Submit" id="pesa_submit" >
</div>
</div>';
			return $ret;
		}

}
?>

