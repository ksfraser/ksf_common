<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

/**************************************************
*
*	Run from parent directory: phpunit tests
*
****************************************************/

require_once( dirname( __FILE__ ) .  '/defines.php' );
require_once( dirname( __FILE__ ) .  '/../class.aspd_tune.php' );

/*********

require_once( 'class.abc_file.php' );
require_once( 'class.ksf_file.php' );
require_once( 'class.abcparser.php' );
require_once( 'class.aspd_tune.php' );
require_once( 'abc_dict.php' );

if( isset( $argv[2] ) )
        $path = $argv[2];
else
        $path = "/mnt2/fs/business/PipingLessons/abc2midi";
$filename = $argv[1];
try {
        $file = new ksf_file( $filename, $path );
        $file->set( "loglevel", PEAR_LOG_ERROR );
        $file->open();
        $tfile = new abc_file();
        $tfile->set( "loglevel", PEAR_LOG_ERROR );
        $tfile->processFile( $file->get_all_contents() );
} catch( Exception $e )
{
}
$str = $tfile->getTune( 0 );
$parser = new abcparser();
$parser->set( "loglevel", PEAR_LOG_DEBUG );
$parser->set( "tune", $str );
$parser->process();

$aspd = new aspd_tune();
$body = $parser->get( 'body_arr' );
$aspd->set( "loglevel", PEAR_LOG_DEBUG );
$aspd->process_bars( $body[0][1] );
//var_dump( $aspd );
$aspd->output();
$aspd->print_tune();
var_dump( $aspd->log );

******/




class aspd_tuneTest extends TestCase
{
 	protected $shared_var;
        protected $shared_val;
        protected $pref_tablename;
        function __construct()
        {
                parent::__construct();
                $this->shared_var =  'pub_unittestvar';
                $this->shared_val = '1';
                $this->pref_tablename = 'test';

        }
        public function testInstanceOf(): aspd_tune
        {
                $o = new aspd_tune( null, null, null );
                $this->assertInstanceOf( aspd_tune::class, $o );
                return $o;
        }
        /**
         * @depends testInstanceOf
         */
        public function testConstructorConfigValues( $o ): aspd_tune
        {
                $this->assertIsArray( $o->get( 'config_values' ) );
                return $o;
        }
        /**
         * @depends testInstanceOf
         */
	public function testSet( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testSet_current_voice( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_body( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testTokenizer( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_melody( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_harmony( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_c_harmony( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_snare( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_bass( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_tenor( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_canntaireachd( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_ABC( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_words( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testBuild_body( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testPrint_tune( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testProcess_bars( $o ): aspd_tune
	{
		return $o;
	}
        /**
         * @depends testInstanceOf
         */
	public function testAdd_bar( $o ): aspd_tune
	{
		//add_bar uses ->current_voice to determine
		//which function to call to add the bar.
		//I had originally planned to test each "voice" and
		//compare against the results of the tests for each of
		//those voices above, but then I realized that there was
		//no need as those tests would test the called functions.
		//
		//All I need to test is ->current_voice being set or not set.
		//add_bar defaults to melody if not set.
		$bar = "ABcd";
		$before = $o->get( "body_arr" );
		$o->add_bar( $bar );
			//This should have added the bar to the melody voice_line
		$after = $o->get( "body_arr" );
		$line = $o->get( "line_count" );
		$vnum = $o->get_voice_number_by_name( "melody" );\
		$melodynum = $vnum;
		//compare before and after checking that ONLY the one array element changed and it was for melody
		$this->assertContains( $after[$vnum][$line], $bar );

		$voice = "harmony";
		$o->set( "current_voice", $v" );
		$o->add_bar( $bar );
			//This should have added to the harmony voice line
		$after2 = $o->get( "body_arr" );
		$line = $o->get( "line_count" );
		$vnum = $o->get_voice_number_by_name( $voice );
		$this->assertContains( $after2[$vnum][$line], $bar );

		//Check that only the Harmony voice changed.
		$this->asserSame( $after[$melodynum], $after2[$melodynum] );


		return $o;
	}
        /**
         * @depends testInstanceOf
         */
/*
	public function testPrint_tune( $o ): aspd_tune
	{
		return $o;
	}
	public function testPrint_tune( $o ): aspd_tune
	{
		return $o;
	}
	public function testPrint_tune( $o ): aspd_tune
	{
		return $o;
	}
	public function testPrint_tune( $o ): aspd_tune
	{
		return $o;
	}
*/
}
