<?php

require_once( 'Settings.php' );

// This is a 'private' class used in AWF_text
//   Never create an object of this class
//   But it can be read
class AWF_line
{
	const LINE_UNDEFINED      = 0;
	const LINE_BLANKLINE      = 1;
	const LINE_WIKITITLE      = 2;
	const LINE_FRAMELINE      = 3;
	const LINE_CATEGORY_ONLY  = 4;
	const LINE_INTERWIKI_ONLY = 5;
	const LINE_WIKIBULLET     = 6;
	const LINE_WIKINUMBER     = 7;
	
	########
	# Data #
	########
	
	// General characteristics
	public $line   = '';
	private $text  = null;
	
	// Line-by-line properties
	public $status     = LINE_UNDEFINED; // number
	public $sizeline   = 0;        // number
	public $blankline  = false;    // boolean (NB: this should be true by default (sizeline==0) but introduces a bug in AWF_text::$nbblanklines < 0)
	public $wikititle  = 0;        // number
	public $frameline  = false;    // boolean
	public $interwikis = array();  // array of strings
	public $categories = array();  // array of strings
	public $wikibullet = false;    // boolean
	public $wikinumber = false;    // boolean
	public $rawbullet  = false;    // false or array( 'type'=>number, 'spaces'=>number )
	public $rawnumber  = false;    // false or array( 'type'=>number, 'spaces'=>number, 'number'=>number )
	
	// Local properties
	public $wikiparagraph = false; // boolean
	public $rawparagraph  = false; // boolean
	
	// Facilities
	public static $regexInterwikis;
	public static $regexCategories;
	public static $regexInterwikis2;
	public static $regexCategories2;
	
	function __construct( $line, $text ) {
		
		$this->line = $line;
		$this->text = $text;
		
		$this->update_props();
	}
	
	/**
	 * Concat this line with another line
	 * 
	 * @param $other_line: (string) the second line
	 */
	function concat( $other_line ) {
		
		$this->line .= $other_line;
		
		$this->update_props();
	}
	
	function __tostring() {
		
		return $this->line;
	}
	
	function update_props() {
		
		$this->update_sizeline();
		$this->update_blankline();
		$this->update_wikititle();
		$this->update_frameline();
		$this->update_interwikis();
		$this->update_categories();
		$this->update_wikibullet();
		$this->update_wikinumber();
		$this->update_rawbullet();
		$this->update_rawnumber();
	}
	
	function update_sizeline() {
		
		// Update the counter
		$this->text->totalsizelines -= $this->sizeline;
		
		// Really update the sizeline status
		$this->sizeline = strlen( $this->line );
		
		// Update the counter
		$this->text->totalsizelines += $this->sizeline;
	}
	
	function update_blankline() {
		
		// Update the counter
		$this->text->nbblanklines -= intval($this->blankline);
		
		// Really update the blankline status
		$this->blankline = ( $this->sizeline == 0 );
		if( $this->blankline ) $this->status = LINE_BLANKLINE;
		
		// Update the counter
		$this->text->nbblanklines += intval($this->blankline);
	}
	
	function update_wikititle() {
		
		// Update the counter
		$this->text->nbwikititles -= intval($this->wikititle);
		
		// Really update the wikititle status
		     if( preg_match( '/^={6}.*={6} *$/u', $this->line ) ) { $this->wikititles = 6; $this->status = LINE_WIKITITLE; }
		else if( preg_match( '/^={5}.*={5} *$/u', $this->line ) ) { $this->wikititles = 5; $this->status = LINE_WIKITITLE; }
		else if( preg_match( '/^={4}.*={4} *$/u', $this->line ) ) { $this->wikititles = 4; $this->status = LINE_WIKITITLE; }
		else if( preg_match( '/^={3}.*={3} *$/u', $this->line ) ) { $this->wikititles = 3; $this->status = LINE_WIKITITLE; }
		else if( preg_match( '/^={2}.*={2} *$/u', $this->line ) ) { $this->wikititles = 2; $this->status = LINE_WIKITITLE; }
		else if( preg_match( '/^={1}.*={1} *$/u', $this->line ) ) { $this->wikititles = 1; $this->status = LINE_WIKITITLE; }
		else $this->wikititle = 0;
		
		// Update the counter
		$this->text->nbwikititles += intval($this->wikititle);
	}
	
