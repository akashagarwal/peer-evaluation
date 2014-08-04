<?php
class GenForm {
    static function onParserInit( Parser $parser ) {
            $parser->setHook( 'genform', array( __CLASS__, 'submitactivityRender' ) );
            return true;
    }

    static function submitactivityRender( $input, array $args, Parser $parser ) {

        $ret = '';

        $rubric = $args[ 'rubric' ];
        global $wgUser;

        $title = Title::newFromText( ':' . $rubric );
        $revision = Revision::newFromTitle ( $title );

        if ( $revision == null )
		  return "Page does not exist";
	    $text = $revision->getText( Revision::FOR_PUBLIC );

        $pos = strpos( $text , "<!--" );
        $activitytitle = substr( $text , 1 , $pos - 1 );
        $type = substr( $text, $pos + 4 , 1 );
        $text = substr( $text , $pos + 9 );

        if ( $type == 1 ) { 

            $pos = strpos( $text , "*" );
            $text = substr( $text, 1 );

            $pos = strpos( $text , "*" );
            $default = substr( $text , 0 , $pos - 1 );            
            $text = substr( $text , $pos + 1 );            

            $pos = strpos( $text , "*" );
            $type1 = substr( $text , 0 , $pos  -1 );            
            $text = substr( $text , $pos + 1 );            

            $nlinepos  = strpos( $text , "\n" );
            $text = substr( $text , $nlinepos + 1 );
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
                $ret .= $value . '<br>';
                $ret .= '
<label for="a'.$key.'yes">Yes</label>
<input type="radio" name="a'.$key.'" id="a'.$key.'yes" value="1">
<label for="a'.$key.'no">No</label>
<input type="radio" name="a'.$key.'" id="a'.$key.'no" value="0"><br>
';
            }
            

            foreach ( $arr2 as $key => $value ) {
                $ret .= $value . '<br>';
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
                    $content .= substr( $text , 2 , $nlinepos - 1 );
                    $text = substr( $text , $nlinepos + 1 );
                    while ( $text[2] == '*' ) {
                        $nlinepos  = strpos( $text , "\n" );
                        $content .= substr( $text , 2 , $nlinepos - 1 );
                        $text = substr( $text , $nlinepos + 1 );                    
                    }

                    switch ( $i ) {
                        case 0:
                            $qbeginner[]=$content;
                            break;
                        case 1:
                            $qintermediate[]=$content;
                            break;
                        case 2:
                            $qadvanced[]=$content;
                            break;
                        
                        default:
                            break;
                    }                    
                }        
            }

            for ( $i=0;  $i < $nos ;  $i++) {    
                
                $ret .= 'Question ' . ($i+1) . ' : <b>' . $q[$i] . '</b><br><br>';
                $ret .= '
<label for="b'.$i.'Beginner">Beginner : '.$qbeginner[$i].'<br></label>
<input type="radio" name="b'.$i.'" id="b'.$i.'Beginner" value="0"><br>
<label for="b'.$i.'Intermediate">Intermediate : '.$qintermediate[$i].'<br></label>
<input type="radio" name="b'.$i.'" id="b'.$i.'Intermediate" value="1"><br>
<label for="b'.$i.'Advanced">Advanced : '.$qadvanced[$i].'<br></label>
<input type="radio" name="b'.$i.'" id="b'.$i.'Advanced" value="2"><br> <br>
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
Your Rating (1-5) : <input type="text" name="rating'.$i.'" id="description'.$i.'"> <br><br>
';            }

            
        }                

        return $ret;
    }
}
?>