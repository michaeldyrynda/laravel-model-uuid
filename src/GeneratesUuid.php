<?php

namespace Dyrynda\Database\Support;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * UUID generation trait.
 *
 * Include this trait in any Eloquent model where you wish to automatically set
 * a UUID field. When saving, if the UUID field has not been set, generate a
 * new UUID value, which will be set on the model and saved by Eloquent.
 *
 * @copyright 2017 Michael Dyrynda
 * @author    Michael Dyrynda <michael@dyrynda.com.au>
 * @license   MIT
 *
 * @property  string  $uuidVersion
 */
trait GeneratesUuid
{
    /**
     * The UUID versions.
     *
     * @var array
     */
    protected $uuidVersions = [
        'uuid1',
        'uuid3',
        'uuid4',
        'uuid5',
        'ordered',
    ];

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param  string  $key
     * @param  array|string|null  $types
     * @return bool
     */
    abstract public function hasCast($key, $types = null);

    /**
     * Boot the trait, adding a creating observer.
     *
     * When persisting a new model instance, we resolve the UUID field, then set
     * a fresh UUID, taking into account if we need to cast to binary or not.
     *
     * @return void
     */
    public static function bootGeneratesUuid()
    {
        static::creating(function ($model) {
            /* @var \Illuminate\Database\Eloquent\Model|static $model */
            $uuid = $model->resolveUuid();

            if (isset($model->attributes[$model->uuidColumn()]) && ! is_null($model->attributes[$model->uuidColumn()])) {
                /* @var \Ramsey\Uuid\Uuid $uuid */
                $uuid = $uuid->fromString(strtolower($model->attributes[$model->uuidColumn()]));
            }

            $model->attributes[$model->uuidColumn()] = $model->hasCast($model->uuidColumn(), 'uuid') ? $uuid->getBytes() : $uuid->toString();
        });
    }

    /**
     * The name of the column that should be used for the UUID.
     *
     * @return string
     */
    public function uuidColumn()
    {
        return 'uuid';
    }

    /**
     * Resolve a UUID instance for the configured version.
     *
     * @return \Ramsey\Uuid\Uuid
     */
    public function resolveUuid()
    {
        if (($version = $this->resolveUuidVersion()) == 'ordered') {
            return Str::orderedUuid();
        }

        return call_user_func([Uuid::class, $version]);
    }

    /**
     * Resolve the UUID version to use when setting the UUID value. Default to uuid4.
     *
     * @return string
     */
    public function resolveUuidVersion()
    {
        if (property_exists($this, 'uuidVersion') && in_array($this->uuidVersion, $this->uuidVersions)) {
            return $this->uuidVersion;
        }

        return 'uuid4';
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $uuid
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereUuid($query, $uuid)
    {
        if ($this->hasCast($this->uuidColumn())) {
            $uuid = $this->resolveUuid()->fromString($uuid)->getBytes();
        }

        return $query->where($this->uuidColumn(), $uuid);
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
	public function castAttribute($key, $value)
    {
	    if ( ! is_null($value)) {
		    if ($key === $this->uuidColumn() ) {
			    return $this->resolveUuid()->fromBytes($value)->toString();
		    } elseif (
			    $this->hasCast($key) &&
			    $this->getCastType($key) == 'uuid' &&
			    $this->resolveUuidVersion() == 'ordered'
		    ) {
			    return Uuid::fromBytes( $value )->toString();
		    }
	    }

        return parent::castAttribute($key, $value);
    }

	/**
	 * Set an attribute value
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public function setAttribute($key, $value) {
		if (
			! empty($value) &&
			$this->hasCast($key) &&
			$this->getCastType($key) == 'uuid' &&
			$this->resolveUuidVersion() == 'ordered'
		) {
			$value = Uuid::fromString( $value )->getBytes();
		}
		return parent::setAttribute($key, $value);
	}
}
