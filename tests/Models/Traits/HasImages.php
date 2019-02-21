<?php

namespace Tests\Models\Traits;

use Tests\Models\Image;
use Ankurk91\Eloquent\MorphToOne;

trait HasImages
{
    use MorphToOne;

    /**
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function featuredImage()
    {
        return $this->morphToOne(Image::class, 'imageable')
            ->withPivot('is_featured')
            ->wherePivot('is_featured', 1);
    }

    /**
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function featuredImageWithDefault()
    {
        return $this->morphToOne(Image::class, 'imageable')
            ->withPivot('is_featured')
            ->wherePivot('is_featured', 1)
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot(['is_featured']);
    }

}
