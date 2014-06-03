<?php
/**
 * HelloWorld SpecialPage for PeerEvaluation extension
 *
 * @file
 * @ingroup Extensions
 */

class SpecialViewActivities extends SpecialPage {
	public function __construct() {
		parent::__construct( 'ViewActivities' );
	}

	/**
	 * Shows the page to the user.
	 * @param string $sub: The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].
	 */
	
	public function execute( $sub ) {
		global $wgOut,$wgRequest;
//		$out = $this->getOutput();
	
//		$out->setPageTitle("PeerEvaluation test page");

//		$wgOut->addHTML("PeerEvaluation test page");

		$id=$wgRequest->getInt('id',$default=0);
		$sort=$wgRequest->getInt('sort',$default=0);

		if ( $id=="0")
		{
			$wgOut->addHTML('<a href="./Special:ViewActivities?id=1">Copyright_MCQ_e-learning_activity</a> <br>');
			$wgOut->addHTML('<a href="./Special:ViewActivities?id=2">Learning_reflection</a>');
		}
		$dbr = wfGetDB( DB_SLAVE );


		if ($id == 1)
		{

			if ($sort==0)
			{
				$wgOut->addHTML('<a href="./Special:ViewActivities?id=1&sort=1">Sort by time</a> <br>');
				$res = $dbr->select(
				'Activities',
				array( '*'),
				$conds = 'Activity_id=1',
				$fname = __METHOD__,
				$options = array( 'ORDER BY' => 'EvalNum ASC' )
				);
			}

			if ($sort==1)
			{
				$wgOut->addHTML('<a href="./Special:ViewActivities?id=1">Sort by number of Evaluations</a> <br>');
				$res = $dbr->select(
				'Activities',
				array( '*'),
				$conds = 'Activity_id=1',
				$fname = __METHOD__,
				$options = array( 'ORDER BY' => 'Timestamp DESC' )
				);
			}
			
			$table='
				<table border="1" >
				<tr>
				  <td>Title</td>
				  <td>URL</td>		
				  <td>Comment</td>
				  <td>Opted in for Evaluation</td>
				  <td>Number of Evaluations</td>
				  <td>Submission Time</td>
				</tr>
				';

			$wgOut->addHTML(" <h1> Click on the title of an Activity to Evaluate it </h1> <br>");
			$wgOut->addHTML($table);
			foreach( $res as $row ) 
			{
			$table='
				<tr>
				  <td>'.$row->Title.'</td>
				  <td>'.$row->URL.'</td>		
				  <td>'.$row->Comment.'</td>
				  <td>'.($row->OptedIn ? "Yes" :"No").'</td>
				  <td>'.$row->EvalNum.'</td>
				  <td>'.$row->Timestamp.'</td>
				</tr>
				';
			$wgOut->addHTML($table);

			}
			$wgOut->addHTML("</table>");

		}

		if ($id == 2)
		{
			if ($sort==0)
			{
				$wgOut->addHTML('<a href="./Special:ViewActivities?id=2&sort=1">Sort by time</a> <br>');
				$res = $dbr->select(
				'Activities',
				array( '*'),
				$conds = 'Activity_id=2',
				$fname = __METHOD__,
				$options = array( 'ORDER BY' => 'EvalNum ASC' )
				);
			}

			if ($sort==1)
			{
				$wgOut->addHTML('<a href="./Special:ViewActivities?id=2">Sort by number of Evaluations</a> <br>');
				$res = $dbr->select(
				'Activities',
				array( '*'),
				$conds = 'Activity_id=2',
				$fname = __METHOD__,
				$options = array( 'ORDER BY' => 'Timestamp DESC' )
				);
			}
			
			$table='
				<table border="1" >
				<tr>
				  <td>Title</td>
				  <td>URL</td>		
				  <td>Comment</td>
				  <td>Opted in for Evaluation</td>
				  <td>Number of Evaluations</td>
				  <td>Submission Time</td>
				</tr>
				';

			$wgOut->addHTML(" <h1> Click on the title of an Activity to Evaluate it </h1> <br>");
			$wgOut->addHTML($table);
			foreach( $res as $row ) 
			{
			$table='
				<tr>
				  <td>'.$row->Title.'</td>
				  <td>'.$row->URL.'</td>		
				  <td>'.$row->Comment.'</td>
				  <td>'.($row->OptedIn ? "Yes" :"No").'</td>
				  <td>'.$row->EvalNum.'</td>
				  <td>'.$row->Timestamp.'</td>
				</tr>
				';
			$wgOut->addHTML($table);

			}
			$wgOut->addHTML("</table>");
			
		}

	}
}
