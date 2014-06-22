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

        $data.='<h3> Activity Details </h3>';
        $data.='<b> Title : </b>'.$activity->Title . '<br>';
        $data.='<b> Comment : </b>'.$activity->Comment . '<br>';
        $data.='<b> URL : </b> <a href="'.$activity->URL.'" target="_blank">'.$activity->URL . '</a><br>';
        $data.='<iframe  width="100%"" height="400" src="'.$activity->URL.'"></iframe><br>';
        $data.='If you cannot see the blog post above please try <a href="'.$activity->URL.'" target="_blank"> clicking here</a>. If you cannot find the specified blog post, kindly choose No in the option to the below question and write a comment. <br>';

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
                <input type="button" value="Submit" id="submitform">
                </form>
            ';
            $data.=$form;
        }

        if ( $activityCd->type == 2 ) {

            $questions = $dbr->select(
                'pe_questions_10point',
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
                $form.='<input type="radio" name="'.$row->id.'" value="Not achieved">Not achieved';
                $form.='<input type="radio" name="'.$row->id.'" value="Achieved">Achieved';
                $form.='<input type="radio" name="'.$row->id.'" value="Merit">Merit';

                $form.='<span id="rNot achieved'.$row->id.'" > "'.$row->Notachieved_rubric.'" <br> ';
                $form.= '</span>';

                $form.='<span id="rcontent"></span>';
                $form.='<br><p>Comment (optional)</p>
                <textarea id="c'.$row->id.'" rows="2" cols="50"></textarea>
                <br>';

                $form.='</div><br>';
            }
            
            $form .= '
                </div>
                <input type="hidden" id="actid" value="'.$id.'">                
                <input type="button" value="Submit" id="submitform">
                </form>
            ';
            $data.=$form;
        }



        $result->addValue(null, $this->getModuleName(),array('success' => $data));
        $result->addValue(null, $this->getModuleName(),array('type' => $activityCd->type));


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
