<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Tests\Concerns\MocksDatabaseConnection;
use Tests\TestCase;

class DatabaseMySqlSchemaGrammarTest extends TestCase
{
    use MocksDatabaseConnection;

    public function test_adding_uuid()
    {
        $blueprint = new Blueprint($this->mockConnection('MySql'), 'users', function ($table) {
            $table->uuid('foo');
            $table->efficientUuid('bar');
        });

        $this->assertEquals([
            'alter table `users` add `foo` char(36) not null',
            'alter table `users` add `bar` binary(16) not null',
        ], $blueprint->toSql());
    }
}
