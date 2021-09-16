<?php

namespace Dyrynda\Database\Support;

use Illuminate\Database\Eloquent\Model;

trait BindsOnUuid
{
	/**
	 * Route bind desired uuid field
	 * Default 'uuid' column name has been set
	 *
	 * @param string $value
	 * @param null|string $field
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function resolveRouteBinding($value, $field = null): Model
	{
		return self::whereUuid($value, $field)->firstOrFail();
	}
}