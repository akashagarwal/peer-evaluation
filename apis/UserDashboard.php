<?php

class apiUserDashboard extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {


        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;
        
        if (!$wgUser->isLoggedIn()) {
            $this->dieUsage('must be logged in',
                'notloggedin');
        };
        $data='';

        $params = $this->extractRequestParams();

        $result = $this->getResult();

        $dbr=$this->getrDB();
        $conditionRecom='';

        $data.='<h2> Your Activity submissions </h2>';
        $ret='';
        $res = $dbr->select(
            'pe_Activities',
            array( '*'),
            $conds = 'userId='.$wgUser->getId(),
            $fname = __METHOD__,
            $options = array( 'ORDER BY' => 'EvalNum ASC' )
        );

        if ($res->numRows())
            $data.='<b> We encourage learners to evaluate their own work. Click on the blue title links below to submit your self-evaluation. </b>';
        else
            $data.='<b> You have not submitted any activites yet.</b><br>';

        $table='
            <table border="1" class="prettytable sortable" >
            <tr>
            <td>Activity</td>
            <td>Title</td>
            <td>URL</td>      
            <td>Comment</td>
            <td>Opted in for Evaluation</td>
            <td>Number of Evaluations</td>
            <td>Submission Time</td>
            </tr>
            ';

        if ($res->numRows())
            $ret.=$table;
        foreach ( $res as $row ) {

            $conditionRecom.=' id!='.$row->id .' and ';

            $activity_cd= $dbr->select(
                'pe_cd_Activities',
                array( '*'),
                $conds = 'id='.$row->Activity_id,
                $fname = __METHOD__,
                $options = array( '' )
            );
            $activity_cd=$activity_cd->fetchObject();


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
                <td>'.$activity_cd->title.'</td>'.
                ($activity_cd->pe_flag ? '<td class="title" id="'.$row->id.'">  <a>'.$row->Title.' </a> </td>' : '<td id="'.$row->id.'"> '.$row->Title.' </td>')
                .' <td> <a href="'.$row->URL.'" target="_blank"> '.$row->URL.'</a> </td>         
                <td>'.$row->Comment.'</td>
                <td>'.($row->OptedIn ? "Yes" :"No").'</td>
                <td>'.$row->EvalNum.'</td>
                <td>'.$row->Timestamp.'</td>
                </tr>
                ';
            $ret.=$table;

        }
        if ($res->numRows()) {
            $ret.="</table>";
            $data.=$ret;
        }

        $data.='<h2>Evaluations of your activities</h2>';
        $evals=$dbr->select(
            'pe_eval_main',
            array( '*'),
            $conds = 'LearnerId='.$wgUser->getId(),
            $fname = __METHOD__,
            $options = array( '' )
        );


        if ($evals->numRows()) {
            $data.='
            <table border="1" class="prettytable sortable "  >
            <tr>
              <td>Activity</td>
              <td>Title</td>
              <td>Submitted by</td>
              <td>URL</td>      
              <td>Comment</td>
              <td>Evaluated by</td>
              <td>Related</td>
              <td>Score</td>
            </tr>
            ';
            $data.='<b>Click on a title for more details</b><br>';
        }
        else 
            $data.='<b> No evaluations on your activities yet. Kindly check again later</b>';

