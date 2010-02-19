<?php 

// 
// AutoWikiFire 0.2
// 
// License GPL
// 

// This is a text only for a test
$opts = array( 'http' => array( 'method' => 'GET', 'header' => 'User-Agent: AutoWikiFire/0.2' ) );
$context  = stream_context_create( $opts );
$text = file_get_contents( 'http://fr.wikipedia.org/w/index.php?title=Josh_Zuckerman&action=raw&oldid=46951521', false, $context );

$newarticle = true;






///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                   SETTINGS                                                                                                        //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Bulleted and numbered lists
 * 
 * An unwikiformatted list is considered as a list if the bullets or the numbers are aligned between following lines:
 * 
    - item 1
    - item 2
    - item 3
    
  1. item 1
  2. item 2
  3. item 3
 * 
 * 
 */

// Characters recognized as a bulleted list
$wgBulletedList = array( '-', '\*' );

$wgNbEmptyLinesMaxBetweenTwoBullets = 1;
$wgNbFilledLinesMaxBetweenTwoBullets = 5;

// Characters recognized as a numbered list (the number must be parenthezed)
$wgNumberedList = array( '([0-9]{1,3})\.' );

$wgNbEmptyLinesMaxBetweenTwoNumbers = 2;
$wgNbFilledLinesMaxBetweenTwoNumbers = 2;

/**
 * Magic words
 */
$wgMagicWords = array( 'DEFAULTSORT:', 'CURRENT(?:DAY|MONTH|YEAR)' );


/**
 * Recreate an outline
 * 
 * Only h2-title can be created.
 * A 'title' must have the following structure:
 * 
The Title of the paragraph `[here 26] -> length of the title = $wgMaxLengthTitle'

`[1]' Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat.

`[2]' Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede. Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh.

`[3]' Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit. Ut velit mauris, egestas sed, gravida nec, ornare ut, mi. Aenean ut orci vel massa suscipit pulvinar.

`[4]' Nulla sollicitudin. Fusce varius, ligula non tempus aliquam, nunc turpis ullamcorper nibh, in tempus sapien eros vitae ligula. Pellentesque rhoncus nunc et augue. Integer id felis. `[here 180] -> minimum length of lines following a title = $wgMinLengthFollowingLines'
`[here 4] -> minimum number of lines following a title = $wgMinNbFollowingLines'
 * 
 * 
 */

// Maximum length allowed to consider a line as a title
$wgMaxLengthTitle = 30;

// Minimum number of lines (eventually separed by 0 or 1 blank lines) required with a length as the next parameter to consider a (previous) line as a title
$wgMinNbFollowingLines = 6;

// Minimum length of the following lines required to consider a (previous) line as a title
$wgMinLengthFollowingLines = 200;

/**
 * Parameters related to categories and interwikis
 */
$wgCategoryLocalName = 'Catégorie';

// Wikipedia interwikis (13/02/2010)
$wgInterwikis  = array( 'aa', 'ab', 'ace', 'af', 'ak', 'als', 'am', 'an', 'ang', 'ar', 'arc', 'ast', 'av', 'ay', 'az', 'ba', 'bar', 'bat-smg', 'bcl', 'be', 'be-x-old', 'bg', 'bh', 'bi', 'bm', 'bn', 'bo', 'bpy', 'br', 'bs', 'bug', 'bxr', 'ca', 'cbk-zam', 'cdo', 'ce', 'ceb', 'ch', 'cho', 'chr', 'chy', 'ckb', 'co', 'cr', 'crh', 'cs', 'csh', 'cu', 'cv', 'cy', 'da', 'de', 'diq', 'dsb', 'dv', 'dz', 'ee', 'el', 'eml', 'en', 'eo', 'es', 'et', 'eu', 'ext', 'fa', 'ff', 'fi', 'fiu-vro', 'fj', 'fo', 'fr', 'frp', 'fur', 'fy', 'ga', 'gan', 'gd', 'gl', 'glk', 'gn', 'got', 'gu', 'gv', 'ha', 'hak', 'haw', 'he', 'hi', 'hif', 'ho', 'hr', 'hsb', 'ht', 'hu', 'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 'ik', 'ilo', 'io', 'is', 'it', 'iu', 'ja', 'jbo', 'jv', 'ka', 'kaa', 'kab', 'kg', 'ki', 'kj', 'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 'ksh', 'ku', 'kv', 'kw', 'ky', 'la', 'lad', 'lb', 'lbe', 'lg', 'li', 'lij', 'lmo', 'ln', 'lo', 'lt', 'lv', 'map-bms', 'mdf', 'mg', 'mh', 'mhr', 'mi', 'mk', 'ml', 'mn', 'mo', 'mr', 'ms', 'mt', 'mus', 'mwl', 'my', 'myv', 'mzn', 'na', 'nah', 'nan', 'nap', 'nb', 'nds', 'nds-nl', 'ne', 'new', 'ng', 'nl', 'nn', 'no', 'nov', 'nrm', 'nv', 'ny', 'oc', 'cm', 'or', 'os', 'pa', 'pag', 'pam', 'pap', 'pcd', 'pdc', 'pi', 'pih', 'pl', 'pms', 'pnb', 'pnt', 'ps', 'pt', 'qu', 'rm', 'rmy', 'rn', 'ro', 'roa-rup', 'roa-tara', 'ru', 'rw', 'sa', 'sah', 'sc', 'scn', 'sco', 'sd', 'se', 'sg', 'sh', 'si', 'simple', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'srn', 'ss', 'st', 'stq', 'su', 'sv', 'sw', 'szl', 'ta', 'te', 'tet', 'tg', 'th', 'ti', 'tk', 'tl', 'tn', 'to', 'tokipona', 'tp', 'tpi', 'tr', 'ts', 'tt', 'tum', 'tw', 'ty', 'udm', 'ug', 'uk', 'ur', 'uz', 've', 'vec', 'vi', 'vls', 'vo', 'wa', 'war', 'wo', 'wuu', 'xal', 'sh', 'yi', 'yo', 'za', 'zea', 'zh', 'zh-cfr', 'zh-classical', 'zh-yue', 'zu' );

// Language interwikis which don't appear in the interwiki toolbar
$wgInterwikisText = array( 'closed-zh-tw', 'cz', 'epo', 'jp', 'minnan', 'nomcom' );


/**
 * Recognize a paragraph in the aim to add blank lines in compact paragraphs
 */
$wgMinNbLinesParagraph = 6;

$wgMinLengthLinesParagraph = 40;


$wgDebug = true;


/** Statistics array

- sizelines[nblines]
- frames: - framelines[nbframelines]
          - frames[nbframes]
- blanklines: - blanklines[nbblanklines]
- interwikis[nbinterwikis]
- categories[nbcategories]
- lists














*/


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                   CODE                                                                                                            //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$nblinesterminal = 179;
$ticksevery = 10;
$nbfiguresleft = 4;

// Horizontal line
for( $i=0; $i<$nblinesterminal; $i++ ) echo '-';
echo "\n";

//////////////
// Analysis //
//////////////

// Do preliminary operations
list( $nblines, $lines ) = preprocessing( $text );

// Construct statistics about the text given
$statistics = new statistics( $text, $nblines, $lines );

$statistics->statisticsL11();

$statistics->statisticsL12();

$statistics->statisticsL13();

$statistics->statisticsL21();

$statistics->statisticsL22();

$statistics->statisticsL23();

$statistics->statisticsL31();

$statistics->statisticsL32();

$statistics->statisticsL33();

$statistics->display_statistics( $nblines, 3, $stat );

// 
//trivial_layouting( $nblines, $lines, true );

////////////////////////////
// Displaying the example //
////////////////////////////

