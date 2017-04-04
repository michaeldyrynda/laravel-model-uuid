<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class UuidResolversTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dyrynda\Database\Support\GeneratesUuid
     */
    protected $generator;

    public function setUp()
    {
        $this->generator = $this->getMockForTrait('Dyrynda\Database\Support\GeneratesUuid');
    }

    /** @test */
    public function it_handles_uuid_version_1()
    {
        $this->generator->uuidVersion = 'uuid1';

        $this->assertSame('uuid1', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_handles_uuid_version_3()
    {
        $this->generator->uuidVersion = 'uuid3';

        $this->assertSame('uuid3', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_handles_uuid_version_4()
    {
        $this->generator->uuidVersion = 'uuid4';

        $this->assertSame('uuid4', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_handles_uuid_version_5()
    {
        $this->generator->uuidVersion = 'uuid5';

        $this->assertSame('uuid5', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_defaults_to_uuid_version_4()
    {
        $this->generator->uuidVersion = 'uuid999';

        $this->assertSame('uuid4', $this->generator->resolveUuidVersion());
    }
}
