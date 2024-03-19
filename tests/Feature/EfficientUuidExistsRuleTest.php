<?php

namespace Tests\Feature;

use Dyrynda\Database\Support\Rules\EfficientUuidExists;
use Ramsey\Uuid\Uuid;
use Tests\Fixtures\EfficientUuidPost;
use Tests\TestCase;

class EfficientUuidExistsRuleTest extends TestCase
{
    /** @test */
    public function it_passes_valid_existing_uuid()
    {
        /** @var \Tests\Fixtures\EfficientUuidPost $post */
        $post = factory(EfficientUuidPost::class)->create();

        $rule = new EfficientUuidExists(EfficientUuidPost::class);

        $this->assertTrue($rule->passes('uuid', $post->uuid));
    }

    /** @test */
    public function it_fails_on_non_existing_uuid()
    {
        $uuid = Uuid::uuid4();

        $rule = new EfficientUuidExists(EfficientUuidPost::class);

        $this->assertFalse($rule->passes('post_id', $uuid));
    }

    /** @test */
    public function it_fails_on_any_non_uuid_invalid_strings()
    {
        $uuid = '1235123564354633';

        $rule = new EfficientUuidExists(EfficientUuidPost::class, 'uuid');

        $this->assertFalse($rule->passes('post_id', $uuid));
    }

    /** @test */
    public function it_works_with_custom_uuid_column_name()
    {
        /** @var \Tests\Fixtures\EfficientUuidPost $post */
        $post = factory(EfficientUuidPost::class)->create();

        $rule = new EfficientUuidExists(EfficientUuidPost::class, 'custom_uuid');

        $this->assertTrue($rule->passes('custom_uuid', $post->custom_uuid));
    }
}
