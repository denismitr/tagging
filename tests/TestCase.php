<?php

use Denismitr\Tagging\TaggingServiceProvider;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TaggingServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();

        Eloquent::unguard();

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations')
        ]);
    }


    public function tearDown()
    {
        \Schema::drop('lessons');
    }


    public function assertIn(array $items, \IteratorAggregate $testArray)
    {
        foreach ($items as $item) {
            $this->assertContains($item, $testArray);
        }
    }


    protected function getEnvironmentSetup($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);

        \Schema::create('lessons', function($table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }
}