        foreach ($evals as $row) {

            $learner = $dbr->select(
                'user',
                array( '*'),
                $conds = 'user_id='.$row->LearnerId,
                $fname = __METHOD__,
                $options = array('')
                );
            $learner=$learner->fetchObject();

            $evaluater = $dbr->select(
                'user',
                array( '*'),
                $conds = 'user_id='.$row->EvaluaterId,
                $fname = __METHOD__,
                $options = array('')
            );
            $evaluater=$evaluater->fetchObject();

            $activity= $dbr->select(
                'pe_Activities',
                array( '*'),
                $conds = 'id='.$row->ActivityId,
                $fname = __METHOD__,
                $options = array( '' )
            );

            $activity=$activity->fetchObject();

            $activity_cd= $dbr->select(
                'pe_cd_Activities',
                array( '*'),
                $conds = 'id='.$activity->Activity_id,
                $fname = __METHOD__,
                $options = array( '' )
            );

            $activity_cd=$activity_cd->fetchObject();


            $data.= '<tr>
              <td> '. $activity_cd->title .' </td>
              <td>  <a class="evaltitle" name='.$row->id.'>'.$activity->Title.' </a> </td>
              <td> <a href="/User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
              <td> <a href="'.$activity->URL.'" target="_blank">'.$activity->URL.'</a></td>        
              <td>'.$activity->Comment.'</td>
              <td> <a href="/User:'.$evaluater->user_name.'">'. $evaluater->user_name .' </a></td>
              <td>'.($row->Related ? "Yes" :"No").'</td>
              <td>'.$row->Score.'</td>
              </tr>
            ';
            if (!$row->Related) {
                $data.='<span style="display:none" id='.$row->id.'> <b>Evaluator said that the post is not related so the Evaluation did not continue </b><br>';
                if ( $row->Related_comment != ' ') {
                    $data.= '<b> Comment : </b> ' . $row->Related_comment; 
                }
                $data.='</span>';
                continue;
            }
            $data.='<span style="display:none" id='.$row->id.'>';

            if ( $row->Related_comment != ' ') {
                $data.= '<b> Overall Comment : </b> <br>' . $row->Related_comment . '<br><br>'; 
            }
            if ( $activity_cd->type == 1) {
                $questions = $dbr->select(
                    'pe_questions_mcq',
                    array( '*'),
                    $conds = 'activity_id='.$activity->Activity_id,
                    $fname = __METHOD__,
                    $options = array( '' )
                );
            }
            if ( $activity_cd->type == 2) {
                $questions = $dbr->select(
                    'pe_questions_10point',
                    array( '*'),
                    $conds = 'activity_id='.$activity->Activity_id,
                    $fname = __METHOD__,
                    $options = array( '' )
                );
            }
            foreach ($questions as $q) {
                $answer = $dbr->select(
                    'pe_answers',
                    array( '*'),
                    $conds = 'EvalMainId='.$row->id . ' and qid = ' . $q->id,
                    $fname = __METHOD__,
                    $options = array( '' )
                );
                $answer=$answer->fetchObject();

                $data.=$q->Question . '<br>' ;
                $data.='<b> '.( $activity_cd->type == 1? 'Answer' : 'Points Awarded').'</b> :'.$answer->answer . '<br>' ;
                if ($answer->Comment!=' ')
                    $data.='Comment :'.$answer->Comment . '<br>' ;
                $data.='<br>';
            }

            $data.='</span>';        

        }
        if ($evals->numRows())
            $data.='</table><br>';


        $evals=$dbr->select(
            'pe_eval_main',
            array( '*'),
            $conds = 'EvaluaterId='.$wgUser->getId().' and LearnerId='.$wgUser->getId() ,
            $fname = __METHOD__,
            $options = array( '' )
        );
        $numE=$evals->numRows();
        $data.='<h2>Your Self Evaluations</h2>';       

