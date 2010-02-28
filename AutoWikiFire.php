<?php

// 
// AutoWikiFire 0.4
// 
// License GPL
// 

// Note: to execute this code, type in a terminal `php autowikifire-0.3.php' (you must have PHP installed on your machine)

require_once( 'Text.php'       );
require_once( 'Decoffrage.php' );
require_once( 'Linker.php'     );

// Options
$nbColsTerminal = 179;
$context  = stream_context_create( array( 'http' => array( 'method' => 'GET', 'header' => 'User-Agent: AutoWikiFire/0.3' ) ) );

// This is a text only for a test
$raw_texts = array();
$raw_texts[] = array( 'Josh Zuckerman', file_get_contents( 'http://fr.wikipedia.org/w/index.php?title=Josh_Zuckerman&action=raw&oldid=46951521', false, $context ) );
$raw_texts[] = array( 'testlinks', 'Saint Jacques de la Lande' );

foreach( $raw_texts as $raw_text ) {
	
	###################
	# Create the text #
	###################
	
	$text = new AWF_text( $raw_text[0], $raw_text[1] );
	
	$text->toggleFigures( $nbColsTerminal );
	echo $text;
	
	// Horizontal line
	for( $i=0; $i<$nbColsTerminal; $i++ ) echo '-'; echo "\n";
	
	$text->display_props();
	
	
	##############
	# Decoffrage #
	##############
	
	$decoffrage = new AWF_Decoffrage( $text );
	$decoffrage->rawparagraphs();
	$decoffrage->rawlists();
	
	
	###########
	# Linking #
	###########
	
	$linker = new AWF_Linker( $text );
	
	$linker->collect_potential_links();
	
	$linker->display_links();
	
	
	######################
	# Display the result #
	######################
	
	// Horizontal line
	for( $i=0; $i<$nbColsTerminal; $i++ ) echo '-'; echo "\n";
	
	// Text
	$text->toggleFigures( $nbColsTerminal );
	echo $text;
	
	// Horizontal line
	for( $i=0; $i<$nbColsTerminal; $i++ ) echo '-'; echo "\n";
}

