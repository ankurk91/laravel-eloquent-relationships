<?php

namespace Tests;

use Tests\Models\Tag;
use Tests\Models\User;
use Tests\Models\Restaurant;

class MorphToOneTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        /**
         * @var Restaurant $restaurant
         * @var User $user
         * @var Tag $contact
         */
        $restaurant = Restaurant::create(['name' => 'ABC Inc']);
        $user = User::create(['email' => 'operator@example.com']);
        $contact = Tag::create(['name' => 'Taylor']);

        $restaurant->tags()->attach($contact, [
            'is_primary' => Tag::IS_PRIMARY,
        ]);
        $user->tags()->attach($contact, [
            'is_primary' => Tag::IS_PRIMARY,
        ]);

        $restaurant = Restaurant::create(['name' => 'XYZ Inc']);
    }

    public function testEagerLoading()
    {
        $restaurant = Restaurant::with('primaryTag')->first();

        $this->assertInstanceOf(Tag::class, $restaurant->primaryTag);
        $this->assertEquals(Tag::IS_PRIMARY, $restaurant->primaryTag->pivot->is_primary);
    }

    public function testLazyLoading()
    {
        $restaurant = Restaurant::find(1);

        $this->assertInstanceOf(Tag::class, $restaurant->primaryTag);
        $this->assertEquals(Tag::IS_PRIMARY, $restaurant->primaryTag->pivot->is_primary);
    }

    public function testWithDefault()
    {
        $restaurant = Restaurant::find(2);

        $this->assertInstanceOf(Tag::class, $restaurant->primaryTagWithDefault);
        $this->assertFalse($restaurant->primaryTagWithDefault->exists);
        $this->assertNull($restaurant->primaryTag);
    }
}
