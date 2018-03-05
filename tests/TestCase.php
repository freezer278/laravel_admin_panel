<?php

namespace Vmorozov\LaravelAdminGenerator\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use \Orchestra\Testbench\TestCase as Orchestra;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

abstract class TestCase extends Orchestra
{
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

    protected function getTestDummyModel(): Model
    {
        return new TestModel();
    }
}