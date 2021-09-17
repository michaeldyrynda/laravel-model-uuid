<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\BindsOnUuid;

class MultipleUuidRouteBoundPost extends Model
{
	use BindsOnUuid;

	public function uuidColumns(): array
	{
		return [
			'uuid', 'custom_uuid'
		];
	}
}
