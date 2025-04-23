<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Ksfraser\Origin\origin;

require_once(dirname(__FILE__) . '/defines.php');
require_once(dirname(__FILE__) . '/../class.origin.php');

class originTest extends TestCase
{
    protected $originInstance;

    protected function setUp(): void
    {
        $this->originInstance = new origin(null, null, null);
    }

    /**
     * @dataProvider propertyProvider
     */
    public function testPropertyTypes(string $property, string $assertionMethod, $expectedValue = null): void
    {
        $value = $this->originInstance->get($property);
        $this->$assertionMethod($value, $expectedValue);
    }

    public function propertyProvider(): array
    {
        return [
            ['config_values', 'assertIsArray'],
            ['tabs', 'assertIsArray'],
            ['help_context', 'assertIsString'],
            ['tb_prefs', 'assertSame', '0_'],
            ['loglevel', 'assertSame', 7],
            ['errors', 'assertIsArray'],
            ['log', 'assertIsArray'],
            ['fields', 'assertIsArray'],
            ['data', 'assertIsArray'],
            ['testvar', 'assertIsArray'],
            ['object_fields', 'assertIsArray'],
            ['application', 'assertIsString'],
            ['module', 'assertIsString'],
            ['container_array', 'assertIsArray'],
            ['interestedin', 'assertIsArray'],
            ['obj_var_name_arr', 'assertIsArray'],
            ['dest_var_name_arr', 'assertIsArray'],
            ['name_value_list', 'assertIsArray'],
        ];
    }

    public function testEventloopInstance(): void
    {
        $this->assertInstanceOf(eventloop::class, $this->originInstance->eventloop);
    }

    public function testClientIsNull(): void
    {
        $this->assertNull($this->originInstance->get('client'));
    }
}
