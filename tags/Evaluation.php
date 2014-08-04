<?php
class Evaluation {
    static function onParserInit( Parser $parser ) {
            $parser->setHook( 'evaluation', array( __CLASS__, 'evaluationRender' ) );
            return true;
    }

    static function evaluationRender( $input, array $args, Parser $parser ) {

        $ret='<link rel="stylesheet" type="text/css" href="/extensions/PeerEvaluation/resources/evaluations.css">';
        $ret.='<script src="/extensions/PeerEvaluation/resources/evaluations.js"></script>';


        $ret.="<div id='form'>";
        $rubric = $args[ 'rubric' ];
        global $wgUser;

        $activity=$args['activity'];
        $title = Title::newFromText( ':' . $rubric );
        $revision = Revision::newFromTitle ( $title );

        if ( $revision == null )
		  return "Page does not exist";
	    $text = $revision->getText( Revision::FOR_PUBLIC );

        $pos = strpos( $text , "<!--" );
        $activitytitle = substr( $text , 1 , $pos - 1 );
        $type = substr( $text, $pos + 4 , 1 );
        $text = substr( $text , $pos + 9 );

        $ret.="<span id='type' value='".$type."' activity='".$activity."' ></span>";

        if ( $type == 1 ) { 

            $pos = strpos( $text , "*" );
            $text = substr( $text, 1 );

            $pos = strpos( $text , "*" );
            $default = substr( $text , 0 , $pos - 1 );            
            $text = substr( $text , $pos + 1 );            

            $pos = strpos( $text , "*" );
            $type1 = substr( $text , 0 , $pos  -1 );            
            $text = substr( $text , $pos );            

            $nlinepos  = strpos( $text , "\n" );
            $nestedcheck = $text[1];

            $arr1 = array();

            while ( $nestedcheck == '*' ) {
                $nlinepos  = strpos( $text , "\n" );
                $arr[] = substr( $text , 2 , $nlinepos - 2 );
                $text = substr( $text , $nlinepos + 1 );
                $nestedcheck = $text[1];
            }

            $text = substr( $text , 1 );            
            $pos = strpos( $text , "*" );
            $type2 = substr( $text , 0 , $pos  - 1 );            
            $text = substr( $text , $pos );            

            $nestedcheck = $text[1];
            $arr2 = array();

            while ( $nestedcheck == '*' ) {
                $nlinepos  = strpos( $text , "\n" );
                $arr2[] = substr( $text , 2 , $nlinepos - 2 );
                $text = substr( $text , $nlinepos + 1 );
                $nestedcheck = $text[1];
            }            

            $ret .= '<form>' ;
            foreach ( $arr as $key => $value ) {
                $ret .= '<span type="q" qid="a'.$key.'" name="a">' . $value . ' </span>  <br>';
                $ret .= '
<label for="a'.$key.'yes">Yes</label>
<input type="radio" name="a'.$key.'" id="a'.$key.'yes" value="1">
<label for="a'.$key.'no">No</label>
<input type="radio" name="a'.$key.'" id="a'.$key.'no" value="0"><br>
';
            }
            

            foreach ( $arr2 as $key => $value ) {
                $ret .= '<span type="q" qid="b'.$key.'" name="b">' . $value . ' </span>  <br>';
                $ret .= '
<label for="b'.$key.'yes">Yes</label>
<input type="radio" name="b'.$key.'" id="b'.$key.'yes" value="1">
<label for="b'.$key.'no">No</label>
<input type="radio" name="b'.$key.'" id="b'.$key.'no" value="0"><br>
';            
            }
        }

        if ( $type == 2 ) {
            
            $q = array();
            $qwt = array();
            $qbeginner = array();
            $qintermediate = array();
            $qadvanced = array();

            $qbeginnerGrade = array();
            $qintermediateGrade= array();
            $qadvancedGrade = array();

            $nos = 0;
            while ( $text[0] == '*' )
            {
                $nos += 1;
                $pos = strpos( $text , "<!--" );
                $q[] = substr( $text , 1 , $pos - 1 );
                $qwt[] = substr( $text, $pos + 4 , 2 );

                $nlinepos  = strpos( $text , "\n" );
                $text = substr( $text , $nlinepos + 1 );

                for ($i=0; $i < 3; $i++) { 

                    $content = '';

                    $nlinepos  = strpos( $text , "\n" );
                    $gradeEnd = strpos( $text , "-->" );
                    $grade = substr( $text , 10 , $gradeEnd - 10 );
                    $text = substr( $text , $nlinepos + 1 );                    

                    $nlinepos  = strpos( $text , "\n" );
                    $content .= substr( $text , 2 , $nlinepos - 1 );
                    $text = substr( $text , $nlinepos + 1 );
                    while ( $text[0] == '#' ) {
                        $nlinepos  = strpos( $text , "\n" );
                        $content .= "\n".substr( $text , 0 , $nlinepos - 1 );
                        $text = substr( $text , $nlinepos + 1 );                    
                    }

                    switch ( $i ) {
                        case 0:
                            $qbeginner[]=$content;
                            $qbeginnerGrade[]=$grade;
                            break;
                        case 1:
                            $qintermediate[]=$content;
                            $qintermediateGrade[]=$grade;
                            break;
                        case 2:
                            $qadvanced[]=$content;
                            $qadvancedGrade[]=$grade;
                            break;
                        
                        default:
                            break;
                    }                    
                }        
            }

            for ( $i=0;  $i < $nos ;  $i++) {    
                $ret .= '<span type="q" qid="b'.$i.'" name="b" q="'.$q[$i].'"">  <br>';                
                $ret .= 'Question ' . ($i+1) . ' : <b>' . $q[$i] . '</b><br><br></span>';
                $ret .= '
<label for="b'.$i.'Beginner"> '.$qbeginnerGrade[$i].' : '.$qbeginner[$i].'<br></label>
<input type="radio" name="b'.$i.'" id="b'.$i.'Beginner" value="'.$qbeginnerGrade[$i].'"><br>
<label for="b'.$i.'Intermediate"> '.$qintermediateGrade[$i].' : '.$qintermediate[$i].'<br></label>
<input type="radio" name="b'.$i.'" id="b'.$i.'Intermediate" value="'.$qintermediateGrade[$i].'"><br>
<label for="b'.$i.'Advanced">' .$qadvancedGrade[$i].' : '.$qadvanced[$i].'<br></label>
<input type="radio" name="b'.$i.'" id="b'.$i.'Advanced" value="'.$qadvancedGrade[$i].'"><br> <br>
';            }

        }

        if ( $type == 3 ) {

            $q = array();
            $qwt = array();
            $qdescription = array();

            $nos = 0;
            while ( $text[0] == '*' )
            {
                $nos += 1;
                $pos = strpos( $text , "<!--" );
                $q[] = substr( $text , 1 , $pos - 1 );
                $qwt[] = substr( $text, $pos + 4 , 2 );

                $nlinepos  = strpos( $text , "\n" );
                $text = substr( $text , $nlinepos + 1 );

                $content = '';

                $nlinepos  = strpos( $text , "\n" );
                $content .= substr( $text , 2 , $nlinepos - 1 ); 
                $text = substr( $text , $nlinepos + 1 );
               
                while ( $text[2] == '*' ) {
                    $nlinepos  = strpos( $text , "\n" );
                    $content .= substr( $text , 2 , $nlinepos - 1 );
                    $text = substr( $text , $nlinepos + 1 );                    
                }

                $qdescription[] = $content;

            }

            for ( $i=0;  $i < $nos ;  $i++) {    
                
                $ret .= 'Question ' . ($i+1) . ' : <b>' . $q[$i] . '</b><br><br>';
                $ret .= '
<label for="description'.$i.'">'.$qdescription[$i].'</label>
Your Rating (1-5) : <input type="text" q="' . $q[$i] . '" name="rating'.$i.'" id="description'.$i.'"> <br><br>';}

            
        }                

        $ret.='<input type="button" id="submit" value="Submit"></input>';
        $ret.='</div>';
        $ret.='<div id="table"></div>';

        return $ret;
    }
}
?>