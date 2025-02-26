<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Tests\Concerns\MocksDatabaseConnection;
use Tests\TestCase;

class DatabaseSQLiteSchemaGrammarTest extends TestCase
{
    use MocksDatabaseConnection;

    public function test_adding_uuid()
    {
        $blueprint = new Blueprint($this->mockConnection('SQLite'), 'users', function ($table) {
            $table->uuid('foo');
            $table->efficientUuid('bar');
        });

        $this->assertEquals(
            ['alter table "users" add column "foo" varchar not null', 'alter table "users" add column "bar" blob(256) not null'],
            $blueprint->toSql()
        );
    }
}
