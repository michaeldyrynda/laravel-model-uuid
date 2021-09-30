<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\BindsOnUuid;

class UuidRouteBoundPost extends Model
{
	use BindsOnUuid;

	public function uuidColumn(): string
	{
		return 'uuid';
	}
}
