<?php

namespace Tests\Concerns;

use Illuminate\Database\Connection;
use Mockery as m;

trait MocksDatabaseConnection
{
    protected function mockConnection(?string $grammar = null)
    {
        $connection = m::mock(Connection::class)
            ->shouldReceive('getTablePrefix')->andReturn('')
            ->shouldReceive('getConfig')->with('prefix_indexes')->andReturn(true)
            ->getMock();

        $grammar ??= 'MySql';
        $grammarClass = "Illuminate\Database\Schema\Grammars\\{$grammar}Grammar";

        $connection->shouldReceive('getSchemaGrammar')->andReturn(new $grammarClass($connection));

        if ($grammar === 'SQLite') {
            $connection->shouldReceive('getServerVersion')->andReturn('3.35');
        }

        $this->beforeApplicationDestroyed(fn () => m::close());

        return $connection;
    }
}
