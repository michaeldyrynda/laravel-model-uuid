<?php

namespace Tests\Fixtures;

class OrderedNonIdPost extends Model
{
    protected $uuidVersion = 'ordered';

	protected $casts = ['uuid' => 'uuid', 'custom_uuid' => 'uuid'];
}