        if ($numE) {
            $data.='
                <table border="1" class="prettytable sortable "  >
                <tr>
                  <td>Activity</td>
                  <td>Title</td>
                  <td>Submitted by</td>
                  <td>URL</td>      
                  <td>Comment</td>
                  <td>Evaluated by</td>
                  <td>Related</td>
                  <td>Score</td>
                </tr>
            ';
        }
        else
        {
            $data.='<b> You have not done a self evaluation yet.<b></br>';
        }
        foreach ($evals as $row) {


            $learner = $dbr->select(
                'user',
                array( '*'),
                $conds = 'user_id='.$row->LearnerId,
                $fname = __METHOD__,
                $options = array('')
                );
            $learner=$learner->fetchObject();

            $evaluater = $dbr->select(
                'user',
                array( '*'),
                $conds = 'user_id='.$row->EvaluaterId,
                $fname = __METHOD__,
                $options = array('')
            );
            $evaluater=$evaluater->fetchObject();

            $activity= $dbr->select(
                'pe_Activities',
                array( '*'),
                $conds = 'id='.$row->ActivityId,
                $fname = __METHOD__,
                $options = array( '' )
            );

            $activity=$activity->fetchObject();
            $conditionRecom.=' id!='.$activity->id .' and ';


            $activity_cd= $dbr->select(
                'pe_cd_Activities',
                array( '*'),
                $conds = 'id='.$activity->Activity_id,
                $fname = __METHOD__,
                $options = array( '' )
            );

            $activity_cd=$activity_cd->fetchObject();


            $data.= '<tr>
              <td> '. $activity_cd->title .' </td>
              <td> '.$activity->Title.' </td>
              <td> <a href="/User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
              <td> <a href="'.$activity->URL.'" target="_blank">'.$activity->URL.'</a></td>        
              <td>'.$activity->Comment.'</td>
              <td> <a href="/User:
              '.$evaluater->user_name.'">'. $evaluater->user_name .' </a></td>
              <td>'.($row->Related ? "Yes" :"No").'</td>
              <td>'.$row->Score.'</td>
              </tr>
            ';    
        }
        $data.='</table><br>';

        $data.='<h2>Evaluations by you</h2>';
        $evals=$dbr->select(
            'pe_eval_main',
            array( '*'),
            $conds = 'EvaluaterId='.$wgUser->getId().' and LearnerId!='.$wgUser->getId(),
            $fname = __METHOD__,
            $options = array( '' )
        );
        $numE=$evals->numRows();       

        if ($numE) {
        $data.='
            <table border="1" class="prettytable sortable "  >
            <tr>
              <td>Activity</td>
              <td>Title</td>
              <td>Submitted by</td>
              <td>URL</td>      
              <td>Comment</td>
              <td>Evaluated by</td>
              <td>Related</td>
              <td>Score</td>
            </tr>
        ';
        }
        else {
            $data.='<b> You have not performed a Peer Evaluation yet.</b><br>';
        }
        foreach ($evals as $row) {


            $learner = $dbr->select(
                'user',
                array( '*'),
                $conds = 'user_id='.$row->LearnerId,
                $fname = __METHOD__,
                $options = array('')
                );
            $learner=$learner->fetchObject();

            $evaluater = $dbr->select(
                'user',
                array( '*'),
                $conds = 'user_id='.$row->EvaluaterId,
                $fname = __METHOD__,
                $options = array('')
            );
            $evaluater=$evaluater->fetchObject();

            $activity= $dbr->select(
                'pe_Activities',
                array( '*'),
                $conds = 'id='.$row->ActivityId,
                $fname = __METHOD__,
                $options = array( '' )
            );

            $activity=$activity->fetchObject();
            $conditionRecom.=' id!='.$activity->id .' and ';
            $evalnum[$activity->Activity_id]+=1;
            

            $activity_cd= $dbr->select(
                'pe_cd_Activities',
                array( '*'),
                $conds = 'id='.$activity->Activity_id,
                $fname = __METHOD__,
                $options = array( '' )
            );

            $activity_cd=$activity_cd->fetchObject();


            $data.= '<tr>
              <td> '. $activity_cd->title .' </td>
              <td> '.$activity->Title.' </td>
              <td> <a href="/User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
              <td> <a href="'.$activity->URL.'" target="_blank">'.$activity->URL.'</a></td>        
              <td>'.$activity->Comment.'</td>
              <td> <a href="/User:
              '.$evaluater->user_name.'">'. $evaluater->user_name .' </a></td>
              <td>'.($row->Related ? "Yes" :"No").'</td>
              <td>'.$row->Score.'</td>
              </tr>
            ';    
        }
        if ($numE)
            $data.='</table><br>';

