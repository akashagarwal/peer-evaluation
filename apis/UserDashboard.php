<?php

class apiUserDashboard extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {


        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;

        $data='';

        $params = $this->extractRequestParams();

        $result = $this->getResult();

        $dbr=$this->getrDB();

        $data.='<h2> Your Activity submissions </h2>';
        $data.='<b> You may click on the title (those in blue) to do a self evaluation. </b>';
        $ret='';
        $res = $dbr->select(
            'pe_Activities',
            array( '*'),
            $conds = 'userId='.$wgUser->getId(),
            $fname = __METHOD__,
            $options = array( 'ORDER BY' => 'EvalNum ASC' )
        );


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

        $ret.=$table;
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
                <td>'.$activity_cd->title.'</td>'.
                ($activity_cd->pe_flag ? '<td class="title" id="'.$row->id.'">  <a>'.$row->Title.' </a> </td>' : '<td id="'.$row->id.'"> '.$row->Title.' </td>')
                .'<td>'.$row->URL.'</td>        
                <td>'.$row->Comment.'</td>
                <td>'.($row->OptedIn ? "Yes" :"No").'</td>
                <td>'.$row->EvalNum.'</td>
                <td>'.$row->Timestamp.'</td>
                </tr>
                ';
            $ret.=$table;

        }
        $ret.="</table>";
        $data.=$ret;

        $data.='<h2>Evaluations of your activities</h2>';
        $evals=$dbr->select(
            'pe_eval_main',
            array( '*'),
            $conds = 'LearnerId='.$wgUser->getId(),
            $fname = __METHOD__,
            $options = array( '' )
        );

        $data.='
            <table border="1" class="prettytable sortable "  >
            <tr>
              <td>Activity </td>
              <td>Title</td>
              <td>Submitted by</td>
              <td>URL</td>      
              <td>Comment</td>
              <td>Evaluated by </td>
              <td> Is_Related </td>
              <td> Score </td>
            </tr>
        ';

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
              <td>  <a class="title" name='.$row->id.'>'.$activity->Title.' </a> </td>
              <td> <a href="/User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
              <td>'.$activity->URL.'</td>        
              <td>'.$activity->Comment.'</td>
              <td> <a href="/User:'.$evaluater->user_name.'">'. $evaluater->user_name .' </a></td>
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
            $conds = 'EvaluaterId='.$wgUser->getId(),
            $fname = __METHOD__,
            $options = array( '' )
        );
        $numE=$evals->numRows();   
        $data.='
            <table border="1" class="prettytable sortable "  >
            <tr>
              <td>Activity </td>
              <td>Title</td>
              <td>Submitted by</td>
              <td>URL</td>      
              <td>Comment</td>
              <td>Evaluated by </td>
              <td> Is_Related </td>
              <td> Score </td>
            </tr>
        ';

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
              <td>  <a class="title" name='.$row->id.'>'.$activity->Title.' </a> </td>
              <td> <a href="/User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
              <td>'.$activity->URL.'</td>        
              <td>'.$activity->Comment.'</td>
              <td> <a href="/User:'.$evaluater->user_name.'">'. $evaluater->user_name .' </a></td>
              <td>'.($row->Related ? "Yes" :"No").'</td>
              <td>'.$row->Score.'</td>
              </tr>
            ';
        

        }
        $data.='</table><br>';

        $data.='<h2> Recommended Activites (of 2nd Learning reflection) for you to Evaluate </h2> ';
        $data.='<b> Till now you have submitted '.$numE.' Evaluations. It is recommended that you do at least 3 evaluations.</b>;
        $ret='';
        $res = $dbr->select(
            'pe_Activities',
            array( '*'),
            $conds = 'Activity_id=2 and OptedIn=1 ',
            $fname = __METHOD__,
            $options = array( 'ORDER BY' => 'EvalNum ASC' , 'LIMIT' => '3' )
        );


        $table='
            <table border="1" class="prettytable sortable" >
            <tr>
            <td>Activity </td>
            <td>Title</td>
            <td>Submitted by</td>
            <td>URL</td>      
            <td>Comment</td>
            <td>Opted in for Evaluation</td>
            <td>Total number of evaluations</td>
            <td>Submission Time</td>
            </tr>
            ';

        $ret.=" <b> Click on the title of an Activity to Evaluate it </b> <br>";
        $ret.=$table;
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
                <td>'.$row->URL.'</td>        
                <td>'.$row->Comment.'</td>
                <td>'.($row->OptedIn ? "Yes" :"No").'</td>
                <td>'.$row->EvalNum.'</td>
                <td>'.$row->Timestamp.'</td>
                </tr>
                ';
            $ret.=$table;
            $ret.="</div>";
        }
        $ret.="</table>";
        $data.=$ret;


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
