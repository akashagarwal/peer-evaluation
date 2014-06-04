<?php
/**
 * HelloWorld SpecialPage for PeerEvaluation extension
 *
 * @file
 * @ingroup Extensions
 */

class SpecialViewAllEvaluations extends SpecialPage {
	public function __construct() {
		parent::__construct( 'ViewAllEvaluations' );
	}

	/**
	 * Shows the page to the user.
	 * @param string $sub: The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	
	public function execute( $sub ) {
		global $wgOut,$wgUser;


		$wgOut->addHTML("<h1> Copyright_MCQ_e-learning_activity </h1>");
		

		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select(
		'pe_Evaluations_Activity1',
		array( '*'),
		$conds = '',
		$fname = __METHOD__,
		$options = array('')
		);

		$table='
			<table border="1" >
			<tr>
			  <td>Title</td>
			  <td>Submitted by</td>
			  <td>URL</td>		
			  <td>Comment</td>
			  <td>Evaluated by </td>
			  <td> Is_Related </td>
			  <td> q1</td>
			  <td> q1-Comment</td>
			  <td> q2</td>
			  <td> q2-Comment</td>
			  <td> q3</td>
			  <td> q3-Comment</td>
			  <td> Other Comments </td>
			</tr>
			';
		$wgOut->addHTML($table);
		foreach( $res as $row ) 
		{
			$learner = $dbr->select(
			'user',
			array( '*'),
			$conds = 'user_id='.$row->LearnerId,
			$fname = __METHOD__,
			$options = array('')
			);

			$learner=$learner->fetchObject();
			
			$evaluator = $dbr->select(
			'user',
			array( '*'),
			$conds = 'user_id='.$row->EvaluatorId,
			$fname = __METHOD__,
			$options = array('')
			);

			$evaluator=$evaluator->fetchObject();

			$act = $dbr->select(
			'pe_Activities',
			array( '*'),
			$conds = 'id='.$row->ActivityId,
			$fname = __METHOD__,
			$options = array( '' )
			);

			$act=$act->fetchObject();

			$table='<tr>';

			$table=$table.'
			  <td>  '.$act->Title.' </a> </td>
			  <td> <a href="./User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
			  <td>'.$act->URL.'</td>		
			  <td>'.$act->Comment.'</td>
  			  <td> <a href="./User:'.$evaluator->user_name.'">'. $evaluator->user_name .' </a></td>
			  <td>'.($row->Related ? "Yes" :"No").'</td>
			';

			switch($row->q1)
			{
				case 1:
					$table=$table.'<td>Not achieved</td>';
					break;
				case 2:
					$table=$table.'<td>Achieved</td>';
					break;
				case 3:
					$table=$table.'<td>Merit</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q1_comment.' </td>';
			switch($row->q2)
			{
				case 1:
					$table=$table.'<td>Not achieved</td>';
					break;
				case 2:
					$table=$table.'<td>Achieved</td>';
					break;
				case 3:
					$table=$table.'<td>Merit</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q2_comment.' </td>';
			switch($row->q3)
			{
				case 1:
					$table=$table.'<td>Not achieved</td>';
					break;
				case 2:
					$table=$table.'<td>Achieved</td>';
					break;
				case 3:
					$table=$table.'<td>Merit</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q3_comment.' </td>';

			$table=$table.'<td> '.$row->Other_Comments.' </td>';

			$table=$table.'</tr>';
			$wgOut->addHTML($table);
//			<td> <a href="./User:'.$user->user_name.'">'. $user->user_name .' </a></td>

		}
		$wgOut->addHTML('</table>');

		$wgOut->addHTML("<h1> Learning_reflection_example </h1>");

		$res = $dbr->select(
		'pe_Evaluations_Activity2',
		array( '*'),
		$conds = '',
		$fname = __METHOD__,
		$options = array('')
		);

		$table='
			<table border="1" >
			<tr>
			  <td>Title</td>
			  <td>Submitted by</td>
			  <td>URL</td>		
			  <td>Comment</td>
			  <td>Evaluated by </td>
			  <td> Is_Related </td>
			  <td> q1</td>
			  <td> q1-Comment</td>
			  <td> q2</td>
			  <td> q2-Comment</td>
			  <td> q3</td>
			  <td> q3-Comment</td>
			  <td> q4</td>
			  <td> q4-Comment</td>
			  <td> Other Comments </td>
			</tr>
			';
		$wgOut->addHTML($table);
		foreach( $res as $row ) 
		{
			$learner = $dbr->select(
			'user',
			array( '*'),
			$conds = 'user_id='.$row->LearnerId,
			$fname = __METHOD__,
			$options = array('')
			);

			$learner=$learner->fetchObject();
			
			$evaluator = $dbr->select(
			'user',
			array( '*'),
			$conds = 'user_id='.$row->EvaluatorId,
			$fname = __METHOD__,
			$options = array('')
			);

			$evaluator=$evaluator->fetchObject();

			$act = $dbr->select(
			'pe_Activities',
			array( '*'),
			$conds = 'id='.$row->ActivityId,
			$fname = __METHOD__,
			$options = array( '' )
			);

			$act=$act->fetchObject();

			$table='<tr>';

			$table=$table.'
			  <td>  '.$act->Title.' </a> </td>
			  <td> <a href="./User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
			  <td>'.$act->URL.'</td>		
			  <td>'.$act->Comment.'</td>
  			  <td> <a href="./User:'.$evaluator->user_name.'">'. $evaluator->user_name .' </a></td>
			  <td>'.($row->Related ? "Yes" :"No").'</td>
			';

			switch($row->q1)
			{
				case 1:
					$table=$table.'<td>No</td>';
					break;
				case 2:
					$table=$table.'<td>Somewhat</td>';
					break;
				case 3:
					$table=$table.'<td>Yes</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q1_comment.' </td>';
			switch($row->q2)
			{
				case 1:
					$table=$table.'<td>No</td>';
					break;
				case 2:
					$table=$table.'<td>Average</td>';
					break;
				case 3:
					$table=$table.'<td>Excellent</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q2_comment.' </td>';
			switch($row->q3)
			{
				case 1:
					$table=$table.'<td>No</td>';
					break;
				case 2:
					$table=$table.'<td>Average</td>';
					break;
				case 3:
					$table=$table.'<td>Excellent</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q3_comment.' </td>';
			switch($row->q4)
			{
				case 1:
					$table=$table.'<td>Unsatisfactory</td>';
					break;
				case 2:
					$table=$table.'<td>Acceptable</td>';
					break;
				case 3:
					$table=$table.'<td>Excellent</td>';
					break;
				default:
					$table=$table.'<td> No answer </td>';
			}
			$table=$table.'<td> '.$row->q4_comment.' </td>';

			$table=$table.'<td> '.$row->Other_Comments.' </td>';

			$table=$table.'</tr>';
			$wgOut->addHTML($table);
//			<td> <a href="./User:'.$user->user_name.'">'. $user->user_name .' </a></td>

		}
		$wgOut->addHTML('</table>');


	}
}
