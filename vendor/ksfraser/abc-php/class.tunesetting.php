<?php

/**//**************************************************************
 * I have built an array of items from the DOCs from bmw to try
 * and convert into ABC.  This should save a bunch of typesetting tim.
 *
 * In addition, I want to be able to add Cainnteraichd to each of the tunes
 * as well as the ABC notes below for students who don't read music lines well.
 *
 * Being able to convert cainnteraichd into ABC would be a bonus.
 *
 * Last possibility would be the conversion of ABC into bmw but that isn't 
 * a current goal for me as I don't intend to use BMW.  MuseScore OTOH...
 * 
 *
 * TODO
 *  During the search/replace, we end up in the situation where
 * a doubling become {gde} and then the next pass replaces
 * the gd with {d} so we end up with {{d}e}.
 *
 * Solution would be to do a string tokenization, and then replace
 * each token once instead of S/R the entire string a whole bunch of times.
 * Another way would be to change the dictionary so that "gd" becomes " gd "
 * for the match so that {gd doesn't trigger...
 * * ***************************************************************/

/***USAGE***
 * Quick Start
 * $tune = new abc_tunesetting();	//Sets a bunch of headers
 * $tune->add_key( "HP" );		//bagpipes
 * $tune->add_notelength( $line );	// 1/16				L
 * $tune->add_meter( $line );		// 2/4				M
 * $tune->add_tempo( $line );		// 90				Q
 * $tune->add_title( $line );		// "My Tune"			T
 * $tune->add_body( $bar, 1, 1, 1 );
 * $tune->add_body( $bar, 1, 1, 2 );
 * $tune->add_body( $bar, 1, 1, 3 );
 * $tune->add_body( $bar, 1, 1, 4 );
 * $tune->output();
 *
 *
 * $tune->add_key( "HP" );		//bagpipes
 * $tune->add_transcription( $line );	//KSF
 * $tune->add_notelength( $line );	// 1/16				L
 * $tune->add_meter( $line );		// 2/4				M
 * $tune->add_tempo( $line );		// 90				Q
 * $tune->add_title( $line );		// "My Tune"			T
 * $tune->add_composer( $line );	// KSF				C
 * $tune->add_history( $line );		//Written after XYZ		H
 * $tune->add_books( $line );		//Scots Guard 3 pg 1		B
 * $tune->add_voice_arr( $line );					V
 * $tune->add_discography( $line );	//The Beatles Greatest Hits	D
 * $tune->add_file_url( $line );	//apple.com			F
 * $tune->add_group( $line );		//Flute				G
 * $tune->add_instruction( $line );	// 				I
 * $tune->add_macro( $line );		// 				m
 * $tune->add_userdef_arr( $line );	// r=//				U
 * $tune->add_notes( $line );		//				N
 * $tune->add_origin( $line );		//Brittany			O
 * $tune->add_parts( $line );		// For playback - part order	P
 * $tune->add_rythm( $line );		// Strathspey			R
 * $tune->add_source( $line );		//				S
 * $tune->add_words_bottom( $line );	// ASPD playts with...		W
 * $tune->add_words( $line, $verse = 0, $linenum = 1, $barnum = 1 );
 * $tune->add_body( $bar, $voice = 1, $linenum = 1, $barnum = 1 )
 * $tune->add_melody( $bar,  $linenum = 1, $barnum = 1, $add_cannt = true, $add_abc = false )
 * $tune->add_harmony( $bar,  $linenum = 1, $barnum = 1 )
 * $tune->add_c_harmony( $bar,  $linenum = 1, $barnum = 1 )
 * $tune->add_snare( $bar,  $linenum = 1, $barnum = 1 )
 * $tune->add_bass( $bar,  $linenum = 1, $barnum = 1 )
 * $tune->add_tenor( $bar,  $linenum = 1, $barnum = 1 )
 * $tune->add_cainnteraichd( $bar,  $linenum = 1, $barnum = 1 )
 * $tune->add_ABC( $bar,  $linenum = 1, $barnum = 1 )
 *
 */

global $abc_dict;
require_once( 'abc_dict.php' );	//Used by get_cannt
require_once( 'class.abc_tunebase.php' );	//Used by get_cannt


/**//**
 * This class is for the setting of ONE tune within a document.
 * 
 * This class is not for an entire document.  It is not designed
 * for document headers, or multiple tunes.  Multiple tunes within
 * a document should have its own class.
 * */
