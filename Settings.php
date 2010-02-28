<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                                                   //
//                                   SETTINGS                                                                                                        //
//                                                                                                                                                   //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/***********************************************************
 *                                                         *
 * General framework about the wiki - objective parameters *
 *                                                         *
 ***********************************************************/

// Name of the database server
$wgDBserver = 'localhost';
$wgDBuser = 'wiki';
$wgDBpassword = 'wiki';
$wgDBbase = 'frwiki_p';

// Local name of the 'Category' magic name
$wgCategoryLocalName = 'Catégorie';

// Wikipedia interwikis (13/02/2010)
$wgInterwikis  = array( 'aa', 'ab', 'ace', 'af', 'ak', 'als', 'am', 'an', 'ang', 'ar', 'arc', 'ast', 'av', 'ay', 'az', 'ba', 'bar', 'bat-smg', 'bcl', 'be', 'be-x-old', 'bg', 'bh', 'bi', 'bm', 'bn', 'bo', 'bpy', 'br', 'bs', 'bug', 'bxr', 'ca', 'cbk-zam', 'cdo', 'ce', 'ceb', 'ch', 'cho', 'chr', 'chy', 'ckb', 'co', 'cr', 'crh', 'cs', 'csh', 'cu', 'cv', 'cy', 'da', 'de', 'diq', 'dsb', 'dv', 'dz', 'ee', 'el', 'eml', 'en', 'eo', 'es', 'et', 'eu', 'ext', 'fa', 'ff', 'fi', 'fiu-vro', 'fj', 'fo', 'fr', 'frp', 'fur', 'fy', 'ga', 'gan', 'gd', 'gl', 'glk', 'gn', 'got', 'gu', 'gv', 'ha', 'hak', 'haw', 'he', 'hi', 'hif', 'ho', 'hr', 'hsb', 'ht', 'hu', 'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 'ik', 'ilo', 'io', 'is', 'it', 'iu', 'ja', 'jbo', 'jv', 'ka', 'kaa', 'kab', 'kg', 'ki', 'kj', 'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 'ksh', 'ku', 'kv', 'kw', 'ky', 'la', 'lad', 'lb', 'lbe', 'lg', 'li', 'lij', 'lmo', 'ln', 'lo', 'lt', 'lv', 'map-bms', 'mdf', 'mg', 'mh', 'mhr', 'mi', 'mk', 'ml', 'mn', 'mo', 'mr', 'ms', 'mt', 'mus', 'mwl', 'my', 'myv', 'mzn', 'na', 'nah', 'nan', 'nap', 'nb', 'nds', 'nds-nl', 'ne', 'new', 'ng', 'nl', 'nn', 'no', 'nov', 'nrm', 'nv', 'ny', 'oc', 'cm', 'or', 'os', 'pa', 'pag', 'pam', 'pap', 'pcd', 'pdc', 'pi', 'pih', 'pl', 'pms', 'pnb', 'pnt', 'ps', 'pt', 'qu', 'rm', 'rmy', 'rn', 'ro', 'roa-rup', 'roa-tara', 'ru', 'rw', 'sa', 'sah', 'sc', 'scn', 'sco', 'sd', 'se', 'sg', 'sh', 'si', 'simple', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'srn', 'ss', 'st', 'stq', 'su', 'sv', 'sw', 'szl', 'ta', 'te', 'tet', 'tg', 'th', 'ti', 'tk', 'tl', 'tn', 'to', 'tokipona', 'tp', 'tpi', 'tr', 'ts', 'tt', 'tum', 'tw', 'ty', 'udm', 'ug', 'uk', 'ur', 'uz', 've', 'vec', 'vi', 'vls', 'vo', 'wa', 'war', 'wo', 'wuu', 'xal', 'sh', 'yi', 'yo', 'za', 'zea', 'zh', 'zh-cfr', 'zh-classical', 'zh-yue', 'zu' );

// Language interwikis which don't appear in the interwiki toolbar (13/02/2010)
$wgFalseInterwikis = array( 'closed-zh-tw', 'cz', 'epo', 'jp', 'minnan', 'nomcom' );

// Magic words
$wgMagicWords = array( 'DEFAULTSORT:', 'CURRENT(?:DAY|MONTH|YEAR)' );

/*************************************************
 *                                               *
 * Subjective parameters used for the properties *
 *                                               *
 *************************************************/

// Characters recognized as a bulleted list
$wgBulletedList = array( '-', '\*' );

// Characters recognized as a numbered list (the number must be parenthezed)
$wgNumberedList = array( '([0-9]{1,3})\.' );


/*************************************************
 *                                               *
 * Subjective parameters used for the décoffrage *
 *                                               *
 *************************************************/


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

$wgNbEmptyLinesMaxBetweenTwoBullets = 1;
$wgNbFilledLinesMaxBetweenTwoBullets = 5;

$wgNbEmptyLinesMaxBetweenTwoNumbers = 0;
$wgNbFilledLinesMaxBetweenTwoNumbers = 2;



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
 * Recognize a paragraph in the aim to add blank lines in compact paragraphs
 */
$wgMinNbLinesParagraph = 6;

$wgMinLengthLinesParagraph = 40;


$wgNbLinesVerySmallParagraph = 2;

/**********************************************
 *                                            *
 * Subjective parameters used for the linking *
 *                                            *
 **********************************************/

/*****************************************************
 *                                                   *
 * Subjective parameters used for the categorization *
 *                                                   *
 *****************************************************/

/**************************************************
 *                                                *
 * Subjective parameters used for the interwiking *
 *                                                *
 **************************************************/




/**
 * 
 * Debugging
 * 
 */

$wgDebug = true;

