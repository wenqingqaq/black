<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
//        $this->visit('/test')
//            ->type('Taylor', 'name')
//            ->check('terms')
//            ->press('Register')
//            ->dontSee('laravel 5');

        $this->visit('/')
            ->see('Laravel 5')
            ->dontSee('Rails');
    }
}