	function update_frameline() {
		
		// Update the counter
		$this->text->nbframelines -= intval($this->frameline);
		
		// Really update the frameline status
		$this->frameline = ( $this->line[0] == ' ' );
		if( $this->frameline ) $this->status = LINE_FRAMELINE;
		
		// Update the counter
		$this->text->nbframelines += intval($this->frameline);
	}
	
	function update_interwikis() {
		
		// A chance to skip this heavy step
		if( $this->status != LINE_UNDEFINED ) return;
		
		// Update the counter
		$this->text->nbinterwikis -= count($this->interwikis);
		
		// Initialize the default value: no interwiki (empty array)
		$this->interwikis = array();
		
		// Compute the interwikis on the line and update the interwikis status
		if( preg_match( self::$regexInterwikis, $this->line ) ) {
			
			preg_match_all( self::$regexInterwikis, $this->line, $localmatches, PREG_SET_ORDER );
			
			for( $i=0; $i<count($localmatches); $i++ ) {
				
				$this->interwikis[$i] = $localmatches[$i][1].':'.$localmatches[$i][2];
			}
			
			if( preg_match( self::$regexInterwikis2, $this->line ) ) $this->status = LINE_INTERWIKI_ONLY;
		}
		
		// Update the counter
		$this->text->nbinterwikis += count($this->interwikis);
	}
	
	function update_categories() {
		
		// A chance to skip this heavy step
		if( $this->status != LINE_UNDEFINED ) return;
		
		// Update the counter
		$this->text->nbcategories -= count($this->categories);
		
		// Initialize the default value: no category (empty array)
		$this->categories = array();
		
		// Compute the categories on the line and update the categories status
		if( preg_match( self::$regexCategories, $this->line ) ) {
			
			preg_match_all( self::$regexCategories, $this->line, $localmatches );
			
			$this->categories = $localmatches[1];
			
			if( preg_match( self::$regexCategories2, $this->line ) ) $this->status = LINE_CATEGORY_ONLY;
		}
		
		// Update the counter
		$this->text->nbcategories += count($this->categories);
	}
	
	function update_wikibullet() {
		
		// A chance to skip this heavy step
		if( $this->status != LINE_UNDEFINED ) return;
		
		// Update the counter
		$this->text->nbwikibullets -= intval($this->wikibullet);
		
		// Really update the wikibullet status
		$this->wikibullet = ( preg_match( '/^\*/u', $this->line ) > 0 );
		if( $this->wikibullet ) $this->status = LINE_WIKIBULLET;
		
		// Update the counter
		$this->text->nbwikibullets += intval($this->wikibullet);
	}
	
	function update_wikinumber() {
		
		// A chance to skip this heavy step
		if( $this->status != LINE_UNDEFINED ) return;
		
		// Update the counter
		$this->text->nbwikinumbers -= intval($this->wikinumber);
		
		// Really update the wikinumber status
		$this->wikinumber = ( preg_match( '/^#/u', $this->line ) > 0 );
		if( $this->wikinumber ) $this->status = LINE_WIKINUMBER;
		
		// Update the counter
		$this->text->nbwikinumbers += intval($this->wikinumber);
	}
	
