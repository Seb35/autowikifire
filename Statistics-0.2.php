<?php

require_once( 'Settings.php' );
require_once( 'Util.php' );

class Statistics
{
	private $text;
	private $nblines, $lines;
	
	private $regexInterwikis;
	private $regexCategories;
	
	public $stat;
	
	function __construct( &$text, &$nblines, &$lines ) {
		
		// Inputs
		$this->text = $text;
		$this->nblines = $nblines;
		$this->lines = $lines;
		
		// Outputs
		$this->stat = array( 'sizelines' => array(),
		                     'blanklines' => array(),
		                     'nbcapitals' => 0,
		                     'nbwords' => array( 'nbwords' => 0 ),
		                     'interwikis' => array(),
		                     'categories' => array(),
		                     'titles' => array( 'wikititles' => array() ),
		                     'frames' => array( 'framelines' => array(), 'frames' => array() ),
		                     'nbtemplates' => array( 'nbtemplates' => 0, 'nbmisformedtemplates' => 0 ),
		                     'nbwikilinks' => array( 'nbwikilinks' => 0 ),
		                     'lists' => array( 'wikibulletedlists' => array(), 'wikinumberedlists' => array(),
		                                       'rawbulletedlists' => array, 'rawnumberedlists' => array() ),
		                     'paragraphs' => array( 'wikiparagraphs' => array(), 'rawparagraphslines' => array(), 'rawparagraphs' => array() )
		                   );
		
		// Intermediary variables
		global $wgInterwikis;
		$this->regexInterwikis = '/\[\[('.implode( '|',$wgInterwikis ).'):(.*)\]\]/uU';
		
		global $wgCategoryLocalName;
		$this->regexCategories = '/\[\[(?:'.$wgCategoryLocalName.'|Category):(.*)\]\]/uU';
	}
	
	function get( $category, $subcategory = false ) {
		
		if( !$subcategory ) return $stat[$category];
		else return $stat[$category][$subcategory];
	}
	
