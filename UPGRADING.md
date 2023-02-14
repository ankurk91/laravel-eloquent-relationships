# Upgrade guide

## From v1.x to 2.x

* Both Traits have been renamed, you can update your Model classes like:

```diff
- use Ankurk91\Eloquent\BelongsToOne;
+ use Ankurk91\Eloquent\HasBelongsToOne;
```

```diff
- use Ankurk91\Eloquent\MorphToOne;
+ use Ankurk91\Eloquent\HasMorphToOne;
```
