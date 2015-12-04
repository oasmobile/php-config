<?php
use Oasis\Mlib\Config\AbstractYamlConfiguration;
use Oasis\Mlib\Config\test\TestConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 12/4/15
 * Time: 11:42 PM
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $mock;
    /** @var AbstractYamlConfiguration */
    protected $conf;
    /** @var string */
    protected $file;

    protected function setUp()
    {
        $this->mock = $this->conf =
            $this->getMockBuilder(AbstractYamlConfiguration::class)
                 ->setMethods(
                     [
                         "getConfigTreeBuilder",
                         "assignProcessedConfig",
                     ]
                 )
                 ->getMock();
        $this->file = sys_get_temp_dir() . "/php-config.test.yml";
    }

    protected function tearDown()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    public function testConfigReading()
    {
        $content = <<<YAML
student_info:
    name: job
    age: 9

YAML;
        file_put_contents($this->file, $content);
        $this->mock->expects($this->once())
                   ->method('getConfigTreeBuilder')
                   ->willReturnCallback(
                       function () {
                           $def  = new TreeBuilder();
                           $root = $def->root('def');

                           $info = $root->children()->arrayNode('student_info');
                           $info->children()->scalarNode('name');
                           $info->children()->integerNode("age")->isRequired();

                           return $def;
                       }
                   );
        $this->mock->expects($this->once())
                   ->method('assignProcessedConfig');

        $this->conf->loadYaml(basename($this->file), [dirname($this->file)]);
        $this->assertEquals(
            [
                "student_info" => [
                    "name" => "job",
                    "age"  => 9,
                ],
            ],
            PHPUnit_Framework_Assert::readAttribute($this->conf, 'processedConfig')
        );
    }
}
