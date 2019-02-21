<?php

namespace Tests\Models;

use Ankurk91\Eloquent\MorphToOne;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use MorphToOne;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'imageable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function restaurants()
    {
        return $this->morphedByMany(Restaurant::class, 'imageable');
    }

    /**
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function restaurant()
    {
        return $this->morphedByOne(Restaurant::class, 'imageable')
            ->wherePivot('is_featured', 1);
    }

    /**
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function user()
    {
        return $this->morphedByOne(User::class, 'imageable')
            ->wherePivot('is_featured', 1);
    }
}
