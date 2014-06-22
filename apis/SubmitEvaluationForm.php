<?php

class apiSubmitEvaluationForm extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {


        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;

        $data='';

        $params = $this->extractRequestParams();

        $result = $this->getResult();

        $id = $params['id'];
        $related = $params['related'];
        $related_comment = $params['related_comment'].' ';

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

        $questions=$dbw->select(
            'pe_questions_mcq',
            array( '*'),
            $conds = 'activity_id='.$activityid,
            $fname = __METHOD__,
            $options = array( '' )
        );

        foreach ( $questions as $row ) {
            $ans=$params[$row->id].' ';
            $Comment=$params['c'.$row->id].' ';

            $dbw->insert(
                'pe_answers',
                array('qid' => $row->id, 'EvalMainId' => $EvalId, 'answer' => $ans , 'Comment' => $Comment  , 'Timestamp' => $date),
                $fname = 'Database::insert', 
                $options = array()
            );
            
        }

        $EvalNum=$activity->EvalNum+1;

        $dbw->update(
            'pe_Activities',
            $values = array('EvalNum' =>  $EvalNum),
            $conds = array('id' => $id),        
            $fname = 'Database::update', $options = array()
        );


        $result->addValue(null, $this->getModuleName(),array('success' => 'Successfully added'));

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