if( true ) {
#if( false ) {

// Horizontal line
for( $i=0; $i<$nblinesterminal; $i++ ) echo '-';
echo "\n";

// Ten's tab
for( $i=0; $i<=$nbfiguresleft; $i++ ) echo ' ';
$counter = 0;
for( $i=0; $i<$nblinesterminal-$nbfiguresleft-1; $i++ ) {
	if( $i%$ticksevery == 0 ) {
		if( $counter >= 9 ) {
			if( $i < $nblinesterminal-$nbfiguresleft ) echo $counter;
			$i++;
		}
		else echo $counter;
		$counter++;
	}
	else echo ' ';
}
echo "\n";

// Unit's tab
echo '     ';
for( $i=0; $i<$nblinesterminal-5; $i++ ) echo $i%10;
echo "\n";

// Analysed text
if( $nblines > 0 ) {
	
	for( $k=0; $k<$nbfiguresleft; $k++ ) echo '0';
	echo '|';
	echo $lines[0];
	echo "\n";
}
for( $j=0; $j<=log($nblines); $j++ ) {
	
	for( $i=pow(10,$j); $i<$nblines && $i<pow(10,$j+1); $i++ ) {
		for( $k=0; $k<$nbfiguresleft-$j-1 ; $k++ ) echo '0';
		echo $i;
		echo '|';
		echo $lines[$i];
		echo "\n";
	}
}

}

// Horizontal line
for( $i=0; $i<$nblinesterminal; $i++ ) echo '-';
echo "\n";




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                   PRE-ANALYSIS                                                                                                    //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Preparation of the text:
 *   - add two lines at the end (to avoid counter side-effects)
 *   - cutting off into an array of lines
 */
function preprocessing( $text ) {
	
	wfDebugBegin( __METHOD__, "Preprocessing" );
	
	// Cutting off into an array of lines
	if( preg_match( '/\r\n/u', $text ) ) $lines = explode( "\r\n", "\r\n\r\n".$text."\r\n\r\n" );
	else $lines = explode( "\n", "\n\n".$text."\n\n" );
	
	// And count the number of lines
	$nblines = count( $lines );
	
	// Strip magic words, categories and interwikis?
	
	wfDebugEnd( __METHOD__ );
	return array( $nblines, $lines );
}




class statistics
{
	private $text;
	private $nblines, $lines;
	
	private $regexInterwikis;
	private $regexCategories;
	
	public $stat;
	
	function __construct( $text, $nblines, $lines ) {
		
		// Inputs
		$this->text = $text;
		$this->nblines = $nblines;
		$this->lines = $lines;
		
		// Outputs
		$this->stat = array();
		
		// Intermediary variables
		global $wgInterwikis;
		$this->regexInterwikis = '/\[\[('.implode( '|',$wgInterwikis ).'):(.*)\]\]/uU';
		
		global $wgCategoryLocalName;
		$this->regexCategories = '/\[\[(?:'.$wgCategoryLocalName.'|Category):(.*)\]\]/uU';
	}
	
	######################
	# Updating functions #
	######################
	
	/**
	 * Update the status 'sizelines' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 */
	function update_sizelines_line( $l ) {
		
		$this->stat['sizelines'][$l] = strlen( $this->lines[$l] );
	}
	
	/**
	 * Update the status 'sizelines'
	 */
	function update_sizelines() {
		
		for( $l=0; $l<$this->nblines; $l++ ) $this->update_sizelines_line( $l );
	}
	
	/**
	 * Update the status 'frameline' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 */
	function update_framelines_line( $l ) {
		
		if( $this->lines[$l][0] == ' ' ) $this->stat['frames']['framelines'][$l] = true;
		else unset( $this->stat['frames']['framelines'][$i] );
	}
	
	/**
	 * Update the status 'framelines'
	 */
	function update_framelines() {
		
		for( $l=0; $l<$this->nblines; $l++ ) $this->update_framelines_line( $l );
	}
	
	/**
	 * Update the status 'interwikis' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 */
	function update_interwikis_line( $l ) {
		
		if( preg_match( $this->regexInterwikis, $this->lines[$l] ) ) {
			
			preg_match_all( $this->regexInterwikis, $this->lines[$l], $localmatches, PREG_SET_ORDER );
			$this->stat['interwikis']['interwikis'][$l] = array();
			$this->stat['interwikis']['nbinterwikis'] += count($localmatches);
			
			for( $i=0; $i<count($localmatches); $i++ ) {
				
				$this->stat['interwikis']['interwikis'][$l][$i] = $localmatches[$i][1].':'.$localmatches[$i][2];
			}
		}
	}
	
