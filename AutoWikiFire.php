<?php

// 
// AutoWikiFire 0.3
// 
// License GPL
// 

// This is a text only for a test
$opts = array( 'http' => array( 'method' => 'GET', 'header' => 'User-Agent: AutoWikiFire/0.3' ) );
$context  = stream_context_create( $opts );
$raw_text = file_get_contents( 'http://fr.wikipedia.org/w/index.php?title=Josh_Zuckerman&action=raw&oldid=46951521', false, $context );

$newarticle = true;




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                   CODE                                                                                                            //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//require_once( 'Util.php' );
require_once( 'Statistics.php' );

$nblinesterminal = 179;
$ticksevery = 10;
$nbfiguresleft = 4;

// Horizontal line
for( $i=0; $i<$nblinesterminal; $i++ ) echo '-';
echo "\n";

//////////////
// Analysis //
//////////////

$text = new AWF_text( $raw_text );

$text->display_statistics();

$nblines = $text->nblines;
$lines = array();
foreach( $text as $index=>$line )
	$lines[$index] = $line->line;

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

