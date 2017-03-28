<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class UuidResolversTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->generator = $this->getMockForTrait('Dyrynda\Database\Support\GeneratesUuid');
    }

    /** @test */
    public function it_handles_uuid_version_1()
    {
        $this->generator->uuidVersion = 'uuid1';

        $this->assertEquals('uuid1', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_handles_uuid_version_3()
    {
        $this->generator->uuidVersion = 'uuid3';

        $this->assertEquals('uuid3', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_handles_uuid_version_4()
    {
        $this->generator->uuidVersion = 'uuid4';

        $this->assertEquals('uuid4', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_handles_uuid_version_5()
    {
        $this->generator->uuidVersion = 'uuid5';

        $this->assertEquals('uuid5', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_defaults_to_uuid_version_4()
    {
        $this->generator->uuidVersion = 'uuid999';

        $this->assertEquals('uuid4', $this->generator->resolveUuidVersion());
    }

    /** @test */
    public function it_resolved_custom_uuid_field_name()
    {
        $this->generator->uuidField = 'testing';

        $this->assertEquals('testing', $this->generator->resolveUuidField());
    }
}
