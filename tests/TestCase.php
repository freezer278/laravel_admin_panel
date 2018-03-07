<?php

namespace Vmorozov\LaravelAdminGenerator\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use \Orchestra\Testbench\TestCase as Orchestra;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

abstract class TestCase extends Orchestra
{
    const MODEL_DEFAULT_ID = 12;
    const MEDIA_DEFAULT_ID = 12;

    public function setUp()
    {
        $this->loadEnvironmentVariables();
        parent::setUp();
        $this->setUpDatabase($this->app);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    protected function loadEnvironmentVariables()
    {
        if (! file_exists(__DIR__.'/../.env')) {
            return;
        }
        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AdminGeneratorServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('file_uploads.files_upload_storage', 'local');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {

    }

    /**
     * Generates test model.
     *
     * @return Model
     */
    protected function getTestDummyModel(): Model
    {
        return new TestModel();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $propertyName Property name to call
     *
     * @return mixed Method return.
     */
    public function getPrivatePropertyValue(&$object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}