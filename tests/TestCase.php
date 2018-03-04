<?php

namespace Vmorozov\LaravelAdminGenerator\Tests;

use Dotenv\Dotenv;
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
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {

    }
}