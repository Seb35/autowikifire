<?php

require_once( 'Settings.php' );

// This is a 'private' class used in AWF_text
//   Never create an object of this class
//   But it can be read
class AWF_line
{
	########
	# Data #
	########
	
	// General characteristics
	public $line   = '';
	private $text  = null;
	
	// Line-by-line statistics
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
	
	// Local statistics
	public $wikiparagraph = false; // boolean
	public $rawparagraph  = false; // boolean
	
	// Facilities
	public static $regexInterwikis;
	public static $regexCategories;
	
	function __construct( $line, $text ) {
		
		$this->line = $line;
		$this->text = $text;
		
		$this->update();
	}
	
	function update() {
		
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
		
		// Update the counter
		$this->text->nbblanklines += intval($this->blankline);
	}
	
	function update_wikititle() {
		
		// Update the counter
		$this->text->nbwikititles -= intval($this->wikititle);
		
		// Really update the wikititle status
		     if( preg_match( '/^={6}.*={6} *$/u', $this->line ) ) $this->wikititles = 6;
		else if( preg_match( '/^={5}.*={5} *$/u', $this->line ) ) $this->wikititles = 5;
		else if( preg_match( '/^={4}.*={4} *$/u', $this->line ) ) $this->wikititles = 4;
		else if( preg_match( '/^={3}.*={3} *$/u', $this->line ) ) $this->wikititles = 3;
		else if( preg_match( '/^={2}.*={2} *$/u', $this->line ) ) $this->wikititles = 2;
		else if( preg_match( '/^={1}.*={1} *$/u', $this->line ) ) $this->wikititles = 1;
		else $this->wikititle = 0;
		
		// Update the counter
		$this->text->nbwikititles += intval($this->wikititle);
	}
	
	function update_frameline() {
		
		// Update the counter
		$this->text->nbframelines -= intval($this->frameline);
		
		// Really update the frameline status
		$this->frameline = ( $this->line[0] == ' ' );
		
		// Update the counter
		$this->text->nbframelines += intval($this->frameline);
	}
	
	function update_interwikis() {
		
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
		}
		
