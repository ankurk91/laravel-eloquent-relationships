<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->initDB();
        $this->migrateDB();
    }

    protected function initDB()
    {
        $db = new DB();
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $db->setAsGlobal();
        $db->bootEloquent();
    }

    protected function migrateDB()
    {
        DB::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->timestamps();
        });

        DB::schema()->create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        DB::schema()->create('restaurant_user', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->tinyInteger('role')->unsigned()->default(0);

            $table->timestamps();
        });
    }
}
