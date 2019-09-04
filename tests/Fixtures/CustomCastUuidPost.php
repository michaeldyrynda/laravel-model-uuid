<?php

namespace Tests\Fixtures;

class CustomCastUuidPost extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $casts = ['custom_uuid' => 'uuid'];

    public function uuidColumn(): string
    {
        return 'custom_uuid';
    }
}