		// Update the counter
		$this->text->nbinterwikis += count($this->interwikis);
	}
	
	function update_categories() {
		
		// Update the counter
		$this->text->nbcategories -= count($this->categories);
		
		// Initialize the default value: no category (empty array)
		$this->categories = array();
		
		// Compute the categories on the line and update the categories status
		if( preg_match( self::$regexCategories, $this->line ) ) {
			
			preg_match_all( self::$regexCategories, $this->line, $localmatches );
			
			$this->categories = $localmatches[1];
		}
		
		// Update the counter
		$this->text->nbcategories += count($this->categories);
	}
	
	function update_wikibullet() {
		
		// Update the counter
		$this->text->nbwikibullets -= intval($this->wikibullet);
		
		// Really update the wikibullet status
		$this->wikibullet = ( preg_match( '/^\*/u', $this->line ) > 0 );
		
		// Update the counter
		$this->text->nbwikibullets += intval($this->wikibullet);
	}
	
	function update_wikinumber() {
		
		// Update the counter
		$this->text->nbwikinumbers -= intval($this->wikinumber);
		
		// Really update the wikinumber status
		$this->wikinumber = ( preg_match( '/^#/u', $this->line ) > 0 );
		
		// Update the counter
		$this->text->nbwikinumbers += intval($this->wikinumber);
	}
	
	function update_rawbullet() {
		
		global $wgBulletedList;
		
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
	################
	# Private data #
	################
	
	private $text;  // string
	private $lines; // array of AWF_line
	private $index; // integer
	
	
	###############
	# Public data #
	###############
	
	// Number of lines (for external uses)
	public $nblines              = 0;
	public $truenblines          = 0;
	
	// Line-by-line statistics
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
	
	// Local statistics
	public $frames               = array();
	public $nbwikiparagraphs     = 0;
	public $nbrawparagraphs      = 0;
	public $rawparagraphs        = array();
	
	// Global statistics
	public $nbwords              = 0;
	public $nbcapitals           = 0;
	public $nbwikilinks          = 0;
	public $nbtemplates          = 0;
	public $nbmisformedtemplates = 0;
	
	
	###############
	# Constructor #
	###############
	
	public function __construct( $text ) {
		
		global $wgCategoryLocalName, $wgInterwikis;
		
		// Initialization of precomputed constants
		AWF_line::$regexCategories = '/\[\[(?:'.$wgCategoryLocalName.'|Category):(.*)\]\]/uU';
		AWF_line::$regexInterwikis = '/\[\[('.implode( '|',$wgInterwikis ).'):(.*)\]\]/uU';
		
		// Trivial initialization
		$this->text  = $text;
		$this->lines = array();
		$this->index = 0;
		
		// Cut off the text into lines
		$lines = $this->explodeText( $text );
		
		// Initialization of the lines
		$this->nblines = count($lines);
		$this->truenblines = $this->nblines-4;
		for( $i=0; $i<count($lines); $i++ ) {
			
			$this->lines[$i] = new AWF_line( $lines[$i], $this );
		}
		
		// Update the statistics
		$this->update_stat();
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
		
		return ( $index >= 0 && $index < count($this->lines) );
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
	 * WARNING: do not update global nor local statistics!
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
		$this->lines[$index] = new AWF_line( $line );
	}
	
	/**
	 * Remove a line of the text
	 * WARNING: do not update global nor local statistics!
	 * 
	 * @param $index: index of the line in the text
	 */
	function offsetUnset( $index ) {
		
		// Don't continue if the offset doesn't exist
		if( !$this->offsetExists($index) ) return;
		
		// Remove the line and compact the resulting array
		for( $i=$index; $i<count($this->lines)-1; $i++ ) {
			
			unset( $this->lines[$i] );
			$this->lines[$i] = $this->lines[$i+1];
		}
		unset( $this->lines[count($this->lines)-1] );
		
		// Update the number of lines
		$this->nblines = count($this->lines);
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
	 * Add a new line inside the text
	 * WARNING: do not update global nor local statistics
	 * 
	 * @param $index: index of the line in the text
	 * @param $line: string of the new line
	 */
	function addLine( $index, $line ) {
		
		// Move all lines to create a space into the text
		for( $i=count($this->lines)-1; $i >= $index; $i-- ) {
			
			$this->lines[$i+1] = $this->lines[$i];
			unset( $this->lines[$i] );
		}
		
		// Add the new line
		$this->lines[$index] = new AWF_line( $line );
		
		// Update the number of lines
		$this->nblines = count($this->lines);
	}
	
	/**
	 * Update the statistics
	 */
	function update_stat() {
		
		$this->update_local_stat();
		$this->update_global_stat();
	}
	
	/**
	 * Update the local statistics
	 */
	function update_local_stat() {
		
		$this->update_frames();
		$this->update_wikiparagraphs();
		$this->update_rawparagraphs();
	}
	
	/**
	 * Update the global statistics
	 */
	function update_global_stat() {
		
		$this->update_nbwords();
		$this->update_nbcapitals();
		$this->update_nbwikilinks();
		$this->update_nbtemplates();
	}
	
	
	#####################
	# Global statistics #
	#####################
	
	function update_nbwords() {
		
		$this->nbwords = preg_match_all( '/ .* /uU', $this->text, $localmatches );
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
	# Local statistics #
	####################
	
	/**
	 * Compute the frames of the text
	 */
	function update_frames() {
		
		// Initialize the default value: no frame (empty array)
		$this->frames = array();
		
		// If no frameline has been computed, skip this step
		if( $this->nbframelines > 0 ) {
			
			for( $index=0; $index<count($this->lines); $index++ ) {
				
				if( $this->lines[$index]->frameline ) {
					
					$size = 1;
					while( $index+$size<count($this->lines) && $this->lines[$index+$size]->frameline ) $size++;
					
					$this->frames[] = array( 'firstline'=>$index, 'size'=>$size );
					$index += $size;
				}
			}
		}
	}
	
	/**
	 * Update the wikiparagraphs of the text
	 */
	function update_wikiparagraphs() {
		
		$insideTemplates = 0;
		$this->nbwikiparagraphs = 0;
		
		for( $index=1; $index<count($this->lines)-1; $index++ ) {
			
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
		
		$insideTemplates = 0;
		$this->nbrawparagraphs = 0;
		$this->rawparagraphs   = array();
		
		for( $index=1; $index<count($this->lines)-1; $index++ ) {
			
			// Initialize the default value: no rawparagraph (false)
			$this->lines[$index]->rawparagraph = false;
			
			// Update the counter 'templates' (this is a simple checking)
			$insideTemplates += preg_match_all( '/\{\{/u', $this->lines[$index]->line, $localmatches )
			                  - preg_match_all( '/\}\}/u', $this->lines[$index]->line, $localmatches );
			
			if( $insideTemplates > 0 ) continue;
			
			if( $this->lines[$index]->blankline || $this->lines[$index]->frameline || $this->lines[$index]->wikibullet || $this->lines[$index]->wikinumber || $this->lines[$index]->wikititle ) continue;
			
			if( !$this->lines[$index+1]->blankline && !$this->lines[$index+1]->frameline && !$this->lines[$index+1]->wikibullet && !$this->lines[$index+1]->wikinumber && !$this->lines[$index+1]->wikititle ) {
				
				$this->lines[$index]->rawparagraph = true;
				$size = 1;
				while( $index+$size<count($this->lines)-1 && !$this->lines[$index+$size]->blankline && !$this->lines[$index+$size]->frameline && !$this->lines[$index+$size]->wikibullet && !$this->lines[$index+$size]->wikinumber && !$this->lines[$index+$size]->wikititle ) {
					
					$this->lines[$index+$size]->rawparagraph = true;
					$size++;
				}
				
				$this->rawparagraphs[] = array( 'firstline'=>$index, 'size'=>$size );
				
				// Update the counter
				$this->nbrawparagraphs += $size;
				
				$index += $size;
			}
		}
	}
	
	
	######################
	# Display statistics #
	######################
	
	function display_statistics(){
		
		echo "* Statistics:\n";
		
		echo "  * Number of lines: ".$this->nblines."\n";
		
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
			printf( '%.1f%%', $this->nbblanklines/$this->truenblines*100 );
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
		echo count($this->frames);
		echo "\n";
		if( count($this->frames) > 0 ) {
			echo '      * {';
			foreach( $this->frames as $frame )
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
		echo count($this->rawparagraphs);
		echo "\n";
		if( count($this->rawparagraphs) > 0 ) {
			echo '      * {';
			foreach( $this->rawparagraphs as $paragraph )
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

