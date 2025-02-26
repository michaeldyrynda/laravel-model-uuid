<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\MocksDatabaseConnection;
use Tests\TestCase;

class DatabasePostgresSchemaGrammarTest extends TestCase
{
    use MocksDatabaseConnection;

    #[Test]
    public function test_adding_uuid()
    {
        $blueprint = new Blueprint($this->mockConnection('Postgres'), 'users', function ($table) {
            $table->uuid('foo');
            $table->efficientUuid('bar');
        });

        $this->assertEquals([
            'alter table "users" add column "foo" uuid not null',
            'alter table "users" add column "bar" bytea not null',
        ], $blueprint->toSql());
    }
}
