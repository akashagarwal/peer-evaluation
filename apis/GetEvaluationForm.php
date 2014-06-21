<?php

class apiGetEvaluationForm extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {


        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;

        $data='';

        $id = NULL;
        $params = $this->extractRequestParams();

        $result = $this->getResult();
        $id=$params['id'];

        $dbr=$this->getDB();

        $activity = $dbr->select(
            'pe_Activities',
            array( '*'),
            $conds = 'id='.$id,
            $fname = __METHOD__,
            $options = array( '' )
        );


        $activity=$activity->fetchObject();

        $activityId=$activity->Activity_id;

        $activityCd = $dbr->select(
            'pe_cd_Activities',
            array( '*'),
            $conds = 'id='.$activityId,
            $fname = __METHOD__,
            $options = array( '' )
        );
        $activityCd=$activityCd->fetchObject();

        if ( !$activityCd->pe_flag ) {
            $result->addValue(null, $this->getModuleName(),array('success' => 0));
            return true;

        }
        if ( $activityCd->type == 1 ) {

            $questions = $dbr->select(
                'pe_questions_mcq',
                array( '*'),
                $conds = 'activity_id= ' . $activityId,
                $fname = __METHOD__,
                $options = array( '' )
            );
            $form ='
                <form>
                Is the content of the post related to ' . $activityCd->title . ' ?
                <input type="radio" name="Related" value="1" class="Related">Yes
                <input type="radio" name="Related" value="0" class="Related">No <br>
                <p>Comment (optional)</p>
                <textarea name="Related_comment" id="Related_comment" rows="2" cols="50"></textarea>
                <br>
                <div id="formcontent">
            ';
            foreach ( $questions as $row ) {
                $form.='<div id="ques" name="'.$row->id.'">';
                $form.=$row->Question . '<br>';
                $no=$row->No_options;
                $form.='<input type="radio" name="'.$row->id.'" value="'.$row->option1.'">' . $row->option1 . '<br>';
                $form.='<input type="radio" name="'.$row->id.'" value="'.$row->option2.'">' . $row->option2 . '<br>';
                if ( $no > 2 ) {
                    $form.='<input type="radio" name="'.$row->id.'" value="'.$row->option3.'">' . $row->option3 . '<br>';

                }
                $form.='<br><p>Comment (optional)</p>
                <textarea id="c'.$row->id.'" rows="2" cols="50"></textarea>
                <br>';

                $form.='</div><br>';
            }
            $form .= '
                </div>
                <input type="hidden" id="actid" value="'.$id.'">
                <input type="submit" value="Submit" id="submitform">
                </form>
            ';
            $data.=$form;
        }


        $result->addValue(null, $this->getModuleName(),array('success' => $data));


        return true;
    }
    
    protected function getDB() {
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
        return 'API to get the Activities';
    }
 
    protected function getExamples() {
        return array (
            'api.php?action=apiGetEvaluationForm&id=1&format=xml',
        );
    }
        public function getVersion() {
		        return __CLASS__ . ': 0';
			    }

}

?>
