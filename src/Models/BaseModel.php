<?php

namespace SedpMis\Lib\Models;


use Illuminate\Database\Eloquent\Model as EloquentModel;
use Abstractions\Transformer\BaseTransformer;
use Carbon\Carbon;

class BaseModel extends EloquentModel
{
    /**
     * Attributes to be hidden.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Attributes data typecasting or transformation.
     *
     * @var array
     */
    protected $typecasts = [];

    /**
     * Trashed attributes when clean() method is called.
     *
     * @var array
     */
    protected $trashedAttributes = [];

    /**
     * Instantiate or make new instance via static method.
     *
     * @param array $attributes Model attributes
     * @return $instance
     */
    public static function make(array $attributes = array())
    {
        return (new ReflectionClass(get_called_class()))->newInstanceArgs(func_get_args());
    }

    /**
     * Returns primary key of the model.
     *
     * @return int|mixed Primary key
     */
    public function key()
    {
        return $this->getKey();
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @override
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $key = snake_case($key);

        if ($this->isInTypecasts($key)) {
            $this->setAttribute($key, ($this->makeTransformer([$key => $value])->getReversed()[$key]));
        } else {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @override
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        // Lets you access attributes via camel-case,
        // so when snake_case version the attributes exists it automatically convert to snake_case
        if (array_key_exists(snake_case($key), $this->attributes)) {
            $key = snake_case($key);
        }

        $value = $this->getAttribute($key);

        // Typecast attribute when exist in $typecasts property
        if ($this->isInTypecasts($key)) {
            return $this->getTypecasted($key, $value);
        }

        return $value;
    }

    /**
     * Check if an attribute is included in transformation map.
     *
     * @param string $attribute Attribute to be checked
     * @return bool
     */
    public function isInTypecasts($attribute)
    {
        return array_key_exists($attribute, $this->typecasts);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        if (!empty($attributes) && !empty($this->typecasts)) {
            $attributes = $this->makeTransformer($attributes)->getReversed();
        }

        parent::fill($attributes);

        return $this;
    }

    /**
     * Returns a data transformer.
     *
     * @param  array  $attributes Array attribute data to be transformed to its type
     * @return Abstractions\Transformer\BaseTransformer
     */
    protected function makeTransformer(array $attributes)
    {
        return (new BaseTransformer)->set($attributes)->setTransformMap($this->makeTransformMap());
    }

    /**
     * Make a string transform map format of typecasts property.
     *
     * @return string
     */
    protected function makeTransformMap()
    {
        $string = '';

        foreach ($this->typecasts as $attribute => $attributeTransformMap) {
            $string .= "{$attribute},{$attributeTransformMap};";
        }

        return remove_whitespaces($string);
    }

    /**
     * Overrides toArray method  of Eloquent to apply transformation or typecasting of attributes.
     *
     * @param array $attributes Selected model attributes to be returned. Optional
     * @return array
     */
    public function toArray(array $attributes = null, array $except = null)
    {
        if (method_exists($this, 'removeAppends')) {
            $this->removeAppends();
        }

        $array = parent::toArray();

        if (!empty($this->typecasts)) {
            $array = $this->makeTransformer($array)->getTransformed();
        }

        if (!empty($attributes)) {
            $array = array_extract($array, $attributes);
        }

        if (!empty($except)) {
            foreach ($except as $exceptAttribute) {
                unset($array[$exceptAttribute]);
            }
        }

        return $array;
    }

    /**
     * Get the typecasted attribute of a certain attribute with the given value.
     *
     * @param  string $attribute Model attribute
     * @param  mixed $value     Model value
     * @return mixed            Typecasted value
     */
    public function getTypecasted($attribute, $value)
    {
        return $this->makeTransformer([$attribute => $value])->getTransformed()[$attribute];
    }

    /**
     * Set typecasts property.
     *
     * @param  array $typecasts
     * @param  bool  $overwrite = false
     * @return $this
     */
    public function setTypecasts(array $typecasts, $overwrite = false)
    {
        if ($overwrite) {
            $this->typecasts = $typecasts;
        } else {
            $this->typecasts = array_merge($this->typecasts, $typecasts);
        }

        return $this;
    }

    /**
     * Override Eloquent::getAttributes(), enabling to pass a selected attributes.
     *
     * @param  array $attributes Array of attributes to be returned
     * @return array
     */
    public function getAttributes(array $attributes = null)
    {
        if ($attributes === null) {
            return parent::getAttributes();
        }

        $modelAttributes = [];

        foreach ($attributes as $attribute) {
            if ($this->$attribute !== null) {
                $modelAttributes[$attribute] = $this->$attribute;
            }
        }

        return $modelAttributes;
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Services\IlluminateExtensions\Collection
     */
    public function newCollection(array $models = array())
    {
        return new \Services\IlluminateExtensions\Collection($models);
    }

    /**
     * Returns the writable attributes of the model.
     *
     * @return array
     */
    public function writable()
    {
        return array_diff($this->fillable, [$this->getKeyName()]);
    }

    /**
     * Makes a copy of the model.
     *
     * @param  bool $fullCopy If true, this will copy also the relations
     * @return static
     */
    public function copy($fullCopy = false)
    {
        $copy = clone $this;

        if (!$fullCopy) {
            $copy->setRelations([]);
        }

        return $copy;
    }

    /**
     * Return random model.
     *
     * @return static
     */
    public static function randomOne()
    {
        $ids   = static::lists('id');
        $index = array_rand($ids);

        return static::find($ids[$index]);
    }

    /**
     * Return the full classpath of the model.
     *
     * @return string
     */
    public static function getClass()
    {
        return static::class;
    }

    /**
     * Get the carbon instance of a datetime attribute.
     *
     * @param  string $attribute
     * @return \Carbon\Carbon
     */
    public function carbon($attribute)
    {
        return new Carbon($this->getAttribute($attribute));
    }

    /**
     * Return the carbon instance of a datetime attribute if not null, else return null.
     *
     * @param  string $attribute
     * @return null|\Carbon\Carbon
     */
    public function carbonOrNull($attribute)
    {
        return is_null($this->getAttribute($attribute)) ? null : $this->carbon($attribute);
    }

    /**
     * Add attributes to appends.
     *
     * @param  array $appends
     * @return $this
     */
    public function addAppends(array $appends)
    {
        $this->appends = array_merge($this->appends, $appends);

        return $this;
    }

    /**
     * Return the attribute from $attributes property, not being mutated or transformed.
     *
     * @param  string $attribute
     * @return mixed
     */
    public function rawAttribute($attribute)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }
    }