	$data.='<h2> Recommended Activites for you to Evaluate </h2> ';
	$activity_cd= $dbr->select(
		'pe_cd_Activities',
		array( '*'),
		$conds = 'pe_flag=1',
		$fname = __METHOD__,
		$options = array( '' )
	);

      	$data.='<b> We recommend that you submit a minimum 3 evaluations for each activity</b><br>';
	foreach ($activity_cd as $row)
	{
	   	    $data.='<h4>'.$row->title.'</h4>';
            $data.='<b>You have submitted '. ($evalnum[$row->id] ? $evalnum[$row->id] : 0) . ' evaluations for this activity.</b>';

//	$data.='<b><a href="http://b.wikieducator.org/User:Akashagarwal/sample-ViewActivities"> You could also click here to view all submitted activities and evaluate them </a> </b> <br>';
        	$ret='';
	        $res = $dbr->select(
	            'pe_Activities',
	            array( '*'),
	            $conds = $conditionRecom.' Activity_id='.$row->id.' and OptedIn=1 ',
	            $fname = __METHOD__,
	            $options = array( 'ORDER BY' => 'EvalNum ASC' , 'LIMIT' => '3' )
	        );


	        $table='
	            <table border="1" class="prettytable sortable" >
	            <tr>
	            <td>Activity</td>
	            <td>Title</td>
	            <td>Submitted by</td>
	            <td>URL</td>      
	            <td>Comment</td>
	            <td>Opted in for Evaluation</td>
	            <td>Total number of evaluations</td>
	            <td>Submission Time</td>
	            </tr>
		';
            if (!$res->numRows())
                $ret.= "<br> Currently, there are no submissions in this catagory for you to evaluate. Kindly check again later. <br>";

            if ($res->numRows()) {
	           $ret.=" <b> Click on the title of an activity in the below table to evaluate it </b> <br>";
	           $ret.=$table;
            };
	        foreach ( $res as $row ) {

	        $activity_cd= $dbr->select(
	                'pe_cd_Activities',
	                array( '*'),
	                $conds = 'id='.$row->Activity_id,
	                $fname = __METHOD__,
	                $options = array( '' )
	        );
		$activity_cd=$activity_cd->fetchObject();


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
	                <td>'.$activity_cd->title.'</td>
	                <td class="title" id="'.$row->id.'">  <a>'.$row->Title.' </a> </td>
	                <td> <a href="/User:'.$user->user_name.'">'. $user->user_name .' </a></td>
	                <td> <a href="'.$row->URL.'" target="_blank"> '.$row->URL.'</a> </td>   
	                <td>'.$row->Comment.'</td>
	                <td>'.($row->OptedIn ? "Yes" :"No").'</td>
	                <td>'.$row->EvalNum.'</td>
	                <td>'.$row->Timestamp.'</td>
	                </tr>
	            ';
                if ($res->numRows())
                    $ret.=$table;
	        }
	        $ret.="</table>";
	        $data.=$ret;
	}
        $data.='
            <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
        ';
        $result->addValue(null, $this->getModuleName(),array('success' => $data));

        return true;
    }
    
    protected function getwDB() {
        return wfGetDB( DB_MASTER );
    }
 
    protected function getrDB() {
        return wfGetDB( DB_SLAVE );
    }

    public function getAllowedParams() {
        return array (
            'id' => null,
        );
    }
 
    public function getParamDescription() {
        return array (
            'id' => 'id'
        );
    }

    public function getDescription() {
        return 'API to get the Evaluations';
    }
 
    protected function getExamples() {
        return array (
            'api.php?action=apiViewEvaluations&id=1&format=xml',
        );
    }
        public function getVersion() {
		        return __CLASS__ . ': 0';
			    }

}

?>
