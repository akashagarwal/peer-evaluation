<?php

class apiSubmitEvaluationForm extends ApiQueryBase {
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

        $id = trim(filter_var($params['id'],FILTER_SANITIZE_NUMBER_INT));
        $related = trim(filter_var($params['related'],FILTER_SANITIZE_NUMBER_INT));
        $related_comment = trim(filter_var($params['related_comment'].' ',FILTER_SANITIZE_STRING));

        $dbw=$this->getwDB();

        $activity = $dbw->select(
            'pe_Activities',
            array( '*'),
            $conds = 'id='.$id,
            $fname = __METHOD__,
            $options = array( '' )
        );
        $activity=$activity->fetchObject();

        $activityid=$activity->Activity_id;
        $date = date('Y-m-d H:i:s');

        $score=0.0;
        $dbw->insert(
            'pe_eval_main',
            array('ActivityId' => $id, 'EvaluaterId' => $wgUser->getId(), 'LearnerId' => $activity->userId , 'Related' => $related  , 'Related_comment' => $related_comment, 'Timestamp' => $date),
            $fname = 'Database::insert', $options = array()
        );

        $temp = $dbw->select(
            'pe_eval_main',
            array( '*'),
            $conds = '',
            $fname = __METHOD__,
            $options = array( 'ORDER BY' => 'Timestamp DESC' )
        );

        $EvalId=$temp->fetchObject()->id;

        $activityCd = $dbw->select(
            'pe_cd_Activities',
            array( '*'),
            $conds = 'id='.$activityid,
            $fname = __METHOD__,
            $options = array( '' )
        );
        $activityCd=$activityCd->fetchObject();

        $data.=$activityCd->type;

        if ( $activityCd->type == 1 ) {

            $questions=$dbw->select(
                'pe_questions_mcq',
                array( '*'),
                $conds = 'activity_id='.$activityid,
                $fname = __METHOD__,
                $options = array( '' )
            );

            foreach ( $questions as $row ) {
                $ans=trim(filter_var($params[$row->id].' ',FILTER_SANITIZE_STRING));

                if ($ans == $row->option1) {
                    $score += ($row->option1_weight/100.0)*$row->weightage_question;
                }
                elseif ($ans == $row->option2) {
                    $score += ($row->option2_weight/100.0)*$row->weightage_question;
                }
                elseif ($ans == $row->option3) {
                    $score += ($row->option3_weight/100.0)*$row->weightage_question;
                }

                $Comment=trim(filter_var($params['c'.$row->id].' ',FILTER_SANITIZE_STRING));

                $dbw->insert(
                    'pe_answers',
                    array('qid' => $row->id, 'EvalMainId' => $EvalId, 'answer' => $ans , 'Comment' => $Comment  , 'Timestamp' => $date),
                    $fname = 'Database::insert', 
                    $options = array()
                );
                
            }
            $score=$score/10;


        }

        if ( $activityCd->type == 2 ) {
//            $data.=$activityid;
            $questions=$dbw->select(
                'pe_questions_10point',
                array( '*'),
                $conds = 'activity_id='.$activityid,
                $fname = __METHOD__,
                $options = array( '' )
            );

            foreach ( $questions as $row ) {
                $ans=trim(filter_var($params[$row->id].' ',FILTER_SANITIZE_STRING));
                $score+=($ans/10.0)*$row->weightage_question;
                $Comment=trim(filter_var($params['c'.$row->id].' ',FILTER_SANITIZE_STRING));
                
 //               $data.=$ans;

                $dbw->insert(
                    'pe_answers',
                    array('qid' => $row->id, 'EvalMainId' => $EvalId, 'answer' => $ans , 'Comment' => $Comment  , 'Timestamp' => $date),
                    $fname = 'Database::insert', 
                    $options = array()
                );
                
            }
        }

        $EvalNum=$activity->EvalNum+1;

        $temp = $dbw->update(
            'pe_eval_main',
            $values = array('Score' => ($activityCd->type == 2 ? $score.' %' : $score.' points'), ),
            $conds = array('id' => $EvalId),        
            $fname = 'Database::update', $options = array()
        );

        $dbw->update(
            'pe_Activities',
            $values = array('EvalNum' =>  $EvalNum),
            $conds = array('id' => $id),        
            $fname = 'Database::update', $options = array()
        );


        $result->addValue(null, $this->getModuleName(),array('success' => 'Your Evaluation is successfully saved'));

        return true;
    }
    
    protected function getwDB() {
        return wfGetDB( DB_MASTER );
    }
 
    protected function getrDB() {
        return wfGetDB( DB_SLAVE );
    }

    public function getAllowedParams() {
        $array=array("related"=>null,"related_comment"=>null,"id"=>null);
        $dbr=$this->getrDB();

        $questions = $dbr->select(
            'pe_questions_mcq',
            array( '*')
        );

        foreach ($questions as $row ) {
            $array[$row->id]=null;
            $array['c'.$row->id]=null;
        };

        return $array;
    }
 
    public function getParamDescription() {
        $array=array("related"=>"related","related_comment"=>"related_comment","id"=>"Activity id");
        $dbr=$this->getrDB();

        $questions = $dbr->select(
            'pe_questions_mcq',
            array( '*')
        );

        foreach ($questions as $row ) {
            $array[$row->id]="form element id";
            $array['c'.$row->id]="Comment";
        };

        return $array;    
    }
 
    public function getDescription() {
        return 'API to submit an Evaluation';
    }
 
    protected function getExamples() {
        return array (
            'api.php?action=apiSubmitEvaluationForm&id=1&format=xml',
        );
    }
        public function getVersion() {
		        return __CLASS__ . ': 0';
			    }

}

?>