	function update_rawbullet() {
		
		global $wgBulletedList;
		
		// A chance to skip this heavy step
		if( $this->status != LINE_UNDEFINED && $this->status != LINE_FRAMELINE ) return;
		
		// Update the counter
		$this->text->nbrawbullets -= intval( $this->rawbullet != false );
		
		// Initialize the default value: no rawbullet (false)
		$this->rawbullet = false;
		
		// Really update the rawbullet status
		for( $i=0; $i<count($wgBulletedList); $i++ ) {
			
			if( preg_match( '/^ *'.$wgBulletedList[$i].'/u', $this->line ) ) {
				
				$this->rawbullet = array( 'type'   => $i,
				                          'spaces' => strlen( preg_replace( '/^( *)'.$wgBulletedList[$i].'.*$/u', '$1', $this->line ) )
				                        );
				break;
			}
		}
		
		// Update the counter
		$this->text->nbrawbullets += intval( $this->rawbullet != false );
	}
	
	function update_rawnumber() {
		
		global $wgNumberedList;
		
		// A chance to skip this heavy step
		if( $this->status != LINE_UNDEFINED && $this->status != LINE_FRAMELINE ) return;
		
		// Update the counter
		$this->text->nbrawnumbers -= intval( $this->rawnumber != false );
		
		// Initialize the default value: no rawnumber (false)
		$this->rawnumber = false;
		
		// Really update the rawnumber status
		for( $i=0; $i<count($wgNumberedList); $i++ ) {
			
			if( preg_match( '/^ *'.$wgNumberedList[$i].'/u', $this->line ) ) {
				
				$this->rawnumber = array( 'type'   => $i,
				                          'spaces' => strlen( preg_replace( '/^( *)'.$wgNumberedList[$i].'.*$/u', '$1', $this->line ) ),
				                          'number' => preg_replace( '/^ *'.$wgNumberedList[$i].'.*$/u', '$1', $this->line )
				                        );
				break;
			}
		}
		
		// Update the counter
		$this->text->nbrawnumbers += intval( $this->rawnumber != false );
	}
}





class AWF_text implements ArrayAccess, Iterator
{
	#################
	# Internal data #
	#################
	
	private $text;    // string
	public $title;    // string
	private $lines;   // array of AWF_line
	private $index;   // integer
	private $figures; // number
	
	
	###############
	# Public data #
	###############
	
	// Number of lines (for external uses)
	public $nblines              = 0;
	public $truenblines          = 0;
	
	// Line-by-line properties
	public $totalsizelines       = 0;
	public $nbblanklines         = 0;
	public $nbwikititles         = 0;
	public $nbframelines         = 0;
	public $nbinterwikis         = 0;
	public $nbcategories         = 0;
	public $nbwikibullets        = 0;
	public $nbwikinumbers        = 0;
	public $nbrawbullets         = 0;
	public $nbrawnumbers         = 0;
	
	// Local properties
	public $frameslist           = array();
	public $nbframeslist         = 0;
	public $nbwikiparagraphs     = 0;
	public $nbrawparagraphs      = 0;
	public $rawparagraphslist    = array();
	public $nbrawparagraphslist  = 0;
	public $rawbulletedlists     = array();
	public $nbrawbulletedlists   = 0;
	public $rawnumberedlists     = array();
	public $nbrawnumberedlists   = 0;
	
	// Global properties
	public $nbwords              = 0;
	public $nbcapitals           = 0;
	public $nbwikilinks          = 0;
	public $nbtemplates          = 0;
	public $nbmisformedtemplates = 0;
	
	
	###############
	# Constructor #
	###############
	
	public function __construct( $title, $text ) {
		
		global $wgCategoryLocalName, $wgInterwikis;
		
		// Initialization of precomputed constants
		AWF_line::$regexCategories  = '/\[\[(?:'.$wgCategoryLocalName.'|Category):(.*)\]\]/uU';
		AWF_line::$regexInterwikis  = '/\[\[('.implode( '|',$wgInterwikis ).'):(.*)\]\]/uU';
		AWF_line::$regexCategories2 = '/^\[\[(?:'.$wgCategoryLocalName.'|Category):(.*)\]\]$/uU';
		AWF_line::$regexInterwikis2 = '/^\[\[('.implode( '|',$wgInterwikis ).'):(.*)\]\]$/uU';
		
		// Trivial initialization
		$this->text    = $text;
		$this->title   = $title;
		$this->lines   = array();
		$this->index   = 0;
		$this->figures = 0;
		
		// Cut off the text into lines
		$lines = $this->explodeText( $text );
		
		// Initialization of the lines
		$this->nblines = count($lines);
		$this->truenblines = $this->nblines-4;
		for( $i=0; $i<$this->nblines; $i++ ) {
			
			$this->lines[$i] = new AWF_line( $lines[$i], $this );
		}
		
		// Update the properties
		$this->update_props();
	}
	
