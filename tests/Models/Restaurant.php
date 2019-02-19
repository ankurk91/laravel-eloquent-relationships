<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use \Ankurk91\Eloquent\BelongsToOne;

    const ROLE_MEMBER = 0;
    const ROLE_OPERATOR = 1;

    protected $guarded = ['id'];

    /**
     * @return \Ankurk91\Eloquent\Relations\BelongsToOne
     */
    public function operator()
    {
        return $this->belongsToOne(User::class)
            ->as('meta')
            ->withPivot(['role'])
            ->withTimestamps()
            ->wherePivot('role', Restaurant::ROLE_OPERATOR);
    }

    /**
     * @return \Ankurk91\Eloquent\Relations\BelongsToOne
     */
    public function operatorWithDefault()
    {
        return $this->belongsToOne(User::class)
            ->as('meta')
            ->withPivot(['role'])
            ->withTimestamps()
            ->wherePivot('role', Restaurant::ROLE_OPERATOR)
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(User::class)
            ->as('meta')
            ->withPivot(['role'])
            ->withTimestamps();
    }
}
