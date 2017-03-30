<?php

namespace Tests;

use Dyrynda\Database\Support\GeneratesUuid;
use Tests\Model;

class CustomUncastPost extends Model
{
    protected $uuidField = 'customField';
}