class abc_tunesetting extends abc_tunebase
{
	/****** The following are Inherited!
	protected $headers_anglo_arr;
	protected $index; //X
	protected $key; //K
	protected $notelength;	//L Default of 1/16 for 2/4 or 6/8.  1/8 for 3/4 4/4 9/8 12/8...
	protected $meter;	//M Default is 4/4 if omitted
	protected $tempo;	//Q default 100bpm   e.g. Q: "Allegro" 1/4=120
	protected $title_arr;	//T
	protected $composer;	//C
	protected $history_arr;	//H
	protected $books_arr;	//B
	protected $voice_arr;	//V
	protected $discography;	//D
	protected $file_url;	//F
	protected $group;	//G  e.g. G:Flute
	protected $instruction_arr;	//I
	protected $macro_arr;	//m
	protected $notes;	//N  i.e. referrences to other similar tunes.
	protected $origin;	//O  e.g. O:Canada; Nova Scotia; Halifax.
	protected $parts;	//P
	protected $rythm;	//R  e.g. hornpipe, double jig, single jig, 48-bar polka
	protected $source;	//S
	protected $userdef_arr;	//U
	protected $transcription;	//Z
	//BODY
	protected $words_arr;	//w	LYRICS
	protected $body_voice_arr;	//The list of voices for the [V:1] line headers
	protected $voice_name_arr;	//The list of voices Names for searching
	protected $voicecount;
	protected $words_bottom_arr;//W
	protected $complete_tune;//What we are going to output.
	protected $body_arr;
	protected $body;
	protected $line_count;	//How many lines did we add?
	protected $header_symbols_arr;
	protected $voices_obj_arr;	//Array of Voice objects
	// X must be first field.  T is second field.  K is last field.  All others optional.
	// Accidentals:
	// 	^ sharp
	// 	_ flat
	// 	= natural
	// 	^^ and __ allowed
	****** ! Inherited */
	function __construct()
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		parent::__construct();
	}
	function get_cannt( $note )
	{
		$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		if( strlen( $note ) < 1 )
		{
			return "";
		}
		global $abc;
		$this->var_dump( __FUNCTION__  . ":" . __LINE__ . "::Note to find:: $note" );
	        if( isset( $abc[$note]['cannt'] ) )
		{
			$c =  $abc[$note]['cannt'];
			$this->var_dump( __FUNCTION__  . ":" . __LINE__ . "::FOUND:: $c" );
	                return $c;
		}
	        else
		{
			$this->var_dump( __FUNCTION__  . ":" . __LINE__ . "::NO CANNT FOUND:: $note", PEAR_LOG_ERR );
	                return $note;
		}
	}

	function xfer_headers( abc_tunesetting $dest )
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		foreach( $this->header_symbols_arr as $header=>$val )
		{
			if( isset( $this->$header )  AND strlen( $this->$header ) > 1 )
			{
				echo "Setting header $header\n\r";
				$call = "add_" . $header;
				$dest->$call( $val );
			}
			else
			{
				echo "Header not set! $header \n\r";
			}
		}
	}
	function output()
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		// X must be first field.  T is second field.  K is last field.  All others optional.
		$this->complete_tune = "X:" . $this->index . "\n\r";
		foreach( $this->title_arr as $title )
		{
			$this->complete_tune .= "T:" . $title . "\n\r";
		}
		$this->build_headers();
		$this->complete_tune .= "K:" . $this->key . "\n\r";
		$this->build_body();
		$this->complete_tune .= $this->body;
	}
	function build_headers()
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		$this->validate_rythm();
		$this->complete_tune .= "Z:" . $this->transcription . ' 
';
		$this->complete_tune .= "L:" . $this->notelength . ' 
';
		$this->complete_tune .= "M:" . $this->meter . ' 
';
		$this->complete_tune .= "Q:" . $this->tempo . ' 
';
		$this->complete_tune .= "C:" . $this->composer . ' 
';
		if( isset( $this->history_arr ) )
		{
			foreach( $this->history_arr as $history )
			{
				$this->complete_tune .= "H:" . $history . ' 
';	
			}
		}
		if( isset(  $this->books_arr ) )
		{
			foreach( $this->books_arr as $book )
			{
				$this->complete_tune .= "B:" . $book . ' 
';
			}
		}
		if( isset( $this->voice_arr ) )
		{
			foreach( $this->voice_arr as $voice )
			{
				$this->complete_tune .= "V:" . $voice . ' 
';
			}
		}
		$this->complete_tune .= "D:" . $this->discography . ' 
';
		$this->complete_tune .= "F:" . $this->file_url . ' 
';
		$this->complete_tune .= "G:" . $this->group . ' 
';
		if( isset( $this->instruction_arr ) )
		{
			foreach( $this->instruction_arr as $instruction )
			{
				$this->complete_tune .= "I:" . $instruction . ' 
';	
			}
		}
		if( isset( $this->macro_arr ) )
		{
			foreach( $this->macro_arr as $macro )
			{
				$this->complete_tune .= "m:" . $macro . ' 
';
			}
		}
		$this->complete_tune .= "N:" . $this->notes . ' 
';
		$this->complete_tune .= "O:" . $this->origin . ' 
';
		$this->complete_tune .= "P:" . $this->parts . ' 
';
		$this->complete_tune .= "R:" . $this->rythm . ' 
';
		$this->complete_tune .= "S:" . $this->source . ' 
