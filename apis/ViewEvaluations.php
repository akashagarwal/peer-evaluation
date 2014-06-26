<?php

class apiViewEvaluations extends ApiQueryBase {
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

        $evals=$dbr->select(
            'pe_eval_main',
            array( '*'),
            $conds = '',
            $fname = __METHOD__,
            $options = array( '' )
        );

        $data.='<h6> Click on a title to get the details </h6>';
        $data.='
            <table border="1" class="prettytable sortable "  >
            <tr>
              <td>Activity</td>
              <td>Title</td>
              <td>Submitted by</td>
              <td>URL</td>      
              <td>Comment</td>
              <td>Evaluated by</td>
              <td>Is_Related</td>
              <td>Score</td>
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


/*
            $data.='Title : ' . $activity->Title . '<br>';
            $data.='URL : ' . $activity->URL . '<br>';            
            $data.=$learner->user_name . '<br>';
            $data.=$evaluater->user_name . '<br>';
*/
            $data.= '<tr>
              <td> '. $activity_cd->title .' </td>
              <td>  <a class="title" name='.$row->id.'>'.$activity->Title.' </a> </td>
              <td> <a href="/User:'.$learner->user_name.'">'. $learner->user_name .' </a></td>
              <td><a href="'.$activity->URL.'" target="_blank"> '.$activity->URL.'</a> </td>        
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
        $data.='<br>';
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
