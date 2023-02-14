<?php
declare(strict_types=1);

namespace Tests;

use Tests\Models\Image;
use Tests\Models\User;
use Tests\Models\Restaurant;

class MorphToOneTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /**
         * @var Restaurant $restaurant
         * @var User $user
         * @var Image $image
         */
        $restaurant = Restaurant::create(['name' => 'ABC Inc']);
        $user = User::create(['email' => 'user@example.com']);
        $image = Image::create(['name' => 'Image']);

        $restaurant->images()->attach($image, [
            'is_featured' => 1,
        ]);
        $user->images()->attach($image, [
            'is_featured' => 1,
        ]);

        $imageTwo = Image::create(['name' => 'Another']);
        $restaurant->images()->attach($imageTwo);

        $restaurantWithoutImage = Restaurant::create(['name' => 'XYZ Inc']);
    }

    public function testEagerLoading()
    {
        $restaurant = Restaurant::with('featuredImage')->first();

        $this->assertInstanceOf(Image::class, $restaurant->featuredImage);
        $this->assertEquals(1, $restaurant->featuredImage->pivot->is_featured);
    }

    public function testLazyLoading()
    {
        $restaurant = Restaurant::find(1);

        $this->assertInstanceOf(Image::class, $restaurant->featuredImage);
        $this->assertEquals(1, $restaurant->featuredImage->pivot->is_featured);
    }

    public function testWithDefault()
    {
        $restaurant = Restaurant::find(2);

        $this->assertInstanceOf(Image::class, $restaurant->featuredImageWithDefault);
        $this->assertFalse($restaurant->featuredImageWithDefault->exists);
        $this->assertNull($restaurant->featuredImage);
    }

    public function testReverseRelation()
    {
        $image = Image::first();

        $this->assertInstanceOf(Restaurant::class, $image->restaurant);
        $this->assertInstanceOf(User::class, $image->user);

        // image two is not a featured image
        $image = Image::find(2);
        $this->assertNull($image->restaurant);
    }
}