';
		foreach( $this->userdef_arr as $userdef )
		{
			$this->complete_tune .= "U:" . $userdef . ' 
';
		}
	}
	/**//**
	 * Tune Types in Piping have associated time signatures
	 *
	 * These are Pipe Band specific, not generic!
	 *
	 * */
	function validate_rythm()
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		switch( $this->meter )
		{
		case '2/2':
			if( $this->rythm !== "Reel" )
				$this->rythm = "Reel";
			break;
		case '2/4':
			if( $this->rythm !== "March" )
				if( $this->rythm !== "Hornpipe" )
					$this->rythm = "March";
		case '4/4':
			if( $this->rythm !== "March" )
				if( $this->rythm !== "Strathspey" )
					$this->rythm = "March";
			break;
		case '5/4':
		case '3/4':
			if( $this->rythm !== "March" )
				$this->rythm = "March";
			break;
		case '6/8':
			if( $this->rythm !== "March" )
				if( $this->rythm !== "Jig" )
					$this->rythm = "March";
			break;
		case '9/8':
			if( $this->rythm !== "March" )
				if( $this->rythm !== "Jig" )
					if( $this->rythm !== "Slip Jig" )
						$this->rythm = "March";
			break;
		case '12/8':
			if( $this->rythm !== "March" )
				if( $this->rythm !== "Jig" )
					$this->rythm = "March";
			break;
/*
		case default:
			$this->rythm = "March";
			break;
*/
		}
	}
	/**//**
	 * This is the complicated part - multiple valid body styles
	 *
	 * I'm going to standardize on the following:
	 * [V:1] a [|: ABcd | abcd | abcd |abcd | $
	 * w:    a [|: lyr-ics lyr-ics | lyr-ics lyr-ics | lyr-ics lyr-ics | lyr-ics lyr-ics | $
	 * [V:2] a [|: ABcd | abcd | abcd |abcd | $
	 * [V:3] a [|: ABcd | abcd | abcd |abcd | $
	 * [V:4] a [|: ABcd | abcd | abcd |abcd | $
	 * ...
	 * W:	Non Lyric Text on bottom
	 * W:	----
	 *
	 * TODO: Figure out how to determine PART endings.
	 * */
	function build_body()
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		$this->body = "";
		$WordsLine = get_voice_number_by_name( "Words" );
		$this->var_dump( "Words Line is $WordsLine \n\r" );
		for( $voice = 1; $voice <= $this->voicecount; $voice++ )
		{
			$line = "[V:" . $this->body_voice_arr[$voice] . "]";
			$restcount = 0;
			//8 part tune - 16 lines
			for( $linenum=1; $linenum < 17; $linenum++ )
			{
				//Bar 0 is a pickup, only applies to odd lines within a part.
				//However, what about second endings for an entire line - throws
				//off the line number count.
				for( $barnum=0; $barnum < 5; $barnum++ )
				{
					if( isset( $this->body_arr[$voice][$linenum][$barnum] ) )
					{
						if( $voice == $WordsLine)
						{
							$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
							$this->var_dump( "This is a Wordsline\n\r" );
							$this->add_words_bottom( $this->body_arr[$voice][$linenum][$barnum] );
						}
						else
						{
							$line .= $this->body_arr[$voice][$linenum][$barnum];
						}
					}
					else
					{
						$line .= " Z ";
						$restcount++;
					}
					$line .= " | ";
				}
				if( $restcount >= 4 )
				{
					$line = " | X4 |  $ \n\r";
				}
				else
				{
					$line .= " $ \n\r";
				}
			}
			$this->body .= $line;
			unset( $line );
		}
		foreach( $this->words_bottom_arr as $line )
		{
			$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
			$this->var_dump( "Adding WordsLine to body\n\r" );
			$this->body .= "W: " . $line . "\n\r";
		}

	}
	/**//**
	 * Music Lines
	 *
	 * Bar 0 is pickup
	 *
	 * */
	function add_body( $bar, $voice = 1, $linenum = 1, $barnum = 1 )
	{
					$this->var_dump( __FUNCTION__  . ":" . __LINE__ );
		//echo "Setting Line by Voice $voice $linenum:$barnum:$bar \r\n";
		if( $linenum > $this->line_count )
		{
			$this->line_count = $linenum;
		}
		if( ! isset( $this->body_arr[$voice][$linenum][$barnum] ) )
			$this->body_arr[$voice][$linenum][$barnum] = $bar;
		else
		{
/**
 * Pipe Band...
			if( $barnum == 4 )
			{
				//Bar 4 for this line is already set
				//echo "Bar 4 already set for $voice:$linenum:$barnum \n\r";
				$linenum++;
				$barnum=1;
			}
			else
			{
*/
				$barnum++;
/*
			}
*/
			$this->add_body( $bar, $voice, $linenum, $barnum );
		}
		//$this->var_dump( $this->body_arr );
	}
	/**//******************************************************************************
	*
	*
	* From https://github.com/jwdj/EasyABC/blob/master/abc_context.py
	*
	***********************************************************************************/
	function get_tune_start_line( )
	{
	}
	/**//******************************************************************************
	*
	*
	* From https://github.com/jwdj/EasyABC/blob/master/abc_context.py
	*
	***********************************************************************************/
	/**//******************************************************************************
	*
	*
	* From https://github.com/jwdj/EasyABC/blob/master/abc_context.py
	*
	***********************************************************************************/
	/**//******************************************************************************
	*
	*
	* From https://github.com/jwdj/EasyABC/blob/master/abc_context.py
	*
	***********************************************************************************/
}


