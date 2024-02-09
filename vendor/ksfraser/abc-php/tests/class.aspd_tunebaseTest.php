<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

/**************************************************
*
*	Run from parent directory: phpunit tests
*
****************************************************/

require_once( dirname( __FILE__ ) .  '/defines.php' );
require_once( dirname( __FILE__ ) .  '/../class.abc_tunebase.php' );

class abc_tunebaseTest extends TestCase
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
        public function testInstanceOf(): abc_tunebase
        {
                $o = new abc_tunebase( null, null, null );
                $this->assertInstanceOf( abc_tunebase::class, $o );
                return $o;
        }
        /**
         * @depends testInstanceOf
         */
        public function testConstructorConfigValues( $o ): abc_tunebase
        {
                $this->assertIsArray( $o->get( 'config_values' ) );
                return $o;
        }
}