	/**
	 * Update the status 'interwikis'
	 */
	function update_interwikis() {
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_interwikis_line( $l );
		}
	}
	
	/**
	 * Update the status 'categories' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 */
	function update_categories_line( $l ) {
		
		if( preg_match( $this->regexCategories, $this->lines[$l] ) ) {
			
			preg_match_all( $this->regexCategories, $this->lines[$l], $localmatches, PREG_SET_ORDER );
			$this->stat['categories']['categories'][$i] = array();
			$this->stat['categories']['nbcategories'] += count($localmatches);
			
			for( $i=0; $i<count($localmatches); $i++ ) {
				
				$this->stat['categories']['categories'][$l][$i] = $localmatches[$i][1];
			}
		}
	}
	
	/**
	 * Update the status 'categories'
	 */
	function update_categories() {
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_categories_line( $l );
		}
	}
	
	/**
	 * Update the status 'blanklines' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 */
	function update_blanklines_line( $l ) {
		
		if( $this->stat['sizelines'][$l] == 0 ) $this->stat['blanklines'][$l] = true;
		else unset( $this->stat['blanklines'][$l] );
	}
	
	/**
	 * Update the status 'blanklines'
	 */
	function update_blanklines() {
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_blanklines_line( $l );
		}
	}
	
	/**
	 * Update the status 'wikibulletedlists' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 * @param boolean $ilsoldlist: this line is an old raw-bulleted list
	 */
	function update_wikibulletedlists_line( $l, $isoldwikilist = false ) {
		
		if( preg_match( '/^\*/u', $this->lines[$l] ) ) {
			
			if( !$isoldwikilist ) {
				
				$this->stat['lists']['wikibulletedlists'][$l] = true;
				$this->stat['lists']['nbwikibulletedlists']++;
			}
		}
		else if( $isoldwikilist ) {
			
			unset( $this->stat['lists']['wikibulletedlists'][$l] );
			$this->stat['lists']['nbwikibulletedlists']--;
		}
	}
	
	/**
	 * Update the status 'rawbulletedlists' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 * @param boolean $ilsoldlist: this line is an old raw-bulleted list
	 */
	function update_rawbulletedlists_line( $l, $isoldrawlist = false ) {
		
		global $wgBulletedList;
		
		for( $i=0; $i<count($wgBulletedList); $i++ ) {
			
			if( preg_match( '/^ *'.$wgBulletedList[$i].'/u', $this->lines[$l] ) ) {
				
				if( !$isoldrawlist ) {
					
					$this->stat['lists']['rawbulletedlists'][$l] = array( 'type'   => $j+1,
					                                                      'spaces' => strlen( preg_replace( '/^( *)'.$wgBulletedList[$i].'.*/u', '$1', $this->lines[$l] ) )
					                                                    );
					$this->stat['lists']['nbrawbulletedlists']++;
					break;
				}
			}
		}
		
		if( $isoldrawlist ) {
			
			if( $i == count($wgBulletedList) ) {
				unset( $this->stat['lists']['rawbulletedlists'][$l] );
				$this->stat['lists']['nbrawbulletedlists']--;
			}
		}
	}
	
	/**
	 * Update the status 'wikibulletedlists'
	 */
	function update_wikibulletedlists() {
		
		$this->stat['lists']['nbwikibulletedlists'] = 0;
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_wikibulletedlists_line( $l );
		}
	}
	
	/**
	 * Update the status 'rawbulletedlists'
	 */
	function update_rawbulletedlists() {
		
		$this->stat['lists']['nbrawbulletedlists'] = 0;
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_rawbulletedlists_line( $l );
		}
	}
	
	/**
	 * Update the status 'wikinumberedlists' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 * @param boolean $ilsoldlist: this line is an old raw-numbered list
	 */
	function update_wikinumberedlists_line( $l, $isoldwikilist = false ) {
		
		if( preg_match( '/^#/u', $this->lines[$l] ) ) {
			
			if( !$isoldwikilist ) {
				
				$this->stat['lists']['wikinumberedlists'][$l] = true;
				$this->stat['lists']['nbwikinumberedlists']++;
			}
		}
		else if( $isoldwikilist ) {
			
			unset( $this->stat['lists']['wikinumberedlists'][$l] );
			$this->stat['lists']['nbwikinumberedlists']--;
		}
	}
	
	/**
	 * Update the status 'rawnumberedlists' of a line
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 * @param boolean $ilsoldlist: this line is an old raw-numbered list
	 */
	function update_rawnumberedlists_line( $l, $isoldrawlist = false ) {
		
		global $wgNumberedList;
		
		for( $i=0; $i<count($wgNumberedList); $i++ ) {
			
			if( preg_match( '/^ *'.$wgNumberedList[$i].'/u', $this->lines[$l] ) ) {
				
				if( !$isoldrawlist ) {
					
					$this->stat['lists']['rawnumberedlists'][$l] = array( 'type'   => $j+1,
					                                                      'spaces' => strlen( preg_replace( '/^( *)'.$wgNumberedList[$i].'.*/u', '$1', $this->lines[$l] ) ),
					                                                      'number' => preg_replace( '/^ *'.$wgNumberedList[$j].'.*/u', '$1', $this->lines[$l] )
					                                                    );
					$this->stat['lists']['nbrawnumberedlists']++;
					break;
				}
			}
		}
		
		if( $isoldrawlist ) {
			
			if( $i == count($wgNumberedList) ) {
				unset( $this->stat['lists']['rawnumberedlists'][$l] );
				$this->stat['lists']['nbrawnumberedlists']--;
			}
		}
	}
	
	/**
	 * Update the status 'wikinumberedlists'
	 */
	function update_wikinumberedlists() {
		
		$this->stat['lists']['nbwikinumberedlists'] = 0;
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_wikinumberedlists_line( $l );
		}
	}
	
	/**
	 * Update the status 'rawnumberedlists'
	 */
	function update_rawnumberedlists() {
		
		$this->stat['lists']['nbrawnumberedlists'] = 0;
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_rawnumberedlists_line( $l );
		}
	}
	
	/**
	 * Update the status 'nbcapitals'
	 */
	function update_nbcapitals() {
		
		$this->stat['nbcapitals'] = preg_match_all( '/[A-Z]/', $this->text, $localmatches );
	}
	
	/**
	 * Update the status 'nbtemplates'
	 */
	function update_nbtemplates() {
		
		$this->stat['nbtemplates']['nbtemplates'] = 0;
		$this->stat['nbtemplates']['nbmisformedtemplates'] = 0; // This is a minimum
		
		$temptext = $this->text;
		$temptext = preg_replace( '/\{\{\{.*\}\}\}/uUm', '', $temptext );
		$temptext = preg_replace( '/\{\{(.*)\}\}/uUm', '$1', $temptext, -1, $this->stat['nbtemplates']['nbtemplates'] );
		
		$this->stat['nbtemplates']['nbmisformedtemplates'] = preg_match_all( '/\{\{/u', $temptext, $localmatches )
		                                                   + preg_match_all( '/\}\}/u', $temptext, $localmatches );
		
		/*
		for( $i=0; $i<$nblines; $i++ ) {
			
			// [^#\{]? to remove `{{{' and `{{#' but it seems to be non-working
			// TODO other magic words -> perhaps add other counters (`{{#', `{{{') and in this case the number of templates is only a substraction of these
			$nbTemplatesLineBegin = preg_match_all( '/\{\{/u', $lines[$i], $localmatches ) - preg_match_all( '/\{\{DEFAULTSORT/uUi', $lines[$i], $localmatches );
			$nbTemplatesLineEnd = preg_match_all( '/\}\}/u', $lines[$i], $localmatches ) - preg_match_all( '/\{\{DEFAULTSORT/uUi', $lines[$i], $localmatches );
			if( $nbTemplates+$nbTemplatesLineBegin-$nbTemplatesLineEnd < 0 ) $nbMisformedTemplates++;
			$nbTemplates = $nbTemplates + $nbTemplatesLineBegin;
		}
		*/
	}
	
	/**
	 * Update the status 'frames'
	 */
	function update_frames() {
		
		$this->stat['frames']['frames'] = array();
		
		if( count($this->stat['frames']['framelines']) > 0 ) {
			
			for( $l=0; $l<$this->nblines; $l++ ) {
				
				if( $this->stat['frames']['framelines'][$l] ) {
					
					$k = 1;
					while( $l+$k<$this->nblines && $this->stat['frames']['framelines'][$l+$k] ) $k++;
					
					$this->stat['frames']['frames'][count($this->stat['frames']['frames'])] = array( $l, $k );
					$l = $l+$k;
				}
			}
		}
	}
	
	/**
	 * Update the status 'wikititles'
	 * 
	 * @param integer $l: number of the line (between 0 and $nblines-1)
	 */
	function update_wikititles_line( $l ) {
			
		     if( preg_match( '/^={6}.*={6} *$/u', $this->lines[$l] ) ) $this->stat['titles']['wikititles'][$l] = 6;
		else if( preg_match( '/^={5}.*={5} *$/u', $this->lines[$l] ) ) $this->stat['titles']['wikititles'][$l] = 5;
		else if( preg_match( '/^={4}.*={4} *$/u', $this->lines[$l] ) ) $this->stat['titles']['wikititles'][$l] = 4;
		else if( preg_match( '/^={3}.*={3} *$/u', $this->lines[$l] ) ) $this->stat['titles']['wikititles'][$l] = 3;
		else if( preg_match( '/^={2}.*={2} *$/u', $this->lines[$l] ) ) $this->stat['titles']['wikititles'][$l] = 2;
		else if( preg_match( '/^={1}.*={1} *$/u', $this->lines[$l] ) ) $this->stat['titles']['wikititles'][$l] = 1;
		else unset( $this->stat['titles']['wikititles'][$l] );
	}
	
	function update_wikititles() {
		
		for( $l=0; $l<$this->nblines; $l++ ) {
			
			$this->update_wikititles_line( $l );
		}
	}
	
	function update_wikiparagraphs() {
		
		$insideTemplates = 0;
		$this->stat['paragraphs']['wikiparagraphlines'] = array();
		
		for( $l=1; $l<$this->nblines-1; $l++ ) {
			
			// Update the counter 'templates'; as above this is a trivial checking
			$insideTemplates += preg_match_all( '/\{\{/u', $lines[$i], $localmatches )
			                  - preg_match_all( '/\}\}/u', $lines[$i], $localmatches );
			
			if( $insideTemplates > 0 ) continue;
			
			if( $this->stat['blanklines'][$l] || $this->stat['frames']['framelines'][$l] || $this->stat['lists']['wikibulletedlists'][$l] || $this->stat['lists']['wikinumberedlists'][$l] || $this->stat['titles']['wikititles'][$l] ) continue;
			
			if( !( $this->stat['blanklines'][$l-1] || $this->stat['frames']['framelines'][$l-1] || $this->stat['lists']['wikibulletedlists'][$l-1] || $this->stat['lists']['wikinumberedlists'][$l-1] || $this->stat['titles']['cleantitles'][$l-1] ) ) continue;
			
			if( !( $this->stat['blanklines'][$l+1] || $this->stat['frames']['framelines'][$l+1] || $this->stat['lists']['wikibulletedlists'][$l+1] || $this->stat['lists']['wikinumberedlists'][$l+1] || $this->stat['titles']['cleantitles'][$l+1] ) ) continue;
			
			$this->stat['paragraphs']['wikiparagraphlines'][$l] = true;
		}
	}
	
	function update_rawparagraphs() {
		
		$insideTemplates = 0;
		$this->stat['paragraphs']['rawparagraphlines'] = array();
		
		for( $l=1; $l<$this->nblines-1; $l++ ) {
			
			//echo $i.'-';
			// Update the counter 'templates'; as above this is a trivial checking
			$insideTemplates += preg_match_all( '/\{\{/u', $lines[$i], $localmatches )
			                  - preg_match_all( '/\}\}/u', $lines[$i], $localmatches );
			
			//echo $insideTemplates.'-';
			if( $insideTemplates > 0 ) continue;
			
			if( $this->stat['lists']['wikibulletedlists'][$l] || $this->stat['lists']['wikinumberedlists'][$l] ) continue;
			
			if( $this->stat['titles']['wikititles'][$l] ) continue;
			
			if( $this->stat['blanklines'][$l] || $this->stat['frames']['framelines'][$l] ) continue;
			
			if( !$this->stat['blanklines'][$l+1] && !$this->stat['framelines'][$l+1] && !$this->stat['lists']['wikibulletedlists'][$l+1] && !$this->stat['lists']['wikinumberedlists'][$l+1] && !$this->stat['titles']['cleantitles'][$l+1] ) {
				
				$this->stat['paragraphs']['rawparagraphlines'][$l] = true;
				$this->stat['paragraphs']['nbrawparagraphlines']++;
				$k = 1;
				while( $l+$k<$this->nblines-1 && !$this->stat['blanklines'][$l+$k] && !$this->stat['framelines'][$l+$k] && !$this->stat['lists']['wikibulletedlists'][$l+$k] && !$this->stat['lists']['wikinumberedlists'][$l+$k] && !$this->stat['titles']['cleantitles'][$l+$k] ) {
					
					$this->stat['paragraphs']['rawparagraphlines'][$i+$k] = true;
					$this->stat['paragraphs']['nbrawparagraphlines']++;
					$k++;
				}
				
				$this->stat['paragraphs']['rawparagraphs'][$this->stat['paragraphs']['nbrawparagraphs']] = array( $i, $k );
				
				$l = $l+$k;
			}
		}
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                                STATISTICS BY LEVELS                                                                               //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Statistics level 11 (objective line-by-line statistics)
	 * 
	 *   - size of each line
	 *   - number of blank lines (TODO: measure this number in a well-wikified article, ~40%?)
	 */
	
	function statisticsL11() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 1-1 (objective line-by-line statistics)' );
		
		#####################
		# Size of the lines #
		#####################
		
		// Create, in the section 'sizelines':
		// * array 'sizelines' of size $nblines:
		//   * each line is a number which is the size of the corresponding line
		
		wfDebugOpBegin( __METHOD, 'sizelines', 'Size of each line' );
		
		$this->update_sizelines();
		
		wfDebugOpEnd( __METHOD__, 'sizelines' );
		
		
		
		###############
		# Blank lines #
		###############
		
		// Create, in the section 'blanklines':
		// * array 'blanklines' of size $nblines:
		//   * each line is a boolean which is true if the line is empty
		// * number 'nbblanklines': number of empty lines
		
		wfDebugOpBegin( __METHOD, 'blanklines', 'Blank lines' );
		
		$this->update_blanklines();
		
		wfDebugOpEnd( __METHOD__, 'blanklines' );
		
		
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 12 (objective meso-scale statistics)
	 * 
	 *   - nop
	 */
	function statisticsL12() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 1-2 (objective meso-scale statistics)' );
		
		// nop
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 13 (objective text-wide statistics)
	 * 
	 *   - number of capitals
	 */
	function statisticsL13() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 1-3 (objective text-wide statistics)' );
		
		######################
		# Number of capitals #
		######################
		
		// Create, in the section 'nbcapitals':
		// * number 'nbcapitals': number of capital letters
		
		wfDebugOpBegin( __METHOD__, 'nbcapitals', 'Number of capitals' );
		
		$this->update_nbcapitals();
		
		wfDebugOpEnd( __METHOD__, 'nbcapitals' );
		
		
		
		
		#################################
		# General structure of the page #
		#################################
		
		// Disambiguation: templates $wgDisambiguationTemplates
		// Notices: templates $wgNoticesTemplates
		// Infobox: one of the templates $wgInfoboxTemplates
		// Introduction: 
		// TOC: 
		// Article: title - dvpt - title - dvpt ...
		// Footnotes:
		// References:
		// External links: 
		// Portals: 
		// Categories: 
		// Interwikis: 
		
		wfDebugOpBegin( __METHOD__, 'structure', 'Structure of the document' );
		
		$stat['structure'] = array( 'disambig'   => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'notices'    => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'infobox'    => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'intro'      => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'toc'        => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'article'    => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'footnotes'  => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'references' => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'extlinks'   => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'portals'    => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'categories' => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ),
		                            'interwikis' => array( 'begin'=>-1, 'end'=>-1, 'other'=>array(), 'nbother'=>0 ));
		
		wfDebugOpEnd( __METHOD__, 'structure' );
		
		
		
		wfDebugEnd( __METHOD__ );
	}
	
	/**
	 * Statistics level 21 (wikisyntaxed line-by-line statistics)
	 * 
	 *   - number of lines beginning with a white space (=frame) (TODO: measure this number in a well-wikified article, <10%?)
	 *   - use of wiki-lists (`*'/`#')
	 *   - wikititles
	 *   - categories
	 *   - interwikis
	 */
	function statisticsL21() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 2-1 (wikisyntaxed line-by-line statistics)' );
		
		######################################
		# Lines beginning with a white space #
		######################################
		
		// Create, in the section 'framelines':
		// * array 'framelines' of size $nblines:
		//   * each line is a boolean which is true if the line begin with a space (=frame)
		// * number 'nbframelines': number of lines beginning with a space
		
		wfDebugOpBegin( __METHOD, 'framelines', 'Lines beginning with a white space' );
		
		$this->update_framelines();
		
		wfDebugOpEnd( __METHOD__, 'framelines' );
		
		
		
		##################
		# Bulleted lists #
		##################
		
		// Create, in the section 'bulletedlists':
		// * array 'bulletedlists' of size $nblines:
		//   * each line is an array of size 2:
		//     * number 'type': -1 for a non-list line; 0 for a wiki-list line; $i+1 for a raw-list line (where $i is the index of the dash in $wgBulletedList)
		//     * number 'spaces': -1 for a non-list line; $nbspaces for the number of spaces before the dash/star/...
		// * number 'nbrawbulletedlists': number of lines recognize as a raw (unwikified) bulleted list
		// * number 'nbwikibulletedlists': number of lines recognize as a wikified bulleted list
		
		wfDebugOpBegin( __METHOD__, 'wikibulletedlists', 'Bulleted lists' );
		
		$this->update_wikibulletedlists();
		
		wfDebugOpEnd( __METHOD__, 'wikibulletedlists' );
		
		
		
		##################
		# Numbered lists #
		##################
		
		// Create, in the section 'numberedlists':
		// * array 'numberedlists' of size $nblines:
		//   * each line is an array of size 3:
		//     * number 'type': -1 for a non-list line; 0 for a wiki-list line; $i+1 for a raw-list line (where $i is the index of the dash in $wgNumberedList)
		//     * number 'spaces': -1 for a non-list line; $nbspaces for the number of spaces before the dash/number sign/...
		//     * number 'number': number indicated as introducing the line
		// * number 'nbrawnumberedlists': number of lines recognize as a raw (unwikified) numbered list
		// * number 'nbwikinumberedlists': number of lines recognize as a wikified numbered list
		
		wfDebugOpBegin( __METHOD__, 'wikinumberedlists', 'Numbered lists' );
		
		$this->update_wikinumberedlists();
		
		wfDebugOpEnd( __METHOD__, 'wikinumberedlists' );
		
		
		
		##########
		# Titles #
		##########
		
		wfDebugOpBegin( __METHOD__, 'wikititles', 'Titles' );
		
		$this->update_wikititles();
		
		wfDebugOpEnd( __METHOD__, 'wikititles' );
		
		
		
		##############
		# Interwikis #
		##############
		
		wfDebugOpBegin( __METHOD__, 'interwikis', 'Interwikis' );
		
		$this->update_interwikis();
		
		wfDebugOpEnd( __METHOD__, 'interwikis' );
		
		
		
		##############
		# Categories #
		##############
		
		wfDebugOpBegin( __METHOD__, 'categories', 'Categories' );
		
		$this->update_categories();
		
		wfDebugOpEnd( __METHOD__, 'categories' );
		
		
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 22 (wikisyntaxed meso-scale statistics)
	 *   
	 *   - cutting off into paragraphs
	 *   * statistics on the number of frames (TODO: measure this on standard articles and on computer science articles (max of frames))
	 */
	function statisticsL22() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 2-2 (wikisyntaxed meso-scale statistics)' );
		
		####################
		# Number of frames #
		####################
		
		// Create, in the section 'frames':
		// * number 'nbframes': number of visual frames
		// * array 'frames' of size 'nbframes':
		//   * each line is an array of size 2:
		//     * number 'begin': first line of the frame (between 0 and $nblines-1)
		//     * number 'size': size, in lines, of the frame
		
		wfDebugOpBegin( __METHOD__, 'frames', 'Number of frames' );
		
		$this->update_frames();
		
		wfDebugOpEnd( __METHOD__, 'frames' );
		
		
		
		###############################
		# Cutting off into paragraphs #
		###############################
		
		// Try to determine the number and the size of paragraphs
		// Create, in the section 'blanklines':
		// * array 'blanklines' of size $nblines:
		//   * each line is a boolean which is true if the line is empty
		// * number 'nbblanklines': number of empty lines
		
		wfDebugOpBegin( __METHOD__, 'paragraphs', 'Cutting off into paragraphs' );
		
		$this->update_wikiparagraphs();
		
		wfDebugOpEnd( __METHOD__, 'paragraphs' );
		
		
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 23 (wikisyntaxed text-wide statistics)
	 * 
	 *   - number of templates
	 */
	function statisticsL23() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 2-3 (wikisyntaxed text-wide statistics)' );
		
		#######################
		# Number of templates #
		#######################
		
		// NB: this count could be improved: }} {{Templat}} {{ = 1 template and 2 probably-misformed templates
		// or use a full-text regex and cutting off into real templates (see the implementation in MW)
		
		// Create, in the section 'nbtemplates':
		// * array 'blanklines' of size $nblines:
		//   * each line is a boolean which is true if the line is empty
		// * number 'nbblanklines': number of empty lines
		
		wfDebugOpBegin( __METHOD__, 'nbtemplates', 'Number of templates' );
		
		$this->update_nbtemplates();
		
		wfDebugOpEnd( __METHOD__, 'nbtemplates' );
		
		
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 31 (unwikisyntaxed line-by-line statistics)
	 * 
	 *   - use of unwikibullets (`-'/`1. 2.')
	 */
	function statisticsL31() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 3-1 (unwikisyntaxed line-by-line statistics)' );
		
		##################
		# Bulleted lists #
		##################
		
		// Create, in the section 'bulletedlists':
		// * array 'bulletedlists' of size $nblines:
		//   * each line is an array of size 2:
		//     * number 'type': -1 for a non-list line; 0 for a wiki-list line; $i+1 for a raw-list line (where $i is the index of the dash in $wgBulletedList)
		//     * number 'spaces': -1 for a non-list line; $nbspaces for the number of spaces before the dash/star/...
		// * number 'nbrawbulletedlists': number of lines recognize as a raw (unwikified) bulleted list
		// * number 'nbwikibulletedlists': number of lines recognize as a wikified bulleted list
		
		wfDebugOpBegin( __METHOD__, 'rawbulletedlists', 'Bulleted lists' );
		
		$this->update_rawbulletedlists();
		
		wfDebugOpEnd( __METHOD__, 'rawbulletedlists' );
		
		
		
		##################
		# Numbered lists #
		##################
		
		// Create, in the section 'numberedlists':
		// * array 'numberedlists' of size $nblines:
		//   * each line is an array of size 3:
		//     * number 'type': -1 for a non-list line; 0 for a wiki-list line; $i+1 for a raw-list line (where $i is the index of the dash in $wgNumberedList)
		//     * number 'spaces': -1 for a non-list line; $nbspaces for the number of spaces before the dash/number sign/...
		//     * number 'number': number indicated as introducing the line
		// * number 'nbrawnumberedlists': number of lines recognize as a raw (unwikified) numbered list
		// * number 'nbwikinumberedlists': number of lines recognize as a wikified numbered list
		
		wfDebugOpBegin( __METHOD__, 'rawnumberedlists', 'Numbered lists' );
		
		$this->update_rawnumberedlists();
		
		wfDebugOpEnd( __METHOD__, 'rawnumberedlists' );
		
		
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 32 (unwikisyntaxed meso-scale statistics)
	 *   
	 *   - nop
	 */
	function statisticsL32() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 3-2 (unwikisyntaxed meso-scale statistics)' );
		
		// nop
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
	/**
	 * Statistics level 33 (unwikisyntax text-wide statistics)
	 * 
	 *   - nop
	 */
	function statisticsL33() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 3-3 (unwikisyntaxed text-wide statistics)' );
		
		// nop
		
		wfDebugEnd( __METHOD__ );
	}
	
	
	
	
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                                DISPLAY STATISTICS                                                                                 //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function display_statistics( $nblines, $level, $stat ){
		
		echo "* Statistics:\n";
		
		echo "  * Number of lines: $nblines\n";
		
		#############
		# Objective #
		#############
		
		#
		# Level 1
		#
		
		echo "  * Level 1-1 (objective line-by-line)\n";
		
		// Sizelines
		echo '    * Size of each line';
		echo "\n";
		echo '      * mean = ';
		printf( '%.1f', array_sum($this->stat['sizelines'])/($this->nblines-4) );
		echo "\n";
		echo '      * {';
		for( $l=0; $l<$this->nblines; $l++ )
			echo ' '.$this->stat['sizelines'][$l];
		echo ' }';
		echo "\n";
		
		// Blanklines
		echo '    * Blank lines:';
		echo "\n";
		echo '      * number = ';
		echo count($this->stat['blanklines']);
		echo "\n";
		if( count($this->stat['blanklines']) > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', count($this->stat['blanklines'])/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['blanklines'][$l] )
					echo ' '.$l;
			echo ' }';
			echo "\n";
		}
		
		
		#
		# Level 2
		#
		
		//echo "  * Level 1-2 (objective meso-scale)\n";
		
		
		#
		# Level 3
		#
		
		echo "  * Level 1-3 (objective full-text)\n";
		
		// Number of capitals
		echo '    * Number of capitals = ';
		echo $this->stat['nbcapitals'].'/'.array_sum($this->stat['sizelines']);
		echo ' = ';
		printf( '%.2f%%', $this->stat['nbcapitals']/array_sum($this->stat['sizelines'])*100 );
		echo "\n";
		
		
		################
		# Wikisyntaxed #
		################
		
		#
		# Level 1
		#
		
		echo "  * Level 2-1 (wikisyntaxed line-by-line)\n";
		
		// Framelines
		echo '    * Framelines:';
		echo "\n";
		echo '      * number = '.count($this->stat['frames']['framelines']);
		echo "\n";
		if( count($this->stat['frames']['framelines']) > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', count($this->stat['frames']['framelines'])/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['frames']['framelines'][$l] )
					echo ' '.$l;
			echo ' }';
			echo "\n";
		}
		
		// Wikibulletedlists
		echo '    * Wiki-bulleted lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->stat['lists']['nbwikibulletedlists'];
		echo "\n";
		if( $this->stat['lists']['nbwikibulletedlists'] > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->stat['lists']['nbwikibulletedlists']/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['lists']['wikibulletedlists'][$l] )
					echo ' '.$l;
			echo ' }';
			echo "\n";
		}
		
		// Wikinumberedlists
		echo '    * Wiki-numbered lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->stat['lists']['nbwikinumberedlists'];
		echo "\n";
		if( $this->stat['lists']['nbwikinumberedlists'] > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->stat['lists']['nbwikinumberedlists']/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['lists']['wikinumberedlists'][$l] )
					echo ' '.$l;
			echo ' }';
			echo "\n";
		}
		
		// Wikititles
		echo '    * Wiki titles:';
		echo "\n";
		echo '      * number = ';
		echo count($this->stat['titles']['wikititles']);
		echo "\n";
		if( count($this->stat['titles']['wikititles']) > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', count($this->stat['titles']['wikititles'])/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['titles']['wikititles'][$l] )
					printf( ' (%d,%d)', $l, $this->stat['titles']['wikititles'][$l] );
			echo ' }';
			echo "\n";
		}
		
		
		#
		# Level 2
		#
		
		echo "  * Level 2-2 (wikisyntaxed meso-scale)\n";
		
		// Frames
		echo '    * Frames:';
		echo "\n";
		echo '      * number = ';
		echo count($this->stat['frames']['frames']);
		echo "\n";
		if( count($this->stat['frames']['frames']) > 0 ) {
			echo '      * {';
			for( $i=0; $i<count($this->stat['frames']['frames']); $i++ )
				printf( ' (%d,%d)', $this->stat['frames']['frames'][$i][0], $this->stat['frames']['frames'][$i][1] );
			echo ' }';
			echo "\n";
		}
		
		// Paragraphs
		echo '    * Wiki paragraphs:';
		echo "\n";
		echo '      * number = ';
		echo count($this->stat['paragraphs']['wikiparagraphlines']);
		echo "\n";
		if( count($this->stat['paragraphs']['wikiparagraphlines']) > 0 ) {
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['paragraphs']['wikiparagraphlines'][$l] )
					echo ' '.$l;
			echo ' }';
			echo "\n";
		}
		
		
		#
		# Level 3
		#
		
		echo "  * Level 2-3 (wikisyntaxed full-text)\n";
		
		// Nbtemplates
		echo '    * Number of templates';
		echo "\n";
		echo '      * well-formed = ';
		echo $this->stat['nbtemplates']['nbtemplates'];
		echo "\n";
		echo '      * probably-misformed = ';
		echo $this->stat['nbtemplates']['nbmisformedtemplates'];
		echo "\n";
		
		
		##################
		# Unwikisyntaxed #
		##################
		
		#
		# Level 1
		#
		
		echo "  * Level 3-1 (unwikisyntaxed line-by-line)\n";
		
		// Rawbulletedlists
		echo '    * Raw-bulleted lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->stat['lists']['nbrawbulletedlists'];
		echo "\n";
		if( $this->stat['lists']['nbrawbulletedlists'] > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->stat['lists']['nbrawbulletedlists']/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['lists']['rawbulletedlists'][$l] )
					printf( ' (%d,%d,%d)', $l, $this->stat['lists']['rawbulletedlists'][$l]['type'], $this->stat['lists']['rawbulletedlists'][$l]['spaces'] );
			echo ' }';
			echo "\n";
		}
		
		// Rawnumberedlists
		echo '    * Raw-numbered lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->stat['lists']['nbrawnumberedlists'];
		echo "\n";
		if( $this->stat['lists']['nbrawnumberedlists'] > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->stat['lists']['nbrawnumberedlists']/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['lists']['rawnumberedlists'][$l] )
					printf( ' (%d,%d,%d,%d)', $l, $this->stat['lists']['rawnumberedlists'][$l]['type'], $this->stat['lists']['rawnumberedlists'][$l]['spaces'], $this->stat['lists']['rawnumberedlists'][$l]['number'] );
			echo ' }';
			echo "\n";
		}
		
		#
		# Level 2
		#
		
		echo "  * Level 3-2 (unwikisyntaxed meso-scale)\n";
		
		// Paragraphs
		echo '    * Raw paragraphs:';
		echo "\n";
		echo '      * number of lines = ';
		echo count($this->stat['paragraphs']['rawparagraphlines']);
		echo "\n";
		if( count($this->stat['paragraphs']['rawparagraphlines']) > 0 ) {
			echo '      * lines = {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['paragraphs']['rawparagraphlines'][$l] )
					echo ' '.$l;
			echo ' }';
			echo "\n";
		}
		
		
		#
		# Level 3
		#
		
		//echo "  * Level 3-3 (unwikisyntaxed full-text)\n";
		
		
	}
	
} // End class statistics

















