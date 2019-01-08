<?php

namespace Ankurk91\Eloquent;

use Ankurk91\Eloquent\Relations\MorphToOne as MorphToOneRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait MorphToOne
{

    /**
     * Define a one-to-one via pivot relationship.
     *
     * @param  string $related
     * @param  string $name
     * @param  string $table
     * @param  string $foreignPivotKey
     * @param  string $relatedPivotKey
     * @param  string $parentKey
     * @param  string $relatedKey
     * @param  bool $inverse
     *
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function morphToOne($related, $name, $table = null, $foreignPivotKey = null,
                               $relatedPivotKey = null, $parentKey = null,
                               $relatedKey = null, $inverse = false)
    {
        $caller = $this->guessBelongsToManyRelation();

        // First, we'll need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we'll make the query
        // instances as well as the relationship instances we need for this.
        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $name . '_id';

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
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $name
     * @param  string $table
     * @param  string $foreignPivotKey
     * @param  string $relatedPivotKey
     * @param  string $parentKey
     * @param  string $relatedKey
     * @param  string $relationName
     * @param  bool $inverse
     *
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    protected function newMorphToOne(Builder $query, Model $parent, $name, $table, $foreignPivotKey,
                                     $relatedPivotKey, $parentKey, $relatedKey,
                                     $relationName = null, $inverse = false)
    {
        return new MorphToOneRelation($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse);
    }

    /**
     * Define a polymorphic, inverse many-to-many relationship but one.
     *
     * @param  string $related
     * @param  string $name
     * @param  string $table
     * @param  string $foreignPivotKey
     * @param  string $relatedPivotKey
     * @param  string $parentKey
     * @param  string $relatedKey
     *
     * @return \Ankurk91\Eloquent\Relations\MorphToOne
     */
    public function morphedByOne($related, $name, $table = null, $foreignPivotKey = null,
                                 $relatedPivotKey = null, $parentKey = null, $relatedKey = null)
    {
        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();

        // For the inverse of the polymorphic many-to-many relations, we will change
        // the way we determine the foreign and other keys, as it is the opposite
        // of the morph-to-many method since we're figuring out these inverses.
        $relatedPivotKey = $relatedPivotKey ?: $name . '_id';

        return $this->morphToOne(
            $related, $name, $table, $foreignPivotKey,
            $relatedPivotKey, $parentKey, $relatedKey, true
        );
    }
}