	private function explodeText( $text ) {
		
		// Cutting off into an array of lines
		if( preg_match( '/\r\n/u', $text ) ) $lines = explode( "\r\n", "\r\n\r\n".$text."\r\n\r\n" );
		else $lines = explode( "\n", "\n\n".$text."\n\n" );
		
		return $lines;
	}
	
	
	#############################
	# Interface for ArrayAccess #
	#############################
	
	/**
	 * Is the index exists?
	 *
	 * @return boolean
	 */
	function offsetExists( $index ) {
		
		return ( $index >= 0 && $index < $this->nblines );
	}
	
	/**
	 * Get a line of the text
	 * 
	 * @param $index: index of the line in the text
	 * @return AWF_line
	 */
	function offsetGet( $index ) {
		
		// Don't continue if the offset doesn't exist
		if( !$this->offsetExists($index) ) return false;
		
		return $this->lines[$index];
	}
	
	/**
	 * Remplace a line in the text
	 *   WARNING: do not update global nor local properties!
	 * 
	 * @param $index: index of the line in the text
	 * @param $line: new string
	 */
	function offsetSet( $index, $line ) {
		
		// Don't continue if the offset doesn't exist
		if( !$this->offsetExists($index) ) return;
		
		// Unset the current value
		unset( $this->lines[$index] );
		
		// Set the new value
		$this->lines[$index] = new AWF_line( $line, $this );
	}
	
	/**
	 * Remove a line of the text
	 *   WARNING: do not update global nor local properties!
	 *   WARNING: do not compact the resulting text!
	 * 
	 * @param $index: index of the line in the text
	 */
	function offsetUnset( $index ) {
		
		// Don't continue if the offset doesn't exist
		if( !$this->offsetExists($index) ) return;
		
		// Remove the line
		unset( $this->lines[$index] );
	}
	
	
	##########################
	# Interface for Iterator #
	##########################
	
	/**
	 * Get the current line
	 * 
	 * @return AWF_line
	 */
	public function current() {
		
		return $this->lines[$this->index];
	}
	
	/**
	 * Go to the next line
	 */
	public function next() {
		
		$this->index++;
	}
	
	/**
	 * Is the current index valid?
	 * 
	 * @return boolean
	 */
	public function valid() {
		
		return $this->offsetExists( $this->index );
	}
	
	/**
	 * Get the current index
	 * 
	 * @return integer
	 */
	public function key() {
		
		return $this->index;
	}
	
	/**
	 * Go the the first index
	 */
	public function rewind() {
		
		$this->index = 0;
	}
	
	
	####################################
	# Other general managing functions #
	####################################
	
	/**
	 * Toggle the state of figures at the beginning of a line (for external printing)
	 */
	function toggleFigures( $nbColsTerm = 0 ) {
		
		$this->figures = $nbColsTerm;
	}
	