function trivial_layouting( &$nblines, &$lines, &$statistics ) {
	
	global $wgBulletedList, $wgNbLinesMaxBetweenTwoBullets;
	global $wgNumberedList, $wgNbLinesMaxBetweenTwoNumbers;
	
	#################
	# Bullet-lister #
	#################
	
	// First pass: remplace all dashes by wiki stars
	
	if( $statistics['bulletedlists'][0] ) {
		
		$linesSinceLastBullet = $nblines-1;
		
		for( $i=0; $i<$nblines-1; $i++ ) {
			
			if( $linesSinceLastBullet > $wgNbLinesMaxBetweenTwoBullets ) continue;
			
			if( $statistics['bulletedlists'][4][$i][0] == -1 || $statistics['bulletedlists'][4][$i][0] == 0 ) continue;
			
			if( $statistics['bulletedlists'][4][$i][0] == $statistics['bulltedlists'][4][$i+1][0] && $statistics['bulletedlists'][4][$i][1] == $statistics['bulletedlists'][4][$i+1][1] ) {
				
				$lines[$i]   = preg_replace( '/^ *'.$wgBulletedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '* $1', $lines[$i]   );
				$lines[$i+1] = preg_replace( '/^ *'.$wgBulletedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '* $1', $lines[$i+1] );
				$statistics['sizelines'][$i] = count( $lines[$i] );
				$statistics['sizelines'][$i+1] = count( $lines[$i+1] );
			}
			
			/*else if( $statistics['sizelines'][$i] == 0 && $statistics['bulletedlists'][4][$i][0] == $statistics['bulletedlists'][4][$i+2][0] && $statistics['bulletedlists'][4][$i][1] == $statistics['bulltedlists'][4][$i+2][1] ) {
					
					$lines[$i]   = preg_replace( '/^ *'.$wgBulletedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '* $1', $lines[$i]   );
					$lines[$i+2] = preg_replace( '/^ *'.$wgBulletedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '* $1', $lines[$i+2] );
					unset( $lines[$i+1] );
					unset( $sizelines[$i+1] );
					$i++;
			}*/
		}
		//array_values( $lines );
		//array_values( $sizelines );
		//$nblines = count( $lines );
	}
   
   if( $statistics['numberedlists'][0] ) {
		
		for( $i=0; $i<$nblines-1; $i++ ) {
			
			if( $statistics['numberedlists'][4][$i][0] == -1 || $statistics['numberedlists'][4][$i][0] == 0 ) continue;
			
			if( $statistics['numberedlists'][4][$i][0] == $statistics['bulltedlists'][4][$i+1][0] && $statistics['numberedlists'][4][$i][1] == $statistics['numberedlists'][4][$i+1][1] && $statistics['numberedlists'][4][$i][2]+1 == $statistics['numberedlists'][4][$i+1][2] ) {
				
				$lines[$i]   = preg_replace( '/^ *'.$wgNumberedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '# $1', $lines[$i]   );
				$lines[$i+1] = preg_replace( '/^ *'.$wgNumberedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '# $1', $lines[$i+1] );
				$statistics['numberedlists'][4][$i] = array( 0, 0, -1 );
				$statistics['numberedlists'][4][$i+1] = array( 0, 0, -1 );
				$statistics['sizelines'][$i] = count( $lines[$i] );
				$statistics['sizelines'][$i+1] = count( $lines[$i+1] );
			}
			
			/*else if( $statistics['sizelines'][$i] == 0 && $statistics['numberedlists'][4][$i][0] == $statistics['numberedlists'][4][$i+2][0] && $statistics['numberedlists'][4][$i][1] == $statistics['bulltedlists'][4][$i+2][1] ) {
					
					$lines[$i]   = preg_replace( '/^ *'.$wgNumberedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '* $1', $lines[$i]   );
					$lines[$i+2] = preg_replace( '/^ *'.$wgNumberedList[$statistics['bulltedlists'][4][$i][0]-1].' *(.*)$/u', '* $1', $lines[$i+2] );
					unset( $lines[$i+1] );
					unset( $sizelines[$i+1] );
					$i++;
			}*/
		}
		//array_values( $lines );
		//array_values( $sizelines );
		//$nblines = count( $lines );
	}
	
	
}











