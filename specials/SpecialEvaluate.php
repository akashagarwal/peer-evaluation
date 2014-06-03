<?php
/**
 * HelloWorld SpecialPage for PeerEvaluation extension
 *
 * @file
 * @ingroup Extensions
 */

class SpecialEvaluate extends SpecialPage {
	public function __construct() {
		parent::__construct( 'Evaluate' );
	}

	/**
	 * Shows the page to the user.
	 * @param string $sub: The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	
	public function execute( $sub ) {
		global $wgOut,$wgRequest,$wgUser;
//		$out = $this->getOutput();
	
//		$out->setPageTitle("PeerEvaluation test page");

//		$wgOut->addHTML("PeerEvaluation test page");

		$id=$wgRequest->getInt('id',$default=0);
		$Activity_id=$wgRequest->getInt('Activity_id',$default=0);
		$dbr = wfGetDB( DB_SLAVE );

		if (!$wgUser->isLoggedIn())
		{
			$wgOut->addHTML("Please Login and return back to this page.");
			return;
		}

		if ( $id=="0" && !$wgRequest->wasPosted())
		{
			$wgOut->addHTML('<h1><a href="./Special:ViewActivities">Please click here and select an Activity to evaluate. </a> </h1> <br>');
			return;
		}

		else if ($Activity_id == 1 && !$wgRequest->wasPosted())
		{

			$activity = $dbr->select(
			'Activities',
			array( '*'),
			$conds = 'id='.$id,
			$fname = __METHOD__,
			$options = array( '' )
			);

			$activity=$activity->fetchObject();

//			$wgOut->addHTML($activity->Title);
			$wgOut->addHTML('<h1> Details of the Activity </h1> <br>');

			$wgOut->addHTML('<p> Title: '.$activity->Title.' </p> ');
			$wgOut->addHTML('<p> URL: <a>'.$activity->URL.' </a></p>');
			$wgOut->addHTML('<p> Comment: '.$activity->Comment.' </p> ');

			$wgOut->addHTML('<h1> Please fill your evaluation below:</h1> <br>');

			$form ='
				<form action="./Special:Evaluate" method="post">
				Is the content of the post related to Copyright_MCQ_e-learning_activity ?
				<input type="radio" name="Related" value="1">Yes
				<input type="radio" name="Related" value="0">No <br>
				<p>Comment (optional)</p>
				<textarea name="Related_comment" rows="4" cols="50">
				</textarea>
				<br>
				<h3> <a href="http://wikieducator.org/Peer_Evaluation/Copyright_MCQ_e-learning_activity#Assessment_items_and_rubric" target="_blank"> For the questions below please click here and answer carefully according to the rubrics provided. </a> </h3>
				Completeness
				<input type="radio" name="q1" value="1">Not achieved
				<input type="radio" name="q1" value="2">Achieved
				<input type="radio" name="q1" value="3">Merit <br>
				<p>Comment (optional)</p>
				<textarea name="q1_comment" rows="4" cols="50">
				</textarea>
				<br>
				Knowledge of copyright
				<input type="radio" name="q2" value="1">Not achieved
				<input type="radio" name="q2" value="2">Achieved
				<input type="radio" name="q2" value="3">Merit <br>
				<p>Comment (optional)</p>
				<textarea name="q2_comment" rows="4" cols="50">
				</textarea>
				<br>
				Learning utility of the questions
				<input type="radio" name="q3" value="1">Not achieved
				<input type="radio" name="q3" value="2">Achieved
				<input type="radio" name="q3" value="3">Merit <br>
				<p>Comment (optional)</p>
				<textarea name="q3_comment" rows="4" cols="50">
				</textarea>
				<br>

				<p>Any additional Comments</p>
				<textarea name="Other_comments" rows="4" cols="50">
				</textarea>
				<br>

				<input type="hidden" name="Activity_id"  value="1" />
				<input type="hidden" name="id"  value="'.$id.'" />
				<input type="hidden" name="LearnerId"  value="'.$activity->userId.'" />
				<input type="submit" value="Submit">
				</form>


			';
			$wgOut->addHTML($form);

		}

		else if ($Activity_id == 2 && !$wgRequest->wasPosted())
		{

			$activity = $dbr->select(
			'Activities',
			array( '*'),
			$conds = 'id='.$id,
			$fname = __METHOD__,
			$options = array( '' )
			);

			$activity=$activity->fetchObject();

//			$wgOut->addHTML($activity->Title);
			$wgOut->addHTML('<h1> Details of the Activity </h1> <br>');

			$wgOut->addHTML('<p> Title: '.$activity->Title.' </p> ');
			$wgOut->addHTML('<p> URL: <a>'.$activity->URL.' </a></p>');
			$wgOut->addHTML('<p> Comment: '.$activity->Comment.' </p> ');

			$wgOut->addHTML('<h1> Please fill your evaluation below:</h1> <br>');

			$form ='
				<form action="./Special:Evaluate" method="post">
				Is the content of the post related to Learning_reflection_example ?
				<input type="radio" name="Related" value="1">Yes
				<input type="radio" name="Related" value="0">No <br>
				<p>Comment (optional)</p>
				<textarea name="Related_comment" rows="4" cols="50">
				</textarea>
				<br>
				<h3> <a href="http://wikieducator.org/Peer_Evaluation/Learning_reflection_example#Suggested_rubric" target="_blank"> For the questions below please click here and answer carefully according to the rubrics provided. </a> </h3>
				Relevance
				<input type="radio" name="q1" value="1">No
				<input type="radio" name="q1" value="2">Somewhat
				<input type="radio" name="q1" value="3">Yes <br>
				<p>Comment (optional)</p>
				<textarea name="q1_comment" rows="4" cols="50">
				</textarea>
				<br>

				Connection with experience
				<input type="radio" name="q2" value="1">No
				<input type="radio" name="q2" value="2">Average
				<input type="radio" name="q2" value="3">Excellent <br>
				<p>Comment (optional)</p>
				<textarea name="q2_comment" rows="4" cols="50">
				</textarea>
				<br>

				Evidence of new learning
				<input type="radio" name="q3" value="1">No
				<input type="radio" name="q3" value="2">Average
				<input type="radio" name="q3" value="3">Excellent <br>
				<p>Comment (optional)</p>
				<textarea name="q3_comment" rows="4" cols="50">
				</textarea>
				<br>

				Overall Rating of the answer
				<input type="radio" name="q4" value="1">Unsatisfactory
				<input type="radio" name="q4" value="2">Acceptable
				<input type="radio" name="q4" value="3">Excellent <br>
				<p>Comment (optional)</p>
				<textarea name="q4_comment" rows="4" cols="50">
				</textarea>
				<br>


				<p>Any additional Comments</p>
				<textarea name="Other_comments" rows="4" cols="50">
				</textarea>
				<br>

				<input type="hidden" name="Activity_id"  value="1" />
				<input type="hidden" name="id"  value="'.$id.'" />
				<input type="hidden" name="LearnerId"  value="'.$activity->userId.'" />
				<input type="submit" value="Submit">
				</form>


			';
			$wgOut->addHTML($form);

		}

	}
}
