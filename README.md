# Missing Eloquent Relationships For Laravel

[![Packagist](https://badgen.net/packagist/v/ankurk91/laravel-eloquent-relationships)](https://packagist.org/packages/ankurk91/laravel-eloquent-relationships)
[![GitHub tag](https://badgen.net/github/tag/ankurk91/laravel-eloquent-relationships)](https://github.com/ankurk91/laravel-eloquent-relationships/tags)
[![License](https://badgen.net/packagist/license/ankurk91/laravel-eloquent-relationships)](LICENSE.txt)
[![Downloads](https://badgen.net/packagist/dt/ankurk91/laravel-eloquent-relationships)](https://packagist.org/packages/ankurk91/laravel-eloquent-relationships/stats)
[![tests](https://github.com/ankurk91/laravel-eloquent-relationships/workflows/tests/badge.svg)](https://github.com/ankurk91/laravel-eloquent-relationships/actions)
[![codecov](https://codecov.io/gh/ankurk91/laravel-eloquent-relationships/branch/main/graph/badge.svg)](https://codecov.io/gh/ankurk91/laravel-eloquent-relationships)

This package adds some missing relationships to Eloquent in Laravel

## Installation

You can install the package via composer:

```bash
composer require ankurk91/laravel-eloquent-relationships
```

## Usage

### BelongsToOne

BelongsToOne relation is almost identical to
standard [BelongsToMany](https://laravel.com/docs/9.x/eloquent-relationships#many-to-many) except it returns one model
instead of Collection of models and `null` if there is no related model in DB (BelongsToMany returns empty Collection in
this case). Example:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ankurk91\Eloquent\HasBelongsToOne;
use Ankurk91\Eloquent\Relations\BelongsToOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restaurant extends Model
{
    use HasBelongsToOne;
    
    /**
     * Each restaurant has only one operator.
     */
    public function operator(): BelongsToOne
    {
        return $this->belongsToOne(User::class)          
            ->wherePivot('is_operator', true);
            //->withDefault();
    }

    /**
     * Get all employees including the operator.
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_operator');
    }   
}    
```

Now you can access the relationship like:

```php
<?php

// eager loading
$restaurant = Restaurant::with('operator')->first();
dump($restaurant->operator);
// lazy loading
$restaurant->load('operator');
// load nested relation
$restaurant->load('operator.profile');
// Perform operations
$restaurant->operator()->update([
  'name'=> 'Taylor'
]);
```

### MorphToOne

MorphToOne relation is almost identical to
standard [MorphToMany](https://laravel.com/docs/9.x/eloquent-relationships#many-to-many-polymorphic-relations) except it
returns one model instead of Collection of models and `null` if there is no related model in DB (MorphToMany returns
empty Collection in this case). Example:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Image extends Model
{ 
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'imageable');
    }

    public function videos(): MorphToMany
    {
        return $this->morphedByMany(Video::class, 'imageable');
    }
}
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ankurk91\Eloquent\HasMorphToOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Ankurk91\Eloquent\Relations\MorphToOne;

class Post extends Model
{
    use HasMorphToOne;

    /**
     * Each post may have one featured image.
     */
    public function featuredImage(): MorphToOne
    {
        return $this->morphToOne(Image::class, 'imageable')
            ->wherePivot('featured', 1);
            //->withDefault();
    }
    
    /**
     * Get all images including the featured.
     */
    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot('featured');
    }

}

```

Now you can access the relationship like:

```php
<?php

// eager loading
$post = Post::with('featuredImage')->first();
dump($post->featuredImage);
// lazy loading
$post->load('featuredImage');
```

## Testing

```bash
composer test
```

## Security

If you discover any security issues, please email `pro.ankurk1[at]gmail[dot]com` instead of using the issue tracker.

## Attribution

* Most of the code is taken from this [PR](https://github.com/laravel/framework/pull/25083)
* Similar package [fidum/laravel-eloquent-morph-to-one](https://github.com/fidum/laravel-eloquent-morph-to-one)

## License

The [MIT](https://opensource.org/licenses/MIT) License.
