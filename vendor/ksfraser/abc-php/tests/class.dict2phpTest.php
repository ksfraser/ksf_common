<?php 
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

/**************************************************
*
*	Run from parent directory: phpunit tests
*
****************************************************/

require_once( dirname( __FILE__ ) .  '/defines.php' );
require_once( dirname( __FILE__ ) .  '/../class.dict2php.php' );

class dict2phpTest extends TestCase
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
        public function testInstanceOf(): dict2php
        {
                $o = new dict2php( null, null, null );
                $this->assertInstanceOf( dict2php::class, $o );
                return $o;
        }
        /**
         * @depends testInstanceOf
         */
        public function testConstructorConfigValues( $o ): dict2php
        {
                $this->assertIsArray( $o->get( 'config_values' ) );
                return $o;
        }
}
