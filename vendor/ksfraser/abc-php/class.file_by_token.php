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


include_once( 'class.base_converter.php' );

class file_by_token extends base_converter
{
	protected $textin_array;
	protected $textin_beats_array;
	protected $textout_array;
	function __construct( $infile = null, $outfile = null )
	{
		parent::__construct( $infile, $outfile );
	}
	/**//**
	* Take the input file, tokenize it, and do the replacement.
	*
	*/
	function search_replace( $sort_dict )
	{
		//Separate the input into beats.
		$this->textin_beats_array = explode( " ", $this->textin );
		$this->textin_array = str_replace( array_keys( $sort_dict ), array_values( $sort_dict ), $this->textin_beats_array );
		$this->textout = implode( ' ', $this->textin_array );
		//var_dump( $this->textin_beats_array );
		//var_dump( $this->textin_array );
		return;

		/**
		* Maybe easier to do a str_replace( array_keys( $sort_dict ), array_values( $sort_dict ), $textin )  
		*BUT we then can't do logging
		* /
*		foreach( $sort_dict as $key => $val )
*		{
*			//echo $textin;
*			$this->textout = str_replace( $key, $val, $this->textin );
*			//echo $textout;
*			$this->textin = $this->textout;
*		}
*		return $this->textout;
		*/
	}
}