	function 
	
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
	 */
	function update_wikibulletedlists_line( $l ) {
		
		if( preg_match( '/^\*/u', $this->lines[$l] ) ) $this->stat['lists']['wikibulletedlists'][$l] = true;
		else unset( $this->stat['lists']['wikibulletedlists'][$l] );
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
	 */
	function update_wikinumberedlists_line( $l ) {
		
		if( preg_match( '/^#/u', $this->lines[$l] ) ) $this->stat['lists']['wikinumberedlists'][$l] = true;
		else unset( $this->stat['lists']['wikinumberedlists'][$l] );
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
					
					$this->stat['lists']['rawnumberedlists'][$l] = array( 'type'   => $i,
					                                                      'spaces' => strlen( preg_replace( '/^( *)'.$wgNumberedList[$i].'.*/u', '$1', $this->lines[$l] ) ),
					                                                      'number' => preg_replace( '/^ *'.$wgNumberedList[$i].'.*/u', '$1', $this->lines[$l] )
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
			
			if( !( $this->stat['blanklines'][$l-1] || $this->stat['frames']['framelines'][$l-1] || $this->stat['lists']['wikibulletedlists'][$l-1] || $this->stat['lists']['wikinumberedlists'][$l-1] || $this->stat['titles']['wikititles'][$l-1] ) ) continue;
			
			if( !( $this->stat['blanklines'][$l+1] || $this->stat['frames']['framelines'][$l+1] || $this->stat['lists']['wikibulletedlists'][$l+1] || $this->stat['lists']['wikinumberedlists'][$l+1] || $this->stat['titles']['wikititles'][$l+1] ) ) continue;
			
			$this->stat['paragraphs']['wikiparagraphlines'][$l] = true;
		}
	}
	
	function update_rawparagraphs() {
		
		$insideTemplates = 0;
		$this->stat['paragraphs']['rawparagraphslines'] = array();
		$this->stat['paragraphs']['rawparagraphs'] = array();
		
		for( $l=1; $l<$this->nblines-1; $l++ ) {
			
			// Update the counter 'templates'; as above this is a trivial checking
			$insideTemplates += preg_match_all( '/\{\{/u', $lines[$i], $localmatches )
			                  - preg_match_all( '/\}\}/u', $lines[$i], $localmatches );
			
			if( $insideTemplates > 0 ) continue;
			
			if( $this->stat['blanklines'][$l] || $this->stat['frames']['framelines'][$l] || $this->stat['lists']['wikibulletedlists'][$l] || $this->stat['lists']['wikinumberedlists'][$l] || $this->stat['titles']['wikititles'][$l] ) continue;
			
			if( !$this->stat['blanklines'][$l+1] && !$this->stat['frames']['framelines'][$l+1] && !$this->stat['lists']['wikibulletedlists'][$l+1] && !$this->stat['lists']['wikinumberedlists'][$l+1] && !$this->stat['titles']['wikititles'][$l+1] ) {
				
				$this->stat['paragraphs']['rawparagraphslines'][$l] = true;
				$k = 1;
				while( $l+$k<$this->nblines-1 && !$this->stat['blanklines'][$l+$k] && !$this->stat['frames']['framelines'][$l+$k] && !$this->stat['lists']['wikibulletedlists'][$l+$k] && !$this->stat['lists']['wikinumberedlists'][$l+$k] && !$this->stat['titles']['wikititles'][$l+$k] ) {
					
					$this->stat['paragraphs']['rawparagraphslines'][$i+$k] = true;
					$k++;
				}
				
				$this->stat['paragraphs']['rawparagraphs'][count($this->stat['paragraphs']['rawparagraphs'])] = array( $l, $k );
				
				$l = $l+$k;
			}
		}
	}
	
	function update_nbwikilinks() {
		
		$this->stat['nbwikilinks']['nbwikilinks'] = preg_match_all( '/\[\[[^\[\]]*\]\]/uUm', $this->text, $localmatches );
	}
	
	function update_nbwords() {
		
		$this->stat['nbwords']['nbwords'] = preg_match_all( '/ .* /uU', $this->text, $localmatches );
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
		
		
		
		###################
		# Number of words #
		###################
		
		wfDebugOpBegin( __METHOD__, 'nbwords', 'Number of words' );
		
		$this->update_nbwords();
		
		wfDebugOpEnd( __METHOD__, 'nbwords' );
		
		
		
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
		
		wfDebugOpBegin( __METHOD__, 'wikiparagraphs', 'Cutting off into wikiparagraphs' );
		
		$this->update_wikiparagraphs();
		
		wfDebugOpEnd( __METHOD__, 'wikiparagraphs' );
		
		
		
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
		
		
		
		#######################
		# Number of wikilinks #
		#######################
		
		wfDebugOpBegin( __METHOD__, 'nbwikilinks', 'Number of wikilinks' );
		
		$this->update_nbwikilinks();
		
		wfDebugOpEnd( __METHOD__, 'nbwikilinks' );
		
		
		
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
	 *   - raw paragraphs
	 */
	function statisticsL32() {
		
		wfDebugBegin( __METHOD__, 'Statistics level 3-2 (unwikisyntaxed meso-scale statistics)' );
		
		###############################
		# Cutting off into paragraphs #
		###############################
		
		wfDebugOpBegin( __METHOD__, 'rawparagraphs', 'Cutting off into raw paragraphs' );
		
		$this->update_rawparagraphs();
		
		wfDebugOpEnd( __METHOD__, 'rawparagraphs' );
		
		
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
	
	function display_statistics(){
		
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
		
		// Number of words
		echo '    * Number of words = ';
		echo $this->stat['nbwords'];
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
		
		// Interwikis
		echo '    * Interwikis:';
		echo "\n";
		echo '      * number = ';
		echo $this->stat['interwikis']['nbinterwikis'];
		echo "\n";
		if( $this->stat['interwikis']['nbinterwikis'] > 0 ) {
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['interwikis']['interwikis'][$l] )
					for( $i=0; $i<count($this->stat['interwikis']['interwikis'][$l]); $i++ )
						echo '      * '.$this->stat['interwikis']['interwikis'][$l][$i]."\n";
		}
		
		// Categories
		echo '    * Categories:';
		echo "\n";
		echo '      * number = ';
		echo $this->stat['categories']['nbcategories'];
		echo "\n";
		if( $this->stat['categories']['nbcategories'] > 0 ) {
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['categories']['categories'][$l] )
					for( $i=0; $i<count($this->stat['categories']['categories'][$l]); $i++ )
						echo '      * '.$this->stat['categories']['categories'][$l][$i]."\n";
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
		
		// Nbwikilinks
		echo '    * Number of wikilinks';
		echo ' = ';
		echo $this->stat['nbwikilinks']['nbwikilinks'].'/'.$this->stat['nbwords']['nbwords'];
		echo ' = ';
		printf( '%.1f%%', $this->stat['nbwikilinks']['nbwikilinks']/$this->stat['nbwords']['nbwords']*100 );
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
		
		// Paragraphs
		echo '    * Raw paragraphs:';
		echo "\n";
		echo '      * number of lines = ';
		echo count($this->stat['paragraphs']['rawparagraphslines']);
		echo "\n";
		if( count($this->stat['paragraphs']['rawparagraphslines']) > 0 ) {
			echo '      * lines = {';
			for( $l=0; $l<$this->nblines; $l++ )
				if( $this->stat['paragraphs']['rawparagraphslines'][$l] )
					echo ' '.$l;
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
		echo '      * number of paragraphs = ';
		echo count($this->stat['paragraphs']['rawparagraphs']);
		echo "\n";
		if( count($this->stat['paragraphs']['rawparagraphs']) > 0 ) {
			echo '      * {';
			for( $i=0; $i<count($this->stat['paragraphs']['rawparagraphs']); $i++ )
				echo ' ('.$this->stat['paragraphs']['rawparagraphs'][$i][0].','.$this->stat['paragraphs']['rawparagraphs'][$i][1].')';
			echo ' }';
			echo "\n";
		}
		
		
		#
		# Level 3
		#
		
		//echo "  * Level 3-3 (unwikisyntaxed full-text)\n";
		
		
	}
	
} // End class statistics

