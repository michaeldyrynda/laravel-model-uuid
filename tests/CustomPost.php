<?php

namespace Tests;

use Dyrynda\Database\Support\GeneratesUuid;
use Tests\Model;

class CustomPost extends Model
{
    protected $uuidField = 'customField';
}
