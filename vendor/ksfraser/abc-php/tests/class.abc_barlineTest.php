<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

/**************************************************
*
*	Run from parent directory: phpunit tests
*
****************************************************/

require_once( dirname( __FILE__ ) .  '/defines.php' );
require_once( dirname( __FILE__ ) .  '/../class.abc_barline.php' );

class abc_barlineTest extends TestCase
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
        public function testInstanceOf(): abc_barline
        {
                $o = new abc_barline( null, null, null );
                $this->assertInstanceOf( abc_barline::class, $o );
                return $o;
        }
        /**
         * @depends testInstanceOf
         */
        public function testConstructorConfigValues( $o ): abc_barline
        {
                $this->assertIsArray( $o->get( 'config_values' ) );
                return $o;
        }
}
