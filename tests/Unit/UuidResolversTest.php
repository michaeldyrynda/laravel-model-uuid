<?php

namespace Tests\Unit;

use PHPUnit_Framework_TestCase;
use Dyrynda\Database\Support\GeneratesUuid;

class UuidResolversTest extends PHPUnit_Framework_TestCase
{
    /**
     * @see \Tests\Unit\UuidResolversTest::it_handles_uuid_versions
     * @return array
     */
    public function provider_for_it_handles_uuid_versions()
    {
        return [
            ['uuid1', 'uuid1'],
            ['uuid3', 'uuid3'],
            ['uuid4', 'uuid4'],
            ['uuid5', 'uuid5'],
            ['uuid999', 'uuid4'],
        ];
    }

    /**
     * @test
     * @param string $version
     * @param string $resolved
     *
     * @dataProvider \Tests\Unit\UuidResolversTest::provider_for_it_handles_uuid_versions
     */
    public function it_handles_uuid_versions($version, $resolved)
    {
        /* @var \Dyrynda\Database\Support\GeneratesUuid $generator */
        $generator = $this->getMockForTrait(GeneratesUuid::class);
        $generator->uuidVersion = $version;

        $this->assertSame($resolved, $generator->resolveUuidVersion());
    }
}