	/**
	 * Affichage du text
	 */
	function __tostring() {
		
		if( $this->nblines == 0 ) return '';
		
		$text = '';
		
		if( $this->figures > 0 ) {
			
			// Number of figures required on the left
			$nbfigures = intval(log($this->nblines,10))+1;
			
			// Number of columns of the terminal
			$nbcols = $this->figures;
			
			// Ten's tab
			for( $i=0; $i<=$nbfigures; $i++ ) $text .= ' ';
			$counter = 0;
			for( $i=0; $i<$nbcols-$nbfigures; $i++ ) {
				if( $i%10 == 0 ) {
					if( $counter >= 9 ) {
						if( $i < $nbcols-$nbfigures-1 ) $text .= $counter;
						$i++;
					}
					else $text .= $counter;
					$counter++;
				}
				else $text .= ' ';
			}
			$text .= "\n";
			
			// Unit's tab
			for( $i=0; $i<=$nbfigures; $i++ ) $text .= ' ';
			for( $i=0; $i<$nbcols-$nbfigures-1; $i++ ) $text .= $i%10;
			$text .= "\n";
			
			// First line
			for( $k=0; $k<$nbfigures; $k++ ) $text .= '0';
			$text .= '|';
			$text .= $this->lines[0];
			$text .= "\n";
			
			// Next lines
			for( $j=0; $j<$nbfigures; $j++ ) {
				
				for( $i=pow(10,$j); $i<$this->nblines && $i<pow(10,$j+1); $i++ ) {
					for( $k=$j+1; $k<$nbfigures ; $k++ ) $text .= '0';
					$text .= $i;
					$text .= '|';
					$text .= $this->lines[$i];
					$text .= "\n";
				}
			}
		}
		else {
			
			for( $i=0; $i<$this->nblines; $i++ ) {
				
				$text .= $this->lines[$i]."\n";
			}
		}
		
		return $text;
	}
	
	/**
	 * Compact the text
	 *   Should be called after one or many line unsets
	 */
	function compact() {
		
		$this->lines = array_values( $this->lines );
		$this->nblines = count( $this->lines );
		$this->index = 0;
	}
	
	/**
	 * Add a new line inside the text
	 * WARNING: do not update global nor local properties
	 * 
	 * @param $index: index of the line in the text
	 * @param $line: string of the new line
	 */
	function addLine( $index, $line ) {
		
		// Move all lines to create a space into the text
		for( $i=$this->nblines-1; $i >= $index; $i-- ) {
			
			$this->lines[$i+1] = $this->lines[$i];
			unset( $this->lines[$i] );
		}
		
		// Add the new line
		$this->lines[$index] = new AWF_line( $line, $this );
		
		// Update the number of lines
		$this->nblines = $this->nblines;
	}
	
	/**
	 * Update the properties
	 */
	function update_props() {
		
		$this->update_local_props();
		$this->update_global_props();
	}
	
	/**
	 * Update the local properties
	 */
	function update_local_props() {
		
		$this->update_frameslist();
		$this->update_wikiparagraphs();
		$this->update_rawparagraphs();
		$this->update_rawbulletedlists();
		$this->update_rawnumberedlists();
	}
	
	/**
	 * Update the global properties
	 */
	function update_global_props() {
		
		$this->update_nbwords();
		$this->update_nbcapitals();
		$this->update_nbwikilinks();
		$this->update_nbtemplates();
	}
	
	
	#####################
	# Global properties #
	#####################
	
	function update_nbwords() {
		
		$this->nbwords = preg_match_all( '/(?:^| ).*(?: |$)/uU', $this->text, $localmatches );
	}
	
	function update_nbcapitals() {
		
		$this->nbcapitals = preg_match_all( '/[A-Z]/', $this->text, $localmatches );
	}
	
	function update_nbwikilinks() {
		
		$this->nbwikilinks = preg_match_all( '/\[\[[^\[\]]*\]\]/uUm', $this->text, $localmatches );
	}
	
