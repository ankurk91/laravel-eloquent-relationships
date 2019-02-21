<?php

namespace Tests\Models\Traits;

use Tests\Models\Tag;

trait HasTags
{
    use \Ankurk91\Eloquent\MorphToOne;

    /**
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function primaryTag()
    {
        return $this->morphToOne(Tag::class, 'taggable')
            ->withPivot(['is_primary'])
            ->wherePivot('is_primary', 1);
    }

    /**
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function primaryTagWithDefault()
    {
        return $this->morphToOne(Tag::class, 'taggable')
            ->withPivot(['is_primary'])
            ->wherePivot('is_primary', 1)
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->withPivot(['is_primary']);
    }

}
