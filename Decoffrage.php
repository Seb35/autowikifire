<?php

require_once( 'Settings.php' );
require_once( 'Text.php'     );

class AWF_Decoffrage {
	
	public $text;
	
	function __construct( $text ) {
		
		$this->text = $text;
	}
	
	/**
	 * Remplace raw paragraphs by wikiparagraphs
	 */
	function rawparagraphs() {
		
		global $wgNbLinesVerySmallParagraph;
		
		foreach( $this->text->rawparagraphslist as $paragraph ) {
			
			$firstline = $paragraph['firstline'];
			$size = $paragraph['size'];
			
			$meanSizelinesParagraph = 0;
			for( $j=0; $j<$size; $j++ ) $meanSizelinesParagraph += $this->text[$first+$j]->sizeline;
			$meanSizelinesParagraph /= $size;
			
			// Only a few lines which seems to be 
			if( $size <= $wgNbLinesVerySmallParagraph ) {
				
				for( $j=1; $j<$size; $j++ ) {
					
					$this->text[$firstline]->concat( ' '.$this->text[$firstline+$j]->line );
					unset( $this->text[$firstline+$j] );
				}
			}
			else {
				
			}
		}
		
		$this->text->compact();
		$this->text->update_props();
	}
	
	/**
	 * Make well-formed wikilists
	 */
	function rawlists() {
		
		global $wgNbEmptyLinesMaxBetweenTwoBullets, $wgNbFilledLinesMaxBetweenTwoBullets;
		global $wgNbEmptyLinesMaxBetweenTwoNumbers, $wgNbFilledLinesMaxBetweenTwoNumbers;
		global $wgBulletedList, $wgNumberedList;
		
		for( $i=0; $i<$this->text->nbrawbulletedlists; $i++ ) {
			
			$firstline = $this->text->rawbulletedlists[$i]['firstline'];
			$size = $this->text->rawbulletedlists[$i]['size'];
			$regex = '/^ *'.$wgBulletedList[ $this->text[$firstline]->rawbullet['type'] ].' *(.*)$/u';
			
			for( $j=0; $j<$size; $j++ ) {
				
				$this->text[$firstline+$j] = preg_replace( $regex, '* $1', $this->text[$firstline+$j] );
			}
			
			if( $i < $this->text->nbrawbulletedlists-1 ) {
				
				if( $this->text[$firstline+$size]->blankline ) {
					
					if( $this->text->rawbulletedlists[$i+1]['firstline']-$firstline-$size <= $wgNbEmptyLinesMaxBetweenTwoBullets ) {
						
						//echo 'raccord vide'.$firstline.'-'.$size.'-'.$this->text->rawbulletedlists[$i+1]['firstline']."\n";
						$l = $firstline+$size;
						while( $this->text[$l]->blankline && $l < $this->text->rawbulletedlists[$i+1]['firstline'] ) $l++;
						if( $l < $this->text->rawbulletedlists[$i+1]['firstline'] ) continue;
						
						for( $i=$firstline+$size; $i<$this->text->rawbulletedlists[$i+1]['firstline']; $i++ ) {
							
							unset( $this->text[$i] );
						}
					}
				}
				else {
					
					if( $this->text->rawbulletedlists[$i+1]['firstline']-$firstline-$size <= $wgNbFilledLinesMaxBetweenTwoBullets ) {
						
						//echo 'raccord plein'.$firstline.'-'.$size.'-'.$this->text->rawbulletedlists[$i+1]['firstline'].'-';
						$l = $firstline+$size;
						while( !$this->text[$l]->blankline && $l < $this->text->rawbulletedlists[$i+1]['firstline'] ) $l++;
						//echo $l.'-';
						if( $l < $this->text->rawbulletedlists[$i+1]['firstline'] ) continue;
						//echo 'ok'."\n";
						for( $j=$firstline+$size; $j<$this->text->rawbulletedlists[$i+1]['firstline']; $j++ ) {
							
							$this->text[$j] = '*: '.$this->text[$j]->line;
							//echo $this->text[$j]."\n";
						}
					}
				}
			}
			
		}
		
		for( $i=0; $i<$this->text->nbrawnumberedlists; $i++ ) {
			
			$firstline = $this->text->rawnumberedlists[$i]['firstline'];
			$size = $this->text->rawnumberedlists[$i]['size'];
			$regex = '/^ *'.$wgNumberedList[ $this->text[$firstline]->rawnumber['type'] ].' *(.*)$/u';
			
			for( $j=0; $j<$size; $j++ ) {
				
				$this->text[$firstline+$j] = preg_replace( $regex, '# $1', $this->text[$firstline+$j] );
			}
			
			/*
			// Get the value just after $v
			$nextElem = current( $this->stat['lists']['rawbulletedlists'] );
			$nl = key( $this->stat['lists']['rawbulletedlists'] );
			
			// Count the number of lines between this bullet and the following
			$emptyLines = 0;
			$filledLines = 0;
			for( $i=0; $i<$nl; $i++ ) {
				
				if( $this->stat['sizelines'][$i] == 0 ) $emptyLines++;
				else $filledLines++;
			}
			
			// If the next bullet belong to this list
			if(    $l == $lastBullet 
			    || !( $emptyLines > $wgNbEmptyLinesMaxBetweenTwoBullets
			       || $filledLines > $wgNbFilledLinesMaxBetweenTwoBullets
			       || $v['type'] != $nextElem['type']
			       || $v['spaces'] != $nextElem['spaces'] )) {
				
				$this->lines[$l] = preg_replace( '/^ *'.$wgBulletedList[$v['type']].' *(.*)$/u', '* $1', $this->lines[$l] );
				$lastBullet = $nl;
				
				$this->stat->update_sizelines_line( $l );
				$this->stat->update_rawbulletedlists_line( $l );
				$this->stat->update_wikibulletedlists_line( $l );
				$this->stat->update_framelines_line( $l );
				$this->stat->update_frames();
				$this->stat->update_wikiparagraphs();
				$this->stat->update_rawparagraphs();
			}*/
		}
		
		// Update the properties
		$this->text->update_props();
	}
}

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

