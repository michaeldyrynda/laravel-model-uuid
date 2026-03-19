<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Support\UsesBinaryUuidBuilder;
use Illuminate\Database\Eloquent\Model as BaseModel;

class BinaryUuidProfile extends BaseModel
{
    use GeneratesUuid;
    use UsesBinaryUuidBuilder;

    protected $table = 'binary_uuid_profiles';

    protected $guarded = [];

    public $timestamps = false;

    public function uuidColumns(): array
    {
        return ['user_id'];
    }

    protected $casts = [
        'user_id' => EfficientUuid::class,
    ];

    public function user()
    {
        return $this->belongsTo(BinaryUuidUser::class, 'user_id', 'id');
    }
}
