<?php

// 
// AutoWikiFire 0.1
// 
// License GPL
// 

// This is a text only for a test
$opts = array( 'http' => array( 'method' => 'GET', 'header' => 'User-Agent: AutoWikiFire/0.1' ) );
$context  = stream_context_create( $opts );
$text = file_get_contents( 'http://fr.wikipedia.org/w/index.php?title=Josh_Zuckerman&action=raw&oldid=46951521', false, $context );



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                    //
//                                   SETTINGS                                                                                                         //
//                                                                                                                                                    //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
$wgBulletedList = array( '-' );

// Characters recognized as a numbered list (the number must be parenthezed)
$wgNumberedList = array( '([0-9]{1,3})\.' );



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

$wgInterwikis = array( 'als', 'an', 'az', 'be', 'boa', 'cz', 'da', 'de', 'en', 'es', 'fi', 'he', 'nl', 'pt', 'tr', 'zh' );

/**
 * Recognize a paragraph in the aim to add blank lines in compact paragraphs
 */
$wgMinNbLinesParagraph = 6;

$wgMinLengthLinesParagraph = 200;






////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                    //
//                                   CODE                                                                                                             //
//                                                                                                                                                    //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

for( $i=0; $i<180; $i++ ) echo '-';echo "\n";
trivial_layouting( $text, true );
echo "\n";
for( $i=0; $i<180; $i++ ) echo '-';echo "\n";
echo $text;

function trivial_layouting( &$text, $newarticle ) {
	
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

