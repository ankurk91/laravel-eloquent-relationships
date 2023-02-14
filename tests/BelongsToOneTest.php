<?php
declare(strict_types=1);

namespace Tests;

use Tests\Models\User;
use Tests\Models\Restaurant;

class BelongsToOneTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /**
         * @var Restaurant $restaurant
         */
        $restaurant = Restaurant::create(['name' => 'ABC Inc']);

        $restaurant->employees()->attach(User::create(['email' => 'operator@example.com']), [
            'is_operator' => 1,
        ]);

        $restaurant->employees()->attach(User::create(['email' => 'member@example.com']));

        $restaurantWithoutOperator = Restaurant::create(['name' => 'XYZ Inc']);
    }

    public function testEagerLoading()
    {
        $restaurant = Restaurant::with('operator')->first();

        $this->assertInstanceOf(User::class, $restaurant->operator);
        $this->assertEquals(1, $restaurant->operator->pivot->is_operator);
    }

    public function testLazyLoading()
    {
        $restaurant = Restaurant::find(1);

        $this->assertInstanceOf(User::class, $restaurant->operator);
        $this->assertEquals(1, $restaurant->operator->pivot->is_operator);
    }

    public function testWithDefault()
    {
        $restaurant = Restaurant::find(2);

        $this->assertInstanceOf(User::class, $restaurant->operatorWithDefault);
        $this->assertFalse($restaurant->operatorWithDefault->exists);
        $this->assertNull($restaurant->operator);
    }
}
