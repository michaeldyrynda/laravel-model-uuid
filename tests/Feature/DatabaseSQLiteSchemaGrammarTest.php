<?php

namespace Tests\Feature;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Mockery as m;
use Tests\TestCase;

class DatabaseSQLiteSchemaGrammarTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testAddingUuid()
    {
        $blueprint = new Blueprint('users', function ($table) {
            $table->uuid('foo');
            $table->efficientUuid('bar');
        });

        $connection = m::mock(Connection::class);
        $connection->expects('getServerVersion')->once();

        $this->assertEquals(
            ['alter table "users" add column "foo" varchar not null', 'alter table "users" add column "bar" blob(256) not null'],
            $blueprint->toSql($connection, new SQLiteGrammar)
        );
    }
}