	function update_nbtemplates() {
		
		$temptext = $this->text;
		$temptext = preg_replace( '/\{\{\{.*\}\}\}/uUm', '', $temptext );
		$temptext = preg_replace( '/\{\{(.*)\}\}/uUm', '$1', $temptext, -1, $this->nbtemplates );
		
		$this->nbmisformedtemplates = preg_match_all( '/\{\{/u', $temptext, $localmatches )
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
	
	
	####################
	# Local properties #
	####################
	
	/**
	 * Compute the frames of the text
	 */
	function update_frameslist() {
		
		// Initialize the default value: no frame (empty array)
		$this->frameslist = array();
		$this->nbframeslist = 0;
		
		// If no frameline has been computed, skip this step
		if( $this->nbframelines > 0 ) {
			
			for( $index=0; $index<$this->nblines; $index++ ) {
				
				if( $this->lines[$index]->frameline ) {
					
					$size = 1;
					while( $index+$size<$this->nblines && $this->lines[$index+$size]->frameline ) $size++;
					
					$this->frameslist[] = array( 'firstline'=>$index, 'size'=>$size );
					$index += $size;
				}
			}
			
			$this->nbframeslist = count( $this->frameslist );
		}
	}
	
	/**
	 * Update the wikiparagraphs of the text
	 */
	function update_wikiparagraphs() {
		
		$insideTemplates = 0;
		$this->nbwikiparagraphs = 0;
		
		for( $index=1; $index<$this->nblines-1; $index++ ) {
			
			// Initialize the default value: no wikiparagraph (false)
			$this->lines[$index]->wikiparagraph = false;
			
			// Update the counter 'templates'; as above this is a trivial checking
			$insideTemplates += preg_match_all( '/\{\{/u', $this->lines[$index]->line, $localmatches )
			                  - preg_match_all( '/\}\}/u', $this->lines[$index]->line, $localmatches );
			
			if( $insideTemplates > 0 ) continue;
			
			if( $this->lines[$index]->blankline || $this->lines[$index]->frameline || $this->lines[$index]->wikibullet || $this->lines[$index]->wikinumber || $this->lines[$index]->wikititle ) continue;
			
			if( !( $this->lines[$index-1]->blankline || $this->lines[$index-1]->frameline || $this->lines[$index-1]->wikibullet || $this->lines[$index-1]->wikinumber || $this->lines[$index-1]->wikititle ) ) continue;
			
			if( !( $this->lines[$index+1]->blankline || $this->lines[$index+1]->frameline || $this->lines[$index+1]->wikibullet || $this->lines[$index+1]->wikinumber || $this->lines[$index+1]->wikititle ) ) continue;
			
			$this->lines[$index]->wikiparagraph = true;
			
			// Update the counter
			$this->nbwikiparagraphs += 1;
		}
	}
	
	/**
	 * Update the rawparagraphs of the text
	 */
	function update_rawparagraphs() {
		
		// Initialize the default values
		$this->nbrawparagraphs     = 0;
		$this->rawparagraphslist   = array();
		$this->nbrawparagraphslist = 0;
		
		//$insideTemplates = 0;
		
		for( $index=1; $index<$this->nblines-1; $index++ ) {
			
			// Initialize the default value: no rawparagraph (false)
			$this->lines[$index]->rawparagraph = false;
			
			// Update the counter 'templates' (this is a simple checking)
			//$insideTemplates += preg_match_all( '/\{\{/u', $this->lines[$index]->line, $localmatches )
			//                  - preg_match_all( '/\}\}/u', $this->lines[$index]->line, $localmatches );
			//
			//if( $insideTemplates > 0 ) continue;
			
			if( $this->lines[$index]->status != LINE_UNDEFINED ) continue;
			
			if( $this->lines[$index+1]->status == LINE_UNDEFINED ) {
				
				$this->lines[$index]->rawparagraph = true;
				$size = 1;
				
				while( $index+$size<$this->nblines-1 && $this->lines[$index+$size]->status == LINE_UNDEFINED ) {
					
					$this->lines[$index+$size]->rawparagraph = true;
					$size++;
				}
				
				$this->rawparagraphslist[] = array( 'firstline'=>$index, 'size'=>$size );
				
				// Update the counter
				$this->nbrawparagraphs += $size;
				
				$index += $size-1;
			}
		}
		
		// Update the counter
		$this->nbrawparagraphslist = count( $this->rawparagraphslist );
	}
	
	function update_rawbulletedlists() {
		
		// Initialize the default values
		$this->rawbulletedlists   = array();
		$this->nbrawbulletedlists = 0;
		
		for( $index=0; $index<$this->nblines; $index++ ) {
			
			if( !$this->lines[$index]->rawbullet ) continue;
			
			$size = 1;
			while(    $index+$size<$this->nblines
			       && $this->lines[$index+$size]->rawbullet
			       && $this->lines[$index]->rawbullet['type'] == $this->lines[$index+$size]->rawbullet['type']
			       && $this->lines[$index]->rawbullet['spaces'] == $this->lines[$index+$size]->rawbullet['spaces'] ) {
				
				$size++;
			}
			
			$this->rawbulletedlists[] = array( 'firstline' => $index, 'size' => $size );
			
			$index += $size-1;
		}
		
		// Update the counter
		$this->nbrawbulletedlists = count( $this->rawbulletedlists );
	}
	
	function update_rawnumberedlists() {
		
		// Initialize the default values
		$this->rawnumberedlists   = array();
		$this->nbrawnumberedlists = 0;
		
		for( $index=0; $index<$this->nblines; $index++ ) {
			
			if( !$this->lines[$index]->rawnumber ) continue;
			
			$size = 1;
			while(    $index+$size<$this->nblines
			       && $this->lines[$index+$size]->rawnumber
			       && $this->lines[$index]->rawnumber['type'] == $this->lines[$index+$size]->rawnumber['type']
			       && $this->lines[$index]->rawnumber['spaces'] == $this->lines[$index+$size]->rawnumber['spaces']
			       && $this->lines[$index]->rawnumber['number'] == $this->lines[$index+$size]->rawnumber['number']
			     ) {
				
				$size++;
			}
			
			$this->rawnumberedlists[] = array( 'firstline' => $index, 'size' => $size );
			
			$index += $size-1;
		}
		
		// Update the counter
		$this->nbrawnumberedlists = count( $this->rawnumberedlists );
	}
	
	
	######################
	# Display properties #
	######################
	
	function display_props(){
		
		echo "* Properties:\n";
		
		####################
		# First properties #
		####################
		
		// Number of lines
		echo "  * Number of lines: ".$this->nblines."\n";
		
		// Sizelines
		echo '    * Status of each line';
		echo "\n";
		echo '      * {';
		foreach( $this as $index=>$line )
			echo ' '.$line->status;
		echo ' }';
		echo "\n";
		
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
		printf( '%.1f', $this->totalsizelines/$this->truenblines );
		echo "\n";
		echo '      * {';
		foreach( $this as $index=>$line )
			echo ' '.$line->sizeline;
		echo ' }';
		echo "\n";
		
		// Blanklines
		echo '    * Blank lines:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbblanklines;
		echo "\n";
		if( $this->nbblanklines > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', ($this->nbblanklines-4)/$this->truenblines*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->blankline )
					echo ' '.$index;
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
		echo $this->nbcapitals.'/'.$this->totalsizelines;
		echo ' = ';
		printf( '%.2f%%', $this->nbcapitals/$this->totalsizelines*100 );
		echo "\n";
		
		// Number of words
		echo '    * Number of words = ';
		echo $this->nbwords;
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
		echo '      * number = '.$this->nbframelines;
		echo "\n";
		if( $this->nbframelines > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->nbframelines/$this->truenblines*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->frameline )
					echo ' '.$index;
			echo ' }';
			echo "\n";
		}
		
		// Wikibulletedlists
		echo '    * Wiki-bulleted lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbwikibullets;
		echo "\n";
		if( $this->nbwikibullets > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->nbwikibullets/$this->truenblines*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->wikibullet )
					echo ' '.$index;
			echo ' }';
			echo "\n";
		}
		
		// Wikinumberedlists
		echo '    * Wiki-numbered lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbwikinumbers;
		echo "\n";
		if( $this->nbwikinumbers > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->nbwikinumbers/$this->truenblines*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->wikinumbers )
					echo ' '.$index;
			echo ' }';
			echo "\n";
		}
		