function trivial_layouting_stub( &$text, $newarticle ) {
	
	global $wgBulletedList, $wgNumberedList, $wgMaxLengthTitle, $wgMinNbFollowingLines, $wgMinLengthFollowingLines;
	global $wgCategoryLocalName, $wgInterwikis, $wgMinNbLinesParagraph, $wgMinLengthLinesParagraph;
	
	///////////////////////////
	// Common initialization //
	///////////////////////////
	
	$text = $text."\n\n";
	$lines = explode( "\n", $text );
	$nblines = count( $lines );
	$sizelines = array( $nblines );
	for( $i=0; $i<$nblines; $i++ ) {
		$sizelines[$i] = strlen( $lines[$i] );
	}
	
	echo $nblines;
	
	///////////////////////////////////////////////////
	// Replace lists begginning with $wgBulletedList //
	///////////////////////////////////////////////////
	
	if( preg_match( '/^ *(?:'.implode('|',$wgBulletedList).').*$/um', $text ) ) {
		
		$firstdash = array_fill( 0, $nblines, 0 );
		$typedash = array_fill( 0, $nblines, 0 );
		
		for( $i=0; $i<$nblines; $i++ ) {
			
			for( $j=0; $j<count($wgBulletedList); $j++ ) {
				
				if( preg_match( '/^( *)'.$wgBulletedList[$j].'.*$/u', $lines[$i] ) ) {
					
					$typedash[$i] = $j+1;
					$firstdash[$i] = strlen( preg_replace( '/^( *)'.$wgBulletedList[$j].'.*$/u', '$1', $lines[$i] ) );
				}
			}
		}
		
		for( $i=0; $i<$nblines-2; $i++ ) {
			
			if( $firstdash[$i] == 0 ) continue;
			
			if( $firstdash[$i] == $firstdash[$i+1] && $typedash[$i] == $typedash[$i+1] ) {
				
				$lines[$i]   = preg_replace( '/^ *'.$wgBulletedList[$typedash[$i]-1].' *(.*)$/u', '* $1', $lines[$i]   );
				$lines[$i+1] = preg_replace( '/^ *'.$wgBulletedList[$typedash[$i]-1].' *(.*)$/u', '* $1', $lines[$i+1] );
			}
			
			else if( $firstdash[$i+1] == 0 && $sizelines[$i] == 0 && $firstdash[$i] == $firstdash[$i+2] && $typedash[$i] == $typedash[$i+2] ) {
					
					$lines[$i]   = preg_replace( '/^ *'.$wgBulletedList[$typedash[$i]-1].' *(.*)$/u', '* $1', $lines[$i]   );
					$lines[$i+2] = preg_replace( '/^ *'.$wgBulletedList[$typedash[$i]-1].' *(.*)$/u', '* $1', $lines[$i+2] );
					unset( $lines[$i+1] );
					unset( $sizelines[$i+1] );
					$i++;
			}
		}
		array_values( $lines );
		array_values( $sizelines );
		$nblines = count( $lines );
	}
	
	echo $nblines;
	
	///////////////////////////////////////////////////
	// Replace lists begginning with $wgNumberedList //
	///////////////////////////////////////////////////
	
	if( preg_match( '/^ *\('.implode('|',$wgNumberedList).'\).*$/um', $text ) ) {
		
		$firstdash = array_fill( 0, $nblines, 0 );
		$typedash = array_fill( 0, $nblines, 0 );
		$numberdash = array_fill( 0, $nblines, -1 );
		for( $i=0; $i<$nblines; $i++ ) {
			
			$firstdash[$i] = 0;
			$typedash[$i] = 0;
			for( $j=0; $j<count($wgNumberedList); $j++ ) {
				
				if( preg_match( '/'.$wgNumberedList[$j].'/u', $lines[$i] ) ) {
					
					$typedash[$i] = $j+1;
					$firstdash[$i] = strlen( preg_replace( '/^( *)'.$wgNumberedList[$j].'.*$/u', '$1', $lines[$i] ) );
					$numberdash[$i] = intval( preg_replace( '/^(?: *)'.$wgNumberedList[$j].'.*$/u', '$1', $lines[$i] ) );
				}
			}
		}
		
		for( $i=0; $i<$nblines-2; $i++ ) {
			
			if( $firstdash[$i] == 0 ) continue;
			
			if( $firstdash[$i] == $firstdash[$i+1] && $typedash[$i] == $typedash[$i+1] && $numberdash[$i]+1 == $numberdash[$i+1] ) {
				
				$lines[$i]   = preg_replace( '/^ *'.$wgNumberedList[$typedash[$i]-1].' *(.*)$/u', '# $1', $lines[$i]   );
				$lines[$i+1] = preg_replace( '/^ *'.$wgNumberedList[$typedash[$i]-1].' *(.*)$/u', '# $1', $lines[$i+1] );
			}
			
			else if( $firstdash[$i+1] == 0 && $firstdash[$i] == $firstdash[$i+2] && $typedash[$i] == $typedash[$i+2] ) {
					
					$lines[$i]   = preg_replace( '/^ *'.$wgNumberedList[$typedash[$i]-1].' *(.*)$/u', '# $1', $lines[$i]   );
					$lines[$i+2] = preg_replace( '/^ *'.$wgNumberedList[$typedash[$i]-1].' *(.*)$/u', '# $1', $lines[$i+2] );
					unset( $lines[$i+1] );
					unset( $sizelines[$i+1] );
					$i++;
			}
		}
		array_values( $lines );
		array_values( $sizelines );
		$nblines = count( $lines );
	}
	
	
	
	//////////////////////////////////
	// Recreate a misformed outline //
	//////////////////////////////////
	
	if( $newarticle == true && !preg_match( '/^={1,6}.*={1,6} *$/m', $text ) ) {
		
		$previousPotentialTitle = -1;
		for( $i=0; $i<$nblines; $i++ ) {
			
			if( $sizelines[$i] == 0 ) continue;
			
			// If a lines has less than $wgMinLengthTitle characters
			if( $sizelines[$i] <= $wgMaxLengthTitle ) {
				
				// Verify the conditions on a "paragraph"
				$k = 0;
				for( $j=0; $j<$nblines && $j<$wgMinNbFollowingLines; $j++ ) {
					
					if( $sizelines[$i] == 0 ) $k++;
					
					if( $sizelines[$j+$i+$k] <= $wgMinLengthFollowingLines ) break;
				}
				
				// We match all the conditions, so make of the initial line a h2-title
				if( $j == $wgMinNbFollowingLines ) {
					
					$lines[$i] = preg_replace( '/^(.*)$/', '== $1 ==', $lines[$i] );
				}
			}
		}
	}
	
	
	
	/////////////////////////////////////////
	// Cut into text-categories-interwikis //
	/////////////////////////////////////////
	
	// Store all categories and interwikis and remove them from the text
	$endText = -1;
	$beginCategories = -1;
	$endCategories = -1;
	$beginInterwikis = -1;
	$endInterwikis = -1;
	$categoriesLines = array_fill( 0, $nblines, 0 );
	$interwikisLines = array_fill( 0, $nblines, 0 );
	$categories = array();
	$interwikis = array();
	//print '/\[\[(?:Category|'.$wgCategoryLocalName.'):.*\]\]/uU'."\n";
	//print '/\[\[('.implode('|',$wgInterwikis).'):.*\]\]/uU'."\n";
	//echo preg_match_all( '/\[\[(?:Category|'.$wgCategoryLocalName.'):.*\]\]/u', $lines[75], $localmatches )."\n";
	echo $nblines;
	for( $i=0; $i<$nblines; $i++ ) {
		
		echo $i;
		echo preg_match_all( '/\[\[(?:Category|'.$wgCategoryLocalName.'):.*\]\]/u', $lines[$i], $localmatches );
		echo ' ';
		//if( count($localmatches[0])>0 ) print_r( $localmatches[0] );
		array_merge( $categories, $localmatches[0] );
		$lines[$i] = preg_replace( '/\[\[(?:Category|'.$wgCategoryLocalName.'):.*\]\]/uU', '', $lines[$i] );
		
		preg_match_all( '/\[\[('.implode('|',$wgInterwikis).'):.*\]\]/uU', $lines[$i], $localmatches );
		array_merge( $interwikis, $localmatches[0] );
		$lines[$i] = preg_replace( '/\[\[('.implode('|',$wgInterwikis).'):.*\]\]/uU', '', $lines[$i] );
		
		$sizelines[$i] = strlen( $lines[$i] );
	}
	//array_unique( $categories );
	//array_unique( $interwikis );
	print_r( $categories );
	print_r( $interwikis );
	/** REMOVE THIS LINE **/ $text = implode( "\n", $lines );}function stub() {
	// Remove empty lines at the end and at the beginning
	$i = $nblines;
	while( $i>=0 && $sizelines[$i] == 0 ) {
		
		unset( $lines[$i] );
		unset( $sizelines[$i] );
		$i--;
	}
	array_values( $lines );
	array_values( $sizelines );
	$nblines = count( $lines );
	
	$i = 0;
	while( $i<$nblines && $sizelines[$i] == 0 ) {
		
		unset( $lines[$i] );
		unset( $sizelines[$i] );
		$i++;
	}
	array_values( $lines );
	array_values( $sizelines );
	$nblines = count( $lines );
	
	// Re-add the categories and interwikis
	$endText = $nblines;
	$lines[$nblines] = '';
	$sizelines[$nblines] = 0;
	$nblines = count( $lines );
	
	if( count($categories)>0 ) {
		
		$beginCategories = $nblines+1;
		
		for( $i=0; $i<count($categories); $i++ ) {
			
			$lines[$nblines] = preg_replace( '/\[\[(?:Category|'.$wgCategoryLocalName.'):(.*)\]\]/uU', '[[Catégorie:$1]]', $categories[$i] );
			$sizelines[$nblines] = strlen( $lines[$nblines] );
			$nblines = count( $lines );
		}
		
		$endCategories = $nblines;
		$lines[$nblines] = '';
		$sizelines[$nblines] = 0;
		$nblines = count( $lines );
	}
	
	if( count($interwikis)>0 ) {
		
		$beginInterwikis = $nblines+1;
		
		for( $i=0; $i<count($interwikis); $i++ ) {
			
			$lines[$nblines] = preg_replace( '/\[\[('.implode('|',$wgInterwikis).'):.*\]\]/uU', '[[Catégorie:$2]]', $interwikis[$i] );
			$sizelines[$nblines] = strlen( $lines[$nblines] );
			$nblines = count( $lines );
		}
		
		$endInterwikis = $nblines;
		$lines[$nblines] = '';
		$sizelines[$nblines] = 0;
		$nblines = count( $lines );
	}
	
	
	
	////////////////////////
	// Manage blank lines //
	////////////////////////
	
	// Add blank lines in case of a compact text
	//  Don't add blank lines between lines beginning with * or #
	//  Don't add blank lines if we are inside a template (with exceptions?)
	$insideTemplates = 0;
	$paragraphsBegin = array();
	$paragraphsSize = array();
	$nbParagraphs = 0;
	for( $i=0; $i<$endText; $i++ ) {
		
		// Update the counter 'templates'
		$insideTemplates -= preg_match_all( '/\}\}/u', $lines[$i], $localmatches );
		$insideTemplates += preg_match_all( '/\{\{(.*)\|/uU', $lines[$i], $localmatches );
		
		if( $insideTemplates > 0 ) continue;
		
		if( $lines[$i][0] == '*' && ($i>0 && $lines[$i-1][0] == '*') ) continue;
		
		if( $lines[$i][0] == '#' && ($i>0 && $lines[$i-1][0] == '#') ) continue;
		
		if( $sizelines[$i] >= $wgMinLengthLinesParagraph ) {
			
			$k = 1;
			while( $i+$k<$endText && $sizelines[$i+$k] >= $wgMinLengthLinesParagraph ) $k++;
			
			if( $k >= $wgMinNbLinesParagraphs ) {
				
				$paragraphsBegin[$nbParagraphs] = $i;
				$paragraphsSize[$nbParagraphs] = $k;
				$nbParagraphs = count( $paragraphsBegin );
			}
		}
	}
	
	if( $nbParagraphs > 0 ) {
		
		$nextParagraph = 0;
		$nblinestoadd = 0;
		for( $i=0; $i<$endText; $i++ ) {
			
			if( $i > $paragraphsBegin[$nextParagraph] && $i < $paragraphsBegin[$nextParagraph]+$paragraphsSize[$nextParagraph] ) {
				
				if( $sizelines[$i] > 0 ) {
					
					$linestoadd[$nblinestoadd] = $i;
					$nblinestoadd++;
				}
			}
		}
		
		array_add_key( $lines, $linestoadd, array_fill( 0, $nblinestoadd, '' ) );
	}
	
	// Remove multiple blank lines
	for( $i=0; $i<$endText; $i++ ) {
		
		if( $sizelines[$i] == 0 ) {
			
			while( $i+1<$endText && $sizelines[$i+1] == 0 ) {
				
				unset( $sizelines[$i+1] );
				$i++;
			}
		}
	}
	
	
	
	//////////////////
	// Finalization //
	//////////////////
	
	$text = implode( "\n", $lines );
}

