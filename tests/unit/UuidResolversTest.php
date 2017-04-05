<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use Dyrynda\Database\Support\GeneratesUuid;

class UuidResolversTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dyrynda\Database\Support\GeneratesUuid
     */
    protected $generator;

    public function setUp()
    {
        $this->generator = $this->getMockForTrait(GeneratesUuid::class);
    }

    /**
     * @see \Tests\UuidResolversTest::it_handles_uuid_versions
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
     * @dataProvider \Tests\UuidResolversTest::provider_for_it_handles_uuid_versions
     */
    public function it_handles_uuid_versions($version, $resolved)
    {
        $this->generator->uuidVersion = $version;

        $this->assertSame($resolved, $this->generator->resolveUuidVersion());
    }
}
