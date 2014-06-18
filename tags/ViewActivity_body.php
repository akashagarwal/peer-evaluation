<?php
class TagViewActivity {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'viewactivities', array( __CLASS__, 'viewactivitiesRender' ) );

		return true;
	}



	static function viewactivitiesRender( $input, array $args, Parser $parser ) {

		function getActivities($id)
		{
			$dbr = wfGetDB( DB_SLAVE );

			$ret='<div id="c'.$id.'" style="display:none">';

			$res = $dbr->select(
					'pe_Activities',
					array( '*'),
					$conds = 'Activity_id='.$id.' and OptedIn=1 ',
					$fname = __METHOD__,
					$options = array( 'ORDER BY' => 'EvalNum ASC' )
					);


			$table='
				<table border="1" >
				<tr>
				<td>Title</td>
				<td>Submitted by</td>
				<td>URL</td>      
				<td>Comment</td>
				<td>Opted in for Evaluation</td>
				<td>Number of Evaluations</td>
				<td>Submission Time</td>
				</tr>
				';

			$ret.=" <h1> Click on the title of an Activity to Evaluate it </h1> <br>";
			$ret.=$table;
			foreach ( $res as $row ) {
				$user = $dbr->select(
						'user',
						array( '*'),
						$conds = 'user_id='.$row->userId,
						$fname = __METHOD__,
						$options = array('')
						);

				$user=$user->fetchObject();
				$table='
					<tr>
					<td> <a href="./Special:Evaluate?id='.$row->id.'&Activity_id=1"> '.$row->Title.' </a> </td>
					<td> <a href="./User:'.$user->user_name.'">'. $user->user_name .' </a></td>
					<td>'.$row->URL.'</td>        
					<td>'.$row->Comment.'</td>
					<td>'.($row->OptedIn ? "Yes" :"No").'</td>
					<td>'.$row->EvalNum.'</td>
					<td>'.$row->Timestamp.'</td>
					</tr>
					';
				$ret.=$table;

			}
			$ret.="</table>";

			$res = $dbr->select(
					'pe_Activities',
					array( '*'),
					$conds = 'Activity_id='.$id.' and OptedIn=0',
					$fname = __METHOD__,
					$options = array( 'ORDER BY' => 'Timestamp DESC' )
					);


			$table='
				<table border="1" >
				<tr>
				<td>Title</td>
				<td>Submitted by</td>
				<td>URL</td>      
				<td>Comment</td>
				<td>Opted in for Evaluation</td>
				<td>Number of Evaluations</td>
				<td>Submission Time</td>
				</tr>
				';

			$ret.=" <h1> Activities not available for evaluation </h1> <br>";
			$ret.=$table;
			foreach ( $res as $row ) {
				$user = $dbr->select(
						'user',
						array( '*'),
						$conds = 'user_id='.$row->userId,
						$fname = __METHOD__,
						$options = array('')
						);

				$user=$user->fetchObject();
				$table='
					<tr>
					<td> '.$row->Title.'  </td>
					<td> <a href="./User:'.$user->user_name.'">'. $user->user_name .' </a></td>
					<td>'.$row->URL.'</td>        
					<td>'.$row->Comment.'</td>
					<td>'.($row->OptedIn ? "Yes" :"No").'</td>
					<td>'.$row->EvalNum.'</td>
					<td>'.$row->Timestamp.'</td>
					</tr>
					';
				$ret.=$table;

			}
			$ret.="</table>";
			$ret.="</div>";
			return $ret;
		}



		$ret='<script src="/extensions/PeerEvaluation/resources/viewactivity.js"></script>';
        global $wgTitle;

        $ret.="You might be viewing an older cached version of this page. Click <a href='".$wgTitle->getFullURL()."?action=purge'>here</a> to refresh<br>";

		$ret .='<button type="button" id="1b" onclick="myFunction1()">1st Learning reflection</button> <button type="button" id="2b" onclick="myFunction2()">2nd Learning reflection</button> <button type="button" id="3b" onclick="myFunction3()">Activity 3.1</button> <button type="button" id="4b" onclick="myFunction4()">Activity 4.1</button> <button type="button" id="5b" onclick="myFunction5()">3rd Learning reflection</button>
			<div id="t1content">
			</div>';

		for ( $i=1;$i<=5;$i++ ) {
			$ret.=getActivities($i);
		}
		return $ret;
	}


}
?>