function linker( &$text ) {
	
	
	
	
	
	
	
	
	
	
	
	
}

function interwikifier( &$text ) {
	
	
	
	
	
	
	
	
	
}

function categorizer( &$text ) {
	
	
	
	

	
	
	
	
	
	
	
}


////////////////////
// Util functions //
////////////////////

/**
 * Add values inside an array at the specified keys (of the old array)
 * The keys must be sorted from low to high
 * 
 * For example:
 *   $original = array( 0, 1, 2, 3, 4 );
 *   $keys = array( 1, 3, 3 );
 *   $values = array( 1.5, 3.3, 3.6 );
 * gives:
 *   $newtab = array( 0, 1, 1.5, 2, 3, 3.3, 3.6, 4 );
 */
function array_add_key( array $original, array $keys, array $values ) {
	
	// Trivial checking
	if( count($keys) != count($values) ) return $original;
	
	$newtab = array_fill( 0, count($original)+count($keys), 0 );
	$k = 0;
	
	for( $i=0; $i<count($original); $i++ ) {
		
		$newtab[$i+$k] = $original[$i];
		
		while( $k<count($keys) && $keys[$k] == $i ) {
			
			$k++;
			$newtab[$i+$k] = $values[$k];
		}
	}
	
	return $newtab;
}

function wfDebugBegin( $name, $msg ) {
	
	wfDebug( $name, "* $msg\n" );
}

function wfDebugOpBegin( $name, $section, $msg ) {
	
	wfDebug( $name, "  * $msg..." );
}

function wfDebugOpEnd( $name ) {
	
	wfDebug( $name, "done\n" );
}

function wfDebugEnd( $name ) {
	
	
}

function wfDebug( $name, $msg ) {
	
	global $wgDebug;
	
	if( $wgDebug ) echo $msg;
}

