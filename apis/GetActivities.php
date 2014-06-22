<?php

class apiGetActivities extends ApiQueryBase {
    public function __construct( $query, $moduleName ) {
        parent :: __construct( $query, $moduleName, '' );
    }
 
    public function execute() {

        function getActivities($id)
        {
            $dbr = wfGetDB( DB_SLAVE );

            $ret='<div id="c'.$id.'"> <br>';

            $activity_cd= $dbr->select(
                'pe_cd_Activities',
                array( '*'),
                $conds = 'id='.$id,
                $fname = __METHOD__,
                $options = array( '' )
            );
            $activity_cd=$activity_cd->fetchObject();

            if (!$activity_cd->pe_flag) {
                $res = $dbr->select(
                    'pe_Activities',
                    array( '*'),
                    $conds = 'Activity_id='.$id,
                    $fname = __METHOD__,
                    $options = array( 'ORDER BY' => 'EvalNum ASC' )
                );


                $table='
                    <table border="1" class="prettytable sortable" >
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
                        <td id="'.$row->id.'"> '.$row->Title.' </td>
                        <td> <a href="/User:'.$user->user_name.'">'. $user->user_name .' </a></td>
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
                return $ret;

            }


            $res = $dbr->select(
                'pe_Activities',
                array( '*'),
                $conds = 'Activity_id='.$id.' and OptedIn=1 ',
                $fname = __METHOD__,
                $options = array( 'ORDER BY' => 'EvalNum ASC' )
            );


            $table='
                <table border="1" class="prettytable sortable" >
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

            $ret.=" <h3> Click on the title of an Activity to Evaluate it </h3> <br>";
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

            $res = $dbr->select(
                    'pe_Activities',
                    array( '*'),
                    $conds = 'Activity_id='.$id.' and OptedIn=0',
                    $fname = __METHOD__,
                    $options = array( 'ORDER BY' => 'Timestamp DESC' )
                    );


            $table='
                <table border="1" class="prettytable sortable">
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

            $ret.=" <h3> Activities not available for evaluation </h3> <br>";
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
                    <td> <a href="/User:'.$user->user_name.'">'. $user->user_name .' </a></td>
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


        global $wgUser, $wgServer;
        global $wgDefaultUserOptions;
        $id = NULL;
        $params = $this->extractRequestParams();

        $result = $this->getResult();


        $result->addValue(null, $this->getModuleName(),array('success' => getActivities($params['id'])));


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
            'api.php?action=apiGetActivities&id=1&format=xml',
        );
    }
        public function getVersion() {
		        return __CLASS__ . ': 0';
			    }

}

?>
