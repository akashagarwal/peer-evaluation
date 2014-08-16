<?php

/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */

class Evaluation {
	static function onParserInit( Parser $parser ) {
			$parser->setHook( 'evaluation', array( __CLASS__, 'evaluationRender' ) );
			return true;
	}

	static function evaluationRender( $input, array $args, Parser $parser ) {

		$ret = '<script src="/extensions/PeerEvaluation/resources/evaluations.js"></script>';

		$ret .= "<div id='form' style='visibility:hidden' >";
		$ret .= "<span id='actDetails'></span>";
		$rubric = filter_var( $args[ 'rubric' ], FILTER_SANITIZE_STRING );
		global $wgUser;

		$activity = filter_var( $args['activity'], FILTER_SANITIZE_STRING );
		$title = Title::newFromText( ':' . $rubric );
		$revision = Revision::newFromTitle ( $title );

		if ( $revision == null )
		  return "Page does not exist";
		$text = $revision->getText( Revision::FOR_PUBLIC );

		$pos = strpos( $text , "<!--" );
		$activitytitle = substr( $text , 1 , $pos - 1 );
		$type = substr( $text, $pos + 4 , 1 );
		$text = substr( $text , $pos + 9 );

		$ret .= "<span id='type' value='" . $type . "' activity='" . $activity . "' ></span>";

		$ret .=  '<form>
			Is the content of the post specified in the above URL related to the desired activity ?
			<input type="radio" name="Related" value="1" class="Related" id="relatedy"> <label for="relatedy">Yes</label>
			<input type="radio" name="Related" value="0" class="Related" id="relatedn"> <label for="relatedn">No</label> <br>
			<span id="relatedError"></span>
			<br>';

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

			$ret .= '<div id="formcontent">';
			foreach ( $arr as $key => $value ) {
				$ret .= '<span type="q" qid="a' . $key . '" name="a">' . $value . ' </span>  <br>';
				$ret .= '
					<label for="a' . $key . 'yes">Yes</label>
					<input type="radio" name="a' . $key . '" id="a' . $key . 'yes" value="1">
					<label for="a' . $key . 'no">No</label>
					<input type="radio" name="a' . $key . '" id="a' . $key . 'no" value="0"><br>
					<span id="errora' . $key . '"></span>
				';
			}


			foreach ( $arr2 as $key => $value ) {
				$ret .= '<span type="q" qid="b' . $key . '" name="b">' . $value . ' </span>  <br>';
				$ret .= '
					<label for="b' . $key . 'yes">Yes</label>
					<input type="radio" name="b' . $key . '" id="b' . $key . 'yes" value="1">
					<label for="b' . $key . 'no">No</label>
					<input type="radio" name="b' . $key . '" id="b' . $key . 'no" value="0"><br>					<span id="errora' . $key . '"></span>
					<span id="errorb' . $key . '"></span>
				';
			}
			$ret .= '</div>';
		}

		if ( $type == 2 ) {

			$q = array();
			$qwt = array();
			$qbeginner = array();
			$qintermediate = array();
			$qadvanced = array();

			$qbeginnerGrade = array();
			$qintermediateGrade = array();
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

				for ( $i = 0; $i < 3; $i++ ) {

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
						$content .= "\n" . substr( $text , 0 , $nlinepos - 1 );
						$text = substr( $text , $nlinepos + 1 );
					}

					switch ( $i ) {
						case 0:
							$qbeginner[] = $content;
							$qbeginnerGrade[] = $grade;
							break;
						case 1:
							$qintermediate[] = $content;
							$qintermediateGrade[] = $grade;
							break;
						case 2:
							$qadvanced[] = $content;
							$qadvancedGrade[] = $grade;
							break;

						default:
							break;
					}
				}
			}
			$ret .= '<div id="formcontent">';
			for ( $i = 0;  $i < $nos ;  $i++ ) {
				$ret .= '<span type="q" qid="b' . $i . '" name="b" q="' . $q[$i] . '"">  <br>';
				$ret .= 'Question ' . ( $i + 1 ) . ' : <b>' . $q[$i] . '</b><br><br></span>';
				$ret .= '<table class="wikitable" >';
				$ret .= '
					<tr>
					<td style="text-align:center" width="33%">
					<label for="b' . $i . 'Beginner"> <i><b>' . $qbeginnerGrade[$i] . '</i></b> : ' . $qbeginner[$i] . '<br></label>
					<input type="radio" name="b' . $i . '" id="b' . $i . 'Beginner" value="' . $qbeginnerGrade[$i] . '"><br>
					</td><td style="text-align:center" width="33%">
					<label for="b' . $i . 'Intermediate"> <i><b>' . $qintermediateGrade[$i] . '</i></b> : ' . $qintermediate[$i] . '<br></label>
					<input type="radio" name="b' . $i . '" id="b' . $i . 'Intermediate" value="' . $qintermediateGrade[$i] . '"><br>
					</td><td style="text-align:center" width="33%">
					<label for="b' . $i . 'Advanced"> <i><b>' . $qadvancedGrade[$i] . '</i></b> : ' . $qadvanced[$i] . '<br></label>
					<input type="radio"  name="b' . $i . '" id="b' . $i . 'Advanced" value="' . $qadvancedGrade[$i] . '"><br> <br>
					</td></tr>
					';
				$ret .= '</table>';
				$ret .= '<span id="errorb' . $i . '"></span>';

			}
			$ret .= '</div>';

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
			$ret .= '<div id="formcontent">';

			for ( $i = 0;  $i < $nos ;  $i++ ) {

				$ret .= 'Question ' . ( $i + 1 ) . ' : <b>' . $q[$i] . '</b><br><br>';
				$ret .= '
					<label for="description' . $i . '">' . $qdescription[$i] . '</label>
					Your Rating (1-5) : <input type="text" q="' . $q[$i] . '" name="rating' . $i . '" id="description' . $i . '"> <br><br>'; 
				$ret .= '<span id="errorrating' . $i . '"></span>';
			}

			$ret .= '</div>';

		}

		$ret .= '<span id="submitError"></span>';
		$ret .= '<input type="button" id="submit" value="Submit"></input>';
		$ret .= '</div>';
		$ret .= '<div id="table"></div>';

		return $ret;
	}
}
?>
