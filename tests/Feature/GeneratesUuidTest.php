<?php

namespace Tests\Feature;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GeneratesUuidTest extends TestCase
{
    #[Test]
    public function it_gets_default_column_name()
    {
        $testModelThatGeneratesUuid = new class
        {
            use GeneratesUuid;
        };

        $this->assertSame(
            $testModelThatGeneratesUuid->uuidColumn(),
            'uuid',
            'The UUID column should be "uuid" when no default is configured.'
        );

        Config::set('model-uuid.column_name', 'uuid_custom');
        $this->assertSame(
            $testModelThatGeneratesUuid->uuidColumn(),
            'uuid_custom',
            'The UUID column should match the configured value.'
        );
    }
}
