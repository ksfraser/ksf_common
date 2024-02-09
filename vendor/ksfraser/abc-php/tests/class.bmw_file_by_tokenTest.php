<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

/**************************************************
*
*	Run from parent directory: phpunit tests
*
****************************************************/

require_once( dirname( __FILE__ ) .  '/defines.php' );
require_once( dirname( __FILE__ ) .  '/../class.bmw_file_by_token.php' );

class bmw_file_by_tokenTest extends TestCase
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
        public function testInstanceOf(): bmw_file_by_token
        {
                $o = new bmw_file_by_token( null, null, null );
                $this->assertInstanceOf( bmw_file_by_token::class, $o );
                return $o;
        }
        /**
         * @depends testInstanceOf
         */
        public function testConstructorConfigValues( $o ): bmw_file_by_token
        {
                $this->assertIsArray( $o->get( 'config_values' ) );
                return $o;
        }
}