    /**
     * Clean attributes, useful before saving. Remove attributes which are not in $fillable.
     *
     * @return $this
     */
    public function clean()
    {
        $fillable            = $this->getFillable();
        $attributesWithValue = array_keys($this->attributes);
        $unFillable          = array_diff($attributesWithValue, $fillable);

        $this->trashedAttributes = array_only($this->attributes, $unFillable);
        $this->attributes        = array_only($this->attributes, $fillable);

        return $this;
    }

    /**
     * Return the trashedAttributes.
     *
     * @return array
     */
    public function getTrashedAttributes()
    {
        return $this->trashedAttributes;
    }

    /**
     * Remove a relation from the model.
     *
     * @param  string $relation
     * @return $this
     */
    public function removeRelation($relation)
    {
        unset($this->relations[$relation]);

        return $this;
    }

    /**
     * Identify if relation exist in the model.
     *
     * @param  string $relation
     * @return bool
     */
    public function relationExists($relation)
    {
        return array_key_exists($relation, $this->relations) ? true : false;
    }

    /**
     * Override. Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Support\Collection|static
     *
     * @throws \ModelNotFoundException
     */
    public static function findOrFail($id, $columns = array('*'))
    {
        if (!is_null($model = static::find($id, $columns))) {
            return $model;
        }

        throw (new ModelNotFoundException)->setModel(static::class, $id);
    }

    /**
     * Alias of save.
     *
     * @return bool
     */
    public function saveModel()
    {
        return $this->save();
    }

    /**
     * Override. Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new EloquentBuilder($query);
    }
}
