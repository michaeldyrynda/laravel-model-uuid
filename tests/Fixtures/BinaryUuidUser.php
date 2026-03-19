<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Support\UsesBinaryUuidBuilder;
use Illuminate\Database\Eloquent\Model as BaseModel;

class BinaryUuidUser extends BaseModel
{
    use GeneratesUuid;
    use UsesBinaryUuidBuilder;

    protected $table = 'binary_uuid_users';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    public function uuidColumn(): string
    {
        return 'id';
    }

    protected $casts = [
        'id' => EfficientUuid::class,
    ];

    public function posts()
    {
        return $this->hasMany(BinaryUuidPost::class, 'user_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(BinaryUuidProfile::class, 'user_id', 'id');
    }
}
