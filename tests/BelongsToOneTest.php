<?php

namespace Tests;

use Tests\Models\User;
use Tests\Models\Restaurant;

class BelongsToOneTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $restaurant = Restaurant::create(['name' => 'ABC Inc']);

        $restaurant->employees()->attach(User::create(['email' => 'operator@example.com']), [
            'role' => Restaurant::ROLE_OPERATOR,
        ]);

        $restaurant->employees()->attach(User::create(['email' => 'member@example.com']), [
            'role' => Restaurant::ROLE_MEMBER,
        ]);

        $restaurantWithoutOperator = Restaurant::create(['name' => 'XYZ Inc']);
    }

    public function testEagerLoading()
    {
        $restaurant = Restaurant::with('operator')->first();

        $this->assertInstanceOf(User::class, $restaurant->operator);
        $this->assertEquals(Restaurant::ROLE_OPERATOR, $restaurant->operator->meta->role);
    }

    public function testLazyLoading()
    {
        $restaurant = Restaurant::find(1);

        $this->assertInstanceOf(User::class, $restaurant->operator);
        $this->assertEquals(Restaurant::ROLE_OPERATOR, $restaurant->operator->meta->role);
    }

    public function testWithDefault()
    {
        $restaurant = Restaurant::find(2);

        $this->assertInstanceOf(User::class, $restaurant->operatorWithDefault);
        $this->assertFalse($restaurant->operatorWithDefault->exists);
        $this->assertNull($restaurant->operator);
    }
}
