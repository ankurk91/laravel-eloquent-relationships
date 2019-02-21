<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Traits\HasTags;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class)
            ->as('meta')
            ->withPivot(['role'])
            ->withTimestamps();
    }
}
