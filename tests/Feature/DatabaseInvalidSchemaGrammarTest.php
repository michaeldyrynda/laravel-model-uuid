<?php

namespace Tests\Feature;

use Dyrynda\Database\Support\Exceptions\UnknownGrammarClass;
use Illuminate\Database\Schema\Blueprint;
use Tests\Concerns\MocksDatabaseConnection;
use Tests\TestCase;

class DatabaseInvalidSchemaGrammarTest extends TestCase
{
    use MocksDatabaseConnection;

    public function test_adding_uuid()
    {
        $connection = $this->mockConnection('SqlServer');

        $blueprint = new Blueprint($connection, 'users', function ($table) {
            $table->uuid('foo');
            $table->efficientUuid('bar');
        });

        $this->expectException(UnknownGrammarClass::class);

        $blueprint->toSql();
    }
}
