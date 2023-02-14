<?php
declare(strict_types=1);

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Traits\HasImages;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class)
            ->withPivot('is_operator')
            ->withTimestamps();
    }
}