		// Wikititles
		echo '    * Wiki titles:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbwikititles;
		echo "\n";
		if( $this->nbwikititles > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->nbwikititles/$this->truenblines*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->wikititle )
					printf( ' (%d,%d)', $index, $line->wikititle );
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
		echo $this->nbframeslist;
		echo "\n";
		if( $this->nbframeslist > 0 ) {
			echo '      * {';
			foreach( $this->frameslist as $frame )
				printf( ' (%d,%d)', $frame['firstline'], $frame['size'] );
			echo ' }';
			echo "\n";
		}
		
		// Paragraphs
		echo '    * Wiki paragraphs:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbwikiparagraphs;
		echo "\n";
		if( $this->nbwikiparagraphs > 0 ) {
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->wikiparagraph )
					echo ' '.$index;
			echo ' }';
			echo "\n";
		}
		
		// Interwikis
		echo '    * Interwikis:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbinterwikis;
		echo "\n";
		if( $this->nbinterwikis > 0 ) {
			foreach( $this as $index=>$line )
				if( $line->interwikis )
					for( $i=0; $i<count($line->interwikis); $i++ )
						echo '      * '.$line->interwikis[$i]."\n";
		}
		
		// Categories
		echo '    * Categories:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbcategories;
		echo "\n";
		if( $this->nbcategories > 0 ) {
			foreach( $this as $index=>$line )
				if( $line->categories )
					for( $i=0; $i<count($line->categories); $i++ )
						echo '      * '.$line->categories[$i]."\n";
		}
		
		
		#
		# Level 3
		#
		
		echo "  * Level 2-3 (wikisyntaxed full-text)\n";
		
		// Nbtemplates
		echo '    * Number of templates';
		echo "\n";
		echo '      * well-formed = ';
		echo $this->nbtemplates;
		echo "\n";
		echo '      * probably-misformed = ';
		echo $this->nbmisformedtemplates;
		echo "\n";
		
		// Nbwikilinks
		echo '    * Number of wikilinks';
		echo ' = ';
		echo $this->nbwikilinks.'/'.$this->nbwords;
		echo ' = ';
		printf( '%.1f%%', $this->nbwikilinks/$this->nbwords*100 );
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
		echo $this->nbrawbullets;
		echo "\n";
		if( $this->nbrawbullets > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->nbrawbullets/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->rawbullet )
					printf( ' (%d,%d,%d)', $index, $line->rawbullet['type'], $line->rawbullet['spaces'] );
			echo ' }';
			echo "\n";
		}
		
		// Rawnumberedlists
		echo '    * Raw-numbered lists:';
		echo "\n";
		echo '      * number = ';
		echo $this->nbrawnumbers;
		echo "\n";
		if( $this->nbrawnumbers > 0 ) {
			echo '      * percentage = ';
			printf( '%.1f%%', $this->nbrawnumbers/($this->nblines-4)*100 );
			echo "\n";
			echo '      * {';
			foreach( $this as $index=>$line )
				if( $line->rawnumber )
					printf( ' (%d,%d,%d,%d)', $index, $line->rawnumber['type'], $line->rawnumber['spaces'], $line->rawnumber['number'] );
			echo ' }';
			echo "\n";
		}
		
		// Paragraphs
		echo '    * Raw paragraphs:';
		echo "\n";
		echo '      * number of lines = ';
		echo $this->nbrawparagraphs;
		echo "\n";
		if( $this->nbrawparagraphs > 0 ) {
			echo '      * lines = {';
			foreach( $this as $index=>$line )
				if( $line->rawparagraph )
					echo ' '.$index;
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
		echo $this->nbrawparagraphslist;
		echo "\n";
		if( $this->nbrawparagraphslist > 0 ) {
			echo '      * {';
			foreach( $this->rawparagraphslist as $paragraph )
				printf( ' (%d,%d)', $paragraph['firstline'], $paragraph['size'] );
			echo ' }';
			echo "\n";
		}
		
		
		#
		# Level 3
		#
		
		//echo "  * Level 3-3 (unwikisyntaxed full-text)\n";
	}
}

