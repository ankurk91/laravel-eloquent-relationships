<?php
declare(strict_types=1);

namespace Ankurk91\Eloquent;

use Ankurk91\Eloquent\Relations\MorphToOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasMorphToOne
{
    /**
     * Define a one-to-one via pivot relationship.
     */
    public function morphToOne(
        string $related,
        string $name,
        string $table = null,
        string $foreignPivotKey = null,
        string $relatedPivotKey = null,
        string $parentKey = null,
        string $relatedKey = null,
        bool $inverse = false
    ): MorphToOne {
        $caller = $this->guessBelongsToManyRelation();

        // First, we'll need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we'll make the query
        // instances as well as the relationship instances we need for this.
        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $name.'_id';

        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        // If no table name was provided, we can guess it by concatenating the two
        // models using underscores in alphabetical order. The two model names
        // are transformed to snake case from their default CamelCase also.
        $table = $table ?: Str::plural($name);

        return $this->newMorphToOne(
            $instance->newQuery(), $this, $name, $table,
            $foreignPivotKey, $relatedPivotKey, $parentKey ?: $this->getKeyName(),
            $relatedKey ?: $instance->getKeyName(), $caller, $inverse
        );
    }

    /**
     * Instantiate a new MorphToOne relationship.
     */
    protected function newMorphToOne(
        Builder $query,
        Model $parent,
        string $name,
        string $table,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $parentKey,
        string $relatedKey,
        string $relationName = null,
        bool $inverse = false
    ): MorphToOne {
        return new MorphToOne($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey,
            $relatedKey,
            $relationName, $inverse);
    }

    /**
     * Define a polymorphic, inverse many-to-many relationship but one.
     */
    public function morphedByOne(
        string $related,
        string $name,
        string $table = null,
        string $foreignPivotKey = null,
        string $relatedPivotKey = null,
        string $parentKey = null,
        string $relatedKey = null
    ): MorphToOne {
        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();

        // For the inverse of the polymorphic many-to-many relations, we will change
        // the way we determine the foreign and other keys, as it is the opposite
        // of the morph-to-many method since we're figuring out these inverses.
        $relatedPivotKey = $relatedPivotKey ?: $name.'_id';

        return $this->morphToOne(
            $related, $name, $table, $foreignPivotKey,
            $relatedPivotKey, $parentKey, $relatedKey, true
        );
    }
}
