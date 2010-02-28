<?php 

require_once( 'Settings.php'   );
require_once( 'Text.php' );


class AWF_linker
{
	########
	# Data #
	########
	
	public $text;
	public $existing_links;
	public $potential_links;
	public $new_links;
	
	private $connec; // Connection with the database
	
	function __construct( $text ) {
		
		$this->text = $text;
		$this->existing_links = array();
		$this->potential_links = array();
		$this->new_links = array();
	}
	
	function collect_potential_links() {
		
		// We assume words beginning with a capital can be a proper name (person, place, enterprise...)
		foreach( $this->text as $line ) {
			
			if( $line->blankline ) continue;
			
			//if( !preg_match( '/(^|[[:blank:]])[[:upper:]][[:^blank:]]*([[:blank:]]|$)/', $line->line ) ) continue;
			// We have at least one word beginning with a capital
			
			// Get an array of individual words - TODO: in French for instance `j'aime' is two words `j' and `aime'
			$linespaced = preg_replace( '/\t/', ' ', $line->line );
			$words = explode( ' ', $linespaced );
			
			// Remove wikisyntax and define clearly the words beginning with a capital
			for( $w=0; $w<count($words); $w++ ) {
				
				$words[$w] = preg_replace( '/(?:\'{2,3}|\]{2}|\[{2}|\}{2,3}| *)/u', '', $words[$w] );
				
				$capital[$w] = false;
				if( preg_match( '/^[A-Z]/u', $words[$w] ) ) $capital[$w] = true;
			}
			
			// Pick up the words beginning with a capital
			for( $w=0; $w<count($words); $w++ ) {
				
				if( !$capital[$w] ) continue;
				
				// Get the longest sequence of words beginning with a capital
				$nbwordscap = 1;
				while( $w+$nbwordscap<count($words) && ( $capital[$w+$nbwordscap] || $words[$w+$nbwordscap] == 'de' || $words[$w+$nbwordscap] == 'la') ) {
					
					$nbwordscap++;
				}
				
				$string = $words[$w];
				$this->potential_links[] = $string;
				for( $i=1; $i<$nbwordscap; $i++ ) {
					
					$string .= ' '.$words[$w+$i];
					if( $capital[$w+$i] ) $this->potential_links[] = $string;
				}
				
				$w += $nbwordscap-1;
			}
		}
		
		// Remove multiple occurences and remove the page title
		$this->potential_links = array_unique( $this->potential_links );
		$this->potential_links = array_diff( $this->potential_links, array( $this->text->title ) );
	}
	
	function verify_potential_links() {
		
		global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBbase;
		
		// Connection
		$connec = mysql_connec( $wgDBserver, $wgDBuser, $wgDBpassword );
		mysql_select_db( $wgDBbase );
		
		
		
		
		
		mysql_close( $connec );
	}
	
	function display_links() {
		
		echo "* Links:\n";
		
		echo '  * Potential links ('.count($this->potential_links)."):\n";
		foreach( $this->potential_links as $link ) {
			
			echo "    * $link\n";
		}
	}
	
	
	
	
}

