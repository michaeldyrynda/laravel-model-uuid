<?php

namespace Tests\Feature;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Mockery as m;
use Tests\TestCase;

class DatabasePostgresSchemaGrammarTest extends TestCase
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

        $this->assertEquals(
            [
                'alter table "users" add column "foo" uuid not null',
                'alter table "users" add column "bar" bytea not null',
            ],
            $blueprint->toSql($connection, new PostgresGrammar)
        );
    }
}
